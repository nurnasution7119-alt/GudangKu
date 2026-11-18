<?php

namespace App\Models;

use CodeIgniter\Model;

class DetailTransaksiModel extends Model
{
    protected $table = 'detail_transaksi';
    protected $primaryKey = 'id';
    protected $returnType = 'array';

    protected $allowedFields = [
        'transaksi_id',
        'barang_id',
        'jumlah',
        'harga_modal',
        'harga_jual',
        'subtotal'
    ];

    protected $useTimestamps = false;
}
