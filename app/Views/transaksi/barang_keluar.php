
<?php
$isOwner = (($role ?? '') === 'owner');
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Barang Keluar - GudangKu</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="bg-light">

<div class="container py-5">

    <div class="card shadow-sm border-0">
        <div class="card-header bg-danger text-white p-3">
            <h4 class="mb-0">📤 Tambah Barang Keluar</h4>
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

            <form action="<?= base_url('transaksi/simpan-keluar') ?>"
                  method="post"
                  id="formBarangKeluar">

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
                                data-stok="<?= esc($item['stok']) ?>"
                                <?php if ($isOwner): ?>
                                    data-harga="<?= esc($item['harga_jual']) ?>"
                                <?php endif; ?>>

                                <?= esc($item['kode_barang']) ?> -
                                <?= esc($item['nama_barang']) ?>
                            </option>
                        <?php endforeach; ?>

                    </select>
                </div>

                <div class="mb-3">
                    <label class="form-label fw-semibold">Stok Tersedia</label>

                    <input type="text"
                           id="stok_tersedia"
                           class="form-control bg-light"
                           value="Pilih barang terlebih dahulu"
                           readonly>
                </div>

                <?php if ($isOwner): ?>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Harga Jual per Barang</label>

                        <input type="text"
                               id="harga_jual"
                               class="form-control bg-light"
                               value="Rp 0"
                               readonly>
                    </div>
                <?php endif; ?>

                <div class="mb-3">
                    <label class="form-label fw-semibold">Jumlah Barang Keluar</label>

                    <input type="number"
                           name="jumlah"
                           id="jumlah"
                           class="form-control"
                           min="1"
                           step="1"
                           placeholder="Contoh: 2"
                           required>

                    <small id="pesanStok" class="text-muted">
                        Masukkan jumlah barang yang keluar.
                    </small>
                </div>

                <?php if ($isOwner): ?>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Total Penjualan</label>

                        <input type="text"
                               id="total_harga"
                               class="form-control fw-bold bg-light"
                               value="Rp 0"
                               readonly>
                    </div>
                <?php endif; ?>

                <div class="mb-3">
                    <label class="form-label fw-semibold">Keterangan</label>

                    <textarea name="keterangan"
                              class="form-control"
                              rows="3"
                              maxlength="500"
                              placeholder="Contoh: Penjualan barang"></textarea>
                </div>

                <div class="d-flex gap-2">
                    <button type="submit"
                            id="btnSimpan"
                            class="btn btn-danger">
                        Simpan Barang Keluar
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
const stokTersedia = document.getElementById('stok_tersedia');
const pesanStok = document.getElementById('pesanStok');
const form = document.getElementById('formBarangKeluar');
const btnSimpan = document.getElementById('btnSimpan');

const isOwner = <?= $isOwner ? 'true' : 'false' ?>;

const hargaJual = isOwner
    ? document.getElementById('harga_jual')
    : null;

const totalHarga = isOwner
    ? document.getElementById('total_harga')
    : null;

let stok = 0;
let harga = 0;

function formatRupiah(angka) {
    return 'Rp ' + angka.toLocaleString('id-ID');
}

function hitungTotal() {
    if (!isOwner) {
        return;
    }

    const jumlah = Number(inputJumlah.value);

    if (!Number.isSafeInteger(jumlah) || jumlah < 1) {
        totalHarga.value = 'Rp 0';
        return;
    }

    totalHarga.value = formatRupiah(jumlah * harga);
}

function perbaruiBarang() {
    const option = pilihanBarang.selectedOptions[0];

    if (!option || !option.value) {
        stok = 0;
        harga = 0;

        stokTersedia.value = 'Pilih barang terlebih dahulu';

        if (isOwner) {
            hargaJual.value = 'Rp 0';
            totalHarga.value = 'Rp 0';
        }

        inputJumlah.value = '';
        inputJumlah.removeAttribute('max');
        return;
    }

    stok = Number(option.dataset.stok);

    if (isOwner) {
        harga = Number(option.dataset.harga);
        hargaJual.value = formatRupiah(harga);
    }

    stokTersedia.value = stok + ' unit';
    inputJumlah.max = stok;

    pesanStok.textContent = 'Stok tersedia: ' + stok + ' unit';
    pesanStok.className = 'text-muted';

    btnSimpan.disabled = false;

    hitungTotal();
}

pilihanBarang.addEventListener('change', perbaruiBarang);

inputJumlah.addEventListener('input', function() {
    const jumlah = Number(this.value);

    if (jumlah > stok) {
        pesanStok.textContent = 'Jumlah melebihi stok yang tersedia!';
        pesanStok.className = 'text-danger';
        btnSimpan.disabled = true;
    } else {
        pesanStok.textContent = 'Stok tersedia: ' + stok + ' unit';
        pesanStok.className = 'text-muted';
        btnSimpan.disabled = false;
    }

    hitungTotal();
});

form.addEventListener('submit', function(event) {
    const jumlah = Number(inputJumlah.value);

    if (!pilihanBarang.value ||
        !Number.isSafeInteger(jumlah) ||
        jumlah < 1 ||
        jumlah > stok) {

        event.preventDefault();
        alert('Periksa kembali barang dan jumlah yang akan dikeluarkan.');
        return;
    }

    if (!confirm(
        'Apakah kamu yakin ingin menyimpan transaksi barang keluar?'
    )) {
        event.preventDefault();
    }
});
</script>

</body>
</html>
