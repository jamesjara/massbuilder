-- MySQL dump 10.13  Distrib 5.5.9, for Win32 (x86)
--
-- Host: 127.0.0.1    Database: massbuilder
-- ------------------------------------------------------
-- Server version	5.0.51b-community-nt-log

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
-- Not dumping tablespaces as no INFORMATION_SCHEMA.FILES table on this server
--

--
-- Current Database: `massbuilder`
--

CREATE DATABASE /*!32312 IF NOT EXISTS*/ `massbuilder` /*!40100 DEFAULT CHARACTER SET utf8 */;

USE `massbuilder`;

--
-- Table structure for table `audittrail`
--

DROP TABLE IF EXISTS `audittrail`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `audittrail` (
  `id` int(11) NOT NULL auto_increment,
  `datetime` datetime NOT NULL,
  `script` varchar(255) default NULL,
  `user` varchar(255) default NULL,
  `action` varchar(255) default NULL,
  `table` varchar(255) default NULL,
  `field` varchar(255) default NULL,
  `keyvalue` longtext,
  `oldvalue` longtext,
  `newvalue` longtext,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `audittrail`
--

LOCK TABLES `audittrail` WRITE;
/*!40000 ALTER TABLE `audittrail` DISABLE KEYS */;
INSERT INTO `audittrail` VALUES (1,'2012-12-08 00:54:45','/massbuilder/massbuilder_source/login.php','jamesjara','login','127.0.0.1','','','','');
/*!40000 ALTER TABLE `audittrail` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `domains`
--

DROP TABLE IF EXISTS `domains`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `domains` (
  `id_domains` int(11) NOT NULL auto_increment,
  `dominio` varchar(145) default NULL,
  PRIMARY KEY  (`id_domains`)
) ENGINE=MyISAM AUTO_INCREMENT=5 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `domains`
--

LOCK TABLES `domains` WRITE;
/*!40000 ALTER TABLE `domains` DISABLE KEYS */;
INSERT INTO `domains` VALUES (1,'www.examendemanejo.com'),(2,'cosevi.examendemanejo.com'),(3,'pais.cosevi.com'),(4,'test.cosevi.com');
/*!40000 ALTER TABLE `domains` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `entries`
--

DROP TABLE IF EXISTS `entries`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `entries` (
  `identries` int(11) NOT NULL auto_increment,
  `domain_id` int(11) default NULL,
  `hash_content` varchar(145) default NULL,
  `fuente` varchar(245) default NULL,
  `id_blogger` int(11) default NULL,
  `published` datetime default NULL,
  `updated` datetime default NULL,
  `categorias` longtext,
  `titulo` varchar(245) default NULL,
  `contenido` longtext,
  `live` varchar(245) default NULL,
  `onBlogger` int(11) default NULL,
  PRIMARY KEY  (`identries`)
) ENGINE=MyISAM AUTO_INCREMENT=7 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `entries`
--

LOCK TABLES `entries` WRITE;
/*!40000 ALTER TABLE `entries` DISABLE KEYS */;
INSERT INTO `entries` VALUES (1,2,'asd','asdas',12312312,NULL,NULL,NULL,'titulo','<p>\r\n	sdfsdf</p>','dddddddd',NULL),(2,2,'asd','asd',1,NULL,NULL,NULL,'titulo2',NULL,NULL,NULL),(3,2,NULL,NULL,NULL,NULL,NULL,NULL,'ad2',NULL,NULL,NULL),(4,2,NULL,NULL,NULL,NULL,NULL,NULL,'ad3',NULL,NULL,NULL),(5,1,'11111111111','blogger',12312312,'2012-11-06 00:00:00','2012-11-07 00:00:00','asd,qwe,zxc,wer','este es el ttulo largo con al descipcion de como uitilziar este sistem','<p>\r\n	este es el ttulo <strong>largo</strong> con al descipcion de <strong>como</strong> uitilziar este sistem</p>','sdfsdf',NULL),(6,2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL);
/*!40000 ALTER TABLE `entries` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tools_backups`
--

DROP TABLE IF EXISTS `tools_backups`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tools_backups` (
  `idtools_backups` int(11) NOT NULL auto_increment,
  `domain_id` varchar(45) default NULL,
  `data` longtext,
  `date` timestamp NULL default NULL,
  PRIMARY KEY  (`idtools_backups`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tools_backups`
--

LOCK TABLES `tools_backups` WRITE;
/*!40000 ALTER TABLE `tools_backups` DISABLE KEYS */;
INSERT INTO `tools_backups` VALUES (1,'2','daa','2012-11-27 05:00:00');
/*!40000 ALTER TABLE `tools_backups` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tools_translation`
--

DROP TABLE IF EXISTS `tools_translation`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tools_translation` (
  `idtools_translation` int(11) NOT NULL auto_increment,
  `domain_id` int(11) default NULL,
  `to_domain` varchar(245) default NULL,
  `media` longtext,
  `date` datetime default NULL,
  `lenguaje` varchar(245) default NULL,
  PRIMARY KEY  (`idtools_translation`)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tools_translation`
--

LOCK TABLES `tools_translation` WRITE;
/*!40000 ALTER TABLE `tools_translation` DISABLE KEYS */;
INSERT INTO `tools_translation` VALUES (1,1,'3',NULL,'2012-11-27 00:00:00','es'),(2,2,'4',NULL,'2012-11-28 00:00:00','es');
/*!40000 ALTER TABLE `tools_translation` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `userlevelpermissions`
--

DROP TABLE IF EXISTS `userlevelpermissions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `userlevelpermissions` (
  `userlevelid` int(11) NOT NULL,
  `tablename` varchar(255) NOT NULL,
  `permission` int(11) NOT NULL,
  PRIMARY KEY  (`userlevelid`,`tablename`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `userlevelpermissions`
--

LOCK TABLES `userlevelpermissions` WRITE;
/*!40000 ALTER TABLE `userlevelpermissions` DISABLE KEYS */;
INSERT INTO `userlevelpermissions` VALUES (0,'{3554BCCA-7E88-4E52-9661-DF55D75275C9}domains',0),(1,'{3554BCCA-7E88-4E52-9661-DF55D75275C9}domains',105),(0,'{3554BCCA-7E88-4E52-9661-DF55D75275C9}entries',0),(1,'{3554BCCA-7E88-4E52-9661-DF55D75275C9}entries',109),(0,'{3554BCCA-7E88-4E52-9661-DF55D75275C9}tools_translation',0),(1,'{3554BCCA-7E88-4E52-9661-DF55D75275C9}tools_translation',104),(0,'{3554BCCA-7E88-4E52-9661-DF55D75275C9}tools_backups',0),(1,'{3554BCCA-7E88-4E52-9661-DF55D75275C9}tools_backups',104),(0,'{3554BCCA-7E88-4E52-9661-DF55D75275C9}audittrail',0),(1,'{3554BCCA-7E88-4E52-9661-DF55D75275C9}audittrail',0),(0,'{3554BCCA-7E88-4E52-9661-DF55D75275C9}users',0),(1,'{3554BCCA-7E88-4E52-9661-DF55D75275C9}users',0),(0,'{3554BCCA-7E88-4E52-9661-DF55D75275C9}userlevelpermissions',0),(1,'{3554BCCA-7E88-4E52-9661-DF55D75275C9}userlevelpermissions',109),(0,'{3554BCCA-7E88-4E52-9661-DF55D75275C9}userlevels',0),(1,'{3554BCCA-7E88-4E52-9661-DF55D75275C9}userlevels',109);
/*!40000 ALTER TABLE `userlevelpermissions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `userlevels`
--

DROP TABLE IF EXISTS `userlevels`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `userlevels` (
  `userlevelid` int(11) NOT NULL,
  `userlevelname` varchar(255) NOT NULL,
  PRIMARY KEY  (`userlevelid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `userlevels`
--

LOCK TABLES `userlevels` WRITE;
/*!40000 ALTER TABLE `userlevels` DISABLE KEYS */;
INSERT INTO `userlevels` VALUES (-1,'Administrator'),(0,'Default'),(1,'Escritor');
/*!40000 ALTER TABLE `userlevels` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `users` (
  `idusers` int(11) NOT NULL auto_increment,
  `user` varchar(45) default NULL,
  `pass` varchar(45) default NULL,
  `email` varchar(145) default NULL,
  `activate` varchar(45) default NULL,
  `promo` text,
  `level` int(11) default NULL,
  PRIMARY KEY  (`idusers`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users`
--

LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
INSERT INTO `users` VALUES (1,'franciny','franciny','franciny@as.com',NULL,'a:1:{s:15:\"LoginRetryCount\";i:0;}',1);
/*!40000 ALTER TABLE `users` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2012-12-13 22:56:47
