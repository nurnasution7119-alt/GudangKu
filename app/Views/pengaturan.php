
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pengaturan - GudangKu</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        body {
            background: #f4f6f9;
            font-family: Arial, sans-serif;
            color: #243447;
        }

        .sidebar {
            min-height: 100vh;
            background: #243447;
            color: white;
            padding: 25px 15px;
        }

        .sidebar h4 {
            font-size: 19px;
            margin-bottom: 35px;
        }

        .sidebar a {
            display: block;
            color: #dce3eb;
            text-decoration: none;
            padding: 12px;
            border-radius: 8px;
            margin-bottom: 8px;
        }

        .sidebar a:hover,
        .sidebar a.active {
            background: #344c65;
            color: white;
        }

        .main-content {
            padding: 30px;
        }

        .topbar {
            background: white;
            padding: 20px 25px;
            border-radius: 12px;
            margin-bottom: 25px;
            box-shadow: 0 3px 12px rgba(0,0,0,0.04);
        }

        .page-card {
            background: white;
            border: none;
            border-radius: 12px;
            padding: 23px;
            margin-bottom: 20px;
            box-shadow: 0 3px 12px rgba(0,0,0,0.04);
        }

        .section-title {
            font-size: 17px;
            font-weight: bold;
            margin-bottom: 18px;
        }

        .info-label {
            color: #718096;
            font-size: 13px;
            margin-bottom: 5px;
        }

        .info-value {
            font-weight: 600;
            overflow-wrap: anywhere;
        }

        .info-item {
            padding: 13px 0;
            border-bottom: 1px solid #edf0f4;
        }

        .info-item:last-child {
            border-bottom: none;
        }

        .feature-icon {
            width: 42px;
            height: 42px;
            border-radius: 10px;
            background: #edf3fa;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 21px;
            flex-shrink: 0;
        }

        .feature-item {
            display: flex;
            gap: 13px;
            align-items: flex-start;
            padding: 13px 0;
            border-bottom: 1px solid #edf0f4;
        }

        .feature-item:last-child {
            border-bottom: none;
        }

        .feature-item p {
            margin: 4px 0 0;
            color: #718096;
            font-size: 13px;
        }

        .notice {
            background: #edf5ff;
            border-left: 4px solid #2878d0;
            padding: 15px;
            border-radius: 8px;
            color: #34516f;
            font-size: 14px;
        }

        .profile-circle {
            width: 55px;
            height: 55px;
            border-radius: 50%;
            background: #e7effa;
            color: #243f60;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 25px;
        }

        .footer {
            color: #8793a1;
            font-size: 13px;
            text-align: center;
            margin-top: 30px;
        }

        @media (max-width: 768px) {
            .sidebar {
                min-height: auto;
            }

            .main-content {
                padding: 18px;
            }
        }
    </style>
</head>

<body>

<?php
    $namaPengguna = session()->get('nama') ?? 'Pengguna';
    $rolePengguna = session()->get('role') ?? '-';
    $inisial = strtoupper(substr($namaPengguna, 0, 1));
?>

