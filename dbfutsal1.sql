-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Aug 11, 2024 at 06:13 PM
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
-- Database: `dbfutsal1`
--

-- --------------------------------------------------------

--
-- Table structure for table `admin`
--

CREATE TABLE `admin` (
  `id_user` int(3) NOT NULL,
  `username` varchar(20) NOT NULL,
  `password` varchar(20) NOT NULL,
  `nama` varchar(50) NOT NULL,
  `no_handphone` varchar(15) NOT NULL,
  `email` varchar(50) NOT NULL,
  `foto` varchar(10000) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `admin`
--

INSERT INTO `admin` (`id_user`, `username`, `password`, `nama`, `no_handphone`, `email`, `foto`) VALUES
(1, 'alvansyahhutasoit', 'alvansyah', 'Alvansyah Hutasoit', '081234567890', 'alvansyah@gmail.com', '66818fb804dad.jpg');

-- --------------------------------------------------------

--
-- Table structure for table `bayar`
--

CREATE TABLE `bayar` (
  `id_bayar` int(11) NOT NULL,
  `id_sewa` int(11) NOT NULL,
  `id_metode_bayar` int(11) NOT NULL,
  `status` varchar(100) NOT NULL,
  `bayar_dp` int(11) NOT NULL,
  `kekurangan` int(11) NOT NULL,
  `bukti` text NOT NULL,
  `tanggal_upload` timestamp NOT NULL DEFAULT current_timestamp(),
  `konfirmasi` varchar(50) NOT NULL DEFAULT 'Belum'
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `chat`
--

CREATE TABLE `chat` (
  `id_chat` int(11) NOT NULL,
  `id_user` int(11) NOT NULL,
  `id_admin` int(11) NOT NULL,
  `admin` varchar(10) NOT NULL,
  `pesan` varchar(10000) NOT NULL,
  `tanggal` date NOT NULL,
  `jam` time NOT NULL,
  `read_status` tinyint(1) DEFAULT 0,
  `read_status_2` tinyint(4) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `chat`
--

INSERT INTO `chat` (`id_chat`, `id_user`, `id_admin`, `admin`, `pesan`, `tanggal`, `jam`, `read_status`, `read_status_2`) VALUES
(0, 2, 1, '', 'tes', '2024-07-05', '02:05:27', 1, 0),
(0, 2, 1, '', 'min', '2024-07-05', '02:09:47', 1, 0),
(0, 2, 1, 'Admin', 'iya kak', '2024-07-05', '02:11:29', 0, 1),
(0, 2, 1, '', 'konfirmasi pembayaran saya min', '2024-07-05', '02:17:06', 1, 0),
(0, 2, 1, 'Admin', 'Baik, kami akan segera mengkonfirmasi pembayaran anda', '2024-07-05', '02:17:37', 0, 1),
(0, 2, 1, 'Admin', 'ditunggu ya kak', '2024-07-05', '02:18:05', 0, 1),
(0, 2, 1, '', 'iya kak', '2024-07-05', '02:18:39', 1, 0),
(0, 2, 1, 'Admin', 'ok', '2024-07-05', '02:18:54', 0, 1),
(0, 2, 1, 'Admin', 'sudah yaa :)', '2024-07-05', '02:19:46', 0, 1),
(0, 2, 1, 'Admin', 'terima kasih telah menghubungi kami :)', '2024-07-05', '02:20:03', 0, 1),
(0, 2, 1, '', 'sama-sama min', '2024-07-05', '02:20:51', 1, 0),
(0, 2, 1, '', 'min', '2024-07-05', '02:37:52', 1, 0),
(0, 3, 1, '', 'hai min', '2024-07-05', '02:41:14', 1, 0),
(0, 2, 1, 'Admin', 'halo', '2024-07-05', '02:55:15', 0, 1),
(0, 2, 1, '', 'iya min', '2024-07-05', '02:56:13', 1, 0),
(0, 2, 1, 'Admin', 'Jangan lupa berikan saran kepada kami ya kak', '2024-07-05', '03:11:09', 0, 1),
(0, 2, 1, '', 'Oke min', '2024-07-05', '03:11:26', 1, 0),
(0, 2, 1, '', 'sudah ya min', '2024-07-05', '03:11:57', 1, 0),
(0, 2, 1, 'Admin', 'terima kasih lagi', '2024-07-05', '03:12:18', 0, 1),
(0, 2, 1, '', 'sama-sama min', '2024-07-05', '03:12:26', 1, 0),
(0, 2, 1, 'Admin', 'ok', '2024-07-05', '03:17:29', 0, 1),
(0, 2, 1, 'Admin', 'halo', '2024-07-05', '03:25:56', 0, 1),
(0, 2, 1, 'Admin', 'halo', '2024-07-05', '03:25:57', 0, 1),
(0, 2, 1, 'Admin', 'tes', '2024-07-05', '03:27:51', 0, 1),
(0, 2, 1, 'Admin', 'min', '2024-07-05', '03:31:21', 0, 1),
(0, 2, 1, 'Admin', 'tes', '2024-07-05', '03:31:48', 0, 1),
(0, 2, 1, 'Admin', 'tes', '2024-07-05', '03:37:23', 0, 1),
(0, 2, 1, 'Admin', 'hai', '2024-07-05', '03:52:04', 0, 1),
(0, 2, 1, 'Admin', 'tes', '2024-07-05', '03:52:09', 0, 1),
(0, 2, 1, '', 'iya', '2024-07-05', '03:52:23', 1, 0),
(0, 2, 1, '', 'tes', '2024-07-05', '03:52:48', 1, 0),
(0, 3, 1, 'Admin', 'iya kak, ada yg bisa dibantu?', '2024-07-05', '04:12:25', 0, 0),
(0, 2, 1, '', 'tes', '2024-07-15', '21:43:47', 1, 0),
(0, 2, 1, 'Admin', 'tes', '2024-07-15', '21:44:11', 0, 1),
(0, 2, 1, '', 'tes', '2024-07-15', '21:44:22', 0, 0),
(0, 2, 1, '', 'halo', '2024-08-11', '10:50:25', 0, 0);

-- --------------------------------------------------------

--
-- Table structure for table `event`
--

CREATE TABLE `event` (
  `id` int(11) NOT NULL,
  `nama` varchar(255) DEFAULT NULL,
  `harga` int(11) DEFAULT NULL,
  `keterangan` varchar(10000) DEFAULT NULL,
  `foto` varchar(1000) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `event`
--

INSERT INTO `event` (`id`, `nama`, `harga`, `keterangan`, `foto`) VALUES
(7, 'berita 1', 0, '                                                                    ', '6699706285ba3.png'),
(8, 'berita 2', 0, '                                                                    ', '6699707be2388.png');

-- --------------------------------------------------------

--
-- Table structure for table `lapangan`
--

CREATE TABLE `lapangan` (
  `id_lapangan` int(11) NOT NULL,
  `nama` varchar(35) NOT NULL,
  `keterangan` text NOT NULL,
  `harga1` int(11) NOT NULL,
  `harga2` int(11) NOT NULL,
  `harga3` int(11) NOT NULL,
  `foto` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `lapangan`
--

INSERT INTO `lapangan` (`id_lapangan`, `nama`, `keterangan`, `harga1`, `harga2`, `harga3`, `foto`) VALUES
(30, 'Lapangan 1', 'Lapangan futsal adalah area khusus untuk bermain futsal, sebuah varian sepak bola yang dimainkan di dalam ruangan dengan tim beranggotakan lima pemain. Lapangan ini biasanya memiliki panjang antara 25 hingga 42 meter dan lebar antara 16 hingga 25 meter, dengan permukaan yang terbuat dari kayu keras atau material sintetis yang halus. Semua garis pada lapangan memiliki lebar 8 cm, dengan garis tengah dan lingkaran tengah yang memiliki radius 3 meter. Area penalti berbentuk setengah lingkaran dengan radius 6 meter dari gawang, yang berukuran lebar 3 meter dan tinggi 2 meter.                                                                                        ', 90000, 100000, 120000, '6602c87cf343d.png'),
(34, 'Lapangan 2', 'Fasilitasnya lengkap                                                                                                            ', 90000, 100000, 120000, '663b9ba76ab06.jpg');

-- --------------------------------------------------------

--
-- Table structure for table `metode_pembayaran`
--

CREATE TABLE `metode_pembayaran` (
  `id_metode_bayar` int(11) NOT NULL,
  `nama` varchar(255) NOT NULL,
  `metode` varchar(255) NOT NULL,
  `nomor` varchar(255) NOT NULL,
  `nominal_dp` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `metode_pembayaran`
--

INSERT INTO `metode_pembayaran` (`id_metode_bayar`, `nama`, `metode`, `nomor`, `nominal_dp`) VALUES
(5, 'admin', 'BANK MANDIRI', '0897313', 20000),
(6, 'ALVANSYAH', 'DANA', '089515311765', 20000);

-- --------------------------------------------------------

--
-- Table structure for table `saran`
--

CREATE TABLE `saran` (
  `id_saran` int(11) NOT NULL,
  `saran` varchar(10000) NOT NULL,
  `id_user` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `saran`
--

INSERT INTO `saran` (`id_saran`, `saran`, `id_user`) VALUES
(1, 'Keren', 2),
(2, 'Bagus', 2),
(3, 'Bagus', 2),
(4, 'bagus', 2),
(6, 'Keren kak', 2),
(7, 'Keren kak', 2),
(8, 'Keren bgt', 2),
(9, 'Mantap kak', 2),
(10, 'Keren', 2),
(11, 'Keren', 2),
(12, 'Mantap', 2),
(13, 'Lapangan nya dicat kak biar lebih bagus', 2),
(14, 'Lapangan nya dicat kak biar lebih bagus', 2),
(15, 'Keren padahal', 2),
(17, 'Ditingkatkan', 2),
(18, 'Bagus kak', 3),
(19, 'Jelek bang', 2);

-- --------------------------------------------------------

--
-- Table structure for table `sewa`
--

CREATE TABLE `sewa` (
  `id_sewa` int(11) NOT NULL,
  `id_user` int(11) NOT NULL,
  `id_lapangan` int(11) NOT NULL,
  `tanggal_pesan` date DEFAULT NULL,
  `jam_pesan` time NOT NULL,
  `lama_sewa` int(11) NOT NULL,
  `jam_mulai` datetime NOT NULL,
  `jam_habis` datetime NOT NULL,
  `harga` int(11) NOT NULL,
  `total` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `sewa`
--

INSERT INTO `sewa` (`id_sewa`, `id_user`, `id_lapangan`, `tanggal_pesan`, `jam_pesan`, `lama_sewa`, `jam_mulai`, `jam_habis`, `harga`, `total`) VALUES
(1, 2, 30, '2024-07-05', '07:22:23', 2, '2024-07-10 08:00:00', '2024-07-10 10:00:00', 90000, 180000),
(2, 2, 34, '2024-07-05', '07:23:05', 3, '2024-07-09 14:00:00', '2024-07-09 17:00:00', 100000, 300000),
(3, 2, 34, '2024-07-05', '07:23:42', 2, '2024-07-07 20:00:00', '2024-07-07 22:00:00', 120000, 240000),
(4, 3, 30, '2024-07-05', '07:24:23', 1, '2024-07-07 20:20:00', '2024-07-07 21:20:00', 120000, 120000),
(5, 3, 34, '2024-07-05', '07:27:26', 1, '2024-07-09 20:27:00', '2024-07-09 21:27:00', 120000, 120000),
(6, 2, 30, '2024-07-05', '07:36:18', 1, '2024-07-06 21:00:00', '2024-07-06 22:00:00', 120000, 120000),
(7, 2, 30, '2024-07-15', '21:12:36', 2, '2024-07-31 21:12:00', '2024-07-31 23:12:00', 120000, 240000),
(8, 2, 30, '2024-08-11', '16:43:54', 1, '2024-08-11 16:43:00', '2024-08-11 17:43:00', 100000, 100000);

-- --------------------------------------------------------

--
-- Table structure for table `slider`
--

CREATE TABLE `slider` (
  `id` int(11) NOT NULL,
  `foto` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `slider`
--

INSERT INTO `slider` (`id`, `foto`) VALUES
(3, '669970930c4ec.png'),
(4, '669970a85cdc6.png');

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE `user` (
  `id_user` int(11) NOT NULL,
  `email` varchar(50) NOT NULL,
  `password` varchar(32) NOT NULL,
  `no_handphone` varchar(20) NOT NULL,
  `jenis_kelamin` varchar(10) NOT NULL,
  `nama_lengkap` varchar(60) NOT NULL,
  `alamat` text NOT NULL,
  `foto` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`id_user`, `email`, `password`, `no_handphone`, `jenis_kelamin`, `nama_lengkap`, `alamat`, `foto`) VALUES
(2, 'user@gmail.com', 'user', '081234567890', 'Laki-laki', 'Antonio Hutasoit', 'Jl. Rowosari No. 4', '66818fb804dad.jpg'),
(3, 'user2@gmail.com', 'user2', '081234567890', 'Laki-laki', 'Axie Hutasoit', 'Jl. Umban Sari', '663d2499a635d.jpg'),
(10, 'user3@gmail.com', 'user3', '081234567890', 'Laki-Laki', 'user3', 'user3', '6684aa600c742.jpg'),
(11, 'user4@gmail.com', 'user4', '081234567890', 'Laki-Laki', 'user4', 'user4', '6684aac626ebd.jpg'),
(12, 'user5@gmail.com', 'user5', '081234567890', 'Laki-Laki', 'user5', 'user5', '6684aae18eb1a.jpg'),
(13, 'user6@gmail.com', 'user6', '081234567890', 'Laki-Laki', 'user6', 'user6', '6684aaf534305.jpg');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admin`
--
ALTER TABLE `admin`
  ADD PRIMARY KEY (`id_user`);

--
-- Indexes for table `bayar`
--
ALTER TABLE `bayar`
  ADD PRIMARY KEY (`id_bayar`),
  ADD KEY `foreign key` (`id_sewa`);

--
-- Indexes for table `chat`
--
ALTER TABLE `chat`
  ADD KEY `fk_chat` (`id_user`),
  ADD KEY `fk_chat1` (`id_admin`);

--
-- Indexes for table `event`
--
ALTER TABLE `event`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `lapangan`
--
ALTER TABLE `lapangan`
  ADD PRIMARY KEY (`id_lapangan`);

--
-- Indexes for table `metode_pembayaran`
--
ALTER TABLE `metode_pembayaran`
  ADD PRIMARY KEY (`id_metode_bayar`);

--
-- Indexes for table `saran`
--
ALTER TABLE `saran`
  ADD PRIMARY KEY (`id_saran`),
  ADD KEY `id_user` (`id_user`);

--
-- Indexes for table `sewa`
--
ALTER TABLE `sewa`
  ADD PRIMARY KEY (`id_sewa`),
  ADD KEY `id_user` (`id_user`),
  ADD KEY `id_lapangan` (`id_lapangan`);

--
-- Indexes for table `slider`
--
ALTER TABLE `slider`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`id_user`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admin`
--
ALTER TABLE `admin`
  MODIFY `id_user` int(3) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `bayar`
--
ALTER TABLE `bayar`
  MODIFY `id_bayar` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `event`
--
ALTER TABLE `event`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `lapangan`
--
ALTER TABLE `lapangan`
  MODIFY `id_lapangan` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=43;

--
-- AUTO_INCREMENT for table `metode_pembayaran`
--
ALTER TABLE `metode_pembayaran`
  MODIFY `id_metode_bayar` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `saran`
--
ALTER TABLE `saran`
  MODIFY `id_saran` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT for table `sewa`
--
ALTER TABLE `sewa`
  MODIFY `id_sewa` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `slider`
--
ALTER TABLE `slider`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `user`
--
ALTER TABLE `user`
  MODIFY `id_user` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `bayar`
--
ALTER TABLE `bayar`
  ADD CONSTRAINT `fk_metode_bayar` FOREIGN KEY (`id_metode_bayar`) REFERENCES `metode_pembayaran` (`id_metode_bayar`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `foreign key` FOREIGN KEY (`id_sewa`) REFERENCES `sewa` (`id_sewa`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `chat`
--
ALTER TABLE `chat`
  ADD CONSTRAINT `fk_chat` FOREIGN KEY (`id_user`) REFERENCES `user` (`id_user`),
  ADD CONSTRAINT `fk_chat1` FOREIGN KEY (`id_admin`) REFERENCES `admin` (`id_user`);

--
-- Constraints for table `saran`
--
ALTER TABLE `saran`
  ADD CONSTRAINT `saran_ibfk_1` FOREIGN KEY (`id_user`) REFERENCES `user` (`id_user`);

--
-- Constraints for table `sewa`
--
ALTER TABLE `sewa`
  ADD CONSTRAINT `sewa_ibfk_1` FOREIGN KEY (`id_user`) REFERENCES `user` (`id_user`),
  ADD CONSTRAINT `sewa_ibfk_2` FOREIGN KEY (`id_lapangan`) REFERENCES `lapangan` (`id_lapangan`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
