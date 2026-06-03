<?php
session_start();
include 'koneksi.php';

if (!isset($_SESSION['login'])) {
    header("Location: index.php");
    exit;
}

if (isset($_POST['kirim_surat'])) {
    $user_id = $_SESSION['id'];
    $nama_pengaju    = mysqli_real_escape_string($koneksi, $_POST['nama_pengaju']);
    $nik_pengaju     = $_SESSION['nik'];
    $tempat_lahir = mysqli_real_escape_string($koneksi, $_POST['tempat_lahir']);
    $tanggal_lahir = mysqli_real_escape_string($koneksi, $_POST['tanggal_lahir']);
    $jenis_kelamin      = mysqli_real_escape_string($koneksi, $_POST['jenis_kelamin']);
    $agama   = mysqli_real_escape_string($koneksi, $_POST['agama']);
    $status_kawin =  mysqli_real_escape_string($koneksi, $_POST['status_kawin']);
    $pekerjaan = mysqli_real_escape_string($koneksi, $_POST['pekerjaan']);
    $warga_negara  = mysqli_real_escape_string($koneksi, $_POST['warga_negara']);
    $keterangan_ditolak  = mysqli_real_escape_string($koneksi, $_POST['keterangan_ditolak']);
    $status_surat  = mysqli_real_escape_string($koneksi, $_POST['status_surat']);
    $tanggal_pengajuan  = mysqli_real_escape_string($koneksi, $_POST['tanggal_pengajuan']);
    $tanggal_approve  = mysqli_real_escape_string($koneksi, $_POST['tanggal_approve']);
    $alamat_rumah  = mysqli_real_escape_string($koneksi, $_POST['alamat_rumah']);
    

    $target_dir = "uploads/";
    if (!is_dir($target_dir)) { mkdir($target_dir, 0777, true); }
    
    function uploadFile($file_name, $target_dir) {
        if(isset($_FILES[$file_name]) && $_FILES[$file_name]['error'] == 0){
            $nama_file = time() . "_" . basename($_FILES[$file_name]["name"]);
            $target_file = $target_dir . $nama_file;
            if (move_uploaded_file($_FILES[$file_name]["tmp_name"], $target_file)) {
                return $nama_file;
            }
        }
        return null;
    }

    $file_pengantar = uploadFile('file_pengantar', $target_dir);
    $file_ktp_kk = uploadFile('file_ktp_kk', $target_dir);
    $file_pas_foto = uploadFile('file_pas_foto', $target_dir);
    $file_surat_pernyataan = uploadFile('file_surat_pernyataan', $target_dir);
    $file_bukti_tinggal = uploadFile('file_bukti_tinggal', $target_dir);

    if(!$file_pengantar || !$file_ktp_kk || !$file_pas_foto || !$file_surat_pernyataan || !$file_bukti_tinggal) {
        echo "<script>alert('Gagal! Pastikan semua file diunggah.');</script>";
    } else {
        $query = "INSERT INTO pengajuan_surat 
                  VALUES ('','$user_id', 'surat keterangan domisili','$nama_pengaju', '$nik_pengaju', '$tempat_lahir', '$tanggal_lahir','$jenis_kelamin', '$agama', '$status_kawin','$warga_negara','$pekerjaan', '$alamat_rumah', '$file_ktp_kk','$file_pengantar', '$file_pas_foto', '$file_surat_pernyataan', '$file_bukti_tinggal', 'pending','$keterangan_ditolak','$tanggal_pengajuan','$tanggal_approve')";

        if (mysqli_query($koneksi, $query)) {
            echo "<script>alert('Surat berhasil diajukan!'); window.location='menu_riwayat.php';</script>";
        } else {
            echo "Error: " . mysqli_error($koneksi);
        }
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
                            <label>Nama Lengkap</label>
                            <div class="input-wrapper">
                                <span class="input-icon">👤</span>
                                <input type="text" name="nama_pengaju" placeholder="Masukkan nama lengkap" required>
                            </div>
                        </div>
                        
                                <input type="number" name="nik_pengaju" value="<?php $_SESSION['nik'] ?>" hidden>

                        <div class="form-group">
                            <label>Alamat</label>
                            <div class="input-wrapper">
                                <span class="input-icon">📍</span>
                                <input type="text" name="alamat_rumah" placeholder="Masukkan Alamat">
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <label>Tempat Lahir</label>
                            <div class="input-wrapper">
                                <span class="input-icon">📍</span>
                                <input type="text" name="tempat_lahir" placeholder="Masukkan tempat lahir">
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
                                <select name="jenis_kelamin">
                                    <option value="" disabled selected>Pilih Jenis Kelamin</option>
                                    <option value="Laki-laki">Laki-laki</option>
                                    <option value="Perempuan">Perempuan</option>
                                </select>
                            </div>
                        </div>

                        <div class="form-group">
                            <label>Pekerjaan</label>
                            <div class="input-wrapper">
                                <span class="input-icon">💼</span>
                                <input type="text" name="pekerjaan" placeholder="Masukkan pekerjaan">
                            </div>
                        </div>

                        <div class="form-group">
                            <label>Agama</label>
                            <div class="input-wrapper">
                                <span class="input-icon">🤲</span>
                                <select name="agama">
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
                                <select name="status_kawin">
                                    <option value="" disabled selected>Pilih Status Perkawinan</option>
                                    <option value="Belum Kawin">Belum Kawin</option>
                                    <option value="Kawin">Kawin</option>
                                    <option value="Cerai Hidup">Cerai Hidup</option>
                                    <option value="Cerai Mati">Cerai Mati</option>
                                </select>
                            </div>
                        </div>

                        <div class="form-group">
                            <label>Warga Negara</label>
                            <div class="input-wrapper">
                                <span class="input-icon">🌐</span>
                                <select name = "warga_negara">
                                    <option value="WNI" selected>WNI (Warga Negara Indonesia)</option>
                                    <option value="WNA">WNA (Warga Negara Asing)</option>
                                </select>
                            </div>
                        </div>

                        <hr class="form-divider">

                        <div class="form-group">
                            <label>Upload Surat Pengantar RW/RT (Wajib Ditandatangani) (Max: 500 kb)</label>
                            <div class="file-upload-wrapper">
                                <input type="file" name="file_pengantar" id="surat-rt" class="file-input">
                            </div>
                        </div>

                        <div class="form-group">
                            <label>Upload KTP dan KK Asli/Fotokopi (Max: 500 kb)</label>
                            <div class="file-upload-wrapper">
                                <input type="file" name="file_ktp_kk" id="ktp-kk" class="file-input">
                            </div>
                        </div>

                        <div class="form-group">
                            <label>Upload Pas Foto 3x4 (Max: 500 kb)</label>
                            <div class="file-upload-wrapper">
                                <input type="file" name="file_pas_foto" id="pas-foto" class="file-input">
                            </div>
                        </div>

                        <div class="form-group">
                            <label>Upload Surat Pernyataan Domisili (Ditandatangani di atas materai Rp10.000) (Max: 500 kb)</label>
                            <div class="file-upload-wrapper">
                                <input type="file" name="file_surat_pernyataan" id="surat-pernyataan" class="file-input">
                            </div>
                        </div>

                        <div class="form-group">
                            <label>Upload Foto Bukti Tempat Tinggal (Kontrak/Perjanjian Sewa atau PBB) (Max: 500 kb)</label>
                            <div class="file-upload-wrapper">
                                <input type="file" name="file_bukti_tinggal" id="bukti-tinggal" class="file-input">
                            </div>
                        </div>
                                <input type="text" name="status_surat" value="pending" hidden>
                                <input type="date" name="tanggal_pengajuan" value="" hidden>
                                <input type="date" name="tanggal_approve" value="" hidden>
                                <input type="text" name="keterangan_ditolak" value="" hidden>
                        <button type="submit" name="kirim_surat" class="send-btn">Send</button>
                    </form>
                </div>
            </main> 
        </div> 
    </div> 
    <script src="script.js"></script>
</body>
</html>