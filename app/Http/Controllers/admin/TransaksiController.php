<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\BarangTransaksi;
use App\Models\ProdukModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class TransaksiController extends Controller
{
    public function showBarangMasuk(Request $request)
    {
        $barangMasuks = BarangTransaksi::with(['produk'])
            ->when($request->search, function ($q) use ($request) {
                $q->whereHas('produk', fn($qq) => $qq->where('jenis_gas', 'like', "%{$request->search}%"))
                    ->orWhere('keterangan', 'like', "%{$request->search}%");
            })
            ->latest('tanggal_transaksi')
            ->paginate(10)
            ->withQueryString();

        $produks = ProdukModel::orderBy('jenis_gas')->get();

        return view('admin.barangmasuk', compact('barangMasuks', 'produks'));
    }

    public function storeBarangMasuk(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'tanggal_transaksi' => ['required', 'date'],
            'items' => ['required', 'array', 'min:1'],
            'items.*.id_produk' => ['required', 'exists:produk,id_produk'],
            'items.*.jenis_transaksi' => ['required', 'in:masuk,retur'],
            'items.*.stok_isi' => ['required', 'integer', 'min:0'],
            'items.*.stok_kosong' => ['required', 'integer', 'min:0'],
            'items.*.stok_pinjam' => ['required', 'integer', 'min:0'],
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => $validator->errors()->first(),
            ], 422);
        }

        foreach ($request->items as $item) {
            BarangTransaksi::create([
                'id_pembelian' => null,
                'id_produk' => $item['id_produk'],
                'jenis_transaksi' => $item['jenis_transaksi'],
                'stok_isi' => (int) $item['stok_isi'],
                'stok_kosong' => (int) $item['stok_kosong'],
                'stok_pinjam' => (int) $item['stok_pinjam'],
                'keterangan' => $item['keterangan'] ?? null,
                'tanggal_transaksi' => $request->tanggal_transaksi,
            ]);
        }

        return response()->json([
            'message' => 'Data barang masuk berhasil disimpan.',
        ]);
    }

    public function showBarangKeluar()
    {
        return view('admin.barangkeluar');
    }
}
