<?php

namespace App\Models;

use CodeIgniter\Model;

class PgsiswaModel extends Model
{
    protected $table = 'pg_siswa';
    protected $primaryKey = 'id_pg_siswa';
    protected $allowedFields = ['id_pg_detail', 'kode_ujian', 'siswa', 'jawaban', 'benar'];

    public function getAllByExamCodeAndSiswa($kode_ujian, $id_siswa)
    {
        return $this
            ->where('kode_ujian', $kode_ujian)
            ->where('siswa', $id_siswa)
            ->get()->getResultObject();
    }
    public function getByExamCodeAndSiswa($kode_ujian, $id_siswa)
    {
        return $this
            ->where('kode_ujian', $kode_ujian)
            ->where('siswa', $id_siswa)
            ->get()->getRowObject();
    }
}
