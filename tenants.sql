-- phpMyAdmin SQL Dump
-- version 5.1.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Aug 26, 2026 at 12:41 PM
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
-- Table structure for table `tenants`
--

CREATE TABLE `tenants` (
  `tenant_id` int(11) NOT NULL,
  `company_name` varchar(100) NOT NULL,
  `company_code` varchar(50) NOT NULL,
  `db_host` varchar(100) NOT NULL DEFAULT 'localhost',
  `db_user` varchar(100) NOT NULL,
  `db_pass` varchar(255) NOT NULL,
  `db_name` varchar(100) NOT NULL,
  `status` tinyint(1) DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tenants`
--

INSERT INTO `tenants` (`tenant_id`, `company_name`, `company_code`, `db_host`, `db_user`, `db_pass`, `db_name`, `status`, `created_at`) VALUES
(1, 'Lendsup', '01', 'localhost', 'quick7_seven', '0782869023abel', 'quick7_baita', 1, '2026-08-26 12:38:01'),

(2, 'Lendsup', '02', 'localhost', 'quick7_seven', '0782869023abel', 'quick7_kajasi', 1, '2026-08-26 12:38:01'),

(3, 'Lendsup', '03', 'localhost', 'quick5_five', '0782869023abel', 'quick5_lendsup', 1, '2026-08-26 12:38:01'),

(4, 'Lendsup', '04', 'localhost', 'quick8_eight', '0782869023abel', 'quick8_hobro', 1, '2026-08-26 12:38:01'),

(5, 'Lendsup', '05', 'localhost', 'quick2_two', '0782869023abel', 'quick2_musha', 1, '2026-08-26 12:38:01'),

(6, 'Lendsup', '06', 'localhost', 'quick2_two', '0782869023abel', 'quick2_leeway', 1, '2026-08-26 12:38:01'),

-- (7, 'Lendsup', '07', 'localhost', 'quick2_two', '0782869023abel', 'quick2_lendsup', 1, '2026-08-26 12:38:01'),


(8, 'Lendsup', '08', 'localhost', 'quick4_lendsup', '0782869023abel', 'quick4_four', 1, '2026-08-26 12:38:01'),

(9, 'Lendsup', '09', 'localhost', 'quick4_four', '0782869023abel', 'quick4_bugema', 1, '2026-08-26 12:38:01'),

(10, 'Lendsup', '10', 'localhost', 'quickacc_nine', '0782869023abel', 'quickacc_lendbs', 1, '2026-08-26 12:38:01'),

(11, 'Lendsup', '11', 'localhost', 'quickacc_nine', '0782869023abel', 'quickacc_9musha', 1, '2026-08-26 12:38:01'),

(12, 'Lendsup', '12', 'localhost', 'quick3_three', '0782869023abel', 'quick3_lendsup', 1, '2026-08-26 12:38:01'),

-- (13, 'Lendsup', '13', 'localhost', 'quickacc_nine', '0782869023abel', 'quickacc_9lends', 1, '2026-08-26 12:38:01'),


(14, 'Lendsup', '14', 'localhost', 'quick8_eight', '0782869023abel', 'quick8_lendsup', 1, '2026-08-26 12:38:01');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `tenants`
--
ALTER TABLE `tenants`
  ADD PRIMARY KEY (`tenant_id`),
  ADD UNIQUE KEY `company_code` (`company_code`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `tenants`
--
ALTER TABLE `tenants`
  MODIFY `tenant_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;


-- ALTER TABLE excess_short
--   ADD COLUMN IF NOT EXISTS officer_id BIGINT(22) NOT NULL DEFAULT 0 AFTER bossrec_id;

ALTER TABLE shortage
  ADD COLUMN IF NOT EXISTS officer_id BIGINT(22) NOT NULL DEFAULT 0 AFTER bossrec_id;

ALTER TABLE completed_loan
  ADD COLUMN IF NOT EXISTS intrests BIGINT(22) NOT NULL DEFAULT 20 AFTER completed;





SELECT
    e.ts_id                                 AS error_log_id,
    e.pay_date                              AS date_correction_was_logged,
    e.pre_amount                            AS old_loan_amount,
    e.amount                                AS new_loan_amount,
    fp.ts_id                                AS field_payment_id,
    fp.clientf_id                           AS client_id,
    CONCAT(c.firstname, ' ', c.lastname)    AS client_name,
    fp.pay_date                             AS field_payment_date_now,
    fp.amount                               AS field_payment_amount_now,
    l.loan_id                               AS edited_loan_id,
    l.b_date                                AS loan_date_after_edit,
    l.amount_given                          AS loan_amount,
    (l.amount_given * 1.2 / 30)             AS expected_daily_payment
FROM errors e
JOIN loans l
      ON l.cliente_id  = e.clients_id
     AND l.bossese_id  = e.boss_id
     AND l.amount_given = e.amount              -- loan now carries the "new" amount from the error log
JOIN field_payment fp
      ON fp.clientf_id = e.clients_id
     AND fp.boss_id    = e.boss_id
     AND fp.mom        = 0
     AND fp.amount     = e.amount              -- collection amount == loan amount (was overwritten)
LEFT JOIN clients c ON c.client_id = fp.clientf_id
WHERE e.error = 'Changing Loan Given'
  -- a genuine cash collection would almost never equal the full loan amount
  AND fp.amount <> ROUND(l.amount_given * 1.2 / 30)
ORDER BY e.clients_id, fp.pay_date;





INSERT INTO completed_loan 
    (loans_no, clientcpid, userscpid, bosscpid, pay_date, e_date, amount_given, completed, intrests)
SELECT 
    cwl.loan_no,
    cwl.clientsid,
    cwl.userseid,
    cwl.bosseseid,
    cwl.pay_date,                                    -- date loan was given
    DATE_ADD(cwl.pay_date, INTERVAL cwl.days DAY),   -- expected end date
    cwl.amount_given,
    1,                                               -- completed flag
    20                                               -- interest percentage
FROM clients_with_loan cwl
WHERE cwl.pay_date >= '2026-08-27'
  AND cwl.pay_date <= CURDATE()
  AND NOT EXISTS (
        SELECT 1 
        FROM completed_loan cl 
        WHERE cl.clientcpid = cwl.clientsid
          AND cl.loans_no   = cwl.loan_no
  )
ORDER BY cwl.pay_date ASC;

