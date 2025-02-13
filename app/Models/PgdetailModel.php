<?php

namespace App\Models;

use CodeIgniter\Model;

class PgdetailModel extends Model
{
    protected $table = 'pg_detail';
    protected $primaryKey = 'id_pg_detail';
    protected $allowedFields = ['kode_ujian', 'soal', 'pg_a', 'pg_b', 'pg_c', 'pg_d', 'pg_e', 'jawaban'];

    public function getAllByExamCode($kode_ujian)
    {
        return $this
            ->where('kode_ujian', $kode_ujian)
            ->get()->getResultObject();
    }
}
