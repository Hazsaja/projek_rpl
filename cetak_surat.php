<?php
session_start();
include 'config/koneksi.php';

// Cek apakah user sudah login
if (!isset($_SESSION['login']) || !isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit;
}

if (!isset($_GET['pengajuan_id'])) {
    echo "Data surat tidak ditemukan!";
    exit;
}

$id_surat = mysqli_real_escape_string($koneksi, $_GET['pengajuan_id']);
$user_id = $_SESSION['user_id'];

// Query dinamis mengambil relasi data pemohon, jenis surat, dan template surat
// Catatan: Menggunakan penamaan primary key yang baru (pengajuan_id, jenis_surat_id)
if ($_SESSION['status'] == 'admin') {
    // Admin bisa cetak surat siapa saja yang sudah disetujui
    $query = "
        SELECT 
            p.pengajuan_id, p.status, p.tanggal_verifikasi, p.nomor_pengajuan,
            dp.nama, dp.nik, dp.tempat_lahir, dp.tanggal_lahir, dp.jenis_kelamin, 
            dp.agama, dp.status_kawin, dp.pekerjaan, dp.kewarganegaraan, dp.alamat,
            js.nama_surat, 
            ts.isi_template 
        FROM pengajuan p
        JOIN data_pemohon dp ON p.pengajuan_id = dp.pengajuan_id
        JOIN jenis_surat js ON p.jenis_surat_id = js.jenis_surat_id
        JOIN template_surat ts ON js.jenis_surat_id = ts.jenis_surat_id
        WHERE p.pengajuan_id = '$id_surat'
    ";
} else {
    // User biasa hanya bisa cetak surat miliknya sendiri yang disetujui
    $query = "
        SELECT 
            p.pengajuan_id, p.status, p.tanggal_verifikasi, p.nomor_pengajuan,
            dp.nama, dp.nik, dp.tempat_lahir, dp.tanggal_lahir, dp.jenis_kelamin, 
            dp.agama, dp.status_kawin, dp.pekerjaan, dp.kewarganegaraan, dp.alamat,
            js.nama_surat, 
            ts.isi_template 
        FROM pengajuan p
        JOIN data_pemohon dp ON p.pengajuan_id = dp.pengajuan_id
        JOIN jenis_surat js ON p.jenis_surat_id = js.jenis_surat_id
        JOIN template_surat ts ON js.jenis_surat_id = ts.jenis_surat_id
        WHERE p.pengajuan_id = '$id_surat' AND p.user_id = '$user_id' AND p.status = 'disetujui'
    ";
}

$result = mysqli_query($koneksi, $query);

if (mysqli_num_rows($result) === 0) {
    echo "<script>alert('Surat tidak tersedia, belum disetujui, atau Anda tidak memiliki akses.'); window.close();</script>";
    exit;
}

$data = mysqli_fetch_assoc($result);

// =========================================================================
// PROSES STRING REPLACEMENT (Menukar shortcode dengan data asli dari tabel)
// =========================================================================
$isi_surat = $data['isi_template'];

// Penyesuaian variabel yang ada di database dengan data diri pemohon
$isi_surat = str_replace('{{nama}}', '<strong>' . strtoupper($data['nama']) . '</strong>', $isi_surat);
$isi_surat = str_replace('{{nik}}', $data['nik'], $isi_surat);
$isi_surat = str_replace('{{tempat_lahir}}', $data['tempat_lahir'], $isi_surat);

// Format ulang tanggal lahir ke format Indonesia (misal: 17 Agustus 1945)
$bulan_indo = array(1 => 'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember');
$tgl_lahir_pecah = explode('-', $data['tanggal_lahir']);
$format_tgl_lahir = $tgl_lahir_pecah[2] . ' ' . $bulan_indo[(int)$tgl_lahir_pecah[1]] . ' ' . $tgl_lahir_pecah[0];

$isi_surat = str_replace('{{tanggal_lahir}}', $format_tgl_lahir, $isi_surat);
$isi_surat = str_replace('{{jenis_kelamin}}', $data['jenis_kelamin'], $isi_surat);
$isi_surat = str_replace('{{agama}}', $data['agama'], $isi_surat);
$isi_surat = str_replace('{{status_kawin}}', $data['status_kawin'], $isi_surat);
$isi_surat = str_replace('{{pekerjaan}}', $data['pekerjaan'], $isi_surat);
$isi_surat = str_replace('{{kewarganegaraan}}', $data['kewarganegaraan'], $isi_surat);
$isi_surat = str_replace('{{alamat}}', $data['alamat'], $isi_surat);

// Mengambil nomor pengajuan dari database atau membuat format default
$nomor_surat = $data['nomor_pengajuan'] ? $data['nomor_pengajuan'] : "474.4 / " . $data['pengajuan_id'] . " / DS-HJ / " . date('Y');

