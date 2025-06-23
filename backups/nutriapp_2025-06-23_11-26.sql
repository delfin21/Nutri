-- MySQL dump 10.13  Distrib 8.0.42, for Linux (x86_64)
--
-- Host: nutriapp-do-user-23340847-0.j.db.ondigitalocean.com    Database: defaultdb
-- ------------------------------------------------------
-- Server version	8.0.35

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
SET @MYSQLDUMP_TEMP_LOG_BIN = @@SESSION.SQL_LOG_BIN;
SET @@SESSION.SQL_LOG_BIN= 0;

--
-- GTID state at the beginning of the backup 
--

SET @@GLOBAL.GTID_PURGED=/*!80000 '+'*/ 'bf681782-4de3-11f0-a91f-6a86da2af667:1-995';

--
-- Table structure for table `admin_activity_logs`
--

DROP TABLE IF EXISTS `admin_activity_logs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `admin_activity_logs` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `admin_id` bigint unsigned NOT NULL,
  `action` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `ip_address` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `user_agent` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `admin_activity_logs`
--

LOCK TABLES `admin_activity_logs` WRITE;
/*!40000 ALTER TABLE `admin_activity_logs` DISABLE KEYS */;
INSERT INTO `admin_activity_logs` VALUES (1,6,'Toggled Status','Changed status for user: buyer@gmail.com','64.224.104.206','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/137.0.0.0 Safari/537.36','2025-06-21 21:34:54','2025-06-21 21:34:54'),(2,6,'Exported Users','Exported user list','64.224.104.206','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/137.0.0.0 Safari/537.36','2025-06-21 21:35:05','2025-06-21 21:35:05');
/*!40000 ALTER TABLE `admin_activity_logs` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `admins`
--

DROP TABLE IF EXISTS `admins`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `admins` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `role` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `mobile` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `image` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `admins`
--

LOCK TABLES `admins` WRITE;
/*!40000 ALTER TABLE `admins` DISABLE KEYS */;
/*!40000 ALTER TABLE `admins` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `audit_logs`
--

DROP TABLE IF EXISTS `audit_logs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `audit_logs` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint unsigned NOT NULL,
  `admin_id` bigint unsigned NOT NULL,
  `field` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `old_value` text COLLATE utf8mb4_unicode_ci,
  `new_value` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `audit_logs`
--

LOCK TABLES `audit_logs` WRITE;
/*!40000 ALTER TABLE `audit_logs` DISABLE KEYS */;
INSERT INTO `audit_logs` VALUES (1,5,6,'status','active','inactive','2025-06-21 21:34:54','2025-06-21 21:34:54');
/*!40000 ALTER TABLE `audit_logs` ENABLE KEYS */;
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
  PRIMARY KEY (`key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cache`
--

LOCK TABLES `cache` WRITE;
/*!40000 ALTER TABLE `cache` DISABLE KEYS */;
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
  PRIMARY KEY (`key`)
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
-- Table structure for table `carts`
--

DROP TABLE IF EXISTS `carts`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `carts` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint unsigned DEFAULT NULL,
  `buyer_id` bigint unsigned NOT NULL,
  `product_id` bigint unsigned NOT NULL,
  `quantity` int NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `carts`
--

LOCK TABLES `carts` WRITE;
/*!40000 ALTER TABLE `carts` DISABLE KEYS */;
INSERT INTO `carts` VALUES (2,NULL,4,1,1,'2025-06-21 17:41:30','2025-06-21 17:41:30'),(4,NULL,9,1,1,'2025-06-23 07:37:04','2025-06-23 07:37:04');
/*!40000 ALTER TABLE `carts` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `categories`
--

DROP TABLE IF EXISTS `categories`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `categories` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `categories`
--

LOCK TABLES `categories` WRITE;
/*!40000 ALTER TABLE `categories` DISABLE KEYS */;
/*!40000 ALTER TABLE `categories` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `conversations`
--

DROP TABLE IF EXISTS `conversations`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `conversations` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `buyer_id` bigint unsigned NOT NULL,
  `farmer_id` bigint unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `conversations_buyer_id_farmer_id_unique` (`buyer_id`,`farmer_id`),
  KEY `conversations_farmer_id_foreign` (`farmer_id`),
  CONSTRAINT `conversations_buyer_id_foreign` FOREIGN KEY (`buyer_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  CONSTRAINT `conversations_farmer_id_foreign` FOREIGN KEY (`farmer_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `conversations`
--

LOCK TABLES `conversations` WRITE;
/*!40000 ALTER TABLE `conversations` DISABLE KEYS */;
INSERT INTO `conversations` VALUES (1,3,1,'2025-06-21 10:49:16','2025-06-21 10:49:16'),(2,4,1,'2025-06-21 20:03:35','2025-06-23 13:17:47');
/*!40000 ALTER TABLE `conversations` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `farmer_documents`
--

DROP TABLE IF EXISTS `farmer_documents`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `farmer_documents` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `farmer_id` bigint unsigned NOT NULL,
  `document_path` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pending',
  `submitted_at` timestamp NULL DEFAULT NULL,
  `admin_note` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `farmer_documents`
--

LOCK TABLES `farmer_documents` WRITE;
/*!40000 ALTER TABLE `farmer_documents` DISABLE KEYS */;
INSERT INTO `farmer_documents` VALUES (1,1,'verification_documents/QBKBQCuwS3E2YjdqvYL9zLcG4xVvRfcnyi5U9F5a.jpg','approved',NULL,NULL,'2025-06-21 14:03:57','2025-06-21 14:06:57'),(2,7,'verification_docs/ZtMxRpv0i8Agqq3cmIB5yU4FOOsH46rbe7KoylTW.jpg','approved','2025-06-22 13:42:57',NULL,'2025-06-22 13:28:25','2025-06-22 13:42:57'),(3,7,'verification_docs/K4Hn2kMUI2PSGkk2huJsfYh4EtM5MnDcvQcY59Xe.jpg','rejected',NULL,'wrong document','2025-06-22 13:43:11','2025-06-22 13:47:20');
/*!40000 ALTER TABLE `farmer_documents` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `farmer_verification_requests`
--

DROP TABLE IF EXISTS `farmer_verification_requests`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `farmer_verification_requests` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint unsigned NOT NULL,
  `document_path` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pending',
  `submitted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `farmer_verification_requests_user_id_foreign` (`user_id`),
  CONSTRAINT `farmer_verification_requests_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `farmer_verification_requests`
--

LOCK TABLES `farmer_verification_requests` WRITE;
/*!40000 ALTER TABLE `farmer_verification_requests` DISABLE KEYS */;
/*!40000 ALTER TABLE `farmer_verification_requests` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `follows`
--

DROP TABLE IF EXISTS `follows`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `follows` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `follower_id` bigint unsigned NOT NULL,
  `followed_id` bigint unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `follows`
--

LOCK TABLES `follows` WRITE;
/*!40000 ALTER TABLE `follows` DISABLE KEYS */;
/*!40000 ALTER TABLE `follows` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `messages`
--

DROP TABLE IF EXISTS `messages`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `messages` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `conversation_id` bigint unsigned DEFAULT NULL,
  `sender_id` bigint unsigned NOT NULL,
  `receiver_id` bigint unsigned NOT NULL,
  `message` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `is_read` tinyint(1) NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `read_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `messages_conversation_id_foreign` (`conversation_id`),
  CONSTRAINT `messages_conversation_id_foreign` FOREIGN KEY (`conversation_id`) REFERENCES `conversations` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `messages`
--

LOCK TABLES `messages` WRITE;
/*!40000 ALTER TABLE `messages` DISABLE KEYS */;
INSERT INTO `messages` VALUES (1,1,1,3,'hi',0,'2025-06-21 18:29:21','2025-06-21 18:29:21',NULL),(2,NULL,1,4,'hi',0,'2025-06-21 18:49:46','2025-06-21 18:49:46',NULL),(3,1,1,3,'hello',0,'2025-06-21 20:02:59','2025-06-21 20:02:59',NULL),(4,2,1,4,'hello',0,'2025-06-21 20:03:39','2025-06-21 20:03:39',NULL),(5,NULL,5,1,'hekllo',0,'2025-06-21 20:07:51','2025-06-21 20:07:51',NULL),(6,2,1,4,'hi hehe',0,'2025-06-23 13:17:47','2025-06-23 13:17:47',NULL);
/*!40000 ALTER TABLE `messages` ENABLE KEYS */;
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
) ENGINE=InnoDB AUTO_INCREMENT=16 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `migrations`
--

