-- phpMyAdmin SQL Dump
-- version 4.8.3
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jun 17, 2019 at 12:08 PM
-- Server version: 10.1.37-MariaDB
-- PHP Version: 7.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `college_portal`
--

DELIMITER $$
--
-- Procedures
--
CREATE DEFINER=`root`@`localhost` PROCEDURE `AuthenticateUser` (IN `username` VARCHAR(30))  BEGIN
		SELECT user_name, pwd FROM users WHERE user_name = username ;
	END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `InsertUser` (IN `username` VARCHAR(30), IN `email` VARCHAR(50), IN `pass` VARCHAR(20), IN `dob` DATE)  BEGIN
 		INSERT INTO users(user_name, email, pwd, dob)
        VALUES(username, email, pass, dob);
        INSERT INTO user_role
        SELECT u.user_id, r.role_id 
        FROM users u, role r 
        WHERE u.user_name = username && r.role_id = 3;
    END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `SelectAllUserDetails` (IN `username` VARCHAR(50))  BEGIN
    	SET @userID =
(SELECT user_id
FROM users
WHERE user_name = username);

SET @roleID = (SELECT role_id FROM user_role WHERE user_id = @userID);
SET @roleName = (SELECT role_name FROM role where role_id = @roleID);

SELECT u.* , ur.role_id, r.role_name
FROM user_role ur, users u, role r
WHERE u.user_id = @userID 
	&& ur.user_id = @userID
	&& r.role_name = @roleName ;
    END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `SelectUser` (IN `col_name` VARCHAR(50), IN `requirement` VARCHAR(50))  BEGIN 
    CASE col_name 
    	WHEN 'user_name' THEN SELECT user_name FROM Users WHERE user_name = requirement;
       	WHEN 'email' THEN SELECT email FROM Users WHERE email = requirement;
        END CASE;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `UpdateUser` (IN `userID` INT(6), IN `userDOB` DATE, IN `userPwd` VARCHAR(30))  BEGIN
    	UPDATE users
        SET dob = userDOB, pwd = userPwd
        WHERE userID = user_id;
	END$$

DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `course`
--

