<?php

namespace App\Controllers;

class Pengaturan extends BaseController
{
    public function index()
    {
        // Cek login
        if (!session()->get('logged_in')) {
            return redirect()->to('/');
        }

        // Cek role
        $role = session()->get('role');

        if (!in_array($role, ['owner', 'karyawan'])) {
            session()->destroy();
            return redirect()->to('/');
        }

        return view('pengaturan');
    }
}
