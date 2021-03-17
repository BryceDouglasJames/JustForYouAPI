-- phpMyAdmin SQL Dump
-- version 5.0.4
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Mar 16, 2021 at 01:28 AM
-- Server version: 10.4.17-MariaDB
-- PHP Version: 8.0.1

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
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
  `ANID` int(11) NOT NULL,
  `QUID` int(11) NOT NULL,
  `CAID` int(11) NOT NULL,
  `answer` int(11) NOT NULL,
  `answer_weight` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

CREATE TABLE `categories` (
  `CAID` int(11) NOT NULL,
  `category` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `questions`
--

CREATE TABLE `questions` (
  `QUID` int(11) NOT NULL,
  `title` varchar(50) NOT NULL,
  `question` blob NOT NULL,
  `CAID` int(11) NOT NULL,
  `weights` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `responses`
--

CREATE TABLE `responses` (
  `RESPID` int(11) NOT NULL,
  `UID` int(11) NOT NULL,
  `QUID` int(11) NOT NULL,
  `CAID` int(11) NOT NULL,
  `ANID` int(11) NOT NULL,
  `response` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `usertable`
--

CREATE TABLE `usertable` (
  `UID` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `Password` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `answers`
--
ALTER TABLE `answers`
  ADD PRIMARY KEY (`ANID`),
  ADD KEY `QUID` (`QUID`),
  ADD KEY `CAID` (`CAID`);

--
-- Indexes for table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`CAID`);

--
-- Indexes for table `questions`
--
ALTER TABLE `questions`
  ADD PRIMARY KEY (`QUID`),
  ADD KEY `CAID` (`CAID`);

--
-- Indexes for table `responses`
--
ALTER TABLE `responses`
  ADD PRIMARY KEY (`RESPID`),
  ADD KEY `UID` (`UID`),
  ADD KEY `QUID` (`QUID`),
  ADD KEY `CAID` (`CAID`),
  ADD KEY `ANID` (`ANID`);

--
-- Indexes for table `usertable`
--
ALTER TABLE `usertable`
  ADD PRIMARY KEY (`UID`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `answers`
--
ALTER TABLE `answers`
  MODIFY `ANID` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `CAID` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `questions`
--
ALTER TABLE `questions`
  MODIFY `QUID` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `responses`
--
ALTER TABLE `responses`
  MODIFY `RESPID` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `usertable`
--
ALTER TABLE `usertable`
  MODIFY `UID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `answers`
--
ALTER TABLE `answers`
  ADD CONSTRAINT `answers_ibfk_1` FOREIGN KEY (`QUID`) REFERENCES `questions` (`QUID`),
  ADD CONSTRAINT `answers_ibfk_2` FOREIGN KEY (`CAID`) REFERENCES `categories` (`CAID`);

--
-- Constraints for table `questions`
--
ALTER TABLE `questions`
  ADD CONSTRAINT `questions_ibfk_1` FOREIGN KEY (`CAID`) REFERENCES `categories` (`CAID`);

--
-- Constraints for table `responses`
--
ALTER TABLE `responses`
  ADD CONSTRAINT `responses_ibfk_1` FOREIGN KEY (`UID`) REFERENCES `usertable` (`UID`),
  ADD CONSTRAINT `responses_ibfk_2` FOREIGN KEY (`CAID`) REFERENCES `categories` (`CAID`),
  ADD CONSTRAINT `responses_ibfk_3` FOREIGN KEY (`QUID`) REFERENCES `questions` (`QUID`),
  ADD CONSTRAINT `responses_ibfk_4` FOREIGN KEY (`ANID`) REFERENCES `answers` (`ANID`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
