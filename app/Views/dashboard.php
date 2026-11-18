
<?php
$labelsGrafik = $grafik_labels ?? [];
$dataMasuk = $grafik_masuk ?? [];
$dataKeluar = $grafik_keluar ?? [];

$totalHarian = [];

foreach ($dataMasuk as $i => $masuk) {
    $totalHarian[] = (int) $masuk + (int) ($dataKeluar[$i] ?? 0);
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - GudangKu</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

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
            padding: 18px 25px;
            border-radius: 12px;
            margin-bottom: 25px;
            box-shadow: 0 3px 12px rgba(0,0,0,0.04);
        }

        .stat-card,
        .content-card {
            background: white;
            border: none;
            border-radius: 12px;
            padding: 22px;
            box-shadow: 0 3px 12px rgba(0,0,0,0.05);
        }

        .stat-card {
            height: 100%;
        }

        .stat-number {
            font-size: 28px;
            font-weight: bold;
        }

        .content-card {
            margin-top: 24px;
        }

        .chart-container {
            position: relative;
            height: 300px;
            width: 100%;
        }

        .shortcut {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            padding: 13px;
            border-radius: 9px;
            text-decoration: none;
            font-weight: bold;
            transition: 0.2s;
        }

        .shortcut:hover {
            opacity: 0.85;
            transform: translateY(-2px);
        }

        .shortcut-blue {
            background: #e9f1ff;
            color: #2463b5;
        }

        .shortcut-green {
            background: #e7f7ed;
            color: #198754;
        }

        .stock-low {
            background: #fff5e5;
            color: #a76500;
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

            .chart-container {
                height: 250px;
            }
        }
    </style>
</head>

<body>

