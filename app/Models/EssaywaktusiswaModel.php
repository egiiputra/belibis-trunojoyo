<?php

namespace App\Models;

use CodeIgniter\Model;

class EssaywaktusiswaModel extends Model
{
    protected $table = 'essay_waktu_siswa';
    protected $primaryKey = 'id_essay_waktu_siswa';
    protected $allowedFields = ['kode_ujian', 'siswa', 'waktu_berakhir', 'selesai'];

    public function getByExamCodeAndEmailSiswa($kode_ujian, $email_siswa)
    {
        return $this
            ->where('kode_ujian', $kode_ujian)
            ->where('siswa', $email_siswa)
            ->get()->getRowObject();
    }
}
