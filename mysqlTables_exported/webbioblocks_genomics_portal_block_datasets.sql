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
-- Table structure for table `genomics_portal_block_datasets`
--

DROP TABLE IF EXISTS `genomics_portal_block_datasets`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `genomics_portal_block_datasets` (
  `portal_name` varchar(100) DEFAULT NULL,
  `portal_getstring` varchar(100) DEFAULT NULL,
  `description` longtext
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COMMENT='This table holds the available datasets for the genomics portal block.';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `genomics_portal_block_datasets`
--

LOCK TABLES `genomics_portal_block_datasets` WRITE;
/*!40000 ALTER TABLE `genomics_portal_block_datasets` DISABLE KEYS */;
INSERT INTO `genomics_portal_block_datasets` VALUES ('AHR','AHR','Gene expression and ChIP-chip datasets related to AHR-related gene expression regulation'),('BCERC','BCERC','Gene expression microarray datasets generated by the BCERC project (mostly private)'),('BPA Genomics','BPAGenomics','Genomics datasets assessing health effects of Bisphenol A'),('Breast Cancer','Breastcancer','Genome-scale datasets related to breast cancer'),('Cancer','Cancer','Cancer related genomics datasets'),('CGH','CGH','Comparative Genomics Hybridization datasets'),('CTSA','CTSA','Clinical and Translational Science Awards (CTSA) program'),('Development','Development','Genomics datasets related to stem cells and development'),('Encode ChIP-Seq','Encode_ChIP_Seq','Production phase ENCODE data from Genome Browser'),('Epigenomics','Epigenomics','Datasets assessing different epigenomics events on the whole-genome scale'),('GDS','GDS','Gene Expression Omnibus Datasets (GDS)'),('JRA Genomics','JRAGenomics','Juvenile Rheumatoid Arthritis (JRA)'),('MCF-7 Toxicogenomics','Mcf7Toxicogenomics','Toxicogenomics data for MCF-7 cell line'),('Predicted TF Binding Sites','PredictedTFBindingSites','Computationally predicted binding sites for human, mouse and rat promoters'),('Prostate Cancer','ProstateCancer','Genome-scale datasets related to prostate cancer'),('Reference','Reference','Uncategorized useful gene expression datasets'),('TCGA','TCGA','mRNA-seq data generated by The Cancer Genome Atlas project'),('Toxicogenomics','Toxicogenomics','A collection of toxicogenomics datasets'),('Transcription Factors','TranscriptionFactors','Genomics datasets pertaining to transcription factor regulatory targets');
/*!40000 ALTER TABLE `genomics_portal_block_datasets` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2014-02-16 13:55:36
