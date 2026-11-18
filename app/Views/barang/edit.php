
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Barang - GudangKu</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body style="background:#f4f6f9;">

<div class="container py-4">

    <div class="card shadow-sm border-0 mx-auto" style="max-width:700px;">
        <div class="card-body p-4">

            <h3 class="fw-bold mb-2">✏️ Edit Barang</h3>
            <p class="text-muted">Perbarui informasi barang GudangKu.</p>

            <?php if (session()->getFlashdata('errors')): ?>
                <div class="alert alert-danger">
                    <?php foreach (session()->getFlashdata('errors') as $error): ?>
                        <p class="mb-1"><?= esc($error) ?></p>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>

            <form action="<?= base_url('data-barang/update/' . $barang['id']) ?>" method="post">

                <?= csrf_field() ?>

                <div class="mb-3">
                    <label class="form-label">Kode Barang</label>
                    <input type="text" class="form-control"
                        value="<?= esc($barang['kode_barang']) ?>" readonly>
                </div>

                <div class="mb-3">
                    <label class="form-label">Nama Barang</label>
                    <input type="text" name="nama_barang" class="form-control"
                        value="<?= esc(old('nama_barang', $barang['nama_barang'])) ?>" required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Stok</label>
                    <input type="number" name="stok" class="form-control"
                        value="<?= esc(old('stok', $barang['stok'])) ?>"
                        min="0" required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Harga Modal</label>
                    <input type="number" name="harga_modal" class="form-control"
                        value="<?= esc(old('harga_modal', $barang['harga_modal'])) ?>"
                        min="0" required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Harga Jual</label>
                    <input type="number" name="harga_jual" class="form-control"
                        value="<?= esc(old('harga_jual', $barang['harga_jual'])) ?>"
                        min="0" required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Tanggal Masuk</label>
                    <input type="date" name="tanggal_masuk" class="form-control"
                        value="<?= esc(old('tanggal_masuk', $barang['tanggal_masuk'])) ?>"
                        required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Tanggal Kedaluwarsa</label>
                    <input type="date" name="tanggal_kedaluwarsa" class="form-control"
                        value="<?= esc(old('tanggal_kedaluwarsa', $barang['tanggal_kedaluwarsa'] ?? '')) ?>">
                </div>

                <div class="d-flex gap-2">
                    <button type="submit" class="btn btn-primary">
                        Simpan Perubahan
                    </button>

                    <a href="<?= base_url('data-barang') ?>" class="btn btn-secondary">
                        Batal
                    </a>
                </div>

            </form>

        </div>
    </div>

</div>

</body>
</html>
