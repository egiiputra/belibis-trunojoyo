<?php

namespace App\Models;

use CodeIgniter\Model;

class UsertokenModel extends Model
{
    protected $table = 'user_token';
    protected $allowedFields = ['email', 'token', 'date_created'];

    public function getUserToken($email, $token)
    {
        return $this
            ->where('email', $email)
            ->where('token', $token)
            ->get()->getRowObject();
    }
}
