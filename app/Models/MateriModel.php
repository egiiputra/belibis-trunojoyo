<?php

namespace App\Models;

use CodeIgniter\Model;

class MateriModel extends Model
{
    protected $table = 'materi';
    protected $primaryKey = 'id_materi';
    protected $allowedFields = ['materi_kode', 'kelas_id', 'email', 'nama', 'gambar', 'title', 'description', 'date_created', 'date_updated'];

    public function getAllbyIdClass($id_kelas)
    {
        return $this
            ->join('user', 'user.email=materi.email')
            ->where('materi.kelas_id', $id_kelas)
            ->get()->getResultObject();
    }
}