LOCK TABLES `migrations` WRITE;
/*!40000 ALTER TABLE `migrations` DISABLE KEYS */;
INSERT INTO `migrations` VALUES (1,'2025_06_19_082930_create_conversations_table',1),(2,'2025_06_20_092722_add_conversation_id_to_messages_table',2),(3,'2025_06_20_102557_make_evidence_path_nullable_in_return_requests',3),(4,'2025_06_20_140050_add_tracking_and_resolution_to_return_requests_table',4),(5,'2025_06_20_143550_add_farmer_evidence_to_return_requests_table',5),(6,'2025_06_20_164705_add_final_resolution_to_return_requests_table',6),(7,'2025_06_20_171232_add_replacement_tracking_to_return_requests_table',7),(8,'2025_06_20_173014_create_store_credits_table',8),(9,'2025_06_20_174622_add_preorder_fields_to_products_table',9),(10,'2025_06_20_214415_add_reference_id_to_payments_table',10),(11,'2025_06_20_221137_update_payments_make_order_ids_json',11),(12,'2025_06_20_210606_add_order_id_and_payload_to_payments_table',12),(13,'2025_06_22_004640_add_verification_columns_to_users_table',13),(14,'2025_06_22_010045_create_farmer_verification_requests_table',14),(15,'2025_06_22_133726_add_submitted_at_to_farmer_documents_table',15);
/*!40000 ALTER TABLE `migrations` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `notifications`
--

DROP TABLE IF EXISTS `notifications`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `notifications` (
  `id` char(36) COLLATE utf8mb4_unicode_ci NOT NULL,
  `type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `notifiable_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `notifiable_id` bigint unsigned NOT NULL,
  `data` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `read_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `notifications`
--

LOCK TABLES `notifications` WRITE;
/*!40000 ALTER TABLE `notifications` DISABLE KEYS */;
INSERT INTO `notifications` VALUES ('00586d56-fbaa-492f-9af0-0c2d515d8f2d','App\\Notifications\\FarmerOrderPlacedNotification','App\\Models\\User',1,'{\"title\":\"New Order Received\",\"body\":\"You have a new order for <strong>Banana (Saba)<\\/strong> from <strong>smith<\\/strong>.\",\"type\":\"farmer_order\",\"senderName\":\"smith\"}',NULL,'2025-06-23 08:12:39','2025-06-23 08:12:39'),('033b87da-2e01-405c-ad75-7a803c127f09','App\\Notifications\\FarmerVerificationStatusNotification','App\\Models\\User',7,'{\"title\":\"Verification Approved\",\"message\":\"Your farmer account has been verified. You can now list products.\",\"icon\":\"bi-check-circle-fill\",\"type\":\"verification\"}',NULL,'2025-06-22 13:42:57','2025-06-22 13:42:57'),('0a9477a4-aa6e-4fb0-9dde-971935f61b2b','App\\Notifications\\AdminAlertNotification','App\\Models\\User',2,'{\"message\":\"New user registered: asfernandez401@gmail.com\",\"icon\":\"bi-person\",\"link\":\"https:\\/\\/nutriapp.shop\\/admin\\/users\",\"type\":\"registration\"}','2025-06-21 18:30:11','2025-06-21 17:41:10','2025-06-21 18:30:11'),('11e4fb74-2b2e-4a59-ad61-7f17f2e9c054','App\\Notifications\\AdminAlertNotification','App\\Models\\User',2,'{\"message\":\"Order ORD-UNK-250623-F7-0001 has been placed by smith\",\"icon\":\"bi-cart-check\",\"link\":\"https:\\/\\/nutriapp.shop\\/admin\\/orders?highlight_order=3\",\"type\":\"order\"}','2025-06-23 09:56:31','2025-06-23 08:12:40','2025-06-23 09:56:31'),('275f1eb6-0e0c-4d37-8544-82dd80d8300b','App\\Notifications\\AdminAlertNotification','App\\Models\\User',2,'{\"message\":\"New user registered: ayaya@buyer.com\",\"icon\":\"bi-person\",\"link\":\"https:\\/\\/nutriapp.shop\\/admin\\/users\",\"type\":\"registration\"}',NULL,'2025-06-23 07:04:58','2025-06-23 07:04:58'),('311fcb73-c11f-49bf-bac7-e608aab24826','App\\Notifications\\NewMessageNotification','App\\Models\\User',3,'{\"message\":\"<i class=\'bi bi-chat-dots\'><\\/i> New message from <strong>mori<\\/strong>\",\"type\":\"message\",\"link\":\"\\/buyer\\/messages\",\"senderName\":\"mori\"}','2025-06-22 13:50:48','2025-06-21 20:02:59','2025-06-22 13:50:48'),('33db4484-9300-47ec-839c-aba73177a10b','App\\Notifications\\AdminAlertNotification','App\\Models\\User',6,'{\"message\":\"Order ORD-UNK-250623-F7-0001 has been placed by smith\",\"icon\":\"bi-cart-check\",\"link\":\"https:\\/\\/nutriapp.shop\\/admin\\/orders?highlight_order=3\",\"type\":\"order\"}',NULL,'2025-06-23 08:12:40','2025-06-23 08:12:40'),('4954caeb-7d72-4575-ac1b-dcdd5bf845a1','App\\Notifications\\AdminAlertNotification','App\\Models\\User',2,'{\"message\":\"New user registered: policarpioian2003@gmail.com\",\"icon\":\"bi-person\",\"link\":\"https:\\/\\/nutriapp.shop\\/admin\\/users\",\"type\":\"registration\"}',NULL,'2025-06-23 07:33:17','2025-06-23 07:33:17'),('4fd0db93-d578-4509-90f5-7a5eaa256433','App\\Notifications\\AdminAlertNotification','App\\Models\\User',2,'{\"message\":\"Order ORD-CAV-250621-F1-0001 has been placed by smith\",\"icon\":\"bi-cart-check\",\"link\":\"https:\\/\\/nutriapp.shop\\/admin\\/orders?highlight_order=1\",\"type\":\"order\"}',NULL,'2025-06-21 17:14:36','2025-06-21 17:14:36'),('51787120-a7b5-41cf-b688-f1bdc3eccd84','App\\Notifications\\ProductApprovedNotification','App\\Models\\User',1,'{\"title\":\"Product Approved \\u2705\",\"message\":\"Your product <strong>Banana (Saba)<\\/strong> has been approved and is now live on the marketplace.\",\"link\":\"https:\\/\\/nutriapp.shop\\/farmer\\/products\",\"icon\":\"bi-check-circle-fill\"}','2025-06-21 14:19:13','2025-06-21 14:08:17','2025-06-21 14:19:13'),('6491a210-c133-479a-8657-51006b763831','App\\Notifications\\FarmerVerificationStatusNotification','App\\Models\\User',7,'{\"title\":\"Verification Rejected\",\"message\":\"Your verification was rejected. Reason: wrong document\",\"icon\":\"bi-x-circle-fill\",\"type\":\"verification\"}',NULL,'2025-06-22 13:47:20','2025-06-22 13:47:20'),('671e7f2b-972d-4fdf-9d3c-8622e68ef385','App\\Notifications\\NewMessageNotification','App\\Models\\User',1,'{\"message\":\"<i class=\'bi bi-chat-dots\'><\\/i> New message from <strong>buyer<\\/strong>\",\"type\":\"message\",\"link\":\"\\/buyer\\/messages\",\"senderName\":\"buyer\"}',NULL,'2025-06-21 20:07:51','2025-06-21 20:07:51'),('67cbb285-6b72-4116-9ddc-5fb876f46159','App\\Notifications\\FarmerOrderPlacedNotification','App\\Models\\User',1,'{\"title\":\"New Order Received\",\"body\":\"You have a new order for <strong>Banana (Saba)<\\/strong> from <strong>smith<\\/strong>.\",\"type\":\"farmer_order\",\"senderName\":\"smith\"}',NULL,'2025-06-21 17:14:36','2025-06-21 17:14:36'),('6b5f97c8-c56b-4869-9fde-f4576f32d9bf','App\\Notifications\\NewMessageNotification','App\\Models\\User',4,'{\"message\":\"<i class=\'bi bi-chat-dots\'><\\/i> New message from <strong>mori<\\/strong>\",\"type\":\"message\",\"link\":\"\\/buyer\\/messages\",\"senderName\":\"mori\"}',NULL,'2025-06-21 18:49:46','2025-06-21 18:49:46'),('7980c3bc-522c-445e-916e-e7ab41a372af','App\\Notifications\\ProductRejectedNotification','App\\Models\\User',1,'{\"title\":\"Product Rejected \\u274c\",\"message\":\"Your product <strong>Banana<\\/strong> was rejected. Please review and resubmit if needed.\",\"link\":\"https:\\/\\/nutriapp.shop\\/farmer\\/products\",\"icon\":\"bi-x-circle-fill\"}',NULL,'2025-06-21 17:11:32','2025-06-21 17:11:32'),('86c34317-8327-40be-9ec9-82f7140a5a29','App\\Notifications\\AdminAlertNotification','App\\Models\\User',6,'{\"message\":\"Order ORD-CAV-250623-F1-0001 has been placed by smith\",\"icon\":\"bi-cart-check\",\"link\":\"https:\\/\\/nutriapp.shop\\/admin\\/orders?highlight_order=2\",\"type\":\"order\"}',NULL,'2025-06-23 08:12:39','2025-06-23 08:12:39'),('95c08c3a-4b5c-45c0-bd34-bdddae919aa0','App\\Notifications\\AdminAlertNotification','App\\Models\\User',2,'{\"message\":\"New user registered: delfin@farmer.com\",\"icon\":\"bi-person\",\"link\":\"https:\\/\\/nutriapp.shop\\/admin\\/users\",\"type\":\"registration\"}',NULL,'2025-06-22 00:57:57','2025-06-22 00:57:57'),('98a3ca84-903e-468d-b518-ee653f45d57d','App\\Notifications\\FarmerOrderPlacedNotification','App\\Models\\User',7,'{\"title\":\"New Order Received\",\"body\":\"You have a new order for <strong>Apple<\\/strong> from <strong>smith<\\/strong>.\",\"type\":\"farmer_order\",\"senderName\":\"smith\"}',NULL,'2025-06-23 08:12:40','2025-06-23 08:12:40'),('a8545127-c3ba-4228-bea6-d7f8d10ebd80','App\\Notifications\\AdminAlertNotification','App\\Models\\User',2,'{\"message\":\"New user registered: smith@buyer.com\",\"icon\":\"bi-person\",\"link\":\"https:\\/\\/nutriapp.shop\\/admin\\/users\",\"type\":\"registration\"}',NULL,'2025-06-21 14:08:55','2025-06-21 14:08:55'),('bcd2f2e6-2721-4340-8736-a6c9ba9500d2','App\\Notifications\\AdminAlertNotification','App\\Models\\User',6,'{\"message\":\"New user registered: policarpioian2003@gmail.com\",\"icon\":\"bi-person\",\"link\":\"https:\\/\\/nutriapp.shop\\/admin\\/users\",\"type\":\"registration\"}',NULL,'2025-06-23 07:33:17','2025-06-23 07:33:17'),('c22d8d20-acae-4ec9-b20d-c5cff474da60','App\\Notifications\\NewMessageNotification','App\\Models\\User',3,'{\"message\":\"<i class=\'bi bi-chat-dots\'><\\/i> New message from <strong>mori<\\/strong>\",\"type\":\"message\",\"link\":\"\\/buyer\\/messages\",\"senderName\":\"mori\"}','2025-06-22 13:50:48','2025-06-21 18:29:21','2025-06-22 13:50:48'),('cd227270-eb00-40c2-ab57-24c6b3d0c7f4','App\\Notifications\\ProductApprovedNotification','App\\Models\\User',1,'{\"title\":\"Product Approved \\u2705\",\"message\":\"Your product <strong>Mango (Carabao)<\\/strong> has been approved and is now live on the marketplace.\",\"link\":\"https:\\/\\/nutriapp.shop\\/farmer\\/products\",\"icon\":\"bi-check-circle-fill\"}',NULL,'2025-06-23 09:57:00','2025-06-23 09:57:00'),('d3e7d784-53a6-4274-9f1b-de944f21d628','App\\Notifications\\ProductApprovedNotification','App\\Models\\User',7,'{\"title\":\"Product Approved \\u2705\",\"message\":\"Your product <strong>Apple<\\/strong> has been approved and is now live on the marketplace.\",\"link\":\"https:\\/\\/nutriapp.shop\\/farmer\\/products\",\"icon\":\"bi-check-circle-fill\"}',NULL,'2025-06-22 13:50:55','2025-06-22 13:50:55'),('d8309630-1ede-4e9e-a471-de49de279063','App\\Notifications\\AdminAlertNotification','App\\Models\\User',6,'{\"message\":\"New user registered: ayaya@buyer.com\",\"icon\":\"bi-person\",\"link\":\"https:\\/\\/nutriapp.shop\\/admin\\/users\",\"type\":\"registration\"}',NULL,'2025-06-23 07:04:58','2025-06-23 07:04:58'),('db32e245-96c4-46f3-9b60-984590ae2136','App\\Notifications\\AdminAlertNotification','App\\Models\\User',2,'{\"message\":\"Order ORD-CAV-250623-F1-0001 has been placed by smith\",\"icon\":\"bi-cart-check\",\"link\":\"https:\\/\\/nutriapp.shop\\/admin\\/orders?highlight_order=2\",\"type\":\"order\"}',NULL,'2025-06-23 08:12:39','2025-06-23 08:12:39'),('de5461f0-70fd-4f56-9633-7df54c53e10f','App\\Notifications\\AdminAlertNotification','App\\Models\\User',2,'{\"message\":\"New user registered: buyer@gmail.com\",\"icon\":\"bi-person\",\"link\":\"https:\\/\\/nutriapp.shop\\/admin\\/users\",\"type\":\"registration\"}',NULL,'2025-06-21 20:07:43','2025-06-21 20:07:43'),('e4ccd2b6-e04a-48d4-b445-773d6244e35a','App\\Notifications\\AdminAlertNotification','App\\Models\\User',6,'{\"message\":\"New user registered: delfin@farmer.com\",\"icon\":\"bi-person\",\"link\":\"https:\\/\\/nutriapp.shop\\/admin\\/users\",\"type\":\"registration\"}',NULL,'2025-06-22 00:57:57','2025-06-22 00:57:57'),('eb93516e-f0f1-4a0f-b452-ff274706b2a6','App\\Notifications\\FarmerVerificationStatusNotification','App\\Models\\User',1,'{\"title\":\"Verification Approved\",\"message\":\"Your farmer account has been verified. You can now list products.\",\"icon\":\"bi-check-circle-fill\",\"type\":\"verification\"}','2025-06-21 14:19:13','2025-06-21 14:06:57','2025-06-21 14:19:13'),('f517bba8-76c8-485a-bdb0-75180aaa28fe','App\\Notifications\\AdminAlertNotification','App\\Models\\User',2,'{\"message\":\"New product added: Banana (Saba) (\\u20b1123.00, 123 kilos)\",\"icon\":\"bi-box-seam\",\"link\":\"https:\\/\\/nutriapp.shop\\/admin\\/products\",\"type\":null}',NULL,'2025-06-21 14:08:10','2025-06-21 14:08:10');
/*!40000 ALTER TABLE `notifications` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `orders`
--

DROP TABLE IF EXISTS `orders`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `orders` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `order_code` varchar(30) COLLATE utf8mb4_unicode_ci NOT NULL,
  `buyer_id` bigint unsigned NOT NULL,
  `farmer_id` bigint unsigned DEFAULT NULL,
  `product_id` bigint unsigned NOT NULL,
  `quantity` int NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `total_price` decimal(10,2) NOT NULL,
  `status` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pending',
  `stock_deducted` tinyint(1) NOT NULL DEFAULT '0',
  `payment_status` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'paid',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `shipping_address` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `phone` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `contact_email` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `buyer_phone` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `buyer_address` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `buyer_city` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `buyer_region` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `buyer_postal_code` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `cancelled_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `orders`
--

LOCK TABLES `orders` WRITE;
/*!40000 ALTER TABLE `orders` DISABLE KEYS */;
INSERT INTO `orders` VALUES (1,'ORD-CAV-250621-F1-0001',3,1,1,5,123.00,615.00,'completed',0,'paid','2025-06-21 17:14:36','2025-06-21 17:14:56',NULL,NULL,NULL,'9163090162','blk5 lot 10','Bacoor','Cavite','4102',NULL),(2,'ORD-CAV-250623-F1-0001',3,1,1,2,123.00,246.00,'shipped',0,'paid','2025-06-23 08:12:39','2025-06-23 13:17:30',NULL,NULL,NULL,'916 309 0162','BLK 5 LOT 10 SPRINGVILLE SOUTH 2 MOLINO 4 BACOOR CAVITE','San Luis','batangas','4102',NULL),(3,'ORD-UNK-250623-F7-0001',3,7,3,1,20.00,20.00,'Pending',0,'paid','2025-06-23 08:12:40','2025-06-23 08:12:40',NULL,NULL,NULL,'916 309 0162','BLK 5 LOT 10 SPRINGVILLE SOUTH 2 MOLINO 4 BACOOR CAVITE','San Luis','batangas','4102',NULL);
/*!40000 ALTER TABLE `orders` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `password_resets`
--

DROP TABLE IF EXISTS `password_resets`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `password_resets` (
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`email`),
  KEY `password_resets_email_index` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `password_resets`
--

LOCK TABLES `password_resets` WRITE;
/*!40000 ALTER TABLE `password_resets` DISABLE KEYS */;
INSERT INTO `password_resets` VALUES ('luna@farmer.com','$2y$12$dSwD.EwlbUyJ2E96QFLlhuV38Nlrz71E/0EvvUtM6cngZh7Jr3fiG','2025-05-04 14:10:21');
/*!40000 ALTER TABLE `password_resets` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `payments`
--

DROP TABLE IF EXISTS `payments`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `payments` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `intent_id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `reference_id` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `order_ids` json DEFAULT NULL,
  `method` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `amount` int NOT NULL,
  `status` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `response_payload` json DEFAULT NULL,
  `buyer_id` bigint unsigned DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `payments`
--

LOCK TABLES `payments` WRITE;
/*!40000 ALTER TABLE `payments` DISABLE KEYS */;
INSERT INTO `payments` VALUES (1,'mock_SVqQp3bBtk',NULL,NULL,'mock',61500,'paid',NULL,3,'2025-06-21 17:14:36','2025-06-21 17:14:36');
/*!40000 ALTER TABLE `payments` ENABLE KEYS */;
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
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(64) COLLATE utf8mb4_unicode_ci NOT NULL,
  `abilities` text COLLATE utf8mb4_unicode_ci,
  `last_used_at` timestamp NULL DEFAULT NULL,
  `expires_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=17 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `personal_access_tokens`
--

LOCK TABLES `personal_access_tokens` WRITE;
/*!40000 ALTER TABLE `personal_access_tokens` DISABLE KEYS */;
INSERT INTO `personal_access_tokens` VALUES (1,'App\\Models\\User',1,'api-token','f1a637d6102f575816f839f1531d73d9b1bb734b995a1e795d6b28d8f5692066','[\"*\"]','2025-06-21 20:36:08',NULL,'2025-06-21 16:41:02','2025-06-21 20:36:08'),(2,'App\\Models\\User',1,'api-token','2fa3d6a302e3657dce897c990085c576cf2a302096f8b94693e99e9c1fe0f9b8','[\"*\"]','2025-06-22 00:33:39',NULL,'2025-06-22 00:19:30','2025-06-22 00:33:39'),(3,'App\\Models\\User',1,'api-token','f7122d7781883a0429f9d28cf051ed5cbf043622c080589fb43ae46400498ebb','[\"*\"]','2025-06-22 00:56:12',NULL,'2025-06-22 00:35:17','2025-06-22 00:56:12'),(4,'App\\Models\\User',7,'api-token','ef5d483ab28c276b6b5591b483251620fb11add99e7ffef6f755170a93c20813','[\"*\"]','2025-06-22 01:22:20',NULL,'2025-06-22 00:58:18','2025-06-22 01:22:20'),(5,'App\\Models\\User',7,'api-token','dc9516580981c37f3e6d10d011263d11fa83e13dba42479c8499416e55c77ca6','[\"*\"]','2025-06-22 01:24:06',NULL,'2025-06-22 01:23:53','2025-06-22 01:24:06'),(6,'App\\Models\\User',7,'api-token','cc0d7a291725a612497bb1d61ef19526bd286c4122817d95a0739498f8c74c01','[\"*\"]','2025-06-22 13:52:49',NULL,'2025-06-22 13:28:17','2025-06-22 13:52:49'),(7,'App\\Models\\User',3,'api-token','5afae5ab3a78f17b1a84f43b11ab106af4d5909d409197dc82c8fb4a00d15544','[\"*\"]',NULL,NULL,'2025-06-22 13:53:28','2025-06-22 13:53:28'),(8,'App\\Models\\User',3,'api-token','b9627eaf6cc4a9c5c40fdcd46a7f03857db556932b9d351a679bf56b48eeede0','[\"*\"]','2025-06-22 15:07:17',NULL,'2025-06-22 14:56:14','2025-06-22 15:07:17'),(9,'App\\Models\\User',1,'api-token','2efe2de7add63c4b64ec6763da177c2e8d3c531f938ad17430f588a4aad4c492','[\"*\"]','2025-06-22 17:08:48',NULL,'2025-06-22 16:51:50','2025-06-22 17:08:48'),(10,'App\\Models\\User',3,'api-token','202ffd0d396c958b590bab9808c1986f8403ec23e215dff071a78b7d395aceff','[\"*\"]',NULL,NULL,'2025-06-22 17:09:58','2025-06-22 17:09:58'),(11,'App\\Models\\User',3,'api-token','c95a3ebeaeeee238858cbb2036d29aea07e968f4a35e6d6aefddc6504e68d204','[\"*\"]','2025-06-22 17:41:42',NULL,'2025-06-22 17:19:15','2025-06-22 17:41:42'),(12,'App\\Models\\User',1,'api-token','661c67fbf6a8b47cb6346259462809ea58459cd8557fa1305043d69151447701','[\"*\"]','2025-06-23 09:58:46',NULL,'2025-06-23 09:54:00','2025-06-23 09:58:46'),(13,'App\\Models\\User',1,'api-token','d01d80d2e5ecee64f8d0549cdb166166de38f4ae0e68153663a43df6061e4344','[\"*\"]','2025-06-23 13:18:18',NULL,'2025-06-23 13:16:49','2025-06-23 13:18:18'),(14,'App\\Models\\User',3,'api-token','0de32ee43b107fd2ab24ed2740a5193bccf06880278dda2c7adc7cb6a1c9aa92','[\"*\"]','2025-06-23 13:20:31',NULL,'2025-06-23 13:18:52','2025-06-23 13:20:31'),(15,'App\\Models\\User',1,'api-token','c24e154bfb690a1c319a916a0cb15b641882162966c2ccf0e76279ba7d224b08','[\"*\"]','2025-06-23 13:21:02',NULL,'2025-06-23 13:20:54','2025-06-23 13:21:02'),(16,'App\\Models\\User',3,'api-token','cfa5528b79927b1cb57f6d47ec25637461b78102620c75ed606b0049089c036a','[\"*\"]',NULL,NULL,'2025-06-23 13:21:25','2025-06-23 13:21:25');
/*!40000 ALTER TABLE `personal_access_tokens` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `product_templates`
--

DROP TABLE IF EXISTS `product_templates`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `product_templates` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `category` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=60 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `product_templates`
--

LOCK TABLES `product_templates` WRITE;
/*!40000 ALTER TABLE `product_templates` DISABLE KEYS */;
INSERT INTO `product_templates` VALUES (1,'Fruits','Mango','2025-06-07 15:30:48','2025-06-07 15:30:48'),(2,'Fruits','Banana','2025-06-07 15:30:48','2025-06-07 15:30:48'),(3,'Fruits','Calamansi','2025-06-07 15:30:48','2025-06-07 15:30:48'),(4,'Fruits','Pineapple','2025-06-07 15:30:48','2025-06-07 15:30:48'),(5,'Fruits','Papaya','2025-06-07 15:30:48','2025-06-07 15:30:48'),(6,'Vegetable','Eggplant','2025-06-07 15:30:48','2025-06-07 15:30:48'),(7,'Vegetable','Tomato','2025-06-07 15:30:48','2025-06-07 15:30:48'),(8,'Vegetable','Squash','2025-06-07 15:30:48','2025-06-07 15:30:48'),(9,'Vegetable','String Beans','2025-06-07 15:30:48','2025-06-07 15:30:48'),(10,'Vegetable','Bitter Gourd','2025-06-07 15:30:48','2025-06-07 15:30:48'),(11,'Grains','Rice','2025-06-07 15:30:48','2025-06-07 15:30:48'),(12,'Grains','Corn','2025-06-07 15:30:48','2025-06-07 15:30:48'),(13,'Spices','Garlic','2025-06-07 15:30:48','2025-06-07 15:30:48'),(14,'Spices','Onion','2025-06-07 15:30:48','2025-06-07 15:30:48'),(15,'Spices','Ginger','2025-06-07 15:30:48','2025-06-07 15:30:48'),(16,'Spices','Chili Pepper','2025-06-07 15:30:48','2025-06-07 15:30:48'),(17,'Beverages','Coffee Beans','2025-06-07 15:30:48','2025-06-07 15:30:48'),(18,'Beverages','Cacao','2025-06-07 15:30:48','2025-06-07 15:30:48'),(19,'Grains','Rice (Brown)','2025-06-18 12:18:05','2025-06-18 12:18:05'),(20,'Grains','Rice (Red)','2025-06-18 12:18:05','2025-06-18 12:18:05'),(21,'Grains','Sticky Rice (Malagkit)','2025-06-18 12:18:05','2025-06-18 12:18:05'),(22,'Grains','Corn (White)','2025-06-18 12:18:05','2025-06-18 12:18:05'),(23,'Grains','Corn (Yellow)','2025-06-18 12:18:05','2025-06-18 12:18:05'),(24,'Fruits','Banana (Saba)','2025-06-18 12:18:05','2025-06-18 12:18:05'),(25,'Fruits','Banana (Lakatan)','2025-06-18 12:18:05','2025-06-18 12:18:05'),(26,'Fruits','Banana (Latundan)','2025-06-18 12:18:05','2025-06-18 12:18:05'),(27,'Fruits','Mango (Carabao)','2025-06-18 12:18:05','2025-06-18 12:18:05'),(28,'Fruits','Mango (Indian)','2025-06-18 12:18:05','2025-06-18 12:18:05'),(29,'Fruits','Lanzones','2025-06-18 12:18:05','2025-06-18 12:18:05'),(30,'Fruits','Guyabano','2025-06-18 12:18:05','2025-06-18 12:18:05'),(31,'Fruits','Dalandan','2025-06-18 12:18:05','2025-06-18 12:18:05'),(32,'Fruits','Watermelon','2025-06-18 12:18:05','2025-06-18 12:18:05'),(33,'Fruits','Pomelo','2025-06-18 12:18:05','2025-06-18 12:18:05'),(34,'Vegetable','Eggplant (Long Purple)','2025-06-18 12:18:05','2025-06-18 12:18:05'),(35,'Vegetable','Tomato (Native)','2025-06-18 12:18:05','2025-06-18 12:18:05'),(36,'Vegetable','String Beans (Pole)','2025-06-18 12:18:05','2025-06-18 12:18:05'),(37,'Vegetable','Pechay','2025-06-18 12:18:05','2025-06-18 12:18:05'),(38,'Vegetable','Malunggay','2025-06-18 12:18:05','2025-06-18 12:18:05'),(39,'Vegetable','Kangkong','2025-06-18 12:18:05','2025-06-18 12:18:05'),(40,'Vegetable','Sayote','2025-06-18 12:18:05','2025-06-18 12:18:05'),(41,'Vegetable','Upo','2025-06-18 12:18:05','2025-06-18 12:18:05'),(42,'Vegetable','Radish','2025-06-18 12:18:05','2025-06-18 12:18:05'),(43,'Vegetable','Okra','2025-06-18 12:18:05','2025-06-18 12:18:05'),(44,'Vegetable','Sitaw (Yardlong Beans)','2025-06-18 12:18:05','2025-06-18 12:18:05'),(45,'Spices','Garlic (Ilocos)','2025-06-18 12:18:05','2025-06-18 12:18:05'),(46,'Spices','Onion (Red)','2025-06-18 12:18:05','2025-06-18 12:18:05'),(47,'Spices','Ginger (Native)','2025-06-18 12:18:05','2025-06-18 12:18:05'),(48,'Spices','Chili Pepper (Labuyo)','2025-06-18 12:18:05','2025-06-18 12:18:05'),(49,'Spices','Turmeric','2025-06-18 12:18:05','2025-06-18 12:18:05'),(50,'Spices','Lemongrass','2025-06-18 12:18:05','2025-06-18 12:18:05'),(51,'Spices','Bay Leaves (Laurel)','2025-06-18 12:18:05','2025-06-18 12:18:05'),(52,'Spices','Chili Flakes','2025-06-18 12:18:05','2025-06-18 12:18:05'),(53,'Spices','Dried Basil','2025-06-18 12:18:05','2025-06-18 12:18:05'),(54,'Beverages','Coffee Beans (Barako)','2025-06-18 12:18:05','2025-06-18 12:18:05'),(55,'Beverages','Coffee Beans (Robusta)','2025-06-18 12:18:05','2025-06-18 12:18:05'),(56,'Beverages','Herbal Tea Leaves','2025-06-18 12:18:05','2025-06-18 12:18:05'),(57,'Beverages','Cacao Beans','2025-06-18 12:18:05','2025-06-18 12:18:05'),(58,'Beverages','Hot Chocolate Mix','2025-06-18 12:18:05','2025-06-18 12:18:05'),(59,'Beverages','Tamarind Juice Concentrate','2025-06-18 12:18:05','2025-06-18 12:18:05');
/*!40000 ALTER TABLE `product_templates` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `products`
--

DROP TABLE IF EXISTS `products`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `products` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `category_id` bigint unsigned DEFAULT NULL,
  `farmer_id` bigint unsigned DEFAULT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `image` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `price` decimal(10,2) NOT NULL,
  `sales_count` int unsigned NOT NULL DEFAULT '0',
  `stock` int NOT NULL,
  `status` enum('pending','approved','rejected') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pending',
  `category` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `province` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `city` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `location` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `harvest_time` date DEFAULT NULL,
  `ripeness` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `shelf_life` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `storage` text COLLATE utf8mb4_unicode_ci,
  `harvested_at` date DEFAULT NULL,
  `is_preorder` tinyint(1) NOT NULL DEFAULT '0',
  `preorder_stock` int DEFAULT NULL,
  `projected_harvest_period` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `products`
--

LOCK TABLES `products` WRITE;
/*!40000 ALTER TABLE `products` DISABLE KEYS */;
INSERT INTO `products` VALUES (1,NULL,1,'Banana (Saba)','asdasdasdasdasd','products/bgcXMZFx0FdGHzeJTSzEFrihRJd4azWo2AboyTBe.jpg',123.00,0,123,'approved','Fruits','Cavite','Bacoor',NULL,'2025-06-21 14:08:10','2025-06-21 14:08:17',NULL,NULL,'Partially Ripe','1 mothn','keep it room tempt dont let it dry','2025-06-21',0,NULL,NULL),(2,NULL,1,'Banana',NULL,'products/UVHLJcIMMpdFfniKOKJ8cCXLDM64HMkgOdZhBilH.jpg',10.00,0,200,'rejected','Fruits','Batangas',NULL,NULL,'2025-06-21 17:06:34','2025-06-22 16:53:30','2025-06-22 16:53:30',NULL,'Ripe',NULL,NULL,'2025-06-21',0,NULL,NULL),(3,NULL,7,'Apple',NULL,'products/YLrLcFyucei82gdXftTIS9oHthE640UGbsObjiiu.jpg',20.00,0,200,'approved','Fruits','Cavite',NULL,NULL,'2025-06-22 13:48:28','2025-06-22 13:50:55',NULL,NULL,'Ripe',NULL,NULL,'2025-06-22',0,NULL,NULL),(4,NULL,1,'Mango (Carabao)',NULL,'products/yht9D9Yz59SjcdUpQy1uxORxHgrJUt5KcNgoIeJz.jpg',100.00,0,58,'approved','Fruits','Cavite',NULL,NULL,'2025-06-23 09:55:58','2025-06-23 09:57:00',NULL,NULL,'Ripe',NULL,NULL,'2025-06-23',0,NULL,NULL);
/*!40000 ALTER TABLE `products` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `ratings`
--

DROP TABLE IF EXISTS `ratings`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `ratings` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `buyer_id` bigint unsigned NOT NULL,
  `product_id` bigint unsigned NOT NULL,
  `order_id` bigint unsigned DEFAULT NULL,
  `rating` tinyint NOT NULL,
  `comment` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `ratings`
--

LOCK TABLES `ratings` WRITE;
/*!40000 ALTER TABLE `ratings` DISABLE KEYS */;
INSERT INTO `ratings` VALUES (1,3,1,1,5,NULL,'2025-06-21 17:15:46','2025-06-21 17:15:46');
/*!40000 ALTER TABLE `ratings` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `return_requests`
--

DROP TABLE IF EXISTS `return_requests`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `return_requests` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `order_id` bigint unsigned NOT NULL,
  `buyer_id` bigint unsigned NOT NULL,
  `reason` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `farmer_response` text COLLATE utf8mb4_unicode_ci,
  `farmer_evidence_path` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `responded_at` timestamp NULL DEFAULT NULL,
  `evidence_path` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pending',
  `admin_response` text COLLATE utf8mb4_unicode_ci,
  `resolved_at` timestamp NULL DEFAULT NULL,
  `is_resolved` tinyint(1) NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `tracking_code` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `resolution_type` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `final_resolution_action` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `replacement_tracking_code` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `return_requests`
--

LOCK TABLES `return_requests` WRITE;
/*!40000 ALTER TABLE `return_requests` DISABLE KEYS */;
INSERT INTO `return_requests` VALUES (1,2,3,'wrong item',NULL,NULL,NULL,NULL,'pending',NULL,NULL,0,'2025-06-23 13:20:23','2025-06-23 13:20:23',NULL,NULL,NULL,NULL);
/*!40000 ALTER TABLE `return_requests` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `reviews`
--

DROP TABLE IF EXISTS `reviews`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `reviews` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `product_id` bigint unsigned NOT NULL,
  `buyer_id` bigint unsigned NOT NULL,
  `rating` tinyint NOT NULL,
  `comment` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
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
-- Table structure for table `store_credits`
--

DROP TABLE IF EXISTS `store_credits`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `store_credits` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `buyer_id` bigint unsigned NOT NULL,
  `amount` decimal(8,2) NOT NULL,
  `description` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `store_credits_buyer_id_foreign` (`buyer_id`),
  CONSTRAINT `store_credits_buyer_id_foreign` FOREIGN KEY (`buyer_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `store_credits`
