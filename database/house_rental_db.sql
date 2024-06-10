-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jun 10, 2024 at 08:23 PM
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
-- Database: `house_rental_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

CREATE TABLE `categories` (
  `id` int(30) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `name` varchar(200) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`id`, `user_id`, `name`) VALUES
(1, 1, 'Condominum'),
(2, 1, 'Bungalow'),
(3, 1, 'Two-Story');

-- --------------------------------------------------------

--
-- Table structure for table `houses`
--

CREATE TABLE `houses` (
  `id` int(30) NOT NULL,
  `house_no` varchar(50) NOT NULL,
  `category_id` int(30) NOT NULL,
  `description` text NOT NULL,
  `price` double NOT NULL,
  `occupancy_status` enum('Occupied','Vacant','Maintenance') NOT NULL,
  `capacity` enum('1-3','4-6','7-9','10+') NOT NULL,
  `address` varchar(50) NOT NULL,
  `user_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `houses`
--

INSERT INTO `houses` (`id`, `house_no`, `category_id`, `description`, `price`, `occupancy_status`, `capacity`, `address`, `user_id`) VALUES
(1, 'MK334', 1, '', 15000, 'Occupied', '1-3', 'Makati', 1),
(2, 'MK335', 1, '', 15000, 'Occupied', '1-3', 'Makati', 1),
(3, 'MK337', 1, '', 15000, 'Occupied', '1-3', 'Makati', 1),
(4, 'MK421', 1, '', 14000, 'Occupied', '1-3', 'Makati', 1),
(5, 'MK422', 1, '', 14000, 'Occupied', '1-3', 'Makati', 1),
(6, 'LP10A', 2, '', 6500, 'Occupied', '1-3', 'Las Piñas', 1),
(7, 'LP10B', 2, '', 6500, 'Occupied', '1-3', 'Las Piñas', 1),
(8, 'LP11A', 3, '', 8000, 'Vacant', '1-3', 'Las Piñas', 1),
(9, 'LP12A', 3, '', 8500, 'Maintenance', '1-3', 'Las Piñas', 1),
(10, 'LP13A', 3, '', 10000, 'Vacant', '4-6', '', 1),
(11, 'BF30', 2, '', 10000, 'Maintenance', '4-6', 'BF Homes LP', 1),
(12, 'BF31', 2, '', 11000, 'Maintenance', '4-6', 'BF Homes LP', 1),
(13, 'BF8B', 2, '', 9000, 'Occupied', '1-3', 'BF Homes LP', 1),
(14, 'BF8C', 2, '', 7500, 'Occupied', '1-3', 'BF Homes LP', 1),
(15, 'BF9', 3, '', 9500, 'Vacant', '4-6', 'BF Homes LP', 1),
(16, 'BF7', 3, '', 10000, 'Vacant', '4-6', 'BF Homes LP', 1),
(17, 'MNL534', 1, '', 8500, 'Vacant', '1-3', 'Manila', 1),
(18, 'MNL535', 1, '', 8500, 'Maintenance', '1-3', '', 1),
(19, 'MNL533', 1, '', 8500, 'Vacant', '1-3', '', 1),
(20, 'MNL536', 1, '', 8500, 'Vacant', '1-3', '', 1);

-- --------------------------------------------------------

--
-- Table structure for table `payments`
--

