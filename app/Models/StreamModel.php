<?php

namespace App\Models;

use CodeIgniter\Model;

class StreamModel extends Model
{
    protected $table = 'stream';
    protected $primaryKey = 'id_stream';
    protected $allowedFields = ['stream_kode', 'kelas_id', 'email', 'nama_user', 'gambar', 'text_stream', 'date_created'];

    public function getStreamByClass($id_kelas)
    {
        return $this
            ->where('kelas_id', $id_kelas)
            ->orderBy('id_stream', 'DESC')
            ->get()->getResultObject();
    }
}