--

LOCK TABLES `store_credits` WRITE;
/*!40000 ALTER TABLE `store_credits` DISABLE KEYS */;
/*!40000 ALTER TABLE `store_credits` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `transactions`
--

DROP TABLE IF EXISTS `transactions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `transactions` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `order_id` bigint unsigned NOT NULL,
  `buyer_id` bigint unsigned NOT NULL,
  `farmer_id` bigint unsigned DEFAULT NULL,
  `amount` decimal(10,2) NOT NULL,
  `total_amount` decimal(10,2) DEFAULT NULL,
  `payment_method` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'Cash on Delivery',
  `status` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'Pending',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `transactions`
--

LOCK TABLES `transactions` WRITE;
/*!40000 ALTER TABLE `transactions` DISABLE KEYS */;
/*!40000 ALTER TABLE `transactions` ENABLE KEYS */;
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
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `verification_document` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `verification_status` enum('pending','verified','rejected') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pending',
  `verification_feedback` text COLLATE utf8mb4_unicode_ci,
  `phone` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `barangay` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `city` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `profile_picture` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `two_factor_secret` text COLLATE utf8mb4_unicode_ci,
  `two_factor_recovery_codes` text COLLATE utf8mb4_unicode_ci,
  `two_factor_confirmed_at` timestamp NULL DEFAULT NULL,
  `role` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'buyer',
  `is_banned` tinyint(1) NOT NULL DEFAULT '0',
  `is_permanently_banned` tinyint(1) NOT NULL DEFAULT '0',
  `banned_until` timestamp NULL DEFAULT NULL,
  `banned_by_admin_id` bigint unsigned DEFAULT NULL,
  `ban_reason` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `current_team_id` bigint unsigned DEFAULT NULL,
  `profile_photo_path` varchar(2048) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `status` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'active',
  `province` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `address` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `payout_method` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `payout_account` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `payout_method_secondary` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `payout_account_secondary` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `payout_verified` tinyint(1) NOT NULL DEFAULT '0',
  `business_name` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `bio` text COLLATE utf8mb4_unicode_ci,
  `profile_photo` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `business_photo` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `street` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `zip` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `is_verified` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users`
--

LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
INSERT INTO `users` VALUES (1,'mori','mori@farmer.com','verification_docs/CnUjR4DMVwBKn0XCC3NEnhG1sAYz2Fe6bepQHC6H.jpg','pending',NULL,'0969 803 6214','Molino 4','Bacoor',NULL,NULL,'$2y$12$0svoH1Wk/xm9fXRYQw4FsOxJ47qIZtuh5AJaVvUoaG71zzaCLJIh2',NULL,NULL,NULL,'farmer',0,0,NULL,NULL,NULL,NULL,NULL,NULL,'2025-06-21 14:02:42','2025-06-22 00:56:12','active','Cavite',NULL,NULL,NULL,NULL,NULL,0,'CROPS ORGANIC FARM',NULL,'g18w6dIaUf5nXInPp6OAjibHqDKn4uQ73NHT7cpW.jpg','jlRzrRotcXvvMoQPkRXbqRyJExGC3ngahntk0UND.jpg','J abad Santos street','4102',1),(2,'iandiaz','ian@admin.com',NULL,'pending',NULL,NULL,NULL,NULL,NULL,NULL,'$2y$12$MPIA4LrB3oavVq9cd1BsXOMP6OS3HAI6IvBbNx4sWS82mJUfqWO8K',NULL,NULL,NULL,'admin',0,0,NULL,NULL,NULL,NULL,NULL,NULL,'2025-06-21 14:04:24','2025-06-21 14:04:24','active',NULL,NULL,NULL,NULL,NULL,NULL,0,NULL,NULL,NULL,NULL,NULL,NULL,0),(3,'smith','smith@buyer.com',NULL,'pending',NULL,NULL,NULL,NULL,NULL,NULL,'$2y$12$fbn4NwG2wEJgz/vlymtpQ.gMDN/ZuDG4j7sCRlajppWH9hSyO2tZq',NULL,NULL,NULL,'buyer',0,0,NULL,NULL,NULL,NULL,NULL,NULL,'2025-06-21 14:08:55','2025-06-21 14:08:55','active',NULL,NULL,NULL,NULL,NULL,NULL,0,NULL,NULL,NULL,NULL,NULL,NULL,0),(4,'Angelica Fernandez','asfernandez401@gmail.com',NULL,'pending',NULL,NULL,NULL,NULL,NULL,NULL,'$2y$12$es./hC8kAkv36HesvkBbtu5OLu5ZfrMgCcFhioCT1.jqGJ3P9xALO',NULL,NULL,NULL,'buyer',0,0,NULL,NULL,NULL,NULL,NULL,NULL,'2025-06-21 17:41:10','2025-06-21 17:41:10','active',NULL,NULL,NULL,NULL,NULL,NULL,0,NULL,NULL,NULL,NULL,NULL,NULL,0),(5,'buyer','buyer@gmail.com',NULL,'pending',NULL,NULL,NULL,NULL,NULL,NULL,'$2y$12$hDQqqXgDo2JF.OnxexhMSeXRNYABNMXeVIBAd6mby1E0IkjNIFSqm',NULL,NULL,NULL,'buyer',0,0,NULL,NULL,NULL,NULL,NULL,NULL,'2025-06-21 20:07:43','2025-06-21 21:34:54','inactive',NULL,NULL,NULL,NULL,NULL,NULL,0,NULL,NULL,NULL,NULL,NULL,NULL,0),(6,'John Paul Ramos','202210285@feualabang.edu.ph',NULL,'pending',NULL,NULL,NULL,NULL,'profile_photos/IynJ33GFgtqd1ktSIrH2UqNFhlDxZJtZDzFy9TiJ.jpg',NULL,'$2y$12$4xyV5oz87czLtMjKY2hUxOckOWovmTVDYtUOPVruIWT6SkBPBev8G',NULL,NULL,NULL,'admin',0,0,NULL,NULL,NULL,'WcQg2w0X2TxVoLBv6Bd3g3S3yMVJrvdmTK76w5hUU6sB2ZVVbGGYbgtLORKM',NULL,NULL,'2025-06-21 21:34:10','2025-06-21 21:36:17','active',NULL,NULL,NULL,NULL,NULL,NULL,0,NULL,NULL,NULL,NULL,NULL,NULL,0),(7,'delfin farmer','delfin@farmer.com','verification_docs/T0AasNHcE4dbsgtvOE8Da09UsXVMk48dXHUhlUmI.jpg','pending',NULL,NULL,NULL,NULL,NULL,NULL,'$2y$12$Wu6EXza238vDpeaihyvVTebF.BOWnb0VPfHLm71W1m5WFwwVzEl5W',NULL,NULL,NULL,'farmer',0,0,NULL,NULL,NULL,NULL,NULL,NULL,'2025-06-22 00:57:57','2025-06-22 13:42:57','active',NULL,NULL,NULL,NULL,NULL,NULL,0,NULL,NULL,NULL,NULL,NULL,NULL,1),(8,'Ayame','ayaya@buyer.com',NULL,'pending',NULL,NULL,NULL,NULL,NULL,NULL,'$2y$12$Ubrmo62denIkPjzNss2pe.jS76U0StAgv9esV2BXgYbSwfbThsO12',NULL,NULL,NULL,'buyer',0,0,NULL,NULL,NULL,NULL,NULL,NULL,'2025-06-23 07:04:58','2025-06-23 07:04:58','active',NULL,NULL,NULL,NULL,NULL,NULL,0,NULL,NULL,NULL,NULL,NULL,NULL,0),(9,'Ian Policarpio','policarpioian2003@gmail.com',NULL,'pending',NULL,NULL,NULL,NULL,NULL,NULL,'$2y$12$OoENRMwqWdyXmUNItf2sReogMg6UXNgwYh/Du76dGb68F9VeABbCu',NULL,NULL,NULL,'buyer',0,0,NULL,NULL,NULL,NULL,NULL,NULL,'2025-06-23 07:33:17','2025-06-23 07:33:17','active',NULL,NULL,NULL,NULL,NULL,NULL,0,NULL,NULL,NULL,NULL,NULL,NULL,0);
/*!40000 ALTER TABLE `users` ENABLE KEYS */;
UNLOCK TABLES;
SET @@SESSION.SQL_LOG_BIN = @MYSQLDUMP_TEMP_LOG_BIN;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2025-06-23 11:26:21
