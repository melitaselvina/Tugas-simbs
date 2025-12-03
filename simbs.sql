-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Dec 03, 2025 at 02:23 PM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `simbs`
--

-- --------------------------------------------------------

--
-- Table structure for table `buku`
--

CREATE TABLE `buku` (
  `id_buku` int(11) NOT NULL,
  `id_kategori` int(11) NOT NULL,
  `penerbit` varchar(100) NOT NULL,
  `tahun` int(11) NOT NULL,
  `tanggal_input` timestamp(6) NOT NULL DEFAULT current_timestamp(6) ON UPDATE current_timestamp(6),
  `penulis` varchar(255) NOT NULL,
  `judul` varchar(255) NOT NULL,
  `gambar` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `buku`
--

INSERT INTO `buku` (`id_buku`, `id_kategori`, `penerbit`, `tahun`, `tanggal_input`, `penulis`, `judul`, `gambar`) VALUES
(1, 3, 'Elex Media Komputindo', 2024, '2025-11-29 01:49:20.531017', 'Rizky Pratama', 'Panduan Lengkap Laravel 11', '692a512081615.jpg'),
(2, 3, 'Gramedia', 2023, '2025-11-29 01:48:49.523063', 'Susi Susanti', 'Dasar-Dasar Python untuk Data Science', '692a51017f798.jpg'),
(3, 1, 'Deepublish', 2022, '2025-11-29 01:48:31.575172', 'Andi Wijaya', 'Kota di Bawah Awan: Seri Fiksi Ilmiah', '692a50ef8c229.jpg'),
(4, 2, ' Kompas', 2021, '2025-11-29 01:48:03.412253', 'Prof. Dr. Budiman', 'Rekam Jejak Kerajaan Majapahit', '692a50d364627.jpg'),
(5, 4, 'Tiga Serangkai', 2023, '2025-11-29 01:47:44.002360', 'Maya Kirana', 'Dongeng Binatang di Hutan Tropis', '692a50c000523.jpg'),
(6, 3, 'Informatika Bandung', 2024, '2025-11-29 01:47:13.937745', 'Ahmad Fauzi', 'Mastering MySQL untuk Web Developer', '692a50a1e4ad7.jpg'),
(7, 1, 'Bentang Pustaka', 2020, '2025-11-29 01:46:52.075257', 'Clara Bella', 'Perjalanan Menuju Mars Alpha', '692a508c121ce.jpg'),
(8, 2, 'Diva Press', 2025, '2025-11-29 01:45:16.144662', 'Rizem Aizid', 'Sejarah Ringkas Perang Dunia 1 &amp; 2 Lengkap dan Ringkas', '692a502c22f69.jpg'),
(9, 4, 'Bumi Aksara', 2022, '2025-11-29 01:46:25.615542', 'Tim Edukasi', 'Belajar Berhitung dan Warna untuk Balita', '692a507195f63.jpg'),
(10, 3, 'Andi Offset', 2023, '2025-11-29 01:46:03.987643', 'Dewi Sartika', 'Membuat Aplikasi Android dengan Kotlin', '692a505bf0ec2.jpg'),
(11, 5, 'Gramedia', 2024, '2025-11-29 01:45:35.192309', 'Natasha Rizky', 'Ternyata Tanpamu', '692a503f2eb94.jpg');

-- --------------------------------------------------------

--
-- Table structure for table `kategori`
--

CREATE TABLE `kategori` (
  `id_kategori` int(11) NOT NULL,
  `nama_kategori` varchar(100) NOT NULL,
  `tanggal_input` timestamp(6) NOT NULL DEFAULT current_timestamp(6) ON UPDATE current_timestamp(6)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `kategori`
--

INSERT INTO `kategori` (`id_kategori`, `nama_kategori`, `tanggal_input`) VALUES
(1, 'Fiksi Ilmiah', '2025-11-28 18:56:34.833877'),
(2, 'Sejarah', '2025-11-28 18:56:48.009188'),
(3, 'Pemrograman', '2025-11-28 18:57:00.802336'),
(4, 'Anak-anak', '2025-11-28 18:57:12.234861'),
(5, 'Puisi', '2025-11-28 18:57:26.057876');

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE `user` (
  `id_user` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`id_user`, `username`, `email`, `password`) VALUES
(1, 'lita', 'lita@gmail.com', '$2y$10$IJFNxcr0zlc86Ko5Qffnlu7ZLvarzyDwA9yqPEWVnw/3bHHKIZgAi');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `buku`
--
ALTER TABLE `buku`
  ADD PRIMARY KEY (`id_buku`);

--
-- Indexes for table `kategori`
--
ALTER TABLE `kategori`
  ADD PRIMARY KEY (`id_kategori`);

--
-- Indexes for table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`id_user`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `buku`
--
ALTER TABLE `buku`
  MODIFY `id_buku` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=114;

--
-- AUTO_INCREMENT for table `kategori`
--
ALTER TABLE `kategori`
  MODIFY `id_kategori` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `user`
--
ALTER TABLE `user`
  MODIFY `id_user` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
