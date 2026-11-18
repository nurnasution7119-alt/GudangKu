
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verifikasi OTP - GudangKu</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f4f6f9;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            margin: 0;
        }

        .container {
            background: white;
            padding: 30px;
            border-radius: 12px;
            width: 340px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
            text-align: center;
        }

        input {
            width: 100%;
            box-sizing: border-box;
            padding: 12px;
            margin: 15px 0;
            border: 1px solid #ccc;
            border-radius: 6px;
            font-size: 18px;
            text-align: center;
            letter-spacing: 5px;
        }

        button {
            width: 100%;
            padding: 12px;
            background: #2563eb;
            color: white;
            border: none;
            border-radius: 6px;
            cursor: pointer;
        }

        .message {
            color: #d00;
            margin-bottom: 10px;
        }

        .success {
            color: green;
        }
    </style>
</head>
<body>

<div class="container">
    <h2>Verifikasi Email</h2>
    <p>Masukkan 6 digit kode OTP yang dikirim ke email kamu.</p>

    <?php if (session()->getFlashdata('error')): ?>
        <p class="message">
            <?= esc(session()->getFlashdata('error')) ?>
        </p>
    <?php endif; ?>

    <?php if (session()->getFlashdata('success')): ?>
        <p class="success">
            <?= esc(session()->getFlashdata('success')) ?>
        </p>
    <?php endif; ?>

    <form action="<?= site_url('verify-otp') ?>" method="post">
        <?= csrf_field() ?>

        <input
            type="text"
            name="otp"
            placeholder="000000"
            maxlength="6"
            inputmode="numeric"
            pattern="[0-9]{6}"
            required
        >

        <button type="submit">Verifikasi OTP</button>
    </form>
</div>

</body>
</html>
