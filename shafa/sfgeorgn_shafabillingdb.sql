-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Feb 20, 2025 at 02:23 PM
-- Server version: 8.0.34-cll-lve
-- PHP Version: 8.3.15

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `sfgeorgn_shafabillingdb`
--

-- --------------------------------------------------------

--
-- Table structure for table `customers_tbl`
--

CREATE TABLE `customers_tbl` (
  `id` int NOT NULL,
  `Fullname` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  `phone` varchar(16) COLLATE utf8mb4_general_ci NOT NULL,
  `address` varchar(200) COLLATE utf8mb4_general_ci NOT NULL,
  `created_by` varchar(200) COLLATE utf8mb4_general_ci NOT NULL,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `customers_tbl`
--

INSERT INTO `customers_tbl` (`id`, `Fullname`, `phone`, `address`, `created_by`, `created_at`) VALUES
(22, 'MUSTAPHA DASS', '07039827679', 'DASS LGA', 'muhammadushafa@gmail.com', '2025-02-08 19:22:53'),
(23, 'ABDULHAMID', '07066646961', 'MUDA LAWAL', 'muhammadushafa@gmail.com', '2025-02-09 12:41:51'),
(24, 'KAMALU LK', '1234567890', 'LIMAN KTG', 'muhammadushafa@gmail.com', '2025-02-10 10:20:05'),
(25, 'BON BOI', '08080071321', 'DASS', 'muhammadushafa@gmail.com', '2025-02-10 11:29:07'),
(26, 'ALH NUHU', '123456789', 'RIMI', 'muhammadushafa@gmail.com', '2025-02-10 13:14:29'),
(27, 'AMINU RIMI', '0801234567', 'RIMI', 'muhammadushafa@gmail.com', '2025-02-10 13:47:02'),
(28, 'DANIEL T/B', '0801234589', 'TABAWA BALEWA', 'muhammadushafa@gmail.com', '2025-02-10 20:22:25'),
(29, 'SILE DAUDA', '09041190404', 'TABAWA BALEWA', 'muhammadushafa@gmail.com', '2025-02-11 19:19:06'),
(30, 'DAN GARI', '12346789', 'BAUCHI', 'muhammadushafa@gmail.com', '2025-02-12 13:25:32'),
(31, 'BABANGIDA LK', '0812345678', 'LK', 'nafiumohammedomar@gmail.com', '2025-02-12 18:24:28');

-- --------------------------------------------------------

--
-- Table structure for table `department_tbl`
--

CREATE TABLE `department_tbl` (
  `deptID` int NOT NULL,
  `Department` varchar(50) COLLATE utf8mb4_general_ci NOT NULL,
  `Status` varchar(50) COLLATE utf8mb4_general_ci NOT NULL DEFAULT 'Active',
  `registerby` varchar(100) COLLATE utf8mb4_general_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `department_tbl`
--

INSERT INTO `department_tbl` (`deptID`, `Department`, `Status`, `registerby`) VALUES
(7, 'Railway', 'Active', 'muhammadushafa@gmail.com'),
(27, 'Kofar Ram', 'Active', 'muhammadushafa@gmail.com'),
(28, 'Makera', 'Active', 'muhammadushafa@gmail.com'),
(29, 'Baba ibrahim', 'Active', 'muhammadushafa@gmail.com');

-- --------------------------------------------------------

--
-- Table structure for table `financecollect_tbl`
--

CREATE TABLE `financecollect_tbl` (
  `id` int NOT NULL,
  `Collectorname` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  `Amount` decimal(10,2) NOT NULL,
  `Reason` text COLLATE utf8mb4_general_ci NOT NULL,
  `Givername` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  `Dateissued` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `Timegive` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `product_tbl`
--

CREATE TABLE `product_tbl` (
  `proID` int NOT NULL,
  `Department` int NOT NULL,
  `Productname` varchar(50) COLLATE utf8mb4_general_ci NOT NULL,
  `Price` varchar(200) COLLATE utf8mb4_general_ci NOT NULL,
  `Status` varchar(20) COLLATE utf8mb4_general_ci NOT NULL DEFAULT 'Active',
  `DateRegister` date NOT NULL,
  `TimeRegister` time NOT NULL,
  `RegisterBy` varchar(50) COLLATE utf8mb4_general_ci NOT NULL,
  `UpdateBy` varchar(100) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `DateUpdate` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `supply_tbl`
--

CREATE TABLE `supply_tbl` (
  `SupplyID` int NOT NULL,
  `Department` int NOT NULL,
  `ProductName` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `Quantity` int NOT NULL,
  `Price` decimal(10,2) NOT NULL,
  `Pprice` decimal(10,2) NOT NULL,
  `UnitPack` varchar(20) COLLATE utf8mb4_general_ci NOT NULL,
  `ExpiryDate` date NOT NULL,
  `SupplyDate` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `RecordedBy` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `Status` varchar(20) COLLATE utf8mb4_general_ci NOT NULL DEFAULT 'Active',
  `StockQuantity` int GENERATED ALWAYS AS ((`Quantity` * `UnitPack`)) STORED
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `supply_tbl`
--

INSERT INTO `supply_tbl` (`SupplyID`, `Department`, `ProductName`, `Quantity`, `Price`, `Pprice`, `UnitPack`, `ExpiryDate`, `SupplyDate`, `RecordedBy`, `Status`) VALUES
(33, 7, 'Samunaka 10.10', 50, 22000.00, 20500.00, '', '2030-01-01', '2025-02-06 16:29:55', 'muhammadushafa@gmail.com', 'Active'),
(34, 7, 'Danbauchi', 1122, 20000.00, 15000.00, '', '2030-01-01', '2025-02-06 16:31:19', 'muhammadushafa@gmail.com', 'Active'),
(35, 7, 'Gas', 785, 35000.00, 30650.00, '', '2030-01-01', '2025-02-06 16:32:35', 'muhammadushafa@gmail.com', 'Active'),
(36, 7, 'Tak Agro', 386, 25000.00, 15500.00, '', '2030-01-01', '2025-02-06 16:34:24', 'muhammadushafa@gmail.com', 'Active'),
(37, 7, 'Dusa', 421, 16500.00, 15100.00, '', '2030-01-01', '2025-02-06 16:36:12', 'muhammadushafa@gmail.com', 'Active'),
(65, 27, 'Dusa', 817, 16500.00, 15100.00, '', '2025-01-01', '2025-02-07 00:14:45', 'muhammadushafa@gmail.com', 'Active'),
(66, 28, 'Zugachi', 225, 41000.00, 39700.00, '', '2035-02-01', '2025-02-07 00:20:34', 'muhammadushafa@gmail.com', 'Active'),
(67, 28, 'Waraka', 198, 45500.00, 41000.00, '', '2023-03-01', '2025-02-07 00:23:06', 'muhammadushafa@gmail.com', 'Active'),
(68, 28, 'Mai doki 10.10', 59, 40000.00, 36000.00, '', '2025-05-05', '2025-02-07 00:24:42', 'muhammadushafa@gmail.com', 'Active'),
(69, 28, 'Rubish', 16, 26000.00, 20000.00, '', '2026-06-06', '2025-02-07 00:27:17', 'muhammadushafa@gmail.com', 'Active'),
(70, 28, 'Dangote', 371, 36500.00, 32500.00, '', '2030-01-04', '2025-02-07 00:30:11', 'muhammadushafa@gmail.com', 'Active'),
(71, 28, 'Golden 20.10.10', 0, 43500.00, 42750.00, '', '2024-01-01', '2025-02-07 00:31:37', 'muhammadushafa@gmail.com', 'Active'),
(72, 27, 'Ammasco 1litre', 785, 44000.00, 41000.00, '', '2030-02-01', '2025-02-07 17:38:07', 'muhammadushafa@gmail.com', 'Active'),
(73, 28, 'GAS', 138, 35000.00, 33850.00, '', '2026-02-02', '2025-02-09 12:05:11', 'muhammadushafa@gmail.com', 'Active'),
(74, 28, 'ZUGASHI 15.15', 49, 41500.00, 40700.00, '', '2030-02-02', '2025-02-09 12:06:27', 'muhammadushafa@gmail.com', 'Active'),
(75, 28, 'NPK INDORAMA', 377, 43000.00, 42230.00, '', '2030-03-01', '2025-02-09 12:13:38', 'muhammadushafa@gmail.com', 'Active'),
(76, 7, 'BOXER BAJAJ', 167, 1295000.00, 1282000.00, '', '2025-01-01', '2025-02-09 12:31:13', 'muhammadushafa@gmail.com', 'Active'),
(77, 28, 'SAMUNAKA', 50, 21500.00, 2030.00, '', '2030-02-01', '2025-02-09 12:51:55', 'muhammadushafa@gmail.com', 'Active'),
(78, 28, 'TAK AGRO', 193, 36000.00, 35000.00, '', '2026-02-02', '2025-02-12 17:56:37', 'muhammadushafa@gmail.com', 'Active'),
(79, 28, 'INDORAMA', 1200, 35500.00, 32500.00, '', '2025-02-02', '2025-02-13 11:00:12', 'muhammadushafa@gmail.com', 'Active'),
(80, 28, 'GOLDEN 20.10. 10', 140, 43500.00, 42750.00, '', '2025-02-22', '2025-02-13 11:03:17', 'muhammadushafa@gmail.com', 'Active'),
(81, 29, 'Maidoki 15.15', 32, 40000.00, 39000.00, '', '2025-02-20', '2025-02-13 18:41:40', 'muhammadushafa@gmail.com', 'Active'),
(82, 29, 'NPK Indorama ', 23, 43000.00, 42000.00, '', '2025-02-28', '2025-02-13 18:42:42', 'muhammadushafa@gmail.com', 'Active'),
(83, 29, 'Dangote ', 35, 35500.00, 32500.00, '', '2025-02-28', '2025-02-13 18:44:22', 'muhammadushafa@gmail.com', 'Active'),
(84, 29, 'Indorama', 51, 35500.00, 32500.00, '', '2025-02-28', '2025-02-13 18:45:45', 'muhammadushafa@gmail.com', 'Active'),
(85, 29, 'Golden 20.10.10', 36, 43500.00, 42750.00, '', '2025-02-28', '2025-02-13 18:47:16', 'muhammadushafa@gmail.com', 'Active'),
(86, 7, 'Indorama', 670, 35500.00, 32500.00, '', '2025-02-20', '2025-02-16 20:10:55', 'muhammadushafa@gmail.com', 'Active'),
(87, 28, 'OCP', 85, 70000.00, 66000.00, '', '2030-05-02', '2025-02-17 16:55:53', 'muhammadushafa@gmail.com', 'Active'),
(88, 7, 'Dangote', 1010, 37400.00, 32500.00, '', '2025-05-20', '2025-02-20 13:28:56', 'muhammadushafa@gmail.com', 'Active');

-- --------------------------------------------------------

--
-- Table structure for table `transaction_tbl`
--

CREATE TABLE `transaction_tbl` (
  `TID` int NOT NULL,
  `tCode` varchar(50) COLLATE utf8mb4_general_ci NOT NULL,
  `tDepartment` int DEFAULT NULL,
  `Product` int DEFAULT NULL,
  `Price` float DEFAULT NULL,
  `qty` float DEFAULT NULL,
  `Amount` float NOT NULL,
  `Credit` float DEFAULT NULL,
  `Customer` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  `TransacDate` date DEFAULT NULL,
  `TransacTime` time NOT NULL,
  `TrasacBy` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  `Status` varchar(20) COLLATE utf8mb4_general_ci DEFAULT 'Not-Paid',
  `nhisno` varchar(20) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `cash` varchar(200) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `transfer` varchar(200) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `pos` varchar(200) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `crypto` varchar(200) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `CID` int DEFAULT NULL,
  `narration` varchar(200) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `creditstatus` varchar(20) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `pprice` float DEFAULT NULL,
  `pprice_amount` float GENERATED ALWAYS AS ((`pprice` * `qty`)) STORED,
  `profit` float GENERATED ALWAYS AS (((coalesce(`Amount`,0) + coalesce(`Credit`,0)) - coalesce(`pprice_amount`,0))) STORED
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `transaction_tbl`
--

INSERT INTO `transaction_tbl` (`TID`, `tCode`, `tDepartment`, `Product`, `Price`, `qty`, `Amount`, `Credit`, `Customer`, `TransacDate`, `TransacTime`, `TrasacBy`, `Status`, `nhisno`, `cash`, `transfer`, `pos`, `crypto`, `CID`, `narration`, `creditstatus`, `pprice`) VALUES
(843, '250207614350265', 28, 70, 36500, 2, 73000, NULL, 'DANGARI', '2025-02-07', '10:00:45', 'muhammadushafa@gmail.com', 'Paid', '0', '43000', '', '30000', NULL, NULL, NULL, NULL, 32500),
(844, '250207391341958', 28, 71, 43500, 30, 1305000, NULL, 'ABDUSSALAM', '2025-02-07', '10:47:36', 'muhammadushafa@gmail.com', 'Paid', '0', '', '', '1305000', NULL, NULL, NULL, NULL, 42750),
(845, '250207848603393', 27, 65, 16500, 8, 132000, NULL, 'DANBURAM', '2025-02-07', '10:52:26', 'muhammadushafa@gmail.com', 'Paid', '0', '132000', '', '', NULL, NULL, NULL, NULL, 15100),
(846, '250207639047832', 28, 70, 36500, 20, 730000, NULL, 'ABDUSSALAM', '2025-02-07', '10:57:36', 'muhammadushafa@gmail.com', 'Paid', '0', '', '730000', '', NULL, NULL, NULL, NULL, 32500),
(847, '250207819700275', 28, 66, 41000, 5, 205000, NULL, 'SANI DASS', '2025-02-07', '12:58:42', 'muhammadushafa@gmail.com', 'Paid', '0', '3085000', '', '', NULL, NULL, NULL, NULL, 39700),
(848, '250207819700275', 28, 67, 45500, 5, 227500, NULL, 'SANI DASS', '2025-02-07', '12:59:27', 'muhammadushafa@gmail.com', 'Paid', '0', '', '', '', NULL, NULL, NULL, NULL, 41000),
(849, '250207819700275', 28, 70, 36500, 25, 912500, NULL, 'SANI DASS', '2025-02-07', '13:00:06', 'muhammadushafa@gmail.com', 'Paid', '0', '', '', '', NULL, NULL, NULL, NULL, 32500),
(850, '250207819700275', 28, 71, 43500, 40, 1740000, NULL, 'SANI DASS', '2025-02-07', '13:01:01', 'muhammadushafa@gmail.com', 'Paid', '0', '', '', '', NULL, NULL, NULL, NULL, 42750),
(851, '250207686746453', 28, 71, 43500, 1, 43500, NULL, 'umar muhd', '2025-02-07', '13:10:07', 'muhammadushafa@gmail.com', 'Paid', '0', '', '', '43500', NULL, NULL, NULL, NULL, 42750),
(852, '250207846352943', 28, 71, 43500, 10, 435000, NULL, 'ALH SALE LK', '2025-02-07', '13:13:01', 'muhammadushafa@gmail.com', 'Paid', '0', '', '', '800000', NULL, NULL, NULL, NULL, 42750),
(853, '250207846352943', 28, 70, 36500, 10, 365000, NULL, 'ALH SALE LK', '2025-02-07', '13:13:12', 'muhammadushafa@gmail.com', 'Paid', '0', '', '', '', NULL, NULL, NULL, NULL, 32500),
(854, '250207966258045', 28, 70, 36400, 15, 546000, NULL, 'DANLADI', '2025-02-07', '13:15:39', 'muhammadushafa@gmail.com', 'Paid', '0', '546000', '', '', NULL, NULL, NULL, NULL, 32500),
(855, '250207284075470', 28, 67, 45500, 1, 45500, NULL, 'CUSTOMER', '2025-02-07', '13:17:12', 'muhammadushafa@gmail.com', 'Paid', '0', '82000', '', '', NULL, NULL, NULL, NULL, 41000),
(856, '250207284075470', 28, 70, 36500, 1, 36500, NULL, 'CUSTOMER', '2025-02-07', '13:17:21', 'muhammadushafa@gmail.com', 'Paid', '0', '', '', '', NULL, NULL, NULL, NULL, 32500),
(857, '250207565037541', 28, 71, 43500, 15, 652500, NULL, 'ATAM', '2025-02-07', '14:08:23', 'muhammadushafa@gmail.com', 'Paid', '0', '', '652500', '', NULL, NULL, NULL, NULL, 42750),
(859, '250207183277473', 28, 70, 36500, 10, 365000, NULL, 'KHALID DASS', '2025-02-07', '14:39:00', 'muhammadushafa@gmail.com', 'Paid', '0', '', '582500', '', NULL, NULL, NULL, NULL, 32500),
(860, '250207183277473', 28, 71, 43500, 5, 217500, NULL, 'KHALID DASS', '2025-02-07', '14:39:33', 'muhammadushafa@gmail.com', 'Paid', '0', '', '', '', NULL, NULL, NULL, NULL, 42750),
(861, '250207366774340', 28, 70, 36500, 7, 255500, NULL, 'KHALID DASS', '2025-02-07', '14:44:40', 'muhammadushafa@gmail.com', 'Paid', '0', '255500', '', '', NULL, NULL, NULL, NULL, 32500),
(863, '250207285094574', 28, 71, 43500, 10, 435000, NULL, 'ABDULL NABARDO', '2025-02-07', '16:22:26', 'muhammadushafa@gmail.com', 'Paid', '0', '', '435000', '', NULL, NULL, NULL, NULL, 42750),
(864, '250207863847298', 28, 70, 36500, 1, 36500, NULL, 'ABDURAZAK', '2025-02-07', '17:06:53', 'muhammadushafa@gmail.com', 'Paid', '0', '', '', '36500', NULL, NULL, NULL, NULL, 32500),
(865, '250207313759236', 28, 71, 43800, 1, 43800, NULL, 'umar', '2025-02-07', '17:09:23', 'muhammadushafa@gmail.com', 'Paid', '0', '43800', '', '', NULL, NULL, NULL, NULL, 42750),
(866, '250207643022909', 27, 72, 44000, 10, 440000, NULL, 'Buhari Said', '2025-02-07', '17:40:13', 'muhammadushafa@gmail.com', 'Paid', '0', '', '264000', '176000', NULL, NULL, NULL, NULL, 41000),
(867, '250207889738393', 28, 70, 36500, 10, 365000, NULL, 'Babangida LK', '2025-02-07', '18:15:23', 'muhammadushafa@gmail.com', 'Paid', '0', '', '1235000', '', NULL, NULL, NULL, NULL, 32500),
(868, '250207889738393', 28, 71, 43500, 20, 870000, NULL, 'Babangida LK', '2025-02-07', '18:15:58', 'muhammadushafa@gmail.com', 'Paid', '0', '', '', '', NULL, NULL, NULL, NULL, 42750),
(869, '250207215080518', 28, 70, 36400, 30, 1092000, NULL, 'JAMILU SORO', '2025-02-07', '18:30:09', 'muhammadushafa@gmail.com', 'Paid', '0', '', '1527000', '', NULL, NULL, NULL, NULL, 32500),
(870, '250207215080518', 28, 71, 43500, 10, 435000, NULL, 'JAMILU SORO', '2025-02-07', '18:30:25', 'muhammadushafa@gmail.com', 'Paid', '0', '', '', '', NULL, NULL, NULL, NULL, 42750),
(871, '250207269959133', 28, 70, 36500, 5, 182500, NULL, 'SANI DASS', '2025-02-07', '19:32:26', 'muhammadushafa@gmail.com', 'Paid', '0', '', '182500', '', NULL, NULL, NULL, NULL, 32500),
(872, '250207990545959', 28, 71, 43500, 10, 435000, NULL, 'safiyanu', '2025-02-07', '19:35:47', 'muhammadushafa@gmail.com', 'Not-Paid', '0', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 42750),
(873, '250207461984748', 28, 70, 36500, 15, 547500, NULL, 'safiyanu', '2025-02-07', '19:39:12', 'muhammadushafa@gmail.com', 'Paid', '0', '', '982500', '', NULL, NULL, NULL, NULL, 32500),
(874, '250207461984748', 28, 71, 43500, 10, 435000, NULL, 'safiyanu', '2025-02-07', '19:39:37', 'muhammadushafa@gmail.com', 'Paid', '0', '', '', '', NULL, NULL, NULL, NULL, 42750),
(875, '250207762042432', 28, 70, 36500, 10, 365000, NULL, 'gwamna', '2025-02-07', '19:41:12', 'muhammadushafa@gmail.com', 'Paid', '0', '', '365000', '', NULL, NULL, NULL, NULL, 32500),
(876, '250208374786597', 28, 71, 43500, 1, 43500, NULL, 'MESSI', '2025-02-08', '10:14:54', 'muhammadushafa@gmail.com', 'Paid', '0', '', '', '43500', NULL, NULL, NULL, NULL, 42750),
(877, '250208108444473', 28, 71, 43500, 5, 217500, NULL, 'NASIRU LK', '2025-02-08', '10:51:23', 'muhammadushafa@gmail.com', 'Paid', '0', '217500', '', '', NULL, NULL, NULL, NULL, 42750),
(878, '250208832106388', 28, 70, 36500, 10, 365000, NULL, 'SALEH LK', '2025-02-08', '11:09:26', 'muhammadushafa@gmail.com', 'Paid', '0', '', '', '800000', NULL, NULL, NULL, NULL, 32500),
(879, '250208832106388', 28, 71, 43500, 10, 435000, NULL, 'SALEH LK', '2025-02-08', '11:09:40', 'muhammadushafa@gmail.com', 'Paid', '0', '', '', '', NULL, NULL, NULL, NULL, 42750),
(880, '250208341377056', 28, 70, 36500, 7, 255500, NULL, 'MUHD DURR', '2025-02-08', '11:30:16', 'muhammadushafa@gmail.com', 'Paid', '0', '', '', '605500', NULL, NULL, NULL, NULL, 32500),
(881, '250208341377056', 28, 71, 43500, 7, 304500, NULL, 'MUHD DURR', '2025-02-08', '11:30:28', 'muhammadushafa@gmail.com', 'Paid', '0', '', '', '', NULL, NULL, NULL, NULL, 42750),
(882, '250208341377056', 28, 67, 45500, 1, 45500, NULL, 'MUHD DURR', '2025-02-08', '11:30:39', 'muhammadushafa@gmail.com', 'Paid', '0', '', '', '', NULL, NULL, NULL, NULL, 41000),
(883, '250208716022486', 28, 70, 36600, 5, 183000, NULL, 'LAWAL ', '2025-02-08', '13:31:25', 'muhammadushafa@gmail.com', 'Paid', '0', '445800', '', '', NULL, NULL, NULL, NULL, 32500),
(884, '250208716022486', 28, 71, 43800, 6, 262800, NULL, 'LAWAL ', '2025-02-08', '13:31:51', 'muhammadushafa@gmail.com', 'Paid', '0', '', '', '', NULL, NULL, NULL, NULL, 42750),
(886, '250208441949201', 28, 70, 36500, 20, 730000, NULL, 'BABANGIDA KANGERE', '2025-02-08', '15:15:36', 'muhammadushafa@gmail.com', 'Paid', '0', '', '730000', '', NULL, NULL, NULL, NULL, 32500),
(887, '250208402067394', 28, 66, 42000, 2, 84000, NULL, 'ABDULLAHI', '2025-02-08', '15:19:30', 'muhammadushafa@gmail.com', 'Paid', '0', '127700', '', '', NULL, NULL, NULL, NULL, 39700),
(889, '250208402067394', 28, 71, 43700, 1, 43700, NULL, 'ABDULLAHI', '2025-02-08', '15:20:35', 'muhammadushafa@gmail.com', 'Paid', '0', '', '', '', NULL, NULL, NULL, NULL, 42750),
(890, '250208215942163', 28, 71, 44000, 1, 44000, NULL, 'DANFILLO', '2025-02-08', '16:38:23', 'muhammadushafa@gmail.com', 'Paid', '0', '44000', '', '', NULL, NULL, NULL, NULL, 42750),
(891, '250208153323721', 28, 70, 36500, 10, 365000, NULL, 'ENGR LK', '2025-02-08', '17:09:43', 'muhammadushafa@gmail.com', 'Paid', '0', '', '', '365000', NULL, NULL, NULL, NULL, 32500),
(892, '250208767988451', 28, 71, 43500, 18, 783000, NULL, 'CUSTOMER', '2025-02-08', '17:11:19', 'muhammadushafa@gmail.com', 'Paid', '0', '', '', '783000', NULL, NULL, NULL, NULL, 42750),
(893, '250208894123454', 28, 70, 36500, 6, 219000, NULL, 'ALIYU DINDIMA', '2025-02-08', '17:38:23', 'muhammadushafa@gmail.com', 'Paid', '0', '', '', '219000', NULL, NULL, NULL, NULL, 32500),
(894, '250208237479201', 27, 65, 16500, 4, 66000, NULL, 'DANBURAM', '2025-02-08', '18:16:34', 'muhammadushafa@gmail.com', 'Paid', '0', '49000', '', '17000', NULL, NULL, NULL, NULL, 15100),
(895, '250208150430211', 28, 70, 36500, 10, 365000, NULL, 'MUSTAPHA DASS', '2025-02-08', '18:56:15', 'muhammadushafa@gmail.com', 'Paid', '0', '', '365000', '', NULL, NULL, NULL, NULL, 32500),
(897, '250209879439555', 28, 70, 36500, 2, 73000, NULL, 'MAL DAHIRU', '2025-02-09', '11:58:17', 'muhammadushafa@gmail.com', 'Paid', '0', '', '', '73000', NULL, NULL, NULL, NULL, 32500),
(898, '250209416743785', 28, 70, 36500, 60, 2190000, NULL, 'DAKTA NABORDO', '2025-02-09', '12:00:40', 'muhammadushafa@gmail.com', 'Paid', '0', '2190000', '', '', NULL, NULL, NULL, NULL, 32500),
(899, '250209272425940', 7, 76, 1295000, 4, 5180000, NULL, 'ALH BABANGIDA', '2025-02-09', '12:44:39', 'muhammadushafa@gmail.com', 'Paid', '0', '', '5180000', '', NULL, NULL, NULL, NULL, 1282000),
(900, '250209531660338', 7, 76, 1295000, 6, 7770000, NULL, 'ALH BABANGIDA', '2025-02-09', '12:47:09', 'muhammadushafa@gmail.com', 'Paid', '0', '', '7770000', '', NULL, NULL, NULL, NULL, 1282000),
(901, '250209712644963', 28, 77, 21500, 11, 236500, NULL, 'DADDY GUDUN', '2025-02-09', '12:54:18', 'muhammadushafa@gmail.com', 'Paid', '0', '273000', '', '', NULL, NULL, NULL, NULL, 20500),
(902, '250209712644963', 28, 70, 36500, 1, 36500, NULL, 'DADDY GUDUN', '2025-02-09', '12:54:35', 'muhammadushafa@gmail.com', 'Paid', '0', '', '', '', NULL, NULL, NULL, NULL, 32500),
(903, '250209565845612', 28, 71, 44000, 2, 88000, NULL, 'CUSTOMER', '2025-02-09', '13:29:19', 'muhammadushafa@gmail.com', 'Paid', '0', '88000', '', '', NULL, NULL, NULL, NULL, 42750),
(904, '250209737719021', 28, 70, 36500, 30, 1095000, NULL, 'HUSSAINI BURGA', '2025-02-09', '13:39:59', 'muhammadushafa@gmail.com', 'Paid', '0', '', '', '1095000', NULL, NULL, NULL, NULL, 32500),
(905, '250209526167972', 28, 75, 43000, 2, 86000, NULL, 'BABANGIDA KANGERE', '2025-02-09', '14:27:52', 'muhammadushafa@gmail.com', 'Paid', '0', '', '344500', '', NULL, NULL, NULL, NULL, 42230),
(906, '250209526167972', 28, 71, 43500, 5, 217500, NULL, 'BABANGIDA KANGERE', '2025-02-09', '14:28:15', 'muhammadushafa@gmail.com', 'Paid', '0', '', '', '', NULL, NULL, NULL, NULL, 42750),
(907, '250209526167972', 28, 66, 41000, 1, 41000, NULL, 'BABANGIDA KANGERE', '2025-02-09', '14:28:37', 'muhammadushafa@gmail.com', 'Paid', '0', '', '', '', NULL, NULL, NULL, NULL, 39700),
(908, '250209543162319', 28, 71, 43500, 5, 217500, NULL, 'MALAMI GOKARU', '2025-02-09', '14:31:04', 'muhammadushafa@gmail.com', 'Paid', '0', '', '217500', '', NULL, NULL, NULL, NULL, 42750),
(909, '250209807273066', 28, 66, 41000, 20, 820000, NULL, 'NASIRU LK', '2025-02-09', '14:55:11', 'muhammadushafa@gmail.com', 'Paid', '0', '', '', '992500', NULL, NULL, NULL, NULL, 39700),
(910, '250209807273066', 28, 73, 34500, 5, 172500, NULL, 'NASIRU LK', '2025-02-09', '14:55:59', 'muhammadushafa@gmail.com', 'Paid', '0', '', '', '', NULL, NULL, NULL, NULL, 33850),
(911, '250209921272229', 28, 71, 43500, 1, 43500, NULL, 'CUSTOMER', '2025-02-09', '15:50:50', 'muhammadushafa@gmail.com', 'Paid', '0', '40000', '', '3500', NULL, NULL, NULL, NULL, 42750),
(913, '250209215372716', 28, 71, 43500, 2, 87000, NULL, 'AHMADU YELWA', '2025-02-09', '18:06:09', 'muhammadushafa@gmail.com', 'Paid', '0', '', '87000', '', NULL, NULL, NULL, NULL, 42750),
(915, '250209463993462', 7, 76, 1295000, 10, 0, 12950000, 'ABDULHAMID', '2025-02-09', '18:10:28', 'muhammadushafa@gmail.com', 'Credit', '7066646961', NULL, NULL, NULL, NULL, 23, NULL, NULL, 1282000),
(916, '250209711402651', 28, 71, 43500, 10, 435000, NULL, 'ENGR LK', '2025-02-09', '18:15:15', 'muhammadushafa@gmail.com', 'Paid', '0', '', '', '435000', NULL, NULL, NULL, NULL, 42750),
(917, '250210576808954', 28, 73, 35000, 2, 70000, NULL, 'CUSTOMER', '2025-02-10', '10:13:10', 'muhammadushafa@gmail.com', 'Paid', '0', '', '', '70000', NULL, NULL, NULL, NULL, 33850),
(919, '250210919221195', 28, 71, 43500, 50, 0, 2175000, 'KAMALU LK', '2025-02-10', '10:21:09', 'muhammadushafa@gmail.com', 'Credit', '1234567890', NULL, NULL, NULL, NULL, 24, NULL, NULL, 42750),
(920, '250210613490891', 28, 70, 36500, 10, 365000, NULL, 'ENGR LK', '2025-02-10', '10:22:50', 'muhammadushafa@gmail.com', 'Paid', '0', '', '', '365000', NULL, NULL, NULL, NULL, 32500),
(923, '250210418454356', 28, 70, 36500, 6, 0, 219000, 'BON BOI', '2025-02-10', '11:30:16', 'muhammadushafa@gmail.com', 'Credit', '8080071321', NULL, NULL, NULL, NULL, 25, NULL, NULL, 32500),
(924, '250210418454356', 28, 71, 43500, 5, 0, 217500, 'BON BOI', '2025-02-10', '11:30:30', 'muhammadushafa@gmail.com', 'Credit', '8080071321', NULL, NULL, NULL, NULL, 25, NULL, NULL, 42750),
(925, '250210460747803', 28, 71, 43600, 4, 174400, NULL, 'MUJAHID', '2025-02-10', '11:34:58', 'muhammadushafa@gmail.com', 'Paid', '0', '', '', '338900', NULL, NULL, NULL, NULL, 42750),
(926, '250210460747803', 28, 70, 36500, 2, 73000, NULL, 'MUJAHID', '2025-02-10', '11:35:16', 'muhammadushafa@gmail.com', 'Paid', '0', '', '', '', NULL, NULL, NULL, NULL, 32500),
(927, '250210460747803', 28, 73, 35000, 2, 70000, NULL, 'MUJAHID', '2025-02-10', '11:35:32', 'muhammadushafa@gmail.com', 'Paid', '0', '', '', '', NULL, NULL, NULL, NULL, 33850),
(928, '250210460747803', 28, 77, 21500, 1, 21500, NULL, 'MUJAHID', '2025-02-10', '11:35:49', 'muhammadushafa@gmail.com', 'Paid', '0', '', '', '', NULL, NULL, NULL, NULL, 20500),
(929, '250210498137263', 27, 72, 43800, 15, 657000, NULL, 'SANI DASS', '2025-02-10', '11:45:07', 'muhammadushafa@gmail.com', 'Paid', '0', '', '657000', '', NULL, NULL, NULL, NULL, 41000),
(930, '250210111900992', 28, 67, 45800, 1, 45800, NULL, 'CUSTOMER', '2025-02-10', '12:08:01', 'muhammadushafa@gmail.com', 'Paid', '0', '82500', '', '', NULL, NULL, NULL, NULL, 41000),
(932, '250210111900992', 28, 70, 36700, 1, 36700, NULL, 'CUSTOMER', '2025-02-10', '12:08:52', 'muhammadushafa@gmail.com', 'Paid', '0', '', '', '', NULL, NULL, NULL, NULL, 32500),
(933, '250210811636850', 28, 71, 44000, 2, 88000, NULL, 'CUSTOMER', '2025-02-10', '12:16:41', 'muhammadushafa@gmail.com', 'Paid', '0', '88000', '', '', NULL, NULL, NULL, NULL, 42750),
(934, '250210589208075', 28, 70, 36500, 10, 365000, NULL, 'SALIM LK', '2025-02-10', '12:52:21', 'muhammadushafa@gmail.com', 'Paid', '0', '', '365000', '', NULL, NULL, NULL, NULL, 32500),
(936, '250210816056127', 28, 70, 36500, 20, 0, 730000, 'ALH NUHU', '2025-02-10', '13:15:05', 'muhammadushafa@gmail.com', 'Credit', '123456789', NULL, NULL, NULL, NULL, 26, NULL, NULL, 32500),
(938, '250210532899756', 28, 73, 34500, 10, 0, 345000, 'AMINU RIMI', '2025-02-10', '13:50:40', 'muhammadushafa@gmail.com', 'Credit', '801234567', NULL, NULL, NULL, NULL, 27, NULL, NULL, 33850),
(939, '250210174463201', 28, 70, 36500, 5, 182500, NULL, 'ALIYU TASHA', '2025-02-10', '15:40:41', 'muhammadushafa@gmail.com', 'Paid', '0', '', '182500', '', NULL, NULL, NULL, NULL, 32500),
(940, '250210811623726', 28, 70, 36500, 2, 73000, NULL, 'LUKMAN', '2025-02-10', '17:48:28', 'muhammadushafa@gmail.com', 'Paid', '0', '162000', '', '', NULL, NULL, NULL, NULL, 32500),
(941, '250210811623726', 28, 71, 43500, 1, 43500, NULL, 'LUKMAN', '2025-02-10', '17:48:44', 'muhammadushafa@gmail.com', 'Paid', '0', '', '', '', NULL, NULL, NULL, NULL, 42750),
(942, '250210811623726', 28, 67, 45500, 1, 45500, NULL, 'LUKMAN', '2025-02-10', '17:49:15', 'muhammadushafa@gmail.com', 'Paid', '0', '', '', '', NULL, NULL, NULL, NULL, 41000),
(943, '250210263941767', 28, 71, 43500, 3, 130500, NULL, 'ALIYU DINDIMA', '2025-02-10', '17:51:50', 'muhammadushafa@gmail.com', 'Paid', '0', '149500', '', '200000', NULL, NULL, NULL, NULL, 42750),
(944, '250210263941767', 28, 70, 36500, 6, 219000, NULL, 'ALIYU DINDIMA', '2025-02-10', '17:52:08', 'muhammadushafa@gmail.com', 'Paid', '0', '', '', '', NULL, NULL, NULL, NULL, 32500),
(945, '250210153211158', 7, 37, 16500, 25, 412500, NULL, 'YAU TASHAN MASS', '2025-02-10', '17:53:49', 'muhammadushafa@gmail.com', 'Paid', '0', '', '412500', '', NULL, NULL, NULL, NULL, 15100),
(946, '250210379707085', 28, 70, 36500, 10, 365000, NULL, 'ENGR LK', '2025-02-10', '18:13:17', 'muhammadushafa@gmail.com', 'Paid', '0', '', '', '365000', NULL, NULL, NULL, NULL, 32500),
(953, '250210187721990', 28, 71, 43500, 10, 435000, NULL, 'MUHD NABORDO', '2025-02-10', '18:59:59', 'muhammadushafa@gmail.com', 'Paid', '0', '', '', '1370000', NULL, NULL, NULL, NULL, 42750),
(954, '250210187721990', 28, 70, 36500, 20, 730000, NULL, 'MUHD NABORDO', '2025-02-10', '19:00:13', 'muhammadushafa@gmail.com', 'Paid', '0', '', '', '', NULL, NULL, NULL, NULL, 32500),
(955, '250210187721990', 28, 66, 41000, 5, 205000, NULL, 'MUHD NABORDO', '2025-02-10', '19:00:42', 'muhammadushafa@gmail.com', 'Paid', '0', '', '', '', NULL, NULL, NULL, NULL, 39700),
(962, '250210142345918', 28, 71, 43500, 45, 1957500, NULL, 'ATAMP', '2025-02-10', '19:25:51', 'muhammadushafa@gmail.com', 'Paid', '0', '', '', '2915000', NULL, NULL, NULL, NULL, 42750),
(963, '250210142345918', 28, 70, 36500, 20, 730000, NULL, 'ATAMP', '2025-02-10', '19:26:39', 'muhammadushafa@gmail.com', 'Paid', '0', '', '', '', NULL, NULL, NULL, NULL, 32500),
(964, '250210142345918', 28, 67, 45500, 5, 227500, NULL, 'ATAMP', '2025-02-10', '19:27:12', 'muhammadushafa@gmail.com', 'Paid', '0', '', '', '', NULL, NULL, NULL, NULL, 41000),
(968, '250210473800037', 28, 71, 43500, 50, 0, 2175000, 'DANIEL T/B', '2025-02-10', '20:27:05', 'muhammadushafa@gmail.com', 'Credit', '801234589', NULL, NULL, NULL, NULL, 28, NULL, NULL, 42750),
(969, '250210473800037', 28, 66, 41000, 30, 0, 1230000, 'DANIEL T/B', '2025-02-10', '20:27:31', 'muhammadushafa@gmail.com', 'Credit', '801234589', NULL, NULL, NULL, NULL, 28, NULL, NULL, 39700),
(971, '250210473800037', 28, 70, 36400, 20, 0, 728000, 'DANIEL T/B', '2025-02-10', '20:30:56', 'muhammadushafa@gmail.com', 'Credit', '801234589', NULL, NULL, NULL, NULL, 28, NULL, NULL, 32500),
(973, '250211281287359', 28, 71, 43500, 2, 87000, NULL, 'AHMAD YELWA', '2025-02-11', '10:55:13', 'muhammadushafa@gmail.com', 'Paid', '0', '7000', '80000', '', NULL, NULL, NULL, NULL, 42750),
(974, '250211609304987', 28, 71, 43500, 15, 652500, NULL, 'ALH NUHU', '2025-02-11', '10:59:01', 'muhammadushafa@gmail.com', 'Paid', '0', '', '652500', '', NULL, NULL, NULL, NULL, 42750),
(975, '250211379979726', 28, 70, 36500, 1, 36500, NULL, 'NUHU ZAHARADDIN', '2025-02-11', '11:00:19', 'muhammadushafa@gmail.com', 'Paid', '0', '', '', '36500', NULL, NULL, NULL, NULL, 32500),
(976, '250211531483469', 28, 71, 43500, 10, 435000, NULL, 'ALIYU', '2025-02-11', '11:10:13', 'muhammadushafa@gmail.com', 'Paid', '0', '', '544500', '', NULL, NULL, NULL, NULL, 42750),
(977, '250211531483469', 28, 70, 36500, 3, 109500, NULL, 'ALIYU', '2025-02-11', '11:10:52', 'muhammadushafa@gmail.com', 'Paid', '0', '', '', '', NULL, NULL, NULL, NULL, 32500),
(980, '250211913043524', 28, 71, 43500, 10, 435000, NULL, 'ALH SALE', '2025-02-11', '11:19:57', 'muhammadushafa@gmail.com', 'Paid', '0', '', '', '800000', NULL, NULL, NULL, NULL, 42750),
(981, '250211913043524', 28, 70, 36500, 10, 365000, NULL, 'ALH SALE', '2025-02-11', '11:20:12', 'muhammadushafa@gmail.com', 'Paid', '0', '', '', '', NULL, NULL, NULL, NULL, 32500),
(982, '250211529607274', 28, 71, 43500, 3, 130500, NULL, 'ABUBAKAR DASS', '2025-02-11', '11:21:53', 'muhammadushafa@gmail.com', 'Paid', '0', '130500', '', '', NULL, NULL, NULL, NULL, 42750),
(984, '08080071321', NULL, NULL, NULL, NULL, 436500, NULL, 'BON BOI', '2025-02-11', '11:26:43', 'muhammadushafa@gmail.com', 'Paid', NULL, '436500', NULL, NULL, NULL, 25, 'bon boi', 'settlement', NULL),
(985, '250211850120455', 28, 75, 43000, 5, 215000, NULL, 'SANI DASS', '2025-02-11', '12:08:34', 'muhammadushafa@gmail.com', 'Paid', '0', '', '2064500', '', NULL, NULL, NULL, NULL, 42230),
(986, '250211850120455', 28, 67, 45400, 5, 227000, NULL, 'SANI DASS', '2025-02-11', '12:08:53', 'muhammadushafa@gmail.com', 'Paid', '0', '', '', '', NULL, NULL, NULL, NULL, 41000),
(987, '250211850120455', 28, 66, 41000, 5, 205000, NULL, 'SANI DASS', '2025-02-11', '12:09:32', 'muhammadushafa@gmail.com', 'Paid', '0', '', '', '', NULL, NULL, NULL, NULL, 39700),
(988, '250211850120455', 28, 70, 36500, 15, 547500, NULL, 'SANI DASS', '2025-02-11', '12:10:11', 'muhammadushafa@gmail.com', 'Paid', '0', '', '', '', NULL, NULL, NULL, NULL, 32500),
(989, '250211850120455', 28, 71, 43500, 20, 870000, NULL, 'SANI DASS', '2025-02-11', '12:10:25', 'muhammadushafa@gmail.com', 'Paid', '0', '', '', '', NULL, NULL, NULL, NULL, 42750),
(990, '250211649456978', 28, 71, 43500, 50, 0, 2175000, 'DANIEL T/B', '2025-02-11', '12:14:18', 'muhammadushafa@gmail.com', 'Credit', '801234589', NULL, NULL, NULL, NULL, 28, NULL, NULL, 42750),
(991, '250211649456978', 28, 70, 36500, 30, 0, 1095000, 'DANIEL T/B', '2025-02-11', '12:14:43', 'muhammadushafa@gmail.com', 'Credit', '801234589', NULL, NULL, NULL, NULL, 28, NULL, NULL, 32500),
(992, '250211649456978', 27, 72, 44000, 5, 0, 220000, 'DANIEL T/B', '2025-02-11', '12:15:04', 'muhammadushafa@gmail.com', 'Credit', '801234589', NULL, NULL, NULL, NULL, 28, NULL, NULL, 41000),
(993, '250211170069777', 28, 70, 36500, 9, 328500, NULL, 'GWAMNA', '2025-02-11', '12:21:27', 'muhammadushafa@gmail.com', 'Paid', '0', '', '', '637000', NULL, NULL, NULL, NULL, 32500),
(994, '250211170069777', 28, 71, 43500, 5, 217500, NULL, 'GWAMNA', '2025-02-11', '12:22:57', 'muhammadushafa@gmail.com', 'Paid', '0', '', '', '', NULL, NULL, NULL, NULL, 42750),
(995, '250211170069777', 28, 67, 45500, 2, 91000, NULL, 'GWAMNA', '2025-02-11', '12:23:21', 'muhammadushafa@gmail.com', 'Paid', '0', '', '', '', NULL, NULL, NULL, NULL, 41000),
(996, '250211378105734', 28, 71, 43500, 15, 652500, NULL, 'MUSTAPHA DASS', '2025-02-11', '12:54:01', 'muhammadushafa@gmail.com', 'Paid', '0', '', '652500', '', NULL, NULL, NULL, NULL, 42750),
(997, '250211995151709', 28, 70, 36500, 3, 109500, NULL, 'MUJAHID', '2025-02-11', '13:06:07', 'muhammadushafa@gmail.com', 'Paid', '0', '', '', '109500', NULL, NULL, NULL, NULL, 32500),
(998, '250211995151709', 28, 71, 43500, 3, 130500, NULL, 'ABBA', '2025-02-11', '13:07:25', 'muhammadushafa@gmail.com', 'Not-Paid', '0', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 42750),
(999, '250211643745016', 28, 71, 43500, 3, 130500, NULL, 'ABBA', '2025-02-11', '13:08:44', 'muhammadushafa@gmail.com', 'Paid', '0', '', '130500', '', NULL, NULL, NULL, NULL, 42750),
(1000, '250211100822567', 28, 70, 36600, 3, 109800, NULL, 'SAIFULLAHI', '2025-02-11', '13:14:27', 'muhammadushafa@gmail.com', 'Paid', '0', '', '', '197200', NULL, NULL, NULL, NULL, 32500),
(1001, '250211100822567', 28, 71, 43700, 2, 87400, NULL, 'SAIFULLAHI', '2025-02-11', '13:14:44', 'muhammadushafa@gmail.com', 'Paid', '0', '', '', '', NULL, NULL, NULL, NULL, 42750),
(1002, '250211793767233', 28, 70, 36500, 5, 182500, NULL, 'MUSTAPHA DASS', '2025-02-11', '13:29:15', 'muhammadushafa@gmail.com', 'Paid', '0', '', '182500', '', NULL, NULL, NULL, NULL, 32500),
(1004, '250211771662426', 28, 75, 42800, 1, 42800, NULL, 'BABANGIDA KANGERE', '2025-02-11', '13:45:45', 'muhammadushafa@gmail.com', 'Paid', '0', '', '', '129800', NULL, NULL, NULL, NULL, 42230),
(1005, '250211771662426', 28, 71, 43500, 2, 87000, NULL, 'BABANGIDA KANGERE', '2025-02-11', '13:46:06', 'muhammadushafa@gmail.com', 'Paid', '0', '', '', '', NULL, NULL, NULL, NULL, 42750),
(1006, '250211393946556', 7, 34, 20000, 1, 20000, NULL, 'CUSTOMER', '2025-02-11', '15:28:46', 'muhammadushafa@gmail.com', 'Not-Paid', '0', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 15000),
(1007, '250211434027656', 28, 70, 36500, 5, 182500, NULL, 'BABANGIDA DASS', '2025-02-11', '15:39:08', 'muhammadushafa@gmail.com', 'Paid', '0', '', '', '532100', NULL, NULL, NULL, NULL, 32500),
(1008, '250211434027656', 28, 71, 43700, 8, 349600, NULL, 'BABANGIDA DASS', '2025-02-11', '15:39:44', 'muhammadushafa@gmail.com', 'Paid', '0', '', '', '', NULL, NULL, NULL, NULL, 42750),
(1009, '250211508275399', 28, 73, 35000, 2, 70000, NULL, 'LUKMAN', '2025-02-11', '17:24:39', 'muhammadushafa@gmail.com', 'Paid', '0', '70000', '', '', NULL, NULL, NULL, NULL, 33850),
(1010, '250211105293105', 28, 73, 35000, 50, 1750000, NULL, 'SILE DAUDA', '2025-02-11', '17:28:10', 'muhammadushafa@gmail.com', 'Paid', '0', '', '1750000', '', NULL, NULL, NULL, NULL, 33850),
(1011, '250211362038459', 28, 71, 43500, 30, 1305000, NULL, 'SILE DAUDA', '2025-02-11', '20:05:35', 'muhammadushafa@gmail.com', 'Paid', '0', '', '2125000', '', NULL, NULL, NULL, NULL, 42750),
(1012, '250211362038459', 28, 66, 41000, 20, 820000, NULL, 'SILE DAUDA', '2025-02-11', '20:06:14', 'muhammadushafa@gmail.com', 'Paid', '0', '', '', '', NULL, NULL, NULL, NULL, 39700),
(1013, '250212999528196', 28, 71, 43800, 1, 43800, NULL, 'UMAR TUDU', '2025-02-12', '10:38:19', 'muhammadushafa@gmail.com', 'Paid', '0', '43800', '', '', NULL, NULL, NULL, NULL, 42750),
(1014, '250212549722601', 28, 71, 43500, 3, 130500, NULL, 'UMAR TUDU', '2025-02-12', '10:43:45', 'muhammadushafa@gmail.com', 'Paid', '0', '', '130500', '', NULL, NULL, NULL, NULL, 42750),
(1015, '250212900996781', 28, 71, 43500, 2, 0, 87000, 'DAN GARI', '2025-02-12', '13:26:13', 'muhammadushafa@gmail.com', 'Credit', '12346789', NULL, NULL, NULL, NULL, 30, NULL, NULL, 42750),
(1017, '250212630483093', 28, 71, 43700, 2, 87400, NULL, 'ALARAMMA', '2025-02-12', '13:30:17', 'muhammadushafa@gmail.com', 'Paid', '0', '124000', '', '', NULL, NULL, NULL, NULL, 42750),
(1018, '250212630483093', 28, 70, 36600, 1, 36600, NULL, 'ALARAMMA', '2025-02-12', '13:31:08', 'muhammadushafa@gmail.com', 'Paid', '0', '', '', '', NULL, NULL, NULL, NULL, 32500),
(1019, '250212266405146', 28, 70, 36500, 10, 365000, NULL, 'ENGR LK', '2025-02-12', '13:34:42', 'muhammadushafa@gmail.com', 'Paid', '0', '', '', '800000', NULL, NULL, NULL, NULL, 32500),
(1020, '250212266405146', 28, 71, 43500, 10, 435000, NULL, 'ENGR LK', '2025-02-12', '13:35:01', 'muhammadushafa@gmail.com', 'Paid', '0', '', '', '', NULL, NULL, NULL, NULL, 42750),
(1021, '250212737843815', 28, 70, 36500, 10, 365000, NULL, 'NASIRU NABORDO', '2025-02-12', '13:39:20', 'muhammadushafa@gmail.com', 'Paid', '0', '', '365000', '', NULL, NULL, NULL, NULL, 32500),
(1022, '250212265052428', 7, 37, 16500, 12, 198000, NULL, 'ALH YAU', '2025-02-12', '13:41:10', 'muhammadushafa@gmail.com', 'Paid', '0', '', '198000', '', NULL, NULL, NULL, NULL, 15100),
(1023, '250212866594880', 28, 70, 36500, 1, 36500, NULL, 'DANLADI CENTRAL', '2025-02-12', '13:42:51', 'muhammadushafa@gmail.com', 'Paid', '0', '36500', '', '', NULL, NULL, NULL, NULL, 32500),
(1024, '250212314492850', 28, 71, 43500, 20, 870000, NULL, 'KONKIYAL', '2025-02-12', '13:44:13', 'muhammadushafa@gmail.com', 'Paid', '0', '', '870000', '', NULL, NULL, NULL, NULL, 42750),
(1025, '250212251043707', 28, 71, 43500, 5, 217500, NULL, 'SUNUSI LK', '2025-02-12', '14:27:54', 'muhammadushafa@gmail.com', 'Paid', '0', '217500', '', '', NULL, NULL, NULL, NULL, 42750),
(1026, '250212895605322', 28, 67, 45200, 10, 452000, NULL, 'BABBAN YAYA', '2025-02-12', '16:31:02', 'muhammadushafa@gmail.com', 'Paid', '0', '452000', '', '', NULL, NULL, NULL, NULL, 41000),
(1027, '250212647102388', 28, 71, 43500, 1, 43500, NULL, 'CUSTOMER', '2025-02-12', '16:32:51', 'muhammadushafa@gmail.com', 'Paid', '0', '43500', '', '', NULL, NULL, NULL, NULL, 42750),
(1028, '250212632446837', 28, 70, 36600, 1, 36600, NULL, 'CUSTOMER', '2025-02-12', '16:34:06', 'muhammadushafa@gmail.com', 'Paid', '0', '36600', '', '', NULL, NULL, NULL, NULL, 32500),
(1029, '0801234589', NULL, NULL, NULL, NULL, 7623000, NULL, 'DANIEL T/B', '2025-02-12', '18:18:43', 'nafiumohammedomar@gmail.com', 'Paid', NULL, '7623000', NULL, NULL, NULL, 28, 'dani', 'settlement', NULL),
(1030, '250212270811656', 28, 71, 43500, 100, 0, 4350000, 'DANIEL T/B', '2025-02-12', '18:22:02', 'nafiumohammedomar@gmail.com', 'Credit', '801234589', NULL, NULL, NULL, NULL, 28, NULL, NULL, 42750),
(1031, '250212270811656', 27, 72, 44000, 15, 0, 660000, 'DANIEL T/B', '2025-02-12', '18:22:57', 'nafiumohammedomar@gmail.com', 'Credit', '801234589', NULL, NULL, NULL, NULL, 28, NULL, NULL, 41000),
(1032, '250212274194747', 28, 70, 36500, 15, 0, 547500, 'BABANGIDA LK', '2025-02-12', '18:26:50', 'nafiumohammedomar@gmail.com', 'Credit', '812345678', NULL, NULL, NULL, NULL, 31, NULL, NULL, 32500),
(1033, '250212274194747', 28, 71, 43500, 20, 0, 870000, 'BABANGIDA LK', '2025-02-12', '18:27:11', 'nafiumohammedomar@gmail.com', 'Credit', '812345678', NULL, NULL, NULL, NULL, 31, NULL, NULL, 42750),
(1034, '250213208047846', 27, 65, 16500, 1, 16500, NULL, 'DANBURAM', '2025-02-13', '10:22:06', 'muhammadushafa@gmail.com', 'Paid', '0', '16500', '', '', NULL, NULL, NULL, NULL, 15100),
(1035, '250213781008908', 28, 71, 43500, 20, 870000, NULL, 'ATAMP', '2025-02-13', '11:22:04', 'nafiumohammedomar@gmail.com', 'Paid', '0', '', '1223000', '', NULL, NULL, NULL, NULL, 42750),
(1036, '250213781008908', 28, 79, 35300, 10, 353000, NULL, 'ATAMP', '2025-02-13', '11:23:05', 'nafiumohammedomar@gmail.com', 'Paid', '0', '', '', '', NULL, NULL, NULL, NULL, 32500),
(1037, '250213874123451', 28, 79, 35300, 20, 706000, NULL, 'ZADAWA', '2025-02-13', '11:26:01', 'nafiumohammedomar@gmail.com', 'Paid', '0', '', '706000', '', NULL, NULL, NULL, NULL, 32500),
(1038, '250213475806440', 28, 70, 36500, 2, 73000, NULL, 'ABUBAKAR ', '2025-02-13', '12:20:18', 'nafiumohammedomar@gmail.com', 'Paid', '0', '', '', '73000', NULL, NULL, NULL, NULL, 32500),
(1039, '250213573620338', 28, 79, 35200, 30, 1056000, NULL, 'DR NABARDO', '2025-02-13', '12:28:05', 'nafiumohammedomar@gmail.com', 'Paid', '0', '', '2546000', '', NULL, NULL, NULL, NULL, 32500),
(1040, '250213573620338', 28, 71, 43400, 25, 1085000, NULL, 'DR NABARDO', '2025-02-13', '12:29:05', 'nafiumohammedomar@gmail.com', 'Paid', '0', '', '', '', NULL, NULL, NULL, NULL, 42750),
(1042, '250213573620338', 28, 74, 40500, 10, 405000, NULL, 'DR NABARDO', '2025-02-13', '12:31:16', 'nafiumohammedomar@gmail.com', 'Paid', '0', '', '', '', NULL, NULL, NULL, NULL, 40700),
(1043, '250213637314764', 28, 70, 36600, 1, 36600, NULL, 'JIGO MAIGADI', '2025-02-13', '12:43:44', 'nafiumohammedomar@gmail.com', 'Paid', '0', '80400', '', '', NULL, NULL, NULL, NULL, 32500),
(1044, '250213637314764', 28, 71, 43800, 1, 43800, NULL, 'JIGO MAIGADI', '2025-02-13', '12:44:29', 'nafiumohammedomar@gmail.com', 'Paid', '0', '', '', '', NULL, NULL, NULL, NULL, 42750),
(1045, '250213979629400', 28, 70, 36500, 10, 365000, NULL, 'GWAMNA', '2025-02-13', '13:23:55', 'muhammadushafa@gmail.com', 'Paid', '0', '365000', '', '', NULL, NULL, NULL, NULL, 32500),
(1046, '250213199356315', 28, 75, 43000, 1, 43000, NULL, 'HASSAN', '2025-02-13', '15:16:49', 'muhammadushafa@gmail.com', 'Paid', '0', '', '43000', '', NULL, NULL, NULL, NULL, 42230),
(1047, '250213764491849', 28, 70, 36500, 10, 365000, NULL, 'ISHAQ', '2025-02-13', '15:18:36', 'muhammadushafa@gmail.com', 'Paid', '0', '', '365000', '', NULL, NULL, NULL, NULL, 32500),
(1050, '250213410213201', 28, 79, 35500, 1, 35500, NULL, 'ABDULLAHI', '2025-02-13', '15:38:20', 'muhammadushafa@gmail.com', 'Paid', '0', '161200', '', '', NULL, NULL, NULL, NULL, 32500),
(1051, '250213410213201', 28, 71, 43700, 1, 43700, NULL, 'ABDULLAHI', '2025-02-13', '15:39:31', 'muhammadushafa@gmail.com', 'Paid', '0', '', '', '', NULL, NULL, NULL, NULL, 42750),
(1052, '250213410213201', 28, 74, 41000, 2, 82000, NULL, 'ABDULLAHI', '2025-02-13', '15:40:33', 'muhammadushafa@gmail.com', 'Paid', '0', '', '', '', NULL, NULL, NULL, NULL, 40700),
(1053, '250213690436308', 28, 79, 35200, 50, 1760000, NULL, 'HAMISU MUDA', '2025-02-13', '16:19:49', 'muhammadushafa@gmail.com', 'Paid', '0', '1760000', '', '', NULL, NULL, NULL, NULL, 32500),
(1054, '250213523445133', 28, 79, 35500, 1, 35500, NULL, 'JOSEPH ISA', '2025-02-13', '16:21:21', 'muhammadushafa@gmail.com', 'Paid', '0', '', '', '35500', NULL, NULL, NULL, NULL, 32500),
(1055, '250213118581850', 28, 79, 35300, 10, 353000, NULL, 'MUSTAPHA DASS', '2025-02-13', '16:22:34', 'muhammadushafa@gmail.com', 'Paid', '0', '', '718000', '', NULL, NULL, NULL, NULL, 32500),
(1056, '250213118581850', 28, 70, 36500, 10, 365000, NULL, 'MUSTAPHA DASS', '2025-02-13', '16:22:53', 'muhammadushafa@gmail.com', 'Paid', '0', '', '', '', NULL, NULL, NULL, NULL, 32500),
(1057, '250213549463147', 28, 80, 43500, 5, 217500, NULL, 'ABUBAKAR DASS', '2025-02-13', '16:35:37', 'muhammadushafa@gmail.com', 'Paid', '0', '', '576500', '', NULL, NULL, NULL, NULL, 42750),
(1058, '250213549463147', 28, 79, 35300, 5, 176500, NULL, 'ABUBAKAR DASS', '2025-02-13', '16:36:16', 'muhammadushafa@gmail.com', 'Paid', '0', '', '', '', NULL, NULL, NULL, NULL, 32500),
(1059, '250213549463147', 28, 70, 36500, 5, 182500, NULL, 'ABUBAKAR DASS', '2025-02-13', '16:37:31', 'muhammadushafa@gmail.com', 'Paid', '0', '', '', '', NULL, NULL, NULL, NULL, 32500),
(1061, '250213509222013', 28, 80, 43500, 13, 565500, NULL, 'HUSSAINI BURGA', '2025-02-13', '16:53:18', 'nafiumohammedomar@gmail.com', 'Paid', '0', '', '975500', '', NULL, NULL, NULL, NULL, 42750),
(1062, '250213509222013', 28, 66, 41000, 10, 410000, NULL, 'HUSSAINI BURGA', '2025-02-13', '16:54:01', 'nafiumohammedomar@gmail.com', 'Paid', '0', '', '', '', NULL, NULL, NULL, NULL, 39700),
(1063, '250213562522261', 28, 80, 43500, 20, 870000, NULL, 'SAFIYANU BADAROMO', '2025-02-13', '17:01:00', 'nafiumohammedomar@gmail.com', 'Paid', '0', '', '870000', '', NULL, NULL, NULL, NULL, 42750),
(1064, '250213553640237', 28, 73, 35000, 10, 350000, NULL, 'ALH BAKO', '2025-02-13', '17:12:33', 'nafiumohammedomar@gmail.com', 'Paid', '0', '', '1348400', '', NULL, NULL, NULL, NULL, 33850),
(1065, '250213553640237', 28, 75, 42800, 3, 128400, NULL, 'ALH BAKO', '2025-02-13', '17:13:21', 'nafiumohammedomar@gmail.com', 'Paid', '0', '', '', '', NULL, NULL, NULL, NULL, 42230),
(1066, '250213553640237', 28, 80, 43500, 20, 870000, NULL, 'ALH BAKO', '2025-02-13', '17:18:32', 'nafiumohammedomar@gmail.com', 'Paid', '0', '', '', '', NULL, NULL, NULL, NULL, 42750),
(1067, '250213799540610', 28, 80, 43500, 20, 870000, NULL, 'ABDULMALIK KAFI', '2025-02-13', '17:33:17', 'nafiumohammedomar@gmail.com', 'Paid', '0', '', '1207500', '', NULL, NULL, NULL, NULL, 42750),
(1068, '250213799540610', 28, 70, 36500, 7, 255500, NULL, 'ABDULMALIK KAFI', '2025-02-13', '17:33:43', 'nafiumohammedomar@gmail.com', 'Paid', '0', '', '', '', NULL, NULL, NULL, NULL, 32500),
(1069, '250213799540610', 28, 66, 41000, 2, 82000, NULL, 'ABDULMALIK KAFI', '2025-02-13', '17:34:12', 'nafiumohammedomar@gmail.com', 'Paid', '0', '', '', '', NULL, NULL, NULL, NULL, 39700),
(1070, '250214533560631', 28, 71, 43500, 2, 87000, NULL, 'DIM-DIMA', '2025-02-14', '10:58:40', 'nafiumohammedomar@gmail.com', 'Paid', '0', '', '269500', '', NULL, NULL, NULL, NULL, 42750),
(1071, '250214533560631', 28, 70, 36500, 5, 182500, NULL, 'DIM-DIMA', '2025-02-14', '11:02:19', 'nafiumohammedomar@gmail.com', 'Paid', '0', '', '', '', NULL, NULL, NULL, NULL, 32500),
(1072, '250214965499359', 28, 80, 43500, 10, 435000, NULL, 'SHATIMAN BOI', '2025-02-14', '11:15:34', 'nafiumohammedomar@gmail.com', 'Paid', '0', '617500', '', '', NULL, NULL, NULL, NULL, 42750),
(1073, '250214965499359', 28, 70, 36500, 5, 182500, NULL, 'SHATIMAN BOI', '2025-02-14', '11:16:10', 'nafiumohammedomar@gmail.com', 'Paid', '0', '', '', '', NULL, NULL, NULL, NULL, 32500),
(1074, '250214763126885', 28, 80, 43500, 10, 435000, NULL, 'AYUBA NABARDO', '2025-02-14', '11:38:05', 'nafiumohammedomar@gmail.com', 'Paid', '0', '435000', '', '', NULL, NULL, NULL, NULL, 42750),
(1075, '250214107062287', 28, 77, 21500, 2, 43000, NULL, 'MUJAHID', '2025-02-14', '11:39:37', 'nafiumohammedomar@gmail.com', 'Paid', '0', '', '', '113400', NULL, NULL, NULL, NULL, 20500),
(1076, '250214107062287', 28, 79, 35400, 1, 35400, NULL, 'MUJAHID', '2025-02-14', '11:40:18', 'nafiumohammedomar@gmail.com', 'Paid', '0', '', '', '', NULL, NULL, NULL, NULL, 32500),
(1077, '250214107062287', 28, 73, 35000, 1, 35000, NULL, 'MUJAHID', '2025-02-14', '11:40:44', 'nafiumohammedomar@gmail.com', 'Paid', '0', '', '', '', NULL, NULL, NULL, NULL, 33850),
(1080, '250214215550906', 28, 70, 36500, 15, 547500, NULL, 'ALH SALE', '2025-02-14', '12:08:38', 'nafiumohammedomar@gmail.com', 'Paid', '0', '', '547500', '', NULL, NULL, NULL, NULL, 32500),
(1081, '250214822455899', 28, 71, 43700, 1, 43700, NULL, 'NASIRU DASS', '2025-02-14', '12:13:29', 'nafiumohammedomar@gmail.com', 'Paid', '0', '', '', '80300', NULL, NULL, NULL, NULL, 42750),
(1082, '250214822455899', 28, 70, 36600, 1, 36600, NULL, 'NASIRU DASS', '2025-02-14', '12:14:16', 'nafiumohammedomar@gmail.com', 'Paid', '0', '', '', '', NULL, NULL, NULL, NULL, 32500),
(1083, '250214915752390', 28, 80, 43500, 10, 435000, NULL, 'ENGR LK', '2025-02-14', '15:40:10', 'muhammadushafa@gmail.com', 'Paid', '0', '', '435000', '', NULL, NULL, NULL, NULL, 42750),
(1084, '250214638650052', 28, 71, 44000, 1, 44000, NULL, 'CUSTOMER', '2025-02-14', '15:42:03', 'muhammadushafa@gmail.com', 'Paid', '0', '44000', '', '', NULL, NULL, NULL, NULL, 42750),
(1085, '250214816987308', 28, 73, 35000, 20, 700000, NULL, 'ALH BAKO', '2025-02-14', '15:43:48', 'muhammadushafa@gmail.com', 'Paid', '0', '700000', '', '', NULL, NULL, NULL, NULL, 33850),
(1086, '250214207972647', 28, 70, 36500, 7, 255500, NULL, 'DAN&#039;GARI', '2025-02-14', '16:10:14', 'muhammadushafa@gmail.com', 'Paid', '0', '255500', '', '', NULL, NULL, NULL, NULL, 32500),
(1088, '250214448205950', 28, 71, 43800, 1, 43800, NULL, 'JONATHAN BITRUS', '2025-02-14', '16:12:46', 'muhammadushafa@gmail.com', 'Paid', '0', '43800', '', '', NULL, NULL, NULL, NULL, 42750),
(1089, '250214633337102', 28, 70, 36500, 1, 36500, NULL, 'AMADU MAKORAN', '2025-02-14', '16:14:23', 'muhammadushafa@gmail.com', 'Paid', '0', '36500', '', '', NULL, NULL, NULL, NULL, 32500),
(1090, '250214563144284', 28, 71, 43800, 2, 87600, NULL, 'MAHMOOD', '2025-02-14', '16:16:03', 'muhammadushafa@gmail.com', 'Paid', '0', '123100', '', '', NULL, NULL, NULL, NULL, 42750),
(1091, '250214563144284', 28, 79, 35500, 1, 35500, NULL, 'MAHMOOD', '2025-02-14', '16:16:32', 'muhammadushafa@gmail.com', 'Paid', '0', '', '', '', NULL, NULL, NULL, NULL, 32500),
(1092, '250214809310060', 28, 80, 43500, 60, 2610000, NULL, 'SANI DASS', '2025-02-14', '16:18:13', 'muhammadushafa@gmail.com', 'Paid', '0', '4070000', '', '', NULL, NULL, NULL, NULL, 42750),
(1093, '250214809310060', 28, 70, 36500, 40, 1460000, NULL, 'SANI DASS', '2025-02-14', '16:18:37', 'muhammadushafa@gmail.com', 'Paid', '0', '', '', '', NULL, NULL, NULL, NULL, 32500),
(1094, '250214329846452', 28, 79, 35500, 1, 35500, NULL, 'HUSSAINI ', '2025-02-14', '16:22:00', 'muhammadushafa@gmail.com', 'Paid', '0', '35500', '', '', NULL, NULL, NULL, NULL, 32500),
(1096, '250214490009279', 28, 71, 43800, 1, 43800, NULL, 'CUSTOMER', '2025-02-14', '16:38:30', 'muhammadushafa@gmail.com', 'Paid', '0', '43800', '', '', NULL, NULL, NULL, NULL, 42750),
(1097, '250214850803322', 28, 79, 35300, 15, 529500, NULL, 'ALH. ADO TUDU', '2025-02-14', '17:02:14', 'muhammadushafa@gmail.com', 'Paid', '0', '', '', '529500', NULL, NULL, NULL, NULL, 32500),
(1098, '250214810585492', 28, 79, 35200, 50, 1760000, NULL, 'HAMISU MUDA', '2025-02-14', '18:07:53', 'nafiumohammedomar@gmail.com', 'Paid', '0', '', '1760000', '', NULL, NULL, NULL, NULL, 32500),
(1099, '250214831895443', 27, 65, 16000, 10, 160000, NULL, 'DANBURAM', '2025-02-14', '18:16:45', 'nafiumohammedomar@gmail.com', 'Paid', '0', '160000', '', '', NULL, NULL, NULL, NULL, 15100),
(1100, '250214572602010', 28, 71, 43800, 1, 43800, NULL, 'MAL UMAR', '2025-02-14', '18:32:31', 'nafiumohammedomar@gmail.com', 'Paid', '0', '43800', '', '', NULL, NULL, NULL, NULL, 42750),
(1101, '250214156840580', 28, 66, 41000, 1, 41000, NULL, 'CUSTOMER', '2025-02-14', '19:11:44', 'muhammadushafa@gmail.com', 'Paid', '0', '41000', '', '', NULL, NULL, NULL, NULL, 39700),
(1102, '250214492129033', 28, 70, 36300, 25, 907500, NULL, 'ABBA SORRO', '2025-02-14', '19:14:37', 'muhammadushafa@gmail.com', 'Paid', '0', '1121500', '', '', NULL, NULL, NULL, NULL, 32500),
(1103, '250214492129033', 28, 75, 42800, 5, 214000, NULL, 'ABBA SORRO', '2025-02-14', '19:15:11', 'muhammadushafa@gmail.com', 'Paid', '0', '', '', '', NULL, NULL, NULL, NULL, 42230),
(1104, '250215510451194', 28, 78, 36000, 5, 180000, NULL, 'NUSA D ISA', '2025-02-15', '10:47:43', 'nafiumohammedomar@gmail.com', 'Paid', '0', '180000', '', '', NULL, NULL, NULL, NULL, 35000),
(1105, '250215288134785', 28, 70, 36500, 7, 255500, NULL, 'MUHD DURR', '2025-02-15', '10:58:08', 'nafiumohammedomar@gmail.com', 'Paid', '0', '429900', '', '', NULL, NULL, NULL, NULL, 32500),
(1106, '250215288134785', 28, 80, 43600, 4, 174400, NULL, 'MUHD DURR', '2025-02-15', '10:58:37', 'nafiumohammedomar@gmail.com', 'Paid', '0', '', '', '', NULL, NULL, NULL, NULL, 42750),
(1108, '250215613040113', 28, 80, 43500, 10, 435000, NULL, 'OGA KHALIF', '2025-02-15', '11:19:52', 'nafiumohammedomar@gmail.com', 'Paid', '0', '1210000', '', '', NULL, NULL, NULL, NULL, 42750),
(1109, '250215613040113', 28, 67, 45500, 5, 227500, NULL, 'OGA KHALIF', '2025-02-15', '11:20:10', 'nafiumohammedomar@gmail.com', 'Paid', '0', '', '', '', NULL, NULL, NULL, NULL, 41000),
(1110, '250215613040113', 28, 70, 36500, 15, 547500, NULL, 'OGA KHALIF', '2025-02-15', '11:21:49', 'nafiumohammedomar@gmail.com', 'Paid', '0', '', '', '', NULL, NULL, NULL, NULL, 32500),
(1111, '250215498863763', 28, 70, 36500, 2, 73000, NULL, 'ABUBAKAR CNTRL', '2025-02-15', '12:19:46', 'muhammadushafa@gmail.com', 'Paid', '0', '73000', '', '', NULL, NULL, NULL, NULL, 32500),
(1112, '250215450848328', 28, 80, 43500, 10, 435000, NULL, 'ENGR LK', '2025-02-15', '12:42:47', 'muhammadushafa@gmail.com', 'Paid', '0', '1693000', '', '', NULL, NULL, NULL, NULL, 42750),
(1113, '250215450848328', 28, 79, 35300, 10, 353000, NULL, 'ENGR LK', '2025-02-15', '12:43:07', 'muhammadushafa@gmail.com', 'Paid', '0', '', '', '', NULL, NULL, NULL, NULL, 32500),
(1114, '250215450848328', 28, 70, 36500, 20, 730000, NULL, 'ENGR LK', '2025-02-15', '12:43:27', 'muhammadushafa@gmail.com', 'Paid', '0', '', '', '', NULL, NULL, NULL, NULL, 32500),
(1115, '250215450848328', 28, 73, 35000, 5, 175000, NULL, 'ENGR LK', '2025-02-15', '12:44:07', 'muhammadushafa@gmail.com', 'Paid', '0', '', '', '', NULL, NULL, NULL, NULL, 40700),
(1116, '250215270306130', 28, 80, 43800, 3, 131400, NULL, 'KAFIN MADAKI', '2025-02-15', '13:19:22', 'muhammadushafa@gmail.com', 'Paid', '0', '204600', '', '', NULL, NULL, NULL, NULL, 42750),
(1117, '250215270306130', 28, 70, 36600, 2, 73200, NULL, 'KAFIN MADAKI', '2025-02-15', '13:19:41', 'muhammadushafa@gmail.com', 'Paid', '0', '', '', '', NULL, NULL, NULL, NULL, 32500),
(1118, '250215379955344', 28, 80, 43500, 10, 435000, NULL, 'ENGR LK', '2025-02-15', '15:29:38', 'nafiumohammedomar@gmail.com', 'Paid', '0', '', '', '435000', NULL, NULL, NULL, NULL, 42750),
(1119, '250215834794961', 28, 70, 36600, 1, 36600, NULL, 'AHMAD JIBRIN', '2025-02-15', '15:34:43', 'nafiumohammedomar@gmail.com', 'Paid', '0', '36600', '', '', NULL, NULL, NULL, NULL, 32500),
(1120, '250215699372200', 28, 70, 36500, 20, 730000, NULL, 'NASIRU LK', '2025-02-15', '16:17:12', 'muhammadushafa@gmail.com', 'Paid', '0', '', '1190500', '', NULL, NULL, NULL, NULL, 32500),
(1121, '250215699372200', 28, 79, 35300, 10, 353000, NULL, 'NASIRU LK', '2025-02-15', '16:17:33', 'muhammadushafa@gmail.com', 'Paid', '0', '', '', '', NULL, NULL, NULL, NULL, 32500),
(1122, '250215699372200', 28, 77, 21500, 5, 107500, NULL, 'NASIRU LK', '2025-02-15', '16:17:47', 'muhammadushafa@gmail.com', 'Paid', '0', '', '', '', NULL, NULL, NULL, NULL, 20500),
(1123, '250215782763029', 28, 80, 43500, 10, 435000, NULL, 'SULAIMAN ZANGO', '2025-02-15', '18:27:19', 'nafiumohammedomar@gmail.com', 'Paid', '0', '', '', '610000', NULL, NULL, NULL, NULL, 42750),
(1124, '250215782763029', 28, 73, 35000, 5, 175000, NULL, 'SULAIMAN ZANGO', '2025-02-15', '18:27:33', 'nafiumohammedomar@gmail.com', 'Paid', '0', '', '', '', NULL, NULL, NULL, NULL, 40700),
(1125, '250215709068979', 28, 70, 36500, 10, 365000, NULL, 'BABANGIDA KANGERE', '2025-02-15', '18:28:53', 'nafiumohammedomar@gmail.com', 'Paid', '0', '', '494100', '', NULL, NULL, NULL, NULL, 32500),
(1126, '250215709068979', 28, 75, 42800, 2, 85600, NULL, 'BABANGIDA KANGERE', '2025-02-15', '18:29:22', 'nafiumohammedomar@gmail.com', 'Paid', '0', '', '', '', NULL, NULL, NULL, NULL, 42230),
(1127, '250215709068979', 28, 71, 43500, 1, 43500, NULL, 'BABANGIDA KANGERE', '2025-02-15', '18:30:10', 'nafiumohammedomar@gmail.com', 'Paid', '0', '', '', '', NULL, NULL, NULL, NULL, 42750),
(1128, '250215571245066', 27, 65, 16000, 14, 224000, NULL, 'DANBURAM', '2025-02-15', '18:31:43', 'nafiumohammedomar@gmail.com', 'Paid', '0', '', '', '224000', NULL, NULL, NULL, NULL, 15100),
(1129, '250216412185851', 28, 79, 35500, 1, 35500, NULL, 'CUSTOMER', '2025-02-16', '10:45:41', 'nafiumohammedomar@gmail.com', 'Paid', '0', '', '', '35500', NULL, NULL, NULL, NULL, 32500),
(1130, '250216194610030', 28, 79, 35300, 4, 141200, NULL, 'DANGARI', '2025-02-16', '10:47:27', 'nafiumohammedomar@gmail.com', 'Paid', '0', '141200', '', '', NULL, NULL, NULL, NULL, 32500),
(1131, '250216613042158', 28, 70, 36500, 3, 109500, NULL, 'MUJAHID', '2025-02-16', '10:49:02', 'nafiumohammedomar@gmail.com', 'Paid', '0', '', '', '109500', NULL, NULL, NULL, NULL, 32500),
(1132, '250216838883717', 28, 79, 35300, 10, 353000, NULL, 'AYUBA NABARDO', '2025-02-16', '10:50:12', 'nafiumohammedomar@gmail.com', 'Paid', '0', '970000', '', '', NULL, NULL, NULL, NULL, 32500),
(1133, '250216838883717', 28, 70, 36400, 5, 182000, NULL, 'AYUBA NABARDO', '2025-02-16', '10:50:30', 'nafiumohammedomar@gmail.com', 'Paid', '0', '', '', '', NULL, NULL, NULL, NULL, 32500),
(1134, '250216838883717', 28, 80, 43500, 10, 435000, NULL, 'AYUBA NABARDO', '2025-02-16', '10:51:11', 'nafiumohammedomar@gmail.com', 'Paid', '0', '', '', '', NULL, NULL, NULL, NULL, 42750),
(1135, '250216157646717', 28, 80, 43700, 5, 218500, NULL, 'BOM-BOY DASS', '2025-02-16', '10:52:39', 'nafiumohammedomar@gmail.com', 'Paid', '0', '', '401500', '', NULL, NULL, NULL, NULL, 42750),
(1136, '250216157646717', 28, 70, 36600, 5, 183000, NULL, 'BOM-BOY DASS', '2025-02-16', '10:53:07', 'nafiumohammedomar@gmail.com', 'Paid', '0', '', '', '', NULL, NULL, NULL, NULL, 32500),
(1137, '250216744821283', 28, 70, 36800, 5, 184000, NULL, 'ABULKADIR ABUBAKAR', '2025-02-16', '11:34:55', 'muhammadushafa@gmail.com', 'Paid', '0', '184000', '', '', NULL, NULL, NULL, NULL, 32500),
(1138, '250216910298380', 28, 77, 21500, 5, 107500, NULL, 'AYUBA NABARDO', '2025-02-16', '13:19:19', 'nafiumohammedomar@gmail.com', 'Paid', '0', '', '107500', '', NULL, NULL, NULL, NULL, 20500),
(1139, '250216728113645', 28, 80, 43800, 6, 262800, NULL, 'YUSUF M FATEH', '2025-02-16', '13:31:32', 'nafiumohammedomar@gmail.com', 'Paid', '0', '', '', '262800', NULL, NULL, NULL, NULL, 42750),
(1141, '250216278164978', 28, 70, 36400, 20, 728000, NULL, 'BABANGIDA LK', '2025-02-16', '13:39:12', 'nafiumohammedomar@gmail.com', 'Paid', '0', '', '2304000', '', NULL, NULL, NULL, NULL, 32500),
(1142, '250216278164978', 28, 79, 35300, 20, 706000, NULL, 'BABANGIDA LK', '2025-02-16', '13:39:28', 'nafiumohammedomar@gmail.com', 'Paid', '0', '', '', '', NULL, NULL, NULL, NULL, 32500),
(1143, '250216278164978', 28, 80, 43500, 20, 870000, NULL, 'BABANGIDA LK', '2025-02-16', '13:39:51', 'nafiumohammedomar@gmail.com', 'Paid', '0', '', '', '', NULL, NULL, NULL, NULL, 42750),
(1144, '250216705421698', 28, 79, 35300, 5, 176500, NULL, 'ZADAWA', '2025-02-16', '13:42:52', 'nafiumohammedomar@gmail.com', 'Paid', '0', '', '176500', '', NULL, NULL, NULL, NULL, 32500),
(1145, '250216780699085', 28, 79, 35300, 10, 353000, NULL, 'AMEER', '2025-02-16', '13:45:36', 'nafiumohammedomar@gmail.com', 'Paid', '0', '717000', '', '', NULL, NULL, NULL, NULL, 32500),
(1146, '250216780699085', 28, 70, 36400, 10, 364000, NULL, 'AMEER', '2025-02-16', '13:46:00', 'nafiumohammedomar@gmail.com', 'Paid', '0', '', '', '', NULL, NULL, NULL, NULL, 32500),
(1147, '250216593807352', 28, 79, 35300, 20, 706000, NULL, 'ENGR LK', '2025-02-16', '13:48:15', 'nafiumohammedomar@gmail.com', 'Paid', '0', '706000', '', '', NULL, NULL, NULL, NULL, 32500),
(1148, '250216876946226', 28, 70, 36500, 4, 146000, NULL, 'ABUBAKAR BURGA', '2025-02-16', '14:05:14', 'nafiumohammedomar@gmail.com', 'Paid', '0', '7000', '', '139000', NULL, NULL, NULL, NULL, 32500),
(1149, '250216898411573', 28, 79, 35400, 2, 70800, NULL, 'ABBA BURGA', '2025-02-16', '15:40:45', 'muhammadushafa@gmail.com', 'Paid', '0', '300', '', '180000', NULL, NULL, NULL, NULL, 32500),
(1150, '250216898411573', 28, 70, 36500, 3, 109500, NULL, 'ABBA BURGA', '2025-02-16', '15:41:01', 'muhammadushafa@gmail.com', 'Paid', '0', '', '', '', NULL, NULL, NULL, NULL, 32500),
(1151, '250216462137858', 28, 70, 36500, 2, 73000, NULL, 'BUZAYE', '2025-02-16', '16:48:04', 'nafiumohammedomar@gmail.com', 'Paid', '0', '203500', '', '', NULL, NULL, NULL, NULL, 32500),
(1152, '250216462137858', 28, 80, 43500, 3, 130500, NULL, 'BUZAYE', '2025-02-16', '16:48:19', 'nafiumohammedomar@gmail.com', 'Paid', '0', '', '', '', NULL, NULL, NULL, NULL, 42750),
(1153, '250216989445027', 28, 75, 43000, 3, 129000, NULL, 'MAL UMAR', '2025-02-16', '17:49:38', 'nafiumohammedomar@gmail.com', 'Paid', '0', '163500', '', '', NULL, NULL, NULL, NULL, 42230),
(1154, '250216989445027', 28, 70, 34500, 1, 34500, NULL, 'MAL UMAR', '2025-02-16', '17:50:57', 'nafiumohammedomar@gmail.com', 'Paid', '0', NULL, '', '', NULL, NULL, NULL, NULL, 32500);
INSERT INTO `transaction_tbl` (`TID`, `tCode`, `tDepartment`, `Product`, `Price`, `qty`, `Amount`, `Credit`, `Customer`, `TransacDate`, `TransacTime`, `TrasacBy`, `Status`, `nhisno`, `cash`, `transfer`, `pos`, `crypto`, `CID`, `narration`, `creditstatus`, `pprice`) VALUES
(1155, '250216586006804', 28, 78, 36000, 2, 72000, NULL, 'BABA IBRAHIM', '2025-02-16', '18:13:42', 'nafiumohammedomar@gmail.com', 'Paid', '0', '72000', '', '', NULL, NULL, NULL, NULL, NULL),
(1156, '250216634570737', 28, 79, 35500, 2, 71000, NULL, 'K.B SAMA DASHI', '2025-02-16', '18:19:58', 'nafiumohammedomar@gmail.com', 'Paid', '0', '71000', '', '', NULL, NULL, NULL, NULL, NULL),
(1157, '250216413181441', 7, 76, 1295000, 3, 3885000, NULL, 'BABANGIDA BOXER', '2025-02-16', '19:20:06', 'nafiumohammedomar@gmail.com', 'Paid', '0', '', '3885000', '', NULL, NULL, NULL, NULL, NULL),
(1158, '250217765490167', 28, 80, 43500, 15, 652500, NULL, 'ABDUL NABARDO', '2025-02-17', '11:53:39', 'muhammadushafa@gmail.com', 'Paid', '0', '1194500', '', '', NULL, NULL, NULL, NULL, 43500),
(1159, '250217765490167', 28, 70, 36500, 10, 365000, NULL, 'ABDUL NABARDO', '2025-02-17', '11:54:04', 'muhammadushafa@gmail.com', 'Paid', '0', '1194500', '', '', NULL, NULL, NULL, NULL, 32500),
(1161, '250217765490167', 28, 79, 35400, 5, 177000, NULL, 'ABDUL NABARDO', '2025-02-17', '11:56:16', 'muhammadushafa@gmail.com', 'Paid', '0', '1194500', '', '', NULL, NULL, NULL, NULL, 32500),
(1162, '250217659305968', 28, 70, 36500, 10, 365000, NULL, 'SALIM LK', '2025-02-17', '12:06:24', 'muhammadushafa@gmail.com', 'Paid', '0', '365000', '', '', NULL, NULL, NULL, NULL, 32500),
(1163, '250217159704771', 28, 79, 35500, 2, 71000, NULL, 'DAN GARI', '2025-02-17', '12:07:47', 'muhammadushafa@gmail.com', 'Paid', '0', '158000', '', '', NULL, NULL, NULL, NULL, 32500),
(1164, '250217159704771', 28, 80, 43500, 2, 87000, NULL, 'DAN GARI', '2025-02-17', '12:08:23', 'muhammadushafa@gmail.com', 'Paid', '0', '158000', '', '', NULL, NULL, NULL, NULL, 43500),
(1165, '250217359145279', 28, 70, 36500, 1, 36500, NULL, 'ABDULRAHMAN CNTRL', '2025-02-17', '12:46:51', 'muhammadushafa@gmail.com', 'Paid', '0', '36500', '', '', NULL, NULL, NULL, NULL, 32500),
(1166, '250217572148792', 28, 79, 35400, 2, 70800, NULL, 'HAMISU NABORDO', '2025-02-17', '12:48:32', 'muhammadushafa@gmail.com', 'Paid', '0', '', '', '70800', NULL, NULL, NULL, NULL, 32500),
(1167, '250217675267602', 28, 70, 36350, 50, 1817500, NULL, 'ABDULRAHMAN CNTRL', '2025-02-17', '12:54:57', 'muhammadushafa@gmail.com', 'Paid', '0', '', '1817500', '', NULL, NULL, NULL, NULL, 32500),
(1168, '250217656198626', 28, 73, 35000, 10, 350000, NULL, 'MESSI', '2025-02-17', '13:55:30', 'muhammadushafa@gmail.com', 'Paid', '0', '', '420600', '', NULL, NULL, NULL, NULL, 33850),
(1169, '250217656198626', 28, 79, 35300, 2, 70600, NULL, 'MESSI', '2025-02-17', '13:55:55', 'muhammadushafa@gmail.com', 'Paid', '0', '', '420600', '', NULL, NULL, NULL, NULL, 32500),
(1170, '250217679737895', 28, 80, 43500, 10, 435000, NULL, 'BABANGIDA DORR', '2025-02-17', '14:53:29', 'muhammadushafa@gmail.com', 'Paid', '0', '', '717000', '', NULL, NULL, NULL, NULL, 43500),
(1171, '250217679737895', 28, 73, 35000, 3, 105000, NULL, 'BABANGIDA DORR', '2025-02-17', '14:53:58', 'muhammadushafa@gmail.com', 'Paid', '0', '', '717000', '', NULL, NULL, NULL, NULL, 33850),
(1172, '250217679737895', 28, 79, 35400, 5, 177000, NULL, 'BABANGIDA DORR', '2025-02-17', '14:54:19', 'muhammadushafa@gmail.com', 'Paid', '0', '', '717000', '', NULL, NULL, NULL, NULL, 32500),
(1173, '250217736868168', 28, 73, 35000, 1, 35000, NULL, 'LAWAL', '2025-02-17', '15:03:15', 'muhammadushafa@gmail.com', 'Paid', '0', '35000', '', '', NULL, NULL, NULL, NULL, 33850),
(1174, '250217792878748', 28, 80, 43500, 10, 435000, NULL, 'UMAR MIYA', '2025-02-17', '16:34:54', 'muhammadushafa@gmail.com', 'Paid', '0', '640000', '', '', NULL, NULL, NULL, NULL, 43500),
(1175, '250217792878748', 28, 66, 41000, 5, 205000, NULL, 'UMAR MIYA', '2025-02-17', '16:35:09', 'muhammadushafa@gmail.com', 'Paid', '0', '640000', '', '', NULL, NULL, NULL, NULL, 39700),
(1176, '250217860199566', 28, 70, 36500, 8, 292000, NULL, 'GWAMNA', '2025-02-17', '16:48:55', 'muhammadushafa@gmail.com', 'Paid', '0', '', '509500', '', NULL, NULL, NULL, NULL, 32500),
(1177, '250217860199566', 28, 80, 43500, 5, 217500, NULL, 'GWAMNA', '2025-02-17', '16:49:15', 'muhammadushafa@gmail.com', 'Paid', '0', '', '509500', '', NULL, NULL, NULL, NULL, 43500),
(1178, '250217139151680', 28, 80, 43400, 30, 1302000, NULL, 'SAFIYANU BADAROMO', '2025-02-17', '16:51:27', 'muhammadushafa@gmail.com', 'Paid', '0', '1504500', '', '', NULL, NULL, NULL, NULL, 43500),
(1179, '250217139151680', 28, 66, 40500, 5, 202500, NULL, 'SAFIYANU BADAROMO', '2025-02-17', '16:51:51', 'muhammadushafa@gmail.com', 'Paid', '0', '1504500', '', '', NULL, NULL, NULL, NULL, 39700),
(1180, '250217619288445', 28, 80, 43500, 20, 870000, NULL, 'ALH BAKO', '2025-02-17', '16:56:20', 'muhammadushafa@gmail.com', 'Paid', '0', '', '1220000', '', NULL, NULL, NULL, NULL, 43500),
(1181, '250217619288445', 28, 87, 70000, 5, 350000, NULL, 'ALH BAKO', '2025-02-17', '16:56:37', 'muhammadushafa@gmail.com', 'Paid', '0', '', '1220000', '', NULL, NULL, NULL, NULL, 66000),
(1182, '250217526103097', 28, 87, 70000, 2, 140000, NULL, 'DANLADI CENTRAL', '2025-02-17', '16:57:43', 'muhammadushafa@gmail.com', 'Paid', '0', '140000', '', '', NULL, NULL, NULL, NULL, 66000),
(1183, '250217391737163', 28, 79, 35300, 10, 353000, NULL, 'BABANGIDA KANGERE', '2025-02-17', '17:38:30', 'muhammadushafa@gmail.com', 'Paid', '0', '', '440000', '', NULL, NULL, NULL, NULL, 32500),
(1184, '250217391737163', 28, 80, 43500, 2, 87000, NULL, 'BABANGIDA KANGERE', '2025-02-17', '17:38:44', 'muhammadushafa@gmail.com', 'Paid', '0', '', '440000', '', NULL, NULL, NULL, NULL, 43500),
(1185, '250217858959036', 27, 65, 16000, 11, 176000, NULL, 'DANBURAM', '2025-02-17', '18:10:29', 'muhammadushafa@gmail.com', 'Paid', '0', '176000', '', '', NULL, NULL, NULL, NULL, 15100),
(1186, '250217559970400', 27, 72, 44000, 10, 440000, NULL, 'BUHARI SAID', '2025-02-17', '19:01:50', 'muhammadushafa@gmail.com', 'Paid', '0', '', '440000', '', NULL, NULL, NULL, NULL, 41000),
(1187, '250217102708142', 28, 80, 43800, 1, 43800, NULL, 'CUSTOMER', '2025-02-17', '19:04:14', 'muhammadushafa@gmail.com', 'Paid', '0', '43800', '', '', NULL, NULL, NULL, NULL, 43500),
(1188, '250218283712699', 28, 80, 43500, 1, 43500, NULL, 'BABANGIDA KANGERE', '2025-02-18', '10:43:16', 'muhammadushafa@gmail.com', 'Paid', '0', '', '129500', '', NULL, NULL, NULL, NULL, 42750),
(1189, '250218283712699', 28, 75, 43000, 2, 86000, NULL, 'BABANGIDA KANGERE', '2025-02-18', '10:43:31', 'muhammadushafa@gmail.com', 'Paid', '0', '', '129500', '', NULL, NULL, NULL, NULL, 42230),
(1190, '250218824267041', 28, 70, 36500, 15, 547500, NULL, 'YARIMA ', '2025-02-18', '10:45:14', 'muhammadushafa@gmail.com', 'Paid', '0', '', '1930000', '', NULL, NULL, NULL, NULL, 32500),
(1191, '250218824267041', 28, 73, 35000, 15, 525000, NULL, 'YARIMA ', '2025-02-18', '10:45:25', 'muhammadushafa@gmail.com', 'Paid', '0', '', '1930000', '', NULL, NULL, NULL, NULL, 33850),
(1192, '250218824267041', 28, 80, 43500, 15, 652500, NULL, 'YARIMA ', '2025-02-18', '10:45:42', 'muhammadushafa@gmail.com', 'Paid', '0', '', '1930000', '', NULL, NULL, NULL, NULL, 42750),
(1193, '250218824267041', 28, 66, 41000, 5, 205000, NULL, 'YARIMA ', '2025-02-18', '10:46:01', 'muhammadushafa@gmail.com', 'Paid', '0', '', '1930000', '', NULL, NULL, NULL, NULL, 39700),
(1194, '250218808409069', 28, 70, 36400, 50, 1820000, NULL, 'SILE DAUDA', '2025-02-18', '10:47:29', 'muhammadushafa@gmail.com', 'Paid', '0', '', '3655000', '', NULL, NULL, NULL, NULL, 32500),
(1195, '250218808409069', 28, 79, 35300, 50, 1765000, NULL, 'SILE DAUDA', '2025-02-18', '10:47:47', 'muhammadushafa@gmail.com', 'Paid', '0', '', '3655000', '', NULL, NULL, NULL, NULL, 32500),
(1196, '250218808409069', 28, 73, 35000, 2, 70000, NULL, 'SILE DAUDA', '2025-02-18', '10:48:18', 'muhammadushafa@gmail.com', 'Paid', '0', '', '3655000', '', NULL, NULL, NULL, NULL, 33850),
(1197, '250218542865796', 7, 76, 1295000, 2, 2590000, NULL, 'ALH BABANGIDA', '2025-02-18', '10:49:51', 'muhammadushafa@gmail.com', 'Paid', '0', '', '2590000', '', NULL, NULL, NULL, NULL, 1282000),
(1198, '250218711719860', 28, 73, 35000, 1, 35000, NULL, 'CUSTOMER', '2025-02-18', '10:51:39', 'muhammadushafa@gmail.com', 'Paid', '0', '70500', '', '', NULL, NULL, NULL, NULL, 33850),
(1199, '250218711719860', 28, 79, 35500, 1, 35500, NULL, 'CUSTOMER', '2025-02-18', '10:51:56', 'muhammadushafa@gmail.com', 'Paid', '0', '70500', '', '', NULL, NULL, NULL, NULL, 32500),
(1201, '250218205327397', 28, 79, 35300, 10, 353000, NULL, 'AMIR', '2025-02-18', '10:55:29', 'muhammadushafa@gmail.com', 'Paid', '0', '353000', '', '', NULL, NULL, NULL, NULL, 32500),
(1202, '250218940591970', 28, 80, 43500, 5, 217500, NULL, 'BABANGIDA KANGERE', '2025-02-18', '10:57:16', 'muhammadushafa@gmail.com', 'Paid', '0', '299500', '', '', NULL, NULL, NULL, NULL, 42750),
(1203, '250218940591970', 28, 66, 41000, 2, 82000, NULL, 'BABANGIDA KANGERE', '2025-02-18', '10:57:45', 'muhammadushafa@gmail.com', 'Paid', '0', '299500', '', '', NULL, NULL, NULL, NULL, 39700),
(1204, '250218857656066', 28, 79, 35500, 3, 106500, NULL, 'CUSTOMER', '2025-02-18', '11:39:02', 'muhammadushafa@gmail.com', 'Paid', '0', '148000', '', '', NULL, NULL, NULL, NULL, 32500),
(1205, '250218857656066', 28, 74, 41500, 1, 41500, NULL, 'CUSTOMER', '2025-02-18', '11:39:14', 'muhammadushafa@gmail.com', 'Paid', '0', '148000', '', '', NULL, NULL, NULL, NULL, 40700),
(1206, '250218597809817', 28, 70, 36500, 10, 365000, NULL, 'ALH SALE', '2025-02-18', '11:57:08', 'muhammadushafa@gmail.com', 'Paid', '0', '', '', '365000', NULL, NULL, NULL, NULL, 32500),
(1207, '250218121945552', 28, 79, 35500, 20, 710000, NULL, 'ALH NUHU', '2025-02-18', '12:12:00', 'muhammadushafa@gmail.com', 'Paid', '0', '710000', '', '', NULL, NULL, NULL, NULL, 32500),
(1208, '250218674415274', 28, 70, 36500, 1, 36500, NULL, 'CUSTOMER', '2025-02-18', '13:20:16', 'muhammadushafa@gmail.com', 'Paid', '0', '36500', '', '', NULL, NULL, NULL, NULL, 32500),
(1209, '250218101369906', 28, 79, 35400, 6, 212400, NULL, 'ABBAKAR DASS', '2025-02-18', '13:22:37', 'muhammadushafa@gmail.com', 'Paid', '0', '212400', '', '', NULL, NULL, NULL, NULL, 32500),
(1210, '250218860666761', 28, 70, 36500, 3, 109500, NULL, 'SUNUSI LK', '2025-02-18', '13:24:22', 'muhammadushafa@gmail.com', 'Paid', '0', '109500', '', '', NULL, NULL, NULL, NULL, 32500),
(1211, '250218725158103', 28, 80, 43800, 1, 43800, NULL, 'CUSTOMER', '2025-02-18', '14:19:08', 'muhammadushafa@gmail.com', 'Paid', '0', '80500', '', '', NULL, NULL, NULL, NULL, 42750),
(1212, '250218725158103', 28, 70, 36700, 1, 36700, NULL, 'CUSTOMER', '2025-02-18', '14:19:27', 'muhammadushafa@gmail.com', 'Paid', '0', '80500', '', '', NULL, NULL, NULL, NULL, 32500),
(1213, '250218667845583', 28, 70, 36700, 2, 73400, NULL, 'ALIYU', '2025-02-18', '14:34:39', 'muhammadushafa@gmail.com', 'Paid', '0', '100000', '', '102400', NULL, NULL, NULL, NULL, 32500),
(1214, '250218667845583', 28, 75, 43000, 3, 129000, NULL, 'ALIYU', '2025-02-18', '14:35:07', 'muhammadushafa@gmail.com', 'Paid', '0', '100000', '', '102400', NULL, NULL, NULL, NULL, 42230),
(1215, '250218346266871', 28, 70, 36500, 50, 1825000, NULL, 'HALLIRU', '2025-02-18', '15:26:41', 'muhammadushafa@gmail.com', 'Paid', '0', '1825000', '', '', NULL, NULL, NULL, NULL, 32500),
(1216, '250218235849784', 28, 70, 36400, 20, 728000, NULL, 'KAMALU LK', '2025-02-18', '16:05:58', 'nafiumohammedomar@gmail.com', 'Paid', '0', '1434000', '', '', NULL, NULL, NULL, NULL, 32500),
(1217, '250218235849784', 28, 79, 35300, 20, 706000, NULL, 'KAMALU LK', '2025-02-18', '16:06:13', 'nafiumohammedomar@gmail.com', 'Paid', '0', '1434000', '', '', NULL, NULL, NULL, NULL, 32500),
(1218, '250218829527029', 28, 79, 35300, 60, 2118000, NULL, 'SALISU MUDA', '2025-02-18', '16:07:42', 'nafiumohammedomar@gmail.com', 'Paid', '0', '', '', '2118000', NULL, NULL, NULL, NULL, 32500),
(1219, '250218708571954', 28, 70, 36500, 10, 365000, NULL, 'ABDULSALAM LK', '2025-02-18', '16:09:05', 'nafiumohammedomar@gmail.com', 'Paid', '0', '', '', '718000', NULL, NULL, NULL, NULL, 32500),
(1220, '250218708571954', 28, 79, 35300, 10, 353000, NULL, 'ABDULSALAM LK', '2025-02-18', '16:10:03', 'nafiumohammedomar@gmail.com', 'Paid', '0', '', '', '718000', NULL, NULL, NULL, NULL, 32500),
(1221, '250218938025086', 28, 80, 43800, 3, 131400, NULL, 'CUSTOMER', '2025-02-18', '16:12:00', 'nafiumohammedomar@gmail.com', 'Paid', '0', '131400', '', '', NULL, NULL, NULL, NULL, 42750),
(1222, '250218615233765', 28, 79, 36000, 3, 108000, NULL, 'AHMAD YELWA', '2025-02-18', '17:38:15', 'muhammadushafa@gmail.com', 'Paid', '0', '', '', '238500', NULL, NULL, NULL, NULL, 32500),
(1223, '250218615233765', 28, 80, 43500, 3, 130500, NULL, 'AHMAD YELWA', '2025-02-18', '17:38:35', 'muhammadushafa@gmail.com', 'Paid', '0', '', '', '238500', NULL, NULL, NULL, NULL, 42750),
(1224, '250218867964341', 27, 65, 16000, 5, 80000, NULL, 'DANBURAM', '2025-02-18', '18:20:50', 'muhammadushafa@gmail.com', 'Paid', '0', '80000', '', '', NULL, NULL, NULL, NULL, 15100),
(1225, '250218881852196', 28, 73, 34700, 1, 34700, NULL, 'ABBA GIADE', '2025-02-18', '18:25:53', 'muhammadushafa@gmail.com', 'Paid', '0', '', '', '34700', NULL, NULL, NULL, NULL, 33850),
(1226, '250218313431510', 28, 73, 34700, 19, 659300, NULL, 'ABBA GIADE', '2025-02-18', '18:27:52', 'muhammadushafa@gmail.com', 'Paid', '0', '', '', '659300', NULL, NULL, NULL, NULL, 33850),
(1227, '250218561627173', 28, 67, 45500, 10, 455000, NULL, 'ADAMU KIRFI', '2025-02-18', '19:24:47', 'muhammadushafa@gmail.com', 'Paid', '0', '', '455000', '', NULL, NULL, NULL, NULL, 41000),
(1228, '250218219114760', 27, 72, 43700, 10, 437000, NULL, 'BUHARI SAID', '2025-02-18', '19:26:59', 'muhammadushafa@gmail.com', 'Paid', '0', '', '437000', '', NULL, NULL, NULL, NULL, 41000),
(1230, '250219932549989', 28, 79, 36400, 6, 218400, NULL, 'DANGARI', '2025-02-19', '10:43:39', 'muhammadushafa@gmail.com', 'Paid', '0', '218400', '', '', NULL, NULL, NULL, NULL, 32500),
(1231, '250219869389375', 28, 70, 37500, 3, 112500, NULL, 'AMOS', '2025-02-19', '10:56:01', 'muhammadushafa@gmail.com', 'Paid', '0', '', '', '112500', NULL, NULL, NULL, NULL, 32500),
(1232, '250219808834472', 28, 70, 37500, 1, 37500, NULL, 'HAMZA ALIYU', '2025-02-19', '11:53:17', 'nafiumohammedomar@gmail.com', 'Paid', '0', '', '', '81500', NULL, NULL, NULL, NULL, 32500),
(1233, '250219808834472', 28, 80, 44000, 1, 44000, NULL, 'HAMZA ALIYU', '2025-02-19', '11:53:43', 'nafiumohammedomar@gmail.com', 'Paid', '0', '', '', '81500', NULL, NULL, NULL, NULL, 42750),
(1234, '250219934840621', 28, 70, 37400, 10, 374000, NULL, 'DIM-DIMA', '2025-02-19', '12:17:50', 'nafiumohammedomar@gmail.com', 'Paid', '0', '366500', '', '7500', NULL, NULL, NULL, NULL, 32500),
(1237, '250219730470770', 28, 79, 36500, 5, 182500, NULL, 'KALIF DASS', '2025-02-19', '12:24:18', 'nafiumohammedomar@gmail.com', 'Not-Paid', '0', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 32500),
(1238, '250219730470770', 28, 70, 37400, 15, 561000, NULL, 'KALIF DASS', '2025-02-19', '12:25:29', 'nafiumohammedomar@gmail.com', 'Not-Paid', '0', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 32500),
(1239, '250219176381743', 28, 70, 37400, 15, 561000, NULL, 'KALIF DASS', '2025-02-19', '12:28:49', 'nafiumohammedomar@gmail.com', 'Paid', '0', '740000', '94500', '', NULL, NULL, NULL, NULL, 32500),
(1240, '250219176381743', 28, 79, 36500, 5, 182500, NULL, 'KALIF DASS', '2025-02-19', '12:29:25', 'nafiumohammedomar@gmail.com', 'Paid', '0', '740000', '94500', '', NULL, NULL, NULL, NULL, 32500),
(1241, '250219176381743', 28, 67, 45500, 2, 91000, NULL, 'KALIF DASS', '2025-02-19', '12:29:54', 'nafiumohammedomar@gmail.com', 'Paid', '0', '740000', '94500', '', NULL, NULL, NULL, NULL, 41000),
(1242, '250219432173156', 28, 70, 37400, 50, 1870000, NULL, 'DANIEAL', '2025-02-19', '14:20:26', 'muhammadushafa@gmail.com', 'Paid', '0', '', '3690000', '', NULL, NULL, NULL, NULL, 32500),
(1243, '250219432173156', 28, 79, 36400, 50, 1820000, NULL, 'DANIEAL', '2025-02-19', '14:21:18', 'muhammadushafa@gmail.com', 'Paid', '0', '', '3690000', '', NULL, NULL, NULL, NULL, 32500),
(1244, '250219551139562', 28, 70, 37400, 50, 1870000, NULL, 'HALLIRU MUDA', '2025-02-19', '14:23:07', 'muhammadushafa@gmail.com', 'Paid', '0', '', '1870000', '', NULL, NULL, NULL, NULL, 32500),
(1245, '250219988817961', 28, 73, 35000, 5, 175000, NULL, 'ABDUL NABARDO', '2025-02-19', '14:25:24', 'muhammadushafa@gmail.com', 'Paid', '0', '1275500', '', '', NULL, NULL, NULL, NULL, 33850),
(1246, '250219988817961', 28, 66, 41000, 5, 205000, NULL, 'ABDUL NABARDO', '2025-02-19', '14:27:22', 'muhammadushafa@gmail.com', 'Paid', '0', '1275500', '', '', NULL, NULL, NULL, NULL, 39700),
(1248, '250219988817961', 28, 79, 36500, 15, 547500, NULL, 'ABDUL NABARDO', '2025-02-19', '14:29:19', 'muhammadushafa@gmail.com', 'Paid', '0', '1275500', '', '', NULL, NULL, NULL, NULL, 32500),
(1249, '250219988817961', 28, 80, 43500, 8, 348000, NULL, 'ABDUL NABARDO', '2025-02-19', '14:31:57', 'muhammadushafa@gmail.com', 'Paid', '0', '1275500', '', '', NULL, NULL, NULL, NULL, 42750),
(1250, '250219983210323', 28, 80, 43500, 100, 4350000, NULL, 'DANIEAL', '2025-02-19', '15:19:44', 'muhammadushafa@gmail.com', 'Paid', '0', '4350000', '', '', NULL, NULL, NULL, NULL, 42750),
(1251, '250219630143398', 28, 70, 37400, 20, 748000, NULL, 'HUSSAINI BURGA', '2025-02-19', '16:19:28', 'nafiumohammedomar@gmail.com', 'Paid', '0', '', '', '748000', NULL, NULL, NULL, NULL, 32500),
(1252, '250219617651893', 28, 70, 37500, 1, 37500, NULL, 'CUSTOMER', '2025-02-19', '16:21:20', 'nafiumohammedomar@gmail.com', 'Paid', '0', '', '', '37500', NULL, NULL, NULL, NULL, 32500),
(1253, '250219999433501', 28, 78, 36000, 2, 72000, NULL, 'BABANGIDA KANGERE', '2025-02-19', '16:38:52', 'nafiumohammedomar@gmail.com', 'Paid', '0', '', '72000', '', NULL, NULL, NULL, NULL, 35000),
(1254, '250219494846057', 28, 79, 36400, 8, 291200, NULL, 'BABANGIDA KANGERE', '2025-02-19', '16:55:32', 'nafiumohammedomar@gmail.com', 'Paid', '0', '', '', '378200', NULL, NULL, NULL, NULL, 32500),
(1255, '250219494846057', 28, 80, 43500, 2, 87000, NULL, 'BABANGIDA KANGERE', '2025-02-19', '16:56:02', 'nafiumohammedomar@gmail.com', 'Paid', '0', '', '', '378200', NULL, NULL, NULL, NULL, 42750),
(1256, '250219775103778', 28, 70, 37400, 10, 374000, NULL, 'ALH SALE', '2025-02-19', '18:06:52', 'nafiumohammedomar@gmail.com', 'Paid', '0', '', '', '556000', NULL, NULL, NULL, NULL, 32500),
(1257, '250219775103778', 28, 79, 36400, 5, 182000, NULL, 'ALH SALE', '2025-02-19', '18:07:43', 'nafiumohammedomar@gmail.com', 'Paid', '0', '', '', '556000', NULL, NULL, NULL, NULL, 32500),
(1258, '250219852514807', 28, 67, 45200, 7, 316400, NULL, 'BABBAN YAYA', '2025-02-19', '18:09:39', 'nafiumohammedomar@gmail.com', 'Paid', '0', '', '316400', '', NULL, NULL, NULL, NULL, 41000),
(1259, '250219578365312', 28, 70, 37300, 10, 373000, NULL, 'DANLADI CENTRAL', '2025-02-19', '19:24:13', 'muhammadushafa@gmail.com', 'Paid', '0', '', '', '373000', NULL, NULL, NULL, NULL, 32500),
(1260, '250220703303580', 28, 70, 37400, 15, 561000, NULL, 'AUWALU MIYA', '2025-02-20', '11:06:35', 'muhammadushafa@gmail.com', 'Paid', '0', '800000', '', '111000', NULL, NULL, NULL, NULL, 32500),
(1261, '250220703303580', 28, 87, 70000, 5, 350000, NULL, 'AUWALU MIYA', '2025-02-20', '11:06:49', 'muhammadushafa@gmail.com', 'Paid', '0', '800000', '', '111000', NULL, NULL, NULL, NULL, 66000),
(1262, '250220135545652', 28, 66, 41000, 2, 82000, NULL, 'ABDULLAHI', '2025-02-20', '11:17:11', 'muhammadushafa@gmail.com', 'Paid', '0', '28400', '', '90000', NULL, NULL, NULL, NULL, 39700),
(1263, '250220135545652', 28, 79, 36400, 1, 36400, NULL, 'ABDULLAHI', '2025-02-20', '11:17:56', 'muhammadushafa@gmail.com', 'Paid', '0', '28400', '', '90000', NULL, NULL, NULL, NULL, 32500),
(1264, '250220815723587', 28, 66, 41000, 4, 164000, NULL, 'ABDULHAKIM', '2025-02-20', '11:55:25', 'muhammadushafa@gmail.com', 'Paid', '0', '', '1003600', '', NULL, NULL, NULL, NULL, 39700),
(1265, '250220815723587', 28, 68, 40000, 4, 160000, NULL, 'ABDULHAKIM', '2025-02-20', '11:56:00', 'muhammadushafa@gmail.com', 'Paid', '0', '', '1003600', '', NULL, NULL, NULL, NULL, 36000),
(1266, '250220815723587', 28, 80, 43600, 2, 87200, NULL, 'ABDULHAKIM', '2025-02-20', '11:56:25', 'muhammadushafa@gmail.com', 'Paid', '0', '', '1003600', '', NULL, NULL, NULL, NULL, 42750),
(1267, '250220815723587', 28, 79, 36400, 6, 218400, NULL, 'ABDULHAKIM', '2025-02-20', '11:57:23', 'muhammadushafa@gmail.com', 'Paid', '0', '', '1003600', '', NULL, NULL, NULL, NULL, 32500),
(1268, '250220815723587', 28, 70, 37400, 10, 374000, NULL, 'ABDULHAKIM', '2025-02-20', '11:57:47', 'muhammadushafa@gmail.com', 'Paid', '0', '', '1003600', '', NULL, NULL, NULL, NULL, 32500),
(1269, '250220911648455', 28, 80, 43500, 20, 870000, NULL, 'ENGR LK', '2025-02-20', '12:01:18', 'muhammadushafa@gmail.com', 'Paid', '0', '', '1608000', '', NULL, NULL, NULL, NULL, 42750),
(1270, '250220911648455', 28, 79, 36400, 10, 364000, NULL, 'ENGR LK', '2025-02-20', '12:02:05', 'muhammadushafa@gmail.com', 'Paid', '0', '', '1608000', '', NULL, NULL, NULL, NULL, 32500),
(1271, '250220911648455', 28, 70, 37400, 10, 374000, NULL, 'ENGR LK', '2025-02-20', '12:02:26', 'muhammadushafa@gmail.com', 'Paid', '0', '', '1608000', '', NULL, NULL, NULL, NULL, 32500),
(1272, '250220667868322', 28, 75, 43000, 8, 344000, NULL, 'DANAZUMI BISHI', '2025-02-20', '12:03:52', 'muhammadushafa@gmail.com', 'Paid', '0', '', '344000', '', NULL, NULL, NULL, NULL, 42230),
(1273, '250220913686809', 28, 79, 36400, 2, 72800, NULL, 'SABITU', '2025-02-20', '12:04:52', 'muhammadushafa@gmail.com', 'Paid', '0', '', '72800', '', NULL, NULL, NULL, NULL, 32500),
(1274, '250220760704496', 28, 80, 43500, 20, 870000, NULL, 'UCHE GAJI', '2025-02-20', '12:06:02', 'muhammadushafa@gmail.com', 'Paid', '0', '', '1598000', '', NULL, NULL, NULL, NULL, 42750),
(1275, '250220760704496', 28, 79, 36400, 20, 728000, NULL, 'UCHE GAJI', '2025-02-20', '12:06:21', 'muhammadushafa@gmail.com', 'Paid', '0', '', '1598000', '', NULL, NULL, NULL, NULL, 32500),
(1276, '250220649437437', 28, 73, 35000, 13, 455000, NULL, 'ZADAWA', '2025-02-20', '12:07:24', 'muhammadushafa@gmail.com', 'Paid', '0', '', '455000', '', NULL, NULL, NULL, NULL, 33850),
(1277, '250220309129886', 28, 80, 43500, 5, 217500, NULL, 'AYUBA NABARDO', '2025-02-20', '12:11:24', 'muhammadushafa@gmail.com', 'Paid', '0', '217500', '', '', NULL, NULL, NULL, NULL, 42750),
(1278, '250220119643347', 28, 73, 35000, 40, 1400000, NULL, 'DANBURAM', '2025-02-20', '12:12:27', 'muhammadushafa@gmail.com', 'Paid', '0', '', '1400000', '', NULL, NULL, NULL, NULL, 33850),
(1279, '250220642093989', 28, 80, 43800, 1, 43800, NULL, 'CUSTOMER', '2025-02-20', '12:23:28', 'muhammadushafa@gmail.com', 'Paid', '0', '', '', '43800', NULL, NULL, NULL, NULL, 42750),
(1280, '250220524083749', 28, 79, 36400, 5, 182000, NULL, 'ABDULMALIK KAFI', '2025-02-20', '12:35:29', 'muhammadushafa@gmail.com', 'Paid', '0', '640000', '', '404500', NULL, NULL, NULL, NULL, 32500),
(1281, '250220524083749', 28, 87, 70000, 3, 210000, NULL, 'ABDULMALIK KAFI', '2025-02-20', '12:36:08', 'muhammadushafa@gmail.com', 'Paid', '0', '640000', '', '404500', NULL, NULL, NULL, NULL, 66000),
(1282, '250220524083749', 28, 80, 43500, 15, 652500, NULL, 'ABDULMALIK KAFI', '2025-02-20', '12:36:49', 'muhammadushafa@gmail.com', 'Paid', '0', '640000', '', '404500', NULL, NULL, NULL, NULL, 42750),
(1283, '250220308926354', 28, 80, 43800, 4, 175200, NULL, 'CUSTOMER', '2025-02-20', '13:08:37', 'muhammadushafa@gmail.com', 'Paid', '0', '175200', '', '', NULL, NULL, NULL, NULL, 42750),
(1284, '250220357500839', 28, 80, 43800, 4, 175200, NULL, 'CUSTOMER', '2025-02-20', '13:24:28', 'muhammadushafa@gmail.com', 'Paid', '0', '', '175200', '', NULL, NULL, NULL, NULL, 42750),
(1287, '250220461886799', 28, 66, 41000, 5, 205000, NULL, 'DR NABARDO', '2025-02-20', '13:29:54', 'muhammadushafa@gmail.com', 'Paid', '0', '', '205000', '', NULL, NULL, NULL, NULL, 39700),
(1288, '250220933370867', 7, 88, 37400, 30, 1122000, NULL, 'DR NABARDO', '2025-02-20', '13:31:53', 'muhammadushafa@gmail.com', 'Paid', '0', '', '', '2214000', NULL, NULL, NULL, NULL, 32500),
(1289, '250220933370867', 7, 86, 36400, 30, 1092000, NULL, 'DR NABARDO', '2025-02-20', '13:32:17', 'muhammadushafa@gmail.com', 'Paid', '0', '', '', '2214000', NULL, NULL, NULL, NULL, 32500),
(1290, '250220738799206', 7, 33, 22000, 50, 1100000, NULL, 'SHAGO', '2025-02-20', '13:34:20', 'muhammadushafa@gmail.com', 'Paid', '0', '', '1100000', '', NULL, NULL, NULL, NULL, 20500),
(1291, '250220508448599', 28, 75, 43000, 10, 430000, NULL, 'MUSTAPHA DASS', '2025-02-20', '14:18:17', 'muhammadushafa@gmail.com', 'Not-Paid', '0', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 42230);

-- --------------------------------------------------------

--
-- Table structure for table `users_tbl`
--

CREATE TABLE `users_tbl` (
  `userID` int NOT NULL,
  `Fullname` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  `Email` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  `UserPassword` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `Department` int NOT NULL,
  `DateRegister` date NOT NULL,
  `TimeRegister` time NOT NULL,
  `Role` varchar(10) COLLATE utf8mb4_general_ci NOT NULL,
  `Status` varchar(20) COLLATE utf8mb4_general_ci NOT NULL DEFAULT 'Active',
  `Phone` varchar(20) COLLATE utf8mb4_general_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users_tbl`
--

INSERT INTO `users_tbl` (`userID`, `Fullname`, `Email`, `UserPassword`, `Department`, `DateRegister`, `TimeRegister`, `Role`, `Status`, `Phone`) VALUES
(11, 'Hamza Ibrahim Danasabe', 'hamxah4u@gmail.com', '$2y$10$VMpuqM04AMeN4BmfmloT0eyR.RJFdedI2BpCJ0v0429h2veXkA98C', 7, '0000-00-00', '00:00:00', 'Admin', 'Active', '08037856962'),
(33, '‎Muhammad Shafa‎', 'muhammadushafa@gmail.com', '$2y$10$DkHQCo5IuEXSjp5Y26CNPu/iIOPYmoSwSUH.jta4g.DbiSc07okCi', 7, '0000-00-00', '00:00:00', 'Admin', 'Active', '08032987462'),
(35, 'Muhammad Nafiu', 'nafiumohammedomar@gmail.com', '$2y$10$tAt20LIGyh.gvk1Zthwu1uIqR9IM9wLcW6m4bj.7x7Vv/mPqu2y5W', 28, '0000-00-00', '00:00:00', 'User', 'Active', '07066646961');

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
-- Indexes for table `transaction_tbl`
--
ALTER TABLE `transaction_tbl`
  ADD PRIMARY KEY (`TID`),
  ADD KEY `Product` (`Product`),
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
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=32;

--
-- AUTO_INCREMENT for table `department_tbl`
--
ALTER TABLE `department_tbl`
  MODIFY `deptID` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=30;

--
-- AUTO_INCREMENT for table `financecollect_tbl`
--
ALTER TABLE `financecollect_tbl`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT for table `product_tbl`
--
ALTER TABLE `product_tbl`
  MODIFY `proID` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=30;

--
-- AUTO_INCREMENT for table `supply_tbl`
--
ALTER TABLE `supply_tbl`
  MODIFY `SupplyID` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=89;

--
-- AUTO_INCREMENT for table `transaction_tbl`
--
ALTER TABLE `transaction_tbl`
  MODIFY `TID` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1292;

--
-- AUTO_INCREMENT for table `users_tbl`
--
ALTER TABLE `users_tbl`
  MODIFY `userID` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=36;

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
  ADD CONSTRAINT `transaction_tbl_ibfk_1` FOREIGN KEY (`tDepartment`) REFERENCES `department_tbl` (`deptID`),
  ADD CONSTRAINT `transaction_tbl_ibfk_2` FOREIGN KEY (`Product`) REFERENCES `supply_tbl` (`SupplyID`);

--
-- Constraints for table `users_tbl`
--
ALTER TABLE `users_tbl`
  ADD CONSTRAINT `users_tbl_ibfk_1` FOREIGN KEY (`Department`) REFERENCES `department_tbl` (`deptID`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
