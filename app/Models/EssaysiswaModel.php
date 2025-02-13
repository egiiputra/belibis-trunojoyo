<?php

namespace App\Models;

use CodeIgniter\Model;

class EssaysiswaModel extends Model
{
    protected $table = 'essay_siswa';
    protected $primaryKey = 'id_essay_siswa';
    protected $allowedFields = ['id_essay_detail', 'kode_ujian', 'siswa', 'jawaban', 'nilai'];

    public function getAllByExamCodeAndSiswa($kode_ujian, $id_siswa)
    {
        return $this
            ->where('kode_ujian', $kode_ujian)
            ->where('siswa', $id_siswa)
            ->get()->getResultObject();
    }
}
