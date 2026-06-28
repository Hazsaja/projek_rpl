<?php
require_once __DIR__ . '/config/koneksi.php';
require_once __DIR__ . '/config/session.php';
require_once __DIR__ . '/templates/sidebar.php';
require_once __DIR__ . '/templates/navbar.php';
require_once __DIR__ . '/templates/footer.php';
require_once __DIR__ . '/phpmailer/src/Exception.php';
require_once __DIR__ . '/phpmailer/src/PHPMailer.php';
require_once __DIR__ . '/phpmailer/src/SMTP.php';

require_once __DIR__ . '/config/mailer_config.php';

require_admin();

// 1. Logika Update Status Surat
if (isset($_POST['aksi'])) {
    $id = mysqli_real_escape_string($koneksi, $_POST['id_surat']);
    $status = mysqli_real_escape_string($koneksi, $_POST['aksi']);
    $catatan_admin = mysqli_real_escape_string($koneksi, $_POST['catatan_admin']);
    $tanggal_sekarang = date('Y-m-d H:i:s');

    $path_surat_selesai = "";

    mysqli_begin_transaction($koneksi);
    try {
        if ($status === 'disetujui' && isset($_FILES['surat_selesai']) && $_FILES['surat_selesai']['error'] === 0) {
            $nama_file = time() . '_' . basename($_FILES['surat_selesai']['name']);
            $target_dir = "uploads/surat_selesai/";

            if (!is_dir($target_dir)) {
                mkdir($target_dir, 0777, true);
            }

            $target_file = $target_dir . $nama_file;

            if (move_uploaded_file($_FILES['surat_selesai']['tmp_name'], $target_file)) {
                $path_surat_selesai = $target_file;
            }
        }

        if ($path_surat_selesai !== "") {
            $query_update = "UPDATE pengajuan SET
                         status = '$status',
                         catatan_admin = '$catatan_admin',
                         tanggal_verifikasi = '$tanggal_sekarang',
                         file_surat_selesai = '$path_surat_selesai'
                         WHERE pengajuan_id = '$id'";
        } else {
            $query_update = "UPDATE pengajuan SET
                         status = '$status',
                         catatan_admin = '$catatan_admin',
                         tanggal_verifikasi = '$tanggal_sekarang'
                         WHERE pengajuan_id = '$id'";
        }
        mysqli_query($koneksi, $query_update);
        mysqli_commit($koneksi);
        if ($status === 'disetujui') {
            $query_user = "SELECT u.nama, u.email FROM pengajuan p JOIN users u ON p.user_id = u.user_id WHERE p.pengajuan_id = '$id'";
            $result_user = mysqli_query($koneksi, $query_user);
            
            if ($result_user && mysqli_num_rows($result_user) > 0) {
                $data_user = mysqli_fetch_assoc($result_user);
                $nama_pemohon = $data_user['nama'];
                $email_pemohon = $data_user['email'];

                $mail = new PHPMailer\PHPMailer\PHPMailer(true);

                try {
                    $mail->isSMTP();
                    $mail->Host       = SMTP_HOST;
                    $mail->SMTPAuth   = true;
                    $mail->Username   = SMTP_USER;
                    $mail->Password   = SMTP_PASS;
                    $mail->SMTPSecure = PHPMailer\PHPMailer\PHPMailer::ENCRYPTION_SMTPS; 
                    $mail->Port       = SMTP_PORT;

                    $mail->setFrom(SENDER_EMAIL, SENDER_NAME);
                    $mail->addAddress($email_pemohon, $nama_pemohon);

                    $mail->isHTML(true);
                    $mail->Subject = 'Pemberitahuan: Pengajuan Surat Anda Disetujui';
                    
                    $catatan_tampil = !empty($catatan_admin) ? $catatan_admin : "<em>Tidak ada catatan tambahan</em>";
                    $mail->Body = "
                        <div style='font-family: Arial, sans-serif; max-width: 600px; margin: 0 auto; padding: 20px; border: 1px solid #ddd; border-radius: 8px;'>
                            <h2 style='color: #2A7B9B;'>Halo, $nama_pemohon</h2>
                            <p>Kami informasikan bahwa pengajuan surat Anda telah <strong>DISETUJUI</strong> oleh Admin.</p>
                            <div style='background-color: #f9f9f9; padding: 15px; border-left: 4px solid #50BC88; margin: 20px 0;'>
                                <strong>Catatan Admin:</strong><br>
                                $catatan_tampil
                            </div>
                            <p>Silakan login ke dalam Sistem Web Desa untuk mengunduh dokumen surat yang telah selesai diproses.</p>
                            <br>
                            <hr style='border: none; border-top: 1px solid #eee;'>
                            <p style='color: #777; font-size: 12px;'>Pesan ini dikirim secara otomatis. Mohon tidak membalas ke alamat email ini.</p>
                            <p>Salam hangat,<br><strong>Admin Desa</strong></p>
                        </div>
                    ";

                    $mail->AltBody = "Halo $nama_pemohon,\n\nPengajuan surat Anda telah DISETUJUI.\nCatatan Admin: $catatan_tampil\n\nSilakan login ke sistem untuk mengunduh surat Anda.\n\nSalam,\nAdmin Desa";

                    $mail->send();
                } catch (Throwable $e) {
                    echo"". $e->getMessage();
                }
            }
        }
        $_SESSION['success_verifikasi'] = true;
        header('admin_surat_masuk.php');
    } catch (Throwable $e) {
        mysqli_rollback($koneksi);
        $error = $e->getMessage();
    }
}

