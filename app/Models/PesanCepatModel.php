<?php

namespace App\Models;

use CodeIgniter\Model;

class PesanCepatModel extends Model
{
    protected $table      = 'pesan_cepat';
    protected $primaryKey = 'id';

    protected $allowedFields = [
        'user_id',
        'nama',
        'email',
        'pesan',
        'created_at',
        'updated_at'
    ];

    protected $useTimestamps = true;
}
