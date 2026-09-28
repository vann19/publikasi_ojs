/*M!999999\- enable the sandbox mode */ 
-- MariaDB dump 10.19  Distrib 10.11.18-MariaDB, for Linux (x86_64)
--
-- Host: localhost    Database: ojs_publikasi
-- ------------------------------------------------------
-- Server version	10.11.18-MariaDB

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
-- Table structure for table `books`
--

DROP TABLE IF EXISTS `books`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `books` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL,
  `slug` varchar(255) DEFAULT NULL,
  `author` varchar(255) NOT NULL,
  `category` varchar(255) DEFAULT NULL,
  `harga` int(11) DEFAULT NULL,
  `publisher` varchar(255) DEFAULT NULL,
  `published_date` date DEFAULT NULL,
  `isbn` varchar(50) DEFAULT NULL,
  `pages` int(11) DEFAULT NULL,
  `country` varchar(100) DEFAULT NULL,
  `language` varchar(100) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `image` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `books`
--

LOCK TABLES `books` WRITE;
/*!40000 ALTER TABLE `books` DISABLE KEYS */;
INSERT INTO `books` VALUES
(1,'Sejarah Sosial Dan Intelektual Islam Di Asia Tenggara/Kepulauan Melayu','sejarah-sosial-dan-intelektual-islam-di-asia-tenggara-kepulauan-melayu','Dani','',400000,'cannon','2024-09-15','232323',4,'indonesia','indonesia','buku ini ditulis dengan rasa gamers ','2026-09-15 16:23:41','/storage/books/book_6ab6899cae97b.png'),
(2,'ppaum','ppaum','budi','Monograf',600000,'dani','2025-08-15','1281281872',60,'Indonesia','indonesia','lalaldjaklda','2026-09-25 14:49:35','/storage/books/book_6ab689ff3c0ba.png');
/*!40000 ALTER TABLE `books` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `comments`
--

DROP TABLE IF EXISTS `comments`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `comments` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nama` varchar(255) NOT NULL,
  `instansi` varchar(255) DEFAULT NULL,
  `komentar` text NOT NULL,
  `is_active` tinyint(1) DEFAULT 0,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `comments`
--

LOCK TABLES `comments` WRITE;
/*!40000 ALTER TABLE `comments` DISABLE KEYS */;
INSERT INTO `comments` VALUES
(1,'imron','pati','jurnala di pt nawa terbaik',1,'2026-09-20 14:01:18'),
(2,'kkk','sdfs','sfs',1,'2026-09-20 14:01:48'),
(3,'sfs','sfs','sfs',1,'2026-09-20 14:01:51'),
(4,'sfs','sfs','sfs',1,'2026-09-20 14:01:54'),
(5,'sfs','sfss','sfsf',1,'2026-09-20 14:01:59'),
(6,'adsad','ada','ada',1,'2026-09-20 14:04:25'),
(7,'ada','ada','ada',1,'2026-09-20 14:04:27');
/*!40000 ALTER TABLE `comments` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `journals`
--

DROP TABLE IF EXISTS `journals`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `journals` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL,
  `issn` varchar(50) DEFAULT NULL,
  `volume` varchar(50) DEFAULT NULL,
  `issue` varchar(50) DEFAULT NULL,
  `published_date` date DEFAULT NULL,
  `link` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `image` varchar(255) DEFAULT NULL,
  `path` varchar(255) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `sinta` varchar(50) DEFAULT NULL,
  `warna` varchar(50) DEFAULT NULL,
  `is_active` tinyint(1) DEFAULT 1,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `journals`
--

LOCK TABLES `journals` WRITE;
/*!40000 ALTER TABLE `journals` DISABLE KEYS */;
INSERT INTO `journals` VALUES
(1,'Al-Khazin: Jurnal Pendidikan Agama Islam','ISSN',NULL,NULL,NULL,'https://e-journal.nawaedukasi.org/index.php/al-khazin','2026-09-20 08:16:13','https://e-journal.nawaedukasi.org/public/journals/3/pageHeaderLogoImage_en.jpg','al-khazin','Jurnal ilmiah Al-Khazin: Jurnal Pendidikan Agama Islam dari Nawa Edukasi.','SINTA','bg-emerald-700',1),
(2,'Jurnal Ilmu Pendidikan','ISSN',NULL,NULL,NULL,'https://e-journal.nawaedukasi.org/index.php/jimulti','2026-09-20 08:16:13','/storage/journals/journal_cover_2_67ca7f18381fa9f5.webp','jimulti','Jurnal ilmiah Jurnal Ilmu Pendidikan dari Nawa Edukasi.','SINTA','bg-sky-800',1),
(3,'BAYAN: Jurnal Studi Islam dan Humaniora','ISSN',NULL,NULL,NULL,'https://e-journal.nawaedukasi.org/index.php/bayan','2026-09-20 08:16:13','https://e-journal.nawaedukasi.org/public/journals/2/pageHeaderLogoImage_en.jpg','bayan','Jurnal ilmiah BAYAN: Jurnal Studi Islam dan Humaniora dari Nawa Edukasi.','SINTA','bg-purple-700',1),
(4,'Al-Qalam: Jurnal Pendidikan Bahasa Arab','ISSN',NULL,NULL,NULL,'https://e-journal.nawaedukasi.org/index.php/al-qalam','2026-09-20 08:16:13','https://e-journal.nawaedukasi.org/public/journals/7/pageHeaderLogoImage_en.jpg','al-qalam','Jurnal ilmiah Al-Qalam: Jurnal Pendidikan Bahasa Arab dari Nawa Edukasi.','SINTA','bg-teal-700',1),
(5,'Journal of Digital Banking and Finance','ISSN',NULL,NULL,NULL,'https://e-journal.nawaedukasi.org/index.php/jdbf','2026-09-20 08:16:13','https://e-journal.nawaedukasi.org/public/journals/10/pageHeaderLogoImage_en.png','jdbf','Jurnal ilmiah Journal of Digital Banking and Finance dari Nawa Edukasi.','SINTA','bg-orange-600',1),
(6,'Journal of Education and Multidisciplinary Studies','ISSN',NULL,NULL,NULL,'https://e-journal.nawaedukasi.org/index.php/jems','2026-09-20 08:16:13','https://e-journal.nawaedukasi.org/public/journals/8/pageHeaderLogoImage_en.png','jems','Jurnal ilmiah Journal of Education and Multidisciplinary Studies dari Nawa Edukasi.','SINTA','bg-green-800',1),
(7,'SEHATI: Jurnal Pengabdian Kepada Masyarakat','ISSN',NULL,NULL,NULL,'https://e-journal.nawaedukasi.org/index.php/jpkm','2026-09-20 08:16:13','https://e-journal.nawaedukasi.org/public/journals/4/pageHeaderLogoImage_en.jpg','jpkm','Jurnal ilmiah SEHATI: Jurnal Pengabdian Kepada Masyarakat dari Nawa Edukasi.','SINTA','bg-rose-800',1),
(8,'Al-Hikmah: Journal of Islamic Educational Psychology and Guidance Counseling','ISSN',NULL,NULL,NULL,'https://e-journal.nawaedukasi.org/index.php/al-hikmah','2026-09-20 08:16:13','https://e-journal.nawaedukasi.org/public/journals/9/pageHeaderLogoImage_en.jpg','al-hikmah','Jurnal ilmiah Al-Hikmah: Journal of Islamic Educational Psychology and Guidance Counseling dari Nawa Edukasi.','SINTA','bg-slate-700',1);
/*!40000 ALTER TABLE `journals` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `seminars`
--

DROP TABLE IF EXISTS `seminars`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `seminars` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `date` date DEFAULT NULL,
  `location` varchar(255) DEFAULT NULL,
  `image` varchar(255) DEFAULT NULL,
  `link` varchar(255) DEFAULT NULL,
  `price` bigint(20) unsigned DEFAULT NULL,
  `is_active` tinyint(1) DEFAULT 1,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `seminars`
--

LOCK TABLES `seminars` WRITE;
/*!40000 ALTER TABLE `seminars` DISABLE KEYS */;
INSERT INTO `seminars` VALUES
(1,'seminar jurna','ada','2026-09-14','','/storage/seminars/seminar_6aafefbb9ccf1.webp','https://cart.hostinger.com/pay/940ea681-95c6-493f-b7fa-dc69f1fa3097',NULL,1,'2026-09-20 14:37:47');
/*!40000 ALTER TABLE `seminars` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(50) NOT NULL,
  `email` varchar(100) DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `username` (`username`),
  UNIQUE KEY `email` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users`
--

LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
INSERT INTO `users` VALUES
(1,'fansya','fansya@nawaedukasinusantara.com','$2y$12$cbiEgR3GPUHM.f1gN4d7oOsjz5nmcjOL1omNPH3wT2.KQSUbKn2TC','2026-09-15 09:56:03');
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

-- Dump completed on 2026-09-28 15:11:39
