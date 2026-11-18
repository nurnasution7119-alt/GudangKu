
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lupa Password - GudangKu</title>

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

        .forgot-card {
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

<div class="forgot-card">

    <div class="text-center mb-4">
        <div class="logo">G</div>
        <h3 class="fw-bold">Lupa Password?</h3>
        <p class="text-muted">
            Masukkan email yang terdaftar untuk menerima kode OTP.
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

    <form action="<?= base_url('forgot-password') ?>" method="post">
        <?= csrf_field() ?>

        <div class="mb-4">
            <label class="form-label">Alamat Gmail</label>
            <input type="email"
                   name="email"
                   class="form-control"
                   placeholder="Masukkan email terdaftar"
                   required>
        </div>

        <button type="submit" class="btn btn-primary w-100">
            Kirim Kode OTP
        </button>
    </form>

    <p class="text-center mt-4 mb-0">
        <a href="<?= base_url('login') ?>">Kembali ke Login</a>
    </p>

</div>

</body>
</html>
