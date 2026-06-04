-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jun 04, 2026 at 03:56 AM
-- Server version: 10.4.24-MariaDB
-- PHP Version: 8.1.6

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `db_desa`
--

-- --------------------------------------------------------

--
-- Table structure for table `data_pemohon`
--

CREATE TABLE `data_pemohon` (
  `data_pemohon_id` int(11) NOT NULL,
  `pengajuan_id` int(11) NOT NULL,
  `nama` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `nik` varchar(16) COLLATE utf8mb4_unicode_ci NOT NULL,
  `tempat_lahir` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `tanggal_lahir` date DEFAULT NULL,
  `jenis_kelamin` enum('Laki-laki','Perempuan') COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `agama` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status_kawin` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `pekerjaan` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `kewarganegaraan` varchar(10) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `alamat` text COLLATE utf8mb4_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `data_pemohon`
--

INSERT INTO `data_pemohon` (`data_pemohon_id`, `pengajuan_id`, `nama`, `nik`, `tempat_lahir`, `tanggal_lahir`, `jenis_kelamin`, `agama`, `status_kawin`, `pekerjaan`, `kewarganegaraan`, `alamat`) VALUES
(1, 2, 'Hazel Muhammad Naufal Ribawa', '2410631170074000', 'Bogor', '2026-06-03', 'Laki-laki', 'Islam', 'Belum Kawin', 'Mahasiswa', 'WNI', 'Bogor'),
(2, 3, 'Hazel Muhammad Naufal Ribawa', '2410631170074000', 'Bogor', '2026-06-11', 'Laki-laki', 'Islam', 'Belum Kawin', 'Mahasiswa', 'WNI', 'Bogor'),
(3, 4, 'Hazel Muhammad Naufal Ribawa', '2410631170074000', 'Bogor', '2026-06-09', 'Laki-laki', 'Islam', 'Kawin', 'Mahasiswa', 'WNI', 'Bogor');

-- --------------------------------------------------------

--
-- Table structure for table `dokumen_pengajuan`
--

CREATE TABLE `dokumen_pengajuan` (
  `dokumen_pengajuan_id` int(11) NOT NULL,
  `pengajuan_id` int(11) NOT NULL,
  `persyaratan_id` int(11) NOT NULL,
  `nama_file` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `path_file` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `uploaded_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `dokumen_pengajuan`
--

INSERT INTO `dokumen_pengajuan` (`dokumen_pengajuan_id`, `pengajuan_id`, `persyaratan_id`, `nama_file`, `path_file`, `uploaded_at`) VALUES
(1, 2, 1, 'Screenshot 2026-06-02 150428.png', 'uploads/1780532477_Screenshot 2026-06-02 150428.png', '2026-06-04 00:21:17'),
(2, 2, 2, 'Screenshot 2026-06-02 150428.png', 'uploads/1780532477_Screenshot 2026-06-02 150428.png', '2026-06-04 00:21:17'),
(3, 2, 3, 'Screenshot 2026-06-02 150428.png', 'uploads/1780532477_Screenshot 2026-06-02 150428.png', '2026-06-04 00:21:17'),
(4, 3, 1, 'Screenshot 2026-06-02 150428.png', 'uploads/1780534545_Screenshot 2026-06-02 150428.png', '2026-06-04 00:55:45'),
(5, 3, 2, 'Screenshot 2026-06-03 194537.png', 'uploads/1780534545_Screenshot 2026-06-03 194537.png', '2026-06-04 00:55:45'),
(6, 3, 3, 'Screenshot 2026-06-03 194537.png', 'uploads/1780534545_Screenshot 2026-06-03 194537.png', '2026-06-04 00:55:45'),
(7, 4, 1, 'Screenshot 2026-06-02 150428.png', 'uploads/1780534635_Screenshot 2026-06-02 150428.png', '2026-06-04 00:57:15'),
(8, 4, 2, 'Screenshot 2026-06-02 150324.png', 'uploads/1780534635_Screenshot 2026-06-02 150324.png', '2026-06-04 00:57:15'),
(9, 4, 3, 'Screenshot 2026-06-03 194537.png', 'uploads/1780534635_Screenshot 2026-06-03 194537.png', '2026-06-04 00:57:15');

-- --------------------------------------------------------

--
-- Table structure for table `jenis_surat`
--

CREATE TABLE `jenis_surat` (
  `jenis_surat_id` int(11) NOT NULL,
  `kode_surat` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `nama_surat` varchar(150) COLLATE utf8mb4_unicode_ci NOT NULL,
  `deskripsi` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `aktif` tinyint(1) DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `jenis_surat`
--

INSERT INTO `jenis_surat` (`jenis_surat_id`, `kode_surat`, `nama_surat`, `deskripsi`, `aktif`, `created_at`) VALUES
(1, 'SKD', 'Surat Keterangan Domisili', 'Surat keterangan tempat tinggal warga', 1, '2026-06-03 15:34:50'),
(2, 'SKU', 'Surat Keterangan Usaha', 'Surat keterangan kepemilikan usaha', 1, '2026-06-03 15:34:50'),
(3, 'SKTM', 'Surat Keterangan Tidak Mampu', 'Surat bantuan sosial dan pendidikan', 1, '2026-06-03 15:34:50'),
(4, 'SKBM', 'Surat Keterangan Belum Menikah', 'Surat persyaratan administrasi tertentu', 1, '2026-06-03 15:34:50'),
(5, 'SPN', 'Surat Pengantar Nikah', 'Surat pengantar untuk KUA', 1, '2026-06-03 15:34:50'),
(6, 'SKL', 'Surat Keterangan Kelahiran', 'Surat pendukung administrasi kelahiran', 1, '2026-06-03 15:34:50'),
(7, 'SKM', 'Surat Keterangan Kematian', 'Surat administrasi kematian warga', 1, '2026-06-03 15:34:50');

-- --------------------------------------------------------

--
-- Table structure for table `pengajuan`
--

CREATE TABLE `pengajuan` (
  `pengajuan_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `jenis_surat_id` int(11) NOT NULL,
  `nomor_pengajuan` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` enum('menunggu','disetujui','ditolak') COLLATE utf8mb4_unicode_ci DEFAULT 'menunggu',
  `catatan_admin` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `tanggal_pengajuan` timestamp NOT NULL DEFAULT current_timestamp(),
  `tanggal_verifikasi` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `pengajuan`
--

INSERT INTO `pengajuan` (`pengajuan_id`, `user_id`, `jenis_surat_id`, `nomor_pengajuan`, `status`, `catatan_admin`, `tanggal_pengajuan`, `tanggal_verifikasi`) VALUES
(2, 2, 1, NULL, 'disetujui', '', '2026-06-04 00:21:17', '2026-06-03 19:50:09'),
(3, 2, 1, NULL, 'ditolak', 'tolak', '2026-06-04 00:55:45', '2026-06-03 19:56:21'),
(4, 2, 1, NULL, 'disetujui', '', '2026-06-04 00:57:15', '2026-06-03 19:57:44');

-- --------------------------------------------------------

--
-- Table structure for table `persyaratan_surat`
--

CREATE TABLE `persyaratan_surat` (
  `persyaratan_surat_id` int(11) NOT NULL,
  `jenis_surat_id` int(11) NOT NULL,
  `nama_persyaratan` varchar(150) COLLATE utf8mb4_unicode_ci NOT NULL,
  `wajib` tinyint(1) DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `persyaratan_surat`
--

INSERT INTO `persyaratan_surat` (`persyaratan_surat_id`, `jenis_surat_id`, `nama_persyaratan`, `wajib`) VALUES
(1, 1, 'Fotokopi KTP', 1),
(2, 1, 'Fotokopi KK', 1),
(3, 1, 'Pas Foto', 1),
(4, 2, 'Fotokopi KTP', 1),
(5, 2, 'Fotokopi KK', 1),
(6, 2, 'Foto Tempat Usaha', 1),
(7, 3, 'Fotokopi KTP', 1),
(8, 3, 'Fotokopi KK', 1),
(9, 3, 'Surat Pengantar RT/RW', 1),
(10, 4, 'Fotokopi KTP', 1),
(11, 4, 'Fotokopi KK', 1),
(12, 4, 'Surat Pernyataan Belum Menikah', 1),
(13, 5, 'Fotokopi KTP', 1),
(14, 5, 'Fotokopi KK', 1),
(15, 5, 'Surat Pengantar RT/RW', 1),
(16, 6, 'Fotokopi KTP Orang Tua', 1),
(17, 6, 'Fotokopi KK', 1),
(18, 6, 'Surat Keterangan Lahir dari Bidan/Rumah Sakit', 1),
(19, 7, 'Fotokopi KTP Pelapor', 1),
(20, 7, 'Fotokopi KK Almarhum/Almarhumah', 1),
(21, 7, 'Surat Keterangan Kematian dari RT/RW atau Rumah Sakit', 1);

-- --------------------------------------------------------

--
-- Table structure for table `template_surat`
--

CREATE TABLE `template_surat` (
  `template_surat_id` int(11) NOT NULL,
  `jenis_surat_id` int(11) NOT NULL,
  `nama_template` varchar(150) COLLATE utf8mb4_unicode_ci NOT NULL,
  `isi_template` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `template_surat`
--

INSERT INTO `template_surat` (`template_surat_id`, `jenis_surat_id`, `nama_template`, `isi_template`, `created_at`) VALUES
(1, 1, 'Template Surat Keterangan Domisili', '<p>Yang bertanda tangan di bawah ini, Kepala Desa Hazeljaya, Kecamatan Jaya, Kabupaten Hazel, menerangkan dengan sebenarnya bahwa:</p>\r\n<table class=\"tabel-biodata\">\r\n    <tr><td width=\"170\">Nama Lengkap</td><td width=\"10\">:</td><td>{{nama}}</td></tr>\r\n    <tr><td>NIK</td><td>:</td><td>{{nik}}</td></tr>\r\n    <tr><td>Tempat, Tanggal Lahir</td><td>:</td><td>{{tempat_lahir}}, {{tanggal_lahir}}</td></tr>\r\n    <tr><td>Jenis Kelamin</td><td>:</td><td>{{jenis_kelamin}}</td></tr>\r\n    <tr><td>Agama</td><td>:</td><td>{{agama}}</td></tr>\r\n    <tr><td>Status Perkawinan</td><td>:</td><td>{{status_kawin}}</td></tr>\r\n    <tr><td>Pekerjaan</td><td>:</td><td>{{pekerjaan}}</td></tr>\r\n    <tr><td>Kewarganegaraan</td><td>:</td><td>{{kewarganegaraan}}</td></tr>\r\n    <tr><td>Alamat Rumah</td><td>:</td><td>{{alamat}}</td></tr>\r\n</table>\r\n<p>Orang tersebut di atas adalah benar-benar warga yang berdomisili dan bertempat tinggal di alamat tersebut, di wilayah Desa Hazeljaya.</p>\r\n<p>Demikian Surat Keterangan Domisili ini dibuat dengan sebenarnya untuk dapat dipergunakan sebagaimana mestinya.</p>', '2026-06-04 01:11:57'),
(2, 2, 'Template Surat Keterangan Kepemilikan Usaha', '<p>Yang bertanda tangan di bawah ini, Kepala Desa Hazeljaya, Kecamatan Jaya, Kabupaten Hazel, menerangkan dengan sebenarnya bahwa:</p>\r\n<table class=\"tabel-biodata\">\r\n    <tr><td width=\"170\">Nama Lengkap</td><td width=\"10\">:</td><td>{{nama}}</td></tr>\r\n    <tr><td>NIK</td><td>:</td><td>{{nik}}</td></tr>\r\n    <tr><td>Tempat, Tanggal Lahir</td><td>:</td><td>{{tempat_lahir}}, {{tanggal_lahir}}</td></tr>\r\n    <tr><td>Jenis Kelamin</td><td>:</td><td>{{jenis_kelamin}}</td></tr>\r\n    <tr><td>Pekerjaan</td><td>:</td><td>{{pekerjaan}}</td></tr>\r\n    <tr><td>Alamat Tempat Tinggal</td><td>:</td><td>{{alamat}}</td></tr>\r\n</table>\r\n<p>Bahwa nama yang tersebut di atas adalah benar penduduk yang berdomisili di Desa Hazeljaya dan yang bersangkutan benar-benar memiliki usaha mandiri di wilayah Desa Hazeljaya.</p>\r\n<p>Demikian Surat Keterangan Usaha (SKU) ini dibuat atas permintaan yang bersangkutan untuk dapat dipergunakan dengan sebaik-baiknya.</p>', '2026-06-04 01:11:57'),
(3, 3, 'Template Surat Keterangan Tidak Mampu', '<p>Yang bertanda tangan di bawah ini, Kepala Desa Hazeljaya, Kecamatan Jaya, Kabupaten Hazel, menerangkan dengan sebenarnya bahwa:</p>\r\n<table class=\"tabel-biodata\">\r\n    <tr><td width=\"170\">Nama Lengkap</td><td width=\"10\">:</td><td>{{nama}}</td></tr>\r\n    <tr><td>NIK</td><td>:</td><td>{{nik}}</td></tr>\r\n    <tr><td>Tempat, Tanggal Lahir</td><td>:</td><td>{{tempat_lahir}}, {{tanggal_lahir}}</td></tr>\r\n    <tr><td>Pekerjaan</td><td>:</td><td>{{pekerjaan}}</td></tr>\r\n    <tr><td>Alamat</td><td>:</td><td>{{alamat}}</td></tr>\r\n</table>\r\n<p>Sesuai dengan pengamatan kami dan keterangan dari RT/RW setempat, nama yang bersangkutan di atas adalah benar warga kami yang keadaan ekonomi keluarganya saat ini termasuk dalam keluarga <b>TIDAK MAMPU (Keluarga Pra-Sejahtera)</b>.</p>\r\n<p>Surat keterangan ini diberikan kepada yang bersangkutan sebagai salah satu persyaratan kelengkapan administrasi.</p>\r\n<p>Demikian Surat Keterangan Tidak Mampu (SKTM) ini dibuat untuk dapat dipergunakan sebagaimana mestinya.</p>', '2026-06-04 01:11:57'),
(4, 4, 'Template Surat Keterangan Belum Menikah', '<p>Yang bertanda tangan di bawah ini, Kepala Desa Hazeljaya, Kecamatan Jaya, Kabupaten Hazel, menerangkan dengan sebenarnya bahwa:</p>\r\n<table class=\"tabel-biodata\">\r\n    <tr><td width=\"170\">Nama Lengkap</td><td width=\"10\">:</td><td>{{nama}}</td></tr>\r\n    <tr><td>NIK</td><td>:</td><td>{{nik}}</td></tr>\r\n    <tr><td>Tempat, Tanggal Lahir</td><td>:</td><td>{{tempat_lahir}}, {{tanggal_lahir}}</td></tr>\r\n    <tr><td>Jenis Kelamin</td><td>:</td><td>{{jenis_kelamin}}</td></tr>\r\n    <tr><td>Agama</td><td>:</td><td>{{agama}}</td></tr>\r\n    <tr><td>Pekerjaan</td><td>:</td><td>{{pekerjaan}}</td></tr>\r\n    <tr><td>Alamat</td><td>:</td><td>{{alamat}}</td></tr>\r\n</table>\r\n<p>Berdasarkan catatan register Desa Hazeljaya dan pengamatan kami di lapangan, yang bersangkutan adalah benar warga kami dan hingga surat ini dikeluarkan, yang bersangkutan benar-benar berstatus <b>BELUM PERNAH MENIKAH</b> (Jejaka/Perawan).</p>\r\n<p>Demikian Surat Keterangan Belum Menikah ini dibuat dengan sebenarnya untuk dapat dipergunakan sebagai persyaratan administrasi.</p>', '2026-06-04 01:15:10'),
(5, 5, 'Template Surat Pengantar Nikah', '<p>Yang bertanda tangan di bawah ini, Kepala Desa Hazeljaya, Kecamatan Jaya, Kabupaten Hazel, menerangkan dengan sebenarnya bahwa:</p>\r\n<table class=\"tabel-biodata\">\r\n    <tr><td width=\"170\">Nama Lengkap</td><td width=\"10\">:</td><td>{{nama}}</td></tr>\r\n    <tr><td>NIK</td><td>:</td><td>{{nik}}</td></tr>\r\n    <tr><td>Tempat, Tanggal Lahir</td><td>:</td><td>{{tempat_lahir}}, {{tanggal_lahir}}</td></tr>\r\n    <tr><td>Jenis Kelamin</td><td>:</td><td>{{jenis_kelamin}}</td></tr>\r\n    <tr><td>Agama</td><td>:</td><td>{{agama}}</td></tr>\r\n    <tr><td>Status Perkawinan</td><td>:</td><td>{{status_kawin}}</td></tr>\r\n    <tr><td>Alamat</td><td>:</td><td>{{alamat}}</td></tr>\r\n</table>\r\n<p>Adalah benar warga yang berdomisili di wilayah Desa Hazeljaya. Surat keterangan ini diberikan kepada yang bersangkutan sebagai <b>Surat Pengantar Nikah</b> untuk mengurus administrasi pernikahan pada Kantor Urusan Agama (KUA) setempat.</p>\r\n<p>Demikian surat pengantar ini dibuat dengan sebenarnya untuk dipergunakan sebagaimana mestinya.</p>', '2026-06-04 01:15:10'),
(6, 6, 'Template Surat Keterangan Kelahiran', '<p>Yang bertanda tangan di bawah ini, Kepala Desa Hazeljaya, Kecamatan Jaya, Kabupaten Hazel, menerangkan dengan sebenarnya bahwa pelapor di bawah ini:</p>\r\n<table class=\"tabel-biodata\">\r\n    <tr><td width=\"170\">Nama Pelapor</td><td width=\"10\">:</td><td>{{nama}}</td></tr>\r\n    <tr><td>NIK</td><td>:</td><td>{{nik}}</td></tr>\r\n    <tr><td>Tempat, Tanggal Lahir</td><td>:</td><td>{{tempat_lahir}}, {{tanggal_lahir}}</td></tr>\r\n    <tr><td>Pekerjaan</td><td>:</td><td>{{pekerjaan}}</td></tr>\r\n    <tr><td>Alamat</td><td>:</td><td>{{alamat}}</td></tr>\r\n</table>\r\n<p>Yang bersangkutan di atas telah melaporkan adanya peristiwa <b>KELAHIRAN</b> anggota keluarganya di lingkungan Desa Hazeljaya sesuai dengan surat keterangan dari Bidan/Rumah Sakit yang dilampirkan.</p>\r\n<p>Surat keterangan ini dikeluarkan sebagai pengantar untuk pengurusan pencatatan dan penerbitan Akta Kelahiran di Dinas Kependudukan dan Pencatatan Sipil.</p>\r\n<p>Demikian surat keterangan ini dibuat agar dapat dipergunakan sebagaimana mestinya.</p>', '2026-06-04 01:15:10'),
(7, 7, 'Template Surat Keterangan Kematian', '<p>Yang bertanda tangan di bawah ini, Kepala Desa Hazeljaya, Kecamatan Jaya, Kabupaten Hazel, menerangkan dengan sebenarnya bahwa pelapor di bawah ini:</p>\r\n<table class=\"tabel-biodata\">\r\n    <tr><td width=\"170\">Nama Pelapor / Ahli Waris</td><td width=\"10\">:</td><td>{{nama}}</td></tr>\r\n    <tr><td>NIK</td><td>:</td><td>{{nik}}</td></tr>\r\n    <tr><td>Tempat, Tanggal Lahir</td><td>:</td><td>{{tempat_lahir}}, {{tanggal_lahir}}</td></tr>\r\n    <tr><td>Pekerjaan</td><td>:</td><td>{{pekerjaan}}</td></tr>\r\n    <tr><td>Alamat</td><td>:</td><td>{{alamat}}</td></tr>\r\n</table>\r\n<p>Yang bersangkutan di atas telah melaporkan adanya peristiwa <b>KEMATIAN</b> anggota keluarganya di lingkungan Desa Hazeljaya berdasarkan pemeriksaan medis / laporan RT RW setempat.</p>\r\n<p>Surat keterangan ini dikeluarkan sebagai pengantar untuk pengurusan Akta Kematian di Dinas Kependudukan dan Pencatatan Sipil, serta keperluan administrasi ahli waris lainnya.</p>\r\n<p>Demikian surat keterangan ini dibuat dengan sebenarnya dan untuk dipergunakan sebagaimana mestinya.</p>', '2026-06-04 01:15:10');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `user_id` int(11) NOT NULL,
  `nama` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `nik` varchar(16) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` enum('admin','user') COLLATE utf8mb4_unicode_ci DEFAULT 'user',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`user_id`, `nama`, `nik`, `email`, `password`, `status`, `created_at`) VALUES
(1, 'Razan Nabil Annadif', '2410631170101000', 'razannabilannadif@gmail.com', '$2y$10$KMeGMEfX9CkJ2JmLISi6wuDXb39/w2f9gfKlmLtVNAgnaXCeANmJy', 'admin', '2026-06-03 15:57:02'),
(2, 'Hazel Muhammad Naufal Ribawa', '2410631170074000', 'hazelmuhammadnaufalribawa@gmail.com', '$2y$10$0q8h2PoNiP1xLWaQz5FZgOSiVXBxrCZrgoMFgJVtGC9kH8Af4nOmG', 'user', '2026-06-03 15:58:50');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `data_pemohon`
--
ALTER TABLE `data_pemohon`
  ADD PRIMARY KEY (`data_pemohon_id`),
  ADD KEY `fk_data_pemohon` (`pengajuan_id`);

--
-- Indexes for table `dokumen_pengajuan`
--
ALTER TABLE `dokumen_pengajuan`
  ADD PRIMARY KEY (`dokumen_pengajuan_id`),
  ADD KEY `fk_dokumen_pengajuan` (`pengajuan_id`),
  ADD KEY `fk_dokumen_persyaratan` (`persyaratan_id`);

--
-- Indexes for table `jenis_surat`
--
ALTER TABLE `jenis_surat`
  ADD PRIMARY KEY (`jenis_surat_id`),
  ADD UNIQUE KEY `kode_surat` (`kode_surat`);

--
-- Indexes for table `pengajuan`
--
ALTER TABLE `pengajuan`
  ADD PRIMARY KEY (`pengajuan_id`),
  ADD UNIQUE KEY `nomor_pengajuan` (`nomor_pengajuan`),
  ADD KEY `fk_pengajuan_user` (`user_id`),
  ADD KEY `fk_pengajuan_jenis` (`jenis_surat_id`);

--
-- Indexes for table `persyaratan_surat`
--
ALTER TABLE `persyaratan_surat`
  ADD PRIMARY KEY (`persyaratan_surat_id`),
  ADD KEY `fk_persyaratan_surat` (`jenis_surat_id`);

--
-- Indexes for table `template_surat`
--
ALTER TABLE `template_surat`
  ADD PRIMARY KEY (`template_surat_id`),
  ADD KEY `fk_template_surat` (`jenis_surat_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`user_id`),
  ADD UNIQUE KEY `nik` (`nik`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `data_pemohon`
--
ALTER TABLE `data_pemohon`
  MODIFY `data_pemohon_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `dokumen_pengajuan`
--
ALTER TABLE `dokumen_pengajuan`
  MODIFY `dokumen_pengajuan_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `jenis_surat`
--
ALTER TABLE `jenis_surat`
  MODIFY `jenis_surat_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `pengajuan`
--
ALTER TABLE `pengajuan`
  MODIFY `pengajuan_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `persyaratan_surat`
--
ALTER TABLE `persyaratan_surat`
  MODIFY `persyaratan_surat_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT for table `template_surat`
--
ALTER TABLE `template_surat`
  MODIFY `template_surat_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `data_pemohon`
--
ALTER TABLE `data_pemohon`
  ADD CONSTRAINT `fk_data_pemohon` FOREIGN KEY (`pengajuan_id`) REFERENCES `pengajuan` (`pengajuan_id`) ON DELETE CASCADE;

--
-- Constraints for table `dokumen_pengajuan`
--
ALTER TABLE `dokumen_pengajuan`
  ADD CONSTRAINT `fk_dokumen_pengajuan` FOREIGN KEY (`pengajuan_id`) REFERENCES `pengajuan` (`pengajuan_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_dokumen_persyaratan` FOREIGN KEY (`persyaratan_id`) REFERENCES `persyaratan_surat` (`persyaratan_surat_id`) ON DELETE CASCADE;

--
-- Constraints for table `pengajuan`
--
ALTER TABLE `pengajuan`
  ADD CONSTRAINT `fk_pengajuan_jenis` FOREIGN KEY (`jenis_surat_id`) REFERENCES `jenis_surat` (`jenis_surat_id`),
  ADD CONSTRAINT `fk_pengajuan_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE;

--
-- Constraints for table `persyaratan_surat`
--
ALTER TABLE `persyaratan_surat`
  ADD CONSTRAINT `fk_persyaratan_surat` FOREIGN KEY (`jenis_surat_id`) REFERENCES `jenis_surat` (`jenis_surat_id`) ON DELETE CASCADE;

--
-- Constraints for table `template_surat`
--
ALTER TABLE `template_surat`
  ADD CONSTRAINT `fk_template_surat` FOREIGN KEY (`jenis_surat_id`) REFERENCES `jenis_surat` (`jenis_surat_id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
