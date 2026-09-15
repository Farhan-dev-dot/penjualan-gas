<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PembelianDetail extends Model
{
    use HasFactory;

    protected $table = 'penjualan_detail';

    protected $primaryKey = 'id_detail';

    public $incrementing = true;

    protected $keyType = 'int';

    protected $fillable = [
        'id_penjualan',
        'id_produk',
        'nama_penerima',
        'telepon_penerima',
        'provinsi',
        'kota',
        'kecamatan',
        'kelurahan',
        'alamat_penerima',
        'catatan',
        'jumlah',
        'subtotal',
        'tipe_transaksi',
        'durasi',
        'mulai_sewa',
        'akhir_sewa',
    ];

    protected $casts = [
        'jumlah' => 'integer',
        'subtotal' => 'integer',
        'durasi' => 'integer',
        'mulai_sewa' => 'datetime',
        'akhir_sewa' => 'datetime',
    ];

    /**
     * Relasi ke penjualan
     */
    public function pembelian(): BelongsTo
    {
        return $this->belongsTo(
            Pembelian::class,
            'id_penjualan',
            'id_penjualan'
        );
    }

    /**
     * Relasi ke produk
     */
    public function produk(): BelongsTo
    {
        return $this->belongsTo(
            ProdukModel::class,
            'id_produk',
            'id_produk'
        );
    }
}
