<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Allow the distinct refill transaction type used by checkout.
     */
    public function up(): void
    {
        Schema::table('pembelian_detail', function (Blueprint $table) {
            $table->string('tipe_transaksi', 20)->change();
        });
    }

    public function down(): void
    {
        // The previous ENUM definition is database-specific and cannot be
        // restored safely without risking existing transaction data.
    }
};
