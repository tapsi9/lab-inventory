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
-- Database: `db_inventory`
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
  `status` enum('borrowed','returned') DEFAULT 'borrowed',
  `borrow_date` datetime DEFAULT current_timestamp(),
  `return_date` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tbl_borrowed_items`
--

INSERT INTO `tbl_borrowed_items` (`id`, `user_id`, `item_id`, `quantity`, `status`, `borrow_date`, `return_date`) VALUES
(1, 13, 85, 1, 'returned', '2025-06-08 21:32:57', '2025-06-08 21:33:18'),
(2, 13, 127, 1, 'borrowed', '2025-06-08 21:57:23', NULL);

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
(39, '/uploads/kjQaOHNi.jpg', 'Hemovac', '1', '/uploads/Alveolar Sacs.jpeg'),
(40, '/uploads/eM01zfsD.jpg', 'Steam Vaporizer', '1', '/uploads/Alveolar Sacs.jpeg'),
(42, '/uploads/hwaEhwK7.jpg', 'Electrocardiography', '1', '/uploads/Alveolar Sacs.jpeg'),
(43, '/uploads/4DsRZfJc.jpg', 'O2 Sat Machine', '1', '/uploads/Alveolar Sacs.jpeg'),
(44, '/uploads/571d8PPA.jpg', 'Kelly Pad', '1', '/uploads/Alveolar Sacs.jpeg'),
(45, '/uploads/yH2dN6p2.jpg', 'Big Kelly', '1', '/uploads/Alveolar Sacs.jpeg'),
(46, '/uploads/yH2dN6p2.jpg', 'Tenaculum', '1', '/uploads/Alveolar Sacs.jpeg'),
(47, '/uploads/jezUYFs5.jpg', 'Tissue Forceps', '1', '/uploads/Alveolar Sacs.jpeg'),
(48, '/uploads/jezUYFs5.jpg', 'Ochsner', '1', '/uploads/Alveolar Sacs.jpeg'),
(49, '/uploads/jezUYFs5.jpg', 'Needle Holder', '1', '/uploads/Alveolar Sacs.jpeg'),
(50, '/uploads/jezUYFs5.jpg', 'Metzenbaum', '1', '/uploads/Alveolar Sacs.jpeg'),
(51, '/uploads/Zfg1sXtU.jpg', 'Endoscopy Tip', '1', '/uploads/Alveolar Sacs.jpeg'),
(52, '/uploads/Zfg1sXtU.jpg', 'Frazier Suction Tips', '1', '/uploads/Alveolar Sacs.jpeg'),
(53, '/uploads/Zfg1sXtU.jpg', 'Suction Tips', '1', '/uploads/Alveolar Sacs.jpeg'),
(54, '/uploads/N57Ny6L4.jpg', 'Vaginal Speculum', '1', '/uploads/Alveolar Sacs.jpeg'),
(55, '/uploads/ieKKS8vs.jpg', 'Sutures', '4', '/uploads/Alveolar Sacs.jpeg'),
(56, '/uploads/zlJqLMot.jpg', 'Deaver', '1', '/uploads/Alveolar Sacs.jpeg'),
(57, '/uploads/zlJqLMot.jpg', 'Malleable', '1', '/uploads/Alveolar Sacs.jpeg'),
(58, '/uploads/zlJqLMot.jpg', 'Army Navy', '1', '/uploads/Alveolar Sacs.jpeg'),
(59, '/uploads/zlJqLMot.jpg', 'Richardson', '1', '/uploads/Alveolar Sacs.jpeg'),
(60, '/uploads/zlJqLMot.jpg', 'Mayo-Hegar Needle Holder', '1', '/uploads/Alveolar Sacs.jpeg'),
(61, '/uploads/zlJqLMot.jpg', 'Forester Sponge Forceps', '1', '/uploads/Alveolar Sacs.jpeg'),
(62, '/uploads/zlJqLMot.jpg', 'Mixter Forceps', '1', '/uploads/Alveolar Sacs.jpeg'),
(63, '/uploads/zlJqLMot.jpg', 'Pean', '1', '/uploads/Alveolar Sacs.jpeg'),
(64, '/uploads/zlJqLMot.jpg', 'Bobcock', '1', '/uploads/Alveolar Sacs.jpeg'),
(65, '/uploads/zlJqLMot.jpg', 'Allis', '1', '/uploads/Alveolar Sacs.jpeg'),
(66, '/uploads/zlJqLMot.jpg', 'Straight Kelly', '1', '/uploads/Alveolar Sacs.jpeg'),
(67, '/uploads/zlJqLMot.jpg', 'Mosquito Clamp Curve', '1', '/uploads/Alveolar Sacs.jpeg'),
(68, '/uploads/zlJqLMot.jpg', 'Towel Clips', '1', '/uploads/Alveolar Sacs.jpeg'),
(69, '/uploads/zlJqLMot.jpg', 'Curved and Mayo Scissors', '1', '/uploads/Alveolar Sacs.jpeg'),
(70, '/uploads/zlJqLMot.jpg', 'Metzenbaum Scissors', '1', '/uploads/Alveolar Sacs.jpeg'),
(71, '/uploads/zlJqLMot.jpg', 'Tissue Forceps', '1', '/uploads/Alveolar Sacs.jpeg'),
(72, '/uploads/zlJqLMot.jpg', 'Adson Forceps', '1', '/uploads/Alveolar Sacs.jpeg'),
(73, '/uploads/zlJqLMot.jpg', '(Russian) long & Short plain & w/ Teeth Forceps.', '1', '/uploads/Alveolar Sacs.jpeg'),
(74, '/uploads/vhlOeuFe.jpg', 'Betadine', '1', '/uploads/Alveolar Sacs.jpeg'),
(75, '/uploads/vhlOeuFe.jpg', 'Pick-Up Forceps', '1', '/uploads/Alveolar Sacs.jpeg'),
(76, '/uploads/vhlOeuFe.jpg', 'Gauze (4x4)', '1', '/uploads/Alveolar Sacs.jpeg'),
(77, '/uploads/vhlOeuFe.jpg', 'Kidney Basin', '1', '/uploads/Alveolar Sacs.jpeg'),
(78, '/uploads/vhlOeuFe.jpg', 'Cotton Balls', '1', '/uploads/Alveolar Sacs.jpeg'),
(79, '/uploads/vhlOeuFe.jpg', 'Sterile Gloves', '1', '/uploads/Alveolar Sacs.jpeg'),
(80, '/uploads/vhlOeuFe.jpg', 'Micropore', '1', '/uploads/Alveolar Sacs.jpeg'),
(81, '/uploads/vhlOeuFe.jpg', 'Wound Swab', '1', '/uploads/Alveolar Sacs.jpeg'),
(82, '/uploads/2ubUHO90.jpg', 'Richardson Retractor', '1', '/uploads/Alveolar Sacs.jpeg'),
(83, '/uploads/2ubUHO90.jpg', 'Retractor', '1', '/uploads/Alveolar Sacs.jpeg'),
(84, '/uploads/GAZynUbH.jpg', 'Vaginal Forcep', '1', '/uploads/Alveolar Sacs.jpeg'),
(85, '/uploads/7XIO8vaE.jpg', 'Kidney Basin Mosquito', '6', '/uploads/Alveolar Sacs.jpeg'),
(86, '/uploads/7XIO8vaE.jpg', 'Surgical Blade', '1', '/uploads/Alveolar Sacs.jpeg'),
(87, '/uploads/7XIO8vaE.jpg', 'Thumb Forcep', '1', '/uploads/Alveolar Sacs.jpeg'),
(88, '/uploads/1Znka7_l.jpg', 'Disposable Razor', '1', '/uploads/Alveolar Sacs.jpeg'),
(89, '/uploads/1Znka7_l.jpg', 'Clean Gloves', '1', '/uploads/Alveolar Sacs.jpeg'),
(90, '/uploads/1Znka7_l.jpg', 'Cotton Balls', '1', '/uploads/Alveolar Sacs.jpeg'),
(91, '/uploads/1Znka7_l.jpg', 'Kidney Basin', '1', '/uploads/Alveolar Sacs.jpeg'),
(92, '/uploads/vZ8e9iWl.jpg', 'Surgical Mask', '1', '/uploads/Alveolar Sacs.jpeg'),
(93, '/uploads/vZ8e9iWl.jpg', 'Surgical Gown with Pocket', '1', '/uploads/Alveolar Sacs.jpeg'),
(94, '/uploads/3uxZ85l0.jpg', 'Fingertop Pulse Oximeter', '1', '/uploads/Alveolar Sacs.jpeg'),
(95, '/uploads/3uxZ85l0.jpg', 'Thermometer', '1', '/uploads/Alveolar Sacs.jpeg'),
(96, '/uploads/rkZtxnoL.jpg', 'Bed Bath Set', '1', '/uploads/Alveolar Sacs.jpeg'),
(97, '/uploads/m6kO_1VH.jpg', 'Ophthalmoscope', '1', '/uploads/Alveolar Sacs.jpeg'),
(98, '/uploads/m6kO_1VH.jpg', 'Measuring Tape', '1', '/uploads/Alveolar Sacs.jpeg'),
(99, '/uploads/m6kO_1VH.jpg', 'Reflex Hammer', '1', '/uploads/Alveolar Sacs.jpeg'),
(100, '/uploads/m6kO_1VH.jpg', 'Tongue Depressor', '1', '/uploads/Alveolar Sacs.jpeg'),
(101, '/uploads/9mMePHQe.jpg', 'Medication Tray', '1', '/uploads/Alveolar Sacs.jpeg'),
(102, '/uploads/CA7B2XAj.jpg', 'Glucometer Set', '1', '/uploads/Alveolar Sacs.jpeg'),
(103, '/uploads/Tt8YzCc_.jpg', 'First Aid Kit', '1', '/uploads/Alveolar Sacs.jpeg'),
(104, '/uploads/QXS6JfEe.jpg', 'OB/CHN Bag', '1', '/uploads/Alveolar Sacs.jpeg'),
(105, '/uploads/odhMxkod.jpg', 'Thermometer', '1', '/uploads/Alveolar Sacs.jpeg'),
(106, '/uploads/odhMxkod.jpg', 'Penlight', '1', '/uploads/Alveolar Sacs.jpeg'),
(107, '/uploads/odhMxkod.jpg', 'Tuning Fork', '1', '/uploads/Alveolar Sacs.jpeg'),
(108, '/uploads/DfPfWBf4.jpg', 'Pitcher', '1', '/uploads/Alveolar Sacs.jpeg'),
(109, '/uploads/DfPfWBf4.jpg', 'Bed Pan', '1', '/uploads/Alveolar Sacs.jpeg'),
(110, '/uploads/DfPfWBf4.jpg', 'Cotton Balls', '1', '/uploads/Alveolar Sacs.jpeg'),
(111, '/uploads/DfPfWBf4.jpg', 'Clean Gloves', '1', '/uploads/Alveolar Sacs.jpeg'),
(112, '/uploads/WY68NqcZ.jpg', 'Breast For Physical Assessment', '1', '/uploads/Alveolar Sacs.jpeg'),
(113, '/uploads/WY68NqcZ.jpg', 'Female Reproductive Organ', '1', '/uploads/Alveolar Sacs.jpeg'),
(114, '/uploads/WY68NqcZ.jpg', 'Male Reproductive Organ', '1', '/uploads/Alveolar Sacs.jpeg'),
(115, '/uploads/gSkFOnm6.jpg', 'Bandage Scissors', '1', '/uploads/Alveolar Sacs.jpeg'),
(116, '/uploads/gSkFOnm6.jpg', 'Sterile Gloves', '1', '/uploads/Alveolar Sacs.jpeg'),
(117, '/uploads/gSkFOnm6.jpg', 'Cord Clamp', '1', '/uploads/Alveolar Sacs.jpeg'),
(118, '/uploads/gSkFOnm6.jpg', 'Cotton Balls', '1', '/uploads/Alveolar Sacs.jpeg'),
(119, '/uploads/gSkFOnm6.jpg', 'Alcohol', '1', '/uploads/Alveolar Sacs.jpeg'),
(120, '/uploads/gSkFOnm6.jpg', 'Betadine', '1', '/uploads/Alveolar Sacs.jpeg'),
(121, '/uploads/gSkFOnm6.jpg', 'Gauze (4x4)', '1', '/uploads/Alveolar Sacs.jpeg'),
(122, '/uploads/pxHUhgY8.jpg', 'Baby Bath Set', '1', '/uploads/Alveolar Sacs.jpeg'),
(123, '/uploads/mPy-OY5v.jpg', 'Pediatric Care Supplies', '1', '/uploads/Alveolar Sacs.jpeg'),
(124, '/uploads/lEOiyZsC.jpg', 'MMDST Kit', '1', '/uploads/Alveolar Sacs.jpeg'),
(125, '/uploads/lEOiyZsC.jpg', 'Children LEarning Materials', '1', '/uploads/Alveolar Sacs.jpeg'),
(126, '/uploads/GAAP9RCE.jpg', 'Children Toys', '4', '/uploads/Alveolar Sacs.jpeg'),
(127, '/uploads/pKampvf6.jpg', 'Children Feeding Set', '4', '/uploads/Alveolar Sacs.jpeg'),
(128, '/uploads/gzbePBI_.jpg', 'Newborn Care Set', '5', '/uploads/Alveolar Sacs.jpeg'),
(129, '/uploads/w_Pzc3tW.jpg', 'Labor and Delivery Simulator', '1', '/uploads/Alveolar Sacs.jpeg'),
(130, '/uploads/w_Pzc3tW.jpg', 'Blood Dye Concentrate', '1', '/uploads/Alveolar Sacs.jpeg'),
(131, '/uploads/w_Pzc3tW.jpg', 'Penguin Suction', '1', '/uploads/Alveolar Sacs.jpeg'),
(132, '/uploads/w_Pzc3tW.jpg', 'Fetal Stethoscope', '1', '/uploads/Alveolar Sacs.jpeg'),
(133, '/uploads/w_Pzc3tW.jpg', 'Urine Catheter', '1', '/uploads/Alveolar Sacs.jpeg'),
(134, '/uploads/w_Pzc3tW.jpg', 'Cervix Ribbon', '1', '/uploads/Alveolar Sacs.jpeg'),
(135, '/uploads/w_Pzc3tW.jpg', 'Syringe 20ml', '1', '/uploads/Alveolar Sacs.jpeg'),
(136, '/uploads/w_Pzc3tW.jpg', 'Normal Gloves 1 Pair', '1', '/uploads/Alveolar Sacs.jpeg'),
(137, '/uploads/w_Pzc3tW.jpg', 'Long Gloves 1 Pair', '1', '/uploads/Alveolar Sacs.jpeg'),
(138, '/uploads/w_Pzc3tW.jpg', 'Fluid Drain', '1', '/uploads/Alveolar Sacs.jpeg'),
(139, '/uploads/w_Pzc3tW.jpg', 'Floor Protector', '1', '/uploads/Alveolar Sacs.jpeg'),
(140, '/uploads/w_Pzc3tW.jpg', 'Directions for use', '1', '/uploads/Alveolar Sacs.jpeg'),
(141, '/uploads/BZsjneqG.jpg', 'Human Digestive System', '1', '/uploads/Alveolar Sacs.jpeg'),
(142, '/uploads/busMcx4U.jpg', 'Ears', '1', '/uploads/Alveolar Sacs.jpeg'),
(143, '/uploads/SU5Nw7iQ.jpg', 'Human Digestive System', '1', '/uploads/Alveolar Sacs.jpeg'),
(144, '/uploads/qK5uz6QM.jpg', 'Kidney', '1', '/uploads/Alveolar Sacs.jpeg'),
(145, '/uploads/7VsDO9fD.jpg', 'Lungs', '1', '/uploads/Alveolar Sacs.jpeg'),
(146, '/uploads/MCzoN-Fd.jpg', 'Eyes', '1', '/uploads/Alveolar Sacs.jpeg'),
(147, '/uploads/qxmAHuGU.jpg', 'Green Stick Fracture', '1', '/uploads/Alveolar Sacs.jpeg'),
(148, '/uploads/0pLbl8rE.jpg', 'Human Bones', '1', '/uploads/Alveolar Sacs.jpeg'),
(149, '/uploads/aghAkLF-.jpg', 'First Aid Box', '1', '/uploads/Alveolar Sacs.jpeg'),
(150, '/uploads/NneYgKXC.jpg', 'Cadaver', '1', '/uploads/Alveolar Sacs.jpeg'),
(151, '/uploads/hIIUYmKB.jpg', 'Alveolar Sacs', '1', '/uploads/Alveolar Sacs.jpeg'),
(152, '/uploads/nZ9RSUMx.jpg', 'Eyes', '1', '/uploads/Alveolar Sacs.jpeg'),
(153, '/uploads/nZ9RSUMx.jpg', 'Preserved Speciment', '1', '/uploads/Alveolar Sacs.jpeg'),
(154, '/uploads/nZ9RSUMx.jpg', 'Brain', '1', '/uploads/Alveolar Sacs.jpeg'),
(155, '/uploads/4BniIamp.jpg', 'Skeleton of a Frog', '1', '/uploads/Alveolar Sacs.jpeg');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_users`
--

