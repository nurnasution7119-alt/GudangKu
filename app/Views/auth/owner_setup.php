
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pendaftaran Owner - GudangKu</title>

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
            max-width: 480px;
            padding: 35px;
            border-radius: 18px;
            background: white;
            box-shadow: 0 8px 30px rgba(0,0,0,0.08);
        }
    </style>
</head>

<body>

<div class="register-card">

    <div class="text-center mb-4">
        <h2 class="fw-bold">📦 GudangKu</h2>
        <h4 class="mt-3">Pendaftaran Owner</h4>
        <p class="text-muted">Buat akun pemilik gudang pertama.</p>
    </div>

    <?php if (session()->getFlashdata('errors')): ?>
        <div class="alert alert-danger">
            <?php foreach (session()->getFlashdata('errors') as $error): ?>
                <div><?= esc($error) ?></div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>

    <?php if (session()->getFlashdata('error')): ?>
        <div class="alert alert-danger">
            <?= esc(session()->getFlashdata('error')) ?>
        </div>
    <?php endif; ?>

    <form action="<?= base_url('owner-setup') ?>" method="post">
        <?= csrf_field() ?>

        <div class="mb-3">
            <label class="form-label">Nama Lengkap</label>
            <input type="text" name="nama" class="form-control"
                   value="<?= old('nama') ?>"
                   placeholder="Masukkan nama lengkap" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Username</label>
            <input type="text" name="username" class="form-control"
                   value="<?= old('username') ?>"
                   placeholder="Buat username" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Email</label>
            <input type="email" name="email" class="form-control"
                   value="<?= old('email') ?>"
                   placeholder="Masukkan email" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Password</label>
            <div class="input-group">
                <input type="password" name="password" id="password"
                       class="form-control"
                       placeholder="Minimal 8 karakter" required>

                <button class="btn btn-outline-secondary" type="button"
                        onclick="togglePassword('password')">
                    👁️
                </button>
            </div>
        </div>

        <div class="mb-4">
            <label class="form-label">Konfirmasi Password</label>
            <div class="input-group">
                <input type="password" name="konfirmasi_password"
                       id="konfirmasi_password"
                       class="form-control"
                       placeholder="Ulangi password" required>

                <button class="btn btn-outline-secondary" type="button"
                        onclick="togglePassword('konfirmasi_password')">
                    👁️
                </button>
            </div>
        </div>

        <button type="submit" class="btn btn-primary w-100">
            Buat Akun Owner
        </button>
    </form>

</div>

<script>
function togglePassword(id) {
    const password = document.getElementById(id);

    password.type = password.type === 'password'
        ? 'text'
        : 'password';
}
</script>

</body>
</html>
