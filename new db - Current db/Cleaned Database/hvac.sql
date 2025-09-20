-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Sep 13, 2025 at 01:14 PM
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
-- Database: `hvac`
--

-- --------------------------------------------------------

--
-- Table structure for table `appliances_type`
--

CREATE TABLE `appliances_type` (
  `appliances_type_id` int(11) NOT NULL,
  `appliances_type_name` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `appointment`
--

CREATE TABLE `appointment` (
  `app_id` int(11) NOT NULL,
  `app_schedule` datetime NOT NULL,
  `app_desc` text NOT NULL,
  `app_created` datetime NOT NULL DEFAULT current_timestamp(),
  `app_status_id` int(11) NOT NULL,
  `app_rating` double NOT NULL,
  `app_comment` text NOT NULL,
  `app_price` varchar(255) NOT NULL,
  `payment_status` enum('Paid','Unpaid') NOT NULL DEFAULT 'Unpaid',
  `decline_justification` text NOT NULL,
  `app_justification` text NOT NULL,
  `service_type_id` int(11) NOT NULL,
  `appliances_type_id` int(11) DEFAULT NULL,
  `user_id` int(11) NOT NULL,
  `user_technician` int(11) NOT NULL,
  `user_technician_2` int(11) DEFAULT NULL,
  `technician_justification` text NOT NULL,
  `app_completed_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `appointment_status`
--

CREATE TABLE `appointment_status` (
  `app_status_id` int(11) NOT NULL,
  `app_status_name` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `appointment_status`
--

INSERT INTO `appointment_status` (`app_status_id`, `app_status_name`) VALUES
(1, 'Approved'),
(2, 'Pending'),
(3, 'Completed'),
(4, 'Declined'),
(5, 'In Progress'),
(6, 'To Rate'),
(9, 'Pending Payment'),
(10, 'Cancelled');


-- --------------------------------------------------------

--
-- Table structure for table `appointment_transaction_address`
--

CREATE TABLE `appointment_transaction_address` (
  `transaction_address_id` int(11) NOT NULL,
  `app_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `municipality_city` varchar(255) NOT NULL,
  `province` varchar(255) NOT NULL,
  `barangay` varchar(255) NOT NULL,
  `zip_code` varchar(10) NOT NULL,
  `full_address` text NOT NULL,
  `captured_at` datetime NOT NULL DEFAULT current_timestamp(),
  `created_by` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `notification`
--

CREATE TABLE `notification` (
  `notification_id` int(11) NOT NULL,
  `event_type` enum('user_registered','user_added','user_edited','user_deleted','appointment_created','appointment_rebooked','appointment_status_changed','payment_status_changed','invoice_overdue','appointment_accepted') NOT NULL,
  `event_description` text NOT NULL,
  `target_user_id` int(11) DEFAULT NULL,
  `actor_user_id` int(11) DEFAULT NULL,
  `related_appointment_id` int(11) DEFAULT NULL,
  `related_invoice_id` int(11) DEFAULT NULL,
  `old_status` varchar(50) DEFAULT NULL,
  `new_status` varchar(50) DEFAULT NULL,
  `additional_data` text DEFAULT NULL,
  `is_read` tinyint(1) NOT NULL DEFAULT 0,
  `is_system_notification` tinyint(1) NOT NULL DEFAULT 0,
  `priority` enum('low','medium','high','urgent') NOT NULL DEFAULT 'medium',
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `read_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `service_type`
--

CREATE TABLE `service_type` (
  `service_type_id` int(11) NOT NULL,
  `service_type_name` varchar(255) NOT NULL,
  `service_type_price_min` decimal(10,2) DEFAULT NULL,
  `service_type_price_max` decimal(10,2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `service_type_appliances`
--

CREATE TABLE `service_type_appliances` (
  `service_type_id` int(11) NOT NULL,
  `appliances_type_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE `user` (
  `user_id` int(11) NOT NULL,
  `user_name` varchar(255) NOT NULL,
  `user_midname` varchar(255) DEFAULT NULL,
  `user_lastname` varchar(255) NOT NULL,
  `user_pass` varchar(255) NOT NULL,
  `user_email` varchar(255) NOT NULL,
  `user_contact` varchar(255) DEFAULT NULL,
  `house_building_street` varchar(255) DEFAULT NULL,
  `barangay` varchar(255) DEFAULT NULL,
  `municipality_city` varchar(255) DEFAULT NULL,
  `province` varchar(255) DEFAULT NULL,
  `zip_code` varchar(10) DEFAULT NULL,
  `user_profile_picture` varchar(255) DEFAULT NULL,
  `user_type_id` int(11) NOT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `last_login` datetime DEFAULT NULL,
  `last_activity` datetime DEFAULT NULL,
  `availability_status` enum('available','unavailable','scheduled') DEFAULT 'available',
  `availability_date` date DEFAULT NULL,
  `availability_start_time` time DEFAULT NULL,
  `availability_end_time` time DEFAULT NULL,
  `availability_notes` text DEFAULT NULL,
  `user_created` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`user_id`, `user_name`, `user_midname`, `user_lastname`, `user_pass`, `user_email`, `user_contact`, `house_building_street`, `barangay`, `municipality_city`, `province`, `zip_code`, `user_profile_picture`, `user_type_id`, `is_active`, `last_login`, `last_activity`, `availability_status`, `availability_date`, `availability_start_time`, `availability_end_time`, `availability_notes`, `user_created`) VALUES
(1, 'admin', 'Admin', 'Admin', 'admin', 'admin@gmail.com', '09686676066', 'Mangalcal', 'Ising', 'Carmen', 'Davao del Norte', '8101', NULL, 1, 1, NULL, NULL, 'available', NULL, NULL, NULL, NULL, '2025-09-13 13:14:00');

-- --------------------------------------------------------

--
-- Table structure for table `user_type`
--

CREATE TABLE `user_type` (
  `user_type_id` int(11) NOT NULL,
  `user_type_name` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `user_type`
--

INSERT INTO `user_type` (`user_type_id`, `user_type_name`) VALUES
(1, 'administrator'),
(2, 'technician'),
(3, 'staff'),
(4, 'customer');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `appliances_type`
--
ALTER TABLE `appliances_type`
  ADD PRIMARY KEY (`appliances_type_id`);

--
-- Indexes for table `appointment`
--
ALTER TABLE `appointment`
  ADD PRIMARY KEY (`app_id`),
  ADD KEY `fk_appointment_appliances_type` (`appliances_type_id`);

--
-- Indexes for table `appointment_status`
--
ALTER TABLE `appointment_status`
  ADD PRIMARY KEY (`app_status_id`);

--
-- Indexes for table `appointment_transaction_address`
--
ALTER TABLE `appointment_transaction_address`
  ADD PRIMARY KEY (`transaction_address_id`),
  ADD UNIQUE KEY `unique_app_address` (`app_id`),
  ADD KEY `idx_app_id` (`app_id`),
  ADD KEY `idx_user_id` (`user_id`),
  ADD KEY `idx_municipality_city` (`municipality_city`),
  ADD KEY `idx_province` (`province`),
  ADD KEY `idx_captured_at` (`captured_at`);

--
-- Indexes for table `notification`
--
ALTER TABLE `notification`
  ADD PRIMARY KEY (`notification_id`),
  ADD KEY `idx_target_user` (`target_user_id`),
  ADD KEY `idx_actor_user` (`actor_user_id`),
  ADD KEY `idx_event_type` (`event_type`),
  ADD KEY `idx_created_at` (`created_at`),
  ADD KEY `idx_is_read` (`is_read`),
  ADD KEY `fk_notification_appointment` (`related_appointment_id`);

--
-- Indexes for table `service_type`
--
ALTER TABLE `service_type`
  ADD PRIMARY KEY (`service_type_id`);

--
-- Indexes for table `service_type_appliances`
--
ALTER TABLE `service_type_appliances`
  ADD PRIMARY KEY (`service_type_id`,`appliances_type_id`),
  ADD KEY `appliances_type_id` (`appliances_type_id`);

--
-- Indexes for table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`user_id`),
  ADD UNIQUE KEY `user_email` (`user_email`),
  ADD KEY `idx_user_is_active` (`is_active`),
  ADD KEY `idx_user_availability_status` (`availability_status`),
  ADD KEY `idx_user_availability_date` (`availability_date`);

--
-- Indexes for table `user_type`
--
ALTER TABLE `user_type`
  ADD PRIMARY KEY (`user_type_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `appliances_type`
--
ALTER TABLE `appliances_type`
  MODIFY `appliances_type_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `appointment`
--
ALTER TABLE `appointment`
  MODIFY `app_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `appointment_transaction_address`
--
ALTER TABLE `appointment_transaction_address`
  MODIFY `transaction_address_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `notification`
--
ALTER TABLE `notification`
  MODIFY `notification_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `service_type`
--
ALTER TABLE `service_type`
  MODIFY `service_type_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `user`
--
ALTER TABLE `user`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `user_type`
--
ALTER TABLE `user_type`
  MODIFY `user_type_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `appointment`
--
ALTER TABLE `appointment`
  ADD CONSTRAINT `fk_appointment_appliances_type` FOREIGN KEY (`appliances_type_id`) REFERENCES `appliances_type` (`appliances_type_id`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Constraints for table `service_type_appliances`
--
ALTER TABLE `service_type_appliances`
  ADD CONSTRAINT `service_type_appliances_ibfk_1` FOREIGN KEY (`service_type_id`) REFERENCES `service_type` (`service_type_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `service_type_appliances_ibfk_2` FOREIGN KEY (`appliances_type_id`) REFERENCES `appliances_type` (`appliances_type_id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
