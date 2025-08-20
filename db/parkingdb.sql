-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Aug 20, 2025 at 05:06 PM
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
-- Database: `parkingdb`
--

-- --------------------------------------------------------

--
-- Table structure for table `email_verifications`
--

CREATE TABLE `email_verifications` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `token` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `email_verifications`
--

INSERT INTO `email_verifications` (`id`, `user_id`, `token`, `created_at`) VALUES
(1, 4, '3a976d577735f0faf3e7ac7e99225b46', '2025-08-10 11:59:42');

-- --------------------------------------------------------

--
-- Table structure for table `space`
--

CREATE TABLE `space` (
  `space_id` int(3) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `lat` varchar(12) DEFAULT NULL,
  `lng` varchar(12) DEFAULT NULL,
  `vehicletype` varchar(20) DEFAULT NULL,
  `location` varchar(50) DEFAULT NULL,
  `status` enum('1','0') NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `space`
--

INSERT INTO `space` (`space_id`, `user_id`, `lat`, `lng`, `vehicletype`, `location`, `status`) VALUES
(7, 3, '27.694634128', '85.32078932', 'Car', 'ANNAPURNA', '1'),
(8, 2, '27.695676505', '85.320704287', 'Motorbike', 'MHP', '1'),
(11, 5, '27.692162169', '85.32553831', 'Motorbike', 'Kathmandu District Court', '0'),
(12, 6, '27.696080238', '85.321283644', 'Motorbike', 'Department of Archaeology', '0'),
(13, 6, '27.696995994', '85.321850328', 'Car', 'Supreme Court', '0'),
(17, 5, '27.6734454', '85.325035', 'Motorbike', 'Patan Durbar Square', '1'),
(19, 5, '27.6696351', '85.3300728', 'Motorbike', 'Dupat', '0');

-- --------------------------------------------------------

--
-- Table structure for table `tblcategory`
--

CREATE TABLE `tblcategory` (
  `ID` int(10) NOT NULL,
  `VehicleCat` varchar(120) DEFAULT NULL,
  `CreationDate` timestamp NOT NULL DEFAULT current_timestamp(),
  `FID` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tblcategory`
--

INSERT INTO `tblcategory` (`ID`, `VehicleCat`, `CreationDate`, `FID`) VALUES
(1, 'Car', '2025-08-10 09:01:43', 1),
(2, 'Motorbike', '2025-08-10 09:01:43', 2),
(3, 'Truck', '2025-08-10 09:01:43', 3),
(4, 'Bicycle', '2025-08-10 10:17:16', NULL),
(5, 'Micro-Bus', '2025-08-12 17:05:37', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `tblpayment`
--

CREATE TABLE `tblpayment` (
  `payment_id` int(11) NOT NULL,
  `space_user_id` int(11) NOT NULL,
  `space_owner_id` int(11) NOT NULL,
  `booking_id` int(11) NOT NULL,
  `amount` decimal(10,2) NOT NULL,
  `commission` decimal(10,2) NOT NULL,
  `paid_status` enum('pending','paid') NOT NULL DEFAULT 'pending',
  `payment_method` varchar(50) DEFAULT 'khalti',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `paid_time` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tblpayment`
--

INSERT INTO `tblpayment` (`payment_id`, `space_user_id`, `space_owner_id`, `booking_id`, `amount`, `commission`, `paid_status`, `payment_method`, `created_at`, `paid_time`) VALUES
(1, 6, 5, 38, 60.00, 9.00, 'paid', 'khalti', '2025-08-16 08:22:46', '2025-08-20 15:01:41'),
(2, 6, 5, 39, 90.00, 13.50, 'paid', 'khalti', '2025-08-17 06:03:37', '2025-08-20 15:01:41'),
(3, 6, 5, 40, 30.00, 4.50, 'paid', 'khalti', '2025-08-17 09:05:42', '2025-08-20 15:01:41'),
(4, 6, 5, 41, 30.00, 4.50, 'paid', 'khalti', '2025-08-17 09:58:36', '2025-08-20 15:01:41'),
(5, 6, 5, 42, 30.00, 4.50, 'paid', 'khalti', '2025-08-17 10:11:26', '2025-08-20 15:01:41'),
(6, 6, 5, 43, 30.00, 4.50, 'paid', 'khalti', '2025-08-17 10:16:47', '2025-08-20 15:01:41'),
(7, 6, 5, 44, 30.00, 4.50, 'paid', 'khalti', '2025-08-17 10:20:09', '2025-08-20 15:01:41'),
(8, 5, 6, 45, 80.00, 12.00, 'pending', 'khalti', '2025-08-17 10:25:31', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `tblvehicle`
--

CREATE TABLE `tblvehicle` (
  `ID` int(10) NOT NULL,
  `UserId` int(11) DEFAULT NULL,
  `Color` varchar(120) DEFAULT NULL,
  `VehicleModel` varchar(100) NOT NULL,
  `VehicleCategory` varchar(120) DEFAULT NULL,
  `VehicleCompanyname` varchar(120) DEFAULT NULL,
  `RegistrationNumber` varchar(120) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tblvehicle`
--

INSERT INTO `tblvehicle` (`ID`, `UserId`, `Color`, `VehicleModel`, `VehicleCategory`, `VehicleCompanyname`, `RegistrationNumber`) VALUES
(1, 2, 'Red', 'Land Cruser', 'Car', 'Toyota', 'BA-2-CHA-1234'),
(2, 3, 'Black', 'Hornet', 'Motorbike', 'Honda', 'BA-5-PA-5678'),
(6, 5, 'Grey', 'Honda Shine', 'Motorbike', 'Honda', 'BA-5-PA-5340'),
(7, 6, 'Blue', 'Hunter 350', 'Motorbike', 'Royal Enfield', 'BA-2-CHA-9870'),
(12, 5, 'Purple', 'BYD Dolphin', 'Car', 'BYD', 'BA-5-PA-1590'),
(13, 6, 'Orange', 'Deepal S05', 'Car', 'Deepal', 'BA-5-PA-7777');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(100) DEFAULT NULL,
  `name` varchar(191) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `password` varchar(100) DEFAULT NULL,
  `role` varchar(100) DEFAULT NULL,
  `Created_date` date DEFAULT NULL,
  `balance` decimal(10,2) DEFAULT 0.00
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `name`, `email`, `phone`, `password`, `role`, `Created_date`, `balance`) VALUES
(1, 'admin', 'Admin User', 'admin@example.com', '9841000089', 'admin123', 'admin', '2025-08-10', 0.00),
(2, 'john_doe', 'John Doe', 'john@example.com', '9800000555', 'john123', 'user', '2025-08-01', 0.00),
(3, 'mary_smith', 'Mary Smith', 'mary@example.com', '9800000002', 'mary123', 'user', '2025-08-05', 0.00),
(5, 'm.kim', 'mkim User', 'mkim013@proton.me', '9841567851', 'ForMkim013', 'user', '2025-08-10', 90.75),
(6, 'rojarmhj', 'Rojar Maharjan', 'rojarmhj@proton.me', '9841600003', 'ForRojar1', 'user', '2025-08-10', 0.00);

-- --------------------------------------------------------

--
-- Table structure for table `userspace`
--

CREATE TABLE `userspace` (
  `userid` int(11) DEFAULT NULL,
  `spaceid` int(11) DEFAULT NULL,
  `vehicle_id` int(11) DEFAULT NULL,
  `userSpaceId` int(11) NOT NULL,
  `status` enum('1','0') DEFAULT NULL,
  `StartTime` datetime NOT NULL DEFAULT current_timestamp(),
  `EndTime` datetime DEFAULT NULL,
  `Fare` decimal(10,2) NOT NULL DEFAULT 0.00,
  `ParkingNumber` int(11) DEFAULT NULL,
  `vehicleCategoryId` int(10) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `userspace`
--

INSERT INTO `userspace` (`userid`, `spaceid`, `vehicle_id`, `userSpaceId`, `status`, `StartTime`, `EndTime`, `Fare`, `ParkingNumber`, `vehicleCategoryId`) VALUES
(5, 12, 6, 19, '0', '2025-08-13 16:31:12', '2025-08-13 17:00:13', 500.00, 55, 2),
(5, 13, 12, 20, '0', '2025-08-13 16:31:44', '2025-08-13 17:00:37', 500.00, 632, 1),
(5, 12, 6, 22, '0', '2025-08-13 18:31:27', '2025-08-14 12:54:03', 225.00, 855, 2),
(6, 19, 7, 23, '0', '2025-08-13 18:34:49', '2025-08-13 18:36:23', 500.00, 405, 2),
(6, 19, 7, 24, '0', '2025-08-14 10:21:59', '2025-08-14 10:23:10', 80.00, 202, 2),
(5, 12, 6, 25, '0', '2025-08-14 10:39:46', '2025-08-14 10:40:26', 30.00, 776, 2),
(5, 12, 6, 26, '0', '2025-08-14 10:42:35', '2025-08-14 10:42:51', 30.00, 438, 2),
(5, 12, 6, 27, '0', '2025-08-14 10:49:25', '2025-08-14 10:49:42', 30.00, 670, 2),
(5, 13, 12, 28, '0', '2025-08-14 10:55:35', '2025-08-14 10:55:58', 80.00, 821, 1),
(5, 13, 12, 29, '0', '2025-08-14 10:59:28', '2025-08-14 10:59:49', 80.00, 708, 1),
(5, 13, 12, 30, '0', '2025-08-14 11:00:55', '2025-08-14 11:01:08', 80.00, 996, 1),
(5, 13, 12, 31, '0', '2025-08-14 11:04:03', '2025-08-14 11:04:20', 80.00, 623, 1),
(5, 12, 6, 32, '0', '2025-08-14 11:09:16', '2025-08-14 11:09:35', 30.00, 650, 2),
(5, 13, 12, 33, '0', '2025-08-14 11:15:09', '2025-08-14 11:15:30', 80.00, 747, 1),
(6, 17, 7, 34, '0', '2025-08-14 08:26:39', '2025-08-14 12:12:17', 90.00, 792, 2),
(6, 11, 7, 35, '0', '2025-08-14 08:34:38', '2025-08-14 12:19:56', 90.00, 592, 2),
(5, 12, 6, 36, '0', '2025-08-16 06:26:42', '2025-08-16 10:12:19', 90.00, 672, 2),
(2, 13, 1, 37, '0', '2025-08-16 06:28:05', '2025-08-16 10:13:23', 255.00, 341, 1),
(6, 17, 7, 38, '0', '2025-08-16 10:21:46', '2025-08-16 14:07:46', 60.00, 721, 2),
(6, 17, 7, 39, '0', '2025-08-17 08:02:53', '2025-08-17 11:48:37', 90.00, 390, 2),
(6, 17, 7, 40, '0', '2025-08-17 14:46:45', '2025-08-17 14:50:42', 30.00, 297, 2),
(6, 17, 7, 41, '0', '2025-08-17 15:42:14', '2025-08-17 15:43:36', 30.00, 141, 2),
(6, 11, 7, 42, '0', '2025-08-17 15:56:07', '2025-08-17 15:56:26', 30.00, 498, 2),
(6, 19, 7, 43, '0', '2025-08-17 16:01:05', '2025-08-17 16:01:47', 30.00, 233, 2),
(6, 17, 7, 44, '0', '2025-08-17 16:04:53', '2025-08-17 16:05:09', 30.00, 290, 2),
(5, 13, 12, 45, '0', '2025-08-17 16:10:15', '2025-08-17 16:10:31', 80.00, 198, 1),
(5, 12, 6, 46, '0', '2025-08-17 16:21:51', '2025-08-17 16:22:18', 30.00, 188, 2),
(5, 12, 6, 47, '0', '2025-08-17 16:21:59', '2025-08-17 16:22:18', 30.00, 362, 2),
(5, 12, 6, 48, '0', '2025-08-18 14:32:25', '2025-08-19 19:22:08', 465.00, 312, 2),
(6, 17, 7, 49, '1', '2025-08-19 19:10:39', NULL, 0.00, 404, 2);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `email_verifications`
--
ALTER TABLE `email_verifications`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `space`
--
ALTER TABLE `space`
  ADD PRIMARY KEY (`space_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `tblcategory`
--
ALTER TABLE `tblcategory`
  ADD PRIMARY KEY (`ID`);

--
-- Indexes for table `tblpayment`
--
ALTER TABLE `tblpayment`
  ADD PRIMARY KEY (`payment_id`),
  ADD KEY `user_id` (`space_user_id`),
  ADD KEY `booking_id` (`booking_id`);

--
-- Indexes for table `tblvehicle`
--
ALTER TABLE `tblvehicle`
  ADD PRIMARY KEY (`ID`),
  ADD KEY `UserId` (`UserId`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `userspace`
--
ALTER TABLE `userspace`
  ADD PRIMARY KEY (`userSpaceId`),
  ADD UNIQUE KEY `ParkingNumber` (`ParkingNumber`),
  ADD KEY `userid` (`userid`),
  ADD KEY `spaceid` (`spaceid`),
  ADD KEY `fk_vehiclecategory` (`vehicleCategoryId`),
  ADD KEY `fk_userspace_vehicle` (`vehicle_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `email_verifications`
--
ALTER TABLE `email_verifications`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `space`
--
ALTER TABLE `space`
  MODIFY `space_id` int(3) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT for table `tblcategory`
--
ALTER TABLE `tblcategory`
  MODIFY `ID` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `tblpayment`
--
ALTER TABLE `tblpayment`
  MODIFY `payment_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `tblvehicle`
--
ALTER TABLE `tblvehicle`
  MODIFY `ID` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `userspace`
--
ALTER TABLE `userspace`
  MODIFY `userSpaceId` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=50;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `space`
--
ALTER TABLE `space`
  ADD CONSTRAINT `space_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

--
-- Constraints for table `tblpayment`
--
ALTER TABLE `tblpayment`
  ADD CONSTRAINT `tblpayment_ibfk_1` FOREIGN KEY (`space_user_id`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `tblpayment_ibfk_2` FOREIGN KEY (`booking_id`) REFERENCES `userspace` (`userSpaceId`);

--
-- Constraints for table `tblvehicle`
--
ALTER TABLE `tblvehicle`
  ADD CONSTRAINT `tblvehicle_ibfk_1` FOREIGN KEY (`UserId`) REFERENCES `users` (`id`);

--
-- Constraints for table `userspace`
--
ALTER TABLE `userspace`
  ADD CONSTRAINT `fk_userspace_space` FOREIGN KEY (`spaceid`) REFERENCES `space` (`space_id`),
  ADD CONSTRAINT `fk_userspace_user` FOREIGN KEY (`userid`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `fk_userspace_vehicle` FOREIGN KEY (`vehicle_id`) REFERENCES `tblvehicle` (`ID`),
  ADD CONSTRAINT `fk_userspace_vehicleCategory` FOREIGN KEY (`vehicleCategoryId`) REFERENCES `tblcategory` (`ID`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_vehicle` FOREIGN KEY (`vehicle_id`) REFERENCES `tblvehicle` (`ID`),
  ADD CONSTRAINT `fk_vehiclecategory` FOREIGN KEY (`vehicleCategoryId`) REFERENCES `tblvehicle` (`ID`),
  ADD CONSTRAINT `userspace_ibfk_1` FOREIGN KEY (`userid`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `userspace_ibfk_2` FOREIGN KEY (`spaceid`) REFERENCES `space` (`space_id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
