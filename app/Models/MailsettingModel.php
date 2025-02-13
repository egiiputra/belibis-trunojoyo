<?php

namespace App\Models;

use CodeIgniter\Model;

class MailsettingModel extends Model
{
    protected $table = 'settings';
    protected $primaryKey = 'id_settings';
    // protected $useTimestamps = true;
    protected $allowedFields = ['smtp_host', 'smtp_user', 'smtp_password', 'smtp_port', 'smtp_crypto'];
}
