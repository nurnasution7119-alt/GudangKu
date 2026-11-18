<?php

namespace App\Models;

use CodeIgniter\Model;

class BarangModel extends Model
{
    protected $table = 'barang';
    protected $primaryKey = 'id';
    protected $returnType = 'array';

    protected $allowedFields = [
        'kode_barang',
        'nama_barang',
        'stok',
        'harga_modal',
        'harga_jual',
        'tanggal_masuk',
        'tanggal_kedaluwarsa',
        'input_oleh'
    ];

    protected $useTimestamps = false;
}
