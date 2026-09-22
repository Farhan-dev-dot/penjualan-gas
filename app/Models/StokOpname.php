<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StokOpname extends Model
{
    use HasFactory;

    protected $table = 'stok_opname';

    protected $primaryKey = 'id_stok_opname';

    protected $fillable = [
        'id_produk',

        'stok_isi_sistem',
        'stok_kosong_sistem',
        'stok_pinjam_sistem',
        'stok_rusak_sistem',

        'stok_isi_fisik',
        'stok_kosong_fisik',
        'stok_pinjam_fisik',
        'stok_rusak_fisik',

        'selisih_isi',
        'selisih_kosong',
        'selisih_pinjam',
        'selisih_rusak',

        'keterangan',
        'tanggal_opname',

    ];

    protected $casts = [
        'tanggal_opname' => 'datetime',
    ];

    /*
    |--------------------------------------------------------------------------
    | Relasi Produk
    |--------------------------------------------------------------------------
    */

    public function produk()
    {
        return $this->belongsTo(ProdukModel::class, 'id_produk', 'id_produk');
    }
}
