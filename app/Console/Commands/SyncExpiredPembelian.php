<?php

namespace App\Console\Commands;

use App\Http\Controllers\customer\PembelianController;
use App\Models\Pembelian;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use Midtrans\Config;
use Midtrans\Transaction;

class SyncExpiredPembelian extends Command
{
    /**
     * Durasinya harus konsisten dengan payload 'expiry' pada Snap::getSnapToken().
     */
    protected const EXPIRY_UNIT = 'hour';
    protected const EXPIRY_DURATION = 1;

    protected $signature = 'pembelian:sync-expired';

    protected $description = 'Sinkronkan status pembelian pending/process yang sudah melewati masa kedaluwarsa Midtrans';

    public function __construct()
    {
        parent::__construct();

        Config::$serverKey    = config('midtrans.server_key');
        Config::$isProduction = config('midtrans.is_production');
        Config::$isSanitized  = true;
        Config::$is3ds        = true;
    }

    public function handle(PembelianController $pembelianController): int
    {
        $batasWaktu = Carbon::now()->sub(
            self::EXPIRY_DURATION,
            self::EXPIRY_UNIT
        );

        $jumlahDiproses = 0;

        Pembelian::query()
            ->whereIn('payment_status', ['pending', 'process'])
            ->where('created_at', '<=', $batasWaktu)
            ->chunkById(100, function ($daftarPembelian) use ($pembelianController, &$jumlahDiproses) {
                foreach ($daftarPembelian as $pembelian) {
                    try {
                        $response = Transaction::status($pembelian->kode_penjualan);
                        $data = json_decode(json_encode($response), true);

                        if (($data['order_id'] ?? null) !== $pembelian->kode_penjualan) {
                            throw new \RuntimeException('Order ID Midtrans tidak cocok.');
                        }

                        $pembelianController->applyMidtransStatus($pembelian, $data);
                        $jumlahDiproses++;
                    } catch (\Throwable $th) {
                        Log::warning('Gagal menyinkronkan status Midtrans transaksi kedaluwarsa', [
                            'kode_penjualan' => $pembelian->kode_penjualan,
                            'message' => $th->getMessage(),
                        ]);
                    }
                }
            });

        $this->info("Sinkronisasi selesai. {$jumlahDiproses} transaksi diperiksa.");

        return self::SUCCESS;
    }
}