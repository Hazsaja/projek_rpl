<?php
require_once __DIR__ . '/config/koneksi.php';
require_once __DIR__ . '/config/session.php';

require_admin();

if (isset($_POST['register'])) {
    $nama     = trim($_POST['nama'] ?? '');
    $nik      = trim($_POST['nik'] ?? '');
    $email    = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    $status   = $_POST['status'] ?? '';

    if ($nama === '' || $nik === '' || $email === '' || $password === '' || $status === '') {
        $error = "Semua kolom wajib diisi.";
    } elseif (!preg_match('/^[0-9]{16}$/', $nik)) {
        $error = "NIK harus terdiri dari 16 digit angka.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = "Format email tidak valid.";
    } elseif (!in_array($status, ['admin', 'user'], true)) {
        $error = "Role pengguna tidak valid.";
    } else {
        $password_hashed = password_hash($password, PASSWORD_DEFAULT);

        $cek_stmt = mysqli_prepare($koneksi, "SELECT id FROM users WHERE email = ? OR nik = ? LIMIT 1");
        mysqli_stmt_bind_param($cek_stmt, "ss", $email, $nik);
        mysqli_stmt_execute($cek_stmt);
        $cek_result = mysqli_stmt_get_result($cek_stmt);

        if (mysqli_num_rows($cek_result) > 0) {
            $error = "Gagal! Email atau NIK sudah terdaftar.";
        } else {
            $insert_stmt = mysqli_prepare($koneksi, "INSERT INTO users (nama, nik, email, password, status) VALUES (?, ?, ?, ?, ?)");
            mysqli_stmt_bind_param($insert_stmt, "sssss", $nama, $nik, $email, $password_hashed, $status);

            if (mysqli_stmt_execute($insert_stmt)) {
                $success = "Pengguna berhasil didaftarkan!";
            } else {
                $error = "Registrasi gagal. Silakan coba lagi.";
            }

            mysqli_stmt_close($insert_stmt);
        }

        mysqli_stmt_close($cek_stmt);
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registrasi Pengguna - Admin</title>
    <link rel="stylesheet" href="style.css">
</head>
<body class="login">
    <div class="login-box" style="height: auto; padding-bottom: 40px; margin-top: 20px; margin-bottom: 20px;">
        <div class="text-box">
            <h5>Registrasi Pengguna</h5>
            <p>Hanya Admin yang dapat mendaftarkan akun baru.</p>
            
            <?php if(isset($error)): ?>
                <p style="color: red; font-size: 12px; text-align: center; margin-top: 5px;"><?= htmlspecialchars($error, ENT_QUOTES, 'UTF-8'); ?></p>
            <?php endif; ?>
            <?php if(isset($success)): ?>
                <p style="color: green; font-size: 12px; text-align: center; margin-top: 5px;"><?= htmlspecialchars($success, ENT_QUOTES, 'UTF-8'); ?></p>
            <?php endif; ?>
        </div>

        <form action="" method="POST">
            <div class="inputLogin-box" style="margin-top: 15px;">
                <input type="text" name="nama" class="nik" placeholder="Nama Lengkap" required style="margin-bottom: 10px;">
                <input type="text" name="nik" class="nik" placeholder="16 Digit NIK" required maxlength="16" style="margin-bottom: 10px;">
                <input type="email" name="email" class="nik" placeholder="E-mail" required style="margin-bottom: 10px;">
                <input type="password" name="password" class="pass" placeholder="Kata Sandi" required style="margin-bottom: 10px;">
                
                <select name="status" class="nik" required style="margin-bottom: 10px; cursor: pointer; color: #666;">
                    <option value="" disabled selected>-- Pilih Role Pengguna --</option>
                    <option value="user">Warga (User)</option>
                    <option value="admin">Admin Desa</option>
                </select>
            </div>
            
            <div class="login-button" style="margin-top: 20px;">
                <button type="submit" name="register">Daftarkan Akun</button>  
            </div>
            
            <div style="text-align: center; margin-top: 20px;">
                <a href="admin.php" style="font-size: 13px; color: #2A7B9B; text-decoration: none; font-weight: bold;">&larr; Kembali ke Dashboard</a>
            </div>
        </form>
    </div>
</body>
</html>
