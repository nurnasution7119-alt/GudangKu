
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Riwayat Transaksi - GudangKu</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="bg-light">

<?php
    $isOwner = ($role ?? session()->get('role')) === 'owner';
?>

<div class="container py-5">

    <div class="card shadow-sm border-0">

        <div class="card-header bg-primary text-white">
            <h4 class="mb-0">📊 Riwayat Transaksi GudangKu</h4>
        </div>

        <div class="card-body">

            <div class="table-responsive">
                <table class="table table-bordered table-striped align-middle">

                    <thead class="table-dark">
                        <tr>
                            <th>No</th>
                            <th>Nomor Transaksi</th>
                            <th>Tanggal</th>
                            <th>Jenis</th>
                            <th>Nama Barang</th>
                            <th>Jumlah</th>

                            <?php if ($isOwner): ?>
                                <th>Total Harga</th>
                            <?php endif; ?>

                            <th>Aksi</th>
                        </tr>
                    </thead>

                    <tbody>

                        <?php if (!empty($riwayat)): ?>

                            <?php foreach ($riwayat as $index => $item): ?>
                                <tr>

                                    <td><?= $index + 1 ?></td>

                                    <td>
                                        <?= esc($item['nomor_transaksi']) ?>
                                    </td>

                                    <td>
                                        <?= esc($item['tanggal_transaksi']) ?>
                                    </td>

                                    <td>
                                        <?php if ($item['jenis_transaksi'] === 'masuk'): ?>
                                            <span class="badge bg-success">
                                                Barang Masuk
                                            </span>
                                        <?php else: ?>
                                            <span class="badge bg-danger">
                                                Barang Keluar
                                            </span>
                                        <?php endif; ?>
                                    </td>

                                    <td>
                                        <?= esc($item['nama_barang']) ?>
                                    </td>

                                    <td>
                                        <?= esc($item['jumlah']) ?>
                                    </td>

                                    <?php if ($isOwner): ?>
                                        <td>
                                            Rp <?= number_format((float) ($item['total_harga'] ?? 0), 0, ',', '.') ?>
                                        </td>
                                    <?php endif; ?>

                                    <td>
                                        <?php if ($isOwner && $item['jenis_transaksi'] === 'keluar'): ?>
                                            <a href="<?= base_url('transaksi/nota/' . $item['id']) ?>"
                                               target="_blank"
                                               class="btn btn-sm btn-primary">
                                                🖨️ Nota
                                            </a>
                                        <?php else: ?>
                                            <span class="text-muted">-</span>
                                        <?php endif; ?>
                                    </td>

                                </tr>
                            <?php endforeach; ?>

                        <?php else: ?>

                            <tr>
                                <td colspan="<?= $isOwner ? '8' : '7' ?>"
                                    class="text-center text-muted py-3">
                                    Belum ada riwayat transaksi.
                                </td>
                            </tr>

                        <?php endif; ?>

                    </tbody>

                </table>
            </div>

            <a href="<?= base_url('transaksi') ?>" class="btn btn-secondary">
                ← Kembali
            </a>

        </div>
    </div>

</div>

</body>
</html>
