<?php
// Pengaturan akun email SMTP
define('SMTP_HOST', 'smtp.gmail.com');
define('SMTP_PORT', 465); // Gunakan 465 untuk SSL, atau 587 untuk TLS
define('SMTP_USER', 'pelayanan.administrasi@gmail.com'); // Ganti dengan email asli Anda
define('SMTP_PASS', 'rbqvqlfgzrxaxdmv'); // Ganti dengan 16 digit App Password dari Google (tanpa spasi)

// Nama pengirim yang akan muncul di email warga
define('SENDER_NAME', 'Admin Desa'); 
define('SENDER_EMAIL', 'pelayanan.administrasi@gmail.com'); // Samakan dengan SMTP_USER
?>