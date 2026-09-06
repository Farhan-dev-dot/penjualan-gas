<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\Pembelian;
use Illuminate\Http\Request;

class PembelianController extends Controller
{
    public function showPembelian(Request $request)
    {
        $query = Pembelian::with(['user', 'details.produk']);

        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('kode_pembelian', 'like', '%' . $request->search . '%')
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
     * Konfirmasi admin hanya dapat dilakukan saat pembayaran menunggu konfirmasi.
     */
    public function updatePaymentStatus(Request $request, Pembelian $pembelian)
    {
        $validated = $request->validate([
            'payment_status' => ['required', 'in:settlement'],
        ]);

        if ($pembelian->payment_status !== 'menunggu_konfirmasi') {
            return response()->json([
                'message' => 'Hanya transaksi berstatus menunggu konfirmasi yang dapat ditandai berhasil.',
            ], 422);
        }

        $pembelian->update([
            'payment_status' => $validated['payment_status'],
        ]);

        return response()->json([
            'success' => true,
            'payment_status' => $pembelian->payment_status,
            'label' => 'Berhasil',
        ]);
    }
}
