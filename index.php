<?php
require_once __DIR__ . '/config/koneksi.php';
require_once __DIR__ . '/config/session.php';

redirect_authenticated_user();

if (isset($_POST['masuk'])) {
    $email = trim($_POST['nik'] ?? '');
    $password = $_POST['password'] ?? '';

    $stmt = mysqli_prepare($koneksi, "SELECT user_id, nama, nik, email, password, status FROM users WHERE nik = ? LIMIT 1");
    mysqli_stmt_bind_param($stmt, "s", $email);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    if (mysqli_num_rows($result) === 1) {
        $data = mysqli_fetch_assoc($result);

        // Verifikasi password hash
        if (password_verify($password, $data['password'])) {
            // Set Session
            $_SESSION['login']  = true;
            $_SESSION['user_id']     = $data['user_id'];
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

    mysqli_stmt_close($stmt);
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
                <p style="color: red; font-size: 12px; text-align: center;"><?= htmlspecialchars($error, ENT_QUOTES, 'UTF-8'); ?></p>
            <?php endif; ?>
        </div>

        <form action="" method="POST">
            <div class="inputLogin-box">
                <input type="number" name="nik" class="nik" placeholder="NIK" required>
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
