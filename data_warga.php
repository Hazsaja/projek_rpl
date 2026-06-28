<?php
require_once __DIR__ . '/config/koneksi.php';
require_once __DIR__ . '/config/session.php';
require_once __DIR__ . '/templates/sidebar.php';
require_once __DIR__ . '/templates/navbar.php';
require_once __DIR__ . '/templates/footer.php';

require_admin();

if (isset($_POST['edit_warga'])) {
    $user_id = mysqli_real_escape_string($koneksi, $_POST['user_id']);
    $nama    = mysqli_real_escape_string($koneksi, $_POST['nama']);
    $nik     = mysqli_real_escape_string($koneksi, $_POST['nik']);
    $email   = mysqli_real_escape_string($koneksi, $_POST['email']);
    $status  = mysqli_real_escape_string($koneksi, $_POST['status']);

    $query_update = "UPDATE users SET 
                        nama = '$nama', 
                        nik = '$nik', 
                        email = '$email', 
                        status = '$status' 
                     WHERE user_id = '$user_id'";

    $result_update = mysqli_query($koneksi, $query_update);

    if ($result_update) {
        header("Location: data_warga.php?msg=sukses_edit");
        exit();
    } else {
        header("Location: data_warga.php?msg=gagal_edit");
        exit();
    }
}

if (isset($_POST['hapus_warga'])) {
    $user_id = mysqli_real_escape_string($koneksi, $_POST['user_id']);

    $query_delete = "DELETE FROM users WHERE user_id = '$user_id'";

    $result_delete = mysqli_query($koneksi, $query_delete);

    if ($result_delete) {
        header("Location: data_warga.php?msg=sukses_hapus");
        exit();
    } else {
        header("Location: data_warga.php?msg=gagal_hapus");
        exit();
    }
}

$limit = isset($_GET['limit']) ? (int)$_GET['limit'] : 5;
$page  = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$offset = ($page - 1) * $limit;

$query_count = "SELECT COUNT(*) as total FROM pengajuan";
$result_count = mysqli_query($koneksi, $query_count);
$row_count = mysqli_fetch_assoc($result_count);
$total_data = $row_count['total'];
$total_pages = ceil($total_data / $limit);

$query =
    "SELECT 
        user_id,
        nama,
        nik,
        email,
        status,
        created_at
    FROM users
    WHERE 1
    ORDER BY created_at DESC
    LIMIT $limit OFFSET $offset
