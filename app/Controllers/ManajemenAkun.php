<?php

namespace App\Controllers;

use App\Models\UserModel;

class ManajemenAkun extends BaseController
{
    protected $userModel;

    public function __construct()
    {
        $this->userModel = new UserModel();
    }

    // Memastikan hanya Owner yang boleh masuk
    private function cekOwner()
    {
        if (!session()->get('logged_in')) {
            return redirect()->to('/');
        }

        if (session()->get('role') !== 'owner') {
            return redirect()->to('/dashboard')
                ->with('error', 'Kamu tidak memiliki akses ke halaman ini.');
        }

        return null;
    }

    // Menampilkan daftar akun Karyawan
    public function index()
    {
        $akses = $this->cekOwner();

        if ($akses !== null) {
            return $akses;
        }

        $data['karyawan'] = $this->userModel
            ->where('role', 'karyawan')
            ->findAll();

        return view('manajemen_akun/index', $data);
    }

    // Menampilkan formulir tambah Karyawan
    public function tambah()
    {
        $akses = $this->cekOwner();

        if ($akses !== null) {
            return $akses;
        }

        return view('manajemen_akun/tambah');
    }

    // Menyimpan akun Karyawan baru
    public function simpan()
    {
        $akses = $this->cekOwner();

        if ($akses !== null) {
            return $akses;
        }

        $rules = [
            'nama' => 'required|min_length[3]|max_length[100]',
            'username' => 'required|min_length[4]|max_length[50]|is_unique[users.username]',
            'email' => 'permit_empty|valid_email|is_unique[users.email]',
            'password' => 'required|min_length[8]',
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()
                ->withInput()
                ->with('errors', $this->validator->getErrors());
        }

        $this->userModel->insert([
            'nama' => $this->request->getPost('nama'),
            'username' => $this->request->getPost('username'),
            'email' => $this->request->getPost('email') ?: null,
            'password' => password_hash(
                $this->request->getPost('password'),
                PASSWORD_DEFAULT
            ),
            'role' => 'karyawan',
            'is_verified' => 1,
        ]);

        return redirect()->to('/manajemen-akun')
            ->with('success', 'Akun Karyawan berhasil ditambahkan.');
    }

    // Menampilkan halaman edit Karyawan
    public function edit($id)
    {
        $akses = $this->cekOwner();

        if ($akses !== null) {
            return $akses;
        }

        $karyawan = $this->userModel
            ->where('id', $id)
            ->where('role', 'karyawan')
            ->first();

        if (!$karyawan) {
            return redirect()->to('/manajemen-akun')
                ->with('error', 'Akun Karyawan tidak ditemukan.');
        }

        return view('manajemen_akun/edit', [
            'karyawan' => $karyawan
        ]);
    }

    // Menyimpan perubahan akun Karyawan
    public function update($id)
    {
        $akses = $this->cekOwner();

        if ($akses !== null) {
            return $akses;
        }

        $karyawan = $this->userModel
            ->where('id', $id)
            ->where('role', 'karyawan')
            ->first();

        if (!$karyawan) {
            return redirect()->to('/manajemen-akun')
                ->with('error', 'Akun Karyawan tidak ditemukan.');
        }

        $rules = [
            'nama' => 'required|min_length[3]|max_length[100]',
            'username' => "required|min_length[4]|max_length[50]|is_unique[users.username,id,$id]",
            'email' => "permit_empty|valid_email|is_unique[users.email,id,$id]",
        ];

        $password = $this->request->getPost('password');

        if ($password !== '') {
            $rules['password'] = 'min_length[8]';
        }

        if (!$this->validate($rules)) {
            return redirect()->back()
                ->withInput()
                ->with('errors', $this->validator->getErrors());
        }

        $data = [
            'nama' => $this->request->getPost('nama'),
            'username' => $this->request->getPost('username'),
            'email' => $this->request->getPost('email') ?: null,
        ];

        if ($password !== '') {
            $data['password'] = password_hash(
                $password,
                PASSWORD_DEFAULT
            );
        }

        $this->userModel->update($id, $data);

        return redirect()->to('/manajemen-akun')
            ->with('success', 'Akun Karyawan berhasil diperbarui.');
    }

    // Menghapus akun Karyawan
    public function hapus($id)
    {
        $akses = $this->cekOwner();

        if ($akses !== null) {
            return $akses;
        }

        $karyawan = $this->userModel
            ->where('id', $id)
            ->where('role', 'karyawan')
            ->first();

        if (!$karyawan) {
            return redirect()->to('/manajemen-akun')
                ->with('error', 'Akun Karyawan tidak ditemukan.');
        }

        if ((int) $id === (int) session()->get('user_id')) {
            return redirect()->to('/manajemen-akun')
                ->with('error', 'Akun yang sedang digunakan tidak dapat dihapus.');
        }

        $this->userModel->delete($id);

        return redirect()->to('/manajemen-akun')
            ->with('success', 'Akun Karyawan berhasil dihapus.');
    }
}
