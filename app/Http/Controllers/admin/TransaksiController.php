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
                $query->whereIn('payment_status', ['menunggu_konfirmasi', 'settlement']);
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
            'items.*.id_produk' => ['required', 'exists:produk,id_produk'],
            'items.*.jenis_transaksi' => ['required', 'in:masuk,retur,pengembalian'],
            'items.*.stok_isi' => ['required', 'integer', 'min:0'],
            'items.*.stok_kosong' => ['required', 'integer', 'min:0'],
            'items.*.stok_pinjam' => ['required', 'integer', 'min:0'],
            'items.*.id_penjualan' => ['nullable', 'exists:penjualan,id_penjualan'],
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => $validator->errors()->first(),
            ], 422);
        }

        foreach ($request->items as $item) {
            if (in_array($item['jenis_transaksi'], ['retur', 'pengembalian'], true)) {
                if (empty($item['id_penjualan'])) {
                    return response()->json([
                        'message' => 'Pembelian asal wajib dipilih untuk transaksi retur atau pengembalian.',
                    ], 422);
                }

                $produkAdaDiPembelian = PembelianDetail::where('id_penjualan', $item['id_penjualan'])
                    ->where('id_produk', $item['id_produk'])
                    ->exists();

                if (!$produkAdaDiPembelian) {
                    return response()->json([
                        'message' => 'Produk retur atau pengembalian tidak terdapat pada pembelian asal.',
                    ], 422);
                }
            }
        }

        $namaPetugas = auth('admin')->user()?->name;

        DB::transaction(function () use ($request, $namaPetugas) {
            foreach ($request->items as $item) {
                $produk = ProdukModel::where('id_produk', $item['id_produk'])
                    ->lockForUpdate()
                    ->firstOrFail();

                $stokIsiSebelum = $produk->stok_isi;
                $stokKosongSebelum = $produk->stok_kosong;
                $stokPinjamSebelum = $produk->stok_pinjam;

                $stokIsi = (int) $item['stok_isi'];
                $stokKosong = (int) $item['stok_kosong'];
                $stokPinjam = (int) $item['stok_pinjam'];

                if ($item['jenis_transaksi'] === 'pengembalian') {
                    $jumlahPengembalian = $stokKosong;
                    $produk->stok_pinjam = max(0, $produk->stok_pinjam - $jumlahPengembalian);
                    $produk->stok_kosong += $jumlahPengembalian;
                } else {
                    $produk->stok_isi += $stokIsi;
                    $produk->stok_kosong += $stokKosong;
                    $produk->stok_pinjam += $stokPinjam;
                }

                $produk->save();

                $barangTransaksi = BarangTransaksi::create([
                    'id_penjualan' => $item['id_penjualan'] ?? null,
                    'id_produk' => $item['id_produk'],
                    'jenis_transaksi' => $item['jenis_transaksi'],
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
                'barangTransaksis' => fn ($query) => $query
                    ->where('jenis_transaksi', 'keluar')
                    ->with('produk')
                    ->latest('tanggal_transaksi'),
            ])
            ->whereHas('barangTransaksis', fn ($query) => $query->where('jenis_transaksi', 'keluar'))
            ->when($request->search, function ($query, $search) {
                $query->where(function ($query) use ($search) {
                    $query->where('kode_penjualan', 'like', "%{$search}%")
                        ->orWhereHas('details', fn ($detail) => $detail
                            ->where('nama_penerima', 'like', "%{$search}%"))
                        ->orWhereHas('barangTransaksis', fn ($transaksi) => $transaksi
                            ->where('jenis_transaksi', 'keluar')
                            ->where(function ($transaksi) use ($search) {
                                $transaksi->where('keterangan', 'like', "%{$search}%")
                                    ->orWhereHas('produk', fn ($produk) => $produk
                                        ->where('jenis_gas', 'like', "%{$search}%"));
                            }));
                });
            })
            ->latest('created_at')
            ->paginate(10)
            ->withQueryString();

        $pembelianSudahKeluar = BarangTransaksi::where('jenis_transaksi', 'keluar')
            ->whereNotNull('id_penjualan')
            ->pluck('id_penjualan');

        $pembelianDetails = PembelianDetail::with(['pembelian', 'produk'])
            ->whereNotIn('id_penjualan', $pembelianSudahKeluar)
            ->whereHas('pembelian', function ($query) {
                $query->whereIn('payment_status', ['menunggu_konfirmasi', 'settlement']);
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

        DB::transaction(function () use ($request, $namaPetugas) {
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
            }
        });

        return redirect()
            ->route('admin.barang-keluar')
            ->with('success', 'Data barang keluar berhasil disimpan.');
    }

    public function printSuratJalan(Pembelian $pembelian)
    {
        $pembelian->load([
            'details',
            'barangTransaksis' => fn ($query) => $query
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
        });

        return redirect()
            ->route('admin.barang-keluar')
            ->with('success', 'Seluruh transaksi barang keluar berhasil dihapus dan stok dikembalikan.');
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
            $transaksi->delete();
        });

        return redirect()
            ->route('admin.barang-masuk')
            ->with('success', 'Data barang masuk berhasil dihapus dan stok dikembalikan.');
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
