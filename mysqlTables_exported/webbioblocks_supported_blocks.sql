CREATE DATABASE  IF NOT EXISTS `webbioblocks` /*!40100 DEFAULT CHARACTER SET latin1 */;
USE `webbioblocks`;
-- MySQL dump 10.13  Distrib 5.6.13, for Win32 (x86)
--
-- Host: localhost    Database: webbioblocks
-- ------------------------------------------------------
-- Server version	5.5.35-0ubuntu0.12.04.1

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
-- Table structure for table `supported_blocks`
--

DROP TABLE IF EXISTS `supported_blocks`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `supported_blocks` (
  `idsupported_bloks` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(45) DEFAULT NULL,
  `type` varchar(45) DEFAULT NULL COMMENT 'This column will hold if it is a logical block or a website data block (logical, website respectively).',
  `description` longtext,
  PRIMARY KEY (`idsupported_bloks`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=latin1 COMMENT='This table will hold the name and information for the supported blocks.';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `supported_blocks`
--

LOCK TABLES `supported_blocks` WRITE;
/*!40000 ALTER TABLE `supported_blocks` DISABLE KEYS */;
INSERT INTO `supported_blocks` VALUES (1,'Genomics Portal Block','website','Genomics portal is a block that links into the Genomics Portal website (www.eh3.us.edu/GenomicsPortal/)'),(2,'DAVID Block','website','DAVID is a block that links into the DAVID Bioinformatics database website (http://david.abcc.ncifcrf.gov/home.jsp)'),(3,'Intersect Block','logical','This block will allow you to take the output from other blocks and intersect them based on a set of criteria'),(4,'Generic Results Block','logical','This block will display the outputed information of a block when it is executed. It displays the outputed information when you when you click on the block.');
/*!40000 ALTER TABLE `supported_blocks` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2014-02-16 13:55:37
