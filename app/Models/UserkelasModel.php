<?php

namespace App\Models;

use CodeIgniter\Model;

class UserkelasModel extends Model
{
    protected $table = 'user_kelas';
    protected $primaryKey = 'id_user_kelas';
    protected $allowedFields = ['kelas_kode', 'email', 'nama', 'role'];

    public function getMyClass($email)
    {
        return $this
            ->join('kelas', 'kelas.kode_kelas=user_kelas.kelas_kode')
            ->where('user_kelas.email', $email)
            ->get()->getResultObject();
    }

    public function getMyClassByCodeAndEmail($kelas_kode, $email)
    {
        return $this
            ->join('kelas', 'kelas.kode_kelas=user_kelas.kelas_kode')
            ->where('user_kelas.kelas_kode', $kelas_kode)
            ->where('user_kelas.email', $email)
            ->get()->getRowObject();
    }

    public function getAllbyClass($kelas_kode)
    {
        return $this
            ->join('kelas', 'kelas.kode_kelas=user_kelas.kelas_kode')
            ->where('user_kelas.kelas_kode', $kelas_kode)
            ->get()->getResultObject();
    }

    public function getAllStudentsByClass($kelas_kode) {
        return $this->db
            ->query(
                "SELECT user.email, user.nama, user.gambar 
                FROM user_kelas INNER JOIN user 
                ON user_kelas.email=user.email 
                WHERE user_kelas.kelas_kode='$kelas_kode' AND user_kelas.role='pelajar'
            ")->getResultObject();
    }

    public function getAllTeachersByClass($kelas_kode) {
        return $this->db
            ->query(
                "SELECT user.email, user.nama, user.gambar 
                FROM user_kelas INNER JOIN user 
                ON user_kelas.email=user.email 
                WHERE user_kelas.kelas_kode='$kelas_kode' AND user_kelas.role='pengajar'
            ")->getResultObject();
    }

    public function addMember($kelas_kode, $email, $nama, $role) {
        // Return false if user is already in a class
        $tmp = $this->db
            ->query(
                "SELECT user.email, user.nama, user.gambar 
                FROM user_kelas INNER JOIN user 
                ON user_kelas.email=user.email 
                WHERE user_kelas.kelas_kode='$kelas_kode' AND user_kelas.email='$email'
                ")->getResultObject();
        if ($tmp) {
            return false;
        }
        return $this->insert(array(
            'kelas_kode' => $kelas_kode,
            'email' => $email,
            'nama' => $nama,
            'role' => $role
        ), false);
    }

    public function changeStudentRole($kode_kelas, $email_user) {
        return $this->db
            ->query("
                UPDATE $this->table SET role='pengajar' WHERE kelas_kode='$kode_kelas' AND email='$email_user'
            ");
    }
}
