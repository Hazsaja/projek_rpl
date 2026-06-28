<?php
require_once __DIR__ . '/config/koneksi.php';
require_once __DIR__ . '/config/session.php';
require_once __DIR__ . '/templates/sidebar.php';
require_once __DIR__ . '/templates/navbar.php';
require_once __DIR__ . '/templates/footer.php';

require_login();
$limit = isset($_GET['limit']) ? (int)$_GET['limit'] : 5; // Menampilkan 5 data per halaman
$page  = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$offset = ($page - 1) * $limit;

$query_count = "SELECT COUNT(*) as total FROM pengajuan";
$result_count = mysqli_query($koneksi, $query_count);
$row_count = mysqli_fetch_assoc($result_count);
$total_data = $row_count['total'];
$total_pages = ceil($total_data / $limit);

$query =
    "SELECT 
        p.pengajuan_id, 
        p.tanggal_pengajuan, 
        p.tanggal_verifikasi, 
        p.status,
        p.file_surat_selesai,
        js.nama_surat, 
        dp.nama AS nama_pengaju, 
        dp.nik AS nik_pengaju 
    FROM pengajuan p
    JOIN jenis_surat js ON p.jenis_surat_id = js.jenis_surat_id
    JOIN data_pemohon dp ON p.pengajuan_id = dp.pengajuan_id
    WHERE p.status = 'disetujui'
    ORDER BY p.tanggal_pengajuan DESC
    LIMIT $limit OFFSET $offset
";
$result = mysqli_query($koneksi, $query);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Arsip</title>
    <link rel="stylesheet" href="menu_style.css">
</head>

<body class="dashboard-body">
    <div class="main-container">
        <?php render_sidebar('pengaturan', $_SESSION['status'] ?? 'user') ?>
        <div class="content-area">
            <?php render_navbar($_SESSION['nama'] ?? 'Warga') ?>

            <main class="page-content">
                <main class="page-content">
                    <div class="content-header">
                        <p class="breadcrumb">Home / <a href="admin.php" style="color: #888; text-decoration: none;">Admin Control</a> / Arsip Surat Disetujui</p>
                    </div>

                    <div class="table-card">
                        <div class="table-header-row">
                            <h2 class="table-title">Riwayat Surat Yang Telah Disetujui</h2>
                        </div>

                        <div class="table-controls">
                            <div class="show-entries">
                                <form method="GET" action=""> 
                                    Show
                                    <select name="limit" onchange="this.form.submit()">
                                        <option value="5" <?= ($limit == 5) ? 'selected' : ''; ?>>5</option>
                                        <option value="10" <?= ($limit == 10) ? 'selected' : ''; ?>>10</option>
                                        <option value="20" <?= ($limit == 20) ? 'selected' : ''; ?>>20</option>
                                    </select>
                                    entries
                                </form>
                            </div>
                            <div class="search-box">
                                Search: <input type="text" placeholder="belum ada fungsi">
                            </div>
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
                                    $no = $offset + 1;
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
                                                            echo '<a href="' . $dok['path_file'] . '" target="_blank" class="doc-link">📄 ' . $dok['nama_persyaratan'] . '</a>';
                                                        }
                                                    } else {
                                                        echo '-';
                                                    }
                                                    ?>
                                                </td>

                                                <td><?= date('d/m/Y H:i', strtotime($row['tanggal_pengajuan'])); ?></td>

                                                <td><span class="badge-<?= $row['status']; ?>"><?= ucfirst($row['status']); ?></span></td>

                                                <td>
                                                    <?php if (!empty($row['file_surat_selesai'])): ?>
                                                        <a href="<?= $row['file_surat_selesai']; ?>" target="_blank" class="btn-action btn-detail" style="text-decoration:none; display:inline-block; padding: 6px 12px; border-radius: 4px; background-color: #28a745;">
                                                            Unduh Surat Asli
                                                        </a>
                                                    <?php else: ?>
                                                        <button class="btn-action btn-print" onclick="bukaModalCetak('<?= $row['pengajuan_id']; ?>')">Cetak Draft</button>
                                                    <?php endif; ?>
                                                </td>
                                            </tr>
                                        <?php
                                        endwhile;
                                    else:
                                        ?>
                                        <tr>
                                            <td colspan="8" style="text-align:center;">Belum ada surat masuk.</td>
                                        </tr>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>

                        <div class="table-footer">
                            <div class="showing-info">
                                Showing <?= ($total_data == 0) ? 0 : $offset + 1; ?> to <?= min($offset + $limit, $total_data); ?> of <?= $total_data; ?> entries
                            </div>

                            <div class="pagination">
                                <?php if ($page > 1): ?>
                                    <a href="?page=<?= $page - 1; ?>&limit=<?= $limit; ?>" class="page-btn" style="text-decoration:none;">Previous</a>
                                <?php else: ?>
                                    <button class="page-btn disabled">Previous</button>
                                <?php endif; ?>

                                <?php if ($page < $total_pages): ?>
                                    <a href="?page=<?= $page + 1; ?>&limit=<?= $limit; ?>" class="page-btn" style="text-decoration:none;">Next</a>
                                <?php else: ?>
                                    <button class="page-btn disabled">Next</button>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </main>
            </main>
        </div>
    </div>
    <?php render_footer() ?>
</body>

</html>