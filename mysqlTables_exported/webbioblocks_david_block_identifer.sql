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
-- Table structure for table `david_block_identifer`
--

DROP TABLE IF EXISTS `david_block_identifer`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `david_block_identifer` (
  `id` int(11) NOT NULL,
  `identifier` mediumtext NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COMMENT='Holds pertinent information for the DAVID block such as available identifers';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `david_block_identifer`
--

LOCK TABLES `david_block_identifer` WRITE;
/*!40000 ALTER TABLE `david_block_identifer` DISABLE KEYS */;
INSERT INTO `david_block_identifer` VALUES (0,'AFFYMETRIX_3PRIME_IVT_ID'),(1,'AFFYMETRIX_EXON_GENE_ID'),(2,'AFFYMETRIX_SNP_ID'),(3,'AGILENT_CHIP_ID'),(4,'AGILENT_ID'),(5,'AGILENT_OLIGO_ID'),(6,'ENSEMBL_GENE_ID'),(7,'ENSEMBL_TRANSCRIPT_ID'),(8,'ENTREZ_GENE_ID'),(9,'FLYBASE_GENE_ID'),(10,'FLYBASE_TRANSCRIPT_ID'),(11,'GENBANK_ACCESSION'),(12,'GENOMIC_GI_ACCESSION'),(13,'GENPEPT_ACCESSION'),(14,'ILLUMINA_ID'),(15,'IPI_ID'),(16,'MGI_ID'),(17,'OFFICIAL_GENE_SYMBOL'),(18,'PFAM_ID'),(19,'PIR_ID'),(20,'PROTEIN_GI_ACCESSION'),(21,'REFSEQ_GENOMIC'),(22,'REFSEQ_MRNA'),(23,'REFSEQ_PROTEIN'),(24,'REFSEQ_RNA'),(25,'RGD_ID'),(26,'SGD_ID'),(27,'TAIR_ID'),(28,'UCSC_GENE_ID'),(29,'UNIGENE'),(30,'UNIPROT_ACCESSION'),(31,'UNIPROT_ID'),(32,'UNIREF100_ID'),(33,'WORMBASE_GENE_ID'),(34,'WORMPEP_ID'),(35,'ZFIN_ID'),(36,'NA');
/*!40000 ALTER TABLE `david_block_identifer` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2014-02-16 13:55:32
