<?php

namespace App\Models;

use CodeIgniter\Model;

class usersModel extends Model
{
    protected $table      = 'users';
    protected $primaryKey = 'id';

    protected $allowedFields = [
        'username',
        'password',
        'nama_lengkap',
        'email',
        'role',
        'departemen',
        'created_at',
        'updated_at',
        'status'
    ];

    protected $useTimestamps = false;
}

