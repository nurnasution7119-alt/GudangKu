
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password - GudangKu</title>

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

        .reset-card {
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

<div class="reset-card">

    <div class="text-center mb-4">
        <div class="logo">G</div>
        <h3 class="fw-bold">Reset Password</h3>
        <p class="text-muted">
            Masukkan kode OTP dan buat password baru.
        </p>
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

    <?php if (session()->getFlashdata('errors')): ?>
        <div class="alert alert-danger">
            <?php foreach (session()->getFlashdata('errors') as $error): ?>
                <div><?= esc($error) ?></div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>

    <form action="<?= base_url('reset-password') ?>" method="post">
        <?= csrf_field() ?>

        <div class="mb-3">
            <label class="form-label">Kode OTP</label>
            <input type="text"
                   name="otp"
                   class="form-control"
                   placeholder="Masukkan 6 digit OTP"
                   maxlength="6"
                   inputmode="numeric"
                   pattern="[0-9]{6}"
                   required>
        </div>

        <div class="mb-3">
            <label class="form-label">Password Baru</label>
            <div class="input-group">
                <input type="password"
                       name="password"
                       id="newPassword"
                       class="form-control"
                       placeholder="Minimal 8 karakter"
                       minlength="8"
                       required>

                <button type="button"
                        class="btn btn-outline-secondary"
                        onclick="togglePassword('newPassword')">
                    👁️
                </button>
            </div>
        </div>

        <div class="mb-4">
            <label class="form-label">Konfirmasi Password Baru</label>
            <div class="input-group">
                <input type="password"
                       name="password_confirm"
                       id="confirmPassword"
                       class="form-control"
                       placeholder="Ulangi password baru"
                       minlength="8"
                       required>

                <button type="button"
                        class="btn btn-outline-secondary"
                        onclick="togglePassword('confirmPassword')">
                    👁️
                </button>
            </div>
        </div>

        <button type="submit" class="btn btn-primary w-100">
            Simpan Password Baru
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
