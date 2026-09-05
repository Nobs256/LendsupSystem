-- phpMyAdmin SQL Dump
-- version 5.1.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Aug 28, 2026 at 05:29 AM
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
-- Database: `quick7_baita`
--

-- --------------------------------------------------------

--
-- Table structure for table `balance_change_log`
--

CREATE TABLE `balance_change_log` (
  `log_id` bigint(22) NOT NULL,
  `loan_no` bigint(22) NOT NULL,
  `client_id` bigint(22) NOT NULL,
  `user_id` bigint(22) NOT NULL,
  `boss_id` bigint(22) NOT NULL,
  `previous_balance` bigint(22) NOT NULL,
  `new_balance` bigint(22) NOT NULL,
  `action_type` varchar(50) NOT NULL,
  `action_timestamp` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `banking`
--

CREATE TABLE `banking` (
  `deposit_id` bigint(22) NOT NULL,
  `userde_id` bigint(22) NOT NULL,
  `bossde_id` bigint(22) NOT NULL,
  `transc` varchar(22) NOT NULL,
  `bank_name` varchar(22) NOT NULL,
  `de_date` date NOT NULL,
  `de_amount` bigint(22) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `banks`
--

CREATE TABLE `banks` (
  `ts_id` bigint(22) NOT NULL,
  `boss_id` bigint(22) NOT NULL,
  `bank_name` varchar(22) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `bosses`
--

CREATE TABLE `bosses` (
  `boss_id` bigint(22) NOT NULL,
  `firstname` varchar(50) NOT NULL,
  `lastname` varchar(20) NOT NULL,
  `nid` varchar(20) NOT NULL,
  `phone` varchar(20) NOT NULL,
  `place_o` varchar(20) NOT NULL,
  `place_r` varchar(20) NOT NULL,
  `dob` varchar(5) NOT NULL,
  `users_image` text NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `boss_number`
--

CREATE TABLE `boss_number` (
  `ts_id` bigint(22) NOT NULL,
  `boss_id` bigint(22) NOT NULL,
  `phone` varchar(22) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `bulkymessages`
--

CREATE TABLE `bulkymessages` (
  `msg_id` bigint(22) NOT NULL,
  `phone` varchar(20) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `cash`
--

CREATE TABLE `cash` (
  `ts_id` bigint(22) NOT NULL,
  `user_id` bigint(22) NOT NULL,
  `boss_id` bigint(22) NOT NULL,
  `pay_date` date NOT NULL,
  `amount` bigint(10) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `charges`
--

CREATE TABLE `charges` (
  `charges_id` bigint(22) NOT NULL,
  `clientch_id` bigint(22) NOT NULL,
  `userch_id` bigint(22) NOT NULL,
  `bossch_id` bigint(22) NOT NULL,
  `amount_ch` bigint(10) NOT NULL,
  `ch_date` date NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `cleared_loans`
--

CREATE TABLE `cleared_loans` (
  `loan_id` bigint(22) NOT NULL,
  `loan_no` bigint(22) NOT NULL,
  `clientc_id` bigint(22) NOT NULL,
  `user_id` bigint(22) NOT NULL,
  `boss_id` bigint(22) NOT NULL,
  `p_date` date NOT NULL,
  `balance` varchar(20) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `clients`
--

CREATE TABLE `clients` (
  `client_id` bigint(22) NOT NULL,
  `users_id` bigint(22) NOT NULL,
  `bosses_id` bigint(22) NOT NULL,
  `firstname` varchar(20) NOT NULL,
  `lastname` varchar(20) NOT NULL,
  `sex` varchar(20) NOT NULL,
  `dob` varchar(6) NOT NULL,
  `marital` varchar(10) NOT NULL,
  `nid` varchar(25) NOT NULL,
  `phone` varchar(20) NOT NULL,
  `place_r` varchar(20) NOT NULL,
  `business` varchar(30) NOT NULL,
  `b_location` varchar(30) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `clients_not_paid`
--

CREATE TABLE `clients_not_paid` (
  `ts_id` bigint(22) NOT NULL,
  `client_id` bigint(22) NOT NULL,
  `user_id` bigint(22) NOT NULL,
  `boss_id` bigint(22) NOT NULL,
  `names` varchar(100) NOT NULL,
  `phone` varchar(20) NOT NULL,
  `amount_given` bigint(10) NOT NULL,
  `date_given` date NOT NULL,
  `last_date_paid` date NOT NULL,
  `last_amount` bigint(10) NOT NULL,
  `balance` bigint(10) NOT NULL,
  `days_missed` bigint(10) NOT NULL,
  `arears` int(10) NOT NULL,
  `total_days` int(4) NOT NULL,
  `ids` varchar(20) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `clients_with_fines`
--

CREATE TABLE `clients_with_fines` (
  `ts_id` bigint(22) NOT NULL,
  `user_id` bigint(22) NOT NULL,
  `boss_id` bigint(22) NOT NULL,
  `clients_id` bigint(22) NOT NULL,
  `loan_id` bigint(22) NOT NULL,
  `pay_date` date NOT NULL,
  `amount` bigint(10) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `clients_with_loan`
--

CREATE TABLE `clients_with_loan` (
  `loan_id` bigint(22) NOT NULL,
  `loan_no` bigint(22) NOT NULL,
  `clientsid` bigint(22) NOT NULL,
  `userseid` bigint(22) NOT NULL,
  `bosseseid` bigint(22) NOT NULL,
  `pay_date` date NOT NULL,
  `amount_given` bigint(22) NOT NULL,
  `daily_p` bigint(22) NOT NULL,
  `debt` bigint(22) NOT NULL,
  `interest` bigint(12) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `completed_loan`
--

CREATE TABLE `completed_loan` (
  `loan_id` bigint(22) NOT NULL,
  `loans_no` bigint(22) NOT NULL,
  `clientcpid` bigint(22) NOT NULL,
  `userscpid` bigint(22) NOT NULL,
  `bosscpid` bigint(22) NOT NULL,
  `pay_date` date NOT NULL,
  `e_date` date NOT NULL,
  `amount_given` bigint(22) NOT NULL,
  `completed` bigint(2) NOT NULL,
  `intrests` int(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `cr`
--

CREATE TABLE `cr` (
  `cr_id` bigint(22) NOT NULL,
  `usercr_id` bigint(22) NOT NULL,
  `bosscr_id` bigint(22) NOT NULL,
  `cr_date` date NOT NULL,
  `branch` varchar(20) NOT NULL,
  `cr_amount` bigint(22) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `daily_report`
--

CREATE TABLE `daily_report` (
  `loan_id` bigint(22) NOT NULL,
  `cliente_id` bigint(22) NOT NULL,
  `usersed_id` bigint(22) NOT NULL,
  `bossesed_id` bigint(22) NOT NULL,
  `b_date` date NOT NULL,
  `arrears` bigint(22) NOT NULL,
  `days_missed` varchar(12) NOT NULL,
  `location` varchar(50) NOT NULL,
  `advance` varchar(4) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `demand`
--

CREATE TABLE `demand` (
  `demand_id` bigint(22) NOT NULL,
  `clientde_id` bigint(22) NOT NULL,
  `userde_id` bigint(22) NOT NULL,
  `bossde_id` bigint(22) NOT NULL,
  `amount` bigint(10) NOT NULL,
  `demand_date` date NOT NULL,
  `given` bigint(2) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `errors`
--

CREATE TABLE `errors` (
  `ts_id` bigint(22) NOT NULL,
  `user_id` bigint(22) NOT NULL,
  `boss_id` bigint(22) NOT NULL,
  `clients_id` bigint(22) NOT NULL,
  `error` varchar(20) NOT NULL,
  `pay_date` date NOT NULL,
  `pre_amount` bigint(10) NOT NULL,
  `amount` bigint(10) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `excess_short`
--

CREATE TABLE `excess_short` (
  `excess_id` bigint(22) NOT NULL,
  `userrec_id` bigint(22) NOT NULL,
  `bossrec_id` bigint(10) NOT NULL,
  `officer_id` bigint(22) NOT NULL DEFAULT 0,
  `rec_date` date NOT NULL,
  `excess_short` varchar(15) NOT NULL,
  `paid_amount` bigint(10) NOT NULL,
  `withdrawn` bigint(4) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `expenses`
--

CREATE TABLE `expenses` (
  `exp_id` bigint(22) NOT NULL,
  `userexp_id` bigint(22) NOT NULL,
  `bossexp_id` bigint(22) NOT NULL,
  `exp_date` date NOT NULL,
  `item` varchar(40) NOT NULL,
  `cost` bigint(22) NOT NULL,
  `naration` varchar(60) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `expenses_list`
--

CREATE TABLE `expenses_list` (
  `exp_id` bigint(22) NOT NULL,
  `userexp_id` bigint(22) NOT NULL,
  `bossexp_id` bigint(22) NOT NULL,
  `item` varchar(40) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `field_payment`
--

CREATE TABLE `field_payment` (
  `ts_id` bigint(22) NOT NULL,
  `user_id` bigint(22) NOT NULL,
  `boss_id` bigint(22) NOT NULL,
  `clientf_id` bigint(22) NOT NULL,
  `pay_date` date NOT NULL,
  `officerid` bigint(10) NOT NULL,
  `amount` bigint(10) NOT NULL,
  `mom` bigint(4) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `general_report`
--

CREATE TABLE `general_report` (
  `op_id` bigint(22) NOT NULL,
  `userop_id` bigint(22) NOT NULL,
  `bossop_id` bigint(22) NOT NULL,
  `op_date` date NOT NULL,
  `op_amount` bigint(22) NOT NULL,
  `loan_given` bigint(22) NOT NULL,
  `loan_paid` bigint(22) NOT NULL,
  `reg_fee` bigint(22) NOT NULL,
  `other_cash_in` bigint(22) NOT NULL,
  `expenses` bigint(22) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `guarantor`
--

CREATE TABLE `guarantor` (
  `guarantor_id` bigint(22) NOT NULL,
  `clientg_id` bigint(22) NOT NULL,
  `usersg_id` bigint(22) NOT NULL,
  `bossesg_id` bigint(22) NOT NULL,
  `g_date` date NOT NULL,
  `g_names1` varchar(30) NOT NULL,
  `g_phone1` varchar(12) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `idTake`
--

CREATE TABLE `idTake` (
  `idTake_id` bigint(22) NOT NULL,
  `clientde_id` bigint(22) NOT NULL,
  `userde_id` bigint(22) NOT NULL,
  `bossde_id` bigint(22) NOT NULL,
  `take_date` date NOT NULL,
  `return_date` date NOT NULL,
  `take` bigint(4) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `idTake_history`
--

CREATE TABLE `idTake_history` (
  `idTake_id` bigint(22) NOT NULL,
  `clientde_id` bigint(22) NOT NULL,
  `userde_id` bigint(22) NOT NULL,
  `bossde_id` bigint(22) NOT NULL,
  `take_date` date NOT NULL,
  `return_date` date NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `loans`
--

CREATE TABLE `loans` (
  `loan_id` bigint(22) NOT NULL,
  `cliente_id` bigint(22) NOT NULL,
  `userse_id` bigint(22) NOT NULL,
  `bossese_id` bigint(22) NOT NULL,
  `b_date` date NOT NULL,
  `amount_given` bigint(22) NOT NULL,
  `security` varchar(50) NOT NULL,
  `nid_client` varchar(20) NOT NULL,
  `reg_fee` bigint(22) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `loans_in_parts`
--

CREATE TABLE `loans_in_parts` (
  `loan_id` bigint(22) NOT NULL,
  `loan_no` int(4) NOT NULL,
  `clientp_id` bigint(22) NOT NULL,
  `usersp_id` bigint(22) NOT NULL,
  `bossesp_id` bigint(22) NOT NULL,
  `bp_date` date NOT NULL,
  `amount_g` bigint(22) NOT NULL,
  `part` varchar(20) NOT NULL,
  `reg_fee` varchar(5) NOT NULL,
  `difference` varchar(22) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `loan_fines`
--

CREATE TABLE `loan_fines` (
  `ts_id` bigint(22) NOT NULL,
  `user_id` bigint(22) NOT NULL,
  `boss_id` bigint(22) NOT NULL,
  `clients_id` bigint(22) NOT NULL,
  `loan_id` bigint(22) NOT NULL,
  `pay_date` date NOT NULL,
  `amount` bigint(10) NOT NULL,
  `fine_type` varchar(20) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `loan_pay`
--

CREATE TABLE `loan_pay` (
  `loan_id` bigint(22) NOT NULL,
  `loanNo` bigint(22) NOT NULL,
  `clients_id` bigint(22) NOT NULL,
  `userse_id` bigint(22) NOT NULL,
  `bossese_id` bigint(22) NOT NULL,
  `p_date` date NOT NULL,
  `amount_paid` bigint(22) NOT NULL,
  `balance` bigint(22) NOT NULL,
  `mom` bigint(4) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `loan_pay_daily`
--

CREATE TABLE `loan_pay_daily` (
  `loan_id` bigint(22) NOT NULL,
  `loanNo` bigint(22) NOT NULL,
  `clients_id` bigint(22) NOT NULL,
  `userse_id` bigint(22) NOT NULL,
  `bossese_id` bigint(22) NOT NULL,
  `p_date` date NOT NULL,
  `amount_paid` bigint(22) NOT NULL,
  `balance` bigint(22) NOT NULL,
  `mom` bigint(4) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `loan_returned`
--

CREATE TABLE `loan_returned` (
  `id` bigint(22) NOT NULL,
  `client_id` bigint(22) NOT NULL,
  `user_id` bigint(22) NOT NULL,
  `boss_id` bigint(22) NOT NULL,
  `date` date NOT NULL,
  `amount_returned` bigint(22) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `location`
--

CREATE TABLE `location` (
  `location_id` bigint(22) NOT NULL,
  `userloc_id` bigint(22) NOT NULL,
  `bossloc_id` bigint(22) NOT NULL,
  `location` varchar(20) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `mobile`
--

CREATE TABLE `mobile` (
  `mobile_id` bigint(22) NOT NULL,
  `clientmo_id` bigint(22) NOT NULL,
  `usermo_id` bigint(22) NOT NULL,
  `bossmo_id` bigint(22) NOT NULL,
  `amount_mo` bigint(10) NOT NULL,
  `mom_date` date NOT NULL,
  `phone_sent` varchar(22) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `mobile_numbers`
--

CREATE TABLE `mobile_numbers` (
  `mobile_id` bigint(22) NOT NULL,
  `usermo_id` bigint(22) NOT NULL,
  `bossmo_id` bigint(22) NOT NULL,
  `phone` varchar(11) NOT NULL,
  `mom_date` date NOT NULL,
  `names` varchar(90) NOT NULL,
  `amount_mo` bigint(10) NOT NULL,
  `withdraw` bigint(10) NOT NULL,
  `balance` bigint(10) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `mom_balance`
--

CREATE TABLE `mom_balance` (
  `mom_id` bigint(22) NOT NULL,
  `userch_id` bigint(22) NOT NULL,
  `bossch_id` bigint(22) NOT NULL,
  `amount` bigint(10) NOT NULL,
  `ch_date` date NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `mom_phones`
--

CREATE TABLE `mom_phones` (
  `ts_id` bigint(22) NOT NULL,
  `user_id` bigint(22) NOT NULL,
  `boss_id` bigint(22) NOT NULL,
  `phone` varchar(22) NOT NULL,
  `company` varchar(22) NOT NULL,
  `active` bigint(2) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `new_users`
--

CREATE TABLE `new_users` (
  `user_id` bigint(22) NOT NULL,
  `boss_id` bigint(22) NOT NULL,
  `firstname` varchar(20) NOT NULL,
  `lastname` varchar(20) NOT NULL,
  `sex` varchar(5) NOT NULL,
  `marital` varchar(10) NOT NULL,
  `phone` varchar(20) NOT NULL,
  `nid` varchar(20) NOT NULL,
  `branch` varchar(20) NOT NULL,
  `place_o` varchar(20) NOT NULL,
  `place_r` varchar(20) NOT NULL,
  `dob` varchar(20) NOT NULL,
  `username` varchar(40) NOT NULL,
  `category` varchar(20) NOT NULL,
  `password` varchar(20) NOT NULL,
  `users_image` text NOT NULL,
  `active` varchar(5) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `officers`
--

CREATE TABLE `officers` (
  `officer_id` bigint(22) NOT NULL,
  `boss_id` bigint(22) NOT NULL,
  `firstname` varchar(20) NOT NULL,
  `lastname` varchar(20) NOT NULL,
  `phone` varchar(20) NOT NULL,
  `nid` varchar(20) NOT NULL,
  `branch` varchar(20) NOT NULL,
  `location` varchar(20) NOT NULL,
  `active` varchar(5) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `op`
--

CREATE TABLE `op` (
  `op_id` bigint(22) NOT NULL,
  `userop_id` bigint(22) NOT NULL,
  `bossop_id` bigint(22) NOT NULL,
  `op_date` date NOT NULL,
  `op_amount` bigint(22) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `op_mom`
--

CREATE TABLE `op_mom` (
  `op_id` bigint(22) NOT NULL,
  `userop_id` bigint(22) NOT NULL,
  `bossop_id` bigint(22) NOT NULL,
  `op_date` date NOT NULL,
  `op_amount` bigint(22) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `payments`
--

CREATE TABLE `payments` (
  `pay_id` bigint(22) NOT NULL,
  `user_id` bigint(22) NOT NULL,
  `boss_id` bigint(22) NOT NULL,
  `amount` bigint(10) NOT NULL,
  `pay_date` date NOT NULL,
  `paid` bigint(10) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `portifolios`
--

CREATE TABLE `portifolios` (
  `ts_id` bigint(22) NOT NULL,
  `user_id` bigint(22) NOT NULL,
  `boss_id` bigint(22) NOT NULL,
  `clientf_id` bigint(22) NOT NULL,
  `date_curr` date NOT NULL,
  `names` varchar(60) NOT NULL,
  `phone` varchar(20) NOT NULL,
  `date_given` date NOT NULL,
  `due_date` date NOT NULL,
  `amount_given` bigint(10) NOT NULL,
  `interest` bigint(10) NOT NULL,
  `balance` bigint(10) NOT NULL,
  `arreas` bigint(10) NOT NULL,
  `days_missed` bigint(10) NOT NULL,
  `loan_no` int(10) NOT NULL,
  `phoneg` varchar(11) NOT NULL,
  `gname` varchar(50) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `profit`
--

CREATE TABLE `profit` (
  `profit_id` bigint(22) NOT NULL,
  `userch_id` bigint(22) NOT NULL,
  `bossch_id` bigint(22) NOT NULL,
  `amount_ch` bigint(10) NOT NULL,
  `ch_date` date NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `receipts`
--

CREATE TABLE `receipts` (
  `receipt_id` bigint(22) NOT NULL,
  `userrec_id` bigint(22) NOT NULL,
  `bossrec_id` bigint(10) NOT NULL,
  `clientrec_id` bigint(22) NOT NULL,
  `loan_norec_id` bigint(22) NOT NULL,
  `rec_date` date NOT NULL,
  `paid_amount` bigint(10) NOT NULL,
  `balancerec` bigint(10) NOT NULL,
  `printed` bigint(2) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `savings`
--

CREATE TABLE `savings` (
  `save_id` bigint(22) NOT NULL,
  `clientsave_id` bigint(22) NOT NULL,
  `user_id` bigint(22) NOT NULL,
  `boss_id` bigint(22) NOT NULL,
  `save_date` date NOT NULL,
  `amount` bigint(22) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `sent_msgs`
--

CREATE TABLE `sent_msgs` (
  `ts_id` bigint(22) NOT NULL,
  `user_id` bigint(22) NOT NULL,
  `boss_id` bigint(22) NOT NULL,
  `msg_date` date NOT NULL,
  `msg` text NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `sent_to_branch`
--

CREATE TABLE `sent_to_branch` (
  `cr_id` bigint(22) NOT NULL,
  `usercr_id` bigint(22) NOT NULL,
  `bosscr_id` bigint(22) NOT NULL,
  `cr_date` date NOT NULL,
  `branch` varchar(20) NOT NULL,
  `cr_amount` bigint(22) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `shortage`
--

CREATE TABLE `shortage` (
  `short_id` bigint(22) NOT NULL,
  `userrec_id` bigint(22) NOT NULL,
  `bossrec_id` bigint(10) NOT NULL,
  `officer_id` bigint(22) NOT NULL DEFAULT 0,
  `rec_date` date NOT NULL,
  `paid_amount` bigint(10) NOT NULL,
  `recovered` bigint(4) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `sms_add`
--

CREATE TABLE `sms_add` (
  `sms_id` bigint(22) NOT NULL,
  `user_id` bigint(22) NOT NULL,
  `boss_id` bigint(22) NOT NULL,
  `sms_date` date NOT NULL,
  `sms_amount` varchar(20) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `sms_queue`
--

CREATE TABLE `sms_queue` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `boss_id` int(11) DEFAULT NULL,
  `phone_number` varchar(20) NOT NULL,
  `message` text NOT NULL,
  `status` enum('pending','sent','failed','invalid') DEFAULT 'pending',
  `response` text DEFAULT NULL,
  `attempts` int(11) DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `sent_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `total_banking`
--

CREATE TABLE `total_banking` (
  `ts_id` bigint(22) NOT NULL,
  `user_id` bigint(22) NOT NULL,
  `boss_id` bigint(22) NOT NULL,
  `bank_name` varchar(22) NOT NULL,
  `total` bigint(22) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `total_mom`
--

CREATE TABLE `total_mom` (
  `ts_id` bigint(22) NOT NULL,
  `phone` varchar(22) NOT NULL,
  `user_id` bigint(22) NOT NULL,
  `boss_id` bigint(22) NOT NULL,
  `total` bigint(22) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `total_msg`
--

CREATE TABLE `total_msg` (
  `msg_id` bigint(22) NOT NULL,
  `user_id` bigint(22) NOT NULL,
  `boss_id` bigint(22) NOT NULL,
  `total` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `total_savings`
--

CREATE TABLE `total_savings` (
  `ts_id` bigint(22) NOT NULL,
  `clientts_id` varchar(22) NOT NULL,
  `user_id` bigint(22) NOT NULL,
  `boss_id` bigint(22) NOT NULL,
  `total` bigint(22) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `transcations`
--

CREATE TABLE `transcations` (
  `ts_id` bigint(22) NOT NULL,
  `user_id` bigint(22) NOT NULL,
  `boss_id` bigint(22) NOT NULL,
  `clientr_id` bigint(22) NOT NULL,
  `transc_date` date NOT NULL,
  `transc_name` varchar(60) NOT NULL,
  `transc_type` varchar(20) NOT NULL,
  `transc_amount` bigint(10) NOT NULL,
  `reg_fee` bigint(10) NOT NULL,
  `mom` bigint(4) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `trash`
--

CREATE TABLE `trash` (
  `ts_id` bigint(22) NOT NULL,
  `user_id` bigint(22) NOT NULL,
  `boss_id` bigint(22) NOT NULL,
  `pay_date` date NOT NULL,
  `amount` bigint(10) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `uknown`
--

CREATE TABLE `uknown` (
  `uknown_id` bigint(22) NOT NULL,
  `userrec_id` bigint(22) NOT NULL,
  `bossrec_id` bigint(10) NOT NULL,
  `rec_date` date NOT NULL,
  `tel` varchar(15) NOT NULL,
  `paid_amount` bigint(10) NOT NULL,
  `known` bigint(2) NOT NULL,
  `known_date` date NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `unknown_cash`
--

CREATE TABLE `unknown_cash` (
  `ts_id` bigint(22) NOT NULL,
  `user_id` bigint(22) NOT NULL,
  `boss_id` bigint(22) NOT NULL,
  `unknown_date` date NOT NULL,
  `amount` bigint(22) NOT NULL,
  `officer` int(6) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `withdraw`
--

CREATE TABLE `withdraw` (
  `with_id` bigint(22) NOT NULL,
  `clientwith_id` bigint(22) NOT NULL,
  `user_id` bigint(22) NOT NULL,
  `boss_id` bigint(22) NOT NULL,
  `with_date` date NOT NULL,
  `amount` bigint(22) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `withdraws`
--

CREATE TABLE `withdraws` (
  `with_id` bigint(22) NOT NULL,
  `withdraws` varchar(22) NOT NULL,
  `user_id` bigint(22) NOT NULL,
  `boss_id` bigint(22) NOT NULL,
  `with_date` date NOT NULL,
  `amount` bigint(22) NOT NULL,
  `phone` varchar(22) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `withdraw_unknown_cash`
--

CREATE TABLE `withdraw_unknown_cash` (
  `ts_id` bigint(22) NOT NULL,
  `user_id` bigint(22) NOT NULL,
  `boss_id` bigint(22) NOT NULL,
  `unknown_date` date NOT NULL,
  `amount` bigint(22) NOT NULL,
  `officer` int(6) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `balance_change_log`
--
ALTER TABLE `balance_change_log`
  ADD PRIMARY KEY (`log_id`);

--
-- Indexes for table `banking`
--
ALTER TABLE `banking`
  ADD PRIMARY KEY (`deposit_id`);

--
-- Indexes for table `banks`
--
ALTER TABLE `banks`
  ADD PRIMARY KEY (`ts_id`);

--
-- Indexes for table `bosses`
--
ALTER TABLE `bosses`
  ADD PRIMARY KEY (`boss_id`);

--
-- Indexes for table `boss_number`
--
ALTER TABLE `boss_number`
  ADD PRIMARY KEY (`ts_id`);

--
-- Indexes for table `bulkymessages`
--
ALTER TABLE `bulkymessages`
  ADD PRIMARY KEY (`msg_id`);

--
-- Indexes for table `cash`
--
ALTER TABLE `cash`
  ADD PRIMARY KEY (`ts_id`);

--
-- Indexes for table `charges`
--
ALTER TABLE `charges`
  ADD PRIMARY KEY (`charges_id`);

--
-- Indexes for table `cleared_loans`
--
ALTER TABLE `cleared_loans`
  ADD PRIMARY KEY (`loan_id`);

--
-- Indexes for table `clients`
--
ALTER TABLE `clients`
  ADD PRIMARY KEY (`client_id`);

--
-- Indexes for table `clients_not_paid`
--
ALTER TABLE `clients_not_paid`
  ADD PRIMARY KEY (`ts_id`);

--
-- Indexes for table `clients_with_fines`
--
ALTER TABLE `clients_with_fines`
  ADD PRIMARY KEY (`ts_id`);

--
-- Indexes for table `clients_with_loan`
--
ALTER TABLE `clients_with_loan`
  ADD PRIMARY KEY (`loan_id`);

--
-- Indexes for table `completed_loan`
--
ALTER TABLE `completed_loan`
  ADD PRIMARY KEY (`loan_id`);

--
-- Indexes for table `cr`
--
ALTER TABLE `cr`
  ADD PRIMARY KEY (`cr_id`);

--
-- Indexes for table `daily_report`
--
ALTER TABLE `daily_report`
  ADD PRIMARY KEY (`loan_id`);

--
-- Indexes for table `demand`
--
ALTER TABLE `demand`
  ADD PRIMARY KEY (`demand_id`);

--
-- Indexes for table `errors`
--
ALTER TABLE `errors`
  ADD PRIMARY KEY (`ts_id`);

--
-- Indexes for table `excess_short`
--
ALTER TABLE `excess_short`
  ADD PRIMARY KEY (`excess_id`);

--
-- Indexes for table `expenses`
--
ALTER TABLE `expenses`
  ADD PRIMARY KEY (`exp_id`);

--
-- Indexes for table `expenses_list`
--
ALTER TABLE `expenses_list`
  ADD PRIMARY KEY (`exp_id`);

--
-- Indexes for table `field_payment`
--
ALTER TABLE `field_payment`
  ADD PRIMARY KEY (`ts_id`);

--
-- Indexes for table `general_report`
--
ALTER TABLE `general_report`
  ADD PRIMARY KEY (`op_id`);

--
-- Indexes for table `guarantor`
--
ALTER TABLE `guarantor`
  ADD PRIMARY KEY (`guarantor_id`);

--
-- Indexes for table `idTake`
--
ALTER TABLE `idTake`
  ADD PRIMARY KEY (`idTake_id`);

--
-- Indexes for table `idTake_history`
--
ALTER TABLE `idTake_history`
  ADD PRIMARY KEY (`idTake_id`);

--
-- Indexes for table `loans`
--
ALTER TABLE `loans`
  ADD PRIMARY KEY (`loan_id`);

--
-- Indexes for table `loans_in_parts`
--
ALTER TABLE `loans_in_parts`
  ADD PRIMARY KEY (`loan_id`);

--
-- Indexes for table `loan_fines`
--
ALTER TABLE `loan_fines`
  ADD PRIMARY KEY (`ts_id`);

--
-- Indexes for table `loan_pay`
--
ALTER TABLE `loan_pay`
  ADD PRIMARY KEY (`loan_id`);

--
-- Indexes for table `loan_pay_daily`
--
ALTER TABLE `loan_pay_daily`
  ADD PRIMARY KEY (`loan_id`);

--
-- Indexes for table `loan_returned`
--
ALTER TABLE `loan_returned`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `location`
--
ALTER TABLE `location`
  ADD PRIMARY KEY (`location_id`);

--
-- Indexes for table `mobile`
--
ALTER TABLE `mobile`
  ADD PRIMARY KEY (`mobile_id`);

--
-- Indexes for table `mobile_numbers`
--
ALTER TABLE `mobile_numbers`
  ADD PRIMARY KEY (`mobile_id`);

--
-- Indexes for table `mom_balance`
--
ALTER TABLE `mom_balance`
  ADD PRIMARY KEY (`mom_id`);

--
-- Indexes for table `mom_phones`
--
ALTER TABLE `mom_phones`
  ADD PRIMARY KEY (`ts_id`);

--
-- Indexes for table `new_users`
--
ALTER TABLE `new_users`
  ADD PRIMARY KEY (`user_id`);

--
-- Indexes for table `officers`
--
ALTER TABLE `officers`
  ADD PRIMARY KEY (`officer_id`);

--
-- Indexes for table `op`
--
ALTER TABLE `op`
  ADD PRIMARY KEY (`op_id`);

--
-- Indexes for table `op_mom`
--
ALTER TABLE `op_mom`
  ADD PRIMARY KEY (`op_id`);

--
-- Indexes for table `payments`
--
ALTER TABLE `payments`
  ADD PRIMARY KEY (`pay_id`);

--
-- Indexes for table `portifolios`
--
ALTER TABLE `portifolios`
  ADD PRIMARY KEY (`ts_id`);

--
-- Indexes for table `profit`
--
ALTER TABLE `profit`
  ADD PRIMARY KEY (`profit_id`);

--
-- Indexes for table `receipts`
--
ALTER TABLE `receipts`
  ADD PRIMARY KEY (`receipt_id`);

--
-- Indexes for table `savings`
--
ALTER TABLE `savings`
  ADD PRIMARY KEY (`save_id`);

--
-- Indexes for table `sent_msgs`
--
ALTER TABLE `sent_msgs`
  ADD PRIMARY KEY (`ts_id`);

--
-- Indexes for table `sent_to_branch`
--
ALTER TABLE `sent_to_branch`
  ADD PRIMARY KEY (`cr_id`);

--
-- Indexes for table `shortage`
--
ALTER TABLE `shortage`
  ADD PRIMARY KEY (`short_id`);

--
-- Indexes for table `sms_add`
--
ALTER TABLE `sms_add`
  ADD PRIMARY KEY (`sms_id`);

--
-- Indexes for table `sms_queue`
--
ALTER TABLE `sms_queue`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `total_banking`
--
ALTER TABLE `total_banking`
  ADD PRIMARY KEY (`ts_id`);

--
-- Indexes for table `total_mom`
--
ALTER TABLE `total_mom`
  ADD PRIMARY KEY (`ts_id`);

--
-- Indexes for table `total_msg`
--
ALTER TABLE `total_msg`
  ADD PRIMARY KEY (`msg_id`);

--
-- Indexes for table `total_savings`
--
ALTER TABLE `total_savings`
  ADD PRIMARY KEY (`ts_id`);

--
-- Indexes for table `transcations`
--
ALTER TABLE `transcations`
  ADD PRIMARY KEY (`ts_id`);

--
-- Indexes for table `trash`
--
ALTER TABLE `trash`
  ADD PRIMARY KEY (`ts_id`);

--
-- Indexes for table `uknown`
--
ALTER TABLE `uknown`
  ADD PRIMARY KEY (`uknown_id`);

--
-- Indexes for table `unknown_cash`
--
ALTER TABLE `unknown_cash`
  ADD PRIMARY KEY (`ts_id`);

--
-- Indexes for table `withdraw`
--
ALTER TABLE `withdraw`
  ADD PRIMARY KEY (`with_id`);

--
-- Indexes for table `withdraws`
--
ALTER TABLE `withdraws`
  ADD PRIMARY KEY (`with_id`);

--
-- Indexes for table `withdraw_unknown_cash`
--
ALTER TABLE `withdraw_unknown_cash`
  ADD PRIMARY KEY (`ts_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `balance_change_log`
--
ALTER TABLE `balance_change_log`
  MODIFY `log_id` bigint(22) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `banking`
--
ALTER TABLE `banking`
  MODIFY `deposit_id` bigint(22) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `banks`
--
ALTER TABLE `banks`
  MODIFY `ts_id` bigint(22) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `bosses`
--
ALTER TABLE `bosses`
  MODIFY `boss_id` bigint(22) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `boss_number`
--
ALTER TABLE `boss_number`
  MODIFY `ts_id` bigint(22) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `bulkymessages`
--
ALTER TABLE `bulkymessages`
  MODIFY `msg_id` bigint(22) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `cash`
--
ALTER TABLE `cash`
  MODIFY `ts_id` bigint(22) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `charges`
--
ALTER TABLE `charges`
  MODIFY `charges_id` bigint(22) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `cleared_loans`
--
ALTER TABLE `cleared_loans`
  MODIFY `loan_id` bigint(22) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `clients`
--
ALTER TABLE `clients`
  MODIFY `client_id` bigint(22) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `clients_not_paid`
--
ALTER TABLE `clients_not_paid`
  MODIFY `ts_id` bigint(22) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `clients_with_fines`
--
ALTER TABLE `clients_with_fines`
  MODIFY `ts_id` bigint(22) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `clients_with_loan`
--
ALTER TABLE `clients_with_loan`
  MODIFY `loan_id` bigint(22) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `completed_loan`
--
ALTER TABLE `completed_loan`
  MODIFY `loan_id` bigint(22) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `cr`
--
ALTER TABLE `cr`
  MODIFY `cr_id` bigint(22) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `daily_report`
--
ALTER TABLE `daily_report`
  MODIFY `loan_id` bigint(22) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `demand`
--
ALTER TABLE `demand`
  MODIFY `demand_id` bigint(22) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `errors`
--
ALTER TABLE `errors`
  MODIFY `ts_id` bigint(22) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `excess_short`
--
ALTER TABLE `excess_short`
  MODIFY `excess_id` bigint(22) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `expenses`
--
ALTER TABLE `expenses`
  MODIFY `exp_id` bigint(22) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `expenses_list`
--
ALTER TABLE `expenses_list`
  MODIFY `exp_id` bigint(22) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `field_payment`
--
ALTER TABLE `field_payment`
  MODIFY `ts_id` bigint(22) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `general_report`
--
ALTER TABLE `general_report`
  MODIFY `op_id` bigint(22) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `guarantor`
--
ALTER TABLE `guarantor`
  MODIFY `guarantor_id` bigint(22) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `idTake`
--
ALTER TABLE `idTake`
  MODIFY `idTake_id` bigint(22) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `idTake_history`
--
ALTER TABLE `idTake_history`
  MODIFY `idTake_id` bigint(22) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `loans`
--
ALTER TABLE `loans`
  MODIFY `loan_id` bigint(22) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `loans_in_parts`
--
ALTER TABLE `loans_in_parts`
  MODIFY `loan_id` bigint(22) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `loan_fines`
--
ALTER TABLE `loan_fines`
  MODIFY `ts_id` bigint(22) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `loan_pay`
--
ALTER TABLE `loan_pay`
  MODIFY `loan_id` bigint(22) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `loan_pay_daily`
--
ALTER TABLE `loan_pay_daily`
  MODIFY `loan_id` bigint(22) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `loan_returned`
--
ALTER TABLE `loan_returned`
  MODIFY `id` bigint(22) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `location`
--
ALTER TABLE `location`
  MODIFY `location_id` bigint(22) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `mobile`
--
ALTER TABLE `mobile`
  MODIFY `mobile_id` bigint(22) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `mobile_numbers`
--
ALTER TABLE `mobile_numbers`
  MODIFY `mobile_id` bigint(22) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `mom_balance`
--
ALTER TABLE `mom_balance`
  MODIFY `mom_id` bigint(22) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `mom_phones`
--
ALTER TABLE `mom_phones`
  MODIFY `ts_id` bigint(22) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `new_users`
--
ALTER TABLE `new_users`
  MODIFY `user_id` bigint(22) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `officers`
--
ALTER TABLE `officers`
  MODIFY `officer_id` bigint(22) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `op`
--
ALTER TABLE `op`
  MODIFY `op_id` bigint(22) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `op_mom`
--
ALTER TABLE `op_mom`
  MODIFY `op_id` bigint(22) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `payments`
--
ALTER TABLE `payments`
  MODIFY `pay_id` bigint(22) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `portifolios`
--
ALTER TABLE `portifolios`
  MODIFY `ts_id` bigint(22) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `profit`
--
ALTER TABLE `profit`
  MODIFY `profit_id` bigint(22) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `receipts`
--
ALTER TABLE `receipts`
  MODIFY `receipt_id` bigint(22) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `savings`
--
ALTER TABLE `savings`
  MODIFY `save_id` bigint(22) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `sent_msgs`
--
ALTER TABLE `sent_msgs`
  MODIFY `ts_id` bigint(22) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `sent_to_branch`
--
ALTER TABLE `sent_to_branch`
  MODIFY `cr_id` bigint(22) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `shortage`
--
ALTER TABLE `shortage`
  MODIFY `short_id` bigint(22) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `sms_add`
--
ALTER TABLE `sms_add`
  MODIFY `sms_id` bigint(22) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `sms_queue`
--
ALTER TABLE `sms_queue`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `total_banking`
--
ALTER TABLE `total_banking`
  MODIFY `ts_id` bigint(22) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `total_mom`
--
ALTER TABLE `total_mom`
  MODIFY `ts_id` bigint(22) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `total_msg`
--
ALTER TABLE `total_msg`
  MODIFY `msg_id` bigint(22) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `total_savings`
--
ALTER TABLE `total_savings`
  MODIFY `ts_id` bigint(22) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `transcations`
--
ALTER TABLE `transcations`
  MODIFY `ts_id` bigint(22) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `trash`
--
ALTER TABLE `trash`
  MODIFY `ts_id` bigint(22) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `uknown`
--
ALTER TABLE `uknown`
  MODIFY `uknown_id` bigint(22) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `unknown_cash`
--
ALTER TABLE `unknown_cash`
  MODIFY `ts_id` bigint(22) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `withdraw`
--
ALTER TABLE `withdraw`
  MODIFY `with_id` bigint(22) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `withdraws`
--
ALTER TABLE `withdraws`
  MODIFY `with_id` bigint(22) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `withdraw_unknown_cash`
--
ALTER TABLE `withdraw_unknown_cash`
  MODIFY `ts_id` bigint(22) NOT NULL AUTO_INCREMENT;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
