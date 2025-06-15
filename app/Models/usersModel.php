<?php

namespace App\Models;

use CodeIgniter\Model;

class usersModel extends Model
{
    protected $table      = 'users';
    protected $primaryKey = 'id';

    protected $allowedFields = [
        'departemen_id',
        'username',
        'password',
        'nama_lengkap',
        'email',
        'role',
        'departemen',
        'created_at',
        'updated_at',
        'login_terakhir',
        'status',
        'url_profile'

    ];

    protected $useTimestamps = true;
}

