-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Apr 03, 2025 at 06:38 PM
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
-- Database: `mhaskarp_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `giftspark_events`
--

CREATE TABLE `giftspark_events` (
  `event_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `person_name` varchar(255) NOT NULL,
  `birthday_date` date NOT NULL,
  `reminder_time` datetime NOT NULL,
  `color_theme` varchar(20) NOT NULL,
  `notes` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `giftspark_events`
--

INSERT INTO `giftspark_events` (`event_id`, `user_id`, `person_name`, `birthday_date`, `reminder_time`, `color_theme`, `notes`) VALUES
(8, 1, 'doogie box 123', '2004-12-12', '1222-12-12 12:12:00', '#8a0000', 'skip'),
(9, 1, 'new person', '2006-07-31', '2025-04-23 12:10:00', '#000000', 'n332ro edited');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `giftspark_events`
--
ALTER TABLE `giftspark_events`
  ADD PRIMARY KEY (`event_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `giftspark_events`
--
ALTER TABLE `giftspark_events`
  MODIFY `event_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
