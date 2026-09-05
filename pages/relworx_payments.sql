-- phpMyAdmin SQL Dump
-- version 5.1.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Jun 30, 2026 at 07:31 AM
-- Server version: 10.5.29-MariaDB
-- PHP Version: 7.4.33

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

-- --------------------------------------------------------

--
-- Table structure for table `relworx_payments`
--

CREATE TABLE `relworx_payments` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `boss_id` int(11) NOT NULL,
  `phone` varchar(20) NOT NULL,
  `amount` decimal(10,2) NOT NULL,
  `sms_units` int(11) NOT NULL,
  `reference` varchar(100) NOT NULL,
  `internal_reference` varchar(100) NOT NULL,
  `status` enum('pending','success','failed') DEFAULT 'pending',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `relworx_payments`
--
ALTER TABLE `relworx_payments`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `relworx_payments`
--
ALTER TABLE `relworx_payments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;




ALTER TABLE total_msg 
ADD COLUMN last_updated TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP AFTER total;


  	$systems = [

    'QA7T' => 'https://ky.quickaccount7.biz/Trust/pages/local_relworx_webhook.php',
    'QA7T2' => 'https://ky.quickaccount7.biz/Trust2/pages/local_relworx_webhook.php',
    'QA7T3' => 'https://ky.quickaccount7.biz/Trust3/pages/local_relworx_webhook.php',
  	'QA7TF' => 'https://ky.quickaccount7.biz/Frm/pages/local_relworx_webhook.php',
  	'QA7KV' => 'https://ky.quickaccount7.biz/KV/pages/local_relworx_webhook.php',
    'QA8J' => 'https://ky.quickaccount8.biz/Jacken/pages/local_relworx_webhook.php',
    'QA8C' => 'https://ky.quickaccount8.biz/Cash/pages/local_relworx_webhook.php',
    'QA8H' => 'https://ky.quickaccount8.biz/Hobro/pages/local_relworx_webhook.php',
    'QA8M' => 'https://ky.quickaccount8.biz/Maktrant/pages/local_relworx_webhook.php',
    'QA8S' => 'https://ky.quickaccount8.biz/Spot/pages/local_relworx_webhook.php',
  	'QA7N' => 'https://ky.quickaccount7.biz/Nyindo/pages/local_relworx_webhook.php',  
    'QA3B' => 'https://ky.quickaccount3.com/Bantunda/pages/local_relworx_webhook.php',
    'QA3C' => 'https://ky.quickaccount3.com/Cooper/pages/local_relworx_webhook.php',
  	'QA3L' => 'https://ky.quickaccount3.com/Lendsup/pages/local_relworx_webhook.php',
  	'QA3M' => 'https://ky.quickaccount3.com/Master/pages/local_relworx_webhook.php',
  	'QA3N' => 'https://ky.quickaccount3.com/NMK/pages/local_relworx_webhook.php',
  	'QA3P' => 'https://ky.quickaccount3.com/Peninah/pages/local_relworx_webhook.php',
  	'QA3Q' => 'https://ky.quickaccount3.com/Quick/pages/local_relworx_webhook.php',
	'QA3S' => 'https://ky.quickaccount3.com/Scom/pages/local_relworx_webhook.php',
  	'QA3' => 'https://ky.quickaccount3.com/pages/local_relworx_webhook.php',
  	'QAIA' => 'https://ky.quickaccounts.info/Aben/pages/local_relworx_webhook.php',
    'QAIB' => 'https://ky.quickaccounts.info/Butera/pages/local_relworx_webhook.php',
    'QAIC' => 'https://ky.quickaccounts.info/Coleb/pages/local_relworx_webhook.php',
    'QAIH' => 'https://ky.quickaccounts.info/Home/pages/local_relworx_webhook.php',
  
  	'QA6B' => 'https://ky.quickaccount6.biz/Bonsoir/pages/local_relworx_webhook.php',
  	'QA6T' => 'https://ky.quickaccount6.biz/Tang/pages/local_relworx_webhook.php',
  	'QA6E' => 'https://ky.quickaccount6.biz/Ezera/pages/local_relworx_webhook.php',
  
  	'QA5ANB' => 'https://ky.quickaccount5.biz/ABN/pages/local_relworx_webhook.php',
  	'QA5K' => 'https://ky.quickaccount5.biz/Kasima/pages/local_relworx_webhook.php',
  	'QA5AM' => 'https://ky.quickaccount5.biz/Aminate/pages/local_relworx_webhook.php',
  	'QA5B' => 'https://ky.quickaccount5.biz/Bilin/pages/local_relworx_webhook.php',
  	'QA5KY' => 'https://ky.quickaccount5.biz/KY/pages/local_relworx_webhook.php',
  
  
  	'QA4C' => 'https://ky.quickaccount4.com/Capital3/pages/local_relworx_webhook.php',
  	'QA4G' => 'https://ky.quickaccount4.com/Gosi/pages/local_relworx_webhook.php',
  	'QA4L' => 'https://ky.quickaccount4.com/Limak/pages/local_relworx_webhook.php',
  	'QA4S' => 'https://ky.quickaccount4.com/Samat/pages/local_relworx_webhook.php',
  
  	'QA9J2' => 'https://nine.quickaccount2.com/Jacken2/pages/local_relworx_webhook.php',
  	'QA9J3' => 'https://nine.quickaccount2.com/Jacken3/pages/local_relworx_webhook.php',
  	'QA9LH' => 'https://nine.quickaccount2.com/Lends_Hoima/pages/local_relworx_webhook.php',
  	'QA9M' => 'https://nine.quickaccount2.com/Musha9/pages/local_relworx_webhook.php',
  	'QA9P' => 'https://nine.quickaccount2.com/Pepe/pages/local_relworx_webhook.php',
  	'QA9S' => 'https://nine.quickaccount2.com/Spark/pages/local_relworx_webhook.php',
  	'QA9TOM' => 'https://nine.quickaccount2.com/Tom/pages/local_relworx_webhook.php',
  	'QA9T' => 'https://nine.quickaccount2.com/Trust/pages/local_relworx_webhook.php',
  	'QA9' => 'https://nine.quickaccount2.com/pages/local_relworx_webhook.php',
  
  	'QA2K' => 'https://ky.quickaccount2.biz/Karara/pages/local_relworx_webhook.php',
  	'QA2BEN' => 'https://ky.quickaccount2.biz/Ben/pages/local_relworx_webhook.php',
  	'QA2B' => 'https://ky.quickaccount2.biz/Bilin2/pages/local_relworx_webhook.php',
  	'QA2F' => 'https://ky.quickaccount2.biz/Fred/pages/local_relworx_webhook.php',
  	'QA2KV' => 'https://ky.quickaccount2.biz/KV/pages/local_relworx_webhook.php',
  	'QA2KAM' => 'https://ky.quickaccount2.biz/Kam/pages/local_relworx_webhook.php',
  
  	'QA10' => 'https://ky.quickaccount10.biz/pages/local_relworx_withdraw_webhook.php'
];










