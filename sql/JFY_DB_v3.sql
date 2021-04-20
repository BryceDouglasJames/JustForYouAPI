-- phpMyAdmin SQL Dump
-- version 5.0.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Apr 20, 2021 at 07:26 AM
-- Server version: 10.4.11-MariaDB
-- PHP Version: 7.4.3

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `jfy_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `answers`
--

CREATE TABLE `answers` (
  `AID` int(11) NOT NULL,
  `CAID` int(11) NOT NULL,
  `title` varchar(50) NOT NULL,
  `question` varchar(200) NOT NULL,
  `answer` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL CHECK (json_valid(`answer`)),
  `answer_weight` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL CHECK (json_valid(`answer_weight`))
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `answers`
--

INSERT INTO `answers` (`AID`, `CAID`, `title`, `question`, `answer`, `answer_weight`) VALUES
(2, 1, 'Test', 'AYOOOO TESTING', '{\"1\":\"ANSWER\"}', '{\"1\":\"67\"}');

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

CREATE TABLE `categories` (
  `CAID` int(11) NOT NULL,
  `category` varchar(25) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`CAID`, `category`) VALUES
(1, 'Mental Health'),
(2, 'General Health');

-- --------------------------------------------------------

--
-- Table structure for table `moderator`
--

CREATE TABLE `moderator` (
  `MODID` int(11) NOT NULL,
  `REQID` int(11) NOT NULL,
  `POSTID` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `requestslog`
--

CREATE TABLE `requestslog` (
  `REQID` int(11) NOT NULL,
  `Time` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `Method` varchar(10) NOT NULL,
  `Path` varchar(100) NOT NULL,
  `Error` varchar(1000) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `responses`
--

CREATE TABLE `responses` (
  `REID` int(11) NOT NULL,
  `UID` int(11) NOT NULL,
  `AID` int(11) NOT NULL,
  `UserChoice` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `userdata`
--

CREATE TABLE `userdata` (
  `PROVID` varchar(50) NOT NULL,
  `UID` int(11) NOT NULL,
  `DOB` date NOT NULL,
  `Weight` int(3) NOT NULL,
  `Height` int(4) NOT NULL,
  `Activity` varchar(20) NOT NULL,
  `Disease` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL,
  `FitScore` int(3) DEFAULT NULL,
  `DietScore` int(3) DEFAULT NULL,
  `MentalScore` int(3) DEFAULT NULL,
  `PersonalScore` int(3) DEFAULT NULL,
  `BMI` int(2) DEFAULT NULL,
  `ProfilePic` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `userdata`
--

INSERT INTO `userdata` (`PROVID`, `UID`, `DOB`, `Weight`, `Height`, `Activity`, `Disease`, `FitScore`, `DietScore`, `MentalScore`, `PersonalScore`, `BMI`, `ProfilePic`) VALUES
('QnJ5Y2Vz', 12, '2021-03-10', 123, 121, 'light', 'None', NULL, NULL, NULL, NULL, NULL, NULL),
('QVlP', 10, '2021-03-11', 123, 121, 'moderate', 'None', NULL, NULL, NULL, NULL, NULL, NULL),
('SGV5VGhlcmU=', 21, '2021-04-09', 123, 121, 'light', 'None', NULL, NULL, NULL, NULL, NULL, NULL),
('YWFh', 11, '2021-03-10', 123, 121, 'moderate', 'None', NULL, NULL, NULL, NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `userposts`
--

CREATE TABLE `userposts` (
  `POSTID` int(11) NOT NULL,
  `UID` int(11) DEFAULT NULL,
  `CID` int(11) DEFAULT NULL,
  `title` varchar(50) NOT NULL,
  `author` varchar(50) DEFAULT NULL,
  `likes` int(11) NOT NULL DEFAULT 0,
  `image` varchar(255) DEFAULT NULL,
  `body` varchar(500) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `userposts`
--

INSERT INTO `userposts` (`POSTID`, `UID`, `CID`, `title`, `author`, `likes`, `image`, `body`, `created_at`, `updated_at`) VALUES
(3, 21, 1, 'Testing', 'HeyThere', 0, '', 'Wow i hope this kinda works', '2021-04-20 07:12:09', '2021-04-20 07:12:09');

-- --------------------------------------------------------

--
-- Table structure for table `usertable`
--

CREATE TABLE `usertable` (
  `UID` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `Password` varchar(100) NOT NULL,
  `NewUser` tinyint(1) NOT NULL,
  `last_login` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `last_logout` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `usertable`
--

INSERT INTO `usertable` (`UID`, `name`, `email`, `Password`, `NewUser`, `last_login`, `last_logout`) VALUES
(7, 'Bryce', 'hello', 'WORKED', 0, '2021-04-18 01:19:04', '0000-00-00 00:00:00'),
(8, 'Tester', 'EmailOfSomething', 'asdf', 0, '2021-04-17 22:59:02', '0000-00-00 00:00:00'),
(9, 'fierybore', 'testing', 'test', 0, '2021-04-17 22:59:02', '0000-00-00 00:00:00'),
(10, 'AYO', 'ahhhh', 'ahhhhh', 0, '2021-04-17 22:59:02', '0000-00-00 00:00:00'),
(11, 'aaa', 'aaa', 'aaa', 0, '2021-04-17 22:59:02', '0000-00-00 00:00:00'),
(12, 'Bryces', 'aaa', 'aa', 0, '2021-04-17 22:59:02', '0000-00-00 00:00:00'),
(20, 'fierybore@@@', 'dsd', '1847c7b97425114aa61bcc8e10dc8b2dad39989c3862465547045a03ae80defb', 1, '2021-04-01 22:59:02', '0000-00-00 00:00:00'),
(21, 'HeyThere', 'Howdy', '79645a9814848b22ba8d4ea02dc78f02188ddd38be6f9432eec249b913bb63ad', 0, '2021-04-19 21:37:13', '2021-04-18 04:33:15'),
(34, 'YAYYYY', 'azzzz@YAYYY', 'a7be604944c9a441f84ffeee19baf9a4949ac6e9620aa0d82f56742405017cec', 1, '2021-04-18 03:52:33', '0000-00-00 00:00:00'),
(35, 'YAYYYY', 'azzzz@YAYYY', 'a7be604944c9a441f84ffeee19baf9a4949ac6e9620aa0d82f56742405017cec', 1, '2021-04-18 03:52:47', '0000-00-00 00:00:00'),
(36, 'YAYYYY', 'azzzz@YAYYY', 'a7be604944c9a441f84ffeee19baf9a4949ac6e9620aa0d82f56742405017cec', 1, '2021-04-18 03:52:47', '0000-00-00 00:00:00'),
(37, 'asfasf', 'asfasf', '2d64acdd6669667e6009360eba02638f717f5448edc44a75f73b4b19c2a21b21', 1, '2021-04-18 03:53:24', '0000-00-00 00:00:00'),
(38, 'waw', 'eqweqwe', '8e90df5ff5fdf135e2db8cc6e36c75590e36931663197c876e31b90aaeade119', 1, '2021-04-18 22:38:04', '0000-00-00 00:00:00'),
(39, 'waw', 'eqweqwe', '8e90df5ff5fdf135e2db8cc6e36c75590e36931663197c876e31b90aaeade119', 1, '2021-04-18 22:39:17', '0000-00-00 00:00:00'),
(40, 'waw', 'eqweqwe', '8e90df5ff5fdf135e2db8cc6e36c75590e36931663197c876e31b90aaeade119', 1, '2021-04-18 22:39:47', '0000-00-00 00:00:00');

-- --------------------------------------------------------

--
-- Table structure for table `weeklyscores`
--

CREATE TABLE `weeklyscores` (
  `SCOREID` int(11) NOT NULL,
  `UID` int(11) NOT NULL,
  `Monday` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`Monday`)),
  `Tuesday` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`Tuesday`)),
  `Wednesday` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`Wednesday`)),
  `Thursday` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`Thursday`)),
  `Friday` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`Friday`)),
  `Saturday` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`Saturday`)),
  `Sunday` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`Sunday`))
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `answers`
--
ALTER TABLE `answers`
  ADD PRIMARY KEY (`AID`),
  ADD KEY `CAID` (`CAID`);

--
-- Indexes for table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`CAID`);

--
-- Indexes for table `moderator`
--
ALTER TABLE `moderator`
  ADD PRIMARY KEY (`MODID`),
  ADD KEY `REQID` (`REQID`),
  ADD KEY `POSTID` (`POSTID`);

--
-- Indexes for table `requestslog`
--
ALTER TABLE `requestslog`
  ADD PRIMARY KEY (`REQID`);

--
-- Indexes for table `responses`
--
ALTER TABLE `responses`
  ADD PRIMARY KEY (`REID`),
  ADD UNIQUE KEY `UID` (`UID`),
  ADD UNIQUE KEY `AID` (`AID`),
  ADD UNIQUE KEY `AID_3` (`AID`),
  ADD KEY `AID_2` (`AID`);

--
-- Indexes for table `userdata`
--
ALTER TABLE `userdata`
  ADD PRIMARY KEY (`PROVID`),
  ADD KEY `UID` (`UID`);

--
-- Indexes for table `userposts`
--
ALTER TABLE `userposts`
  ADD PRIMARY KEY (`POSTID`),
  ADD KEY `UID` (`UID`),
  ADD KEY `CID` (`CID`);

--
-- Indexes for table `usertable`
--
ALTER TABLE `usertable`
  ADD PRIMARY KEY (`UID`);

--
-- Indexes for table `weeklyscores`
--
ALTER TABLE `weeklyscores`
  ADD PRIMARY KEY (`SCOREID`),
  ADD KEY `UID` (`UID`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `answers`
--
ALTER TABLE `answers`
  MODIFY `AID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `CAID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `requestslog`
--
ALTER TABLE `requestslog`
  MODIFY `REQID` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `userposts`
--
ALTER TABLE `userposts`
  MODIFY `POSTID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `usertable`
