<?php
require_once __DIR__ . '/config/koneksi.php';
require_once __DIR__ . '/config/session.php';
require_once __DIR__ . '/templates/sidebar.php';
require_once __DIR__ . '/templates/navbar.php';
require_once __DIR__ . '/templates/footer.php';

require_admin();

// 1. Logika Update Status Surat
if (isset($_POST['aksi'])) {
    $id = mysqli_real_escape_string($koneksi, $_POST['id_surat']);
    $status = mysqli_real_escape_string($koneksi, $_POST['aksi']); // 'disetujui' atau 'ditolak'
    $catatan_admin = mysqli_real_escape_string($koneksi, $_POST['catatan_admin']);
    $tanggal_sekarang = date('Y-m-d H:i:s');

    // Penyesuaian nama kolom sesuai db_desa.sql (status, catatan_admin, tanggal_verifikasi)
    // Perhatikan WHERE pengajuan_id = '$id'
    $query_update = "UPDATE pengajuan SET
                     status = '$status',
                     catatan_admin = '$catatan_admin',
                     tanggal_verifikasi = '$tanggal_sekarang'
                     WHERE pengajuan_id = '$id'";

    mysqli_query($koneksi, $query_update);
    echo "<script>alert('Status surat berhasil diperbarui!'); window.location='admin_surat_masuk.php';</script>";
}

// 2. Query untuk mengambil data pengajuan beserta nama surat dan data pemohon
$query = "
    SELECT 
        p.pengajuan_id, 
        p.tanggal_pengajuan, 
        p.tanggal_verifikasi, 
        p.status, 
        js.nama_surat, 
        dp.nama AS nama_pengaju, 
        dp.nik AS nik_pengaju 
    FROM pengajuan p
    JOIN jenis_surat js ON p.jenis_surat_id = js.jenis_surat_id
    JOIN data_pemohon dp ON p.pengajuan_id = dp.pengajuan_id
    ORDER BY p.tanggal_pengajuan DESC
";
$result = mysqli_query($koneksi, $query);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - Daftar Surat Masuk</title>
    <link rel="stylesheet" href="menu_style.css">
    <style>
        /* Tambahan style agar dokumen terlihat rapi */
        .doc-link {
            display: block;
            font-size: 11px;
            color: #007bff;
            text-decoration: none;
            margin-bottom: 3px;
        }
        .doc-link:hover { text-decoration: underline; }
    </style>
</head>
<body class="dashboard-body">
    <div class="main-container">
        <?php render_sidebar('admin', 'admin'); ?>

        <div class="content-area">
            <?php render_navbar($_SESSION['nama'] ?? 'Admin'); ?>

            <main class="page-content">
                <div class="content-header">
                    <p class="breadcrumb">Home / <a href="admin.php" style="color: #888; text-decoration: none;">Admin Control</a> / Surat Masuk</p>
                </div>

                <div class="table-card">
                    <div class="table-header-row">
                        <h2 class="table-title">Riwayat Surat Yang Masuk</h2>
                    </div>

                    <div class="table-responsive">
                        <table class="data-table">
                            <thead>
                                <tr>
                                    <th>NO</th>
                                    <th>NIK</th>
                                    <th>NAMA PENGAJU</th>
                                    <th>JENIS SURAT</th>
                                    <th>DOKUMEN LAMPIRAN</th>
                                    <th>TANGGAL PENGAJUAN</th>
                                    <th>STATUS</th>
                                    <th>AKSI (VERIFIKASI)</th>
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
                                    <!-- Sebelumnya terbalik, sekarang NIK dan NAMA sudah disesuaikan -->
                                    <td><?= $row['nik_pengaju']; ?></td>
                                    <td><?= $row['nama_pengaju']; ?></td>
                                    <td><?= $row['nama_surat']; ?></td>
                                    
                                    <td>
                                        <?php
                                        // 3. Query tambahan untuk mengambil daftar dokumen berdasarkan ID pengajuan
                                        $pengajuan_id = $row['pengajuan_id'];
                                        $q_dokumen = mysqli_query($koneksi, "
                                            SELECT doc.path_file, req.nama_persyaratan 
                                            FROM dokumen_pengajuan doc 
                                            JOIN persyaratan_surat req ON doc.persyaratan_id = req.persyaratan_surat_id 
                                            WHERE doc.pengajuan_id = '$pengajuan_id'
                                        ");
                                                                                
                                        if (mysqli_num_rows($q_dokumen) > 0) {
                                            while ($dok = mysqli_fetch_assoc($q_dokumen)) {
                                                echo '<a href="'.$dok['path_file'].'" target="_blank" class="doc-link">📄 '.$dok['nama_persyaratan'].'</a>';
                                            }
                                        } else {
                                            echo '-';
                                        }
                                        ?>
                                    </td>
                                    
                                    <td><?= date('d/m/Y H:i', strtotime($row['tanggal_pengajuan'])); ?></td>
                                    
                                    <td><span class="badge-<?= $row['status']; ?>"><?= ucfirst($row['status']); ?></span></td>
                                    
                                    <td>
                                        <!-- Cek enum status db_desa: 'menunggu', 'disetujui', 'ditolak' -->
                                        <?php if ($row['status'] == 'menunggu'): ?>
                                        <form method="POST" style="display:inline; margin-top: 5px;">
                                            <input type="hidden" name="id_surat" value="<?= $row['pengajuan_id']; ?>">
                                            <!-- Catatan wajib diisi jika ditolak, bisa pakai JS validation tambahan -->
                                            <input type="text" name="catatan_admin" placeholder="Catatan (wajib jika ditolak)" style="padding: 5px; width: 130px; font-size: 11px; margin-bottom: 5px;">
                                            <br>
                                            <button type="submit" name="aksi" value="disetujui" class="btn-approve" style="background-color: #28a745; color: white; border: none; padding: 5px 10px; cursor: pointer;">Terima</button>
                                            <button type="submit" name="aksi" value="ditolak" class="btn-reject" style="background-color: #dc3545; color: white; border: none; padding: 5px 10px; cursor: pointer;">Tolak</button>
                                        </form>
                                        <?php else: ?>
                                            <i>Telah diverifikasi<br>pada <?= date('d/m/y', strtotime($row['tanggal_verifikasi'])); ?></i>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                                <?php 
                                    endwhile; 
                                else:
                                ?>
                                <tr><td colspan="8" style="text-align:center;">Belum ada surat masuk.</td></tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </main>
        </div>
    </div>
    <?php render_footer(); ?>
</body>
</html>