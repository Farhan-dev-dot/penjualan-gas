<?php

namespace App\Http\Controllers\customer;

use App\Http\Controllers\Controller;
use App\Models\Pembelian;
use App\Models\PembelianDetail;
use App\Models\ProdukModel;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Midtrans\Config;
use Midtrans\Snap;
use Midtrans\Transaction;

class PembelianController extends Controller
{
    public function __construct()
    {
        Config::$serverKey    = config('midtrans.server_key');
        Config::$isProduction = config('midtrans.is_production');
        Config::$isSanitized  = true;
        Config::$is3ds        = true;
    }

    public function showChekout()
    {
        if (!filled(auth()->user()?->foto_ktp)) {
            return redirect()->route('user.profile')->with(
                'error',
                'Unggah foto KTP terlebih dahulu sebelum melakukan pemesanan.'
            );
        }

        $detailSebelumnya = PembelianDetail::whereHas('pembelian', function ($query) {
            $query->where('id_user', auth()->id());
        })
            ->latest('id_detail')
            ->first();

        return view('components.formchekout', compact('detailSebelumnya'));
    }

    public function showPesanan(Request $request)
    {
        $status = $request->query('status');

        $query = Pembelian::with([
            'details.produk'
        ])
            ->where('id_user', auth()->id())
            ->latest();

        if ($status === 'pending') {

            // Belum lunas
            $query->whereIn('payment_status', [
                'pending',
                'process'
            ]);
        } elseif ($status === 'settlement') {

            // Sudah lunas
            $query->whereIn('payment_status', [
                'settlement',
                'menunggu_konfirmasi',
            ]);
        } elseif ($status) {

            $query->where('payment_status', $status);
        }

        $pesanan = $query->paginate(10)->withQueryString();

        return view('customer.pesanan.index', compact(
            'pesanan',
            'status'
        ));
    }


    public function showPesananDetail($id_penjualan)
    {
        $pembelian = Pembelian::with(['details.produk'])
            ->where('id_penjualan', $id_penjualan)
            ->where('id_user', auth()->id())
            ->firstOrFail();

        $pembelian->details
            ->where('tipe_transaksi', 'pinjam')
            ->each(function (PembelianDetail $sewaDetail) use ($pembelian) {
                $refillDetails = PembelianDetail::where('id_produk', $sewaDetail->id_produk)
                    ->where('tipe_transaksi', 'refil')
                    ->whereHas('pembelian', function ($query) use ($pembelian, $sewaDetail) {
                        $query->where('id_user', $pembelian->id_user)
                            ->whereIn('payment_status', ['menunggu_konfirmasi', 'settlement'])
                            ->whereDate('created_at', '>=', $sewaDetail->mulai_sewa)
                            ->whereDate('created_at', '<=', $sewaDetail->akhir_sewa);
                    });

                $sewaDetail->jumlah_refill = $refillDetails->count();
                $sewaDetail->jumlah_tabung_refill = (int) $refillDetails->sum('jumlah');
            });

        return view('customer.pesanan.detail_pesanan', compact('pembelian'));
    }

    public function showRefill($id_penjualan)
    {
        $pembelian = Pembelian::with(['details' => function ($query) {
            $query->with(['pembelian', 'produk'])
                ->where('tipe_transaksi', 'pinjam')
                ->whereNotNull('akhir_sewa')
                ->whereDate('akhir_sewa', '>=', today());
        }])
            ->where('id_penjualan', $id_penjualan)
            ->where('id_user', auth()->id())
            ->whereIn('payment_status', ['menunggu_konfirmasi', 'settlement'])
            ->firstOrFail();

        $sewaDetails = $pembelian->details
            ->map(function (PembelianDetail $sewaDetail) {
                $jumlahDisewa = (int) $sewaDetail->jumlah;
                $sewaDetail->maksimal_refill = $jumlahDisewa;

                return $sewaDetail;
            });

        return view('customer.pesanan.refill', compact('sewaDetails'));
    }

