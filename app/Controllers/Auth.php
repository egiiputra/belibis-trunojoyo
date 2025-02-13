<?php

namespace App\Controllers;

use App\Models\UserModel;
use App\Models\MailsettingModel;
use App\Models\UsertokenModel;
use App\Models\KelasModel;
use App\Models\UserkelasModel;
// use PHPExcel;
// use PHPExcel_IOFactory;

class Auth extends BaseController
{
    protected $UserModel;
    protected $MailsettingModel;
    protected $UsertokenModel;

    public function __construct()
    {
        $this->validation = \Config\Services::validation();
        $this->email = \Config\Services::email();

        $this->UserModel = new UserModel();
        $this->MailsettingModel = new MailsettingModel();
        $this->UsertokenModel = new UsertokenModel();
        $this->KelasModel = new KelasModel();
        $this->UserkelasModel = new UserkelasModel();
        date_default_timezone_set('Asia/Jakarta');
    }

    public function index()
    {
        if (session()->get('logout') == 'true') {
            session()->destroy();
        }
        if (session()->get('email') != null) {
            return redirect()->to('user');
        }
        $data['judul'] = "BELIBIS | Authentication";
        $data['validation'] = $this->validation;
        return view('auth/index', $data);
    }

    public function login()
    {
        $email = $this->request->getVar('email');
        $password = $this->request->getVar('password');

        $user = $this->UserModel->getuser($email);
        if ($user == null) {
            session()->setFlashdata(
                'pesan',
                "<script>
                    Swal.fire(
                        'Error',
                        'email tidak ditemukan',
                        'error'
                        )
                </script>"
            );
            return redirect()->to('auth#sign-in');
        }

        if ($user->is_active == 0) {
            session()->setFlashdata(
                'pesan',
                "<script>
                    Swal.fire(
                        'Error',
                        'Akun Tidak Aktif',
                        'error'
                        )
                </script>"
            );
            return redirect()->to('auth#sign-in');
        }

        if (password_verify($password, $user->password)) {

            session()->set([
                'id' => $user->id_user,
                'email' => $email,
                'no_regis' => $user->no_regis,
                'role_id' => $user->role_id
            ]);

            session()->setFlashdata(
                'pesan',
                "<script>
                    Swal.fire(
                        'Berhasil',
                        'Berhasil Login',
                        'success'
                        )
                </script>"
            );
            return redirect()->to('user');
        } else {
            session()->setFlashdata(
                'pesan',
                "<script>
                    Swal.fire(
                        'Error',
                        'Password Salah',
                        'error'
                        )
                </script>"
            );
            return redirect()->to('auth#sign-in')->withInput();
        }
    }

    public function signup()
    {
        if (session()->get('email') != null) {
            redirect()->to('user');
        }

        $data['judul'] = "BELIBIS | Sign Up";
        $data['validation'] = $this->validation;
        return view('auth/signup', $data);
    }

