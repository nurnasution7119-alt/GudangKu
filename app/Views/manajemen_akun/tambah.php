
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Karyawan - GudangKu</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body style="background:#f4f6f9;">

<div class="container py-5">
    <div class="card shadow-sm border-0 p-4 mx-auto" style="max-width:600px;">

        <h3 class="fw-bold mb-2">Tambah Karyawan</h3>
        <p class="text-muted">Buat akun baru untuk karyawan GudangKu.</p>

        <?php if (session()->getFlashdata('error')): ?>
            <div class="alert alert-danger">
                <?= esc(session()->getFlashdata('error')) ?>
            </div>
        <?php endif; ?>

        <form action="<?= base_url('manajemen-akun/simpan') ?>" method="post">
            <?= csrf_field() ?>

            <div class="mb-3">
                <label class="form-label">Nama Lengkap</label>
                <input type="text" name="nama" class="form-control"
                       value="<?= old('nama') ?>" required>
            </div>

            <div class="mb-3">
                <label class="form-label">Username</label>
                <input type="text" name="username" class="form-control"
                       value="<?= old('username') ?>" required>
            </div>

            <div class="mb-3">
                <label class="form-label">Email</label>
                <input type="email" name="email" class="form-control"
                       value="<?= old('email') ?>" required>
            </div>

            <div class="mb-3">
                <label class="form-label">Password</label>

                <div class="input-group">
                    <input type="password" name="password"
                           id="password" class="form-control"
                           required minlength="8">

                    <button class="btn btn-outline-secondary"
                            type="button"
                            onclick="togglePassword()"
                            id="toggleBtn">
                        👁️
                    </button>
                </div>

                <small class="text-muted">Minimal 8 karakter.</small>
            </div>

            <div class="d-flex gap-2 mt-4">
                <button type="submit" class="btn btn-primary">
                    Simpan Karyawan
                </button>

                <a href="<?= base_url('manajemen-akun') ?>"
                   class="btn btn-outline-secondary">
                    Batal
                </a>
            </div>
        </form>

    </div>
</div>

<script>
function togglePassword() {
    const password = document.getElementById('password');
    const button = document.getElementById('toggleBtn');

    if (password.type === 'password') {
        password.type = 'text';
        button.textContent = '🙈';
    } else {
        password.type = 'password';
        button.textContent = '👁️';
    }
}
</script>

</body>
</html>
