-- phpMyAdmin SQL Dump
-- version 5.1.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Dec 11, 2022 at 03:11 PM
-- Server version: 10.4.22-MariaDB
-- PHP Version: 8.1.2

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `easylearning`
--

-- --------------------------------------------------------

--
-- Table structure for table `courses`
--

CREATE TABLE `courses` (
  `course_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `course_name` varchar(255) DEFAULT NULL,
  `course_description` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `courses`
--

INSERT INTO `courses` (`course_id`, `user_id`, `course_name`, `course_description`) VALUES
(1, 1, 'course_name', 'course description');

-- --------------------------------------------------------

--
-- Table structure for table `course_content`
--

CREATE TABLE `course_content` (
  `course_id` int(11) NOT NULL,
  `lecture_id` int(11) NOT NULL,
  `location` varchar(255) NOT NULL,
  `public_name` varchar(255) NOT NULL,
  `lesson_description` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `course_content`
--

INSERT INTO `course_content` (`course_id`, `lecture_id`, `location`, `public_name`, `lesson_description`) VALUES
(1, 1, 'kvnsd/asncjsnca/sfcncx', 'newLessonName', 'description');

-- --------------------------------------------------------

--
-- Table structure for table `subscriptions`
--

CREATE TABLE `subscriptions` (
  `progress` int(11) NOT NULL,
  `active` tinyint(1) DEFAULT NULL,
  `user_id` int(11) DEFAULT NULL,
  `course_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `subscriptions`
--

INSERT INTO `subscriptions` (`progress`, `active`, `user_id`, `course_id`) VALUES
(0, 1, 2, 2);

-- --------------------------------------------------------

--
-- Table structure for table `tokens`
--

CREATE TABLE `tokens` (
  `user_id` int(11) NOT NULL,
  `token` varchar(255) NOT NULL,
  `expiration_time` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `tokens`
--

INSERT INTO `tokens` (`user_id`, `token`, `expiration_time`) VALUES
(12, '$2y$10$3.Pbu6Yac.0cJxRbdJkQR.qkonaD9Bz.iEeBWs9qe4f22/RRv4z3W', '2022-11-26'),
(13, '$2y$10$JPRmLDlZMHcw6dYCDwvSYunFtB8B2FYBlkX77j82LxlHZrsppEvUy$2y$10$gzM5IYvgzzUHkUQqZdCJ7usyLqJ7H48sxx4r68bg2kytm34y/wx4S$2y$10$0nBAizkodpq1rBzh4OlyueAwcAn1p3qQ1oA8jEJRxE4P38fWEuXY.', '2022-12-12'),
(14, '', '0000-00-00'),
(15, '', '0000-00-00'),
(16, '', '0000-00-00'),
(17, '', '0000-00-00');

-- --------------------------------------------------------

--
-- Table structure for table `users_data`
--

CREATE TABLE `users_data` (
  `user_id` int(11) NOT NULL,
  `user_name` varchar(30) NOT NULL,
  `first_name` varchar(30) NOT NULL,
  `last_name` varchar(30) NOT NULL,
  `password` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `users_data`
--

INSERT INTO `users_data` (`user_id`, `user_name`, `first_name`, `last_name`, `password`) VALUES
(12, 'vasea123', 'first10', 'last10', '$2y$10$Cp1qOzf5s7agElrHg10y0ea.NgJwPu57Jxwvhyt8fxWhrheEQ5z.a'),
(13, 'lalalal', 'firashvbdkjsst10', 'lassdkhvkjsbdhjaut10', '$2y$10$dbtMkBpUdUVZ1wGu5/rQ6.vobHmLBF1TISmx4HS8WC0uf/912iz3q'),
(14, '1', 'firashvbdkjsst10', 'lassdkhvkjsbdhjaut10', '$2y$10$wqcM9XQmdZq8dxo43GJw6.ZITv4WuVa.OijJ1z1hly5qT6KZKirHW'),
(15, '2', 'firashvbdkjsst10', 'lassdkhvkjsbdhjaut10', '$2y$10$.PO3chdFiAMa1d3kGOuQJeHPp5bs5nAVjIfff1jwW.9LpF5.95HqG'),
(16, '3', 'firashvbdkjsst10', 'lassdkhvkjsbdhjaut10', '$2y$10$HRlFkBhFqpliEJfc0kuwPOxF3BvhaCAP094AfMFu0W.C9hWgejKUG'),
(17, '4', 'firashvbdkjsst10', 'lassdkhvkjsbdhjaut10', '$2y$10$hynxIrhthuMDs/PV8ekWtO/yXSmFsQkRgxmr3w0dizT.xHUdtyCMu');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `courses`
--
ALTER TABLE `courses`
  ADD PRIMARY KEY (`course_id`),
  ADD UNIQUE KEY `user_id` (`user_id`);

--
-- Indexes for table `course_content`
--
ALTER TABLE `course_content`
  ADD PRIMARY KEY (`lecture_id`),
  ADD UNIQUE KEY `course_id` (`course_id`);

--
-- Indexes for table `tokens`
--
ALTER TABLE `tokens`
  ADD UNIQUE KEY `user_id` (`user_id`);

--
-- Indexes for table `users_data`
--
ALTER TABLE `users_data`
  ADD PRIMARY KEY (`user_id`),
  ADD UNIQUE KEY `user_id` (`user_id`),
  ADD UNIQUE KEY `user_name` (`user_name`),
  ADD UNIQUE KEY `user_name_2` (`user_name`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `users_data`
--
ALTER TABLE `users_data`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
