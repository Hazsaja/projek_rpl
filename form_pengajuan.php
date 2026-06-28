<?php
require_once __DIR__ . '/config/koneksi.php';
require_once __DIR__ . '/config/session.php';
require_once __DIR__ . '/templates/sidebar.php';
require_once __DIR__ . '/templates/navbar.php';
require_once __DIR__ . '/templates/footer.php';

require_login();

// Tangkap ID jenis surat dari URL
if (!isset($_GET['jenis_surat_id'])) {
    echo "<script>alert('Pilih jenis surat terlebih dahulu!'); window.location='menu.php';</script>";
    exit;
}

$jenis_surat_id = mysqli_real_escape_string($koneksi, $_GET['jenis_surat_id']);
$user_id = $_SESSION['user_id'];

// Ambil nama surat untuk ditampilkan di Judul Form
$q_surat = mysqli_query($koneksi, "SELECT nama_surat FROM jenis_surat WHERE jenis_surat_id = '$jenis_surat_id'");
if (mysqli_num_rows($q_surat) == 0) {
    die("Jenis surat tidak valid!");
}
$data_surat = mysqli_fetch_assoc($q_surat);

if (isset($_POST['kirim_surat'])) {
    // Ambil data dari form
    $nama            = mysqli_real_escape_string($koneksi, $_POST['nama_pengaju']);
    $nik             = mysqli_real_escape_string($koneksi, $_POST['nik_pengaju']);
    $tempat_lahir    = mysqli_real_escape_string($koneksi, $_POST['tempat_lahir']);
    $tanggal_lahir   = mysqli_real_escape_string($koneksi, $_POST['tanggal_lahir']);
    $jenis_kelamin   = mysqli_real_escape_string($koneksi, $_POST['jenis_kelamin']);
    $agama           = mysqli_real_escape_string($koneksi, $_POST['agama']);
    $status_kawin    = mysqli_real_escape_string($koneksi, $_POST['status_kawin']);
    $pekerjaan       = mysqli_real_escape_string($koneksi, $_POST['pekerjaan']);
    $kewarganegaraan = mysqli_real_escape_string($koneksi, $_POST['warga_negara']);
    $alamat          = mysqli_real_escape_string($koneksi, $_POST['alamat_rumah']);

    $target_dir = "uploads/";
    if (!is_dir($target_dir)) {
        mkdir($target_dir, 0777, true);
    }

    // Mulai Transaksi
    mysqli_begin_transaction($koneksi);

    try {
        // 1. Insert ke tabel pengajuan
        mysqli_query($koneksi, "INSERT INTO pengajuan (user_id, jenis_surat_id, status) VALUES ('$user_id', '$jenis_surat_id', 'menunggu')");
        $pengajuan_id = mysqli_insert_id($koneksi);

        // 2. Insert ke tabel data_pemohon
        $query_pemohon = "INSERT INTO data_pemohon 
                          (pengajuan_id, nama, nik, tempat_lahir, tanggal_lahir, jenis_kelamin, agama, status_kawin, pekerjaan, kewarganegaraan, alamat) 
                          VALUES 
                          ('$pengajuan_id', '$nama', '$nik', '$tempat_lahir', '$tanggal_lahir', '$jenis_kelamin', '$agama', '$status_kawin', '$pekerjaan', '$kewarganegaraan', '$alamat')";
        mysqli_query($koneksi, $query_pemohon);

        // 3. Proses Upload File Dinamis berdasarkan persyaratan_surat
        foreach ($_FILES as $input_name => $file) {
            if (strpos($input_name, 'syarat_') === 0 && $file['error'] == 0) {
                // Ekstrak ID persyaratan dari name form
                $persyaratan_id = str_replace('syarat_', '', $input_name);

                $nama_file_asli = basename($file["name"]);
                $nama_file_unik = time() . "_" . $nama_file_asli;
                $target_file = $target_dir . $nama_file_unik;

                if (move_uploaded_file($file["tmp_name"], $target_file)) {
                    mysqli_query($koneksi, "INSERT INTO dokumen_pengajuan (pengajuan_id, persyaratan_id, nama_file, path_file) 
                                            VALUES ('$pengajuan_id', '$persyaratan_id', '$nama_file_asli', '$target_file')");
                } else {
                    throw new Exception("Gagal mengunggah file: " . $nama_file_asli);
                }
            }
        }

        // Commit jika sukses semua
        mysqli_commit($koneksi);
        $_SESSION['success_pengajuan'] = true;
        header('location: menu.php');
        exit;
    } catch (Exception $e) {
        mysqli_rollback($koneksi);
        $error = $e->getMessage();
    }
}
?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Form <?= $data_surat['nama_surat']; ?></title>
    <link rel="stylesheet" href="menu_style.css">
</head>

<body class="dashboard-body">
    <div class="main-container">
        <?php render_sidebar('pembuatan', $_SESSION['status'] ?? 'user'); ?>
        <div class="content-area">
            <?php render_navbar($_SESSION['nama'] ?? 'Warga'); ?>

            <main class="page-content">
                <div class="content-header">
                    <p class="breadcrumb">
                        <a href="menu.php" style="color: #888; text-decoration: none;">Home</a> /
                        <a href="menu.php" style="color: #888; text-decoration: none;">Pembuatan Surat</a> /
                        <strong style="color: #333;">Form <?= $data_surat['nama_surat']; ?></strong>
                    </p>
                </div>

                <div class="form-card">
                    <h2 class="form-title">Form <?= $data_surat['nama_surat']; ?></h2>

                    <form id="formPengajuan" action="" method="post" enctype="multipart/form-data">

                        <div class="form-group">
                            <label>NIK Pemohon</label>
                            <div class="input-wrapper">
                                <span class="input-icon">🆔</span>
                                <input type="text" name="nik_pengaju" value="<?= $_SESSION['nik'] ?? ''; ?>" readonly required style="background: #f1f1f1;">
                            </div>
                        </div>

                        <div class="form-group">
                            <label>Nama Lengkap</label>
                            <div class="input-wrapper">
                                <span class="input-icon">👤</span>
                                <input type="text" name="nama_pengaju" placeholder="Masukkan nama lengkap" required>
                            </div>
                        </div>

                        <div class="form-group">
                            <label>Tempat Lahir</label>
                            <div class="input-wrapper">
                                <span class="input-icon">📍</span>
                                <input type="text" name="tempat_lahir" placeholder="Masukkan tempat lahir" required>
                            </div>
                        </div>

                        <div class="form-group">
                            <label>Tanggal Lahir</label>
                            <div class="input-wrapper">
                                <span class="input-icon">📅</span>
                                <input type="date" name="tanggal_lahir" required>
                            </div>
                        </div>

                        <div class="form-group">
                            <label>Jenis Kelamin</label>
                            <div class="input-wrapper">
                                <span class="input-icon">⚥</span>
                                <select name="jenis_kelamin" required>
                                    <option value="" disabled selected>Pilih Jenis Kelamin</option>
                                    <option value="Laki-laki">Laki-laki</option>
                                    <option value="Perempuan">Perempuan</option>
                                </select>
                            </div>
                        </div>

                        <div class="form-group">
                            <label>Agama</label>
                            <div class="input-wrapper">
                                <span class="input-icon">🤲</span>
                                <select name="agama" required>
                                    <option value="" disabled selected>Pilih Agama</option>
                                    <option value="Islam">Islam</option>
                                    <option value="Kristen Protestan">Kristen Protestan</option>
                                    <option value="Katolik">Katolik</option>
                                    <option value="Hindu">Hindu</option>
                                    <option value="Buddha">Buddha</option>
                                    <option value="Konghucu">Konghucu</option>
                                </select>
                            </div>
                        </div>

                        <div class="form-group">
                            <label>Status Perkawinan</label>
                            <div class="input-wrapper">
                                <span class="input-icon">💍</span>
                                <select name="status_kawin" required>
                                    <option value="" disabled selected>Pilih Status Perkawinan</option>
                                    <option value="Belum Kawin">Belum Kawin</option>
                                    <option value="Kawin">Kawin</option>
                                    <option value="Cerai Hidup">Cerai Hidup</option>
                                    <option value="Cerai Mati">Cerai Mati</option>
                                </select>
                            </div>
                        </div>

                        <div class="form-group">
                            <label>Pekerjaan</label>
                            <div class="input-wrapper">
                                <span class="input-icon">💼</span>
                                <input type="text" name="pekerjaan" placeholder="Masukkan pekerjaan" required>
                            </div>
                        </div>

                        <div class="form-group">
                            <label>Warga Negara</label>
                            <div class="input-wrapper">
                                <span class="input-icon">🌐</span>
                                <select name="warga_negara" required>
                                    <option value="WNI" selected>WNI (Warga Negara Indonesia)</option>
                                    <option value="WNA">WNA (Warga Negara Asing)</option>
                                </select>
                            </div>
                        </div>

                        <div class="form-group">
                            <label>Alamat Lengkap</label>
                            <div class="input-wrapper">
                                <span class="input-icon">📍</span>
                                <input type="text" name="alamat_rumah" placeholder="Masukkan Alamat Lengkap" required>
                            </div>
                        </div>

                        <hr class="form-divider">
                        <p style="margin-bottom: 15px; color: #555; font-size: 0.9em;"><strong>Catatan:</strong> Dokumen di bawah ini wajib diunggah sesuai ketentuan persyaratan sistem.</p>

                        <?php
                        $q_syarat = mysqli_query($koneksi, "SELECT persyaratan_surat_id, nama_persyaratan FROM persyaratan_surat WHERE jenis_surat_id = '$jenis_surat_id'");

                        while ($syarat = mysqli_fetch_assoc($q_syarat)) {
                            $input_name = "syarat_" . $syarat['persyaratan_surat_id'];
                        ?>
                            <div class="form-group">
                                <label>Upload <?= $syarat['nama_persyaratan']; ?> (Max: 2MB)</label>
                                <div class="file-upload-wrapper">
                                    <input type="file" name="<?= $input_name; ?>" class="file-input" accept=".jpg,.jpeg,.png,.pdf" required>
                                </div>
                            </div>
                        <?php } ?>

                        <input type="hidden" name="kirim_surat" value="1">
                        <button type="button" id="btnAjukan" class="send-btn">Ajukan Surat</button>
                    </form>
                </div>
            </main>
        </div>
    </div>
    <div id="confirmModal" class="custom-modal-overlay" style="display: none;">
        <div class="custom-modal-box animate-pop">
            <div class="modal-icon confirm">❓</div>
            <h3>Konfirmasi Pengajuan</h3>
            <p>Apakah Anda yakin data yang diisi sudah benar dan ingin mengajukan surat ini?</p>
            <div class="modal-btn-group">
                <button type="button" id="confirmNo" class="modal-btn btn-secondary">Batal</button>
                <button type="button" id="confirmYes" class="modal-btn btn-primary">Ya, Kirim</button>
            </div>
        </div>
    </div>
    <?php if (isset($error) && !empty($error)): ?>
        <div id="errorModal" class="custom-modal-overlay">
            <div class="custom-modal-box animate-pop">
                <div class="modal-icon error">❌</div>
                <h3>Pengajuan Gagal!</h3>
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
    <script src="script.js"></script>
    <script>
        const form = document.getElementById('formPengajuan');
        const btnAjukan = document.getElementById('btnAjukan');
        const confirmModal = document.getElementById('confirmModal');
        const confirmYes = document.getElementById('confirmYes');
        const confirmNo = document.getElementById('confirmNo');

        btnAjukan.addEventListener('click', function() {
            if (form.checkValidity()) {
                confirmModal.style.display = 'flex';
            } else {
                form.reportValidity();
            }
        });

        confirmNo.addEventListener('click', function() {
            confirmModal.style.display = 'none';
        });

        confirmYes.addEventListener('click', function() {
            form.submit();
        });
    </script>
</body>

</html>