<div class="container-fluid">
    <div class="row">

        <!-- Sidebar -->
        <div class="col-md-2 sidebar">
            <h4>📦 GudangKu</h4>

            <a href="<?= base_url('dashboard') ?>">🏠 Dashboard</a>
            <a href="<?= base_url('data-barang') ?>">📦 Data Barang</a>
            <a href="<?= base_url('transaksi') ?>">🛒 Transaksi</a>
            <a href="<?= base_url('laporan') ?>">📊 Laporan</a>

            <?php if ($rolePengguna === 'owner'): ?>
                <a href="<?= base_url('manajemen-akun') ?>">👤 Manajemen Akun</a>
            <?php endif; ?>

            <a href="<?= base_url('pengaturan') ?>" class="active">⚙️ Pengaturan</a>

            <hr class="border-secondary">

            <a href="<?= base_url('logout') ?>"
               onclick="return confirm('Apakah kamu yakin ingin keluar?')">
                🚪 Keluar
            </a>
        </div>

        <!-- Konten Utama -->
        <div class="col-md-10 main-content">

            <!-- Header -->
            <div class="topbar">
                <h4 class="mb-1">⚙️ Pengaturan GudangKu</h4>
                <small class="text-muted">
                    Informasi dan pusat pengaturan aplikasi Manajemen Gudang Barang Kebutuhan Harian.
                </small>
            </div>

            <!-- Informasi Akun -->
            <div class="page-card">
                <div class="d-flex align-items-center gap-3">
                    <div class="profile-circle">
                        <?= esc($inisial) ?>
                    </div>

                    <div>
                        <h5 class="mb-1"><?= esc($namaPengguna) ?></h5>
                        <span class="badge bg-primary">
                            <?= esc(ucfirst($rolePengguna)) ?>
                        </span>
                        <div class="text-muted small mt-1">
                            Akun yang sedang digunakan
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">

                <!-- Informasi Aplikasi -->
                <div class="col-lg-6">
                    <div class="page-card h-100">
                        <h5 class="section-title">📋 Informasi Aplikasi</h5>

                        <div class="info-item">
                            <div class="info-label">Nama Aplikasi</div>
                            <div class="info-value">GudangKu</div>
                        </div>

                        <div class="info-item">
                            <div class="info-label">Jenis Aplikasi</div>
                            <div class="info-value">
                                Sistem Manajemen Gudang Barang Kebutuhan Harian
                            </div>
                        </div>

                        <div class="info-item">
                            <div class="info-label">Pengembang</div>
                            <div class="info-value">GudangKu</div>
                        </div>

                        <div class="info-item">
                            <div class="info-label">Versi Aplikasi</div>
                            <div class="info-value">1.0</div>
                        </div>

                        <div class="info-item">
                            <div class="info-label">Status Aplikasi</div>
                            <div class="info-value">
                                <span class="badge bg-success">Aktif</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Informasi Penggunaan -->
                <div class="col-lg-6">
                    <div class="page-card h-100">
                        <h5 class="section-title">📖 Informasi Penggunaan</h5>

                        <p class="text-muted">
                            GudangKu merupakan aplikasi yang digunakan untuk
                            membantu pengelolaan persediaan barang kebutuhan
                            harian agar lebih mudah, teratur, dan terpantau.
                        </p>

                        <div class="notice">
                            Gunakan menu yang tersedia pada sidebar untuk
                            mengakses setiap fitur aplikasi sesuai dengan
                            hak akses akun masing-masing.
                        </div>

                        <div class="mt-3">
                            <strong>Pengguna saat ini:</strong>
                            <p class="text-muted mb-0">
                                <?= esc($namaPengguna) ?> -
                                <?= esc(ucfirst($rolePengguna)) ?>
                            </p>
                        </div>
                    </div>
                </div>

            </div>

            <!-- Fitur Aplikasi -->
            <div class="page-card">
                <h5 class="section-title">✨ Fitur Aplikasi GudangKu</h5>

                <div class="row">

                    <div class="col-md-6">
                        <div class="feature-item">
                            <div class="feature-icon">📦</div>
                            <div>
                                <strong>Data Barang</strong>
                                <p>Mengelola informasi barang, harga, dan jumlah stok.</p>
                            </div>
                        </div>

                        <div class="feature-item">
                            <div class="feature-icon">🛒</div>
                            <div>
                                <strong>Transaksi</strong>
                                <p>Mencatat transaksi barang masuk dan barang keluar.</p>
                            </div>
                        </div>

                        <div class="feature-item">
                            <div class="feature-icon">📊</div>
                            <div>
                                <strong>Laporan</strong>
                                <p>Melihat informasi stok dan riwayat transaksi.</p>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="feature-item">
                            <div class="feature-icon">🏠</div>
                            <div>
                                <strong>Dashboard</strong>
                                <p>Melihat ringkasan data dan aktivitas terbaru.</p>
                            </div>
                        </div>

                        <?php if ($rolePengguna === 'owner'): ?>
                            <div class="feature-item">
                                <div class="feature-icon">👤</div>
                                <div>
                                    <strong>Manajemen Akun</strong>
                                    <p>Mengelola akun pengguna sesuai hak akses Owner.</p>
                                </div>
                            </div>
                        <?php endif; ?>

                        <div class="feature-item">
                            <div class="feature-icon">⚙️</div>
                            <div>
                                <strong>Pengaturan</strong>
                                <p>Melihat informasi aplikasi dan panduan penggunaan.</p>
                            </div>
                        </div>
                    </div>

                </div>
            </div>

            <!-- Keamanan -->
            <div class="page-card">
                <h5 class="section-title">🔐 Keamanan Akun</h5>

                <div class="notice">
                    <strong>Perhatikan keamanan akun Anda.</strong>
                    <ul class="mb-0 mt-2">
                        <li>Jangan membagikan kata sandi kepada orang lain.</li>
                        <li>Gunakan akun sesuai dengan hak akses yang diberikan.</li>
                        <li>Pastikan keluar dari aplikasi setelah selesai digunakan.</li>
                    </ul>
                </div>
            </div>

            <!-- Footer -->
            <div class="footer">
                © <?= date('Y') ?> GudangKu - Sistem Manajemen Gudang Barang Kebutuhan Harian.
                <br>
                Versi 1.0
            </div>

        </div>
    </div>
</div>

</body>
</html>
