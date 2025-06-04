<?php

namespace App\Models;

use CodeIgniter\Model;

class ReportModel extends Model
{
    protected $table      = 'reports';
    protected $primaryKey = 'id';

    protected $allowedFields = [
        'tipe_report',
        'generated_by',
        'report_period',
        'file_path',
        'created_at'
    ];

    protected $useTimestamps = false;
}
