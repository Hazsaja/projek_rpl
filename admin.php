<?php
require_once __DIR__ . '/config/session.php';
require_once __DIR__ . '/templates/sidebar.php';
require_once __DIR__ . '/templates/navbar.php';
require_once __DIR__ . '/templates/footer.php';

require_admin();
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - Dashboard Control</title>
    <link rel="stylesheet" href="menu_style.css">
</head>
<body class="dashboard-body">
    <div class="main-container">
        <?php render_sidebar('admin', 'admin'); ?>

        <div class="content-area">
            <?php render_navbar($_SESSION['nama'] ?? 'Admin', 3); ?>

            <main class="page-content">
                <div class="content-header">
                    <p class="breadcrumb">Home / Admin Control</p>
                </div>

                <div class="document-grid">
                    <div class="doc-card">
                        <div class="card-header theme-gradient-full">
                            <div class="header-content">
                                <h2 class="card-title">Antrean Surat Masuk</h2>
                            </div>
                        </div>
                        <div class="card-body">
                            <p class="card-description">Periksa dan kelola pengajuan dokumen baru dari warga yang menunggu tindakan persetujuan atau penolakan.</p>
                            <button class="buat-surat-btn" onclick="window.location.href='admin_surat_masuk.php'">Kelola Surat</button>
                        </div>
                    </div>

                    <div class="doc-card">
                        <div class="card-header theme-green">
                            <div class="header-content">
                                <h2 class="card-title">Manajemen Data Warga</h2>
                            </div>
                        </div>
                        <div class="card-body">
                            <p class="card-description">Kelola informasi akun pengguna, verifikasi NIK, serta pembaruan data warga yang terdaftar pada sistem.</p>
                            <button class="buat-surat-btn">Lihat Data</button>
                        </div>
                    </div>

                    <div class="doc-card">
                        <div class="card-header theme-blue-green">
                            <div class="header-content">
                                <h2 class="card-title">Arsip Surat Disetujui</h2>
                            </div>
                        </div>
                        <div class="card-body">
                            <p class="card-description">Akses riwayat seluruh surat yang telah selesai diproses, disetujui, dan dicetak dalam satu periode waktu.</p>
                            <button class="buat-surat-btn">Buka Arsip</button>
                        </div>
                    </div>

                    <div class="doc-card">
                        <div class="card-header theme-blue-green" style="background: linear-gradient(90deg, #2A7B9B 0%, #50BC88 100%);">
                            <div class="header-content">
                                <h2 class="card-title">Registrasi Akun Baru</h2>
                            </div>
                        </div>
                        <div class="card-body">
                            <p class="card-description">Daftarkan akun warga atau admin baru ke dalam sistem agar mereka bisa mengajukan surat.</p>
                            <a href="register.php" style="text-decoration: none;">
                                <button class="buat-surat-btn" style="width: 100%; cursor: pointer;">Buka Form Registrasi</button>
                            </a>
                        </div>
                    </div>

                    <div class="doc-card">
                        <div class="card-header theme-cream">
                            <div class="header-content">
                                <h2 class="card-title">Pengaturan Sistem</h2>
                            </div>
                        </div>
                        <div class="card-body">
                            <p class="card-description">Lakukan konfigurasi aplikasi, tambah jenis template surat baru, dan sesuaikan profil admin kelurahan/desa.</p>
                            <button class="buat-surat-btn">Pengaturan</button>
                        </div>
                    </div>
                </div>
            </main>
        </div>
    </div>
    <?php render_footer(); ?>
</body>
</html>
