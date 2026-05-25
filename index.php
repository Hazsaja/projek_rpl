<?php
session_start();
include 'koneksi.php';


if (isset($_SESSION['login'])) {
    if ($_SESSION['status'] == 'admin') {
        header("Location: admin.php");
    } else {
        header("Location: menu.php");
    }
    exit;
}

if (isset($_POST['masuk'])) {
    $email = mysqli_real_escape_string($koneksi, $_POST['email']);
    $password = mysqli_real_escape_string($koneksi, $_POST['password']);

    $query = "SELECT * FROM users WHERE email='$email'";
    $result = mysqli_query($koneksi, $query);

    if (mysqli_num_rows($result) === 1) {
        $data = mysqli_fetch_assoc($result);

        // Verifikasi password hash
        if (password_verify($password, $data['password'])) {
            // Set Session
            $_SESSION['login']  = true;
            $_SESSION['id']     = $data['id'];
            $_SESSION['nama']   = $data['nama'];
            $_SESSION['status'] = $data['status'];
            $_SESSION['nik'] = $data['nik'];

            // Redirect berdasarkan status
            if ($data['status'] == 'admin') {
                header("Location: admin.php");
            } else {
                header("Location: menu.php");
            }
            exit;
        } else {
            $error = "Password yang Anda masukkan salah!";
        }
    } else {
        $error = "Email tidak ditemukan!";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Page</title>
    <link rel="stylesheet" href="style.css">
</head>
<body class="login">
    <div class="login-box">
        <div class="text-box">
            <h5>Selamat Datang</h5>
            <p>Harap Masukan Email anda yang telah terdaftar</p>
            
            <?php if(isset($error)): ?>
                <p style="color: red; font-size: 12px; text-align: center;"><?= $error; ?></p>
            <?php endif; ?>
        </div>

        <form action="" method="POST">
            <div class="inputLogin-box">
                <input type="text" name="email" class="nik" placeholder="E-mail" required>
                <input type="password" name="password" class="pass" placeholder="Kata Sandi" required>
            </div>
            <div class="check-forgot">
                <div class="checkbox-box">
                    <input type="checkbox">
                    <p>Ingat saya</p>
                </div>
                <a href="#">Lupa Kata Sandi</a>
            </div>
            <div class="login-button">
                <button type="submit" name="masuk">Masuk</button>  
            </div>
        </form>
    </div>
    <script src="script.js"></script>
</body>
</html>