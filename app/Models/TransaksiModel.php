<?php

namespace App\Models;

use CodeIgniter\Model;

class TransaksiModel extends Model
{
    protected $table            = 'transaksi';
    protected $primaryKey       = 'id';
    protected $returnType       = 'array';
    protected $allowedFields    = [
        'nomor_transaksi',
        'user_id',
        'jenis_transaksi',
        'total_harga'
    ];

    protected $useTimestamps = false;
}
