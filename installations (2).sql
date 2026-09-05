-- phpMyAdmin SQL Dump
-- version 5.1.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Aug 26, 2026 at 12:38 PM
-- Server version: 10.5.29-MariaDB
-- PHP Version: 7.4.33

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `quickacc_admin`
--

-- --------------------------------------------------------

--
-- Table structure for table `installations`
--

CREATE TABLE `installations` (
  `installation_id` int(10) UNSIGNED NOT NULL,
  `company_name` varchar(100) NOT NULL,
  `label` varchar(100) NOT NULL,
  `db_host` varchar(255) NOT NULL,
  `db_port` smallint(5) UNSIGNED NOT NULL DEFAULT 3306,
  `db_name` varchar(100) NOT NULL,
  `db_user` varchar(100) NOT NULL,
  `db_pass_enc` varbinary(512) NOT NULL,
  `boss_id` varchar(50) NOT NULL,
  `status` enum('active','disabled') NOT NULL DEFAULT 'active',
  `last_checked_at` datetime DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_at` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `installations`
--

INSERT INTO `installations` (`installation_id`, `company_name`, `label`, `db_host`, `db_port`, `db_name`, `db_user`, `db_pass_enc`, `boss_id`, `status`, `last_checked_at`, `created_at`, `updated_at`) VALUES
(1, 'Lendsup', 'Lendsup 7', 'localhost', 3306, 'quick7_baita', 'quick7_seven', 0x834ce1352ac7cac32cdf0cf208faa380c80d1414bbfec0e6ba83f8446761d298729314f3c7f162aa4325, '1', 'active', NULL, '2026-08-13 10:47:56', '2026-08-13 16:36:43'),
(2, 'Lendsup', 'Lendsup 7', 'localhost', 3306, 'quick7_kajasi', 'quick7_seven', 0x834ce1352ac7cac32cdf0cf208faa380c80d1414bbfec0e6ba83f8446761d298729314f3c7f162aa4325, '1', 'active', NULL, '2026-08-13 10:47:56', '2026-08-13 16:37:04'),
(3, 'Lendsup', 'Lendsup 5', 'localhost', 3306, 'quick5_lendsup', 'quick5_five', 0x834ce1352ac7cac32cdf0cf208faa380c80d1414bbfec0e6ba83f8446761d298729314f3c7f162aa4325, '1', 'active', NULL, '2026-08-13 10:47:56', '2026-08-25 09:06:12'),
(4, 'Hobro', 'Hoboro 8', 'localhost', 3306, 'quick8_hobro', 'quick8_eight', 0x834ce1352ac7cac32cdf0cf208faa380c80d1414bbfec0e6ba83f8446761d298729314f3c7f162aa4325, '1', 'active', NULL, '2026-08-13 10:47:56', '2026-08-13 16:37:37'),
(5, 'Musha', 'Musha 2', 'localhost', 3306, 'quick2_musha', 'quick2_two', 0x834ce1352ac7cac32cdf0cf208faa380c80d1414bbfec0e6ba83f8446761d298729314f3c7f162aa4325, '1', 'active', NULL, '2026-08-13 10:47:56', '2026-08-13 16:37:53'),
(6, 'Leeway', 'Leeway 2', 'localhost', 3306, 'quick2_leeway', 'quick2_two', 0x834ce1352ac7cac32cdf0cf208faa380c80d1414bbfec0e6ba83f8446761d298729314f3c7f162aa4325, '1', 'active', NULL, '2026-08-13 10:47:56', '2026-08-13 16:38:45'),
(7, 'Lendsup', 'Lendsup 2', 'localhost', 3306, 'quick2_lendsup', 'quick2_two', 0x834ce1352ac7cac32cdf0cf208faa380c80d1414bbfec0e6ba83f8446761d298729314f3c7f162aa4325, '1', 'active', NULL, '2026-08-13 10:47:56', '2026-08-13 16:39:09'),
(8, 'Lendsup', 'Lendsup 4', 'localhost', 3306, 'quick4_lendsup', 'quick4_four', 0x834ce1352ac7cac32cdf0cf208faa380c80d1414bbfec0e6ba83f8446761d298729314f3c7f162aa4325, '1', 'active', NULL, '2026-08-13 10:47:56', '2026-08-13 16:39:29'),
(9, 'Lendsup', 'Lendsup 4', 'localhost', 3306, 'quick4_bugema', 'quick4_four', 0x834ce1352ac7cac32cdf0cf208faa380c80d1414bbfec0e6ba83f8446761d298729314f3c7f162aa4325, '1', 'active', NULL, '2026-08-13 10:47:56', '2026-08-25 09:10:11'),
(10, 'Lendsup', 'Lendsup 9', 'localhost', 3306, 'quickacc_lendbs', 'quickacc_nine', 0x834ce1352ac7cac32cdf0cf208faa380c80d1414bbfec0e6ba83f8446761d298729314f3c7f162aa4325, '1', 'active', NULL, '2026-08-13 10:47:56', '2026-08-25 09:12:02'),
(11, 'Musha', 'Musha 9', 'localhost', 3306, 'quickacc_9musha', 'quickacc_nine', 0x834ce1352ac7cac32cdf0cf208faa380c80d1414bbfec0e6ba83f8446761d298729314f3c7f162aa4325, '1', 'active', NULL, '2026-08-13 10:47:56', '2026-08-25 09:12:37'),
(12, 'Lendsup', 'Lendsup 3', 'localhost', 3306, 'quick3_lendsup', 'quick3_three', 0x834ce1352ac7cac32cdf0cf208faa380c80d1414bbfec0e6ba83f8446761d298729314f3c7f162aa4325, '1', 'active', NULL, '2026-08-13 10:47:56', '2026-08-25 09:13:13'),
(13, 'Lendsup', 'lendsup 9', 'localhost', 3306, 'quickacc_9lends', 'quickacc_nine', 0x834ce1352ac7cac32cdf0cf208faa380c80d1414bbfec0e6ba83f8446761d298729314f3c7f162aa4325, '1', 'active', NULL, '2026-08-13 10:47:56', '2026-08-25 09:12:37'),
(14, 'Lendsup', 'Lendsup 8', 'localhost', 3306, 'quick8_lendsup', 'quick8_eight', 0x834ce1352ac7cac32cdf0cf208faa380c80d1414bbfec0e6ba83f8446761d298729314f3c7f162aa4325, '1', 'active', NULL, '2026-08-13 10:47:56', '2026-08-13 16:37:37');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `installations`
--
ALTER TABLE `installations`
  ADD PRIMARY KEY (`installation_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `installations`
--
ALTER TABLE `installations`
  MODIFY `installation_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
