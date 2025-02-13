<?php

namespace App\Controllers;

use App\Models\UserModel;
use App\Models\StreamModel;
use App\Models\FilestreamModel;
use App\Models\KomenstreamModel;

class Unduh extends BaseController
{
    protected $UserModel;
    protected $StreamModel;
    protected $FilestreamModel;
    protected $KomenstreamModel;

    public function __construct()
    {
        $this->validation = \Config\Services::validation();
        $this->email = \Config\Services::email();

        $this->UserModel = new UserModel();
        $this->StreamModel = new StreamModel();
        $this->FilestreamModel = new FilestreamModel();
        $this->KomenstreamModel = new KomenstreamModel();
        date_default_timezone_set('Asia/Jakarta');
    }

    public function stream($nama)
    {
        $nama_file = decrypt_url($nama);
        return $this->response->download('./assets/stream_file/' . $nama_file, null);
    }

    public function excel_pg()
    {
        return $this->response->download('./assets/stream_file/template.xlsx', null);
    }
}
