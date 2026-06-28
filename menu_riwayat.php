<?php
require_once __DIR__ . '/config/koneksi.php';
require_once __DIR__ . '/config/session.php';
require_once __DIR__ . '/templates/sidebar.php';
require_once __DIR__ . '/templates/navbar.php';
require_once __DIR__ . '/templates/footer.php';

require_login();

// Menggunakan session 'id' atau 'user_id' tergantung pengaturan login Anda
$user_id = $_SESSION['user_id'] ?? $_SESSION['id'];

$limit = isset($_GET['limit']) ? (int)$_GET['limit'] : 5;
$page  = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$offset = ($page - 1) * $limit;

$allowed_sort_columns = [
    'tanggal' => 'p.tanggal_pengajuan',
    'jenis'   => 'js.nama_surat',
    'status'  => 'p.status',
    'nik'     => 'dp.nik',
    'catatan' => 'p.catatan_admin',
    'tgl_konfirmasi' => 'p.tanggal_verifikasi'
];

$sort_key = isset($_GET['sort']) && array_key_exists($_GET['sort'], $allowed_sort_columns) ? $_GET['sort'] : 'tanggal';
$order    = isset($_GET['order']) && strtoupper($_GET['order']) === 'ASC' ? 'ASC' : 'DESC';
$sort_column = $allowed_sort_columns[$sort_key];

function getSortLink($column_key, $current_sort, $current_order, $limit)
{
    $new_order = ($current_sort === $column_key && $current_order === 'ASC') ? 'DESC' : 'ASC';
    $icon = '&uarr;&darr;';
    if ($current_sort === $column_key) {
        $icon = $current_order === 'ASC' ? '&uarr;' : '&darr;';
    }
    return "<a href=\"?page=1&limit=$limit&sort=$column_key&order=$new_order\" style=\"text-decoration:none; color:inherit; margin-left:5px;\"><span>$icon</span></a>";
}

$query_count = "SELECT COUNT(*) AS total FROM pengajuan WHERE user_id = '$user_id'";
$result_count = mysqli_query($koneksi, $query_count);
$row_count = mysqli_fetch_assoc($result_count);
$total_data = $row_count['total'];

$total_pages = ceil($total_data / $limit);
$query =
    "SELECT 
    p.pengajuan_id, p.tanggal_pengajuan, p.tanggal_verifikasi, p.status, p.catatan_admin, p.file_surat_selesai,
    js.nama_surat, dp.nik
    FROM pengajuan p
    JOIN jenis_surat js ON p.jenis_surat_id = js.jenis_surat_id
    JOIN data_pemohon dp ON p.pengajuan_id = dp.pengajuan_id
    WHERE p.user_id = '$user_id'
    ORDER BY $sort_column $order
    LIMIT $limit OFFSET $offset
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
                    <p class="breadcrumb">
                        <a href="menu.php" style="color: #888; text-decoration: none;">Home</a> /
                        <strong style="color: #333;">Riwayat Surat</strong>
                    </p>
                </div>

                <div class="info-alert">
                    <div class="info-header">
                        <strong>Informasi!</strong>
                        <button class="close-btn">&times;</button>
                    </div>
                    <ul class="info-list">
                        <li>Tombol Cetak muncul ketika surat telah disetujui oleh Admin, harap untuk menunggu.</li>
                        <li>Saat tidak ada printer atau pencetak yang terhubung maka saat tekan Print, akan di unduh di perangkat anda</li>
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
                            <form method="GET" action="">
                                <input type="hidden" name="sort" value="<?= $sort_key; ?>">
                                <input type="hidden" name="order" value="<?= $order; ?>">

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
                                    <th>TANGGAL MASUK <?= getSortLink('tanggal', $sort_key, $order, $limit); ?></th>
                                    <th>JENIS SURAT <?= getSortLink('jenis', $sort_key, $order, $limit); ?></th>
                                    <th>KEPUTUSAN <?= getSortLink('status', $sort_key, $order, $limit); ?></th>
                                    <th>NIK <?= getSortLink('nik', $sort_key, $order, $limit); ?></th>
                                    <th>KETERANGAN DITOLAK <?= getSortLink('catatan', $sort_key, $order, $limit); ?></th>
                                    <th>TANGGAL KONFIRMASI <?= getSortLink('tgl_konfirmasi', $sort_key, $order, $limit); ?></th>
                                    <th>AKTIVITAS</th>
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
                                                    <?php if (!empty($row['file_surat_selesai'])): ?>
                                                        <a href="<?= $row['file_surat_selesai']; ?>" target="_blank" class="btn-action btn-detail" style="text-decoration:none; display:inline-block; padding: 6px 12px; border-radius: 4px; background-color: #28a745;">
                                                            Unduh Surat Asli
                                                        </a>
                                                    <?php else: ?>
                                                        <button class="btn-action btn-print" onclick="bukaModalCetak('<?= $row['pengajuan_id']; ?>')">Cetak Draft</button>
                                                    <?php endif; ?>

                                                <?php elseif ($row['status'] == 'ditolak'): ?>
                                                    <button class="btn-action" style="background:#ccc; cursor:not-allowed;" disabled>Ditolak</button>
                                                <?php else: ?>
                                                    <button class="btn-action btn-print" onclick="bukaModalCetak('<?= $row['pengajuan_id']; ?>')">Pratinjau</button>
                                                <?php endif; ?>
                                            </td>
                                        </tr>
                                    <?php
                                    endwhile;
                                else:
                                    ?>
                                    <tr>
                                        <td colspan="8" style="text-align:center;">Belum ada pengajuan surat.</td>
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
        </div>
    </div>

    <div id="modalCetak" class="modal-overlay">
        <div class="modal-box">
            <div class="modal-header">
                <h3>Pratinjau Cetak Surat</h3>
                <button class="btn-close-modal" onclick="tutupModalCetak()">Tutup</button>
            </div>
            <div class="modal-body">
                <iframe id="iframeCetak" src=""></iframe>
            </div>
        </div>
    </div>

    <script>
        function bukaModalCetak(idPengajuan) {
            document.getElementById('modalCetak').style.display = 'flex';

            var urlSurat = 'cetak_surat.php?pengajuan_id=' + idPengajuan;
            document.getElementById('iframeCetak').src = urlSurat;
        }

        function tutupModalCetak() {
            document.getElementById('modalCetak').style.display = 'none';

            document.getElementById('iframeCetak').src = '';
        }
    </script>
    <?php render_footer(); ?>
</body>

</html>