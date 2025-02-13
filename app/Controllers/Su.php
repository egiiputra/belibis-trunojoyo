<?php

namespace App\Controllers;

use App\Models\UserModel;
use App\Models\MailsettingModel;
use App\Models\UsertokenModel;
use App\Models\KelasModel;
use App\Models\UserkelasModel;
use App\Models\StreamModel;
use App\Models\KomenstreamModel;
use App\Models\KomenmateriModel;
use App\Models\KomentugasModel;
use App\Models\FilestreamModel;
use App\Models\MateriModel;
use App\Models\TugasModel;
use App\Models\UsertugasModel;
use App\Models\UjianModel;
use App\Models\PgdetailModel;
use App\Models\EssaysiswaModel;
use App\Models\EssaydetailModel;
use App\Models\PgsiswaModel;
use App\Models\PgwaktusiswaModel;
use App\Models\EssaywaktusiswaModel;

use App\Controllers\Auth;

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Reader\Xls;
use PhpOffice\PhpSpreadsheet\Reader\Xlsx;

class Su extends BaseController
{
    protected $UserModel;
    protected $MailsettingModel;
    protected $UsertokenModel;
    protected $KelasModel;
    protected $UserkelasModel;
    protected $StreamModel;
    protected $FilestreamModel;
    protected $KomenstreamModel;
    protected $KomenmateriModel;
    protected $KomentugasModel;
    protected $MateriModel;
    protected $TugasModel;
    protected $UsertugasModel;
    protected $UjianModel;
    protected $PgdetailModel;
    protected $EssaysiswaModel;
    protected $EssaydetailModel;
    protected $PgsiswaModel;
    protected $PgwaktusiswaModel;
    protected $EssaywaktusiswaModel;

