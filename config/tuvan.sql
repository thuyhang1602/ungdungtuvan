-- phpMyAdmin SQL Dump
-- version 5.0.3
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Apr 03, 2021 at 05:38 PM
-- Server version: 10.4.14-MariaDB
-- PHP Version: 7.2.34

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `tuvan`
--

-- --------------------------------------------------------

--
-- Table structure for table `comments`
--

CREATE TABLE `comments` (
  `id` int(11) NOT NULL,
  `content` text DEFAULT NULL,
  `user_id` int(255) DEFAULT NULL,
  `post_id` int(11) NOT NULL,
  `parent_comment_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `messages`
--

CREATE TABLE `messages` (
  `msg_id` int(11) NOT NULL,
  `incoming_msg_id` int(255) NOT NULL,
  `outgoing_msg_id` int(255) NOT NULL,
  `msg` varchar(1000) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `posts`
--

CREATE TABLE `posts` (
  `id` int(11) NOT NULL,
  `title` varchar(50) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `unique_id` int(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `posts`
--

INSERT INTO `posts` (`id`, `title`, `description`, `unique_id`, `created_at`, `updated_at`) VALUES
(13, 'Thông báo', 'abc', 960922130, '2021-04-01 16:32:11', '2021-04-01 23:32:11'),
(14, 'Thông báo 2', 'cde', 960922130, '2021-04-01 16:41:39', '2021-04-01 23:41:39');

-- --------------------------------------------------------

--
-- Table structure for table `score`
--

CREATE TABLE `score` (
  `id` int(11) NOT NULL,
  `semester` varchar(3) DEFAULT NULL,
  `subject_id` int(255) DEFAULT NULL,
  `subject_name` varchar(255) DEFAULT NULL,
  `credits` int(2) DEFAULT NULL,
  `medium_score` int(5) DEFAULT NULL,
  `user_id` int(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `score`
--

INSERT INTO `score` (`id`, `semester`, `subject_id`, `subject_name`, `credits`, `medium_score`, `user_id`) VALUES
(1, 'HK1', 1, 'Tiếng Anh 2', 3, 8, 960922130),
(2, 'HK1', 2, 'Hệ thống thông tin quản lý', 3, 10, 960922130),
(3, 'HK1', 3, 'Quản lý nguồn cung cấp', 3, 7, 960922130),
(4, 'HK2', 4, 'Nguyên lý quản trị doanh nghiệp', 4, 8, 960922130),
(5, 'HK2', 5, 'Thương mại điện tử', 3, 6, 960922130),
(6, 'HK2', 6, 'Phát triển ứng dụng', 2, 7, 960922130);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `user_id` int(11) NOT NULL,
  `unique_id` int(255) NOT NULL,
  `firstname` varchar(255) NOT NULL,
  `lastname` varchar(255) NOT NULL,
  `position` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `img` varchar(255) NOT NULL,
  `status` varchar(255) NOT NULL,
  `major` varchar(255) DEFAULT NULL,
  `school` varchar(255) DEFAULT NULL,
  `sex` varchar(5) DEFAULT NULL,
  `auth` varchar(10) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `school_year` int(1) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`user_id`, `unique_id`, `firstname`, `lastname`, `position`, `email`, `password`, `img`, `status`, `major`, `school`, `sex`, `auth`, `created_at`, `updated_at`, `school_year`) VALUES
(30, 960922130, 'Quỳnh', 'Liêu', 'student', 'hyquynh123@gmail.com', '$2y$10$FB4DJ7e1WX/lpuF2NJ4.8O2YrHVzcQCZtCChGb9fjo3N.UIc/Vc6K', '1617294544quynh.jpg', 'Offline now', 'CNTT', 'KHTN', 'femal', 'verify', '2021-04-01 16:29:04', '2021-04-03 21:03:11', NULL),
(31, 301642398, 'Hằng', 'Nguyễn', 'student', 'luannh@magenest.com', '$2y$10$vTKn.Pm9IFKYjnmuwYGBi.isC7yOyVzdYpL2weES9Z.1UPV18tyf6', '1617444934hang.jpg', 'Offline now', 'ATTT', 'KHTN', 'male', 'verify', '2021-04-03 10:15:34', '2021-04-03 21:06:16', 4),
(32, 838684602, 'Luân', 'Nguyễn', 'teacher', 'nguyenhuuluan17@gmail.com', '$2y$10$emWiDBRFq/K0ME9eblbKlOw/YZ/7FRM1XEPdyb33Q.1B0nqgriNT.', '1617459350luan.jpg', 'Offline now', 'Lập trình web', 'KMA', 'male', 'verify', '2021-04-03 14:15:50', '2021-04-03 21:16:09', 0);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `comments`
--
ALTER TABLE `comments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `FK_CommentPost` (`post_id`);

--
-- Indexes for table `messages`
--
ALTER TABLE `messages`
  ADD PRIMARY KEY (`msg_id`);

--
-- Indexes for table `posts`
--
ALTER TABLE `posts`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `score`
--
ALTER TABLE `score`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`user_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `comments`
--
ALTER TABLE `comments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=58;

--
-- AUTO_INCREMENT for table `messages`
--
ALTER TABLE `messages`
  MODIFY `msg_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=46;

--
-- AUTO_INCREMENT for table `posts`
--
ALTER TABLE `posts`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `score`
--
ALTER TABLE `score`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=33;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `comments`
--
ALTER TABLE `comments`
  ADD CONSTRAINT `FK_CommentPost` FOREIGN KEY (`post_id`) REFERENCES `posts` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
