-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Mar 03, 2026 at 11:33 AM
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
-- Database: `aiu_paper_repository_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `access_status`
--

CREATE TABLE `access_status` (
  `status_id` int(11) NOT NULL,
  `status_name` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `account`
--

CREATE TABLE `account` (
  `account_id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password_hash` varchar(255) NOT NULL,
  `role_type` enum('STUDENT','ADMIN') NOT NULL,
  `status_id` int(11) DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `account`
--

INSERT INTO `account` (`account_id`, `username`, `password_hash`, `role_type`, `status_id`, `created_at`) VALUES
(1002, 'mama', '$2y$10$kSmEUKQrxhFofHODu/S2.eRtiNQibuzCggPMvJ94cBUyLBPX9eqFq', 'ADMIN', 1, '2026-02-01 07:17:40'),
(2001, 'mama', '$2y$10$gI1c2iN4YoNsvDryb26gq.nXYpzrZIA8EMbvLJQ.1VTVI1nWoIZQa', 'ADMIN', 1, '2026-02-01 07:17:12'),
(2002, 'Admin', '$2y$10$KAx18tbRpqqeLNF.LGj/9u1OcwmhwhaQGgFrOSt5OlFGGnN9IcSdC', 'ADMIN', 1, '2026-02-04 02:44:15'),
(23456, 'Asma', '$2y$10$r20WkH5qqLB8Ms2PRj6x0.mOn3wq5FUn9/r0TTGrrzt7NXrqNe1X2', 'STUDENT', 1, '2026-02-03 03:11:06'),
(24356, 'benat', '$2y$10$2Sz1L.akoMXXGI17TzyNdOPds5lO2OTr7oR2ls9CPZyb469abZxDG', 'STUDENT', 1, '2026-02-03 15:25:12'),
(38344, 'new', '$2y$10$MmZLnO6sutB4P4Lv.JSlje8/Byexcc1jltaDxlLDQk5O3YmVAVpk6', 'ADMIN', 1, '2026-02-04 02:47:29'),
(123498, 'Asma', '$2y$10$5u6y1U0Svur7pPie0fH2WOihq5lLS84HVb9bMlrVLFrXjlumiHaNK', 'STUDENT', 1, '2026-02-02 17:25:14'),
(736282, 'Ahmed', '$2y$10$kQD2UL.CeMTzWC/5Xn/4cOLDaC3sc4rQsEWOaTFS3Z8JYDSHIDJxy', 'STUDENT', 1, '2026-02-03 03:13:27'),
(743827, 'Ali', '$2y$10$L6zALaKci.1N9Po5CPUqKOIAf2gFN4Rt0wdrEf8hX.r/l7F4vRLOa', 'STUDENT', 1, '2026-02-03 03:12:46'),
(24102345, 'Sara', '$2y$10$ataTrtiM9XnS49CjFNGdPerfpdWIDJM8lMDx9VHADMwh9UDWfaNWi', 'STUDENT', 1, '2026-02-03 03:24:03');

-- --------------------------------------------------------

--
-- Table structure for table `admin`
--

CREATE TABLE `admin` (
  `admin_id` int(11) NOT NULL,
  `admin_name` varchar(100) NOT NULL,
  `admin_email` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `admin`
--

INSERT INTO `admin` (`admin_id`, `admin_name`, `admin_email`) VALUES
(1002, 'ama mama', 'haroonhela@gmail.com'),
(2001, 'Admin User', 'admin@example.com');

-- --------------------------------------------------------

--
-- Table structure for table `category`
--

CREATE TABLE `category` (
  `category_id` int(11) NOT NULL,
  `category_name` varchar(100) NOT NULL,
  `description` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `category`
--

INSERT INTO `category` (`category_id`, `category_name`, `description`) VALUES
(1, 'Computer Science', 'Research papers related to computing, AI, and software engineering'),
(2, 'Business', 'Business, economics, and management studies'),
(3, 'Social Sciences', 'Research in sociology, psychology, and related fields'),
(4, 'Media & Communication', 'Studies in journalism, communication, and media'),
(5, 'Education', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `citation`
--

CREATE TABLE `citation` (
  `citation_id` int(11) NOT NULL,
  `paper_id` int(11) NOT NULL,
  `source_id` int(11) DEFAULT NULL,
  `citation_date` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `citation`
--

INSERT INTO `citation` (`citation_id`, `paper_id`, `source_id`, `citation_date`) VALUES
(1, 1, 1, '2025-01-31'),
(2, 0, 1, '2026-01-02'),
(3, 13, 1, '2026-02-02'),
(4, 19, 1, '2026-09-07'),
(5, 19, 1, '2026-05-03');

-- --------------------------------------------------------

--
-- Table structure for table `citation_sourse`
--

CREATE TABLE `citation_sourse` (
  `source_id` int(11) NOT NULL,
  `source_type` varchar(50) DEFAULT NULL,
  `source_name` varchar(255) DEFAULT NULL,
  `publisher` varchar(255) DEFAULT NULL,
  `year` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `citation_sourse`
--

INSERT INTO `citation_sourse` (`source_id`, `source_type`, `source_name`, `publisher`, `year`) VALUES
(1, 'Journal', 'International Journal of AI', 'Springer', 2024),
(2, 'journal', 'Times new', 'gdhs', 2023),
(3, 'journal', 'Times new', 'gdhs', 2023);

-- --------------------------------------------------------

--
-- Table structure for table `download_record`
--

CREATE TABLE `download_record` (
  `download_id` int(11) NOT NULL,
  `student_id` int(11) NOT NULL,
  `paper_id` int(11) NOT NULL,
  `download_time` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `download_record`
--

INSERT INTO `download_record` (`download_id`, `student_id`, `paper_id`, `download_time`) VALUES
(1, 235678, 1, '2026-02-01 07:10:52'),
(2, 123498, 4, '2026-02-02 17:35:41'),
(3, 123498, 4, '2026-02-02 17:37:05'),
(4, 123498, 4, '2026-02-02 17:39:51'),
(5, 123498, 4, '2026-02-02 17:42:44'),
(6, 123498, 4, '2026-02-02 17:42:49'),
(7, 123498, 2, '2026-02-02 17:52:39'),
(8, 235678, 2, '2026-02-03 01:58:37'),
(9, 235678, 2, '2026-02-03 02:00:28'),
(10, 235678, 2, '2026-02-03 03:09:01'),
(11, 235678, 2, '2026-02-03 03:09:02'),
(12, 24102345, 14, '2026-02-03 03:52:09'),
(13, 24102345, 16, '2026-02-03 03:52:12'),
(14, 23456, 15, '2026-02-03 05:38:22'),
(15, 23456, 11, '2026-02-03 05:38:28'),
(16, 23456, 11, '2026-02-03 05:39:49'),
(17, 23456, 11, '2026-02-03 05:40:38'),
(18, 23456, 14, '2026-02-03 05:40:57'),
(19, 23456, 15, '2026-02-03 06:24:42'),
(20, 23456, 12, '2026-02-03 09:17:52'),
(21, 23456, 12, '2026-02-03 11:00:01'),
(22, 24356, 9, '2026-02-03 15:32:00');

-- --------------------------------------------------------

--
-- Table structure for table `keyword`
--

CREATE TABLE `keyword` (
  `keyword_id` int(11) NOT NULL,
  `keyword_name` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `keyword`
--

INSERT INTO `keyword` (`keyword_id`, `keyword_name`) VALUES
(8, '#data #2026'),
(6, 'abs'),
(1, 'Artificial Intelligence'),
(4, 'Communication Studies'),
(2, 'Database Systems'),
(7, 'inside'),
(3, 'Software Engineering'),
(5, 'wed');

-- --------------------------------------------------------

--
-- Table structure for table `login_log`
--

CREATE TABLE `login_log` (
  `log_id` int(11) NOT NULL,
  `account_id` int(11) NOT NULL,
  `login_time` datetime DEFAULT current_timestamp(),
  `ip_address` varchar(45) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `login_log`
--

INSERT INTO `login_log` (`log_id`, `account_id`, `login_time`, `ip_address`) VALUES
(1, 235678, '2026-02-01 00:08:03', '192.168.1.10');

-- --------------------------------------------------------

--
-- Table structure for table `metric`
--

CREATE TABLE `metric` (
  `metric_id` int(11) NOT NULL,
  `metric_name` varchar(100) DEFAULT NULL,
  `unit` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `metric`
--

INSERT INTO `metric` (`metric_id`, `metric_name`, `unit`) VALUES
(1, 'Download Count', 'times'),
(2, 'Citation Count', 'citations'),
(3, 'Submission Count', 'submissions');

-- --------------------------------------------------------

--
-- Table structure for table `paper_approval`
--

CREATE TABLE `paper_approval` (
  `approval_id` int(11) NOT NULL,
  `paper_id` int(11) DEFAULT NULL,
  `approval_status` enum('Pending','Approved','Rejected') DEFAULT 'Pending',
  `approval_time` timestamp NOT NULL DEFAULT current_timestamp(),
  `admin_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `paper_approval`
