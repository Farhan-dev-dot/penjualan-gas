<?php

namespace App\Http\Controllers\customer;

use App\Http\Controllers\Controller;
use App\Models\ProdukModel as Produk;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;

class ProdukController extends Controller
{
    public function index(Request $request)
    {
        // ================= NORMALISASI FILTER =================
        // Apa pun bentuk request-nya (string tunggal / array / null), selalu jadi array.
        // Ini satu-satunya tempat normalisasi dilakukan, tidak lagi di view.
        $selectedJenisGas = Arr::wrap($request->input('jenis_gas', []));
        $selectedBerat    = array_map('strval', Arr::wrap($request->input('berat', [])));

        // ================= DATA OPSI FILTER (untuk dropdown/checkbox) =================
        $jenisGasList = Produk::select('jenis_gas')
            ->whereNotNull('jenis_gas')
            ->distinct()
            ->orderBy('jenis_gas')
            ->pluck('jenis_gas');

        $beratList = Produk::select('berat', 'satuan')
            ->whereNotNull('berat')
            ->distinct()
            ->orderBy('berat')
            ->get();

        $stokOptions = [
            '' => 'Semua',
            'tersedia' => 'Tersedia',
            'menipis' => 'Stok Menipis',
        ];

        // ================= QUERY PRODUK =================
        $query = Produk::query();

        if (!empty($selectedJenisGas)) {
            $query->whereIn('jenis_gas', $selectedJenisGas);
        }

        if (!empty($selectedBerat)) {
            $query->whereIn('berat', $selectedBerat);
        }

        if ($request->filled('harga_min')) {
            $query->where('harga', '>=', (int) $request->input('harga_min'));
        }

        if ($request->filled('harga_max')) {
            $query->where('harga', '<=', (int) $request->input('harga_max'));
        }

        if ($request->input('stok') === 'tersedia') {
            $query->where('stok_isi', '>', 5); // sesuaikan ambang batas
        } elseif ($request->input('stok') === 'menipis') {
            $query->whereBetween('stok_isi', [1, 5]);
        } else {
            // default: tetap sembunyikan yang stok habis total
            $query->where('stok_isi', '>', 0);
        }

        match ($request->input('sort')) {
            'harga_asc'  => $query->orderBy('harga', 'asc'),
            'harga_desc' => $query->orderBy('harga', 'desc'),
            'terlaris'   => $query->orderBy('terjual', 'desc'), // sesuaikan jika kolom ini ada
            default      => $query->latest(),
        };

        $produk = $query->paginate(8)->withQueryString();

        // ================= KIRIM KE VIEW =================
        return view('customer.produk.index', [
            'produk'           => $produk,
            'jenisGasList'     => $jenisGasList,
            'beratList'        => $beratList,
            'stokOptions'      => $stokOptions,
            'selectedJenisGas' => $selectedJenisGas,
            'selectedBerat'    => $selectedBerat,
        ]);
    }
}
