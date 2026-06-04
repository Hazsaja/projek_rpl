<?php
require_once __DIR__ . '/config/koneksi.php'; // Tambahkan koneksi database
require_once __DIR__ . '/config/session.php';
require_once __DIR__ . '/templates/sidebar.php';
require_once __DIR__ . '/templates/navbar.php';
require_once __DIR__ . '/templates/footer.php';

require_login();

// Mengambil daftar jenis surat yang berstatus aktif dari database
$q_jenis_surat = mysqli_query($koneksi, "SELECT jenis_surat_id, nama_surat, deskripsi FROM jenis_surat WHERE aktif = 1");
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pembuatan Surat</title>
    <link rel="stylesheet" href="menu_style.css">
</head>
<body class="dashboard-body">
    <div class="main-container">
        <?php render_sidebar('pembuatan', $_SESSION['status'] ?? 'user', 'HazelJaya'); ?>

        <div class="content-area">
            <?php render_navbar($_SESSION['nama'] ?? 'Warga'); ?>

            <main class="page-content">
                <div class="content-header">
                    <p class="breadcrumb">Home / Pembuatan Surat</p>
                </div>

                <div class="document-grid">
                    <?php 
                    // Array untuk memutar warna/tema CSS card agar tidak membosankan
                    $themes = ['theme-gradient-full', 'theme-green', 'theme-blue-green', 'theme-cream', 'theme-purple', 'theme-orange'];
                    $theme_index = 0;

                    // Looping otomatis untuk membuat card surat sesuai database
                    while ($surat = mysqli_fetch_assoc($q_jenis_surat)) { 
                        // Menentukan warna tema
                        $current_theme = $themes[$theme_index % count($themes)];
                        $theme_index++;
                    ?>
                    
                    <div class="doc-card">
                        <div class="card-header <?= $current_theme; ?>">
                            <div class="header-content">
                                <h2 class="card-title"><?= htmlspecialchars($surat['nama_surat']); ?></h2>
                            </div>
                        </div>
                        <div class="card-body">
                            <p class="card-description"><?= htmlspecialchars($surat['deskripsi']); ?></p>
                            
                            <a href="form_pengajuan.php?jenis_surat_id=<?= $surat['jenis_surat_id']; ?>" style="text-decoration: none;">
                                <button class="buat-surat-btn">Buat Surat</button>
                            </a>
                        </div>
                    </div>

                    <?php } ?>
                </div>
            </main>
        </div>
    </div>
    <?php render_footer(); ?>
</body>
</html>