<div class="container-fluid">
    <div class="row">

        <!-- SIDEBAR -->
        <div class="col-md-2 sidebar">
            <h4>📦 GudangKu</h4>

            <a href="<?= base_url('dashboard') ?>" class="active">🏠 Dashboard</a>
            <a href="<?= base_url('data-barang') ?>">📦 Data Barang</a>
            <a href="<?= base_url('transaksi') ?>">🛒 Transaksi</a>
            <a href="<?= base_url('laporan') ?>">📊 Laporan</a>

            <?php if (session()->get('role') === 'owner'): ?>
                <a href="<?= base_url('manajemen-akun') ?>">👤 Manajemen Akun</a>
            <?php endif; ?>

            <a href="<?= base_url('pengaturan') ?>">⚙️ Pengaturan</a>

            <hr class="border-secondary">

            <a href="<?= base_url('logout') ?>"
               onclick="return confirm('Apakah kamu yakin ingin keluar?')">
                🚪 Keluar
            </a>
        </div>

        <!-- KONTEN -->
        <div class="col-md-10 main-content">

            <!-- HEADER -->
            <div class="topbar d-flex justify-content-between align-items-center flex-wrap gap-3">
                <div>
                    <h4 class="mb-1">Dashboard</h4>
                    <small class="text-muted">
                        Sistem Manajemen Gudang Barang Kebutuhan Harian
                    </small>
                </div>

                <span class="badge bg-primary p-2">
                    <?= esc(session()->get('nama')) ?>
                    - <?= ucfirst(esc(session()->get('role'))) ?>
                </span>
            </div>

            <!-- RINGKASAN -->
            <h5 class="mb-3">Ringkasan Data Gudang</h5>

            <div class="row g-3">

                <div class="col-md-3 col-6">
                    <div class="stat-card">
                        <p class="text-muted mb-2">Total Barang</p>
                        <div class="stat-number text-primary">
                            <?= number_format($total_barang ?? 0, 0, ',', '.') ?>
                        </div>
                        <small class="text-muted">Jenis barang tersedia</small>
                    </div>
                </div>

                <div class="col-md-3 col-6">
                    <div class="stat-card">
                        <p class="text-muted mb-2">Total Stok</p>
                        <div class="stat-number text-success">
                            <?= number_format($total_stok ?? 0, 0, ',', '.') ?>
                        </div>
                        <small class="text-muted">Jumlah seluruh stok</small>
                    </div>
                </div>

                <div class="col-md-3 col-6">
                    <div class="stat-card">
                        <p class="text-muted mb-2">Transaksi Hari Ini</p>
                        <div class="stat-number text-warning">
                            <?= number_format($transaksi_hari_ini ?? 0, 0, ',', '.') ?>
                        </div>
                        <small class="text-muted">Transaksi tercatat</small>
                    </div>
                </div>

                <div class="col-md-3 col-6">
                    <div class="stat-card">
                        <p class="text-muted mb-2">Barang Hampir Habis</p>
                        <div class="stat-number text-danger">
                            <?= number_format($barang_hampir_habis ?? 0, 0, ',', '.') ?>
                        </div>
                        <small class="text-muted">Stok 5 atau kurang</small>
                    </div>
                </div>

            </div>

            <!-- NOTIFIKASI STOK -->
            <div class="content-card">
                <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mb-3">
                    <h5 class="mb-0">🔔 Notifikasi Stok Barang</h5>

                    <?php if (($total_barang_stok_menipis ?? 0) > 0): ?>
                        <span class="badge bg-warning text-dark">
                            <?= $total_barang_stok_menipis ?> barang perlu diperhatikan
                        </span>
                    <?php else: ?>
                        <span class="badge bg-success">
                            Stok dalam kondisi aman
                        </span>
                    <?php endif; ?>
                </div>

                <?php if (!empty($barang_stok_menipis)): ?>

                    <div class="alert alert-warning">
                        Beberapa barang memiliki stok 5 atau kurang.
                        Periksa daftar berikut dan lakukan pengisian stok jika diperlukan.
                    </div>

                    <div class="table-responsive">
                        <table class="table table-bordered table-hover align-middle">
                            <thead class="table-light">
                                <tr>
                                    <th>No</th>
                                    <th>Kode Barang</th>
                                    <th>Nama Barang</th>
                                    <th>Stok Tersisa</th>
                                    <th>Status</th>
                                </tr>
                            </thead>

                            <tbody>
                                <?php $noStok = 1; ?>

                                <?php foreach ($barang_stok_menipis as $barang): ?>
                                    <tr>
                                        <td><?= $noStok++ ?></td>
                                        <td><?= esc($barang['kode_barang']) ?></td>
                                        <td><?= esc($barang['nama_barang']) ?></td>
                                        <td>
                                            <strong class="text-danger">
                                                <?= number_format($barang['stok'], 0, ',', '.') ?>
                                            </strong>
                                        </td>
                                        <td>
                                            <?php if ((int) $barang['stok'] <= 0): ?>
                                                <span class="badge bg-danger">Stok Habis</span>
                                            <?php else: ?>
                                                <span class="badge bg-warning text-dark">Stok Menipis</span>
                                            <?php endif; ?>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>

                <?php else: ?>

                    <div class="alert alert-success mb-0">
                        <strong>Semua stok aman!</strong>
                        Saat ini tidak ada barang dengan stok 5 atau kurang.
                    </div>

                <?php endif; ?>
            </div>

            <!-- TOMBOL PINTASAN -->
            <div class="content-card">
                <h5 class="mb-3">⚡ Pintasan Menu</h5>

                <div class="row g-3">
                    <div class="col-md-6">
                        <a href="<?= base_url('data-barang') ?>"
                           class="shortcut shortcut-blue">
                            📦 Lihat Data Barang
                        </a>
                    </div>

                    <div class="col-md-6">
                        <a href="<?= base_url('transaksi') ?>"
                           class="shortcut shortcut-green">
                            🛒 Kelola Transaksi
                        </a>
                    </div>
                </div>
            </div>

            <!-- GRAFIK -->
            <div class="content-card">
                <h5 class="mb-1">📊 Grafik Barang Masuk dan Keluar</h5>
                <small class="text-muted">
                    Jumlah transaksi selama 7 hari terakhir
                </small>

                <div class="chart-container mt-4">
                    <canvas id="grafikBarang"></canvas>
                </div>
            </div>

            <div class="content-card">
                <h5 class="mb-1">📈 Grafik Perkembangan Transaksi</h5>
                <small class="text-muted">
                    Total transaksi masuk dan keluar setiap hari
                </small>

                <div class="chart-container mt-4">
                    <canvas id="grafikTotal"></canvas>
                </div>
            </div>

            <!-- SELAMAT DATANG -->
            <div class="content-card">
                <h5>Selamat Datang!</h5>
                <p class="text-muted mb-0">
                    Selamat datang di aplikasi Manajemen Gudang Barang
                    Kebutuhan Harian. Melalui aplikasi ini, pengelolaan
                    barang dan transaksi dapat dilakukan dengan lebih
                    mudah dan teratur.
                </p>
            </div>

            <!-- AKTIVITAS TERBARU -->
            <div class="content-card">
                <h5 class="mb-3">🕒 Aktivitas Terbaru</h5>

                <div class="table-responsive">
                    <table class="table table-bordered table-hover align-middle">
                        <thead class="table-light">
                            <tr>
                                <th>No</th>
                                <th>Aktivitas</th>
                                <th>Akun</th>
                                <th>Tanggal</th>
                            </tr>
                        </thead>

                        <tbody>
                            <?php if (!empty($aktivitas_terbaru)): ?>

                                <?php $no = 1; ?>

                                <?php foreach ($aktivitas_terbaru as $aktivitas): ?>
                                    <tr>
                                        <td><?= $no++ ?></td>

                                        <td>
                                            <?php if ($aktivitas['jenis_transaksi'] === 'masuk'): ?>
                                                <span class="badge bg-success">Barang Masuk</span>
                                            <?php else: ?>
                                                <span class="badge bg-danger">Barang Keluar</span>
                                            <?php endif; ?>

                                            <br>

                                            <strong>
                                                <?= esc($aktivitas['nama_barang'] ?? '-') ?>
                                            </strong>

                                            <br>

                                            <small class="text-muted">
                                                Jumlah:
                                                <?= number_format($aktivitas['jumlah'] ?? 0, 0, ',', '.') ?>
                                            </small>
                                        </td>

                                        <td>
                                            <?= esc($aktivitas['nama_akun'] ?? 'Tidak diketahui') ?>
                                        </td>

                                        <td>
                                            <?= !empty($aktivitas['tanggal_transaksi'])
                                                ? date('d-m-Y H:i', strtotime($aktivitas['tanggal_transaksi']))
                                                : '-' ?>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>

                            <?php else: ?>

                                <tr>
                                    <td colspan="4" class="text-center text-muted">
                                        Belum ada aktivitas tercatat.
                                    </td>
                                </tr>

                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- FOOTER -->
            <div class="footer">
                © <?= date('Y') ?> GudangKu - Sistem Manajemen Gudang Barang Kebutuhan Harian.
            </div>

        </div>
    </div>
