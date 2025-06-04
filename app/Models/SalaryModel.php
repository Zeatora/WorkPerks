<?php

namespace App\Models;

use CodeIgniter\Model;

class SalaryModel extends Model
{
    protected $table      = 'salaries';
    protected $primaryKey = 'id';

    protected $allowedFields = [
        'user_id',
        'gaji_pokok',
        'bonus',
        'insentif_kinerja',
        'total_gaji',
        'periode',
        'created_at'
    ];



    protected $useTimestamps = false;

}