// Format tanggal verifikasi/approve
$tanggal_untuk_dicetak = !empty($data['tanggal_verifikasi']) ? $data['tanggal_verifikasi'] : date('Y-m-d');
$tgl_app_pecah = explode('-', date('Y-m-d', strtotime($tanggal_untuk_dicetak)));
$format_tgl_approve = $tgl_app_pecah[2] . ' ' . $bulan_indo[(int)$tgl_app_pecah[1]] . ' ' . $tgl_app_pecah[0];?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cetak Surat - <?= htmlspecialchars($data['nama_surat']); ?></title>
    <style>
        body {
            font-family: "Times New Roman", Times, serif;
            color: #000;
            background-color: #f0f0f0;
            /* Beri warna abu agar kertas putih lebih terlihat di layar */
            margin: 0;
            padding: 0;
        }

        .kertas-surat {
            width: 210mm;
            min-height: 297mm;
            padding: 20mm;
            margin: 10mm auto;
            background: white;
            box-sizing: border-box;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        .kop-surat {
            text-align: center;
            border-bottom: 3px solid #000;
            padding-bottom: 10px;
            margin-bottom: 20px;
        }

        .kop-surat h1,
        .kop-surat h2,
        .kop-surat p {
            margin: 2px 0;
        }

        .judul-surat {
            text-align: center;
            margin-bottom: 30px;
        }

        .judul-surat h3 {
            text-decoration: underline;
            margin-bottom: 5px;
            text-transform: uppercase;
        }

        .isi-surat {
            text-align: justify;
            line-height: 1.5;
            font-size: 12pt;
        }

        /* Style untuk tabel jika di dalam template nanti ada butuh style biodata */
        .tabel-biodata {
            margin: 20px 0 20px 30px;
        }

        .tabel-biodata td {
            padding: 5px;
            vertical-align: top;
        }

        .ttd-container {
            width: 100%;
            margin-top: 50px;
        }

        .ttd-box {
            float: right;
            text-align: center;
            width: 250px;
        }

        .ttd-box p {
            margin: 5px 0;
        }

        .nama-kades {
            margin-top: 70px;
            font-weight: bold;
            text-decoration: underline;
        }

        .action-container {
            text-align: center;
            margin: 20px auto;
            max-width: 800px;
        }

        .btn-back {
            background-color: #2A7B9B;
            color: white;
            padding: 10px 18px;
            border: none;
            border-radius: 4px;
            font-size: 14px;
            font-weight: bold;
            cursor: pointer;
            text-decoration: none;
            display: inline-block;
            transition: background-color 0.2s;
            font-family: "Segoe UI", Tahoma, Geneva, Verdana, sans-serif;
        }

        .btn-back:hover {
            background-color: #1f5c75;
        }

        .btn-reprint {
            background-color: #6c757d;
            /* Warna abu-abu (btn-print) */
            margin-left: 10px;
        }

        .btn-reprint:hover {
            background-color: #5a6268;
        }

        .modal-overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.6);
            z-index: 9999;
            display: none;
            /* Disembunyikan secara default */
            align-items: center;
            justify-content: center;
        }

        .modal-content {
            background-color: #fff;
            width: 80%;
            max-width: 900px;
            height: 90vh;
            /* Tinggi modal 90% dari layar */
            border-radius: 8px;
            display: flex;
            flex-direction: column;
            overflow: hidden;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.3);
            animation: fadeInModal 0.3s ease;
        }

        .modal-header {
            background-color: var(--color-cool-blue);
            color: white;
            padding: 15px 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .modal-header h3 {
            margin: 0;
            font-size: 16px;
            color: white;
        }

        .btn-close-modal {
            background: #dc3545;
            color: white;
            border: none;
            padding: 5px 12px;
            border-radius: 4px;
            cursor: pointer;
            font-weight: bold;
        }

        .btn-close-modal:hover {
            background: #c82333;
        }

        .modal-body {
            flex-grow: 1;
            width: 100%;
            padding: 0;
        }

        /* Iframe untuk memuat file cetak_surat.php */
        .modal-body iframe {
            width: 100%;
            height: 100%;
            border: none;
        }

        @keyframes fadeInModal {
            from {
                opacity: 0;
                transform: translateY(-20px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @media print {

            .no-print {
                display: none !important;
            }

            body {
                background: none;
            }

            .kertas-surat {
                margin: 0;
                padding: 0;
                width: auto;
                height: auto;
                box-shadow: none;
            }
        }
    </style>
</head>

<body onload="window.print()">

    <div class="kertas-surat">
        <div class="kop-surat">
            <h2>PEMERINTAH KABUPATEN HAZEL</h2>
            <h2>KECAMATAN JAYA</h2>
            <h1>DESA HAZELJAYA</h1>
            <p>Jl. Raya Hazeljaya No. 123, Kec. Jaya, Kab. Hazel, Kode Pos 12345</p>
        </div>

        <div class="judul-surat">
            <h3><?= htmlspecialchars($data['nama_surat']); ?></h3>
            <p>Nomor: <?= $nomor_surat; ?></p>
        </div>

        <div class="isi-surat">
            <?= $isi_surat; ?>
        </div>

        <div class="ttd-container">
            <div class="ttd-box">
                <p>Hazeljaya, <?= $format_tgl_approve; ?></p>
                <p>Kepala Desa Hazeljaya</p>
                <div class="nama-kades">Bapak Kepala Desa</div>
            </div>
            <div style="clear: both;"></div>
        </div>
    </div>

</body>

</html>