    public function __construct()
    {
        $this->validation = \Config\Services::validation();
        $this->email = \Config\Services::email();

        $this->UserModel = new UserModel();
        $this->MailsettingModel = new MailsettingModel();
        $this->UsertokenModel = new UsertokenModel();
        $this->KelasModel = new KelasModel();
        $this->UserkelasModel = new UserkelasModel();
        $this->StreamModel = new StreamModel();
        $this->FilestreamModel = new FilestreamModel();
        $this->KomenstreamModel = new KomenstreamModel();
        $this->KomenmateriModel = new KomenmateriModel();
        $this->KomentugasModel = new KomentugasModel();
        $this->MateriModel = new MateriModel();
        $this->TugasModel = new TugasModel();
        $this->UsertugasModel = new UsertugasModel();
        $this->UjianModel = new UjianModel();
        $this->PgdetailModel = new PgdetailModel();
        $this->EssaysiswaModel = new EssaysiswaModel();
        $this->EssaydetailModel = new EssaydetailModel();
        $this->PgsiswaModel = new PgsiswaModel();
        $this->PgwaktusiswaModel = new PgwaktusiswaModel();
        $this->EssaywaktusiswaModel = new EssaywaktusiswaModel();
        $this->Auth = new Auth();
        date_default_timezone_set('Asia/Jakarta');
    }
    public function hapus_kelas($data_kode_kelas = '')
    {
        $kode_kelas = decrypt_url($data_kode_kelas);

        $kelas = $this->KelasModel->getClassByCode($kode_kelas);

        // STREAM
        $streams = $this->StreamModel->asObject()->where(['kelas_id' => $kelas->id_kelas])->find();
        foreach ($streams as $stream) {
            $file_streams = $this->FilestreamModel->asObject()->where(['kode_stream' => $stream->stream_kode])->find();
            foreach ($file_streams as $file_stream) {
                unlink('assets/stream_file/' . $file_stream->nama_file);
            }

            $this->FilestreamModel
                ->where('kode_stream', $stream->stream_kode)
                ->delete();
        }
        $this->KomenstreamModel
            ->where('id_kelas', $kelas->id_kelas)
            ->delete();

        $this->StreamModel
            ->where('kelas_id', $kelas->id_kelas)
            ->delete();
        // STREAM

        // MATERI   
        $materials = $this->MateriModel->asObject()->where(['kelas_id' => $kelas->id_kelas])->find();
        foreach ($materials as $material) {
            $this->KomenmateriModel
                ->where('kode_materi', $material->materi_kode)
                ->delete();

            $file_streams = $this->FilestreamModel->asObject()->where(['kode_stream' => $material->materi_kode])->find();
            foreach ($file_streams as $file_stream) {
                unlink('assets/stream_file/' . $file_stream->nama_file);
            }
            $this->FilestreamModel
                ->where('kode_stream', $material->materi_kode)
                ->delete();
        }
        $this->MateriModel
            ->where('kelas_id', $kelas->id_kelas)
            ->delete();
        // MATERI

        // TUGAS
        $assignments = $this->TugasModel->getAllByClassCode($kelas->kode_kelas);
        foreach ($assignments as $assignment) {
            $this->KomentugasModel
                ->where('kode_tugas', $assignment->kode_tugas)
                ->delete();
            $file_streams = $this->FilestreamModel->asObject()->where(['kode_stream' => $assignment->kode_tugas])->find();
            foreach ($file_streams as $file_stream) {
                unlink('assets/stream_file/' . $file_stream->nama_file);
            }
            $this->FilestreamModel
                ->where('kode_stream', $assignment->kode_tugas)
                ->delete();
            $tugas_siswa = $this->UsertugasModel->getAllByKodeTugas($assignment->kode_tugas);
            foreach ($tugas_siswa as $ts) {
                $file_streams = $this->FilestreamModel->asObject()->where(['kode_stream' => $ts->kode_user_tugas])->find();
                foreach ($file_streams as $file_stream) {
                    unlink('assets/stream_file/' . $file_stream->nama_file);
                }
                $this->FilestreamModel
                    ->where('kode_stream', $ts->kode_user_tugas)
                    ->delete();
            }
            $this->UsertugasModel
                ->where('kode_tugas', $assignment->kode_tugas)
                ->delete();
        }
        $this->TugasModel
            ->where('kelas_kode', $kelas->kode_kelas)
            ->delete();
        // TUGAS

        // UJIAN
        $exams = $this->UjianModel
            ->where('kode_kelas', $kelas->kode_kelas)
            ->get()->getResultObject();

        if ($exams != null) {
            foreach ($exams as $exam) {
                if ($exam->jenis_ujian == 1) {
                    $this->PgwaktusiswaModel
                        ->where('kode_ujian', $exam->kode_ujian)
                        ->delete();
                    $this->PgsiswaModel
                        ->where('kode_ujian', $exam->kode_ujian)
                        ->delete();
                    $this->PgdetailModel
                        ->where('kode_ujian', $exam->kode_ujian)
                        ->delete();
                } else {
                    $this->EssaywaktusiswaModel
                        ->where('kode_ujian', $exam->kode_ujian)
                        ->delete();
                    $this->EssaysiswaModel
                        ->where('kode_ujian', $exam->kode_ujian)
                        ->delete();
                    $this->EssaydetailModel
                        ->where('kode_ujian', $exam->kode_ujian)
                        ->delete();
                }
            }
        }
        $this->UjianModel
            ->where('kode_kelas', $kode_kelas)
            ->delete();

        $this->UserkelasModel
            ->where('kelas_kode', $kode_kelas)
            ->delete();

        if ($kelas->bg_class != 'images/bg-class.png') {
            unlink('vendor/dist/' . $kelas->bg_class);
        }

        $this->KelasModel
            ->where('kode_kelas', $kode_kelas)
            ->delete();

        session()->setFlashdata(
            'pesan',
            '<script>
                Swal.fire(
                    "Berhasil",
                    "berhasil dihapus",
                    "success"
                )
            </script>'
        );
        return redirect()->to('user');
    }
    // START :: MATERIAL
    public function addmaterials($kode_kelas = '')
    {
        $kelas_kode = decrypt_url($kode_kelas);

        // CEK JIKA TIDAK ADA KODE KELAS
        if ($kelas_kode == '') {
            session()->setFlashdata(
                'pesan',
                '<script>
                    Swal.fire(
                        "Error",
                        "Somethings Wrong with class code",
                        "error"
                    )
                </script>'
            );
            return redirect()->to('user');
        }

        // CARI DATA KELAS BERDASARKAN KODE KELAS
        $kelas = $this->KelasModel->asObject()->where(['kode_kelas' => $kelas_kode])->first();
        // CEK APAKAH KELAS TIDAK DITEMUKAN
        if ($kelas == null) {
            session()->setFlashdata(
                'pesan',
                '<script>
                    Swal.fire(
                        "Error",
                        "Class not Found",
                        "error"
                    )
                </script>'
            );
            return redirect()->to('user');
        }

        // CEK APAKAH KELAS TERSEBUT ADALAH KELAS YANG DIA BUAT
        [ $is_pengajar, $kelas_saya ] = $this->Auth->isUserPengajar(session()->get('email'), $kelas_kode);

        if ($is_pengajar) {
            $data = [
                'breadcrumb1' => 'User',
                'breadcrumb2' => 'Materials &raquo; Add Materials &raquo; ' . $kelas_saya->mapel . ' ' . $kelas_saya->nama_kelas,
                'dashboard' => '',
                'clases' => 'menu--active',
                'materials' => 'menu--active',
                'topdashboard' => '',
                'topclases' => '',
                'topmaterials' => 'top-menu--active',
                'assignments' => '',
                'topassignment' => '',
                'topexam' => '',
                'topanggota' => '',
                'exam' => '',
                'profile' => '',
                'topprofile' => '',
                'data_kode_kelas' => $kelas_kode
            ];
            // End Menu
            $data['judul'] = 'Ruang Belajar By Belibis| Tambah Materials';
            $data['user'] = $this->UserModel->getuser(session()->get('email'));
            $data['myclass'] = $this->KelasModel->getMyClass(session()->get('email'));
            $data['classes'] = $this->UserkelasModel->getMyClass(session()->get('email'));
            $data['kelas'] = $kelas_saya;
            $data['list_materi'] = $this->MateriModel->getAllbyIdClass($kelas_saya->id_kelas);

            return view('super_user/tambah-materi', $data);
        }
    }
    public function addmaterials_($kode_kelas = '')
    {
        $user = $this->UserModel->getuser(session()->get('email'));
        // TANGKAP SEMUA DATA YANG DIKIRIM
        $data_materi = [
            'materi_kode' => $this->request->getVar('materi_code'),
            'kelas_id' => $this->request->getVar('kelas_id'),
            'email' => $user->email,
            'nama' => $user->nama,
            'gambar' => $user->gambar,
            'title' => $this->request->getVar('title'),
            'description' => $this->request->getVar('description'),
            'date_created' => time()
        ];
        // CEK APAKAH DI KELAS TERSEBUT SUDAH ADA SISWA YANG JOIN
        $siswa = $this->UserkelasModel->getAllbyClass(decrypt_url($kode_kelas));
        if ($siswa == null) {
            session()->setFlashdata(
                'pesan',
                "<script>
                    Swal.fire(
                    'Error',
                    'Di kelas ini belum ada siswa',
                    'error'
                    )
                </script>"
            );
            return redirect()->to('su/addmaterials/' . $kode_kelas)->withInput();
        }

        // SIAPKAN DATA SISWA UNTUK DIKIRIMKAN NOTIFIKASI EMAIL
        $data_siswa = '';
        foreach ($siswa as $s) {
            $data_siswa .= $s->email . ',';
        }

        // CEK APAKAH ADA FILE U=YANG DIPILIH
        $cek = $this->request->getFileMultiple('materi_file');
        if ($cek[0]->getError() != 4) {
            $data_file = [];

            // FUNGSI UPLOAD FILE
            foreach ($this->request->getFileMultiple('materi_file') as $file) {
                // Generate nama file Random
                $nama_file = str_replace(' ', '_', $file->getName());
                // Upload Gambar
                $file->move('assets/stream_file', $nama_file);

                array_push($data_file, [
                    'kode_stream' => $this->request->getVar('materi_code'),
                    'nama_file' => $nama_file
                ]);
            }
            $this->FilestreamModel->insertBatch($data_file);
        }

        // KIRIM NOTIFIKASI EMAIL
        $mail = $this->MailsettingModel->asObject()->first();
        $config['SMTPHost'] = $mail->smtp_host;
        $config['SMTPUser'] = $mail->smtp_user;
        $config['SMTPPass'] = $mail->smtp_password;
        $config['SMTPPort'] = $mail->smtp_port;
        $config['SMTPCrypto'] = $mail->smtp_crypto;

        $this->email->initialize($config);
        $this->email->setNewline("\r\n");
        $this->email->setFrom($mail->smtp_user, 'Ruang Belajar By Belibis');
        $this->email->setTo($data_siswa);
        $this->email->setSubject('Material');
        $this->email->setMessage('
            <div style="color: #000; padding: 10px;">
                <div
                    style="font-family: `Segoe UI`, Tahoma, Geneva, Verdana, sans-serif; font-size: 20px; color: #1C3FAA; font-weight: bold;">
                    Ruang Belajar</div>
                <small style="color: #000;">V 2.0 by Belibis</small>
                <br>
                <p style="font-family: `Segoe UI`, Tahoma, Geneva, Verdana, sans-serif; color: #000;">Hallo <br>
                    <span style="color: #000;">' . $user->nama . ' post new Materials</span></p>
                <table style="font-family: `Segoe UI`, Tahoma, Geneva, Verdana, sans-serif; color: #000;">
                    <tr>
                        <td>Material</td>
                        <td> : ' . $this->request->getVar('title') . '</td>
                    </tr>
                </table>
                <br>
                <a href="' . base_url() . '/auth"
                    style="display: inline-block; width: 100px; height: 30px; background: #1C3FAA; color: #fff; text-decoration: none; border-radius: 5px; text-align: center; line-height: 30px; font-family: `Segoe UI`, Tahoma, Geneva, Verdana, sans-serif;">
                    Sign In Now!
                </a>
            </div>
        
        ');

        // CEK APAKAH EMAIL SUDAH TERKIRIM
        if ($this->email->send()) {
            // JIKA EMAIL TERKIRIM
            // INSERT DATA
            $this->MateriModel->insert($data_materi);
            session()->setFlashdata(
                'pesan',
                "<script>
                    Swal.fire(
                    'Success..! ',
                    'Materi Berhasil Di Posting',
                    'success'
                    )
                </script>"
            );
            return redirect()->to('user/materials/' . $kode_kelas);
        } else {
            // JIKA EMAIL TIDAK TERKIRIM
            echo $this->email->printDebugger();
            die();
        }
    }
    public function showmaterial()
    {
        $id_materi = decrypt_url($this->request->getVar('data'));
        $kode_kelas = decrypt_url($this->request->getVar('code'));

        // CEK APAKAH KODE KELAS BENAR
        $kelas = $this->KelasModel->getClassByCode($kode_kelas);
        if ($kelas == null) {
            session()->setFlashdata(
                'pesan',
                "<script>
                    Swal.fire(
                    'Error',
                    'Wrong Class',
                    'error'
                    )
                </script>"
            );
            return redirect()->to('user');
        }

        //CEK APAKAH MATERI ADA 
        $materi = $this->MateriModel->asObject()->find($id_materi);
        if ($materi == null) {
            session()->setFlashdata(
                'pesan',
                "<script>
                    Swal.fire(
                    'Error',
                    'Tidak ada materi',
                    'error'
                    )
                </script>"
            );
            return redirect()->to('user/materials/' . $kode_kelas);
        }

        // DATA MENU
        $data = [
            'breadcrumb1' => 'User',
            'breadcrumb2' => 'Materials &raquo; Show Material',
            'dashboard' => '',
            'clases' => 'menu--active',
            'materials' => 'menu--active',
            'topdashboard' => '',
            'topclases' => '',
            'topmaterials' => 'top-menu--active',
            'topassignment' => '',
            'assignments' => '',
            'topexam' => '',
            'exam' => '',
            'profile' => '',
            'topprofile' => '',
            'data_kode_kelas' => $kode_kelas
        ];
        // END DATA MENU

        $data['judul'] = 'Ruang Belajar By Belibis| Detail Materials';
        $data['user'] = $this->UserModel->getuser(session()->get('email'));
        $data['myclass'] = $this->KelasModel->getMyClass(session()->get('email'));
        $data['classes'] = $this->UserkelasModel->getMyClass(session()->get('email'));
        $data['file_stream'] = $this->FilestreamModel->asObject()->findAll();
        $data['materi'] = $materi;


        return view('super_user/detail-materi', $data);
    }
    public function updatematerial()
    {
        $id_materi = decrypt_url($this->request->getVar('data'));
        $kode_kelas = decrypt_url($this->request->getVar('code'));

        // CEK APAKAH KODE KELAS BENAR
        $kelas = $this->KelasModel->getClassByCode($kode_kelas);
        if ($kelas == null) {
            session()->setFlashdata(
                'pesan',
                "<script>
                    Swal.fire(
                    'Error',
                    'Wrong Class',
                    'error'
                    )
                </script>"
            );
            return redirect()->to('user');
        }

        //CEK APAKAH MATERI ADA 
        $materi = $this->MateriModel->asObject()->find($id_materi);
        if ($materi == null) {
            session()->setFlashdata(
                'pesan',
                "<script>
                    Swal.fire(
                    'Error',
                    'Tidak ada materi',
                    'error'
                    )
                </script>"
            );
            return redirect()->to('user/materials/' . $kode_kelas);
        }

        // DATA MENU
        $data = [
            'breadcrumb1' => 'User',
            'breadcrumb2' => 'Materials &raquo; Edit Material',
            'dashboard' => '',
            'clases' => 'menu--active',
            'materials' => 'menu--active',
            'topdashboard' => '',
            'topclases' => '',
            'topmaterials' => 'top-menu--active',
            'topassignment' => '',
            'assignments' => '',
            'topexam' => '',
            'exam' => '',
            'profile' => '',
            'topprofile' => '',
            'data_kode_kelas' => $kode_kelas
        ];
        // END DATA MENU

        $data['judul'] = 'Ruang Belajar By Belibis| Edit Materials';
        $data['user'] = $this->UserModel->getuser(session()->get('email'));
        $data['myclass'] = $this->KelasModel->getMyClass(session()->get('email'));
        $data['classes'] = $this->UserkelasModel->getMyClass(session()->get('email'));
        $data['file_stream'] = $this->FilestreamModel->asObject()->findAll();
        $data['materi'] = $materi;
        $data['kelas'] = $kelas;

        return view('super_user/edit-materi', $data);
    }
    public function updatematerial_($kode_kelas = '')
    {
        // CEK APAKAH ADA FILE U=YANG DIPILIH
        $cek = $this->request->getFileMultiple('materi_file');
        if ($cek[0]->getError() != 4) {
            $data_file = [];

            // FUNGSI UPLOAD FILE
            foreach ($this->request->getFileMultiple('materi_file') as $file) {
                // Generate nama file Random
                $nama_file = str_replace(' ', '_', $file->getName());
                // Upload Gambar
                $file->move('assets/stream_file', $nama_file);

                array_push($data_file, [
                    'kode_stream' => $this->request->getVar('materi_code'),
                    'nama_file' => $nama_file
                ]);
            }
            $this->FilestreamModel->insertBatch($data_file);
        }
        // EDIT DATA MATERI
        $this->MateriModel
            ->where('materi_kode', $this->request->getVar('materi_code'))
            ->set('title', $this->request->getVar('title'))
            ->set('description', $this->request->getVar('description'))
            ->set('date_updated', time())
            ->update();

        session()->setFlashdata(
            'pesan',
            "<script>
                Swal.fire(
                'Success..! ',
                'Materi Berhasil Di Update',
                'success'
                )
            </script>"
        );
        return redirect()->to('user/materials/' . $kode_kelas);
    }
    public function rfmaterial()
    {
        $id_file = $this->request->getVar('id_file');
        $file = $this->FilestreamModel->asObject()->find($id_file);
        unlink('assets/stream_file/' . $file->nama_file);
        $this->FilestreamModel->delete($id_file);
    }
    public function deletematerial()
    {
        $id_materi = decrypt_url($this->request->getVar('data'));
        $kode_kelas = decrypt_url($this->request->getVar('code'));

        // CEK APAKAH KODE KELAS BENAR
        $kelas = $this->KelasModel->getClassByCode($kode_kelas);
        if ($kelas == null) {
            session()->setFlashdata(
                'pesan',
                "<script>
                    Swal.fire(
                    'Error',
                    'Wrong Class',
                    'error'
                    )
                </script>"
            );
            return redirect()->to('user');
        }

        //CEK APAKAH MATERI ADA 
        $materi = $this->MateriModel->asObject()->find($id_materi);
        if ($materi == null) {
            session()->setFlashdata(
                'pesan',
                "<script>
                    Swal.fire(
                    'Error',
                    'Tidak ada materi',
                    'error'
                    )
                </script>"
            );
            return redirect()->to('user/materials/' . $kode_kelas);
        }

        // HAPUS BERKAS BERKAS MATERI
        $berkas_file = $this->FilestreamModel->asObject()->where('kode_stream', $materi->materi_kode)->find();
        foreach ($berkas_file as $berkas) {
            unlink('assets/stream_file/' . $berkas->nama_file);
        }

        // HAPUS DATA KOMENTAR
        $this->KomenmateriModel
            ->where('kode_materi', $materi->materi_kode)
            ->delete();

        // HAPUS DATA BERKAS
        $this->FilestreamModel
            ->where('kode_stream', $materi->materi_kode)
            ->delete();

        // HAPUS DATA MATERI
        $this->MateriModel->delete($id_materi);

        session()->setFlashdata(
            'pesan',
            "<script>
                Swal.fire(
                'Success..! ',
                'Materi Berhasil Di Hapus',
                'success'
                )
            </script>"
        );
        return redirect()->to('user/materials/' . encrypt_url($kode_kelas));
    }
    // END :: MATERIAL

    // START :: ASSIGNMENT
    public function addassignment($kode_kelas = '')
    {
        $kelas_kode = decrypt_url($kode_kelas);

        // CEK JIKA TIDAK ADA KODE KELAS
        if ($kelas_kode == '') {
            session()->setFlashdata(
                'pesan',
                '<script>
                    Swal.fire(
                        "Error",
                        "Somethings Wrong with class code",
                        "error"
                    )
                </script>'
            );
            return redirect()->to('user');
        }

        // CARI DATA KELAS BERDASARKAN KODE KELAS
        $kelas = $this->KelasModel->asObject()->where(['kode_kelas' => $kelas_kode])->first();
        // CEK APAKAH KELAS TIDAK DITEMUKAN
        if ($kelas == null) {
            session()->setFlashdata(
                'pesan',
                '<script>
                    Swal.fire(
                        "Error",
                        "Class not Found",
                        "error"
                    )
                </script>'
            );
            return redirect()->to('user');
        }

        // CEK APAKAH KELAS TERSEBUT ADALAH KELAS YANG DIA BUAT
        [ $is_pengajar, $kelas_saya ] = $this->Auth->isUserPengajar(session()->get('email'), $kelas_kode);

        if ($is_pengajar) {
            $data = [
                'breadcrumb1' => 'User',
                'breadcrumb2' => 'Assignments &raquo; Add Assignment &raquo; ' . $kelas_saya->mapel . ' ' . $kelas_saya->nama_kelas,
                'dashboard' => '',
                'clases' => '',
                'materials' => '',
                'topdashboard' => '',
                'topclases' => '',
                'topmaterials' => '',
                'assignments' => '',
                'topassignment' => 'top-menu--active',
                'topexam' => '',
                'topanggota' => '',
                'exam' => '',
                'profile' => '',
                'topprofile' => '',
                'data_kode_kelas' => $kelas_kode
            ];
            // End Menu
            $data['judul'] = 'Ruang Belajar By Belibis| Tambah Assignment';
            $data['user'] = $this->UserModel->getuser(session()->get('email'));
            $data['myclass'] = $this->KelasModel->getMyClass(session()->get('email'));
            $data['classes'] = $this->UserkelasModel->getMyClass(session()->get('email'));
            $data['kelas'] = $kelas_saya;

            return view('super_user/tambah-tugas', $data);
        }
    }
    public function addassignment_($kode_kelas = '')
    {
        $kelas_kode = decrypt_url($kode_kelas);
        $user = $this->UserModel->getuser(session()->get('email'));
        // CEK JIKA TIDAK ADA KODE KELAS
        if ($kelas_kode == '') {
            session()->setFlashdata(
                'pesan',
                '<script>
                    Swal.fire(
                        "Error",
                        "Somethings Wrong with class code",
                        "error"
                    )
                </script>'
            );
            return redirect()->to('user');
        }

        // CARI DATA KELAS BERDASARKAN KODE KELAS
        $kelas = $this->KelasModel->asObject()->where(['kode_kelas' => $kelas_kode])->first();
        // CEK APAKAH KELAS TIDAK DITEMUKAN
        if ($kelas == null) {
            session()->setFlashdata(
                'pesan',
                '<script>
                    Swal.fire(
                        "Error",
                        "Class not Found",
                        "error"
                    )
                </script>'
            );
            return redirect()->to('user');
        }

        // CEK APAKAH KELAS TERSEBUT ADALAH KELAS YANG DIA BUAT
        [ $is_pengajar, $kelas_saya ] = $this->Auth->isUserPengajar(session()->get('email'), $kelas_kode);

        if ($kelas_saya == null) {
            session()->setFlashdata(
                'pesan',
                '<script>
                    Swal.fire(
                        "Error",
                        "Not Your Class",
                        "error"
                    )
                </script>'
            );
            return redirect()->to('user/' . $kode_kelas);
        }

        // CEK APAKAH DI KELAS TERSEBUT SUDAH ADA SISWA YANG JOIN
        $siswa = $this->UserkelasModel->getAllbyClass(decrypt_url($kode_kelas));
        if ($siswa == null) {
            session()->setFlashdata(
                'pesan',
                "<script>
                    Swal.fire(
                    'Error',
                    'Di kelas ini belum ada siswa',
                    'error'
                    )
                </script>"
            );
            return redirect()->to('su/addassignment/' . $kode_kelas)->withInput();
        }

        // SIAPKAN DATA SISWA UNTUK DIKIRIMKAN NOTIFIKASI EMAIL
        $data_siswa = '';
        foreach ($siswa as $s) {
            $data_siswa .= $s->email . ',';
        }

        // CEK APAKAH ADA FILE YANG DIPILIH
        $cek = $this->request->getFileMultiple('assignment_file');
        if ($cek[0]->getError() != 4) {
            $data_file = [];
            $kode_stream = $this->request->getVar('assignment_code');
            // FUNGSI UPLOAD FILE
            foreach ($this->request->getFileMultiple('assignment_file') as $file) {
                // replace white spce
                
                $nama_file = str_replace(' ', '_', $file->getName());
                
                $arr_nama = explode('.', $nama_file);
                $nama_file = implode('.', array_slice($arr_nama, 0, -1));
                $ekstensi = $arr_nama[count($arr_nama) - 1];

                # cut file name
                $nama_file = substr($nama_file, 0, 128 - ((10+1) + (strlen($ekstensi) + 1)));
                $nama_file = $nama_file . '_' . $kode_stream . '.' . $ekstensi;

                // Upload Gambar
                $file->move('assets/stream_file', $nama_file);

                array_push($data_file, [
                    'kode_stream' => $kode_stream,
                    'nama_file' => $nama_file
                ]);
            }
            $this->FilestreamModel->insertBatch($data_file);
        }

        // KIRIM NOTIFIKASI EMAIL
        $mail = $this->MailsettingModel->asObject()->first();
        $config['SMTPHost'] = $mail->smtp_host;
        $config['SMTPUser'] = $mail->smtp_user;
        $config['SMTPPass'] = $mail->smtp_password;
        $config['SMTPPort'] = $mail->smtp_port;
        $config['SMTPCrypto'] = $mail->smtp_crypto;

        $this->email->initialize($config);
        $this->email->setNewline("\r\n");
        $this->email->setFrom($mail->smtp_user, 'Ruang Belajar By Belibis');
        $this->email->setTo($data_siswa);
        $this->email->setSubject('Assignment');
        $this->email->setMessage('
            <div style="color: #000; padding: 10px;">
                <div
                    style="font-family: `Segoe UI`, Tahoma, Geneva, Verdana, sans-serif; font-size: 20px; color: #1C3FAA; font-weight: bold;">
                    Ruang Belajar</div>
                <small style="color: #000;">V 2.0 by Belibis</small>
                <br>
                <p style="font-family: `Segoe UI`, Tahoma, Geneva, Verdana, sans-serif; color: #000;">Hallo para siswa <br>
                    <span style="color: #000;">' . $user->nama . ' post new Assignment</span></p>
                <table style="font-family: `Segoe UI`, Tahoma, Geneva, Verdana, sans-serif; color: #000;">
                    <tr>
                        <td>Assignment</td>
                        <td> : ' . $this->request->getVar('title') . '</td>
                    </tr>
                    <tr>
                        <td>Due Date</td>
                        <td> : ' . $this->request->getVar('date') . ' ' . $this->request->getVar('time') . '</td>
                    </tr>
                </table>
                <br>
                <p style="font-family: `Segoe UI`, Tahoma, Geneva, Verdana, sans-serif; color: #000;">Sign in now and do your work<br> Good Luck!</p>
                <br>
                <a href="' . base_url() . '/auth"
                    style="display: inline-block; width: 100px; height: 30px; background: #1C3FAA; color: #fff; text-decoration: none; border-radius: 5px; text-align: center; line-height: 30px; font-family: `Segoe UI`, Tahoma, Geneva, Verdana, sans-serif;">
                    Sign In Now!
                </a>
            </div>
        
        ');

        // CEK APAKAH EMAIL SUDAH TERKIRIM
        if ($this->email->send()) {
            // JIKA EMAIL TERKIRIM
            // INSERT DATA
            $this->TugasModel->save([
                'kode_tugas' => $this->request->getVar('assignment_code'),
                'kelas_kode' => $this->request->getVar('kelas_kode'),
                'nama' => $user->nama,
                'email' => $user->email,
                'gambar' => $user->gambar,
                'title' => $this->request->getVar('title'),
                'description' => $this->request->getVar('description'),
                'date_created' => time(),
                'due_date' => $this->request->getVar('date') . ' ' . $this->request->getVar('time')
            ]);
            session()->setFlashdata(
                'pesan',
                "<script>
                    Swal.fire(
                    'Success..! ',
                    'Tugas Berhasil Di Posting',
                    'success'
                    )
                </script>"
            );
            return redirect()->to('user/assignment/' . $kode_kelas);
        } else {
            // JIKA EMAIL TIDAK TERKIRIM
            echo $this->email->printDebugger();
            die();
        }
    }
    public function showassignment()
    {
        $id_tugas = decrypt_url($this->request->getVar('data'));
        $kode_kelas = decrypt_url($this->request->getVar('code'));

        // CEK APAKAH KODE KELAS BENAR
        $kelas = $this->KelasModel->getClassByCode($kode_kelas);
        if ($kelas == null) {
            session()->setFlashdata(
                'pesan',
                "<script>
                    Swal.fire(
                    'Error',
                    'Wrong Class',
                    'error'
                    )
                </script>"
            );
            return redirect()->to('user');
        }

        //CEK APAKAH TUGAS ADA 
        $tugas = $this->TugasModel->asObject()->find($id_tugas);
        if ($tugas == null) {
            session()->setFlashdata(
                'pesan',
                "<script>
                    Swal.fire(
                    'Error',
                    'Tidak ada tugas',
                    'error'
                    )
                </script>"
            );
            return redirect()->to('user/assignment/' . encrypt_url($kode_kelas));
        }

        // CEK APAKAH KELAS TERSEBUT ADALAH KELAS YANG DIA BUAT
        [ $is_pengajar, $kelas_saya ] = $this->Auth->isUserPengajar(session()->get('email'), $kode_kelas);

        if ($is_pengajar) {
            $data = [
                'breadcrumb1' => 'User',
                'breadcrumb2' => 'Assignments &raquo; Detail Assignment &raquo; ' . $kelas_saya->mapel . ' ' . $kelas_saya->nama_kelas,
                'dashboard' => '',
                'clases' => '',
                'materials' => '',
                'topdashboard' => '',
                'topclases' => '',
                'topmaterials' => '',
                'topanggota' => '',
                'assignments' => 'menu--active',
                'topassignment' => 'top-menu--active',
                'topexam' => '',
                'exam' => '',
                'profile' => '',
                'topprofile' => '',
                'data_kode_kelas' => $kode_kelas
            ];
            // End Menu
            $data['judul'] = 'Ruang Belajar By Belibis| Detail Assignment';
            $data['user'] = $this->UserModel->getuser(session()->get('email'));
            $data['myclass'] = $this->KelasModel->getMyClass(session()->get('email'));
            $data['classes'] = $this->UserkelasModel->getMyClass(session()->get('email'));
            $data['kelas'] = $kelas_saya;
            $data['tugas'] = $tugas;
            $data['student_work'] = $this->UsertugasModel->getAllByKodeTugas($tugas->kode_tugas);
            $data['file_stream'] = $this->FilestreamModel->asObject()->findAll();
            return view('super_user/detail-tugas', $data);
        }
    }
    public function updateassignment()
    {
        $id_tugas = decrypt_url($this->request->getVar('data'));
        $kode_kelas = decrypt_url($this->request->getVar('code'));

        // CEK APAKAH KODE KELAS BENAR
        $kelas = $this->KelasModel->getClassByCode($kode_kelas);
        if ($kelas == null) {
            session()->setFlashdata(
                'pesan',
                "<script>
                    Swal.fire(
                    'Error',
                    'Wrong Class',
                    'error'
                    )
                </script>"
            );
            return redirect()->to('user');
        }

        //CEK APAKAH TUGAS ADA 
        $tugas = $this->TugasModel->asObject()->find($id_tugas);
        if ($tugas == null) {
            session()->setFlashdata(
                'pesan',
                "<script>
                    Swal.fire(
                    'Error',
                    'Tidak ada tugas',
                    'error'
                    )
                </script>"
            );
            return redirect()->to('user/assignment/' . encrypt_url($kode_kelas));
        }

        // CEK APAKAH KELAS TERSEBUT ADALAH KELAS YANG DIA BUAT
        [ $is_pengajar, $kelas_saya ] = $this->Auth->isUserPengajar(session()->get('email'), $kode_kelas);

        if ($is_pengajar) {
            $data = [
                'breadcrumb1' => 'User',
                'breadcrumb2' => 'Assignments &raquo; Update Assignment &raquo; ' . $kelas_saya->mapel . ' ' . $kelas_saya->nama_kelas,
                'dashboard' => '',
                'clases' => '',
                'materials' => '',
                'topdashboard' => '',
                'topclases' => '',
                'topmaterials' => '',
                'assignments' => 'menu--active',
                'topassignment' => 'top-menu--active',
                'topexam' => '',
                'topanggota' => '',
                'exam' => '',
                'profile' => '',
                'topprofile' => '',
                'data_kode_kelas' => $kode_kelas
            ];
            // End Menu
            $data['judul'] = 'Ruang Belajar By Belibis| Update Assignment';
            $data['user'] = $this->UserModel->getuser(session()->get('email'));
            $data['myclass'] = $this->KelasModel->getMyClass(session()->get('email'));
            $data['classes'] = $this->UserkelasModel->getMyClass(session()->get('email'));
            $data['kelas'] = $kelas_saya;
            $data['tugas'] = $tugas;
            $data['student_work'] = $this->UsertugasModel->getAllByKodeTugas($tugas->kode_tugas);
            $data['file_stream'] = $this->FilestreamModel->asObject()->findAll();
            return view('super_user/edit-tugas', $data);
        }
    }
    public function updateassignment_($kode_kelas = '')
    {
        // CEK APAKAH ADA FILE U=YANG DIPILIH
        $cek = $this->request->getFileMultiple('assignment_file');
        if ($cek[0]->getError() != 4) {
            $data_file = [];
            $kode_stream = $this->request->getVar('assignment_code');
            // FUNGSI UPLOAD FILE
            foreach ($this->request->getFileMultiple('assignment_file') as $file) {
                // replace white spce
                
                $nama_file = str_replace(' ', '_', $file->getName());
                
                $arr_nama = explode('.', $nama_file);
                $nama_file = implode('.', array_slice($arr_nama, 0, -1));
                $ekstensi = $arr_nama[count($arr_nama) - 1];

                # cut file name
                $nama_file = substr($nama_file, 0, 128 - ((10+1) + (strlen($ekstensi) + 1)));
                $nama_file = $nama_file . '_' . $kode_stream . '.' . $ekstensi;

                // Upload Gambar
                $file->move('assets/stream_file', $nama_file);

                array_push($data_file, [
                    'kode_stream' => $kode_stream,
                    'nama_file' => $nama_file
                ]);
            }
            $this->FilestreamModel->insertBatch($data_file);
        }
        // EDIT DATA TUGAS
        $this->TugasModel
            ->where('kode_tugas', $this->request->getVar('assignment_code'))
            ->set('title', $this->request->getVar('title'))
            ->set('description', $this->request->getVar('description'))
            ->set('due_date', $this->request->getVar('date') . ' ' . $this->request->getVar('time'))
            ->set('date_updated', time())
            ->update();

        session()->setFlashdata(
            'pesan',
            "<script>
                Swal.fire(
                'Success..! ',
                'Tugas Berhasil Di Update',
                'success'
                )
            </script>"
        );
        return redirect()->to('user/assignment/' . $kode_kelas);
    }
    public function rftugas()
    {
        $id_file = $this->request->getVar('id_file');
        $file = $this->FilestreamModel->asObject()->find($id_file);
        unlink('assets/stream_file/' . $file->nama_file);
        $this->FilestreamModel->delete($id_file);
    }
    public function showanswer()
    {
        $id_tugas = $this->request->getVar('data');
        $kode_kelas = decrypt_url($this->request->getVar('code'));
        $email_siswa = decrypt_url($this->request->getVar('list'));

        // CEK APAKAH KODE KELAS BENAR
        $kelas = $this->KelasModel->getClassByCode($kode_kelas);
        if ($kelas == null) {
            session()->setFlashdata(
                'pesan',
                "<script>
                    Swal.fire(
                    'Error',
                    'Wrong Class',
                    'error'
                    )
                </script>"
            );
            return redirect()->to('user');
        }

        //CEK APAKAH TUGAS ADA 
        $tugas = $this->TugasModel->asObject()->find($id_tugas);
        if ($tugas == null) {
            session()->setFlashdata(
                'pesan',
                "<script>
                    Swal.fire(
                    'Error',
                    'Tidak ada tugas',
                    'error'
                    )
                </script>"
            );
            return redirect()->to('user/assignment/' . encrypt_url($kode_kelas));
        }

        // CEK APAKAH KELAS TERSEBUT ADALAH KELAS YANG DIA BUAT
        [ $is_pengajar, $kelas_saya ] = $this->Auth->isUserPengajar(session()->get('email'), $kode_kelas);

        if ($is_pengajar) {
            $data = [
                'breadcrumb1' => 'User',
                'breadcrumb2' => 'Assignments &raquo; Detail Assignment &raquo; ' . $kelas_saya->mapel . ' ' . $kelas_saya->nama_kelas,
                'dashboard' => '',
                'clases' => '',
                'materials' => '',
                'topdashboard' => '',
                'topclases' => '',
                'topmaterials' => '',
                'assignments' => 'menu--active',
                'topassignment' => 'top-menu--active',
                'topanggota' => '',
                'topexam' => '',
                'exam' => '',
                'profile' => '',
                'topprofile' => '',
                'data_kode_kelas' => $kode_kelas
            ];
            // End Menu
            $data['judul'] = 'Ruang Belajar By Belibis| Tugas Siswa';
            $data['user'] = $this->UserModel->getuser(session()->get('email'));
            $data['myclass'] = $this->KelasModel->getMyClass(session()->get('email'));
            $data['classes'] = $this->UserkelasModel->getMyClass(session()->get('email'));
            $data['kelas'] = $kelas_saya;
            $data['tugas'] = $tugas;
            $data['tugas_siswa'] = $this->UsertugasModel->getByKodeTugasAndEmail($tugas->kode_tugas, $email_siswa);
            $data['file_stream'] = $this->FilestreamModel->asObject()->findAll();
            // dd($data['tugas_siswa']);
            return view('super_user/lihat-tugas', $data);
        }
    }
    public function graded()
    {
        $id_tugas = $this->request->getVar('id_tugas');
        $kode_kelas = $this->request->getVar('data_kode_kelas');
        $email_siswa = $this->request->getVar('email');

        $this->UsertugasModel->save([
            'id_user_tugas' => $this->request->getVar('id_user_tugas'),
            'grade' => $this->request->getVar('value')
        ]);

        session()->setFlashdata(
            'pesan',
            "<script>
                Swal.fire(
                'Success..! ',
                'Tugas Berhasil Di Nilai',
                'success'
                )
            </script>"
        );
        return redirect()->to('su/showanswer?data=' . $id_tugas . '&code=' . $kode_kelas . '&list=' . $email_siswa);
    }
    public function deleteassignment()
    {
        $id_tugas = decrypt_url($this->request->getVar('data'));
        $kode_kelas = decrypt_url($this->request->getVar('code'));

        // CEK APAKAH KODE KELAS BENAR
        $kelas = $this->KelasModel->getClassByCode($kode_kelas);
        if ($kelas == null) {
            session()->setFlashdata(
                'pesan',
                "<script>
                    Swal.fire(
                    'Error',
                    'Wrong Class',
                    'error'
                    )
                </script>"
            );
            return redirect()->to('user');
        }

        //CEK APAKAH TUGAS ADA 
        $tugas = $this->TugasModel->asObject()->find($id_tugas);
        if ($tugas == null) {
            session()->setFlashdata(
                'pesan',
                "<script>
                    Swal.fire(
                    'Error',
                    'Tidak ada tugas',
                    'error'
                    )
                </script>"
            );
            return redirect()->to('user/assignment/' . encrypt_url($kode_kelas));
        }

        // CEK APAKAH KELAS TERSEBUT ADALAH KELAS YANG DIA BUAT
        [ $is_pengajar, $kelas_saya ] = $this->Auth->isUserPengajar(session()->get('email'), $kode_kelas);

        if ($is_pengajar) {
            // AMBIL DATA FILE
            $file_tugas = $this->FilestreamModel
                ->where('kode_stream', $tugas->kode_tugas)
                ->get()->getResultObject();
            if ($file_tugas != null) {
                foreach ($file_tugas as $file) {
                    unlink('assets/stream_file/' . $file->nama_file);
                }
            }

            // AMBIL DATA TUGAS SISWA
            $user_tugas = $this->UsertugasModel
                ->where('kode_tugas', $tugas->kode_tugas)
                ->get()->getResultObject();

            if ($user_tugas != null) {
                foreach ($user_tugas as $ut) {
                    $file_siswa = $this->FilestreamModel
                        ->where('kode_stream', $ut->kode_user_tugas)
                        ->get()->getResultObject();
                    if ($file_siswa != null) {
                        foreach ($file_siswa as $fs) {
                            unlink('assets/stream_file/' . $fs->nama_file);
                            $this->FilestreamModel
                                ->where('kode_stream', $fs->kode_stream)
                                ->delete();
                        }
                    }
                }
            }

            // HAPUS DATA KOMENTAR TUGAS
            $this->KomentugasModel
                ->where('kode_tugas', $tugas->kode_tugas)
                ->delete();

            // Hapus Data File
            $this->FilestreamModel
                ->where('kode_stream', $tugas->kode_tugas)
                ->delete();

            // HAPUS DATA TUGAS USER
            $this->UsertugasModel
                ->where('kode_tugas', $tugas->kode_tugas)
                ->delete();

            // HAPUS DATA TUGAS
            $this->TugasModel->delete($id_tugas);

            session()->setFlashdata(
                'pesan',
                "<script>
                    Swal.fire(
                    'Berhasil',
                    'Tugas Dihapus',
                    'success'
                    )
                </script>"
            );
            return redirect()->to('user/assignment/' . encrypt_url($kode_kelas));
        }
    }
    // END :: ASSIGNMENT

    // START :: EXAM
    // ==> PILIHAN GANDA
    public function addpg($kode_kelas = '')
    {
        $kelas_kode = decrypt_url($kode_kelas);

        // CEK JIKA TIDAK ADA KODE KELAS
        if ($kelas_kode == '') {
            session()->setFlashdata(
                'pesan',
                '<script>
                    Swal.fire(
                        "Error",
                        "Somethings Wrong with class code",
                        "error"
                    )
                </script>'
            );
            return redirect()->to('user');
        }

        // CARI DATA KELAS BERDASARKAN KODE KELAS
        $kelas = $this->KelasModel->asObject()->where(['kode_kelas' => $kelas_kode])->first();
        // CEK APAKAH KELAS TIDAK DITEMUKAN
        if ($kelas == null) {
            session()->setFlashdata(
                'pesan',
                '<script>
                    Swal.fire(
                        "Error",
                        "Class not Found",
                        "error"
                    )
                </script>'
            );
            return redirect()->to('user');
        }

        // CEK APAKAH KELAS TERSEBUT ADALAH KELAS YANG DIA BUAT
        [ $is_pengajar, $kelas_saya ] = $this->Auth->isUserPengajar(session()->get('email'), $kelas_kode);

        if ($is_pengajar) {
            $data = [
                'breadcrumb1' => 'User',
                'breadcrumb2' => 'Exam &raquo; Add Exam &raquo;' . $kelas_saya->mapel . ' ' . $kelas_saya->nama_kelas,
                'dashboard' => '',
                'clases' => '',
                'materials' => '',
                'topdashboard' => '',
                'topclases' => '',
                'topmaterials' => '',
                'assignments' => '',
                'topassignment' => '',
                'topexam' => 'top-menu--active',
                'topanggota' => '',
                'exam' => 'menu--active',
                'profile' => '',
                'topprofile' => '',
                'data_kode_kelas' => $kelas_kode
            ];
            // End Menu
            $data['judul'] = 'Ruang Belajar By Belibis| Add Exam';
            $data['user'] = $this->UserModel->getuser(session()->get('email'));
            $data['myclass'] = $this->KelasModel->getMyClass(session()->get('email'));
            $data['classes'] = $this->UserkelasModel->getMyClass(session()->get('email'));
            $data['kelas'] = $kelas_saya;
            return view('super_user/tambah-ujian-pg', $data);
        }
    }
    public function addpg_($kode_kelas = '')
    {
        $kelas_kode = decrypt_url($kode_kelas);
        $user = $this->UserModel->getuser(session()->get('email'));

        // CEK JIKA TIDAK ADA KODE KELAS
        if ($kelas_kode == '') {
            session()->setFlashdata(
                'pesan',
                '<script>
                    Swal.fire(
                        "Error",
                        "Somethings Wrong with class code",
                        "error"
                    )
                </script>'
            );
            return redirect()->to('user');
        }

        // CARI DATA KELAS BERDASARKAN KODE KELAS
        $kelas = $this->KelasModel->asObject()->where(['kode_kelas' => $kelas_kode])->first();
        // CEK APAKAH KELAS TIDAK DITEMUKAN
        if ($kelas == null) {
            session()->setFlashdata(
                'pesan',
                '<script>
                    Swal.fire(
                        "Error",
                        "Class not Found",
                        "error"
                    )
                </script>'
            );
            return redirect()->to('user');
        }

        // CEK APAKAH DI KELAS TERSEBUT SUDAH ADA SISWA YANG JOIN
        $siswa = $this->UserkelasModel->getAllbyClass($kelas_kode);
        if ($siswa == null) {
            session()->setFlashdata(
                'pesan',
                "<script>
                    Swal.fire(
                    'Error',
                    'Di kelas ini belum ada siswa',
                    'error'
                    )
                </script>"
            );
            return redirect()->to('su/addpg/' . $kode_kelas)->withInput();
        }

        // SIAPKAN DATA SISWA UNTUK DIKIRIMKAN NOTIFIKASI EMAIL
        $data_siswa = '';
        foreach ($siswa as $s) {
            $data_siswa .= $s->email . ',';
        }

        // CEK APAKAH KELAS TERSEBUT ADALAH KELAS YANG DIA BUAT
        [ $is_pengajar, $kelas_saya ] = $this->Auth->isUserPengajar(session()->get('email'), $kelas_kode);

        if ($is_pengajar) {
            // DATA UJIAN
            $kode_ujian = random_string('alnum', 10);
            $data_ujian = [
                'kode_ujian' => $kode_ujian,
                'kode_kelas' => $kelas_kode,
                'nama_ujian' => $this->request->getVar('nama_ujian'),
                'tanggal_dibuat' => time(),
                'waktu_jam' => $this->request->getVar('waktu_jam'),
                'waktu_menit' => $this->request->getVar('waktu_menit'),
                'jenis_ujian' => 1 //jenis ujian berisi angka 1 dan 2, jika 1 berarti ujian pilihan ganda, jika 2 berari essay
            ];
            // END DATA UJIAN

            // DATA DETAIL UJIAN PG
            $nama_soal = $this->request->getVar('nama_soal');
            $data_detail_ujian = array();
            $index = 0;
            foreach ($nama_soal as $nama) {
                array_push($data_detail_ujian, array(
                    'kode_ujian' => $kode_ujian,
                    'soal' => $nama,
                    'pg_a' => 'A. ' . $this->request->getVar('pg_a')[$index],
                    'pg_b' => 'B. ' . $this->request->getVar('pg_b')[$index],
                    'pg_c' => 'C. ' . $this->request->getVar('pg_c')[$index],
                    'pg_d' => 'D. ' . $this->request->getVar('pg_d')[$index],
                    'pg_e' => 'E. ' . $this->request->getVar('pg_e')[$index],
                    'jawaban' => $this->request->getVar('jawaban')[$index],
                ));

                $index++;
            }
            // END DATA DETAIL UJIAN PG
            // var_dump($data_ujian);
            // echo "<pre>";
            // var_dump($data_detail_ujian);
            // die;

            // KIRIM NOTIFIKASI EMAIL
            $mail = $this->MailsettingModel->asObject()->first();
            $config['SMTPHost'] = $mail->smtp_host;
            $config['SMTPUser'] = $mail->smtp_user;
            $config['SMTPPass'] = $mail->smtp_password;
            $config['SMTPPort'] = $mail->smtp_port;
            $config['SMTPCrypto'] = $mail->smtp_crypto;

            $this->email->initialize($config);
            $this->email->setNewline("\r\n");
            $this->email->setFrom($mail->smtp_user, 'Ruang Belajar By Belibis');
            $this->email->setTo($data_siswa);
            $this->email->setSubject('Exam');
            $this->email->setMessage('
                <div style="color: #000; padding: 10px;">
                    <div
                        style="font-family: `Segoe UI`, Tahoma, Geneva, Verdana, sans-serif; font-size: 20px; color: #1C3FAA; font-weight: bold;">
                        Ruang Belajar</div>
                    <small style="color: #000;">V 2.0 by Belibis</small>
                    <br>
                    <p style="font-family: `Segoe UI`, Tahoma, Geneva, Verdana, sans-serif; color: #000;">Hallo para siswa <br>
                        <span style="color: #000;">' . $user->nama . ' post new Exam</span></p>
                    <table style="font-family: `Segoe UI`, Tahoma, Geneva, Verdana, sans-serif; color: #000;">
                            <tr>
                                <td>Exam</td>
                                <td> : ' . $this->request->getVar('nama_ujian') . '</td>
                            </tr>
                            <tr>
                                <td>Waktu</td>
                                <td> : ' . $this->request->getVar('waktu_jam') . ' JAM ' . $this->request->getVar('waktu_menit') . ' MENIT</td>
                            </tr>
                        </table>
                        <br>
                        <p style="font-family: `Segoe UI`, Tahoma, Geneva, Verdana, sans-serif; color: #000;">Sign in now and do your exam<br> Good Luck!</p>
                        <br>
                    <a href="' . base_url() . '/auth"
                        style="display: inline-block; width: 100px; height: 30px; background: #1C3FAA; color: #fff; text-decoration: none; border-radius: 5px; text-align: center; line-height: 30px; font-family: `Segoe UI`, Tahoma, Geneva, Verdana, sans-serif;">
                        Sign In Now!
                    </a>
                </div>
        
            ');

            // CEK APAKAH EMAIL SUDAH TERKIRIM
            if ($this->email->send()) {
                // JIKA EMAIL TERKIRIM
                // INSERT DATA UJIAN
                $this->UjianModel->save($data_ujian);
                // INSERT DATA DETAIL UJIAN
                $this->PgdetailModel->insertBatch($data_detail_ujian);

                session()->setFlashdata(
                    'pesan',
                    "<script>
                    Swal.fire(
                    'Success..! ',
                    'Ujian Berhasil Di Posting',
                    'success'
                    )
                </script>"
                );
                return redirect()->to('user/ujian/' . $kode_kelas);
            } else {
                // JIKA EMAIL TIDAK TERKIRIM
                echo $this->email->printDebugger();
                die();
            }
        }
    }
    public function pg_excel($kode_kelas = '')
    {
        $kelas_kode = decrypt_url($kode_kelas);
        $user = $this->UserModel->getuser(session()->get('email'));

        // CEK JIKA TIDAK ADA KODE KELAS
        if ($kelas_kode == '') {
            session()->setFlashdata(
                'pesan',
                '<script>
                    Swal.fire(
                        "Error",
                        "Somethings Wrong with class code",
                        "error"
                    )
                </script>'
            );
            return redirect()->to('user');
        }

        // CARI DATA KELAS BERDASARKAN KODE KELAS
        $kelas = $this->KelasModel->asObject()->where(['kode_kelas' => $kelas_kode])->first();
        // CEK APAKAH KELAS TIDAK DITEMUKAN
        if ($kelas == null) {
            session()->setFlashdata(
                'pesan',
                '<script>
                    Swal.fire(
                        "Error",
                        "Class not Found",
                        "error"
                    )
                </script>'
            );
            return redirect()->to('user');
        }

        // CEK APAKAH DI KELAS TERSEBUT SUDAH ADA SISWA YANG JOIN
        $siswa = $this->UserkelasModel->getAllbyClass($kelas_kode);
        if ($siswa == null) {
            session()->setFlashdata(
                'pesan',
                "<script>
                    Swal.fire(
                    'Error',
                    'Di kelas ini belum ada siswa',
                    'error'
                    )
                </script>"
            );
            return redirect()->to('su/addpg/' . $kode_kelas)->withInput();
        }

        // SIAPKAN DATA SISWA UNTUK DIKIRIMKAN NOTIFIKASI EMAIL
        $data_siswa = '';
        foreach ($siswa as $s) {
            $data_siswa .= $s->email . ',';
        }

        // CEK APAKAH KELAS TERSEBUT ADALAH KELAS YANG DIA BUAT
        [ $is_pengajar, $kelas_saya ] = $this->Auth->isUserPengajar(session()->get('email'), $kelas_kode);

        if ($is_pengajar) {
            // DATA UJIAN
            $kode_ujian = random_string('alnum', 10);
            $data_ujian = [
                'kode_ujian' => $kode_ujian,
                'kode_kelas' => $kelas_kode,
                'nama_ujian' => $this->request->getVar('e_nama_ujian'),
                'tanggal_dibuat' => time(),
                'waktu_jam' => $this->request->getVar('e_waktu_jam'),
                'waktu_menit' => $this->request->getVar('e_waktu_menit'),
                'jenis_ujian' => 1 //jenis ujian berisi angka 1 dan 2, jika 1 berarti ujian pilihan ganda, jika 2 berari essay
            ];
            // END DATA UJIAN

            // SIAPKAN DATA DETAIL UJIAN PG BERUPA ARRAY KOSONG
            $data_detail_ujian = array();
            // TANGKAP FILE EXCEL YANG DI UPLLOAD
            $file = $this->request->getFile('file_excel');
            // AMBIL EXTENSI EXCEL YANG DI UPLOAD
            $ekstensi = $file->getClientExtension();

            // JIKA EKSTENSINYA XLS BERARTI FORMAT EXCEL VERSI LAMA
            if ($ekstensi == 'xls') {
                $reader = new Xls();
            }
            // JIKA EKSTENSINYA XLSX BERARTI FORMAT EXCEL VERSI BARU
            if ($ekstensi == 'xlsx') {
                $reader = new Xlsx();
            }
            /** Load $inputFileName to a Spreadsheet Object  **/
            $spreadsheet = $reader->load($file);
            // SIMPAN DATA EXCEL KEDALAM VARIABLE $data DAN UBAH MENJADI ARRAY
            $data = $spreadsheet->getActiveSheet()->toArray();
            // LOOPING DATA EXCEL
            foreach ($data as $baris => $kolom) {
                // KARENA DI DALAM EXCELNYA MEMILIKI HEADER / JUDUL (contoh : nama | kelas | email)
                // MAKA SKIP BAGIAN JUDUL / BARIS PERTAMA
                if ($baris != 0) {
                    // AMBDIL DATA DARI BARIS KEDUA DAN MENYIMPANNYA KEDALAM VARIABEL $data_detail_ujian
                    if ($kolom[0] != null) {
                        array_push($data_detail_ujian, array(
                            'kode_ujian' => $kode_ujian,
                            'soal' => $kolom[0],
                            'pg_a' => 'A. ' . $kolom[1],
                            'pg_b' => 'B. ' . $kolom[2],
                            'pg_c' => 'C. ' . $kolom[3],
                            'pg_d' => 'D. ' . $kolom[4],
                            'pg_e' => 'E. ' . $kolom[5],
                            'jawaban' => $kolom[6],
                        ));
                    }
                }
            }
            // var_dump($data_ujian);
            // echo "<pre>";
            // var_dump($data_detail_ujian);
            // die;

            // KIRIM NOTIFIKASI EMAIL
            $mail = $this->MailsettingModel->asObject()->first();
            $config['SMTPHost'] = $mail->smtp_host;
            $config['SMTPUser'] = $mail->smtp_user;
            $config['SMTPPass'] = $mail->smtp_password;
            $config['SMTPPort'] = $mail->smtp_port;
            $config['SMTPCrypto'] = $mail->smtp_crypto;

            $this->email->initialize($config);
            $this->email->setNewline("\r\n");
            $this->email->setFrom($mail->smtp_user, 'Ruang Belajar By Belibis');
            $this->email->setTo($data_siswa);
            $this->email->setSubject('Exam');
            $this->email->setMessage('
                <div style="color: #000; padding: 10px;">
                    <div
                        style="font-family: `Segoe UI`, Tahoma, Geneva, Verdana, sans-serif; font-size: 20px; color: #1C3FAA; font-weight: bold;">
                        Ruang Belajar</div>
                    <small style="color: #000;">V 2.0 by Belibis</small>
                    <br>
                    <p style="font-family: `Segoe UI`, Tahoma, Geneva, Verdana, sans-serif; color: #000;">Hallo para siswa <br>
                        <span style="color: #000;">' . $user->nama . ' post new Exam</span></p>
                    <table style="font-family: `Segoe UI`, Tahoma, Geneva, Verdana, sans-serif; color: #000;">
                            <tr>
                                <td>Exam</td>
                                <td> : ' . $this->request->getVar('e_nama_ujian') . '</td>
                            </tr>
                            <tr>
                                <td>Waktu</td>
                                <td> : ' . $this->request->getVar('e_waktu_jam') . ' JAM ' . $this->request->getVar('e_waktu_menit') . ' MENIT</td>
                            </tr>
                        </table>
                        <br>
                        <p style="font-family: `Segoe UI`, Tahoma, Geneva, Verdana, sans-serif; color: #000;">Sign in now and do your exam<br> Good Luck!</p>
                        <br>
                    <a href="' . base_url() . '/auth"
                        style="display: inline-block; width: 100px; height: 30px; background: #1C3FAA; color: #fff; text-decoration: none; border-radius: 5px; text-align: center; line-height: 30px; font-family: `Segoe UI`, Tahoma, Geneva, Verdana, sans-serif;">
                        Sign In Now!
                    </a>
                </div>
        
            ');

            // CEK APAKAH EMAIL SUDAH TERKIRIM
            if ($this->email->send()) {
                // JIKA EMAIL TERKIRIM
                // INSERT DATA UJIAN
                $this->UjianModel->save($data_ujian);
                // INSERT DATA DETAIL UJIAN
                $this->PgdetailModel->insertBatch($data_detail_ujian);

                session()->setFlashdata(
                    'pesan',
                    "<script>
                    Swal.fire(
                    'Success..! ',
                    'Tugas Berhasil Di Posting',
                    'success'
                    )
                </script>"
                );
                return redirect()->to('user/ujian/' . $kode_kelas);
            } else {
                // JIKA EMAIL TIDAK TERKIRIM
                echo $this->email->printDebugger();
                die();
            }
        }
    }
    public function showpg()
    {
        $kode_ujian = decrypt_url($this->request->getVar('data'));
        $kode_kelas = decrypt_url($this->request->getVar('kelas'));

        // CEK JIKA TIDAK ADA KODE KELAS
        if ($kode_kelas == '') {
            session()->setFlashdata(
                'pesan',
                '<script>
                    Swal.fire(
                        "Error",
                        "Somethings Wrong with class code",
                        "error"
                    )
                </script>'
            );
            return redirect()->to('user');
        }

        // CARI DATA KELAS BERDASARKAN KODE KELAS
        $kelas = $this->KelasModel->asObject()->where(['kode_kelas' => $kode_kelas])->first();
        // CEK APAKAH KELAS TIDAK DITEMUKAN
        if ($kelas == null) {
            session()->setFlashdata(
                'pesan',
                '<script>
                    Swal.fire(
                        "Error",
                        "Class not Found",
                        "error"
                    )
                </script>'
            );
            return redirect()->to('user');
        }

        // CARI DATA UJIAN BERDASARKAN KODE UJIAN
        $ujian = $this->UjianModel->asObject()->where(['kode_ujian' => $kode_ujian])->first();
        // CEK APAKAH DATA UJIAN DITEMUKAN
        if ($ujian == null) {
            session()->setFlashdata(
                'pesan',
                '<script>
                    Swal.fire(
                        "Error",
                        "Exam not Found",
                        "error"
                    )
                </script>'
            );
            return redirect()->to('user/ujian/' . encrypt_url($kode_kelas));
        }

        // CEK APAKAH KELAS TERSEBUT ADALAH KELAS YANG DIA BUAT
        [ $is_pengajar, $kelas_saya ] = $this->Auth->isUserPengajar(session()->get('email'), $kelas_kode);

        if ($is_pengajar) {
            $data = [
                'breadcrumb1' => 'User',
                'breadcrumb2' => 'Exam &raquo; Detail Exam &raquo;' . $kelas_saya->mapel . ' ' . $kelas_saya->nama_kelas,
                'dashboard' => '',
                'clases' => '',
                'materials' => '',
                'topdashboard' => '',
                'topclases' => '',
                'topmaterials' => '',
                'assignments' => '',
                'topassignment' => '',
                'topexam' => 'top-menu--active',
                'exam' => 'menu--active',
                'profile' => '',
                'topprofile' => '',
                'data_kode_kelas' => $kode_kelas
            ];
            // End Menu
            $data['judul'] = 'Ruang Belajar By Belibis| Detail Exam';
            $data['user'] = $this->UserModel->getuser(session()->get('email'));
            $data['myclass'] = $this->KelasModel->getMyClass(session()->get('email'));
            $data['classes'] = $this->UserkelasModel->getMyClass(session()->get('email'));
            $data['kelas'] = $kelas_saya;
            $data['ujian'] = $ujian;
            $data['detail_ujian'] = $this->PgdetailModel->getAllByExamCode($kode_ujian);
            $data['siswa_saya'] = $this->UserkelasModel->asObject()->where(['kelas_kode' => $kode_kelas])->find();
            $data['waktu_siswa'] = $this->PgwaktusiswaModel->asObject()->where(['kode_ujian' => $kode_ujian])->find();

            // CARI DATA SISWA YANG BELUM MENGERJAKAN UJIAN
            $db = \Config\Database::connect();
            $query = $db->query("SELECT * FROM user_kelas WHERE email NOT IN ( SELECT siswa FROM pg_waktu_siswa WHERE kode_ujian = '$kode_ujian') ");
            $data['belum_mengerjakan'] = $query->getResultObject();
            // dd($data['waktu_siswa']);

            return view('super_user/detail-ujian-pg', $data);
        }
    }
    public function pg_siswa()
    {
        $kode_ujian = decrypt_url($this->request->getVar('data'));
        $kode_kelas = decrypt_url($this->request->getVar('kelas'));
        $email_siswa = decrypt_url($this->request->getVar('siswa'));

        // CEK JIKA TIDAK ADA KODE KELAS
        if ($kode_kelas == '') {
            session()->setFlashdata(
                'pesan',
                '<script>
                    Swal.fire(
                        "Error",
                        "Somethings Wrong with class code",
                        "error"
                    )
                </script>'
            );
            return redirect()->to('user');
        }

        // CARI DATA KELAS BERDASARKAN KODE KELAS
        $kelas = $this->KelasModel->asObject()->where(['kode_kelas' => $kode_kelas])->first();
        // CEK APAKAH KELAS TIDAK DITEMUKAN
        if ($kelas == null) {
            session()->setFlashdata(
                'pesan',
                '<script>
                    Swal.fire(
                        "Error",
                        "Class not Found",
                        "error"
                    )
                </script>'
            );
            return redirect()->to('user');
        }

        // CARI DATA UJIAN BERDASARKAN KODE UJIAN
        $ujian = $this->UjianModel->asObject()->where(['kode_ujian' => $kode_ujian])->first();
        // CEK APAKAH DATA UJIAN DITEMUKAN
        if ($ujian == null) {
            session()->setFlashdata(
                'pesan',
                '<script>
                    Swal.fire(
                        "Error",
                        "Exam not Found",
                        "error"
                    )
                </script>'
            );
            return redirect()->to('user/ujian/' . encrypt_url($kode_kelas));
        }

        // CEK APAKAH KELAS TERSEBUT ADALAH KELAS YANG DIA BUAT
        [ $is_pengajar, $kelas_saya ] = $this->Auth->isUserPengajar(session()->get('email'), $kelas_kode);

        if ($is_pengajar) {
            $data = [
                'breadcrumb1' => 'User',
                'breadcrumb2' => 'Exam &raquo; Detail Exam &raquo;' . $kelas_saya->mapel . ' ' . $kelas_saya->nama_kelas,
                'dashboard' => '',
                'clases' => '',
                'materials' => '',
                'topdashboard' => '',
                'topclases' => '',
                'topmaterials' => '',
                'assignments' => '',
                'topassignment' => '',
                'topexam' => 'top-menu--active',
                'exam' => 'menu--active',
                'profile' => '',
                'topprofile' => '',
                'data_kode_kelas' => $kode_kelas
            ];
            // End Menu
            $data['judul'] = 'Ruang Belajar By Belibis| Jawaban Siswa';
            $data['user'] = $this->UserModel->getuser(session()->get('email'));
            $data['myclass'] = $this->KelasModel->getMyClass(session()->get('email'));
            $data['classes'] = $this->UserkelasModel->getMyClass(session()->get('email'));
            $data['kelas'] = $kelas_saya;
            $data['ujian'] = $ujian;
            $data['detail_ujian'] = $this->PgdetailModel->getAllByExamCode($kode_ujian);
            $data['siswa'] = $this->UserModel->getuser($email_siswa);

            return view('super_user/detail-ujian-pg-siswa', $data);
        }
    }
    public function deleteujianpg()
    {
        $kode_ujian = decrypt_url($this->request->getVar('ujian'));
        $kode_kelas = decrypt_url($this->request->getVar('kelas'));

        // CEK JIKA TIDAK ADA KODE KELAS
        if ($kode_kelas == '') {
            session()->setFlashdata(
                'pesan',
                '<script>
                    Swal.fire(
                        "Error",
                        "Somethings Wrong with class code",
                        "error"
                    )
                </script>'
            );
            return redirect()->to('user');
        }

        // CARI DATA KELAS BERDASARKAN KODE KELAS
        $kelas = $this->KelasModel->asObject()->where(['kode_kelas' => $kode_kelas])->first();
        // CEK APAKAH KELAS TIDAK DITEMUKAN
        if ($kelas == null) {
            session()->setFlashdata(
                'pesan',
                '<script>
                    Swal.fire(
                        "Error",
                        "Class not Found",
                        "error"
                    )
                </script>'
            );
            return redirect()->to('user');
        }

        // CARI DATA UJIAN BERDASARKAN KODE UJIAN
        $ujian = $this->UjianModel->asObject()->where(['kode_ujian' => $kode_ujian])->first();
        // CEK APAKAH DATA UJIAN DITEMUKAN
        if ($ujian == null) {
            session()->setFlashdata(
                'pesan',
                '<script>
                    Swal.fire(
                        "Error",
                        "Exam not Found",
                        "error"
                    )
                </script>'
            );
            return redirect()->to('user/ujian/' . encrypt_url($kode_kelas));
        }

        // CEK APAKAH KELAS TERSEBUT ADALAH KELAS YANG DIA BUAT
        [ $is_pengajar, $kelas_saya ] = $this->Auth->isUserPengajar(session()->get('email'), $kelas_kode);

        if ($is_pengajar) {

            // HAPUS DATA PG WAKTU SISWA
            $this->PgwaktusiswaModel
                ->where('kode_ujian', $kode_ujian)
                ->delete();

            // HAPUS DATAJAWABAN SISWA
            $this->PgsiswaModel
                ->where('kode_ujian', $kode_ujian)
                ->delete();

            // HAPUS DATA DETAIL UJIAN
            $this->PgdetailModel
                ->where('kode_ujian', $kode_ujian)
                ->delete();

            // HAPUS DATA UJIAN
            $this->UjianModel
                ->where('kode_ujian', $kode_ujian)
                ->delete();

            session()->setFlashdata(
                'pesan',
                "<script>
                    Swal.fire(
                    'Success..! ',
                    'Ujian Berhasil Di Hapus',
                    'success'
                    )
                </script>"
            );
            return redirect()->to('user/ujian/' . encrypt_url($kode_kelas));
        }
    }
    // ==> PILIHAN GANDA

    // ==> ESSAY
    public function addessay($kode_kelas = '')
    {
        $kelas_kode = decrypt_url($kode_kelas);

        // CEK JIKA TIDAK ADA KODE KELAS
        if ($kelas_kode == '') {
            session()->setFlashdata(
                'pesan',
                '<script>
                    Swal.fire(
                        "Error",
                        "Somethings Wrong with class code",
                        "error"
                    )
                </script>'
            );
            return redirect()->to('user');
        }

        // CARI DATA KELAS BERDASARKAN KODE KELAS
        $kelas = $this->KelasModel->asObject()->where(['kode_kelas' => $kelas_kode])->first();
        // CEK APAKAH KELAS TIDAK DITEMUKAN
        if ($kelas == null) {
            session()->setFlashdata(
                'pesan',
                '<script>
                    Swal.fire(
                        "Error",
                        "Class not Found",
                        "error"
                    )
                </script>'
            );
            return redirect()->to('user');
        }

        // CEK APAKAH KELAS TERSEBUT ADALAH KELAS YANG DIA BUAT
        [ $is_pengajar, $kelas_saya ] = $this->Auth->isUserPengajar(session()->get('email'), $kelas_kode);

        if ($is_pengajar) {
            $data = [
                'breadcrumb1' => 'User',
                'breadcrumb2' => 'Exam &raquo; Add Exam &raquo;' . $kelas_saya->mapel . ' ' . $kelas_saya->nama_kelas,
                'dashboard' => '',
                'clases' => '',
                'materials' => '',
                'topdashboard' => '',
                'topclases' => '',
                'topmaterials' => '',
                'assignments' => '',
                'topassignment' => '',
                'topexam' => 'top-menu--active',
                'topanggota' => '',
                'exam' => 'menu--active',
                'profile' => '',
                'topprofile' => '',
                'data_kode_kelas' => $kelas_kode
            ];
            // End Menu
            $data['judul'] = 'Ruang Belajar By Belibis| Add Exam';
            $data['user'] = $this->UserModel->getuser(session()->get('email'));
            $data['myclass'] = $this->KelasModel->getMyClass(session()->get('email'));
            $data['classes'] = $this->UserkelasModel->getMyClass(session()->get('email'));
            $data['kelas'] = $kelas_saya;
            return view('super_user/tambah-ujian-essay', $data);
        }
    }
    public function addessay_($kode_kelas = '')
    {
        $kelas_kode = decrypt_url($kode_kelas);
        $user = $this->UserModel->getuser(session()->get('email'));

        // CEK JIKA TIDAK ADA KODE KELAS
        if ($kelas_kode == '') {
            session()->setFlashdata(
                'pesan',
                '<script>
                    Swal.fire(
                        "Error",
                        "Somethings Wrong with class code",
                        "error"
                    )
                </script>'
            );
            return redirect()->to('user');
        }

        // CARI DATA KELAS BERDASARKAN KODE KELAS
        $kelas = $this->KelasModel->asObject()->where(['kode_kelas' => $kelas_kode])->first();
        // CEK APAKAH KELAS TIDAK DITEMUKAN
        if ($kelas == null) {
            session()->setFlashdata(
                'pesan',
                '<script>
                    Swal.fire(
                        "Error",
                        "Class not Found",
                        "error"
                    )
                </script>'
            );
            return redirect()->to('user');
        }

        // CEK APAKAH DI KELAS TERSEBUT SUDAH ADA SISWA YANG JOIN
        $siswa = $this->UserkelasModel->getAllbyClass($kelas_kode);
        if ($siswa == null) {
            session()->setFlashdata(
                'pesan',
                "<script>
                    Swal.fire(
                    'Error',
                    'Di kelas ini belum ada siswa',
                    'error'
                    )
                </script>"
            );
            return redirect()->to('su/addessay/' . $kode_kelas)->withInput();
        }

        // SIAPKAN DATA SISWA UNTUK DIKIRIMKAN NOTIFIKASI EMAIL
        $data_siswa = '';
        foreach ($siswa as $s) {
            $data_siswa .= $s->email . ',';
        }

        // CEK APAKAH KELAS TERSEBUT ADALAH KELAS YANG DIA BUAT
        [ $is_pengajar, $kelas_saya ] = $this->Auth->isUserPengajar(session()->get('email'), $kelas_kode);

        if ($is_pengajar) {
            // DATA UJIAN
            $kode_ujian = random_string('alnum', 10);
            $data_ujian = [
                'kode_ujian' => $kode_ujian,
                'kode_kelas' => $kelas_kode,
                'nama_ujian' => $this->request->getVar('nama_ujian'),
                'tanggal_dibuat' => time(),
                'waktu_jam' => $this->request->getVar('waktu_jam'),
                'waktu_menit' => $this->request->getVar('waktu_menit'),
                'jenis_ujian' => 2 //jenis ujian berisi angka 1 dan 2, jika 1 berarti ujian pilihan ganda, jika 2 berari essay
            ];
            // END DATA UJIAN

            // DATA DETAIL UJIAN PG
            $nama_soal = $this->request->getVar('nama_soal');
            $data_detail_ujian = array();
            $index = 0;
            foreach ($nama_soal as $nama) {
                array_push($data_detail_ujian, array(
                    'kode_ujian' => $kode_ujian,
                    'nama_soal' => $nama,
                ));

                $index++;
            }
            // END DATA DETAIL UJIAN PG
            // var_dump($data_ujian);
            // echo "<pre>";
            // var_dump($data_detail_ujian);
            // die;

            // KIRIM NOTIFIKASI EMAIL
            $mail = $this->MailsettingModel->asObject()->first();
            $config['SMTPHost'] = $mail->smtp_host;
            $config['SMTPUser'] = $mail->smtp_user;
            $config['SMTPPass'] = $mail->smtp_password;
            $config['SMTPPort'] = $mail->smtp_port;
            $config['SMTPCrypto'] = $mail->smtp_crypto;

            $this->email->initialize($config);
            $this->email->setNewline("\r\n");
            $this->email->setFrom($mail->smtp_user, 'Ruang Belajar By Belibis');
            $this->email->setTo($data_siswa);
            $this->email->setSubject('Exam');
            $this->email->setMessage('
                <div style="color: #000; padding: 10px;">
                    <div
                        style="font-family: `Segoe UI`, Tahoma, Geneva, Verdana, sans-serif; font-size: 20px; color: #1C3FAA; font-weight: bold;">
                        Ruang Belajar</div>
                    <small style="color: #000;">V 2.0 by Belibis</small>
                    <br>
                    <p style="font-family: `Segoe UI`, Tahoma, Geneva, Verdana, sans-serif; color: #000;">Hallo para siswa <br>
                        <span style="color: #000;">' . $user->nama . ' post new Exam</span></p>
                    <table style="font-family: `Segoe UI`, Tahoma, Geneva, Verdana, sans-serif; color: #000;">
                            <tr>
                                <td>Exam</td>
                                <td> : ' . $this->request->getVar('nama_ujian') . '</td>
                            </tr>
                            <tr>
                                <td>Waktu</td>
                                <td> : ' . $this->request->getVar('waktu_jam') . ' JAM ' . $this->request->getVar('waktu_menit') . ' MENIT</td>
                            </tr>
                        </table>
                        <br>
                        <p style="font-family: `Segoe UI`, Tahoma, Geneva, Verdana, sans-serif; color: #000;">Sign in now and do your exam<br> Good Luck!</p>
                        <br>
                    <a href="' . base_url() . '/auth"
                        style="display: inline-block; width: 100px; height: 30px; background: #1C3FAA; color: #fff; text-decoration: none; border-radius: 5px; text-align: center; line-height: 30px; font-family: `Segoe UI`, Tahoma, Geneva, Verdana, sans-serif;">
                        Sign In Now!
                    </a>
                </div>
        
            ');

            // CEK APAKAH EMAIL SUDAH TERKIRIM
            if ($this->email->send()) {
                // JIKA EMAIL TERKIRIM
                // INSERT DATA UJIAN
                $this->UjianModel->save($data_ujian);
                // INSERT DATA DETAIL UJIAN
                $this->EssaydetailModel->insertBatch($data_detail_ujian);

                session()->setFlashdata(
                    'pesan',
                    "<script>
                    Swal.fire(
                    'Success..! ',
                    'Ujian Berhasil Di Posting',
                    'success'
                    )
                </script>"
                );
                return redirect()->to('user/ujian/' . $kode_kelas);
            } else {
                // JIKA EMAIL TIDAK TERKIRIM
                echo $this->email->printDebugger();
                die();
            }
        }
    }
    public function showessay()
    {
        $kode_ujian = decrypt_url($this->request->getVar('data'));
        $kode_kelas = decrypt_url($this->request->getVar('kelas'));

        // CEK JIKA TIDAK ADA KODE KELAS
        if ($kode_kelas == '') {
            session()->setFlashdata(
                'pesan',
                '<script>
                    Swal.fire(
                        "Error",
                        "Somethings Wrong with class code",
                        "error"
                    )
                </script>'
            );
            return redirect()->to('user');
        }

        // CARI DATA KELAS BERDASARKAN KODE KELAS
        $kelas = $this->KelasModel->asObject()->where(['kode_kelas' => $kode_kelas])->first();
        // CEK APAKAH KELAS TIDAK DITEMUKAN
        if ($kelas == null) {
            session()->setFlashdata(
                'pesan',
                '<script>
                    Swal.fire(
                        "Error",
                        "Class not Found",
                        "error"
                    )
                </script>'
            );
            return redirect()->to('user');
        }

        // CARI DATA UJIAN BERDASARKAN KODE UJIAN
        $ujian = $this->UjianModel->asObject()->where(['kode_ujian' => $kode_ujian])->first();
        // CEK APAKAH DATA UJIAN DITEMUKAN
        if ($ujian == null) {
            session()->setFlashdata(
                'pesan',
                '<script>
                    Swal.fire(
                        "Error",
                        "Exam not Found",
                        "error"
                    )
                </script>'
            );
            return redirect()->to('user/ujian/' . encrypt_url($kode_kelas));
        }

        // CEK APAKAH KELAS TERSEBUT ADALAH KELAS YANG DIA BUAT
        [ $is_pengajar, $kelas_saya ] = $this->Auth->isUserPengajar(session()->get('email'), $kelas_kode);

        if ($is_pengajar) {
            $data = [
                'breadcrumb1' => 'User',
                'breadcrumb2' => 'Exam &raquo; Detail Exam &raquo;' . $kelas_saya->mapel . ' ' . $kelas_saya->nama_kelas,
                'dashboard' => '',
                'clases' => '',
                'materials' => '',
                'topdashboard' => '',
                'topclases' => '',
                'topmaterials' => '',
                'assignments' => '',
                'topassignment' => '',
                'topexam' => 'top-menu--active',
                'exam' => 'menu--active',
                'profile' => '',
                'topprofile' => '',
                'data_kode_kelas' => $kode_kelas
            ];
            // End Menu
            $data['judul'] = 'Ruang Belajar By Belibis| Detail Exam';
            $data['user'] = $this->UserModel->getuser(session()->get('email'));
            $data['myclass'] = $this->KelasModel->getMyClass(session()->get('email'));
            $data['classes'] = $this->UserkelasModel->getMyClass(session()->get('email'));
            $data['kelas'] = $kelas_saya;
            $data['ujian'] = $ujian;
            $data['detail_ujian'] = $this->EssaydetailModel->getAllByExamCode($kode_ujian);
            $data['siswa_saya'] = $this->UserkelasModel->asObject()->where(['kelas_kode' => $kode_kelas])->find();
            $data['waktu_siswa'] = $this->EssaywaktusiswaModel->asObject()->where(['kode_ujian' => $kode_ujian])->find();

            // CARI DATA SISWA YANG BELUM MENGERJAKAN UJIAN
            $db = \Config\Database::connect();
            $query = $db->query("SELECT * FROM user_kelas WHERE email NOT IN ( SELECT siswa FROM essay_waktu_siswa WHERE kode_ujian = '$kode_ujian') ");
            $data['belum_mengerjakan'] = $query->getResultObject();
            // dd($data['waktu_siswa']);

            return view('super_user/detail-ujian-essay', $data);
        }
    }
    public function essay_siswa()
    {
        $kode_ujian = decrypt_url($this->request->getVar('data'));
        $kode_kelas = decrypt_url($this->request->getVar('kelas'));
        $email_siswa = decrypt_url($this->request->getVar('siswa'));

        // CEK JIKA TIDAK ADA KODE KELAS
        if ($kode_kelas == '') {
            session()->setFlashdata(
                'pesan',
                '<script>
                    Swal.fire(
                        "Error",
                        "Somethings Wrong with class code",
                        "error"
                    )
                </script>'
            );
            return redirect()->to('user');
        }

        // CARI DATA KELAS BERDASARKAN KODE KELAS
        $kelas = $this->KelasModel->asObject()->where(['kode_kelas' => $kode_kelas])->first();
        // CEK APAKAH KELAS TIDAK DITEMUKAN
        if ($kelas == null) {
            session()->setFlashdata(
                'pesan',
                '<script>
                    Swal.fire(
                        "Error",
                        "Class not Found",
                        "error"
                    )
                </script>'
            );
            return redirect()->to('user');
        }

        // CARI DATA UJIAN BERDASARKAN KODE UJIAN
        $ujian = $this->UjianModel->asObject()->where(['kode_ujian' => $kode_ujian])->first();
        // CEK APAKAH DATA UJIAN DITEMUKAN
        if ($ujian == null) {
            session()->setFlashdata(
                'pesan',
                '<script>
                    Swal.fire(
                        "Error",
                        "Exam not Found",
                        "error"
                    )
                </script>'
            );
            return redirect()->to('user/ujian/' . encrypt_url($kode_kelas));
        }

        // CEK APAKAH KELAS TERSEBUT ADALAH KELAS YANG DIA BUAT
        [ $is_pengajar, $kelas_saya ] = $this->Auth->isUserPengajar(session()->get('email'), $kelas_kode);

        if ($is_pengajar) {
            $data = [
                'breadcrumb1' => 'User',
                'breadcrumb2' => 'Exam &raquo; Detail Exam &raquo;' . $kelas_saya->mapel . ' ' . $kelas_saya->nama_kelas,
                'dashboard' => '',
                'clases' => '',
                'materials' => '',
                'topdashboard' => '',
                'topclases' => '',
                'topmaterials' => '',
                'assignments' => '',
                'topassignment' => '',
                'topexam' => 'top-menu--active',
                'exam' => 'menu--active',
                'profile' => '',
                'topprofile' => '',
                'data_kode_kelas' => $kode_kelas
            ];
            // End Menu
            $data['judul'] = 'Ruang Belajar By Belibis| Jawaban Siswa';
            $data['user'] = $this->UserModel->getuser(session()->get('email'));
            $data['myclass'] = $this->KelasModel->getMyClass(session()->get('email'));
            $data['classes'] = $this->UserkelasModel->getMyClass(session()->get('email'));
            $data['kelas'] = $kelas_saya;
            $data['ujian'] = $ujian;
            $data['detail_ujian'] = $this->EssaydetailModel->getAllByExamCode($kode_ujian);
            $data['siswa'] = $this->UserModel->getuser($email_siswa);

            return view('super_user/detail-ujian-essay-siswa', $data);
        }
    }
    public function nilai_essay()
    {
        $id_user = $this->request->getVar('id_user');
        $kode_kelas = $this->request->getVar('kode_kelas');
        $kode_ujian = $this->request->getVar('kode_ujian');

        $essay_detail = $this->EssaydetailModel->getAllByExamCode($kode_ujian);
        // dd($essay_detail);

        foreach ($essay_detail as $ed) {
            if ($this->request->getVar("$ed->id_essay_detail") != null) {
                $this->EssaysiswaModel
                    ->where('id_essay_detail', $ed->id_essay_detail)
                    ->where('siswa', $id_user)
                    ->set('nilai', $this->request->getVar("$ed->id_essay_detail"))
                    ->update();
            } else {
                $this->EssaysiswaModel
                    ->where('id_essay_detail', $ed->id_essay_detail)
                    ->where('siswa', $id_user)
                    ->set('nilai', $this->request->getVar("$ed->id_essay_detail"))
                    ->update();
            }
        }
        session()->setFlashdata(
            'pesan',
            '<script>
                Swal.fire(
                   "Berhasil",
                    "berhasil dinilai",
                    "success"
                    );
            </script>'
        );
        return redirect()->to('su/showessay?data=' . encrypt_url($kode_ujian) . '&kelas=' . encrypt_url($kode_kelas));
    }
    public function deleteujianessay()
    {
        $kode_ujian = decrypt_url($this->request->getVar('ujian'));
        $kode_kelas = decrypt_url($this->request->getVar('kelas'));

        // CEK JIKA TIDAK ADA KODE KELAS
        if ($kode_kelas == '') {
            session()->setFlashdata(
                'pesan',
                '<script>
                    Swal.fire(
                        "Error",
                        "Somethings Wrong with class code",
                        "error"
                    )
                </script>'
            );
            return redirect()->to('user');
        }

        // CARI DATA KELAS BERDASARKAN KODE KELAS
        $kelas = $this->KelasModel->asObject()->where(['kode_kelas' => $kode_kelas])->first();
        // CEK APAKAH KELAS TIDAK DITEMUKAN
        if ($kelas == null) {
            session()->setFlashdata(
                'pesan',
                '<script>
                    Swal.fire(
                        "Error",
                        "Class not Found",
                        "error"
                    )
                </script>'
            );
            return redirect()->to('user');
        }

        // CARI DATA UJIAN BERDASARKAN KODE UJIAN
        $ujian = $this->UjianModel->asObject()->where(['kode_ujian' => $kode_ujian])->first();
        // CEK APAKAH DATA UJIAN DITEMUKAN
        if ($ujian == null) {
            session()->setFlashdata(
                'pesan',
                '<script>
                    Swal.fire(
                        "Error",
                        "Exam not Found",
                        "error"
                    )
                </script>'
            );
            return redirect()->to('user/ujian/' . encrypt_url($kode_kelas));
        }

        // CEK APAKAH KELAS TERSEBUT ADALAH KELAS YANG DIA BUAT
        [ $is_pengajar, $kelas_saya ] = $this->Auth->isUserPengajar(session()->get('email'), $kelas_kode);

        if ($is_pengajar) {

            // HAPUS DATA PG WAKTU SISWA
            $this->EssaywaktusiswaModel
                ->where('kode_ujian', $kode_ujian)
                ->delete();

            // HAPUS DATAJAWABAN SISWA
            $this->EssaysiswaModel
                ->where('kode_ujian', $kode_ujian)
                ->delete();

            // HAPUS DATA DETAIL UJIAN
            $this->EssaydetailModel
                ->where('kode_ujian', $kode_ujian)
                ->delete();

            // HAPUS DATA UJIAN
            $this->UjianModel
                ->where('kode_ujian', $kode_ujian)
                ->delete();

            session()->setFlashdata(
                'pesan',
                "<script>
                    Swal.fire(
                    'Success..! ',
                    'Ujian Berhasil Di Hapus',
                    'success'
                    )
                </script>"
            );
            return redirect()->to('user/ujian/' . encrypt_url($kode_kelas));
        }
    }
    // ==> ESSAY
    // END :: EXAM

    // START :: SUMMERNOTE
    public function upload_image()
    {
        if ($this->request->getFile('file')) {
            $data_file = $this->request->getFile('file');
            $data_name = $data_file->getRandomName();
            $data_file->move("assets/stream_file/", $data_name);

            echo base_url("assets/stream_file/$data_name");
        }
    }
    public function delete_image()
    {
        $src = $this->request->getVar('src');

        if ($src != null) {
            $file_name = str_replace(base_url() . "/", "", $src);
            if (unlink($file_name)) {
                echo "File Dihapus";
            }
        }
    }
    // END :: SUMMERNOTE

    // START :: INVITE TEACHER
    public function invite_teacher()
    {
        // RETURN OBJ (status, message, content(html))
        // CEK APAKAH TERDAPAT PENGGUNA DENGAN EMAIL TERSEBUT
        $kode_kelas = decrypt_url($this->request->getVar('kode_kelas'));
        $email = $this->request->getVar('email_pengajar');
        $user = $this->UserModel->getuser($email);
        if ($user == null) {
            return json_encode(
                array(
                    'status' => 404,
                    'message'=> "Pengguna dengan email $email tidak ditemukan",
                    'content' => null
                )
            );
        }

        $result = $this->UserkelasModel->addMember($kode_kelas, $email, $user->nama, 'pengajar');
        if (!$result) { // Pengajar sudah ditambahkan
            return json_encode(
                array(
                    'status' => 404,
                    'message'=> "Pengguna dengan email $email sudah ditambahkan",
                    'content' => null
                )
            );
        }
        $data['pengajar'] = array();
        $pemilik_kelas =  $this->KelasModel
            ->where('kode_kelas', $kode_kelas)
            ->get()->getResultObject();

        foreach ($pemilik_kelas as $p) {
            $data['pengajar'][] = (object) array(
                'gambar_user' => $p->gambar_user,
                'nama_user' => $p->nama_user,
                'email_user' => $p->email_user
            );
        }
        $pengajar_kelas = $this->UserkelasModel->getAllTeachersByClass($kode_kelas);

        foreach ($pengajar_kelas as $p) {
            $data['pengajar'][] = (object) array(
                'gambar_user' => $p->gambar,
                'nama_user' => $p->nama,
                'email_user' => $p->email
            );
        }
        return json_encode(
            array(
                'status' => 201,
                'message'=> "Pengajar berhasil ditambahkan",
                'content' => View('super_user/list-pengajar', $data)
            )
        );
    }
    // END :: INVITE TEACHE


    // START :: CHANGE STUDENT ROLE
    function change_student_role() {
        $kode_kelas = $this->request->getVar('kode_kelas');
        $email_user = $this->request->getVar('email_user');

        $this->UserkelasModel->changeStudentRole($kode_kelas, $email_user);

        // GET DATA PENGAJAR
        $data_pengajar['pengajar'] = array();
        $pemilik_kelas =  $this->KelasModel
            ->where('kode_kelas', $kode_kelas)
            ->get()->getResultObject();

        foreach ($pemilik_kelas as $p) {
            $data_pengajar['pengajar'][] = (object) array(
                'gambar_user' => $p->gambar_user,
                'nama_user' => $p->nama_user,
                'email_user' => $p->email_user
            );
        }

        $pengajar_kelas = $this->UserkelasModel->getAllTeachersByClass($kode_kelas);

        foreach ($pengajar_kelas as $p) {
            $data_pengajar['pengajar'][] = (object) array(
                'gambar_user' => $p->gambar,
                'nama_user' => $p->nama,
                'email_user' => $p->email
            );
        }
        $pelajar = $this->UserkelasModel->getAllStudentsByClass($kode_kelas);
        $data_pelajar['pelajar'] = $pelajar;
        return json_encode(
            array(
                'list-pengajar' => View('super_user/list-pengajar', $data_pengajar),
                'list-pelajar' => View('super_user/list-pelajar', $data_pelajar)
            )
        );
    }
    // END :: CHANGE STUDENT ROLE
}
