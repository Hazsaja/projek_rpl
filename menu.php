<?php
session_start();
if (!isset($_SESSION['login'])) {
    header("Location: index.php");
    exit;
}
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

        <nav class="sidebar">
            <div class="sidebar-header">
                <div class="logo-box">
                    <img src="asset/logo_web.png" alt="" class="icon-logo">
                    <button class="menu-toggle-btn">○</button>
                </div>
            </div>

            <ul class="nav-links">
                <li class="nav-item active-gradient"> 
                    <a href="menu.php" class="nav-link">
                        <img src="asset/home_page.png" alt="" class="icon-home">
                        <span>Pembuatan Surat</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="menu_riwayat.php" class="nav-link history-link">
                        <i class="icon-history"></i>
                        <span>Riwayat Surat</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="logout.php" class="nav-link history-link">
                        <img src="asset/icon_logout.png" alt="" class="icon-history">
                        <span>Keluar</span>
                    </a>
                </li>
            </ul>
        </nav>

        <div class="content-area">
            <header class="top-header">
                <div class="top-header-right">
                    <div class="notifications">Halo, <?= $_SESSION['nama'] ?? 'Warga'; ?>!
                        <i class="icon-bell"></i>
                        <span class="notification-badge">1</span>
                    </div>
                    <div class="user-profile">
                        <img src="asset/akun.png" alt="User Profile" class="profile-img">
                        <i class="icon-status-active"></i>
                    </div>
                </div>
            </header>

            <main class="page-content">
                <div class="content-header">
                    <p class="breadcrumb">Home / Pembuatan Surat</p>
                </div>

                <div class="document-grid">
                    <div class="doc-card">
                        <div class="card-header theme-gradient-full">
                            <div class="header-content">
                                <h2 class="card-title">Surat keterangan Domisili</h2>
                            </div>
                        </div>
                        <div class="card-body">
                            <p class="card-description">dokumen resmi dari kelurahan/desa yang menerangkan alamat tempat tinggal seseorang atau badan usaha, sering digunakan untuk keperluan administrasi</p>
                            <a href="surat.php"><button class="buat-surat-btn">Buat Surat</button></a>
                        </div>
                    </div>

                    <div class="doc-card">
                        <div class="card-header theme-green">
                            <div class="header-content">
                                <h2 class="card-title">Surat Keterangan Tidak Mampu (SKTM)</h2>
                            </div>
                        </div>
                        <div class="card-body">
                            <p class="card-description">dokumen resmi dari desa/kelurahan yang menyatakan seseorang kurang mampu secara finansial. Ini digunakan untuk keringanan biaya sekolah, pengobatan, atau bantuan sosial..</p>
                            <button class="buat-surat-btn">Buat Surat</button>
                        </div>
                    </div>

                    <div class="doc-card">
                        <div class="card-header theme-blue-green">
                            <div class="header-content">
                                <h2 class="card-title">Surat Keterangan Ahli Waris</h2>
                            </div>
                        </div>
                        <div class="card-body">
                            <p class="card-description">dokumen resmi yang menyatakan siapa saja yang berhak atas harta peninggalan seseorang yang sudah meninggal dunia.</p>
                            <button class="buat-surat-btn">Buat Surat</button>
                        </div>
                    </div>

                    <div class="doc-card">
                        <div class="card-header theme-cream">
                            <div class="header-content">
                                <h2 class="card-title">Surat Pernyataan Belum Menikah</h2>
                            </div>
                        </div>
                        <div class="card-body">
                            <p class="card-description">dokumen resmi yang menegaskan bahwa seseorang berstatus lajang atau belum pernah menikah secara resmi maupun agama. Surat ini diterbitkan oleh Kelurahan/Kecamatan (SKBM).</p>
                            <button class="buat-surat-btn">Buat Surat</button>
                        </div>
                    </div>
                </div> 
            </main> 
        </div> 
    </div> 
    <script src="script.js"></script>
</body>
</html>