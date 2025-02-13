<?php

namespace App\Models;

use CodeIgniter\Model;

class FilestreamModel extends Model
{
    protected $table = 'file_stream';
    protected $allowedFields = ['kode_stream', 'nama_file'];
}