    public function paymentToken($id_penjualan)
    {
        $pembelian = Pembelian::with(['details.produk'])
            ->where('id_penjualan', $id_penjualan)
            ->where('id_user', auth()->id())
            ->whereIn('payment_status', ['pending', 'process'])
            ->firstOrFail();

        // Pastikan status lokal tidak tertinggal sebelum Snap dibuka kembali.
        // Contohnya, Midtrans sudah menyatakan transaksi expire tetapi webhook
        // belum sempat masuk ke aplikasi.
        try {
            $response = Transaction::status($pembelian->kode_penjualan);
            $data = json_decode(json_encode($response), true);

            if (($data['order_id'] ?? null) === $pembelian->kode_penjualan) {
                $pembelian = $this->applyMidtransStatus($pembelian, $data);
            }
        } catch (\Throwable $th) {
            Log::warning('Gagal memeriksa status Midtrans sebelum membuka pembayaran', [
                'kode_penjualan' => $pembelian->kode_penjualan,
                'message' => $th->getMessage(),
            ]);
        }

        if (!in_array($pembelian->payment_status, ['pending', 'process'], true)) {
            return response()->json([
                'message' => $pembelian->payment_status === 'expire'
                    ? 'Transaksi sudah kedaluwarsa.'
                    : 'Transaksi ini tidak dapat dibayar lagi.',
                'payment_status' => $pembelian->payment_status,
            ], 409);
        }

        if (!$pembelian->snap_token) {
            $details = $pembelian->details->map(function ($detail) {
                return [
                    'id' => (string) $detail->id_produk,
                    'price' => (int) ($detail->subtotal / max(1, $detail->jumlah)),
                    'quantity' => (int) $detail->jumlah,
                    'name' => $detail->produk?->jenis_gas ?? 'Produk',
                ];
            })->values()->all();

            $firstDetail = $pembelian->details->first();

            $snapToken = Snap::getSnapToken([
                'transaction_details' => [
                    'order_id' => $pembelian->kode_penjualan,
                    'gross_amount' => $pembelian->gross_amount,
                ],

                'item_details' => $details,

                'customer_details' => [
                    'first_name' => $firstDetail?->nama_penerima
                        ?? $pembelian->user?->name
                        ?? 'Customer',

                    'email' => $firstDetail?->email_penerima
                        ?? $pembelian->user?->email,

                    'phone' => $firstDetail?->telepon_penerima,
                ],
            ]);

            $pembelian->update([
                'snap_token' => $snapToken
            ]);
        }

        return response()->json([
            'snap_token' => $pembelian->snap_token
        ]);
    }

    /**
     * Sinkronkan status transaksi dari Midtrans untuk transaksi milik pelanggan.
     * Ini menjadi fallback apabila webhook tidak dapat menjangkau server lokal.
     */
    public function syncPaymentStatus($id_penjualan)
    {
        $pembelian = Pembelian::where('id_penjualan', $id_penjualan)
            ->where('id_user', auth()->id())
            ->firstOrFail();

        try {
            $response = Transaction::status($pembelian->kode_penjualan);
            $data = json_decode(json_encode($response), true);

            if (($data['order_id'] ?? null) !== $pembelian->kode_penjualan) {
                throw new \RuntimeException('Order ID Midtrans tidak cocok.');
            }

            $pembelian = $this->applyMidtransStatus($pembelian, $data);

            return response()->json([
                'success' => true,
                'payment_status' => $pembelian->payment_status,
            ]);
        } catch (\Throwable $th) {
            Log::warning('Gagal menyinkronkan status Midtrans', [
                'kode_penjualan' => $pembelian->kode_penjualan,
                'message' => $th->getMessage(),
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Status pembayaran belum dapat disinkronkan.',
            ], 502);
        }
    }

    public function createTransaction(Request $request)
    {
        try {
            $user = $request->user();

            if (!filled($user?->foto_ktp)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Unggah foto KTP terlebih dahulu sebelum melakukan pemesanan.',
                ], 403);
            }

            $request->validate([
                'nama_penerima'     => 'required|string|max:150',
                'telepon_penerima'  => 'required|string|max:20',
                'email_penerima'    => 'nullable|email|max:150',
                'kota'              => 'nullable|string|max:100',
                'kecamatan'         => 'nullable|string|max:100',
                'kelurahan'          => 'nullable|string|max:100',
                'alamat_penerima'   => 'required|string',
                'catatan'           => 'nullable|string',
                'cart_items'        => 'required|string',
                'jenis_sewa'        => 'nullable|in:harian,bulanan',
                'mulai_sewa'        => 'nullable|date',
            ]);

            $cartItems = json_decode($request->input('cart_items'), true);

