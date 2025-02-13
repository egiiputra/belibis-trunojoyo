<?php

namespace App\Models;

use CodeIgniter\Model;

class PgwaktusiswaModel extends Model
{
    protected $table = 'pg_waktu_siswa';
    protected $primaryKey = 'id_pg_waktu_siswa';
    protected $allowedFields = ['kode_ujian', 'siswa', 'waktu_berakhir', 'selesai'];

    public function getByExamCodeAndIdSiswa($kode_ujian, $id_user)
    {
        $this
            ->where('kode_ujian', $kode_ujian)
            ->where('siswa', $id_user)
            ->get()->getRowObject();
    }
}