CREATE TABLE `payments` (
  `id` int(30) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `tenant_id` int(30) NOT NULL,
  `amount` float NOT NULL,
  `invoice` varchar(50) NOT NULL,
  `date_created` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `payments`
--

INSERT INTO `payments` (`id`, `user_id`, `tenant_id`, `amount`, `invoice`, `date_created`) VALUES
(1, 1, 8, 6500, '', '2024-02-12 00:00:00'),
(4, 1, 8, 6500, '', '2024-01-11 00:00:00'),
(5, 1, 8, 6500, '', '2024-03-12 00:00:00'),
(6, 1, 8, 6500, '', '2024-04-13 00:00:00'),
(7, 1, 8, 6500, '', '2024-05-16 00:00:00'),
(15, 1, 7, 6500, '', '2023-12-16 00:00:00'),
(16, 1, 7, 6500, '', '2024-01-10 00:00:00'),
(17, 1, 7, 4500, '', '2024-02-14 00:00:00'),
(18, 1, 0, 8500, '', '2024-03-07 00:00:00'),
(19, 1, 7, 6500, '', '2024-04-20 00:00:00'),
(20, 1, 7, 6500, '', '2024-05-20 00:00:00'),
(22, 1, 7, 2000, '', '2024-06-07 00:00:00'),
(26, 1, 3, 30000, '', '2023-10-06 00:00:00'),
(27, 1, 3, 15000, '', '2023-12-14 00:00:00'),
(28, 1, 3, 15000, '', '2024-01-01 00:00:00'),
(29, 1, 3, 12000, '', '2024-02-06 00:00:00'),
(30, 1, 3, 18000, '', '2024-03-07 00:00:00'),
(31, 1, 3, 10000, '', '2024-04-11 00:00:00'),
(32, 1, 3, 20000, '', '2024-05-31 00:00:00'),
(33, 1, 5, 14000, '', '2024-05-10 00:00:00'),
(34, 1, 4, 20000, '', '2024-01-11 00:00:00'),
(35, 1, 4, 8000, '', '2024-02-16 00:00:00'),
(36, 1, 4, 14000, '', '2024-03-15 00:00:00'),
(37, 1, 4, 14000, '', '2024-04-12 00:00:00'),
(38, 1, 4, 10000, '', '2024-05-25 00:00:00'),
(39, 1, 2, 30000, '', '2024-04-19 00:00:00'),
(40, 1, 1, 15000, '', '2024-02-16 00:00:00'),
(41, 1, 1, 15000, '', '2024-03-16 00:00:00'),
(42, 1, 1, 15000, '', '2024-04-17 00:00:00'),
(43, 1, 1, 15000, '', '2024-05-16 00:00:00'),
(44, 1, 10, 7500, '', '2024-03-15 00:00:00'),
(45, 1, 10, 7500, '', '2024-04-26 00:00:00'),
(46, 1, 10, 5000, '', '2024-05-18 00:00:00'),
(47, 1, 11, 9000, '', '2024-04-19 00:00:00'),
(48, 1, 11, 6000, '', '2024-05-11 00:00:00');

-- --------------------------------------------------------

--
-- Table structure for table `system_settings`
--

CREATE TABLE `system_settings` (
  `id` int(30) NOT NULL,
  `name` text NOT NULL,
  `email` varchar(200) NOT NULL,
  `contact` varchar(20) NOT NULL,
  `cover_img` text NOT NULL,
  `about_content` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `system_settings`
--

INSERT INTO `system_settings` (`id`, `name`, `email`, `contact`, `cover_img`, `about_content`) VALUES
(1, 'House Rental Management System', 'info@sample.comm', '+6948 8542 623', '1603344720_1602738120_pngtree-purple-hd-business-banner-image_5493.jpg', '&lt;p style=&quot;text-align: center; background: transparent; position: relative;&quot;&gt;&lt;span style=&quot;color: rgb(0, 0, 0); font-family: &amp;quot;Open Sans&amp;quot;, Arial, sans-serif; font-weight: 400; text-align: justify;&quot;&gt;&amp;nbsp;is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry&rsquo;s standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum.&lt;/span&gt;&lt;br&gt;&lt;/p&gt;&lt;p style=&quot;text-align: center; background: transparent; position: relative;&quot;&gt;&lt;br&gt;&lt;/p&gt;&lt;p style=&quot;text-align: center; background: transparent; position: relative;&quot;&gt;&lt;br&gt;&lt;/p&gt;&lt;p&gt;&lt;/p&gt;');

-- --------------------------------------------------------

--
-- Table structure for table `tenants`
--

CREATE TABLE `tenants` (
  `id` int(30) NOT NULL,
  `firstname` varchar(100) NOT NULL,
  `middlename` varchar(100) NOT NULL,
  `lastname` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `contact` varchar(50) NOT NULL,
  `house_id` int(30) NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT 1 COMMENT '1 = active, 0= inactive',
  `date_in` date DEFAULT current_timestamp(),
  `contract_start` date DEFAULT NULL,
  `contract_end` date DEFAULT NULL,
  `user_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tenants`
--

INSERT INTO `tenants` (`id`, `firstname`, `middlename`, `lastname`, `email`, `contact`, `house_id`, `status`, `date_in`, `contract_start`, `contract_end`, `user_id`) VALUES
(1, 'Juan', 'Cruz', 'Santos', 'juan.santos@example.com', '09171234567', 1, 1, '2024-03-04', '2024-02-05', '2025-06-15', 1),
(2, 'Maria', 'Diaz', 'Reyes', 'maria.reyes@example.com', '09181234567', 2, 1, '2024-06-10', '2024-04-10', '2024-12-10', 1),
(3, 'Jose', 'dela Cruz', 'Bautista', 'jose.bautista@example.com', '09192234567', 3, 1, '2025-02-05', '2023-10-06', '2025-10-06', 1),
(4, 'Ana', 'Mendoza', 'Garcia', 'ana.garcia@example.com', '09193234567', 4, 1, '2024-06-10', '2024-01-06', '2025-01-06', 1),
(5, 'Pedro', 'Santos', 'Lopez', 'pedro.lopez@example.com', '09194234567', 5, 1, '2024-05-04', '2024-05-10', '2024-12-10', 1),
(6, 'Elena', 'Reyes', 'Aquino', 'elena.aquino@example.com', '09195234567', 0, 0, '2024-06-10', '2025-01-01', '2025-06-01', 1),
(7, 'Luz', 'Martinez', 'Dela Cruz', 'luz.delacruz@example.com', '09197234567', 6, 1, '2024-06-10', '2023-12-02', '2025-06-02', 1),
(8, 'Rafael', 'Aquino', 'Flores', 'rafael.flores@example.com', '09198234567', 7, 1, '2024-01-17', '2024-01-10', '2025-04-10', 1),
(9, 'Isabel', 'Bautista', 'Ramos', 'isabel.ramos@example.com', '09199234567', 0, 0, '2024-06-10', '2025-01-03', '2026-01-03', 1),
(10, 'Luis', 'Santos', 'Mendoza', 'luis.mendoza@example.com', '09170234567', 14, 1, '2024-06-10', '2024-03-02', '2025-02-02', 1),
(11, 'Teresa', 'Lopez', 'Navarro', 'teresa.navarro@example.com', '09171234568', 13, 1, '2024-06-10', '2024-04-06', '2025-06-06', 1);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(30) NOT NULL,
  `email` varchar(100) NOT NULL,
  `firstname` varchar(100) NOT NULL,
  `lastname` varchar(100) NOT NULL,
  `username` varchar(200) NOT NULL,
  `password` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `email`, `firstname`, `lastname`, `username`, `password`) VALUES
(1, 'cyrusnathaniel.florendo@tup.edu.ph', 'Cyrus', 'Florendo', 'CyFlo', '420e57b017066b44e05ea1577f6e2e12');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `houses`
--
ALTER TABLE `houses`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `payments`
--
ALTER TABLE `payments`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `system_settings`
--
ALTER TABLE `system_settings`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tenants`
--
ALTER TABLE `tenants`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `id` int(30) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `houses`
--
ALTER TABLE `houses`
  MODIFY `id` int(30) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT for table `payments`
--
ALTER TABLE `payments`
  MODIFY `id` int(30) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=49;

--
-- AUTO_INCREMENT for table `system_settings`
--
ALTER TABLE `system_settings`
  MODIFY `id` int(30) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `tenants`
--
ALTER TABLE `tenants`
  MODIFY `id` int(30) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(30) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