            if (empty($cartItems)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Keranjang masih kosong.',
                ], 422);
            }

            $grossAmount = 0;
            $itemDetails = [];
            $jumlahTabung = 0;
            $hasRefil = collect($cartItems)->contains(function ($item) {
                return ($item['tipe_transaksi'] ?? null) === 'refil';
            });

            $jenisSewa = $hasRefil
                ? null
                : ($request->input('jenis_sewa') ?: null);
            foreach ($cartItems as $item) {
                $price    = (int) ($item['price'] ?? 0);
                $quantity = (int) ($item['quantity'] ?? 1);

                if (($item['tipe_transaksi'] ?? null) === 'refil') {
                    $sewaDetail = PembelianDetail::where('id_produk', $item['id_produk'] ?? $item['id'] ?? 0)
                        ->where('tipe_transaksi', 'pinjam')
                        ->whereDate('akhir_sewa', '>=', today())
                        ->whereHas('pembelian', function ($query) use ($user) {
                            $query->where('id_user', $user->id)
                                ->whereIn('payment_status', ['menunggu_konfirmasi', 'settlement']);
                        })
                        ->first();

                    if (!$sewaDetail) {
                        return response()->json([
                            'success' => false,
                            'message' => 'Sewa aktif untuk produk refill tidak ditemukan.',
                        ], 422);
                    }
                }

                $jumlahTabung += $quantity;

                $grossAmount += ($price * $quantity);

                $itemDetails[] = [
                    'id'       => (string) ($item['id_produk'] ?? $item['id'] ?? 'produk'),
                    'price'    => $price,
                    'quantity' => $quantity,
                    'name'     => (string) ($item['name'] ?? $item['variant'] ?? 'Produk'),
                ];

                if (!empty($item['is_pinjam'])) {
                    $pinjamAmount = 2000 * $quantity;
                    $grossAmount += $pinjamAmount;
                    $itemDetails[] = [
                        'id'       => 'pinjam-' . ($item['id_produk'] ?? $item['id'] ?? 'produk'),
                        'price'    => 2000,
                        'quantity' => $quantity,
                        'name'     => 'Biaya Pinjam Tabung',
                    ];
                }
            }

            $durasiSewa = $jenisSewa ? (int) $request->input('durasi', 1) : null;
            $totalHariSewa = $jenisSewa
                ? ($jenisSewa === 'bulanan' ? $durasiSewa * 30 : $durasiSewa)
                : null;
            $mulaiSewa = $jenisSewa
                ? Carbon::parse($request->input('mulai_sewa', now()->toDateString()))
                : null;
            $akhirSewa = $jenisSewa
                ? $mulaiSewa->copy()->addDays($totalHariSewa - 1)
                : null;

            if ($jenisSewa) {
                $biayaSewa = $jumlahTabung * $totalHariSewa * 10000;

                $grossAmount += $biayaSewa;
                $itemDetails[] = [
                    'id'       => 'sewa-tabung',
                    'price'    => $biayaSewa,
                    'quantity' => 1,
                    'name'     => 'Sewa Tabung (' . $jumlahTabung . ' tabung, ' . $durasiSewa . ' ' .
                        ($jenisSewa === 'bulanan' ? 'bulan' : 'hari') . ')',
                ];
            }

            if ($grossAmount <= 0) {
                return response()->json([
                    'success' => false,
                    'message' => 'Total transaksi tidak valid.',
                ], 422);
            }

            $kodePenjualan = 'INV-' . time() . '-' . $user->id;

            $pembelian = DB::transaction(function () use (
                $request,
                $cartItems,
                $grossAmount,
                $kodePenjualan,
                $jenisSewa,
                $durasiSewa,
                $totalHariSewa,
                $mulaiSewa,
                $akhirSewa
            ) {

                $pembelian = Pembelian::create([
                    'kode_penjualan'   => $kodePenjualan,
                    'id_user'          => Auth::id(),
                    'gross_amount'     => $grossAmount,
                    'payment_type'     => null,
                    'payment_status'   => 'pending',
                ]);

                foreach ($cartItems as $item) {
                    $harga  = (int) ($item['price'] ?? 0);
                    $jumlah = (int) ($item['quantity'] ?? 1);
                    $isRefil = ($item['tipe_transaksi'] ?? null) === 'refil';
                    $isPinjam = !$isRefil && (!empty($item['is_pinjam']) || $jenisSewa);
                    $tipeTransaksi = $isPinjam
                        ? 'pinjam'
                        : (in_array($item['tipe_transaksi'] ?? null, ['isi_ulang', 'refil'], true)
                            ? $item['tipe_transaksi']
                            : 'isi_ulang');

                    PembelianDetail::create([
                        'id_penjualan'     => $pembelian->id_penjualan,
                        'id_produk'        => $item['id_produk'] ?? $item['id'] ?? null,
                        'nama_penerima'    => $request->nama_penerima,
                        'telepon_penerima' => $request->telepon_penerima,
                        'email_penerima'   => $request->email_penerima,
                        'provinsi'         => $request->provinsi,
                        'kota'             => $request->kota,
                        'kecamatan'        => $request->kecamatan,
                        'kelurahan'         => $request->kelurahan,
                        'alamat_penerima'  => $request->alamat_penerima,
                        'catatan'          => $request->input('catatan'),
                        'jumlah'           => $jumlah,
                        'subtotal'         => ($harga * $jumlah),
                        'tipe_transaksi'   => $tipeTransaksi,
                        'durasi'           => $isPinjam ? ($totalHariSewa ?? null) : null,
                        'mulai_sewa'       => $isPinjam && $mulaiSewa
                            ? $mulaiSewa->toDateString()
                            : null,
                        'akhir_sewa'       => $isPinjam && $akhirSewa
                            ? $akhirSewa->toDateString()
                            : null,
                    ]);
                }

                return $pembelian;
            });

            $transactionDetails = [
                'transaction_details' => [
                    'order_id'     => $pembelian->kode_penjualan,
                    'gross_amount' => $pembelian->gross_amount,
                ],
                'item_details' => $itemDetails,
                'customer_details' => [
                    'first_name' => $request->nama_penerima,
                    'email'      => $request->email_penerima,
                    'phone'      => $request->telepon_penerima,
                    'shipping_address' => [
                        'first_name'   => $request->nama_penerima,
                        'phone'        => $request->telepon_penerima,
                        'address'      => $request->alamat_penerima,
                        'city'         => $request->kota,
                        'ward'         => $request->kelurahan,
                        'country_code' => 'IDN',
                    ],
                ],
            ];

            $snapToken = Snap::getSnapToken($transactionDetails);

            $pembelian->update(['snap_token' => $snapToken]);

            return response()->json([
                'success'     => true,
                'snap_token'  => $snapToken,
                'order_id'    => $pembelian->kode_penjualan,
            ]);
        } catch (\Throwable $th) {
            Log::error('Gagal membuat transaksi pembelian', [
                'message' => $th->getMessage(),
                'trace' => $th->getTraceAsString(),
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Gagal membuat transaksi.',
                'error'   => $th->getMessage(),
            ], 500);
        }
    }

    public function handleNotification(Request $request)
    {
        try {
            $data = $request->all();
            $orderId = $data['order_id'] ?? null;
            $signatureKey = $data['signature_key'] ?? null;
            $signaturePayload = ($orderId ?? '') . ($data['status_code'] ?? '') .
                ($data['gross_amount'] ?? '') . Config::$serverKey;

            if (!$orderId || !$signatureKey || !hash_equals(hash('sha512', $signaturePayload), $signatureKey)) {
                return response()->json(['success' => false, 'message' => 'Signature Midtrans tidak valid.'], 403);
            }

            $pembelian = Pembelian::where('kode_penjualan', $orderId)->firstOrFail();
            $pembelian = $this->applyMidtransStatus($pembelian, $data);

            return response()->json([
                'success' => true,
                'message' => 'Notifikasi berhasil diproses.',
                'kode_penjualan' => $orderId,
                'payment_type' => $data['payment_type'] ?? null,
                'transaction_status' => $data['transaction_status'] ?? null,
                'payment_status' => $pembelian->payment_status,
            ]);
        } catch (\Throwable $th) {
            Log::error('Midtrans Notification Error', [
                'message' => $th->getMessage(),
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Gagal memproses notifikasi.',
            ], 500);
        }
    }

    /** Terapkan status Midtrans secara atomik dan idempoten. */
    private function applyMidtransStatus(Pembelian $pembelian, array $data): Pembelian
    {
        return DB::transaction(function () use ($pembelian, $data) {
            $pembelian = Pembelian::with('details')
                ->whereKey($pembelian->getKey())
                ->lockForUpdate()
                ->firstOrFail();

            $transactionStatus = $data['transaction_status'] ?? null;
            $paid = $transactionStatus === 'settlement' ||
                ($transactionStatus === 'capture' && ($data['fraud_status'] ?? 'accept') === 'accept');
            $wasPaid = in_array($pembelian->payment_status, ['menunggu_konfirmasi', 'settlement'], true);
            $wasTerminal = in_array($pembelian->payment_status, ['expire', 'cancel', 'deny', 'failure'], true);

            // Status sukses dan terminal tidak boleh ditimpa notifikasi lama yang datang terlambat.
            if (($wasPaid && !$paid) || $wasTerminal) {
                return $pembelian;
            }

            if ($paid) {
                $pembelian->payment_status = $pembelian->payment_status === 'settlement'
                    ? 'settlement'
                    : 'menunggu_konfirmasi';
            } elseif (in_array($transactionStatus, ['pending', 'expire', 'cancel', 'deny', 'failure'], true)) {
                $pembelian->payment_status = $transactionStatus;
            }

            $pembelian->payment_type = $data['payment_type'] ?? $pembelian->payment_type;
            $pembelian->midtrans_response = $data;

            $pembelian->save();

            return $pembelian;
        });
    }
}
