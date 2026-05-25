<?php
session_start();
include 'koneksi.php';

if (!isset($_SESSION['login'])) {
    header("Location: index.php");
    exit;
}

if (!isset($_GET['id'])) {
    echo "Data surat tidak ditemukan!";
    exit;
}

$id_surat = $_GET['id'];
$user_id = $_SESSION['id'];


if ($_SESSION['status'] == 'admin') {
    $query = "SELECT * FROM pengajuan_surat WHERE id = '$id_surat' AND status_surat = 'disetujui'";
} else {
    $query = "SELECT * FROM pengajuan_surat WHERE id = '$id_surat' AND user_id = '$user_id' AND status_surat = 'disetujui'";
}

$result = mysqli_query($koneksi, $query);

if (mysqli_num_rows($result) === 0) {
    echo "Surat tidak tersedia atau belum disetujui.";
    exit;
}

$data = mysqli_fetch_assoc($result);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cetak Surat - <?= $data['jenis_surat']; ?></title>
    <style>
       
        body {
            font-family: "Times New Roman", Times, serif;
            color: #000;
            background-color: #fff;
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
        }
        .kop-surat {
            text-align: center;
            border-bottom: 3px solid #000;
            padding-bottom: 10px;
            margin-bottom: 20px;
        }
        .kop-surat h1, .kop-surat h2, .kop-surat h3, .kop-surat p {
            margin: 2px 0;
        }
        .judul-surat {
            text-align: center;
            margin-bottom: 30px;
        }
        .judul-surat h3 {
            text-decoration: underline;
            margin-bottom: 5px;
        }
        .isi-surat {
            text-align: justify;
            line-height: 1.5;
        }
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
        .ttd-box p { margin: 5px 0; }
        .nama-kades {
            margin-top: 70px;
            font-weight: bold;
            text-decoration: underline;
        }
        
        @media print {
            body { background: none; }
            .kertas-surat { margin: 0; padding: 0; width: auto; height: auto; box-shadow: none; }
        }
    </style>
</head>
<body onload="window.print()"> <div class="kertas-surat">
        <div class="kop-surat">
            <h2>PEMERINTAH KABUPATEN HAZEL</h2>
            <h2>KECAMATAN JAYA</h2>
            <h1>DESA HAZELJAYA</h1>
            <p>Jl. Raya Hazeljaya No. 123, Kec. Jaya, Kab. Hazel, Kode Pos 12345</p>
        </div>

        <div class="judul-surat">
            <h3>SURAT KETERANGAN DOMISILI</h3>
            <p>Nomor: 474.4 / <?= $data['id']; ?> / DS-HJ / <?= date('Y'); ?></p>
        </div>

        <div class="isi-surat">
            <p>Yang bertanda tangan di bawah ini, Kepala Desa Hazeljaya, Kecamatan Jaya, Kabupaten Hazel, menerangkan dengan sebenarnya bahwa:</p>
            
            <table class="tabel-biodata">
                <tr>
                    <td>Nama Lengkap</td>
                    <td>:</td>
                    <td><strong><?= $data['nama_pengaju']; ?></strong></td>
                </tr>
                <tr>
                    <td>NIK</td>
                    <td>:</td>
                    <td><?= $data['nik_pengaju']; ?></td>
                </tr>
                <tr>
                    <td>Alamat Rumah</td>
                    <td>:</td>
                    <td><?= $data['alamat_rumah']; ?></td>
                </tr>
            </table>

            <p>Orang tersebut di atas adalah benar-benar warga yang berdomisili dan bertempat tinggal di alamat tersebut, di wilayah Desa Hazeljaya.</p>
            <p>Demikian surat keterangan domisili ini dibuat dengan sebenarnya untuk dapat dipergunakan sebagaimana mestinya.</p>
        </div>

        <div class="ttd-container">
            <div class="ttd-box">
                <p>Hazeljaya, <?= date('d F Y', strtotime($data['tanggal_approve'])); ?></p>
                <p>Kepala Desa Hazeljaya</p>
                <div class="nama-kades">Bapak Kepala Desa</div>
            </div>
            <div style="clear: both;"></div>
        </div>
    </div>

</body>
</html>