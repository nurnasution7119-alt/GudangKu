
<?php
$isOwner = ($role ?? session()->get('role')) === 'owner';

$hariIni = date('Y-m-d');
$tanggalOtomatisAwal = date('Y-m-01');
$tanggalOtomatisAkhir = $hariIni;

$tanggalAwalTampil = $tanggal_awal ?? $tanggalOtomatisAwal;
$tanggalAkhirTampil = $tanggal_akhir ?? $tanggalOtomatisAkhir;
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan - GudangKu</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        body {
            background: #f4f6f9;
            font-family: Arial, sans-serif;
        }

        .main-content {
            max-width: 1200px;
            margin: auto;
            padding: 35px 25px;
        }

        .topbar, .stat-card {
            background: white;
            border-radius: 12px;
            box-shadow: 0 3px 12px rgba(0,0,0,0.05);
        }

        .topbar {
            padding: 22px 25px;
            margin-bottom: 30px;
        }

        .stat-card {
            border: none;
            padding: 22px;
            height: 100%;
        }

        .stat-label {
            color: #6c757d;
            font-size: 14px;
            margin-bottom: 12px;
        }

        .stat-number {
            font-size: 25px;
            font-weight: bold;
            margin-bottom: 8px;
            overflow-wrap: anywhere;
        }

        .stat-description {
            color: #7b8490;
            font-size: 13px;
        }

        .report-title {
            color: #243447;
            font-weight: bold;
        }

        @media (max-width: 576px) {
            .main-content {
                padding: 20px 14px;
            }

            .topbar {
                padding: 18px;
            }

            .stat-number {
                font-size: 22px;
            }
        }

        @media print {
            body {
                background: white !important;
            }

            .main-content {
                max-width: 100%;
                padding: 0;
            }

            .topbar {
                box-shadow: none;
                border: none;
                padding: 10px 0;
            }

            .stat-card {
                box-shadow: none;
                border: 1px solid #ddd;
            }

            .no-print {
                display: none !important;
            }

            .card {
                box-shadow: none !important;
                border: 1px solid #ddd !important;
            }

            .table {
                font-size: 11px;
            }

            .stat-number {
                font-size: 18px;
            }
        }
    </style>
</head>

<body>

