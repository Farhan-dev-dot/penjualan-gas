<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\ProdukModel;
use App\Models\StokOpname;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class StokOpnameController extends Controller
{
    public function index(Request $request)
    {
        $produk = null;

        if ($request->filled('kode_produk')) {
            $produk = ProdukModel::where('kode_produk', trim($request->kode_produk))->first();
        }

        $opnames = StokOpname::with('produk')
            ->when($request->filled('search'), function ($query) use ($request) {
                $search = trim($request->search);
                $query->whereHas('produk', function ($produkQuery) use ($search) {
                    $produkQuery->where('kode_produk', 'like', "%{$search}%")
                        ->orWhere('jenis_gas', 'like', "%{$search}%");
                });
            })
            ->latest('tanggal_opname')
            ->paginate(10)
            ->withQueryString();

        return view('admin.stokopname', compact('produk', 'opnames'));
    }

    /**
     * Data produk untuk modal picker stok opname.
     * Mengembalikan partial blade (bukan JSON) agar langsung bisa
     * di-inject ke dalam modal via fetch().
     */
    public function produkPicker(Request $request)
    {
        $search = trim((string) $request->input('search'));

        $produks = ProdukModel::query()
            ->when($search !== '', function ($query) use ($search) {
                $query->where(function ($produkQuery) use ($search) {
                    $produkQuery->where('kode_produk', 'like', "%{$search}%")
                        ->orWhere('jenis_gas', 'like', "%{$search}%");
                });
            })
            ->orderBy('jenis_gas')
            ->paginate(10)
            ->withQueryString();

        if ($request->ajax() || $request->header('X-Requested-With') === 'XMLHttpRequest') {
            return view('admin.components.produkpickertable', compact('produks'));
        }

        return redirect()->route('admin.stok-opname');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'id_produk' => ['required', 'exists:produk,id_produk'],
            'stok_isi_fisik' => ['required', 'integer', 'min:0'],
            'stok_kosong_fisik' => ['required', 'integer', 'min:0'],
            'stok_pinjam_fisik' => ['required', 'integer', 'min:0'],
            'stok_rusak_fisik' => ['required', 'integer', 'min:0'],
            'keterangan' => ['nullable', 'string', 'max:1000'],
            'tanggal_opname' => ['required', 'date'],
            'penyesuaian' => ['nullable', 'boolean'],
        ]);

        DB::transaction(function () use ($validated, $request) {
            $produk = ProdukModel::whereKey($validated['id_produk'])
                ->lockForUpdate()
                ->firstOrFail();

            $fisik = [
                'isi' => (int) $validated['stok_isi_fisik'],
                'kosong' => (int) $validated['stok_kosong_fisik'],
                'pinjam' => (int) $validated['stok_pinjam_fisik'],
                'rusak' => (int) $validated['stok_rusak_fisik'],
            ];
            $sistem = [
                'isi' => (int) $produk->stok_isi,
                'kosong' => (int) $produk->stok_kosong,
                'pinjam' => (int) $produk->stok_pinjam,
                'rusak' => (int) $produk->stok_rusak,
            ];

            $namaPetugas = auth()->user()?->name;

            StokOpname::create([
                'id_produk' => $produk->id_produk,
                'stok_isi_sistem' => $sistem['isi'],
                'stok_kosong_sistem' => $sistem['kosong'],
                'stok_pinjam_sistem' => $sistem['pinjam'],
                'stok_rusak_sistem' => $sistem['rusak'],
                'stok_isi_fisik' => $fisik['isi'],
                'petugas' => $namaPetugas,
                'stok_kosong_fisik' => $fisik['kosong'],
                'stok_pinjam_fisik' => $fisik['pinjam'],
                'stok_rusak_fisik' => $fisik['rusak'],
                'selisih_isi' => $fisik['isi'] - $sistem['isi'],
                'selisih_kosong' => $fisik['kosong'] - $sistem['kosong'],
                'selisih_pinjam' => $fisik['pinjam'] - $sistem['pinjam'],
                'selisih_rusak' => $fisik['rusak'] - $sistem['rusak'],
                'keterangan' => $validated['keterangan'] ?? null,
                'tanggal_opname' => $validated['tanggal_opname'],
            ]);

            if ($request->boolean('penyesuaian')) {
                $produk->update([
                    'stok_isi' => $fisik['isi'],
                    'stok_kosong' => $fisik['kosong'],
                    'stok_pinjam' => $fisik['pinjam'],
                    'stok_rusak' => $fisik['rusak'],
                ]);
            }
        });

        return redirect()->route('admin.stok-opname')
            ->with('success', 'Stok opname berhasil disimpan.');
    }

    public function destroy(StokOpname $stokOpname)
    {
        DB::transaction(function () use ($stokOpname) {
            $opname = StokOpname::whereKey($stokOpname->getKey())
                ->lockForUpdate()
                ->firstOrFail();
            $produk = ProdukModel::whereKey($opname->id_produk)
                ->lockForUpdate()
                ->firstOrFail();

            $produk->update([
                'stok_isi' => $opname->stok_isi_sistem,
                'stok_kosong' => $opname->stok_kosong_sistem,
                'stok_pinjam' => $opname->stok_pinjam_sistem,
                'stok_rusak' => $opname->stok_rusak_sistem,
            ]);

            $opname->delete();
        });

        return redirect()->route('admin.stok-opname')
            ->with('success', 'Data stok opname dihapus dan stok dikembalikan seperti semula.');
    }
}
