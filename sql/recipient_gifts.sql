-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Apr 26, 2025 at 03:07 AM
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
-- Database: `giftspark`
--

-- --------------------------------------------------------

--
-- Table structure for table `recipient_gifts`
--

CREATE TABLE `recipient_gifts` (
  `recipient_id` int(11) NOT NULL,
  `gift_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `recipient_gifts`
--

INSERT INTO `recipient_gifts` (`recipient_id`, `gift_id`) VALUES
(19, 7),
(19, 8);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `recipient_gifts`
--
ALTER TABLE `recipient_gifts`
  ADD PRIMARY KEY (`recipient_id`,`gift_id`),
  ADD KEY `gift_id` (`gift_id`);

--
-- Constraints for dumped tables
--

--
-- Constraints for table `recipient_gifts`
--
ALTER TABLE `recipient_gifts`
  ADD CONSTRAINT `recipient_gifts_ibfk_1` FOREIGN KEY (`recipient_id`) REFERENCES `giftspark_events` (`event_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `recipient_gifts_ibfk_2` FOREIGN KEY (`gift_id`) REFERENCES `gift_ideas` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
