<?php

namespace App\Models;

use CodeIgniter\Model;

class KomenmateriModel extends Model
{
    protected $table = 'komen_material';
    protected $primaryKey = 'id_komen';
    protected $allowedFields = ['kode_materi', 'nama', 'email', 'gambar', 'isi_komen', 'date_send'];

    public function getByKodeMateri($kode_materi)
    {
    	return $this
    	->where('kode_materi', $kode_materi)
    	->get()->getResultObject();
    }
}
