<?php

namespace App\Controllers;

use App\Models\UserModel;

class Auth extends BaseController
{
    // HALAMAN PILIH LOGIN
    public function chooseLogin()
    {
        return view('auth/pilih_login');
    }

    // HALAMAN LOGIN
    public function login()
    {
        if (session()->get('logged_in')) {
            return redirect()->to('/dashboard');
        }

        $loginRole = $this->request->getGet('role');

        if (!in_array($loginRole, ['owner', 'karyawan'])) {
            return redirect()->to('/');
        }

        return view('auth/login', [
            'loginRole' => $loginRole
        ]);
    }

    // PROSES LOGIN OWNER DAN KARYAWAN
    public function attemptLogin()
    {
        $username = $this->request->getPost('username');
        $password = $this->request->getPost('password');
        $loginRole = $this->request->getPost('role');

        if (!in_array($loginRole, ['owner', 'karyawan'])) {
            return redirect()->to('/');
        }

        $userModel = new UserModel();
        $user = $userModel->where('username', $username)->first();

        if (!$user || !password_verify($password, $user['password'])) {
            return redirect()->back()
                ->with('error', 'Username atau password salah.');
        }

        if ($user['role'] !== $loginRole) {
            return redirect()->back()
                ->with('error', 'Jenis akun tidak sesuai dengan pilihan login.');
        }

        if ((int) $user['is_verified'] !== 1) {
            session()->set('pending_user_id', $user['id']);

            return redirect()->to('/verify-otp')
                ->with('error', 'Email belum diverifikasi. Silakan masukkan OTP.');
        }

        session()->regenerate();

        session()->set([
            'user_id'   => $user['id'],
            'nama'      => $user['nama'],
            'username'  => $user['username'],
            'role'      => $user['role'],
            'logged_in' => true
        ]);

        return redirect()->to('/dashboard');
    }

    // HALAMAN REGISTRASI KARYAWAN
    public function register()
    {
        return view('auth/register');
    }

    // PROSES REGISTRASI KARYAWAN
    public function attemptRegister()
    {
        $rules = [
            'nama' => 'required|min_length[3]|max_length[100]',
            'username' => 'required|min_length[3]|max_length[50]',
            'email' => 'required|valid_email|max_length[150]',
            'password' => 'required|min_length[8]'
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()
                ->withInput()
                ->with('errors', $this->validator->getErrors());
        }

        $userModel = new UserModel();

        $username = $this->request->getPost('username');
        $emailAddress = $this->request->getPost('email');

        if ($userModel->where('username', $username)->first()) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Username sudah digunakan.');
        }

