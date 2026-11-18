<?php

namespace App\Models;

use CodeIgniter\Model;

class UserModel extends Model
{
    protected $table = 'users';
    protected $primaryKey = 'id';
    protected $returnType = 'array';

    protected $allowedFields = [
        'nama',
        'username',
        'email',
        'password',
        'role',
        'is_verified',
        'otp_code',
        'otp_expired_at'
    ];

    protected $useTimestamps = false;
}