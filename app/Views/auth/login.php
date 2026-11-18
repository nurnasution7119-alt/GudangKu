
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - GudangKu</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        body {
            min-height: 100vh;
            background: linear-gradient(135deg, #e8eaff, #f8f9ff);
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: Arial, sans-serif;
        }

        .login-card {
            width: 100%;
            max-width: 420px;
            padding: 35px;
            border-radius: 18px;
            background: white;
            box-shadow: 0 8px 30px rgba(0,0,0,0.08);
        }

        .logo {
            width: 60px;
            height: 60px;
            background: #5146e5;
            color: white;
            border-radius: 15px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 28px;
            margin: 0 auto 15px;
        }
    </style>
</head>

<body>

<?php
    $loginRole = $loginRole ?? 'karyawan';
    $judulLogin = $loginRole === 'owner' ? 'Login Owner' : 'Login Karyawan';
?>

<div class="login-card">

    <div class="text-center mb-4">
        <div class="logo">G</div>
        <h3 class="fw-bold">GudangKu</h3>
        <p class="text-muted"><?= esc($judulLogin) ?></p>
        <small class="text-secondary">
            Silakan masuk menggunakan akun Anda
        </small>
    </div>

    <?php if (session()->getFlashdata('error')): ?>
        <div class="alert alert-danger">
            <?= esc(session()->getFlashdata('error')) ?>
        </div>
    <?php endif; ?>

    <?php if (session()->getFlashdata('success')): ?>
        <div class="alert alert-success">
            <?= esc(session()->getFlashdata('success')) ?>
        </div>
    <?php endif; ?>

    <form action="<?= base_url('login') ?>" method="post">
        <?= csrf_field() ?>

        <input type="hidden" name="role" value="<?= esc($loginRole) ?>">

        <div class="mb-3">
            <label class="form-label">Username</label>
            <input
                type="text"
                name="username"
                class="form-control"
                placeholder="Masukkan username"
                required
            >
        </div>

        <div class="mb-3">
            <label class="form-label">Password</label>

            <div class="input-group">
                <input
                    type="password"
                    name="password"
                    id="password"
                    class="form-control"
                    placeholder="Masukkan password"
                    required
                >

                <button
                    class="btn btn-outline-secondary"
                    type="button"
                    onclick="togglePassword()"
                >
                    👁️
                </button>
            </div>
        </div>

        <div class="text-end mb-3">
            <a href="<?= base_url('forgot-password') ?>"
               class="text-decoration-none">
                Lupa Password?
            </a>
        </div>

        <button type="submit" class="btn btn-primary w-100">
            Masuk
        </button>
    </form>

    <?php if ($loginRole === 'karyawan'): ?>
        <p class="text-center mt-4 mb-0">
            Belum punya akun?
            <a href="<?= base_url('register') ?>">Daftar di sini</a>
        </p>
    <?php endif; ?>

    <div class="text-center mt-3">
        <a href="<?= base_url('/') ?>" class="text-decoration-none">
            ← Kembali ke pilihan login
        </a>
    </div>

</div>

<script>
function togglePassword() {
    const password = document.getElementById('password');

    password.type = password.type === 'password'
        ? 'text'
        : 'password';
}
</script>

</body>
</html>
