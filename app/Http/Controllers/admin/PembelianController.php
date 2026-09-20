<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\BarangTransaksi;
use App\Models\Pembelian;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PembelianController extends Controller
{
    public function showPembelian(Request $request)
    {
        $query = Pembelian::with(['user', 'details.produk']);

        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('kode_penjualan', 'like', '%' . $request->search . '%')
                    ->orWhereHas('user', fn($u) => $u->where('name', 'like', '%' . $request->search . '%'));
            });
        }

        if ($request->filled('status')) {
            $query->where('payment_status', $request->status);
        }

        $pembelians = $query->latest()->paginate(10)->withQueryString();

        return view('admin.pembelian', compact('pembelians'));
    }

    /**
     * Satu langkah status berikutnya yang boleh dilakukan admin:
     * pending -> menunggu_konfirmasi, menunggu_konfirmasi -> settlement.
     * Status 'dikirim' tidak di sini karena diisi otomatis saat admin
     * menginput barang keluar (TransaksiController::storeBarangKeluar).
     */
    private const NEXT_STATUS = [
        'pending' => 'menunggu_konfirmasi',
        'process' => 'menunggu_konfirmasi',
        'menunggu_konfirmasi' => 'settlement',
    ];

    public function updatePaymentStatus(Request $request, Pembelian $pembelian)
    {
        $validated = $request->validate([
            'payment_status' => ['required', 'in:menunggu_konfirmasi,settlement'],
        ]);

        $nextStatus = self::NEXT_STATUS[$pembelian->payment_status] ?? null;

        if ($nextStatus === null || $validated['payment_status'] !== $nextStatus) {
            return response()->json([
                'message' => 'Status transaksi ini tidak dapat diubah ke status tersebut.',
            ], 422);
        }

        // 'settlement' = pembayaran dikonfirmasi admin, transaksi masuk
        // antrean Barang Keluar dan akan berubah menjadi 'dikirim'
        // setelah barangnya benar-benar keluar.
        $pembelian->update([
            'payment_status' => $validated['payment_status'],
        ]);

        return response()->json([
            'success' => true,
            'payment_status' => $pembelian->payment_status,
            'label' => $validated['payment_status'] === 'settlement'
                ? 'Berhasil'
                : 'Menunggu Konfirmasi',
        ]);
    }

    public function destroy(Pembelian $pembelian)
    {
        if (BarangTransaksi::where('id_penjualan', $pembelian->id_penjualan)->exists()) {
            return redirect()->route('admin.pembelian')
                ->with('error', 'Penjualan tidak dapat dihapus karena sudah diproses menjadi barang keluar.');
        }

        DB::transaction(function () use ($pembelian) {
            $pembelian->details()->delete();
            $pembelian->delete();
        });

        return redirect()->route('admin.pembelian')
            ->with('success', 'Data penjualan berhasil dihapus.');
    }
}
