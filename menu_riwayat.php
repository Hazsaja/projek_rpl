<?php
require_once __DIR__ . '/config/koneksi.php';
require_once __DIR__ . '/config/session.php';
require_once __DIR__ . '/templates/sidebar.php';
require_once __DIR__ . '/templates/navbar.php';
require_once __DIR__ . '/templates/footer.php';

require_login();

// Menggunakan session 'id' atau 'user_id' tergantung pengaturan login Anda
$user_id = $_SESSION['user_id'] ?? $_SESSION['id'];

// Menggunakan JOIN untuk mengambil data relasi dari jenis_surat dan data_pemohon
$query = "
    SELECT 
        p.pengajuan_id, 
        p.tanggal_pengajuan, 
        p.status, 
        p.catatan_admin, 
        p.tanggal_verifikasi, 
        js.nama_surat, 
        dp.nik 
    FROM pengajuan p
    JOIN jenis_surat js ON p.jenis_surat_id = js.jenis_surat_id
    JOIN data_pemohon dp ON p.pengajuan_id = dp.pengajuan_id
    WHERE p.user_id = '$user_id' 
    ORDER BY p.tanggal_pengajuan DESC
";
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
        <?php render_sidebar('riwayat', $_SESSION['status'] ?? 'user'); ?>

        <div class="content-area">
            <?php render_navbar($_SESSION['nama'] ?? 'Warga'); ?>

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
                                if (mysqli_num_rows($result) > 0):
                                    while ($row = mysqli_fetch_assoc($result)):
                                ?>
                                <tr>
                                    <td><?= $no++; ?></td>
                                    <td><?= date('d/m/Y', strtotime($row['tanggal_pengajuan'])); ?></td>
                                    
                                    <!-- Diubah menggunakan field nama_surat dari tabel jenis_surat -->
                                    <td><?= $row['nama_surat']; ?></td>
                                    
                                    <!-- Diubah menggunakan field status dari tabel pengajuan -->
                                    <td><span class="badge-<?= $row['status']; ?>"><?= ucfirst($row['status']); ?></span></td>
                                    
                                    <!-- Diubah menggunakan NIK dari tabel data_pemohon agar lebih akurat jika mengajukan untuk keluarga -->
                                    <td><?= $row['nik']; ?></td>
                                    
                                    <!-- Diubah menggunakan field catatan_admin -->
                                    <td><?= $row['catatan_admin'] ? $row['catatan_admin'] : '-'; ?></td>
                                    
                                    <!-- Diubah menggunakan field tanggal_verifikasi -->
                                    <td><?= $row['tanggal_verifikasi'] ? date('d/m/Y', strtotime($row['tanggal_verifikasi'])) : '-'; ?></td>
                                    
                                    <td>
                                        <!-- Penyesuaian pengecekan status menjadi 'disetujui' sesuai enum di database -->
                                        <?php if ($row['status'] == 'disetujui'): ?>
                                            <a href="cetak_surat.php?pengajuan_id=<?= $row['pengajuan_id']; ?>" target="_blank" class="btn-action btn-print" style="text-decoration:none; display:inline-block;">Cetak</a>
                                        <?php else: ?>
                                            -
                                        <?php endif; ?>
                                    </td>
                                </tr>
                                <?php
                                    endwhile;
                                else:
                                ?>
                                <tr><td colspan="8" style="text-align:center;">Belum ada pengajuan surat.</td></tr>
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
    <?php render_footer(); ?>
</body>
</html>