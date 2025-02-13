<?php

namespace App\Models;

use CodeIgniter\Model;

class KomentugasModel extends Model
{
    protected $table = 'komen_tugas';
    protected $primaryKey = 'id_komen';
    protected $allowedFields = ['kode_tugas', 'nama', 'email', 'gambar', 'isi_komen', 'date_send'];

    public function getByKodeTugas($kode_tugas)
    {
        return $this
            ->where('kode_tugas', $kode_tugas)
            ->get()->getResultObject();
    }
}
