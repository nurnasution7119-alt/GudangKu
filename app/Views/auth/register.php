
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registrasi - GudangKu</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        body {
            min-height: 100vh;
            background: linear-gradient(135deg, #e8eaff, #f8f9ff);
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: Arial, sans-serif;
            padding: 20px;
        }

        .register-card {
            width: 100%;
            max-width: 440px;
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

<div class="register-card">

    <div class="text-center mb-4">
        <div class="logo">G</div>
        <h3 class="fw-bold">Buat Akun GudangKu</h3>
        <p class="text-muted">Daftarkan akun karyawan baru</p>
    </div>

    <?php if (session()->getFlashdata('error')): ?>
        <div class="alert alert-danger">
            <?= esc(session()->getFlashdata('error')) ?>
        </div>
    <?php endif; ?>

    <?php if (session()->getFlashdata('errors')): ?>
        <div class="alert alert-danger">
            <?php foreach (session()->getFlashdata('errors') as $error): ?>
                <div><?= esc($error) ?></div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>

    <form action="<?= base_url('register') ?>" method="post">
        <?= csrf_field() ?>

        <div class="mb-3">
            <label class="form-label">Nama Lengkap</label>
            <input type="text" name="nama" class="form-control"
                   placeholder="Masukkan nama lengkap"
                   value="<?= esc(old('nama')) ?>" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Username</label>
            <input type="text" name="username" class="form-control"
                   placeholder="Buat username"
                   value="<?= esc(old('username')) ?>" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Email</label>
            <input type="email" name="email" class="form-control"
                   placeholder="Masukkan alamat email"
                   value="<?= esc(old('email')) ?>" required>
        </div>

        <div class="mb-4">
            <label class="form-label">Password</label>

            <div class="input-group">
                <input type="password" name="password"
                       id="registerPassword"
                       class="form-control"
                       placeholder="Buat password minimal 8 karakter"
                       minlength="8" required>

                <button class="btn btn-outline-secondary"
                        type="button"
                        onclick="toggleRegisterPassword()">
                    👁️
                </button>
            </div>
        </div>

        <button type="submit" class="btn btn-primary w-100">
            Daftar Akun
        </button>
    </form>

    <p class="text-center mt-4 mb-0">
        Sudah punya akun?
        <a href="<?= base_url('login') ?>">Login di sini</a>
    </p>

</div>

<script>
function toggleRegisterPassword() {
    const password = document.getElementById('registerPassword');

    password.type = password.type === 'password'
        ? 'text'
        : 'password';
}
</script>

</body>
</html>
