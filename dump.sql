-- MySQL dump 10.13  Distrib 5.7.16, for Linux (x86_64)
--
-- Host: localhost    Database: pricecheckerpro
-- ------------------------------------------------------
-- Server version	5.7.16-0ubuntu0.16.04.1

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
-- Current Database: `pricecheckerpro`
--

CREATE DATABASE /*!32312 IF NOT EXISTS*/ `pricecheckerpro` /*!40100 DEFAULT CHARACTER SET latin1 */;

USE `pricecheckerpro`;

--
-- Table structure for table `brands`
--

DROP TABLE IF EXISTS `brands`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `brands` (
  `brand_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(30) NOT NULL,
  `logo_url` varchar(50) NOT NULL,
  `domain` varchar(20) DEFAULT NULL,
  PRIMARY KEY (`brand_id`),
  UNIQUE KEY `brand_id` (`brand_id`),
  UNIQUE KEY `name` (`name`),
  KEY `name_2` (`name`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `brands`
--

LOCK TABLES `brands` WRITE;
/*!40000 ALTER TABLE `brands` DISABLE KEYS */;
INSERT INTO `brands` VALUES (3,'Apple','//logo.clearbit.com/apple.com?size=64',NULL),(4,'Pentel','//logo.clearbit.com/pentelpen.ru?size=64',NULL),(5,'Crest','//logo.clearbit.com/crest.com?size=64',NULL),(6,'Oral-B','//logo.clearbit.com/oralb.com?size=64',NULL),(7,'Anderson Watts Ltd','//logo.clearbit.com/?size=64',NULL),(8,'CLIF','//logo.clearbit.com/halfpasthuman.com?size=64',NULL);
/*!40000 ALTER TABLE `brands` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `merchants`
--

DROP TABLE IF EXISTS `merchants`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `merchants` (
  `merchant_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` int(30) NOT NULL,
  `logo_url` int(50) NOT NULL,
  `domain` varchar(40) NOT NULL,
  UNIQUE KEY `merchant_id` (`merchant_id`),
  UNIQUE KEY `merchant_id_2` (`merchant_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `merchants`
--

LOCK TABLES `merchants` WRITE;
/*!40000 ALTER TABLE `merchants` DISABLE KEYS */;
/*!40000 ALTER TABLE `merchants` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `products`
--

DROP TABLE IF EXISTS `products`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `products` (
  `product_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(100) NOT NULL,
  `ean` int(13) DEFAULT NULL,
  `upc` int(13) NOT NULL,
  `asin` char(10) DEFAULT NULL,
  `description` varchar(200) NOT NULL,
  `brand_id` bigint(20) unsigned NOT NULL,
  `model` varchar(20) DEFAULT NULL,
  `dimension` varchar(20) DEFAULT NULL,
  `weight` varchar(20) DEFAULT NULL,
  `currency` varchar(20) DEFAULT NULL,
  `image_url` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`product_id`),
  UNIQUE KEY `product_id` (`product_id`),
  UNIQUE KEY `upc` (`upc`),
  KEY `brand_id` (`brand_id`),
  KEY `upc_2` (`upc`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `products`
--

LOCK TABLES `products` WRITE;
/*!40000 ALTER TABLE `products` DISABLE KEYS */;
/*!40000 ALTER TABLE `products` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `user_track`
--

DROP TABLE IF EXISTS `user_track`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `user_track` (
  `user_id` int(11) NOT NULL,
  `upc` int(11) NOT NULL,
  `tracked_timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `user_track`
--

LOCK TABLES `user_track` WRITE;
/*!40000 ALTER TABLE `user_track` DISABLE KEYS */;
INSERT INTO `user_track` VALUES (1,12345,'2017-03-08 01:07:48'),(10152,6688866,'2017-03-09 22:49:42'),(10152,6688866,'2017-03-09 22:51:02'),(1,4546789,'2017-03-09 22:52:59'),(1,12345,'2017-03-12 23:41:14'),(1,12345,'2017-03-22 19:44:42'),(1,12345,'2017-03-22 19:44:43');
/*!40000 ALTER TABLE `user_track` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `users` (
  `user_id` int(11) NOT NULL DEFAULT '0',
  `email` varchar(30) NOT NULL,
  `name` varchar(40) DEFAULT NULL,
  `sex` char(1) DEFAULT NULL,
  `created_timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `modified_timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `last_accessed` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `picture` varchar(200) DEFAULT NULL,
  `locale` varchar(5) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users`
--

LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
INSERT INTO `users` VALUES (1,'btraas@gmail.com','Brayden Traas','m','2016-12-03 08:29:37','2017-03-22 20:04:33','2017-03-22 20:04:33','https://lh4.googleusercontent.com/-LBuEFgz0iwU/AAAAAAAAAAI/AAAAAAAAEwU/AIdCfoKeduM/s96-c/photo.jpg','en'),(10149,'brayden@tra.as','Brayden Traas',NULL,'2017-01-28 10:11:37','2017-02-11 08:28:35','2017-02-11 08:28:35','https://lh5.googleusercontent.com/-xYH2YpKL7Bs/AAAAAAAAAAI/AAAAAAAAAAA/ADPlhfKGjxBv7ilvo4A7mXA5BUMy_bQvmA/s96-c/photo.jpg','en'),(10150,'arroyomorris@yahoo.ca','Morris Arroyo',NULL,'2017-03-02 20:14:15','2017-03-02 20:14:15','2017-03-02 20:14:15','https://lh3.googleusercontent.com/-eN8wlvkRcvM/AAAAAAAAAAI/AAAAAAAAAAA/AAomvV3EAhlUCZ3wj65sO607G5OKZ5XdeA/s96-c/photo.jpg','en'),(10151,'liondesignsinc@gmail.com','Lion Designs',NULL,'2017-03-02 23:12:21','2017-03-02 23:12:21','2017-03-02 23:12:21','https://lh3.googleusercontent.com/-0V3ax-v5oQg/AAAAAAAAAAI/AAAAAAAAAAA/AAomvV1Nw0koi1iGsDhyxsd620L77mPVSg/s96-c/photo.jpg','en'),(10152,'kenttttt0@gmail.com','Kent Huang',NULL,'2017-03-02 23:12:32','2017-03-09 23:24:15','2017-03-09 23:24:15','https://lh3.googleusercontent.com/-9-r4Asp7vsQ/AAAAAAAAAAI/AAAAAAAAAAA/AAomvV30r0ef9bG7NvLQvbrTYUBf8xWKBg/s96-c/photo.jpg','en-CA'),(0,'danielcapacio@gmail.com','Daniel Capacio',NULL,'2017-03-12 04:34:31','2017-03-12 04:34:31','2017-03-12 04:34:31','https://lh4.googleusercontent.com/-YH0rDSPnTPY/AAAAAAAAAAI/AAAAAAAAADo/WKd1g0pLu_c/s96-c/photo.jpg','en'),(0,'zhaoliangbz@gmail.com','Liang Zhao',NULL,'2017-03-15 03:28:09','2017-03-20 19:14:54','2017-03-20 19:14:54','https://lh3.googleusercontent.com/-TtbU-VApozQ/AAAAAAAAAAI/AAAAAAAAAAA/AAomvV1JTd3aH7kCMSquC6UbJtiPno-fQg/s96-c/photo.jpg','en-CA'),(0,'sienamante@gmail.com','Siena Marie',NULL,'2017-03-18 17:15:53','2017-03-18 17:15:53','2017-03-18 17:15:53','https://lh4.googleusercontent.com/-zhaa0S7B7j4/AAAAAAAAAAI/AAAAAAAAEI8/MlP6vUbHA_s/s96-c/photo.jpg','en');
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

-- Dump completed on 2017-03-22 13:08:01
