-- MySQL dump 10.13  Distrib 8.0.44, for macos26.0 (arm64)
--
-- Host: localhost    Database: souldaires
-- ------------------------------------------------------
-- Server version	8.3.0

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!50503 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `blogs`
--

DROP TABLE IF EXISTS `blogs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `blogs` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint unsigned NOT NULL,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `slug` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `body` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `cover_image` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` enum('draft','published') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'draft',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `blogs_slug_unique` (`slug`),
  KEY `blogs_user_id_foreign` (`user_id`),
  CONSTRAINT `blogs_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `blogs`
--

LOCK TABLES `blogs` WRITE;
/*!40000 ALTER TABLE `blogs` DISABLE KEYS */;
/*!40000 ALTER TABLE `blogs` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `cache`
--

DROP TABLE IF EXISTS `cache`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `cache` (
  `key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `value` mediumtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` int NOT NULL,
  PRIMARY KEY (`key`),
  KEY `cache_expiration_index` (`expiration`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cache`
--

LOCK TABLES `cache` WRITE;
/*!40000 ALTER TABLE `cache` DISABLE KEYS */;
INSERT INTO `cache` VALUES ('laravel-cache-spatie.permission.cache','a:3:{s:5:\"alias\";a:0:{}s:11:\"permissions\";a:0:{}s:5:\"roles\";a:0:{}}',1776267043);
/*!40000 ALTER TABLE `cache` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `cache_locks`
--

DROP TABLE IF EXISTS `cache_locks`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `cache_locks` (
  `key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `owner` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` int NOT NULL,
  PRIMARY KEY (`key`),
  KEY `cache_locks_expiration_index` (`expiration`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cache_locks`
--

LOCK TABLES `cache_locks` WRITE;
/*!40000 ALTER TABLE `cache_locks` DISABLE KEYS */;
/*!40000 ALTER TABLE `cache_locks` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `chapters`
--

DROP TABLE IF EXISTS `chapters`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `chapters` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `series_id` bigint unsigned NOT NULL,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `body` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `chapter_order` int NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `chapters_series_id_foreign` (`series_id`),
  CONSTRAINT `chapters_series_id_foreign` FOREIGN KEY (`series_id`) REFERENCES `series` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `chapters`
--

LOCK TABLES `chapters` WRITE;
/*!40000 ALTER TABLE `chapters` DISABLE KEYS */;
/*!40000 ALTER TABLE `chapters` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `comments`
--

DROP TABLE IF EXISTS `comments`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `comments` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint unsigned NOT NULL,
  `content_id` bigint unsigned NOT NULL,
  `comment` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `comments_user_id_foreign` (`user_id`),
  KEY `comments_content_id_foreign` (`content_id`),
  CONSTRAINT `comments_content_id_foreign` FOREIGN KEY (`content_id`) REFERENCES `contents` (`id`) ON DELETE CASCADE,
  CONSTRAINT `comments_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `comments`
--

LOCK TABLES `comments` WRITE;
/*!40000 ALTER TABLE `comments` DISABLE KEYS */;
/*!40000 ALTER TABLE `comments` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `contents`
--

DROP TABLE IF EXISTS `contents`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `contents` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint unsigned NOT NULL,
  `type` enum('series','story','poem','quote') COLLATE utf8mb4_unicode_ci NOT NULL,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `slug` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `cover_image` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` enum('draft','published') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'draft',
  `is_paid` tinyint(1) NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `contents_slug_unique` (`slug`),
  KEY `contents_user_id_foreign` (`user_id`),
  CONSTRAINT `contents_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `contents`
--

LOCK TABLES `contents` WRITE;
/*!40000 ALTER TABLE `contents` DISABLE KEYS */;
/*!40000 ALTER TABLE `contents` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `contest_entries`
--

DROP TABLE IF EXISTS `contest_entries`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `contest_entries` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `contest_id` bigint unsigned NOT NULL,
  `user_id` bigint unsigned NOT NULL,
  `submission` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `contest_entries_contest_id_foreign` (`contest_id`),
  KEY `contest_entries_user_id_foreign` (`user_id`),
  CONSTRAINT `contest_entries_contest_id_foreign` FOREIGN KEY (`contest_id`) REFERENCES `contests` (`id`) ON DELETE CASCADE,
  CONSTRAINT `contest_entries_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `contest_entries`
--

LOCK TABLES `contest_entries` WRITE;
/*!40000 ALTER TABLE `contest_entries` DISABLE KEYS */;
/*!40000 ALTER TABLE `contest_entries` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `contests`
--

DROP TABLE IF EXISTS `contests`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `contests` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint unsigned NOT NULL,
  `type` enum('series','story','poem','quote') COLLATE utf8mb4_unicode_ci NOT NULL,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `slug` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `cover_image` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` enum('draft','published') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'draft',
  `is_paid` tinyint(1) NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `contests_slug_unique` (`slug`),
  KEY `contests_user_id_foreign` (`user_id`),
  CONSTRAINT `contests_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `contests`
--

LOCK TABLES `contests` WRITE;
/*!40000 ALTER TABLE `contests` DISABLE KEYS */;
/*!40000 ALTER TABLE `contests` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `earn_orders`
--

DROP TABLE IF EXISTS `earn_orders`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `earn_orders` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint unsigned NOT NULL,
  `total_amount` decimal(10,2) NOT NULL,
  `status` enum('pending','completed') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pending',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `earn_orders_user_id_foreign` (`user_id`),
  CONSTRAINT `earn_orders_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `earn_orders`
--

LOCK TABLES `earn_orders` WRITE;
/*!40000 ALTER TABLE `earn_orders` DISABLE KEYS */;
/*!40000 ALTER TABLE `earn_orders` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `earn_payments`
--

DROP TABLE IF EXISTS `earn_payments`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `earn_payments` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint unsigned NOT NULL,
  `amount` decimal(10,2) NOT NULL,
  `status` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `earn_payments_user_id_foreign` (`user_id`),
  CONSTRAINT `earn_payments_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `earn_payments`
--

LOCK TABLES `earn_payments` WRITE;
/*!40000 ALTER TABLE `earn_payments` DISABLE KEYS */;
/*!40000 ALTER TABLE `earn_payments` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `earn_submissions`
--

DROP TABLE IF EXISTS `earn_submissions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `earn_submissions` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `earn_order_id` bigint unsigned NOT NULL,
  `task_id` bigint unsigned NOT NULL,
  `proof_link` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` enum('pending','approved','rejected') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pending',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `earn_submissions_earn_order_id_foreign` (`earn_order_id`),
  KEY `earn_submissions_task_id_foreign` (`task_id`),
  CONSTRAINT `earn_submissions_earn_order_id_foreign` FOREIGN KEY (`earn_order_id`) REFERENCES `earn_orders` (`id`) ON DELETE CASCADE,
  CONSTRAINT `earn_submissions_task_id_foreign` FOREIGN KEY (`task_id`) REFERENCES `earn_tasks` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `earn_submissions`
--

LOCK TABLES `earn_submissions` WRITE;
/*!40000 ALTER TABLE `earn_submissions` DISABLE KEYS */;
/*!40000 ALTER TABLE `earn_submissions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `earn_tasks`
--

DROP TABLE IF EXISTS `earn_tasks`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `earn_tasks` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `reward_amount` decimal(10,2) NOT NULL,
  `platform` enum('instagram','youtube','review') COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `earn_tasks`
--

LOCK TABLES `earn_tasks` WRITE;
/*!40000 ALTER TABLE `earn_tasks` DISABLE KEYS */;
/*!40000 ALTER TABLE `earn_tasks` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `failed_jobs`
--

DROP TABLE IF EXISTS `failed_jobs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `failed_jobs` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `uuid` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `connection` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `queue` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `exception` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `failed_jobs`
--

LOCK TABLES `failed_jobs` WRITE;
/*!40000 ALTER TABLE `failed_jobs` DISABLE KEYS */;
/*!40000 ALTER TABLE `failed_jobs` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `followers`
--

DROP TABLE IF EXISTS `followers`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `followers` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `follower_id` bigint unsigned NOT NULL,
  `following_id` bigint unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `followers_follower_id_following_id_unique` (`follower_id`,`following_id`),
  KEY `followers_following_id_foreign` (`following_id`),
  CONSTRAINT `followers_follower_id_foreign` FOREIGN KEY (`follower_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  CONSTRAINT `followers_following_id_foreign` FOREIGN KEY (`following_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=25 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `followers`
--

LOCK TABLES `followers` WRITE;
/*!40000 ALTER TABLE `followers` DISABLE KEYS */;
INSERT INTO `followers` VALUES (1,1,7,'2026-03-14 22:59:29','2026-03-14 22:59:29'),(2,8,1,'2026-03-14 22:59:29','2026-03-14 22:59:29'),(3,6,8,'2026-03-14 22:59:29','2026-03-14 22:59:29'),(4,4,2,'2026-03-14 22:59:29','2026-03-14 22:59:29'),(5,7,4,'2026-03-14 22:59:29','2026-03-14 22:59:29'),(6,3,9,'2026-03-14 22:59:29','2026-03-14 22:59:29'),(8,2,5,'2026-03-14 22:59:29','2026-03-14 22:59:29'),(9,8,4,'2026-03-14 22:59:29','2026-03-14 22:59:29'),(10,4,9,'2026-03-14 22:59:29','2026-03-14 22:59:29'),(11,1,2,'2026-03-14 22:59:29','2026-03-14 22:59:29'),(12,9,6,'2026-03-14 22:59:29','2026-03-14 22:59:29'),(13,8,7,'2026-03-14 22:59:29','2026-03-14 22:59:29'),(16,9,3,'2026-03-14 22:59:29','2026-03-14 22:59:29'),(17,3,5,'2026-03-14 22:59:29','2026-03-14 22:59:29'),(19,6,3,'2026-03-14 22:59:29','2026-03-14 22:59:29'),(20,2,9,'2026-03-14 22:59:29','2026-03-14 22:59:29'),(21,6,5,'2026-03-14 22:59:29','2026-03-14 22:59:29'),(22,8,5,'2026-03-14 22:59:29','2026-03-14 22:59:29'),(23,4,1,'2026-03-14 22:59:29','2026-03-14 22:59:29'),(24,10,7,NULL,NULL);
/*!40000 ALTER TABLE `followers` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `job_batches`
--

DROP TABLE IF EXISTS `job_batches`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `job_batches` (
  `id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `total_jobs` int NOT NULL,
  `pending_jobs` int NOT NULL,
  `failed_jobs` int NOT NULL,
  `failed_job_ids` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `options` mediumtext COLLATE utf8mb4_unicode_ci,
  `cancelled_at` int DEFAULT NULL,
  `created_at` int NOT NULL,
  `finished_at` int DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `job_batches`
--

LOCK TABLES `job_batches` WRITE;
/*!40000 ALTER TABLE `job_batches` DISABLE KEYS */;
/*!40000 ALTER TABLE `job_batches` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `jobs`
--

DROP TABLE IF EXISTS `jobs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `jobs` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `queue` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `attempts` tinyint unsigned NOT NULL,
  `reserved_at` int unsigned DEFAULT NULL,
  `available_at` int unsigned NOT NULL,
  `created_at` int unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `jobs_queue_index` (`queue`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `jobs`
--

LOCK TABLES `jobs` WRITE;
/*!40000 ALTER TABLE `jobs` DISABLE KEYS */;
/*!40000 ALTER TABLE `jobs` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `libraries`
--

DROP TABLE IF EXISTS `libraries`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `libraries` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint unsigned NOT NULL,
  `story_id` bigint unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `libraries_user_id_story_id_unique` (`user_id`,`story_id`),
  KEY `libraries_story_id_foreign` (`story_id`),
  CONSTRAINT `libraries_story_id_foreign` FOREIGN KEY (`story_id`) REFERENCES `stories` (`id`) ON DELETE CASCADE,
  CONSTRAINT `libraries_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=20 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `libraries`
--

LOCK TABLES `libraries` WRITE;
/*!40000 ALTER TABLE `libraries` DISABLE KEYS */;
INSERT INTO `libraries` VALUES (7,1,13,'2026-03-14 22:59:29','2026-03-14 22:59:29'),(8,1,14,'2026-03-14 22:59:29','2026-03-14 22:59:29'),(9,2,14,'2026-03-14 22:59:29','2026-03-14 22:59:29'),(10,1,15,'2026-03-14 22:59:29','2026-03-14 22:59:29'),(11,2,15,'2026-03-14 22:59:29','2026-03-14 22:59:29'),(12,3,15,'2026-03-14 22:59:29','2026-03-14 22:59:29'),(13,1,17,'2026-03-14 22:59:29','2026-03-14 22:59:29'),(14,2,17,'2026-03-14 22:59:29','2026-03-14 22:59:29'),(15,3,17,'2026-03-14 22:59:29','2026-03-14 22:59:29'),(16,1,18,'2026-03-14 22:59:29','2026-03-14 22:59:29'),(17,10,18,'2026-03-14 23:11:37','2026-03-14 23:11:37'),(19,10,24,'2026-03-20 23:32:56','2026-03-20 23:32:56');
/*!40000 ALTER TABLE `libraries` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `migrations`
--

DROP TABLE IF EXISTS `migrations`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `migrations` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `migration` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=43 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `migrations`
--

LOCK TABLES `migrations` WRITE;
/*!40000 ALTER TABLE `migrations` DISABLE KEYS */;
INSERT INTO `migrations` VALUES (1,'0001_01_01_000000_create_users_table',1),(2,'0001_01_01_000001_create_cache_table',1),(3,'0001_01_01_000002_create_jobs_table',1),(4,'2026_01_30_174433_create_contents_table',1),(5,'2026_01_30_174433_create_followers_table',1),(6,'2026_01_30_174433_create_series_table',1),(7,'2026_01_30_174433_create_views_table',1),(8,'2026_01_30_174433_create_votes_table',1),(9,'2026_01_30_174434_create_blogs_table',1),(10,'2026_01_30_174434_create_earn_orders_table',1),(11,'2026_01_30_174434_create_earn_payments_table',1),(12,'2026_01_30_174434_create_earn_tasks_table',1),(13,'2026_01_30_174434_create_promotion_packages_table',1),(14,'2026_01_30_174434_create_promotions_table',1),(15,'2026_01_30_174434_create_reviews_table',1),(16,'2026_01_30_174435_create_chapters_table',1),(17,'2026_01_30_174435_create_contests_table',1),(18,'2026_01_30_174435_create_services_table',1),(19,'2026_01_30_174437_create_earn_submissions_table',1),(20,'2026_01_30_174439_create_comments_table',1),(21,'2026_01_30_174445_create_contest_entries_table',1),(22,'2026_01_30_174495_create_service_orders_table',1),(23,'2026_01_30_174599_create_service_payments_table',1),(24,'2026_01_30_274434_create_promotion_payments_table',1),(25,'2026_02_01_130635_update_users_table',1),(26,'2026_02_01_130805_create_permission_tables',1),(27,'2026_03_01_070612_add_auth_fields_to_users_table',1),(28,'2026_03_01_071144_create_personal_access_tokens_table',1),(29,'2026_03_02_144433_add_reset_otp_to_users_table',1),(30,'2026_03_02_145235_modify_reset_otp_column',1),(31,'2026_03_15_100000_create_stories_table',1),(32,'2026_03_15_100001_create_story_chapters_table',1),(33,'2026_03_15_100002_create_story_likes_table',1),(34,'2026_03_15_100003_create_story_comments_table',1),(35,'2026_03_15_100004_create_libraries_table',1),(36,'2026_03_15_100005_create_story_reads_table',1),(37,'2026_03_15_100006_create_story_reports_table',1),(38,'2026_03_16_090000_add_writing_experience_fields_to_stories_table',2),(39,'2026_03_16_090100_add_editor_fields_to_story_chapters_table',2),(40,'2026_03_16_090200_add_selection_fields_to_story_comments_table',2),(41,'2026_03_16_090300_make_story_comment_body_nullable',3),(42,'2026_03_16_090400_extend_story_type_enum_with_poems',4);
/*!40000 ALTER TABLE `migrations` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `model_has_permissions`
--

DROP TABLE IF EXISTS `model_has_permissions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `model_has_permissions` (
  `permission_id` bigint unsigned NOT NULL,
  `model_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `model_id` bigint unsigned NOT NULL,
  PRIMARY KEY (`permission_id`,`model_id`,`model_type`),
  KEY `model_has_permissions_model_id_model_type_index` (`model_id`,`model_type`),
  CONSTRAINT `model_has_permissions_permission_id_foreign` FOREIGN KEY (`permission_id`) REFERENCES `permissions` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `model_has_permissions`
--

LOCK TABLES `model_has_permissions` WRITE;
/*!40000 ALTER TABLE `model_has_permissions` DISABLE KEYS */;
/*!40000 ALTER TABLE `model_has_permissions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `model_has_roles`
--

DROP TABLE IF EXISTS `model_has_roles`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `model_has_roles` (
  `role_id` bigint unsigned NOT NULL,
  `model_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `model_id` bigint unsigned NOT NULL,
  PRIMARY KEY (`role_id`,`model_id`,`model_type`),
  KEY `model_has_roles_model_id_model_type_index` (`model_id`,`model_type`),
  CONSTRAINT `model_has_roles_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `model_has_roles`
--

LOCK TABLES `model_has_roles` WRITE;
/*!40000 ALTER TABLE `model_has_roles` DISABLE KEYS */;
INSERT INTO `model_has_roles` VALUES (1,'App\\Models\\User',1),(2,'App\\Models\\User',2),(2,'App\\Models\\User',3),(2,'App\\Models\\User',4),(2,'App\\Models\\User',5),(2,'App\\Models\\User',6),(2,'App\\Models\\User',7),(2,'App\\Models\\User',8),(2,'App\\Models\\User',9);
/*!40000 ALTER TABLE `model_has_roles` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `password_reset_tokens`
--

DROP TABLE IF EXISTS `password_reset_tokens`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `password_reset_tokens`
--

LOCK TABLES `password_reset_tokens` WRITE;
/*!40000 ALTER TABLE `password_reset_tokens` DISABLE KEYS */;
/*!40000 ALTER TABLE `password_reset_tokens` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `permissions`
--

DROP TABLE IF EXISTS `permissions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `permissions` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `guard_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `permissions_name_guard_name_unique` (`name`,`guard_name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `permissions`
--

LOCK TABLES `permissions` WRITE;
/*!40000 ALTER TABLE `permissions` DISABLE KEYS */;
/*!40000 ALTER TABLE `permissions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `personal_access_tokens`
--

DROP TABLE IF EXISTS `personal_access_tokens`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `personal_access_tokens` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `tokenable_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `tokenable_id` bigint unsigned NOT NULL,
  `name` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(64) COLLATE utf8mb4_unicode_ci NOT NULL,
  `abilities` text COLLATE utf8mb4_unicode_ci,
  `last_used_at` timestamp NULL DEFAULT NULL,
  `expires_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `personal_access_tokens_token_unique` (`token`),
  KEY `personal_access_tokens_tokenable_type_tokenable_id_index` (`tokenable_type`,`tokenable_id`),
  KEY `personal_access_tokens_expires_at_index` (`expires_at`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `personal_access_tokens`
--

LOCK TABLES `personal_access_tokens` WRITE;
/*!40000 ALTER TABLE `personal_access_tokens` DISABLE KEYS */;
INSERT INTO `personal_access_tokens` VALUES (1,'App\\Models\\User',10,'auth_token','3a801bd4b1f47737bb35ed4be36200b8018ed2c4f27ee14009069f846d1b671e','[\"*\"]',NULL,NULL,'2026-03-14 23:02:08','2026-03-14 23:02:08'),(2,'App\\Models\\User',10,'auth_token','80d561f5d963c4bed86d6024e784e4b91fadce6625a70b6d0cbecbee5da1c9ad','[\"*\"]',NULL,NULL,'2026-03-14 23:07:45','2026-03-14 23:07:45'),(3,'App\\Models\\User',10,'auth_token','f3bf94724a84257e9bba2b623b3967cc75d1e8a1b347e2a57c048a42b6fc5a27','[\"*\"]',NULL,NULL,'2026-03-15 00:08:41','2026-03-15 00:08:41'),(4,'App\\Models\\User',10,'auth_token','b08e679f5eea6ea2689e2d1619409e67392b4468d5cb39657c68238b43e2c957','[\"*\"]',NULL,NULL,'2026-03-15 08:53:12','2026-03-15 08:53:12'),(5,'App\\Models\\User',10,'auth_token','c7c91392e4a2226cb24377afdb44a414fd043190bd4d074a8089e4dc76edfa69','[\"*\"]',NULL,NULL,'2026-03-16 02:00:51','2026-03-16 02:00:51'),(6,'App\\Models\\User',10,'auth_token','38318bbef6fcf559465735a8e97e08e4104e3ff1a23090460e055a20dfd1b313','[\"*\"]',NULL,NULL,'2026-03-20 22:37:23','2026-03-20 22:37:23'),(7,'App\\Models\\User',10,'auth_token','56ffe7963f9834cccdbab464b84d8533792869443b21a09bf5944d4667e75bdd','[\"*\"]',NULL,NULL,'2026-03-21 23:24:05','2026-03-21 23:24:05'),(8,'App\\Models\\User',11,'auth_token','105b4f4b912abce7e23cfeaf489ad2511dcfcdc34e104dc6287b672c52867b1a','[\"*\"]',NULL,NULL,'2026-04-08 00:24:05','2026-04-08 00:24:05'),(9,'App\\Models\\User',10,'auth_token','3750309e41631a12e4cc8f0bf847cd6bf0e2cbf2f5c55eae20897ecba85eb0f4','[\"*\"]',NULL,NULL,'2026-04-14 10:00:18','2026-04-14 10:00:18');
/*!40000 ALTER TABLE `personal_access_tokens` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `promotion_packages`
--

DROP TABLE IF EXISTS `promotion_packages`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `promotion_packages` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `type` enum('book','brand') COLLATE utf8mb4_unicode_ci NOT NULL,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `duration_days` int NOT NULL,
  `features` json DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `promotion_packages`
--

LOCK TABLES `promotion_packages` WRITE;
/*!40000 ALTER TABLE `promotion_packages` DISABLE KEYS */;
/*!40000 ALTER TABLE `promotion_packages` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `promotion_payments`
--

DROP TABLE IF EXISTS `promotion_payments`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `promotion_payments` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `promotion_id` bigint unsigned NOT NULL,
  `payment_id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `amount` decimal(10,2) NOT NULL,
  `status` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `promotion_payments_promotion_id_foreign` (`promotion_id`),
  CONSTRAINT `promotion_payments_promotion_id_foreign` FOREIGN KEY (`promotion_id`) REFERENCES `promotions` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `promotion_payments`
--

LOCK TABLES `promotion_payments` WRITE;
/*!40000 ALTER TABLE `promotion_payments` DISABLE KEYS */;
/*!40000 ALTER TABLE `promotion_payments` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `promotions`
--

DROP TABLE IF EXISTS `promotions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `promotions` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint unsigned NOT NULL,
  `content_id` bigint unsigned DEFAULT NULL,
  `package_id` bigint unsigned NOT NULL,
  `status` enum('pending','approved','rejected') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pending',
  `starts_at` datetime DEFAULT NULL,
  `ends_at` datetime DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `promotions_user_id_foreign` (`user_id`),
  KEY `promotions_content_id_foreign` (`content_id`),
  KEY `promotions_package_id_foreign` (`package_id`),
  CONSTRAINT `promotions_content_id_foreign` FOREIGN KEY (`content_id`) REFERENCES `contents` (`id`) ON DELETE SET NULL,
  CONSTRAINT `promotions_package_id_foreign` FOREIGN KEY (`package_id`) REFERENCES `promotion_packages` (`id`),
  CONSTRAINT `promotions_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `promotions`
--

LOCK TABLES `promotions` WRITE;
/*!40000 ALTER TABLE `promotions` DISABLE KEYS */;
/*!40000 ALTER TABLE `promotions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `reviews`
--

DROP TABLE IF EXISTS `reviews`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `reviews` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint unsigned NOT NULL,
  `content_id` bigint unsigned NOT NULL,
  `rating` tinyint NOT NULL,
  `review` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `reviews_user_id_foreign` (`user_id`),
  KEY `reviews_content_id_foreign` (`content_id`),
  CONSTRAINT `reviews_content_id_foreign` FOREIGN KEY (`content_id`) REFERENCES `contents` (`id`) ON DELETE CASCADE,
  CONSTRAINT `reviews_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `reviews`
--

LOCK TABLES `reviews` WRITE;
/*!40000 ALTER TABLE `reviews` DISABLE KEYS */;
/*!40000 ALTER TABLE `reviews` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `role_has_permissions`
--

DROP TABLE IF EXISTS `role_has_permissions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `role_has_permissions` (
  `permission_id` bigint unsigned NOT NULL,
  `role_id` bigint unsigned NOT NULL,
  PRIMARY KEY (`permission_id`,`role_id`),
  KEY `role_has_permissions_role_id_foreign` (`role_id`),
  CONSTRAINT `role_has_permissions_permission_id_foreign` FOREIGN KEY (`permission_id`) REFERENCES `permissions` (`id`) ON DELETE CASCADE,
  CONSTRAINT `role_has_permissions_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `role_has_permissions`
--

LOCK TABLES `role_has_permissions` WRITE;
/*!40000 ALTER TABLE `role_has_permissions` DISABLE KEYS */;
/*!40000 ALTER TABLE `role_has_permissions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `roles`
--

DROP TABLE IF EXISTS `roles`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `roles` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `guard_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `roles_name_guard_name_unique` (`name`,`guard_name`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `roles`
--

LOCK TABLES `roles` WRITE;
/*!40000 ALTER TABLE `roles` DISABLE KEYS */;
INSERT INTO `roles` VALUES (1,'admin','web','2026-03-14 22:59:27','2026-03-14 22:59:27'),(2,'writer','web','2026-03-14 22:59:27','2026-03-14 22:59:27');
/*!40000 ALTER TABLE `roles` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `series`
--

DROP TABLE IF EXISTS `series`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `series` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `content_id` bigint unsigned NOT NULL,
  `genre` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `is_completed` tinyint(1) NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `series_content_id_foreign` (`content_id`),
  CONSTRAINT `series_content_id_foreign` FOREIGN KEY (`content_id`) REFERENCES `contents` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `series`
--

LOCK TABLES `series` WRITE;
/*!40000 ALTER TABLE `series` DISABLE KEYS */;
/*!40000 ALTER TABLE `series` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `service_orders`
--

DROP TABLE IF EXISTS `service_orders`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `service_orders` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint unsigned NOT NULL,
  `service_id` bigint unsigned NOT NULL,
  `status` enum('pending','completed') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pending',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `service_orders_user_id_foreign` (`user_id`),
  KEY `service_orders_service_id_foreign` (`service_id`),
  CONSTRAINT `service_orders_service_id_foreign` FOREIGN KEY (`service_id`) REFERENCES `services` (`id`) ON DELETE CASCADE,
  CONSTRAINT `service_orders_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `service_orders`
--

LOCK TABLES `service_orders` WRITE;
/*!40000 ALTER TABLE `service_orders` DISABLE KEYS */;
/*!40000 ALTER TABLE `service_orders` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `service_payments`
--

DROP TABLE IF EXISTS `service_payments`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `service_payments` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `service_order_id` bigint unsigned NOT NULL,
  `payment_id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `amount` decimal(10,2) NOT NULL,
  `status` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `service_payments_service_order_id_foreign` (`service_order_id`),
  CONSTRAINT `service_payments_service_order_id_foreign` FOREIGN KEY (`service_order_id`) REFERENCES `service_orders` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `service_payments`
--

LOCK TABLES `service_payments` WRITE;
/*!40000 ALTER TABLE `service_payments` DISABLE KEYS */;
/*!40000 ALTER TABLE `service_payments` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `services`
--

DROP TABLE IF EXISTS `services`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `services` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `status` enum('active','inactive') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'active',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `services`
--

LOCK TABLES `services` WRITE;
/*!40000 ALTER TABLE `services` DISABLE KEYS */;
/*!40000 ALTER TABLE `services` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `sessions`
--

DROP TABLE IF EXISTS `sessions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `sessions` (
  `id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` bigint unsigned DEFAULT NULL,
  `ip_address` varchar(45) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `user_agent` text COLLATE utf8mb4_unicode_ci,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `last_activity` int NOT NULL,
  PRIMARY KEY (`id`),
  KEY `sessions_user_id_index` (`user_id`),
  KEY `sessions_last_activity_index` (`last_activity`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `sessions`
--

LOCK TABLES `sessions` WRITE;
/*!40000 ALTER TABLE `sessions` DISABLE KEYS */;
INSERT INTO `sessions` VALUES ('bG4LktQxuw2jG0uLM4VqxtspWxmI7gD6ljzbBf6c',1,'127.0.0.1','Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36','YTo1OntzOjY6Il90b2tlbiI7czo0MDoiajBhNlhQTDRIUkZCWnBSMktsWkdtUkwxMFEyVFMxcUZ2UFBNMGR2VSI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MjE6Imh0dHA6Ly9sb2NhbGhvc3Q6ODAwMSI7czo1OiJyb3V0ZSI7czoxMDoiZGlhcnkuaG9tZSI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fXM6MzoidXJsIjthOjA6e31zOjUwOiJsb2dpbl93ZWJfNTliYTM2YWRkYzJiMmY5NDAxNTgwZjAxNGM3ZjU4ZWE0ZTMwOTg5ZCI7aToxO30=',1776180896),('Vs8PsUJ1OdPZJ9n6fucWKELxG0eIC9aDmBHJ9307',NULL,'127.0.0.1','Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36','YTo0OntzOjY6Il90b2tlbiI7czo0MDoiNHR3Nkt5VGZnYmU3b21Id003dWlUZld0eGpwUmJaU1ROdUZId1JXZyI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MjE6Imh0dHA6Ly9sb2NhbGhvc3Q6ODAwMSI7czo1OiJyb3V0ZSI7czoxMDoiZGlhcnkuaG9tZSI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fXM6MzoidXJsIjthOjE6e3M6ODoiaW50ZW5kZWQiO3M6Njg6Imh0dHA6Ly9sb2NhbGhvc3Q6ODAwMS9zdG9yaWVzL251bXF1YW0tdXQtbmloaWwtdmVsLVBneTJ2L2NoYXB0ZXJzLzQ3Ijt9fQ==',1776181166),('zDeLKkYUxSDCX18PfmCJ7SlAg0oOiReBLMeNy0Gy',10,'127.0.0.1','Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36','YTo1OntzOjY6Il90b2tlbiI7czo0MDoiM1J0NTJrTEhBdUlQV0xTeThsZTBrWEx3SkEwSklYbU92ZE81WkhkMSI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MjE6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMSI7czo1OiJyb3V0ZSI7czoxMDoiZGlhcnkuaG9tZSI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fXM6MzoidXJsIjthOjA6e31zOjUwOiJsb2dpbl93ZWJfNTliYTM2YWRkYzJiMmY5NDAxNTgwZjAxNGM3ZjU4ZWE0ZTMwOTg5ZCI7aToxMDt9',1776180712);
/*!40000 ALTER TABLE `sessions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `stories`
--

DROP TABLE IF EXISTS `stories`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `stories` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint unsigned NOT NULL,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `slug` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `cover_image` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `genre` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `tags` json DEFAULT NULL,
  `language` enum('hindi','hinglish') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'hinglish',
  `story_type` enum('short_story','series','poems') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'short_story',
  `type` enum('short_story','series','poems') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'short_story',
  `content_guidance` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `visibility` enum('draft','public','premium') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'draft',
  `theme` enum('light','dark','sepia') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'light',
  `bg_image` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `bg_color` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` enum('ongoing','completed') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'ongoing',
  `approval_status` enum('pending','approved','rejected') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pending',
  `approved_at` timestamp NULL DEFAULT NULL,
  `rejected_at` timestamp NULL DEFAULT NULL,
  `admin_notes` text COLLATE utf8mb4_unicode_ci,
  `read_time` int unsigned NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `stories_slug_unique` (`slug`),
  KEY `stories_user_id_approval_status_visibility_index` (`user_id`,`approval_status`,`visibility`),
  CONSTRAINT `stories_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=30 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `stories`
--

LOCK TABLES `stories` WRITE;
/*!40000 ALTER TABLE `stories` DISABLE KEYS */;
INSERT INTO `stories` VALUES (10,5,'Nostrum omnis repellendus odit.','nostrum-omnis-repellendus-odit-ih69i',NULL,'Recusandae praesentium incidunt vel molestias blanditiis. Blanditiis cupiditate sunt sed quod mollitia pariatur. Qui fugit error id voluptatem perferendis dolorem.\n\nEt quis cumque et aut sit ipsum a sunt. Cupiditate libero eum non magni dicta dolor facilis natus. Quis animi hic rerum vero quaerat.','Drama','[\"placeat\", \"placeat\", \"natus\"]','hinglish','short_story','short_story',NULL,'public','light',NULL,NULL,'completed','pending',NULL,NULL,NULL,2,'2026-03-14 22:59:29','2026-03-14 22:59:29'),(11,6,'Sed voluptatem corrupti in.','sed-voluptatem-corrupti-in-gzCIr',NULL,'In quisquam atque enim vitae aliquid ad perspiciatis. Possimus ratione culpa doloribus quo id quae. Corporis culpa quas quia odio.\n\nSimilique vitae laudantium explicabo illum cumque dolore. Ad iusto voluptate maxime harum. Dolores consequatur soluta porro quia magni voluptas veritatis. Qui alias id sit aut placeat nobis.','Thriller','[\"est\", \"nesciunt\", \"exercitationem\"]','hinglish','series','short_story',NULL,'public','light',NULL,NULL,'ongoing','pending',NULL,NULL,NULL,2,'2026-03-14 22:59:29','2026-03-14 22:59:29'),(12,8,'Velit sint voluptas sunt et.','velit-sint-voluptas-sunt-et-N9fVI',NULL,'Explicabo aut voluptatem repellat dolore. Error eos officiis natus doloremque placeat aut sed quos. Eum dolorem voluptatem et adipisci mollitia quasi quas. Iure animi et et ea ea.\n\nFacere sed quia sequi. Consequuntur hic voluptates libero. Ut explicabo ut consectetur deleniti. Et neque est tempore dignissimos maiores occaecati.','Romance','[\"vel\", \"eos\", \"enim\"]','hinglish','series','short_story',NULL,'draft','light',NULL,NULL,'ongoing','rejected',NULL,'2026-03-14 22:59:29',NULL,1,'2026-03-14 22:59:29','2026-03-14 22:59:29'),(13,2,'Numquam ut nihil vel.','numquam-ut-nihil-vel-Pgy2v',NULL,'Fuga corporis quis deleniti. Voluptas dolor eligendi nihil corrupti. Tenetur placeat iste nesciunt impedit quis.\n\nEst beatae sunt officia error minus asperiores repellendus. Suscipit nulla ut recusandae. Reiciendis assumenda harum numquam ex ut laudantium.','Thriller','[\"nulla\", \"aut\", \"tenetur\"]','hindi','series','short_story',NULL,'public','light',NULL,NULL,'completed','approved','2026-03-14 22:59:29',NULL,NULL,3,'2026-03-14 22:59:29','2026-03-14 22:59:29'),(14,6,'Qui voluptas et.','qui-voluptas-et-FZhof',NULL,'Qui corrupti excepturi enim et repellat exercitationem neque. Dicta odio eum amet in. Delectus ratione doloremque et. Omnis ex esse enim aperiam ut in.\n\nEius ab adipisci impedit enim beatae sed. Quia assumenda officia voluptas qui officiis. Adipisci voluptatibus animi inventore vel. Officia sunt in dignissimos non et repudiandae.','Comedy','[\"quia\", \"aut\", \"et\"]','hinglish','short_story','short_story',NULL,'public','light',NULL,NULL,'completed','approved','2026-03-14 22:59:29',NULL,NULL,2,'2026-03-14 22:59:29','2026-03-14 22:59:29'),(15,4,'Voluptatem harum ratione id.','voluptatem-harum-ratione-id-oiMwU',NULL,'Dolore dolor natus aspernatur. Eaque sed reprehenderit asperiores accusamus accusamus. Non modi commodi aut vitae saepe dolores. Quaerat quidem soluta labore sit ad ut repudiandae.\n\nPerspiciatis voluptatem itaque temporibus nihil quam voluptas. Vitae consectetur consequatur incidunt enim et impedit. Dolorem quis est nulla nobis fugiat vel. Quam est mollitia eius eius et molestiae. Similique fugit excepturi ea officiis ad a qui.','Comedy','[\"quia\", \"facere\", \"unde\"]','hinglish','series','short_story',NULL,'public','light',NULL,NULL,'ongoing','approved','2026-03-14 22:59:29',NULL,NULL,3,'2026-03-14 22:59:29','2026-03-14 22:59:29'),(16,8,'Iste qui eveniet.','iste-qui-eveniet-JiBUF',NULL,'Laboriosam ad vero perferendis. Sit beatae ullam ipsam modi assumenda ab. Aut molestiae alias nam tenetur magni odit. Ut deleniti aliquid vel illo ab eius.\n\nAmet voluptate accusantium ea in velit. Ut expedita nobis sunt impedit. Iste aut atque omnis suscipit voluptatem.','Comedy','[\"qui\", \"explicabo\", \"eligendi\"]','hinglish','series','short_story',NULL,'draft','light',NULL,NULL,'completed','pending',NULL,NULL,NULL,2,'2026-03-14 22:59:29','2026-03-14 22:59:29'),(17,4,'Ea velit blanditiis voluptatem.','ea-velit-blanditiis-voluptatem-XL5Zy',NULL,'Sit et molestiae praesentium. Tenetur labore sequi blanditiis maiores earum libero non. Neque necessitatibus omnis et est neque.\n\nEarum perspiciatis voluptatem exercitationem et quis ut. Officiis dolores rerum consequatur. Magnam et consequatur excepturi. Iure enim perferendis reprehenderit molestiae hic necessitatibus.','Mystery','[\"laudantium\", \"fugit\", \"aut\"]','hindi','short_story','short_story',NULL,'public','light',NULL,NULL,'completed','approved','2026-03-14 22:59:29',NULL,NULL,3,'2026-03-14 22:59:29','2026-03-14 22:59:29'),(18,3,'Quo voluptatum hic consequuntur.','quo-voluptatum-hic-consequuntur-BD1aS',NULL,'Voluptatem ut velit aliquam vel pariatur libero quia. Ut quod et sit rerum laboriosam omnis voluptatem. Qui nesciunt voluptas rem aut blanditiis ipsam sint. Sunt quos doloribus saepe et quod adipisci.\n\nVoluptas harum nam molestiae officia quo aut. Molestias quia architecto consequatur qui ipsam. Voluptatem enim sint ipsa consectetur quia autem impedit quidem. Repellendus quisquam at minus.','Thriller','[\"amet\", \"beatae\", \"atque\"]','hinglish','short_story','short_story',NULL,'public','light',NULL,NULL,'completed','approved','2026-03-14 22:59:29',NULL,NULL,3,'2026-03-14 22:59:29','2026-03-14 22:59:29'),(24,10,'manshu ki khani oski jubani','manshu-ki-khani-oski-jubani',NULL,'Qui optio et dolorem excepturi ut vitae','Poetry',NULL,'hindi','short_story','short_story','Dignissimos consequu','premium','light',NULL,'#f9447b','completed','approved','2026-03-20 23:28:44',NULL,NULL,1,'2026-03-20 23:25:47','2026-03-20 23:34:38'),(25,10,'aut enim quo aut pariatur','aut-enim-quo-aut-pariatur',NULL,'Eos qui asperiores elit aliqua Culpa','Drama',NULL,'hinglish','series','series','Modi dolorem provide','draft','sepia',NULL,'#65a1f9','completed','pending',NULL,NULL,NULL,0,'2026-03-20 23:51:29','2026-03-20 23:51:29'),(26,10,'Deserunt sed nihil ad enim amet sunt enim totam ipsam omnis','deserunt-sed-nihil-ad-enim-amet-sunt-enim-totam-ipsam-omnis',NULL,'Minus adipisci error deleniti sit','Drama',NULL,'hindi','poems','poems','Incididunt cum quia','premium','dark',NULL,'#1f2c10','completed','pending',NULL,NULL,NULL,0,'2026-03-21 00:34:35','2026-03-21 00:34:35'),(27,10,'unde animi deleniti sunt proident','unde-animi-deleniti-sunt-proident',NULL,'Quam dolorem enim praesentium laborum Qui exercitationem exercitationem','Fantasy',NULL,'hindi','poems','poems','Tempore recusandae','draft','light',NULL,'#6b507b','completed','rejected',NULL,'2026-03-22 00:59:13','no issue',1,'2026-03-21 23:24:40','2026-03-22 00:59:13'),(28,10,'Anshu Ki Story','anshu-ki-story','stories/covers/FhphMtsP1k9gzEpPVfTvMDSTLkdlGBM9iTXRg38l.png','edtrfyguhijokl','Fantasy',NULL,'hinglish','series','series','18','public','dark',NULL,'#924545','ongoing','approved','2026-03-22 00:56:31',NULL,NULL,1,'2026-03-22 00:53:55','2026-03-22 00:56:31'),(29,10,'srdfgjhkl','srdfgjhkl',NULL,'rsetdyfugkhlij;kl',NULL,NULL,'hinglish','short_story','short_story',NULL,'draft','light',NULL,'#ffffff','ongoing','pending',NULL,NULL,NULL,0,'2026-04-14 10:00:43','2026-04-14 10:00:43');
/*!40000 ALTER TABLE `stories` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `story_chapters`
--

DROP TABLE IF EXISTS `story_chapters`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `story_chapters` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `story_id` bigint unsigned NOT NULL,
  `chapter_title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `chapter_number` int unsigned NOT NULL,
  `content` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `word_count` int unsigned NOT NULL DEFAULT '0',
  `reading_time` int unsigned NOT NULL DEFAULT '0',
  `read_time` int unsigned NOT NULL DEFAULT '5',
  `audio_file` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `youtube_url` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `spotify_playlist` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` enum('draft','published') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'draft',
  `continue_reading_after` int unsigned DEFAULT NULL,
  `sort_order` int unsigned NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `story_chapters_story_id_status_index` (`story_id`,`status`),
  CONSTRAINT `story_chapters_story_id_foreign` FOREIGN KEY (`story_id`) REFERENCES `stories` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=80 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `story_chapters`
--

LOCK TABLES `story_chapters` WRITE;
/*!40000 ALTER TABLE `story_chapters` DISABLE KEYS */;
INSERT INTO `story_chapters` VALUES (36,10,'Chapter 1: harum et ex',1,'Iure et iste repudiandae iste minima. Fuga voluptatem dolor eum dolores. Soluta id iusto est dolor sit architecto. Rerum sit autem voluptatem iure id aut.\n\nAccusantium est mollitia placeat ex. Sunt aut accusamus esse modi eos doloremque. Officia magnam necessitatibus odit distinctio enim.\n\nDeserunt omnis autem iste id aliquam eligendi ipsam. Alias culpa dolores consequatur quas aperiam rerum.\n\nUt consequatur porro a et velit eaque reprehenderit. Quasi autem provident deserunt repellendus culpa maxime provident. Quia sed et quia occaecati.',0,0,1,NULL,NULL,NULL,'published',NULL,0,'2026-03-14 22:59:29','2026-03-14 22:59:29'),(37,10,'Chapter 2: debitis cupiditate similique',2,'Aperiam adipisci quo non ab et hic. Nihil voluptates distinctio reiciendis hic voluptatibus accusantium. In porro tenetur consequatur reiciendis cupiditate.\n\nAd rerum temporibus ut maiores ea. Doloribus ut facilis autem voluptates. Quaerat voluptate ex rerum fugit.\n\nDolores ea deleniti omnis. Cumque eum sed ab nesciunt suscipit provident. Eius beatae consequuntur praesentium.\n\nHic voluptas dolores neque perspiciatis. Quia veritatis laudantium et beatae et vel. Iure in nisi a cum vero. Harum a aut reprehenderit aut facilis.',0,0,1,NULL,NULL,NULL,'published',NULL,1,'2026-03-14 22:59:29','2026-03-14 22:59:29'),(38,11,'Chapter 1: magni enim itaque',1,'Sint sed et omnis reiciendis deleniti. Et qui dolorem accusantium ipsa aperiam. Blanditiis hic qui reprehenderit aspernatur rerum voluptatem.\n\nVel dolorem et exercitationem et voluptate dicta aliquam. Perspiciatis et deleniti sed amet explicabo vero. Voluptate totam ab neque sit aliquid et itaque cumque.\n\nSunt magni id est repudiandae quas placeat eligendi aut. Fuga voluptatem quia iste vitae vel quaerat. Ipsa earum sequi sapiente ad et nemo natus vel.\n\nQuae exercitationem alias laborum delectus delectus officia. Quasi veritatis et voluptatem mollitia. Rerum voluptatem quibusdam quam facere animi veniam.',0,0,1,NULL,NULL,NULL,'published',NULL,0,'2026-03-14 22:59:29','2026-03-14 22:59:29'),(39,11,'Chapter 2: et enim sequi',2,'Est repudiandae culpa voluptas eaque dolorem velit accusantium. Illo sint aut aliquid velit ullam et. Dicta tempora voluptatem magnam officiis numquam.\n\nRepudiandae sunt voluptas itaque aut odit illo. Sit illum voluptas pariatur dolorum et. Aliquam repellendus consequatur omnis est autem aut.\n\nEx fugit similique aut. Eveniet delectus recusandae quidem a possimus ea sed. Sunt sunt nesciunt mollitia minus. Non distinctio consequatur accusantium earum voluptas id.\n\nPorro sint repudiandae culpa magni consectetur. Nihil et eum facilis. Necessitatibus nemo voluptatem id velit omnis. Ea odit aut ex cupiditate. Reprehenderit numquam facilis ipsum aut.',0,0,1,NULL,NULL,NULL,'draft',NULL,1,'2026-03-14 22:59:29','2026-03-14 22:59:29'),(40,11,'Chapter 3: enim culpa unde',3,'Commodi vel atque magnam occaecati aut omnis. Quo praesentium sed et alias. Qui libero et ea qui ex quia est.\n\nAlias ab qui ut dicta et facilis cumque cumque. Provident quae quis rerum. Quasi placeat iusto tempore ut sit quo laborum consectetur. Quos est exercitationem veniam voluptatem est. Sapiente quia laboriosam id alias.\n\nSunt ipsum ab vero ducimus accusamus consequuntur. Magnam cupiditate accusantium in dolorem voluptate. Consequatur iusto repellendus est. Dicta aliquam reprehenderit eum blanditiis.\n\nQui nemo excepturi iure amet ea rerum. Ut et odio qui sed debitis. Ea cupiditate excepturi qui repellat et voluptatem. Qui tempore quod ex aspernatur ut. Aspernatur est rem fugiat ratione cumque culpa.',0,0,1,NULL,NULL,NULL,'published',NULL,2,'2026-03-14 22:59:29','2026-03-14 22:59:29'),(41,11,'Chapter 4: fugit praesentium aut',4,'Consequatur quia mollitia nobis aliquam quasi enim. Qui nulla est sit odit velit odit nemo. Eos repellendus molestiae consequatur et enim.\n\nUt nemo ex neque consequuntur aperiam. Et quidem cupiditate officiis aliquid autem natus. Voluptas omnis earum odio consectetur repellendus repellendus dicta.\n\nProvident dolores repudiandae perferendis est odit magni aperiam. Quia sunt ipsam quia facere. Ipsum dolore illum quis cupiditate facere. Sint eum quis eaque libero pariatur iste accusantium quisquam.\n\nNon doloribus veritatis autem. Ad nemo voluptatem iste officia cupiditate ea cum illo. Fugit rerum est cumque dolores laudantium atque.',0,0,1,NULL,NULL,NULL,'draft',NULL,3,'2026-03-14 22:59:29','2026-03-14 22:59:29'),(42,12,'Chapter 1: sit dolores assumenda',1,'Sed tempora tempore et minima reiciendis recusandae autem facilis. Natus nihil ex architecto aut maxime qui. Natus et voluptatem praesentium eveniet est est error nisi. Id autem pariatur sint non quisquam.\n\nInventore enim nisi quibusdam exercitationem dolorem aspernatur. Amet odit iure aliquid totam dicta optio quibusdam. Expedita cum beatae rerum omnis sunt non voluptates. Cum sed quo nam doloremque dolorum sapiente possimus.\n\nEst sit cumque repellat qui quia. Necessitatibus explicabo quod possimus expedita voluptas temporibus tempora. Ducimus quisquam facilis velit omnis repudiandae. Voluptatem magni rerum repellendus. Atque quas velit nulla autem dolore sint est velit.\n\nAut at doloribus ad necessitatibus magnam aut dolorem. Et est omnis pariatur laborum et. Facere tempora molestias ipsum possimus odio numquam. Dolores vitae nihil ut mollitia quasi corrupti quo sapiente.',0,0,1,NULL,NULL,NULL,'published',NULL,0,'2026-03-14 22:59:29','2026-03-14 22:59:29'),(43,12,'Chapter 2: qui ex ut',2,'Optio fugiat quaerat doloremque nobis laboriosam. Blanditiis quidem voluptatem eveniet sunt quaerat.\n\nEarum doloribus et quo qui. Quia dolor ut laborum minima fuga assumenda quo. Expedita doloremque nostrum eaque neque. Aut qui blanditiis rem.\n\nSit porro tempora cum. Voluptas accusamus aut nisi possimus et. Iste quae placeat rem sit pariatur voluptatem cupiditate.\n\nEt placeat quos dolorum quia provident accusantium quia. Sit numquam saepe explicabo nesciunt hic. Minus fuga repudiandae eum dolor ullam sed.',0,0,1,NULL,NULL,NULL,'draft',NULL,1,'2026-03-14 22:59:29','2026-03-14 22:59:29'),(44,13,'Chapter 1: hic ipsa qui',1,'Non sapiente officiis eum fugit ad. Quam fuga et eum repellat. Distinctio neque architecto in eum numquam eius.\n\nPorro atque itaque nulla esse et et. Dolor omnis consectetur quae perferendis culpa est. Vel neque omnis blanditiis esse id nesciunt. Distinctio nulla tempore aspernatur facere ea ipsam.\n\nMaxime expedita deleniti aut fuga enim provident repellat. Et temporibus deleniti ut. Velit dolor illum nemo et repellat doloremque aut odio. Natus ea consectetur nam deserunt non.\n\nAssumenda dolore aspernatur aut sapiente. Enim voluptates perferendis illo accusantium sint voluptatibus. Dicta optio suscipit eius temporibus non cupiditate eligendi.',0,0,1,NULL,NULL,NULL,'published',NULL,0,'2026-03-14 22:59:29','2026-03-14 22:59:29'),(45,13,'Chapter 2: reprehenderit dolores aut',2,'Et voluptatibus ab ullam voluptas. Accusantium tempore voluptatem consequatur. Esse qui enim quibusdam dolorem velit.\n\nNecessitatibus voluptatibus id maxime. Velit fuga laudantium minus. Fugiat molestiae sit magni odit distinctio.\n\nFugiat eum neque asperiores et et maiores esse. Quo consectetur et blanditiis accusantium dolorum pariatur. Odio quia incidunt doloremque culpa et. Hic sed et soluta quas et perspiciatis molestias. Necessitatibus mollitia non vitae.\n\nId adipisci aperiam voluptas error nesciunt facere. Ad minima adipisci ut unde pariatur libero. Natus aut et dolorem.',0,0,1,NULL,NULL,NULL,'draft',NULL,1,'2026-03-14 22:59:29','2026-03-14 22:59:29'),(46,13,'Chapter 3: repellat voluptas possimus',3,'Dolore ab porro qui tempore quidem neque eius. Natus possimus doloremque rem tempore qui quasi voluptatem. Deserunt officia quis cumque qui saepe consequuntur tempore amet. Voluptas ut voluptate ut et iusto possimus itaque. Eum enim quis ipsum alias iste excepturi.\n\nAut eos sequi soluta nobis et. Est voluptatibus non non quaerat dicta laboriosam quae. Dolor illum id perspiciatis illo incidunt.\n\nVel omnis est saepe numquam fuga et. Cum quia qui aliquam sed doloribus perspiciatis reprehenderit. Inventore qui numquam eos in labore.\n\nEt quis quidem et explicabo eveniet non eaque. Quibusdam perspiciatis perspiciatis vero velit et soluta.',0,0,1,NULL,NULL,NULL,'draft',NULL,2,'2026-03-14 22:59:29','2026-03-14 22:59:29'),(47,13,'Chapter 4: voluptates saepe aspernatur',4,'Alias fuga autem architecto iure laboriosam animi sunt. Quia sed perspiciatis totam. Porro eos aut eius esse fuga et. Qui debitis nihil error impedit saepe consequuntur in.\n\nEt soluta consequatur magnam dolorem. Aliquam et et dolorum natus consequatur sint. Error provident et quia assumenda magni nemo.\n\nEa officia maxime eos. Aliquid quia animi ullam velit. Molestiae minus ducimus nesciunt molestias sapiente. Maiores nobis et impedit soluta nihil illum.\n\nItaque quo et deleniti minima ex occaecati. Fugit veritatis et nihil occaecati nam est consectetur. Et voluptatem autem vel porro non id voluptatibus. Alias non sit vel unde itaque. Nam rerum repudiandae assumenda.',0,0,1,NULL,NULL,NULL,'published',NULL,3,'2026-03-14 22:59:29','2026-03-14 22:59:29'),(48,13,'Chapter 5: nihil aliquam et',5,'Est eligendi velit veniam totam a. Et et deserunt a inventore. Cupiditate consequatur porro ea et qui ut corrupti.\n\nItaque consequatur repellat deserunt dolores qui rerum. Aliquid ut cupiditate pariatur et. Earum dolores dolorem molestiae. Placeat totam possimus nulla magni facilis hic est.\n\nIllo delectus modi repudiandae cumque accusantium. Omnis facilis repellat dignissimos placeat. Facere aperiam qui deserunt aut nesciunt odit atque. Expedita qui ipsum asperiores molestiae est.\n\nEaque expedita aut occaecati nisi accusamus. Fugiat corrupti ea molestiae natus autem. Ratione porro ut adipisci porro error. In molestiae sunt facilis ex.',0,0,1,NULL,NULL,NULL,'published',NULL,4,'2026-03-14 22:59:29','2026-03-14 22:59:29'),(49,14,'Chapter 1: recusandae ipsam exercitationem',1,'Culpa ipsam nam animi id modi. Neque sint quibusdam sed nihil. Qui non nihil deleniti in architecto enim nostrum. Exercitationem qui eius laudantium nisi quod numquam.\n\nDolorem sapiente repellat placeat officia praesentium qui quas consequatur. Enim voluptas ex ut eos perspiciatis mollitia. Nulla voluptatum natus non vel voluptatem. Voluptatum quibusdam odit ad est.\n\nBeatae et possimus exercitationem expedita sit. Quo esse non est facere excepturi. Aut odit animi eos sed omnis quo. In impedit debitis autem amet nisi culpa.\n\nFacere doloremque placeat quis optio qui quia explicabo magni. Debitis ut et sit sequi recusandae. Nulla esse numquam velit sint placeat. Sit eos in neque dolores aut. Et ut repellat eligendi aut perferendis.',0,0,1,NULL,NULL,NULL,'published',NULL,0,'2026-03-14 22:59:29','2026-03-14 22:59:29'),(50,14,'Chapter 2: quidem et suscipit',2,'Et et dolore repudiandae eum aliquid dignissimos. Qui rerum suscipit incidunt ut.\n\nRepellendus minus consequatur consequatur deleniti et cumque illo. Totam illum temporibus consectetur. Necessitatibus ex inventore asperiores eligendi. Quas et iste sint eligendi molestias molestias nulla beatae.\n\nConsequatur voluptatem fuga ipsam quia saepe ut. Ipsa ex a recusandae unde voluptas vero dignissimos. Autem nihil rerum cupiditate molestiae et. Harum aut eos est architecto perspiciatis cupiditate et.\n\nQuod distinctio cum a dicta commodi assumenda commodi. Ipsa dolore aliquid possimus velit cupiditate placeat eos. Maiores beatae doloribus quos deserunt consequatur consectetur.',0,0,1,NULL,NULL,NULL,'draft',NULL,1,'2026-03-14 22:59:29','2026-03-14 22:59:29'),(51,14,'Chapter 3: aut esse sapiente',3,'Et explicabo itaque sed perspiciatis veniam ab. Voluptatem odit esse repellat adipisci excepturi nostrum aut. Atque vel inventore itaque aut quos voluptatem.\n\nQuisquam nemo nihil qui porro voluptas voluptatem quibusdam. Perspiciatis rerum rerum ullam officiis incidunt doloremque molestias. Temporibus repudiandae totam harum modi.\n\nEst in molestias rerum distinctio sed qui. Vel assumenda et ut. Iure ipsa assumenda animi.\n\nTotam nobis voluptas dolorem dolores mollitia iusto ut. Accusantium sit beatae distinctio eum unde qui natus voluptatem. Vero enim molestiae dolores aut quisquam expedita architecto. Dicta nemo qui similique modi.',0,0,1,NULL,NULL,NULL,'draft',NULL,2,'2026-03-14 22:59:29','2026-03-14 22:59:29'),(52,14,'Chapter 4: ut qui aliquam',4,'Aut quia temporibus eligendi voluptas. Quo omnis voluptatibus maiores sit perspiciatis eveniet sit architecto.\n\nError sit ducimus deleniti ad amet qui. Repellendus quo error nam. Ratione eius sit repellendus reiciendis repudiandae. Perspiciatis eius omnis quasi.\n\nFugit dicta optio cupiditate voluptatum non quae non. Ea placeat doloribus reprehenderit. Voluptatum id qui dolorem voluptate molestiae facere voluptatum nam.\n\nVel unde consequatur odit natus nesciunt beatae. Animi sit occaecati consequatur consequatur necessitatibus. Dolor qui quis rerum dolore. Totam provident ea error veniam repellendus sed modi nesciunt.',0,0,1,NULL,NULL,NULL,'published',NULL,3,'2026-03-14 22:59:29','2026-03-14 22:59:29'),(53,15,'Chapter 1: consequatur ut ut',1,'A aut laboriosam excepturi. Aut odio odio magnam et velit iure. Nam cupiditate magni et cum tenetur repellat nisi.\n\nEt beatae voluptatem natus dicta alias distinctio ipsum. Reprehenderit vitae non voluptatem placeat consequatur.\n\nEa dolorem veniam rem porro. Laborum corporis sequi odit eum a. Labore dicta pariatur aut quo et quo non. Nostrum minima temporibus voluptatibus et quia.\n\nNatus molestiae architecto et numquam minima. Quidem excepturi veniam eum. Hic expedita maiores sit libero reprehenderit blanditiis. Est voluptatem ratione necessitatibus deleniti dolores similique. Aut expedita deserunt excepturi odit.',0,0,1,NULL,NULL,NULL,'published',NULL,0,'2026-03-14 22:59:29','2026-03-14 22:59:29'),(54,15,'Chapter 2: et sint qui',2,'Nihil est autem sit incidunt. Sed quia laboriosam maiores architecto ut aut quibusdam veniam. Aperiam cupiditate repellat fugiat quos quod et aut.\n\nAut aut sint explicabo dolor et itaque maxime. Tenetur et odio consectetur aperiam iste nihil. Cupiditate non nihil quis ad eum quis provident.\n\nEt ducimus occaecati ut dolore dolor aperiam expedita. Facilis est exercitationem ut accusamus accusamus. Aut quia et dolores commodi iusto.\n\nDolorem quibusdam praesentium quis. Autem fugit sequi vel. Voluptas hic tenetur non aliquid non eum.',0,0,1,NULL,NULL,NULL,'published',NULL,1,'2026-03-14 22:59:29','2026-03-14 22:59:29'),(55,15,'Chapter 3: autem accusantium corporis',3,'Sed facere culpa perspiciatis veniam autem recusandae. Est odit sed maiores dolores. Et velit autem quis quia.\n\nHic dignissimos iure amet ut deserunt dolor. Rerum nihil ut consequatur facere ratione. Ut culpa iusto ipsum accusamus. Qui et itaque accusamus molestias odio.\n\nEnim similique non aut fugit corrupti autem omnis. Quia in et voluptas expedita assumenda. Distinctio ipsum ullam labore omnis non illum mollitia.\n\nVel sint distinctio molestias aut. Consectetur sed illo qui tempore facilis provident aliquid. Ullam ut occaecati accusantium veritatis.',0,0,1,NULL,NULL,NULL,'published',NULL,2,'2026-03-14 22:59:29','2026-03-14 22:59:29'),(56,16,'Chapter 1: doloremque neque sunt',1,'Eos rerum magni eos sit nam est laudantium. Libero tenetur suscipit ut sapiente aut voluptas. Qui quis cupiditate sequi accusamus aperiam aut magni. Nam molestias ab aut quibusdam harum laudantium necessitatibus. Et ipsam quos at debitis dolor sint culpa.\n\nSed placeat qui dolorem. Dolor vitae omnis aut tenetur.\n\nIpsum asperiores nulla eaque excepturi iure omnis dolore. Quis reprehenderit ut culpa sed. Quis ullam dolorum odit commodi asperiores. Cum et et impedit ut.\n\nConsequatur et sapiente sit sed sunt dolor quia. Velit tempora rem in laborum dolores ut. Sed in neque repellendus enim consequatur.',0,0,1,NULL,NULL,NULL,'published',NULL,0,'2026-03-14 22:59:29','2026-03-14 22:59:29'),(57,16,'Chapter 2: exercitationem magni ad',2,'Beatae vero adipisci labore nulla numquam expedita expedita. Provident eum laudantium saepe ut voluptatem expedita cupiditate. Dolorem enim amet repellendus nisi laborum nobis iure.\n\nEt dolor sint eaque. Voluptatum non ab aut laboriosam nulla ratione reiciendis. Aut quia ipsum non officia debitis. Quidem odit magni voluptas velit.\n\nReprehenderit nam amet architecto aut aliquid enim ipsa. Similique aspernatur aperiam qui. Ut at laborum nostrum perspiciatis aperiam laudantium sint.\n\nQui sequi odio aspernatur est excepturi. Numquam magni natus adipisci expedita quos ut. Cupiditate eos praesentium dolorum enim quis tempora. Velit itaque et amet doloremque eius voluptatem.',0,0,1,NULL,NULL,NULL,'published',NULL,1,'2026-03-14 22:59:29','2026-03-14 22:59:29'),(58,16,'Chapter 3: autem et natus',3,'Hic sequi molestias magni eaque. Fugiat cupiditate consectetur itaque animi mollitia repudiandae ea ea. Est ad aspernatur rerum voluptate nemo ullam illum. Fugiat aliquid sed odio libero sunt non culpa.\n\nUt et omnis voluptatibus iste nihil. Voluptatem repudiandae enim tempore quaerat quas. Consequatur qui quisquam amet molestias.\n\nVoluptatibus ut quis corporis. Illum aut eius ut animi sed cum accusantium fuga. Quia accusantium est qui ipsa necessitatibus quia.\n\nQui nemo iusto quasi doloremque. Quisquam dolores dolorum provident perferendis aut alias. At necessitatibus doloremque dolor assumenda. Ducimus atque ut cum et dignissimos.',0,0,1,NULL,NULL,NULL,'draft',NULL,2,'2026-03-14 22:59:29','2026-03-14 22:59:29'),(59,17,'Chapter 1: ut id iure',1,'Assumenda aliquid et doloremque quisquam impedit cupiditate magnam ut. Dicta ut magni sint voluptas rerum tempore. Odio excepturi et ipsum sint id amet.\n\nAssumenda delectus explicabo qui qui voluptatem nemo vel. Autem non voluptatum numquam iure cumque. Vitae debitis et eaque sit.\n\nDistinctio sequi veritatis minus omnis. Consectetur aut aut praesentium saepe consequatur ratione soluta. Quibusdam quam aliquid explicabo molestiae. Doloremque rerum doloremque labore dolores. Ducimus esse et impedit enim et.\n\nQuaerat occaecati corporis est assumenda. Necessitatibus maxime non nihil minima assumenda aut. Repellendus quia aperiam autem placeat quo fugiat iusto animi. Nostrum quidem numquam voluptates debitis aut ad.',0,0,1,NULL,NULL,NULL,'published',NULL,0,'2026-03-14 22:59:29','2026-03-14 22:59:29'),(60,17,'Chapter 2: assumenda quo deleniti',2,'Est corrupti dolore voluptatibus quibusdam earum et nisi tenetur. Ea tempora asperiores saepe aspernatur. Illum id voluptatem atque nemo natus laborum et.\n\nIncidunt consequuntur ipsa eum et doloribus officiis. Quia voluptatem nisi corporis ducimus. Eos eos impedit quis est.\n\nQuae magnam sed et cupiditate quia. Sunt id officiis ea maiores.\n\nNecessitatibus quisquam consequuntur sint totam omnis rem voluptate. Nemo sapiente neque vel blanditiis qui. Aliquid est fuga veritatis quaerat molestias.',0,0,1,NULL,NULL,NULL,'published',NULL,1,'2026-03-14 22:59:29','2026-03-14 22:59:29'),(61,17,'Chapter 3: sit cumque officia',3,'Eius est consequatur mollitia. Fugit porro voluptas voluptas eius recusandae sapiente. Optio ipsum ea et qui. Sint omnis expedita eveniet voluptatem suscipit et iusto.\n\nDolorum temporibus labore repellendus ex earum aperiam. Ut molestiae cum sunt sequi fugit aut vitae maxime. Magni et et laudantium ipsum.\n\nInventore magni commodi illum commodi explicabo. Ad doloribus sed sed aperiam qui sed. Maxime quia sit eum fugiat ad quibusdam.\n\nMinus molestiae rerum beatae sed quaerat similique. Minima iure ut id repudiandae cupiditate distinctio sint. Et unde architecto ut vel aspernatur tempore.',0,0,1,NULL,NULL,NULL,'published',NULL,2,'2026-03-14 22:59:29','2026-03-14 22:59:29'),(62,18,'Chapter 1: quis dolores et',1,'Nihil nobis voluptate quia recusandae molestiae. Vero minima quia libero. Deserunt aut tenetur non incidunt. Praesentium illo libero nihil ut enim beatae.\n\nAtque odit reprehenderit quia est dicta praesentium corrupti. Ipsum repellat temporibus nulla harum sequi iure aut. Ab porro veritatis aliquid non et voluptatem. Repellat blanditiis dicta et necessitatibus.\n\nIncidunt sunt et eveniet beatae eveniet. Incidunt sit deserunt dolorem ea. Et consectetur nihil id totam eum.\n\nQui dolorum qui quisquam magni odio ut. Dignissimos magni aut voluptates voluptatem. Corrupti qui non deleniti doloribus voluptatum aut dolor facere.',0,0,1,NULL,NULL,NULL,'published',NULL,0,'2026-03-14 22:59:29','2026-03-14 22:59:29'),(63,18,'Chapter 2: sed aut dolor',2,'Beatae dolorem sed aut voluptatem sed. Reiciendis vero eum quos velit nihil inventore. Alias ex aliquid nemo et quis. Voluptatum id sint et et corporis.\n\nCumque est eos quas recusandae enim. Ex rem non optio sapiente at quam. Beatae expedita aut quae eaque eos culpa animi. Voluptas quas molestiae itaque culpa explicabo voluptates quis.\n\nCupiditate rem cupiditate et reprehenderit. Animi et et facilis. Voluptate iure neque natus neque quos.\n\nSoluta atque quia sunt sunt maiores sint sed ratione. Et totam voluptatum similique aut fugiat pariatur est. Veritatis mollitia alias aperiam voluptatum laudantium quo molestias eveniet. Id iure omnis nisi qui quidem sit.',0,0,1,NULL,NULL,NULL,'published',NULL,1,'2026-03-14 22:59:29','2026-03-14 22:59:29'),(64,18,'Chapter 3: eos libero voluptates',3,'Quas et velit error est. Dolorum nobis similique vel sunt minus. Excepturi dolorem eligendi officia quia. Est reiciendis consequuntur officia veritatis et.\n\nSed suscipit enim iste incidunt accusamus consequatur vel tempore. Perspiciatis mollitia perferendis quam voluptatum qui minima maiores. Dolor quia dicta et.\n\nVel iste amet inventore temporibus autem fugiat esse. Sed harum velit aliquid quia voluptate ex. Possimus nisi ex dolore iure voluptatem suscipit quis. Quidem saepe nihil rem quasi et consequatur possimus. Sit dolor id occaecati fuga unde adipisci nihil.\n\nQuia illo quis officia. Et expedita velit consequatur qui. Ducimus corporis voluptate doloremque est.',0,0,1,NULL,NULL,NULL,'draft',NULL,2,'2026-03-14 22:59:29','2026-03-14 22:59:29'),(65,18,'Chapter 4: at rerum sint',4,'Consequatur sed veritatis voluptate adipisci odit sed qui. Et aut ut enim itaque aliquam at esse. Qui recusandae ducimus et natus consequatur dolor dolor.\n\nCupiditate eum error itaque temporibus eum dolore. In corrupti architecto suscipit. Qui est doloribus praesentium corrupti et. Perspiciatis aut repudiandae ea quod vel fuga veniam.\n\nRerum porro ut enim tempore et maiores hic. Ea velit corrupti quia numquam corporis. Qui dicta rerum voluptatum perspiciatis in.\n\nQuia et quasi consequuntur repellat similique. Quia sapiente qui quas at voluptas voluptate velit laboriosam. Harum qui non est nulla repellendus commodi fuga.',0,0,1,NULL,NULL,NULL,'published',NULL,3,'2026-03-14 22:59:29','2026-03-14 22:59:29'),(70,24,'Aloo kaa pyarr monaa',1,'<p><br></p><p> </p><p>4</p><p>f</p><p>42</p><p>f2tvwv v wrg </p><p>23r 23r. r 32 r 32 r 2 r 32r. r32 r 32 r23 </p><p>f32. r23 r 23 r 23r3</p><p>--------------------------</p><p>vwe. wr</p><p><br></p><p>--------------------------</p><p>2 r 32r 32 r 23 r 23</p><p>f32 f23 23 r 32 r 32r. 23 r23 r 23 r 23 r 23 r 23</p><p>2fffg42f322 23. 32 r 32 </p><p>23 fe f 24 f32 fg43 g 43   r g 3 g 34g. 43 g 43 g34 g wr r w f</p><p>t43t35 r r eg.  t34</p><p>ef 2fr 32 r32 r32ft 43 t 4 t4 2 t24t r 23 r 23r 23 r 23 r 23 r23 r 23 r 23r 23 r 23</p>',79,1,1,NULL,NULL,NULL,'published',NULL,1,'2026-03-20 23:25:52','2026-03-20 23:34:38'),(71,24,'Untitled chapter',2,'<p><br></p>',0,1,1,NULL,NULL,NULL,'draft',NULL,2,'2026-03-20 23:27:34','2026-03-20 23:27:34'),(72,24,'Untitled chapter',3,'<p><br></p>',0,1,1,NULL,NULL,NULL,'draft',NULL,3,'2026-03-20 23:28:20','2026-03-20 23:28:20'),(73,25,'Allo or  oska Dhoka',1,'<p><br></p>',0,1,1,NULL,NULL,NULL,'draft',NULL,1,'2026-03-20 23:51:34','2026-03-20 23:51:39'),(74,25,'Untitled chapter',2,'<p><br></p>',0,1,1,NULL,NULL,NULL,'draft',NULL,2,'2026-03-21 00:03:18','2026-03-21 00:03:18'),(75,26,'n.  ml',1,'<p><br></p>',0,1,1,NULL,NULL,NULL,'draft',NULL,1,'2026-03-21 00:34:40','2026-03-21 01:40:44'),(76,27,'vfwrg3',1,'<p><br></p>',0,1,1,NULL,NULL,NULL,'published',NULL,1,'2026-03-21 23:24:46','2026-03-21 23:25:12'),(77,27,'Untitled chapter',2,'<p><br></p><p><br></p><p>--------------------------</p><p><br></p><p>--------------------------</p><p><br></p><p>--------------------------</p><p><img src=\"http://localhost/storage/stories/editor-images/04aVDxxJciRQnb1V2tWc5Y7mLvOtuXGoXPv5Btdr.png\"></p><p><img src=\"http://localhost/storage/stories/editor-images/Zp4fLeSJ4ROMZxXcEK7ssnhGyd6wPJRJ2REDJAcq.png\"></p><p><br></p><p>[ Continue Reading ]</p><p><br></p><p>--------------------------</p><p><br></p><p>--------------------------</p><p><br></p><p>--------------------------</p>',4,1,1,NULL,NULL,NULL,'draft',NULL,2,'2026-03-22 00:51:26','2026-03-22 00:52:41'),(78,28,'Ik Prem Khatha',1,'<p><br></p>',0,1,1,NULL,NULL,NULL,'published',NULL,1,'2026-03-22 00:54:00','2026-03-22 00:55:50'),(79,29,'Untitled chapter',1,'<p><br></p>',0,1,1,NULL,NULL,NULL,'draft',NULL,1,'2026-04-14 10:00:48','2026-04-14 10:00:48');
/*!40000 ALTER TABLE `story_chapters` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `story_comments`
--

DROP TABLE IF EXISTS `story_comments`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `story_comments` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint unsigned NOT NULL,
  `story_chapter_id` bigint unsigned NOT NULL,
  `parent_id` bigint unsigned DEFAULT NULL,
  `body` text COLLATE utf8mb4_unicode_ci,
  `line_number` int unsigned DEFAULT NULL,
  `start_index` int unsigned DEFAULT NULL,
  `end_index` int unsigned DEFAULT NULL,
  `selected_text` text COLLATE utf8mb4_unicode_ci,
  `reaction` enum('heart','fire','sad') COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` enum('visible','hidden','removed') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'visible',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `story_comments_user_id_foreign` (`user_id`),
  KEY `story_comments_parent_id_foreign` (`parent_id`),
  KEY `story_comments_story_chapter_id_status_index` (`story_chapter_id`,`status`),
  CONSTRAINT `story_comments_parent_id_foreign` FOREIGN KEY (`parent_id`) REFERENCES `story_comments` (`id`) ON DELETE CASCADE,
  CONSTRAINT `story_comments_story_chapter_id_foreign` FOREIGN KEY (`story_chapter_id`) REFERENCES `story_chapters` (`id`) ON DELETE CASCADE,
  CONSTRAINT `story_comments_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=48 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `story_comments`
--

LOCK TABLES `story_comments` WRITE;
/*!40000 ALTER TABLE `story_comments` DISABLE KEYS */;
INSERT INTO `story_comments` VALUES (15,8,36,NULL,'Unde velit cupiditate quia quae repudiandae nesciunt.',NULL,NULL,NULL,NULL,NULL,'visible','2026-03-14 22:59:29','2026-03-14 22:59:29'),(16,1,36,NULL,'Saepe qui nisi excepturi voluptas ab ullam alias debitis dignissimos.',NULL,NULL,NULL,NULL,NULL,'visible','2026-03-14 22:59:29','2026-03-14 22:59:29'),(17,9,37,NULL,'Sint aliquid saepe doloribus numquam quas illo saepe voluptate.',NULL,NULL,NULL,NULL,NULL,'visible','2026-03-14 22:59:29','2026-03-14 22:59:29'),(18,7,37,NULL,'Fuga tempora officia reiciendis deserunt laboriosam rerum architecto recusandae sapiente.',NULL,NULL,NULL,NULL,NULL,'visible','2026-03-14 22:59:29','2026-03-14 22:59:29'),(19,9,37,NULL,'Ab omnis ab nobis ea officia est et eos.',NULL,NULL,NULL,NULL,NULL,'visible','2026-03-14 22:59:29','2026-03-14 22:59:29'),(20,5,38,NULL,'Nihil quo aut dolorem voluptatem aut est nemo magni.',NULL,NULL,NULL,NULL,NULL,'visible','2026-03-14 22:59:29','2026-03-14 22:59:29'),(21,3,38,NULL,'Quis nulla ut rem nihil natus ut cum.',NULL,NULL,NULL,NULL,NULL,'visible','2026-03-14 22:59:29','2026-03-14 22:59:29'),(22,7,38,NULL,'Sit quo ut et nihil qui.',NULL,NULL,NULL,NULL,NULL,'visible','2026-03-14 22:59:29','2026-03-14 22:59:29'),(23,5,40,NULL,'Fugit incidunt quisquam numquam consequatur voluptas accusamus alias porro magnam et accusamus.',NULL,NULL,NULL,NULL,NULL,'visible','2026-03-14 22:59:29','2026-03-14 22:59:29'),(24,8,40,NULL,'Omnis qui incidunt error et non voluptas est cumque.',NULL,NULL,NULL,NULL,NULL,'visible','2026-03-14 22:59:29','2026-03-14 22:59:29'),(25,7,42,NULL,'Saepe beatae sapiente quo culpa recusandae quisquam explicabo recusandae nobis.',NULL,NULL,NULL,NULL,NULL,'visible','2026-03-14 22:59:29','2026-03-14 22:59:29'),(26,5,42,NULL,'Repellat dignissimos commodi numquam occaecati autem fugit quos.',NULL,NULL,NULL,NULL,NULL,'visible','2026-03-14 22:59:29','2026-03-14 22:59:29'),(27,4,42,NULL,'Nesciunt alias dolore esse quibusdam id itaque distinctio nam inventore.',NULL,NULL,NULL,NULL,NULL,'visible','2026-03-14 22:59:29','2026-03-14 22:59:29'),(28,6,47,NULL,'Tenetur maiores labore deserunt sequi rerum rerum debitis odit dolores.',NULL,NULL,NULL,NULL,NULL,'visible','2026-03-14 22:59:29','2026-03-14 22:59:29'),(29,2,47,NULL,'Est enim voluptatem est ex qui.',NULL,NULL,NULL,NULL,NULL,'visible','2026-03-14 22:59:29','2026-03-14 22:59:29'),(30,6,47,NULL,'Assumenda aut voluptatum quis quidem accusamus perferendis ut nostrum provident.',NULL,NULL,NULL,NULL,NULL,'visible','2026-03-14 22:59:29','2026-03-14 22:59:29'),(31,3,48,NULL,'Alias ex qui et quis nam veniam totam ut iure sed.',NULL,NULL,NULL,NULL,NULL,'visible','2026-03-14 22:59:29','2026-03-14 22:59:29'),(32,7,48,NULL,'Aut qui accusamus quo ut et.',NULL,NULL,NULL,NULL,NULL,'visible','2026-03-14 22:59:29','2026-03-14 22:59:29'),(33,8,48,NULL,'Qui doloribus perspiciatis quia et rem fugit.',NULL,NULL,NULL,NULL,NULL,'visible','2026-03-14 22:59:29','2026-03-14 22:59:29'),(34,2,52,NULL,'Non eveniet est et exercitationem facere ut aut.',NULL,NULL,NULL,NULL,NULL,'visible','2026-03-14 22:59:29','2026-03-14 22:59:29'),(35,6,52,NULL,'Provident nostrum cumque molestiae inventore laudantium nesciunt.',NULL,NULL,NULL,NULL,NULL,'visible','2026-03-14 22:59:29','2026-03-14 22:59:29'),(36,7,52,NULL,'Eum iure ipsum minima deserunt architecto eaque quam veniam iusto dolor.',NULL,NULL,NULL,NULL,NULL,'visible','2026-03-14 22:59:29','2026-03-14 22:59:29'),(37,7,57,NULL,'Natus aut veritatis quo non aut.',NULL,NULL,NULL,NULL,NULL,'visible','2026-03-14 22:59:29','2026-03-14 22:59:29'),(38,3,59,NULL,'Rerum nemo voluptate eos quae delectus corporis.',NULL,NULL,NULL,NULL,NULL,'visible','2026-03-14 22:59:29','2026-03-14 22:59:29'),(39,2,59,NULL,'Laudantium rerum explicabo ut voluptatem voluptas consequatur eaque repudiandae.',NULL,NULL,NULL,NULL,NULL,'visible','2026-03-14 22:59:29','2026-03-14 22:59:29'),(40,5,60,NULL,'Ducimus sequi totam rem ea quisquam commodi quae impedit maiores.',NULL,NULL,NULL,NULL,NULL,'visible','2026-03-14 22:59:29','2026-03-14 22:59:29'),(41,4,63,NULL,'Aut dolor pariatur minus quas et tenetur ut voluptas ullam.',NULL,NULL,NULL,NULL,NULL,'visible','2026-03-14 22:59:29','2026-03-14 22:59:29'),(42,4,63,NULL,'Voluptate quis consectetur perferendis ut repellendus.',NULL,NULL,NULL,NULL,NULL,'visible','2026-03-14 22:59:29','2026-03-14 22:59:29'),(43,7,63,NULL,'Mollitia voluptates nihil saepe in eaque vel beatae.',NULL,NULL,NULL,NULL,NULL,'visible','2026-03-14 22:59:29','2026-03-14 22:59:29'),(44,4,65,NULL,'Et autem consequatur rerum molestias ducimus nobis voluptas provident.',NULL,NULL,NULL,NULL,NULL,'visible','2026-03-14 22:59:29','2026-03-14 22:59:29'),(45,9,65,NULL,'Enim laboriosam culpa sit placeat sed et aut aut sed.',NULL,NULL,NULL,NULL,NULL,'visible','2026-03-14 22:59:29','2026-03-14 22:59:29'),(46,10,65,NULL,'Very godd',NULL,NULL,NULL,NULL,NULL,'visible','2026-03-14 23:13:30','2026-03-15 00:05:33');
/*!40000 ALTER TABLE `story_comments` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `story_likes`
--

DROP TABLE IF EXISTS `story_likes`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `story_likes` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint unsigned NOT NULL,
  `story_id` bigint unsigned DEFAULT NULL,
  `story_chapter_id` bigint unsigned DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `story_likes_user_story_unique` (`user_id`,`story_id`),
  UNIQUE KEY `story_likes_user_chapter_unique` (`user_id`,`story_chapter_id`),
  KEY `story_likes_story_id_foreign` (`story_id`),
  KEY `story_likes_story_chapter_id_foreign` (`story_chapter_id`),
  CONSTRAINT `story_likes_story_chapter_id_foreign` FOREIGN KEY (`story_chapter_id`) REFERENCES `story_chapters` (`id`) ON DELETE CASCADE,
  CONSTRAINT `story_likes_story_id_foreign` FOREIGN KEY (`story_id`) REFERENCES `stories` (`id`) ON DELETE CASCADE,
  CONSTRAINT `story_likes_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=44 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `story_likes`
--

LOCK TABLES `story_likes` WRITE;
/*!40000 ALTER TABLE `story_likes` DISABLE KEYS */;
INSERT INTO `story_likes` VALUES (17,1,11,NULL,'2026-03-14 22:59:29','2026-03-14 22:59:29'),(18,2,11,NULL,'2026-03-14 22:59:29','2026-03-14 22:59:29'),(19,1,13,NULL,'2026-03-14 22:59:29','2026-03-14 22:59:29'),(20,3,13,NULL,'2026-03-14 22:59:29','2026-03-14 22:59:29'),(21,1,14,NULL,'2026-03-14 22:59:29','2026-03-14 22:59:29'),(22,1,15,NULL,'2026-03-14 22:59:29','2026-03-14 22:59:29'),(23,1,17,NULL,'2026-03-14 22:59:29','2026-03-14 22:59:29'),(24,2,17,NULL,'2026-03-14 22:59:29','2026-03-14 22:59:29'),(25,3,17,NULL,'2026-03-14 22:59:29','2026-03-14 22:59:29'),(33,8,NULL,37,'2026-03-14 22:59:29','2026-03-14 22:59:29'),(34,7,NULL,38,'2026-03-14 22:59:29','2026-03-14 22:59:29'),(35,6,NULL,42,'2026-03-14 22:59:29','2026-03-14 22:59:29'),(36,9,NULL,49,'2026-03-14 22:59:29','2026-03-14 22:59:29'),(37,1,NULL,57,'2026-03-14 22:59:29','2026-03-14 22:59:29'),(38,5,NULL,63,'2026-03-14 22:59:29','2026-03-14 22:59:29'),(39,4,NULL,65,'2026-03-14 22:59:29','2026-03-14 22:59:29'),(40,10,18,NULL,'2026-03-14 23:11:46','2026-03-14 23:11:46'),(41,10,24,NULL,'2026-03-20 23:33:05','2026-03-20 23:33:05'),(42,10,NULL,70,'2026-03-20 23:35:00','2026-03-20 23:35:00'),(43,10,NULL,78,'2026-03-22 00:56:57','2026-03-22 00:56:57');
/*!40000 ALTER TABLE `story_likes` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `story_reads`
--

DROP TABLE IF EXISTS `story_reads`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `story_reads` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint unsigned NOT NULL,
  `story_id` bigint unsigned NOT NULL,
  `story_chapter_id` bigint unsigned DEFAULT NULL,
  `read_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `story_reads_story_chapter_id_foreign` (`story_chapter_id`),
  KEY `story_reads_user_id_story_id_index` (`user_id`,`story_id`),
  KEY `story_reads_story_id_read_at_index` (`story_id`,`read_at`),
  CONSTRAINT `story_reads_story_chapter_id_foreign` FOREIGN KEY (`story_chapter_id`) REFERENCES `story_chapters` (`id`) ON DELETE CASCADE,
  CONSTRAINT `story_reads_story_id_foreign` FOREIGN KEY (`story_id`) REFERENCES `stories` (`id`) ON DELETE CASCADE,
  CONSTRAINT `story_reads_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=48 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `story_reads`
--

LOCK TABLES `story_reads` WRITE;
/*!40000 ALTER TABLE `story_reads` DISABLE KEYS */;
INSERT INTO `story_reads` VALUES (14,7,10,36,'2026-03-14 22:59:29'),(15,4,10,37,'2026-03-14 22:59:29'),(16,1,11,40,'2026-03-14 22:59:29'),(17,1,12,42,'2026-03-14 22:59:29'),(18,4,13,44,'2026-03-14 22:59:29'),(19,6,13,47,'2026-03-14 22:59:29'),(20,9,13,48,'2026-03-14 22:59:29'),(21,6,14,49,'2026-03-14 22:59:29'),(22,7,15,53,'2026-03-14 22:59:29'),(23,5,15,55,'2026-03-14 22:59:29'),(24,4,16,57,'2026-03-14 22:59:29'),(25,5,17,59,'2026-03-14 22:59:29'),(26,4,17,60,'2026-03-14 22:59:29'),(27,5,17,61,'2026-03-14 22:59:29'),(28,5,18,62,'2026-03-14 22:59:29'),(29,5,18,63,'2026-03-14 22:59:29'),(30,7,18,65,'2026-03-14 22:59:29'),(31,10,18,62,'2026-03-15 04:41:58'),(32,10,18,63,'2026-03-15 04:42:01'),(33,10,18,65,'2026-03-15 04:42:02'),(34,1,18,65,'2026-03-15 05:35:16'),(38,10,17,60,'2026-03-21 04:53:00'),(39,10,17,59,'2026-03-21 04:53:03'),(40,10,24,70,'2026-03-21 05:04:49'),(41,10,13,44,'2026-03-21 05:15:18'),(42,10,13,47,'2026-03-21 05:15:21'),(43,10,13,48,'2026-03-21 05:15:23'),(44,10,14,52,'2026-03-22 04:57:48'),(45,10,14,49,'2026-03-22 04:57:51'),(46,10,28,78,'2026-03-22 06:26:49'),(47,11,28,78,'2026-04-08 05:54:06');
/*!40000 ALTER TABLE `story_reads` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `story_reports`
--

DROP TABLE IF EXISTS `story_reports`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `story_reports` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint unsigned NOT NULL,
  `story_id` bigint unsigned NOT NULL,
  `reason` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `details` text COLLATE utf8mb4_unicode_ci,
  `status` enum('pending','reviewed','dismissed') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pending',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `story_reports_user_id_story_id_unique` (`user_id`,`story_id`),
  KEY `story_reports_story_id_foreign` (`story_id`),
  CONSTRAINT `story_reports_story_id_foreign` FOREIGN KEY (`story_id`) REFERENCES `stories` (`id`) ON DELETE CASCADE,
  CONSTRAINT `story_reports_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `story_reports`
--

LOCK TABLES `story_reports` WRITE;
/*!40000 ALTER TABLE `story_reports` DISABLE KEYS */;
INSERT INTO `story_reports` VALUES (3,4,13,'Other','Laborum aspernatur deserunt impedit.','pending','2026-03-14 22:59:29','2026-03-14 22:59:29');
/*!40000 ALTER TABLE `story_reports` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `users` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `username` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `phone` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `dob` date DEFAULT NULL,
  `pronouns` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `google_id` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `is_verified` tinyint(1) NOT NULL DEFAULT '0',
  `otp` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `otp_expires_at` timestamp NULL DEFAULT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `avatar` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `bio` text COLLATE utf8mb4_unicode_ci,
  `status` enum('active','blocked') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'active',
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `reset_otp` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `reset_otp_expires_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_email_unique` (`email`),
  UNIQUE KEY `users_username_unique` (`username`)
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users`
--

LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
INSERT INTO `users` VALUES (1,'Admin User','admin','admin@souldiaries.com',NULL,NULL,NULL,NULL,1,NULL,NULL,'2026-03-14 22:59:27','$2y$12$OLhjFwzOksacEAgsfj1BT.fi2voAVyMR4F1YSWp2xvMf1KU9.S7Bi',NULL,NULL,'active','MCxtBruzMeMvxy6vz4lxtgYgrEbFrYbMPLpPkqm6sjAdSx4KeyR0bhZl4cfW','2026-03-14 22:59:27','2026-03-14 22:59:27',NULL,NULL),(2,'Rahul Barton','writer115xc','writer1@souldiaries.com',NULL,NULL,NULL,NULL,0,NULL,NULL,'2026-03-14 22:59:28','$2y$12$ZIENIYbr6raFZewAUgMjkedPqRqSIkM.nysj4ndsb51Awm7Je6Uq2',NULL,'Exercitationem hic enim quis earum voluptatem similique labore nisi aut.','active',NULL,'2026-03-14 22:59:28','2026-03-14 22:59:28',NULL,NULL),(3,'Prof. Jarvis Torp','writer2ZqY2','writer2@souldiaries.com',NULL,NULL,NULL,NULL,0,NULL,NULL,'2026-03-14 22:59:28','$2y$12$F70wnfcXWvaLu061lSiaUehft.qm2zPLgDU4hmuMO4mim/vuVzOBG',NULL,'Fuga eaque ut quidem aut delectus officia et ducimus ipsam omnis.','active',NULL,'2026-03-14 22:59:28','2026-03-14 22:59:28',NULL,NULL),(4,'Miss Kacie Hammes','writer3SvrG','writer3@souldiaries.com',NULL,NULL,NULL,NULL,0,NULL,NULL,'2026-03-14 22:59:28','$2y$12$lIZMsDQK/Fsiy7lC8mN6OuIY5w1PtH0qfP.tTb.FCR.dD6EXwCl0K',NULL,'Aperiam id quos autem quam animi modi quibusdam laudantium qui.','active',NULL,'2026-03-14 22:59:28','2026-03-14 22:59:28',NULL,NULL),(5,'Olaf Goldner','writer4AY6z','writer4@souldiaries.com',NULL,NULL,NULL,NULL,0,NULL,NULL,'2026-03-14 22:59:28','$2y$12$/gM.mP7IolEGFY/A.QWrc.R8Mm94AU3yGvS2kVAHj6.KHKSH5Ca6W',NULL,'Odio culpa quidem rerum eius eum incidunt dicta.','active',NULL,'2026-03-14 22:59:28','2026-03-14 22:59:28',NULL,NULL),(6,'Louvenia Walsh','writer58mQM','writer5@souldiaries.com',NULL,NULL,NULL,NULL,0,NULL,NULL,'2026-03-14 22:59:29','$2y$12$nOGmP2KOCvre.YGVFzxFM.Gpn/SNSthRwNMzEnIQMjq3USqo9t5ne',NULL,'Aut modi earum aut necessitatibus debitis.','active',NULL,'2026-03-14 22:59:29','2026-03-14 22:59:29',NULL,NULL),(7,'Dayana Abbott','writer6ZI3s','writer6@souldiaries.com',NULL,NULL,NULL,NULL,0,NULL,NULL,'2026-03-14 22:59:29','$2y$12$kRPACbwapo6ZNJvGG5vGNurYjVyvBhcWW5dBqSGMxcI/J.j5uPlSq',NULL,'Dolore ipsum sunt repudiandae quibusdam voluptatibus modi perferendis.','active',NULL,'2026-03-14 22:59:29','2026-03-14 22:59:29',NULL,NULL),(8,'Reymundo Kunze','writer7dalr','writer7@souldiaries.com',NULL,NULL,NULL,NULL,0,NULL,NULL,'2026-03-14 22:59:29','$2y$12$wHyD/cnv6TyU2VeTTkxwo.qam6aWB/hDm.JHQTysrcGwFxU5XPEw6',NULL,'Voluptatem deleniti consequuntur illo libero neque error veniam dolores ut laboriosam voluptatem.','active',NULL,'2026-03-14 22:59:29','2026-03-14 22:59:29',NULL,NULL),(9,'Juana Auer','writer8jPiO','writer8@souldiaries.com',NULL,NULL,NULL,NULL,0,NULL,NULL,'2026-03-14 22:59:29','$2y$12$IN1D/qTsDPFnxTPvkwY8z.Vpqsvu7P8QXSJz9kacVBpuLVy9z.YIm',NULL,'Sint mollitia dolorem consequuntur consequuntur quo sed velit voluptates facere.','active',NULL,'2026-03-14 22:59:29','2026-03-14 22:59:29',NULL,NULL),(10,'Himanshu Mehra','EllyMans','manshu.developer@gmail.com','07988532993','2002-09-22','She/Her',NULL,1,NULL,NULL,NULL,'$2y$12$AfTETNQv3mKeAawIjfTa7eETz4d8Ckq7sylYpwDWNnoTIUzbiDWPK','images/avtars/avtar-28.png',NULL,'active',NULL,'2026-03-14 23:01:11','2026-04-14 10:01:32',NULL,NULL),(11,'Himanshu Mehra','wacuf','manshusmartboy@gmail.com','7988532993','2001-03-02','They/Them',NULL,1,NULL,NULL,NULL,'$2y$12$iqGP0x088nwiFBIYdJTg9OuQoglv7POVme6R/V6wEAmFk.3V4SNGC','images/avtars/avtar-9.png',NULL,'active',NULL,'2026-04-08 00:23:36','2026-04-08 00:24:33',NULL,NULL);
/*!40000 ALTER TABLE `users` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `views`
--

DROP TABLE IF EXISTS `views`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `views` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint unsigned DEFAULT NULL,
  `content_id` bigint unsigned NOT NULL,
  `ip_address` varchar(45) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `views_user_id_foreign` (`user_id`),
  KEY `views_content_id_foreign` (`content_id`),
  CONSTRAINT `views_content_id_foreign` FOREIGN KEY (`content_id`) REFERENCES `contents` (`id`) ON DELETE CASCADE,
  CONSTRAINT `views_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `views`
--

LOCK TABLES `views` WRITE;
/*!40000 ALTER TABLE `views` DISABLE KEYS */;
/*!40000 ALTER TABLE `views` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `votes`
--

DROP TABLE IF EXISTS `votes`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `votes` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint unsigned NOT NULL,
  `content_id` bigint unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `votes_user_id_foreign` (`user_id`),
  KEY `votes_content_id_foreign` (`content_id`),
  CONSTRAINT `votes_content_id_foreign` FOREIGN KEY (`content_id`) REFERENCES `contents` (`id`) ON DELETE CASCADE,
  CONSTRAINT `votes_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `votes`
--

LOCK TABLES `votes` WRITE;
/*!40000 ALTER TABLE `votes` DISABLE KEYS */;
/*!40000 ALTER TABLE `votes` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2026-04-14 21:16:14
