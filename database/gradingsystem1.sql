-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jan 12, 2024 at 08:56 AM
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
(4, 'IT/Math', 'IT', 'Math', 'Eduardo Tacorda', 1, '2024', '2025', 'Tuesday', '13:45:00', '15:45:00', 'Room 404'),
(5, 'CRIM/Alpha', 'CRIM', 'Alpha', 'Sample User', 2, '2024', '2025', 'Monday', '07:30:00', '09:00:00', 'Room 301'),
(6, 'IT/English', 'IT', 'English', 'Eduardo Tacorda', 1, '2024', '2025', 'Tuesday', '09:00:00', '10:30:00', 'Room 404');

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
(4, 'News S. User', 'News', 'Sample', 'User', 'Regular', 201910042, 4),
(5, 'This I. Staffs', 'This', 'Is the name of', 'Staffs', 'Regular', 201910040, 5);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `account_table`
--
ALTER TABLE `account_table`
  ADD PRIMARY KEY (`account_id`);

--
-- Indexes for table `course_subject_table`
--
ALTER TABLE `course_subject_table`
  ADD PRIMARY KEY (`course_subject_id`);

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
-- AUTO_INCREMENT for table `course_subject_table`
--
ALTER TABLE `course_subject_table`
  MODIFY `course_subject_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `student_table`
--
ALTER TABLE `student_table`
  MODIFY `student_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
