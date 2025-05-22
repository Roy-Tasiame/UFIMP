-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: May 11, 2025 at 12:36 PM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.1.25

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `ashform`
--

-- --------------------------------------------------------

--
-- Table structure for table `field_options`
--

CREATE TABLE `field_options` (
  `id` int(11) NOT NULL,
  `field_id` int(11) NOT NULL,
  `option_text` varchar(255) NOT NULL,
  `order_position` int(11) NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `field_options`
--

INSERT INTO `field_options` (`id`, `field_id`, `option_text`, `order_position`, `created_at`, `updated_at`) VALUES
(1, 3, 'New', 0, '2025-02-23 15:25:31', '2025-02-23 15:25:31'),
(2, 3, 'New 2', 1, '2025-02-23 15:25:31', '2025-02-23 15:25:31'),
(3, 5, 'True', 0, '2025-02-23 15:48:37', '2025-02-23 15:48:37'),
(4, 5, 'False', 1, '2025-02-23 15:48:37', '2025-02-23 15:48:37'),
(5, 6, 'True', 0, '2025-02-23 15:51:21', '2025-02-23 15:51:21'),
(6, 14, 'Accra', 0, '2025-03-16 14:19:55', '2025-03-16 14:19:55'),
(7, 14, 'Tema', 1, '2025-03-16 14:19:55', '2025-03-16 14:19:55'),
(8, 14, 'Anim', 2, '2025-03-16 14:19:55', '2025-03-16 14:19:55'),
(9, 20, 'Jack', 0, '2025-03-18 12:46:38', '2025-03-18 12:46:38'),
(10, 20, 'James', 1, '2025-03-18 12:46:38', '2025-03-18 12:46:38'),
(11, 20, 'John', 2, '2025-03-18 12:46:38', '2025-03-18 12:46:38'),
(12, 21, 'Another', 0, '2025-03-18 12:46:38', '2025-03-18 12:46:38'),
(13, 21, 'Jack', 1, '2025-03-18 12:46:38', '2025-03-18 12:46:38'),
(14, 21, 'Yohane', 2, '2025-03-18 12:46:38', '2025-03-18 12:46:38'),
(15, 21, 'Roy', 3, '2025-03-18 12:46:38', '2025-03-18 12:46:38'),
(16, 26, 'A', 0, '2025-03-21 12:20:07', '2025-03-21 12:20:07'),
(17, 26, 'B', 1, '2025-03-21 12:20:07', '2025-03-21 12:20:07'),
(18, 26, 'C', 2, '2025-03-21 12:20:07', '2025-03-21 12:20:07'),
(19, 26, 'D', 3, '2025-03-21 12:20:07', '2025-03-21 12:20:07'),
(20, 29, '1', 0, '2025-03-21 12:20:07', '2025-03-21 12:20:07'),
(21, 29, '2', 1, '2025-03-21 12:20:07', '2025-03-21 12:20:07'),
(22, 30, 'Jack', 0, '2025-03-21 12:20:07', '2025-03-21 12:20:07'),
(23, 30, 'Jim', 1, '2025-03-21 12:20:07', '2025-03-21 12:20:07'),
(24, 30, 'john', 2, '2025-03-21 12:20:07', '2025-03-21 12:20:07'),
(25, 35, 'Jack', 0, '2025-05-07 08:04:20', '2025-05-07 08:04:20'),
(26, 35, 'Ma', 1, '2025-05-07 08:04:20', '2025-05-07 08:04:20'),
(27, 40, 'MIS', 0, '2025-05-08 05:05:33', '2025-05-08 05:05:33'),
(28, 40, 'CS', 1, '2025-05-08 05:05:33', '2025-05-08 05:05:33'),
(29, 40, 'Engineering', 2, '2025-05-08 05:05:33', '2025-05-08 05:05:33'),
(30, 40, 'ECONS', 3, '2025-05-08 05:05:33', '2025-05-08 05:05:33'),
(31, 42, 'CSIS', 0, '2025-05-08 05:05:33', '2025-05-08 05:05:33'),
(32, 42, 'BA/ECONS', 1, '2025-05-08 05:05:33', '2025-05-08 05:05:33'),
(33, 42, 'Humanities and Social Sciences', 2, '2025-05-08 05:05:33', '2025-05-08 05:05:33'),
(34, 44, 'MIS', 0, '2025-05-08 09:09:40', '2025-05-08 09:09:40'),
(35, 44, 'CS', 1, '2025-05-08 09:09:40', '2025-05-08 09:09:40'),
(36, 44, 'Engineering', 2, '2025-05-08 09:09:40', '2025-05-08 09:09:40');

-- --------------------------------------------------------

--
-- Table structure for table `forms`
--

CREATE TABLE `forms` (
  `id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  `user_id` int(11) NOT NULL,
  `due_date` datetime DEFAULT NULL,
  `description` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `forms`
--

INSERT INTO `forms` (`id`, `title`, `created_at`, `updated_at`, `user_id`, `due_date`, `description`) VALUES
(1, 'Jack', '2025-02-23 14:53:00', '2025-02-23 14:53:00', 3, '2025-05-25 14:53:00', NULL),
(2, 'New Form', '2025-02-23 15:25:31', '2025-02-23 15:25:31', 3, '2025-05-29 14:53:00', NULL),
(8, 'Today\'s form', '2025-03-16 10:31:59', '2025-03-16 10:31:59', 3, '2025-05-28 14:53:00', NULL),
(9, 'Additional Form', '2025-03-16 14:19:55', '2025-03-16 14:19:55', 3, '2025-06-05 14:53:00', NULL),
(11, 'Another try', '2025-03-18 12:46:38', '2025-03-18 12:46:38', 3, '2025-06-22 14:53:00', NULL),
(12, 'New form Creation', '2025-03-21 12:20:07', '2025-03-21 12:20:07', 3, '2025-06-20 14:53:00', NULL),
(15, 'Another Form', '2025-05-07 08:04:20', '2025-05-07 08:04:20', 3, '2025-06-20 14:53:00', NULL),
(16, 'Water Conservation Form', '2025-05-08 05:05:33', '2025-05-08 05:05:33', 3, '2025-05-08 05:03:00', NULL),
(17, 'Roy\'s Form', '2025-05-08 09:09:40', '2025-05-08 09:09:40', 3, '2025-05-09 09:08:00', NULL),
(18, 'Commencement Survey', '2025-05-09 04:41:30', '2025-05-09 04:41:30', 9, '2025-05-09 04:41:00', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `form_fields`
--

CREATE TABLE `form_fields` (
  `id` int(11) NOT NULL,
  `form_id` int(11) NOT NULL,
  `label` varchar(255) NOT NULL,
  `type` varchar(50) NOT NULL,
  `required` tinyint(1) DEFAULT 0,
  `order_position` int(11) NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `form_fields`
--

INSERT INTO `form_fields` (`id`, `form_id`, `label`, `type`, `required`, `order_position`, `created_at`, `updated_at`) VALUES
(1, 1, 'New', 'text', 0, 0, '2025-02-23 14:53:00', '2025-02-23 14:53:00'),
(2, 2, 'Question 1', 'text', 0, 0, '2025-02-23 15:25:31', '2025-02-23 15:25:31'),
(3, 2, 'Question 2', 'checkbox', 0, 1, '2025-02-23 15:25:31', '2025-02-23 15:25:31'),
(9, 8, 'Question 1', 'text', 0, 0, '2025-03-16 10:31:59', '2025-03-16 10:31:59'),
(10, 8, 'Question 2', 'textarea', 0, 1, '2025-03-16 10:31:59', '2025-03-16 10:31:59'),
(11, 8, 'Question 3', 'number', 0, 2, '2025-03-16 10:31:59', '2025-03-16 10:31:59'),
(12, 9, 'What is your name?', 'text', 0, 0, '2025-03-16 14:19:55', '2025-03-16 14:19:55'),
(13, 9, 'What is your age', 'number', 0, 1, '2025-03-16 14:19:55', '2025-03-16 14:19:55'),
(14, 9, 'Location?', 'checkbox', 0, 2, '2025-03-16 14:19:55', '2025-03-16 14:19:55'),
(15, 9, 'Environment', 'textarea', 0, 3, '2025-03-16 14:19:55', '2025-03-16 14:19:55'),
(16, 11, 'Question 1', 'text', 1, 0, '2025-03-18 12:46:38', '2025-03-18 12:46:38'),
(17, 11, 'Question 2', 'textarea', 0, 1, '2025-03-18 12:46:38', '2025-03-18 12:46:38'),
(18, 11, 'Question 3', 'number', 0, 2, '2025-03-18 12:46:38', '2025-03-18 12:46:38'),
(19, 11, 'Question 4', 'date', 0, 3, '2025-03-18 12:46:38', '2025-03-18 12:46:38'),
(20, 11, 'Question 5', 'radio', 0, 4, '2025-03-18 12:46:38', '2025-03-18 12:46:38'),
(21, 11, 'Question 6', 'checkbox', 0, 5, '2025-03-18 12:46:38', '2025-03-18 12:46:38'),
(22, 11, 'Question 7', 'file', 0, 6, '2025-03-18 12:46:38', '2025-03-18 12:46:38'),
(23, 11, 'Question 8', 'rating', 0, 7, '2025-03-18 12:46:38', '2025-03-18 12:46:38'),
(24, 12, 'New Question', 'text', 0, 0, '2025-03-21 12:20:07', '2025-03-21 12:20:07'),
(25, 12, 'ALread Question', 'textarea', 0, 1, '2025-03-21 12:20:07', '2025-03-21 12:20:07'),
(26, 12, 'Multile choice', 'checkbox', 0, 2, '2025-03-21 12:20:07', '2025-03-21 12:20:07'),
(27, 12, 'Question 4', 'number', 0, 3, '2025-03-21 12:20:07', '2025-03-21 12:20:07'),
(28, 12, 'Question 5', 'date', 0, 4, '2025-03-21 12:20:07', '2025-03-21 12:20:07'),
(29, 12, 'Question 6', 'radio', 0, 5, '2025-03-21 12:20:07', '2025-03-21 12:20:07'),
(30, 12, 'Question 7', 'select', 0, 6, '2025-03-21 12:20:07', '2025-03-21 12:20:07'),
(31, 13, '', 'text', 0, 0, '2025-04-06 19:48:34', '2025-04-06 19:48:34'),
(32, 13, '', 'text', 0, 1, '2025-04-06 19:48:34', '2025-04-06 19:48:34'),
(33, 14, 'Question', 'text', 0, 0, '2025-05-06 13:00:23', '2025-05-06 13:00:23'),
(34, 15, 'Question 1', 'text', 0, 0, '2025-05-07 08:04:20', '2025-05-07 08:04:20'),
(35, 15, 'Question 2', 'checkbox', 0, 1, '2025-05-07 08:04:20', '2025-05-07 08:04:20'),
(36, 16, 'Age?', 'number', 0, 0, '2025-05-08 05:05:33', '2025-05-08 05:05:33'),
(37, 16, 'Height?', 'text', 0, 1, '2025-05-08 05:05:33', '2025-05-08 05:05:33'),
(38, 16, 'Gender', 'textarea', 0, 2, '2025-05-08 05:05:33', '2025-05-08 05:05:33'),
(39, 16, 'Course', 'date', 0, 3, '2025-05-08 05:05:33', '2025-05-08 05:05:33'),
(40, 16, 'Program?', 'checkbox', 0, 4, '2025-05-08 05:05:33', '2025-05-08 05:05:33'),
(41, 16, 'Office?', 'file', 0, 5, '2025-05-08 05:05:33', '2025-05-08 05:05:33'),
(42, 16, 'Department?', 'radio', 0, 6, '2025-05-08 05:05:33', '2025-05-08 05:05:33'),
(43, 17, 'What department are you in?', 'text', 0, 0, '2025-05-08 09:09:40', '2025-05-08 09:09:40'),
(44, 17, 'Program?', 'checkbox', 0, 1, '2025-05-08 09:09:40', '2025-05-08 09:09:40'),
(45, 18, 'Here it ', 'text', 0, 0, '2025-05-09 04:41:30', '2025-05-09 04:41:30'),
(46, 18, 'Yes!!!', 'textarea', 0, 1, '2025-05-09 04:41:30', '2025-05-09 04:41:30');

-- --------------------------------------------------------

--
-- Table structure for table `form_responses`
--

CREATE TABLE `form_responses` (
  `id` int(11) NOT NULL,
  `form_id` int(11) NOT NULL,
  `created_at` datetime NOT NULL,
  `completed_at` datetime DEFAULT NULL,
  `user_id` int(11) DEFAULT NULL,
  `is_complete` tinyint(1) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `form_responses`
--

INSERT INTO `form_responses` (`id`, `form_id`, `created_at`, `completed_at`, `user_id`, `is_complete`) VALUES
(1, 8, '2025-03-16 10:34:36', NULL, 4, 0),
(2, 9, '2025-03-16 14:34:24', NULL, 4, 0),
(3, 11, '2025-03-18 12:47:53', NULL, NULL, 0),
(4, 12, '2025-03-21 12:20:53', NULL, NULL, 0),
(5, 1, '2025-05-08 04:43:15', NULL, 2, 1),
(6, 1, '2025-05-08 04:43:15', NULL, 2, 1),
(7, 1, '2025-05-08 04:43:15', NULL, 2, 1),
(8, 1, '2025-05-08 04:43:15', NULL, 2, 1),
(9, 17, '2025-05-08 09:11:54', NULL, 2, 1),
(10, 17, '2025-05-08 11:32:22', NULL, NULL, 0),
(11, 17, '2025-05-08 11:33:12', NULL, NULL, 0),
(12, 2, '2025-05-11 05:26:54', NULL, 12, 1),
(13, 9, '2025-05-11 05:39:49', NULL, 12, 1),
(14, 17, '2025-05-11 06:00:31', NULL, 12, 0);

-- --------------------------------------------------------

--
-- Table structure for table `form_views`
--

CREATE TABLE `form_views` (
  `id` int(11) NOT NULL,
  `form_id` int(11) NOT NULL,
  `viewed_at` datetime NOT NULL DEFAULT current_timestamp(),
  `ip_address` varchar(45) DEFAULT NULL,
  `user_agent` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `response_values`
--

CREATE TABLE `response_values` (
  `id` int(11) NOT NULL,
  `response_id` int(11) NOT NULL,
  `field_id` int(11) NOT NULL,
  `value` text NOT NULL,
  `created_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `response_values`
--

INSERT INTO `response_values` (`id`, `response_id`, `field_id`, `value`, `created_at`) VALUES
(1, 1, 9, 'jack', '2025-03-16 10:34:36'),
(2, 1, 10, 'jack', '2025-03-16 10:34:36'),
(3, 2, 12, '', '2025-03-16 14:34:24'),
(4, 2, 13, '-1', '2025-03-16 14:34:24'),
(5, 2, 14, 'Accra, Tema', '2025-03-16 14:34:24'),
(6, 3, 16, 'Non est nulla laboru', '2025-03-18 12:47:53'),
(7, 3, 17, 'Quo totam nostrud co', '2025-03-18 12:47:53'),
(8, 3, 18, '42', '2025-03-18 12:47:53'),
(9, 3, 19, '', '2025-03-18 12:47:53'),
(10, 3, 20, 'Jack', '2025-03-18 12:47:53'),
(11, 3, 21, 'Another, Jack', '2025-03-18 12:47:53'),
(12, 4, 24, '', '2025-03-21 12:20:53'),
(13, 4, 25, '', '2025-03-21 12:20:53'),
(14, 4, 27, '-3', '2025-03-21 12:20:53'),
(15, 4, 28, '', '2025-03-21 12:20:53'),
(16, 4, 29, '1', '2025-03-21 12:20:53'),
(17, 5, 1, '', '2025-05-08 04:43:15'),
(18, 6, 1, '', '2025-05-08 04:43:15'),
(19, 7, 1, '', '2025-05-08 04:43:15'),
(20, 8, 1, '', '2025-05-08 04:43:15'),
(21, 9, 43, 'CSIS', '2025-05-08 09:11:54'),
(22, 9, 44, '[\"MIS\"]', '2025-05-08 09:11:54'),
(23, 10, 43, '', '2025-05-08 11:32:22'),
(24, 10, 44, 'MIS, CS, Engineering', '2025-05-08 11:32:22'),
(25, 11, 43, '', '2025-05-08 11:33:12'),
(26, 11, 44, 'MIS', '2025-05-08 11:33:12'),
(29, 13, 12, 'Final Test', '2025-05-11 05:39:49'),
(30, 13, 14, '[\"Accra\"]', '2025-05-11 05:39:49'),
(31, 13, 15, 'Yeah', '2025-05-11 05:39:49'),
(32, 12, 2, 'Yeah', '2025-05-11 05:40:06'),
(33, 12, 3, '[\"New\"]', '2025-05-11 05:40:06'),
(34, 14, 43, 'g', '2025-05-11 06:00:31');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `user_id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password_hash` varchar(255) NOT NULL,
  `role` enum('admin','user','student','department') DEFAULT 'user',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `major` varchar(255) DEFAULT NULL,
  `phone_number` varchar(20) DEFAULT NULL,
  `student_id` varchar(50) DEFAULT NULL,
  `class_year` int(4) DEFAULT NULL,
  `academic_department` varchar(255) DEFAULT NULL,
  `profile_updated` tinyint(1) DEFAULT 0,
  `first_name` varchar(255) DEFAULT NULL,
  `last_name` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`user_id`, `name`, `email`, `password_hash`, `role`, `created_at`, `major`, `phone_number`, `student_id`, `class_year`, `academic_department`, `profile_updated`, `first_name`, `last_name`) VALUES
(1, 'John Doe', 'john.doe@example.com', '$2y$10$Rs6VtrShZkT3d28gDwobReMZ.kpC8EyKHw1fpu.Vpwidhc7LtinAm', 'student', '2025-02-22 18:52:59', 'Computer Science', '1234567890', 'S1234567', 2025, '0', 1, 'John', 'Doe'),
(2, 'Roy Nikita', 'roy.tasiame@ashesi.edu.gh', '$2y$10$IZ2e0BSjQPemxiOaCpfN2eZE6CMPhEEd.f1wipOwDvs.UW7AKoPsm', 'student', '2025-02-22 18:53:36', 'MIS', '233 234567890', '12342021', 2021, NULL, 1, 'Roy', 'Nikita'),
(3, 'admin', 'admin@ashesi.edu.gh', '$2y$10$OuXLCKtjtlcYLWsblBQ9eO9g/fUHO4bKof3ENYnXWi1P7yvg9zPFm', 'admin', '2025-02-22 18:55:58', NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL),
(4, 'newuser', 'newuser@ashesi.edu.gh', '$2y$10$yyPX0nYtQjLDiGQr3nQ.JOZrxZDG86xgXZ1WCDaDArQUbuUK/ZlbO', 'student', '2025-02-23 15:57:08', NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL),
(5, 'New Student', 'student@ashesi.edu.gh', '$2y$10$q9vwz8g9dHK/m19F13Ek3uUMzF7fDlBAL7ExXk1SUpYGCAD9qx.eO', 'student', '2025-03-20 12:16:38', NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL),
(6, 'Create', 'create@gmail.com', '$2y$10$be/TMGGo5OFK7ad/il5BgOKqBWgwzvBxDJidzaLwSt3KVr0OLvlXW', 'student', '2025-04-17 11:37:21', NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL),
(8, 'an', 'an@gmail.com', '$2y$10$Ztc4n8rjg30vVsYMRjnXc.PZId31vYZVRPe1Q3sRIfvNdkUcOtolm', 'student', '2025-04-17 11:40:20', NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL),
(9, 'SLE', 'sle@ashesi.edu.gh', '$2y$10$lV9C6oxCWJ1QESMd.CSCcuWKeYugWIVR/rYc3oQE7tb49q7DMqOAi', 'department', '2025-05-06 21:52:48', NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL),
(10, 'ODIP', 'odip@ashesi.edu.gh', '$2y$10$dMFIaK/5AAM2/fsARCBi0.bLThzE47/ybD0qm33MthtoMCROXovxK', 'department', '2025-05-06 22:09:18', NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL),
(11, 'Test Student', 'test.student@ashesi.edu.gh', '$2y$10$5A5MFSva0bYDumk35CEm/.GpsBUtTdSL0ZFuiYdAI4ni6frw/8jzC', 'student', '2025-05-09 04:03:50', NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL),
(12, '1 1', 'final.test@ashesi.edu.gh', '$2y$10$N0eF/eNF/lC1SL2X1RaE7OhrNoHadQ6IEIKSRrSqNDWxkDITv1e/m', 'student', '2025-05-11 05:24:18', '1', '1', '1', 1, NULL, 1, '1', '1');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `field_options`
--
ALTER TABLE `field_options`
  ADD PRIMARY KEY (`id`),
  ADD KEY `field_id` (`field_id`);

--
-- Indexes for table `forms`
--
ALTER TABLE `forms`
  ADD PRIMARY KEY (`id`),
  ADD KEY `forms_user_fk` (`user_id`);

--
-- Indexes for table `form_fields`
--
ALTER TABLE `form_fields`
  ADD PRIMARY KEY (`id`),
  ADD KEY `form_id` (`form_id`);

--
-- Indexes for table `form_responses`
--
ALTER TABLE `form_responses`
  ADD PRIMARY KEY (`id`),
  ADD KEY `form_id` (`form_id`),
  ADD KEY `form_responses_user_fk` (`user_id`);

--
-- Indexes for table `form_views`
--
ALTER TABLE `form_views`
  ADD PRIMARY KEY (`id`),
  ADD KEY `form_id` (`form_id`);

--
-- Indexes for table `response_values`
--
ALTER TABLE `response_values`
  ADD PRIMARY KEY (`id`),
  ADD KEY `response_id` (`response_id`),
  ADD KEY `field_id` (`field_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`user_id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `field_options`
--
ALTER TABLE `field_options`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=37;

--
-- AUTO_INCREMENT for table `forms`
--
ALTER TABLE `forms`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT for table `form_fields`
--
ALTER TABLE `form_fields`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=47;

--
-- AUTO_INCREMENT for table `form_responses`
--
ALTER TABLE `form_responses`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `form_views`
--
ALTER TABLE `form_views`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `response_values`
--
ALTER TABLE `response_values`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=35;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `field_options`
--
ALTER TABLE `field_options`
  ADD CONSTRAINT `field_options_ibfk_1` FOREIGN KEY (`field_id`) REFERENCES `form_fields` (`id`);

--
-- Constraints for table `forms`
--
ALTER TABLE `forms`
  ADD CONSTRAINT `forms_user_fk` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`);

--
-- Constraints for table `form_fields`
--
ALTER TABLE `form_fields`
  ADD CONSTRAINT `form_fields_ibfk_1` FOREIGN KEY (`form_id`) REFERENCES `forms` (`id`);

--
-- Constraints for table `form_responses`
--
ALTER TABLE `form_responses`
  ADD CONSTRAINT `form_responses_ibfk_1` FOREIGN KEY (`form_id`) REFERENCES `forms` (`id`),
  ADD CONSTRAINT `form_responses_user_fk` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`);

--
-- Constraints for table `form_views`
--
ALTER TABLE `form_views`
  ADD CONSTRAINT `form_views_ibfk_1` FOREIGN KEY (`form_id`) REFERENCES `forms` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `response_values`
--
ALTER TABLE `response_values`
  ADD CONSTRAINT `response_values_ibfk_1` FOREIGN KEY (`response_id`) REFERENCES `form_responses` (`id`),
  ADD CONSTRAINT `response_values_ibfk_2` FOREIGN KEY (`field_id`) REFERENCES `form_fields` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
