<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BarangTransaksi extends Model
{
    protected $table = 'barang_transaksi';

    protected $primaryKey = 'id_transaksi';

    protected $fillable = [
        'id_pembelian',
        'id_detail',
        'id_produk',
        'jenis_transaksi',
        'stok_isi',
        'stok_kosong',
        'stok_pinjam',
        'keterangan',
        'tanggal_transaksi',
    ];

    protected $casts = [
        'tanggal_transaksi' => 'datetime',
    ];

    /*
    |--------------------------------------------------------------------------
    | Relasi ke Pembelian
    |--------------------------------------------------------------------------
    | Banyak barang transaksi dimiliki oleh satu pembelian.
    */

    public function pembelian()
    {
        return $this->belongsTo(
            Pembelian::class,
            'id_pembelian',
            'id_pembelian'
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Relasi ke Pembelian Detail
    |--------------------------------------------------------------------------
    */

    public function detail()
    {
        return $this->belongsTo(
            PembelianDetail::class,
            'id_detail',
            'id_detail'
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Relasi ke Produk
    |--------------------------------------------------------------------------
    */

    public function produk()
    {
        return $this->belongsTo(
            ProdukModel::class,
            'id_produk',
            'id_produk'
        );
    }
}
