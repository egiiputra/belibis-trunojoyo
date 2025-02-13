<?php

namespace App\Models;

use CodeIgniter\Model;

class UserModel extends Model
{
    protected $table = 'user';
    protected $primaryKey = 'id_user';
    protected $allowedFields = ['no_regis', 'nama', 'email', 'password', 'gambar', 'role_id', 'is_active', 'date_created'];

    public function getuser($email)
    {
        return $this->where(['email' => $email])->asObject()->first();
    }
}
