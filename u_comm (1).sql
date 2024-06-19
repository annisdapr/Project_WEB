-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jun 19, 2024 at 02:47 PM
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
-- Database: `u_comm`
--

-- --------------------------------------------------------

--
-- Table structure for table `divisi`
--

CREATE TABLE `divisi` (
  `id` int(11) NOT NULL,
  `nama_divisi` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `divisi`
--

INSERT INTO `divisi` (`id`, `nama_divisi`) VALUES
(1, 'Acara'),
(2, 'Humas'),
(3, 'PDD'),
(4, 'Logistik'),
(5, 'Konsumsi'),
(6, 'Koordinator Lapangan'),
(7, 'Sponsorship');

-- --------------------------------------------------------

--
-- Table structure for table `events`
--

CREATE TABLE `events` (
  `id` int(11) NOT NULL,
  `ukm_id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `type` enum('kepanitiaan','anggota') NOT NULL,
  `deskripsi` text DEFAULT NULL,
  `start_date` date DEFAULT NULL,
  `end_date` date DEFAULT NULL,
  `total_participants` int(11) DEFAULT NULL,
  `requirements` varchar(255) DEFAULT NULL,
  `additional_requirements` text DEFAULT NULL,
  `contact_person_name` varchar(100) DEFAULT NULL,
  `contact_person_id_line` varchar(100) DEFAULT NULL,
  `contact_person_phone` varchar(20) DEFAULT NULL,
  `header_image` varchar(255) DEFAULT NULL,
  `divisi` int(11) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `events`
--

INSERT INTO `events` (`id`, `ukm_id`, `name`, `type`, `deskripsi`, `start_date`, `end_date`, `total_participants`, `requirements`, `additional_requirements`, `contact_person_name`, `contact_person_id_line`, `contact_person_phone`, `header_image`, `divisi`) VALUES
(4, 2, 'kernel session', 'kepanitiaan', 'blblblbbl', '2024-06-11', '2024-06-30', 30, 'KTM,CV,KHS', 'smt 4', 'dadang', 'uhuy', '087755439320', '', 7);

-- --------------------------------------------------------

--
-- Table structure for table `registrations`
--

CREATE TABLE `registrations` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `event_id` int(11) NOT NULL,
  `nama` varchar(255) NOT NULL,
  `no_telepon` varchar(20) NOT NULL,
  `fakultas` varchar(255) NOT NULL,
  `prodi` varchar(255) NOT NULL,
  `divisi1` varchar(255) NOT NULL,
  `divisi2` varchar(255) NOT NULL,
  `alasan` text NOT NULL,
  `cv` varchar(255) DEFAULT NULL,
  `khs` varchar(255) DEFAULT NULL,
  `portofolio` varchar(255) DEFAULT NULL,
  `status` varchar(20) DEFAULT 'pending',
  `registration_date` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `registrations`
--

INSERT INTO `registrations` (`id`, `user_id`, `event_id`, `nama`, `no_telepon`, `fakultas`, `prodi`, `divisi1`, `divisi2`, `alasan`, `cv`, `khs`, `portofolio`, `status`, `registration_date`) VALUES
(6, 1, 4, 'dadang', '0123456789', 'FISIP', 'Sosiologi', '1', '3', 'haoahaohaoahaoahaoaha', 'QUIZ PRAKTIKUM CITRA DIGITAL.pdf', '', '', 'pending', '2024-06-19 10:05:46');

-- --------------------------------------------------------

--
-- Table structure for table `requirements`
--

CREATE TABLE `requirements` (
  `id` int(11) NOT NULL,
  `event_id` int(11) NOT NULL,
  `requirement` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `ukm`
--

CREATE TABLE `ukm` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `deskripsi` text DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `instagram` varchar(100) DEFAULT NULL,
  `avatar` varchar(255) DEFAULT NULL,
  `kategori` enum('Agama','Olahraga','Kemahasiswaan','Minat&Bakat','Seni') NOT NULL DEFAULT 'Kemahasiswaan'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `ukm`
--

INSERT INTO `ukm` (`id`, `user_id`, `name`, `deskripsi`, `email`, `instagram`, `avatar`, `kategori`) VALUES
(2, 7, 'Buveja', NULL, NULL, NULL, NULL, 'Olahraga');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('student','ukm','admin') NOT NULL,
  `jurusan` varchar(100) DEFAULT NULL,
  `fakultas` varchar(100) DEFAULT NULL,
  `nim` varchar(20) DEFAULT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `avatar` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `name`, `email`, `password`, `role`, `jurusan`, `fakultas`, `nim`, `phone`, `avatar`) VALUES
(1, 'dadang170', 'Dadang Sunandar', 'annissadwiaprilia@gmail.com', '$2y$10$wOemhEKX0BOTT2g9e3GLi.Db4U/3jdZeyO.j73oyGVYmj.QtCzSUy', 'student', NULL, NULL, NULL, NULL, NULL),
(3, 'mincom77', 'tatang', 'santuybeobeo@gmail.com', '$2y$10$8P3aas/ucJGstDPwUQQ2Uefse4lUPBGYfy3C9DPz59BBawpf9f10m', 'admin', NULL, NULL, NULL, NULL, NULL),
(7, 'buveja88', 'Buveja', '2210511148@mahasiswa.upnvj.ac.id', '$2y$10$oLZN3baaff6iYURG8Kn4I.gZsfzecJm0xuDrmAatEnf2ciEoWnhDG', 'ukm', NULL, NULL, NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `wishlist`
--

CREATE TABLE `wishlist` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `event_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `divisi`
--
ALTER TABLE `divisi`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `events`
--
ALTER TABLE `events`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_events_ukm` (`ukm_id`);

--
-- Indexes for table `registrations`
--
ALTER TABLE `registrations`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `event_id` (`event_id`);

--
-- Indexes for table `requirements`
--
ALTER TABLE `requirements`
  ADD PRIMARY KEY (`id`),
  ADD KEY `event_id` (`event_id`);

--
-- Indexes for table `ukm`
--
ALTER TABLE `ukm`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `wishlist`
--
ALTER TABLE `wishlist`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `event_id` (`event_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `divisi`
--
ALTER TABLE `divisi`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `events`
--
ALTER TABLE `events`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `registrations`
--
ALTER TABLE `registrations`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `requirements`
--
ALTER TABLE `requirements`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `ukm`
--
ALTER TABLE `ukm`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `wishlist`
--
ALTER TABLE `wishlist`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `events`
--
ALTER TABLE `events`
  ADD CONSTRAINT `events_ibfk_1` FOREIGN KEY (`ukm_id`) REFERENCES `ukm` (`id`),
  ADD CONSTRAINT `fk_events_ukm` FOREIGN KEY (`ukm_id`) REFERENCES `ukm` (`id`);

--
-- Constraints for table `registrations`
--
ALTER TABLE `registrations`
  ADD CONSTRAINT `registrations_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `registrations_ibfk_2` FOREIGN KEY (`event_id`) REFERENCES `events` (`id`);

--
-- Constraints for table `requirements`
--
ALTER TABLE `requirements`
  ADD CONSTRAINT `requirements_ibfk_1` FOREIGN KEY (`event_id`) REFERENCES `events` (`id`);

--
-- Constraints for table `ukm`
--
ALTER TABLE `ukm`
  ADD CONSTRAINT `ukm_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

--
-- Constraints for table `wishlist`
--
ALTER TABLE `wishlist`
  ADD CONSTRAINT `wishlist_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `wishlist_ibfk_2` FOREIGN KEY (`event_id`) REFERENCES `events` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
