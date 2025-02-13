<?php

namespace App\Models;

use CodeIgniter\Model;

class TugasModel extends Model
{
    protected $table = 'tugas';
    protected $primaryKey = 'id_tugas';
    protected $allowedFields = ['kode_tugas', 'kelas_kode', 'nama', 'email', 'gambar', 'title', 'description', 'date_created', 'due_date', 'date_updated'];

    public function getAllByClassCode($kode_kelas)
    {
        return $this
            ->where('kelas_kode', $kode_kelas)
            ->get()->getResultObject();
    }
    public function getAllbyEmailAndClassCode($kelas_kode, $email=null)
    {
        if ($email == null) {
            return $this
            ->where('kelas_kode', $kelas_kode)
            ->get()->getResultObject();
        }
        return $this
            ->where('kelas_kode', $kelas_kode)
            ->where('email', $email)
            ->get()->getResultObject();
    }
}
