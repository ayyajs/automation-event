-- phpMyAdmin SQL Dump
-- version 4.5.4.1deb2ubuntu2.1
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Jan 20, 2023 at 12:11 PM
-- Server version: 5.7.33-0ubuntu0.16.04.1
-- PHP Version: 7.3.27-9+ubuntu16.04.1+deb.sury.org+1

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `trainees_santhosh_event_management_oops`
--

-- --------------------------------------------------------

--
-- Table structure for table `tbl_event`
--

CREATE TABLE `tbl_event` (
  `event_id` int(11) NOT NULL,
  `event_name` int(11) NOT NULL,
  `event_title` varchar(255) NOT NULL,
  `event_location` int(11) NOT NULL,
  `venue` text NOT NULL,
  `image` varchar(255) NOT NULL,
  `event_date` date NOT NULL,
  `start_time` varchar(255) NOT NULL,
  `end_time` varchar(255) NOT NULL,
  `food` varchar(255) NOT NULL,
  `status` enum('active','inactive') NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tbl_event`
--

INSERT INTO `tbl_event` (`event_id`, `event_name`, `event_title`, `event_location`, `venue`, `image`, `event_date`, `start_time`, `end_time`, `food`, `status`, `created_at`, `updated_at`) VALUES
(1, 2, 'happy birthday', 1, 'Chennai', '1674196284_ring-2.jpeg', '2023-01-28', '07.00 PM', '08.00 PM', 'south indian', 'active', '2023-01-20 12:01:01', '2023-01-20 12:01:01');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_event_type`
--

CREATE TABLE `tbl_event_type` (
  `id` int(11) NOT NULL,
  `event_name` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `status` enum('active','inactive') NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tbl_event_type`
--

INSERT INTO `tbl_event_type` (`id`, `event_name`, `description`, `status`, `created_at`, `updated_at`) VALUES
(1, 'Marriage', 'Marriage', 'inactive', '2023-01-20 11:59:50', '2023-01-20 11:59:50'),
(2, 'Birthday', 'Birthday', 'active', '2023-01-20 12:00:07', '2023-01-20 12:00:07');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_location`
--

CREATE TABLE `tbl_location` (
  `location_id` int(11) NOT NULL,
  `location` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tbl_location`
--

INSERT INTO `tbl_location` (`location_id`, `location`) VALUES
(1, 'Chennai'),
(2, 'Madurai'),
(3, 'Bengaluru'),
(4, 'Thanchavoor'),
(5, 'Trichy'),
(6, 'Coimbatore'),
(7, 'Thirunelveli'),
(8, 'Kochin'),
(9, 'Theni'),
(10, 'Thiruvanamali');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `tbl_event`
--
ALTER TABLE `tbl_event`
  ADD PRIMARY KEY (`event_id`);

--
-- Indexes for table `tbl_event_type`
--
ALTER TABLE `tbl_event_type`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tbl_location`
--
ALTER TABLE `tbl_location`
  ADD PRIMARY KEY (`location_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `tbl_event`
--
ALTER TABLE `tbl_event`
  MODIFY `event_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT for table `tbl_event_type`
--
ALTER TABLE `tbl_event_type`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT for table `tbl_location`
--
ALTER TABLE `tbl_location`
  MODIFY `location_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
