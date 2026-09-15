<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\BarangTransaksi;
use App\Models\PembelianDetail;
use App\Models\ProdukModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class ProdukController extends Controller
{
    public function ShowProduk(Request $request)
    {

        $produks = ProdukModel::query()
            ->when($request->search, function ($query, $search) {
                $query->where('jenis_gas', 'like', "%{$search}%")
                    ->orWhere('kode_produk', 'like', "%{$search}%");
            })
            ->latest()
            ->paginate(10)
            ->withQueryString();


        return view('admin.produk', compact('produks'));
    }


    protected function rules($id = null)
    {
        return [
            'jenis_gas' => 'required|string|max:150',
            'berat' => 'required|numeric|min:0',
            'satuan' => 'required|string|max:20',
            'harga' => 'required|numeric|min:0',
            'deskripsi' => 'nullable|string',
            'foto' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'stok_isi' => 'required|integer|min:0',
            'stok_kosong' => 'required|integer|min:0',
            'stok_pinjam' => 'required|integer|min:0',
        ];
    }
    public function store(Request $request)
    {
        $validated = $request->validate($this->rules());

        // Ambil kode produk terakhir
        $lastProduk = ProdukModel::orderByDesc('id_produk')->first();

        if ($lastProduk) {
            // Ambil angka dari PROD-001
            $lastNumber = (int) str_replace('PROD-', '', $lastProduk->kode_produk);

            // Tambahkan 1
            $nextNumber = $lastNumber + 1;
        } else {
            // Jika belum ada produk
            $nextNumber = 1;
        }

        // Format menjadi PROD-001
        $validated['kode_produk'] = 'PROD-' . str_pad($nextNumber, 3, '0', STR_PAD_LEFT);

        if ($request->hasFile('foto')) {
            $validated['foto'] = $request->file('foto')
                ->store('produk', 'public');
        }

        ProdukModel::create($validated);

        return redirect()
            ->route('admin.produk')
            ->with('success', 'Produk berhasil ditambahkan.');
    }

    public function update(Request $request, ProdukModel $produk)
    {
        $validated = $request->validate($this->rules($produk->id_produk));

        if ($request->hasFile('foto')) {
            if ($produk->foto) {
                Storage::disk('public')->delete($produk->foto);
            }
            $validated['foto'] = $request->file('foto')->store('produk', 'public');
        }

        $produk->update($validated);

        return redirect()->route('admin.produk')
            ->with('success', 'Produk berhasil diperbarui.');
    }

    public function destroy(ProdukModel $produk)
    {
        $dipakaiTransaksi = BarangTransaksi::where('id_produk', $produk->id_produk)->exists();
        $dipakaiPembelian = PembelianDetail::where('id_produk', $produk->id_produk)->exists();

        if ($dipakaiTransaksi || $dipakaiPembelian) {
            return redirect()->route('admin.produk')
                ->with('error', 'Produk tidak dapat dihapus karena sudah digunakan dalam transaksi atau pembelian.');
        }

        if ($produk->foto) {
            Storage::disk('public')->delete($produk->foto);
        }

        $produk->delete();

        return redirect()->route('admin.produk')
            ->with('success', 'Produk berhasil dihapus.');
    }
}
