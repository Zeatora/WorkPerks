<?php

namespace App\Models;

use CodeIgniter\Model;

class ClaimModel extends Model
{
    protected $table      = 'claims';
    protected $primaryKey = 'id';

    protected $allowedFields = [
        'user_id',
        'tipe_klaim',
        'jumlah',
        'deskripsi',
        'status',
        'submitted_at',
        'approved_at'
    ];

    protected $useTimestamps = false;
}
