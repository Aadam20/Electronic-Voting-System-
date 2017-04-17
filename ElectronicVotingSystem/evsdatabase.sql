-- phpMyAdmin SQL Dump
-- version 4.0.10deb1
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Apr 17, 2017 at 05:37 PM
-- Server version: 5.5.54-0ubuntu0.14.04.1
-- PHP Version: 5.5.9-1ubuntu4.21

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `evsdatabase`
--
CREATE DATABASE IF NOT EXISTS `evsdatabase` DEFAULT CHARACTER SET latin1 COLLATE latin1_swedish_ci;
USE `evsdatabase`;

-- --------------------------------------------------------

--
-- Table structure for table `adminList`
--

CREATE TABLE IF NOT EXISTS `adminList` (
  `adminID` int(11) NOT NULL,
  `password` varchar(200) NOT NULL,
  PRIMARY KEY (`password`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `adminList`
--

INSERT INTO `adminList` (`adminID`, `password`) VALUES
(1234, '1234'),
(0, 'Admin'),
(813006410, 'mercery1?');

-- --------------------------------------------------------

--
-- Table structure for table `mailinglist`
--

CREATE TABLE IF NOT EXISTS `mailinglist` (
  `RegistrationNumber` int(11) NOT NULL,
  `Constituency` varchar(100) NOT NULL,
  `Password` varchar(200) NOT NULL,
  `TimeOfRegistration` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`RegistrationNumber`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `mailinglist`
--

INSERT INTO `mailinglist` (`RegistrationNumber`, `Constituency`, `Password`, `TimeOfRegistration`) VALUES
(814001534, 'St. George North East', '8nxaB25aBv', '2017-04-13 10:15:13');

-- --------------------------------------------------------

--
-- Table structure for table `parties`
--

CREATE TABLE IF NOT EXISTS `parties` (
  `Party` varchar(200) NOT NULL,
  `Acronym` varchar(200) NOT NULL,
  `Color_Code` varchar(10) NOT NULL,
  PRIMARY KEY (`Acronym`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `parties`
--

INSERT INTO `parties` (`Party`, `Acronym`, `Color_Code`) VALUES
('National Democratic Congress', 'NDC', 'yellow'),
('New National Party', 'NNP', '#008000');

-- --------------------------------------------------------

--
-- Table structure for table `results`
--

CREATE TABLE IF NOT EXISTS `results` (
  `released` int(11) NOT NULL,
  `time_of_release` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`released`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `results`
--

INSERT INTO `results` (`released`, `time_of_release`) VALUES
(1, '2017-04-13 10:59:25');

-- --------------------------------------------------------

--
-- Table structure for table `voters`
--

CREATE TABLE IF NOT EXISTS `voters` (
  `RegistrationNumber` int(11) NOT NULL,
  `Surname` varchar(100) NOT NULL,
  `GivenNames` varchar(200) NOT NULL,
  `Sex` varchar(10) NOT NULL,
  `DateOfBirth` date NOT NULL,
  `CountryOfBirth` varchar(200) NOT NULL,
  `DateIssued` date NOT NULL,
  `ExpireDate` date NOT NULL,
  `HeightFeet` int(11) NOT NULL,
  `HeightInches` int(11) NOT NULL,
  `Voted` int(11) NOT NULL DEFAULT '0',
  `Constituency` varchar(200) NOT NULL,
  `Password` varchar(200) NOT NULL,
  `Phone` varchar(200) NOT NULL,
  `E-Mail` varchar(200) NOT NULL,
  PRIMARY KEY (`RegistrationNumber`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `voters`
--

INSERT INTO `voters` (`RegistrationNumber`, `Surname`, `GivenNames`, `Sex`, `DateOfBirth`, `CountryOfBirth`, `DateIssued`, `ExpireDate`, `HeightFeet`, `HeightInches`, `Voted`, `Constituency`, `Password`, `Phone`, `E-Mail`) VALUES
(814001534, 'Ahmad', 'Aadam', 'M', '1995-12-04', 'Grenada', '2015-04-13', '2025-04-13', 6, 2, 1, 'St. George North East', '6b06b3f0c160f0fac3ae412c656ac99095720859', '1(474)742-6372', 'AadamAhmad@gmail.com');

-- --------------------------------------------------------

--
-- Table structure for table `votes`
--

CREATE TABLE IF NOT EXISTS `votes` (
  `Constituency` varchar(100) NOT NULL,
  `NDC` int(11) NOT NULL DEFAULT '0',
  `NNP` int(11) NOT NULL DEFAULT '0',
  `NNPTraditional` int(11) NOT NULL DEFAULT '0',
  `NDCTraditional` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`Constituency`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `votes`
--

INSERT INTO `votes` (`Constituency`, `NDC`, `NNP`, `NNPTraditional`, `NDCTraditional`) VALUES
('St. Andrew North West', 0, 0, 34, 12),
('St. Andrew South East', 1, 0, 2, 3),
('St. Andrew South West', 0, 0, 0, 0),
('St. George North East', 0, 0, 4, 4),
('St. George North West', 0, 0, 22, 39),
('St. George South', 0, 0, 0, 0),
('St. George South East', 0, 0, 0, 0),
('St. John', 0, 0, 0, 0),
('St. Mark', 0, 0, 0, 0),
('St. Patrick East', 0, 0, 0, 0),
('St. Patrick West', 0, 0, 0, 0),
('Town of St. George', 0, 0, 0, 0);

-- --------------------------------------------------------

--
-- Table structure for table `votes_offline`
--

CREATE TABLE IF NOT EXISTS `votes_offline` (
  `Constituency` varchar(200) NOT NULL,
  `NNP` int(11) NOT NULL DEFAULT '0',
  `NDC` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`Constituency`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `votes_offline`
--

INSERT INTO `votes_offline` (`Constituency`, `NNP`, `NDC`) VALUES
('St. Andrew North West', 0, 0),
('St. Andrew South East', 0, 0),
('St. Andrew South West', 0, 0),
('St. George North East', 0, 0),
('St. George North West', 0, 0),
('St. George South', 0, 0),
('St. George South East', 0, 0),
('St. John', 11, 10),
('St. Mark', 12, 13),
('St. Patrick East', 0, 0),
('St. Patrick West', 0, 0),
('Town of St. George', 0, 0);

-- --------------------------------------------------------

--
-- Table structure for table `votes_online`
--

CREATE TABLE IF NOT EXISTS `votes_online` (
  `Constituency` varchar(200) NOT NULL,
  `NNP` int(11) NOT NULL DEFAULT '0',
  `NDC` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`Constituency`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `votes_online`
--

INSERT INTO `votes_online` (`Constituency`, `NNP`, `NDC`) VALUES
('St. Andrew North West', 0, 0),
('St. Andrew South East', 0, 0),
('St. Andrew South West', 0, 0),
('St. George North East', 2, 1),
('St. George North West', 0, 0),
('St. George South', 0, 0),
('St. George South East', 0, 0),
('St. John', 3, 4),
('St. Mark', 6, 5),
('St. Patrick East', 0, 0),
('St. Patrick West', 0, 0),
('Town of St. George', 0, 0);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