";
$result = mysqli_query($koneksi, $query);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Data Warga</title>
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
                        <p class="breadcrumb">Home / <a href="admin.php" style="color: #888; text-decoration: none;">Admin Control</a> / Data Warga</p>
                    </div>

                    <div class="table-card">
                        <div class="table-header-row">
                            <h2 class="table-title">Data Warga</h2>
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
                                        <th>NAMA</th>
                                        <th>NIK</th>
                                        <th>EMAIL</th>
                                        <th>STATUS</th>
                                        <th>AKSI</th>
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
                                                <td><?= $row['nama']; ?></td>
                                                <td><?= $row['nik']; ?></td>
                                                <td><?= $row['email']; ?></td>
                                                <td><span class="badge-<?= $row['status']; ?>"><?= ucfirst($row['status']); ?></span></td>
                                                <td>
                                                    <div style="display: flex;">
                                                        <button class="btn-edit" onclick="openEditModal(<?= $row['user_id'] ?>, '<?= htmlspecialchars($row['nama']) ?>', '<?= htmlspecialchars($row['nik']) ?>', '<?= htmlspecialchars($row['email']) ?>', '<?= htmlspecialchars($row['status']) ?>')">Edit</button>

                                                        <button class="btn-delete" onclick="openDeleteModal(<?= $row['user_id'] ?>)">Hapus</button>
                                                    </div>
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
    <div id="editModal" class="custom-modal-overlay" style="display: none;">
        <div class="custom-modal-box animate-pop" style="width: 420px;">
            <div class="modal-header">
                <h3 style="margin: 0; font-size: 18px; color: #555;">Edit Data Warga</h3>
                <button class="close-btn" type="button" onclick="closeEditModal()">×</button>
            </div>

            <form id="editForm" method="POST" action="">
                <input type="hidden" name="user_id" id="edit_user_id">

                <div class="form-group" style="text-align: left;">
                    <label>Nama Lengkap</label>
                    <div class="input-wrapper">
                        <svg class="input-icon" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="top: 12px;">
                            <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path>
                            <circle cx="12" cy="7" r="4"></circle>
                        </svg>
                        <input type="text" name="nama" id="edit_nama" required>
                    </div>
                </div>

                <div class="form-group" style="text-align: left;">
                    <label>Nomor Induk Kependudukan (NIK)</label>
                    <div class="input-wrapper">
                        <svg class="input-icon" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="top: 12px;">
                            <rect x="3" y="4" width="18" height="16" rx="2" ry="2"></rect>
                            <circle cx="9" cy="10" r="2"></circle>
                            <line x1="15" y1="8" x2="17" y2="8"></line>
                            <line x1="15" y1="12" x2="17" y2="12"></line>
                            <line x1="7" y1="16" x2="17" y2="16"></line>
                        </svg>
                        <input type="text" name="nik" id="edit_nik" required>
                    </div>
                </div>

                <div class="form-group" style="text-align: left;">
                    <label>Alamat Email</label>
                    <div class="input-wrapper">
                        <svg class="input-icon" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="top: 12px;">
                            <path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"></path>
                            <polyline points="22,6 12,13 2,6"></polyline>
                        </svg>
                        <input type="text" inputmode="email" name="email" id="edit_email" required>
                    </div>
                </div>

                <div class="form-group" style="text-align: left;">
                    <label>Status Akun</label>
                    <div class="input-wrapper">
                        <svg class="input-icon" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="top: 12px;">
                            <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"></path>
                        </svg>
                        <select name="status" id="edit_status" required>
                            <option value="user">User (Warga)</option>
                            <option value="admin">Admin</option>
                        </select>
                    </div>
                </div>

                <div class="modal-footer modal-btn-group" style="margin-top: 25px;">
                    <button type="button" class="modal-btn btn-secondary" onclick="closeEditModal()">Batal</button>
                    <button type="submit" name="edit_warga" class="modal-btn btn-primary">Simpan Perubahan</button>
                </div>
            </form>
        </div>
    </div>

    <div id="deleteModal" class="custom-modal-overlay" style="display: none;">
        <div class="custom-modal-box animate-pop">
            <div class="modal-icon error">
                <svg width="60" height="60" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <circle cx="12" cy="12" r="10"></circle>
                    <line x1="12" y1="8" x2="12" y2="12"></line>
                    <line x1="12" y1="16" x2="12.01" y2="16"></line>
                </svg>
            </div>

            <h3>Konfirmasi Hapus</h3>
            <p>Apakah Anda yakin ingin menghapus data warga ini? Semua data pengajuan surat terkait juga akan ikut terhapus.</p>

            <form id="deleteForm" method="POST" action="">
                <input type="hidden" name="user_id" id="delete_user_id">
                <div class="modal-btn-group">
                    <button type="button" class="modal-btn btn-secondary" onclick="closeDeleteModal()">Batal</button>
                    <button type="submit" name="hapus_warga" class="modal-btn btn-danger">Ya, Hapus Data</button>
                </div>
            </form>
        </div>
    </div>
    <?php render_footer() ?>
    <script>
        function openEditModal(id, nama, nik, email, status) {
            document.getElementById('edit_user_id').value = id;
            document.getElementById('edit_nama').value = nama;
            document.getElementById('edit_nik').value = nik;
            document.getElementById('edit_email').value = email;
            document.getElementById('edit_status').value = status;

            document.getElementById('editModal').style.display = 'flex';
        }

        function closeEditModal() {
            document.getElementById('editModal').style.display = 'none';
        }

        function openDeleteModal(id) {
            document.getElementById('delete_user_id').value = id;

            document.getElementById('deleteModal').style.display = 'flex';
        }

        function closeDeleteModal() {
            document.getElementById('deleteModal').style.display = 'none';
        }
    </script>
</body>

</html>