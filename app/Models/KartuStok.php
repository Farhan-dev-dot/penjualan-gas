<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KartuStok extends Model
{
    use HasFactory;

    protected $table = 'kartu_stok';

    protected $primaryKey = 'id_kartu_stok';

    protected $fillable = [
        'id_transaksi',
        'stok_isi_sebelum',
        'stok_kosong_sebelum',
        'stok_pinjam_sebelum',
        'stok_isi_sesudah',
        'stok_kosong_sesudah',
        'stok_pinjam_sesudah',
        'tanggal_transaksi',
    ];

    protected $casts = [
        'tanggal_transaksi' => 'datetime',
    ];

    public function barangTransaksi()
    {
        return $this->belongsTo(
            BarangTransaksi::class,
            'id_transaksi',
            'id_transaksi'
        );
    }
}
