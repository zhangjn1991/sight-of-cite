-- MySQL dump 10.13  Distrib 5.6.19, for osx10.7 (i386)
--
-- Host: 127.0.0.1    Database: sightofc_db
-- ------------------------------------------------------
-- Server version	5.5.38

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `Article`
--

DROP TABLE IF EXISTS `Article`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `Article` (
  `pub_id` int(16) NOT NULL DEFAULT '0',
  `volume` int(4) DEFAULT NULL,
  PRIMARY KEY (`pub_id`)
) ENGINE=InnoDB DEFAULT CHARSET=big5;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `Article`
--

LOCK TABLES `Article` WRITE;
/*!40000 ALTER TABLE `Article` DISABLE KEYS */;
/*!40000 ALTER TABLE `Article` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `Author`
--

DROP TABLE IF EXISTS `Author`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `Author` (
  `auth_id` int(16) NOT NULL DEFAULT '0',
  `auth_name` varchar(64) DEFAULT NULL,
  `auth_date_of_birth` date DEFAULT NULL,
  `auth_cite_count` int(8) DEFAULT NULL,
  `auth_pub_count` int(8) DEFAULT NULL,
  `auth_interest` varchar(256) DEFAULT NULL,
  PRIMARY KEY (`auth_id`)
) ENGINE=InnoDB DEFAULT CHARSET=big5;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `Author`
--

LOCK TABLES `Author` WRITE;
/*!40000 ALTER TABLE `Author` DISABLE KEYS */;
INSERT INTO `Author` VALUES (1,'Ben Shneiderman','1947-08-21',61502,NULL,'hci, information visualization, social media'),(2,'Donald A Norman','1935-12-25',69864,NULL,'hci, cognitive science, human-centered system design'),(3,'Edwin Hutchins',NULL,21144,NULL,'cognitive science, distributed congnition, ethnography'),(4,'James D Hollan',NULL,8442,76,'hci, artificial intelligence'),(5,'Stephen W Draper',NULL,1183,85,'hci, computer education, education'),(6,'Jackob Nielsen',NULL,9588,235,'hci, software engineering, methimatics'),(7,'William Buxton',NULL,6737,244,'hci, graphics, software engineering'),(8,'Hiroshi Ishii',NULL,5841,243,'hci, distributed and parallel computing, graphics'),(9,'Brygg Ullmer',NULL,3396,48,'hci, scientific computing, graphics'),(10,'Thomas Bandel',NULL,613,31,'hci, graphics'),(11,'James J Gibson',NULL,10465,116,'psychiatry and psychology, neuroscience, oncology'),(101,'test author','2015-03-03',123,456,'abcInterest'),(102,'test author Jr.','2015-03-12',657,43,'field 0'),(103,'test author Jr.II','2015-03-20',654,45,'field 1');
/*!40000 ALTER TABLE `Author` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `Author_of`
--

DROP TABLE IF EXISTS `Author_of`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `Author_of` (
  `pub_id` int(16) NOT NULL DEFAULT '0',
  `auth_id` int(16) NOT NULL DEFAULT '0',
  PRIMARY KEY (`pub_id`,`auth_id`)
) ENGINE=InnoDB DEFAULT CHARSET=big5;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `Author_of`
--

LOCK TABLES `Author_of` WRITE;
/*!40000 ALTER TABLE `Author_of` DISABLE KEYS */;
INSERT INTO `Author_of` VALUES (0,7),(7,0),(8,0),(9,0),(10,0),(10,1),(10,2);
/*!40000 ALTER TABLE `Author_of` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `Book`
--

DROP TABLE IF EXISTS `Book`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `Book` (
  `pub_id` int(16) NOT NULL DEFAULT '0',
  `version` int(2) DEFAULT NULL,
  `publisher` varchar(64) DEFAULT NULL,
  PRIMARY KEY (`pub_id`)
) ENGINE=InnoDB DEFAULT CHARSET=big5;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `Book`
--

LOCK TABLES `Book` WRITE;
/*!40000 ALTER TABLE `Book` DISABLE KEYS */;
/*!40000 ALTER TABLE `Book` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `Cite`
--

DROP TABLE IF EXISTS `Cite`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `Cite` (
  `citee_id` int(16) NOT NULL DEFAULT '0',
  `citer_id` int(16) NOT NULL DEFAULT '0',
  `note_id` int(16) DEFAULT NULL,
  PRIMARY KEY (`citee_id`,`citer_id`)
) ENGINE=InnoDB DEFAULT CHARSET=big5;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `Cite`
--

LOCK TABLES `Cite` WRITE;
/*!40000 ALTER TABLE `Cite` DISABLE KEYS */;
/*!40000 ALTER TABLE `Cite` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `Location`
--

DROP TABLE IF EXISTS `Location`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `Location` (
  `loc_id` int(16) NOT NULL DEFAULT '0',
  `loc_name` varchar(64) DEFAULT NULL,
  `loc_field` varchar(64) DEFAULT NULL,
  `loc_pub_count` int(8) DEFAULT NULL,
  `loc_cite_count` int(8) DEFAULT NULL,
  `loc_self_cite_count` int(8) DEFAULT NULL,
  PRIMARY KEY (`loc_id`)
) ENGINE=InnoDB DEFAULT CHARSET=big5;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `Location`
--

LOCK TABLES `Location` WRITE;
/*!40000 ALTER TABLE `Location` DISABLE KEYS */;
INSERT INTO `Location` VALUES (0,'some loc','some field',2345,45,3),(1,'loc 1','field 1',454,45,2),(2,'loc 2','field 2',5679,456,34);
/*!40000 ALTER TABLE `Location` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `Note`
--

DROP TABLE IF EXISTS `Note`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `Note` (
  `note_id` int(16) NOT NULL DEFAULT '0',
  `note_content` varchar(2048) DEFAULT NULL,
  `note_date` date DEFAULT NULL,
  `note_rating` int(1) DEFAULT NULL,
  PRIMARY KEY (`note_id`)
) ENGINE=InnoDB DEFAULT CHARSET=big5;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `Note`
--

LOCK TABLES `Note` WRITE;
/*!40000 ALTER TABLE `Note` DISABLE KEYS */;
/*!40000 ALTER TABLE `Note` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `Proceeding`
--

DROP TABLE IF EXISTS `Proceeding`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `Proceeding` (
  `pub_id` int(16) NOT NULL DEFAULT '0',
  `pages` int(8) DEFAULT NULL,
  PRIMARY KEY (`pub_id`)
) ENGINE=InnoDB DEFAULT CHARSET=big5;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `Proceeding`
--

LOCK TABLES `Proceeding` WRITE;
/*!40000 ALTER TABLE `Proceeding` DISABLE KEYS */;
/*!40000 ALTER TABLE `Proceeding` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `Publication`
--

DROP TABLE IF EXISTS `Publication`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `Publication` (
  `pub_id` int(16) NOT NULL DEFAULT '0',
  `pub_title` varchar(256) DEFAULT NULL,
  `pub_year` year(4) DEFAULT NULL,
  `pub_cite_count` int(8) DEFAULT NULL,
  `pub_ISBN` varchar(64) DEFAULT NULL,
  `pub_MSid` int(16) DEFAULT NULL,
  `loc_id` int(16) DEFAULT '0',
  PRIMARY KEY (`pub_id`)
) ENGINE=InnoDB DEFAULT CHARSET=big5;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `Publication`
--

LOCK TABLES `Publication` WRITE;
/*!40000 ALTER TABLE `Publication` DISABLE KEYS */;
INSERT INTO `Publication` VALUES (1,'Direct Manipulation: A Step Beyongd Programming Languages',1983,725,'10.1109/MC.1983.1654471',847553,0),(2,'Direct Manipulation Interfaces',1985,329,'10.1207/s15327051hci0104_2',46937326,0),(3,'User-Centered System Design: New Perspectives in Human-Computer Interaction',1986,2659,'978-0898598728',1302100,0),(4,'The Design of Everyday Things',2002,7886,'978-0465050659',1255320,0),(5,'Usability Engineering',1997,12912,'978-0080520292',696277,0),(6,'Usability Inspection Methods',1994,675,'10.1145/259963.260531',895867,0),(7,'Affordance Conventions, and Design',1999,229,'10.1145/301153.301168',869782,0),(8,'The Ecological Approach to Visual Perception',2013,5559,NULL,1282975,0),(9,'The Theory of Affordances',1977,569,NULL,1339562,0),(10,'There\'s More to Interaction Than Meets the Eye: Some Issues in Manual Input',1986,106,NULL,3986894,0),(11,'The Psychology of Everyday Things',1988,1589,NULL,1250107,0),(12,'Tangible Bits: Towards Seamless Interfaces between People, Bits, and Atoms',1997,1469,'10.1145/258549.258715',282336,0),(13,'Charade: Remote Control of Objects Using Free-hand Gestures',1993,150,'10.1145/159544.159562',120374,0),(14,'Cognition in the Wild',1995,2483,NULL,1279547,0),(15,'Cognitive Engineering',1986,2124,NULL,1291082,0),(16,'Computers as Theatre',1993,534,NULL,39255926,0),(17,'Artificial Intelligence and Tutoring Systems',2004,441,NULL,2742963,0),(18,'Visual Interpretation of Hand Gestures for Human-computer Interaction: A Review',1997,679,'10.1109/34.598226',350092,0),(19,'User-defined Gestures for Surface Computing',2009,53,'10.1145/1518701.1518866',4702542,0),(20,'Ambient Displays: Turning Architectual Space into an Interface between People and Digital Information',1998,235,'10.1007/3-540-69706-3_4',274287,0),(21,'Emerging Frameworks for Tangible User Interfaces',2000,421,'10.1147/sj.393.0915',821649,0),(22,'What We Talk about When We Talk about Context',2004,369,'10.1007/s00779-003-0253-8',872733,0),(23,'Distributed Cognition: Towards a New Foundation for Human-computer Interaction Research',2000,435,'10.1145/353485.353487',174649,0),(24,'Things that Make Us Smart: Defending Human Attributes in the Age of the Machine',1993,541,NULL,1364793,0),(25,'The Invisible Computer: Why Good Products Can Fail, The Personal Computer is So Complex, and Information Appliances are the Solution',1998,265,NULL,39254645,0),(101,'aoeuhtns',NULL,NULL,NULL,NULL,0),(102,'aoeu',NULL,NULL,NULL,NULL,0),(103,'test title 5',2015,100,'12348',NULL,0),(104,'aoeuoaeu',NULL,NULL,NULL,NULL,0),(105,'abc paper',2013,1001,'123123',NULL,0),(106,'ccccccc',2017,NULL,NULL,NULL,0),(107,'abc',2015,0,'',NULL,0),(108,'htnss',NULL,NULL,NULL,NULL,0),(109,'abc paper',2013,1001,'123123',NULL,0),(110,'abc paper',2013,1001,'123123',NULL,0),(111,'test title 5',2015,100,'12348',NULL,0),(112,'updated title 2',2015,102,'001-0000000000',NULL,0),(113,'test title 5',2015,100,'12348',NULL,0),(114,'test title 5',2015,100,'12348',NULL,0),(115,'test title 5',2015,100,'12348',NULL,0),(116,'test title 5',2015,100,'12348',NULL,0),(117,'test title 5',2015,100,'12348',NULL,0),(118,'test title 5',2015,0,'12347',NULL,0),(119,'test title 5',2015,100,'12348',NULL,0),(120,'test title 5',2015,100,'12348',NULL,0),(121,'test title 5',2015,100,'12348',NULL,0);
/*!40000 ALTER TABLE `Publication` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `Tag`
--

DROP TABLE IF EXISTS `Tag`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `Tag` (
  `tag_id` int(16) NOT NULL DEFAULT '0',
  `tag_content` varchar(64) DEFAULT NULL,
  PRIMARY KEY (`tag_id`)
) ENGINE=InnoDB DEFAULT CHARSET=big5;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `Tag`
--

LOCK TABLES `Tag` WRITE;
/*!40000 ALTER TABLE `Tag` DISABLE KEYS */;
/*!40000 ALTER TABLE `Tag` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `Tag_of`
--

DROP TABLE IF EXISTS `Tag_of`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `Tag_of` (
  `pub_id` int(16) NOT NULL DEFAULT '0',
  `tag_id` int(16) NOT NULL DEFAULT '0',
  PRIMARY KEY (`pub_id`,`tag_id`)
) ENGINE=InnoDB DEFAULT CHARSET=big5;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `Tag_of`
--

LOCK TABLES `Tag_of` WRITE;
/*!40000 ALTER TABLE `Tag_of` DISABLE KEYS */;
/*!40000 ALTER TABLE `Tag_of` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2015-04-01  2:34:43
