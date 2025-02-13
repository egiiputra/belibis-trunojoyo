<?php

namespace App\Controllers;

use App\Models\UserModel;
use App\Models\MailsettingModel;
use App\Models\UsertokenModel;
use App\Models\KelasModel;
use App\Models\UserkelasModel;
use App\Models\StreamModel;
use App\Models\FilestreamModel;
use App\Models\MateriModel;
use App\Models\TugasModel;
use App\Models\UsertugasModel;
use App\Models\UjianModel;
use App\Models\PgdetailModel;
use App\Models\EssaydetailModel;
use App\Models\EssaysiswaModel;
use App\Models\PgsiswaModel;
use App\Models\PgwaktusiswaModel;
use App\Models\EssaywaktusiswaModel;

use App\Controllers\Auth;

class User extends BaseController
{
    protected $UserModel;
    protected $MailsettingModel;
    protected $UsertokenModel;
    protected $KelasModel;
    protected $UserkelasModel;
    protected $StreamModel;
    protected $FilestreamModel;
    protected $MateriModel;
    protected $TugasModel;
    protected $UsertugasModel;
    protected $UjianModel;
    protected $PgdetailModel;
    protected $EssaydetailModel;
    protected $EssaysiswaModel;
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
        $this->MateriModel = new MateriModel();
        $this->TugasModel = new TugasModel();
        $this->UsertugasModel = new UsertugasModel();
        $this->UjianModel = new UjianModel();
        $this->PgdetailModel = new PgdetailModel();
        $this->EssaydetailModel = new EssaydetailModel();
        $this->EssaysiswaModel = new EssaysiswaModel();
        $this->PgsiswaModel = new PgsiswaModel();
        $this->PgwaktusiswaModel = new PgwaktusiswaModel();
        $this->EssaywaktusiswaModel = new EssaywaktusiswaModel();
        $this->Auth = new Auth();
        date_default_timezone_set('Asia/Jakarta');
    }

    public function index()
    {
        if (session()->get('email') == null) {
            return redirect()->to('auth');
        }

        // Begin Menu
        $data = [
            'breadcrumb1' => 'User',
            'breadcrumb2' => 'Dashboard',
            'dashboard' => 'menu--active',
            'clases' => '',
            'topdashboard' => 'top-menu--active',
            'profile' => '',
            'topprofile' => '',
            'topclases' => ''
        ];
        // End Menu
        $data['judul'] = "Ruang Belajar By BLIBIS | User Dashboard";
        $data['user'] = $this->UserModel->getuser(session()->get('email'));
        $data['myclass'] = $this->KelasModel->getMyClass(session()->get('email'));
        $data['classes'] = $this->UserkelasModel->getMyClass(session()->get('email'));

        // dd($data['classes']);
        return view('user/index', $data);
    }

    // START :: PROFILE
    public function profile()
    {
        if (session()->get('email') == null) {
            return redirect()->to('auth');
        }

        // Begin Menu
        $data = [
            'breadcrumb1' => 'User',
            'breadcrumb2' => 'My Profile',
            'dashboard' => '',
            'profile' => 'menu--active',
            'clases' => '',
            'topdashboard' => '',
            'topprofile' => 'top-menu--active',
            'topclases' => ''
        ];
        // End Menu
        $data['judul'] = "Ruang Belajar By BLIBIS | User Profile";
        $data['user'] = $this->UserModel->getuser(session()->get('email'));
        $data['myclass'] = $this->KelasModel->getMyClass(session()->get('email'));
        $data['classes'] = $this->UserkelasModel->getMyClass(session()->get('email'));
        return view('user/profile', $data);
    }
    public function editprofile()
    {
        if (!$this->validate([
            'gambar' => [
                'rules' => 'max_size[gambar,2024]|is_image[gambar]|mime_in[gambar,image/jpg,image/jpeg,image/png]',
                'errors' => [
                    'max_size' => 'ukuran tidak boleh melebihi 2MB',
                    'is_image' => 'file yang dipilih harus gambar',
                    'mime_in' => 'file yang dipilih harus gambar'
                ]
            ]
        ])) {
            return redirect()->to('user/profile/')->withInput();
        }

        $fileGambar = $this->request->getFile('gambar');

        // Cek Gambar, Apakah Tetap Gambar lama
        if ($fileGambar->getError() == 4) {
            $nama_gambar = $this->request->getVar('gambar_lama');
        } else {
            // Generate nama file Random
            $nama_gambar = $fileGambar->getRandomName();
            // Upload Gambar
            $fileGambar->move('vendor/dist/user', $nama_gambar);
            // hapus File Yang Lama
            if ($this->request->getVar('gambar_lama') != 'default.png') {
                unlink('vendor/dist/user/' . $this->request->getVar('gambar_lama'));
            }
        }

        $this->UserModel
            ->where('email', session()->get('email'))
            ->set([
                'nama' => $this->request->getVar('nama'),
                'gambar' => $nama_gambar
            ])
            ->update();

        $this->KelasModel
            ->where('email_user', session()->get('email'))
            ->set([
                'nama_user' => $this->request->getVar('nama'),
                'gambar_user' => $nama_gambar
            ])
            ->update();

        session()->setFlashdata(
            'pesan',
            '<script>
                Swal.fire(
                    "Berhasil",
                    "Profile Updated",
                    "success"
                )
            </script>'
        );
        return redirect()->to('user/profile');
    }
    public function cpassword()
    {
        $user = $this->UserModel->asObject()->find(session()->get('id'));

        // APAKAH PASSWORD BENAR
        if (password_verify($this->request->getVar('cpassword'), $user->password)) {
            $this->UserModel->save([
                'id_user' => $user->id_user,
                'password' => password_hash($this->request->getVar('npassword'), PASSWORD_DEFAULT)
            ]);

            session()->setFlashdata(
                'pesan',
                '<script>
                    Swal.fire(
                        "Berhasil",
                        "Password Changed",
                        "success"
                    )
                </script>'
            );
            return redirect()->to('user/profile');
        }

        session()->setFlashdata(
            'pesan',
            '<script>
                Swal.fire(
                    "Error",
                    "Wrong Password",
                    "error"
                )
            </script>'
        );
        return redirect()->to('user/profile');
    }
    // END :: PROFILE


    // START :: KELAS
    public function createclass()
    {
        if (!$this->validate([
            'gambar' => [
                'rules' => 'max_size[gambar,2024]|is_image[gambar]|mime_in[gambar,image/jpg,image/jpeg,image/png]',
                'errors' => [
                    'max_size' => 'ukuran tidak boleh melebihi 2MB',
                    'is_image' => 'file yang dipilih harus gambar',
                    'mime_in' => 'file yang dipilih harus gambar'
                ]
            ]
        ])) {
            session()->setFlashdata(
                'pesan',
                '<script>
                    Swal.fire(
                        "Gagal",
                        "Banner yang dipilih harus gambar berukuran maks 2 MB",
                        "error"
                    )
                </script>'
            );
            return redirect()->to('user')->withInput();
        }

        $user = $this->UserModel->asObject()->find(session()->get('id'));
        // Check if user choose his/her own bg-class
        $fileGambar = $this->request->getFile('gambar');

        if ($fileGambar->getError() == 4) {
            // Set with default value in database
            $nama_gambar = null;
        } else {
            // Generate nama file Random
            $nama_gambar = $fileGambar->getRandomName();
            // Upload Gambar
            $fileGambar->move('vendor/dist/user', $nama_gambar);
        }
        $this->KelasModel->save([
            'kode_kelas' => $this->request->getVar('kelas_kode'),
            'nama_user' => $user->nama,
            'email_user' => $user->email,
            'nama_kelas' => $this->request->getVar('nama_kelas'),
            'mapel' => $this->request->getVar('mapel'),
            'gambar_user' => $user->gambar,
            'bg_class' => ($nama_gambar == null) ? 'images/bg-class.png':"user/$nama_gambar",
        ]);
        // Add validation for image size limit 2mb

        session()->setFlashdata(
            'pesan',
            '<script>
                Swal.fire(
                    "Berhasil",
                    "Class has been Added",
                    "success"
                )
            </script>'
        );
        return redirect()->to('user');
    }
    public function joinclass()
    {
        $kode_kelas = $this->request->getVar('kelas_kode');
        $kelas = $this->KelasModel->getClassByCode($kode_kelas);
        $user = $this->UserModel->asObject()->find(session()->get('id'));

        // CEK APAKAH KELAS ADA
        if ($kelas == null) {
            session()->setFlashdata(
                'pesan',
                '<script>
                    Swal.fire(
                        "Error",
                        "Wrong Class Code",
                        "error"
                    )
                </script>'
            );
            return redirect()->to('user');
        }
        // CEK APAKAH JOIN KE KELAS SENDIRI
        if ($kelas->email_user == session()->get('email')) {
            session()->setFlashdata(
                'pesan',
                '<script>
                    Swal.fire(
                        "Error",
                        "You cannot join in your own class",
                        "error"
                    )
                </script>'
            );
            return redirect()->to('user');
        }
        // CEK APAKAH SUDAH PERNAH JOIN KE KELAS TERSEBUT
        $user_kelas = $this->UserkelasModel->getMyClassByCodeAndEmail($kode_kelas, session()->get('email'));
        if ($user_kelas != null) {
            session()->setFlashdata(
                'pesan',
                '<script>
                    Swal.fire(
                        "Info",
                        "You Already Joined",
                        "info"
                    )
                </script>'
            );
            return redirect()->to('user');
        }

        // JIKA KELAS ADA & BUKAN KELAS SENDIRI & BELUM PERNAH JOIN
        $this->UserkelasModel->save([
            'kelas_kode' => $kode_kelas,
            'email' => session()->get('email'),
            'nama' => $user->nama
        ]);
        session()->setFlashdata(
            'pesan',
            '<script>
                Swal.fire(
                    "Berhasil",
                    "Successfully Joined",
                    "success"
                )
            </script>'
        );
        return redirect()->to('user');
    }
    public function ajaxeditkelas()
    {
        $kode_kelas = $this->request->getPost('kelas_saya');
        $kelas = $this->KelasModel->where('kode_kelas', $kode_kelas)->asObject()->find();
        return json_encode($kelas[0]);
        // echo '
        // <div class="mt-3">
        //     <input type="hidden" name="e_id_kelas" id="e_id_kelas" class="input w-full border mt-2" value="' . $kelas[0]->id_kelas . '" required>
        //     <label class="flex flex-col sm:flex-row"> Class Name
        //         <span class="sm:ml-auto mt-1 sm:mt-0 text-xs text-gray-600">Required</span>
        //     </label>
        //     <input type="text" name="e_nama_kelas" id="e_nama_kelas" class="input w-full border mt-2" value="' . $kelas[0]->nama_kelas . '" required>
        // </div>
        // <div class="mt-3">
        //     <label class="flex flex-col sm:flex-row"> Class Subject
        //         <span class="sm:ml-auto mt-1 sm:mt-0 text-xs text-gray-600">Required</span>
        //     </label>
        //     <input type="text" name="e_mapel" id="e_mapel" class="input w-full border mt-2" value="' . $kelas[0]->mapel . '" required>
        // </div>
        // <div class="mt-3">
        //     <label class="flex flex-col sm:flex-row"> Class Banner </label>
        //     <input type="file" name="gambar_kelas" class="input border mt-2 flex w-full">
        //     <input type="hidden" name="gambar_lama" class="input border mt-2 flex w-full" value="' . $kelas[0]->bg_class . '">
        // </div>
        // <button type="submit" class="button bg-theme-1 text-white mt-5">Submit</button>
        // ';
    }
    public function editclass()
    {
        if (!$this->validate([
            'gambar_kelas' => [
                'rules' => 'max_size[gambar_kelas,2048]|is_image[gambar_kelas]|mime_in[gambar_kelas,image/jpg,image/jpeg,image/png]',
                'errors' => [
                    'max_size' => 'ukuran tidak boleh melebihi 2MB',
                    'is_image' => 'file yang dipilih harus gambar',
                    'mime_in' => 'file yang dipilih harus gambar'
                ]
            ]
        ])) {
            session()->setFlashdata(
                'pesan',
                '<script>
                    Swal.fire(
                        "Gagal",
                        "' . $this->validator->getError('gambar_kelas') . '",
                        "error"
                    )
                </script>'
            );
            return redirect()->to('user')->withInput();
        }

        $fileGambar = $this->request->getFile('gambar_kelas');

        // Cek Gambar, Apakah Tetap Gambar lama
        if ($fileGambar->getError() == 4) {
            $nama_gambar = $this->request->getVar('gambar_lama');
        } else {
            // Generate nama file Random
            $nama_gambar = 'user/' .$fileGambar->getRandomName();
            // Upload Gambar
            $fileGambar->move('vendor/dist', $nama_gambar);
            // hapus File Yang Lama
            if ($this->request->getVar('gambar_lama') != 'bg-class.png' &&
                $this->request->getVar('gambar_lama') != '') {
                unlink('vendor/dist/' . $this->request->getVar('gambar_lama'));
            }
        }
        
        $id = $this->request->getVar('e_id_kelas');

        $this->KelasModel->update($id, [
            'nama_kelas' => $this->request->getVar('e_nama_kelas'),
            'mapel' => $this->request->getVar('e_mapel'),
            'bg_class' => $nama_gambar
        ]);

        session()->setFlashdata(
            'pesan',
            '<script>
                Swal.fire(
                    "Berhasil",
                    "Class Updated",
                    "success"
                )
            </script>'
        );
        return redirect()->to('user');
    }
    public function classes($kode_kelas = '')
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

        [ $is_pengajar, $kelas_saya ] = $this->Auth->isUserPengajar(session()->get('email'), $kelas_kode);
        if ($is_pengajar) {
            // JIKA INI KELAS YANG DI BUAT,MAKA ARAHKAN KE HALAMAN SUPER USER

            // Begin Menu
            $data = [
                'breadcrumb1' => 'User',
                'breadcrumb2' => 'Class &raquo; ' . $kelas_saya->mapel . ' ' . $kelas_saya->nama_kelas,
                'dashboard' => '',
                'clases' => 'menu--active',
                'materials' => '',
                'topdashboard' => '',
                'topclases' => 'top-menu--active',
                'topmaterials' => '',
                'topassignment' => '',
                'topanggota' => '',
                'assignments' => '',
                'topexam' => '',
                'exam' => '',
                'profile' => '',
                'topprofile' => '',
                'data_kode_kelas' => $kelas_kode
            ];
            // End Menu
            $data['judul'] = 'Ruang Belajar By BELIBIS | Room Class';
            $data['user'] = $this->UserModel->getuser(session()->get('email'));
            $data['myclass'] = $this->KelasModel->getMyClass(session()->get('email'));
            $data['classes'] = $this->UserkelasModel->getMyClass(session()->get('email'));
            $data['kelas'] = $kelas_saya;
            $data['data_stream'] = $this->StreamModel->getStreamByClass($kelas_saya->id_kelas);
            $data['file_stream'] = $this->FilestreamModel->asObject()->findAll();

            return view('super_user/room-class', $data);
        }

        if ($kelas_saya != null) {
            // Begin Menu
            $data = [
                'breadcrumb1' => 'User',
                'breadcrumb2' => 'Class &raquo; ' . $kelas_saya->mapel . ' ' . $kelas_saya->nama_kelas,
                'dashboard' => '',
                'clases' => 'menu--active',
                'materials' => '',
                'topdashboard' => '',
                'topclases' => 'top-menu--active',
                'topmaterials' => '',
                'topassignment' => '',
                'topanggota' => '',
                'assignments' => '',
                'topexam' => '',
                'exam' => '',
                'profile' => '',
                'topprofile' => '',
                'data_kode_kelas' => $kelas_kode
            ];
            // End Menu
            $data['judul'] = 'Ruang Belajar By BELIBIS | Room Class';
            $data['user'] = $this->UserModel->getuser(session()->get('email'));
            $data['myclass'] = $this->KelasModel->getMyClass(session()->get('email'));
            $data['classes'] = $this->UserkelasModel->getMyClass(session()->get('email'));
            $data['kelas'] = $kelas_saya;
            $data['data_stream'] = $this->StreamModel->getStreamByClass($kelas_saya->id_kelas);
            $data['file_stream'] = $this->FilestreamModel->asObject()->findAll();
            return view('user/room-class', $data);
        }
    }
    // END :: KELAS

    // START :: STREAM
    public function streamadd($kode_kelas)
    {
        // Ambil Data User
        $user = $this->UserModel->getuser(session()->get('email'));

        // Tangkap data Stream
        $data_stream = [
            'stream_kode' => $this->request->getVar('stream_kode'),
            'kelas_id' => $this->request->getVar('kelas_id'),
            'email' => $user->email,
            'nama_user' => $user->nama,
            'gambar' => $user->gambar,
            'date_created' => time(),
            'text_stream' => $this->request->getVar('stream_text')
        ];

        // CEK APAKAH ADA FILE U=YANG DIPILIH
        $cek = $this->request->getFileMultiple('stream_file');
        if ($cek[0]->getError() != 4) {
            $data_file = [];

            // FUNGSI UPLOAD FILE
            foreach ($this->request->getFileMultiple('stream_file') as $file) {
                // Generate nama file Random
                $nama_file = str_replace(' ', '_', $file->getName());
                // Upload Gambar
                $file->move('assets/stream_file', $nama_file);

                array_push($data_file, [
                    'kode_stream' => $this->request->getVar('stream_kode'),
                    'nama_file' => $nama_file
                ]);
            }
            $this->FilestreamModel->insertBatch($data_file);
        }

        $this->StreamModel->insert($data_stream);

        session()->setFlashdata(
            'pesan',
            '<script>
                Swal.fire(
                    "Berhasil",
                    "berhasil di posting",
                    "success"
                )
            </script>'
        );
        return redirect()->to('user/classes/' . $kode_kelas);
    }
    // END :: STREAM

    // START :: MATERIALS
    public function materials($kode_kelas = '')
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

        [ $is_pengajar, $kelas_saya ] = $this->Auth->isUserPengajar(session()->get('email'), $kelas_kode);
        if ($is_pengajar) {
            $data = [
                'breadcrumb1' => 'User',
                'breadcrumb2' => 'Materials &raquo; ' . $kelas_saya->mapel . ' ' . $kelas_saya->nama_kelas,
                'dashboard' => '',
                'clases' => '',
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
            $data['judul'] = 'Ruang Belajar By BELIBIS | Materials';
            $data['user'] = $this->UserModel->getuser(session()->get('email'));
            $data['myclass'] = $this->KelasModel->getMyClass(session()->get('email'));
            $data['classes'] = $this->UserkelasModel->getMyClass(session()->get('email'));
            $data['kelas'] = $kelas_saya;
            $data['list_materi'] = $this->MateriModel->getAllbyIdClass($kelas_saya->id_kelas);

            return view('super_user/materi', $data);
        }

        if ($kelas_saya != null) {
            $data = [
                'breadcrumb1' => 'User',
                'breadcrumb2' => 'Materials &raquo; ' . $kelas_saya->mapel . ' ' . $kelas_saya->nama_kelas,
                'dashboard' => '',
                'clases' => '',
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
            $data['judul'] = 'Ruang Belajar By BELIBIS | Materials';
            $data['user'] = $this->UserModel->getuser(session()->get('email'));
            $data['myclass'] = $this->KelasModel->getMyClass(session()->get('email'));
            $data['classes'] = $this->UserkelasModel->getMyClass(session()->get('email'));
            $data['kelas'] = $kelas_saya;
            $data['list_materi'] = $this->MateriModel->getAllbyIdClass($kelas_saya->id_kelas);

            return view('user/materi', $data);
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
            'topanggota' => '',
            'assignments' => '',
            'topexam' => '',
            'exam' => '',
            'profile' => '',
            'topprofile' => '',
            'data_kode_kelas' => $kode_kelas
        ];
        // END DATA MENU

        $data['judul'] = 'Ruang Belajar By BELIBIS | Detail Materials';
        $data['user'] = $this->UserModel->getuser($materi->email);
        $data['myclass'] = $this->KelasModel->getMyClass(session()->get('email'));
        $data['classes'] = $this->UserkelasModel->getMyClass(session()->get('email'));
        $data['file_stream'] = $this->FilestreamModel->asObject()->findAll();
        $data['materi'] = $materi;


        return view('user/detail-materi', $data);
    }
    // END :: MATERIALS

    // START :: ASSIGNMENT
    public function assignment($kode_kelas = '')
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

        [ $is_pengajar, $kelas_saya ] = $this->Auth->isUserPengajar(session()->get('email'), $kelas_kode);
        // var_dump($is_pengajar);
        // var_dump($kelas_saya);
        // exit;
        if ($is_pengajar) {
            $data = [
                'breadcrumb1' => 'User',
                'breadcrumb2' => 'Assignments &raquo; ' . $kelas_saya->mapel . ' ' . $kelas_saya->nama_kelas,
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
                'data_kode_kelas' => $kelas_kode
            ];
            // End Menu
            $data['judul'] = 'Ruang Belajar By BELIBIS | Assignment';
            $data['user'] = $this->UserModel->getuser(session()->get('email'));
            $data['myclass'] = $this->KelasModel->getMyClass(session()->get('email'));
            $data['classes'] = $this->UserkelasModel->getMyClass(session()->get('email'));
            $data['kelas'] = $kelas_saya;
            $data['list_tugas'] = $this->TugasModel->getAllbyEmailAndClassCode($kelas_kode);

            return view('super_user/tugas', $data);
        }

        if ($kelas_saya != null) {
            $data = [
                'breadcrumb1' => 'User',
                'breadcrumb2' => 'Assignments &raquo; ' . $kelas_saya->mapel . ' ' . $kelas_saya->nama_kelas,
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
                'data_kode_kelas' => $kelas_kode
            ];
            // End Menu
            $data['judul'] = 'Ruang Belajar By BELIBIS | Assignment';
            $data['user'] = $this->UserModel->getuser(session()->get('email'));
            $data['myclass'] = $this->KelasModel->getMyClass(session()->get('email'));
            $data['classes'] = $this->UserkelasModel->getMyClass(session()->get('email'));
            $data['kelas'] = $kelas_saya;
            $data['list_tugas'] = $this->TugasModel->getAllByClassCode($kelas_kode);

            return view('user/tugas', $data);
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

        // CEK APAKAH DIA JOIN KE DALAM KELAS TERSEBUT
        $kelas_saya = $this->UserkelasModel->getMyClassByCodeAndEmail($kode_kelas, session()->get('email'));

        if ($kelas_saya != null) {
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
                'topexam' => '',
                'topanggota' => '',
                'exam' => '',
                'profile' => '',
                'topprofile' => '',
                'data_kode_kelas' => $kode_kelas
            ];
            // End Menu
            $data['judul'] = 'Ruang Belajar By BELIBIS | Detail Assignment';
            $data['user'] = $this->UserModel->getuser(session()->get('email'));
            $data['myclass'] = $this->KelasModel->getMyClass(session()->get('email'));
            $data['classes'] = $this->UserkelasModel->getMyClass(session()->get('email'));
            $data['kelas'] = $kelas_saya;
            $data['tugas'] = $tugas;
            $data['tugas_saya'] = $this->UsertugasModel->getByKodeTugasAndEmail($tugas->kode_tugas, session()->get('email'));
            $data['file_stream'] = $this->FilestreamModel->asObject()->findAll();
            return view('user/detail-tugas', $data);
        }
    }
    public function submit($kode_kelas = '')
    {
        $user = $this->UserModel->getuser(session()->get('email'));
        $tugas = $this->TugasModel->asObject()->where(['kode_tugas' => $this->request->getVar('kode_tugas')])->first();
        $waktu_sekarang = date('Y-m-d H:i', time());
        $batas_waktu = $tugas->due_date;

        if ($waktu_sekarang > $batas_waktu) {
            $is_telat = 1; // 1 Berarti YA 
            // return "Sudah Telat";
        } else {
            $is_telat = 0; // 0 Berarti TIDAK 
            // return "Masih Belum Telat";
        }

        // CEK APAKAH ADA FILE YANG DIPILIH
        $cek = $this->request->getFileMultiple('my_assignment_file');
        if ($cek[0]->getError() != 4) {
            $data_file = [];

            // FUNGSI UPLOAD FILE
            foreach ($this->request->getFileMultiple('my_assignment_file') as $file) {
                // Generate nama file Random
                $nama_file = str_replace(' ', '_', $file->getName());
                // Upload Gambar
                $file->move('assets/stream_file', $nama_file);

                array_push($data_file, [
                    'kode_stream' => $this->request->getVar('kode_user_tugas'),
                    'nama_file' => $nama_file
                ]);
            }
            $this->FilestreamModel->insertBatch($data_file);
        }

        // INSERT DATA TUGAS SISWA
        $this->UsertugasModel->save([
            'kode_user_tugas' => $this->request->getVar('kode_user_tugas'),
            'kode_tugas' => $this->request->getVar('kode_tugas'),
            'kode_kelas' => $this->request->getVar('kode_kelas'),
            'nama' => $user->nama,
            'email' => $user->email,
            'gambar' => $user->gambar,
            'teks' => $this->request->getVar('teks'),
            'date_send' => time(),
            'is_late' => $is_telat
        ]);

        session()->setFlashdata(
            'pesan',
            '<script>
                Swal.fire(
                    "Berhasil",
                    "Tugas Berhasil di kirim",
                    "success"
                )
            </script>'
        );
        session()->setFlashdata('tab-tugas', 'true');
        return redirect()->to('user/showassignment?data=' . encrypt_url($tugas->id_tugas) . '&code=' . $kode_kelas);
    }
    // END :: ASSIGNMENT

    // START :: UJIAN
    public function ujian($kode_kelas = '')
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

        [ $is_pengajar, $kelas_saya ] = $this->Auth->isUserPengajar(session()->get('email'), $kelas_kode);
        if ($is_pengajar) {
            $data = [
                'breadcrumb1' => 'User',
                'breadcrumb2' => 'Exam &raquo; ' . $kelas_saya->mapel . ' ' . $kelas_saya->nama_kelas,
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
            $data['judul'] = 'Ruang Belajar By BELIBIS | Exam';
            $data['user'] = $this->UserModel->getuser(session()->get('email'));
            $data['myclass'] = $this->KelasModel->getMyClass(session()->get('email'));
            $data['classes'] = $this->UserkelasModel->getMyClass(session()->get('email'));
            $data['kelas'] = $kelas_saya;
            $data['list_ujian'] = $this->UjianModel->getAllByClassCode($kelas_kode);
            return view('super_user/ujian', $data);
        }

        if ($kelas_saya != null) {
            $data = [
                'breadcrumb1' => 'User',
                'breadcrumb2' => 'Exam &raquo; ' . $kelas_saya->mapel . ' ' . $kelas_saya->nama_kelas,
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
            $data['judul'] = 'Ruang Belajar By BELIBIS | Exam';
            $data['user'] = $this->UserModel->getuser(session()->get('email'));
            $data['myclass'] = $this->KelasModel->getMyClass(session()->get('email'));
            $data['classes'] = $this->UserkelasModel->getMyClass(session()->get('email'));
            $data['kelas'] = $kelas_saya;
            $data['list_ujian'] = $this->UjianModel->getAllByClassCode($kelas_kode);
            return view('user/ujian', $data);
        }
    }
    public function showpg()
    {
        $kode_ujian = decrypt_url($this->request->getVar('data'));
        $kode_kelas = decrypt_url($this->request->getVar('kelas'));

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

        // CEK APAKAH KODE UJIAN BENAR
        $ujian = $this->UjianModel->asObject()->where(['kode_ujian' => $kode_ujian])->first();
        if ($ujian == null) {
            session()->setFlashdata(
                'pesan',
                "<script>
                    Swal.fire(
                    'Error',
                    'Tidak Ada Ujian',
                    'error'
                    )
                </script>"
            );
            return redirect()->to('user/ujian/' . encrypt_url($kode_kelas));
        }

        // CEK APAKAH DIA SUDAH JOIN KE KELAS INI
        $kelas_saya = $this->UserkelasModel->getMyClassByCodeAndEmail($kode_kelas, session()->get('email'));
        if ($kelas_saya != null) {
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
                'topanggota' => '',
                'exam' => 'menu--active',
                'profile' => '',
                'topprofile' => '',
                'data_kode_kelas' => $kode_kelas
            ];
            // End Menu
            // MENGAMBIL DATA DETAIL UJIAN
            $pg_detail = $this->PgdetailModel->getAllByExamCode($kode_ujian);
            // Mengambil Data siswa
            $siswa = $this->UserModel->getuser(session()->get('email'));

            // mENGAMBIL wAKTU sEKARANG
            $waktu_sekarang = date('Y-m-d H:i:s', time());

            // MENGAMBIL DATA WAKTU SISWA
            $pg_waktu_siswa = $this->PgwaktusiswaModel
                ->where('kode_ujian', $kode_ujian)
                ->where('siswa', $siswa->email)
                ->get()->getRowObject();
            // CEK APAKAH DATANYA KOSONG
            if ($pg_waktu_siswa == null) {
                $data_pg_siswa = [];
                foreach ($pg_detail as $pd) {
                    array_push($data_pg_siswa, [
                        'id_pg_detail' => $pd->id_pg_detail,
                        'kode_ujian' => $pd->kode_ujian,
                        'siswa' => $siswa->id_user
                    ]);
                }

                $data_waktu_siswa = [
                    'kode_ujian' => $kode_ujian,
                    'siswa' => $siswa->email,
                    'waktu_berakhir' => date('M d, Y H:i:s', strtotime("+$ujian->waktu_jam hours +$ujian->waktu_menit minutes +0 seconds", strtotime("$waktu_sekarang"))),
                    'selesai' => 0
                ];

                $this->PgsiswaModel->insertBatch($data_pg_siswa);
                $this->PgwaktusiswaModel->save($data_waktu_siswa);
            }

            $data['judul'] = 'Ruang Belajar By BELIBIS | Exam';
            $data['user'] = $siswa;
            $data['guru'] = $this->UserModel->getuser($kelas->email_user);
            $data['myclass'] = $this->KelasModel->getMyClass(session()->get('email'));
            $data['classes'] = $this->UserkelasModel->getMyClass(session()->get('email'));
            $data['kelas'] = $kelas_saya;
            $data['ujian'] = $ujian;
            $data['detail_ujian'] = $pg_detail;

            $data['waktu_siswa'] = $this->PgwaktusiswaModel
                ->where('kode_ujian', $kode_ujian)
                ->where('siswa', $siswa->email)
                ->get()->getRowObject();

            return view('user/detail-ujian-pg', $data);
        }
    }
    public function submit_pg()
    {
        // Mengambil data yg dikirim
        $id_siswa = $this->request->getVar('siswa');
        $kode_ujian = $this->request->getVar('ujian');
        $kode_kelas = $this->request->getVar('kelas');
        $siswa = $this->UserModel->asObject()->find($id_siswa);
        // dd($id_siswa);

        $pg_detail = $this->PgdetailModel->getAllByExamCode($kode_ujian);

        foreach ($pg_detail as $pd) {
            if ($this->request->getVar("$pd->id_pg_detail") == $pd->jawaban) {
                $this->PgsiswaModel
                    ->where('id_pg_detail', $pd->id_pg_detail)
                    ->where('siswa', $id_siswa)
                    ->set('jawaban', $this->request->getVar("$pd->id_pg_detail"))
                    ->set('benar', 1)
                    ->update();
                // $this->db->set('jawaban', $this->request->getVar("$pd->id_pg_detail"));
                // $this->db->set('benar', 1);
                // $this->db->where('id_pg_detail', $pd->id_pg_detail);
                // $this->db->where('siswa', $id_siswa);
                // $this->db->update('pg_siswa');
            } else {
                if ($this->request->getVar("$pd->id_pg_detail") == NULL) {
                    $this->PgsiswaModel
                        ->where('id_pg_detail', $pd->id_pg_detail)
                        ->where('siswa', $id_siswa)
                        ->set('jawaban', $this->request->getVar("$pd->id_pg_detail"))
                        ->set('benar', 2)
                        ->update();
                    // $this->db->set('jawaban', $this->request->getVar("$pd->id_pg_detail"));
                    // $this->db->set('benar', 2);
                    // $this->db->where('id_pg_detail', $pd->id_pg_detail);
                    // $this->db->where('siswa', $id_siswa);
                    // $this->db->update('pg_siswa');
                } else {
                    $this->PgsiswaModel
                        ->where('id_pg_detail', $pd->id_pg_detail)
                        ->where('siswa', $id_siswa)
                        ->set('jawaban', $this->request->getVar("$pd->id_pg_detail"))
                        ->set('benar', 0)
                        ->update();
                    // $this->db->set('jawaban', $this->request->getVar("$pd->id_pg_detail"));
                    // $this->db->set('benar', 0);
                    // $this->db->where('id_pg_detail', $pd->id_pg_detail);
                    // $this->db->where('siswa', $id_siswa);
                    // $this->db->update('pg_siswa');
                }
            }
        }

        $this->PgwaktusiswaModel
            ->where('kode_ujian', $kode_ujian)
            ->where('siswa', $siswa->email)
            ->set('selesai', 1)
            ->update();
        // $this->db->set('selesai', 1);
        // $where = [
        //     'kode_ujian' => $kode_ujian,
        //     'siswa' => $id_siswa
        // ];
        // $this->db->where($where);
        // $this->db->update('pg_waktu_siswa');

        // $this->session->set_flashdata('pesan', '
        //                 <script>
        //                     Swal.fire(
        //                     "Berhasil",
        //                     "jawabanmu berhasil dikirim",
        //                     "success"
        //                     );
        //                 </script>
        //                 ');
        // redirect('user/showpg?data=' . encrypt_url($kode_ujian) . '&kelas=' . encrypt_url($kode_kelas));

        session()->setFlashdata(
            'pesan',
            '<script>
                Swal.fire(
                    "Berhasil",
                    "jawabanmu berhasil dikirim",
                    "success"
                    );
            </script>'
        );
        return redirect()->to('user/showpg?data=' . encrypt_url($kode_ujian) . '&kelas=' . encrypt_url($kode_kelas));
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
        $kelas_saya = $this->UserkelasModel->getMyClassByCodeAndEmail($kode_kelas, session()->get('email'));
        if ($kelas_saya != null) {
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
                'topanggota'=> '',
                'exam' => 'menu--active',
                'profile' => '',
                'topprofile' => '',
                'data_kode_kelas' => $kode_kelas
            ];
            // End Menu
            // MENGAMBIL DATA DETAIL UJIAN
            $essay_detail = $this->EssaydetailModel->getAllByExamCode($kode_ujian);
            // Mengambil Data siswa
            $siswa = $this->UserModel->getuser(session()->get('email'));

            // mENGAMBIL wAKTU sEKARANG
            $waktu_sekarang = date('Y-m-d H:i:s', time());

            // MENGAMBIL DATA WAKTU SISWA
            $essay_waktu_siswa = $this->EssaywaktusiswaModel
                ->where('kode_ujian', $kode_ujian)
                ->where('siswa', $siswa->email)
                ->get()->getRowObject();
            // CEK APAKAH DATANYA KOSONG
            if ($essay_waktu_siswa == null) {
                $data_essay_siswa = [];
                foreach ($essay_detail as $ed) {
                    array_push($data_essay_siswa, [
                        'id_essay_detail' => $ed->id_essay_detail,
                        'kode_ujian' => $ed->kode_ujian,
                        'siswa' => $siswa->id_user
                    ]);
                }

                $data_waktu_siswa = [
                    'kode_ujian' => $kode_ujian,
                    'siswa' => $siswa->email,
                    'waktu_berakhir' => date('M d, Y H:i:s', strtotime("+$ujian->waktu_jam hours +$ujian->waktu_menit minutes +0 seconds", strtotime("$waktu_sekarang"))),
                    'selesai' => 0
                ];

                $this->EssaysiswaModel->insertBatch($data_essay_siswa);
                $this->EssaywaktusiswaModel->save($data_waktu_siswa);
            }

            $data['judul'] = 'Ruang Belajar By BELIBIS | Exam';
            $data['user'] = $siswa;
            $data['guru'] = $this->UserModel->getuser($kelas->email_user);
            $data['myclass'] = $this->KelasModel->getMyClass(session()->get('email'));
            $data['classes'] = $this->UserkelasModel->getMyClass(session()->get('email'));
            $data['kelas'] = $kelas_saya;
            $data['ujian'] = $ujian;
            $data['detail_ujian'] = $essay_detail;

            $data['waktu_siswa'] = $this->EssaywaktusiswaModel->getByExamCodeAndEmailSiswa($kode_ujian, $siswa->email);

            return view('user/detail-ujian-essay', $data);
        }
    }
    public function submit_essay()
    {
        // Mengambil data yg dikirim
        $id_siswa = $this->request->getVar('siswa');
        $kode_ujian = $this->request->getVar('ujian');
        $kode_kelas = $this->request->getVar('kelas');

        $siswa = $this->UserModel->asObject()->find($id_siswa);

        // $essay_detail = $this->db->get_where('essay_detail', ['kode_ujian' => $kode_ujian])->result();
        $essay_detail = $this->EssaydetailModel->getAllByExamCode($kode_ujian);

        foreach ($essay_detail as $ed) {
            if ($this->request->getVar("$ed->id_essay_detail") != null) {
                $this->EssaysiswaModel
                    ->where('id_essay_detail', $ed->id_essay_detail)
                    ->where('siswa', $id_siswa)
                    ->set('jawaban', $this->request->getVar("$ed->id_essay_detail"))
                    ->update();
                // $this->db->set('jawaban', $this->request->getVar("$ed->id_essay_detail"));
                // $this->db->where('id_essay_detail', $ed->id_essay_detail);
                // $this->db->where('siswa', $id_siswa);
                // $this->db->update('essay_siswa');
            } else {
                $this->EssaysiswaModel
                    ->where('id_essay_detail', $ed->id_essay_detail)
                    ->where('siswa', $id_siswa)
                    ->set('jawaban', null)
                    ->update();
                // $this->db->set('jawaban', NULL);
                // $this->db->where('id_essay_detail', $ed->id_essay_detail);
                // $this->db->where('siswa', $id_siswa);
                // $this->db->update('essay_siswa');
            }
        }
        $this->EssaywaktusiswaModel
            ->where('kode_ujian', $kode_ujian)
            ->where('siswa', $siswa->email)
            ->set('selesai', 1)
            ->update();
        // $this->db->set('selesai', 1);
        // $where = [
        //     'kode_ujian' => $kode_ujian,
        //     'siswa' => $siswa->email
        // ];
        // $this->db->where($where);
        // $this->db->update('essay_waktu_siswa');

        // $ujian = $this->db->get_where('ujian', ['kode_ujian' => $kode_ujian])->row();

        // $this->session->set_flashdata('pesan', '
        //                 <script>
        //                     Swal.fire(
        //                     "Berhasil",
        //                     "jawabanmu berhasil dikirim",
        //                     "success"
        //                     );
        //                 </script>
        //                 ');
        // redirect('user/showessay?data=' . encrypt_url($kode_ujian) . '&kelas=' . encrypt_url($kode_kelas));
        session()->setFlashdata(
            'pesan',
            '<script>
                Swal.fire(
                    "Berhasil",
                    "jawabanmu berhasil dikirim",
                    "success"
                    );
            </script>'
        );
        return redirect()->to('user/showessay?data=' . encrypt_url($kode_ujian) . '&kelas=' . encrypt_url($kode_kelas));
    }
    // END :: UJIAN

    // START :: ANGGOTA
    public function anggota($kode_kelas = '')
    {
        $kode_kelas = decrypt_url($kode_kelas);
        $pemilik_kelas = $this->KelasModel
            ->where('kode_kelas', $kode_kelas)
            ->get()->getResultObject();

        $pengajar = array();
        foreach ($pemilik_kelas as $p) {
            $pengajar[] = (object) array(
                'gambar_user' => $p->gambar_user,
                'nama_user' => $p->nama_user,
                'email_user' => $p->email_user
            );
        }

        $pengajar_kelas = $this->UserkelasModel->getAllTeachersByClass($kode_kelas);

        foreach ($pengajar_kelas as $p) {
            $pengajar[] = (object) array(
                'gambar_user' => $p->gambar,
                'nama_user' => $p->nama,
                'email_user' => $p->email
            );
        }
        $pelajar = $this->UserkelasModel->getAllStudentsByClass($kode_kelas);
        // CEK APAKAH KELAS TERSEBUT ADALAH KELAS YANG DIA BUAT
        $kelas_saya = $this->KelasModel
            ->where('kode_kelas', $kode_kelas)
            ->where('email_user', session()->get('email'))
            ->get()->getRowObject();
        if ($kelas_saya != null) {
            // JIKA INI KELAS YANG DI BUAT,MAKA ARAHKAN KE HALAMAN SUPER USER

            // Begin Menu
            $data = [
                'breadcrumb1' => 'User',
                'breadcrumb2' => 'Class &raquo; ' . $kelas_saya->mapel . ' ' . $kelas_saya->nama_kelas,
                'dashboard' => '',
                'clases' => 'menu--active',
                'materials' => '',
                'topdashboard' => '',
                'topclases' => '',
                'topmaterials' => '',
                'topassignment' => '',
                'topanggota' => 'top-menu--active',
                'assignments' => '',
                'topexam' => '',
                'exam' => '',
                'profile' => '',
                'topprofile' => '',
                'data_kode_kelas' => $kode_kelas,
                'pengajar' => $pengajar,
                'pelajar' => $pelajar
            ];
            // End Menu
            $data['judul'] = 'Ruang Belajar By BELIBIS | Room Class';
            $data['user'] = $this->UserModel->getuser(session()->get('email'));
            $data['myclass'] = $this->KelasModel->getMyClass(session()->get('email'));
            $data['classes'] = $this->UserkelasModel->getMyClass(session()->get('email'));
            $data['kelas'] = $kelas_saya;

            return view('super_user/anggota', $data);
        }

        $kelas_saya = $this->UserkelasModel->getMyClassByCodeAndEmail($kode_kelas, session()->get('email'));
        if ($kelas_saya != null) {
            // Begin Menu
            $data = [
                'breadcrumb1' => 'User',
                'breadcrumb2' => 'Class &raquo; ' . $kelas_saya->mapel . ' ' . $kelas_saya->nama_kelas,
                'dashboard' => '',
                'clases' => '',
                'materials' => '',
                'topdashboard' => '',
                'topclases' => '',
                'topmaterials' => '',
                'topassignment' => '',
                'topanggota' => 'top-menu--active',
                'assignments' => '',
                'topexam' => '',
                'exam' => '',
                'profile' => '',
                'topprofile' => '',
                'data_kode_kelas' => $kode_kelas,
                'pengajar' => $pengajar,
                'pelajar' => $pelajar
            ];
            // End Menu
            $data['judul'] = 'Ruang Belajar By BELIBIS | Room Class';
            $data['user'] = $this->UserModel->getuser(session()->get('email'));
            $data['myclass'] = $this->KelasModel->getMyClass(session()->get('email'));
            $data['classes'] = $this->UserkelasModel->getMyClass(session()->get('email'));
            $data['kelas'] = $kelas_saya;

            return view('user/anggota', $data);
        }
    }
    // END :: ANGGOTA
}
