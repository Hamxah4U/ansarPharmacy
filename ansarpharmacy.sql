-- phpMyAdmin SQL Dump
-- version 5.1.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Oct 24, 2025 at 06:19 PM
-- Server version: 10.4.21-MariaDB
-- PHP Version: 7.4.25

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `shafa`
--

-- --------------------------------------------------------

--
-- Table structure for table `customers_tbl`
--

CREATE TABLE `customers_tbl` (
  `id` int(11) NOT NULL,
  `Fullname` varchar(100) NOT NULL,
  `phone` varchar(16) NOT NULL,
  `email` varchar(200) DEFAULT NULL,
  `password` varchar(250) NOT NULL,
  `address` varchar(200) NOT NULL,
  `created_by` varchar(200) NOT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `department_tbl`
--

CREATE TABLE `department_tbl` (
  `deptID` int(11) NOT NULL,
  `Department` varchar(50) NOT NULL,
  `Status` varchar(50) NOT NULL DEFAULT 'Active',
  `registerby` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `department_tbl`
--

INSERT INTO `department_tbl` (`deptID`, `Department`, `Status`, `registerby`) VALUES
(7, 'Ansar Pharmacy', 'Active', 'muhammadushafa@gmail.com');

-- --------------------------------------------------------

--
-- Table structure for table `financecollect_tbl`
--

CREATE TABLE `financecollect_tbl` (
  `id` int(11) NOT NULL,
  `Collectorname` varchar(100) NOT NULL,
  `Amount` decimal(10,2) NOT NULL,
  `Reason` text NOT NULL,
  `Givername` varchar(100) NOT NULL,
  `Dateissued` datetime NOT NULL DEFAULT current_timestamp(),
  `Timegive` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `product_tbl`
--

CREATE TABLE `product_tbl` (
  `proID` int(11) NOT NULL,
  `Department` int(11) NOT NULL,
  `Productname` varchar(50) NOT NULL,
  `Price` varchar(200) NOT NULL,
  `Status` varchar(20) NOT NULL DEFAULT 'Active',
  `DateRegister` date NOT NULL,
  `TimeRegister` time NOT NULL,
  `RegisterBy` varchar(50) NOT NULL,
  `UpdateBy` varchar(100) DEFAULT NULL,
  `DateUpdate` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `supply_tbl`
--

CREATE TABLE `supply_tbl` (
  `SupplyID` int(11) NOT NULL,
  `Department` int(11) NOT NULL,
  `ProductName` varchar(255) NOT NULL,
  `Quantity` int(11) DEFAULT NULL,
  `Price` float NOT NULL,
  `wholesaleprice` float NOT NULL,
  `Pprice` float NOT NULL,
  `UnitPack` int(11) NOT NULL,
  `pack` int(11) NOT NULL,
  `ExpiryDate` date NOT NULL,
  `SupplyDate` datetime NOT NULL DEFAULT current_timestamp(),
  `RecordedBy` varchar(255) DEFAULT NULL,
  `Status` varchar(20) NOT NULL DEFAULT 'Active',
  `StockQuantity` int(11) GENERATED ALWAYS AS (`Quantity` * `UnitPack`) STORED
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `tikvaah_transaction`
--

CREATE TABLE `tikvaah_transaction` (
  `id` int(11) NOT NULL,
  `code` varchar(100) NOT NULL,
  `customer_name` varchar(100) NOT NULL,
  `service` varchar(100) NOT NULL,
  `price` decimal(10,0) NOT NULL,
  `qty` int(11) NOT NULL,
  `amount` int(11) GENERATED ALWAYS AS (`price` * `qty`) STORED,
  `tdate` date NOT NULL,
  `ttime` time NOT NULL,
  `tuser` varchar(100) NOT NULL,
  `status` varchar(20) NOT NULL DEFAULT 'paid'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `tikvaah_transaction`
--

INSERT INTO `tikvaah_transaction` (`id`, `code`, `customer_name`, `service`, `price`, `qty`, `tdate`, `ttime`, `tuser`, `status`) VALUES
(10, '250712307281507', 'work in customer', '34', '50', 3, '2025-07-12', '11:51:55', 'usmanabubakarelder128@gmail.com', 'paid'),
(11, '250712651722185', 'work in customer', '34', '50', 10, '2025-07-12', '11:52:43', 'usmanabubakarelder128@gmail.com', 'paid'),
(12, '250712269150514', 'work in customer', '34', '200', 1, '2025-07-12', '11:53:48', 'usmanabubakarelder128@gmail.com', 'paid'),
(14, '250712390230260', 'Aliyu Pali', '36', '1000', 1, '2025-07-12', '14:25:26', 'hamxah4u@gmail.com', 'paid'),
(15, '250712882332200', 'work in customer', '36', '1000', 1, '2025-07-12', '16:10:29', 'hamxah4u@gmail.com', 'paid'),
(16, '250713139219020', 'work in customer', '34', '50', 6, '2025-07-13', '05:24:57', 'usmanabubakarelder128@gmail.com', 'paid'),
(17, '250713764316760', 'work in customer', '34', '50', 1, '2025-07-13', '06:06:05', 'usmanabubakarelder128@gmail.com', 'paid'),
(18, '250713870406710', 'work in customer', '34', '50', 4, '2025-07-13', '07:05:51', 'usmanabubakarelder128@gmail.com', 'paid'),
(19, '250713342922949', 'work in customer', '34', '70', 33, '2025-07-13', '10:36:23', 'usmanabubakarelder128@gmail.com', 'paid'),
(20, '250713895142368', 'work in customer', '34', '45', 220, '2025-07-13', '10:46:12', 'usmanabubakarelder128@gmail.com', 'paid'),
(21, '250713876077591', 'work in customer', '36', '500', 1, '2025-07-13', '12:45:26', 'usmanabubakarelder128@gmail.com', 'paid'),
(22, '250714929893071', 'work in customer', '34', '50', 1, '2025-07-14', '04:51:55', 'usmanabubakarelder128@gmail.com', 'paid'),
(23, '250714962176905', 'work in customer', '34', '50', 5, '2025-07-14', '03:27:08', 'usmanabubakarelder128@gmail.com', 'paid'),
(24, '250714626854914', 'work in customer', '34', '50', 4, '2025-08-01', '14:40:29', 'usmanabubakarelder128@gmail.com', 'paid'),
(25, '250714780190184', 'work in customer', '7', '50', 6, '2025-08-01', '15:14:25', 'usmanabubakarelder128@gmail.com', 'paid'),
(27, '250801788762193', 'work in customer', '7', '3000', 1, '2025-08-01', '14:33:49', 'usmanabubakarelder128@gmail.com', 'paid'),
(28, '250801848130696', 'work in customer', '7', '50', 2, '2025-08-01', '18:39:31', 'usmanabubakarelder128@gmail.com', 'paid'),
(29, '250801842078238', 'work in customer', '36', '1000', 1, '2025-08-01', '18:43:53', 'usmanabubakarelder128@gmail.com', 'paid'),
(30, '250801733532549', 'work in customer', '7', '50', 2, '2025-08-01', '19:41:43', 'usmanabubakarelder128@gmail.com', 'paid'),
(31, '250802784342573', 'work in customer', '7', '400', 8, '2025-08-02', '12:10:09', 'usmanabubakarelder128@gmail.com', 'paid'),
(32, '250802204497876', 'RAHAMA', '38', '1500', 1, '2025-08-02', '16:56:05', 'usmanabubakarelder128@gmail.com', 'paid'),
(33, '250803230380780', 'work in customer', '7', '100', 102, '2025-08-03', '11:16:56', 'usmanabubakarelder128@gmail.com', 'paid'),
(34, '250803550664708', 'work in customer', '7', '100', 2, '2025-08-03', '13:39:08', 'usmanabubakarelder128@gmail.com', 'paid'),
(35, '250803773789351', 'work in customer', '36', '1000', 1, '2025-08-03', '15:05:12', 'usmanabubakarelder128@gmail.com', 'paid'),
(36, '250803551728451', 'work in customer', '7', '200', 1, '2025-08-03', '15:51:02', 'usmanabubakarelder128@gmail.com', 'paid'),
(37, '250803482060404', 'work in customer', '7', '50', 10, '2025-08-03', '17:18:06', 'usmanabubakarelder128@gmail.com', 'paid'),
(38, '250803482060404', 'work in customer', '7', '1500', 1, '2025-08-03', '17:18:18', 'usmanabubakarelder128@gmail.com', 'paid'),
(39, '250803284184558', 'work in customer', '7', '1000', 1, '2025-08-03', '17:18:45', 'usmanabubakarelder128@gmail.com', 'paid'),
(40, '250803558467267', 'work in customer', '38', '500', 1, '2025-08-03', '20:47:05', 'usmanabubakarelder128@gmail.com', 'paid'),
(41, '250804883349069', 'work in customer', '7', '50', 4, '2025-08-04', '12:38:15', 'usmanabubakarelder128@gmail.com', 'paid'),
(42, '250804650771676', 'work in customer', '38', '800', 1, '2025-08-04', '15:26:58', 'usmanabubakarelder128@gmail.com', 'paid'),
(43, '250804650771676', 'work in customer', '7', '50', 12, '2025-08-04', '15:27:34', 'usmanabubakarelder128@gmail.com', 'paid'),
(44, '250804534784146', 'Abba Dan-sara', '36', '1500', 1, '2025-08-04', '18:39:40', 'usmanabubakarelder128@gmail.com', 'paid'),
(45, '250804629981607', 'work in customer', '7', '1', 50, '2025-08-04', '16:29:18', 'usmanabubakarelder128@gmail.com', 'paid'),
(46, '250804348381357', 'work in customer', '7', '50', 6, '2025-08-04', '16:37:30', 'usmanabubakarelder128@gmail.com', 'paid'),
(47, '250804408006880', 'work in customer', '38', '500', 1, '2025-08-04', '16:31:38', 'usmanabubakarelder128@gmail.com', 'paid'),
(48, '250805924359262', 'work in customer', '36', '1000', 1, '2025-08-05', '11:01:35', 'usmanabubakarelder128@gmail.com', 'paid'),
(49, '250805117797631', 'work in customer', '7', '50', 1, '2025-08-05', '12:06:07', 'usmanabubakarelder128@gmail.com', 'paid'),
(50, '250805117797631', 'work in customer', '7', '100', 1, '2025-08-05', '12:10:45', 'usmanabubakarelder128@gmail.com', 'paid'),
(51, '250805205200939', 'work in customer', '7', '500', 2, '2025-08-05', '14:20:11', 'usmanabubakarelder128@gmail.com', 'paid'),
(52, '250805692277592', 'work in customer', '7', '50', 2, '2025-08-05', '14:34:01', 'usmanabubakarelder128@gmail.com', 'paid'),
(53, '250805218014976', 'musa', '7', '0', 3, '2025-08-05', '16:47:56', 'usmanabubakarelder128@gmail.com', 'paid'),
(54, '250805975643282', 'Hauwa\'u', '38', '2000', 1, '2025-08-05', '17:17:18', 'usmanabubakarelder128@gmail.com', 'paid'),
(55, '250805857740136', 'Dauda', '7', '100', 1, '2025-08-05', '17:35:16', 'usmanabubakarelder128@gmail.com', 'paid'),
(56, '250805857740136', 'work in customer', '34', '50', 1, '2025-08-05', '17:35:55', 'usmanabubakarelder128@gmail.com', 'paid'),
(57, '250805215478913', 'work in customer', '7', '50', 2, '2025-08-05', '17:36:30', 'usmanabubakarelder128@gmail.com', 'paid'),
(58, '250805583768830', 'work in customer', '7', '100', 10, '2025-08-05', '18:30:01', 'usmanabubakarelder128@gmail.com', 'paid'),
(59, '250805744819287', 'work in customer', '36', '1000', 1, '2025-08-05', '18:57:41', 'usmanabubakarelder128@gmail.com', 'paid'),
(60, '250806272357582', 'work in customer', '36', '5000', 1, '2025-08-06', '15:03:17', 'hamxah4u@gmail.com', 'paid'),
(62, '250806884234157', 'work in customer', '34', '3350', 1, '2025-08-06', '15:03:44', 'hamxah4u@gmail.com', 'paid'),
(63, '250807538582427', 'work in customer', '34', '50', 2, '2025-08-07', '12:09:01', 'hamxah4u@gmail.com', 'paid'),
(64, '250807358481759', 'Anas Ismail', '34', '50', 2, '2025-08-07', '17:45:17', 'hamxah4u@gmail.com', 'paid'),
(65, '250807293825687', 'Malam Bashar', '34', '50', 4, '2025-08-07', '17:52:36', 'hamxah4u@gmail.com', 'paid'),
(66, '250808791512475', 'work in customer', '36', '1000', 1, '2025-08-08', '00:47:19', 'usmanabubakarelder128@gmail.com', 'paid'),
(67, '250808730148168', 'work in customer', '34', '1500', 1, '2025-08-08', '02:10:48', 'usmanabubakarelder128@gmail.com', 'paid'),
(68, '250809506337741', 'Titi', '36', '1000', 1, '2025-08-08', '01:30:59', 'hamxah4u@gmail.com', 'paid'),
(69, '250809506337741', 'Muazu', '36', '1000', 1, '2025-08-08', '01:31:12', 'hamxah4u@gmail.com', 'paid'),
(70, '250809506337741', 'Amatullahi', '36', '2000', 1, '2025-08-08', '01:31:27', 'hamxah4u@gmail.com', 'paid'),
(71, '250812426889258', 'work in customer', '34', '50', 6, '2025-08-12', '09:57:34', 'usmanabubakarelder128@gmail.com', 'paid'),
(72, '250812341871100', 'work in customer', '34', '50', 1, '2025-08-12', '10:30:47', 'usmanabubakarelder128@gmail.com', 'paid'),
(73, '250812180419248', 'Muhammadu', '36', '2000', 1, '2025-08-12', '10:53:38', 'usmanabubakarelder128@gmail.com', 'paid'),
(75, '250812858228907', 'work in customer', '7', '100', 30, '2025-08-12', '09:37:41', 'usmanabubakarelder128@gmail.com', 'paid'),
(76, '250813762198037', 'work in customer', '34', '50', 80, '2025-08-13', '18:25:40', 'usmanabubakarelder128@gmail.com', 'paid'),
(77, '250813762198037', 'work in customer', '36', '1000', 1, '2025-08-13', '18:25:48', 'usmanabubakarelder128@gmail.com', 'paid'),
(78, '250813229119750', 'work in customer', '7', '100', 1, '2025-08-13', '21:38:31', 'usmanabubakarelder128@gmail.com', 'paid'),
(79, '250814705063760', 'work in customer', '38', '500', 1, '2025-08-14', '11:12:21', 'usmanabubakarelder128@gmail.com', 'paid'),
(80, '250814595816013', 'work in customer', '34', '50', 6, '2025-08-14', '11:21:11', 'usmanabubakarelder128@gmail.com', 'paid'),
(81, '250814805372910', 'Yabanya', '7', '100', 1, '2025-08-14', '11:46:16', 'usmanabubakarelder128@gmail.com', 'paid'),
(82, '250814270071552', 'work in customer', '34', '40', 1, '2025-08-14', '12:45:30', 'usmanabubakarelder128@gmail.com', 'paid'),
(83, '250814299651540', 'work in customer', '34', '10', 1, '2025-08-14', '12:50:48', 'usmanabubakarelder128@gmail.com', 'paid'),
(84, '250815277186098', 'work in customer', '7', '100', 1, '2025-08-15', '12:11:48', 'usmanabubakarelder128@gmail.com', 'paid'),
(85, '250815689939427', 'work in customer', '36', '200', 1, '2025-08-15', '15:29:41', 'usmanabubakarelder128@gmail.com', 'paid'),
(86, '250816113699290', 'work in customer', '34', '50', 1, '2025-08-16', '10:03:21', 'usmanabubakarelder128@gmail.com', 'paid'),
(87, '250816113699290', 'work in customer', '34', '50', 1, '2025-08-16', '10:03:21', 'usmanabubakarelder128@gmail.com', 'paid'),
(88, '250816273061933', 'work in customer', '7', '100', 1, '2025-08-16', '10:57:51', 'usmanabubakarelder128@gmail.com', 'paid'),
(89, '250816273061933', 'work in customer', '37', '400', 1, '2025-08-16', '10:58:24', 'usmanabubakarelder128@gmail.com', 'paid'),
(90, '250816657853418', 'work in customer', '7', '100', 7, '2025-08-16', '11:16:34', 'usmanabubakarelder128@gmail.com', 'paid'),
(91, '250817940747746', 'work in customer', '7', '100', 3, '2025-08-17', '11:09:04', 'usmanabubakarelder128@gmail.com', 'paid'),
(92, '250817543641637', 'work in customer', '34', '50', 6, '2025-08-17', '11:44:49', 'usmanabubakarelder128@gmail.com', 'paid'),
(93, '250817543641637', 'work in customer', '7', '50', 4, '2025-08-17', '11:51:47', 'usmanabubakarelder128@gmail.com', 'paid'),
(94, '250818664249247', 'work in customer', '38', '2300', 1, '2025-08-18', '18:16:38', 'usmanabubakarelder128@gmail.com', 'paid'),
(95, '250818664249247', 'work in customer', '7', '1000', 1, '2025-08-18', '18:16:57', 'usmanabubakarelder128@gmail.com', 'paid'),
(96, '250818664249247', 'work in customer', '36', '4000', 2, '2025-08-18', '18:17:51', 'usmanabubakarelder128@gmail.com', 'paid'),
(97, '250818664249247', 'work in customer', '36', '1500', 1, '2025-08-18', '18:18:13', 'usmanabubakarelder128@gmail.com', 'paid'),
(98, '250818664249247', 'work in customer', '38', '80000', 1, '2025-08-18', '18:18:36', 'usmanabubakarelder128@gmail.com', 'paid'),
(99, '250818581719727', 'work in customer', '7', '6000', 1, '2025-08-18', '19:20:31', 'usmanabubakarelder128@gmail.com', 'paid'),
(100, '250818572537126', 'work in customer', '37', '2000', 1, '2025-08-18', '19:21:28', 'usmanabubakarelder128@gmail.com', 'paid'),
(101, '250830824753563', 'work in customer', '7', '50', 2, '2025-08-30', '10:55:56', 'usmanabubakarelder128@gmail.com', 'paid'),
(102, '250818833320713', 'work in customer', '7', '2550', 1, '2025-08-30', '21:59:55', 'usmanabubakarelder128@gmail.com', 'paid'),
(103, '250818833320713', 'work in customer', '34', '50', 15, '2025-08-30', '22:00:29', 'usmanabubakarelder128@gmail.com', 'paid'),
(104, '250818833320713', 'work in customer', '38', '700', 1, '2025-08-30', '22:00:50', 'usmanabubakarelder128@gmail.com', 'paid'),
(105, '250830675290760', 'work in customer', '7', '150', 1, '2025-08-30', '19:19:49', 'usmanabubakarelder128@gmail.com', 'paid'),
(106, '250901396296742', 'work in customer', '7', '50', 4, '2025-09-01', '21:28:28', 'usmanabubakarelder128@gmail.com', 'paid'),
(107, '250901396296742', 'work in customer', '38', '300', 1, '2025-09-01', '21:28:45', 'usmanabubakarelder128@gmail.com', 'paid'),
(108, '250901396296742', 'work in customer', '7', '100', 2, '2025-09-01', '21:28:53', 'usmanabubakarelder128@gmail.com', 'paid'),
(109, '250902420233431', 'work in customer', '38', '300', 1, '2025-09-02', '04:15:18', 'usmanabubakarelder128@gmail.com', 'paid'),
(110, '250902420233431', 'work in customer', '7', '100', 2, '2025-09-02', '04:15:29', 'usmanabubakarelder128@gmail.com', 'paid'),
(111, '250902548305630', 'work in customer', '7', '3000', 1, '2025-09-02', '04:31:16', 'usmanabubakarelder128@gmail.com', 'paid'),
(112, '250902548305630', 'work in customer', '37', '2000', 1, '2025-09-02', '04:31:26', 'usmanabubakarelder128@gmail.com', 'paid'),
(113, '250903411054686', 'work in customer', '34', '50', 6, '2025-09-03', '22:35:57', 'hamxah4u@gmail.com', 'paid'),
(114, '250904939545867', 'work in customer', '34', '50', 2, '2025-09-03', '23:35:18', 'hamxah4u@gmail.com', 'paid'),
(115, '250904685577204', 'work in customer', '7', '150', 1, '2025-09-03', '00:13:46', 'hamxah4u@gmail.com', 'paid'),
(116, '250904845637615', 'work in customer', '7', '50', 3, '2025-09-04', '12:51:25', 'hamxah4u@gmail.com', 'paid'),
(117, '250904887687399', 'work in customer', '38', '1600', 1, '2025-09-04', '12:53:44', 'hamxah4u@gmail.com', 'paid'),
(118, '250904289274504', 'work in customer', '36', '4000', 2, '2025-09-03', '12:54:19', 'hamxah4u@gmail.com', 'paid'),
(119, '250904398413462', 'work in customer', '34', '70', 8, '2025-09-04', '06:15:04', 'hamxah4u@gmail.com', 'paid'),
(120, '250904276975699', 'work in customer', '34', '70', 3, '2025-09-04', '10:17:57', 'hamxah4u@gmail.com', 'paid'),
(121, '250905457798199', 'work in customer', '7', '100', 1, '2025-09-05', '11:24:19', 'usmanabubakarelder128@gmail.com', 'paid'),
(122, '250905457798199', 'work in customer', '34', '50', 2, '2025-09-05', '11:24:29', 'usmanabubakarelder128@gmail.com', 'paid'),
(123, '250905245294079', 'work in customer', '7', '100', 4, '2025-09-05', '14:27:39', 'usmanabubakarelder128@gmail.com', 'paid'),
(124, '250905245294079', 'work in customer', '37', '100', 1, '2025-09-05', '14:27:48', 'usmanabubakarelder128@gmail.com', 'paid'),
(125, '250905397371129', 'work in customer', '7', '100', 2, '2025-09-05', '18:13:59', 'usmanabubakarelder128@gmail.com', 'paid'),
(126, '250905397371129', 'work in customer', '37', '100', 2, '2025-09-05', '18:14:28', 'usmanabubakarelder128@gmail.com', 'paid'),
(127, '250907107492391', 'work in customer', '7', '5500', 1, '2025-09-07', '13:32:02', 'usmanabubakarelder128@gmail.com', 'paid'),
(128, '250907107492391', 'work in customer', '37', '1500', 1, '2025-09-07', '13:32:14', 'usmanabubakarelder128@gmail.com', 'paid'),
(129, '250907107492391', 'work in customer', '7', '450', 1, '2025-09-07', '13:32:32', 'usmanabubakarelder128@gmail.com', 'paid'),
(130, '250907107492391', 'work in customer', '38', '350', 1, '2025-09-07', '13:32:41', 'usmanabubakarelder128@gmail.com', 'paid'),
(131, '250907177839161', 'work in customer', '34', '50', 14, '2025-09-07', '14:39:22', 'usmanabubakarelder128@gmail.com', 'paid'),
(132, '250907177839161', 'work in customer', '7', '1300', 1, '2025-09-07', '15:12:55', 'usmanabubakarelder128@gmail.com', 'paid'),
(133, '250907900769266', 'work in customer', '7', '1000', 1, '2025-09-07', '16:06:41', 'usmanabubakarelder128@gmail.com', 'paid'),
(134, '250907480065431', 'work in customer', '34', '500', 1, '2025-09-07', '16:13:34', 'usmanabubakarelder128@gmail.com', 'paid'),
(135, '250908257424867', 'work in customer', '7', '50', 9, '2025-09-08', '09:49:19', 'usmanabubakarelder128@gmail.com', 'paid'),
(136, '250908763405493', 'work in customer', '7', '50', 1, '2025-09-08', '11:38:28', 'usmanabubakarelder128@gmail.com', 'paid'),
(137, '250908450206654', 'Yabanya', '7', '150', 1, '2025-09-08', '13:09:08', 'hamxah4u@gmail.com', 'paid'),
(138, '250908450206654', 'work in customer', '36', '1000', 1, '2025-09-08', '13:09:25', 'hamxah4u@gmail.com', 'paid'),
(139, '250908204963308', 'work in customer', '34', '70', 3, '2025-09-08', '13:10:01', 'hamxah4u@gmail.com', 'paid'),
(140, '250908204963308', 'work in customer', '7', '50', 4, '2025-09-08', '13:10:18', 'hamxah4u@gmail.com', 'paid'),
(141, '250908278438623', 'work in customer', '38', '490', 1, '2025-09-08', '13:29:17', 'hamxah4u@gmail.com', 'paid'),
(142, '250909201917081', 'work in customer', '34', '50', 3, '2025-09-09', '15:23:28', 'usmanabubakarelder128@gmail.com', 'paid'),
(143, '250909201917081', 'work in customer', '34', '100', 1, '2025-09-09', '15:23:42', 'usmanabubakarelder128@gmail.com', 'paid'),
(144, '250909131509622', 'work in customer', '7', '3500', 1, '2025-09-09', '16:44:22', 'usmanabubakarelder128@gmail.com', 'paid'),
(145, '250909131509622', 'work in customer', '37', '500', 1, '2025-09-09', '16:44:32', 'usmanabubakarelder128@gmail.com', 'paid'),
(146, '250909321216105', 'work in customer', '36', '15000', 1, '2025-09-09', '16:48:12', 'usmanabubakarelder128@gmail.com', 'paid'),
(147, '250909321216105', 'work in customer', '38', '1500', 1, '2025-09-09', '16:48:32', 'usmanabubakarelder128@gmail.com', 'paid'),
(148, '250909321216105', 'work in customer', '38', '1500', 1, '2025-09-09', '16:48:44', 'usmanabubakarelder128@gmail.com', 'paid'),
(149, '250909321216105', 'work in customer', '36', '5000', 1, '2025-09-09', '16:49:06', 'usmanabubakarelder128@gmail.com', 'paid'),
(150, '250909121710635', 'work in customer', '38', '490', 1, '2025-09-09', '16:50:04', 'usmanabubakarelder128@gmail.com', 'paid'),
(151, '250911379402667', 'work in customer', '7', '2400', 1, '2025-09-11', '20:41:35', 'usmanabubakarelder128@gmail.com', 'paid'),
(152, '250911379402667', 'work in customer', '37', '400', 1, '2025-09-11', '20:42:03', 'usmanabubakarelder128@gmail.com', 'paid'),
(153, '250911379402667', 'work in customer', '7', '50', 2, '2025-09-11', '21:44:24', 'usmanabubakarelder128@gmail.com', 'paid'),
(154, '250912366167582', 'work in customer', '7', '100', 2, '2025-09-12', '10:05:50', 'usmanabubakarelder128@gmail.com', 'paid'),
(155, '250912462669572', 'work in customer', '34', '250', 1, '2025-09-12', '12:15:49', 'usmanabubakarelder128@gmail.com', 'paid'),
(156, '250912920190679', 'work in customer', '7', '100', 2, '2025-09-12', '12:40:10', 'usmanabubakarelder128@gmail.com', 'paid'),
(157, '250912920190679', 'work in customer', '37', '100', 1, '2025-09-12', '12:40:17', 'usmanabubakarelder128@gmail.com', 'paid'),
(158, '250912146996245', 'work in customer', '7', '100', 1, '2025-09-12', '12:40:44', 'usmanabubakarelder128@gmail.com', 'paid'),
(159, '250912883090170', 'work in customer', '7', '50', 8, '2025-09-12', '16:52:04', 'usmanabubakarelder128@gmail.com', 'paid'),
(160, '250912883090170', 'work in customer', '7', '150', 2, '2025-09-12', '16:52:54', 'usmanabubakarelder128@gmail.com', 'paid'),
(161, '250913199633564', 'Coach Yahaya', '7', '50', 4, '2025-09-13', '11:06:15', 'hamxah4u@gmail.com', 'paid'),
(162, '250913802745714', 'work in customer', '7', '200', 1, '2025-09-13', '21:57:23', 'usmanabubakarelder128@gmail.com', 'paid'),
(163, '250913802745714', 'work in customer', '36', '2000', 1, '2025-09-13', '21:57:36', 'usmanabubakarelder128@gmail.com', 'paid'),
(164, '250914579061594', 'work in customer', '34', '50', 5, '2025-09-14', '12:39:26', 'usmanabubakarelder128@gmail.com', 'paid'),
(165, '250914579061594', 'Sani', '34', '50', 2, '2025-09-14', '12:39:49', 'usmanabubakarelder128@gmail.com', 'paid'),
(166, '250914315061456', 'work in customer', '7', '50', 4, '2025-09-14', '21:36:28', 'usmanabubakarelder128@gmail.com', 'paid'),
(167, '250915548480822', 'work in customer', '7', '50', 3, '2025-09-14', '00:29:38', 'usmanabubakarelder128@gmail.com', 'paid'),
(168, '250915548480822', 'work in customer', '7', '50', 1, '2025-09-14', '00:36:53', 'usmanabubakarelder128@gmail.com', 'paid'),
(169, '250914412530539', 'work in customer', '7', '1200', 1, '2025-09-14', '04:11:35', 'usmanabubakarelder128@gmail.com', 'paid'),
(170, '250914412530539', 'work in customer', '37', '300', 1, '2025-09-14', '04:11:49', 'usmanabubakarelder128@gmail.com', 'paid'),
(171, '250915306079676', 'work in customer', '7', '100', 2, '2025-09-15', '16:14:47', 'usmanabubakarelder128@gmail.com', 'paid'),
(172, '250915306079676', 'work in customer', '7', '100', 2, '2025-09-15', '16:16:25', 'usmanabubakarelder128@gmail.com', 'paid'),
(173, '250915306079676', 'work in customer', '38', '4000', 1, '2025-09-15', '16:18:55', 'usmanabubakarelder128@gmail.com', 'paid'),
(174, '250918451276152', 'work in customer', '7', '500', 1, '2025-09-18', '10:12:43', 'usmanabubakarelder128@gmail.com', 'paid'),
(175, '250918451276152', 'work in customer', '37', '500', 1, '2025-09-18', '10:13:10', 'usmanabubakarelder128@gmail.com', 'paid'),
(176, '250918451276152', 'work in customer', '38', '2000', 1, '2025-09-18', '10:13:22', 'usmanabubakarelder128@gmail.com', 'paid'),
(177, '250918451276152', 'work in customer', '7', '100', 1, '2025-09-18', '10:13:37', 'usmanabubakarelder128@gmail.com', 'paid'),
(178, '250918451276152', 'work in customer', '7', '500', 1, '2025-09-18', '10:13:50', 'usmanabubakarelder128@gmail.com', 'paid'),
(179, '250918451276152', 'work in customer', '38', '1000', 1, '2025-09-18', '10:14:02', 'usmanabubakarelder128@gmail.com', 'paid'),
(180, '250918451276152', 'work in customer', '36', '500', 1, '2025-09-18', '10:14:14', 'usmanabubakarelder128@gmail.com', 'paid'),
(181, '250918451276152', 'work in customer', '38', '300', 1, '2025-09-18', '10:14:55', 'usmanabubakarelder128@gmail.com', 'paid'),
(182, '250918451276152', 'work in customer', '7', '50', 14, '2025-09-18', '10:15:23', 'usmanabubakarelder128@gmail.com', 'paid'),
(183, '250918451276152', 'work in customer', '7', '3000', 1, '2025-09-18', '10:19:18', 'usmanabubakarelder128@gmail.com', 'paid'),
(184, '250918451276152', 'work in customer', '38', '500', 1, '2025-09-18', '10:20:16', 'usmanabubakarelder128@gmail.com', 'paid'),
(185, '250918451276152', 'work in customer', '7', '1800', 1, '2025-09-18', '10:20:43', 'usmanabubakarelder128@gmail.com', 'paid'),
(186, '250918451276152', 'work in customer', '37', '2000', 1, '2025-09-18', '10:21:18', 'usmanabubakarelder128@gmail.com', 'paid'),
(187, '250918451276152', 'work in customer', '38', '1000', 1, '2025-09-18', '10:21:28', 'usmanabubakarelder128@gmail.com', 'paid'),
(188, '250918451276152', 'work in customer', '7', '6000', 1, '2025-09-18', '10:21:46', 'usmanabubakarelder128@gmail.com', 'paid'),
(189, '250918451276152', 'work in customer', '37', '2000', 1, '2025-09-18', '10:22:05', 'usmanabubakarelder128@gmail.com', 'paid'),
(190, '250918451276152', 'work in customer', '38', '5000', 1, '2025-09-18', '10:24:10', 'usmanabubakarelder128@gmail.com', 'paid'),
(191, '250918451276152', 'work in customer', '38', '800', 1, '2025-09-18', '10:24:46', 'usmanabubakarelder128@gmail.com', 'paid'),
(192, '250918451276152', 'work in customer', '7', '1200', 1, '2025-09-18', '10:24:57', 'usmanabubakarelder128@gmail.com', 'paid'),
(193, '250918451276152', 'work in customer', '7', '300', 1, '2025-09-18', '10:25:12', 'usmanabubakarelder128@gmail.com', 'paid'),
(194, '250918149287607', 'work in customer', '7', '200', 1, '2025-09-18', '10:26:41', 'usmanabubakarelder128@gmail.com', 'paid'),
(195, '250918149287607', 'work in customer', '38', '300', 1, '2025-09-18', '10:26:54', 'usmanabubakarelder128@gmail.com', 'paid'),
(196, '250918149287607', 'work in customer', '38', '200', 1, '2025-09-18', '10:27:07', 'usmanabubakarelder128@gmail.com', 'paid'),
(197, '250918149287607', 'work in customer', '7', '200', 1, '2025-09-18', '10:27:22', 'usmanabubakarelder128@gmail.com', 'paid'),
(198, '250918149287607', 'work in customer', '34', '1000', 1, '2025-09-18', '10:27:41', 'usmanabubakarelder128@gmail.com', 'paid'),
(199, '250918149287607', 'work in customer', '38', '3000', 1, '2025-09-18', '10:28:39', 'usmanabubakarelder128@gmail.com', 'paid'),
(200, '250918149287607', 'work in customer', '38', '1300', 1, '2025-09-18', '10:28:49', 'usmanabubakarelder128@gmail.com', 'paid'),
(201, '250918149287607', 'work in customer', '36', '1500', 1, '2025-09-18', '10:29:01', 'usmanabubakarelder128@gmail.com', 'paid'),
(202, '250918149287607', 'work in customer', '36', '5000', 1, '2025-09-18', '10:31:00', 'usmanabubakarelder128@gmail.com', 'paid'),
(203, '250920643316799', 'work in customer', '7', '200', 1, '2025-09-20', '10:32:13', 'usmanabubakarelder128@gmail.com', 'paid'),
(204, '250920643316799', 'work in customer', '34', '70', 1, '2025-09-20', '10:32:41', 'usmanabubakarelder128@gmail.com', 'paid'),
(205, '250920643316799', 'work in customer', '7', '930', 1, '2025-09-20', '10:32:54', 'usmanabubakarelder128@gmail.com', 'paid'),
(206, '250920627389297', 'work in customer', '38', '1050', 1, '2025-09-20', '12:24:50', 'usmanabubakarelder128@gmail.com', 'paid'),
(207, '250913413974202', 'work in customer', '37', '200', 1, '2025-09-13', '03:53:36', 'usmanabubakarelder128@gmail.com', 'paid'),
(208, '250913413974202', 'work in customer', '7', '550', 1, '2025-09-13', '03:53:48', 'usmanabubakarelder128@gmail.com', 'paid'),
(209, '250913413974202', 'work in customer', '7', '50', 2, '2025-09-13', '03:54:10', 'usmanabubakarelder128@gmail.com', 'paid'),
(210, '250921397902491', 'work in customer', '7', '50', 3, '2025-09-21', '16:41:08', 'hamxah4u@gmail.com', 'paid'),
(211, '250921397902491', 'work in customer', '7', '50', 3, '2025-09-21', '16:41:08', 'hamxah4u@gmail.com', 'paid'),
(212, '250924295610686', 'work in customer', '34', '50', 20, '2025-09-24', '11:35:21', 'hamxah4u@gmail.com', 'paid'),
(213, '250924295610686', 'work in customer', '36', '200', 1, '2025-09-24', '11:35:35', 'hamxah4u@gmail.com', 'paid'),
(214, '250924485152407', 'work in customer', '7', '300', 1, '2025-09-24', '15:38:47', 'hamxah4u@gmail.com', 'paid'),
(215, '250924485152407', 'work in customer', '7', '300', 1, '2025-09-24', '15:38:47', 'hamxah4u@gmail.com', 'paid'),
(216, '250924702779235', 'work in customer', '7', '150', 25, '2025-09-24', '18:07:46', 'usmanabubakarelder128@gmail.com', 'paid'),
(217, '250924942645924', 'work in customer', '37', '50', 25, '2025-09-24', '18:08:15', 'usmanabubakarelder128@gmail.com', 'paid'),
(218, '250924916766999', 'work in customer', '34', '100', 1, '2025-09-24', '18:08:43', 'usmanabubakarelder128@gmail.com', 'paid'),
(219, '250924916766999', 'work in customer', '38', '300', 1, '2025-09-24', '18:08:54', 'usmanabubakarelder128@gmail.com', 'paid'),
(220, '250922725709978', 'work in customer', '7', '6000', 1, '2025-09-22', '18:46:31', 'hamxah4u@gmail.com', 'paid'),
(221, '250922213649649', 'work in customer', '37', '2000', 1, '2025-09-22', '18:47:06', 'hamxah4u@gmail.com', 'paid'),
(222, '250922213649649', 'work in customer', '38', '9800', 1, '2025-09-22', '18:47:25', 'hamxah4u@gmail.com', 'paid'),
(223, '250922213649649', 'work in customer', '38', '9800', 1, '2025-09-22', '18:47:25', 'hamxah4u@gmail.com', 'paid'),
(224, '250922213649649', 'work in customer', '38', '5170', 2, '2025-09-22', '18:47:47', 'hamxah4u@gmail.com', 'paid'),
(225, '250922213649649', 'work in customer', '36', '700', 1, '2025-09-22', '18:48:45', 'hamxah4u@gmail.com', 'paid'),
(226, '250924285871897', 'work in customer', '7', '400', 1, '2025-09-24', '20:01:57', 'hamxah4u@gmail.com', 'paid'),
(227, '250924624054150', 'CAC', '36', '5170', 1, '2025-09-24', '20:03:00', 'hamxah4u@gmail.com', 'paid'),
(228, '250924581067740', 'work in customer', '7', '150', 15, '2025-09-24', '21:30:08', 'hamxah4u@gmail.com', 'paid'),
(229, '250924581067740', 'work in customer', '37', '50', 15, '2025-09-24', '21:30:16', 'hamxah4u@gmail.com', 'paid'),
(230, '250925596075697', 'work in customer', '7', '100', 3, '2025-09-25', '12:39:15', 'usmanabubakarelder128@gmail.com', 'paid'),
(231, '250925596075697', 'work in customer', '37', '200', 1, '2025-09-25', '12:39:29', 'usmanabubakarelder128@gmail.com', 'paid'),
(232, '250925158533238', 'work in customer', '7', '400', 1, '2025-09-25', '14:33:34', 'usmanabubakarelder128@gmail.com', 'paid'),
(233, '250925158533238', 'work in customer', '7', '400', 1, '2025-09-25', '14:33:34', 'usmanabubakarelder128@gmail.com', 'paid'),
(234, '250925556610464', 'work in customer', '7', '50', 3, '2025-09-25', '18:58:07', 'hamxah4u@gmail.com', 'paid'),
(235, '250925177969916', 'work in customer', '36', '650', 1, '2025-09-25', '19:28:20', 'hamxah4u@gmail.com', 'paid'),
(236, '250925604895319', 'work in customer', '38', '1000', 1, '2025-09-25', '19:32:22', 'hamxah4u@gmail.com', 'paid'),
(237, '250925187370288', 'work in customer', '7', '600', 1, '2025-09-25', '20:51:44', 'hamxah4u@gmail.com', 'paid'),
(238, '250925187370288', 'work in customer', '37', '200', 1, '2025-09-25', '20:51:53', 'hamxah4u@gmail.com', 'paid'),
(239, '250925187370288', 'work in customer', '7', '400', 1, '2025-09-25', '20:52:15', 'hamxah4u@gmail.com', 'paid'),
(240, '250925187370288', 'work in customer', '37', '100', 1, '2025-09-25', '20:52:23', 'hamxah4u@gmail.com', 'paid'),
(241, '250925233393053', 'work in customer', '7', '1000', 1, '2025-09-25', '21:14:24', 'hamxah4u@gmail.com', 'paid'),
(242, '250926316294592', 'work in customer', '7', '100', 3, '2025-09-25', '09:27:58', 'hamxah4u@gmail.com', 'paid'),
(244, '250926187827288', 'work in customer', '7', '4000', 1, '2025-09-26', '17:34:42', 'usmanabubakarelder128@gmail.com', 'paid'),
(245, '250926187827288', 'work in customer', '37', '1000', 1, '2025-09-26', '17:34:56', 'usmanabubakarelder128@gmail.com', 'paid'),
(246, '250926384931794', 'work in customer', '7', '50', 1, '2025-09-26', '19:12:58', 'usmanabubakarelder128@gmail.com', 'paid'),
(247, '250926384931794', 'work in customer', '7', '250', 1, '2025-09-26', '19:13:24', 'usmanabubakarelder128@gmail.com', 'paid'),
(248, '250926384931794', 'work in customer', '37', '50', 1, '2025-09-26', '19:13:34', 'usmanabubakarelder128@gmail.com', 'paid'),
(249, '250926384931794', 'work in customer', '7', '1200', 1, '2025-09-26', '19:13:51', 'usmanabubakarelder128@gmail.com', 'paid'),
(250, '250926384931794', 'work in customer', '37', '300', 1, '2025-09-26', '19:15:01', 'usmanabubakarelder128@gmail.com', 'paid'),
(251, '250927763277178', 'work in customer', '36', '650', 2, '2025-09-27', '12:53:04', 'hamxah4u@gmail.com', 'paid'),
(252, '250927780797532', 'work in customer', '36', '2000', 1, '2025-09-27', '15:35:18', 'hamxah4u@gmail.com', 'paid'),
(253, '250927708150251', 'work in customer', '7', '700', 1, '2025-09-27', '15:40:30', 'hamxah4u@gmail.com', 'paid'),
(254, '250927708150251', 'work in customer', '37', '300', 1, '2025-09-27', '15:40:44', 'hamxah4u@gmail.com', 'paid'),
(255, '250927402989187', 'work in customer', '7', '500', 1, '2025-09-27', '18:57:12', 'hamxah4u@gmail.com', 'paid'),
(256, '250927380734609', 'm sports', '34', '50', 2, '2025-09-27', '19:53:44', 'usmanabubakarelder128@gmail.com', 'paid'),
(257, '250927708815530', 'work in customer', '7', '100', 5, '2025-09-27', '19:54:22', 'usmanabubakarelder128@gmail.com', 'paid'),
(258, '250929739927203', 'jjjj', '36', '600', 1, '2025-09-29', '10:13:23', 'hamxah4u@gmail.com', 'paid');

-- --------------------------------------------------------

--
-- Table structure for table `transaction_tbl`
--

CREATE TABLE `transaction_tbl` (
  `TID` int(11) NOT NULL,
  `tCode` varchar(50) NOT NULL,
  `tDepartment` int(11) DEFAULT NULL,
  `Product` int(11) DEFAULT NULL,
  `Price` float DEFAULT NULL,
  `qty` float DEFAULT NULL,
  `Amount` float NOT NULL,
  `Credit` float DEFAULT NULL,
  `Customer` varchar(100) NOT NULL,
  `TransacDate` date DEFAULT NULL,
  `TransacTime` time NOT NULL,
  `TrasacBy` varchar(100) NOT NULL,
  `Status` varchar(20) DEFAULT 'Not-Paid',
  `nhisno` varchar(20) DEFAULT NULL,
  `cash` varchar(200) DEFAULT NULL,
  `transfer` varchar(200) DEFAULT NULL,
  `pos` varchar(200) DEFAULT NULL,
  `crypto` varchar(200) DEFAULT NULL,
  `CID` int(11) DEFAULT NULL,
  `narration` varchar(200) DEFAULT NULL,
  `creditstatus` varchar(20) DEFAULT NULL,
  `pprice` float DEFAULT NULL,
  `pprice_amount` float GENERATED ALWAYS AS (`pprice` * `qty`) STORED,
  `profit` float GENERATED ALWAYS AS (coalesce(`Amount`,0) + coalesce(`Credit`,0) - coalesce(`pprice_amount`,0)) STORED
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `users_tbl`
--

CREATE TABLE `users_tbl` (
  `userID` int(11) NOT NULL,
  `Fullname` varchar(100) NOT NULL,
  `Email` varchar(100) NOT NULL,
  `UserPassword` varchar(255) NOT NULL,
  `Department` int(11) NOT NULL,
  `DateRegister` date NOT NULL,
  `TimeRegister` time NOT NULL,
  `Role` varchar(10) NOT NULL,
  `Status` varchar(20) NOT NULL DEFAULT 'Active',
  `Phone` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `users_tbl`
--

INSERT INTO `users_tbl` (`userID`, `Fullname`, `Email`, `UserPassword`, `Department`, `DateRegister`, `TimeRegister`, `Role`, `Status`, `Phone`) VALUES
(11, 'Hamza Ibrahim Danasabe', 'hamxah4u@gmail.com', '$2y$10$rsAPBqxnifDbGLBhelhxwekKyJqdCP1uMTuYivZDjKVXKnpUyzYwm', 7, '0000-00-00', '00:00:00', 'Admin', 'Active', '12345'),
(40, 'Abdulrahim Ibrahim', 'ansariyaph30@gmail.com', '$2y$10$yzepkWyva6WgQKJHukRzEuXL7ufkANoXiTGntBI/LHiHfN7IFapii', 7, '0000-00-00', '00:00:00', 'Admin', 'Active', '08036228364'),
(41, 'Abdulsalam Bashirat', 'akinife27@gmail.com', '$2y$10$BPMd6hhONi7GNib/.FhxUu7.VJou80FF6EZbg/UGCK7Ab9wPaKR9O', 7, '0000-00-00', '00:00:00', 'Admin', 'Active', '07066440262'),
(42, 'Fatima Bello', 'bellofatimah58@gmail.com', '$2y$10$9VxaKpW0LAbmjxuG7ds9.uL1ktj5PhBdYChK14AHjQweVO4q6Z/y.', 7, '0000-00-00', '00:00:00', 'Admin', 'Active', '08162970936'),
(46, 'Maryam Bala Saeed', 'balamsaeed182@gmail.com', '$2y$10$So6vyUMhGjDMbRGTS539LuXJn2gPljaDYx7/VEI8j0eLW0fzcUROS', 7, '0000-00-00', '00:00:00', 'User', 'Active', '07066604427'),
(47, 'Hafsat Dahiru Haruna', 'dhafsat445@gmail.com', '$2y$10$5tm7y9.UllPHz6ZRGAxRCukzh0GCVbSNCb2NjhNhShHEC3C433qna', 7, '0000-00-00', '00:00:00', 'User', 'Active', '08143100881');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `customers_tbl`
--
ALTER TABLE `customers_tbl`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `department_tbl`
--
ALTER TABLE `department_tbl`
  ADD PRIMARY KEY (`deptID`);

--
-- Indexes for table `financecollect_tbl`
--
ALTER TABLE `financecollect_tbl`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `product_tbl`
--
ALTER TABLE `product_tbl`
  ADD PRIMARY KEY (`proID`),
  ADD KEY `Department` (`Department`);

--
-- Indexes for table `supply_tbl`
--
ALTER TABLE `supply_tbl`
  ADD PRIMARY KEY (`SupplyID`),
  ADD UNIQUE KEY `ProductName` (`ProductName`,`ExpiryDate`),
  ADD KEY `Department` (`Department`);

--
-- Indexes for table `tikvaah_transaction`
--
ALTER TABLE `tikvaah_transaction`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `transaction_tbl`
--
ALTER TABLE `transaction_tbl`
  ADD PRIMARY KEY (`TID`),
  ADD KEY `transaction_tbl_ibfk_1` (`tDepartment`);

--
-- Indexes for table `users_tbl`
--
ALTER TABLE `users_tbl`
  ADD PRIMARY KEY (`userID`),
  ADD KEY `Department` (`Department`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `customers_tbl`
--
ALTER TABLE `customers_tbl`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=41;

--
-- AUTO_INCREMENT for table `department_tbl`
--
ALTER TABLE `department_tbl`
  MODIFY `deptID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=41;

--
-- AUTO_INCREMENT for table `financecollect_tbl`
--
ALTER TABLE `financecollect_tbl`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=66;

--
-- AUTO_INCREMENT for table `product_tbl`
--
ALTER TABLE `product_tbl`
  MODIFY `proID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=30;

--
-- AUTO_INCREMENT for table `supply_tbl`
--
ALTER TABLE `supply_tbl`
  MODIFY `SupplyID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=135;

--
-- AUTO_INCREMENT for table `tikvaah_transaction`
--
ALTER TABLE `tikvaah_transaction`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=259;

--
-- AUTO_INCREMENT for table `transaction_tbl`
--
ALTER TABLE `transaction_tbl`
  MODIFY `TID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1821;

--
-- AUTO_INCREMENT for table `users_tbl`
--
ALTER TABLE `users_tbl`
  MODIFY `userID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=48;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `product_tbl`
--
ALTER TABLE `product_tbl`
  ADD CONSTRAINT `product_tbl_ibfk_1` FOREIGN KEY (`Department`) REFERENCES `department_tbl` (`deptID`);

--
-- Constraints for table `supply_tbl`
--
ALTER TABLE `supply_tbl`
  ADD CONSTRAINT `supply_tbl_ibfk_1` FOREIGN KEY (`Department`) REFERENCES `department_tbl` (`deptID`);

--
-- Constraints for table `transaction_tbl`
--
ALTER TABLE `transaction_tbl`
  ADD CONSTRAINT `transaction_tbl_ibfk_1` FOREIGN KEY (`tDepartment`) REFERENCES `department_tbl` (`deptID`);

--
-- Constraints for table `users_tbl`
--
ALTER TABLE `users_tbl`
  ADD CONSTRAINT `users_tbl_ibfk_1` FOREIGN KEY (`Department`) REFERENCES `department_tbl` (`deptID`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