--

INSERT INTO `paper_approval` (`approval_id`, `paper_id`, `approval_status`, `approval_time`, `admin_id`) VALUES
(8, 8, 'Pending', '2026-02-03 03:14:33', NULL),
(9, 9, 'Approved', '2026-02-03 03:45:59', 1002),
(10, 10, 'Approved', '2026-02-03 04:30:28', 1002),
(11, 11, 'Approved', '2026-02-03 03:29:47', 1002),
(12, 12, 'Approved', '2026-02-03 03:29:52', 1002),
(13, 13, 'Rejected', '2026-02-03 03:29:32', 1002),
(14, 14, 'Approved', '2026-02-03 03:29:19', 1002),
(17, 17, 'Approved', '2026-02-03 08:40:05', 1002),
(18, 18, 'Pending', '2026-02-03 11:06:49', NULL),
(19, 19, 'Pending', '2026-02-03 19:33:55', 1002),
(20, 20, 'Pending', '2026-02-03 18:08:49', NULL),
(21, 21, 'Approved', '2026-02-03 18:15:30', 1002),
(22, 22, 'Pending', '2026-02-03 19:35:27', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `paper_keyword`
--

CREATE TABLE `paper_keyword` (
  `paper_id` int(11) NOT NULL,
  `keyword_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `paper_keyword`
--

INSERT INTO `paper_keyword` (`paper_id`, `keyword_id`) VALUES
(20, 5),
(21, 6),
(22, 7),
(23, 8);

-- --------------------------------------------------------

--
-- Table structure for table `programme`
--

CREATE TABLE `programme` (
  `programme_id` int(11) NOT NULL,
  `programme_name` varchar(100) NOT NULL,
  `faculty` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `programme`
--

INSERT INTO `programme` (`programme_id`, `programme_name`, `faculty`) VALUES
(2602, 'SCI', 'Science'),
(2603, 'SBSS', 'School of Business and Social Sciences'),
(2604, 'BPIR', 'Business, Public International Relations'),
(2605, 'MEDIA & COMMUNICATION', 'Media and Communication');

-- --------------------------------------------------------

--
-- Table structure for table `report`
--

CREATE TABLE `report` (
  `report_id` int(11) NOT NULL,
  `report_type_id` int(11) DEFAULT NULL,
  `student_id` int(11) DEFAULT NULL,
  `paper_id` int(11) DEFAULT NULL,
  `created_at` date DEFAULT NULL,
  `admin_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `report`
--

INSERT INTO `report` (`report_id`, `report_type_id`, `student_id`, `paper_id`, `created_at`, `admin_id`) VALUES
(1, 1, 235678, 1, '2025-01-31', 2001),
(2, 2, 235678, 1, '2025-02-01', 2001),
(3, 3, 235678, 1, '2025-02-02', 2001),
(4, 1, 123498, 4, '2026-02-02', 2002),
(5, 2, 235678, 5, '2026-02-02', 2004),
(6, 2, 24102345, 14, '2026-02-03', 2001),
(7, 3, 23456, 8, '2026-02-03', 2003),
(8, 2, 24356, 14, '2026-02-03', 2004),
(9, 3, 24102345, 17, '2026-02-03', 2002),
(10, 2, 24102345, 12, '2026-02-03', 2001),
(11, 2, 24102345, 12, '2026-02-03', 2001),
(12, 4, 24102345, 13, '2026-02-03', 2002),
(13, 5, 24102345, 13, '2026-02-03', 2002),
(14, 6, 24102345, 13, '2026-02-03', 2002),
(15, 7, 24102345, 19, '2026-02-03', 2003),
(16, 8, 24102345, 19, '2026-02-03', 2003),
(17, 1, 23456, 10, '2026-02-03', 2003);

-- --------------------------------------------------------

--
-- Table structure for table `report_static`
--

CREATE TABLE `report_static` (
  `stat_id` int(11) NOT NULL,
  `report_id` int(11) DEFAULT NULL,
  `metric_id` int(11) DEFAULT NULL,
  `metric_value` decimal(10,2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `report_static`
--

INSERT INTO `report_static` (`stat_id`, `report_id`, `metric_id`, `metric_value`) VALUES
(1, 1, 1, 5.00),
(2, 1, 2, 2.00),
(3, 2, 3, 1.00);

-- --------------------------------------------------------

--
-- Table structure for table `report_type`
--

CREATE TABLE `report_type` (
  `report_type_id` int(11) NOT NULL,
  `type_name` varchar(100) DEFAULT NULL,
  `description` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `report_type`
--

INSERT INTO `report_type` (`report_type_id`, `type_name`, `description`) VALUES
(1, 'Plagiarism Report', 'Generated to check similarity index of submitted papers'),
(2, 'Progress Report', 'Tracks student submission progress and deadlines'),
(3, 'Approval Report', 'Shows admin approval/rejection status of papers');

-- --------------------------------------------------------

--
-- Table structure for table `research_paper`
--

CREATE TABLE `research_paper` (
  `paper_id` int(11) NOT NULL,
  `title` varchar(200) NOT NULL,
  `abstract` text NOT NULL,
  `publication_year` year(4) NOT NULL,
  `file_path` varchar(255) NOT NULL,
  `student_id` int(11) NOT NULL,
  `category_id` int(11) NOT NULL,
  `supervisor_id` int(11) NOT NULL,
  `download_count` int(11) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `research_paper`
--

INSERT INTO `research_paper` (`paper_id`, `title`, `abstract`, `publication_year`, `file_path`, `student_id`, `category_id`, `supervisor_id`, `download_count`) VALUES
(8, 'Software', 'First Software Engineering project', '2026', 'uploads/B6 (6).pdf', 23456, 1, 5, 0),
(9, 'Math', 'Complete Computer math research', '2024', 'uploads/MATHS ASSIGNMENT.pdf', 23456, 1, 3, 1),
(10, 'DataBase', 'Complete database research paper', '2026', 'uploads/B6_BCS2B_FINAL (3).pdf', 743827, 1, 6, 0),
(11, 'Academic Writing', 'Integrity in Writing', '2026', 'uploads/LLN1012 - COURSEWORK 3 - COMPONENT 1 (OUTLINE SAMPLE).pdf', 743827, 3, 4, 3),
(12, 'OOP', 'Object oriented Programing ', '2026', 'uploads/oop_project.pdf', 24102345, 1, 2, 2),
(13, 'Probability', 'new Research of Statics', '2024', 'uploads/B6 (3).pdf', 24102345, 1, 5, 0),
(14, 'Probability', 'new Research of Statics', '2024', 'uploads/B6 (3).pdf', 24102345, 1, 5, 2),
(17, 'AI', 'New research in student usage of AI', '2025', 'uploads/Activity_diagram.pdf', 24102345, 1, 2, 0),
(19, 'Economy2', 'Research abuot count', '2023', 'uploads/oop_project (2).pdf', 24356, 4, 4, 0),
(20, 'asd', 'new', '2024', 'uploads/oop_project (3).pdf', 24102345, 4, 2, 0),
(21, 'new', 'se', '2026', 'uploads/COMPONENT 1 (PEER REVIEWING SHEET 1).pdf', 24102345, 5, 7, 0),
(22, 'klh', 'jhjk', '2024', 'uploads/B6 (2).pdf', 23456, 4, 6, 0);

-- --------------------------------------------------------

--
-- Table structure for table `student`
--

CREATE TABLE `student` (
  `student_id` int(11) NOT NULL,
  `student_fname` varchar(100) NOT NULL,
  `student_lname` varchar(100) NOT NULL,
  `student_email` varchar(150) NOT NULL,
  `programme_id` int(11) NOT NULL,
  `status_id` int(11) DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `student`
--

INSERT INTO `student` (`student_id`, `student_fname`, `student_lname`, `student_email`, `programme_id`, `status_id`) VALUES
(23456, 'Asma', 'Ayobi', 'Asma@gmail.com', 2603, 1),
(24356, 'Benat', 'Siraj', 'benat@gmail.com', 2603, 1),
(743827, 'Ali', 'Ahmed', 'Ahmed@gmail.com', 2605, 1),
(24102345, 'Sara', 'Rasoli', 'Sara@gmail.com', 2602, 1);

-- --------------------------------------------------------

--
-- Table structure for table `submission_log`
--

CREATE TABLE `submission_log` (
  `submission_id` int(11) NOT NULL,
  `paper_id` int(11) NOT NULL,
  `student_id` int(11) NOT NULL,
  `submission_time` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `submission_log`
--

INSERT INTO `submission_log` (`submission_id`, `paper_id`, `student_id`, `submission_time`) VALUES
(1, 1, 235678, '2026-02-01 07:10:07');

-- --------------------------------------------------------

--
-- Table structure for table `supervisor`
--

CREATE TABLE `supervisor` (
  `supervisor_id` int(11) NOT NULL,
  `supervisor_name` varchar(100) NOT NULL,
  `supervisor_email` varchar(150) NOT NULL,
  `department` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `supervisor`
--

INSERT INTO `supervisor` (`supervisor_id`, `supervisor_name`, `supervisor_email`, `department`) VALUES
(1, 'Dr. Ahmad', 'ahmad@aiu.edu.my', 'Computer Science'),
(2, 'Dr. Lim', 'lim@aiu.edu.my', 'Business'),
(3, 'Dr. Fatimah', 'fatimah@aiu.edu.my', 'Social Sciences'),
(4, 'Dr. Kumar', 'kumar@aiu.edu.my', 'Media & Communication'),
(5, 'madam Nadiah', '', ''),
(6, 'Madam Umi', '', ''),
(7, 'Dr Sav', '', '');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `access_status`
--
ALTER TABLE `access_status`
  ADD PRIMARY KEY (`status_id`),
  ADD KEY `status_name` (`status_name`);

--
-- Indexes for table `account`
--
ALTER TABLE `account`
  ADD PRIMARY KEY (`account_id`),
  ADD KEY `username` (`username`),
  ADD KEY `status_id` (`status_id`);

--
-- Indexes for table `admin`
--
ALTER TABLE `admin`
  ADD PRIMARY KEY (`admin_id`),
  ADD UNIQUE KEY `admin_email` (`admin_email`);

--
-- Indexes for table `category`
--
ALTER TABLE `category`
  ADD PRIMARY KEY (`category_id`),
  ADD UNIQUE KEY `category_name` (`category_name`);

--
-- Indexes for table `citation`
--
ALTER TABLE `citation`
  ADD PRIMARY KEY (`citation_id`),
  ADD KEY `paper_id` (`paper_id`),
  ADD KEY `source_id` (`source_id`);

--
-- Indexes for table `citation_sourse`
--
ALTER TABLE `citation_sourse`
  ADD PRIMARY KEY (`source_id`);

--
-- Indexes for table `download_record`
--
ALTER TABLE `download_record`
  ADD PRIMARY KEY (`download_id`),
  ADD KEY `student_id` (`student_id`),
  ADD KEY `paper_id` (`paper_id`);

--
-- Indexes for table `keyword`
--
ALTER TABLE `keyword`
  ADD PRIMARY KEY (`keyword_id`),
  ADD UNIQUE KEY `keyword_name` (`keyword_name`);

--
-- Indexes for table `login_log`
--
ALTER TABLE `login_log`
  ADD PRIMARY KEY (`log_id`);

--
-- Indexes for table `metric`
--
ALTER TABLE `metric`
  ADD PRIMARY KEY (`metric_id`);

--
-- Indexes for table `paper_approval`
--
ALTER TABLE `paper_approval`
  ADD PRIMARY KEY (`approval_id`),
  ADD KEY `paper_id` (`paper_id`),
  ADD KEY `admin_id` (`admin_id`);

--
-- Indexes for table `paper_keyword`
--
ALTER TABLE `paper_keyword`
  ADD PRIMARY KEY (`paper_id`),
  ADD KEY `keyword_id` (`keyword_id`);

--
-- Indexes for table `programme`
--
ALTER TABLE `programme`
  ADD PRIMARY KEY (`programme_id`);

--
-- Indexes for table `report`
--
ALTER TABLE `report`
  ADD PRIMARY KEY (`report_id`),
  ADD KEY `report_type_id` (`report_type_id`),
  ADD KEY `admin_id` (`admin_id`);

--
-- Indexes for table `report_static`
--
ALTER TABLE `report_static`
  ADD PRIMARY KEY (`stat_id`),
  ADD KEY `report_id` (`report_id`),
  ADD KEY `metric_id` (`metric_id`);

--
-- Indexes for table `report_type`
--
ALTER TABLE `report_type`
  ADD PRIMARY KEY (`report_type_id`);

--
-- Indexes for table `research_paper`
--
ALTER TABLE `research_paper`
  ADD PRIMARY KEY (`paper_id`),
  ADD KEY `student_id` (`student_id`),
  ADD KEY `category_id` (`category_id`),
  ADD KEY `supervisor_id` (`supervisor_id`);

--
-- Indexes for table `student`
--
ALTER TABLE `student`
  ADD PRIMARY KEY (`student_id`),
  ADD UNIQUE KEY `student_email` (`student_email`),
  ADD KEY `programme_id` (`programme_id`);

--
-- Indexes for table `submission_log`
--
ALTER TABLE `submission_log`
  ADD PRIMARY KEY (`submission_id`),
  ADD KEY `paper_id` (`paper_id`),
  ADD KEY `student_id` (`student_id`);

--
-- Indexes for table `supervisor`
--
ALTER TABLE `supervisor`
  ADD PRIMARY KEY (`supervisor_id`),
  ADD KEY `supervisor_email` (`supervisor_email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `account`
--
ALTER TABLE `account`
  MODIFY `account_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24102346;

--
-- AUTO_INCREMENT for table `category`
--
ALTER TABLE `category`
  MODIFY `category_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `citation`
--
ALTER TABLE `citation`
  MODIFY `citation_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `citation_sourse`
--
ALTER TABLE `citation_sourse`
  MODIFY `source_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `download_record`
--
ALTER TABLE `download_record`
  MODIFY `download_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- AUTO_INCREMENT for table `keyword`
--
ALTER TABLE `keyword`
  MODIFY `keyword_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `login_log`
--
ALTER TABLE `login_log`
  MODIFY `log_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `metric`
--
ALTER TABLE `metric`
  MODIFY `metric_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `paper_approval`
--
ALTER TABLE `paper_approval`
  MODIFY `approval_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24;

--
-- AUTO_INCREMENT for table `programme`
--
ALTER TABLE `programme`
  MODIFY `programme_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2606;

--
-- AUTO_INCREMENT for table `report`
--
ALTER TABLE `report`
  MODIFY `report_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT for table `report_static`
--
ALTER TABLE `report_static`
  MODIFY `stat_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `report_type`
--
ALTER TABLE `report_type`
  MODIFY `report_type_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `research_paper`
--
ALTER TABLE `research_paper`
  MODIFY `paper_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24;

--
-- AUTO_INCREMENT for table `submission_log`
--
ALTER TABLE `submission_log`
  MODIFY `submission_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `supervisor`
--
ALTER TABLE `supervisor`
  MODIFY `supervisor_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
