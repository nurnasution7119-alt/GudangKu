
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nota Transaksi - GudangKu</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        body {
            background: #f4f6f9;
        }

        .nota {
            max-width: 650px;
            margin: 40px auto;
            padding: 30px;
            background: white;
            border-radius: 10px;
            box-shadow: 0 2px 12px rgba(0,0,0,0.08);
        }

        .garis {
            border-top: 2px dashed #777;
            margin: 20px 0;
        }

        @media print {
            body {
                background: white;
            }

            .nota {
                margin: 0 auto;
                box-shadow: none;
                max-width: 100%;
            }

            .no-print {
                display: none !important;
            }
        }
    </style>
</head>

<body>

<?php
    $transaksi = $nota[0];
    $total = 0;

    foreach ($nota as $item) {
        $total += (float) $item['subtotal'];
    }
?>

<div class="nota">

    <div class="text-center">
        <h3 class="fw-bold">📦 GudangKu</h3>
        <p class="mb-1">Nota Transaksi Barang Keluar</p>
        <div class="garis"></div>
    </div>

    <table class="table table-borderless table-sm">
        <tr>
            <td width="40%">Nomor Transaksi</td>
            <td>: <?= esc($transaksi['nomor_transaksi']) ?></td>
        </tr>
        <tr>
            <td>Tanggal</td>
            <td>: <?= esc($transaksi['tanggal_transaksi']) ?></td>
        </tr>
        <tr>
            <td>Jenis Transaksi</td>
            <td>: Barang Keluar</td>
        </tr>
    </table>

    <div class="garis"></div>

    <h6 class="fw-bold">Detail Barang</h6>

    <div class="table-responsive">
        <table class="table table-bordered align-middle">
            <thead class="table-light">
                <tr>
                    <th>Nama Barang</th>
                    <th>Jumlah</th>
                    <th>Harga</th>
                    <th>Subtotal</th>
                </tr>
            </thead>

            <tbody>
                <?php foreach ($nota as $item): ?>
                    <tr>
                        <td>
                            <?= esc($item['nama_barang']) ?>
                        </td>
                        <td>
                            <?= esc($item['jumlah']) ?>
                        </td>
                        <td>
                            Rp <?= number_format((float) $item['harga_jual'], 0, ',', '.') ?>
                        </td>
                        <td>
                            Rp <?= number_format((float) $item['subtotal'], 0, ',', '.') ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

    <div class="text-end">
        <h5 class="fw-bold">
            Total Pembayaran:
            Rp <?= number_format($total, 0, ',', '.') ?>
        </h5>
    </div>

    <div class="garis"></div>

    <div class="text-center">
        <p class="mb-1">Terima kasih telah bertransaksi.</p>
        <small>GudangKu - Sistem Manajemen Gudang</small>
    </div>

    <div class="text-center mt-4 no-print">
        <button onclick="window.print()" class="btn btn-primary">
            🖨️ Cetak Nota
        </button>

        <a href="<?= base_url('transaksi/riwayat') ?>" class="btn btn-secondary">
            Kembali
        </a>
    </div>

</div>

</body>
</html>

