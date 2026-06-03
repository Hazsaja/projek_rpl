-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Apr 05, 2026 at 08:54 PM
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
-- Table structure for table `pengajuan_surat`
--

CREATE TABLE `pengajuan_surat` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `jenis_surat` varchar(50) DEFAULT 'Surat Keterangan Domisili',
  `nama_pengaju` varchar(100) NOT NULL,
  `nik_pengaju` varchar(16) NOT NULL,
  `tempat_lahir` varchar(100) NOT NULL,
  `tanggal_lahir` date NOT NULL,
  `jenis_kelamin` enum('Laki-laki','Perempuan') NOT NULL,
  `agama` varchar(50) NOT NULL,
  `status_kawin` varchar(20) NOT NULL,
  `warga_negara` varchar(3) NOT NULL,
  `pekerjaan` varchar(100) NOT NULL,
  `alamat_rumah` text NOT NULL,
  `file_ktp_kk` varchar(255) DEFAULT NULL,
  `file_pengantar` varchar(255) NOT NULL,
  `file_pas_foto` varchar(255) DEFAULT NULL,
  `file_surat_pernyataan` varchar(255) DEFAULT NULL,
  `file_bukti_tinggal` varchar(255) DEFAULT NULL,
  `status_surat` enum('pending','disetujui','ditolak') DEFAULT 'pending',
  `keterangan_ditolak` text DEFAULT NULL,
  `tanggal_pengajuan` timestamp NOT NULL DEFAULT current_timestamp(),
  `tanggal_approve` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `pengajuan_surat`
--

INSERT INTO `pengajuan_surat` (`id`, `user_id`, `jenis_surat`, `nama_pengaju`, `nik_pengaju`, `tempat_lahir`, `tanggal_lahir`, `jenis_kelamin`, `agama`, `status_kawin`, `warga_negara`, `pekerjaan`, `alamat_rumah`, `file_ktp_kk`, `file_pengantar`, `file_pas_foto`, `file_surat_pernyataan`, `file_bukti_tinggal`, `status_surat`, `keterangan_ditolak`, `tanggal_pengajuan`, `tanggal_approve`) VALUES
(1, 2, 'surat keterangan domisili', 'jhjksefsef', '2410631170134000', 'esfesf', '2026-04-06', 'Laki-laki', 'Kristen Protestan', 'Kawin', 'WNI', 'sefesfesf', 'sefesfes', '1775414523_Screenshot 2026-04-05 231620.png', '1775414523_Screenshot 2026-04-05 231620.png', '1775414523_Screenshot 2026-04-05 231620.png', '1775414523_Screenshot 2026-04-05 231620.png', '1775414523_Screenshot 2026-04-05 231620.png', 'disetujui', '', '0000-00-00 00:00:00', '2026-04-05 20:42:49'),
(2, 2, 'surat keterangan domisili', 'jhjksefsefeafs', '2410631170134000', 'cfbfcbc', '2026-04-25', 'Laki-laki', 'Katolik', 'Belum Kawin', 'WNI', 'sefesfesf', 'bgcbc', '1775414836_Screenshot 2026-04-05 204946.png', '1775414836_Screenshot 2026-04-05 204946.png', '1775414836_Screenshot 2026-04-05 231620.png', '1775414836_Screenshot 2026-04-05 204946.png', '1775414836_Screenshot 2026-04-06 013749.png', 'disetujui', '', '0000-00-00 00:00:00', '2026-04-05 20:48:45');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `nama` varchar(100) NOT NULL,
  `nik` varchar(16) NOT NULL,
  `status` enum('admin','user') DEFAULT 'user',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `email`, `password`, `nama`, `nik`, `status`, `created_at`) VALUES
(1, 'razannabilannadif@gmail.com', '$2y$10$4GnEavyFeOkwO9TIaMTIzOA3nHmChnDXgQRElDgMa.2mHtrtUV/D2', 'RAZAN NABIL ANnADIF', '2410631170101000', 'admin', '2026-04-05 16:58:21'),
(2, 'dewaputuatmadewantara@gmail.com', '$2y$10$uCLWFD7YGVpqVMiLS2dUrePxT41I4dXK13RrNwtTdrGmoOBAMtlOS', 'DEWA PUTU ATMA DEWANTARA', '2410631170134000', 'user', '2026-04-05 16:59:13');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `pengajuan_surat`
--
ALTER TABLE `pengajuan_surat`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`),
  ADD UNIQUE KEY `nik` (`nik`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `pengajuan_surat`
--
ALTER TABLE `pengajuan_surat`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `pengajuan_surat`
--
ALTER TABLE `pengajuan_surat`
  ADD CONSTRAINT `pengajuan_surat_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
