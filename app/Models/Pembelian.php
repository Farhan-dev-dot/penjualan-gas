<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Pembelian extends Model
{
    use HasFactory;

    protected $table = 'penjualan';

    protected $primaryKey = 'id_penjualan';

    public $incrementing = true;

    protected $keyType = 'int';

    protected $fillable = [
        'kode_penjualan',
        'id_user',
        'gross_amount',
        'payment_type',
        'payment_status',
        'midtrans_response',
        'snap_token',
    ];

    protected $casts = [
        'gross_amount' => 'integer',
        'midtrans_response' => 'array',
    ];

    /**
     * Relasi ke User
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'id_user', 'id');
    }

    /**
     * Relasi ke detail penjualan
     */
    public function details(): HasMany
    {
        return $this->hasMany(
            PembelianDetail::class,
            'id_penjualan',
            'id_penjualan'
        );
    }

    /**
     * Seluruh pergerakan stok yang dibuat dari pembelian ini.
     */
    public function barangTransaksis(): HasMany
    {
        return $this->hasMany(
            BarangTransaksi::class,
            'id_penjualan',
            'id_penjualan'
        );
    }
}
