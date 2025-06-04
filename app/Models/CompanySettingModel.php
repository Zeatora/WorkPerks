<?php

namespace App\Models;

use CodeIgniter\Model;

class CompanySettingModel extends Model
{
    protected $table      = 'company_settings';
    protected $primaryKey = 'id';

    protected $allowedFields = [
        'setting_key',
        'setting_value',
        'deskripsi',
        'updated_at'
    ];

    protected $useTimestamps = false;
}
