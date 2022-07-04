-- MariaDB dump 10.17  Distrib 10.4.11-MariaDB, for osx10.10 (x86_64)
--
-- Host: 127.0.0.1    Database: prwb_2021_a04
-- ------------------------------------------------------
-- Server version	10.4.11-MariaDB

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `board`
--

DROP TABLE IF EXISTS `board`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `board` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `Title` varchar(128) NOT NULL,
  `Owner` int(11) NOT NULL,
  `CreatedAt` datetime NOT NULL DEFAULT current_timestamp(),
  `ModifiedAt` datetime DEFAULT NULL,
  PRIMARY KEY (`ID`),
  UNIQUE KEY `Title` (`Title`),
  KEY `Owner` (`Owner`),
  CONSTRAINT `board_ibfk_1` FOREIGN KEY (`Owner`) REFERENCES `user` (`ID`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `board`
--

LOCK TABLES `board` WRITE;
/*!40000 ALTER TABLE `board` DISABLE KEYS */;
INSERT INTO `board` VALUES (1,'Projet PRWB',1,'2020-10-11 17:48:59',NULL),(2,'Projet ANC3',3,'2020-10-11 17:48:59',NULL),(4,'Boulot',5,'2020-11-25 18:54:53',NULL);
/*!40000 ALTER TABLE `board` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `card`
--

DROP TABLE IF EXISTS `card`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `card` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `Title` varchar(128) NOT NULL,
  `Body` text NOT NULL,
  `Position` int(11) NOT NULL DEFAULT 0,
  `CreatedAt` datetime NOT NULL DEFAULT current_timestamp(),
  `ModifiedAt` datetime DEFAULT NULL,
  `Author` int(11) NOT NULL,
  `Column` int(11) NOT NULL,
  `DueDate` date DEFAULT NULL,
  PRIMARY KEY (`ID`),
  KEY `Author` (`Author`),
  KEY `Column` (`Column`),
  CONSTRAINT `card_ibfk_1` FOREIGN KEY (`Author`) REFERENCES `user` (`ID`),
  CONSTRAINT `card_ibfk_2` FOREIGN KEY (`Column`) REFERENCES `Column` (`ID`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `card`
--

LOCK TABLES `card` WRITE;
/*!40000 ALTER TABLE `card` DISABLE KEYS */;
INSERT INTO `card` VALUES (1,'Analyse conceptuelle','Faire l\'analyse conceptuelle de la base de donn�es du projet.',1,'2020-10-11 17:56:40','2020-11-27 13:07:39',2,3,NULL),(2,'Mockups it�ration 1','Faire des prototypes d\'�crans pour les fonctionnalit�s de la premi?re it�ration.',0,'2020-10-11 17:56:40','2020-11-27 13:07:40',1,2,NULL),(3,'Ecrire �nonc� it�ration 1.','',1,'2020-10-11 17:58:37','2020-11-27 13:07:42',4,2,'2021-01-01'),(4,'Ech�ances IT1 !','D�cider des dates d\'�ch�ance pour la premi?re it�ration.',0,'2020-10-11 17:58:37','2020-11-27 13:07:34',1,3,NULL),(6,'Enonc� it�ration 2','',0,'2020-11-27 13:07:54',NULL,5,1,NULL);
/*!40000 ALTER TABLE `card` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `collaborate`
--

DROP TABLE IF EXISTS `collaborate`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `collaborate` (
  `Board` int(11) NOT NULL,
  `Collaborator` int(11) NOT NULL,
  PRIMARY KEY (`Board`,`Collaborator`),
  KEY `Collaborator` (`Collaborator`),
  CONSTRAINT `collaborate_ibfk_1` FOREIGN KEY (`Collaborator`) REFERENCES `User` (`ID`),
  CONSTRAINT `collaborate_ibfk_2` FOREIGN KEY (`Board`) REFERENCES `Board` (`ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `collaborate`
--

LOCK TABLES `collaborate` WRITE;
/*!40000 ALTER TABLE `collaborate` DISABLE KEYS */;
INSERT INTO `collaborate` VALUES (1,2),(1,4),(1,5),(2,1);
/*!40000 ALTER TABLE `collaborate` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `column`
--

DROP TABLE IF EXISTS `column`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `column` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `Title` varchar(128) NOT NULL,
  `Position` int(11) NOT NULL DEFAULT 0,
  `CreatedAt` datetime NOT NULL DEFAULT current_timestamp(),
  `ModifiedAt` datetime DEFAULT NULL,
  `Board` int(11) NOT NULL,
  PRIMARY KEY (`ID`),
  KEY `Board` (`Board`),
  CONSTRAINT `column_ibfk_1` FOREIGN KEY (`Board`) REFERENCES `board` (`ID`)
) ENGINE=InnoDB AUTO_INCREMENT=16 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `column`
--

LOCK TABLES `column` WRITE;
/*!40000 ALTER TABLE `column` DISABLE KEYS */;
INSERT INTO `column` VALUES (1,'A faire',0,'2020-10-11 17:51:59',NULL,1),(2,'En cours',1,'2020-10-11 17:51:59',NULL,1),(3,'Termin�',2,'2020-10-11 17:52:27',NULL,1),(4,'A faire',0,'2020-10-11 17:52:27',NULL,2),(5,'Fini',1,'2020-10-11 17:53:07',NULL,2),(6,'Abandonn�',2,'2020-10-11 17:53:07',NULL,2),(11,'Pas urgent...',0,'2020-11-25 18:55:06',NULL,4),(12,'Ne pas perdre de vue',1,'2020-11-25 18:55:17',NULL,4),(13,'Pour hier',2,'2020-11-25 18:55:32',NULL,4),(15,'Trop tard',3,'2020-11-25 18:56:11',NULL,4);
/*!40000 ALTER TABLE `column` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `comment`
--

DROP TABLE IF EXISTS `comment`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `comment` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `Body` text NOT NULL,
  `CreatedAt` datetime NOT NULL DEFAULT current_timestamp(),
  `ModifiedAt` datetime DEFAULT NULL,
  `Author` int(11) NOT NULL,
  `Card` int(11) NOT NULL,
  PRIMARY KEY (`ID`),
  KEY `Author` (`Author`),
  KEY `Card` (`Card`),
  CONSTRAINT `comment_ibfk_1` FOREIGN KEY (`Author`) REFERENCES `User` (`ID`),
  CONSTRAINT `comment_ibfk_2` FOREIGN KEY (`Card`) REFERENCES `Card` (`ID`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `comment`
--

LOCK TABLES `comment` WRITE;
/*!40000 ALTER TABLE `comment` DISABLE KEYS */;
INSERT INTO `comment` VALUES (1,'Ceci est un commentaire','2020-11-27 14:45:39',NULL,5,6),(2,'Voil? un autre commentaire','2020-11-27 14:46:02',NULL,1,6);
/*!40000 ALTER TABLE `comment` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `participate`
--

DROP TABLE IF EXISTS `participate`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `participate` (
  `Participant` int(11) NOT NULL,
  `Card` int(11) NOT NULL,
  PRIMARY KEY (`Participant`,`Card`),
  KEY `Card` (`Card`),
  CONSTRAINT `participate_ibfk_1` FOREIGN KEY (`Card`) REFERENCES `Card` (`ID`),
  CONSTRAINT `participate_ibfk_2` FOREIGN KEY (`Participant`) REFERENCES `User` (`ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `participate`
--

LOCK TABLES `participate` WRITE;
/*!40000 ALTER TABLE `participate` DISABLE KEYS */;
INSERT INTO `participate` VALUES (1,1),(5,1);
/*!40000 ALTER TABLE `participate` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `user`
--

DROP TABLE IF EXISTS `user`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `user` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `Mail` varchar(128) NOT NULL,
  `FullName` varchar(128) NOT NULL,
  `Password` varchar(256) NOT NULL,
  `RegisteredAt` datetime NOT NULL DEFAULT current_timestamp(),
  `Role` enum('user','admin') DEFAULT 'user',
  PRIMARY KEY (`ID`),
  UNIQUE KEY `Mail` (`Mail`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `user`
--

LOCK TABLES `user` WRITE;
/*!40000 ALTER TABLE `user` DISABLE KEYS */;
INSERT INTO `user` VALUES (1,'boverhaegen@epfc.eu','Boris Verhaegen','56ce92d1de4f05017cf03d6cd514d6d1','2020-10-11 17:46:19','admin'),(2,'bepenelle@epfc.eu','Beno�t Penelle','56ce92d1de4f05017cf03d6cd514d6d1','2020-10-11 17:46:19','admin'),(3,'brlacroix@epfc.eu','Bruno Lacroix','56ce92d1de4f05017cf03d6cd514d6d1','2020-10-11 17:47:20','user'),(4,'xapigeolet@epfc.eu','Xavier Pigeolet','56ce92d1de4f05017cf03d6cd514d6d1','2020-10-11 17:47:20','admin'),(5,'galagaffe@epfc.eu','Gaston Lagaffe','56ce92d1de4f05017cf03d6cd514d6d1','2020-11-25 18:46:55','user');
/*!40000 ALTER TABLE `user` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2021-02-07 18:18:23