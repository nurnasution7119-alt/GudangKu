<?php

namespace App\Controllers;

use App\Models\UserModel;

class OwnerSetup extends BaseController
{
    public function index()
    {
        $userModel = new UserModel();

        // Cek apakah akun Owner sudah ada
        $ownerAda = $userModel
            ->where('role', 'owner')
            ->first();

        if ($ownerAda) {
            return redirect()->to('/')
                ->with('error', 'Akun Owner sudah tersedia. Halaman pendaftaran ditutup.');
        }

        return view('auth/owner_setup');
    }

    public function create()
    {
        $userModel = new UserModel();

        // Pastikan belum ada akun Owner
        $ownerAda = $userModel
            ->where('role', 'owner')
            ->first();

        if ($ownerAda) {
            return redirect()->to('/')
                ->with('error', 'Akun Owner sudah tersedia.');
        }

        $rules = [
            'nama' => 'required|min_length[3]|max_length[100]',
            'username' => 'required|min_length[4]|max_length[50]|is_unique[users.username]',
            'email' => 'required|valid_email|is_unique[users.email]',
            'password' => 'required|min_length[8]',
            'konfirmasi_password' => 'required|matches[password]',
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()
                ->withInput()
                ->with('errors', $this->validator->getErrors());
        }

        $userModel->insert([
            'nama' => $this->request->getPost('nama'),
            'username' => $this->request->getPost('username'),
            'email' => $this->request->getPost('email'),
            'password' => password_hash(
                $this->request->getPost('password'),
                PASSWORD_DEFAULT
            ),
            'role' => 'owner',
            'is_verified' => 1,
        ]);

        return redirect()->to('/login?role=owner')
            ->with('success', 'Akun Owner berhasil dibuat. Silakan login.');
    }
}
