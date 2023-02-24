-- MySQL dump 10.13  Distrib 8.0.13, for Win64 (x86_64)
--
-- Host: 127.0.0.1    Database: project_portfolio
-- ------------------------------------------------------
-- Server version	5.5.5-10.4.18-MariaDB

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
 SET NAMES utf8 ;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `project_archives`
--

DROP TABLE IF EXISTS `project_archives`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
 SET character_set_client = utf8mb4 ;
CREATE TABLE `project_archives` (
  `project_archive_id` int(11) NOT NULL AUTO_INCREMENT,
  `project_id` int(11) NOT NULL,
  `file_name` varchar(500) NOT NULL,
  `archive_type` int(11) NOT NULL,
  `archive_size` varchar(20) NOT NULL,
  `files_count` int(11) DEFAULT 0,
  `created_on` timestamp NULL DEFAULT current_timestamp(),
  `modified_on` timestamp NULL DEFAULT NULL,
  `active` smallint(6) DEFAULT 1,
  PRIMARY KEY (`project_archive_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `project_archives`
--

LOCK TABLES `project_archives` WRITE;
/*!40000 ALTER TABLE `project_archives` DISABLE KEYS */;
/*!40000 ALTER TABLE `project_archives` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `project_deleted`
--

DROP TABLE IF EXISTS `project_deleted`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
 SET character_set_client = utf8mb4 ;
CREATE TABLE `project_deleted` (
  `project_deleted_id` int(11) NOT NULL AUTO_INCREMENT,
  `project_id` int(11) NOT NULL,
  `deleted_folder` varchar(200) NOT NULL,
  `deleted_on` timestamp NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`project_deleted_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `project_deleted`
--

LOCK TABLES `project_deleted` WRITE;
/*!40000 ALTER TABLE `project_deleted` DISABLE KEYS */;
/*!40000 ALTER TABLE `project_deleted` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `project_designs`
--

DROP TABLE IF EXISTS `project_designs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
 SET character_set_client = utf8mb4 ;
CREATE TABLE `project_designs` (
  `project_design_id` int(11) NOT NULL AUTO_INCREMENT,
  `project_id` int(11) NOT NULL,
  `name` varchar(200) NOT NULL,
  `file_name` varchar(200) NOT NULL,
  `orientation` varchar(50) NOT NULL,
  `created_on` timestamp NULL DEFAULT current_timestamp(),
  `modified_on` timestamp NULL DEFAULT NULL,
  `active` smallint(6) DEFAULT 1,
  PRIMARY KEY (`project_design_id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;


--
-- Table structure for table `project_skills`
--

DROP TABLE IF EXISTS `project_skills`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
 SET character_set_client = utf8mb4 ;
CREATE TABLE `project_skills` (
  `project_skill_id` int(11) NOT NULL AUTO_INCREMENT,
  `project_type_id` int(11) NOT NULL,
  `skill_id` int(11) NOT NULL,
  `active` smallint(6) DEFAULT 1,
  `created_on` timestamp NULL DEFAULT current_timestamp(),
  `modified_on` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`project_skill_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `project_skills`
--

LOCK TABLES `project_skills` WRITE;
/*!40000 ALTER TABLE `project_skills` DISABLE KEYS */;
/*!40000 ALTER TABLE `project_skills` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `project_types`
--

DROP TABLE IF EXISTS `project_types`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
 SET character_set_client = utf8mb4 ;
CREATE TABLE `project_types` (
  `project_type_id` int(11) NOT NULL AUTO_INCREMENT,
  `type` varchar(100) NOT NULL,
  `active` smallint(6) NOT NULL DEFAULT 1,
  `created_on` timestamp NOT NULL DEFAULT current_timestamp(),
  `modified_on` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`project_type_id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `project_types`
--

LOCK TABLES `project_types` WRITE;
/*!40000 ALTER TABLE `project_types` DISABLE KEYS */;
INSERT INTO `project_types` VALUES (1,'Front End ',1,'2023-01-08 07:57:34',NULL),(2,'Design',1,'2023-01-08 07:57:34',NULL),(3,'Video',1,'2023-01-08 07:57:34',NULL);
/*!40000 ALTER TABLE `project_types` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `project_videos`
--

DROP TABLE IF EXISTS `project_videos`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
 SET character_set_client = utf8mb4 ;
CREATE TABLE `project_videos` (
  `project_video_id` int(11) NOT NULL AUTO_INCREMENT,
  `project_id` int(11) NOT NULL,
  `link` varchar(500) NOT NULL,
  `video_id` varchar(200) NOT NULL,
  `created_on` timestamp NULL DEFAULT current_timestamp(),
  `modified_on` timestamp NULL DEFAULT NULL,
  `active` smallint(6) DEFAULT 1,
  PRIMARY KEY (`project_video_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `project_videos`
--

LOCK TABLES `project_videos` WRITE;
/*!40000 ALTER TABLE `project_videos` DISABLE KEYS */;
/*!40000 ALTER TABLE `project_videos` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `project_visitors`
--

DROP TABLE IF EXISTS `project_visitors`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
 SET character_set_client = utf8mb4 ;
CREATE TABLE `project_visitors` (
  `project_visitors_id` int(11) NOT NULL AUTO_INCREMENT,
  `project_id` int(11) NOT NULL,
  `visitor_id` int(11) NOT NULL,
  PRIMARY KEY (`project_visitors_id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `project_visitors`
--

LOCK TABLES `project_visitors` WRITE;
/*!40000 ALTER TABLE `project_visitors` DISABLE KEYS */;
INSERT INTO `project_visitors` VALUES (1,2,405);
/*!40000 ALTER TABLE `project_visitors` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `projects`
--

DROP TABLE IF EXISTS `projects`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
 SET character_set_client = utf8mb4 ;
CREATE TABLE `projects` (
  `project_id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `title` varchar(200) NOT NULL,
  `url` varchar(200) NOT NULL,
  `project_type_id` int(11) NOT NULL,
  `description` varchar(2000) NOT NULL,
  `public` smallint(6) DEFAULT 0,
  `folder_name` varchar(200) NOT NULL,
  `created_on` timestamp NULL DEFAULT current_timestamp(),
  `modified_on` timestamp NULL DEFAULT NULL,
  `active` smallint(6) NOT NULL DEFAULT 1,
  `uploaded` smallint(6) NOT NULL,
  PRIMARY KEY (`project_id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `skill_types`
--

DROP TABLE IF EXISTS `skill_types`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
 SET character_set_client = utf8mb4 ;
CREATE TABLE `skill_types` (
  `skill_type_id` int(11) NOT NULL AUTO_INCREMENT,
  `type` varchar(200) NOT NULL,
  `active` smallint(2) NOT NULL DEFAULT 1,
  PRIMARY KEY (`skill_type_id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `skill_types`
--

LOCK TABLES `skill_types` WRITE;
/*!40000 ALTER TABLE `skill_types` DISABLE KEYS */;
INSERT INTO `skill_types` VALUES (1,'Programming',1);
/*!40000 ALTER TABLE `skill_types` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `skills`
--

DROP TABLE IF EXISTS `skills`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
 SET character_set_client = utf8mb4 ;
CREATE TABLE `skills` (
  `skill_id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(200) NOT NULL,
  `skill_type_id` int(11) NOT NULL,
  `created_on` timestamp NULL DEFAULT current_timestamp(),
  `modified_on` timestamp NULL DEFAULT NULL,
  `active` smallint(6) DEFAULT 1,
  PRIMARY KEY (`skill_id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `skills`
--

LOCK TABLES `skills` WRITE;
/*!40000 ALTER TABLE `skills` DISABLE KEYS */;
INSERT INTO `skills` VALUES (1,'HTML',1,'2023-01-08 07:41:52',NULL,1),(2,'CSS',1,'2023-01-08 07:41:52',NULL,1),(3,'JS',1,'2023-01-08 07:41:52',NULL,1);
/*!40000 ALTER TABLE `skills` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `social_media`
--

DROP TABLE IF EXISTS `social_media`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
 SET character_set_client = utf8mb4 ;
CREATE TABLE `social_media` (
  `social_media_id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `github` varchar(200) NOT NULL,
  `linkedin` varchar(200) NOT NULL,
  `twitter` varchar(200) NOT NULL,
  `codepen` varchar(200) NOT NULL,
  `created_on` timestamp NULL DEFAULT current_timestamp(),
  `modified_on` timestamp NULL DEFAULT NULL,
  `active` smallint(6) DEFAULT 1,
  PRIMARY KEY (`social_media_id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `user_skills`
--

DROP TABLE IF EXISTS `user_skills`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
 SET character_set_client = utf8mb4 ;
CREATE TABLE `user_skills` (
  `user_skill_id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `skill_id` int(11) NOT NULL,
  `created_on` timestamp NULL DEFAULT current_timestamp(),
  `modified_on` timestamp NULL DEFAULT NULL,
  `active` smallint(6) DEFAULT 1,
  PRIMARY KEY (`user_skill_id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;


--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
 SET character_set_client = utf8mb4 ;
CREATE TABLE `users` (
  `user_id` int(11) NOT NULL AUTO_INCREMENT,
  `full_name` varchar(200) NOT NULL,
  `username` varchar(200) NOT NULL,
  `email` varchar(200) NOT NULL,
  `website` varchar(200) NOT NULL,
  `company` varchar(200) NOT NULL,
  `location` varchar(500) NOT NULL,
  `bio` varchar(500) NOT NULL,
  `profile_picture` varchar(500) NOT NULL,
  `password` varchar(200) NOT NULL,
  `account_level` smallint(6) NOT NULL DEFAULT 0,
  `verified` smallint(6) NOT NULL DEFAULT 0,
  `private` smallint(6) NOT NULL DEFAULT 1,
  `disabled` smallint(6) NOT NULL DEFAULT 0,
  `created_on` timestamp NOT NULL DEFAULT current_timestamp(),
  `modified_on` timestamp NULL DEFAULT NULL,
  `active` smallint(6) NOT NULL DEFAULT 1,
  PRIMARY KEY (`user_id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;



--
-- Table structure for table `verifications`
--

DROP TABLE IF EXISTS `verifications`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
 SET character_set_client = utf8mb4 ;
CREATE TABLE `verifications` (
  `verification_id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `code` varchar(200) NOT NULL,
  `expire_on` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `verified_on` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `created_on` timestamp NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`verification_id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;



--
-- Table structure for table `visitors`
--

DROP TABLE IF EXISTS `visitors`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
 SET character_set_client = utf8mb4 ;
CREATE TABLE `visitors` (
  `visitor_id` int(11) NOT NULL AUTO_INCREMENT,
  `ip_address` varchar(200) NOT NULL,
  `city` varchar(200) DEFAULT NULL,
  `state` varchar(200) DEFAULT NULL,
  `country` varchar(50) DEFAULT NULL,
  `browser` varchar(2000) NOT NULL,
  `browser_version` varchar(50) NOT NULL,
  `os` varchar(200) DEFAULT NULL,
  `device` varchar(200) DEFAULT NULL,
  `page` varchar(100) NOT NULL,
  `url` varchar(500) NOT NULL,
  `reference` varchar(500) DEFAULT NULL,
  `visited_at` timestamp NULL DEFAULT current_timestamp(),
  `modified_on` timestamp NULL DEFAULT NULL,
  `active` smallint(6) DEFAULT 1,
  PRIMARY KEY (`visitor_id`)
) ENGINE=InnoDB AUTO_INCREMENT=437 DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;


/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2023-01-08 13:34:16
