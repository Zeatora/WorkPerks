<?php

namespace App\Models;

use CodeIgniter\Model;

class UserBenefitModel extends Model
{
    protected $table      = 'user_benefit';
    protected $primaryKey = 'id';

    protected $allowedFields = [
        'user_id',
        'benefit_id',
        'tanggal_mulai',
        'tanggal_berakhir',
        'status'
    ];
}
