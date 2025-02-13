<?php

namespace App\Models;

use CodeIgniter\Model;

class UjianModel extends Model
{
    protected $table = 'ujian';
    protected $primaryKey = 'id_ujian';
    protected $allowedFields = ['kode_ujian', 'kode_kelas', 'nama_ujian', 'tanggal_dibuat', 'waktu_jam', 'waktu_menit', 'jenis_ujian'];

    public function getAllByClassCode($kode_kelas)
    {
        return $this
            ->where('kode_kelas', $kode_kelas)
            ->orderBy('id_ujian', 'DESC')
            ->get()->getResultObject();
    }
}
