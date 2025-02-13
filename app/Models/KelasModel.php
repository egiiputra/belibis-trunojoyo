<?php

namespace App\Models;

use CodeIgniter\Model;

class KelasModel extends Model
{
    protected $table = 'kelas';
    protected $primaryKey = 'id_kelas';
    protected $allowedFields = ['kode_kelas', 'nama_user', 'email_user', 'nama_kelas', 'mapel', 'gambar_user', 'bg_class'];

    public function getMyClass($email)
    {
        return $this
            ->join('user', 'user.email=kelas.email_user')
            ->where('kelas.email_user', $email)
            ->get()->getResultObject();
    }

    public function getClassByCode($kode_kelas)
    {
        return $this
            ->join('user', 'user.email=kelas.email_user')
            ->where('kelas.kode_kelas', $kode_kelas)
            ->get()->getRowObject();
    }
}
