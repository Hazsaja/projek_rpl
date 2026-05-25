<?php
session_start();
include 'koneksi.php';

// Proteksi Halaman User
if (!isset($_SESSION['login'])) {
    header("Location: index.php");
    exit;
}

$user_id = $_SESSION['id'];
    
$query = "SELECT * FROM pengajuan_surat WHERE user_id = '$user_id' ORDER BY tanggal_pengajuan DESC";
$result = mysqli_query($koneksi, $query);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Riwayat Surat Saya</title>
    <link rel="stylesheet" href="menu_style.css">
</head>
<body class="dashboard-body">
    <div class="main-container">
        <nav class="sidebar">
            <div class="sidebar-header">
                <div class="logo-box">
                    <span class="logo-text">Jaya</span>
                    <button class="menu-toggle-btn">○</button>
                </div>
            </div>
            <ul class="nav-links">
                <li class="nav-item"> 
                    <a href="menu.php" class="nav-link">
                        <i class="icon-create-document"></i>
                        <span>Pembuatan Surat</span>
                    </a>
                </li>
                <li class="nav-item active-gradient">
                    <a href="menu_riwayat.php" class="nav-link history-link">
                        <i class="icon-history"></i>
                        <span>Riwayat Surat</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="logout.php" class="nav-link history-link">
                        <i class="icon-history"></i>
                        <span>Keluar</span>
                    </a>
                </li>
            </ul>
        </nav>

       <div class="content-area">
            <header class="top-header">
                <div class="top-header-right">
                    <div class="notifications">Halo, <?= $_SESSION['nama'] ?? 'warga'; ?>!
                        <i class="icon-bell"></i>
                        <span class="notification-badge">1</span>
                    </div>
                    <div class="user-profile">
                        <img src="https://via.placeholder.com/40" alt="User Profile" class="profile-img">
                        <i class="icon-status-active"></i>
                    </div>
                </div>
            </header>

            <main class="page-content">
                <div class="content-header">
                    <p class="breadcrumb">Home / Riwayat Surat</p>
                </div>

                <div class="info-alert">
                    <div class="info-header">
                        <strong>Informasi!</strong>
                        <button class="close-btn">&times;</button>
                    </div>
                    <ul class="info-list">
                        <li>Tombol Download muncul ketika surat telah disetujui oleh Admin, harap untuk menunggu.</li>
                        <li>Disarankan ukuran layar 90%.</li>
                    </ul>
                </div>

                <div class="table-card">
                    <div class="table-header-row">
                        <h2 class="table-title">
                            <a href="menu.php" style="text-decoration: none;">
                                <span class="back-arrow">&larr;</span>
                            </a> 
                            Riwayat Surat Yang Diajukan
                        </h2>
                    </div>

                    <div class="table-controls">
                        <div class="show-entries">
                            Show 
                            <select>
                                <option>10</option>
                                <option>25</option>
                                <option>50</option>
                            </select> 
                            entries
                        </div>
                        <div class="search-box">
                            Search: <input type="text">
                        </div>
                    </div>

                    <div class="table-responsive">
                        <table class="data-table">
                            <thead>
                                <tr>
                                    <th>NO <span>&uarr;&darr;</span></th>
                                    <th>TANGGAL MASUK <span>&uarr;&darr;</span></th>
                                    <th>JENIS SURAT <span>&uarr;&darr;</span></th>
                                    <th>KEPUTUSAN <span>&uarr;&darr;</span></th>
                                    <th>NIK <span>&uarr;&darr;</span></th>
                                    <th>KETERANGAN DITOLAK <span>&uarr;&darr;</span></th>
                                    <th>TANGGAL APPROVE <span>&uarr;&darr;</span></th>
                                    <th>AKTIVITAS <span>&uarr;&darr;</span></th>
                                </tr>
                            </thead>
                            <tbody>
                               <?php 
                                $no = 1;
                                if(mysqli_num_rows($result) > 0):
                                    while($row = mysqli_fetch_assoc($result)): 
                                ?>
                                <tr>
                                    <td><?= $no++; ?></td>
                                    <td><?=  date('d/m/Y', strtotime($row['tanggal_pengajuan'])); ?></td>
                                    <td><?= $row['jenis_surat']; ?></td>
                                    <td><span class="badge-<?= $row['status_surat']; ?>"><?= ucfirst($row['status_surat']); ?></span></td>
                                    <td><?= $_SESSION['nik']; ?></td>
                                    <td><?= $row['keterangan_ditolak'] ?? '-'; ?></td>
                                    <td><?= $row['tanggal_approve'] ? date('d/m/Y', strtotime($row['tanggal_approve'])) : '-'; ?></td>
                                    <td>
                                        <?php if($row['status_surat'] == 'disetujui'): ?>
                                            <a href="cetak_surat.php?id=<?= $row['id']; ?>" target="_blank" class="btn-action btn-print" style="text-decoration:none; display:inline-block;">Cetak</a>
                                        <?php else: ?>
                                            -
                                        <?php endif; ?>
                                    </td>
                                </tr>
                                <?php 
                                    endwhile; 
                                else:
                                ?>
                                <tr><td colspan="7" style="text-align:center;">Belum ada pengajuan surat.</td></tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>

                    <div class="table-footer">
                        <div class="showing-info">Showing 0 to 0 of 0 entries</div>
                        <div class="pagination">
                            <button class="page-btn disabled">Previous</button>
                            <button class="page-btn disabled">Next</button>
                        </div>
                    </div>
                </div>
            </main> 
        </div> 
    </div> 
    <script src="script.js"></script>
</body>
</html>