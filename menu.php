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
                    <p class="breadcrumb">
                        <a href="menu.php" style="color: #888; text-decoration: none;">Home</a> /
                        <strong style="color: #333;">Pembuatan Surat</strong>
                    </p>
                </div>

                <div class="document-grid">
                    <?php
                    while ($surat = mysqli_fetch_assoc($q_jenis_surat)) {
                    ?>

                        <div class="doc-card">
                            <div class="card-header banner-background">
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
    <?php if (isset($_SESSION['success_pengajuan'])): ?>
        <div id="successModal" class="custom-modal-overlay">
            <div class="custom-modal-box animate-pop">
                <div class="modal-icon success">✓</div>
                <h3>Pengajuan Berhasil!</h3>
                <p>Surat Anda telah berhasil diajukan dan saat ini sedang menunggu verifikasi oleh Admin.</p>
                <button id="closeSuccessModal" class="modal-btn btn-primary" style="width: 100%;">Selesai</button>
            </div>
        </div>

        <?php unset($_SESSION['success_pengajuan']); ?>

        <script>
            document.getElementById('closeSuccessModal').addEventListener('click', function() {
                document.getElementById('successModal').style.display = 'none';
            });
        </script>
    <?php endif; ?>
    <?php render_footer(); ?>
</body>

</html>