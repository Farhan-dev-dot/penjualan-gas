<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Pembelian extends Model
{
    use HasFactory;

    protected $table = 'pembelian';

    protected $primaryKey = 'id_pembelian';

    public $incrementing = true;

    protected $keyType = 'int';

    protected $fillable = [
        'kode_pembelian',
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
     * Relasi ke detail pembelian
     */
    public function details(): HasMany
    {
        return $this->hasMany(
            PembelianDetail::class,
            'id_pembelian',
            'id_pembelian'
        );
    }
}
