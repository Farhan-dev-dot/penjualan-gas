<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\BarangTransaksi;
use App\Models\KartuStok;
use App\Models\Pembelian;
use App\Models\PembelianDetail;
use App\Models\ProdukModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class TransaksiController extends Controller
{
    public function showBarangMasuk(Request $request)
    {
        $barangMasuks = BarangTransaksi::with(['produk'])
            ->whereIn('jenis_transaksi', [
                'masuk',
                'retur',
                'pengembalian'
            ])
            ->when($request->search, function ($q) use ($request) {
                $q->where(function ($query) use ($request) {
                    $query->whereHas('produk', function ($qq) use ($request) {
                        $qq->where(
                            'jenis_gas',
                            'like',
                            "%{$request->search}%"
                        );
                    })
                        ->orWhere(
                            'keterangan',
                            'like',
                            "%{$request->search}%"
                        );
                });
            })
            ->latest('tanggal_transaksi')
            ->paginate(10)
            ->withQueryString();

        $produks = ProdukModel::orderBy('jenis_gas')->get();
        $pembelianDetails = PembelianDetail::with('pembelian')
            ->whereHas('pembelian', function ($query) {
                $query->whereIn('payment_status', ["dikirim"]);
            })
            ->latest('id_detail')
            ->get();

        return view(
            'admin.barangmasuk',
            compact('barangMasuks', 'produks', 'pembelianDetails')
        );
    }

    public function storeBarangMasuk(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'tanggal_transaksi' => ['required', 'date'],

            'items' => ['required', 'array', 'min:1'],

            'items.*.id_produk' => [
                'required',
                'exists:produk,id_produk'
            ],

            'items.*.jenis_transaksi' => [
                'required',
                'in:masuk,retur,pengembalian'
            ],

            'items.*.stok_isi' => [
                'required',
                'integer',
                'min:0'
            ],

            'items.*.stok_kosong' => [
                'required',
                'integer',
                'min:0'
            ],

            'items.*.stok_pinjam' => [
                'required',
                'integer',
                'min:0'
            ],

            /*
            |--------------------------------------------------------------------------
            | Pembelian asal wajib dipilih untuk retur / pengembalian.
            |--------------------------------------------------------------------------
            */

            'items.*.id_penjualan' => [
                'nullable',
                'required_if:items.*.jenis_transaksi,retur,pengembalian',
                'exists:penjualan,id_penjualan',
            ],

            'items.*.keterangan' => [
                'nullable',
                'string'
            ],
        ], [
            'items.*.id_penjualan.required_if' =>
            'Pembelian asal wajib dipilih untuk transaksi retur atau pengembalian.',
        ]);

        /*
        |--------------------------------------------------------------------------
        | VALIDASI KECOCOKAN PRODUK DENGAN PEMBELIAN ASAL
        |--------------------------------------------------------------------------
        | Untuk retur & pengembalian, produk yang dipilih WAJIB ada di pembelian
        | asal. Jika tidak cocok, request ditolak (422) dan transaksi TIDAK
        | diupdate dan TIDAK dimasukkan ke database.
        */

        $validator->after(function ($validator) use ($request) {

            foreach ($request->input('items', []) as $index => $item) {

                $jenis = $item['jenis_transaksi'] ?? null;
                $idPenjualan = $item['id_penjualan'] ?? null;
                $idProduk = $item['id_produk'] ?? null;

                /*
                |--------------------------------------------------------------------------
                | Stok tidak boleh kosong semua (isi, kosong, dan pinjam = 0).
                |--------------------------------------------------------------------------
                | Berlaku untuk semua jenis transaksi. Bila ketiganya 0, tidak ada
                | pergerakan stok yang bisa dicatat, sehingga transaksi ditolak.
                */

                $stokIsiItem = (int) ($item['stok_isi'] ?? 0);
                $stokKosongItem = (int) ($item['stok_kosong'] ?? 0);
                $stokPinjamItem = (int) ($item['stok_pinjam'] ?? 0);

                if ($stokIsiItem === 0 && $stokKosongItem === 0 && $stokPinjamItem === 0) {
                    $validator->errors()->add(
                        "items.{$index}.stok_isi",
                        "Baris " . ($index + 1) . ": Jumlah stok tidak boleh 0 semuanya. " .
                            "Isi minimal salah satu dari Isi, Kosong, atau Pinjam. " .
                            "Transaksi dibatalkan dan tidak disimpan."
                    );

                    continue;
                }

                /*
                |--------------------------------------------------------------------------
                | Transaksi 'masuk' manual terikat pembelian asal.
                |--------------------------------------------------------------------------
                | Boleh diinput tanpa id_penjualan maupun dengan pembelian asal.
                | Pengecekan jenis gas hanya berlaku untuk retur / pengembalian.
                */
                if (!in_array($jenis, ['retur', 'pengembalian', "masuk"], true)) {
                    continue;
                }

                // Pembelian asal kosong sudah divalidasi required_if di atas.
                if (empty($idPenjualan) || empty($idProduk)) {
                    continue;
                }

                /*
                |--------------------------------------------------------------------------
                | Jenis gas harus sama dengan jenis gas pada pembelian asal.
                |--------------------------------------------------------------------------
                | Contoh: pembelian asal Argon, maka barang masuk wajib Argon.
                | Bila barang masuk Nitrogen, transaksi ditolak.
                */

                $produkDipilih = ProdukModel::find($idProduk);
                $jenisGasDipilih = $produkDipilih?->jenis_gas;

                // Kumpulkan jenis gas yang benar-benar ada pada pembelian asal.
                $jenisGasPembelian = PembelianDetail::with('produk')
                    ->where('id_penjualan', $idPenjualan)
                    ->get()
                    ->pluck('produk.jenis_gas')
                    ->filter()
                    ->unique()
                    ->values();

                // Produk cocok bila jenis gasnya ada pada pembelian asal.
                $produkAda = $jenisGasDipilih !== null
                    && $jenisGasPembelian->contains($jenisGasDipilih);

                if ($produkAda) {
                    continue;
                }

                $jenisGasDipilihLabel = $jenisGasDipilih ?? 'Tidak diketahui';
                $jenisGasPembelianLabel = $jenisGasPembelian->implode(', ') ?: 'Tidak diketahui';

                $validator->errors()->add(
                    "items.{$index}.id_produk",
                    "Baris " . ($index + 1) . ": Barang tidak sesuai dengan pembelian asal. " .
                        "Jenis gas yang dimasukkan '{$jenisGasDipilihLabel}', " .
                        "sedangkan jenis gas pada pembelian asal adalah '{$jenisGasPembelianLabel}'. " .
                        "Transaksi dibatalkan dan tidak disimpan."
                );
            }
        });

        if ($validator->fails()) {
            return response()->json([
                'message' => $validator->errors()->first(),
                'errors' => $validator->errors()->toArray(),
            ], 422);
        }

        $namaPetugas = auth('admin')->user()?->name;

        /*
    |--------------------------------------------------------------------------
    | ID PENJUALAN YANG AKAN DIUBAH MENJADI BERHASIL
    |--------------------------------------------------------------------------
    */

        $idPenjualanSelesai = [];

        DB::transaction(function () use (
            $request,
            $namaPetugas,
            &$idPenjualanSelesai
        ) {

            foreach ($request->items as $item) {

                $produk = ProdukModel::where(
                    'id_produk',
                    $item['id_produk']
                )
                    ->lockForUpdate()
                    ->firstOrFail();

                /*
            |--------------------------------------------------------------------------
            | STOK SEBELUM
            |--------------------------------------------------------------------------
            */

                $stokIsiSebelum = $produk->stok_isi;
                $stokKosongSebelum = $produk->stok_kosong;
                $stokPinjamSebelum = $produk->stok_pinjam;

                $stokIsi = (int) $item['stok_isi'];
                $stokKosong = (int) $item['stok_kosong'];
                $stokPinjam = (int) $item['stok_pinjam'];

                /*
            |--------------------------------------------------------------------------
            | PROSES STOK
            |--------------------------------------------------------------------------
            */

                if ($item['jenis_transaksi'] === 'pengembalian') {

                    /*
                |--------------------------------------------------------------------------
                | PENGEMBALIAN
                |
                | Tabung pinjaman kembali ke gudang.
                |
                | stok_pinjam  -
                | stok_kosong  +
                |--------------------------------------------------------------------------
                */

                    $jumlahPengembalian = $stokKosong;

                    // Jangan sampai stok pinjam minus
                    if ($jumlahPengembalian > $produk->stok_pinjam) {
                        throw new \Exception(
                            "Jumlah pengembalian {$jumlahPengembalian} " .
                                "melebihi stok pinjam {$produk->stok_pinjam}."
                        );
                    }

                    $produk->stok_pinjam -= $jumlahPengembalian;
                    $produk->stok_kosong += $jumlahPengembalian;
                } else {

                    /*
                |--------------------------------------------------------------------------
                | MASUK / RETUR
                |
                | Stok ditambahkan sesuai input.
                |--------------------------------------------------------------------------
                */

                    $produk->stok_isi += $stokIsi;
                    $produk->stok_kosong += $stokKosong;
                    $produk->stok_pinjam += $stokPinjam;
                }

                $produk->save();

                /*
            |--------------------------------------------------------------------------
            | BARANG TRANSAKSI
            |--------------------------------------------------------------------------
            */

                /*
                |--------------------------------------------------------------------------
                | Kolom id_penjualan bertipe NOT NULL di database.
                |--------------------------------------------------------------------------
                | Barang masuk manual ('masuk' tanpa pembelian asal) tidak punya
                | id_penjualan, sehingga disimpan sebagai 0 agar tidak melanggar
                | constraint NOT NULL dan tidak menunjuk pembelian mana pun.
                */

                $idPenjualanItem = (int) ($item['id_penjualan'] ?? 0);

                if ($idPenjualanItem <= 0) {
                    $idPenjualanItem = 0;
                }

                $barangTransaksi = BarangTransaksi::create([
                    'id_penjualan' => $idPenjualanItem,
                    'id_produk' => $item['id_produk'],
                    'jenis_transaksi' => $item['jenis_transaksi'],

                    'stok_isi' => $stokIsi,
                    'stok_kosong' => $stokKosong,
                    'stok_pinjam' => $stokPinjam,

                    'keterangan' => $item['keterangan'] ?? null,

                    'tanggal_transaksi' => $request->tanggal_transaksi,

                    'nama_petugas' => $namaPetugas,
                ]);

                /*
            |--------------------------------------------------------------------------
            | KARTU STOK
            |--------------------------------------------------------------------------
            */

                KartuStok::create([
                    'id_transaksi' => $barangTransaksi->id_transaksi,

                    'stok_isi_sebelum' => $stokIsiSebelum,
                    'stok_kosong_sebelum' => $stokKosongSebelum,
                    'stok_pinjam_sebelum' => $stokPinjamSebelum,

                    'stok_isi_sesudah' => $produk->stok_isi,
                    'stok_kosong_sesudah' => $produk->stok_kosong,
                    'stok_pinjam_sesudah' => $produk->stok_pinjam,

                    'tanggal_transaksi' => $request->tanggal_transaksi,
                ]);

                /*
            |--------------------------------------------------------------------------
            | SIMPAN ID PENJUALAN
            |
            | Hanya retur / pengembalian yang memiliki pembelian asal.
            |--------------------------------------------------------------------------
            */

                if (
                    in_array(
                        $item['jenis_transaksi'],
                        ['retur', 'pengembalian', "masuk"],
                        true
                    )
                    && !empty($item['id_penjualan'])
                ) {

                    $idPenjualanSelesai[$item['id_penjualan']]
                        = $item['id_penjualan'];
                }
            }

            /*
        |--------------------------------------------------------------------------
        | UBAH STATUS PEMBELIAN
        |--------------------------------------------------------------------------
        */

            if (!empty($idPenjualanSelesai)) {

                Pembelian::whereIn(
                    'id_penjualan',
                    array_values($idPenjualanSelesai)
                )
                    ->where('payment_status', '!=', 'berhasil')
                    ->update([
                        'payment_status' => 'berhasil'
                    ]);
            }
        });

        return response()->json([
            'message' => 'Data barang masuk berhasil disimpan.',
        ]);
    }

    public function showBarangKeluar(Request $request)
    {
        $barangKeluars = Pembelian::query()
            ->with([
                'details',
                'barangTransaksis' => fn($query) => $query
                    ->where('jenis_transaksi', 'keluar')
                    ->with('produk')
                    ->latest('tanggal_transaksi'),
            ])
            ->whereHas('barangTransaksis', fn($query) => $query->where('jenis_transaksi', 'keluar'))
            ->when($request->search, function ($query, $search) {
                $query->where(function ($query) use ($search) {
                    $query->where('kode_penjualan', 'like', "%{$search}%")
                        ->orWhereHas('details', fn($detail) => $detail
                            ->where('nama_penerima', 'like', "%{$search}%"))
                        ->orWhereHas('barangTransaksis', fn($transaksi) => $transaksi
                            ->where('jenis_transaksi', 'keluar')
                            ->where(function ($transaksi) use ($search) {
                                $transaksi->where('keterangan', 'like', "%{$search}%")
                                    ->orWhereHas('produk', fn($produk) => $produk
                                        ->where('jenis_gas', 'like', "%{$search}%"));
                            }));
                });
            })
            ->latest('created_at')
            ->paginate(10)
            ->withQueryString();

        $pembelianSudahKeluar = BarangTransaksi::where('jenis_transaksi', 'keluar')
            ->where('id_penjualan', '>', 0)
            ->pluck('id_penjualan');


        $pembelianDetails = PembelianDetail::with(['pembelian', 'produk'])
            ->whereNotIn('id_penjualan', $pembelianSudahKeluar)
            ->whereHas('pembelian', function ($query) {
                $query->where('payment_status', 'menunggu_konfirmasi');
            })
            ->latest('id_detail')
            ->get();


        return view('admin.barangkeluar', compact('barangKeluars', 'pembelianDetails'));
    }
    public function storeBarangKeluar(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'tanggal_transaksi' => ['required', 'date'],
            'items' => ['required', 'array', 'min:1'],
            'items.*.id_detail' => ['required', 'exists:penjualan_detail,id_detail'],
            'items.*.stok_isi' => ['required', 'integer', 'min:0'],
            'items.*.stok_kosong' => ['required', 'integer', 'min:0'],
            'items.*.stok_pinjam' => ['required', 'integer', 'min:0'],
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => $validator->errors()->first(),
            ], 422);
        }

        $namaPetugas = auth('admin')->user()?->name;

        // Semua id_penjualan yang ikut dalam sekali input barang keluar ini.
        $idPenjualanDikirim = [];

        DB::transaction(function () use ($request, $namaPetugas, &$idPenjualanDikirim) {
            foreach ($request->items as $item) {
                $detail = PembelianDetail::with('pembelian')
                    ->where('id_detail', $item['id_detail'])
                    ->lockForUpdate()
                    ->firstOrFail();

                $produk = ProdukModel::where('id_produk', $detail->id_produk)
                    ->lockForUpdate()
                    ->firstOrFail();

                $stokIsiSebelum = $produk->stok_isi;
                $stokKosongSebelum = $produk->stok_kosong;
                $stokPinjamSebelum = $produk->stok_pinjam;

                $stokIsi = (int) $item['stok_isi'];
                $stokKosong = (int) $item['stok_kosong'];
                $stokPinjam = (int) $item['stok_pinjam'];

                if ($detail->tipe_transaksi === 'pinjam') {
                    // Tabung isi dipinjamkan ke pelanggan
                    $produk->stok_isi = max(0, $produk->stok_isi - $stokIsi);
                    $produk->stok_pinjam += $stokPinjam;
                } else {
                    // isi_ulang / refil: tabung isi keluar,
                    // tabung kosong milik pelanggan masuk ke gudang
                    $produk->stok_isi = max(0, $produk->stok_isi - $stokIsi);
                    $produk->stok_kosong += $stokKosong;
                }

                $produk->save();

                $barangTransaksi = BarangTransaksi::create([
                    'id_penjualan' => $detail->id_penjualan,
                    'id_produk' => $detail->id_produk,
                    'jenis_transaksi' => 'keluar',
                    'stok_isi' => $stokIsi,
                    'stok_kosong' => $stokKosong,
                    'stok_pinjam' => $stokPinjam,
                    'keterangan' => $item['keterangan'] ?? null,
                    'tanggal_transaksi' => $request->tanggal_transaksi,
                    'nama_petugas' => $namaPetugas,
                ]);

                KartuStok::create([
                    'id_transaksi' => $barangTransaksi->id_transaksi,
                    'stok_isi_sebelum' => $stokIsiSebelum,
                    'stok_kosong_sebelum' => $stokKosongSebelum,
                    'stok_pinjam_sebelum' => $stokPinjamSebelum,
                    'stok_isi_sesudah' => $produk->stok_isi,
                    'stok_kosong_sesudah' => $produk->stok_kosong,
                    'stok_pinjam_sesudah' => $produk->stok_pinjam,
                    'tanggal_transaksi' => $request->tanggal_transaksi,
                ]);


                $idPenjualanDikirim[$detail->id_penjualan] = $detail->id_penjualan;
            }

            if (!empty($idPenjualanDikirim)) {
                Pembelian::whereIn('id_penjualan', array_values($idPenjualanDikirim))
                    ->where('payment_status', '!=', 'dikirim')
                    ->update(['payment_status' => 'dikirim']);
            }
        });

        return redirect()
            ->route('admin.barang-keluar')
            ->with('success', 'Data barang keluar berhasil disimpan dan status pembelian berubah menjadi dikirim.');
    }

    public function printSuratJalan(Pembelian $pembelian)
    {
        $pembelian->load([
            'details',
            'barangTransaksis' => fn($query) => $query
                ->where('jenis_transaksi', 'keluar')
                ->with('produk')
                ->latest('tanggal_transaksi'),
        ]);

        if ($pembelian->barangTransaksis->isEmpty()) {
            abort(404);
        }

        return view('components.print_suratjalan', [
            'pembelian' => $pembelian,
            'transaksis' => $pembelian->barangTransaksis,
            'detail' => $pembelian->details->first(),
        ]);
    }

    public function destroyBarangKeluar(Pembelian $pembelian)
    {
        DB::transaction(function () use ($pembelian) {
            $pembelian = Pembelian::whereKey($pembelian->getKey())
                ->lockForUpdate()
                ->firstOrFail();

            $transaksis = BarangTransaksi::where('id_penjualan', $pembelian->id_penjualan)
                ->where('jenis_transaksi', 'keluar')
                ->lockForUpdate()
                ->get();

            if ($transaksis->isEmpty()) {
                abort(404);
            }

            foreach ($transaksis as $transaksi) {
                $produk = ProdukModel::where('id_produk', $transaksi->id_produk)
                    ->lockForUpdate()
                    ->firstOrFail();
                $detail = PembelianDetail::where('id_penjualan', $transaksi->id_penjualan)
                    ->where('id_produk', $transaksi->id_produk)
                    ->first();

                $produk->stok_isi += $transaksi->stok_isi;

                if ($detail?->tipe_transaksi === 'pinjam') {
                    $produk->stok_pinjam = max(0, $produk->stok_pinjam - $transaksi->stok_pinjam);
                } else {
                    $produk->stok_kosong = max(0, $produk->stok_kosong - $transaksi->stok_kosong);
                }

                $produk->save();
                KartuStok::where('id_transaksi', $transaksi->id_transaksi)->delete();
                $transaksi->delete();
            }

            /*
            |--------------------------------------------------------------------------
            | Pengiriman dibatalkan: status kembali menjadi 'menunggu_konfirmasi'
            |--------------------------------------------------------------------------
            | Supaya pembelian ini kembali masuk antrean Barang Keluar dan bisa
            | diinput ulang oleh admin.
            */
            if ($pembelian->payment_status === 'dikirim') {
                $pembelian->update(['payment_status' => 'menunggu_konfirmasi']);
            }
        });

        return redirect()
            ->route('admin.barang-keluar')
            ->with('success', 'Seluruh transaksi barang keluar berhasil dihapus, stok dikembalikan, dan status pembelian kembali menjadi menunggu konfirmasi.');
    }

    public function destroyBarangMasuk(BarangTransaksi $barangTransaksi)
    {
        if (!in_array($barangTransaksi->jenis_transaksi, ['masuk', 'retur', 'pengembalian'], true)) {
            abort(404);
        }

        DB::transaction(function () use ($barangTransaksi) {
            $transaksi = BarangTransaksi::whereKey($barangTransaksi->getKey())
                ->lockForUpdate()
                ->firstOrFail();
            $produk = ProdukModel::where('id_produk', $transaksi->id_produk)
                ->lockForUpdate()
                ->firstOrFail();

            if ($transaksi->jenis_transaksi === 'pengembalian') {
                if ($transaksi->stok_kosong > $produk->stok_kosong) {
                    abort(422, 'Stok kosong tidak mencukupi untuk membatalkan transaksi.');
                }

                $produk->stok_kosong -= $transaksi->stok_kosong;
                $produk->stok_pinjam += $transaksi->stok_kosong;
            } else {
                if (
                    $transaksi->stok_isi > $produk->stok_isi ||
                    $transaksi->stok_kosong > $produk->stok_kosong ||
                    $transaksi->stok_pinjam > $produk->stok_pinjam
                ) {
                    abort(422, 'Stok tidak mencukupi untuk membatalkan transaksi.');
                }

                $produk->stok_isi -= $transaksi->stok_isi;
                $produk->stok_kosong -= $transaksi->stok_kosong;
                $produk->stok_pinjam -= $transaksi->stok_pinjam;
            }

            $produk->save();
            KartuStok::where('id_transaksi', $transaksi->id_transaksi)->delete();

            $idPenjualan = $transaksi->id_penjualan;

            $transaksi->delete();

            /*
            |--------------------------------------------------------------------------
            | KEMBALIKAN STATUS PEMBELIAN MENJADI 'dikirim'
            |--------------------------------------------------------------------------
            | Saat barang masuk (retur / pengembalian / masuk) dibuat dengan pembelian
            | asal, statusnya diubah menjadi 'berhasil'. Ketika barang masuk tersebut
            | dihapus, status pembelian dikembalikan menjadi 'dikirim'.
            |
            | Tidak dilakukan bila masih ada transaksi barang masuk lain yang
            | menempel pada pembelian asal yang sama.
            */

            if (!empty($idPenjualan)) {

                $masihAdaTransaksi = BarangTransaksi::where('id_penjualan', $idPenjualan)
                    ->whereIn('jenis_transaksi', ['masuk', 'retur', 'pengembalian'])
                    ->exists();

                if (!$masihAdaTransaksi) {
                    Pembelian::where('id_penjualan', $idPenjualan)
                        ->where('payment_status', 'berhasil')
                        ->update([
                            'payment_status' => 'dikirim'
                        ]);
                }
            }
        });

        return redirect()
            ->route('admin.barang-masuk')
            ->with('success', 'Data barang masuk berhasil dihapus, stok dikembalikan, dan status pembelian kembali menjadi dikirim.');
    }

    public function showBarangRusak()
    {
        $produks = ProdukModel::query()
            ->when(request('search'), function ($query, $search) {
                $query->where('jenis_gas', 'like', "%{$search}%")
                    ->orWhere('kode_produk', 'like', "%{$search}%");
            })
            ->orderBy('jenis_gas')
            ->paginate(10)
            ->withQueryString();

        return view('admin.barangrusak', compact('produks'));
    }

    public function storeBarangRusak(Request $request)
    {
        $validated = $request->validate([
            'id_produk' => ['required', 'exists:produk,id_produk'],
            'kondisi' => ['required', 'in:stok_isi,stok_kosong,stok_pinjam'],
            'jumlah' => ['required', 'integer', 'min:1'],
        ]);

        DB::transaction(function () use ($validated) {
            $produk = ProdukModel::where('id_produk', $validated['id_produk'])
                ->lockForUpdate()
                ->firstOrFail();

            $jumlah = (int) $validated['jumlah'];
            $stokTersedia = (int) $produk->{$validated['kondisi']};

            if ($stokTersedia < $jumlah) {
                abort(422, 'Jumlah melebihi stok pada kondisi yang dipilih.');
            }

            $produk->decrement($validated['kondisi'], $jumlah);
            $produk->increment('stok_rusak', $jumlah);
        });

        return redirect()->route('admin.barang-rusak')
            ->with('success', 'Stok barang rusak berhasil ditambahkan.');
    }

    public function destroyBarangRusak(Request $request)
    {
        $validated = $request->validate([
            'id_produk' => ['required', 'exists:produk,id_produk'],
            'jumlah' => ['required', 'integer', 'min:1'],
        ]);

        DB::transaction(function () use ($validated) {
            $produk = ProdukModel::where('id_produk', $validated['id_produk'])
                ->lockForUpdate()
                ->firstOrFail();

            $jumlah = (int) $validated['jumlah'];

            if ($produk->stok_rusak < $jumlah) {
                abort(422, 'Jumlah barang rusak yang dihapus melebihi stok tersedia.');
            }

            $produk->decrement('stok_rusak', $jumlah);
            $produk->increment('stok_kosong', $jumlah);
        });

        return redirect()->route('admin.barang-rusak')
            ->with('success', 'Barang rusak dihapus dan dikembalikan ke stok kosong.');
    }
}
