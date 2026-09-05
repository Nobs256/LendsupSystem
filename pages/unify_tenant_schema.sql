-- Unified tenant schema migration for MariaDB 10.5+
-- Run this once in each tenant database. It is safe to run repeatedly.

SET SQL_MODE = 'NO_AUTO_VALUE_ON_ZERO';
SET time_zone = '+00:00';

-- Ensure the core tables exist before applying column upgrades.
CREATE TABLE IF NOT EXISTS clients (
  client_id BIGINT(22) NOT NULL AUTO_INCREMENT,
  users_id BIGINT(22) NOT NULL,
  bosses_id BIGINT(22) NOT NULL,
  firstname VARCHAR(20) NOT NULL,
  lastname VARCHAR(20) NOT NULL,
  sex VARCHAR(20) NOT NULL,
  dob VARCHAR(6) NOT NULL,
  marital VARCHAR(10) NOT NULL,
  nid VARCHAR(25) NOT NULL,
  phone VARCHAR(20) NOT NULL,
  place_r VARCHAR(20) NOT NULL,
  business VARCHAR(30) NOT NULL,
  b_location VARCHAR(30) NOT NULL,
  reg_date DATE NOT NULL DEFAULT (CURRENT_DATE),
  PRIMARY KEY (client_id)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

CREATE TABLE IF NOT EXISTS clients_with_loan (
  loan_id BIGINT(22) NOT NULL AUTO_INCREMENT,
  loan_no BIGINT(22) NOT NULL,
  clientsid BIGINT(22) NOT NULL,
  userseid BIGINT(22) NOT NULL,
  bosseseid BIGINT(22) NOT NULL,
  pay_date DATE NOT NULL,
  amount_given BIGINT(22) NOT NULL,
  daily_p BIGINT(22) NOT NULL,
  debt BIGINT(22) NOT NULL,
  interest BIGINT(12) NOT NULL,
  days INT(3) NOT NULL DEFAULT 30,
  loan_type VARCHAR(20) NOT NULL DEFAULT 'Daily',
  PRIMARY KEY (loan_id)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

CREATE TABLE IF NOT EXISTS loan_pay (
  loan_id BIGINT(22) NOT NULL AUTO_INCREMENT,
  loanNo BIGINT(22) NOT NULL,
  clients_id BIGINT(22) NOT NULL,
  userse_id BIGINT(22) NOT NULL,
  bossese_id BIGINT(22) NOT NULL,
  p_date DATE NOT NULL,
  amount_paid BIGINT(22) NOT NULL,
  balance BIGINT(22) NOT NULL,
  mom BIGINT(4) NOT NULL,
  intrest_paid VARCHAR(10) NOT NULL DEFAULT '0',
  PRIMARY KEY (loan_id)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

CREATE TABLE IF NOT EXISTS loan_pay_daily (
  loan_id BIGINT(22) NOT NULL AUTO_INCREMENT,
  loanNo BIGINT(22) NOT NULL,
  clients_id BIGINT(22) NOT NULL,
  userse_id BIGINT(22) NOT NULL,
  bossese_id BIGINT(22) NOT NULL,
  p_date DATE NOT NULL,
  amount_paid BIGINT(22) NOT NULL,
  balance BIGINT(22) NOT NULL,
  mom BIGINT(4) NOT NULL,
  intrest_paid VARCHAR(10) NOT NULL DEFAULT '0',
  PRIMARY KEY (loan_id)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- Client registration currently omits reg_date, so provide the database default.
ALTER TABLE clients
  ADD COLUMN IF NOT EXISTS reg_date DATE NULL;
UPDATE clients SET reg_date = CURRENT_DATE WHERE reg_date IS NULL;
ALTER TABLE clients
  MODIFY COLUMN reg_date DATE NOT NULL DEFAULT (CURRENT_DATE);

-- Loan metadata required by the daily and loan-payment screens.
ALTER TABLE clients_with_loan
  ADD COLUMN IF NOT EXISTS days INT(3) NOT NULL DEFAULT 30,
  ADD COLUMN IF NOT EXISTS loan_type VARCHAR(20) NOT NULL DEFAULT 'Monthly';

-- Interest is recorded by the standard and field-payment connectors.
ALTER TABLE loan_pay
  ADD COLUMN IF NOT EXISTS intrest_paid VARCHAR(10) NOT NULL DEFAULT '0';
ALTER TABLE loan_pay_daily
  ADD COLUMN IF NOT EXISTS intrest_paid VARCHAR(10) NOT NULL DEFAULT '0';

-- The application uses the singular legacy table name field_payment.
CREATE TABLE IF NOT EXISTS field_payment (
  ts_id BIGINT(22) NOT NULL AUTO_INCREMENT,
  user_id BIGINT(22) NOT NULL,
  boss_id BIGINT(22) NOT NULL,
  clientf_id BIGINT(22) NOT NULL,
  pay_date DATE NOT NULL,
  officerid BIGINT(10) NOT NULL,
  amount BIGINT(10) NOT NULL,
  mom BIGINT(4) NOT NULL DEFAULT 0,
  pyt_method VARCHAR(20) NOT NULL DEFAULT 'Office',
  PRIMARY KEY (ts_id)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;
ALTER TABLE field_payment
  ADD COLUMN IF NOT EXISTS pyt_method VARCHAR(20) NOT NULL DEFAULT 'Office';

-- Track which field officer an excess or shortage belongs to.
ALTER TABLE excess_short
  ADD COLUMN IF NOT EXISTS officer_id BIGINT(22) NOT NULL DEFAULT 0;
ALTER TABLE shortage
  ADD COLUMN IF NOT EXISTS officer_id BIGINT(22) NOT NULL DEFAULT 0;

-- Loan-in-parts connector dependency.
CREATE TABLE IF NOT EXISTS loans_in_parts (
  loan_id BIGINT(22) NOT NULL AUTO_INCREMENT,
  loan_no INT(4) NOT NULL,
  clientp_id BIGINT(22) NOT NULL,
  usersp_id BIGINT(22) NOT NULL,
  bossesp_id BIGINT(22) NOT NULL,
  bp_date DATE NOT NULL,
  amount_g BIGINT(22) NOT NULL,
  part VARCHAR(20) NOT NULL,
  reg_fee VARCHAR(5) NOT NULL,
  difference VARCHAR(22) NOT NULL,
  PRIMARY KEY (loan_id)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- Field mobile-money connector dependency.
CREATE TABLE IF NOT EXISTS loan_pay_cash (
  loan_id BIGINT(22) NOT NULL AUTO_INCREMENT,
  loanNo BIGINT(22) NOT NULL,
  clients_id BIGINT(22) NOT NULL,
  userse_id BIGINT(22) NOT NULL,
  bossese_id BIGINT(22) NOT NULL,
  p_date DATE NOT NULL,
  amount_paid BIGINT(22) NOT NULL,
  balance BIGINT(22) NOT NULL,
  mom BIGINT(4) NOT NULL,
  PRIMARY KEY (loan_id)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- SMS queue used by the shared notification worker.
CREATE TABLE IF NOT EXISTS sms_queue (
  id INT(11) NOT NULL AUTO_INCREMENT,
  user_id INT(11) DEFAULT NULL,
  boss_id INT(11) DEFAULT NULL,
  phone_number VARCHAR(20) NOT NULL,
  message TEXT NOT NULL,
  status ENUM('pending','sent','failed','invalid') NOT NULL DEFAULT 'pending',
  response TEXT DEFAULT NULL,
  attempts INT(11) NOT NULL DEFAULT 0,
  created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  sent_at DATETIME DEFAULT NULL,
  PRIMARY KEY (id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
ALTER TABLE sms_queue
  MODIFY COLUMN status ENUM('pending','sent','failed','invalid') NOT NULL DEFAULT 'pending';

-- Relworx SMS-credit payment records used by the shared payment flow.
CREATE TABLE IF NOT EXISTS relworx_payments (
  id INT(11) NOT NULL AUTO_INCREMENT,
  user_id INT(11) NOT NULL,
  boss_id INT(11) NOT NULL,
  phone VARCHAR(20) NOT NULL,
  amount DECIMAL(10,2) NOT NULL,
  sms_units INT(11) NOT NULL,
  reference VARCHAR(100) NOT NULL,
  internal_reference VARCHAR(100) NOT NULL,
  status ENUM('pending','success','failed') NOT NULL DEFAULT 'pending',
  created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  updated_at TIMESTAMP NULL DEFAULT NULL,
  PRIMARY KEY (id)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;


CREATE TABLE IF NOT EXISTS `loan_returned` (
  `id` bigint(22) NOT NULL,
  `client_id` bigint(22) NOT NULL,
  `user_id` bigint(22) NOT NULL,
  `boss_id` bigint(22) NOT NULL,
  `date` date NOT NULL,
  `amount_returned` bigint(22) NOT NULL,
  PRIMARY KEY (id)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- Helpful tenant-scoped lookup indexes. Duplicate indexes are intentionally avoided.
ALTER TABLE clients
  ADD INDEX IF NOT EXISTS idx_clients_tenant_phone (bosses_id, users_id, phone);
ALTER TABLE clients_with_loan
  ADD INDEX IF NOT EXISTS idx_active_loan_client (bosseseid, userseid, clientsid);
ALTER TABLE loan_pay
  ADD INDEX IF NOT EXISTS idx_loan_pay_lookup (bossese_id, userse_id, clients_id, loanNo, p_date, mom);
ALTER TABLE field_payment
  ADD INDEX IF NOT EXISTS idx_field_payment_lookup (boss_id, user_id, clientf_id, officerid, pay_date);




-- SHOW COLUMNS FROM clients;
-- SHOW COLUMNS FROM clients_with_loan;
-- SHOW COLUMNS FROM loan_pay;
-- SHOW COLUMNS FROM field_payment;
-- SHOW TABLES LIKE 'loans_in_parts';
-- SHOW TABLES LIKE 'loan_pay_cash';
-- SHOW TABLES LIKE 'sms_queue';
-- SHOW TABLES LIKE 'relworx_payments';

-- ALTER TABLE `loan_returned` MODIFY `id` bigint(22) NOT NULL AUTO_INCREMENT;
