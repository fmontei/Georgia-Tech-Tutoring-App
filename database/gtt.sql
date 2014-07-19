-- phpMyAdmin SQL Dump
-- version 4.1.14
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: Jul 19, 2014 at 08:40 PM
-- Server version: 5.6.17
-- PHP Version: 5.5.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `gtt`
--

-- --------------------------------------------------------

--
-- Table structure for table `administrator`
--

CREATE TABLE IF NOT EXISTS `administrator` (
  `GTID` char(9) NOT NULL DEFAULT '',
  `Password` varchar(30) NOT NULL,
  PRIMARY KEY (`GTID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `administrator`
--

INSERT INTO `administrator` (`GTID`, `Password`) VALUES
('000000020', '000000020');

-- --------------------------------------------------------

--
-- Table structure for table `course`
--

CREATE TABLE IF NOT EXISTS `course` (
  `School` varchar(100) NOT NULL DEFAULT '',
  `Number` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`School`,`Number`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `course`
--

INSERT INTO `course` (`School`, `Number`) VALUES
('ECE', 1000),
('ECE', 2000),
('ECE', 3000),
('ECE', 4000),
('ECE', 5000);

-- --------------------------------------------------------

--
-- Table structure for table `graduate`
--

CREATE TABLE IF NOT EXISTS `graduate` (
  `GTID` char(9) NOT NULL DEFAULT '',
  `Password` varchar(30) NOT NULL,
  PRIMARY KEY (`GTID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `graduate`
--

INSERT INTO `graduate` (`GTID`, `Password`) VALUES
('000000001', '000000001'),
('000000002', '000000002'),
('000000003', '000000003'),
('000000004', '000000004'),
('000000005', '000000005');

-- --------------------------------------------------------

--
-- Table structure for table `hires`
--

CREATE TABLE IF NOT EXISTS `hires` (
  `GTID_Undergraduate` char(9) NOT NULL DEFAULT '',
  `GTID_Tutor` char(9) NOT NULL DEFAULT '',
  `School` varchar(100) NOT NULL DEFAULT '',
  `Number` int(11) NOT NULL DEFAULT '0',
  `Time` varchar(8) NOT NULL DEFAULT '',
  `Semester` varchar(6) NOT NULL DEFAULT '',
  `Weekday` varchar(10) NOT NULL DEFAULT '',
  PRIMARY KEY (`GTID_Undergraduate`,`GTID_Tutor`,`School`,`Number`,`Time`,`Semester`,`Weekday`),
  KEY `School` (`School`,`Number`),
  KEY `GTID_Tutor` (`GTID_Tutor`,`Time`,`Semester`,`Weekday`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `hires`
--

INSERT INTO `hires` (`GTID_Undergraduate`, `GTID_Tutor`, `School`, `Number`, `Time`, `Semester`, `Weekday`) VALUES
('000000011', '000000004', 'ECE', 1000, '9am', 'FALL', 'Monday');

-- --------------------------------------------------------

--
-- Table structure for table `professor`
--

CREATE TABLE IF NOT EXISTS `professor` (
  `GTID` char(9) NOT NULL DEFAULT '',
  `Password` varchar(30) NOT NULL,
  PRIMARY KEY (`GTID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `professor`
--

INSERT INTO `professor` (`GTID`, `Password`) VALUES
('000000021', '000000021'),
('000000022', '000000022'),
('000000023', '000000023'),
('000000024', '000000024');

-- --------------------------------------------------------

--
-- Table structure for table `rates`
--

CREATE TABLE IF NOT EXISTS `rates` (
  `GTID_Undergraduate` char(9) NOT NULL DEFAULT '',
  `GTID_Tutor` char(9) NOT NULL DEFAULT '',
  `School` varchar(100) DEFAULT NULL,
  `Number` int(11) DEFAULT NULL,
  `Num_Evaluation` tinyint(4) DEFAULT NULL,
  `Desc_Evaluation` varchar(1000) DEFAULT NULL,
  PRIMARY KEY (`GTID_Undergraduate`,`GTID_Tutor`),
  KEY `GTID_Tutor` (`GTID_Tutor`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `rates`
--

INSERT INTO `rates` (`GTID_Undergraduate`, `GTID_Tutor`, `School`, `Number`, `Num_Evaluation`, `Desc_Evaluation`) VALUES
('000000011', '000000004', 'ECE', 1000, 3, 'Nice guy.'),
('000000012', '000000005', 'ECE', 2000, 1, 'Terrible.'),
('000000013', '000000007', 'ECE', 3000, 2, 'Bad.'),
('000000014', '000000008', 'ECE', 4000, 4, 'Very good.'),
('000000015', '000000009', 'ECE', 5000, 1, 'Pretty Bad.'),
('000000016', '000000010', 'ECE', 5000, 4, 'Excellent.');

-- --------------------------------------------------------

--
-- Table structure for table `recommends`
--

CREATE TABLE IF NOT EXISTS `recommends` (
  `GTID_Tutor` char(9) NOT NULL DEFAULT '',
  `GTID_Professor` char(9) NOT NULL DEFAULT '',
  `Num_Evaluation` tinyint(4) DEFAULT NULL,
  `Desc_Evaluation` varchar(1000) DEFAULT NULL,
  PRIMARY KEY (`GTID_Tutor`,`GTID_Professor`),
  KEY `GTID_Professor` (`GTID_Professor`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `recommends`
--

INSERT INTO `recommends` (`GTID_Tutor`, `GTID_Professor`, `Num_Evaluation`, `Desc_Evaluation`) VALUES
('000000004', '000000021', 3, 'Hard worker.'),
('000000005', '000000021', 3, 'Nice worker.'),
('000000007', '000000022', 3, 'Gentle worker.'),
('000000008', '000000023', 3, 'Soft worker.'),
('000000009', '000000024', 3, 'Real worker.'),
('000000010', '000000024', 3, 'Caring worker.');

-- --------------------------------------------------------

--
-- Table structure for table `student`
--

CREATE TABLE IF NOT EXISTS `student` (
  `GTID` char(9) NOT NULL DEFAULT '',
  `Password` varchar(30) NOT NULL,
  `Email` varchar(40) NOT NULL,
  `Name` varchar(100) NOT NULL,
  PRIMARY KEY (`GTID`),
  UNIQUE KEY `Email` (`Email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `student`
--

INSERT INTO `student` (`GTID`, `Password`, `Email`, `Name`) VALUES
('000000000', '000000000', 'ssans@gmail.com', 'September Sans'),
('000000001', '000000001', 'jsauer@gmail.com', 'Jerome Sauer'),
('000000002', '000000002', 'emachin@gmail.com', 'Elodia Machin'),
('000000003', '000000003', 'smazzota@gmail.com', 'Spring Mazzota'),
('000000004', '000000004', 'emcmaster@gmail.com', 'Elanor Mcmaster'),
('000000005', '000000005', 'dvigna@gmail.com', 'Darcy Vigna'),
('000000006', '000000006', 'csylvester@gmail.com', 'Cathi Sylvester'),
('000000007', '000000007', 'tfiller@gmail.com', 'Temple Filler'),
('000000008', '000000008', 'jcolosimo@gmail.com', 'Jen Colosimo'),
('000000009', '000000009', 'twoodall@gmail.com', 'Tama Woodall'),
('000000010', '000000010', 'tcammarata@gmail.com', 'Terra Cammarata'),
('000000011', '000000011', 'nmcneeley@gmail.com', 'Naoma Mcneeley'),
('000000012', '000000012', 'kduryea@gmail.com', 'Kandis Duryea'),
('000000013', '000000013', 'rmariott@gmail.com', 'Renaldo Mariott'),
('000000014', '000000014', 'cbeirne@gmail.com', 'Caryn Beirne'),
('000000015', '000000015', 'sstack@gmail.com', 'Shan Stack'),
('000000016', '000000016', 'hbolen@gmail.com', 'Hiedi Bolen'),
('000000017', '000000017', 'mleavell@gmail.com', 'Malvina Leavell'),
('000000018', '000000018', 'ldacosta@gmail.com', 'Lincoln Dacosta'),
('000000019', '000000019', 'cvasconcellos@gmail.com', 'Carly Vasconcellos');

-- --------------------------------------------------------

--
-- Table structure for table `tutor`
--

CREATE TABLE IF NOT EXISTS `tutor` (
  `GTID` char(9) NOT NULL DEFAULT '',
  `Password` varchar(30) NOT NULL,
  `Phone` char(10) DEFAULT NULL,
  `GPA` decimal(3,2) NOT NULL,
  PRIMARY KEY (`GTID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `tutor`
--

INSERT INTO `tutor` (`GTID`, `Password`, `Phone`, `GPA`) VALUES
('000000004', '000000004', '4045555555', '3.50'),
('000000005', '000000005', '4046666666', '3.60'),
('000000007', '000000007', '4047777777', '3.70'),
('000000008', '000000008', '4048888888', '3.80'),
('000000009', '000000009', '4049999999', '3.90'),
('000000010', '000000010', '4041010101', '3.10');

-- --------------------------------------------------------

--
-- Table structure for table `tutors`
--

CREATE TABLE IF NOT EXISTS `tutors` (
  `GTID_Tutor` char(9) NOT NULL DEFAULT '',
  `School` varchar(100) NOT NULL DEFAULT '',
  `Number` int(11) NOT NULL DEFAULT '0',
  `GTA` tinyint(1) DEFAULT NULL,
  PRIMARY KEY (`GTID_Tutor`,`School`,`Number`),
  KEY `School` (`School`,`Number`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `tutors`
--

INSERT INTO `tutors` (`GTID_Tutor`, `School`, `Number`, `GTA`) VALUES
('000000004', 'ECE', 1000, 1),
('000000005', 'ECE', 2000, 1),
('000000007', 'ECE', 3000, 0),
('000000008', 'ECE', 4000, 0),
('000000009', 'ECE', 5000, 0),
('000000010', 'ECE', 5000, 0);

-- --------------------------------------------------------

--
-- Table structure for table `tutor_time_slot`
--

CREATE TABLE IF NOT EXISTS `tutor_time_slot` (
  `GTID` char(9) NOT NULL DEFAULT '',
  `Time` varchar(8) NOT NULL DEFAULT '',
  `Semester` varchar(6) NOT NULL DEFAULT '',
  `Weekday` varchar(10) NOT NULL DEFAULT '',
  PRIMARY KEY (`GTID`,`Time`,`Semester`,`Weekday`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `tutor_time_slot`
--

INSERT INTO `tutor_time_slot` (`GTID`, `Time`, `Semester`, `Weekday`) VALUES
('000000004', '10am', 'FALL', 'Monday'),
('000000004', '10am', 'SPRING', 'Monday'),
('000000004', '11am', 'FALL', 'Monday'),
('000000004', '11am', 'SPRING', 'Monday'),
('000000004', '9am', 'FALL', 'Monday'),
('000000004', '9am', 'SPRING', 'Monday'),
('000000005', '1pm', 'FALL', 'Tuesday'),
('000000005', '1pm', 'SPRING', 'Tuesday'),
('000000005', '1pm', 'SUMMER', 'Tuesday'),
('000000005', '2pm', 'FALL', 'Tuesday'),
('000000005', '2pm', 'SPRING', 'Tuesday'),
('000000005', '2pm', 'SUMMER', 'Tuesday'),
('000000005', '3pm', 'FALL', 'Tuesday'),
('000000005', '3pm', 'SPRING', 'Tuesday'),
('000000005', '3pm', 'SUMMER', 'Tuesday'),
('000000007', '1pm', 'FALL', 'Tuesday'),
('000000007', '1pm', 'SPRING', 'Tuesday'),
('000000007', '1pm', 'SUMMER', 'Tuesday'),
('000000007', '2pm', 'FALL', 'Tuesday'),
('000000007', '2pm', 'SPRING', 'Tuesday'),
('000000007', '2pm', 'SUMMER', 'Tuesday'),
('000000007', '3pm', 'FALL', 'Tuesday'),
('000000007', '3pm', 'SPRING', 'Tuesday'),
('000000007', '3pm', 'SUMMER', 'Tuesday'),
('000000008', '2pm', 'FALL', 'Wednesday'),
('000000008', '2pm', 'SPRING', 'Wednesday'),
('000000008', '3pm', 'FALL', 'Wednesday'),
('000000008', '3pm', 'SPRING', 'Wednesday'),
('000000008', '4pm', 'FALL', 'Wednesday'),
('000000008', '4pm', 'SPRING', 'Wednesday'),
('000000009', '10am', 'FALL', 'Thursday'),
('000000009', '10am', 'SPRING', 'Thursday'),
('000000009', '11am', 'FALL', 'Thursday'),
('000000009', '11am', 'SPRING', 'Thursday'),
('000000009', '9am', 'FALL', 'Thursday'),
('000000009', '9am', 'SPRING', 'Thursday'),
('000000010', '1pm', 'FALL', 'Friday'),
('000000010', '1pm', 'SPRING', 'Friday'),
('000000010', '2pm', 'FALL', 'Friday'),
('000000010', '2pm', 'SPRING', 'Friday'),
('000000010', '3pm', 'FALL', 'Friday'),
('000000010', '3pm', 'SPRING', 'Friday');

-- --------------------------------------------------------

--
-- Table structure for table `undergraduate`
--

CREATE TABLE IF NOT EXISTS `undergraduate` (
  `GTID` char(9) NOT NULL DEFAULT '',
  `Password` varchar(30) NOT NULL,
  PRIMARY KEY (`GTID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `undergraduate`
--

INSERT INTO `undergraduate` (`GTID`, `Password`) VALUES
('000000000', '000000000'),
('000000006', '000000006'),
('000000007', '000000007'),
('000000008', '000000008'),
('000000009', '000000009'),
('000000010', '000000010'),
('000000011', '000000011'),
('000000012', '000000012'),
('000000013', '000000013'),
('000000014', '000000014'),
('000000015', '000000015'),
('000000016', '000000016'),
('000000017', '000000017'),
('000000018', '000000018'),
('000000019', '000000019');

--
-- Constraints for dumped tables
--

--
-- Constraints for table `graduate`
--
ALTER TABLE `graduate`
  ADD CONSTRAINT `graduate_ibfk_1` FOREIGN KEY (`GTID`) REFERENCES `student` (`GTID`);

--
-- Constraints for table `hires`
--
ALTER TABLE `hires`
  ADD CONSTRAINT `hires_ibfk_1` FOREIGN KEY (`GTID_Undergraduate`) REFERENCES `undergraduate` (`GTID`),
  ADD CONSTRAINT `hires_ibfk_2` FOREIGN KEY (`GTID_Tutor`) REFERENCES `tutor` (`GTID`),
  ADD CONSTRAINT `hires_ibfk_3` FOREIGN KEY (`School`, `Number`) REFERENCES `course` (`School`, `Number`),
  ADD CONSTRAINT `hires_ibfk_4` FOREIGN KEY (`GTID_Tutor`, `Time`, `Semester`, `Weekday`) REFERENCES `tutor_time_slot` (`GTID`, `Time`, `Semester`, `Weekday`);

--
-- Constraints for table `rates`
--
ALTER TABLE `rates`
  ADD CONSTRAINT `rates_ibfk_1` FOREIGN KEY (`GTID_Tutor`) REFERENCES `tutor` (`GTID`),
  ADD CONSTRAINT `rates_ibfk_2` FOREIGN KEY (`GTID_Undergraduate`) REFERENCES `undergraduate` (`GTID`);

--
-- Constraints for table `recommends`
--
ALTER TABLE `recommends`
  ADD CONSTRAINT `recommends_ibfk_1` FOREIGN KEY (`GTID_Tutor`) REFERENCES `tutor` (`GTID`),
  ADD CONSTRAINT `recommends_ibfk_2` FOREIGN KEY (`GTID_Professor`) REFERENCES `professor` (`GTID`);

--
-- Constraints for table `tutors`
--
ALTER TABLE `tutors`
  ADD CONSTRAINT `tutors_ibfk_1` FOREIGN KEY (`GTID_Tutor`) REFERENCES `tutor` (`GTID`),
  ADD CONSTRAINT `tutors_ibfk_2` FOREIGN KEY (`School`, `Number`) REFERENCES `course` (`School`, `Number`);

--
-- Constraints for table `tutor_time_slot`
--
ALTER TABLE `tutor_time_slot`
  ADD CONSTRAINT `tutor_time_slot_ibfk_1` FOREIGN KEY (`GTID`) REFERENCES `student` (`GTID`);

--
-- Constraints for table `undergraduate`
--
ALTER TABLE `undergraduate`
  ADD CONSTRAINT `undergraduate_ibfk_1` FOREIGN KEY (`GTID`) REFERENCES `student` (`GTID`);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
