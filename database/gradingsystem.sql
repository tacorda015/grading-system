-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jan 18, 2024 at 02:11 AM
-- Server version: 10.4.28-MariaDB
-- PHP Version: 8.2.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `gradingsystem`
--

-- --------------------------------------------------------

--
-- Table structure for table `account_table`
--

CREATE TABLE `account_table` (
  `account_id` int(11) NOT NULL,
  `account_username` varchar(100) NOT NULL,
  `account_password` varchar(200) NOT NULL,
  `account_fName` varchar(100) NOT NULL,
  `account_lName` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `account_table`
--

INSERT INTO `account_table` (`account_id`, `account_username`, `account_password`, `account_fName`, `account_lName`) VALUES
(1, 'etacorda', 'U2FtcGxlQDEyMw==', 'Eduardo', 'Tacorda'),
(2, 'suser', 'U2FtcGxlQDEyMw==', 'Sample', 'User');

-- --------------------------------------------------------

--
-- Table structure for table `component_table`
--

CREATE TABLE `component_table` (
  `component_id` int(11) NOT NULL,
  `component_name` varchar(100) NOT NULL,
  `component_percentage` int(11) NOT NULL,
  `grading_session_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `component_table`
--

INSERT INTO `component_table` (`component_id`, `component_name`, `component_percentage`, `grading_session_id`) VALUES
(33, 'Regular Quizzes', 30, 9),
(34, 'Participation', 45, 9),
(35, 'Requirements', 10, 9),
(36, 'Exam', 15, 9),
(37, 'Regular Quizzes', 25, 10),
(38, 'Participation', 25, 10),
(39, 'Requirements', 25, 10),
(40, 'Exam', 25, 10),
(41, 'Regular Quizzes', 25, 11),
(42, 'Participation', 25, 11),
(43, 'Requirements', 25, 11),
(44, 'Exam', 25, 11),
(45, 'Regular Quizzes', 25, 12),
(46, 'Participation', 25, 12),
(47, 'Requirements', 25, 12),
(48, 'Exam', 25, 12),
(49, 'Regular Quizzes', 25, 13),
(50, 'Participation', 25, 13),
(51, 'Requirements', 25, 13),
(52, 'Exam', 25, 13),
(53, 'Regular Quizzes', 25, 14),
(54, 'Participation', 25, 14),
(55, 'Requirements', 25, 14),
(56, 'Exam', 25, 14),
(57, 'Regular Quizzes', 25, 15),
(58, 'Participation', 25, 15),
(59, 'Requirements', 25, 15),
(60, 'Exam', 25, 15),
(61, 'Regular Quizzes', 25, 16),
(62, 'Participation', 25, 16),
(64, 'Requirements', 25, 16),
(65, 'Exam', 25, 16);

-- --------------------------------------------------------

--
-- Table structure for table `component_value_table`
--

CREATE TABLE `component_value_table` (
  `component_value_id` int(11) NOT NULL,
  `component_value_name` varchar(20) NOT NULL,
  `component_value` int(11) NOT NULL,
  `component_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `component_value_table`
--

INSERT INTO `component_value_table` (`component_value_id`, `component_value_name`, `component_value`, `component_id`) VALUES
(97, 'RQ1', 50, 33),
(98, 'RQ2', 20, 33),
(99, 'RQ3', 50, 33),
(100, 'RQ4', 40, 33),
(101, 'RQ5', 50, 33),
(102, 'P1', 0, 34),
(103, 'P2', 45, 34),
(104, 'P3', 0, 34),
(105, 'P4', 20, 34),
(106, 'P5', 0, 34),
(107, 'R1', 50, 35),
(108, 'E1', 90, 36),
(109, 'RQ1', 0, 37),
(110, 'RQ2', 0, 37),
(111, 'RQ3', 0, 37),
(112, 'RQ4', 0, 37),
(113, 'RQ5', 0, 37),
(114, 'P1', 0, 38),
(115, 'P2', 0, 38),
(116, 'P3', 0, 38),
(117, 'P4', 0, 38),
(118, 'P5', 0, 38),
(119, 'R1', 0, 39),
(120, 'E1', 0, 40),
(121, 'RQ1', 0, 41),
(122, 'RQ2', 0, 41),
(123, 'RQ3', 0, 41),
(124, 'RQ4', 0, 41),
(125, 'RQ5', 0, 41),
(126, 'P1', 0, 42),
(127, 'P2', 0, 42),
(128, 'P3', 0, 42),
(129, 'P4', 0, 42),
(130, 'P5', 0, 42),
(131, 'R1', 0, 43),
(132, 'E1', 0, 44),
(133, 'RQ1', 0, 45),
(134, 'RQ2', 0, 45),
(135, 'RQ3', 0, 45),
(136, 'RQ4', 0, 45),
(137, 'RQ5', 0, 45),
(138, 'P1', 0, 46),
(139, 'P2', 0, 46),
(140, 'P3', 0, 46),
(141, 'P4', 0, 46),
(142, 'P5', 0, 46),
(143, 'R1', 0, 47),
(144, 'E1', 0, 48),
(145, 'RQ1', 0, 49),
(146, 'RQ2', 0, 49),
(147, 'RQ3', 0, 49),
(148, 'RQ4', 0, 49),
(149, 'RQ5', 0, 49),
(150, 'P1', 0, 50),
(151, 'P2', 0, 50),
(152, 'P3', 0, 50),
(153, 'P4', 0, 50),
(154, 'P5', 0, 50),
(155, 'R1', 0, 51),
(156, 'E1', 0, 52),
(157, 'RQ1', 0, 53),
(158, 'RQ2', 0, 53),
(159, 'RQ3', 0, 53),
(160, 'RQ4', 0, 53),
(161, 'RQ5', 0, 53),
(162, 'P1', 0, 54),
(163, 'P2', 0, 54),
(164, 'P3', 0, 54),
(165, 'P4', 0, 54),
(166, 'P5', 0, 54),
(167, 'R1', 0, 55),
(168, 'E1', 0, 56),
(169, 'RQ1', 0, 57),
(170, 'RQ2', 0, 57),
(171, 'RQ3', 0, 57),
(172, 'RQ4', 0, 57),
(173, 'RQ5', 0, 57),
(174, 'P1', 0, 58),
(175, 'P2', 0, 58),
(176, 'P3', 0, 58),
(177, 'P4', 0, 58),
(178, 'P5', 0, 58),
(179, 'R1', 0, 59),
(180, 'E1', 0, 60),
(181, 'RQ1', 0, 61),
(182, 'RQ2', 0, 61),
(183, 'RQ3', 0, 61),
(184, 'RQ4', 0, 61),
(185, 'RQ5', 0, 61),
(186, 'P1', 0, 62),
(187, 'P2', 0, 62),
(188, 'P3', 0, 62),
(189, 'P4', 0, 62),
(190, 'P5', 0, 62),
(191, 'RQ1', 0, 63),
(192, 'R1', 0, 64),
(193, 'RQ2', 0, 63),
(194, 'RQ3', 0, 63),
(195, 'E1', 0, 65),
(196, 'RQ4', 0, 63),
(197, 'RQ5', 0, 63);

-- --------------------------------------------------------

--
-- Table structure for table `course_subject_table`
--

CREATE TABLE `course_subject_table` (
  `course_subject_id` int(11) NOT NULL,
  `course_subject_name` varchar(150) NOT NULL,
  `course_name` varchar(100) NOT NULL,
  `subject_name` varchar(100) NOT NULL,
  `course_subject_teacher` varchar(100) NOT NULL,
  `teacher_id` int(11) NOT NULL,
  `sy_start` year(4) NOT NULL,
  `sy_end` year(4) NOT NULL,
  `course_subject_day` varchar(30) NOT NULL,
  `course_subject_time_start` time NOT NULL,
  `course_subject_time_end` time NOT NULL,
  `course_subject_room` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `course_subject_table`
--

INSERT INTO `course_subject_table` (`course_subject_id`, `course_subject_name`, `course_name`, `subject_name`, `course_subject_teacher`, `teacher_id`, `sy_start`, `sy_end`, `course_subject_day`, `course_subject_time_start`, `course_subject_time_end`, `course_subject_room`) VALUES
(3, 'IT/Math', 'IT', 'Math', 'Eduardo Tacorda', 1, '2024', '2025', 'Monday', '10:00:00', '11:00:00', 'Room 401'),
(4, 'CRIM/Alpha', 'CRIM', 'Alpha', 'Eduardo Tacorda', 1, '2024', '2025', 'Wednesday', '10:30:00', '11:30:00', 'Room 404');

-- --------------------------------------------------------

--
-- Table structure for table `grading_session_table`
--

CREATE TABLE `grading_session_table` (
  `grading_session_id` int(11) NOT NULL,
  `grading_session_name` varchar(100) NOT NULL,
  `grading_session_base` int(11) NOT NULL,
  `grading_session_percentage` int(11) NOT NULL,
  `course_subject_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `grading_session_table`
--

INSERT INTO `grading_session_table` (`grading_session_id`, `grading_session_name`, `grading_session_base`, `grading_session_percentage`, `course_subject_id`) VALUES
(9, 'First Grading', 60, 20, 3),
(10, 'Second Grading', 60, 20, 3),
(11, 'Third Grading', 60, 30, 3),
(12, 'Fourth Grading', 60, 30, 3),
(13, 'Preliminary', 60, 20, 4),
(14, 'Midterm', 60, 20, 4),
(15, 'SemiFinals', 60, 20, 4),
(16, 'Finals', 60, 40, 4);

-- --------------------------------------------------------

--
-- Table structure for table `student_grade_table`
--

CREATE TABLE `student_grade_table` (
  `student_grade_id` int(11) NOT NULL,
  `student_grade` int(11) NOT NULL,
  `student_id` int(11) NOT NULL,
  `component_value_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `student_grade_table`
--

INSERT INTO `student_grade_table` (`student_grade_id`, `student_grade`, `student_id`, `component_value_id`) VALUES
(1, 10, 6, 97),
(2, 0, 6, 106),
(3, 0, 6, 108),
(4, 50, 8, 107),
(5, 50, 7, 107),
(6, 50, 7, 97),
(7, 30, 7, 98),
(8, 40, 7, 99),
(9, 40, 7, 100),
(10, 30, 7, 101),
(22, 40, 8, 100),
(23, 50, 8, 99),
(24, 50, 8, 97),
(25, 50, 8, 101),
(26, 20, 8, 98),
(27, 10, 6, 101),
(28, 40, 6, 100),
(29, 0, 8, 121),
(30, 0, 8, 104),
(31, 45, 8, 103),
(32, 90, 8, 108),
(33, 10, 6, 98),
(34, 20, 6, 105),
(35, 0, 6, 104),
(36, 10, 6, 107),
(37, 20, 8, 105),
(38, 0, 6, 102),
(39, 0, 8, 106),
(40, 20, 6, 99),
(41, 0, 7, 108),
(42, 0, 6, 124),
(43, 10, 6, 103),
(44, 0, 8, 102),
(45, 0, 7, 104);

-- --------------------------------------------------------

--
-- Table structure for table `student_table`
--

CREATE TABLE `student_table` (
  `student_id` int(11) NOT NULL,
  `student_full_name` varchar(150) NOT NULL,
  `student_fName` varchar(100) NOT NULL,
  `student_mName` varchar(100) DEFAULT NULL,
  `student_lName` varchar(100) NOT NULL,
  `student_status` varchar(30) NOT NULL,
  `student_number` int(11) NOT NULL,
  `course_subject_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `student_table`
--

INSERT INTO `student_table` (`student_id`, `student_full_name`, `student_fName`, `student_mName`, `student_lName`, `student_status`, `student_number`, `course_subject_id`) VALUES
(1, 'Juan D. Cruz', 'Juan', 'Dela', 'Cruz', 'Regular', 201910045, 6),
(2, 'Juan C. Tamad', 'Juan', 'Cruz', 'Tamad', 'Regular', 201910046, 6),
(3, 'New S. Sample', 'New', 'Sample', 'Sample', 'Regular', 201910043, 4),
(5, 'This I. Staffs', 'This', 'Is the name of', 'Staffs', 'Regular', 201910040, 5),
(6, 'Eduardo  Tacorda', 'Eduardo', '', 'Tacorda', 'Regular', 201910040, 3),
(7, 'Fourth S. Student4', 'Fourth', 'Sample', 'Student4', 'Regular', 201910041, 3),
(8, 'Chicky  Bracchi', 'Chicky', '', 'Bracchi', 'Regular', 201910042, 3);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `account_table`
--
ALTER TABLE `account_table`
  ADD PRIMARY KEY (`account_id`);

--
-- Indexes for table `component_table`
--
ALTER TABLE `component_table`
  ADD PRIMARY KEY (`component_id`);

--
-- Indexes for table `component_value_table`
--
ALTER TABLE `component_value_table`
  ADD PRIMARY KEY (`component_value_id`);

--
-- Indexes for table `course_subject_table`
--
ALTER TABLE `course_subject_table`
  ADD PRIMARY KEY (`course_subject_id`);

--
-- Indexes for table `grading_session_table`
--
ALTER TABLE `grading_session_table`
  ADD PRIMARY KEY (`grading_session_id`);

--
-- Indexes for table `student_grade_table`
--
ALTER TABLE `student_grade_table`
  ADD PRIMARY KEY (`student_grade_id`);

--
-- Indexes for table `student_table`
--
ALTER TABLE `student_table`
  ADD PRIMARY KEY (`student_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `account_table`
--
ALTER TABLE `account_table`
  MODIFY `account_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `component_table`
--
ALTER TABLE `component_table`
  MODIFY `component_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=97;

--
-- AUTO_INCREMENT for table `component_value_table`
--
ALTER TABLE `component_value_table`
  MODIFY `component_value_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=289;

--
-- AUTO_INCREMENT for table `course_subject_table`
--
ALTER TABLE `course_subject_table`
  MODIFY `course_subject_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `grading_session_table`
--
ALTER TABLE `grading_session_table`
  MODIFY `grading_session_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;

--
-- AUTO_INCREMENT for table `student_grade_table`
--
ALTER TABLE `student_grade_table`
  MODIFY `student_grade_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=46;

--
-- AUTO_INCREMENT for table `student_table`
--
ALTER TABLE `student_table`
  MODIFY `student_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
