-- MySQL dump 10.13  Distrib 5.1.41, for debian-linux-gnu (i486)
--
-- Host: 10.121.1.253    Database: oldlit
-- ------------------------------------------------------
-- Server version	5.0.32-Debian_7etch6-log

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
-- Table structure for table `poll_check`
--

DROP TABLE IF EXISTS `poll_check`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `poll_check` (
  `pollid` int(11) NOT NULL default '0',
  `ip` varchar(20) NOT NULL default '',
  `time` varchar(14) NOT NULL default ''
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `poll_check`
--

LOCK TABLES `poll_check` WRITE;
/*!40000 ALTER TABLE `poll_check` DISABLE KEYS */;
INSERT INTO `poll_check` VALUES (0,'127.0.1.1','1276715730');
/*!40000 ALTER TABLE `poll_check` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `poll_data`
--

DROP TABLE IF EXISTS `poll_data`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `poll_data` (
  `pollid` int(11) NOT NULL default '0',
  `polltext` varchar(50) NOT NULL default '',
  `votecount` int(11) NOT NULL default '0',
  `voteid` int(11) NOT NULL default '0',
  `status` varchar(6) default NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `poll_data`
--

LOCK TABLES `poll_data` WRITE;
/*!40000 ALTER TABLE `poll_data` DISABLE KEYS */;
INSERT INTO `poll_data` VALUES (2,'',0,4,NULL),(2,'',0,3,NULL),(2,'No',0,2,NULL),(2,'Si',0,1,NULL),(1,'Si, ma con fasce di riduzione',0,3,NULL),(1,'',0,5,NULL),(1,'',0,4,NULL),(1,'Si, 10 euro per tutti.',1,2,NULL),(1,'No, voglio che rimanga invariata.',0,1,NULL),(1,'',0,10,NULL),(1,'',0,9,NULL),(1,'',0,8,NULL),(1,'',0,7,NULL),(1,'',0,6,NULL),(2,'',0,5,NULL),(2,'',0,6,NULL),(2,'',0,7,NULL),(2,'',0,8,NULL),(2,'',0,9,NULL),(2,'',0,10,NULL),(3,'Si',1,1,NULL),(3,'No',0,2,NULL),(3,'',0,3,NULL),(3,'',0,4,NULL),(3,'',0,5,NULL),(3,'',0,6,NULL),(3,'',0,7,NULL),(3,'',0,8,NULL),(3,'',0,9,NULL),(3,'',0,10,NULL);
/*!40000 ALTER TABLE `poll_data` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `poll_desc`
--

DROP TABLE IF EXISTS `poll_desc`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `poll_desc` (
  `pollid` int(11) NOT NULL default '0',
  `polltitle` varchar(100) NOT NULL default '',
  `timestamp` datetime NOT NULL default '0000-00-00 00:00:00',
  `votecount` mediumint(9) NOT NULL default '0',
  `STATUS` varchar(6) default NULL,
  PRIMARY KEY  (`pollid`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `poll_desc`
--

LOCK TABLES `poll_desc` WRITE;
/*!40000 ALTER TABLE `poll_desc` DISABLE KEYS */;
INSERT INTO `poll_desc` VALUES (1,'Vuoi variare la quota di iscrizione?','2010-06-16 14:36:33',0,'active'),(2,'Vuoi diminuire gli anni di mandato del consiglio direttivo da 2 a 1?','2010-06-16 14:37:15',0,NULL),(3,'Vuoi aumentare da 3 a 5 il numero delle cariche del consiglio direttivo?','2010-06-16 14:37:51',0,NULL);
/*!40000 ALTER TABLE `poll_desc` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2010-06-16 21:31:06
