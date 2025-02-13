<?php

namespace App\Controllers;

use App\Models\UserModel;
use App\Models\StreamModel;
use App\Models\FilestreamModel;
use App\Models\KomenstreamModel;
use App\Models\KomenmateriModel;
use App\Models\KomentugasModel;

class Stream extends BaseController
{
    protected $UserModel;
    protected $StreamModel;
    protected $FilestreamModel;
    protected $KomenstreamModel;
    protected $KomenmateriModel;
    protected $KomentugasModel;

    public function __construct()
    {
        $this->validation = \Config\Services::validation();
        $this->email = \Config\Services::email();

        $this->UserModel = new UserModel();
        $this->StreamModel = new StreamModel();
        $this->FilestreamModel = new FilestreamModel();
        $this->KomenstreamModel = new KomenstreamModel();
        $this->KomenmateriModel = new KomenmateriModel();
        $this->KomentugasModel = new KomentugasModel();
        date_default_timezone_set('Asia/Jakarta');
    }

    public function classtream()
    {
        $user = $this->UserModel->getuser(session()->get('email'));
        $data = [
            'id_kelas' => $this->request->getVar('id_kelas'),
            'stream' => $this->request->getVar('kode_stream'),
            'nama_stream' => $user->nama,
            'email_stream' => $user->email,
            'gambar_stream' => $user->gambar,
            'isi_komen' => $this->request->getVar('isi_komen'),
            'date_send' => time()
        ];

        $this->KomenstreamModel->save($data);
    }

    public function getclasstream()
    {
        $id_kelas = $this->request->getVar('id_kelas');

        $kode_stream = $this->request->getVar('kode_stream');
        $komen_stream = $this->KomenstreamModel->getByIdKelasAndKodeStream($id_kelas, $kode_stream);

        if ($komen_stream != null) {
            foreach ($komen_stream as $komen) {
                $user = $this->UserModel->getuser($komen->email_stream);
                echo '
                        <div class="pt-5">
                            <div class="flex">
                                <div class="w-10 h-10 sm:w-12 sm:h-12 flex-none image-fit">
                                    <img alt="gambar" class="rounded-full" src="' . base_url() . '/vendor/dist/user/' . $user->gambar . '">
                                </div>
                                <div class="ml-3 flex-1">
                                    <div class="flex items-center"> <a href="" class="font-medium">' . $user->nama . '</a></div>
                                    <div class="text-gray-600 text-xs sm:text-sm">' . time_ago(date('Y-m-d H:i:s', $komen->date_send)) . '</div>
                                    <div class="mb-5" style="white-space: pre-line; margin-top: -20px;">
                                        ' . $komen->isi_komen . '
                                    </div>
                                </div>
                            </div>
                        </div>
                ';
            }
        }
    }

    public function materi()
    {
        $user = $this->UserModel->getuser(session()->get('email'));
        $data = [
            'kode_materi' => $this->request->getVar('kode_materi'),
            'nama' => $user->nama,
            'email' => $user->email,
            'gambar' => $user->gambar,
            'isi_komen' => $this->request->getVar('isi_komen'),
            'date_send' => time()
        ];

        $this->KomenmateriModel->save($data);
    }

    public function getmateri()
    {
        $kode_materi = $this->request->getVar('kode_materi');
        $komen_materi = $this->KomenmateriModel->getByKodeMateri($kode_materi);

        if ($komen_materi != null) {
            foreach ($komen_materi as $komen) {
                $user = $this->UserModel->getuser($komen->email);
                echo '
                        <div class="pt-5">
                            <div class="flex">
                                <div class="w-10 h-10 sm:w-12 sm:h-12 flex-none image-fit">
                                    <img alt="gambar" class="rounded-full" src="' . base_url() . '/vendor/dist/user/' . $user->gambar . '">
                                </div>
                                <div class="ml-3 flex-1">
                                    <div class="flex items-center"> <a href="" class="font-medium">' . $user->nama . '</a></div>
                                    <div class="text-gray-600 text-xs sm:text-sm">' . time_ago(date('Y-m-d H:i:s', $komen->date_send)) . '</div>
                                    <div class="mb-5" style="white-space: pre-line; margin-top: -20px;">
                                        ' . $komen->isi_komen . '
                                    </div>
                                </div>
                            </div>
                        </div>
                ';
            }
        }
    }


    // TUGAS
    public function tugas()
    {
        $user = $this->UserModel->getuser(session()->get('email'));
        $data = [
            'kode_tugas' => $this->request->getVar('kode_tugas'),
            'nama' => $user->nama,
            'email' => $user->email,
            'gambar' => $user->gambar,
            'isi_komen' => $this->request->getVar('isi_komen'),
            'date_send' => time()
        ];

        $this->KomentugasModel->save($data);
    }

    public function gettugas()
    {
        $kode_tugas = $this->request->getVar('kode_tugas');
        $komen_tugas = $this->KomentugasModel->getByKodeTugas($kode_tugas);

        if ($komen_tugas != null) {
            foreach ($komen_tugas as $komen) {
                $user = $this->UserModel->getuser($komen->email);
                echo '
                    <div class="pt-5">
                        <div class="flex">
                            <div class="w-10 h-10 sm:w-12 sm:h-12 flex-none image-fit">
                                <img alt="gambar" class="rounded-full" src="' . base_url() . '/vendor/dist/user/' . $user->gambar . '">
                            </div>
                            <div class="ml-3 flex-1">
                                <div class="flex items-center"> <a href="" class="font-medium">' . $user->nama . '</a></div>
                                <div class="text-gray-600 text-xs sm:text-sm">' . time_ago(date('Y-m-d H:i:s', $komen->date_send)) . '</div>
                                <div class="mb-5" style="white-space: pre-line; margin-top: -20px;">
                                    ' . $komen->isi_komen . '
                                </div>
                            </div>
                        </div>
                    </div>
                ';
            }
        }
    }
    // END TUGAS
}
