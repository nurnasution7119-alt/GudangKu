
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manajemen Akun - GudangKu</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        body {
            background: #f4f6f9;
            font-family: Arial, sans-serif;
        }

        .container-box {
            background: white;
            padding: 25px;
            border-radius: 14px;
            box-shadow: 0 3px 12px rgba(0,0,0,0.05);
        }
    </style>
</head>
<body>

<div class="container py-5">

    <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-3">
        <div>
            <h3 class="fw-bold">👤 Manajemen Akun Karyawan</h3>
            <p class="text-muted mb-0">
                Kelola akun karyawan GudangKu.
            </p>
        </div>

        <a href="<?= base_url('manajemen-akun/tambah') ?>"
           class="btn btn-primary">
            + Tambah Karyawan
        </a>
    </div>

    <?php if (session()->getFlashdata('success')): ?>
        <div class="alert alert-success">
            <?= esc(session()->getFlashdata('success')) ?>
        </div>
    <?php endif; ?>

    <?php if (session()->getFlashdata('error')): ?>
        <div class="alert alert-danger">
            <?= esc(session()->getFlashdata('error')) ?>
        </div>
    <?php endif; ?>

    <div class="container-box">

        <div class="d-flex justify-content-between align-items-center mb-3">
            <h5 class="mb-0">Daftar Karyawan</h5>

            <a href="<?= base_url('dashboard') ?>"
               class="btn btn-outline-secondary btn-sm">
                ← Kembali ke Dashboard
            </a>
        </div>

        <div class="table-responsive">

            <table class="table table-bordered table-hover align-middle">

                <thead class="table-light">
                    <tr>
                        <th>No</th>
                        <th>Nama</th>
                        <th>Username</th>
                        <th>Email</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>

                <tbody>

                    <?php if (!empty($karyawan)): ?>

                        <?php $no = 1; ?>

                        <?php foreach ($karyawan as $pegawai): ?>

                            <tr>
                                <td><?= $no++ ?></td>

                                <td><?= esc($pegawai['nama']) ?></td>

                                <td><?= esc($pegawai['username']) ?></td>

                                <td><?= esc($pegawai['email'] ?? '-') ?></td>

                                <td>
                                    <?php if ($pegawai['is_verified'] == 1): ?>
                                        <span class="badge bg-success">
                                            Terverifikasi
                                        </span>
                                    <?php else: ?>
                                        <span class="badge bg-warning text-dark">
                                            Belum Terverifikasi
                                        </span>
                                    <?php endif; ?>
                                </td>

                                <td>
                                    <div class="d-flex gap-2">

                                        <a href="<?= base_url('manajemen-akun/edit/' . $pegawai['id']) ?>"
                                           class="btn btn-warning btn-sm">
                                            Edit
                                        </a>

                                        <form
                                            action="<?= base_url('manajemen-akun/hapus/' . $pegawai['id']) ?>"
                                            method="post"
                                            onsubmit="return confirm('Yakin ingin menghapus akun ini?')"
                                        >
                                            <?= csrf_field() ?>

                                            <button type="submit"
                                                    class="btn btn-danger btn-sm">
                                                Hapus
                                            </button>
                                        </form>

                                    </div>
                                </td>
                            </tr>

                        <?php endforeach; ?>

                    <?php else: ?>

                        <tr>
                            <td colspan="6"
                                class="text-center text-muted py-4">
                                Belum ada akun Karyawan.
                            </td>
                        </tr>

                    <?php endif; ?>

                </tbody>

            </table>

        </div>

    </div>

    <p class="text-center text-muted mt-4">
        © <?= date('Y') ?> GudangKu
    </p>

</div>

</body>
</html>
