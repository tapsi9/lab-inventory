-- Active: 1740143789218@@127.0.0.1@3306@db_criminology
-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jun 16, 2025 at 05:19 PM
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
-- Database: `db_criminology`
--

-- --------------------------------------------------------

--
-- Table structure for table `tbl_borrowed_items`
--

CREATE TABLE `tbl_borrowed_items` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `item_id` int(11) NOT NULL,
  `quantity` int(11) NOT NULL,
  `status` enum('borrowed','returned','','') DEFAULT NULL,
  `borrow_date` datetime DEFAULT NULL,
  `return_date` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tbl_borrowed_items`
--

INSERT INTO `tbl_borrowed_items` (`id`, `user_id`, `item_id`, `quantity`, `status`, `borrow_date`, `return_date`) VALUES
(1, 9, 9, 1, 'returned', NULL, '2025-06-16 21:43:49'),
(2, 9, 9, 1, 'returned', NULL, '2025-06-16 21:43:50'),
(3, 9, 10, 1, 'returned', NULL, '2025-06-16 21:56:20');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_items`
--

CREATE TABLE `tbl_items` (
  `id` int(11) NOT NULL,
  `photo` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `quantity` varchar(255) NOT NULL,
  `qr_code` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tbl_items`
--

INSERT INTO `tbl_items` (`id`, `photo`, `name`, `quantity`, `qr_code`) VALUES
(1, '/uploads/Analytical Balance.jpeg', 'Analytical Balance', '1', '/uploads/qr.jpeg'),
(2, '/uploads/Bullet Recovery Box.jpeg', 'Bullet Recovery Box', '1', '/uploads/qr.jpeg'),
(3, '/uploads/Camera.jpeg', 'Camera', '1', '/uploads/qr.jpeg'),
(4, '/uploads/Card Holder.jpeg', 'Card Holder', '1', '/uploads/qr.jpeg'),
(5, '/uploads/Computerized polygraph system.jpeg', 'Computerized polygraph system', '1', '/uploads/qr.jpeg'),
(6, '/uploads/Crime Scene Drafting kit.jpeg', 'Crime Scene Drafting kit', '1', '/uploads/qr.jpeg'),
(7, '/uploads/Developer.jpeg', 'Developer', '1', '/uploads/qr.jpeg'),
(8, '/uploads/Enlarger.jpeg', 'Enlarger', '1', '/uploads/qr.jpeg'),
(9, '/uploads/Evidence tape.jpeg', 'Evidence tape', '5', '/uploads/qr.jpeg'),
(10, '/uploads/Films  Black & White Negative.jpeg', 'Films  Black & White Negative', '5', '/uploads/qr.jpeg'),
(11, '/uploads/Films Color Plus.jpeg', 'Films Color Plus', '4', '/uploads/qr.jpeg'),
(12, '/uploads/Finger print case.jpeg', 'Finger print case', '1', '/uploads/qr.jpeg'),
(13, '/uploads/Fingerprint brush.jpeg', 'Fingerprint brush', '5', '/uploads/qr.jpeg'),
(14, '/uploads/Fingerprint Lifting Tape.jpeg', 'Fingerprint Lifting Tape', '1', '/uploads/qr.jpeg'),
(15, '/uploads/Illuminated Magnifier.jpeg', 'Illuminated Magnifier', '13', '/uploads/qr.jpeg'),
(16, '/uploads/Ink Roller.jpeg', 'Ink Roller', '2', '/uploads/qr.jpeg'),
(17, '/uploads/Ink slide.jpeg', 'Ink slide', '2', '/uploads/qr.jpeg'),
(18, '/uploads/Latent Fingerprint Powder.jpeg', 'Latent Fingerprint Powder', '1', '/uploads/qr.jpeg'),
(19, '/uploads/Magnifying glass.jpeg', 'Magnifying glass', '14', '/uploads/qr.jpeg'),
(20, '/uploads/Narcotics Analysis Reagent kit.jpeg', 'Narcotics Analysis Reagent kit', '1', '/uploads/qr.jpeg'),
(21, '/uploads/Photographic paper.jpeg', 'Photographic paper', '4', '/uploads/qr.jpeg'),
(22, '/uploads/REEL.jpeg', 'REEL', '1', '/uploads/qr.jpeg'),
(23, '/uploads/Ridge Counter.jpeg', 'Ridge Counter', '2', '/uploads/qr.jpeg'),
(24, '/uploads/Shadow Graph.jpeg', 'Shadow Graph', '1', '/uploads/qr.jpeg'),
(25, '/uploads/Silk Black.jpeg', 'Silk Black', '2', '/uploads/qr.jpeg'),
(26, '/uploads/Timer.jpeg', 'Timer', '2', '/uploads/qr.jpeg'),
(27, '/uploads/Trays.jpeg', 'Trays', '4', '/uploads/qr.jpeg'),
(28, '/uploads/Tripod.jpeg', 'Tripod', '2', '/uploads/qr.jpeg'),
(29, '/uploads/494575506_1429197498265428_1303791818217228012_n.jpg', 'Example Name', '8', '/uploads/Alveolar Sacs.jpeg');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_users`
--

CREATE TABLE `tbl_users` (
  `id` int(11) NOT NULL,
  `user_name` int(11) NOT NULL,
  `user_email` int(11) NOT NULL,
  `user_course` int(11) NOT NULL,
  `user_password` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `tbl_borrowed_items`
--
ALTER TABLE `tbl_borrowed_items`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tbl_items`
--
ALTER TABLE `tbl_items`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `tbl_borrowed_items`
--
ALTER TABLE `tbl_borrowed_items`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `tbl_items`
--
ALTER TABLE `tbl_items`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=30;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