        if ($userModel->where('email', $emailAddress)->first()) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Email sudah terdaftar.');
        }

        $otp = (string) random_int(100000, 999999);

        $data = [
            'nama'           => $this->request->getPost('nama'),
            'username'       => $username,
            'email'          => $emailAddress,
            'password'       => password_hash(
                $this->request->getPost('password'),
                PASSWORD_DEFAULT
            ),
            'role'           => 'karyawan',
            'is_verified'    => 0,
            'otp_code'       => $otp,
            'otp_expired_at' => date('Y-m-d H:i:s', time() + 600)
        ];

        if (!$userModel->insert($data)) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Registrasi gagal. Silakan coba lagi.');
        }

        $email = service('email');

        $email->setFrom(
            env('email.fromEmail'),
            env('email.fromName')
        );

        $email->setTo($emailAddress);
        $email->setSubject('Kode Verifikasi GudangKu');

        $message = "
            <h2>Verifikasi Akun GudangKu</h2>
            <p>Halo " . esc($data['nama']) . ",</p>
            <p>Kode OTP kamu adalah:</p>
            <h1>{$otp}</h1>
            <p>Kode ini berlaku selama 10 menit.</p>
            <p>Jangan berikan kode ini kepada orang lain.</p>
        ";

        $email->setMessage($message);

        if (!$email->send()) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Email OTP gagal dikirim. Periksa konfigurasi email.');
        }

        session()->set('pending_user_id', $userModel->getInsertID());

        return redirect()->to('/verify-otp')
            ->with('success', 'Kode OTP sudah dikirim ke email kamu.');
    }

    // HALAMAN VERIFIKASI OTP
    public function verifyOtp()
    {
        if (!session()->get('pending_user_id')) {
            return redirect()->to('/register');
        }

        return view('auth/verify_otp');
    }

    // PROSES VERIFIKASI OTP
    public function attemptVerifyOtp()
    {
        $userId = session()->get('pending_user_id');
        $otp = $this->request->getPost('otp');

        if (!$userId) {
            return redirect()->to('/register');
        }

        $userModel = new UserModel();
        $user = $userModel->find($userId);

        if (!$user) {
            return redirect()->to('/register');
        }

        if (
            empty($user['otp_expired_at']) ||
            strtotime($user['otp_expired_at']) < time()
        ) {
            return redirect()->back()
                ->with('error', 'Kode OTP sudah kedaluwarsa.');
        }

        if (!hash_equals((string) $user['otp_code'], (string) $otp)) {
            return redirect()->back()
                ->with('error', 'Kode OTP salah.');
        }

        $userModel->update($userId, [
            'is_verified' => 1,
            'otp_code' => null,
            'otp_expired_at' => null
        ]);

        session()->remove('pending_user_id');

        return redirect()->to('/login')
            ->with('success', 'Email berhasil diverifikasi. Silakan login.');
    }

    // HALAMAN LUPA PASSWORD
    public function forgotPassword()
    {
        return view('auth/forgot_password');
    }

    // KIRIM OTP RESET PASSWORD
    public function sendResetOtp()
    {
        $emailAddress = trim($this->request->getPost('email'));

        if (!filter_var($emailAddress, FILTER_VALIDATE_EMAIL)) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Masukkan alamat email yang valid.');
        }

        $userModel = new UserModel();

        $user = $userModel->where('email', $emailAddress)
            ->where('is_verified', 1)
            ->first();

        if (!$user) {
            return redirect()->back()
                ->with('error', 'Akun tidak ditemukan atau email belum diverifikasi.');
        }

        $otp = (string) random_int(100000, 999999);

        $userModel->update($user['id'], [
            'otp_code' => $otp,
            'otp_expired_at' => date('Y-m-d H:i:s', time() + 600)
        ]);

        $email = service('email');

        $email->setFrom(
            env('email.fromEmail'),
            env('email.fromName')
        );

        $email->setTo($emailAddress);
        $email->setSubject('Reset Password GudangKu');

        $message = "
            <h2>Reset Password GudangKu</h2>
            <p>Halo " . esc($user['nama']) . ",</p>
            <p>Kode OTP untuk mengganti password kamu adalah:</p>
            <h1>{$otp}</h1>
            <p>Kode berlaku selama 10 menit.</p>
            <p>Jika kamu tidak meminta reset password, abaikan email ini.</p>
            <p>Jangan berikan kode ini kepada orang lain.</p>
        ";

        $email->setMessage($message);

        if (!$email->send()) {
            $userModel->update($user['id'], [
                'otp_code' => null,
                'otp_expired_at' => null
            ]);

            return redirect()->back()
                ->with('error', 'OTP gagal dikirim. Silakan coba lagi.');
        }

        session()->set('password_reset_user_id', $user['id']);

        return redirect()->to('/reset-password')
            ->with('success', 'Kode OTP berhasil dikirim ke Gmail kamu.');
    }

    // HALAMAN RESET PASSWORD
    public function resetPassword()
    {
        if (!session()->get('password_reset_user_id')) {
            return redirect()->to('/forgot-password');
        }

        return view('auth/reset_password');
    }

    // SIMPAN PASSWORD BARU
    public function attemptResetPassword()
    {
        $userId = session()->get('password_reset_user_id');
        $otp = $this->request->getPost('otp');
        $password = $this->request->getPost('password');

        if (!$userId) {
            return redirect()->to('/forgot-password');
        }

        $rules = [
            'otp' => 'required|exact_length[6]|numeric',
            'password' => 'required|min_length[8]',
            'password_confirm' => 'required|matches[password]'
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()
                ->withInput()
                ->with('errors', $this->validator->getErrors());
        }

        $userModel = new UserModel();
        $user = $userModel->find($userId);

        if (!$user) {
            session()->remove('password_reset_user_id');

            return redirect()->to('/forgot-password');
        }

        if (
            empty($user['otp_expired_at']) ||
            strtotime($user['otp_expired_at']) < time()
        ) {
            return redirect()->back()
                ->with('error', 'Kode OTP sudah kedaluwarsa. Silakan minta kode baru.');
        }

        if (!hash_equals((string) $user['otp_code'], (string) $otp)) {
            return redirect()->back()
                ->with('error', 'Kode OTP salah.');
        }

        $userModel->update($userId, [
            'password' => password_hash($password, PASSWORD_DEFAULT),
            'otp_code' => null,
            'otp_expired_at' => null
        ]);

        session()->remove('password_reset_user_id');

        return redirect()->to('/')
            ->with('success', 'Password berhasil diubah. Silakan login kembali.');
    }

    // LOGOUT
    public function logout()
    {
        session()->destroy();

        return redirect()->to('/');
    }
}