<div class="main-content">

    <!-- Header -->
    <div class="topbar d-flex justify-content-between align-items-center flex-wrap gap-3">
        <div>
            <h3 class="report-title mb-2">📊 Laporan GudangKu</h3>
            <p class="text-muted mb-0">
                Ringkasan data gudang dan transaksi
            </p>
        </div>

        <div class="d-flex gap-2 no-print">
            <button onclick="window.print()" class="btn btn-primary px-4">
                🖨️ Cetak Laporan
            </button>

            <a href="<?= base_url('dashboard') ?>" class="btn btn-secondary px-4">
                ← Kembali
            </a>
        </div>
    </div>

    <!-- Filter Periode Laporan -->
    <div class="card border-0 shadow-sm mb-4 no-print">
        <div class="card-body p-4">

            <h5 class="fw-bold mb-3">🔎 Filter Laporan Transaksi</h5>

            <form action="<?= base_url('laporan') ?>" method="get">

                <div class="row g-3 align-items-end">

                    <div class="col-md-4">
                        <label class="form-label">Pilih Periode</label>

                        <select name="periode" id="periode" class="form-select"
                                onchange="tampilkanTanggal()">

                            <option value="harian" <?= ($periode ?? '') === 'harian' ? 'selected' : '' ?>>
                                Harian
                            </option>

                            <option value="mingguan" <?= ($periode ?? '') === 'mingguan' ? 'selected' : '' ?>>
                                Mingguan
                            </option>

                            <option value="bulanan" <?= ($periode ?? '') === 'bulanan' ? 'selected' : '' ?>>
                                Bulanan
                            </option>

                            <option value="tahunan" <?= ($periode ?? '') === 'tahunan' ? 'selected' : '' ?>>
                                Tahunan
                            </option>

                            <option value="custom" <?= ($periode ?? '') === 'custom' ? 'selected' : '' ?>>
                                Rentang Tanggal
                            </option>

                        </select>
                    </div>

                    <div class="col-md-3 tanggal-custom">
                        <label class="form-label">Tanggal Awal</label>

                        <input type="date"
                               name="tanggal_awal"
                               id="tanggal_awal"
                               class="form-control"
                               value="<?= esc($tanggalAwalTampil) ?>">
                    </div>

                    <div class="col-md-3 tanggal-custom">
                        <label class="form-label">Tanggal Akhir</label>

                        <input type="date"
                               name="tanggal_akhir"
                               id="tanggal_akhir"
                               class="form-control"
                               value="<?= esc($tanggalAkhirTampil) ?>">
                    </div>

                    <div class="col-md-2 d-flex gap-2">
                        <button type="submit" class="btn btn-primary">
                            Tampilkan
                        </button>

                        <a href="<?= base_url('laporan') ?>"
                           class="btn btn-secondary">
                            Reset
                        </a>
                    </div>

                </div>
            </form>
        </div>
    </div>

    <h5 class="mb-3 report-title">Ringkasan Laporan</h5>

    <!-- Statistik -->
    <div class="row g-4">

        <div class="col-12 col-sm-6 col-xl-3">
            <div class="stat-card">
                <p class="stat-label">📦 Total Barang</p>

                <div class="stat-number text-primary">
                    <?= number_format($total_barang, 0, ',', '.') ?>
                </div>

                <div class="stat-description">
                    Jenis barang tersedia
                </div>
            </div>
        </div>

        <div class="col-12 col-sm-6 col-xl-3">
            <div class="stat-card">
                <p class="stat-label">📊 Total Stok</p>

                <div class="stat-number text-success">
                    <?= number_format($total_stok, 0, ',', '.') ?>
                </div>

                <div class="stat-description">
                    Jumlah seluruh stok
                </div>
            </div>
        </div>

        <?php if ($isOwner): ?>

            <div class="col-12 col-sm-6 col-xl-3">
                <div class="stat-card">
                    <p class="stat-label">📥 Total Barang Masuk</p>

                    <div class="stat-number text-warning">
                        Rp <?= number_format($total_masuk, 0, ',', '.') ?>
                    </div>

                    <div class="stat-description">
                        Total nilai pembelian sesuai periode
                    </div>
                </div>
            </div>

            <div class="col-12 col-sm-6 col-xl-3">
                <div class="stat-card">
                    <p class="stat-label">📤 Total Barang Keluar</p>

                    <div class="stat-number text-danger">
                        Rp <?= number_format($total_keluar, 0, ',', '.') ?>
                    </div>

                    <div class="stat-description">
                        Total nilai penjualan sesuai periode
                    </div>
                </div>
            </div>

        <?php endif; ?>

    </div>

    <!-- Detail Stok Barang -->
    <div class="card border-0 shadow-sm mt-4">
        <div class="card-body p-4">

            <h5 class="fw-bold mb-3">📦 Detail Stok Barang</h5>

            <div class="table-responsive">
                <table class="table table-bordered table-hover align-middle">

                    <thead class="table-light">
                        <tr>
                            <th>No</th>
                            <th>Kode Barang</th>
                            <th>Nama Barang</th>
                            <th>Stok</th>

                            <?php if ($isOwner): ?>
                                <th>Harga Modal</th>
                                <th>Harga Jual</th>
                            <?php endif; ?>

                            <th>Tanggal Masuk</th>
                        </tr>
                    </thead>

                    <tbody>

                        <?php if (!empty($daftar_barang)): ?>

                            <?php $no = 1; ?>

                            <?php foreach ($daftar_barang as $barang): ?>
                                <tr>
                                    <td><?= $no++ ?></td>
                                    <td><?= esc($barang['kode_barang']) ?></td>
                                    <td><?= esc($barang['nama_barang']) ?></td>

                                    <td>
                                        <?= number_format($barang['stok'], 0, ',', '.') ?>
                                    </td>

                                    <?php if ($isOwner): ?>
                                        <td>
                                            Rp <?= number_format($barang['harga_modal'], 0, ',', '.') ?>
                                        </td>

                                        <td>
                                            Rp <?= number_format($barang['harga_jual'], 0, ',', '.') ?>
                                        </td>
                                    <?php endif; ?>

                                    <td><?= esc($barang['tanggal_masuk']) ?></td>
                                </tr>
                            <?php endforeach; ?>

                        <?php else: ?>

                            <tr>
                                <td colspan="<?= $isOwner ? 7 : 5 ?>"
                                    class="text-center text-muted">
                                    Belum ada data barang.
                                </td>
                            </tr>

                        <?php endif; ?>

                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Riwayat Transaksi -->
    <div class="card border-0 shadow-sm mt-4">
        <div class="card-body p-4">

            <h5 class="fw-bold mb-3">🧾 Riwayat Transaksi</h5>

            <p class="text-muted small">
                Periode:
                <?= esc(date('d-m-Y', strtotime($tanggal_awal))) ?>
                sampai
                <?= esc(date('d-m-Y', strtotime($tanggal_akhir))) ?>
            </p>

            <div class="table-responsive">
                <table class="table table-bordered table-hover align-middle">

                    <thead class="table-light">
                        <tr>
                            <th>No</th>
                            <th>Nomor Transaksi</th>
                            <th>Jenis</th>
                            <th>Nama Barang</th>
                            <th>Jumlah</th>

                            <?php if ($isOwner): ?>
                                <th>Subtotal</th>
                            <?php endif; ?>

                            <th>Akun</th>
                            <th>Tanggal</th>
                        </tr>
                    </thead>

                    <tbody>

                        <?php if (!empty($riwayat_transaksi)): ?>

                            <?php $no = 1; ?>

                            <?php foreach ($riwayat_transaksi as $transaksi): ?>
                                <tr>
                                    <td><?= $no++ ?></td>

                                    <td>
                                        <?= esc($transaksi['nomor_transaksi']) ?>
                                    </td>

                                    <td>
                                        <?php if ($transaksi['jenis_transaksi'] === 'masuk'): ?>
                                            <span class="badge bg-success">
                                                Barang Masuk
                                            </span>
                                        <?php else: ?>
                                            <span class="badge bg-danger">
                                                Barang Keluar
                                            </span>
                                        <?php endif; ?>
                                    </td>

                                    <td><?= esc($transaksi['nama_barang']) ?></td>

                                    <td>
                                        <?= number_format($transaksi['jumlah'], 0, ',', '.') ?>
                                    </td>

                                    <?php if ($isOwner): ?>
                                        <td>
                                            Rp <?= number_format($transaksi['subtotal'], 0, ',', '.') ?>
                                        </td>
                                    <?php endif; ?>

                                    <td>
                                        <?= esc($transaksi['nama_akun'] ?? '-') ?>
                                    </td>

                                    <td>
                                        <?= esc(date(
                                            'd-m-Y H:i',
                                            strtotime($transaksi['tanggal_transaksi'])
                                        )) ?>
                                    </td>
                                </tr>
                            <?php endforeach; ?>

                        <?php else: ?>

                            <tr>
                                <td colspan="<?= $isOwner ? 8 : 7 ?>"
                                    class="text-center text-muted py-4">
                                    Tidak ada transaksi pada periode ini.
                                </td>
                            </tr>

                        <?php endif; ?>

                    </tbody>
                </table>
            </div>

            <div class="text-muted small">
                Jumlah transaksi ditampilkan:
                <?= count($riwayat_transaksi ?? []) ?> baris detail.
            </div>

        </div>
    </div>

    <!-- Footer -->
    <div class="text-center text-muted mt-5">
        <small>
            © <?= date('Y') ?> GudangKu - Sistem Manajemen Gudang
        </small>
    </div>

</div>

<script>
    function tampilkanTanggal() {
        const periode = document.getElementById('periode').value;

        const tanggal = document.querySelectorAll('.tanggal-custom');
        const awal = document.getElementById('tanggal_awal');
        const akhir = document.getElementById('tanggal_akhir');

        tanggal.forEach(function (elemen) {
            elemen.style.display = periode === 'custom' ? '' : 'none';
        });

        if (periode === 'custom') {
            const sekarang = new Date();

            const tahun = sekarang.getFullYear();
            const bulan = String(sekarang.getMonth() + 1).padStart(2, '0');
            const hari = String(sekarang.getDate()).padStart(2, '0');

            awal.value = `${tahun}-${bulan}-01`;
            akhir.value = `${tahun}-${bulan}-${hari}`;
        }
    }

    document.addEventListener('DOMContentLoaded', function () {
        tampilkanTanggal();
    });
</script>

</body>
</html>
