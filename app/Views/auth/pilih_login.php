
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pilih Login - GudangKu</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        body {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            background: #f1f5f9;
            font-family: Arial, sans-serif;
        }

        .login-box {
            width: 100%;
            max-width: 430px;
            background: white;
            padding: 35px;
            border-radius: 18px;
            box-shadow: 0 8px 30px rgba(0,0,0,0.08);
            text-align: center;
        }

        .logo {
            font-size: 42px;
            margin-bottom: 10px;
        }

        .login-option {
            display: block;
            text-decoration: none;
            color: #1e293b;
            padding: 20px;
            margin-top: 18px;
            border: 1px solid #e2e8f0;
            border-radius: 12px;
            text-align: left;
            transition: 0.2s;
        }

        .login-option:hover {
            border-color: #2563eb;
            background: #f8fafc;
            transform: translateY(-2px);
        }

        .login-option h5 {
            margin-bottom: 6px;
            font-weight: bold;
        }

        .login-option p {
            margin: 0;
            color: #64748b;
            font-size: 14px;
        }

        .owner {
            color: #15803d;
        }

        .karyawan {
            color: #2563eb;
        }
    </style>
</head>

<body>

<div class="login-box">

    <div class="logo">📦</div>

    <h2 class="fw-bold">GudangKu</h2>

    <p class="text-secondary">
        Sistem Informasi Manajemen Gudang
    </p>

    <hr>

    <h5 class="mb-3">Silakan Pilih Jenis Akun</h5>

    <a href="<?= base_url('login?role=owner') ?>" class="login-option">
        <h5 class="owner">🛡️ Login Owner</h5>
        <p>Masuk sebagai pemilik dan pengelola gudang.</p>
    </a>

    <a href="<?= base_url('login?role=karyawan') ?>" class="login-option">
        <h5 class="karyawan">👥 Login Karyawan</h5>
        <p>Masuk sebagai karyawan gudang.</p>
    </a>

    <div class="mt-4">
        <small class="text-secondary">
            GudangKu &copy; <?= date('Y') ?>
        </small>
    </div>

</div>

</body>
</html>