--
ALTER TABLE `usertable`
  MODIFY `UID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=41;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `answers`
--
ALTER TABLE `answers`
  ADD CONSTRAINT `answers_ibfk_2` FOREIGN KEY (`CAID`) REFERENCES `categories` (`CAID`);

--
-- Constraints for table `requestslog`
--
ALTER TABLE `requestslog`
  ADD CONSTRAINT `requestslog_ibfk_1` FOREIGN KEY (`REQID`) REFERENCES `moderator` (`REQID`);

--
-- Constraints for table `responses`
--
ALTER TABLE `responses`
  ADD CONSTRAINT `responses_ibfk_1` FOREIGN KEY (`UID`) REFERENCES `usertable` (`UID`),
  ADD CONSTRAINT `responses_ibfk_2` FOREIGN KEY (`AID`) REFERENCES `answers` (`AID`);

--
-- Constraints for table `userdata`
--
ALTER TABLE `userdata`
  ADD CONSTRAINT `userdata_ibfk_1` FOREIGN KEY (`UID`) REFERENCES `usertable` (`UID`) ON DELETE CASCADE;

--
-- Constraints for table `userposts`
--
ALTER TABLE `userposts`
  ADD CONSTRAINT `userposts_ibfk_2` FOREIGN KEY (`UID`) REFERENCES `usertable` (`UID`),
  ADD CONSTRAINT `userposts_ibfk_3` FOREIGN KEY (`CID`) REFERENCES `categories` (`CAID`);

--
-- Constraints for table `weeklyscores`
--
ALTER TABLE `weeklyscores`
  ADD CONSTRAINT `weeklyscores_ibfk_1` FOREIGN KEY (`UID`) REFERENCES `usertable` (`UID`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
