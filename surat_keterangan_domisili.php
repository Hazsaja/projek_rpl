<?php
session_start();
include 'config/koneksi.php';

// Cek apakah user sudah login
if (!isset($_SESSION['login']) || !isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit;
}

if (isset($_POST['kirim_surat'])) {
    $user_id = $_SESSION['user_id'];
    $jenis_surat_id = 1; // ID 1 adalah Surat Keterangan Domisili (SKD) di tabel jenis_surat

    // Ambil data dari form untuk tabel data_pemohon
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
    if (!is_dir($target_dir)) { mkdir($target_dir, 0777, true); }

    // Mulai Transaksi Database (Agar jika salah satu gagal, semua dibatalkan)
    mysqli_begin_transaction($koneksi);

    try {
        // 1. Insert ke tabel `pengajuan`
        // Status dan tanggal_pengajuan otomatis terisi default dari database
        $query_pengajuan = "INSERT INTO pengajuan (user_id, jenis_surat_id, status) 
                            VALUES ('$user_id', '$jenis_surat_id', 'menunggu')";
        mysqli_query($koneksi, $query_pengajuan);
        
        // Ambil ID pengajuan yang baru saja dibuat
        $pengajuan_id = mysqli_insert_id($koneksi);

        // 2. Insert ke tabel `data_pemohon`
        $query_pemohon = "INSERT INTO data_pemohon 
                          (pengajuan_id, nama, nik, tempat_lahir, tanggal_lahir, jenis_kelamin, agama, status_kawin, pekerjaan, kewarganegaraan, alamat) 
                          VALUES 
                          ('$pengajuan_id', '$nama', '$nik', '$tempat_lahir', '$tanggal_lahir', '$jenis_kelamin', '$agama', '$status_kawin', '$pekerjaan', '$kewarganegaraan', '$alamat')";
        mysqli_query($koneksi, $query_pemohon);

        // 3. Proses Upload File & Insert ke `dokumen_pengajuan`
        // Pemetaan ID Persyaratan sesuai db_desa.sql untuk jenis_surat = 1
        $persyaratan_wajib = [
            'file_ktp' => 1,      // 1 = Fotokopi KTP
            'file_kk' => 2,       // 2 = Fotokopi KK
            'file_pas_foto' => 3  // 3 = Pas Foto
        ];

        foreach ($persyaratan_wajib as $input_name => $persyaratan_id) {
            if (isset($_FILES[$input_name]) && $_FILES[$input_name]['error'] == 0) {
                $nama_file_asli = basename($_FILES[$input_name]["name"]);
                $nama_file_unik = time() . "_" . $nama_file_asli;
                $target_file = $target_dir . $nama_file_unik;
                
                if (move_uploaded_file($_FILES[$input_name]["tmp_name"], $target_file)) {
                    // Insert file path ke tabel dokumen_pengajuan
                    $query_dokumen = "INSERT INTO dokumen_pengajuan (pengajuan_id, persyaratan_id, nama_file, path_file) 
                                      VALUES ('$pengajuan_id', '$persyaratan_id', '$nama_file_asli', '$target_file')";
                    mysqli_query($koneksi, $query_dokumen);
                } else {
                    throw new Exception("Gagal mengunggah file: " . $nama_file_asli);
                }
            } else {
                throw new Exception("Semua dokumen wajib diunggah.");
            }
        }

        // Jika semua langkah di atas berhasil, Commit (Simpan Permanen) ke database
        mysqli_commit($koneksi);
        echo "<script>alert('Surat Keterangan Domisili berhasil diajukan!'); window.location='menu_riwayat.php';</script>";

    } catch (Exception $e) {
        // Jika ada error (misal file gagal upload), batalkan insert tabel pengajuan dan data_pemohon
        mysqli_rollback($koneksi);
        $pesan_error = $e->getMessage();
        echo "<script>alert('Gagal! $pesan_error');</script>";
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Form Surat Keterangan Domisili</title>
    <link rel="stylesheet" href="menu_style.css">
</head>
<body class="dashboard-body">

    <div class="main-container">

        <nav class="sidebar">
            <!-- (Bagian Sidebar tidak ada perubahan) -->
            <div class="sidebar-header">
                <div class="logo-box">
                    <span class="logo-text">HazelJaya</span>
                    <button class="menu-toggle-btn">○</button>
                </div>
            </div>

            <ul class="nav-links">
                <li class="nav-item active-gradient"> 
                    <a href="menu.php" class="nav-link">
                        <i class="icon-create-document"></i>
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
                        <i class="icon-history"></i>
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
                        <img src="https://via.placeholder.com/40" alt="User Profile" class="profile-img">
                        <i class="icon-status-active"></i>
                    </div>
                </div>
            </header>

            <main class="page-content">
                <div class="content-header">
                    <p class="breadcrumb">
                        <a href="menu.php" style="color: #888; text-decoration: none;">Home</a> / 
                        <a href="menu.php" style="color: #888; text-decoration: none;">Pembuatan Surat</a> / 
                        <strong style="color: #333;">Form Surat Keterangan Domisili</strong>
                    </p>
                </div>

                <div class="form-card">
                    <h2 class="form-title">Form Surat Keterangan Domisili</h2>
                    
                    <form action="" method="post" enctype="multipart/form-data">
                        
                        <div class="form-group">
                            <label>NIK Pemohon</label>
                            <div class="input-wrapper">
                                <span class="input-icon">🆔</span>
                                <!-- Memperbaiki pemanggilan nilai NIK dari session agar tampil/terbaca dengan benar -->
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
                            <label>Alamat Lengkap Domisili</label>
                            <div class="input-wrapper">
                                <span class="input-icon">📍</span>
                                <input type="text" name="alamat_rumah" placeholder="Masukkan Alamat Lengkap" required>
                            </div>
                        </div>

                        <hr class="form-divider">
                        <p style="margin-bottom: 15px; color: #555; font-size: 0.9em;"><strong>Catatan:</strong> Dokumen di bawah ini wajib diunggah sesuai ketentuan persyaratan sistem.</p>

                        <!-- Dokumen Upload disesuaikan persis dengan tabel persyaratan_surat untuk jenis_surat_id = 1 -->
                        <div class="form-group">
                            <label>Upload Fotokopi KTP (Max: 2MB)</label>
                            <div class="file-upload-wrapper">
                                <input type="file" name="file_ktp" class="file-input" accept=".jpg,.jpeg,.png,.pdf" required>
                            </div>
                        </div>

                        <div class="form-group">
                            <label>Upload Fotokopi KK (Max: 2MB)</label>
                            <div class="file-upload-wrapper">
                                <input type="file" name="file_kk" class="file-input" accept=".jpg,.jpeg,.png,.pdf" required>
                            </div>
                        </div>

                        <div class="form-group">
                            <label>Upload Pas Foto (Max: 2MB)</label>
                            <div class="file-upload-wrapper">
                                <input type="file" name="file_pas_foto" class="file-input" accept=".jpg,.jpeg,.png" required>
                            </div>
                        </div>

                        <!-- Hapus input hidden yang tidak diperlukan karena tabel 'pengajuan' sudah menanganinya menggunakan DEFAULT otomatis -->

                        <button type="submit" name="kirim_surat" class="send-btn">Ajukan Surat</button>
                    </form>
                </div>
            </main> 
        </div> 
    </div> 
    <script src="script.js"></script>
</body>
</html>