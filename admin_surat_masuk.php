<?php
session_start();
include 'koneksi.php';


if (!isset($_SESSION['login']) || $_SESSION['status'] !== 'admin') {
    header("Location: index.php");
    exit;
}

if (isset($_POST['aksi'])) {
    $id = $_POST['id_surat'];
    $status = $_POST['aksi']; 
    $keterangan = mysqli_real_escape_string($koneksi, $_POST['keterangan']);
    $tanggal_sekarang = date('Y-m-d H:i:s');

    $query_update = "UPDATE pengajuan_surat SET 
                     status_surat = '$status', 
                     keterangan_ditolak = '$keterangan', 
                     tanggal_approve = '$tanggal_sekarang' 
                     WHERE id = '$id'";
    
    mysqli_query($koneksi, $query_update);
    echo "<script>alert('Status surat berhasil diperbarui!'); window.location='admin_surat_masuk.php';</script>";
}

$query = "SELECT * FROM pengajuan_surat ORDER BY tanggal_pengajuan DESC";
$result = mysqli_query($koneksi, $query);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - Daftar Surat Masuk</title>
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
                    <a href="admin.php" class="nav-link">
                        <i class="icon-create-document"></i>
                        <span>Admin Control</span>
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
                    <div class="notifications">Halo, <?= $_SESSION['nama'] ?? 'admin'; ?>!
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
                    <p class="breadcrumb">Home / <a href="admin.php" style="color: #888; text-decoration: none;">Admin Control</a> / Surat Masuk</p>
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
                            Riwayat Surat Yang Masuk
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
                                    <th>NAMA PENGAJU <span>&uarr;&darr;</span></th>
                                    <th>NIK <span>&uarr;&darr;</span></th>
                                    <th>JENIS SURAT <span>&uarr;&darr;</span></th>
                                    <th>STATUS <span>&uarr;&darr;</span></th>
                                    <th>DOKUMEN <span>&uarr;&darr;</span></th>
                                    <th>TANGGAL APPROVE <span>&uarr;&darr;</span></th>
                                    <th>SELISIH <span>&uarr;&darr;</span></th>
                                    <th>AKSI <span>&uarr;&darr;</span></th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php 
                                $no = 1;
                                while($row = mysqli_fetch_assoc($result)): 
                                    // Hitung selisih hari dari tanggal pengajuan ke hari ini
                                    $tgl_awal = new DateTime($row['tanggal_pengajuan']);
                                    $tgl_akhir = new DateTime();
                                    $diff = $tgl_awal->diff($tgl_akhir);
                                ?>
                                <tr>
                                    <td><?= $no++; ?></td>
                                    <td><?= $row['nama_pengaju']; ?></td>
                                    <td><?= $row['nik_pengaju']; ?></td>
                                    <td><?= $row['jenis_surat']; ?></td>
                                    <td><span class="badge-<?= $row['status_surat']; ?>"><?= ucfirst($row['status_surat']); ?></span></td>
                                    <td>
                                        <a href="uploads/<?= $row['file_ktp_kk']; ?>" target="_blank">KTP</a> | 
                                        <a href="uploads/<?= $row['file_pas_foto']; ?>" target="_blank">Foto</a>
                                    </td>
                                    <td><?= $row['tanggal_approve'] ?? '-'; ?></td>
                                    <td><?= $diff->d; ?> Hari</td>
                                    <td>
                                        <?php if($row['status_surat'] == 'pending'): ?>
                                        <form method="POST" style="display:inline;">
                                            <input type="hidden" name="id_surat" value="<?= $row['id']; ?>">
                                            <input type="text" name="keterangan" placeholder="Catatan (jika ditolak)">
                                            <button type="submit" name="aksi" value="disetujui" class="btn-approve">Terima</button>
                                            <button type="submit" name="aksi" value="ditolak" class="btn-reject">Tolak</button>
                                        </form>
                                        <?php else: ?>
                                            Selesai
                                        <?php endif; ?>
                                    </td>
                                </tr>
                                <?php endwhile; ?>
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