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
     * Satu-satunya perubahan status manual yang boleh dilakukan admin lewat
     * dropdown: dari 'dikirim' menjadi 'settlement' (Berhasil), dan hanya
     * untuk transaksi 'pinjam' (bukan 'isi_ulang').
     */
    private const NEXT_STATUS = [
        'dikirim' => 'settlement',
    ];

    public function updatePaymentStatus(Request $request, Pembelian $pembelian)
    {
        $validated = $request->validate([
            'payment_status' => ['required', 'in:settlement'],
        ]);

        // Hanya transaksi 'pinjam' yang boleh diubah manual (bukan 'isi_ulang').
        $isPinjam = $pembelian->details()
            ->where('tipe_transaksi', 'pinjam')
            ->exists();

        if (! $isPinjam) {
            return response()->json([
                'message' => 'Hanya transaksi pinjam yang statusnya dapat diubah manual.',
            ], 422);
        }

        $currentStatus = strtolower(trim((string) $pembelian->payment_status));
        $requestedStatus = strtolower(trim($validated['payment_status']));

        $nextStatus = self::NEXT_STATUS[$currentStatus] ?? null;

        if ($nextStatus === null || $requestedStatus !== $nextStatus) {
            return response()->json([
                'message' => 'Status transaksi ini tidak dapat diubah ke status tersebut.',
            ], 422);
        }

        // 'settlement' = transaksi selesai (status terakhir), tidak bisa diubah lagi.
        $pembelian->update([
            'payment_status' => $nextStatus,
        ]);

        return response()->json([
            'success' => true,
            'payment_status' => $pembelian->payment_status,
            'label' => 'Berhasil',
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
