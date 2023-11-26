-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Aug 20, 2023 at 01:39 PM
-- Server version: 10.4.24-MariaDB
-- PHP Version: 8.1.6

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `paganadb`
--

-- --------------------------------------------------------

--
-- Table structure for table `about_us`
--

CREATE TABLE `about_us` (
  `id` int(11) NOT NULL,
  `description` text NOT NULL,
  `type` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `about_us`
--

INSERT INTO `about_us` (`id`, `description`, `type`) VALUES
(1, 'Aadasdsadsadaadasdsasdfsdfdsfsdfdsssdfsdfds', 'Mission');

-- --------------------------------------------------------

--
-- Table structure for table `amenities`
--

CREATE TABLE `amenities` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `rate` double NOT NULL,
  `quantity` int(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `amenities`
--

INSERT INTO `amenities` (`id`, `name`, `rate`, `quantity`) VALUES
(1, 'Pillow', 15, 20),
(2, 'Blanket', 10, 25);

-- --------------------------------------------------------

--
-- Table structure for table `amenities_reservation`
--

CREATE TABLE `amenities_reservation` (
  `id` int(11) NOT NULL,
  `reservation_id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `quantity` int(20) NOT NULL,
  `checkin` date NOT NULL,
  `checkout` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `amenities_reservation`
--

INSERT INTO `amenities_reservation` (`id`, `reservation_id`, `name`, `quantity`, `checkin`, `checkout`) VALUES
(12, 33, 'Pillow', 15, '2023-08-05', '2023-08-06'),
(13, 33, 'Blanket', 15, '2023-08-05', '2023-08-06'),
(14, 38, 'Pillow', 0, '2023-07-18', '2023-07-19'),
(15, 38, 'Blanket', 0, '2023-07-18', '2023-07-19');

-- --------------------------------------------------------

--
-- Table structure for table `inquiries`
--

CREATE TABLE `inquiries` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `subject` varchar(255) NOT NULL,
  `message` text NOT NULL,
  `status` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `inquiries`
--

INSERT INTO `inquiries` (`id`, `name`, `email`, `subject`, `message`, `status`) VALUES
(1, 'Abra K Zam', 'lukasaong14@gmail.com', 'SSS', 'adasdsadsad\r\n', 0),
(2, 'joserizal', 'abra@gmail.com', 'VVVV', 'zlxcvcxvcxvcxvcxvxcvxc', 0);

-- --------------------------------------------------------

--
-- Table structure for table `reservation`
--

CREATE TABLE `reservation` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `room_id` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `phone` varchar(255) NOT NULL,
  `address` text NOT NULL,
  `checkin` date NOT NULL,
  `checkout` date NOT NULL,
  `amount_paid` double NOT NULL,
  `total_rate` double NOT NULL,
  `transaction_id` varchar(255) NOT NULL,
  `status` varchar(255) NOT NULL,
  `datecreated` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `reservation`
--

INSERT INTO `reservation` (`id`, `user_id`, `room_id`, `name`, `email`, `phone`, `address`, `checkin`, `checkout`, `amount_paid`, `total_rate`, `transaction_id`, `status`, `datecreated`) VALUES
(13, 0, 'Room 1', 'AA', 'aaa@aaa.com', '09558420699', 'lukaasd', '2023-07-12', '2023-07-15', 20.2, 20.2, '714641367', 'Accepted', '2024-07-08 19:50:20'),
(14, 0, 'Room 2', 'aa', 'kkkk@a.com', '09558420699', 'Loollll', '2023-07-11', '2023-07-13', 40.4, 40.4, '966458167', 'Accepted', '2023-08-08 19:50:20'),
(15, 0, 'Room 1', 'aaaa', 'aaa@aa.com', '09558420699', 'dcsdfsdf', '2023-07-07', '2023-07-10', 60.6, 60.6, '214934813', 'Accepted', '2023-07-15 19:50:20'),
(34, 0, 'Room 2', 'Abra K. Zam', 'lukasaong16@gmail.com', '09666666666', 'Jose Lim Street', '2023-07-09', '2023-07-10', 20.2, 20.2, '432906424', 'Accepted', '2023-07-08 19:50:20'),
(35, 0, 'Room 2', 'admin', 'lukasaong14@gmail.com', '09666666666', 'Jose Lim Street', '2023-07-20', '2023-07-22', 40.4, 40.4, '679259491', 'Accepted', '2023-07-11 09:18:21'),
(36, 25, 'Room 1', 'Abra K. Zam', 'lukasaong16@gmail.com', '09666666666', 'Jose Lim Street', '2023-07-20', '2023-07-21', 20.2, 20.2, '106740', 'Accepted', '2023-07-12 09:10:11'),
(37, 0, 'Room 2', 'Yoshi Odom', 'lukasaong16@gmail.com', '09666666666', 'Veniam reprehenderi', '0000-00-00', '0000-00-00', 0, 45.2, '126891847', 'Rejected', '2023-07-14 08:26:00'),
(38, 25, 'Room 1', 'admin', 'lukasaong16@gmail.com', '09666666666', 'Jose Lim Street', '0000-00-00', '0000-00-00', 45.2, 45.2, '419874343', 'Rejected', '2023-07-17 20:00:10');

-- --------------------------------------------------------

--
-- Table structure for table `room`
--

CREATE TABLE `room` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `type` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `rate` double NOT NULL,
  `status` int(11) NOT NULL,
  `image` text NOT NULL,
  `images` mediumtext NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `room`
--

INSERT INTO `room` (`id`, `name`, `type`, `description`, `rate`, `status`, `image`, `images`) VALUES
(1, 'Room 2', 'Single', 'Cool Sya', 20.2, 0, 'room2.jpg', ''),
(2, 'Room 1', 'Single', 'Maganda ka Nga', 20.2, 0, 'room1.jpg', ''),
(8, 'Roomd', 'Single', 'rrrrr', 20, 0, 'room2.jpg', '366380860_304176038800067_3893972397658701756_n.jpg, download (5).jpg, room2.jpg');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `fname` varchar(255) NOT NULL,
  `mname` varchar(255) NOT NULL,
  `lname` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `phone` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `status` int(11) NOT NULL,
  `verification_token` varchar(255) NOT NULL,
  `user_level` varchar(255) NOT NULL,
  `image` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `fname`, `mname`, `lname`, `email`, `phone`, `password`, `status`, `verification_token`, `user_level`, `image`) VALUES
(1, 'Admin', 'Admin', 'Admin', 'admin@gmail.com', '', '$2y$10$xS.QHsScbqKjEnfQAQvDbuHRo5GMlfhwdwY.qZns2iz/P2LnLws/i', 1, '0', 'Administrator', ''),
(23, 'Joh', 'I', 'Bangs', 'lukasaong13@gmail.com', '', '$2y$10$ZnV/sR0.CQcufrooG8EKRusilsH6Sn9dlas9ItKPxwxTYSx3OoBNu', 1, '31c399dbfc223396062667016ca47ccb7f22d75adbd41e18dc1995b1aeb9315f', 'Guest', ''),
(25, 'Joh', 'I', 'Bangs', 'lukasaong16@gmail.com', '', '$2y$10$xS.QHsScbqKjEnfQAQvDbuHRo5GMlfhwdwY.qZns2iz/P2LnLws/i', 1, 'a02d9d5ea2a9645232f32141d1051315610052f82ab2e358fd428c91ac728192', 'Guest', ''),
(26, 'Rindo', 'A', 'LFF', 'lukasaong14@gmail.com', '9666666666', '$2y$10$Ll5lg7PYg6S2bJuIdznX7e0ntzWxhsGn58aRxWr89ey/vX9tBKXuS', 0, 'a1e41497b2030ab2f7f0d20aa5e84106a8c5734242ee070b5157676261446771', 'Guest', '');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `about_us`
--
ALTER TABLE `about_us`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `amenities`
--
ALTER TABLE `amenities`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `amenities_reservation`
--
ALTER TABLE `amenities_reservation`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `inquiries`
--
ALTER TABLE `inquiries`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `reservation`
--
ALTER TABLE `reservation`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `room`
--
ALTER TABLE `room`
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
-- AUTO_INCREMENT for table `about_us`
--
ALTER TABLE `about_us`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `amenities`
--
ALTER TABLE `amenities`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `amenities_reservation`
--
ALTER TABLE `amenities_reservation`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `inquiries`
--
ALTER TABLE `inquiries`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `reservation`
--
ALTER TABLE `reservation`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=39;

--
-- AUTO_INCREMENT for table `room`
--
ALTER TABLE `room`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=27;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