$limit = isset($_GET['limit']) ? (int)$_GET['limit'] : 5; // Menampilkan 5 data per halaman
$page  = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$offset = ($page - 1) * $limit;

$query_count = "SELECT COUNT(*) as total FROM pengajuan";
$result_count = mysqli_query($koneksi, $query_count);
$row_count = mysqli_fetch_assoc($result_count);
$total_data = $row_count['total'];
$total_pages = ceil($total_data / $limit);

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
    LIMIT $limit OFFSET $offset
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

        .doc-link:hover {
            text-decoration: underline;
        }

        .modal {
            display: none;
            position: fixed;
            z-index: 9999;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.6);
            overflow: auto;
        }
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
                                                <!-- Cek enum status db_desa: 'menunggu', 'disetujui', 'ditolak' -->
                                                <?php if ($row['status'] == 'menunggu'): ?>
                                                    <button type="button" class="btn-action btn-terima" onclick="bukaModalProses('<?= $row['pengajuan_id']; ?>')">
                                                        Proses Surat
                                                    </button>
                                                <?php else: ?>
                                                    <i>Telah diverifikasi pada <br>(<?= date('d/m/y', strtotime($row['tanggal_verifikasi'])); ?>)</i>
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
        </div>
    </div>
    <div id="modalProsesSurat" class="custom-modal-overlay" style="display: none;">
        <div class="modal-content" style="max-width: 500px; margin: 10% auto; padding: 20px; background:#fff; border-radius:8px; position:relative; box-shadow: 0 4px 15px rgba(0,0,0,0.2);">
            <span class="close-btn" onclick="tutupModal()" style="position:absolute; right:15px; top:10px; font-size:24px; cursor:pointer; color:#aaa;">&times;</span>
            <h3 style="margin-top: 0; margin-bottom: 20px; color: #333;">Tindak Lanjut Surat</h3>

            <div style="margin-bottom: 20px; padding: 15px; background: #f8f9fa; border-radius: 6px; border: 1px solid #e9ecef;">
                <label style="font-weight: bold; display: block; margin-bottom: 5px; font-size: 14px;">1. Unduh / Cetak Draft Surat:</label>
                <p style="font-size: 11px; color: #6c757d; margin-top: 0; margin-bottom: 12px;">
                    Klik tombol di bawah untuk mencetak fisik draf surat langsung dari sini tanpa membuka tab baru.
                </p>
                <button type="button" class="btn-action" style="background-color: #17a2b8; color: white; width: 100%; padding: 10px; font-weight: bold; border:none; border-radius:4px; cursor:pointer;" onclick="unduhDraftSeketika()">
                    📥 Cetak / Unduh Draft Surat
                </button>
            </div>

            <iframe id="iframeDownloadSeketika" style="display: none;"></iframe>

            <form id="formProsesSurat" method="POST" enctype="multipart/form-data">
                <input type="hidden" name="id_surat" id="modal_id_surat">

                <div class="form-group" style="margin-bottom: 15px;">
                    <label for="file_surat_ttd" style="font-weight:bold; display:block; margin-bottom:5px; font-size: 14px;">2. Upload Surat Bertanda Tangan:</label>
                    <input type="file" name="surat_selesai" id="file_surat_ttd" accept=".pdf,.jpg,.jpeg,.png" style="width:100%; padding:8px; border:1px solid #ccc; border-radius:4px;">
                </div>

                <div class="form-group" style="margin-bottom: 15px;">
                    <label for="catatan" style="font-weight:bold; display:block; margin-bottom:5px; font-size: 14px;">Catatan Admin (Wajib jika ditolak):</label>
                    <input type="text" name="catatan_admin" id="catatan" placeholder="Masukkan catatan..." style="width:100%; padding:8px; border:1px solid #ccc; border-radius:4px;">
                </div>

                <div style="text-align: right; display: flex; gap: 10px; justify-content: flex-end; margin-top: 25px;">
                    <button type="submit" name="aksi" value="ditolak" class="btn-reject" onclick="return validasiTolak()" style="background-color: #dc3545; color: white; border: none; padding: 10px 20px; border-radius: 4px; cursor: pointer; font-weight:600;">Tolak</button>
                    <button type="submit" name="aksi" value="disetujui" class="btn-approve" style="background-color: #28a745; color: white; border: none; padding: 10px 20px; border-radius: 4px; cursor: pointer; font-weight:600;">Setujui & Unggah</button>
                </div>
            </form>
        </div>
    </div>
    <?php if (isset($_SESSION['success_verifikasi'])): ?>
        <div id="successModal" class="custom-modal-overlay">
            <div class="custom-modal-box animate-pop">
                <div class="modal-icon success">✓</div>
                <h3>Verifikasi Berhasil!</h3>
                <p>Surat telah berhasil diunggah dan sudah bisa diunduh pengguna.</p>
                <button id="closeSuccessModal" class="modal-btn btn-primary" style="width: 100%;">Selesai</button>
            </div>
        </div>

        <?php unset($_SESSION['success_verifikasi']); ?>

        <script>
            document.getElementById('closeSuccessModal').addEventListener('click', function() {
                document.getElementById('successModal').style.display = 'none';
            });
        </script>
    <?php endif; ?>
    <?php if (isset($error) && !empty($error)): ?>
        <div id="errorModal" class="custom-modal-overlay">
            <div class="custom-modal-box animate-pop">
                <div class="modal-icon error">❌</div>
                <h3>Verifikasi Gagal!</h3>
                <p><?= htmlspecialchars($error); ?></p>
                <p style="font-size: 0.85em; color: #888; margin-top:-15px;">Silakan periksa kembali data Anda atau coba beberapa saat lagi.</p>
                <button id="closeErrorModal" class="modal-btn btn-danger" style="width: 100%;">Tutup</button>
            </div>
        </div>

        <script>
            // Logika untuk menutup modal error
            document.getElementById('closeErrorModal').addEventListener('click', function() {
                document.getElementById('errorModal').style.display = 'none';
            });
        </script>
    <?php endif; ?>
    <?php render_footer(); ?>
    <script>
        // Variabel global untuk menyimpan ID Surat yang sedang diproses di modal
        let idSuratAktif = null;

        function bukaModalProses(idSurat) {
            const modal = document.getElementById('modalProsesSurat');
            modal.style.display = 'block';

            // Simpan ID Surat ke variabel dan ke input hidden form
            idSuratAktif = idSurat;
            document.getElementById('modal_id_surat').value = idSurat;

            // Reset iframe biar bersih setiap kali modal baru dibuka
            document.getElementById('iframeDownloadSeketika').src = '';
        }

        function tutupModal() {
            document.getElementById('modalProsesSurat').style.display = 'none';
            document.getElementById('formProsesSurat').reset();
            document.getElementById('iframeDownloadSeketika').src = '';
            idSuratAktif = null;
        }

        // FUNGSI UTAMA: Menembak cetak_surat.php ke dalam iframe tersembunyi
        function unduhDraftSeketika() {
            if (!idSuratAktif) {
                alert("ID Surat tidak valid atau tidak ditemukan.");
                return;
            }

            // Masukkan URL ke iframe tersembunyi.
            // Karena cetak_surat.php otomatis menjalankan window.print() saat dimuat,
            // dialog Print browser Anda akan muncul seketika di halaman ini tanpa pindah tab!
            const iframe = document.getElementById('iframeDownloadSeketika');
            iframe.src = 'cetak_surat.php?pengajuan_id=' + idSuratAktif;
        }

        window.onclick = function(event) {
            const modal = document.getElementById('modalProsesSurat');
            if (event.target == modal) {
                tutupModal();
            }
        }

        function validasiTolak() {
            const catatan = document.getElementById('catatan').value;
            if (catatan.trim() === '') {
                alert("Catatan admin wajib diisi jika Anda menolak surat!");
                return false;
            }
            return true;
        }
    </script>
</body>

</html>