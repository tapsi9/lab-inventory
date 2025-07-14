-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jun 16, 2025 at 05:18 PM
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
-- Database: `db_it`
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
(1, 11, 1, 1, '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2, 11, 1, 1, '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(3, 11, 1, 1, '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(4, 11, 1, 1, '', NULL, NULL),
(5, 11, 4, 1, 'returned', NULL, '2025-06-16 22:47:48'),
(6, 11, 28, 1, 'returned', NULL, '2025-06-16 22:48:17'),
(7, 11, 29, 1, 'borrowed', NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `tbl_items`
--

CREATE TABLE `tbl_items` (
  `id` int(11) NOT NULL,
  `photo` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `quantity` varchar(255) NOT NULL,
  `qr_code` varchar(255) NOT NULL,
  `qr_code_value` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tbl_items`
--

INSERT INTO `tbl_items` (`id`, `photo`, `name`, `quantity`, `qr_code`, `qr_code_value`) VALUES
(1, '/uploads/584fe8c37dbba5462f7c20ba62ce6165.jpeg', 'Complete Crimping Tool', '6', '/uploads/Complete Crimping Tool_qrcode.png', 'item-001'),
(2, '/uploads/8c62f8951429085e04d9b986bc0f6507.jpeg', 'Flat screw driver', '8', '/uploads/Flat screw driver _qrcode.png', 'item-002'),
(3, '/uploads/4834aecd7e0c7d475580cd7b8b507019.jpeg', 'Flexo metro', '8', '/uploads/Flexo metro _qrcode.png', 'item-003'),
(4, '/uploads/30490f5211bed7674f07fbe22571e435.jpeg', 'Cable Tester', '10', '/uploads/Cable Tester _qrcode.png', 'item-004'),
(5, '/uploads/f5a0f12d5ba45d857c69ca1e6a36e8e0.jpeg', 'Multifunction screw driver', '5', '/uploads/Multifunction screw driver _qrcode.png', 'item-005'),
(6, '/uploads/IMG_0002.JPG', 'Mouse', '30', '/uploads/Mouse_qrcode.png', 'item-006'),
(7, '/uploads/IMG_0003.JPG', 'Keyboard ', '30', '/uploads/Keyboard _qrcode.png', 'item-007'),
(8, '/uploads/IMG_0005.JPG', 'Web camera', '4', '/uploads/Web camera _qrcode.png', 'item-008'),
(9, '/uploads/IMG_0006.JPG', 'Monitor ', '30', '/uploads/Monitor _qrcode.png', 'item-009'),
(10, '/uploads/IMG_0007.JPG', 'Headset', '30', '/uploads/_storage_emulated_0_Download_Headset_qrcode.png', 'item-010'),
(11, '/uploads/IMG_0008.JPG', 'System Unit ', '30', '/uploads/System Unit _qrcode.png', 'item-011'),
(12, '/uploads/IMG_0012.JPG', 'AVR', '30', '/uploads/AVR_qrcode.png', 'item-012'),
(13, '/uploads/IMG_0013.JPG', 'LAN cable', '6', '/uploads/LAN cable _qrcode.png', 'item-013'),
(14, '/uploads/IMG_0014.JPG', 'VGA', '2', '/uploads/VGA_qrcode.png', 'item-014'),
(15, '/uploads/IMG_0015.JPG', 'Mouse pad ', '30', '/uploads/Mouse pad _qrcode.png', 'item-015'),
(16, '/uploads/IMG_9999.JPG', 'Television ', '1', '/uploads/Television _qrcode.png', 'item-016'),
(17, '/uploads/IMG_20250421_161143.jpeg', 'Battery ', '5', '/uploads/Battery _qrcode.png', 'item-017'),
(18, '/uploads/IMG_20250421_161329.jpeg', 'Flashlight ', '5', '/uploads/Flashlight _qrcode.png', 'item-018'),
(19, '/uploads/IMG_20250421_161729.jpeg', 'Wire Tracker', '5', '/uploads/Wire Tracker _qrcode.png', 'item-019'),
(20, '/uploads/IMG_20250421_162716.jpeg', 'Printer', '1', '/uploads/_storage_emulated_0_Download_Printer _qrcode.png', 'item-020'),
(29, '/uploads/685031f337ddc.png', 'EXAMPLE ITEM', '4', '', NULL);

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
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `tbl_items`
--
ALTER TABLE `tbl_items`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=30;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
