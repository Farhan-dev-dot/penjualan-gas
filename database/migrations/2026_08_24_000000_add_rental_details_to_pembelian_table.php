<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('pembelian', function (Blueprint $table) {
            $table->string('jenis_sewa')->nullable()->after('gross_amount');
            $table->unsignedInteger('jumlah_tabung')->nullable()->after('jenis_sewa');
            $table->unsignedInteger('durasi_sewa')->nullable()->after('jumlah_tabung');
            $table->date('tanggal_mulai')->nullable()->after('durasi_sewa');
            $table->date('tanggal_selesai')->nullable()->after('tanggal_mulai');
            $table->unsignedBigInteger('biaya_sewa')->default(0)->after('tanggal_selesai');
        });
    }

    public function down(): void
    {
        Schema::table('pembelian', function (Blueprint $table) {
            $table->dropColumn([
                'jenis_sewa',
                'jumlah_tabung',
                'durasi_sewa',
                'tanggal_mulai',
                'tanggal_selesai',
                'biaya_sewa',
            ]);
        });
    }
};
