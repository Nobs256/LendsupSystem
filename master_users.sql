-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Aug 26, 2026 at 05:38 PM
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
-- Database: `quick_master`
--

-- --------------------------------------------------------

--
-- Table structure for table `master_users`
--

CREATE TABLE `master_users` (
  `user_id` int(11) NOT NULL,
  `tenant_id` int(11) NOT NULL,
  `username` varchar(100) NOT NULL,
  `active` tinyint(1) DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `master_users`
--

INSERT INTO `master_users` (`user_id`, `tenant_id`, `username`, `active`) VALUES
-- abaita
(1, 1, 'lendsup', 1),

-- kajasi Namutumba1  Kamuli 1
(2, 2, ' babra', 1),
(3, 2, 'hope', 1),
-- (4, 2, 'kamuli', 1), duplicate

-- ndese and kanoni
(5, 3, 'phoebe', 1),
(6, 3, 'joan', 1),

-- Kasanga and kabwohe
(7, 4, 'arnold', 1),
(8, 4, 'nagasha', 1),

-- musha mengo
(9, 5, 'aidah', 1),

-- Buwama1 and Kalungu
(10, 6, 'leeway', 1),
(11, 6, 'lee', 1),

-- Muyembe and Bubulo
-- (12, 8, 'muyembe', 1), duplicate
(13, 8, 'bubulo', 1),

-- bugema
(14, 9, 'bugema', 1),

-- Busia
-- (15, 10, 'janet', 1), duplicate

-- Wandegeya and Bwoyogerere
(16, 11, 'xxxx', 1),
(17, 11, 'peace', 1),

-- Hoima and Kansera
(18, 12, 'adrine', 1),
(19, 12, 'lendsup2', 1);

-- mafubira
-- (20, 13, 'mafubira', 1); duplicate


--
-- Indexes for dumped tables
--

--
-- Indexes for table `master_users`
--
ALTER TABLE `master_users`
  ADD PRIMARY KEY (`user_id`),
  ADD UNIQUE KEY `username` (`username`),
  ADD KEY `tenant_id` (`tenant_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `master_users`
--
ALTER TABLE `master_users`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `master_users`
--
ALTER TABLE `master_users`
  ADD CONSTRAINT `master_users_ibfk_1` FOREIGN KEY (`tenant_id`) REFERENCES `tenants` (`tenant_id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
