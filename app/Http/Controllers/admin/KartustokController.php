<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\KartuStok;
use App\Models\ProdukModel;
use Illuminate\Http\Request;

class KartustokController extends Controller
{
    public function ShowKartuStok(Request $request)
    {
        $request->validate([
            'perusahaan' => ['sometimes', 'string', 'max:255'],
            'tanggal_mulai' => ['sometimes', 'date'],
            'tanggal_akhir' => ['sometimes', 'date', 'after_or_equal:tanggal_mulai'],
        ]);

        $sudahFilter =
            $request->filled('perusahaan') ||
            $request->filled('tanggal_mulai') ||
            $request->filled('tanggal_akhir');

        $kartuStoks = null;

        if ($sudahFilter) {

            $kartuStoks = KartuStok::with([
                'barangTransaksi.produk',
                'barangTransaksi.pembelian.details',
            ])

                // ============================
                // FILTER NAMA PENERIMA
                // ============================
                ->when($request->filled('perusahaan'), function ($query) use ($request) {

                    $search = trim($request->perusahaan);

                    $query->whereHas(
                        'barangTransaksi.pembelian.details',
                        function ($detailQuery) use ($search) {

                            $detailQuery->where(
                                'nama_penerima',
                                'LIKE',
                                '%' . $search . '%'
                            );
                        }
                    );
                })

                // ============================
                // FILTER TANGGAL MULAI
                // ============================
                ->when($request->filled('tanggal_mulai'), function ($query) use ($request) {

                    $query->whereDate(
                        'tanggal_transaksi',
                        '>=',
                        $request->tanggal_mulai
                    );
                })

                // ============================
                // FILTER TANGGAL AKHIR
                // ============================
                ->when($request->filled('tanggal_akhir'), function ($query) use ($request) {

                    $query->whereDate(
                        'tanggal_transaksi',
                        '<=',
                        $request->tanggal_akhir
                    );
                })

                // ============================
                // URUTKAN
                // ============================
                ->orderBy('tanggal_transaksi', 'asc')
                ->orderBy('id_kartu_stok', 'asc')

                // ============================
                // PAGINATION
                // ============================
                ->paginate(20)
                ->withQueryString();
        }

        return view('admin.kartustok', compact('kartuStoks'));
    }

    public function print(Request $request)
    {
        if (
            !$request->filled('perusahaan') &&
            !$request->filled('tanggal_mulai') &&
            !$request->filled('tanggal_akhir')
        ) {
            return redirect()
                ->route('admin.kartu-stok')
                ->with('error', 'Silakan gunakan filter terlebih dahulu sebelum mencetak kartu stok.');
        }

        $produks = ProdukModel::query()
            ->orderBy('jenis_gas')
            ->orderBy('berat')
            ->get(['id_produk', 'jenis_gas', 'berat', 'satuan']);

        $kartuStoks = KartuStok::with([
            'barangTransaksi.produk',
            'barangTransaksi.pembelian.details',
        ])
            ->when($request->filled('perusahaan'), function ($query) use ($request) {
                $search = trim($request->perusahaan);

                $query->whereHas('barangTransaksi.pembelian.details', function ($detailQuery) use ($search) {
                    $detailQuery->where('nama_penerima', 'LIKE', '%' . $search . '%');
                });
            })
            ->when($request->filled('tanggal_mulai'), function ($query) use ($request) {
                $query->whereDate('tanggal_transaksi', '>=', $request->tanggal_mulai);
            })
            ->when($request->filled('tanggal_akhir'), function ($query) use ($request) {
                $query->whereDate('tanggal_transaksi', '<=', $request->tanggal_akhir);
            })
            ->orderBy('tanggal_transaksi')
            ->orderBy('id_kartu_stok')
            ->get();

        $headerDetail = $kartuStoks
            ->map(function ($kartuStok) {
                $transaksi = $kartuStok->barangTransaksi;

                return $transaksi?->pembelian?->details?->firstWhere(
                    'id_produk',
                    $transaksi?->id_produk,
                );
            })
            ->filter()
            ->first();

        return view('components.print_kartustok', compact('produks', 'kartuStoks', 'headerDetail'));
    }
}
