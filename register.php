<?php
session_start();
include 'koneksi.php';


if (!isset($_SESSION['login']) || $_SESSION['status'] !== 'admin') {
    header("Location: index.php");
    exit;
}


if (isset($_POST['register'])) {
    $nama     = mysqli_real_escape_string($koneksi, $_POST['nama']);
    $nik      = mysqli_real_escape_string($koneksi, $_POST['nik']);
    $email    = mysqli_real_escape_string($koneksi, $_POST['email']);
    $password = mysqli_real_escape_string($koneksi, $_POST['password']);
    $status   = mysqli_real_escape_string($koneksi, $_POST['status']);

   
    $password_hashed = password_hash($password, PASSWORD_DEFAULT);

    $cek_query = "SELECT * FROM users WHERE email='$email' OR nik='$nik'";
    $cek_result = mysqli_query($koneksi, $cek_query);

    if (mysqli_num_rows($cek_result) > 0) {
        $error = "Gagal! Email atau NIK sudah terdaftar.";
    } else {
        
        $query = "INSERT INTO users (nama, nik, email, password, status) 
                  VALUES ('$nama', '$nik', '$email', '$password_hashed', '$status')";
        
        if (mysqli_query($koneksi, $query)) {
            $success = "Pengguna berhasil didaftarkan!";
        } else {
            $error = "Error: " . mysqli_error($koneksi);
        }
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
                <p style="color: red; font-size: 12px; text-align: center; margin-top: 5px;"><?= $error; ?></p>
            <?php endif; ?>
            <?php if(isset($success)): ?>
                <p style="color: green; font-size: 12px; text-align: center; margin-top: 5px;"><?= $success; ?></p>
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