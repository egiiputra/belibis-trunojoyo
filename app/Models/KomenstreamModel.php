<?php

namespace App\Models;

use CodeIgniter\Model;

class KomenstreamModel extends Model
{
    protected $table = 'komen_stream';
    protected $primaryKey = 'id_komen_stream';
    protected $allowedFields = ['id_kelas', 'stream', 'nama_stream', 'email_stream', 'gambar_stream', 'isi_komen', 'date_send'];

    public function getByIdKelasAndKodeStream($id_kelas, $kode_stream)
    {
    	return $this
    	->where('id_kelas', $id_kelas)
    	->where('stream', $kode_stream)
    	->get()->getResultObject();
    }
}