CREATE TABLE `course` (
  `course_id` int(6) UNSIGNED NOT NULL,
  `course_name` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `course`
--

INSERT INTO `course` (`course_id`, `course_name`) VALUES
(1, 'Accounting'),
(2, 'Agricultural Science'),
(3, 'Aquaculture'),
(4, 'Architecture'),
(5, 'Animation'),
(6, 'Aerospace'),
(7, 'Aircraft Maintenance'),
(8, 'Automotive Engineering'),
(9, 'Anthropology'),
(10, 'Actuarial Science'),
(11, 'Advertising'),
(12, 'Animal Science'),
(13, 'Audiology'),
(14, 'ACCA'),
(15, 'A-Levels'),
(16, 'American Degree Programme'),
(17, 'Banking'),
(18, 'Biomedical Science'),
(19, 'Business Administration'),
(20, 'Biomedical Engineering'),
(21, 'Biology'),
(22, 'Commerce'),
(23, 'Computer Engineering'),
(24, 'Computer Science'),
(25, 'Chemical Engineering'),
(26, 'Civil Engineering'),
(27, 'Culinary Arts'),
(28, 'Communications'),
(29, 'Counselling'),
(30, 'Chemistry'),
(31, 'College Foundations'),
(32, 'Canandian Pre-University'),
(33, 'Chemical Engineering with Oil and Gas'),
(34, 'Construction project management'),
(35, 'Design'),
(36, 'Dentistry'),
(37, 'Drama, Theatre & Film'),
(38, 'Economics'),
(39, 'Early Childhood Education'),
(40, 'Electrical Engineering'),
(41, 'Environmental Engineering'),
(42, 'Environmental Health'),
(43, 'Environmental Science'),
(44, 'Events Management'),
(45, 'Engineering Technology'),
(46, 'Food Technology'),
(47, 'Forensic Science'),
(48, 'Fashion Design'),
(49, 'Food Science'),
(50, 'Finance Management'),
(51, 'Game Design'),
(52, 'Graphic Design'),
(53, 'Geology'),
(54, 'Human Resource Management'),
(55, 'Hospitality Management'),
(56, 'History'),
(57, 'Interior Design'),
(58, 'Information System'),
(59, 'Industrial and Manufacturing Engineering'),
(60, 'Islamic Finance'),
(61, 'Industrial Electronics'),
(62, 'ICAEW'),
(63, 'Jurisprudence'),
(64, 'Journalism Broadcasting'),
(65, 'Language Studies'),
(66, 'Law'),
(67, 'Medical Laboratory Technology'),
(68, 'Multimedia'),
(69, 'Marketing'),
(70, 'Mechanical Engineering'),
(71, 'Marine Biology'),
(72, 'Marine Engineering'),
(73, 'Marine Science'),
(74, 'Mathematics'),
(75, 'Medicine'),
(76, 'Music'),
(77, 'Mechatronic Engineering'),
(78, 'Nursing'),
(79, 'Nutrition and Dietetics'),
(80, 'Nanotechnology'),
(81, 'Optometry'),
(82, 'Physiotherapy'),
(83, 'Piloting'),
(84, 'Petroleum Engineering'),
(85, 'Political Science'),
(86, 'Public Relations'),
(87, 'Pharmacy'),
(88, 'Psychology'),
(89, 'Physics'),
(90, 'Port Management'),
(91, 'Patisserie & Gastronomic Cuisine'),
(92, 'Quantity Surveying'),
(93, 'Radiography and Medical Imaging'),
(94, 'Religious Studies'),
(95, 'Sports Science'),
(96, 'Sociology'),
(97, 'Shipping Management'),
(98, 'South Australian Matriculation / SACE International'),
(99, 'Traditional Finance'),
(100, 'TESOL & TESL'),
(101, 'Traditional Medicine'),
(102, 'Tourism Management'),
(103, 'Urban and Regional Planning'),
(104, 'Veterinary Medicine');

-- --------------------------------------------------------

--
-- Table structure for table `gallery`
--

CREATE TABLE `gallery` (
  `image_id` bigint(20) UNSIGNED NOT NULL,
  `image_path` text NOT NULL,
  `news_id` bigint(20) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `institute`
--

CREATE TABLE `institute` (
  `institute_id` int(10) UNSIGNED NOT NULL,
  `institute_name` varchar(100) NOT NULL,
  `address` varchar(255) NOT NULL,
  `address_url` varchar(255) NOT NULL,
  `state_id` int(6) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `institute_course`
--

CREATE TABLE `institute_course` (
  `institute_id` int(10) UNSIGNED NOT NULL,
  `course_id` int(6) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `institute_user`
--

CREATE TABLE `institute_user` (
  `institute_id` int(10) UNSIGNED NOT NULL,
  `user_id` int(6) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `news`
--

CREATE TABLE `news` (
  `news_id` bigint(20) UNSIGNED NOT NULL,
  `content` text,
  `news_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `institute_id` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `rate`
--

CREATE TABLE `rate` (
  `user_id` int(6) UNSIGNED NOT NULL,
  `institute_id` int(6) UNSIGNED NOT NULL,
  `rating` int(1) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `role`
--

CREATE TABLE `role` (
  `role_id` int(6) UNSIGNED NOT NULL,
  `role_name` varchar(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `role`
--

INSERT INTO `role` (`role_id`, `role_name`) VALUES
(1, 'Super Admin'),
(2, 'Admin'),
(3, 'User');

-- --------------------------------------------------------

--
-- Table structure for table `state`
--

CREATE TABLE `state` (
  `state_id` int(6) UNSIGNED NOT NULL,
  `state_name` varchar(30) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `state`
--

INSERT INTO `state` (`state_id`, `state_name`) VALUES
(1, 'Johor'),
(2, 'Kedah'),
(3, 'Kelantan'),
(4, 'Malacca'),
(5, 'Negeri Sembilan'),
(6, 'Pahang'),
(7, 'Penang'),
(8, 'Perak'),
(9, 'Perlis'),
(10, 'Sabah'),
(11, 'Sarawak'),
(12, 'Selangor'),
(13, 'Terengganu');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `user_id` int(6) UNSIGNED NOT NULL,
  `user_name` varchar(30) NOT NULL,
  `email` varchar(50) NOT NULL,
  `pwd` varchar(20) NOT NULL,
  `dob` date NOT NULL,
  `recent_changes` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`user_id`, `user_name`, `email`, `pwd`, `dob`, `recent_changes`) VALUES
(11, 'jeffreytan', 'tanhoetheng@gmail.com', '12345abcde', '2000-04-12', '2019-06-16 06:48:13'),
(12, 'laukuansin', 'laukuansin@gmail.com', '12345abcde', '2000-03-05', '2019-06-17 00:39:37'),
(13, 'chuangjingyee', 'cjy@gmail.com', '12345abcde', '2000-12-12', '2019-06-13 15:07:31'),
(14, 'peyxinyee', 'pxy@gmail.com', '12345abcde', '1212-12-12', '2019-06-13 15:15:36'),
(16, 'amanda', 'amanda@gmail.com', '12345abcde', '2000-12-12', '2019-06-16 06:31:42'),
(19, 'jeffreytht', 'jeffrey@gmail.com', '12345abcde', '2000-12-12', '2019-06-16 06:45:42'),
(20, 'dannylu', 'dannylu@gmail.com', 'qw1234567890', '2000-02-18', '2019-06-17 00:11:56'),
(21, 'chuang jing yee', 'p18010120@student.newinti.edu.my', '12345abcde', '2000-04-15', '2019-06-17 06:25:19');

-- --------------------------------------------------------

--
-- Table structure for table `user_role`
--

CREATE TABLE `user_role` (
  `user_id` int(6) UNSIGNED NOT NULL,
  `role_id` int(6) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `user_role`
--

INSERT INTO `user_role` (`user_id`, `role_id`) VALUES
(11, 1),
(12, 2),
(13, 2),
(14, 2),
(16, 3),
(19, 3),
(20, 3),
(21, 3);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `course`
--
ALTER TABLE `course`
  ADD PRIMARY KEY (`course_id`);

--
-- Indexes for table `gallery`
--
ALTER TABLE `gallery`
  ADD PRIMARY KEY (`image_id`),
  ADD KEY `news_id` (`news_id`);

--
-- Indexes for table `institute`
--
ALTER TABLE `institute`
  ADD PRIMARY KEY (`institute_id`),
  ADD KEY `state_id` (`state_id`);

--
-- Indexes for table `institute_course`
--
ALTER TABLE `institute_course`
  ADD KEY `institute_id` (`institute_id`),
  ADD KEY `course_id` (`course_id`);

--
-- Indexes for table `institute_user`
--
ALTER TABLE `institute_user`
  ADD KEY `institute_id` (`institute_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `news`
--
ALTER TABLE `news`
  ADD PRIMARY KEY (`news_id`),
  ADD KEY `institute_id` (`institute_id`);

--
-- Indexes for table `rate`
--
ALTER TABLE `rate`
  ADD KEY `user_id` (`user_id`),
  ADD KEY `institute_id` (`institute_id`);

--
-- Indexes for table `role`
--
ALTER TABLE `role`
  ADD PRIMARY KEY (`role_id`);

--
-- Indexes for table `state`
--
ALTER TABLE `state`
  ADD PRIMARY KEY (`state_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`user_id`);

--
-- Indexes for table `user_role`
--
ALTER TABLE `user_role`
  ADD KEY `user_id` (`user_id`),
  ADD KEY `role_id` (`role_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `course`
--
ALTER TABLE `course`
  MODIFY `course_id` int(6) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=105;

--
-- AUTO_INCREMENT for table `gallery`
--
ALTER TABLE `gallery`
  MODIFY `image_id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `institute`
--
ALTER TABLE `institute`
  MODIFY `institute_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `news`
--
ALTER TABLE `news`
  MODIFY `news_id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `role`
--
ALTER TABLE `role`
  MODIFY `role_id` int(6) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `state`
--
ALTER TABLE `state`
  MODIFY `state_id` int(6) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `user_id` int(6) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `gallery`
--
ALTER TABLE `gallery`
  ADD CONSTRAINT `gallery_ibfk_1` FOREIGN KEY (`news_id`) REFERENCES `news` (`news_id`);

--
-- Constraints for table `institute_course`
--
ALTER TABLE `institute_course`
  ADD CONSTRAINT `institute_course_ibfk_1` FOREIGN KEY (`institute_id`) REFERENCES `institute` (`institute_id`),
  ADD CONSTRAINT `institute_course_ibfk_2` FOREIGN KEY (`course_id`) REFERENCES `course` (`course_id`);

--
-- Constraints for table `institute_user`
--
ALTER TABLE `institute_user`
  ADD CONSTRAINT `institute_user_ibfk_1` FOREIGN KEY (`institute_id`) REFERENCES `institute` (`institute_id`),
  ADD CONSTRAINT `institute_user_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`);

--
-- Constraints for table `news`
--
ALTER TABLE `news`
  ADD CONSTRAINT `news_ibfk_1` FOREIGN KEY (`institute_id`) REFERENCES `institute` (`institute_id`);

--
-- Constraints for table `rate`
--
ALTER TABLE `rate`
  ADD CONSTRAINT `rate_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`),
  ADD CONSTRAINT `rate_ibfk_2` FOREIGN KEY (`institute_id`) REFERENCES `institute` (`institute_id`);

--
-- Constraints for table `user_role`
--
ALTER TABLE `user_role`
  ADD CONSTRAINT `user_role_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`),
  ADD CONSTRAINT `user_role_ibfk_2` FOREIGN KEY (`role_id`) REFERENCES `role` (`role_id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
