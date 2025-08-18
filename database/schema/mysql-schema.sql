/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;
DROP TABLE IF EXISTS `airfare_refs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `airfare_refs` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `origin_city_id` bigint unsigned NOT NULL,
  `destination_city_id` bigint unsigned NOT NULL,
  `class` enum('ECONOMY','BUSINESS') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'ECONOMY',
  `pp_estimate` decimal(12,2) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `unique_airfare_route_class` (`origin_city_id`,`destination_city_id`,`class`),
  KEY `airfare_refs_origin_city_id_index` (`origin_city_id`),
  KEY `airfare_refs_destination_city_id_index` (`destination_city_id`),
  CONSTRAINT `airfare_refs_destination_city_id_foreign` FOREIGN KEY (`destination_city_id`) REFERENCES `cities` (`id`) ON DELETE CASCADE,
  CONSTRAINT `airfare_refs_origin_city_id_foreign` FOREIGN KEY (`origin_city_id`) REFERENCES `cities` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=33 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `atcost_components`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `atcost_components` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `code` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `atcost_components_code_unique` (`code`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
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
DROP TABLE IF EXISTS `cities`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `cities` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `kemendagri_code` varchar(10) COLLATE utf8mb4_unicode_ci NOT NULL,
  `province_id` bigint unsigned NOT NULL,
  `name` varchar(120) COLLATE utf8mb4_unicode_ci NOT NULL,
  `type` enum('KAB','KOTA') COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `cities_kemendagri_code_unique` (`kemendagri_code`),
  KEY `cities_province_id_index` (`province_id`),
  KEY `cities_name_index` (`name`),
  CONSTRAINT `cities_province_id_foreign` FOREIGN KEY (`province_id`) REFERENCES `provinces` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=18 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `districts`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `districts` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `kemendagri_code` varchar(10) COLLATE utf8mb4_unicode_ci NOT NULL,
  `city_id` bigint unsigned NOT NULL,
  `name` varchar(120) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `districts_kemendagri_code_unique` (`kemendagri_code`),
  KEY `districts_city_id_index` (`city_id`),
  KEY `districts_name_index` (`name`),
  CONSTRAINT `districts_city_id_foreign` FOREIGN KEY (`city_id`) REFERENCES `cities` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=21 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `doc_number_formats`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `doc_number_formats` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `doc_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `unit_scope_id` bigint unsigned DEFAULT NULL,
  `format_string` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `doc_code` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `reset_policy` enum('NEVER','YEARLY','MONTHLY') COLLATE utf8mb4_unicode_ci NOT NULL,
  `padding` tinyint unsigned NOT NULL DEFAULT '3',
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `notes` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `doc_number_formats_unit_scope_id_foreign` (`unit_scope_id`),
  KEY `doc_number_formats_doc_type_index` (`doc_type`),
  CONSTRAINT `doc_number_formats_unit_scope_id_foreign` FOREIGN KEY (`unit_scope_id`) REFERENCES `units` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `document_numbers`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `document_numbers` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `doc_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `doc_id` bigint unsigned DEFAULT NULL,
  `number` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `generated_by_user_id` bigint unsigned NOT NULL,
  `is_manual` tinyint(1) NOT NULL DEFAULT '0',
  `old_number` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `format_id` bigint unsigned DEFAULT NULL,
  `sequence_id` bigint unsigned DEFAULT NULL,
  `meta` json DEFAULT NULL,
  `created_at` timestamp NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `document_numbers_number_unique` (`number`),
  KEY `document_numbers_generated_by_user_id_foreign` (`generated_by_user_id`),
  KEY `document_numbers_format_id_foreign` (`format_id`),
  KEY `document_numbers_sequence_id_foreign` (`sequence_id`),
  KEY `document_numbers_doc_type_index` (`doc_type`),
  KEY `document_numbers_doc_id_index` (`doc_id`),
  CONSTRAINT `document_numbers_format_id_foreign` FOREIGN KEY (`format_id`) REFERENCES `doc_number_formats` (`id`) ON DELETE SET NULL,
  CONSTRAINT `document_numbers_generated_by_user_id_foreign` FOREIGN KEY (`generated_by_user_id`) REFERENCES `users` (`id`),
  CONSTRAINT `document_numbers_sequence_id_foreign` FOREIGN KEY (`sequence_id`) REFERENCES `number_sequences` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `echelons`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `echelons` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `code` varchar(10) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `echelons_code_unique` (`code`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
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
DROP TABLE IF EXISTS `intra_district_transport_refs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `intra_district_transport_refs` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `origin_place_id` bigint unsigned NOT NULL,
  `destination_district_id` bigint unsigned NOT NULL,
  `pp_amount` decimal(12,2) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `unique_intra_district_transport` (`origin_place_id`,`destination_district_id`),
  KEY `intra_district_transport_refs_destination_district_id_foreign` (`destination_district_id`),
  CONSTRAINT `intra_district_transport_refs_destination_district_id_foreign` FOREIGN KEY (`destination_district_id`) REFERENCES `districts` (`id`) ON DELETE CASCADE,
  CONSTRAINT `intra_district_transport_refs_origin_place_id_foreign` FOREIGN KEY (`origin_place_id`) REFERENCES `org_places` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=98 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `intra_province_transport_refs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `intra_province_transport_refs` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `origin_place_id` bigint unsigned NOT NULL,
  `destination_city_id` bigint unsigned NOT NULL,
  `pp_amount` decimal(12,2) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `unique_intra_province_transport` (`origin_place_id`,`destination_city_id`),
  KEY `intra_province_transport_refs_destination_city_id_foreign` (`destination_city_id`),
  CONSTRAINT `intra_province_transport_refs_destination_city_id_foreign` FOREIGN KEY (`destination_city_id`) REFERENCES `cities` (`id`) ON DELETE CASCADE,
  CONSTRAINT `intra_province_transport_refs_origin_place_id_foreign` FOREIGN KEY (`origin_place_id`) REFERENCES `org_places` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=82 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
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
DROP TABLE IF EXISTS `lodging_caps`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `lodging_caps` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `province_id` bigint unsigned NOT NULL,
  `travel_grade_id` bigint unsigned NOT NULL,
  `cap_amount` decimal(12,2) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `unique_lodging_province_grade` (`province_id`,`travel_grade_id`),
  KEY `lodging_caps_province_id_index` (`province_id`),
  KEY `lodging_caps_travel_grade_id_index` (`travel_grade_id`),
  CONSTRAINT `lodging_caps_province_id_foreign` FOREIGN KEY (`province_id`) REFERENCES `provinces` (`id`) ON DELETE CASCADE,
  CONSTRAINT `lodging_caps_travel_grade_id_foreign` FOREIGN KEY (`travel_grade_id`) REFERENCES `travel_grades` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=41 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `migrations`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `migrations` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `migration` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=47 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `nota_dinas`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `nota_dinas` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `doc_no` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `number_is_manual` tinyint(1) NOT NULL DEFAULT '0',
  `number_manual_reason` text COLLATE utf8mb4_unicode_ci,
  `number_format_id` bigint unsigned DEFAULT NULL,
  `number_sequence_id` bigint unsigned DEFAULT NULL,
  `number_scope_unit_id` bigint unsigned DEFAULT NULL,
  `to_user_id` bigint unsigned NOT NULL,
  `from_user_id` bigint unsigned NOT NULL,
  `tembusan` text COLLATE utf8mb4_unicode_ci,
  `spt_request_date` date NOT NULL,
  `nd_date` date NOT NULL,
  `sifat` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `lampiran_count` int NOT NULL,
  `hal` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `dasar` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `maksud` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `destination_city_id` bigint unsigned NOT NULL,
  `start_date` date NOT NULL,
  `end_date` date NOT NULL,
  `days_count` smallint NOT NULL,
  `requesting_unit_id` bigint unsigned NOT NULL,
  `signer_user_id` bigint unsigned NOT NULL,
  `status` enum('DRAFT','SUBMITTED','APPROVED','REJECTED') COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_by` bigint unsigned NOT NULL,
  `approved_by` bigint unsigned DEFAULT NULL,
  `approved_at` timestamp NULL DEFAULT NULL,
  `notes` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `nota_dinas_doc_no_unique` (`doc_no`),
  KEY `nota_dinas_number_format_id_foreign` (`number_format_id`),
  KEY `nota_dinas_number_sequence_id_foreign` (`number_sequence_id`),
  KEY `nota_dinas_number_scope_unit_id_foreign` (`number_scope_unit_id`),
  KEY `nota_dinas_to_user_id_foreign` (`to_user_id`),
  KEY `nota_dinas_from_user_id_foreign` (`from_user_id`),
  KEY `nota_dinas_destination_city_id_foreign` (`destination_city_id`),
  KEY `nota_dinas_requesting_unit_id_foreign` (`requesting_unit_id`),
  KEY `nota_dinas_signer_user_id_foreign` (`signer_user_id`),
  KEY `nota_dinas_created_by_foreign` (`created_by`),
  KEY `nota_dinas_approved_by_foreign` (`approved_by`),
  CONSTRAINT `nota_dinas_approved_by_foreign` FOREIGN KEY (`approved_by`) REFERENCES `users` (`id`),
  CONSTRAINT `nota_dinas_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`),
  CONSTRAINT `nota_dinas_destination_city_id_foreign` FOREIGN KEY (`destination_city_id`) REFERENCES `cities` (`id`),
  CONSTRAINT `nota_dinas_from_user_id_foreign` FOREIGN KEY (`from_user_id`) REFERENCES `users` (`id`),
  CONSTRAINT `nota_dinas_number_format_id_foreign` FOREIGN KEY (`number_format_id`) REFERENCES `doc_number_formats` (`id`) ON DELETE SET NULL,
  CONSTRAINT `nota_dinas_number_scope_unit_id_foreign` FOREIGN KEY (`number_scope_unit_id`) REFERENCES `units` (`id`) ON DELETE SET NULL,
  CONSTRAINT `nota_dinas_number_sequence_id_foreign` FOREIGN KEY (`number_sequence_id`) REFERENCES `number_sequences` (`id`) ON DELETE SET NULL,
  CONSTRAINT `nota_dinas_requesting_unit_id_foreign` FOREIGN KEY (`requesting_unit_id`) REFERENCES `units` (`id`),
  CONSTRAINT `nota_dinas_signer_user_id_foreign` FOREIGN KEY (`signer_user_id`) REFERENCES `users` (`id`),
  CONSTRAINT `nota_dinas_to_user_id_foreign` FOREIGN KEY (`to_user_id`) REFERENCES `users` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `nota_dinas_participants`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `nota_dinas_participants` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `nota_dinas_id` bigint unsigned NOT NULL,
  `user_id` bigint unsigned NOT NULL,
  `role_in_trip` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `nota_dinas_participants_nota_dinas_id_user_id_unique` (`nota_dinas_id`,`user_id`),
  KEY `nota_dinas_participants_user_id_foreign` (`user_id`),
  CONSTRAINT `nota_dinas_participants_nota_dinas_id_foreign` FOREIGN KEY (`nota_dinas_id`) REFERENCES `nota_dinas` (`id`) ON DELETE CASCADE,
  CONSTRAINT `nota_dinas_participants_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `number_sequences`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `number_sequences` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `doc_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `unit_scope_id` bigint unsigned DEFAULT NULL,
  `year_scope` smallint unsigned DEFAULT NULL,
  `month_scope` tinyint unsigned DEFAULT NULL,
  `current_value` bigint unsigned NOT NULL,
  `last_generated_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uniq_numseq_doc_unit_year_month` (`doc_type`,`unit_scope_id`,`year_scope`,`month_scope`),
  KEY `number_sequences_unit_scope_id_foreign` (`unit_scope_id`),
  KEY `number_sequences_doc_type_index` (`doc_type`),
  KEY `number_sequences_year_scope_index` (`year_scope`),
  KEY `number_sequences_month_scope_index` (`month_scope`),
  CONSTRAINT `number_sequences_unit_scope_id_foreign` FOREIGN KEY (`unit_scope_id`) REFERENCES `units` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `official_vehicle_transport_refs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `official_vehicle_transport_refs` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `origin_place_id` bigint unsigned NOT NULL,
  `destination_district_id` bigint unsigned NOT NULL,
  `pp_amount` decimal(12,2) NOT NULL,
  `context` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `unique_official_vehicle_transport` (`origin_place_id`,`destination_district_id`,`context`),
  KEY `official_vehicle_transport_refs_destination_district_id_foreign` (`destination_district_id`),
  CONSTRAINT `official_vehicle_transport_refs_destination_district_id_foreign` FOREIGN KEY (`destination_district_id`) REFERENCES `districts` (`id`) ON DELETE CASCADE,
  CONSTRAINT `official_vehicle_transport_refs_origin_place_id_foreign` FOREIGN KEY (`origin_place_id`) REFERENCES `org_places` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=195 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `org_places`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `org_places` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(120) COLLATE utf8mb4_unicode_ci NOT NULL,
  `city_id` bigint unsigned DEFAULT NULL,
  `district_id` bigint unsigned DEFAULT NULL,
  `is_org_headquarter` tinyint(1) NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `org_places_name_unique` (`name`),
  KEY `org_places_city_id_index` (`city_id`),
  KEY `org_places_district_id_index` (`district_id`),
  CONSTRAINT `org_places_city_id_foreign` FOREIGN KEY (`city_id`) REFERENCES `cities` (`id`) ON DELETE SET NULL,
  CONSTRAINT `org_places_district_id_foreign` FOREIGN KEY (`district_id`) REFERENCES `districts` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `org_settings`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `org_settings` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `short_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `address` text COLLATE utf8mb4_unicode_ci,
  `city` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `province` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `phone` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `website` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `head_user_id` bigint unsigned DEFAULT NULL,
  `head_title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'Kepala Dinas',
  `signature_path` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `stamp_path` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `settings` json DEFAULT NULL,
  `singleton` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `org_settings_singleton_unique` (`singleton`),
  KEY `org_settings_head_user_id_foreign` (`head_user_id`),
  CONSTRAINT `org_settings_head_user_id_foreign` FOREIGN KEY (`head_user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
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
DROP TABLE IF EXISTS `perdiem_rates`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `perdiem_rates` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `province_id` bigint unsigned NOT NULL,
  `travel_grade_id` bigint unsigned NOT NULL,
  `satuan` varchar(10) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'OH',
  `luar_kota` decimal(12,2) NOT NULL,
  `dalam_kota_gt8h` decimal(12,2) NOT NULL,
  `diklat` decimal(12,2) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `unique_perdiem_province_grade` (`province_id`,`travel_grade_id`),
  KEY `perdiem_rates_province_id_index` (`province_id`),
  KEY `perdiem_rates_travel_grade_id_index` (`travel_grade_id`),
  CONSTRAINT `perdiem_rates_province_id_foreign` FOREIGN KEY (`province_id`) REFERENCES `provinces` (`id`) ON DELETE CASCADE,
  CONSTRAINT `perdiem_rates_travel_grade_id_foreign` FOREIGN KEY (`travel_grade_id`) REFERENCES `travel_grades` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=41 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `positions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `positions` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `type` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `echelon_id` bigint unsigned DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `positions_echelon_id_foreign` (`echelon_id`),
  CONSTRAINT `positions_echelon_id_foreign` FOREIGN KEY (`echelon_id`) REFERENCES `echelons` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `provinces`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `provinces` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `kemendagri_code` varchar(10) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `provinces_kemendagri_code_unique` (`kemendagri_code`),
  KEY `provinces_name_index` (`name`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `ranks`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `ranks` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `code` varchar(10) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `ranks_code_unique` (`code`)
) ENGINE=InnoDB AUTO_INCREMENT=35 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `receipt_lines`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `receipt_lines` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `receipt_id` bigint unsigned NOT NULL,
  `component` enum('PERDIEM','REPRESENTASI','LODGING','AIRFARE','INTRA_PROV','INTRA_DISTRICT','OFFICIAL_VEHICLE','TAXI','RORO','TOLL','PARKIR_INAP','RAPID_TEST','LAINNYA') COLLATE utf8mb4_unicode_ci NOT NULL,
  `qty` decimal(10,2) NOT NULL,
  `unit` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `unit_amount` decimal(16,2) NOT NULL,
  `line_total` decimal(16,2) NOT NULL,
  `ref_table` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `ref_id` bigint DEFAULT NULL,
  `cap_amount` decimal(16,2) DEFAULT NULL,
  `is_over_cap` tinyint(1) NOT NULL DEFAULT '0',
  `over_cap_amount` decimal(16,2) DEFAULT NULL,
  `remark` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `receipt_lines_receipt_id_foreign` (`receipt_id`),
  CONSTRAINT `receipt_lines_receipt_id_foreign` FOREIGN KEY (`receipt_id`) REFERENCES `receipts` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `receipts`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `receipts` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `doc_no` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `number_is_manual` tinyint(1) NOT NULL DEFAULT '0',
  `number_manual_reason` text COLLATE utf8mb4_unicode_ci,
  `number_format_id` bigint unsigned DEFAULT NULL,
  `number_sequence_id` bigint unsigned DEFAULT NULL,
  `number_scope_unit_id` bigint unsigned DEFAULT NULL,
  `sppd_id` bigint unsigned NOT NULL,
  `travel_grade_id` bigint unsigned NOT NULL,
  `receipt_no` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `receipt_date` date DEFAULT NULL,
  `payee_user_id` bigint unsigned NOT NULL,
  `total_amount` decimal(16,2) NOT NULL,
  `notes` text COLLATE utf8mb4_unicode_ci,
  `status` enum('DRAFT','FINAL') COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `receipts_doc_no_unique` (`doc_no`),
  KEY `receipts_number_format_id_foreign` (`number_format_id`),
  KEY `receipts_number_sequence_id_foreign` (`number_sequence_id`),
  KEY `receipts_number_scope_unit_id_foreign` (`number_scope_unit_id`),
  KEY `receipts_sppd_id_foreign` (`sppd_id`),
  KEY `receipts_travel_grade_id_foreign` (`travel_grade_id`),
  KEY `receipts_payee_user_id_foreign` (`payee_user_id`),
  CONSTRAINT `receipts_number_format_id_foreign` FOREIGN KEY (`number_format_id`) REFERENCES `doc_number_formats` (`id`) ON DELETE SET NULL,
  CONSTRAINT `receipts_number_scope_unit_id_foreign` FOREIGN KEY (`number_scope_unit_id`) REFERENCES `units` (`id`) ON DELETE SET NULL,
  CONSTRAINT `receipts_number_sequence_id_foreign` FOREIGN KEY (`number_sequence_id`) REFERENCES `number_sequences` (`id`) ON DELETE SET NULL,
  CONSTRAINT `receipts_payee_user_id_foreign` FOREIGN KEY (`payee_user_id`) REFERENCES `users` (`id`),
  CONSTRAINT `receipts_sppd_id_foreign` FOREIGN KEY (`sppd_id`) REFERENCES `sppd` (`id`),
  CONSTRAINT `receipts_travel_grade_id_foreign` FOREIGN KEY (`travel_grade_id`) REFERENCES `travel_grades` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `representation_rates`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `representation_rates` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `travel_grade_id` bigint unsigned NOT NULL,
  `satuan` varchar(10) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'OH',
  `luar_kota` decimal(12,2) NOT NULL,
  `dalam_kota_gt8h` decimal(12,2) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `unique_representation_travel_grade` (`travel_grade_id`),
  KEY `representation_rates_travel_grade_id_index` (`travel_grade_id`),
  CONSTRAINT `representation_rates_travel_grade_id_foreign` FOREIGN KEY (`travel_grade_id`) REFERENCES `travel_grades` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
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
DROP TABLE IF EXISTS `sppd`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `sppd` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `doc_no` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `number_is_manual` tinyint(1) NOT NULL DEFAULT '0',
  `number_manual_reason` text COLLATE utf8mb4_unicode_ci,
  `number_format_id` bigint unsigned DEFAULT NULL,
  `number_sequence_id` bigint unsigned DEFAULT NULL,
  `number_scope_unit_id` bigint unsigned NOT NULL,
  `sppd_date` date NOT NULL,
  `spt_id` bigint unsigned NOT NULL,
  `user_id` bigint unsigned NOT NULL,
  `origin_place_id` bigint unsigned NOT NULL,
  `destination_city_id` bigint unsigned NOT NULL,
  `transport_mode_id` bigint unsigned NOT NULL,
  `trip_type` enum('LUAR_DAERAH','DALAM_DAERAH_GT8H','DALAM_DAERAH_LE8H','DIKLAT') COLLATE utf8mb4_unicode_ci NOT NULL,
  `start_date` date NOT NULL,
  `end_date` date NOT NULL,
  `days_count` smallint NOT NULL,
  `funding_source` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` enum('DRAFT','ISSUED','IN_TRAVEL','RETURNED','VERIFIED','PAID','CANCELLED') COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `sppd_doc_no_unique` (`doc_no`),
  KEY `sppd_number_format_id_foreign` (`number_format_id`),
  KEY `sppd_number_sequence_id_foreign` (`number_sequence_id`),
  KEY `sppd_number_scope_unit_id_foreign` (`number_scope_unit_id`),
  KEY `sppd_spt_id_foreign` (`spt_id`),
  KEY `sppd_user_id_foreign` (`user_id`),
  KEY `sppd_origin_place_id_foreign` (`origin_place_id`),
  KEY `sppd_destination_city_id_foreign` (`destination_city_id`),
  KEY `sppd_transport_mode_id_foreign` (`transport_mode_id`),
  CONSTRAINT `sppd_destination_city_id_foreign` FOREIGN KEY (`destination_city_id`) REFERENCES `cities` (`id`),
  CONSTRAINT `sppd_number_format_id_foreign` FOREIGN KEY (`number_format_id`) REFERENCES `doc_number_formats` (`id`) ON DELETE SET NULL,
  CONSTRAINT `sppd_number_scope_unit_id_foreign` FOREIGN KEY (`number_scope_unit_id`) REFERENCES `units` (`id`),
  CONSTRAINT `sppd_number_sequence_id_foreign` FOREIGN KEY (`number_sequence_id`) REFERENCES `number_sequences` (`id`) ON DELETE SET NULL,
  CONSTRAINT `sppd_origin_place_id_foreign` FOREIGN KEY (`origin_place_id`) REFERENCES `org_places` (`id`),
  CONSTRAINT `sppd_spt_id_foreign` FOREIGN KEY (`spt_id`) REFERENCES `spt` (`id`),
  CONSTRAINT `sppd_transport_mode_id_foreign` FOREIGN KEY (`transport_mode_id`) REFERENCES `transport_modes` (`id`),
  CONSTRAINT `sppd_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `sppd_divisum_signoffs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `sppd_divisum_signoffs` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `sppd_id` bigint unsigned NOT NULL,
  `signed_place` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `signed_date` date NOT NULL,
  `signed_by_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `signed_by_position` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `note` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `sppd_divisum_signoffs_sppd_id_foreign` (`sppd_id`),
  CONSTRAINT `sppd_divisum_signoffs_sppd_id_foreign` FOREIGN KEY (`sppd_id`) REFERENCES `sppd` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `sppd_itineraries`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `sppd_itineraries` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `sppd_id` bigint unsigned NOT NULL,
  `leg_no` int NOT NULL,
  `date` date DEFAULT NULL,
  `from_place` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `to_place` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `mode_detail` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `ticket_no` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `remarks` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `sppd_itineraries_sppd_id_foreign` (`sppd_id`),
  CONSTRAINT `sppd_itineraries_sppd_id_foreign` FOREIGN KEY (`sppd_id`) REFERENCES `sppd` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `spt`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `spt` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `doc_no` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `number_is_manual` tinyint(1) NOT NULL DEFAULT '0',
  `number_manual_reason` text COLLATE utf8mb4_unicode_ci,
  `number_format_id` bigint unsigned DEFAULT NULL,
  `number_sequence_id` bigint unsigned DEFAULT NULL,
  `number_scope_unit_id` bigint unsigned DEFAULT NULL,
  `spt_date` date NOT NULL,
  `nota_dinas_id` bigint unsigned NOT NULL,
  `signed_by_user_id` bigint unsigned NOT NULL,
  `assignment_title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `origin_place_id` bigint unsigned NOT NULL,
  `destination_city_id` bigint unsigned NOT NULL,
  `start_date` date NOT NULL,
  `end_date` date NOT NULL,
  `days_count` smallint NOT NULL,
  `funding_source` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` enum('DRAFT','SIGNED','CANCELLED') COLLATE utf8mb4_unicode_ci NOT NULL,
  `notes` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `spt_doc_no_unique` (`doc_no`),
  KEY `spt_number_format_id_foreign` (`number_format_id`),
  KEY `spt_number_sequence_id_foreign` (`number_sequence_id`),
  KEY `spt_number_scope_unit_id_foreign` (`number_scope_unit_id`),
  KEY `spt_nota_dinas_id_foreign` (`nota_dinas_id`),
  KEY `spt_signed_by_user_id_foreign` (`signed_by_user_id`),
  KEY `spt_origin_place_id_foreign` (`origin_place_id`),
  KEY `spt_destination_city_id_foreign` (`destination_city_id`),
  CONSTRAINT `spt_destination_city_id_foreign` FOREIGN KEY (`destination_city_id`) REFERENCES `cities` (`id`),
  CONSTRAINT `spt_nota_dinas_id_foreign` FOREIGN KEY (`nota_dinas_id`) REFERENCES `nota_dinas` (`id`),
  CONSTRAINT `spt_number_format_id_foreign` FOREIGN KEY (`number_format_id`) REFERENCES `doc_number_formats` (`id`) ON DELETE SET NULL,
  CONSTRAINT `spt_number_scope_unit_id_foreign` FOREIGN KEY (`number_scope_unit_id`) REFERENCES `units` (`id`) ON DELETE SET NULL,
  CONSTRAINT `spt_number_sequence_id_foreign` FOREIGN KEY (`number_sequence_id`) REFERENCES `number_sequences` (`id`) ON DELETE SET NULL,
  CONSTRAINT `spt_origin_place_id_foreign` FOREIGN KEY (`origin_place_id`) REFERENCES `org_places` (`id`),
  CONSTRAINT `spt_signed_by_user_id_foreign` FOREIGN KEY (`signed_by_user_id`) REFERENCES `users` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `spt_members`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `spt_members` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `spt_id` bigint unsigned NOT NULL,
  `user_id` bigint unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `spt_members_spt_id_user_id_unique` (`spt_id`,`user_id`),
  KEY `spt_members_user_id_foreign` (`user_id`),
  CONSTRAINT `spt_members_spt_id_foreign` FOREIGN KEY (`spt_id`) REFERENCES `spt` (`id`) ON DELETE CASCADE,
  CONSTRAINT `spt_members_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `transport_modes`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `transport_modes` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `code` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `transport_modes_code_unique` (`code`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `travel_grades`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `travel_grades` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `code` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(200) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `travel_grades_code_unique` (`code`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `travel_routes`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `travel_routes` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `origin_place_id` bigint unsigned NOT NULL,
  `destination_place_id` bigint unsigned NOT NULL,
  `mode_id` bigint unsigned NOT NULL,
  `is_roundtrip` tinyint(1) NOT NULL DEFAULT '0',
  `class` enum('ECONOMY','BUSINESS') COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `unique_route_combination` (`origin_place_id`,`destination_place_id`,`mode_id`,`class`),
  KEY `travel_routes_origin_place_id_index` (`origin_place_id`),
  KEY `travel_routes_destination_place_id_index` (`destination_place_id`),
  KEY `travel_routes_mode_id_index` (`mode_id`),
  CONSTRAINT `travel_routes_destination_place_id_foreign` FOREIGN KEY (`destination_place_id`) REFERENCES `org_places` (`id`) ON DELETE CASCADE,
  CONSTRAINT `travel_routes_mode_id_foreign` FOREIGN KEY (`mode_id`) REFERENCES `transport_modes` (`id`) ON DELETE CASCADE,
  CONSTRAINT `travel_routes_origin_place_id_foreign` FOREIGN KEY (`origin_place_id`) REFERENCES `org_places` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `trip_report_signers`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `trip_report_signers` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `trip_report_id` bigint unsigned NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `nip` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `position` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `trip_report_signers_trip_report_id_foreign` (`trip_report_id`),
  CONSTRAINT `trip_report_signers_trip_report_id_foreign` FOREIGN KEY (`trip_report_id`) REFERENCES `trip_reports` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `trip_reports`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `trip_reports` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `doc_no` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `number_is_manual` tinyint(1) NOT NULL DEFAULT '0',
  `number_manual_reason` text COLLATE utf8mb4_unicode_ci,
  `number_format_id` bigint unsigned DEFAULT NULL,
  `number_sequence_id` bigint unsigned DEFAULT NULL,
  `number_scope_unit_id` bigint unsigned DEFAULT NULL,
  `spt_id` bigint unsigned NOT NULL,
  `report_no` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `report_date` date DEFAULT NULL,
  `place_from` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `place_to` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `depart_date` date NOT NULL,
  `return_date` date NOT NULL,
  `activities` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_by_user_id` bigint unsigned NOT NULL,
  `status` enum('DRAFT','SUBMITTED','APPROVED') COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `trip_reports_doc_no_unique` (`doc_no`),
  KEY `trip_reports_number_format_id_foreign` (`number_format_id`),
  KEY `trip_reports_number_sequence_id_foreign` (`number_sequence_id`),
  KEY `trip_reports_number_scope_unit_id_foreign` (`number_scope_unit_id`),
  KEY `trip_reports_spt_id_foreign` (`spt_id`),
  KEY `trip_reports_created_by_user_id_foreign` (`created_by_user_id`),
  CONSTRAINT `trip_reports_created_by_user_id_foreign` FOREIGN KEY (`created_by_user_id`) REFERENCES `users` (`id`),
  CONSTRAINT `trip_reports_number_format_id_foreign` FOREIGN KEY (`number_format_id`) REFERENCES `doc_number_formats` (`id`) ON DELETE SET NULL,
  CONSTRAINT `trip_reports_number_scope_unit_id_foreign` FOREIGN KEY (`number_scope_unit_id`) REFERENCES `units` (`id`) ON DELETE SET NULL,
  CONSTRAINT `trip_reports_number_sequence_id_foreign` FOREIGN KEY (`number_sequence_id`) REFERENCES `number_sequences` (`id`) ON DELETE SET NULL,
  CONSTRAINT `trip_reports_spt_id_foreign` FOREIGN KEY (`spt_id`) REFERENCES `spt` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `units`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `units` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `code` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `parent_id` bigint unsigned DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `units_code_unique` (`code`),
  KEY `units_parent_id_foreign` (`parent_id`),
  CONSTRAINT `units_parent_id_foreign` FOREIGN KEY (`parent_id`) REFERENCES `units` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `user_travel_grade_maps`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `user_travel_grade_maps` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint unsigned NOT NULL,
  `travel_grade_id` bigint unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `user_travel_grade_maps_user_id_unique` (`user_id`),
  KEY `user_travel_grade_maps_user_id_index` (`user_id`),
  KEY `user_travel_grade_maps_travel_grade_id_index` (`travel_grade_id`),
  CONSTRAINT `user_travel_grade_maps_travel_grade_id_foreign` FOREIGN KEY (`travel_grade_id`) REFERENCES `travel_grades` (`id`) ON DELETE CASCADE,
  CONSTRAINT `user_travel_grade_maps_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
DROP TABLE IF EXISTS `users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `users` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `nip` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `nik` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `gelar_depan` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `gelar_belakang` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `phone` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `whatsapp` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `address` text COLLATE utf8mb4_unicode_ci,
  `unit_id` bigint unsigned DEFAULT NULL,
  `position_id` bigint unsigned DEFAULT NULL,
  `rank_id` bigint unsigned DEFAULT NULL,
  `npwp` varchar(25) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `bank_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `bank_account_no` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `bank_account_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `birth_date` date DEFAULT NULL,
  `gender` varchar(10) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `signature_path` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `photo_path` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `is_signer` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_email_unique` (`email`),
  UNIQUE KEY `users_nip_unique` (`nip`),
  UNIQUE KEY `users_nik_unique` (`nik`),
  KEY `users_unit_id_foreign` (`unit_id`),
  KEY `users_position_id_foreign` (`position_id`),
  KEY `users_rank_id_foreign` (`rank_id`),
  CONSTRAINT `users_position_id_foreign` FOREIGN KEY (`position_id`) REFERENCES `positions` (`id`) ON DELETE SET NULL,
  CONSTRAINT `users_rank_id_foreign` FOREIGN KEY (`rank_id`) REFERENCES `ranks` (`id`) ON DELETE SET NULL,
  CONSTRAINT `users_unit_id_foreign` FOREIGN KEY (`unit_id`) REFERENCES `units` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