</div>

<!-- GRAFIK CHART.JS -->
<script>
const labelGrafik = <?= json_encode($labelsGrafik, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP) ?>;
const dataMasuk = <?= json_encode($dataMasuk) ?>;
const dataKeluar = <?= json_encode($dataKeluar) ?>;
const dataTotal = <?= json_encode($totalHarian) ?>;

// Grafik Barang Masuk dan Keluar
new Chart(document.getElementById('grafikBarang'), {
    type: 'bar',
    data: {
        labels: labelGrafik,
        datasets: [
            {
                label: 'Barang Masuk',
                data: dataMasuk,
                backgroundColor: '#198754',
                borderRadius: 5
            },
            {
                label: 'Barang Keluar',
                data: dataKeluar,
                backgroundColor: '#dc3545',
                borderRadius: 5
            }
        ]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        interaction: {
            intersect: false,
            mode: 'index'
        },
        plugins: {
            legend: {
                position: 'bottom'
            }
        },
        scales: {
            y: {
                beginAtZero: true,
                ticks: {
                    precision: 0
                }
            }
        }
    }
});

// Grafik Total Transaksi
new Chart(document.getElementById('grafikTotal'), {
    type: 'line',
    data: {
        labels: labelGrafik,
        datasets: [{
            label: 'Total Transaksi',
            data: dataTotal,
            borderColor: '#2878d0',
            backgroundColor: 'rgba(40,120,208,0.12)',
            fill: true,
            tension: 0.35,
            pointRadius: 5,
            pointHoverRadius: 7
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
            legend: {
                position: 'bottom'
            }
        },
        scales: {
            y: {
                beginAtZero: true,
                ticks: {
                    precision: 0
                }
            }
        }
    }
});
</script>

</body>
</html>
