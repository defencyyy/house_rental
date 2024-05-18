-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Apr 30, 2024 at 04:30 PM
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
  `name` varchar(200) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`id`, `name`) VALUES
(1, 'Studio-Type Home'),
(2, 'Bungalow Home'),
(3, 'Two-Story Home'),
(4, 'Other Type');

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
  `address` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `houses`
--

INSERT INTO `houses` (`id`, `house_no`, `category_id`, `description`, `price`, `occupancy_status`, `capacity`, `address`) VALUES
(1, '625', 4, 'Sample', 2500, 'Occupied', '1-3', ''),
(5, '622', 2, '', 6000, 'Vacant', '1-3', ''),
(6, '623', 1, '', 5000, 'Occupied', '1-3', ''),
(7, '620', 1, '', 7000, 'Vacant', '1-3', ''),
(8, '621', 1, '', 2222, 'Vacant', '1-3', '');

-- --------------------------------------------------------

--
-- Table structure for table `payments`
--

CREATE TABLE `payments` (
  `id` int(30) NOT NULL,
  `tenant_id` int(30) NOT NULL,
  `amount` float NOT NULL,
  `invoice` varchar(50) NOT NULL,
  `date_created` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `payments`
--

INSERT INTO `payments` (`id`, `tenant_id`, `amount`, `invoice`, `date_created`) VALUES
(1, 2, 2500, '123456', '2020-10-26 11:29:35'),
(2, 2, 7500, '136654', '2020-10-26 11:30:21'),
(3, 3, 6000, '', '2024-04-29 14:28:33');

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
  `contract_end` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tenants`
--

INSERT INTO `tenants` (`id`, `firstname`, `middlename`, `lastname`, `email`, `contact`, `house_id`, `status`, `date_in`, `contract_start`, `contract_end`) VALUES
(2, 'Johns', 'C', 'Smiths', 'jsmith@sample.com', '+18456-5455-55', 1, 1, '2020-07-02', NULL, NULL),
(3, 'AJ', 'C', 'Quiambao', 'aj@gmail.com', '0916', 3, 1, '2024-03-15', '2024-04-30', '2025-04-30'),
(4, 'Da', 'Dog', 'Pan', 'pandog@gmail.com', '9123823211', 4, 0, '0000-00-00', '2024-05-03', '2025-05-03'),
(5, 'Dog', 'Per', 'Panda', 'pandog@gmail.com', '92138893123', 4, 0, '0000-00-00', '2024-04-24', '2025-04-24'),
(6, '', '', '', '', '', 0, 1, '0000-00-00', '0000-00-00', '0000-00-00'),
(7, '33', '33', '123', '32131', '213213', 4, 0, '0000-00-00', '0000-00-00', '0000-00-00'),
(8, '', '', 'Cyka', '', '', 4, 0, '2024-04-30', '0000-00-00', '0000-00-00'),
(9, '', '', 'Cyka', '', '', 4, 0, '0000-00-00', '0000-00-00', '0000-00-00'),
(10, 'Loda', 'Loda', 'Panda ', '', '', 0, 1, '0000-00-00', '0000-00-00', '0000-00-00'),
(11, '', '', 'Pando', '', '', 5, 0, '2024-04-30', '0000-00-00', '0000-00-00'),
(12, 'Kekok', '', 'Pando', '', '', 5, 0, '2024-04-30', '0000-00-00', '0000-00-00'),
(13, 'Kekok', '', 'Panda', '', '', 5, 1, '2024-04-30', '0000-00-00', '0000-00-00'),
(14, '', '', '', '', '', 0, 1, '0000-00-00', '0000-00-00', '0000-00-00');

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
(2, 'aeronjquiambao@gmail.com', 'AERON JUSTIN', 'QUIAMBAO', 'Aj', '202cb962ac59075b964b07152d234b70'),
(3, 'uyyn90786@gmail.com', 'Justa', 'Kori', 'Kim', '827ccb0eea8a706c4c34a16891f84e7b'),
(4, 'aeronjustin.quiambao@tup.edu.ph', 'Summer', 'Solstice', 'Doggy', '202cb962ac59075b964b07152d234b70'),
(5, 'piningcalautit@gmail.com', 'Justa', 'Kori', 'are', 'b0baee9d279d34fa1dfd71aadb908c3f');

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
  MODIFY `id` int(30) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `houses`
--
ALTER TABLE `houses`
  MODIFY `id` int(30) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `payments`
--
ALTER TABLE `payments`
  MODIFY `id` int(30) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `system_settings`
--
ALTER TABLE `system_settings`
  MODIFY `id` int(30) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `tenants`
--
ALTER TABLE `tenants`
  MODIFY `id` int(30) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(30) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
