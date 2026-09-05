-- phpMyAdmin SQL Dump
-- version 5.1.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Aug 26, 2026 at 12:39 PM
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
-- Table structure for table `branches`
--

CREATE TABLE `branches` (
  `branch_id` int(10) UNSIGNED NOT NULL,
  `installation_id` int(10) UNSIGNED NOT NULL,
  `branch_name` varchar(100) NOT NULL,
  `sort_order` smallint(5) UNSIGNED NOT NULL DEFAULT 0,
  `status` enum('active','disabled') NOT NULL DEFAULT 'active',
  `created_at` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `branches`
--

INSERT INTO `branches` (`branch_id`, `installation_id`, `branch_name`, `sort_order`, `status`, `created_at`) VALUES
(1, 1, 'Abaita', 1, 'active', '2026-08-13 16:04:48'),
(2, 2, 'Namutumba1', 1, 'active', '2026-08-13 16:04:48'),
(3, 2, 'Kamuli 1', 0, 'active', '2026-08-13 16:04:48'),
(4, 2, 'Kajjansi', 1, 'active', '2026-08-13 16:04:48'),
(5, 3, 'Ndese', 0, 'active', '2026-08-13 16:04:48'),
(6, 3, 'Kanoni', 1, 'active', '2026-08-13 16:04:48'),
(7, 4, 'Kasanga', 0, 'active', '2026-08-13 16:04:48'),
(8, 4, 'Kabwohe', 1, 'active', '2026-08-13 16:04:48'),
(9, 5, 'Mengo', 0, 'active', '2026-08-13 16:04:48'),
(10, 6, 'Buwama1', 1, 'active', '2026-08-13 16:04:48'),
(11, 6, 'Kalungu', 0, 'active', '2026-08-13 16:04:48'),
(12, 8, 'Muyembe', 1, 'active', '2026-08-13 16:04:48'),
(13, 8, 'Bubulo', 1, 'active', '2026-08-13 16:04:48'),
(14, 9, 'bugema', 1, 'active', '2026-08-13 16:04:48'),
(15, 13, 'Kangulumila', 1, 'active', '2026-08-13 16:04:48'),
(16, 11, 'Wandegeya', 1, 'active', '2026-08-13 16:04:48'),
(17, 11, 'Bwoyogerere', 1, 'active', '2026-08-13 16:04:48'),
(18, 12, 'Hoima', 1, 'active', '2026-08-13 16:04:48'),
(19, 12, 'Kansera', 1, 'active', '2026-08-13 16:04:48'),
(20, 14, 'mafubira', 1, 'active', '2026-08-13 16:04:48');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `branches`
--
ALTER TABLE `branches`
  ADD PRIMARY KEY (`branch_id`),
  ADD KEY `installation_id` (`installation_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `branches`
--
ALTER TABLE `branches`
  MODIFY `branch_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `branches`
--
ALTER TABLE `branches`
  ADD CONSTRAINT `branches_ibfk_1` FOREIGN KEY (`installation_id`) REFERENCES `installations` (`installation_id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
