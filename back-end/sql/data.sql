-- phpMyAdmin SQL Dump
-- version 4.2.10
-- http://www.phpmyadmin.net
--
-- Host: localhost:8889
-- Generation Time: Apr 01, 2015 at 09:11 PM
-- Server version: 5.5.38
-- PHP Version: 5.6.2

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";

--
-- Database: `sightofc_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `Article`
--

CREATE TABLE `Article` (
  `pub_id` int(16) NOT NULL DEFAULT '0',
  `volume` int(4) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=big5;

-- --------------------------------------------------------

--
-- Table structure for table `Author`
--

CREATE TABLE `Author` (
  `auth_id` int(16) NOT NULL DEFAULT '0',
  `auth_name` varchar(64) DEFAULT NULL,
  `auth_date_of_birth` date DEFAULT NULL,
  `auth_cite_count` int(8) DEFAULT NULL,
  `auth_pub_count` int(8) DEFAULT NULL,
  `auth_interest` varchar(256) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=big5;

--
-- Dumping data for table `Author`
--

INSERT INTO `Author` (`auth_id`, `auth_name`, `auth_date_of_birth`, `auth_cite_count`, `auth_pub_count`, `auth_interest`) VALUES
(1, 'Thomas Baudel', NULL, NULL, NULL, NULL),
(2, 'Michel Beaudouin-Lafon', NULL, NULL, NULL, NULL),
(3, 'Scott Brave', NULL, NULL, NULL, NULL),
(4, 'William Buxton', NULL, NULL, NULL, NULL),
(5, 'Andrew Dahley', NULL, NULL, NULL, NULL),
(6, 'Paul Dourish', NULL, NULL, NULL, NULL),
(7, 'Stephen W Draper', NULL, NULL, NULL, NULL),
(8, 'James J Gibson', NULL, NULL, NULL, NULL),
(9, 'Matt Gorbet', NULL, NULL, NULL, NULL),
(10, 'James D Hollan', NULL, NULL, NULL, NULL),
(11, 'Thomas S. Huang', NULL, NULL, NULL, NULL),
(12, 'Edwin L Hutchins', NULL, NULL, NULL, NULL),
(13, 'Hiroshi Ishii', NULL, NULL, NULL, NULL),
(14, 'David Kirsh', NULL, NULL, NULL, NULL),
(15, 'Brenda Laurel', NULL, NULL, NULL, NULL),
(16, 'Meredith Ringel Morris', NULL, NULL, NULL, NULL),
(17, 'Jakob Nielsen', NULL, NULL, NULL, NULL),
(18, 'Donald A Norman', NULL, NULL, NULL, NULL),
(19, 'Vladimir I Pavlovic', NULL, NULL, NULL, NULL),
(20, 'Rajeev Sharma', NULL, NULL, NULL, NULL),
(21, 'B Shneiderman', NULL, NULL, NULL, NULL),
(22, 'Brygg Ullmer', NULL, NULL, NULL, NULL),
(23, 'E Wenger', NULL, NULL, NULL, NULL),
(24, 'Andrew D Wilson', NULL, NULL, NULL, NULL),
(25, 'Craig Wisneski', NULL, NULL, NULL, NULL),
(26, 'Jacob O Wobbrock', NULL, NULL, NULL, NULL),
(27, 'Paul Yarin', NULL, NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `Author_of`
--

CREATE TABLE `Author_of` (
  `pub_id` int(16) NOT NULL DEFAULT '0',
  `auth_id` int(16) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=big5;

--
-- Dumping data for table `Author_of`
--

INSERT INTO `Author_of` (`pub_id`, `auth_id`) VALUES
(1, 21),
(2, 10),
(2, 12),
(2, 18),
(3, 7),
(3, 18),
(4, 18),
(5, 17),
(6, 17),
(7, 18),
(8, 8),
(9, 8),
(10, 4),
(11, 18),
(12, 13),
(12, 22),
(13, 1),
(13, 2),
(14, 12),
(15, 18),
(16, 15),
(17, 23),
(18, 11),
(18, 19),
(18, 21),
(19, 16),
(19, 24),
(19, 26),
(20, 3),
(20, 5),
(20, 9),
(20, 13),
(20, 22),
(20, 25),
(20, 27),
(21, 13),
(21, 22),
(22, 6),
(23, 10),
(23, 12),
(23, 14),
(24, 18),
(25, 18),
(26, 1),
(27, 1),
(28, 1);

-- --------------------------------------------------------

--
-- Table structure for table `Book`
--

CREATE TABLE `Book` (
  `pub_id` int(16) NOT NULL DEFAULT '0',
  `version` int(2) DEFAULT NULL,
  `publisher` varchar(64) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=big5;

-- --------------------------------------------------------

--
-- Table structure for table `Cite`
--

CREATE TABLE `Cite` (
  `citee_id` int(16) NOT NULL DEFAULT '0',
  `citer_id` int(16) NOT NULL DEFAULT '0',
  `note_id` int(16) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=big5;

-- --------------------------------------------------------

--
-- Table structure for table `Location`
--

CREATE TABLE `Location` (
  `loc_id` int(16) NOT NULL DEFAULT '0',
  `loc_name` varchar(64) DEFAULT NULL,
  `loc_field` varchar(64) DEFAULT NULL,
  `loc_pub_count` int(8) DEFAULT NULL,
  `loc_cite_count` int(8) DEFAULT NULL,
  `loc_self_cite_count` int(8) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=big5;

--
-- Dumping data for table `Location`
--

INSERT INTO `Location` (`loc_id`, `loc_name`, `loc_field`, `loc_pub_count`, `loc_cite_count`, `loc_self_cite_count`) VALUES
(0, 'some loc', 'some field', 2345, 45, 3),
(1, 'loc 1', 'field 1', 454, 45, 2),
(2, 'loc 2', 'field 2', 5679, 456, 34);

-- --------------------------------------------------------

--
-- Table structure for table `Note`
--

CREATE TABLE `Note` (
  `note_id` int(16) NOT NULL DEFAULT '0',
  `note_content` varchar(2048) DEFAULT NULL,
  `note_date` date DEFAULT NULL,
  `note_rating` int(1) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=big5;

-- --------------------------------------------------------

--
-- Table structure for table `Proceeding`
--

CREATE TABLE `Proceeding` (
  `pub_id` int(16) NOT NULL DEFAULT '0',
  `pages` int(8) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=big5;

-- --------------------------------------------------------

--
-- Table structure for table `Publication`
--

CREATE TABLE `Publication` (
  `pub_id` int(16) NOT NULL DEFAULT '0',
  `pub_title` varchar(256) DEFAULT NULL,
  `pub_year` year(4) DEFAULT NULL,
  `pub_cite_count` int(8) DEFAULT NULL,
  `pub_ISBN` varchar(64) DEFAULT NULL,
  `pub_MSid` int(16) DEFAULT NULL,
  `loc_id` int(16) DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=big5;

--
-- Dumping data for table `Publication`
--

INSERT INTO `Publication` (`pub_id`, `pub_title`, `pub_year`, `pub_cite_count`, `pub_ISBN`, `pub_MSid`, `loc_id`) VALUES
(1, 'Direct Manipulation: A Step Beyongd Programming Languages', 1983, 725, '10.1109/MC.1983.1654471', 847553, 0),
(2, 'Direct Manipulation Interfaces', 1985, 329, '10.1207/s15327051hci0104_2', 46937326, 0),
(3, 'User-Centered System Design: New Perspectives in Human-Computer Interaction', 1986, 2659, '978-0898598728', 1302100, 0),
(4, 'The Design of Everyday Things', 2002, 7886, '978-0465050659', 1255320, 0),
(5, 'Usability Engineering', 1997, 12912, '978-0080520292', 696277, 0),
(6, 'Usability Inspection Methods', 1994, 675, '10.1145/259963.260531', 895867, 0),
(7, 'Affordance Conventions, and Design', 1999, 229, '10.1145/301153.301168', 869782, 0),
(8, 'The Ecological Approach to Visual Perception', 2013, 5559, NULL, 1282975, 0),
(9, 'The Theory of Affordances', 1977, 569, '', 1339562, 0),
(10, 'There''s More to Interaction Than Meets the Eye: Some Issues in Manual Input', 1986, 106, NULL, 3986894, 0),
(11, 'The Psychology of Everyday Things', 1988, 1589, NULL, 1250107, 0),
(12, 'Tangible Bits: Towards Seamless Interfaces between People, Bits, and Atoms', 1997, 1469, '10.1145/258549.258715', 282336, 0),
(13, 'Charade: Remote Control of Objects Using Free-hand Gestures', 1993, 150, '10.1145/159544.159562', 120374, 0),
(14, 'Cognition in the Wild', 1995, 2483, NULL, 1279547, 0),
(15, 'Cognitive Engineering', 1986, 2124, NULL, 1291082, 0),
(16, 'Computers as Theatre', 1993, 534, NULL, 39255926, 0),
(17, 'Artificial Intelligence and Tutoring Systems', 2004, 441, NULL, 2742963, 0),
(18, 'Visual Interpretation of Hand Gestures for Human-computer Interaction: A Review', 1997, 679, '10.1109/34.598226', 350092, 0),
(19, 'User-defined Gestures for Surface Computing', 2009, 53, '10.1145/1518701.1518866', 4702542, 0),
(20, 'Ambient Displays: Turning Architectual Space into an Interface between People and Digital Information', 1998, 235, '10.1007/3-540-69706-3_4', 274287, 0),
(21, 'Emerging Frameworks for Tangible User Interfaces', 2000, 421, '10.1147/sj.393.0915', 821649, 0),
(22, 'What We Talk about When We Talk about Context', 2004, 369, '10.1007/s00779-003-0253-8', 872733, 0),
(23, 'Distributed Cognition: Towards a New Foundation for Human-computer Interaction Research', 2000, 435, '10.1145/353485.353487', 174649, 0),
(24, 'Things that Make Us Smart: Defending Human Attributes in the Age of the Machine', 1993, 541, NULL, 1364793, 0),
(25, 'The Invisible Computer: Why Good Products Can Fail, The Personal Computer is So Complex, and Information Appliances are the Solution', 1998, 265, NULL, 39254645, 0);

-- --------------------------------------------------------

--
-- Table structure for table `Tag`
--

CREATE TABLE `Tag` (
  `tag_id` int(16) NOT NULL DEFAULT '0',
  `tag_content` varchar(64) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=big5;

-- --------------------------------------------------------

--
-- Table structure for table `Tag_of`
--

CREATE TABLE `Tag_of` (
  `pub_id` int(16) NOT NULL DEFAULT '0',
  `tag_id` int(16) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=big5;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `Article`
--
ALTER TABLE `Article`
 ADD PRIMARY KEY (`pub_id`);

--
-- Indexes for table `Author`
--
ALTER TABLE `Author`
 ADD PRIMARY KEY (`auth_id`);

--
-- Indexes for table `Author_of`
--
ALTER TABLE `Author_of`
 ADD PRIMARY KEY (`pub_id`,`auth_id`);

--
-- Indexes for table `Book`
--
ALTER TABLE `Book`
 ADD PRIMARY KEY (`pub_id`);

--
-- Indexes for table `Cite`
--
ALTER TABLE `Cite`
 ADD PRIMARY KEY (`citee_id`,`citer_id`);

--
-- Indexes for table `Location`
--
ALTER TABLE `Location`
 ADD PRIMARY KEY (`loc_id`);

--
-- Indexes for table `Note`
--
ALTER TABLE `Note`
 ADD PRIMARY KEY (`note_id`);

--
-- Indexes for table `Proceeding`
--
ALTER TABLE `Proceeding`
 ADD PRIMARY KEY (`pub_id`);

--
-- Indexes for table `Publication`
--
ALTER TABLE `Publication`
 ADD PRIMARY KEY (`pub_id`);

--
-- Indexes for table `Tag`
--
ALTER TABLE `Tag`
 ADD PRIMARY KEY (`tag_id`);

--
-- Indexes for table `Tag_of`
--
ALTER TABLE `Tag_of`
 ADD PRIMARY KEY (`pub_id`,`tag_id`);
