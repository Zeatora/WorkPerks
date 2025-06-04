<?php

namespace App\Models;

use CodeIgniter\Model;

class BenefitModel extends Model
{
    protected $table      = 'benefits';
    protected $primaryKey = 'id';

    protected $allowedFields = [
        'nama',
        'deskripsi',
        'kategori',
        'is_active',
        'created_at',
        'updated_at'
    ];

    protected $useTimestamps = true;
}
