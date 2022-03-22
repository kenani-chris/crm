-- MySQL dump 10.13  Distrib 8.0.16, for macos10.14 (x86_64)
--
-- Host: localhost    Database: hugotech
-- ------------------------------------------------------
-- Server version	5.7.26

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
-- Table structure for table `attendances`
--

DROP TABLE IF EXISTS `attendances`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
 SET character_set_client = utf8mb4 ;
CREATE TABLE `attendances` (
  `id` char(36) COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` char(36) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `lead_id` char(36) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `attendance_status` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `attendance_date` date DEFAULT NULL,
  `no_of_tasks` int(11) DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `attendances_user_id_foreign` (`user_id`),
  KEY `attendances_lead_id_foreign` (`lead_id`),
  CONSTRAINT `attendances_lead_id_foreign` FOREIGN KEY (`lead_id`) REFERENCES `users` (`id`),
  CONSTRAINT `attendances_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `attendances`
--

LOCK TABLES `attendances` WRITE;
/*!40000 ALTER TABLE `attendances` DISABLE KEYS */;
/*!40000 ALTER TABLE `attendances` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `catalog_histories`
--

DROP TABLE IF EXISTS `catalog_histories`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
 SET character_set_client = utf8mb4 ;
CREATE TABLE `catalog_histories` (
  `id` char(36) COLLATE utf8mb4_unicode_ci NOT NULL,
  `catalog_id` char(36) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `history_status` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `user_id` char(36) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `user_level` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `catalog_histories_user_id_foreign` (`user_id`),
  KEY `catalog_histories_catalog_id_foreign` (`catalog_id`),
  CONSTRAINT `catalog_histories_catalog_id_foreign` FOREIGN KEY (`catalog_id`) REFERENCES `catalogs` (`id`),
  CONSTRAINT `catalog_histories_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `catalog_histories`
--

LOCK TABLES `catalog_histories` WRITE;
/*!40000 ALTER TABLE `catalog_histories` DISABLE KEYS */;
/*!40000 ALTER TABLE `catalog_histories` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `catalog_w_c_w_s`
--

DROP TABLE IF EXISTS `catalog_w_c_w_s`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
 SET character_set_client = utf8mb4 ;
CREATE TABLE `catalog_w_c_w_s` (
  `id` char(36) COLLATE utf8mb4_unicode_ci NOT NULL,
  `missing_information` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `contacted` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `responded` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `catalog_id` char(36) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `analyst_id` char(36) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `user_id` char(36) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `inprogress_comments` text COLLATE utf8mb4_unicode_ci,
  `inprogress_date` date DEFAULT NULL,
  `completed_comments` text COLLATE utf8mb4_unicode_ci,
  `completed_date` date DEFAULT NULL,
  `supervisor_completed_comments` text COLLATE utf8mb4_unicode_ci,
  `supervisor_completed_date` date DEFAULT NULL,
  `supervisor_revision_comments` text COLLATE utf8mb4_unicode_ci,
  `supervisor_revision_date` date DEFAULT NULL,
  `supervisor_id` char(36) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `catalog_status` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT 'Pending',
  `analyst_comments` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `catalog_w_c_w_s_user_id_foreign` (`user_id`),
  KEY `catalog_w_c_w_s_analyst_id_foreign` (`analyst_id`),
  KEY `catalog_w_c_w_s_supervisor_id_foreign` (`supervisor_id`),
  KEY `catalog_w_c_w_s_catalog_id_foreign` (`catalog_id`),
  CONSTRAINT `catalog_w_c_w_s_analyst_id_foreign` FOREIGN KEY (`analyst_id`) REFERENCES `users` (`id`),
  CONSTRAINT `catalog_w_c_w_s_catalog_id_foreign` FOREIGN KEY (`catalog_id`) REFERENCES `catalogs` (`id`),
  CONSTRAINT `catalog_w_c_w_s_supervisor_id_foreign` FOREIGN KEY (`supervisor_id`) REFERENCES `users` (`id`),
  CONSTRAINT `catalog_w_c_w_s_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `catalog_w_c_w_s`
--

LOCK TABLES `catalog_w_c_w_s` WRITE;
/*!40000 ALTER TABLE `catalog_w_c_w_s` DISABLE KEYS */;
/*!40000 ALTER TABLE `catalog_w_c_w_s` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `catalogs`
--

DROP TABLE IF EXISTS `catalogs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
 SET character_set_client = utf8mb4 ;
CREATE TABLE `catalogs` (
  `id` char(36) COLLATE utf8mb4_unicode_ci NOT NULL,
  `brand_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `catalog_line_sheet` text COLLATE utf8mb4_unicode_ci,
  `product_photograph` text COLLATE utf8mb4_unicode_ci,
  `first_order_min` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `re_order_min` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `lead_time_days` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `special_instructions` text COLLATE utf8mb4_unicode_ci,
  `contact_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `year_established` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `based_in_city_street` text COLLATE utf8mb4_unicode_ci,
  `made_in` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `phone_number` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `instagram` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `stockists` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `brand_description` text COLLATE utf8mb4_unicode_ci,
  `lifestyle_images` text COLLATE utf8mb4_unicode_ci,
  `subject_line` text COLLATE utf8mb4_unicode_ci,
  `profile_pic` text COLLATE utf8mb4_unicode_ci,
  `values` text COLLATE utf8mb4_unicode_ci,
  `status` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'Pending',
  `user_id` char(36) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `sku_count` int(11) DEFAULT NULL,
  `explain_missing_info` text COLLATE utf8mb4_unicode_ci,
  `stall_date` date DEFAULT NULL,
  `unstall_date` date DEFAULT NULL,
  `brand_source` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `brand_token` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `catalogs_user_id_foreign` (`user_id`),
  CONSTRAINT `catalogs_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `catalogs`
--

LOCK TABLES `catalogs` WRITE;
/*!40000 ALTER TABLE `catalogs` DISABLE KEYS */;
/*!40000 ALTER TABLE `catalogs` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `migrations`
--

DROP TABLE IF EXISTS `migrations`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
 SET character_set_client = utf8mb4 ;
CREATE TABLE `migrations` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `migration` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=19 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `migrations`
--

LOCK TABLES `migrations` WRITE;
/*!40000 ALTER TABLE `migrations` DISABLE KEYS */;
INSERT INTO `migrations` VALUES (1,'2014_10_12_000000_create_users_table',1),(2,'2014_10_12_100000_create_password_resets_table',1),(3,'2020_09_11_100009_add_api_token_to_users',1),(9,'2020_10_08_121058_add_reporting_lead_to_users_table',1),(10,'2020_12_08_064732_create_catalogs_table',2),(11,'2020_12_09_071247_create_attendances_table',2),(13,'2020_12_09_100213_create_catalog_w_c_w_s_table',3),(15,'2020_12_17_073414_add_skucount_to_catalogs_table',4),(16,'2020_12_21_060028_add_brandtoken_to_catalogs_table',5),(18,'2020_12_22_064203_create_catalog_histories_table',6);
/*!40000 ALTER TABLE `migrations` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `password_resets`
--

DROP TABLE IF EXISTS `password_resets`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
 SET character_set_client = utf8mb4 ;
CREATE TABLE `password_resets` (
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  KEY `password_resets_email_index` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `password_resets`
--

LOCK TABLES `password_resets` WRITE;
/*!40000 ALTER TABLE `password_resets` DISABLE KEYS */;
/*!40000 ALTER TABLE `password_resets` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
 SET character_set_client = utf8mb4 ;
CREATE TABLE `users` (
  `id` char(36) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `level` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'Analyst',
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `api_token` varchar(80) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `reporting_lead` char(36) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_email_unique` (`email`),
  UNIQUE KEY `users_api_token_unique` (`api_token`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users`
--

LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
INSERT INTO `users` VALUES ('6e7368b7-d188-4b37-bb94-c407202ffda3','Duncan Mwirigi','dungates.gates198@gmail.com','Admin',NULL,'$2y$10$PEGwsSOcf2h.78VGu3ASb.mqNPsxn5F1wwLXLSKBkT30jd4f1IO4.','jTUJJrkRtgM4EmX6mdv2ARS3TOYKP7gAhscTeY5jzxc7eFFoBH7Ex1igwwnx',NULL,'2020-12-08 11:31:17','2020-12-08 11:31:17',NULL),('8657f201-dda2-40e8-89b6-f9ca79854725','Elon Musk','elonmusk@gmail.com','Analyst',NULL,'$2y$10$XaZYfyuBY09cccgCO7/kc.xN1AcOcmFscNTxIUWKaMmY/ETIAApha','8nTm29yP0uRHH2c0m7wMAHQLplMRpBI1WQnhAvuvWKD5A2BC1fFUitoWDaHc',NULL,'2020-12-09 04:11:25','2020-12-09 04:11:25','6e7368b7-d188-4b37-bb94-c407202ffda3'),('b2897864-6eea-41b8-a560-fd160b89e45d','Kim Martin','kimmartin@gmail.com','Analyst',NULL,'$2y$10$rfp43meqrS0KzLKMg7texeWzuf03I8FqMITgPc/daFslmdIprsNnK','EKjctWsEqIrmj1iAbLgcgXLWSQtRIQydAimCSJVOg7EyTiOutKdObisyWYeR',NULL,'2020-12-09 04:11:00','2020-12-09 04:11:00','6e7368b7-d188-4b37-bb94-c407202ffda3'),('b5ed8c5b-bc2e-4b23-b895-1226d91be06b','Jordan Kim','jordankim@gmail.com','Analyst',NULL,'$2y$10$T2yLhR7j38rxz3zJ/Ezo1.nI1NETppaorbHGjtj/Ga9xGkPcsz4J2','4zYhSmuGUTzZuxGyi3d9lGDBKrM6y8Fx4jKRsldx6WF4EB9rt5xWHVR9gFEb',NULL,'2020-12-08 12:14:06','2020-12-08 12:14:06','6e7368b7-d188-4b37-bb94-c407202ffda3'),('e5419b96-7466-4d49-8172-b28b42b7e982','James Ohinda','james@gmail.com','Analyst',NULL,'$2y$10$w3lASLyUzFpCtHxCyZKnAOex8fsZfCLCWYac55UJU2s1C.jgZcmyO','ZwMyithb3t1IpstijdMUJ8sN6v025USnxylSz9N9okrrMsWhW9SyM5t5VODi',NULL,'2020-12-08 12:15:12','2020-12-08 12:15:12','6e7368b7-d188-4b37-bb94-c407202ffda3');
/*!40000 ALTER TABLE `users` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping events for database 'hugotech'
--

--
-- Dumping routines for database 'hugotech'
--
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2020-12-22 14:19:29
