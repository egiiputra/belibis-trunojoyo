<?php

namespace App\Models;

use CodeIgniter\Model;

class UsertugasModel extends Model
{
    protected $table = 'user_tugas';
    protected $primaryKey = 'id_user_tugas';
    protected $allowedFields = ['kode_user_tugas', 'kode_tugas', 'kode_kelas', 'nama', 'email', 'gambar', 'teks', 'date_send', 'is_late', 'grade'];

    public function getAllByKodeTugas($kode_tugas)
    {
        return $this
            ->join('user', 'user.email=user_tugas.email')
            ->where('kode_tugas', $kode_tugas)
            ->get()->getResultObject();
    }

    public function getByKodeTugasAndEmail($kode_tugas, $email)
    {
        return $this
            ->join('user', 'user.email=user_tugas.email')
            ->where('user_tugas.kode_tugas', $kode_tugas)
            ->where('user_tugas.email', $email)
            ->get()->getRowObject();
    }
}