CREATE TABLE `tbl_users` (
  `id` int(255) NOT NULL,
  `full_name` varchar(255) NOT NULL,
  `user_name` varchar(255) NOT NULL,
  `user_course` varchar(255) NOT NULL,
  `user_email` varchar(255) NOT NULL,
  `user_password` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tbl_users`
--

INSERT INTO `tbl_users` (`id`, `full_name`, `user_name`, `user_course`, `user_email`, `user_password`) VALUES
(6, 'ProgrammerFullName', 'ProgrammerUserName', 'BSIT', 'programmer@yahoo.com', 'c8837b23ff8aaa8a2dde915473ce0991'),
(7, 'Shen Ron', 'Keetrix', 'BSIT', 'keet@gmail.com', 'c8837b23ff8aaa8a2dde915473ce0991'),
(8, 'Nursing Full Name', 'NursingUserName', 'BSN', 'nursing@yahoo.com', '5676cfe1da29ab42311b21eca342a9b3'),
(9, 'Manuel', 'BSCRIM', 'BSCRIM', 'BSCRIM@gmail.com', '21acddd10620910f4a05db7b7bbbd949'),
(10, 'Susan', 'BSTHM', 'BSTHM', 'BSTHM@gmail.com', '928761b0d11d1dd22bcddc27aec4733d'),
(11, 'Katie', 'BSIT', 'BSIT', 'BSIT@gmail.com', '5d8ed9d034659f9496028621f63e3f64'),
(13, 'Abigail', 'BSN', 'BSN', 'BSN@gmail.com', '15b0ad5c0a41b76b7891f2e3a621fd26');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `tbl_borrowed_items`
--
ALTER TABLE `tbl_borrowed_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `item_id` (`item_id`);

--
-- Indexes for table `tbl_items`
--
ALTER TABLE `tbl_items`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tbl_users`
--
ALTER TABLE `tbl_users`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `tbl_borrowed_items`
--
ALTER TABLE `tbl_borrowed_items`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `tbl_items`
--
ALTER TABLE `tbl_items`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=156;

--
-- AUTO_INCREMENT for table `tbl_users`
--
ALTER TABLE `tbl_users`
  MODIFY `id` int(255) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `tbl_borrowed_items`
--
ALTER TABLE `tbl_borrowed_items`
  ADD CONSTRAINT `tbl_borrowed_items_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `tbl_users` (`id`),
  ADD CONSTRAINT `tbl_borrowed_items_ibfk_2` FOREIGN KEY (`item_id`) REFERENCES `tbl_items` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
