
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Transaksi - GudangKu</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        body {
            background: #f4f6f9;
        }

        .card {
            border: none;
            border-radius: 12px;
        }

        .summary-card {
            border-left: 4px solid #198754;
        }

        .action-card {
            transition: 0.2s;
        }

        .action-card:hover {
            transform: translateY(-3px);
        }

        .table th {
            white-space: nowrap;
        }
    </style>
</head>

<body>

<?php
    $isOwner = ($role ?? session()->get('role')) === 'owner';

    $totalJenis = count($barang ?? []);
    $totalStok = 0;

    foreach (($barang ?? []) as $item) {
        $totalStok += (int) $item['stok'];
    }
?>

<div class="container py-4">

    <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mb-4">
        <div>
            <h3 class="fw-bold mb-1">🛒 Transaksi GudangKu</h3>
            <p class="text-muted mb-0">
                Kelola barang masuk dan barang keluar.
            </p>
        </div>

        <a href="<?= base_url('dashboard') ?>" class="btn btn-secondary">
            Kembali ke Dashboard
        </a>
    </div>

    <?php if (session()->getFlashdata('success')): ?>
        <div class="alert alert-success alert-dismissible fade show">
            <?= esc(session()->getFlashdata('success')) ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>

    <?php if (session()->getFlashdata('error')): ?>
        <div class="alert alert-danger alert-dismissible fade show">
            <?= esc(session()->getFlashdata('error')) ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>

    <!-- Ringkasan -->
    <div class="row g-3 mb-4">

        <div class="col-md-6">
            <div class="card shadow-sm p-3 summary-card h-100">
                <div class="text-muted">Total Jenis Barang</div>
                <h3 class="fw-bold mb-0">
                    <?= number_format($totalJenis, 0, ',', '.') ?>
                </h3>
            </div>
        </div>

        <div class="col-md-6">
            <div class="card shadow-sm p-3 summary-card h-100">
                <div class="text-muted">Total Seluruh Stok</div>
                <h3 class="fw-bold mb-0">
                    <?= number_format($totalStok, 0, ',', '.') ?>
                </h3>
            </div>
        </div>

    </div>

    <!-- Menu Transaksi -->
    <div class="row g-3 mb-4">

        <?php if ($isOwner): ?>
            <div class="col-md-4">
                <div class="card shadow-sm p-3 h-100 action-card">
                    <h5>📥 Barang Masuk</h5>
                    <p class="text-muted">
                        Catat pembelian dan penambahan stok barang.
                    </p>
                    <a href="<?= base_url('transaksi/barang-masuk') ?>"
                       class="btn btn-success mt-auto">
                        Tambah Barang Masuk
                    </a>
                </div>
            </div>
        <?php endif; ?>

        <div class="col-md-<?= $isOwner ? '4' : '6' ?>">
            <div class="card shadow-sm p-3 h-100 action-card">
                <h5>📤 Barang Keluar</h5>
                <p class="text-muted">
                    Catat penjualan dan pengeluaran barang.
                </p>
                <a href="<?= base_url('transaksi/barang-keluar') ?>"
                   class="btn btn-danger mt-auto">
                    Tambah Barang Keluar
                </a>
            </div>
        </div>

        <div class="col-md-<?= $isOwner ? '4' : '6' ?>">
            <div class="card shadow-sm p-3 h-100 action-card">
                <h5>📊 Riwayat Transaksi</h5>
                <p class="text-muted">
                    Periksa seluruh transaksi yang telah disimpan.
                </p>
                <a href="<?= base_url('transaksi/riwayat') ?>"
                   class="btn btn-primary mt-auto">
                    Lihat Riwayat
                </a>
            </div>
        </div>

    </div>

    <!-- Daftar Barang -->
    <div class="card shadow-sm">
        <div class="card-body">

            <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mb-3">
                <h5 class="fw-bold mb-0">Daftar Barang Tersedia</h5>
                <span class="badge bg-success">
                    <?= $totalJenis ?> Jenis Barang
                </span>
            </div>

            <div class="table-responsive">
                <table class="table table-bordered table-striped align-middle">

                    <thead class="table-light">
                        <tr>
                            <th>No</th>
                            <th>Kode Barang</th>
                            <th>Nama Barang</th>
                            <th>Stok</th>

                            <?php if ($isOwner): ?>
                                <th>Harga Modal</th>
                            <?php endif; ?>

                            <th>Harga Jual</th>
                            <th>Status</th>
                        </tr>
                    </thead>

                    <tbody>

                        <?php if (!empty($barang)): ?>

                            <?php $no = 1; ?>

                            <?php foreach ($barang as $item): ?>
                                <tr>
                                    <td><?= $no++ ?></td>

                                    <td><?= esc($item['kode_barang']) ?></td>

                                    <td><?= esc($item['nama_barang']) ?></td>

                                    <td>
                                        <?= number_format((int) $item['stok'], 0, ',', '.') ?>
                                    </td>

                                    <?php if ($isOwner): ?>
                                        <td>
                                            Rp <?= number_format((float) ($item['harga_modal'] ?? 0), 0, ',', '.') ?>
                                        </td>
                                    <?php endif; ?>

                                    <td>
                                        Rp <?= number_format((float) $item['harga_jual'], 0, ',', '.') ?>
                                    </td>

                                    <td>
                                        <?php if ((int) $item['stok'] <= 0): ?>
                                            <span class="badge bg-danger">Stok Habis</span>
                                        <?php elseif ((int) $item['stok'] <= 5): ?>
                                            <span class="badge bg-warning text-dark">Stok Rendah</span>
                                        <?php else: ?>
                                            <span class="badge bg-success">Tersedia</span>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                            <?php endforeach; ?>

                        <?php else: ?>

                            <tr>
                                <td colspan="<?= $isOwner ? '7' : '6' ?>" class="text-center text-muted py-4">
                                    Belum ada barang tersedia.
                                </td>
                            </tr>

                        <?php endif; ?>

                    </tbody>

                </table>
            </div>

        </div>
    </div>

</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
