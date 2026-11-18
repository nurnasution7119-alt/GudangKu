
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Barang Masuk - GudangKu</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="bg-light">

<div class="container py-5">

    <div class="card shadow-sm border-0">
        <div class="card-header bg-success text-white p-3">
            <h4 class="mb-0">📥 Tambah Barang Masuk</h4>
        </div>

        <div class="card-body p-4">

            <?php if (session()->getFlashdata('error')): ?>
                <div class="alert alert-danger">
                    <?= esc(session()->getFlashdata('error')) ?>
                </div>
            <?php endif; ?>

            <?php if (session()->getFlashdata('success')): ?>
                <div class="alert alert-success">
                    <?= esc(session()->getFlashdata('success')) ?>
                </div>
            <?php endif; ?>

            <form action="<?= base_url('transaksi/simpan-masuk') ?>"
                  method="post"
                  id="formBarangMasuk">

                <?= csrf_field() ?>

                <div class="mb-3">
                    <label class="form-label fw-semibold">Pilih Barang</label>

                    <select name="barang_id"
                            id="barang_id"
                            class="form-select"
                            required>

                        <option value="">-- Pilih Barang --</option>

                        <?php foreach ($barang as $item): ?>
                            <option
                                value="<?= esc($item['id']) ?>"
                                data-harga="<?= esc($item['harga_modal']) ?>">

                                <?= esc($item['kode_barang']) ?> -
                                <?= esc($item['nama_barang']) ?>
                            </option>
                        <?php endforeach; ?>

                    </select>
                </div>

                <div class="mb-3">
                    <label class="form-label fw-semibold">
                        Jumlah Barang Masuk
                    </label>

                    <input type="number"
                           name="jumlah"
                           id="jumlah"
                           class="form-control"
                           min="1"
                           step="1"
                           placeholder="Contoh: 10"
                           required>
                </div>

                <div class="mb-3">
                    <label class="form-label fw-semibold">
                        Harga Modal per Barang (Rp)
                    </label>

                    <input type="number"
                           name="harga_modal"
                           id="harga_modal"
                           class="form-control"
                           min="1"
                           step="1"
                           placeholder="Contoh: 25000"
                           required>

                    <small class="text-muted">
                        Harga otomatis mengikuti harga modal terakhir.
                        Kamu tetap bisa mengubahnya. Masukkan angka tanpa
                        titik atau koma.
                    </small>
                </div>

                <div class="mb-3">
                    <label class="form-label fw-semibold">
                        Total Pembelian
                    </label>

                    <input type="text"
                           id="total_harga"
                           class="form-control fw-bold bg-light"
                           value="Rp 0"
                           readonly>
                </div>

                <div class="mb-3">
                    <label class="form-label fw-semibold">
                        Keterangan
                    </label>

                    <textarea name="keterangan"
                              class="form-control"
                              rows="3"
                              maxlength="500"
                              placeholder="Contoh: Pembelian stok baru"></textarea>
                </div>

                <div class="d-flex gap-2">
                    <button type="submit"
                            id="btnSimpan"
                            class="btn btn-success">
                        Simpan Barang Masuk
                    </button>

                    <a href="<?= base_url('transaksi') ?>"
                       class="btn btn-secondary">
                        Kembali
                    </a>
                </div>

            </form>

        </div>
    </div>

</div>

<script>
const pilihanBarang = document.getElementById('barang_id');
const inputJumlah = document.getElementById('jumlah');
const inputHarga = document.getElementById('harga_modal');
const totalHarga = document.getElementById('total_harga');
const form = document.getElementById('formBarangMasuk');

function formatRupiah(angka) {
    return 'Rp ' + angka.toLocaleString('id-ID');
}

function hitungTotal() {
    const jumlah = Number(inputJumlah.value);
    const harga = Number(inputHarga.value);

    if (
        !Number.isSafeInteger(jumlah) ||
        !Number.isSafeInteger(harga) ||
        jumlah < 1 ||
        harga < 1
    ) {
        totalHarga.value = 'Rp 0';
        return;
    }

    const total = jumlah * harga;

    if (!Number.isSafeInteger(total)) {
        totalHarga.value = 'Nilai terlalu besar';
        return;
    }

    totalHarga.value = formatRupiah(total);
}

pilihanBarang.addEventListener('change', function() {
    const option = this.selectedOptions[0];

    if (!option || !option.value) {
        inputHarga.value = '';
        totalHarga.value = 'Rp 0';
        return;
    }

    inputHarga.value = option.dataset.harga || '';
    hitungTotal();
});

inputJumlah.addEventListener('input', hitungTotal);
inputHarga.addEventListener('input', hitungTotal);

form.addEventListener('submit', function(event) {
    const jumlah = Number(inputJumlah.value);
    const harga = Number(inputHarga.value);

    if (
        !pilihanBarang.value ||
        !Number.isSafeInteger(jumlah) ||
        !Number.isSafeInteger(harga) ||
        jumlah < 1 ||
        harga < 1 ||
        !Number.isSafeInteger(jumlah * harga)
    ) {
        event.preventDefault();
        alert('Periksa kembali barang, jumlah, dan harga modal.');
        return;
    }

    if (!confirm(
        'Apakah kamu yakin ingin menyimpan transaksi barang masuk?'
    )) {
        event.preventDefault();
    }
});
</script>

</body>
</html>
