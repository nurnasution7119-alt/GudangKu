
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Data Barang - GudangKu</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        body {
            background: #f4f6f9;
        }

        .card {
            border-radius: 12px;
        }

        .table th {
            white-space: nowrap;
        }

        .table td {
            white-space: nowrap;
        }

        .judul {
            color: #263238;
        }
    </style>
</head>

<body>

<?php
    $role = session()->get('role');
    $isOwner = ($role === 'owner');
?>

<div class="container py-4">

    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h3 class="fw-bold judul">📦 Data Barang</h3>
            <p class="text-muted mb-0">
                Kelola data barang GudangKu.
            </p>
        </div>

        <a href="<?= base_url('dashboard') ?>" class="btn btn-secondary">
            Kembali
        </a>
    </div>

    <div class="card shadow-sm border-0">
        <div class="card-body p-4">

            <!-- Judul dan tombol tambah -->
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h5 class="fw-bold mb-1">Daftar Barang</h5>
                    <small class="text-muted">
                        Login sebagai: <?= esc(ucfirst($role)) ?>
                    </small>
                </div>

                <?php if ($isOwner): ?>
                    <a href="<?= base_url('data-barang/tambah') ?>"
                       class="btn btn-primary">
                        + Tambah Barang
                    </a>
                <?php endif; ?>
            </div>

            <!-- Notifikasi -->
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

            <!-- Pencarian dan Filter -->
            <form action="<?= base_url('data-barang') ?>" method="get" class="mb-4">
                <div class="row g-2">

                    <div class="col-md-6">
                        <input
                            type="text"
                            name="keyword"
                            class="form-control"
                            placeholder="Cari kode atau nama barang..."
                            value="<?= esc($keyword ?? '') ?>">
                    </div>

                    <div class="col-md-4">
                        <select name="stok" class="form-select">
                            <option value="">Semua Stok</option>

                            <option value="menipis"
                                <?= ($filterStok ?? '') === 'menipis' ? 'selected' : '' ?>>
                                Stok Menipis (1–5)
                            </option>

                            <option value="tersedia"
                                <?= ($filterStok ?? '') === 'tersedia' ? 'selected' : '' ?>>
                                Stok Tersedia
                            </option>

                            <option value="habis"
                                <?= ($filterStok ?? '') === 'habis' ? 'selected' : '' ?>>
                                Stok Habis
                            </option>
                        </select>
                    </div>

                    <div class="col-md-2 d-flex gap-2">
                        <button type="submit" class="btn btn-primary w-100">
                            Cari
                        </button>

                        <a href="<?= base_url('data-barang') ?>"
                           class="btn btn-outline-secondary">
                            Reset
                        </a>
                    </div>

                </div>
            </form>

            <!-- Tabel Barang -->
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
                                <th>Aksi</th>
                            <?php endif; ?>

                            <th>Tanggal Masuk</th>
                            <th>Kedaluwarsa</th>
                        </tr>
                    </thead>

                    <tbody>
                        <?php if (!empty($barang)): ?>

                            <?php $no = 1; ?>

                            <?php foreach ($barang as $item): ?>
                                <tr>
                                    <td><?= $no++ ?></td>

                                    <td>
                                        <span class="fw-semibold">
                                            <?= esc($item['kode_barang']) ?>
                                        </span>
                                    </td>

                                    <td><?= esc($item['nama_barang']) ?></td>

                                    <td>
                                        <?php if ($item['stok'] == 0): ?>
                                            <span class="badge bg-danger">Habis</span>
                                        <?php elseif ($item['stok'] <= 5): ?>
                                            <span class="badge bg-warning text-dark">
                                                <?= esc($item['stok']) ?> (Menipis)
                                            </span>
                                        <?php else: ?>
                                            <span class="badge bg-success">
                                                <?= esc($item['stok']) ?> (Tersedia)
                                            </span>
                                        <?php endif; ?>
                                    </td>

                                    <?php if ($isOwner): ?>
                                        <td>
                                            Rp <?= number_format($item['harga_modal'], 0, ',', '.') ?>
                                        </td>

                                        <td>
                                            Rp <?= number_format($item['harga_jual'], 0, ',', '.') ?>
                                        </td>

                                        <td>
                                            <div class="d-flex gap-2">
                                                <a href="<?= base_url('data-barang/edit/' . $item['id']) ?>"
                                                   class="btn btn-warning btn-sm">
                                                    Edit
                                                </a>

                                                <form action="<?= base_url('data-barang/hapus/' . $item['id']) ?>"
                                                      method="post"
                                                      onsubmit="return confirm('Yakin ingin menghapus barang ini?')">

                                                    <?= csrf_field() ?>

                                                    <button type="submit" class="btn btn-danger btn-sm">
                                                        Hapus
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    <?php endif; ?>

                                    <td><?= esc($item['tanggal_masuk']) ?></td>

                                    <td><?= esc($item['tanggal_kedaluwarsa'] ?? '-') ?></td>
                                </tr>

                            <?php endforeach; ?>

                        <?php else: ?>

                            <tr>
                                <td colspan="<?= $isOwner ? 9 : 6 ?>"
                                    class="text-center text-muted py-4">
                                    Data barang tidak ditemukan.
                                </td>
                            </tr>

                        <?php endif; ?>
                    </tbody>

                </table>
            </div>

            <div class="text-muted small mt-2">
                Jumlah barang ditampilkan: <?= count($barang ?? []) ?> jenis.
            </div>

        </div>
    </div>

</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
