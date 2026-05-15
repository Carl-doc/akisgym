-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Mar 11, 2026 at 12:03 PM
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
-- Database: `akisgym_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `tbl_admin`
--

CREATE TABLE `tbl_admin` (
  `admin_id` int(11) NOT NULL,
  `full_name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('super_admin','admin','staff') DEFAULT 'admin',
  `status` enum('active','inactive') DEFAULT 'active',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tbl_admin`
--

INSERT INTO `tbl_admin` (`admin_id`, `full_name`, `email`, `password`, `role`, `status`, `created_at`, `updated_at`) VALUES
(1, 'System Admin', 'admin@gym.com', '$2a$10$abcdefghijklmnopqrstuv', 'super_admin', 'active', '2026-03-11 10:56:11', '2026-03-11 10:56:11'),
(2, 'Staff User', 'staff@gym.com', '$2a$10$abcdefghijklmnopqrstuv', 'staff', 'active', '2026-03-11 10:56:11', '2026-03-11 10:56:11');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_logs`
--

CREATE TABLE `tbl_logs` (
  `log_id` int(11) NOT NULL,
  `admin_id` int(11) DEFAULT NULL,
  `action` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tbl_logs`
--

INSERT INTO `tbl_logs` (`log_id`, `admin_id`, `action`, `description`, `ip_address`, `created_at`) VALUES
(1, 1, 'Created Member', 'Added member John Doe', '127.0.0.1', '2026-03-11 10:56:11'),
(2, 1, 'Processed Payment', 'Recorded VIP payment for Maria Santos', '127.0.0.1', '2026-03-11 10:56:11');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_member`
--

CREATE TABLE `tbl_member` (
  `member_id` int(11) NOT NULL,
  `member_code` varchar(20) NOT NULL,
  `first_name` varchar(100) NOT NULL,
  `last_name` varchar(100) NOT NULL,
  `gender` enum('male','female','other') DEFAULT 'other',
  `birth_date` date DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `address` text DEFAULT NULL,
  `emergency_contact_name` varchar(100) DEFAULT NULL,
  `emergency_contact_phone` varchar(20) DEFAULT NULL,
  `profile_image` varchar(255) DEFAULT NULL,
  `status` enum('active','inactive','expired','banned') DEFAULT 'active',
  `joined_date` date DEFAULT curdate(),
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tbl_member`
--

INSERT INTO `tbl_member` (`member_id`, `member_code`, `first_name`, `last_name`, `gender`, `birth_date`, `email`, `password`, `phone`, `address`, `emergency_contact_name`, `emergency_contact_phone`, `profile_image`, `status`, `joined_date`, `created_at`, `updated_at`) VALUES
(1, 'MBR-001', 'John', 'Doe', 'male', '2000-05-10', 'john@example.com', '', '09123456789', 'Tacloban City', 'Jane Doe', '09987654321', NULL, 'active', '2026-03-11', '2026-03-11 10:56:11', '2026-03-11 10:56:11'),
(2, 'MBR-002', 'Maria', 'Santos', 'female', '1998-08-21', 'maria@example.com', '', '09111222333', 'Ormoc City', 'Pedro Santos', '09998887777', NULL, 'active', '2026-03-11', '2026-03-11 10:56:11', '2026-03-11 10:56:11'),
(3, 'MBR-003', 'joshua', 'reyes', 'male', NULL, 'joshua@gym.com', '$2y$10$flOWlks3gLgHZSX2hoqWae/wHFQJ.o21IvkUDkek5JeDeCAYmwTbW', '09123456789', NULL, NULL, NULL, NULL, 'active', '2026-03-11', '2026-03-11 10:58:22', '2026-03-11 10:58:22');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_member_subscriptions`
--

CREATE TABLE `tbl_member_subscriptions` (
  `member_subscription_id` int(11) NOT NULL,
  `member_id` int(11) NOT NULL,
  `subscription_id` int(11) NOT NULL,
  `start_date` date NOT NULL,
  `end_date` date NOT NULL,
  `status` enum('active','expired','cancelled','pending') DEFAULT 'pending',
  `assigned_by` int(11) DEFAULT NULL,
  `notes` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tbl_member_subscriptions`
--

INSERT INTO `tbl_member_subscriptions` (`member_subscription_id`, `member_id`, `subscription_id`, `start_date`, `end_date`, `status`, `assigned_by`, `notes`, `created_at`, `updated_at`) VALUES
(1, 1, 2, '2026-03-11', '2026-04-10', 'active', 1, 'Premium plan assigned', '2026-03-11 10:56:11', '2026-03-11 10:56:11'),
(2, 2, 3, '2026-03-11', '2026-04-10', 'active', 1, 'VIP plan assigned', '2026-03-11 10:56:11', '2026-03-11 10:56:11');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_notifications`
--

CREATE TABLE `tbl_notifications` (
  `notification_id` int(11) NOT NULL,
  `member_id` int(11) NOT NULL,
  `title` varchar(150) NOT NULL,
  `message` text NOT NULL,
  `notification_type` enum('reminder','expiration','promotion','announcement') DEFAULT 'reminder',
  `is_read` tinyint(1) DEFAULT 0,
  `sent_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tbl_notifications`
--

INSERT INTO `tbl_notifications` (`notification_id`, `member_id`, `title`, `message`, `notification_type`, `is_read`, `sent_at`) VALUES
(1, 1, 'Membership Reminder', 'Your membership will expire soon. Please renew on time.', 'reminder', 0, '2026-03-11 18:56:11'),
(2, 2, 'Promo Offer', 'Upgrade next month and enjoy added benefits.', 'promotion', 0, '2026-03-11 18:56:11');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_subscription`
--

CREATE TABLE `tbl_subscription` (
  `subscription_id` int(11) NOT NULL,
  `plan_name` varchar(100) NOT NULL,
  `description` text DEFAULT NULL,
  `price` decimal(10,2) NOT NULL,
  `duration_days` int(11) NOT NULL,
  `access_level` enum('regular','premium','vip') DEFAULT 'regular',
  `status` enum('active','inactive') DEFAULT 'active',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tbl_subscription`
--

INSERT INTO `tbl_subscription` (`subscription_id`, `plan_name`, `description`, `price`, `duration_days`, `access_level`, `status`, `created_at`, `updated_at`) VALUES
(1, 'Regular', 'Basic gym access', 800.00, 30, 'regular', 'active', '2026-03-11 10:56:11', '2026-03-11 10:56:11'),
(2, 'Premium', 'Gym access with group classes', 1500.00, 30, 'premium', 'active', '2026-03-11 10:56:11', '2026-03-11 10:56:11'),
(3, 'VIP', 'Full gym access with trainer support', 2500.00, 30, 'vip', 'active', '2026-03-11 10:56:11', '2026-03-11 10:56:11');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_transactions`
--

CREATE TABLE `tbl_transactions` (
  `transaction_id` int(11) NOT NULL,
  `transaction_code` varchar(30) NOT NULL,
  `member_subscription_id` int(11) NOT NULL,
  `member_id` int(11) NOT NULL,
  `subscription_id` int(11) NOT NULL,
  `amount` decimal(10,2) NOT NULL,
  `payment_method` enum('cash','gcash','bank_transfer','card') DEFAULT 'cash',
  `payment_status` enum('paid','pending','failed','refunded') DEFAULT 'paid',
  `payment_date` datetime DEFAULT current_timestamp(),
  `reference_number` varchar(100) DEFAULT NULL,
  `processed_by` int(11) DEFAULT NULL,
  `remarks` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tbl_transactions`
--

INSERT INTO `tbl_transactions` (`transaction_id`, `transaction_code`, `member_subscription_id`, `member_id`, `subscription_id`, `amount`, `payment_method`, `payment_status`, `payment_date`, `reference_number`, `processed_by`, `remarks`, `created_at`, `updated_at`) VALUES
(1, 'TRX-1001', 1, 1, 2, 1500.00, 'cash', 'paid', '2026-03-11 18:56:11', 'CASH-1001', 1, 'Initial premium membership payment', '2026-03-11 10:56:11', '2026-03-11 10:56:11'),
(2, 'TRX-1002', 2, 2, 3, 2500.00, 'gcash', 'paid', '2026-03-11 18:56:11', 'GCASH-1002', 1, 'Initial VIP membership payment', '2026-03-11 10:56:11', '2026-03-11 10:56:11');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `tbl_admin`
--
ALTER TABLE `tbl_admin`
  ADD PRIMARY KEY (`admin_id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `tbl_logs`
--
ALTER TABLE `tbl_logs`
  ADD PRIMARY KEY (`log_id`),
  ADD KEY `fk_logs_admin` (`admin_id`);

--
-- Indexes for table `tbl_member`
--
ALTER TABLE `tbl_member`
  ADD PRIMARY KEY (`member_id`),
  ADD UNIQUE KEY `member_code` (`member_code`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `tbl_member_subscriptions`
--
ALTER TABLE `tbl_member_subscriptions`
  ADD PRIMARY KEY (`member_subscription_id`),
  ADD KEY `fk_member_subscriptions_member` (`member_id`),
  ADD KEY `fk_member_subscriptions_subscription` (`subscription_id`),
  ADD KEY `fk_member_subscriptions_admin` (`assigned_by`);

--
-- Indexes for table `tbl_notifications`
--
ALTER TABLE `tbl_notifications`
  ADD PRIMARY KEY (`notification_id`),
  ADD KEY `fk_notifications_member` (`member_id`);

--
-- Indexes for table `tbl_subscription`
--
ALTER TABLE `tbl_subscription`
  ADD PRIMARY KEY (`subscription_id`),
  ADD UNIQUE KEY `plan_name` (`plan_name`);

--
-- Indexes for table `tbl_transactions`
--
ALTER TABLE `tbl_transactions`
  ADD PRIMARY KEY (`transaction_id`),
  ADD UNIQUE KEY `transaction_code` (`transaction_code`),
  ADD KEY `fk_transactions_member_subscription` (`member_subscription_id`),
  ADD KEY `fk_transactions_member` (`member_id`),
  ADD KEY `fk_transactions_subscription` (`subscription_id`),
  ADD KEY `fk_transactions_admin` (`processed_by`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `tbl_admin`
--
ALTER TABLE `tbl_admin`
  MODIFY `admin_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `tbl_logs`
--
ALTER TABLE `tbl_logs`
  MODIFY `log_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `tbl_member`
--
ALTER TABLE `tbl_member`
  MODIFY `member_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `tbl_member_subscriptions`
--
ALTER TABLE `tbl_member_subscriptions`
  MODIFY `member_subscription_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `tbl_notifications`
--
ALTER TABLE `tbl_notifications`
  MODIFY `notification_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `tbl_subscription`
--
ALTER TABLE `tbl_subscription`
  MODIFY `subscription_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `tbl_transactions`
--
ALTER TABLE `tbl_transactions`
  MODIFY `transaction_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `tbl_logs`
--
ALTER TABLE `tbl_logs`
  ADD CONSTRAINT `fk_logs_admin` FOREIGN KEY (`admin_id`) REFERENCES `tbl_admin` (`admin_id`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Constraints for table `tbl_member_subscriptions`
--
ALTER TABLE `tbl_member_subscriptions`
  ADD CONSTRAINT `fk_member_subscriptions_admin` FOREIGN KEY (`assigned_by`) REFERENCES `tbl_admin` (`admin_id`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_member_subscriptions_member` FOREIGN KEY (`member_id`) REFERENCES `tbl_member` (`member_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_member_subscriptions_subscription` FOREIGN KEY (`subscription_id`) REFERENCES `tbl_subscription` (`subscription_id`) ON UPDATE CASCADE;

--
-- Constraints for table `tbl_notifications`
--
ALTER TABLE `tbl_notifications`
  ADD CONSTRAINT `fk_notifications_member` FOREIGN KEY (`member_id`) REFERENCES `tbl_member` (`member_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `tbl_transactions`
--
ALTER TABLE `tbl_transactions`
  ADD CONSTRAINT `fk_transactions_admin` FOREIGN KEY (`processed_by`) REFERENCES `tbl_admin` (`admin_id`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_transactions_member` FOREIGN KEY (`member_id`) REFERENCES `tbl_member` (`member_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_transactions_member_subscription` FOREIGN KEY (`member_subscription_id`) REFERENCES `tbl_member_subscriptions` (`member_subscription_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_transactions_subscription` FOREIGN KEY (`subscription_id`) REFERENCES `tbl_subscription` (`subscription_id`) ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
