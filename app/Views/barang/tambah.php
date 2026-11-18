
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Barang - GudangKu</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body style="background:#f4f6f9;">

<div class="container py-5">
    <div class="card shadow-sm border-0 p-4 mx-auto" style="max-width:700px;">

        <h3 class="fw-bold mb-2">📦 Tambah Barang</h3>
        <p class="text-muted">Masukkan informasi barang GudangKu.</p>

        <?php if (session()->getFlashdata('errors')): ?>
            <div class="alert alert-danger">
                <?php foreach (session()->getFlashdata('errors') as $error): ?>
                    <div><?= esc($error) ?></div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>

        <form action="<?= base_url('data-barang/simpan') ?>" method="post">
            <?= csrf_field() ?>

            <div class="mb-3">
                <label class="form-label">Nama Barang</label>
                <input type="text" name="nama_barang" class="form-control"
                    value="<?= old('nama_barang') ?>" required>
            </div>

            <div class="mb-3">
                <label class="form-label">Stok Awal</label>
                <input type="number" name="stok" class="form-control"
                    min="0" value="<?= old('stok', 0) ?>" required>
            </div>

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label">Harga Modal</label>
                    <input type="number" name="harga_modal" class="form-control"
                        min="0" step="0.01" value="<?= old('harga_modal') ?>" required>
                </div>

                <div class="col-md-6 mb-3">
                    <label class="form-label">Harga Jual</label>
                    <input type="number" name="harga_jual" class="form-control"
                        min="0" step="0.01" value="<?= old('harga_jual') ?>" required>
                </div>
            </div>

           <div class="mb-3">
    <label class="form-label">Tanggal Masuk</label>
    <input type="date" name="tanggal_masuk" class="form-control"
        value="<?= old('tanggal_masuk', date('Y-m-d')) ?>" required>
</div>

            <
<div class="mb-3">
    <label class="form-label">Masa Simpan Barang</label>
    <select name="masa_simpan" id="masa_simpan" class="form-select">
        <option value="">Pilih Masa Simpan</option>
        <option value="1">1 Bulan</option>
        <option value="3">3 Bulan</option>
        <option value="6">6 Bulan</option>
        <option value="12">12 Bulan</option>
        <option value="24">24 Bulan</option>
    </select>
</div>

<div class="mb-3">
    <label class="form-label">Tanggal Kedaluwarsa</label>
    <input type="date" name="tanggal_kedaluwarsa"
        id="tanggal_kedaluwarsa" class="form-control" readonly>
    <small class="text-muted">
        Tanggal kedaluwarsa dihitung otomatis berdasarkan tanggal masuk dan masa simpan.
    </small>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const tanggalMasuk = document.querySelector('[name="tanggal_masuk"]');
    const masaSimpan = document.getElementById('masa_simpan');
    const tanggalKedaluwarsa = document.getElementById('tanggal_kedaluwarsa');

    function hitungKedaluwarsa() {
        if (!tanggalMasuk.value || !masaSimpan.value) {
            tanggalKedaluwarsa.value = '';
            return;
        }

        const tanggal = new Date(tanggalMasuk.value + 'T12:00:00');
        const hariAsli = tanggal.getDate();

        tanggal.setDate(1);
        tanggal.setMonth(tanggal.getMonth() + Number(masaSimpan.value));

        const hariTerakhir = new Date(
            tanggal.getFullYear(),
            tanggal.getMonth() + 1,
            0
        ).getDate();

        tanggal.setDate(Math.min(hariAsli, hariTerakhir));

        const tahun = tanggal.getFullYear();
        const bulan = String(tanggal.getMonth() + 1).padStart(2, '0');
        const hari = String(tanggal.getDate()).padStart(2, '0');

        tanggalKedaluwarsa.value = `${tahun}-${bulan}-${hari}`;
    }

    tanggalMasuk.addEventListener('change', hitungKedaluwarsa);
    masaSimpan.addEventListener('change', hitungKedaluwarsa);
});
</script>


            <div class="d-flex gap-2 mt-4">
                <button type="submit" class="btn btn-primary">
                    Simpan Barang
                </button>

                <a href="<?= base_url('data-barang') ?>" class="btn btn-secondary">
                    Batal
                </a>
            </div>

        </form>
    </div>
</div>

</body>
</html>