    public function signup_()
    {
        if (!$this->validate([
            'name' => [
                'rules' => 'required',
                'errors' => [
                    'required' => 'Nama harus di isi'
                ]
            ],
            'email' => [
                'rules' => 'required|is_unique[user.email]',
                'errors' => [
                    'required' => 'Email harus di isi',
                    'is_unique' => 'Email sudah Terdaftar'
                ]
            ],
            'password' => [
                'rules' => 'required|matches[password2]',
                'errors' => [
                    'required' => 'Password harus di isi',
                    'matches' => 'Password Tidak Sama'
                ]
            ],
            'password2' => [
                'rules' => 'required|matches[password]',
                'errors' => [
                    'required' => 'Password harus di isi',
                    'matches' => 'Password Tidak Sama'
                ]
            ],
        ])) {
            return redirect()->to('auth/signup')->withInput();
        }

        $user = $this->UserModel->asObject()->orderBy('id_user', 'DESC')->first();
        if ($user == null) {
            $no_regis = 19079100;
        } else {
            $no_regis = ($user->no_regis + 1);
        }

        $email = $this->request->getVar('email');
        $token = random_string('alnum', 32);
        $user_token = [
            'email' => $email,
            'token' => $token,
            'date_created' => time()
        ];

        $mail = $this->MailsettingModel->asObject()->first();
        $config['SMTPHost'] = $mail->smtp_host;
        $config['SMTPUser'] = $mail->smtp_user;
        $config['SMTPPass'] = $mail->smtp_password;
        $config['SMTPPort'] = $mail->smtp_port;
        $config['SMTPCrypto'] = $mail->smtp_crypto;

        $this->email->initialize($config);
        $this->email->setNewline("\r\n");
        $this->email->setFrom($mail->smtp_user, 'Ruang Belajar By BELIBIS');
        $this->email->setTo($this->request->getVar('email'));
        $this->email->setSubject('Aktivasi AKun Ruang Belajar');
        $this->email->setMessage('
            <div style="color: #000; padding: 10px;">
                <div style="font-family: `Segoe UI`, Tahoma, Geneva, Verdana, sans-serif; font-size: 20px; color: #1C3FAA; font-weight: bold;">
                    BELIBIS
                </div>
                <small style="color: #000;">V 2.0 by BELIBIS</small>
                <br>
                <p style="font-family: `Segoe UI`, Tahoma, Geneva, Verdana, sans-serif; color: #000;">Hallo ' . $this->request->getVar('name') . ' <br>
                    <span style="color: #000;">Your account successfully added, click the button to verify your account</span></p>
                <br>
                <a href="' . base_url() . '/auth/verify?email=' . $this->request->getVar('email') . '&token=' . $token . '" style="display: inline-block; width: 100px; height: 30px; background: #1C3FAA; color: #fff; text-decoration: none; border-radius: 5px; text-align: center; line-height: 30px; font-family: `Segoe UI`, Tahoma, Geneva, Verdana, sans-serif;">Verify</a>
                <br>
                <p>Jika Tombol tidak bisa di klik silahkan copy link di bawah ini dan tempelkan / paste di browser</p>
                <a href="' . base_url() . '/auth/verify?email=' . $this->request->getVar('email') . '&token=' . $token . '">
                ' . base_url() . '/auth/verify?email=' . $this->request->getVar('email') . '&token=' . $token . '
                </a>
            </div>
        
        ');

        // CEK APAKAH EMAIL SUDAH TERKIRIM
        if ($this->email->send()) {
            // JIKA EMAIL TERKIRIM
            // INSERT DATA USER
            $this->UserModel->save([
                'no_regis' => $no_regis,
                'nama' => $this->request->getVar('name'),
                'email' => $this->request->getVar('email'),
                'password' => password_hash($this->request->getVar('password'), PASSWORD_DEFAULT),
                'gambar' => 'default.png',
                'role_id' => 2,
                'is_active' => 0,
                'date_created' => time()
            ]);
            // INSERT DATA TOKEN
            $this->UsertokenModel->save($user_token);
            session()->setFlashdata(
                'pesan',
                "<script>
                    Swal.fire(
                    'Success..! ',
                    'Your Account Has been Created!<br>Please Check your email to verify',
                    'success'
                    )
                </script>"
            );
            return redirect()->to('auth');
        } else {
            // JIKA EMAIL TIDAK TERKIRIM
            echo $this->email->printDebugger();
            die();
        }
    }

    public function verify()
    {
        $email = $this->request->getVar('email');
        $token = $this->request->getVar('token');

        $user_token = $this->UsertokenModel->getUserToken($email, $token);
        if (!$user_token) {
            session()->setFlashdata(
                'pesan',
                "<script>
                Swal.fire(
                    'Error',
                    'Email atau Token Salah',
                    'error'
                    )
                    </script>"
            );
            return redirect()->to('auth');
        }

        // CEK KADALUARSA TOKEN
        if (time() - $user_token->date_created > (60 * 60 * 24)) {
            $this->UsertokenModel->delete($user_token->id_user);
            session()->setFlashdata(
                'pesan',
                "<script>
                Swal.fire(
                    'Error',
                    'Token kadaluarsa, silahkan lakukan pendaftaran ulang',
                    'error'
                    )
                    </script>"
            );
            return redirect()->to('auth');
        }

        $user = $this->UserModel->getuser($email);
        $this->UserModel->save([
            'id_user' => $user->id_user,
            'is_active' => 1
        ]);
        $this->UsertokenModel->delete($user_token->id);
        session()->setFlashdata(
            'pesan',
            "<script>
                Swal.fire(
                    'Berhasil',
                    'Akun sudah aktif',
                    'success'
                )
            </script>"
        );
        return redirect()->to('auth');
    }
    public function logout()
    {
        session()->setFlashdata(
            'pesan',
            '<script>
                Swal.fire(
                    "Berhasil",
                    "Anda sudah logout",
                    "success"
                )
            </script>'
        );
        session()->set([
            'logout' => 'true'
        ]);
        return redirect()->to('auth');
    }

    public function forgotpassword()
    {
        if (session()->get('email') != null) {
            redirect()->to('user');
        }

        $data['judul'] = "Ruang Belajar By BELIBIS | Forgot Password";
        $data['validation'] = $this->validation;
        return view('auth/forgot-password', $data);
    }
    public function forgotpassword_()
    {
        // TANGKAP EMAIL YANG DI INPUTKAN
        $email = $this->request->getVar('email');

        //CARI DATA USER BERDASARKAN EMAIL 
        $user = $this->UserModel->getuser($email);

        // CEK APAKAH USER TERSEBUT TERDAFTAR DI DATABASE

        // JIKA TIDAK ADA
        if ($user == null) {
            session()->setFlashdata(
                'pesan',
                '<script>
                    Swal.fire(
                        "Error",
                        "akun tidak ditemukan",
                        "error"
                    )
                </script>'
            );
            return redirect()->to('auth/forgotpassword');
        }

        // JIKA ADA
        // KIRIM NOTIFIKASI RESET PASSWORD VIA EMAIL
        $token = random_string('alnum', 32);
        $user_token = [
            'email' => $email,
            'token' => $token,
            'date_created' => time()
        ];

        $mail = $this->MailsettingModel->asObject()->first();
        $config['SMTPHost'] = $mail->smtp_host;
        $config['SMTPUser'] = $mail->smtp_user;
        $config['SMTPPass'] = $mail->smtp_password;
        $config['SMTPPort'] = $mail->smtp_port;
        $config['SMTPCrypto'] = $mail->smtp_crypto;

        $this->email->initialize($config);
        $this->email->setNewline("\r\n");
        $this->email->setFrom($mail->smtp_user, 'Ruang Belajar By BELIBIS');
        $this->email->setTo($email);
        $this->email->setSubject('Reset Password');
        $this->email->setMessage('
            <div style="color: #000; padding: 10px;">
                <div style="font-family: `Segoe UI`, Tahoma, Geneva, Verdana, sans-serif; font-size: 20px; color: #1C3FAA; font-weight: bold;">
                    BELIBIS
                </div>
                <small style="color: #000;">V 2.0 by BELIBIS</small>
                <br>
                <p style="font-family: `Segoe UI`, Tahoma, Geneva, Verdana, sans-serif; color: #000;">Hallo<br>
                    <span style="color: #000;">just click the button to reset your password</span></p>
                <br>
                <a href="' . base_url() . '/auth/resetpswd?email=' . $email . '&token=' . $token . '" style="display: inline-block; width: 100px; height: 30px; background: #1C3FAA; color: #fff; text-decoration: none; border-radius: 5px; text-align: center; line-height: 30px; font-family: `Segoe UI`, Tahoma, Geneva, Verdana, sans-serif;">new password</a>
                <br>
                <p>Jika Tombol tidak bisa di klik silahkan copy link di bawah ini dan tempelkan / paste di browser</p>
                <a href="' . base_url() . '/auth/resetpswd?email=' . $email . '&token=' . $token . '">
                ' . base_url() . 'auth/resetpswd?email=' . $email . '&token=' . $token . '
                </a>
            </div>
        
        ');

        // CEK APAKAH EMAIL SUDAH TERKIRIM
        if ($this->email->send()) {
            // JIKA EMAIL TERKIRIM
            // INSERT DATA TOKEN
            $this->UsertokenModel->save($user_token);
            session()->setFlashdata(
                'pesan',
                "<script>
                    Swal.fire(
                    'Success..! ',
                    'Please check your email to reset your password',
                    'success'
                    )
                </script>"
            );
            return redirect()->to('auth');
        } else {
            // JIKA EMAIL TIDAK TERKIRIM
            echo $this->email->printDebugger();
            die();
        }
    }
    public function resetpswd()
    {
        $email = $this->request->getVar('email');
        $token = $this->request->getVar('token');

        $user_token = $this->UsertokenModel->getUserToken($email, $token);
        if (!$user_token) {
            session()->setFlashdata(
                'pesan',
                "<script>
                Swal.fire(
                    'Error',
                    'Email atau Token Salah',
                    'error'
                    )
                    </script>"
            );
            return redirect()->to('auth');
        }

        // CEK KADALUARSA TOKEN
        if (time() - $user_token->date_created > (60 * 60 * 24)) {
            $this->UsertokenModel->delete($user_token->id_user);
            session()->setFlashdata(
                'pesan',
                "<script>
                Swal.fire(
                    'Error',
                    'Token kadaluarsa, silahkan ulangi proses',
                    'error'
                    )
                    </script>"
            );
            return redirect()->to('auth');
        }
        $data['judul'] = "Ruang Belajar By BELIBIS | Reset Password";
        $data['email'] = $email;
        $data['token'] = $token;
        $data['validation'] = $this->validation;
        return view('auth/reset-password', $data);
    }

    public function changepassword()
    {
        $email = $this->request->getVar('email');
        $token = $this->request->getVar('token');

        if (!$this->validate([
            'password' => [
                'rules' => 'required|matches[password2]',
                'errors' => [
                    'required' => 'Password harus di isi',
                    'matches' => 'Password Tidak Sama'
                ]
            ],
            'password2' => [
                'rules' => 'required|matches[password]',
                'errors' => [
                    'required' => 'Password harus di isi',
                    'matches' => 'Password Tidak Sama'
                ]
            ],
        ])) {
            return redirect()->to('auth/resetpswd?email=' . $email . '&token=' . $token)->withInput();
        }

        $user = $this->UserModel->getuser($email);

        $this->UserModel->save([
            'id_user' => $user->id_user,
            'password' => password_hash($this->request->getVar('password'), PASSWORD_DEFAULT)
        ]);

        session()->setFlashdata(
            'pesan',
            "<script>
                Swal.fire(
                'Success..! ',
                'Password Changed',
                'success'
                )
            </script>"
        );
        return redirect()->to('auth');
    }

    public function isUserPengajar($email, $kelas_kode) {
        // CEK APAKAH KELAS TERSEBUT ADALAH KELAS YANG DIA BUAT
        $kelas_saya = $this->KelasModel
            ->where('kode_kelas', $kelas_kode)
            ->where('email_user', $email)
            ->get()->getRowObject();

        if ($kelas_saya != null) {
            return array(
                true,
                $kelas_saya
            );
        }

        $kelas_saya = $this->UserkelasModel->getMyClassByCodeAndEmail($kelas_kode, $email);
        // CEK APAKAH KELAS INI SAYA AJAR
        if ($kelas_saya->role == 'pengajar') {
            return array(
                true,
                $kelas_saya
            );
        }

        return array(
            false,
            $kelas_saya
        );
    }
}
