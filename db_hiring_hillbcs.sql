-- phpMyAdmin SQL Dump
-- version 5.2.3deb1
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Dec 24, 2025 at 02:49 PM
-- Server version: 11.8.5-MariaDB-2+b2 from Debian
-- PHP Version: 8.4.11

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `db_hiring_hillbcs`
--

-- --------------------------------------------------------

--
-- Table structure for table `cache`
--

CREATE TABLE `cache` (
  `key` varchar(255) NOT NULL,
  `value` mediumtext NOT NULL,
  `expiration` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `cache`
--

INSERT INTO `cache` (`key`, `value`, `expiration`) VALUES
('hiring-hall-cache-a6f155de15268698bea3ed1df3f9aab3', 'i:1;', 1766558657),
('hiring-hall-cache-a6f155de15268698bea3ed1df3f9aab3:timer', 'i:1766558657;', 1766558657),
('hiring-hall-cache-fffef0074897d076466294805a41a234', 'i:2;', 1766570373),
('hiring-hall-cache-fffef0074897d076466294805a41a234:timer', 'i:1766570373;', 1766570373);

-- --------------------------------------------------------

--
-- Table structure for table `cache_locks`
--

CREATE TABLE `cache_locks` (
  `key` varchar(255) NOT NULL,
  `owner` varchar(255) NOT NULL,
  `expiration` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `candidate_profiles`
--

CREATE TABLE `candidate_profiles` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `display_name` varchar(255) NOT NULL,
  `title` varchar(255) DEFAULT NULL,
  `location` varchar(255) DEFAULT NULL,
  `work_mode` varchar(255) DEFAULT NULL,
  `degree` varchar(255) DEFAULT NULL,
  `years_experience` tinyint(3) UNSIGNED DEFAULT NULL,
  `availability` varchar(255) DEFAULT NULL,
  `job_type` varchar(50) DEFAULT NULL,
  `expertise_categories` longtext DEFAULT NULL,
  `expected_salary_min` decimal(10,2) DEFAULT NULL,
  `expected_salary_max` decimal(10,2) DEFAULT NULL,
  `salary_currency` varchar(3) NOT NULL DEFAULT 'USD',
  `verified` tinyint(1) NOT NULL DEFAULT 0,
  `rating` decimal(3,2) DEFAULT NULL,
  `rating_count` int(10) UNSIGNED NOT NULL DEFAULT 0,
  `headline` varchar(255) DEFAULT NULL,
  `about` longtext DEFAULT NULL,
  `cv_path` varchar(255) DEFAULT NULL,
  `career_objective` longtext DEFAULT NULL,
  `education_details` longtext DEFAULT NULL,
  `certifications` longtext DEFAULT NULL,
  `key_achievements` longtext DEFAULT NULL,
  `activities_interests` longtext DEFAULT NULL,
  `references_block` longtext DEFAULT NULL,
  `experience_overview` longtext DEFAULT NULL,
  `skills` longtext DEFAULT NULL,
  `tools_used` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`tools_used`)),
  `languages` longtext DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `candidate_profiles`
--

INSERT INTO `candidate_profiles` (`id`, `user_id`, `display_name`, `title`, `location`, `work_mode`, `degree`, `years_experience`, `availability`, `job_type`, `expertise_categories`, `expected_salary_min`, `expected_salary_max`, `salary_currency`, `verified`, `rating`, `rating_count`, `headline`, `about`, `cv_path`, `career_objective`, `education_details`, `certifications`, `key_achievements`, `activities_interests`, `references_block`, `experience_overview`, `skills`, `tools_used`, `languages`, `created_at`, `updated_at`) VALUES
(1, 4, 'Brandon', 'VA', 'BALUARTE', 'Remote', 'BSIT', 4, 'Full Time', NULL, NULL, 900.00, 900.00, 'USD', 0, NULL, 0, 'WOW', 'TEST', NULL, 'TEST', '[{\"course\":\"TEST\",\"school\":\"TEST\",\"location\":\"TEST\",\"dates\":\"TEST\"}]', '[{\"title\":\"TEST\",\"provider\":\"TEST\"}]', '[\"TEST\"]', '[\"TEST\"]', '[{\"name\":\"TEST\",\"designation\":\"TEST\",\"company\":\"TEST\",\"mobile\":\"TEST\",\"email\":\"test@example.com\",\"location\":\"TEST\"}]', '{\"position\":\"TEST\",\"company\":\"TEST\",\"location\":\"TEST\",\"start_date\":\"TEST\",\"end_date\":\"TEST\",\"responsibilities\":[\"TEST\"]}', NULL, NULL, NULL, '2025-12-04 03:12:53', '2025-12-04 09:27:46'),
(2, 8, 'Candidate User', NULL, 'asda', 'Remote', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'USD', 0, NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-12-04 05:29:30', '2025-12-05 04:18:30'),
(3, 6, 'Test Nash', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'USD', 0, NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-12-06 22:50:50', '2025-12-06 22:50:50');

-- --------------------------------------------------------

--
-- Table structure for table `chat_attachments`
--

CREATE TABLE `chat_attachments` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `message_id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `disk` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'public',
  `path` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `original_name` varchar(255) DEFAULT NULL,
  `mime` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `size` bigint(20) UNSIGNED NOT NULL DEFAULT 0,
  `width` int(10) UNSIGNED DEFAULT NULL,
  `height` int(10) UNSIGNED DEFAULT NULL,
  `meta` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `chat_attachments`
--

INSERT INTO `chat_attachments` (`id`, `message_id`, `user_id`, `disk`, `path`, `original_name`, `mime`, `size`, `width`, `height`, `meta`, `created_at`, `updated_at`) VALUES
(1, 27, 9790, 'public', '/storage/chat/q57IxT1BMy7oeFaJ6u2g6bMjBD4zamaNrQMHyLxu.pdf', NULL, 'application/pdf', 2314521, NULL, NULL, NULL, '2025-08-20 03:34:00', '2025-08-20 03:34:00'),
(2, 28, 9790, 'public', '/storage/chat/npQfcgRTDL8vmiviifQEoe0Iqnj4zyjbaV90DuWQ.pdf', NULL, 'application/pdf', 2314521, NULL, NULL, NULL, '2025-08-20 03:34:19', '2025-08-20 03:34:19'),
(3, 29, 9790, 'public', '/storage/chat/Zuqfsm2GfgKPOM9xIoUUPurjmIShkbGut2xyXcc4.pdf', NULL, 'application/pdf', 2102783, NULL, NULL, NULL, '2025-08-20 03:35:31', '2025-08-20 03:35:31'),
(4, 63, 9790, 'public', '/storage/chat/cSzm2KV1WQF1jPexppUrpJuhgqAJEeJ2rficXZ3F.png', NULL, 'image/png', 579233, NULL, NULL, NULL, '2025-08-21 07:00:30', '2025-08-21 07:00:30'),
(5, 64, 9787, 'public', '/storage/chat/vRbk0OO8UroUnbNUjQCrp8ZgeZow6HkEsygNUxJJ.jpg', NULL, 'image/jpeg', 690948, NULL, NULL, NULL, '2025-08-21 07:01:37', '2025-08-21 07:01:37'),
(6, 65, 9802, 'public', '/storage/chat/VVRnpw0zsHIDZaoOKoo6LyNR5yVA0FFMtIC6XcFu.png', NULL, 'image/png', 65236, NULL, NULL, NULL, '2025-08-21 07:02:15', '2025-08-21 07:02:15'),
(7, 71, 9790, 'public', '/storage/chat/3NZzpZEndh488mqp5RpsVwmdgVszpdV13Zs2WhaA.png', NULL, 'image/png', 584719, NULL, NULL, NULL, '2025-08-21 07:05:24', '2025-08-21 07:05:24'),
(8, 72, 9787, 'public', '/storage/chat/syMJMKn2Tdo2sjFldeZxkMoUXegX2sy2VzznNjJp.jpg', NULL, 'image/jpeg', 690948, NULL, NULL, NULL, '2025-08-21 07:05:28', '2025-08-21 07:05:28'),
(9, 113, 1, 'public', '/storage/chat/7ztc6cRuyiAWw7Yv2EMX8nJQUA7KiPnBOGI629zs.png', NULL, 'image/png', 56721, NULL, NULL, NULL, '2025-08-23 10:26:25', '2025-08-23 10:26:25'),
(10, 114, 1, 'public', '/storage/chat/qemRoNBJS26h7ENV9D2ipd6cVP5vj7mGdj3nx2zn.png', NULL, 'image/png', 81433, NULL, NULL, NULL, '2025-08-23 10:26:32', '2025-08-23 10:26:32'),
(11, 168, 9790, 'public', '/storage/chat/mfuohlCAEJBSB3I8K4HLSPPDQt5cCYJlHgxDaVoU.jpg', NULL, 'image/jpeg', 88293, NULL, NULL, NULL, '2025-08-26 07:22:45', '2025-08-26 07:22:45'),
(12, 197, 1, 'public', '/storage/chat/LVcVqTM4zpNXYvNIBXRCYVG446rqZRzdlWSSY4J4.png', NULL, 'image/png', 8279, NULL, NULL, NULL, '2025-08-27 06:04:10', '2025-08-27 06:04:10'),
(13, 206, 30, 'public', '/storage/chat/PV4gH0AH3raHST0oLndpsJ6Zl23aBD6NWnQgmEnv.png', NULL, 'image/png', 91347, NULL, NULL, NULL, '2025-08-27 06:09:25', '2025-08-27 06:09:25'),
(14, 214, 30, 'public', '/storage/chat/Jzb1D6s3Ry77RTJNiImXCA4tBuVRqbDdIlcEYW6L.png', NULL, 'image/png', 732352, NULL, NULL, NULL, '2025-08-27 06:24:27', '2025-08-27 06:24:27'),
(15, 217, 9799, 'public', '/storage/chat/DOzo1qe5sISDsL9LvXUX9fu5YCm4HfGMiCD8snDx.png', NULL, 'image/png', 176347, NULL, NULL, NULL, '2025-08-27 18:47:31', '2025-08-27 18:47:31'),
(16, 228, 9800, 'public', '/storage/chat/cecpjPNr4sxUucOpJ0HWPigMLob2O8acHxEkKZiD.png', NULL, 'image/png', 101691, NULL, NULL, NULL, '2025-08-28 18:07:41', '2025-08-28 18:07:41'),
(17, 228, 9800, 'public', '/storage/chat/ykgD2gOwp3xqzzsFFgCrXV2O95jeVi3YIDDZRSN5.png', NULL, 'image/png', 91138, NULL, NULL, NULL, '2025-08-28 18:07:41', '2025-08-28 18:07:41'),
(18, 228, 9800, 'public', '/storage/chat/GocMEma2rgFUoYGXe3rqqzq6iQ3aRmnnnZaqG0nI.png', NULL, 'image/png', 89639, NULL, NULL, NULL, '2025-08-28 18:07:41', '2025-08-28 18:07:41'),
(19, 228, 9800, 'public', '/storage/chat/ctTTLxj8NoxPcFtr9xgyhi0DRjtDfAUfjlApwjpI.png', NULL, 'image/png', 92979, NULL, NULL, NULL, '2025-08-28 18:07:41', '2025-08-28 18:07:41'),
(20, 228, 9800, 'public', '/storage/chat/n20FQILumGjVwmiwRZYgXGOofLnM95qbBeZzqzhx.png', NULL, 'image/png', 97630, NULL, NULL, NULL, '2025-08-28 18:07:41', '2025-08-28 18:07:41'),
(21, 229, 9799, 'public', '/storage/chat/cccffWKaSt6qNU07x1hyV6S9qtIYijxhEPY7C9NJ.png', NULL, 'image/png', 215050, NULL, NULL, NULL, '2025-08-28 20:32:21', '2025-08-28 20:32:21'),
(22, 248, 9787, 'public', '/storage/chat/vV5UxJ0AIWeK9EPBpNFvJRZjhSnUBkUALgQnU3lo.png', NULL, 'image/png', 25264, NULL, NULL, NULL, '2025-08-29 06:21:44', '2025-08-29 06:21:44'),
(23, 261, 9787, 'public', '/storage/chat/pPVTLqsUV355TnzaDNJjYjyEhRmHQwA7XdBdcUmj.png', NULL, 'image/png', 123834, NULL, NULL, NULL, '2025-08-29 06:24:27', '2025-08-29 06:24:27'),
(24, 264, 30, 'public', '/storage/chat/HPDCAw3OrbcidVjl7eQiBlyfmvuIZSQZYN1VA9fh.png', NULL, 'image/png', 91537, NULL, NULL, NULL, '2025-08-29 06:25:17', '2025-08-29 06:25:17'),
(25, 278, 9827, 'public', '/storage/chat/vcWMs62S2W2q7Fiw76f9e6QhQ3CAXqhwOLxUJ7t0.png', NULL, 'image/png', 35004, NULL, NULL, NULL, '2025-08-29 08:07:03', '2025-08-29 08:07:03'),
(26, 313, 30, 'public', '/storage/chat/Qpb32VF4y5rOrRezfmEDFbt7gTr0hVJobiqPZA74.png', NULL, 'image/png', 20915, NULL, NULL, NULL, '2025-08-31 19:05:49', '2025-08-31 19:05:49'),
(27, 321, 9813, 'public', '/storage/chat/J9gxkm31wny1VciC0lC3c8W8zClPxrtUiMuaQxPT.png', NULL, 'image/png', 1111853, NULL, NULL, NULL, '2025-08-31 19:06:54', '2025-08-31 19:06:54'),
(28, 331, 30, 'public', '/storage/chat/wtLORaZGvuH59etCpOLF3peXHmwjVF75nfReUnLQ.png', NULL, 'image/png', 83392, NULL, NULL, NULL, '2025-08-31 19:08:32', '2025-08-31 19:08:32'),
(29, 407, 9787, 'public', '/storage/chat/SrpMCmohzY96OSliyJWlctNU0kOnTsWNpCpC8eBx.png', NULL, 'image/png', 93005, NULL, NULL, NULL, '2025-09-01 06:12:06', '2025-09-01 06:12:06'),
(30, 407, 9787, 'public', '/storage/chat/1NhiNwwS2fiA6h9Oo5kDMidSCcimkIKxPDKOxNEv.png', NULL, 'image/png', 125651, NULL, NULL, NULL, '2025-09-01 06:12:06', '2025-09-01 06:12:06'),
(31, 471, 9799, 'public', '/storage/chat/UdmVjSl0J8xI40td5sC5x7Ez4xcGfAYa7wOigbU1.png', NULL, 'image/png', 149688, NULL, NULL, NULL, '2025-09-01 21:16:11', '2025-09-01 21:16:11'),
(32, 472, 9800, 'public', '/storage/chat/NMsjK6a3Uyr44HWvitoqhnGwtfl6nw685mMcirx8.png', NULL, 'image/png', 75412, NULL, NULL, NULL, '2025-09-01 21:24:03', '2025-09-01 21:24:03'),
(33, 472, 9800, 'public', '/storage/chat/OsZvBYiNmnLNgwKvHqMduANQU61ITHJM5wbSNJVw.png', NULL, 'image/png', 93871, NULL, NULL, NULL, '2025-09-01 21:24:03', '2025-09-01 21:24:03'),
(34, 522, 9787, 'public', '/storage/chat/Teh3GLhBasFsbzSexk4UanRCNroFquGHBzsx8M1T.png', NULL, 'image/png', 165073, NULL, NULL, NULL, '2025-09-03 06:16:34', '2025-09-03 06:16:34'),
(35, 575, 9799, 'public', '/storage/chat/kqBtfoLU64oYbZasOjD7LuMWT5bzmYq24TMSdAu5.png', NULL, 'image/png', 205514, NULL, NULL, NULL, '2025-09-04 18:43:37', '2025-09-04 18:43:37'),
(36, 578, 9799, 'public', '/storage/chat/T1Y7ASrl6OCBCnlea2DcIrbweTfBxKcFyqvQOY8W.png', NULL, 'image/png', 169868, NULL, NULL, NULL, '2025-09-04 19:17:14', '2025-09-04 19:17:14'),
(37, 579, 9799, 'public', '/storage/chat/lubzRcJqIDTtA48YiMChKiLWkPZo87Dse5O2ExJE.png', NULL, 'image/png', 192941, NULL, NULL, NULL, '2025-09-04 21:06:22', '2025-09-04 21:06:22'),
(38, 604, 30, 'public', '/storage/chat/6asqa53JvpCxthwyVzIaSW2pkrdLLlNPMADpfcBM.png', NULL, 'image/png', 538013, NULL, NULL, NULL, '2025-09-06 06:22:58', '2025-09-06 06:22:58'),
(39, 722, 30, 'public', '/storage/chat/CWCJ0jRID16QA8trlEsVqMc457yovY9vzRRp4tww.png', NULL, 'image/png', 1003770, NULL, NULL, NULL, '2025-09-11 17:04:53', '2025-09-11 17:04:53'),
(40, 747, 9798, 'public', '/storage/chat/Udij7ZMr1eqhRN8SowVkdAezmIxgmYhZwvvgjxO9.png', NULL, 'image/png', 5665, NULL, NULL, NULL, '2025-09-12 06:10:51', '2025-09-12 06:10:51'),
(41, 774, 30, 'public', '/storage/chat/ypL1E5m00x8vidIBoX4saa8cFBzcSCimrT0uGkaw.png', NULL, 'image/png', 24851, NULL, NULL, NULL, '2025-09-15 06:03:03', '2025-09-15 06:03:03'),
(42, 807, 9801, 'public', '/storage/chat/1cVxtInuxQ6LfZp0q3fZ1zx8wY1rPsMqrYE8Anxo.png', NULL, 'image/png', 1067830, NULL, NULL, NULL, '2025-09-15 18:03:44', '2025-09-15 18:03:44'),
(43, 825, 30, 'public', '/storage/chat/Hr3qoZMSlZWHwRqDmgoxbmlmDFJG7STcIa5ehABQ.png', NULL, 'image/png', 9640, NULL, NULL, NULL, '2025-09-15 18:11:50', '2025-09-15 18:11:50'),
(44, 831, 30, 'public', '/storage/chat/dhaB8XBBhsDgfK6fg9FXh1i2WWKJtWwePorkMDIb.png', NULL, 'image/png', 9815, NULL, NULL, NULL, '2025-09-15 18:13:05', '2025-09-15 18:13:05'),
(45, 837, 30, 'public', '/storage/chat/H55cDbto0bjbfPN7hYESoZURm6fAFcxpMvp5yh9m.png', NULL, 'image/png', 9963, NULL, NULL, NULL, '2025-09-15 18:22:22', '2025-09-15 18:22:22'),
(46, 838, 30, 'public', '/storage/chat/iJS9XYl1tPSLsLJEtFBQMhLgoPLpAvtsdxd9AH10.png', NULL, 'image/png', 25863, NULL, NULL, NULL, '2025-09-15 18:22:50', '2025-09-15 18:22:50'),
(47, 927, 9810, 'public', '/storage/chat/9vfzuHcIUYwQbBmFxjCv7TOKFeXdyvmWrpySJA1O.png', NULL, 'image/png', 39921, NULL, NULL, NULL, '2025-09-18 09:38:06', '2025-09-18 09:38:06'),
(48, 930, 9810, 'public', '/storage/chat/33L5hLVVMbH6tRALBkbeHR7GNCjFRRbCWBS8se20.png', NULL, 'image/png', 55409, NULL, NULL, NULL, '2025-09-18 15:17:44', '2025-09-18 15:17:44'),
(49, 932, 9810, 'public', '/storage/chat/woNUTHSn17Jil67SdQkHumVdc5CL2G2PuZY6gOSr.png', NULL, 'image/png', 55409, NULL, NULL, NULL, '2025-09-18 15:17:54', '2025-09-18 15:17:54'),
(50, 950, 9810, 'public', '/storage/chat/mYuIneFODGfHLyMU5UFAgTR42OvdfLD1aBeyLmtB.png', NULL, 'image/png', 56036, NULL, NULL, NULL, '2025-09-19 07:05:24', '2025-09-19 07:05:24'),
(51, 952, 9810, 'public', '/storage/chat/U1WK3pKGCngDChrcWAM4GHNEYuq545yDCtyWYtXO.png', NULL, 'image/png', 54092, NULL, NULL, NULL, '2025-09-19 07:33:05', '2025-09-19 07:33:05'),
(52, 1273, 30, 'public', '/storage/chat/AneiyAVbtt6uiiLKoLuPtxbNFcEjPDpacOIz8xZe.png', NULL, 'image/png', 61747, NULL, NULL, NULL, '2025-09-30 19:16:14', '2025-09-30 19:16:14'),
(53, 1365, 9798, 'public', '/storage/chat/zPxzHKj2xGW5mCA7UHIBPmQkUvDMjf64ZQgooQxU.pdf', NULL, 'application/pdf', 6226456, NULL, NULL, NULL, '2025-10-03 05:45:38', '2025-10-03 05:45:38'),
(54, 1366, 9798, 'public', '/storage/chat/mqYXKi3tepUGh86AlVr6JpzwIEttKXWRLwCRutv9.pdf', NULL, 'application/pdf', 153468, NULL, NULL, NULL, '2025-10-03 05:46:09', '2025-10-03 05:46:09'),
(55, 1407, 9810, 'public', '/storage/chat/gfLxTy6bHAgDOJxpRYoTqg5YZG7ZBLbWIiEQqzUO.png', NULL, 'image/png', 315805, NULL, NULL, NULL, '2025-10-06 07:23:43', '2025-10-06 07:23:43'),
(56, 1495, 9787, 'public', '/storage/chat/CG5QrxCYX7NXIDPIStIkq6G1DJKdn7bJcqDUKgtv.png', NULL, 'image/png', 99588, NULL, NULL, NULL, '2025-10-08 06:12:15', '2025-10-08 06:12:15'),
(57, 1614, 9810, 'public', '/storage/chat/hrn9s91eLOtmIXuWpV9r843dqRQw8mcXrEeVUg2p.png', NULL, 'image/png', 22735, NULL, NULL, NULL, '2025-10-13 12:42:35', '2025-10-13 12:42:35'),
(58, 1616, 9810, 'public', '/storage/chat/YWPWM7HIKSqNsqbMyn4YAWasKBmnSNwMpTXRm3Fv.png', NULL, 'image/png', 15341, NULL, NULL, NULL, '2025-10-13 14:02:36', '2025-10-13 14:02:36'),
(59, 1966, 7, 'public', 'chat/videos/Z01bh3dvwySbCCMp8qKeTTiNVEYJEizT18uioCHO.mkv', '2025-12-21 23-05-40.mkv', 'video/x-matroska', 7130505, NULL, NULL, NULL, '2025-12-24 00:57:56', '2025-12-24 00:57:56');

-- --------------------------------------------------------

--
-- Table structure for table `chat_bot_commands`
--

CREATE TABLE `chat_bot_commands` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `bot_id` bigint(20) UNSIGNED NOT NULL,
  `required_role_id` bigint(20) UNSIGNED DEFAULT NULL,
  `command` varchar(255) NOT NULL,
  `description` varchar(255) DEFAULT NULL,
  `sample_input` varchar(255) DEFAULT NULL,
  `execution_handler` varchar(255) DEFAULT NULL,
  `cooldown_seconds` int(10) UNSIGNED NOT NULL DEFAULT 0,
  `visibility` varchar(255) NOT NULL DEFAULT 'public',
  `metadata` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`metadata`)),
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `chat_bot_profiles`
--

CREATE TABLE `chat_bot_profiles` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `conversation_id` bigint(20) UNSIGNED DEFAULT NULL,
  `owner_user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `name` varchar(255) NOT NULL,
  `slug` varchar(255) NOT NULL,
  `avatar_url` varchar(255) DEFAULT NULL,
  `trigger_prefix` varchar(255) NOT NULL DEFAULT '/',
  `default_role` varchar(255) DEFAULT NULL,
  `is_global` tinyint(1) NOT NULL DEFAULT 0,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `settings` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`settings`)),
  `description` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `chat_conversation`
--

CREATE TABLE `chat_conversation` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `type` enum('dm','group') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `invite_code` varchar(32) DEFAULT NULL,
  `invite_enabled` tinyint(1) NOT NULL DEFAULT 0,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `photo` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_by` bigint(20) UNSIGNED NOT NULL,
  `is_public` tinyint(1) NOT NULL DEFAULT 0,
  `settings` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL,
  `meet` int(11) DEFAULT NULL,
  `meet_by` int(11) DEFAULT NULL,
  `is_group` int(11) NOT NULL DEFAULT 0,
  `joined` int(11) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `chat_conversation`
--

INSERT INTO `chat_conversation` (`id`, `type`, `invite_code`, `invite_enabled`, `name`, `photo`, `created_by`, `is_public`, `settings`, `meet`, `meet_by`, `is_group`, `joined`, `created_at`, `updated_at`) VALUES
(2, 'dm', NULL, 0, NULL, NULL, 9790, 0, NULL, 1, NULL, 0, 0, '2025-08-17 16:36:05', '2025-10-16 16:00:12'),
(3, 'dm', NULL, 0, NULL, NULL, 9790, 0, NULL, 0, NULL, 0, 0, '2025-08-17 16:37:41', '2025-08-17 16:37:41'),
(4, 'dm', NULL, 0, NULL, NULL, 9790, 0, NULL, NULL, NULL, 0, 0, '2025-08-17 16:38:31', '2025-10-16 16:14:31'),
(5, 'dm', NULL, 0, NULL, NULL, 9790, 0, NULL, 0, NULL, 0, 0, '2025-08-17 16:39:09', '2025-10-02 08:17:43'),
(6, 'group', NULL, 0, 'Marketing Team', NULL, 9790, 0, NULL, 1, NULL, 0, 0, '2025-08-18 17:09:53', '2025-10-16 20:00:30'),
(7, 'group', NULL, 0, 'Portal Training & Navigation Team', NULL, 9790, 0, NULL, 1, NULL, 0, 8, '2025-08-18 18:31:04', '2025-10-20 19:06:56'),
(8, 'dm', NULL, 0, NULL, NULL, 9790, 0, NULL, 0, NULL, 0, 0, '2025-08-20 03:33:22', '2025-08-20 03:33:22'),
(9, 'group', NULL, 0, 'California Commercial Cleaning Team', NULL, 9790, 0, NULL, NULL, NULL, 0, 0, '2025-08-20 04:33:01', '2025-10-16 06:18:56'),
(10, 'group', NULL, 0, 'IMS_ZoomInfo Team', NULL, 9790, 0, NULL, 1, NULL, 0, 0, '2025-08-21 15:54:34', '2025-10-16 16:28:10'),
(11, 'group', NULL, 0, 'Web Development Team', NULL, 9790, 0, NULL, 1, NULL, 0, 15, '2025-08-22 05:59:45', '2025-10-20 07:33:21'),
(12, 'dm', NULL, 0, NULL, NULL, 9827, 0, NULL, 0, NULL, 0, 0, '2025-08-22 09:02:35', '2025-08-22 09:02:35'),
(13, 'dm', NULL, 0, NULL, NULL, 9827, 0, NULL, 0, NULL, 0, 0, '2025-08-22 09:03:18', '2025-08-22 09:03:18'),
(14, 'dm', NULL, 0, NULL, NULL, 9798, 0, NULL, 0, NULL, 0, 0, '2025-08-22 19:05:35', '2025-10-01 19:11:44'),
(15, 'dm', NULL, 0, NULL, NULL, 30, 0, NULL, NULL, NULL, 0, 0, '2025-08-23 00:17:19', '2025-10-15 07:27:22'),
(16, 'group', NULL, 0, 'Hillbcs Directors, Manager & Owners Exclusive Meeting', NULL, 9790, 0, NULL, 1, NULL, 0, 0, '2025-08-24 17:09:44', '2025-10-20 17:31:27'),
(17, 'group', NULL, 0, 'Hillbcs QB Invoicing', NULL, 9790, 0, NULL, 1, NULL, 0, 2, '2025-08-25 05:39:49', '2025-10-20 06:25:20'),
(18, 'group', NULL, 0, 'PlanPanther-(Sales Team)', NULL, 30, 0, NULL, NULL, NULL, 0, 0, '2025-08-25 06:54:09', '2025-10-13 07:03:03'),
(19, 'group', NULL, 0, 'Hillbs Handbook Creation', NULL, 9790, 0, NULL, 0, NULL, 0, 0, '2025-08-26 07:16:24', '2025-10-07 18:01:33'),
(20, 'group', NULL, 0, 'Cleantec Prep Meeting', NULL, 9790, 0, NULL, 1, NULL, 0, 0, '2025-08-26 14:34:22', '2025-10-14 15:48:53'),
(21, 'dm', NULL, 0, NULL, NULL, 9827, 0, NULL, NULL, NULL, 0, 0, '2025-08-28 06:26:58', '2025-10-10 11:31:33'),
(22, 'dm', NULL, 0, NULL, NULL, 9827, 0, NULL, 0, NULL, 0, 0, '2025-08-29 08:06:49', '2025-09-25 14:56:36'),
(23, 'dm', NULL, 0, NULL, NULL, 9779, 0, NULL, 0, NULL, 0, 0, '2025-08-30 12:55:41', '2025-08-30 12:55:41'),
(24, 'dm', NULL, 0, NULL, NULL, 9779, 0, NULL, 0, NULL, 0, 0, '2025-08-30 12:55:50', '2025-10-01 10:01:01'),
(25, 'dm', NULL, 0, NULL, NULL, 9797, 0, NULL, 0, NULL, 0, 0, '2025-08-31 18:58:37', '2025-08-31 18:58:37'),
(26, 'group', NULL, 0, 'Plan Panther Weekly Meeting', NULL, 9790, 0, NULL, 1, NULL, 0, 4, '2025-08-31 19:44:45', '2025-10-20 07:49:28'),
(27, 'dm', NULL, 0, NULL, NULL, 44, 0, NULL, NULL, NULL, 0, 0, '2025-09-03 08:09:09', '2025-10-14 18:30:58'),
(28, 'dm', NULL, 0, NULL, NULL, 9790, 0, NULL, 0, NULL, 0, 0, '2025-09-03 17:00:35', '2025-09-03 17:00:35'),
(29, 'dm', NULL, 0, NULL, NULL, 9635, 0, NULL, 0, NULL, 0, 0, '2025-09-04 04:58:51', '2025-10-01 19:01:40'),
(30, 'dm', NULL, 0, NULL, NULL, 9808, 0, NULL, 0, NULL, 0, 0, '2025-09-04 05:01:41', '2025-09-04 05:01:41'),
(31, 'dm', NULL, 0, NULL, NULL, 9820, 0, NULL, NULL, NULL, 0, 0, '2025-09-04 05:04:38', '2025-10-16 07:05:04'),
(32, 'dm', NULL, 0, NULL, NULL, 44, 0, NULL, NULL, NULL, 0, 0, '2025-09-04 05:45:01', '2025-10-14 18:31:03'),
(33, 'dm', NULL, 0, NULL, NULL, 44, 0, NULL, NULL, NULL, 0, 0, '2025-09-04 05:45:09', '2025-10-14 18:30:56'),
(34, 'dm', NULL, 0, NULL, NULL, 8593, 0, NULL, 0, NULL, 0, 0, '2025-09-04 15:49:45', '2025-10-06 05:30:52'),
(35, 'dm', NULL, 0, NULL, NULL, 9776, 0, NULL, 0, NULL, 0, 0, '2025-09-04 18:35:49', '2025-09-04 18:35:49'),
(36, 'dm', NULL, 0, NULL, NULL, 9776, 0, NULL, 0, NULL, 0, 0, '2025-09-04 18:35:54', '2025-09-04 18:35:54'),
(37, 'dm', NULL, 0, NULL, NULL, 9805, 0, NULL, 0, NULL, 0, 0, '2025-09-04 18:37:49', '2025-09-04 18:37:49'),
(38, 'dm', NULL, 0, NULL, NULL, 9797, 0, NULL, 0, NULL, 0, 0, '2025-09-04 18:43:18', '2025-09-04 18:43:18'),
(39, 'dm', NULL, 0, NULL, NULL, 4911, 0, NULL, NULL, NULL, 0, 0, '2025-09-05 10:39:52', '2025-10-17 07:04:24'),
(40, 'dm', NULL, 0, NULL, NULL, 4911, 0, NULL, NULL, NULL, 0, 0, '2025-09-06 06:09:27', '2025-10-12 14:51:33'),
(41, 'group', NULL, 0, 'Saffi-Kimberly-Douglas', NULL, 4911, 0, NULL, NULL, NULL, 0, 0, '2025-09-06 06:13:18', '2025-10-09 06:03:45'),
(42, 'dm', NULL, 0, NULL, NULL, 5542, 0, NULL, NULL, NULL, 0, 0, '2025-09-08 18:50:28', '2025-10-14 18:43:14'),
(43, 'dm', NULL, 0, NULL, NULL, 9816, 0, NULL, 0, NULL, 0, 0, '2025-09-10 05:58:46', '2025-09-10 05:58:46'),
(44, 'dm', NULL, 0, NULL, NULL, 9635, 0, NULL, NULL, NULL, 0, 0, '2025-09-10 08:08:43', '2025-10-08 19:08:32'),
(45, 'dm', NULL, 0, NULL, NULL, 9806, 0, NULL, 0, NULL, 0, 0, '2025-09-10 14:00:54', '2025-10-02 17:46:48'),
(46, 'dm', NULL, 0, NULL, NULL, 9808, 0, NULL, 0, NULL, 0, 0, '2025-09-10 18:29:07', '2025-09-10 18:29:07'),
(47, 'dm', NULL, 0, NULL, NULL, 8593, 0, NULL, 0, NULL, 0, 0, '2025-09-10 19:02:27', '2025-10-06 05:30:53'),
(48, 'dm', NULL, 0, NULL, NULL, 9811, 0, NULL, NULL, NULL, 0, 0, '2025-09-11 18:34:38', '2025-10-20 06:03:25'),
(49, 'dm', NULL, 0, NULL, NULL, 9816, 0, NULL, 0, NULL, 0, 0, '2025-09-11 19:38:29', '2025-09-11 19:38:29'),
(50, 'group', NULL, 0, 'Combine Team_Kimberly\'s & Sofia\'s Team', NULL, 9790, 0, NULL, NULL, NULL, 0, 0, '2025-09-15 16:05:02', '2025-10-20 18:04:30'),
(51, 'dm', NULL, 0, NULL, NULL, 9790, 0, NULL, 0, NULL, 0, 0, '2025-09-16 16:08:40', '2025-09-16 16:08:40'),
(52, 'dm', NULL, 0, NULL, NULL, 9811, 0, NULL, 0, NULL, 0, 0, '2025-09-17 15:13:37', '2025-10-01 10:01:07'),
(53, 'dm', NULL, 0, NULL, NULL, 30, 0, NULL, 0, NULL, 0, 0, '2025-09-18 07:21:01', '2025-09-25 14:56:27'),
(54, 'dm', NULL, 0, NULL, NULL, 30, 0, NULL, NULL, NULL, 0, 0, '2025-09-18 07:21:18', '2025-10-17 07:04:57'),
(55, 'dm', NULL, 0, NULL, NULL, 9810, 0, NULL, NULL, NULL, 0, 0, '2025-09-18 08:02:54', '2025-10-09 08:22:12'),
(56, 'dm', NULL, 0, NULL, NULL, 9810, 0, NULL, NULL, NULL, 0, 0, '2025-09-18 14:28:21', '2025-10-17 07:04:52'),
(57, 'dm', NULL, 0, NULL, NULL, 9635, 0, NULL, 0, NULL, 0, 0, '2025-09-18 18:26:35', '2025-10-01 19:01:36'),
(58, 'group', NULL, 0, 'Sofia-Douglas-Saffi', NULL, 9790, 0, NULL, 1, NULL, 0, 0, '2025-09-22 05:56:49', '2025-10-13 07:27:36'),
(59, 'dm', NULL, 0, NULL, NULL, 9790, 0, NULL, 0, NULL, 0, 0, '2025-09-25 16:58:49', '2025-09-25 16:58:49'),
(60, 'dm', NULL, 0, NULL, NULL, 9790, 0, NULL, 0, NULL, 0, 0, '2025-09-25 17:03:44', '2025-09-25 17:03:44'),
(61, 'dm', NULL, 0, NULL, NULL, 9798, 0, NULL, 0, NULL, 0, 0, '2025-09-26 07:55:53', '2025-09-26 07:55:53'),
(62, 'group', NULL, 0, 'Follow-ups', NULL, 9798, 0, NULL, 0, NULL, 0, 1, '2025-09-26 07:56:36', '2025-09-26 08:01:09'),
(63, 'dm', NULL, 0, NULL, NULL, 4911, 0, NULL, 0, NULL, 0, 1, '2025-09-30 21:03:55', '2025-09-30 21:08:02'),
(64, 'dm', NULL, 0, NULL, NULL, 9635, 0, NULL, NULL, NULL, 0, 0, '2025-10-02 18:47:36', '2025-10-08 18:24:20'),
(65, 'dm', NULL, 0, NULL, NULL, 9820, 0, NULL, NULL, NULL, 0, 0, '2025-10-02 18:58:19', '2025-10-08 18:44:08'),
(66, 'dm', NULL, 0, NULL, NULL, 9829, 0, NULL, NULL, NULL, 0, 0, '2025-10-02 19:15:56', '2025-10-08 19:06:17'),
(67, 'dm', NULL, 0, NULL, NULL, 9837, 0, NULL, NULL, NULL, 0, 0, '2025-10-06 06:17:41', '2025-10-13 15:15:59'),
(68, 'dm', NULL, 0, NULL, NULL, 9829, 0, NULL, NULL, NULL, 0, 0, '2025-10-06 10:55:22', '2025-10-13 12:42:29'),
(69, 'dm', NULL, 0, NULL, NULL, 9810, 0, NULL, NULL, NULL, 0, 0, '2025-10-13 14:02:29', '2025-10-13 14:02:29'),
(70, 'dm', NULL, 0, NULL, NULL, 9790, 0, NULL, NULL, NULL, 0, 0, '2025-10-14 16:40:52', '2025-10-14 16:40:52'),
(71, 'dm', NULL, 0, NULL, NULL, 5965, 0, NULL, NULL, NULL, 0, 0, '2025-10-14 18:23:06', '2025-10-14 18:23:06'),
(72, 'dm', NULL, 0, NULL, NULL, 9820, 0, NULL, NULL, NULL, 0, 0, '2025-10-16 01:51:35', '2025-10-16 01:51:35'),
(73, 'dm', NULL, 0, NULL, NULL, 9787, 0, NULL, NULL, NULL, 0, 0, '2025-10-20 06:19:06', '2025-10-20 06:19:06'),
(74, 'dm', NULL, 0, NULL, NULL, 44, 0, NULL, NULL, NULL, 0, 0, '2025-11-11 22:46:57', '2025-11-11 22:46:57'),
(99, 'dm', NULL, 0, NULL, NULL, 4911, 0, NULL, NULL, NULL, 0, 0, '2025-11-21 19:48:47', '2025-11-21 19:48:47'),
(100, 'dm', NULL, 0, NULL, NULL, 9831, 0, NULL, NULL, NULL, 0, 0, '2025-11-22 12:36:09', '2025-11-22 12:36:09'),
(108, 'dm', NULL, 0, NULL, NULL, 4, 0, NULL, NULL, NULL, 0, 0, '2025-12-06 06:32:47', '2025-12-06 06:32:47'),
(109, 'group', NULL, 0, 'test group', NULL, 4, 0, '{\"invite_enabled\":true,\"channel_admin_only\":false,\"channel_slow_mode\":false,\"channel_visibility\":\"public\",\"bot_enabled\":false,\"bot_name\":\"ModBot\",\"bot_role\":\"moderator\",\"permissions\":{\"warn\":true,\"mute\":true,\"kick\":false,\"delete\":true},\"rules\":{\"profanity\":true,\"spam\":true,\"links\":false,\"caps\":false},\"violation_action\":\"warn\",\"description\":null}', NULL, NULL, 0, 0, '2025-12-07 05:43:55', '2025-12-07 22:26:25');

-- --------------------------------------------------------

--
-- Table structure for table `chat_conversation_participants`
--

CREATE TABLE `chat_conversation_participants` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `conversation_id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `role` enum('owner','admin','member') NOT NULL DEFAULT 'member',
  `is_pinned` tinyint(1) NOT NULL DEFAULT 0,
  `is_archived` tinyint(1) NOT NULL DEFAULT 0,
  `is_trashed` tinyint(1) NOT NULL DEFAULT 0,
  `is_muted` tinyint(1) NOT NULL DEFAULT 0,
  `muted_until` timestamp NULL DEFAULT NULL COMMENT 'Timestamp until which member is muted',
  `can_send_messages` tinyint(1) NOT NULL DEFAULT 1,
  `can_view_channels` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL COMMENT 'JSON array of channel IDs this member can view, null = all' CHECK (json_valid(`can_view_channels`)),
  `last_read_message_id` bigint(20) UNSIGNED DEFAULT NULL,
  `last_read_at` timestamp NULL DEFAULT NULL,
  `joined_at` timestamp NULL DEFAULT NULL,
  `left_at` timestamp NULL DEFAULT NULL,
  `unread_count` int(11) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `chat_conversation_participants`
--

INSERT INTO `chat_conversation_participants` (`id`, `conversation_id`, `user_id`, `role`, `is_pinned`, `is_archived`, `is_trashed`, `is_muted`, `muted_until`, `can_send_messages`, `can_view_channels`, `last_read_message_id`, `last_read_at`, `joined_at`, `left_at`, `unread_count`, `created_at`, `updated_at`) VALUES
(1, 1, 44, 'owner', 0, 0, 0, 0, NULL, 1, NULL, NULL, NULL, '2025-08-17 09:20:30', NULL, 0, '2025-08-17 09:20:30', '2025-08-17 09:20:30'),
(2, 1, 1, 'member', 0, 0, 0, 0, NULL, 1, NULL, NULL, NULL, '2025-08-17 09:20:30', NULL, 0, '2025-08-17 09:20:30', '2025-08-17 09:20:30'),
(3, 2, 9790, 'owner', 0, 0, 0, 0, NULL, 1, NULL, 1861, '2025-11-21 15:53:27', '2025-08-17 16:36:05', NULL, 2, '2025-08-17 16:36:05', '2025-11-21 17:13:58'),
(4, 2, 4911, 'member', 0, 0, 0, 0, NULL, 1, NULL, 1870, '2025-11-21 17:58:43', '2025-08-17 16:36:05', NULL, 0, '2025-08-17 16:36:05', '2025-11-21 17:58:43'),
(5, 3, 9790, 'owner', 0, 0, 0, 0, NULL, 1, NULL, 1830, '2025-11-21 07:31:20', '2025-08-17 16:37:41', NULL, 1, '2025-08-17 16:37:41', '2025-11-21 07:31:40'),
(6, 3, 9798, 'member', 0, 0, 0, 0, NULL, 1, NULL, 1831, '2025-11-22 12:50:15', '2025-08-17 16:37:41', NULL, 0, '2025-08-17 16:37:41', '2025-11-22 12:50:15'),
(7, 4, 9790, 'owner', 0, 0, 0, 0, NULL, 1, NULL, 144, '2025-10-16 16:14:31', '2025-08-17 16:38:31', NULL, 0, '2025-08-17 16:38:31', '2025-10-16 16:14:31'),
(8, 4, 30, 'member', 0, 0, 0, 0, NULL, 1, NULL, 144, '2025-10-02 17:46:40', '2025-08-17 16:38:31', NULL, 0, '2025-08-17 16:38:31', '2025-10-02 17:46:40'),
(9, 5, 9790, 'owner', 0, 0, 0, 0, NULL, 1, NULL, 4, '2025-08-21 19:37:15', '2025-08-17 16:39:09', NULL, 0, '2025-08-17 16:39:09', '2025-08-21 19:37:15'),
(10, 5, 9802, 'member', 0, 0, 0, 0, NULL, 1, NULL, 4, '2025-10-02 08:17:43', '2025-08-17 16:39:09', NULL, 0, '2025-08-17 16:39:09', '2025-10-02 08:17:43'),
(11, 6, 9790, 'owner', 0, 0, 0, 0, NULL, 1, NULL, 1687, '2025-10-16 06:29:31', '2025-08-18 17:09:53', NULL, 0, '2025-08-18 17:09:53', '2025-10-16 06:29:31'),
(12, 6, 4911, 'member', 0, 0, 0, 0, NULL, 1, NULL, 1698, '2025-10-16 15:58:46', '2025-08-18 17:09:53', NULL, 0, '2025-08-18 17:09:53', '2025-10-16 15:58:46'),
(13, 6, 9787, 'member', 0, 0, 0, 0, NULL, 1, NULL, 1688, '2025-10-16 06:29:36', '2025-08-18 17:09:53', NULL, 0, '2025-08-18 17:09:53', '2025-10-16 06:29:36'),
(14, 6, 9802, 'member', 0, 0, 0, 0, NULL, 1, NULL, 1691, '2025-10-16 06:32:11', '2025-08-18 17:09:53', NULL, 0, '2025-08-18 17:09:53', '2025-10-16 06:32:11'),
(15, 6, 9806, 'member', 0, 0, 0, 0, NULL, 1, NULL, 1086, '2025-09-24 17:24:37', '2025-08-18 17:09:53', NULL, 0, '2025-08-18 17:09:53', '2025-09-24 17:24:37'),
(16, 7, 9790, 'owner', 0, 0, 0, 0, NULL, 1, NULL, 1514, '2025-10-08 18:06:29', '2025-08-18 18:31:04', NULL, 4, '2025-08-18 18:31:04', '2025-12-23 10:19:45'),
(17, 7, 30, 'member', 0, 0, 0, 0, NULL, 1, NULL, 1795, '2025-10-20 18:16:00', '2025-08-18 18:31:04', NULL, 4, '2025-08-18 18:31:04', '2025-12-23 10:19:45'),
(18, 7, 4911, 'member', 0, 0, 0, 0, NULL, 1, NULL, 1797, '2025-11-21 16:15:34', '2025-08-18 18:31:04', NULL, 4, '2025-08-18 18:31:04', '2025-12-23 10:19:46'),
(19, 7, 9798, 'member', 0, 0, 0, 0, NULL, 1, NULL, 1797, '2025-11-21 13:20:00', '2025-08-18 18:31:04', NULL, 4, '2025-08-18 18:31:04', '2025-12-23 10:19:46'),
(20, 7, 9799, 'member', 0, 0, 0, 0, NULL, 1, NULL, 1793, '2025-10-20 18:04:42', '2025-08-18 18:31:04', NULL, 4, '2025-08-18 18:31:04', '2025-12-23 10:19:46'),
(21, 7, 9800, 'member', 0, 0, 0, 0, NULL, 1, NULL, 1792, '2025-10-20 18:03:29', '2025-08-18 18:31:04', NULL, 4, '2025-08-18 18:31:04', '2025-12-23 10:19:46'),
(22, 7, 9801, 'member', 0, 0, 0, 0, NULL, 1, NULL, 1790, '2025-10-20 18:01:24', '2025-08-18 18:31:04', NULL, 4, '2025-08-18 18:31:04', '2025-12-23 10:19:46'),
(23, 7, 9802, 'member', 0, 0, 0, 0, NULL, 1, NULL, 1716, '2025-10-16 18:06:47', '2025-08-18 18:31:04', NULL, 4, '2025-08-18 18:31:04', '2025-12-23 10:19:46'),
(24, 7, 9803, 'member', 0, 0, 0, 0, NULL, 1, NULL, 1791, '2025-10-20 18:02:31', '2025-08-18 18:31:04', NULL, 4, '2025-08-18 18:31:04', '2025-12-23 10:19:46'),
(25, 7, 9806, 'member', 0, 0, 0, 0, NULL, 1, NULL, 276, '2025-08-29 14:50:41', '2025-08-18 18:31:04', NULL, 4, '2025-08-18 18:31:04', '2025-12-23 10:19:46'),
(26, 7, 9810, 'member', 0, 0, 0, 0, NULL, 1, NULL, 1566, '2025-10-10 07:09:45', '2025-08-18 18:31:04', NULL, 4, '2025-08-18 18:31:04', '2025-12-23 10:19:46'),
(27, 8, 9790, 'owner', 0, 0, 0, 0, NULL, 1, NULL, 29, '2025-08-20 03:41:30', '2025-08-20 03:33:22', NULL, 0, '2025-08-20 03:33:22', '2025-08-20 03:41:30'),
(28, 8, 9787, 'member', 0, 0, 0, 0, NULL, 1, NULL, 27, '2025-08-27 06:03:15', '2025-08-20 03:33:22', NULL, 0, '2025-08-20 03:33:22', '2025-08-27 06:03:15'),
(29, 9, 9790, 'owner', 0, 0, 0, 0, NULL, 1, NULL, 115, '2025-11-21 15:22:03', '2025-08-20 04:33:01', NULL, 0, '2025-08-20 04:33:01', '2025-11-21 15:22:03'),
(30, 9, 1, 'member', 0, 0, 0, 0, NULL, 1, NULL, 115, '2025-09-02 21:13:09', '2025-08-20 04:33:01', NULL, 0, '2025-08-20 04:33:01', '2025-09-02 21:13:09'),
(31, 9, 30, 'member', 0, 0, 0, 0, NULL, 1, NULL, 115, '2025-09-11 17:29:57', '2025-08-20 04:33:01', NULL, 0, '2025-08-20 04:33:01', '2025-09-11 17:29:57'),
(32, 9, 4911, 'member', 0, 0, 0, 0, NULL, 1, NULL, 115, '2025-08-24 06:37:15', '2025-08-20 04:33:01', NULL, 0, '2025-08-20 04:33:01', '2025-08-24 06:37:15'),
(33, 9, 9779, 'member', 0, 0, 0, 0, NULL, 1, NULL, 115, '2025-10-16 06:18:56', '2025-08-20 04:33:01', NULL, 0, '2025-08-20 04:33:01', '2025-10-16 06:18:56'),
(34, 9, 9787, 'member', 0, 0, 0, 0, NULL, 1, NULL, 115, '2025-09-08 05:52:24', '2025-08-20 04:33:01', NULL, 0, '2025-08-20 04:33:01', '2025-09-08 05:52:24'),
(35, 9, 9802, 'member', 0, 0, 0, 0, NULL, 1, NULL, 53, '2025-08-21 16:57:43', '2025-08-20 04:33:01', NULL, 0, '2025-08-20 04:33:01', '2025-08-21 16:57:43'),
(36, 9, 9803, 'member', 0, 0, 0, 0, NULL, 1, NULL, 115, '2025-08-25 06:56:40', '2025-08-20 04:33:01', NULL, 0, '2025-08-20 04:33:01', '2025-08-25 06:56:40'),
(37, 9, 9806, 'member', 0, 0, 0, 0, NULL, 1, NULL, 115, '2025-08-29 14:50:38', '2025-08-20 04:33:01', NULL, 0, '2025-08-20 04:33:01', '2025-08-29 14:50:38'),
(38, 9, 9810, 'member', 0, 0, 0, 0, NULL, 1, NULL, 115, '2025-10-03 11:02:31', '2025-08-20 04:33:01', NULL, 0, '2025-08-20 04:33:01', '2025-10-03 11:02:31'),
(39, 9, 9814, 'member', 0, 0, 0, 0, NULL, 1, NULL, 115, '2025-09-24 16:29:23', '2025-08-20 04:33:01', NULL, 0, '2025-08-20 04:33:01', '2025-09-24 16:29:23'),
(40, 9, 9825, 'member', 0, 0, 0, 0, NULL, 1, NULL, 115, '2025-09-03 06:51:50', NULL, NULL, 0, NULL, '2025-09-03 06:51:50'),
(41, 10, 9790, 'owner', 0, 0, 0, 0, NULL, 1, NULL, 1705, '2025-10-16 16:10:07', '2025-08-21 15:54:34', NULL, 0, '2025-08-21 15:54:34', '2025-10-16 16:10:07'),
(42, 10, 30, 'member', 0, 0, 0, 0, NULL, 1, NULL, 1703, '2025-10-16 16:03:20', '2025-08-21 15:54:34', NULL, 0, '2025-08-21 15:54:34', '2025-10-16 16:03:20'),
(43, 10, 4911, 'member', 0, 0, 0, 0, NULL, 1, NULL, 1708, '2025-11-21 14:40:48', '2025-08-21 15:54:34', NULL, 0, '2025-08-21 15:54:34', '2025-11-21 14:40:48'),
(44, 10, 9801, 'member', 0, 0, 0, 0, NULL, 1, NULL, 1702, '2025-10-16 16:00:14', '2025-08-21 15:54:34', NULL, 0, '2025-08-21 15:54:34', '2025-10-16 16:00:14'),
(45, 10, 9806, 'member', 0, 0, 0, 0, NULL, 1, NULL, NULL, NULL, '2025-08-21 15:54:34', NULL, 0, '2025-08-21 15:54:34', '2025-08-21 15:54:34'),
(46, 11, 9790, 'owner', 0, 0, 0, 0, NULL, 1, NULL, 1839, '2025-11-21 10:47:54', '2025-08-22 05:59:45', NULL, 13, '2025-08-22 05:59:45', '2025-11-21 20:13:05'),
(47, 11, 1, 'member', 0, 0, 0, 0, NULL, 1, NULL, 1578, '2025-10-10 11:31:51', '2025-08-22 05:59:45', NULL, 34, '2025-08-22 05:59:45', '2025-11-21 20:13:05'),
(48, 11, 30, 'member', 0, 0, 0, 0, NULL, 1, NULL, 1746, '2025-10-20 06:02:31', '2025-08-22 05:59:45', NULL, 34, '2025-08-22 05:59:45', '2025-11-21 20:13:05'),
(49, 11, 4911, 'member', 0, 0, 0, 0, NULL, 1, NULL, 1903, '2025-11-21 20:13:04', '2025-08-22 05:59:45', NULL, 0, '2025-08-22 05:59:45', '2025-11-21 20:13:04'),
(50, 11, 9787, 'member', 0, 0, 0, 0, NULL, 1, NULL, 1747, '2025-10-20 06:02:54', '2025-08-22 05:59:45', NULL, 34, '2025-08-22 05:59:45', '2025-11-21 20:13:05'),
(51, 11, 9806, 'member', 0, 0, 0, 0, NULL, 1, NULL, 1071, '2025-09-24 06:01:15', '2025-08-22 05:59:45', NULL, 34, '2025-08-22 05:59:45', '2025-11-21 20:13:05'),
(52, 11, 9810, 'member', 0, 0, 0, 0, NULL, 1, NULL, 1754, '2025-10-20 07:03:44', '2025-08-22 05:59:45', NULL, 34, '2025-08-22 05:59:45', '2025-11-21 20:13:06'),
(53, 11, 9814, 'member', 0, 0, 0, 0, NULL, 1, NULL, 1730, '2025-10-17 11:22:02', '2025-08-22 05:59:45', NULL, 34, '2025-08-22 05:59:45', '2025-11-21 20:13:06'),
(54, 11, 9827, 'member', 0, 0, 0, 0, NULL, 1, NULL, 456, '2025-09-02 06:34:32', '2025-08-22 05:59:45', '2025-09-12 05:55:52', 34, '2025-08-22 05:59:45', '2025-11-21 20:13:06'),
(55, 12, 9827, 'owner', 0, 0, 0, 0, NULL, 1, NULL, 102, '2025-08-22 09:02:51', '2025-08-22 09:02:35', NULL, 1, '2025-08-22 09:02:35', '2025-11-21 06:07:44'),
(56, 12, 9790, 'member', 0, 0, 0, 0, NULL, 1, NULL, 1821, '2025-11-21 06:07:43', '2025-08-22 09:02:35', NULL, 0, '2025-08-22 09:02:35', '2025-11-21 06:07:43'),
(57, 13, 9827, 'owner', 0, 0, 0, 0, NULL, 1, NULL, NULL, NULL, '2025-08-22 09:03:18', NULL, 0, '2025-08-22 09:03:18', '2025-08-22 09:03:18'),
(58, 13, 9806, 'member', 0, 0, 0, 0, NULL, 1, NULL, NULL, NULL, '2025-08-22 09:03:18', NULL, 0, '2025-08-22 09:03:18', '2025-08-22 09:03:18'),
(59, 14, 9798, 'owner', 0, 0, 0, 0, NULL, 1, NULL, NULL, NULL, '2025-08-22 19:05:35', NULL, 0, '2025-08-22 19:05:35', '2025-08-22 19:05:35'),
(60, 14, 44, 'member', 0, 0, 0, 0, NULL, 1, NULL, NULL, '2025-11-22 14:22:15', '2025-08-22 19:05:35', NULL, 0, '2025-08-22 19:05:35', '2025-11-22 14:22:15'),
(61, 15, 30, 'owner', 0, 0, 0, 0, NULL, 1, NULL, 1682, '2025-10-18 03:43:30', '2025-08-23 00:17:19', NULL, 0, '2025-08-23 00:17:19', '2025-10-18 03:43:30'),
(62, 15, 9779, 'member', 0, 0, 0, 0, NULL, 1, NULL, 659, '2025-09-24 05:49:23', '2025-08-23 00:17:19', NULL, 0, '2025-08-23 00:17:19', '2025-09-24 05:49:23'),
(63, 16, 9790, 'owner', 0, 0, 0, 0, NULL, 1, NULL, 1782, '2025-10-20 16:58:03', '2025-08-24 17:09:44', NULL, 2, '2025-08-24 17:09:44', '2025-12-23 10:21:01'),
(64, 16, 1, 'member', 0, 0, 0, 0, NULL, 1, NULL, 1243, '2025-10-10 11:31:40', '2025-08-24 17:09:44', NULL, 2, '2025-08-24 17:09:44', '2025-12-23 10:21:01'),
(65, 16, 30, 'member', 0, 0, 0, 0, NULL, 1, NULL, 1784, '2025-10-20 17:01:14', '2025-08-24 17:09:44', NULL, 2, '2025-08-24 17:09:44', '2025-12-23 10:21:01'),
(66, 16, 4911, 'member', 0, 0, 0, 0, NULL, 1, NULL, 1243, '2025-09-30 06:12:41', '2025-08-24 17:09:44', NULL, 2, '2025-08-24 17:09:44', '2025-12-23 10:21:01'),
(67, 16, 9787, 'member', 0, 0, 0, 0, NULL, 1, NULL, 1785, '2025-10-20 17:10:33', '2025-08-24 17:09:44', NULL, 2, '2025-08-24 17:09:44', '2025-12-23 10:21:01'),
(68, 16, 9806, 'member', 0, 0, 0, 0, NULL, 1, NULL, 122, '2025-08-29 14:50:35', '2025-08-24 17:09:44', NULL, 2, '2025-08-24 17:09:44', '2025-12-23 10:21:01'),
(69, 16, 9813, 'member', 0, 0, 0, 0, NULL, 1, NULL, 1786, '2025-10-20 17:11:53', '2025-08-24 17:09:44', NULL, 2, '2025-08-24 17:09:44', '2025-12-23 10:21:01'),
(70, 17, 9790, 'owner', 0, 0, 0, 0, NULL, 1, NULL, 1752, '2025-11-21 15:13:14', '2025-08-25 05:39:49', NULL, 0, '2025-08-25 05:39:49', '2025-11-21 15:13:14'),
(71, 17, 4911, 'member', 0, 0, 0, 0, NULL, 1, NULL, 1748, '2025-10-20 06:03:24', '2025-08-25 05:39:49', NULL, 0, '2025-08-25 05:39:49', '2025-10-20 06:03:24'),
(72, 17, 9806, 'member', 0, 0, 0, 0, NULL, 1, NULL, 128, '2025-08-29 14:50:29', '2025-08-25 05:39:49', NULL, 0, '2025-08-25 05:39:49', '2025-08-29 14:50:29'),
(73, 17, 9812, 'member', 0, 0, 0, 0, NULL, 1, NULL, 1749, '2025-10-20 06:03:32', '2025-08-25 05:39:49', NULL, 0, '2025-08-25 05:39:49', '2025-10-20 06:03:32'),
(74, 17, 9814, 'member', 0, 0, 0, 0, NULL, 1, NULL, 1610, '2025-10-13 11:04:53', '2025-08-25 05:39:49', NULL, 0, '2025-08-25 05:39:49', '2025-10-13 11:04:53'),
(75, 18, 30, 'owner', 0, 0, 0, 0, NULL, 1, NULL, 148, '2025-09-06 04:23:13', '2025-08-25 06:54:09', NULL, 1, '2025-08-25 06:54:09', '2025-11-22 14:21:52'),
(76, 18, 44, 'member', 0, 0, 0, 0, NULL, 1, NULL, 1907, '2025-11-22 14:21:52', '2025-08-25 06:54:09', NULL, 0, '2025-08-25 06:54:09', '2025-11-22 14:21:52'),
(77, 18, 9799, 'member', 0, 0, 0, 0, NULL, 1, NULL, 148, '2025-08-27 18:05:01', '2025-08-25 06:54:09', NULL, 1, '2025-08-25 06:54:09', '2025-11-22 14:21:53'),
(78, 18, 9800, 'member', 0, 0, 0, 0, NULL, 1, NULL, 148, '2025-09-02 21:56:31', '2025-08-25 06:54:09', NULL, 1, '2025-08-25 06:54:09', '2025-11-22 14:21:53'),
(79, 18, 9802, 'member', 0, 0, 0, 0, NULL, 1, NULL, 152, '2025-08-25 07:03:48', '2025-08-25 06:54:09', NULL, 1, '2025-08-25 06:54:09', '2025-11-22 14:21:53'),
(80, 18, 9803, 'member', 0, 0, 0, 0, NULL, 1, NULL, 148, '2025-10-13 07:03:03', '2025-08-25 06:54:09', NULL, 1, '2025-08-25 06:54:09', '2025-11-22 14:21:53'),
(82, 19, 1, 'member', 0, 0, 0, 0, NULL, 1, NULL, NULL, NULL, '2025-08-26 07:16:24', NULL, 0, '2025-08-26 07:16:24', '2025-08-26 07:16:24'),
(83, 19, 4911, 'member', 0, 0, 0, 0, NULL, 1, NULL, NULL, NULL, '2025-08-26 07:16:24', NULL, 0, '2025-08-26 07:16:24', '2025-08-26 07:16:24'),
(84, 19, 9787, 'member', 0, 0, 0, 0, NULL, 1, NULL, NULL, NULL, '2025-08-26 07:16:24', NULL, 0, '2025-08-26 07:16:24', '2025-08-26 07:16:24'),
(85, 19, 9806, 'member', 0, 0, 0, 0, NULL, 1, NULL, NULL, NULL, '2025-08-26 07:16:24', NULL, 0, '2025-08-26 07:16:24', '2025-08-26 07:16:24'),
(86, 19, 9814, 'member', 0, 0, 0, 0, NULL, 1, NULL, NULL, NULL, '2025-08-26 07:16:24', NULL, 0, '2025-08-26 07:16:24', '2025-08-26 07:16:24'),
(87, 20, 9790, 'owner', 0, 0, 0, 0, NULL, 1, NULL, 1642, '2025-10-14 15:20:25', '2025-08-26 14:34:22', NULL, 0, '2025-08-26 14:34:22', '2025-10-14 15:20:25'),
(88, 20, 4911, 'member', 0, 0, 0, 0, NULL, 1, NULL, 1461, '2025-10-11 05:01:37', '2025-08-26 14:34:22', NULL, 0, '2025-08-26 14:34:22', '2025-10-11 05:01:37'),
(89, 20, 9797, 'member', 0, 0, 0, 0, NULL, 1, NULL, 1264, '2025-09-30 15:33:51', '2025-08-26 14:34:22', NULL, 0, '2025-08-26 14:34:22', '2025-09-30 15:33:51'),
(90, 20, 9806, 'member', 0, 0, 0, 0, NULL, 1, NULL, 863, '2025-09-23 06:29:58', '2025-08-26 14:34:22', NULL, 0, '2025-08-26 14:34:22', '2025-09-23 06:29:58'),
(91, 20, 9813, 'member', 0, 0, 0, 0, NULL, 1, NULL, 1641, '2025-10-14 14:31:28', '2025-08-26 14:34:22', NULL, 0, '2025-08-26 14:34:22', '2025-10-14 14:31:28'),
(92, 21, 9827, 'owner', 0, 0, 0, 0, NULL, 1, NULL, 221, '2025-08-28 06:28:23', '2025-08-28 06:26:58', NULL, 0, '2025-08-28 06:26:58', '2025-08-28 06:28:23'),
(93, 21, 1, 'member', 0, 0, 0, 0, NULL, 1, NULL, 221, '2025-10-13 08:16:50', '2025-08-28 06:26:58', NULL, 0, '2025-08-28 06:26:58', '2025-10-13 08:16:50'),
(94, 11, 9798, 'member', 0, 0, 0, 0, NULL, 1, NULL, 1897, '2025-11-22 12:50:21', '2025-08-29 06:20:46', NULL, 0, '2025-08-29 06:20:46', '2025-11-22 12:50:21'),
(95, 22, 9827, 'owner', 0, 0, 0, 0, NULL, 1, NULL, 279, '2025-08-29 08:07:19', '2025-08-29 08:06:49', NULL, 0, '2025-08-29 08:06:49', '2025-08-29 08:07:19'),
(96, 22, 30, 'member', 0, 0, 0, 0, NULL, 1, NULL, 281, '2025-09-25 14:56:36', '2025-08-29 08:06:49', NULL, 0, '2025-08-29 08:06:49', '2025-09-25 14:56:36'),
(97, 6, 9827, 'member', 0, 0, 0, 0, NULL, 1, NULL, 474, '2025-09-02 06:34:36', '2025-08-29 17:40:58', '2025-09-11 16:27:35', 0, '2025-08-29 17:40:58', '2025-09-11 16:27:35'),
(98, 23, 9779, 'owner', 0, 0, 0, 0, NULL, 1, NULL, NULL, NULL, '2025-08-30 12:55:41', NULL, 0, '2025-08-30 12:55:41', '2025-08-30 12:55:41'),
(99, 23, 9803, 'member', 0, 0, 0, 0, NULL, 1, NULL, NULL, NULL, '2025-08-30 12:55:41', NULL, 0, '2025-08-30 12:55:41', '2025-08-30 12:55:41'),
(100, 24, 9779, 'owner', 0, 0, 0, 0, NULL, 1, NULL, NULL, NULL, '2025-08-30 12:55:50', NULL, 0, '2025-08-30 12:55:50', '2025-08-30 12:55:50'),
(101, 24, 9810, 'member', 0, 0, 0, 0, NULL, 1, NULL, NULL, NULL, '2025-08-30 12:55:50', NULL, 0, '2025-08-30 12:55:50', '2025-08-30 12:55:50'),
(102, 25, 9797, 'owner', 0, 0, 0, 0, NULL, 1, NULL, NULL, NULL, '2025-08-31 18:58:37', NULL, 0, '2025-08-31 18:58:37', '2025-08-31 18:58:37'),
(104, 26, 9790, 'owner', 0, 0, 0, 0, NULL, 1, NULL, 1771, '2025-10-20 07:33:49', '2025-08-31 19:44:45', NULL, 18, '2025-08-31 19:44:45', '2025-12-23 12:59:03'),
(105, 26, 30, 'member', 0, 0, 0, 0, NULL, 1, NULL, 1759, '2025-10-20 07:06:23', '2025-08-31 19:44:45', NULL, 18, '2025-08-31 19:44:45', '2025-12-23 12:59:03'),
(106, 26, 44, 'member', 0, 0, 0, 0, NULL, 1, NULL, 1762, '2025-11-22 14:21:56', '2025-08-31 19:44:45', NULL, 18, '2025-08-31 19:44:45', '2025-12-23 12:59:03'),
(107, 26, 4911, 'member', 0, 0, 0, 0, NULL, 1, NULL, 1775, '2025-11-21 15:11:45', '2025-08-31 19:44:45', NULL, 18, '2025-08-31 19:44:45', '2025-12-23 12:59:03'),
(108, 26, 9799, 'member', 0, 0, 0, 0, NULL, 1, NULL, 1775, '2025-10-20 18:04:23', '2025-08-31 19:44:45', NULL, 18, '2025-08-31 19:44:45', '2025-12-23 12:59:03'),
(109, 26, 9800, 'member', 0, 0, 0, 0, NULL, 1, NULL, 1760, '2025-10-20 07:06:29', '2025-08-31 19:44:45', NULL, 18, '2025-08-31 19:44:45', '2025-12-23 12:59:03'),
(110, 26, 9802, 'member', 0, 0, 0, 0, NULL, 1, NULL, 1763, '2025-10-20 07:16:13', '2025-08-31 19:44:45', NULL, 18, '2025-08-31 19:44:45', '2025-12-23 12:59:04'),
(111, 26, 9803, 'member', 0, 0, 0, 0, NULL, 1, NULL, 1761, '2025-10-20 07:06:29', '2025-08-31 19:44:45', NULL, 18, '2025-08-31 19:44:45', '2025-12-23 12:59:04'),
(112, 26, 9806, 'member', 0, 0, 0, 0, NULL, 1, NULL, 997, '2025-09-22 07:06:03', '2025-08-31 19:44:45', NULL, 18, '2025-08-31 19:44:45', '2025-12-23 12:59:04'),
(113, 26, 9810, 'member', 0, 0, 0, 0, NULL, 1, NULL, 1766, '2025-10-20 07:19:27', '2025-08-31 19:56:02', NULL, 18, '2025-08-31 19:56:02', '2025-12-23 12:59:04'),
(114, 27, 44, 'owner', 0, 0, 0, 0, NULL, 1, NULL, 1840, '2025-11-22 14:29:45', '2025-09-03 08:09:09', NULL, 0, '2025-09-03 08:09:09', '2025-11-22 14:29:45'),
(115, 27, 9810, 'member', 0, 0, 0, 0, NULL, 1, NULL, 1779, '2025-10-20 09:55:58', '2025-09-03 08:09:09', NULL, 1, '2025-09-03 08:09:09', '2025-11-21 11:21:00'),
(117, 28, 9828, 'member', 0, 0, 0, 0, NULL, 1, NULL, NULL, NULL, '2025-09-03 17:00:36', NULL, 0, '2025-09-03 17:00:36', '2025-09-03 17:00:36'),
(118, 29, 9635, 'owner', 0, 0, 0, 0, NULL, 1, NULL, NULL, NULL, '2025-09-04 04:58:51', NULL, 0, '2025-09-04 04:58:51', '2025-09-04 04:58:51'),
(119, 29, 9802, 'member', 0, 0, 0, 0, NULL, 1, NULL, NULL, NULL, '2025-09-04 04:58:51', NULL, 0, '2025-09-04 04:58:51', '2025-09-04 04:58:51'),
(120, 30, 9808, 'member', 0, 0, 0, 0, NULL, 1, NULL, NULL, NULL, NULL, NULL, 0, '2025-09-04 05:01:41', '2025-09-04 05:01:41'),
(121, 30, 30, 'member', 0, 0, 0, 0, NULL, 1, NULL, NULL, NULL, NULL, NULL, 0, '2025-09-04 05:01:41', '2025-09-04 05:01:41'),
(122, 31, 9820, 'member', 0, 0, 0, 0, NULL, 1, NULL, 1686, '2025-10-16 06:27:57', NULL, NULL, 0, '2025-09-04 05:04:38', '2025-10-16 06:27:57'),
(123, 31, 30, 'member', 0, 0, 0, 0, NULL, 1, NULL, 1690, '2025-10-18 03:43:36', NULL, NULL, 0, '2025-09-04 05:04:38', '2025-10-18 03:43:36'),
(124, 32, 44, 'member', 0, 0, 0, 0, NULL, 1, NULL, NULL, '2025-11-22 14:29:27', NULL, NULL, 0, '2025-09-04 05:45:01', '2025-11-22 14:29:27'),
(125, 32, 30, 'member', 0, 0, 0, 0, NULL, 1, NULL, NULL, NULL, NULL, NULL, 0, '2025-09-04 05:45:01', '2025-09-04 05:45:01'),
(126, 33, 44, 'member', 0, 0, 0, 0, NULL, 1, NULL, NULL, NULL, NULL, NULL, 0, '2025-09-04 05:45:09', '2025-09-04 05:45:09'),
(127, 33, 9799, 'member', 0, 0, 0, 0, NULL, 1, NULL, NULL, NULL, NULL, NULL, 0, '2025-09-04 05:45:09', '2025-09-04 05:45:09'),
(128, 34, 8593, 'member', 0, 0, 0, 0, NULL, 1, NULL, NULL, NULL, NULL, NULL, 0, '2025-09-04 15:49:45', '2025-09-04 15:49:45'),
(129, 34, 30, 'member', 0, 0, 0, 0, NULL, 1, NULL, NULL, NULL, NULL, NULL, 0, '2025-09-04 15:49:45', '2025-09-04 15:49:45'),
(130, 35, 9776, 'member', 0, 0, 0, 0, NULL, 1, NULL, NULL, NULL, NULL, NULL, 0, '2025-09-04 18:35:49', '2025-09-04 18:35:49'),
(131, 35, 30, 'member', 0, 0, 0, 0, NULL, 1, NULL, NULL, NULL, NULL, NULL, 0, '2025-09-04 18:35:49', '2025-09-04 18:35:49'),
(132, 36, 9776, 'member', 0, 0, 0, 0, NULL, 1, NULL, NULL, NULL, NULL, NULL, 0, '2025-09-04 18:35:54', '2025-09-04 18:35:54'),
(133, 36, 9801, 'member', 0, 0, 0, 0, NULL, 1, NULL, NULL, NULL, NULL, NULL, 0, '2025-09-04 18:35:54', '2025-09-04 18:35:54'),
(134, 37, 9805, 'member', 0, 0, 0, 0, NULL, 1, NULL, NULL, NULL, NULL, NULL, 0, '2025-09-04 18:37:49', '2025-09-04 18:37:49'),
(135, 37, 30, 'member', 0, 0, 0, 0, NULL, 1, NULL, NULL, NULL, NULL, NULL, 0, '2025-09-04 18:37:49', '2025-09-04 18:37:49'),
(136, 38, 9797, 'member', 0, 0, 0, 0, NULL, 1, NULL, NULL, NULL, NULL, NULL, 0, '2025-09-04 18:43:18', '2025-09-04 18:43:18'),
(137, 38, 9813, 'member', 0, 0, 0, 0, NULL, 1, NULL, NULL, NULL, NULL, NULL, 0, '2025-09-04 18:43:18', '2025-09-04 18:43:18'),
(138, 39, 4911, 'member', 0, 0, 0, 0, NULL, 1, NULL, 1743, '2025-11-21 15:29:43', NULL, NULL, 0, '2025-09-05 10:39:52', '2025-11-21 15:29:43'),
(139, 39, 9810, 'member', 0, 0, 0, 0, NULL, 1, NULL, 1743, '2025-10-20 06:00:46', NULL, NULL, 0, '2025-09-05 10:39:52', '2025-10-20 06:00:46'),
(140, 40, 4911, 'owner', 0, 0, 0, 0, NULL, 1, NULL, 1290, '2025-11-21 16:22:24', '2025-09-06 06:09:27', NULL, 0, '2025-09-06 06:09:27', '2025-11-21 16:22:24'),
(141, 40, 30, 'member', 0, 0, 0, 0, NULL, 1, NULL, 1290, '2025-10-15 17:06:05', '2025-09-06 06:09:27', NULL, 0, '2025-09-06 06:09:27', '2025-10-15 17:06:05'),
(142, 41, 4911, 'owner', 0, 0, 0, 0, NULL, 1, NULL, 1298, '2025-10-01 05:36:12', '2025-09-06 06:13:18', NULL, 0, '2025-09-06 06:13:18', '2025-10-01 05:36:12'),
(143, 41, 30, 'member', 0, 0, 0, 0, NULL, 1, NULL, 1298, '2025-10-09 06:03:45', '2025-09-06 06:13:18', NULL, 0, '2025-09-06 06:13:18', '2025-10-09 06:03:45'),
(145, 42, 5542, 'member', 0, 0, 0, 0, NULL, 1, NULL, NULL, NULL, NULL, NULL, 0, '2025-09-08 18:50:28', '2025-09-08 18:50:28'),
(146, 42, 30, 'member', 0, 0, 0, 0, NULL, 1, NULL, NULL, NULL, NULL, NULL, 0, '2025-09-08 18:50:28', '2025-09-08 18:50:28'),
(147, 43, 9816, 'member', 0, 0, 0, 0, NULL, 1, NULL, NULL, NULL, NULL, NULL, 0, '2025-09-10 05:58:47', '2025-09-10 05:58:47'),
(148, 43, 12, 'member', 0, 0, 0, 0, NULL, 1, NULL, NULL, NULL, NULL, NULL, 0, '2025-09-10 05:58:47', '2025-09-10 05:58:47'),
(149, 44, 9635, 'member', 0, 0, 0, 0, NULL, 1, NULL, 1737, '2025-10-20 07:46:05', NULL, NULL, 0, '2025-09-10 08:08:43', '2025-10-20 07:46:05'),
(150, 44, 30, 'member', 0, 0, 0, 0, NULL, 1, NULL, 1737, '2025-10-20 05:58:17', NULL, NULL, 0, '2025-09-10 08:08:43', '2025-10-20 05:58:17'),
(151, 45, 9806, 'member', 0, 0, 0, 0, NULL, 1, NULL, NULL, NULL, NULL, NULL, 0, '2025-09-10 14:00:54', '2025-09-10 14:00:54'),
(152, 45, 30, 'member', 0, 0, 0, 0, NULL, 1, NULL, NULL, NULL, NULL, NULL, 0, '2025-09-10 14:00:54', '2025-09-10 14:00:54'),
(153, 46, 9808, 'member', 0, 0, 0, 0, NULL, 1, NULL, NULL, NULL, NULL, NULL, 0, '2025-09-10 18:29:07', '2025-09-10 18:29:07'),
(154, 46, 9801, 'member', 0, 0, 0, 0, NULL, 1, NULL, NULL, NULL, NULL, NULL, 0, '2025-09-10 18:29:07', '2025-09-10 18:29:07'),
(155, 47, 8593, 'member', 0, 0, 0, 0, NULL, 1, NULL, NULL, NULL, NULL, NULL, 0, '2025-09-10 19:02:32', '2025-09-10 19:02:32'),
(156, 47, 9803, 'member', 0, 0, 0, 0, NULL, 1, NULL, NULL, NULL, NULL, NULL, 0, '2025-09-10 19:02:32', '2025-09-10 19:02:32'),
(157, 6, 9815, 'member', 0, 0, 0, 0, NULL, 1, NULL, 1692, '2025-10-16 06:32:32', '2025-09-11 16:28:05', NULL, 0, '2025-09-11 16:28:05', '2025-10-16 06:32:32'),
(158, 6, 9801, 'member', 0, 0, 0, 0, NULL, 1, NULL, 719, '2025-09-11 16:44:29', '2025-09-11 16:29:56', NULL, 0, '2025-09-11 16:29:56', '2025-09-11 16:44:29'),
(159, 6, 9814, 'member', 0, 0, 0, 0, NULL, 1, NULL, 1575, '2025-10-13 11:05:23', '2025-09-11 16:30:27', NULL, 0, '2025-09-11 16:30:27', '2025-10-13 11:05:23'),
(160, 6, 30, 'member', 0, 0, 0, 0, NULL, 1, NULL, 1529, '2025-10-09 06:08:20', '2025-09-11 16:31:43', NULL, 0, '2025-09-11 16:31:43', '2025-10-09 06:08:20'),
(161, 48, 9811, 'member', 0, 0, 0, 0, NULL, 1, NULL, NULL, NULL, NULL, NULL, 0, '2025-09-11 18:34:38', '2025-09-11 18:34:38'),
(162, 48, 9812, 'member', 0, 0, 0, 0, NULL, 1, NULL, NULL, NULL, NULL, NULL, 0, '2025-09-11 18:34:38', '2025-09-11 18:34:38'),
(163, 49, 9816, 'member', 0, 0, 0, 0, NULL, 1, NULL, NULL, NULL, NULL, NULL, 0, '2025-09-11 19:38:29', '2025-09-11 19:38:29'),
(164, 49, 9830, 'member', 0, 0, 0, 0, NULL, 1, NULL, NULL, NULL, NULL, NULL, 0, '2025-09-11 19:38:29', '2025-09-11 19:38:29'),
(165, 11, 9802, 'member', 0, 0, 0, 0, NULL, 1, NULL, NULL, NULL, '2025-09-12 05:57:22', NULL, 34, '2025-09-12 05:57:22', '2025-11-21 20:13:06'),
(166, 11, 9825, 'member', 0, 0, 0, 0, NULL, 1, NULL, 1376, '2025-10-05 09:53:48', '2025-09-15 05:51:30', NULL, 34, '2025-09-15 05:51:30', '2025-11-21 20:13:06'),
(168, 50, 12, 'member', 0, 0, 0, 0, NULL, 1, NULL, NULL, NULL, '2025-09-15 16:05:02', NULL, 0, '2025-09-15 16:05:02', '2025-09-15 16:05:02'),
(169, 50, 30, 'member', 0, 0, 0, 0, NULL, 1, NULL, 870, '2025-09-16 15:59:44', '2025-09-15 16:05:02', NULL, 0, '2025-09-15 16:05:02', '2025-09-16 15:59:44'),
(170, 50, 4911, 'member', 0, 0, 0, 0, NULL, 1, NULL, 880, '2025-10-06 18:18:40', '2025-09-15 16:05:02', NULL, 0, '2025-09-15 16:05:02', '2025-10-06 18:18:40'),
(171, 50, 9799, 'member', 0, 0, 0, 0, NULL, 1, NULL, 880, '2025-10-20 18:04:30', '2025-09-15 16:05:02', NULL, 0, '2025-09-15 16:05:02', '2025-10-20 18:04:30'),
(172, 50, 9800, 'member', 0, 0, 0, 0, NULL, 1, NULL, NULL, NULL, '2025-09-15 16:05:02', NULL, 0, '2025-09-15 16:05:02', '2025-09-15 16:05:02'),
(173, 50, 9801, 'member', 0, 0, 0, 0, NULL, 1, NULL, 868, '2025-09-16 15:59:31', '2025-09-15 16:05:02', NULL, 0, '2025-09-15 16:05:02', '2025-09-16 15:59:31'),
(174, 50, 9802, 'member', 0, 0, 0, 0, NULL, 1, NULL, NULL, NULL, '2025-09-15 16:05:02', NULL, 0, '2025-09-15 16:05:02', '2025-09-15 16:05:02'),
(175, 50, 9803, 'member', 0, 0, 0, 0, NULL, 1, NULL, 793, '2025-09-15 18:40:20', '2025-09-15 16:05:02', NULL, 0, '2025-09-15 16:05:02', '2025-09-15 18:40:20'),
(176, 50, 9810, 'member', 0, 0, 0, 0, NULL, 1, NULL, 880, '2025-10-03 11:02:33', '2025-09-15 16:05:02', NULL, 0, '2025-09-15 16:05:02', '2025-10-03 11:02:33'),
(177, 50, 9814, 'member', 0, 0, 0, 0, NULL, 1, NULL, 880, '2025-10-01 18:26:49', '2025-09-15 16:05:02', NULL, 0, '2025-09-15 16:05:02', '2025-10-01 18:26:49'),
(178, 50, 9815, 'member', 0, 0, 0, 0, NULL, 1, NULL, 880, '2025-09-26 07:07:23', '2025-09-15 16:05:02', NULL, 0, '2025-09-15 16:05:02', '2025-09-26 07:07:23'),
(179, 50, 9819, 'member', 0, 0, 0, 0, NULL, 1, NULL, NULL, NULL, '2025-09-15 16:05:02', NULL, 0, '2025-09-15 16:05:02', '2025-09-15 16:05:02'),
(181, 51, 9819, 'member', 0, 0, 0, 0, NULL, 1, NULL, NULL, NULL, '2025-09-16 16:08:40', NULL, 0, '2025-09-16 16:08:40', '2025-09-16 16:08:40'),
(182, 52, 9811, 'member', 0, 0, 0, 0, NULL, 1, NULL, NULL, NULL, NULL, NULL, 0, '2025-09-17 15:13:37', '2025-09-17 15:13:37'),
(183, 52, 9810, 'member', 0, 0, 0, 0, NULL, 1, NULL, NULL, NULL, NULL, NULL, 0, '2025-09-17 15:13:37', '2025-09-17 15:13:37'),
(184, 53, 30, 'owner', 0, 0, 0, 0, NULL, 1, NULL, NULL, NULL, '2025-09-18 07:21:01', NULL, 0, '2025-09-18 07:21:01', '2025-09-18 07:21:01'),
(185, 53, 9821, 'member', 0, 0, 0, 0, NULL, 1, NULL, NULL, NULL, '2025-09-18 07:21:01', NULL, 0, '2025-09-18 07:21:01', '2025-09-18 07:21:01'),
(186, 54, 30, 'owner', 0, 0, 0, 0, NULL, 1, NULL, 927, '2025-10-02 05:00:31', '2025-09-18 07:21:18', NULL, 0, '2025-09-18 07:21:18', '2025-10-02 05:00:31'),
(187, 54, 9810, 'member', 0, 0, 0, 0, NULL, 1, NULL, 927, '2025-10-17 07:04:57', '2025-09-18 07:21:18', NULL, 0, '2025-09-18 07:21:18', '2025-10-17 07:04:57'),
(188, 55, 9810, 'owner', 0, 0, 0, 0, NULL, 1, NULL, 1780, '2025-10-20 09:56:04', '2025-09-18 08:02:54', NULL, 0, '2025-09-18 08:02:54', '2025-10-20 09:56:04'),
(189, 55, 9821, 'member', 0, 0, 0, 0, NULL, 1, NULL, NULL, NULL, '2025-09-18 08:02:54', NULL, 0, '2025-09-18 08:02:54', '2025-09-18 08:02:54'),
(190, 56, 9810, 'owner', 0, 0, 0, 0, NULL, 1, NULL, 1744, '2025-10-20 06:00:55', '2025-09-18 14:28:21', NULL, 0, '2025-09-18 14:28:21', '2025-10-20 06:00:55'),
(191, 56, 9806, 'member', 0, 0, 0, 0, NULL, 1, NULL, NULL, NULL, '2025-09-18 14:28:21', NULL, 0, '2025-09-18 14:28:21', '2025-09-18 14:28:21'),
(192, 57, 9635, 'member', 0, 0, 0, 0, NULL, 1, NULL, NULL, NULL, NULL, NULL, 0, '2025-09-18 18:26:35', '2025-09-18 18:26:35'),
(193, 57, 9803, 'member', 0, 0, 0, 0, NULL, 1, NULL, NULL, NULL, NULL, NULL, 0, '2025-09-18 18:26:35', '2025-09-18 18:26:35'),
(195, 58, 4911, 'member', 0, 0, 0, 0, NULL, 1, NULL, 1594, '2025-10-13 06:56:10', '2025-09-22 05:56:49', NULL, 0, '2025-09-22 05:56:49', '2025-10-13 06:56:10'),
(196, 58, 9806, 'member', 0, 0, 0, 0, NULL, 1, NULL, NULL, NULL, '2025-09-22 05:56:49', NULL, 0, '2025-09-22 05:56:49', '2025-09-22 05:56:49'),
(197, 58, 9830, 'member', 0, 0, 0, 0, NULL, 1, NULL, NULL, NULL, '2025-09-22 05:56:49', NULL, 0, '2025-09-22 05:56:49', '2025-09-22 05:56:49'),
(199, 59, 9805, 'member', 0, 0, 0, 0, NULL, 1, NULL, NULL, NULL, '2025-09-25 16:58:49', NULL, 0, '2025-09-25 16:58:49', '2025-09-25 16:58:49'),
(201, 60, 9804, 'member', 0, 0, 0, 0, NULL, 1, NULL, NULL, NULL, '2025-09-25 17:03:44', NULL, 0, '2025-09-25 17:03:44', '2025-09-25 17:03:44'),
(202, 61, 9798, 'owner', 0, 0, 0, 0, NULL, 1, NULL, NULL, NULL, '2025-09-26 07:55:53', NULL, 0, '2025-09-26 07:55:53', '2025-09-26 07:55:53'),
(203, 61, 9815, 'member', 0, 0, 0, 0, NULL, 1, NULL, NULL, NULL, '2025-09-26 07:55:53', NULL, 0, '2025-09-26 07:55:53', '2025-09-26 07:55:53'),
(204, 62, 9798, 'owner', 0, 0, 0, 0, NULL, 1, NULL, 1193, '2025-10-02 21:15:38', '2025-09-26 07:56:36', NULL, 0, '2025-09-26 07:56:36', '2025-10-02 21:15:38'),
(205, 62, 30, 'member', 0, 0, 0, 0, NULL, 1, NULL, 1193, '2025-10-20 08:57:14', '2025-09-26 07:56:36', NULL, 0, '2025-09-26 07:56:36', '2025-10-20 08:57:14'),
(207, 62, 9810, 'member', 0, 0, 0, 0, NULL, 1, NULL, 1193, '2025-09-26 09:04:06', '2025-09-26 07:56:36', NULL, 0, '2025-09-26 07:56:36', '2025-09-26 09:04:06'),
(208, 62, 9815, 'member', 0, 0, 0, 0, NULL, 1, NULL, 1193, '2025-09-26 12:36:52', '2025-09-26 07:56:36', NULL, 0, '2025-09-26 07:56:36', '2025-09-26 12:36:52'),
(209, 63, 4911, 'owner', 0, 0, 0, 0, NULL, 1, NULL, 1295, '2025-10-12 14:51:29', '2025-09-30 21:03:55', NULL, 0, '2025-09-30 21:03:55', '2025-10-12 14:51:29'),
(210, 63, 9801, 'member', 0, 0, 0, 0, NULL, 1, NULL, 1294, '2025-09-30 21:07:51', '2025-09-30 21:03:55', NULL, 0, '2025-09-30 21:03:55', '2025-09-30 21:07:51'),
(211, 7, 9814, 'member', 0, 0, 0, 0, NULL, 1, NULL, 1605, '2025-10-13 11:04:59', '2025-10-01 18:28:16', NULL, 4, '2025-10-01 18:28:16', '2025-12-23 10:19:46'),
(212, 10, 9814, 'member', 0, 0, 0, 0, NULL, 1, NULL, 1550, '2025-10-09 16:50:15', '2025-10-02 16:03:56', NULL, 0, '2025-10-02 16:03:56', '2025-10-09 16:50:15'),
(213, 64, 9635, 'member', 0, 0, 0, 0, NULL, 1, NULL, NULL, NULL, NULL, NULL, 0, '2025-10-02 18:47:36', '2025-10-02 18:47:36'),
(214, 64, 9800, 'member', 0, 0, 0, 0, NULL, 1, NULL, NULL, NULL, NULL, NULL, 0, '2025-10-02 18:47:36', '2025-10-02 18:47:36'),
(215, 65, 9820, 'member', 0, 0, 0, 0, NULL, 1, NULL, NULL, NULL, NULL, NULL, 0, '2025-10-02 18:58:19', '2025-10-02 18:58:19'),
(216, 65, 9803, 'member', 0, 0, 0, 0, NULL, 1, NULL, NULL, NULL, NULL, NULL, 0, '2025-10-02 18:58:19', '2025-10-02 18:58:19'),
(217, 66, 9829, 'member', 0, 0, 0, 0, NULL, 1, NULL, NULL, NULL, NULL, NULL, 0, '2025-10-02 19:15:56', '2025-10-02 19:15:56'),
(218, 66, 9803, 'member', 0, 0, 0, 0, NULL, 1, NULL, NULL, NULL, NULL, NULL, 0, '2025-10-02 19:15:56', '2025-10-02 19:15:56'),
(219, 67, 9837, 'member', 0, 0, 0, 0, NULL, 1, NULL, NULL, NULL, NULL, NULL, 0, '2025-10-06 06:17:41', '2025-10-06 06:17:41'),
(220, 67, 9829, 'member', 0, 0, 0, 0, NULL, 1, NULL, NULL, NULL, NULL, NULL, 0, '2025-10-06 06:17:41', '2025-10-06 06:17:41'),
(221, 68, 9829, 'member', 0, 0, 0, 0, NULL, 1, NULL, 1732, '2025-10-18 19:52:05', NULL, NULL, 0, '2025-10-06 10:55:22', '2025-10-18 19:52:05'),
(222, 68, 9810, 'member', 0, 0, 0, 0, NULL, 1, NULL, 1778, '2025-10-20 09:55:49', NULL, NULL, 0, '2025-10-06 10:55:22', '2025-10-20 09:55:49'),
(223, 69, 9810, 'owner', 0, 0, 0, 0, NULL, 1, NULL, 1777, '2025-10-20 09:35:31', '2025-10-13 14:02:29', NULL, 0, '2025-10-13 14:02:29', '2025-10-20 09:35:31'),
(224, 69, 9635, 'member', 0, 0, 0, 0, NULL, 1, NULL, 1710, '2025-10-16 17:51:57', '2025-10-13 14:02:29', NULL, 0, '2025-10-13 14:02:29', '2025-10-16 17:51:57'),
(226, 70, 9820, 'member', 0, 0, 0, 0, NULL, 1, NULL, NULL, NULL, '2025-10-14 16:40:52', NULL, 0, '2025-10-14 16:40:52', '2025-10-14 16:40:52'),
(227, 71, 5965, 'member', 0, 0, 0, 0, NULL, 1, NULL, 1800, '2025-11-21 03:43:26', NULL, NULL, 0, '2025-10-14 18:23:06', '2025-11-21 03:43:26'),
(228, 71, 30, 'member', 0, 0, 0, 0, NULL, 1, NULL, 1800, '2025-11-21 03:39:21', NULL, NULL, 0, '2025-10-14 18:23:06', '2025-11-21 03:39:21'),
(229, 72, 9820, 'member', 0, 0, 0, 0, NULL, 1, NULL, 1733, '2025-10-18 03:41:59', NULL, NULL, 0, '2025-10-16 01:51:35', '2025-10-18 03:41:59'),
(230, 72, 9810, 'member', 0, 0, 0, 0, NULL, 1, NULL, 1781, '2025-10-20 12:20:13', NULL, NULL, 0, '2025-10-16 01:51:35', '2025-10-20 12:20:13'),
(231, 73, 9787, 'owner', 0, 0, 0, 0, NULL, 1, NULL, 1751, '2025-10-20 16:55:25', '2025-10-20 06:19:06', NULL, 0, '2025-10-20 06:19:06', '2025-10-20 16:55:25'),
(232, 73, 9779, 'member', 0, 0, 0, 0, NULL, 1, NULL, NULL, NULL, '2025-10-20 06:19:06', NULL, 0, '2025-10-20 06:19:06', '2025-10-20 06:19:06'),
(233, 74, 44, 'owner', 0, 0, 0, 0, NULL, 1, NULL, NULL, '2025-11-22 14:29:25', '2025-11-11 22:46:57', NULL, 0, '2025-11-11 22:46:57', '2025-11-22 14:29:25'),
(234, 74, 9836, 'member', 0, 0, 0, 0, NULL, 1, NULL, NULL, NULL, '2025-11-11 22:46:57', NULL, 0, '2025-11-11 22:46:57', '2025-11-11 22:46:57'),
(235, 75, 9798, 'owner', 0, 0, 0, 0, NULL, 1, NULL, NULL, '2025-11-21 07:48:13', '2025-11-13 09:11:41', NULL, 0, '2025-11-13 09:11:41', '2025-11-21 07:48:13'),
(236, 75, 30, 'member', 0, 0, 0, 0, NULL, 1, NULL, NULL, NULL, '2025-11-13 09:11:41', NULL, 0, '2025-11-13 09:11:41', '2025-11-13 09:11:41'),
(237, 75, 44, 'member', 0, 0, 0, 0, NULL, 1, NULL, NULL, '2025-11-21 11:20:51', '2025-11-13 09:11:41', NULL, 0, '2025-11-13 09:11:41', '2025-11-21 11:20:51'),
(238, 76, 4911, 'owner', 0, 0, 0, 0, NULL, 1, NULL, NULL, '2025-11-21 19:36:40', '2025-11-21 15:32:17', NULL, 0, '2025-11-21 15:32:17', '2025-11-21 19:36:40'),
(239, 76, 9836, 'member', 0, 0, 0, 0, NULL, 1, NULL, NULL, NULL, '2025-11-21 15:32:17', NULL, 0, '2025-11-21 15:32:17', '2025-11-21 15:32:17'),
(240, 77, 4911, 'owner', 0, 0, 0, 0, NULL, 1, NULL, NULL, '2025-11-21 16:47:39', '2025-11-21 15:32:17', NULL, 0, '2025-11-21 15:32:17', '2025-11-21 16:47:39'),
(241, 77, 9802, 'member', 0, 0, 0, 0, NULL, 1, NULL, NULL, NULL, '2025-11-21 15:32:17', NULL, 0, '2025-11-21 15:32:17', '2025-11-21 15:32:17'),
(242, 78, 4911, 'owner', 0, 0, 0, 0, NULL, 1, NULL, NULL, '2025-11-21 17:58:49', '2025-11-21 15:32:18', NULL, 0, '2025-11-21 15:32:18', '2025-11-21 17:58:49'),
(243, 78, 9834, 'member', 0, 0, 0, 0, NULL, 1, NULL, NULL, NULL, '2025-11-21 15:32:18', NULL, 0, '2025-11-21 15:32:18', '2025-11-21 15:32:18'),
(244, 79, 4911, 'owner', 0, 0, 0, 0, NULL, 1, NULL, 1865, '2025-11-21 18:00:15', '2025-11-21 15:32:37', NULL, 0, '2025-11-21 15:32:37', '2025-11-21 18:00:15'),
(245, 79, 1, 'member', 0, 0, 0, 0, NULL, 1, NULL, NULL, NULL, '2025-11-21 15:32:37', NULL, 1, '2025-11-21 15:32:37', '2025-11-21 16:33:51'),
(246, 79, 12, 'member', 0, 0, 0, 0, NULL, 1, NULL, NULL, NULL, '2025-11-21 15:32:37', NULL, 1, '2025-11-21 15:32:37', '2025-11-21 16:33:52'),
(247, 79, 30, 'member', 0, 0, 0, 0, NULL, 1, NULL, NULL, NULL, '2025-11-21 15:32:37', NULL, 1, '2025-11-21 15:32:37', '2025-11-21 16:33:52'),
(248, 79, 44, 'member', 0, 0, 0, 0, NULL, 1, NULL, NULL, NULL, '2025-11-21 15:32:37', NULL, 1, '2025-11-21 15:32:37', '2025-11-21 16:33:52'),
(249, 79, 5542, 'member', 0, 0, 0, 0, NULL, 1, NULL, NULL, NULL, '2025-11-21 15:32:37', NULL, 1, '2025-11-21 15:32:37', '2025-11-21 16:33:52'),
(250, 80, 4911, 'member', 0, 0, 0, 0, NULL, 1, NULL, 1882, '2025-11-21 18:19:45', '2025-11-21 16:27:18', NULL, 1, '2025-11-21 16:27:18', '2025-11-21 18:19:53'),
(251, 80, 9798, 'member', 0, 0, 0, 0, NULL, 1, NULL, 1883, '2025-11-21 18:55:17', '2025-11-21 16:27:18', NULL, 0, '2025-11-21 16:27:18', '2025-11-21 18:55:17'),
(252, 81, 4911, 'owner', 0, 0, 0, 0, NULL, 1, NULL, 1885, '2025-11-21 18:21:01', '2025-11-21 16:28:06', NULL, 0, '2025-11-21 16:28:06', '2025-11-21 18:21:01'),
(253, 81, 9790, 'member', 0, 0, 0, 0, NULL, 1, NULL, NULL, NULL, '2025-11-21 16:28:06', NULL, 4, '2025-11-21 16:28:06', '2025-11-21 18:20:35'),
(254, 81, 9798, 'member', 0, 0, 0, 0, NULL, 1, NULL, 1886, '2025-11-21 18:20:34', '2025-11-21 16:28:06', NULL, 0, '2025-11-21 16:28:06', '2025-11-21 18:20:34'),
(255, 82, 4911, 'member', 0, 0, 0, 0, NULL, 1, NULL, 1867, '2025-11-21 19:04:05', '2025-11-21 16:47:17', NULL, 0, '2025-11-21 16:47:17', '2025-11-21 19:04:05'),
(256, 82, 1, 'member', 0, 0, 0, 0, NULL, 1, NULL, NULL, NULL, '2025-11-21 16:47:17', NULL, 1, '2025-11-21 16:47:17', '2025-11-21 16:47:31'),
(257, 83, 4911, 'owner', 0, 0, 0, 0, NULL, 1, NULL, 1868, '2025-11-21 18:21:03', '2025-11-21 16:47:47', NULL, 0, '2025-11-21 16:47:47', '2025-11-21 18:21:03'),
(258, 83, 30, 'member', 0, 0, 0, 0, NULL, 1, NULL, NULL, NULL, '2025-11-21 16:47:47', NULL, 1, '2025-11-21 16:47:47', '2025-11-21 16:48:16'),
(260, 84, 4911, 'owner', 0, 0, 0, 0, NULL, 1, NULL, 1884, '2025-11-21 18:20:11', '2025-11-21 18:20:05', NULL, 0, '2025-11-21 18:20:05', '2025-11-21 18:20:11'),
(261, 84, 9776, 'member', 0, 0, 0, 0, NULL, 1, NULL, NULL, NULL, '2025-11-21 18:20:05', NULL, 1, '2025-11-21 18:20:05', '2025-11-21 18:20:11'),
(262, 83, 9798, 'member', 0, 0, 0, 0, NULL, 1, NULL, NULL, '2025-11-21 18:22:56', '2025-11-21 18:21:32', NULL, 0, '2025-11-21 18:21:32', '2025-11-21 18:22:56'),
(263, 85, 9798, 'owner', 0, 0, 0, 0, NULL, 1, NULL, 1888, '2025-11-21 18:22:58', '2025-11-21 18:21:48', NULL, 0, '2025-11-21 18:21:48', '2025-11-21 18:22:58'),
(264, 86, 4911, 'owner', 0, 0, 0, 0, NULL, 1, NULL, NULL, '2025-11-21 18:23:29', '2025-11-21 18:23:28', NULL, 0, '2025-11-21 18:23:28', '2025-11-21 18:23:29'),
(266, 87, 30, 'member', 0, 0, 0, 0, NULL, 1, NULL, NULL, NULL, '2025-11-21 18:40:39', NULL, 0, '2025-11-21 18:40:39', '2025-11-21 18:40:39'),
(267, 87, 44, 'member', 0, 0, 0, 0, NULL, 1, NULL, NULL, NULL, '2025-11-21 18:40:39', NULL, 0, '2025-11-21 18:40:39', '2025-11-21 18:40:39'),
(268, 87, 9798, 'member', 0, 0, 0, 0, NULL, 1, NULL, NULL, '2025-11-21 18:41:32', '2025-11-21 18:40:39', NULL, 0, '2025-11-21 18:40:39', '2025-11-21 18:41:32'),
(269, 87, 4911, 'owner', 0, 0, 0, 0, NULL, 1, NULL, NULL, '2025-11-21 18:47:40', '2025-11-21 18:40:39', NULL, 0, '2025-11-21 18:40:39', '2025-11-21 18:47:40'),
(270, 88, 12, 'member', 0, 0, 0, 0, NULL, 1, NULL, NULL, NULL, '2025-11-21 18:47:50', NULL, 0, '2025-11-21 18:47:50', '2025-11-21 18:47:50'),
(271, 88, 30, 'member', 0, 0, 0, 0, NULL, 1, NULL, NULL, NULL, '2025-11-21 18:47:50', NULL, 0, '2025-11-21 18:47:50', '2025-11-21 18:47:50'),
(272, 88, 4911, 'owner', 0, 0, 0, 0, NULL, 1, NULL, NULL, '2025-11-21 18:53:15', '2025-11-21 18:47:50', NULL, 0, '2025-11-21 18:47:50', '2025-11-21 18:53:15'),
(273, 89, 1, 'member', 0, 0, 0, 0, NULL, 1, NULL, NULL, NULL, '2025-11-21 18:53:04', NULL, 0, '2025-11-21 18:53:04', '2025-11-21 18:53:04'),
(274, 89, 12, 'member', 0, 0, 0, 0, NULL, 1, NULL, NULL, NULL, '2025-11-21 18:53:04', NULL, 0, '2025-11-21 18:53:04', '2025-11-21 18:53:04'),
(275, 89, 30, 'member', 0, 0, 0, 0, NULL, 1, NULL, NULL, NULL, '2025-11-21 18:53:04', NULL, 0, '2025-11-21 18:53:04', '2025-11-21 18:53:04'),
(276, 89, 44, 'member', 0, 0, 0, 0, NULL, 1, NULL, NULL, NULL, '2025-11-21 18:53:04', NULL, 0, '2025-11-21 18:53:04', '2025-11-21 18:53:04'),
(277, 89, 5542, 'member', 0, 0, 0, 0, NULL, 1, NULL, NULL, NULL, '2025-11-21 18:53:04', NULL, 0, '2025-11-21 18:53:04', '2025-11-21 18:53:04'),
(278, 89, 5965, 'member', 0, 0, 0, 0, NULL, 1, NULL, NULL, NULL, '2025-11-21 18:53:04', NULL, 0, '2025-11-21 18:53:04', '2025-11-21 18:53:04'),
(279, 89, 8593, 'member', 0, 0, 0, 0, NULL, 1, NULL, NULL, NULL, '2025-11-21 18:53:04', NULL, 0, '2025-11-21 18:53:04', '2025-11-21 18:53:04'),
(280, 89, 4911, 'owner', 0, 0, 0, 0, NULL, 1, NULL, NULL, '2025-11-21 18:53:16', '2025-11-21 18:53:04', NULL, 0, '2025-11-21 18:53:04', '2025-11-21 18:53:16'),
(281, 90, 1, 'member', 0, 0, 0, 0, NULL, 1, NULL, NULL, NULL, '2025-11-21 18:54:10', NULL, 0, '2025-11-21 18:54:10', '2025-11-21 18:54:10'),
(282, 90, 12, 'member', 0, 0, 0, 0, NULL, 1, NULL, NULL, NULL, '2025-11-21 18:54:10', NULL, 0, '2025-11-21 18:54:10', '2025-11-21 18:54:10'),
(283, 90, 30, 'member', 0, 0, 0, 0, NULL, 1, NULL, NULL, NULL, '2025-11-21 18:54:10', NULL, 0, '2025-11-21 18:54:10', '2025-11-21 18:54:10'),
(284, 90, 44, 'member', 0, 0, 0, 0, NULL, 1, NULL, NULL, NULL, '2025-11-21 18:54:10', NULL, 0, '2025-11-21 18:54:10', '2025-11-21 18:54:10'),
(285, 90, 5542, 'member', 0, 0, 0, 0, NULL, 1, NULL, NULL, NULL, '2025-11-21 18:54:10', NULL, 0, '2025-11-21 18:54:10', '2025-11-21 18:54:10'),
(286, 90, 5965, 'member', 0, 0, 0, 0, NULL, 1, NULL, NULL, NULL, '2025-11-21 18:54:10', NULL, 0, '2025-11-21 18:54:10', '2025-11-21 18:54:10'),
(287, 90, 8593, 'member', 0, 0, 0, 0, NULL, 1, NULL, NULL, NULL, '2025-11-21 18:54:10', NULL, 0, '2025-11-21 18:54:10', '2025-11-21 18:54:10'),
(288, 90, 4911, 'owner', 0, 0, 0, 0, NULL, 1, NULL, NULL, '2025-11-21 18:54:13', '2025-11-21 18:54:10', NULL, 0, '2025-11-21 18:54:10', '2025-11-21 18:54:13'),
(289, 91, 1, 'member', 0, 0, 0, 0, NULL, 1, NULL, NULL, NULL, '2025-11-21 18:59:23', NULL, 0, '2025-11-21 18:59:23', '2025-11-21 18:59:23'),
(290, 91, 12, 'member', 0, 0, 0, 0, NULL, 1, NULL, NULL, NULL, '2025-11-21 18:59:23', NULL, 0, '2025-11-21 18:59:23', '2025-11-21 18:59:23'),
(291, 91, 30, 'member', 0, 0, 0, 0, NULL, 1, NULL, NULL, NULL, '2025-11-21 18:59:23', NULL, 0, '2025-11-21 18:59:23', '2025-11-21 18:59:23'),
(292, 91, 44, 'member', 0, 0, 0, 0, NULL, 1, NULL, NULL, NULL, '2025-11-21 18:59:23', NULL, 0, '2025-11-21 18:59:23', '2025-11-21 18:59:23'),
(293, 91, 5542, 'member', 0, 0, 0, 0, NULL, 1, NULL, NULL, NULL, '2025-11-21 18:59:23', NULL, 0, '2025-11-21 18:59:23', '2025-11-21 18:59:23'),
(294, 91, 5965, 'member', 0, 0, 0, 0, NULL, 1, NULL, NULL, NULL, '2025-11-21 18:59:23', NULL, 0, '2025-11-21 18:59:23', '2025-11-21 18:59:23'),
(295, 91, 4911, 'owner', 0, 0, 0, 0, NULL, 1, NULL, NULL, '2025-11-21 19:14:07', '2025-11-21 18:59:23', NULL, 0, '2025-11-21 18:59:23', '2025-11-21 19:14:07'),
(297, 92, 12, 'member', 0, 0, 0, 0, NULL, 1, NULL, NULL, NULL, '2025-11-21 19:03:39', NULL, 0, '2025-11-21 19:03:39', '2025-11-21 19:03:39'),
(298, 92, 30, 'member', 0, 0, 0, 0, NULL, 1, NULL, NULL, NULL, '2025-11-21 19:03:39', NULL, 0, '2025-11-21 19:03:39', '2025-11-21 19:03:39'),
(299, 92, 44, 'member', 0, 0, 0, 0, NULL, 1, NULL, NULL, NULL, '2025-11-21 19:03:39', NULL, 0, '2025-11-21 19:03:39', '2025-11-21 19:03:39'),
(300, 92, 5542, 'member', 0, 0, 0, 0, NULL, 1, NULL, NULL, NULL, '2025-11-21 19:03:39', NULL, 0, '2025-11-21 19:03:39', '2025-11-21 19:03:39'),
(301, 92, 5965, 'member', 0, 0, 0, 0, NULL, 1, NULL, NULL, NULL, '2025-11-21 19:03:39', NULL, 0, '2025-11-21 19:03:39', '2025-11-21 19:03:39'),
(302, 92, 4911, 'owner', 0, 0, 0, 0, NULL, 1, NULL, NULL, '2025-11-21 19:06:49', '2025-11-21 19:03:39', NULL, 0, '2025-11-21 19:03:39', '2025-11-21 19:06:49'),
(304, 93, 12, 'member', 0, 0, 0, 0, NULL, 1, NULL, NULL, NULL, '2025-11-21 19:06:01', NULL, 0, '2025-11-21 19:06:01', '2025-11-21 19:06:01'),
(305, 93, 30, 'member', 0, 0, 0, 0, NULL, 1, NULL, NULL, NULL, '2025-11-21 19:06:01', NULL, 0, '2025-11-21 19:06:01', '2025-11-21 19:06:01'),
(306, 93, 44, 'member', 0, 0, 0, 0, NULL, 1, NULL, NULL, NULL, '2025-11-21 19:06:01', NULL, 0, '2025-11-21 19:06:01', '2025-11-21 19:06:01'),
(307, 93, 5542, 'member', 0, 0, 0, 0, NULL, 1, NULL, NULL, NULL, '2025-11-21 19:06:01', NULL, 0, '2025-11-21 19:06:01', '2025-11-21 19:06:01'),
(308, 93, 4911, 'owner', 0, 0, 0, 0, NULL, 1, NULL, NULL, '2025-11-21 19:06:30', '2025-11-21 19:06:01', NULL, 0, '2025-11-21 19:06:01', '2025-11-21 19:06:30'),
(322, 97, 4911, 'member', 0, 0, 0, 0, NULL, 1, NULL, 1889, '2025-11-21 19:36:52', '2025-11-21 19:33:32', NULL, 0, '2025-11-21 19:33:32', '2025-11-21 19:36:52'),
(323, 97, 9839, 'member', 0, 0, 0, 0, NULL, 1, NULL, NULL, NULL, '2025-11-21 19:33:32', NULL, 1, '2025-11-21 19:33:32', '2025-11-21 19:36:31'),
(330, 99, 4911, 'member', 0, 0, 0, 0, NULL, 1, NULL, 1902, '2025-11-21 20:12:50', '2025-11-21 19:48:47', NULL, 0, '2025-11-21 19:48:47', '2025-11-21 20:12:50'),
(331, 99, 9798, 'member', 0, 0, 0, 0, NULL, 1, NULL, 1901, '2025-11-22 12:52:31', '2025-11-21 19:48:47', NULL, 0, '2025-11-21 19:48:47', '2025-11-22 12:52:31'),
(332, 100, 9831, 'member', 0, 0, 0, 0, NULL, 1, NULL, 1904, '2025-11-22 12:36:17', '2025-11-22 12:36:09', NULL, 0, '2025-11-22 12:36:09', '2025-11-22 12:36:17'),
(333, 100, 9798, 'member', 0, 0, 0, 0, NULL, 1, NULL, NULL, '2025-11-22 12:49:39', '2025-11-22 12:36:09', NULL, 0, '2025-11-22 12:36:09', '2025-11-22 12:49:39'),
(341, 108, 4, 'member', 0, 0, 0, 0, NULL, 1, NULL, 1925, '2025-12-23 07:45:31', '2025-12-06 06:32:47', NULL, 0, '2025-12-06 06:32:47', '2025-12-23 07:45:31'),
(342, 108, 6, 'member', 0, 0, 0, 0, NULL, 1, NULL, 1924, '2025-12-07 09:52:04', '2025-12-06 06:32:47', NULL, 0, '2025-12-06 06:32:47', '2025-12-07 09:52:04'),
(343, 109, 4, 'member', 0, 0, 0, 1, NULL, 0, NULL, 1973, '2025-12-24 06:13:34', '2025-12-24 03:51:43', NULL, 0, '2025-12-07 05:43:55', '2025-12-24 06:13:34'),
(344, 109, 6, 'member', 0, 0, 0, 0, NULL, 1, NULL, NULL, '2025-12-07 23:28:21', '2025-12-07 10:45:37', NULL, 26, '2025-12-07 10:45:37', '2025-12-24 05:24:42'),
(345, 109, 8, 'member', 0, 0, 0, 0, NULL, 1, NULL, 1926, '2025-12-07 23:34:25', '2025-12-07 23:33:50', NULL, 25, '2025-12-07 23:33:50', '2025-12-24 05:24:42'),
(346, 109, 1, 'member', 0, 0, 0, 0, NULL, 1, NULL, NULL, NULL, '2025-12-24 00:41:02', NULL, 10, '2025-12-24 00:41:02', '2025-12-24 05:24:42');

-- --------------------------------------------------------

--
-- Table structure for table `chat_discussion_topics`
--

CREATE TABLE `chat_discussion_topics` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `conversation_id` bigint(20) UNSIGNED NOT NULL,
  `created_by` bigint(20) UNSIGNED DEFAULT NULL,
  `slug` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `position` int(10) UNSIGNED NOT NULL DEFAULT 0,
  `visibility` varchar(255) NOT NULL DEFAULT 'public',
  `slow_mode_seconds` int(10) UNSIGNED NOT NULL DEFAULT 0,
  `is_archived` tinyint(1) NOT NULL DEFAULT 0,
  `is_readonly` tinyint(1) NOT NULL DEFAULT 0 COMMENT 'Only moderators/admins can post',
  `allowed_roles` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL COMMENT 'JSON array of roles that can view this channel' CHECK (json_valid(`allowed_roles`)),
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `chat_discussion_topics`
--

INSERT INTO `chat_discussion_topics` (`id`, `conversation_id`, `created_by`, `slug`, `name`, `description`, `position`, `visibility`, `slow_mode_seconds`, `is_archived`, `is_readonly`, `allowed_roles`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 109, 4, 'chats', 'chats', NULL, 1, 'public', 0, 0, 0, NULL, '2025-12-07 08:07:16', '2025-12-07 08:07:16', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `chat_discussion_topic_messages`
--

CREATE TABLE `chat_discussion_topic_messages` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `topic_id` bigint(20) UNSIGNED NOT NULL,
  `message_id` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `chat_friendships`
--

CREATE TABLE `chat_friendships` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `requester_id` bigint(20) UNSIGNED NOT NULL,
  `addressee_id` bigint(20) UNSIGNED NOT NULL,
  `status` enum('pending','accepted','declined','blocked') NOT NULL DEFAULT 'pending',
  `responded_at` timestamp NULL DEFAULT NULL,
  `blocked_by_user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `chat_group_invites`
--

CREATE TABLE `chat_group_invites` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `conversation_id` bigint(20) UNSIGNED NOT NULL,
  `created_by` bigint(20) UNSIGNED NOT NULL,
  `code` varchar(255) NOT NULL,
  `max_uses` int(10) UNSIGNED DEFAULT NULL,
  `uses_count` int(10) UNSIGNED NOT NULL DEFAULT 0,
  `last_used_at` timestamp NULL DEFAULT NULL,
  `expires_at` timestamp NULL DEFAULT NULL,
  `is_revoked` tinyint(1) NOT NULL DEFAULT 0,
  `metadata` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`metadata`)),
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `chat_group_invites`
--

INSERT INTO `chat_group_invites` (`id`, `conversation_id`, `created_by`, `code`, `max_uses`, `uses_count`, `last_used_at`, `expires_at`, `is_revoked`, `metadata`, `created_at`, `updated_at`) VALUES
(1, 109, 4, 'JxmGDLMc', NULL, 1, '2025-12-07 10:45:37', NULL, 1, NULL, '2025-12-07 10:41:12', '2025-12-07 10:41:12'),
(2, 109, 4, 'kKBDBBca', NULL, 1, '2025-12-07 23:33:50', NULL, 0, NULL, '2025-12-07 23:25:17', '2025-12-07 23:25:17');

-- --------------------------------------------------------

--
-- Table structure for table `chat_messages`
--

CREATE TABLE `chat_messages` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `conversation_id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `type` enum('text','image','file','system','call','gif','video') NOT NULL DEFAULT 'text',
  `body` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `deleted_by_moderator` tinyint(1) NOT NULL DEFAULT 0,
  `original_body` text DEFAULT NULL,
  `reply_to_message_id` bigint(20) UNSIGNED DEFAULT NULL,
  `forwarded_from_message_id` bigint(20) UNSIGNED DEFAULT NULL,
  `forwarded_metadata` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`forwarded_metadata`)),
  `meta` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL,
  `edited_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `chat_messages`
--

INSERT INTO `chat_messages` (`id`, `conversation_id`, `user_id`, `type`, `body`, `deleted_by_moderator`, `original_body`, `reply_to_message_id`, `forwarded_from_message_id`, `forwarded_metadata`, `meta`, `edited_at`, `deleted_at`, `created_at`, `updated_at`) VALUES
(1, 2, 9790, 'text', 'hello testing 123, Monday Ph&nbsp;', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-08-17 16:36:41', '2025-08-17 16:36:41'),
(3, 4, 9790, 'text', 'hello Ms. Kim', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-08-17 16:38:56', '2025-08-17 16:38:56'),
(4, 5, 9790, 'text', 'Alfa put an avatar photo in your profile; you\'re a ghost. LOL', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-08-17 16:40:05', '2025-08-17 16:40:05'),
(5, 4, 30, 'text', 'Hi! Ms. Saffi. I will be jumping out at 10 PM. We will have our PlanPanther meeting with Juan then.', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-08-18 06:45:16', '2025-08-18 06:45:16'),
(6, 4, 9790, 'text', 'Ms Kim, I forwarded to you the email of Cesilia from Mr. Douglas\'s email. I think this is for the twins or Chelyn \'s work to do with the bidding process.', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-08-18 15:16:45', '2025-08-18 15:16:45'),
(7, 4, 9790, 'text', 'During your meeting today, please inform our team about this matter. Thank you', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-08-18 15:18:17', '2025-08-18 15:18:17'),
(8, 4, 30, 'text', 'Hi. Ms. Saffi, what specific action would you like the team to take on the document? Save all those in Ms. Cesilia\'s CRM Porta?', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-08-18 16:17:29', '2025-08-18 16:17:29'),
(11, 6, 4911, 'text', 'Anyone here ?', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-08-18 17:13:30', '2025-08-18 17:13:30'),
(20, 7, 9790, 'text', 'Hello Team, ready for testing now.', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-08-18 18:32:01', '2025-08-18 18:32:01'),
(21, 7, 9801, 'text', 'Good morning', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-08-18 18:33:10', '2025-08-18 18:33:10'),
(24, 7, 30, 'text', 'Naka receive mog call from me?', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-08-18 18:35:07', '2025-08-18 18:35:07'),
(27, 8, 9790, 'text', 'hello testing 123', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-08-20 03:34:00', '2025-08-20 03:34:00'),
(30, 9, 1, 'text', 'Hello Everyone&nbsp;👋🏻', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-08-20 05:16:51', '2025-08-20 05:16:51'),
(34, 9, 9790, 'text', 'Hello, the meeting starts in a few minutes. Please stand by', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-08-20 05:52:20', '2025-08-20 05:52:20'),
(35, 9, 1, 'text', 'Noted Ma\'am Saffi.', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-08-20 05:53:41', '2025-08-20 05:53:41'),
(43, 9, 1, 'text', 'Please join here:&nbsp;<a href=\"https://meet.hillbcs.com/9\" rel=\"noopener noreferrer\" target=\"_blank\">https://meet.hillbcs.com/9</a>', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-08-20 06:01:25', '2025-08-20 06:01:25'),
(49, 2, 4911, 'text', 'Hello :)&nbsp; did u get ur coffee ?', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-08-20 16:19:37', '2025-08-20 16:19:37'),
(52, 2, 9790, 'text', 'yes done earlier in my morning time.', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-08-20 18:25:29', '2025-08-20 18:25:29'),
(54, 2, 4911, 'text', 'Hello Saffi !', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-08-21 06:26:05', '2025-08-21 06:26:05'),
(55, 2, 9790, 'text', 'yes I am in.', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-08-21 06:29:45', '2025-08-21 06:29:45'),
(56, 6, 9790, 'text', 'Hello, we will start the meeting now.', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-08-21 06:30:14', '2025-08-21 06:30:14'),
(64, 6, 9787, 'text', NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-08-21 07:01:37', '2025-08-21 07:01:37'),
(65, 6, 9802, 'text', NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-08-21 07:02:15', '2025-08-21 07:02:15'),
(66, 6, 9787, 'text', 'do you still have access to this one sir?', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-08-21 07:02:23', '2025-08-21 07:02:23'),
(67, 6, 9787, 'text', 'I keep hearing the ringing of the call', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-08-21 07:02:57', '2025-08-21 07:02:57'),
(68, 6, 9787, 'text', 'here sir', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-08-21 07:03:27', '2025-08-21 07:03:27'),
(69, 6, 4911, 'text', 'me too ; I hear ringing', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-08-21 07:04:18', '2025-08-21 07:04:18'),
(70, 6, 9787, 'text', 'again with this bug', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-08-21 07:04:33', '2025-08-21 07:04:33'),
(71, 6, 9790, 'text', NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-08-21 07:05:24', '2025-08-21 07:05:24'),
(72, 6, 9787, 'text', NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-08-21 07:05:28', '2025-08-21 07:05:28'),
(73, 6, 9787, 'text', 'this', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-08-21 07:05:33', '2025-08-21 07:05:33'),
(74, 6, 9787, 'text', 'can you see the image sir?', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-08-21 07:06:20', '2025-08-21 07:06:20'),
(75, 6, 9787, 'text', 'sent it to your whatsapp sir', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-08-21 07:06:59', '2025-08-21 07:06:59'),
(84, 7, 30, 'text', 'Hello! TEAM. Please start the call on time. I will just have a short meeting with Ms. Saffi, Sir Douglas and Ms. Alfa.', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-08-21 17:57:06', '2025-08-21 17:57:06'),
(85, 7, 30, 'text', 'I will Join right after&nbsp; we finished. Thanks!', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-08-21 17:57:31', '2025-08-21 17:57:31'),
(102, 12, 9827, 'text', 'hi', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-08-22 09:02:51', '2025-08-22 09:02:51'),
(103, 15, 30, 'text', '<p>Hello Miss Cesilia, </p>\r\n\r\n<p>I hope you are having a great day. I\'m just giving you a\r\nsummary of what we have done this week for you. I am proud to let you know that\r\nthe files that you have shared with Mr. Douglas have been uploaded to your portal,\r\nand 3 projects (Maple, Chick-fil-A, Cypress) were added to the PROJECT\r\nMANAGEMENT. We also added some contacts to the RELATIONSHIPS; these contacts\r\nare from your files.</p>\r\n\r\n<p>Furthermore, in FILE MANAGER, there are the files: ASSORTED\r\nFORMS, BOOKLET, CONTRACT FILES, and VIDEOS. Feel free to reach out through\r\nhere if you need anything else.</p>', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-08-23 00:20:05', '2025-08-23 00:20:05'),
(107, 2, 9790, 'text', 'hello, Mr. Douglas, do you have any errands that I can work on?', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-08-23 06:16:21', '2025-08-23 06:16:21'),
(112, 9, 9790, 'text', 'we are here M,s Kim &amp; Marc', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-08-23 09:31:36', '2025-08-23 09:31:36'),
(113, 9, 1, 'text', NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-08-23 10:26:25', '2025-08-23 10:26:25'),
(114, 9, 1, 'text', NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-08-23 10:26:32', '2025-08-23 10:26:32'),
(115, 9, 1, 'text', 'The <b><i>Budget </i></b>and <b><i>Expenses</i></b> are already updated in&nbsp;<b>Cypress Family Apartments – Paradise </b>Project Management.', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-08-23 10:28:56', '2025-08-23 10:28:56'),
(118, 16, 9790, 'text', 'Hello, I\'ll start calling now.', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-08-24 18:57:21', '2025-08-24 18:57:21'),
(122, 16, 4911, 'text', 'Hi Saffi,&nbsp; I am on phone with sister ....&nbsp; be free soon', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-08-24 19:00:49', '2025-08-24 19:00:49'),
(128, 17, 9790, 'text', 'hello, everyone. I will call now, let\'s do it an invoicing.', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-08-25 05:47:57', '2025-08-25 05:47:57'),
(143, 4, 9790, 'text', 'Ms Kim checks Cesilia\'s portal regularly. THanks', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-08-25 06:37:44', '2025-08-25 06:37:44'),
(144, 4, 30, 'text', 'Got your message, Ms. Saffi.', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-08-25 06:39:19', '2025-08-25 06:39:19'),
(147, 18, 30, 'text', 'Hello! Good morning, Team. We will start at 7 AM. See you!', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-08-25 06:55:22', '2025-08-25 06:55:22'),
(148, 18, 9803, 'text', 'got it Miss Kimberly', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-08-25 06:56:01', '2025-08-25 06:56:01'),
(154, 2, 4911, 'text', 'Hi , r u there ?', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-08-25 16:56:36', '2025-08-25 16:56:36'),
(162, 6, 9790, 'text', 'The meeting will start in a few minutes. Please stand by. Thank you', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-08-26 06:07:28', '2025-08-26 06:07:28'),
(168, 6, 9790, 'text', NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-08-26 07:22:45', '2025-08-26 07:22:45'),
(169, 6, 9790, 'text', 'testing 123', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-08-26 07:23:05', '2025-08-26 07:23:05'),
(173, 20, 9790, 'text', 'Hello, good morning. Please join the meeting.', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-08-26 14:38:31', '2025-08-26 14:38:31'),
(174, 2, 9790, 'text', 'please join the meeting', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-08-26 14:44:56', '2025-08-26 14:44:56'),
(175, 2, 9790, 'text', '<a href=\"https://meet.hillbcs.com/20\" rel=\"noopener noreferrer\" target=\"_blank\">https://meet.hillbcs.com/20</a>', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-08-26 14:45:06', '2025-08-26 14:45:06'),
(179, 2, 9790, 'text', 'weird', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-08-26 15:15:09', '2025-08-26 15:15:09'),
(181, 20, 9790, 'text', 'So weird, I click join, but when I\'m joining, only me inside the meeting', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-08-26 15:17:42', '2025-08-26 15:17:42'),
(182, 7, 30, 'text', 'Hello! I will call now.', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-08-26 18:00:12', '2025-08-26 18:00:12'),
(216, 7, 9801, 'system', 'Meeting started', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-08-27 18:00:41', '2025-08-27 18:00:41'),
(217, 7, 9799, 'text', NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-08-27 18:47:30', '2025-08-27 18:47:30'),
(218, 7, 30, 'text', 'Hello, Sir Kent. Do you have any information regarding the email that was sent to Sir Rob\'s email address? Please see the screenshot that Joy sent above.', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-08-27 19:05:44', '2025-08-27 19:05:44'),
(220, 21, 9827, 'text', 'hi sir kent&nbsp;', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-08-28 06:27:06', '2025-08-28 06:27:06'),
(221, 21, 9827, 'text', 'asa makita ang link sa meeting?', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-08-28 06:27:14', '2025-08-28 06:27:14'),
(222, 6, 4911, 'text', 'Hello to all; r we ready to start Marketing Meeting ?', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-08-28 06:27:31', '2025-08-28 06:27:31'),
(223, 6, 9790, 'text', 'yes&nbsp;', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-08-28 06:29:09', '2025-08-28 06:29:09'),
(224, 6, 9790, 'text', 'I\'m in', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-08-28 06:29:41', '2025-08-28 06:29:41'),
(225, 6, 9790, 'system', 'Meeting started', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-08-28 06:30:55', '2025-08-28 06:30:55'),
(226, 6, 9790, 'text', '<a href=\"https://meet.hillbcs.com/6\" rel=\"noopener noreferrer\" target=\"_blank\">https://meet.hillbcs.com/6</a>', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-08-28 06:35:16', '2025-08-28 06:35:16'),
(227, 7, 30, 'text', 'Hi! I am already inside the call guys.', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-08-28 18:02:57', '2025-08-28 18:02:57'),
(228, 7, 9800, 'text', '<p><span>Hello Sir Kent, here are some projects that could not be uploaded (server error)</span></p>\r\n<p></p>', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-08-28 18:07:41', '2025-08-28 18:07:41'),
(229, 7, 9799, 'text', NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-08-28 20:32:21', '2025-08-28 20:32:21'),
(233, 7, 9798, 'text', 'Thank you for this. I will check this asap.', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-08-29 06:18:09', '2025-08-29 06:18:09'),
(276, 7, 9798, 'text', 'can you upload the file you attached Ms. @joy and @jane ?', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-08-29 07:07:36', '2025-08-29 07:07:36'),
(277, 22, 9827, 'text', 'Hi Miss', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-08-29 08:06:58', '2025-08-29 08:06:58'),
(278, 22, 9827, 'text', NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-08-29 08:07:03', '2025-08-29 08:07:03'),
(279, 22, 9827, 'text', 'naay 2 ka notification sa project management sa plan panther&nbsp;', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-08-29 08:07:19', '2025-08-29 08:07:19'),
(280, 15, 9779, 'system', 'Cesilia Cortez Started the Meeting', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-08-30 12:54:36', '2025-08-30 12:54:36'),
(281, 22, 30, 'text', 'Good morning, William. Sorry for the late reply. We don\'t need to do anything about those. But thank you for reporting on that.&nbsp;', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-08-31 18:48:02', '2025-08-31 18:48:02'),
(282, 16, 9790, 'text', 'Hello everyone, are you there?', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-08-31 18:56:34', '2025-08-31 18:56:34'),
(283, 16, 9790, 'text', 'Who\'s online now?', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-08-31 18:56:47', '2025-08-31 18:56:47'),
(284, 16, 9813, 'text', 'me', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-08-31 18:57:53', '2025-08-31 18:57:53'),
(285, 20, 9797, 'text', 'Saff', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-08-31 18:58:41', '2025-08-31 18:58:41'),
(286, 16, 4911, 'text', 'are we meeting now ?', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-08-31 18:59:36', '2025-08-31 18:59:36'),
(287, 16, 4911, 'system', 'Douglas Hill Started the Meeting', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-08-31 19:01:19', '2025-08-31 19:01:19'),
(288, 16, 9790, 'system', 'Saffi Patino Joined the Meeting', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-08-31 19:01:32', '2025-08-31 19:01:32'),
(289, 16, 9813, 'system', 'Sita Cabeling Joined the Meeting', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-08-31 19:01:53', '2025-08-31 19:01:53'),
(290, 16, 9790, 'system', 'Saffi Patino Left the Meeting.', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-08-31 19:02:00', '2025-08-31 19:02:00'),
(291, 16, 30, 'system', 'Kimberly Ann Madelo Joined the Meeting', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-08-31 19:02:00', '2025-08-31 19:02:00'),
(292, 16, 4911, 'system', 'Douglas Hill Left the Meeting.', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-08-31 19:02:15', '2025-08-31 19:02:15'),
(293, 16, 4911, 'system', 'Douglas Hill Joined the Meeting', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-08-31 19:02:22', '2025-08-31 19:02:22'),
(294, 16, 4911, 'system', 'Douglas Hill Left the Meeting.', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-08-31 19:02:34', '2025-08-31 19:02:34'),
(295, 16, 30, 'text', 'Sit and I are in another call.', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-08-31 19:02:46', '2025-08-31 19:02:46'),
(296, 16, 30, 'text', 'Sita', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-08-31 19:02:51', '2025-08-31 19:02:51'),
(297, 16, 4911, 'text', 'Is anyone inside the meeting room?', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-08-31 19:02:53', '2025-08-31 19:02:53'),
(298, 16, 9790, 'system', 'Saffi Patino Joined the Meeting', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-08-31 19:03:01', '2025-08-31 19:03:01'),
(299, 16, 4911, 'system', 'Douglas Hill Joined the Meeting', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-08-31 19:03:20', '2025-08-31 19:03:20'),
(300, 16, 30, 'text', 'When I clicked the join meeting. I end up inside the call with Sita.', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-08-31 19:03:25', '2025-08-31 19:03:25'),
(301, 16, 4911, 'system', 'Douglas Hill Left the Meeting.', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-08-31 19:03:39', '2025-08-31 19:03:39'),
(302, 16, 9813, 'system', 'Sita Cabeling Left the Meeting.', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-08-31 19:04:04', '2025-08-31 19:04:04'),
(303, 16, 30, 'system', 'Kimberly Ann Madelo Left the Meeting.', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-08-31 19:04:05', '2025-08-31 19:04:05'),
(304, 16, 4911, 'system', 'Douglas Hill Joined the Meeting', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-08-31 19:04:35', '2025-08-31 19:04:35'),
(305, 16, 9790, 'system', 'Saffi Patino Joined the Meeting', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-08-31 19:04:36', '2025-08-31 19:04:36'),
(306, 16, 4911, 'system', 'Douglas Hill Left the Meeting.', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-08-31 19:04:52', '2025-08-31 19:04:52'),
(307, 16, 30, 'text', 'Where is the call meeting? We are lost again.', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-08-31 19:04:58', '2025-08-31 19:04:58'),
(308, 16, 30, 'system', 'Kimberly Ann Madelo Joined the Meeting', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-08-31 19:05:12', '2025-08-31 19:05:12'),
(309, 16, 9813, 'system', 'Sita Cabeling Joined the Meeting', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-08-31 19:05:33', '2025-08-31 19:05:33'),
(310, 16, 9790, 'system', 'Saffi Patino Left the Meeting.', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-08-31 19:05:35', '2025-08-31 19:05:35'),
(311, 16, 9790, 'system', 'Saffi Patino Joined the Meeting', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-08-31 19:05:40', '2025-08-31 19:05:40'),
(312, 16, 30, 'system', 'Kimberly Ann Madelo Left the Meeting.', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-08-31 19:05:45', '2025-08-31 19:05:45'),
(313, 16, 30, 'text', NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-08-31 19:05:49', '2025-08-31 19:05:49'),
(314, 16, 9790, 'system', 'Saffi Patino Joined the Meeting', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-08-31 19:05:54', '2025-08-31 19:05:54'),
(315, 16, 9813, 'system', 'Sita Cabeling Left the Meeting.', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-08-31 19:06:03', '2025-08-31 19:06:03'),
(316, 16, 9813, 'system', 'Sita Cabeling Joined the Meeting', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-08-31 19:06:15', '2025-08-31 19:06:15'),
(317, 16, 4911, 'system', 'Douglas Hill Joined the Meeting', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-08-31 19:06:33', '2025-08-31 19:06:33'),
(318, 16, 9790, 'system', 'Saffi Patino Joined the Meeting', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-08-31 19:06:41', '2025-08-31 19:06:41'),
(319, 16, 4911, 'system', 'Douglas Hill Left the Meeting.', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-08-31 19:06:43', '2025-08-31 19:06:43'),
(320, 16, 9790, 'system', 'Saffi Patino Left the Meeting.', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-08-31 19:06:46', '2025-08-31 19:06:46'),
(321, 16, 9813, 'text', NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-08-31 19:06:54', '2025-08-31 19:06:54'),
(323, 16, 9813, 'text', 'only me', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-08-31 19:07:05', '2025-08-31 19:07:05'),
(325, 16, 9790, 'system', 'Saffi Patino Joined the Meeting', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-08-31 19:07:14', '2025-08-31 19:07:14'),
(326, 16, 30, 'text', 'Are you all inside the call room now?', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-08-31 19:07:19', '2025-08-31 19:07:19'),
(327, 16, 9813, 'system', 'Sita Cabeling Joined the Meeting', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-08-31 19:07:26', '2025-08-31 19:07:26'),
(328, 16, 30, 'text', '🤔🤔🤔🤔', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-08-31 19:07:50', '2025-08-31 19:07:50'),
(329, 16, 30, 'system', 'Kimberly Ann Madelo Joined the Meeting', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-08-31 19:08:13', '2025-08-31 19:08:13'),
(330, 16, 30, 'system', 'Kimberly Ann Madelo Left the Meeting.', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-08-31 19:08:28', '2025-08-31 19:08:28'),
(331, 16, 30, 'text', NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-08-31 19:08:32', '2025-08-31 19:08:32'),
(332, 16, 9790, 'system', 'Saffi Patino Joined the Meeting', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-08-31 19:09:30', '2025-08-31 19:09:30'),
(333, 16, 9790, 'system', 'Saffi Patino Joined the Meeting', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-08-31 19:11:17', '2025-08-31 19:11:17'),
(334, 16, 9790, 'system', 'Saffi Patino Joined the Meeting', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-08-31 19:14:02', '2025-08-31 19:14:02'),
(335, 16, 9790, 'system', 'Saffi Patino Joined the Meeting', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-08-31 19:14:31', '2025-08-31 19:14:31'),
(336, 16, 9790, 'system', 'Saffi Patino Started the Meeting', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-08-31 19:16:25', '2025-08-31 19:16:25'),
(337, 16, 9790, 'system', 'Saffi Patino Started the Meeting', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-08-31 19:24:24', '2025-08-31 19:24:24'),
(338, 16, 9790, 'system', 'Saffi Patino Started the Meeting', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-08-31 19:25:28', '2025-08-31 19:25:28'),
(339, 16, 9790, 'system', 'Saffi Patino Joined the Meeting', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-08-31 19:28:43', '2025-08-31 19:28:43'),
(340, 16, 9790, 'system', 'Saffi Patino Left the Meeting.', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-08-31 19:29:23', '2025-08-31 19:29:23'),
(341, 16, 9790, 'system', 'Saffi Patino Started the Meeting', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-08-31 19:31:18', '2025-08-31 19:31:18'),
(342, 16, 9790, 'text', '<a href=\"https://meet.hillbcs.com/Hillbcs%20Directorts%20%20Manager%20%20%20Owners%20Exclusive%20Meeting%20Meeting%20ZAVPsOPpa5GIb4Cb7EizxHXAlv7YduOqfYqeHIZD16\" rel=\"noopener noreferrer\" target=\"_blank\">https://meet.hillbcs.com/Hillbcs%20Directorts%20%20Manager%20%20%20Owners%20Exclusive%20Meeting%20Meeting%20ZAVPsOPpa5GIb4Cb7EizxHXAlv7YduOqfYqeHIZD16</a>', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-08-31 19:32:05', '2025-08-31 19:32:05'),
(343, 16, 9790, 'system', 'Saffi Patino Joined the Meeting', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-08-31 19:38:13', '2025-08-31 19:38:13'),
(344, 16, 9790, 'system', 'Saffi Patino Joined the Meeting', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-08-31 19:45:17', '2025-08-31 19:45:17'),
(345, 16, 9790, 'text', 'hey join me in my call', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-08-31 19:46:06', '2025-08-31 19:46:06'),
(346, 16, 4911, 'system', 'Douglas Hill Joined the Meeting', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-08-31 19:46:33', '2025-08-31 19:46:33'),
(347, 16, 4911, 'system', 'Douglas Hill Left the Meeting.', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-08-31 19:46:41', '2025-08-31 19:46:41'),
(348, 16, 4911, 'system', 'Douglas Hill Joined the Meeting', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-08-31 19:48:03', '2025-08-31 19:48:03'),
(349, 16, 4911, 'system', 'Douglas Hill Left the Meeting.', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-08-31 19:48:22', '2025-08-31 19:48:22'),
(350, 16, 9790, 'text', '<a href=\"https://meet.hillbcs.com/Hillbcs%20Directorts%20%20Manager%20%20%20Owners%20Exclusive%20Meeting%20Meeting%20ZAVPsOPpa5GIb4Cb7EizxHXAlv7YduOqfYqeHIZD16\" rel=\"noopener noreferrer\" target=\"_blank\">https://meet.hillbcs.com/Hillbcs%20Directorts%20%20Manager%20%20%20Owners%20Exclusive%20Meeting%20Meeting%20ZAVPsOPpa5GIb4Cb7EizxHXAlv7YduOqfYqeHIZD16</a>', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-08-31 19:49:50', '2025-08-31 19:49:50'),
(351, 16, 4911, 'system', 'Douglas Hill Joined the Meeting', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-08-31 19:52:21', '2025-08-31 19:52:21'),
(352, 16, 4911, 'system', 'Douglas Hill Left the Meeting.', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-08-31 19:52:35', '2025-08-31 19:52:35'),
(353, 16, 4911, 'system', 'Douglas Hill Joined the Meeting', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-08-31 19:52:57', '2025-08-31 19:52:57'),
(354, 16, 4911, 'system', 'Douglas Hill Left the Meeting.', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-08-31 19:53:10', '2025-08-31 19:53:10'),
(355, 16, 9790, 'system', 'Saffi Patino Joined the Meeting', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-08-31 19:56:24', '2025-08-31 19:56:24'),
(356, 16, 9790, 'system', 'Saffi Patino Joined the Meeting', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-08-31 19:56:47', '2025-08-31 19:56:47'),
(357, 16, 4911, 'system', 'Douglas Hill Joined the Meeting', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-08-31 20:00:40', '2025-08-31 20:00:40'),
(358, 16, 4911, 'system', 'Douglas Hill Left the Meeting.', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-08-31 20:01:08', '2025-08-31 20:01:08'),
(359, 16, 9790, 'system', 'Saffi Patino Joined the Meeting', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-08-31 20:03:21', '2025-08-31 20:03:21'),
(364, 16, 9790, 'system', 'Saffi Patino Left the Meeting.', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-08-31 20:04:16', '2025-08-31 20:04:16'),
(365, 16, 9790, 'system', 'Saffi Patino Started the Meeting', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-08-31 20:04:40', '2025-08-31 20:04:40'),
(366, 16, 9790, 'system', 'Saffi Patino Joined the Meeting', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-08-31 20:04:41', '2025-08-31 20:04:41'),
(367, 16, 9790, 'system', 'Saffi Patino Left the Meeting.', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-08-31 20:04:45', '2025-08-31 20:04:45'),
(368, 16, 9790, 'system', 'Saffi Patino Left the Meeting.', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-08-31 21:41:30', '2025-08-31 21:41:30'),
(435, 26, 30, 'text', 'Hello! Everyone. Let\'s meet at 7:00 AM. Thank you!', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-09-01 06:59:21', '2025-09-01 06:59:21'),
(438, 26, 30, 'system', 'Kimberly Ann Madelo Started the Meeting', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-09-01 07:00:28', '2025-09-01 07:00:28'),
(439, 17, 9790, 'text', 'hello, our meeting starts now.', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-09-01 07:00:48', '2025-09-01 07:00:48'),
(440, 17, 9790, 'system', 'Saffi Patino Started the Meeting', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-09-01 07:00:54', '2025-09-01 07:00:54'),
(441, 26, 30, 'text', 'If there is no notification call. Please just click the Join Meeting icon on top.', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-09-01 07:01:57', '2025-09-01 07:01:57'),
(442, 17, 9790, 'system', 'Saffi Patino Joined the Meeting', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-09-01 07:02:03', '2025-09-01 07:02:03'),
(443, 7, 9799, 'text', 'we\'ll try tomorrow sir kent', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-09-01 07:02:04', '2025-09-01 07:02:04'),
(444, 26, 9799, 'system', 'Joy Cantancio Started the Meeting', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-09-01 07:02:16', '2025-09-01 07:02:16'),
(445, 26, 9803, 'system', 'Chelynn Joined the Meeting', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-09-01 07:02:48', '2025-09-01 07:02:48'),
(446, 26, 9800, 'system', 'Jane Cantancio Started the Meeting', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-09-01 07:02:50', '2025-09-01 07:02:50'),
(448, 17, 9790, 'system', 'Saffi Patino Started the Meeting', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-09-01 07:03:55', '2025-09-01 07:03:55'),
(454, 26, 9802, 'system', 'Alfa Macasi Joined the Meeting', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-09-01 07:08:13', '2025-09-01 07:08:13'),
(455, 26, 44, 'system', 'Juan Joined the Meeting', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-09-01 07:08:29', '2025-09-01 07:08:29'),
(457, 17, 9790, 'system', 'Saffi Patino Joined the Meeting', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-09-01 07:14:39', '2025-09-01 07:14:39'),
(458, 17, 9790, 'system', 'Saffi Patino Started the Meeting', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-09-01 07:18:25', '2025-09-01 07:18:25'),
(459, 17, 9790, 'system', 'Saffi Patino Joined the Meeting', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-09-01 07:18:58', '2025-09-01 07:18:58'),
(460, 26, 9802, 'system', 'Alfa Macasi Left the Meeting.', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-09-01 07:24:11', '2025-09-01 07:24:11'),
(461, 26, 9802, 'system', 'Alfa Macasi Joined the Meeting', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-09-01 07:24:11', '2025-09-01 07:24:11'),
(462, 26, 9800, 'system', 'Jane Cantancio Left the Meeting.', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-09-01 08:23:25', '2025-09-01 08:23:25'),
(463, 26, 9799, 'system', 'Joy Cantancio Left the Meeting.', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-09-01 08:23:31', '2025-09-01 08:23:31'),
(464, 7, 30, 'text', 'Hello team. I will call now.', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-09-01 17:59:36', '2025-09-01 17:59:36'),
(465, 7, 30, 'system', 'Kimberly Ann Madelo Started the Meeting', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-09-01 18:00:33', '2025-09-01 18:00:33'),
(466, 7, 9803, 'system', 'Chelynn Joined the Meeting', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-09-01 18:02:19', '2025-09-01 18:02:19'),
(467, 7, 9799, 'system', 'Joy Cantancio Joined the Meeting', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-09-01 18:16:13', '2025-09-01 18:16:13'),
(468, 7, 9800, 'text', 'Hello sir @Kent, the file upload feature is now functional. Thank you', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-09-01 18:51:52', '2025-09-01 18:51:52'),
(469, 7, 9800, 'text', '@development Team', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-09-01 18:52:09', '2025-09-01 18:52:09'),
(470, 7, 9799, 'system', 'Joy Cantancio Left the Meeting.', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-09-01 19:11:33', '2025-09-01 19:11:33'),
(471, 7, 9799, 'text', 'Sir @Kent, we retried uploading the file that failed last week. Some files were successfully uploaded, but there are still a few that failed.', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-09-01 21:16:11', '2025-09-01 21:16:11'),
(472, 7, 9800, 'text', 'Same concern here, Sir Kent. Some projects were successfully uploaded, but others were not.', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-09-01 21:24:03', '2025-09-01 21:24:03'),
(473, 6, 9827, 'system', 'William Jover Started the Meeting', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-09-02 06:34:35', '2025-09-02 06:34:35'),
(474, 6, 9827, 'system', 'William Jover Joined the Meeting', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-09-02 06:34:36', '2025-09-02 06:34:36'),
(475, 6, 9827, 'system', 'William Jover Left the Meeting.', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-09-02 06:34:51', '2025-09-02 06:34:51'),
(476, 20, 9790, 'text', 'Hello everyone, good morning, Philippines!', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-09-02 14:20:38', '2025-09-02 14:20:38'),
(477, 20, 9790, 'system', 'Saffi Patino Started the Meeting', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-09-02 14:20:46', '2025-09-02 14:20:46'),
(478, 20, 4911, 'system', 'Douglas Hill Started the Meeting', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-09-02 14:32:30', '2025-09-02 14:32:30'),
(479, 20, 4911, 'system', 'Douglas Hill Left the Meeting.', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-09-02 14:33:07', '2025-09-02 14:33:07'),
(480, 20, 4911, 'system', 'Douglas Hill Started the Meeting', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-09-02 14:33:47', '2025-09-02 14:33:47'),
(481, 20, 4911, 'system', 'Douglas Hill Left the Meeting.', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-09-02 14:34:14', '2025-09-02 14:34:14'),
(482, 20, 4911, 'system', 'Douglas Hill Started the Meeting', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-09-02 14:35:01', '2025-09-02 14:35:01'),
(483, 20, 4911, 'system', 'Douglas Hill Left the Meeting.', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-09-02 14:35:43', '2025-09-02 14:35:43'),
(484, 20, 4911, 'system', 'Douglas Hill Started the Meeting', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-09-02 14:37:48', '2025-09-02 14:37:48'),
(485, 20, 4911, 'system', 'Douglas Hill Joined the Meeting', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-09-02 14:38:08', '2025-09-02 14:38:08'),
(486, 20, 4911, 'system', 'Douglas Hill Left the Meeting.', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-09-02 14:38:18', '2025-09-02 14:38:18'),
(487, 20, 4911, 'system', 'Douglas Hill Left the Meeting.', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-09-02 14:38:19', '2025-09-02 14:38:19'),
(488, 7, 30, 'text', 'Hi! Guys. I will call now', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-09-02 18:00:27', '2025-09-02 18:00:27'),
(489, 7, 30, 'system', 'Kimberly Ann Madelo Joined the Meeting', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-09-02 18:00:29', '2025-09-02 18:00:29'),
(490, 7, 9803, 'system', 'Chelynn Joined the Meeting', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-09-02 18:01:59', '2025-09-02 18:01:59'),
(491, 7, 9801, 'system', 'Jing Beltran Joined the Meeting', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-09-02 18:02:00', '2025-09-02 18:02:00'),
(492, 7, 9800, 'system', 'Jane Cantancio Joined the Meeting', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-09-02 18:02:17', '2025-09-02 18:02:17'),
(493, 7, 9801, 'system', 'Jing Beltran Left the Meeting.', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-09-02 18:02:20', '2025-09-02 18:02:20'),
(494, 7, 9801, 'system', 'Jing Beltran Joined the Meeting', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-09-02 18:02:23', '2025-09-02 18:02:23'),
(495, 7, 9801, 'system', 'Jing Beltran Joined the Meeting', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-09-02 18:03:31', '2025-09-02 18:03:31'),
(496, 7, 9801, 'system', 'Jing Beltran Left the Meeting.', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-09-02 18:03:40', '2025-09-02 18:03:40'),
(497, 7, 9801, 'system', 'Jing Beltran Joined the Meeting', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-09-02 18:05:03', '2025-09-02 18:05:03'),
(498, 7, 9801, 'system', 'Jing Beltran Left the Meeting.', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-09-02 18:06:01', '2025-09-02 18:06:01'),
(499, 7, 9799, 'system', 'Joy Cantancio Joined the Meeting', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-09-02 18:07:05', '2025-09-02 18:07:05'),
(500, 7, 9790, 'system', 'Saffi Patino Joined the Meeting', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-09-02 18:08:56', '2025-09-02 18:08:56'),
(501, 7, 30, 'system', 'Kimberly Ann Madelo Left the Meeting.', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-09-02 19:06:02', '2025-09-02 19:06:02'),
(502, 7, 9803, 'system', 'Chelynn Left the Meeting.', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-09-02 19:06:02', '2025-09-02 19:06:02'),
(503, 7, 9799, 'system', 'Joy Cantancio Left the Meeting.', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-09-02 19:06:28', '2025-09-02 19:06:28'),
(504, 15, 30, 'text', 'Hello, Ms. Cesilia. Just an update regarding the SOP files tasking. We have already finished the first two SOP, the \"Accounting Dept.\" &amp; the \"CCC-QuickBooks online-invoicing\". I have stored both of your files in the File Manager of your Portal. We will continue with the remaining files and inform you once we have completed them.&nbsp; May you have a good rest, and thank you very much!', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-09-02 20:14:57', '2025-09-02 20:14:57'),
(506, 7, 9798, 'text', 'Bidders List Loading Issue fixed.', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-09-02 21:17:18', '2025-09-02 21:17:18'),
(507, 7, 9798, 'text', 'CRM Declined Status Fixed.', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-09-02 21:29:37', '2025-09-02 21:29:37'),
(541, 7, 9798, 'text', 'If you are still experiencing issues with the meeting such as a white screen or \'page not found\' error, it is due to a DNS configuration. Previously, two IP addresses were set, which caused the browser to get confused about where to connect. The issue is not related to your device or ISP. it’s specifically with the DNS on the \'www\' access. The duplicate entry has already been removed, and the DNS will update within 12-24 hours. Thank you for your patience.', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-09-03 07:43:33', '2025-09-03 07:43:33'),
(543, 27, 44, 'system', 'Juan Started the Meeting', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-09-03 08:09:16', '2025-09-03 08:09:16'),
(544, 28, 9790, 'text', 'Hello, Bessie, welcome po sa Portal ng Hillbcs, huwag makalimutan we\'re here to assist you, me and my team. Feel free to call me anytime.Pwede ka naman mag comment, mag share ng ideas mo or mag recommend, tatanggapin namin. Have a great day! Thank you', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-09-03 17:04:29', '2025-09-03 17:04:29'),
(545, 7, 9790, 'text', 'Hello everyone! Who\'s here?', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-09-03 17:57:29', '2025-09-03 17:57:29'),
(546, 7, 4911, 'text', 'I am here ! ready to go; solve world hunger, save the planet and make peace o earth ,,,&nbsp; we just need a leader - YOU !&nbsp; :)&nbsp;', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-09-03 17:59:14', '2025-09-03 17:59:14'),
(547, 7, 4911, 'system', 'Douglas Hill Started the Meeting', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-09-03 17:59:39', '2025-09-03 17:59:39'),
(548, 7, 9790, 'system', 'Saffi Patino Joined the Meeting', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-09-03 18:00:00', '2025-09-03 18:00:00'),
(549, 7, 30, 'system', 'Kimberly Ann Madelo Joined the Meeting', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-09-03 18:00:19', '2025-09-03 18:00:19'),
(550, 7, 9803, 'system', 'Chelynn Joined the Meeting', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-09-03 18:01:32', '2025-09-03 18:01:32'),
(551, 7, 9799, 'system', 'Joy Cantancio Joined the Meeting', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-09-03 18:02:21', '2025-09-03 18:02:21'),
(552, 7, 9800, 'system', 'Jane Cantancio Joined the Meeting', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-09-03 18:02:46', '2025-09-03 18:02:46'),
(553, 7, 30, 'system', 'Kimberly Ann Madelo Left the Meeting.', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-09-03 19:37:13', '2025-09-03 19:37:13'),
(554, 7, 9799, 'system', 'Joy Cantancio Left the Meeting.', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-09-03 19:37:21', '2025-09-03 19:37:21'),
(559, 6, 9790, 'system', 'Saffi Patino Started the Meeting', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-09-04 06:28:51', '2025-09-04 06:28:51'),
(560, 6, 9787, 'system', 'Sofia Dima-ano Started the Meeting', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-09-04 06:30:16', '2025-09-04 06:30:16'),
(561, 6, 9806, 'system', 'Saffi Patiño Started the Meeting', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-09-04 06:32:01', '2025-09-04 06:32:01'),
(562, 6, 9806, 'system', 'Saffi Patiño Left the Meeting.', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-09-04 08:00:02', '2025-09-04 08:00:02'),
(563, 10, 30, 'text', 'Hello! Good morning. We will have our meeting here at 4 PM. See you in a bit. THANK YOU VERY MUCH!', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-09-04 15:54:49', '2025-09-04 15:54:49'),
(564, 10, 9801, 'text', 'Good morning', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-09-04 15:55:17', '2025-09-04 15:55:17'),
(565, 10, 30, 'system', 'Kimberly Ann Madelo Started the Meeting', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-09-04 16:00:33', '2025-09-04 16:00:33'),
(566, 10, 9790, 'system', 'Saffi Patino Joined the Meeting', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-09-04 16:01:09', '2025-09-04 16:01:09'),
(567, 10, 9801, 'system', 'Jing Beltran Joined the Meeting', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-09-04 16:01:19', '2025-09-04 16:01:19'),
(568, 10, 30, 'system', 'Kimberly Ann Madelo Left the Meeting.', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-09-04 16:28:04', '2025-09-04 16:28:04'),
(569, 10, 9790, 'system', 'Saffi Patino Left the Meeting.', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-09-04 16:39:58', '2025-09-04 16:39:58'),
(570, 7, 30, 'text', 'Hi! TEAM. I will call now.', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-09-04 18:00:11', '2025-09-04 18:00:11'),
(571, 7, 30, 'system', 'Kimberly Ann Madelo Joined the Meeting', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-09-04 18:00:15', '2025-09-04 18:00:15'),
(572, 7, 9803, 'system', 'Chelynn Joined the Meeting', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-09-04 18:02:11', '2025-09-04 18:02:11'),
(573, 7, 9799, 'system', 'Joy Cantancio Joined the Meeting', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-09-04 18:07:07', '2025-09-04 18:07:07'),
(574, 7, 9800, 'system', 'Jane Cantancio Joined the Meeting', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-09-04 18:07:27', '2025-09-04 18:07:27'),
(575, 7, 9799, 'text', NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-09-04 18:43:37', '2025-09-04 18:43:37'),
(576, 7, 30, 'system', 'Kimberly Ann Madelo Left the Meeting.', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-09-04 19:01:05', '2025-09-04 19:01:05'),
(577, 7, 9799, 'system', 'Joy Cantancio Left the Meeting.', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-09-04 19:01:17', '2025-09-04 19:01:17'),
(578, 7, 9799, 'text', NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-09-04 19:17:14', '2025-09-04 19:17:14'),
(579, 7, 9799, 'text', NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-09-04 21:06:22', '2025-09-04 21:06:22'),
(581, 39, 4911, 'text', 'Hi Darwin !&nbsp; How are you doing ?&nbsp; I am testing the avatar click feature :)&nbsp; Please reply when you see this message . Thank you !', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-09-05 10:41:03', '2025-09-05 10:41:03'),
(582, 2, 4911, 'text', 'I hope you are sleeping well - thank you for all your hard work dear :)', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-09-05 10:41:47', '2025-09-05 10:41:47'),
(583, 39, 9810, 'text', 'Hi sir goodmorning, Sorry for the late reply', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-09-05 13:27:16', '2025-09-05 13:27:16'),
(584, 39, 9810, 'text', 'I was checking and monitoring some other accounts&nbsp;', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-09-05 13:27:28', '2025-09-05 13:27:28'),
(585, 15, 30, 'text', 'Good afternoon, Ms. Cesilia. I would like to inform you that we have completed the last two files: the \"CCC Capability Statement\" and the \"CCC Flyer.\" I already uploaded the files to the client portal\'s file manager. Thank you, and have a good rest.', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-09-05 16:42:57', '2025-09-05 16:42:57'),
(596, 2, 9790, 'text', 'Thank you, and you\'re welcome too, dear.', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-09-06 04:31:39', '2025-09-06 04:31:39'),
(597, 40, 4911, 'text', 'Hi Kimberly&nbsp;', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-09-06 06:09:43', '2025-09-06 06:09:43'),
(598, 2, 9790, 'system', 'Saffi Patino Started the Meeting', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-09-06 06:10:40', '2025-09-06 06:10:40'),
(599, 41, 4911, 'text', 'Hello Saffi and Kimberly !&nbsp; &nbsp;let\'s meet !', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-09-06 06:13:59', '2025-09-06 06:13:59'),
(600, 41, 4911, 'system', 'Douglas Hill Started the Meeting', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-09-06 06:14:05', '2025-09-06 06:14:05'),
(601, 41, 9790, 'system', 'Saffi Patino Joined the Meeting', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-09-06 06:14:10', '2025-09-06 06:14:10'),
(602, 41, 9790, 'text', 'Ms. Kim please join!', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-09-06 06:15:01', '2025-09-06 06:15:01'),
(603, 41, 30, 'system', 'Kimberly Ann Madelo Joined the Meeting', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-09-06 06:16:40', '2025-09-06 06:16:40'),
(604, 41, 30, 'text', NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-09-06 06:22:58', '2025-09-06 06:22:58'),
(605, 41, 30, 'system', 'Kimberly Ann Madelo Left the Meeting.', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-09-06 06:24:44', '2025-09-06 06:24:44'),
(606, 41, 30, 'system', 'Kimberly Ann Madelo Joined the Meeting', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-09-06 06:24:48', '2025-09-06 06:24:48'),
(607, 41, 30, 'system', 'Kimberly Ann Madelo Left the Meeting.', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-09-06 07:27:10', '2025-09-06 07:27:10'),
(608, 16, 9790, 'text', '<span>Hello Directors &amp; Manager, we will have a meeting tomorrow at 10 am PH time, which is 7 pm Cali time. Thank you.</span>', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-09-07 06:14:54', '2025-09-07 06:14:54'),
(609, 16, 9790, 'text', 'hello, I start to call now.', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-09-07 18:59:25', '2025-09-07 18:59:25'),
(610, 16, 9790, 'system', 'Saffi Patino Started the Meeting', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-09-07 18:59:39', '2025-09-07 18:59:39'),
(611, 16, 30, 'system', 'Kimberly Ann Madelo Joined the Meeting', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-09-07 19:02:14', '2025-09-07 19:02:14'),
(612, 16, 30, 'system', 'Kimberly Ann Madelo Left the Meeting.', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-09-07 19:27:27', '2025-09-07 19:27:27'),
(613, 17, 9812, 'system', 'Luisa Patino Started the Meeting', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-09-08 05:46:58', '2025-09-08 05:46:58'),
(614, 17, 9790, 'system', 'Saffi Patino Started the Meeting', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-09-08 05:48:41', '2025-09-08 05:48:41'),
(615, 17, 9812, 'system', 'Luisa Patino Started the Meeting', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-09-08 05:49:03', '2025-09-08 05:49:03'),
(616, 17, 4911, 'system', 'Douglas Hill Joined the Meeting', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-09-08 05:50:24', '2025-09-08 05:50:24'),
(617, 17, 9790, 'system', 'Saffi Patino Left the Meeting.', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-09-08 05:51:53', '2025-09-08 05:51:53'),
(618, 17, 9790, 'system', 'Saffi Patino Left the Meeting.', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-09-08 05:52:29', '2025-09-08 05:52:29'),
(624, 17, 4911, 'system', 'Douglas Hill Left the Meeting.', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-09-08 06:08:34', '2025-09-08 06:08:34'),
(627, 26, 30, 'text', 'Hello TEAM. Are you all here already?', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-09-08 07:00:51', '2025-09-08 07:00:51'),
(628, 26, 30, 'text', '@Juan?', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-09-08 07:00:57', '2025-09-08 07:00:57'),
(629, 26, 30, 'system', 'Kimberly Ann Madelo Started the Meeting', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-09-08 07:02:00', '2025-09-08 07:02:00'),
(630, 26, 9803, 'system', 'Chelynn Joined the Meeting', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-09-08 07:02:09', '2025-09-08 07:02:09'),
(631, 26, 44, 'system', 'Juan Joined the Meeting', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-09-08 07:10:59', '2025-09-08 07:10:59'),
(632, 26, 9806, 'system', 'Saffi Patiño Joined the Meeting', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-09-08 07:19:29', '2025-09-08 07:19:29'),
(633, 26, 9810, 'system', 'Darwin Garote Joined the Meeting', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-09-08 07:22:53', '2025-09-08 07:22:53'),
(634, 26, 9810, 'system', 'Darwin Garote Left the Meeting.', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-09-08 07:23:11', '2025-09-08 07:23:11'),
(635, 26, 9810, 'system', 'Darwin Garote Joined the Meeting', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-09-08 07:23:15', '2025-09-08 07:23:15'),
(636, 26, 9810, 'system', 'Darwin Garote Left the Meeting.', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-09-08 07:23:21', '2025-09-08 07:23:21'),
(637, 26, 9810, 'system', 'Darwin Garote Joined the Meeting', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-09-08 07:23:36', '2025-09-08 07:23:36'),
(638, 26, 9810, 'system', 'Darwin Garote Left the Meeting.', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-09-08 07:25:50', '2025-09-08 07:25:50'),
(639, 26, 9810, 'system', 'Darwin Garote Joined the Meeting', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-09-08 07:32:25', '2025-09-08 07:32:25'),
(643, 26, 9810, 'system', 'Darwin Garote Left the Meeting.', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-09-08 08:11:08', '2025-09-08 08:11:08'),
(644, 26, 9806, 'system', 'Saffi Patiño Left the Meeting.', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-09-08 08:12:32', '2025-09-08 08:12:32'),
(645, 26, 9806, 'system', 'Saffi Patiño Joined the Meeting', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-09-08 08:12:36', '2025-09-08 08:12:36'),
(646, 26, 9810, 'system', 'Darwin Garote Joined the Meeting', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-09-08 08:17:44', '2025-09-08 08:17:44'),
(647, 26, 44, 'system', 'Juan Left the Meeting.', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-09-08 08:30:34', '2025-09-08 08:30:34'),
(648, 26, 9806, 'system', 'Saffi Patiño Left the Meeting.', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-09-08 08:30:49', '2025-09-08 08:30:49'),
(649, 26, 9810, 'system', 'Darwin Garote Left the Meeting.', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-09-08 08:31:08', '2025-09-08 08:31:08'),
(650, 26, 30, 'system', 'Kimberly Ann Madelo Left the Meeting.', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-09-08 08:32:46', '2025-09-08 08:32:46');
INSERT INTO `chat_messages` (`id`, `conversation_id`, `user_id`, `type`, `body`, `deleted_by_moderator`, `original_body`, `reply_to_message_id`, `forwarded_from_message_id`, `forwarded_metadata`, `meta`, `edited_at`, `deleted_at`, `created_at`, `updated_at`) VALUES
(651, 7, 30, 'text', 'Hi! everyone. I am going to call now.', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-09-08 18:00:48', '2025-09-08 18:00:48'),
(652, 7, 30, 'system', 'Kimberly Ann Madelo Joined the Meeting', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-09-08 18:01:15', '2025-09-08 18:01:15'),
(653, 7, 9801, 'system', 'Jing Beltran Joined the Meeting', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-09-08 18:01:23', '2025-09-08 18:01:23'),
(654, 7, 9803, 'system', 'Chelynn Joined the Meeting', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-09-08 18:02:05', '2025-09-08 18:02:05'),
(655, 7, 30, 'system', 'Kimberly Ann Madelo Left the Meeting.', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-09-08 19:19:03', '2025-09-08 19:19:03'),
(656, 6, 9787, 'system', 'Sofia Dima-ano Started the Meeting', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-09-09 06:32:28', '2025-09-09 06:32:28'),
(657, 6, 9806, 'system', 'Saffi Patiño Joined the Meeting', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-09-09 06:35:13', '2025-09-09 06:35:13'),
(658, 15, 30, 'text', 'Good morning, Ms. Cesilia, I hope your&nbsp; day is completely good. I wanted to update you on the tasks we have completed today from all the files you emailed last week. We have placed all the necessary information inside your client portal.', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-09-09 06:45:04', '2025-09-09 06:45:04'),
(659, 15, 30, 'text', 'If you would like to explore your PlanPanther CRM Portal, I would be happy to assist you and demonstrate all the features it offers. Please feel free to reach out to me if you have any questions or need clarification regarding the portal.', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-09-09 06:45:20', '2025-09-09 06:45:20'),
(660, 6, 9806, 'system', 'Saffi Patiño Left the Meeting.', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-09-09 06:45:52', '2025-09-09 06:45:52'),
(661, 6, 4911, 'system', 'Douglas Hill Joined the Meeting', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-09-09 06:47:48', '2025-09-09 06:47:48'),
(662, 6, 9790, 'system', 'Saffi Patino Joined the Meeting', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-09-09 06:48:57', '2025-09-09 06:48:57'),
(663, 6, 9806, 'system', 'Saffi Patiño Joined the Meeting', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-09-09 06:50:16', '2025-09-09 06:50:16'),
(664, 6, 4911, 'system', 'Douglas Hill Left the Meeting.', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-09-09 07:27:36', '2025-09-09 07:27:36'),
(665, 6, 9806, 'system', 'Saffi Patiño Left the Meeting.', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-09-09 07:27:52', '2025-09-09 07:27:52'),
(666, 6, 9787, 'system', 'Sofia Dima-ano Left the Meeting.', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-09-09 07:27:56', '2025-09-09 07:27:56'),
(667, 20, 9790, 'system', 'Saffi Patino Started the Meeting', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-09-09 14:26:53', '2025-09-09 14:26:53'),
(668, 20, 9790, 'system', 'Saffi Patino Joined the Meeting', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-09-09 14:27:07', '2025-09-09 14:27:07'),
(669, 20, 9813, 'system', 'Sita Cabeling Joined the Meeting', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-09-09 14:36:33', '2025-09-09 14:36:33'),
(670, 20, 9813, 'system', 'Sita Cabeling Left the Meeting.', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-09-09 14:57:30', '2025-09-09 14:57:30'),
(671, 7, 9803, 'system', 'Chelynn Joined the Meeting', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-09-09 18:01:03', '2025-09-09 18:01:03'),
(672, 7, 9803, 'system', 'Chelynn Left the Meeting.', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-09-09 18:01:38', '2025-09-09 18:01:38'),
(673, 7, 30, 'text', 'Sorry Guys. I\'ll call now.', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-09-09 18:02:08', '2025-09-09 18:02:08'),
(674, 7, 30, 'system', 'Kimberly Ann Madelo Joined the Meeting', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-09-09 18:02:15', '2025-09-09 18:02:15'),
(675, 7, 9801, 'system', 'Jing Beltran Joined the Meeting', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-09-09 18:02:47', '2025-09-09 18:02:47'),
(676, 7, 9803, 'system', 'Chelynn Joined the Meeting', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-09-09 18:02:56', '2025-09-09 18:02:56'),
(677, 7, 30, 'system', 'Kimberly Ann Madelo Joined the Meeting', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-09-09 18:03:19', '2025-09-09 18:03:19'),
(678, 7, 9803, 'system', 'Chelynn Left the Meeting.', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-09-09 19:05:40', '2025-09-09 19:05:40'),
(679, 7, 9801, 'system', 'Jing Beltran Left the Meeting.', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-09-09 19:05:46', '2025-09-09 19:05:46'),
(680, 7, 30, 'system', 'Kimberly Ann Madelo Left the Meeting.', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-09-09 19:06:23', '2025-09-09 19:06:23'),
(688, 7, 30, 'text', 'Hello Guys! I will start the call now.', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-09-10 18:00:33', '2025-09-10 18:00:33'),
(689, 7, 30, 'system', 'Kimberly Ann Madelo Started the Meeting', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-09-10 18:00:37', '2025-09-10 18:00:37'),
(690, 7, 9801, 'system', 'Jing Beltran Started the Meeting', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-09-10 18:02:10', '2025-09-10 18:02:10'),
(691, 7, 9803, 'system', 'Chelynn Joined the Meeting', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-09-10 18:22:08', '2025-09-10 18:22:08'),
(692, 7, 30, 'system', 'Kimberly Ann Madelo Left the Meeting.', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-09-10 19:09:21', '2025-09-10 19:09:21'),
(694, 6, 4911, 'system', 'Douglas Hill Started the Meeting', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-09-11 06:32:47', '2025-09-11 06:32:47'),
(695, 6, 9787, 'system', 'Sofia Dima-ano Left the Meeting.', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-09-11 06:33:41', '2025-09-11 06:33:41'),
(696, 6, 9787, 'system', 'Sofia Dima-ano Started the Meeting', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-09-11 06:33:43', '2025-09-11 06:33:43'),
(697, 6, 9790, 'system', 'Saffi Patino Joined the Meeting', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-09-11 06:34:06', '2025-09-11 06:34:06'),
(698, 6, 9806, 'system', 'Saffi Patiño Joined the Meeting', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-09-11 06:36:15', '2025-09-11 06:36:15'),
(699, 6, 4911, 'system', 'Douglas Hill Left the Meeting.', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-09-11 07:32:08', '2025-09-11 07:32:08'),
(700, 6, 9806, 'system', 'Saffi Patiño Left the Meeting.', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-09-11 07:32:28', '2025-09-11 07:32:28'),
(701, 10, 30, 'text', 'Hello! Everyone. I will call now.', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-09-11 16:00:27', '2025-09-11 16:00:27'),
(702, 10, 30, 'system', 'Kimberly Ann Madelo Started the Meeting', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-09-11 16:01:23', '2025-09-11 16:01:23'),
(703, 10, 9801, 'system', 'Jing Beltran Joined the Meeting', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-09-11 16:02:43', '2025-09-11 16:02:43'),
(704, 10, 9790, 'system', 'Saffi Patino Joined the Meeting', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-09-11 16:04:42', '2025-09-11 16:04:42'),
(705, 10, 30, 'system', 'Kimberly Ann Madelo Left the Meeting.', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-09-11 16:32:44', '2025-09-11 16:32:44'),
(706, 10, 4911, 'system', 'Douglas Hill Started the Meeting', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-09-11 16:33:30', '2025-09-11 16:33:30'),
(707, 6, 9790, 'system', 'Saffi Patino Started the Meeting', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-09-11 16:34:46', '2025-09-11 16:34:46'),
(708, 6, 9790, 'system', 'Saffi Patino Joined the Meeting', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-09-11 16:35:01', '2025-09-11 16:35:01'),
(709, 10, 4911, 'system', 'Douglas Hill Left the Meeting.', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-09-11 16:35:27', '2025-09-11 16:35:27'),
(710, 6, 9790, 'text', 'Guys, please join the meeting now.', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-09-11 16:35:34', '2025-09-11 16:35:34'),
(711, 6, 4911, 'system', 'Douglas Hill Started the Meeting', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-09-11 16:36:00', '2025-09-11 16:36:00'),
(712, 6, 4911, 'system', 'Douglas Hill Left the Meeting.', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-09-11 16:39:47', '2025-09-11 16:39:47'),
(713, 6, 4911, 'text', 'are we in this meeting room ??', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-09-11 16:40:05', '2025-09-11 16:40:05'),
(714, 6, 30, 'system', 'Kimberly Ann Madelo Joined the Meeting', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-09-11 16:40:15', '2025-09-11 16:40:15'),
(715, 40, 4911, 'text', 'Hi Kimberly,&nbsp; are you here ?', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-09-11 16:41:17', '2025-09-11 16:41:17'),
(716, 6, 4911, 'system', 'Douglas Hill Joined the Meeting', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-09-11 16:42:27', '2025-09-11 16:42:27'),
(717, 6, 9787, 'system', 'Sofia Dima-ano Joined the Meeting', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-09-11 16:43:20', '2025-09-11 16:43:20'),
(718, 6, 9814, 'system', 'Marc Sabaulan Joined the Meeting', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-09-11 16:44:25', '2025-09-11 16:44:25'),
(719, 6, 9801, 'system', 'Jing Beltran Joined the Meeting', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-09-11 16:44:29', '2025-09-11 16:44:29'),
(720, 6, 9806, 'system', 'Saffi Patiño Joined the Meeting', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-09-11 16:48:08', '2025-09-11 16:48:08'),
(721, 6, 9814, 'system', 'Marc Sabaulan Joined the Meeting', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-09-11 17:00:42', '2025-09-11 17:00:42'),
(722, 6, 30, 'text', NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-09-11 17:04:53', '2025-09-11 17:04:53'),
(723, 6, 4911, 'system', 'Douglas Hill Left the Meeting.', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-09-11 17:55:22', '2025-09-11 17:55:22'),
(724, 7, 30, 'system', 'Kimberly Ann Madelo Joined the Meeting', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-09-11 18:00:09', '2025-09-11 18:00:09'),
(725, 7, 9803, 'system', 'Chelynn Joined the Meeting', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-09-11 18:01:41', '2025-09-11 18:01:41'),
(726, 7, 9801, 'system', 'Jing Beltran Joined the Meeting', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-09-11 18:01:43', '2025-09-11 18:01:43'),
(727, 6, 9806, 'system', 'Saffi Patiño Left the Meeting.', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-09-11 18:02:26', '2025-09-11 18:02:26'),
(728, 7, 9799, 'system', 'Joy Cantancio Joined the Meeting', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-09-11 18:04:04', '2025-09-11 18:04:04'),
(729, 7, 9800, 'system', 'Jane Cantancio Joined the Meeting', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-09-11 18:05:10', '2025-09-11 18:05:10'),
(730, 7, 9799, 'system', 'Joy Cantancio Left the Meeting.', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-09-11 19:15:47', '2025-09-11 19:15:47'),
(731, 7, 30, 'system', 'Kimberly Ann Madelo Left the Meeting.', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-09-11 19:15:50', '2025-09-11 19:15:50'),
(763, 6, 4911, 'system', 'Douglas Hill Joined the Meeting', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-09-13 06:28:36', '2025-09-13 06:28:36'),
(764, 6, 4911, 'system', 'Douglas Hill Left the Meeting.', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-09-13 06:28:53', '2025-09-13 06:28:53'),
(765, 6, 4911, 'text', 'Hello Marketing Team; just checking ... did we get the Ace High campaign emails all out ?', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-09-13 06:30:28', '2025-09-13 06:30:28'),
(766, 17, 4911, 'text', 'Helloooo&nbsp; :)', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-09-15 05:52:12', '2025-09-15 05:52:12'),
(767, 17, 9790, 'system', 'Saffi Patino Joined the Meeting', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-09-15 05:52:48', '2025-09-15 05:52:48'),
(768, 17, 4911, 'text', 'Hi Saffi, fancy meeting you here :)&nbsp;', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-09-15 05:53:13', '2025-09-15 05:53:13'),
(769, 17, 4911, 'system', 'Douglas Hill Joined the Meeting', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-09-15 05:53:49', '2025-09-15 05:53:49'),
(786, 26, 30, 'system', 'Kimberly Ann Madelo Started the Meeting', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-09-15 07:04:49', '2025-09-15 07:04:49'),
(787, 26, 9803, 'system', 'Chelynn Joined the Meeting', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-09-15 07:05:05', '2025-09-15 07:05:05'),
(792, 26, 30, 'system', 'Kimberly Ann Madelo Left the Meeting.', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-09-15 08:03:48', '2025-09-15 08:03:48'),
(793, 50, 9790, 'text', '<span>Hello Everyone, Mr. Douglas wants a meeting tomorrow at 4pm, California time for Kimberly\'s team and Sofia\'s team with special participation of Michelle Kalafatis. Attendance is a MUST!</span>', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-09-15 16:06:06', '2025-09-15 16:06:06'),
(794, 7, 30, 'text', 'I will call now.', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-09-15 18:00:48', '2025-09-15 18:00:48'),
(795, 7, 30, 'system', 'Kimberly Ann Madelo Started the Meeting', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-09-15 18:00:50', '2025-09-15 18:00:50'),
(796, 7, 9803, 'system', 'Chelynn Joined the Meeting', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-09-15 18:01:09', '2025-09-15 18:01:09'),
(797, 7, 9803, 'system', 'Chelynn Left the Meeting.', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-09-15 18:01:31', '2025-09-15 18:01:31'),
(798, 7, 9803, 'system', 'Chelynn Joined the Meeting', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-09-15 18:01:41', '2025-09-15 18:01:41'),
(799, 7, 9801, 'system', 'Jing Beltran Joined the Meeting', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-09-15 18:01:51', '2025-09-15 18:01:51'),
(800, 7, 9803, 'system', 'Chelynn Left the Meeting.', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-09-15 18:02:25', '2025-09-15 18:02:25'),
(801, 7, 9801, 'text', 'asa naku ahahhaahah', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-09-15 18:02:33', '2025-09-15 18:02:33'),
(802, 7, 9803, 'text', 'ako pud ako ra isa', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-09-15 18:02:52', '2025-09-15 18:02:52'),
(803, 7, 9801, 'text', 'hahahahah', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-09-15 18:03:01', '2025-09-15 18:03:01'),
(804, 7, 9801, 'system', 'Jing Beltran Joined the Meeting', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-09-15 18:03:07', '2025-09-15 18:03:07'),
(805, 7, 30, 'text', 'Ga loading akoa screen', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-09-15 18:03:28', '2025-09-15 18:03:28'),
(806, 7, 30, 'text', 'Please share link na lang sa', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-09-15 18:03:39', '2025-09-15 18:03:39'),
(807, 7, 9801, 'text', 'ako ra isa lage', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-09-15 18:03:44', '2025-09-15 18:03:44'),
(808, 7, 9803, 'text', 'ni out nako share miss jing', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-09-15 18:04:00', '2025-09-15 18:04:00'),
(809, 7, 9801, 'text', '<a href=\"https://meet.hillbcs.com/Portal%20Training%20%20%20Navigation%20Team%20Meeting%20ZAVPsOPpa5GIb4Cb7EizxHXAlv7YduOqfYqeHIZD7\" rel=\"noopener noreferrer\" target=\"_blank\">Portal Training Navigation Team Meeting ZAV Ps O Ppa 5 G Ib 4 Cb 7 Eizx HX Alv 7 Ydu Oqf Yqe HIZD 7 | Hill Business Consulting Services</a>', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-09-15 18:04:13', '2025-09-15 18:04:13'),
(810, 7, 9803, 'text', '3 nalang gani ta di pajud mag tagbo haha', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-09-15 18:04:32', '2025-09-15 18:04:32'),
(811, 7, 30, 'text', 'Unsa mana imoha g share Jing?', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-09-15 18:04:48', '2025-09-15 18:04:48'),
(812, 7, 30, 'text', 'Dili ma paste dri?', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-09-15 18:04:57', '2025-09-15 18:04:57'),
(813, 7, 9803, 'text', '<a href=\"https://meet.hillbcs.com/Portal%20Training%20%20%20Navigation%20Team%20Meeting%20ZAVPsOPpa5GIb4Cb7EizxHXAlv7YduOqfYqeHIZD7\" rel=\"noopener noreferrer\" target=\"_blank\">https://meet.hillbcs.com/Portal%20Training%20%20%20Navigation%20Team%20Meeting%20ZAVPsOPpa5GIb4Cb7EizxHXAlv7YduOqfYqeHIZD7</a>', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-09-15 18:05:26', '2025-09-15 18:05:26'),
(814, 7, 30, 'system', 'Kimberly Ann Madelo Left the Meeting.', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-09-15 18:05:39', '2025-09-15 18:05:39'),
(815, 7, 30, 'system', 'Kimberly Ann Madelo Started the Meeting', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-09-15 18:07:06', '2025-09-15 18:07:06'),
(816, 7, 30, 'system', 'Kimberly Ann Madelo Left the Meeting.', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-09-15 18:07:28', '2025-09-15 18:07:28'),
(817, 7, 30, 'system', 'Kimberly Ann Madelo Left the Meeting.', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-09-15 18:07:37', '2025-09-15 18:07:37'),
(818, 7, 9803, 'text', 'hala why', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-09-15 18:08:27', '2025-09-15 18:08:27'),
(819, 7, 9803, 'text', 'nagkita na baya mi ni iss jing te', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-09-15 18:08:42', '2025-09-15 18:08:42'),
(820, 7, 30, 'text', 'Nah. gatuyok akoa system', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-09-15 18:10:10', '2025-09-15 18:10:10'),
(821, 7, 9803, 'text', 'ni out mi te', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-09-15 18:10:32', '2025-09-15 18:10:32'),
(822, 7, 9803, 'text', 'call daw balik', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-09-15 18:10:38', '2025-09-15 18:10:38'),
(823, 7, 30, 'system', 'Kimberly Ann Madelo Started the Meeting', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-09-15 18:11:28', '2025-09-15 18:11:28'),
(824, 7, 9801, 'system', 'Jing Beltran Joined the Meeting', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-09-15 18:11:43', '2025-09-15 18:11:43'),
(825, 7, 30, 'text', NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-09-15 18:11:50', '2025-09-15 18:11:50'),
(826, 7, 9803, 'system', 'Chelynn Joined the Meeting', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-09-15 18:11:56', '2025-09-15 18:11:56'),
(827, 7, 30, 'system', 'Kimberly Ann Madelo Left the Meeting.', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-09-15 18:12:13', '2025-09-15 18:12:13'),
(828, 7, 9803, 'text', 'naa nami te nisulod nami', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-09-15 18:12:28', '2025-09-15 18:12:28'),
(829, 7, 30, 'text', 'Proceed na mo Che sa Nivigation. Aron dili ta malangay', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-09-15 18:12:42', '2025-09-15 18:12:42'),
(830, 7, 30, 'system', 'Kimberly Ann Madelo Left the Meeting.', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-09-15 18:12:55', '2025-09-15 18:12:55'),
(831, 7, 30, 'text', 'Same thingh lang saakoa end', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-09-15 18:13:05', '2025-09-15 18:13:05'),
(832, 7, 30, 'system', 'Kimberly Ann Madelo Joined the Meeting', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-09-15 18:13:22', '2025-09-15 18:13:22'),
(833, 7, 30, 'system', 'Kimberly Ann Madelo Left the Meeting.', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-09-15 18:13:32', '2025-09-15 18:13:32'),
(834, 7, 30, 'system', 'Kimberly Ann Madelo Joined the Meeting', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-09-15 18:19:10', '2025-09-15 18:19:10'),
(835, 7, 30, 'system', 'Kimberly Ann Madelo Left the Meeting.', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-09-15 18:19:19', '2025-09-15 18:19:19'),
(836, 7, 30, 'system', 'Kimberly Ann Madelo Joined the Meeting', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-09-15 18:21:47', '2025-09-15 18:21:47'),
(837, 7, 30, 'text', 'Still, White screen and loading!!!!!!!!!!!!!!!!!!!!!!!!!!', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-09-15 18:22:22', '2025-09-15 18:22:22'),
(838, 7, 30, 'text', NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-09-15 18:22:50', '2025-09-15 18:22:50'),
(839, 7, 30, 'system', 'Kimberly Ann Madelo Left the Meeting.', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-09-15 18:25:28', '2025-09-15 18:25:28'),
(840, 7, 30, 'system', 'Kimberly Ann Madelo Joined the Meeting', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-09-15 18:32:00', '2025-09-15 18:32:00'),
(841, 7, 30, 'system', 'Kimberly Ann Madelo Left the Meeting.', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-09-15 18:32:24', '2025-09-15 18:32:24'),
(842, 7, 30, 'system', 'Kimberly Ann Madelo Joined the Meeting', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-09-15 18:42:18', '2025-09-15 18:42:18'),
(843, 7, 30, 'system', 'Kimberly Ann Madelo Left the Meeting.', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-09-15 18:43:19', '2025-09-15 18:43:19'),
(844, 7, 30, 'system', 'Kimberly Ann Madelo Joined the Meeting', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-09-15 18:48:27', '2025-09-15 18:48:27'),
(845, 7, 30, 'system', 'Kimberly Ann Madelo Left the Meeting.', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-09-15 18:50:20', '2025-09-15 18:50:20'),
(846, 7, 9803, 'system', 'Chelynn Left the Meeting.', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-09-15 19:03:19', '2025-09-15 19:03:19'),
(847, 6, 9787, 'system', 'Sofia Dima-ano Joined the Meeting', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-09-16 06:33:47', '2025-09-16 06:33:47'),
(848, 6, 9787, 'system', 'Sofia Dima-ano Joined the Meeting', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-09-16 07:01:52', '2025-09-16 07:01:52'),
(849, 6, 9787, 'system', 'Sofia Dima-ano Left the Meeting.', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-09-16 07:03:28', '2025-09-16 07:03:28'),
(850, 6, 9787, 'system', 'Sofia Dima-ano Joined the Meeting', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-09-16 07:03:40', '2025-09-16 07:03:40'),
(851, 6, 9806, 'system', 'Saffi Patiño Joined the Meeting', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-09-16 07:06:59', '2025-09-16 07:06:59'),
(852, 6, 9790, 'system', 'Saffi Patino Joined the Meeting', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-09-16 07:14:59', '2025-09-16 07:14:59'),
(853, 6, 9787, 'system', 'Sofia Dima-ano Joined the Meeting', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-09-16 08:01:24', '2025-09-16 08:01:24'),
(854, 6, 9787, 'system', 'Sofia Dima-ano Left the Meeting.', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-09-16 08:01:35', '2025-09-16 08:01:35'),
(855, 6, 9806, 'system', 'Saffi Patiño Left the Meeting.', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-09-16 08:01:46', '2025-09-16 08:01:46'),
(856, 6, 9787, 'system', 'Sofia Dima-ano Joined the Meeting', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-09-16 08:01:47', '2025-09-16 08:01:47'),
(857, 6, 9787, 'system', 'Sofia Dima-ano Left the Meeting.', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-09-16 08:02:00', '2025-09-16 08:02:00'),
(858, 20, 9790, 'text', 'Good morning everyone, let\'s do a call now!', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-09-16 14:28:08', '2025-09-16 14:28:08'),
(859, 20, 9790, 'system', 'Saffi Patino Started the Meeting', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-09-16 14:28:13', '2025-09-16 14:28:13'),
(860, 20, 9813, 'system', 'Sita Cabeling Started the Meeting', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-09-16 14:28:24', '2025-09-16 14:28:24'),
(861, 20, 4911, 'system', 'Douglas Hill Joined the Meeting', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-09-16 14:35:22', '2025-09-16 14:35:22'),
(862, 20, 4911, 'system', 'Douglas Hill Left the Meeting.', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-09-16 14:58:59', '2025-09-16 14:58:59'),
(863, 20, 9813, 'system', 'Sita Cabeling Left the Meeting.', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-09-16 14:59:16', '2025-09-16 14:59:16'),
(864, 50, 9790, 'text', 'Hello everyone, good morning.', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-09-16 15:57:51', '2025-09-16 15:57:51'),
(865, 50, 9801, 'text', 'Good morning', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-09-16 15:58:19', '2025-09-16 15:58:19'),
(866, 50, 9790, 'system', 'Saffi Patino Started the Meeting', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-09-16 15:59:07', '2025-09-16 15:59:07'),
(867, 50, 9790, 'system', 'Saffi Patino Joined the Meeting', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-09-16 15:59:08', '2025-09-16 15:59:08'),
(868, 50, 9801, 'system', 'Jing Beltran Joined the Meeting', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-09-16 15:59:31', '2025-09-16 15:59:31'),
(869, 50, 30, 'text', 'Good morning!', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-09-16 15:59:40', '2025-09-16 15:59:40'),
(870, 50, 30, 'system', 'Kimberly Ann Madelo Joined the Meeting', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-09-16 15:59:44', '2025-09-16 15:59:44'),
(871, 6, 9806, 'system', 'Saffi Patiño Started the Meeting', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-09-16 16:01:50', '2025-09-16 16:01:50'),
(872, 6, 9806, 'system', 'Saffi Patiño Joined the Meeting', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-09-16 16:02:28', '2025-09-16 16:02:28'),
(873, 6, 9806, 'system', 'Saffi Patiño Left the Meeting.', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-09-16 16:02:58', '2025-09-16 16:02:58'),
(874, 6, 9806, 'system', 'Saffi Patiño Joined the Meeting', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-09-16 16:03:01', '2025-09-16 16:03:01'),
(875, 6, 9806, 'system', 'Saffi Patiño Left the Meeting.', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-09-16 16:03:54', '2025-09-16 16:03:54'),
(876, 6, 9806, 'system', 'Saffi Patiño Started the Meeting', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-09-16 16:05:12', '2025-09-16 16:05:12'),
(877, 6, 9806, 'system', 'Saffi Patiño Left the Meeting.', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-09-16 16:05:38', '2025-09-16 16:05:38'),
(878, 50, 9790, 'text', '<a href=\"https://meet.hillbcs.com/Combine%20Team_Kimberly%20s%20%20%20Sofia%20s%20Team%20Meeting%20ZAVPsOPpa5GIb4Cb7EizxHXAlv7YduOqfYqeHIZD50\" rel=\"noopener noreferrer\" target=\"_blank\">https://meet.hillbcs.com/Combine%20Team_Kimberly%20s%20%20%20Sofia%20s%20Team%20Meeting%20ZAVPsOPpa5GIb4Cb7EizxHXAlv7YduOqfYqeHIZD50</a>', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-09-16 16:07:18', '2025-09-16 16:07:18'),
(879, 2, 9790, 'text', '<a href=\"https://meet.hillbcs.com/Combine%20Team_Kimberly%20s%20%20%20Sofia%20s%20Team%20Meeting%20ZAVPsOPpa5GIb4Cb7EizxHXAlv7YduOqfYqeHIZD50\" rel=\"noopener noreferrer\" target=\"_blank\">https://meet.hillbcs.com/Combine%20Team_Kimberly%20s%20%20%20Sofia%20s%20Team%20Meeting%20ZAVPsOPpa5GIb4Cb7EizxHXAlv7YduOqfYqeHIZD50</a>', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-09-16 16:07:43', '2025-09-16 16:07:43'),
(880, 50, 4911, 'system', 'Douglas Hill Joined the Meeting', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-09-16 16:07:59', '2025-09-16 16:07:59'),
(881, 51, 9790, 'text', '<a href=\"https://meet.hillbcs.com/Combine%20Team_Kimberly%20s%20%20%20Sofia%20s%20Team%20Meeting%20ZAVPsOPpa5GIb4Cb7EizxHXAlv7YduOqfYqeHIZD50\" rel=\"noopener noreferrer\" target=\"_blank\">https://meet.hillbcs.com/Combine%20Team_Kimberly%20s%20%20%20Sofia%20s%20Team%20Meeting%20ZAVPsOPpa5GIb4Cb7EizxHXAlv7YduOqfYqeHIZD50</a>', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-09-16 16:09:03', '2025-09-16 16:09:03'),
(882, 51, 9790, 'text', 'hello Michelle, please click the link above for our meeting today. Thank you', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-09-16 16:10:02', '2025-09-16 16:10:02'),
(883, 7, 9803, 'system', 'Chelynn Joined the Meeting', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-09-16 18:00:21', '2025-09-16 18:00:21'),
(884, 7, 9803, 'system', 'Chelynn Left the Meeting.', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-09-16 18:00:34', '2025-09-16 18:00:34'),
(885, 7, 30, 'text', 'I will call now', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-09-16 18:00:57', '2025-09-16 18:00:57'),
(886, 7, 30, 'system', 'Kimberly Ann Madelo Joined the Meeting', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-09-16 18:01:00', '2025-09-16 18:01:00'),
(887, 7, 30, 'system', 'Kimberly Ann Madelo Left the Meeting.', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-09-16 18:01:09', '2025-09-16 18:01:09'),
(888, 7, 30, 'system', 'Kimberly Ann Madelo Joined the Meeting', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-09-16 18:01:11', '2025-09-16 18:01:11'),
(889, 7, 9803, 'system', 'Chelynn Joined the Meeting', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-09-16 18:01:20', '2025-09-16 18:01:20'),
(890, 7, 9801, 'system', 'Jing Beltran Joined the Meeting', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-09-16 18:01:22', '2025-09-16 18:01:22'),
(891, 7, 9803, 'text', 'why nga mahimong ako ang moderator?', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-09-16 18:01:48', '2025-09-16 18:01:48'),
(892, 7, 30, 'text', 'I am in the call now', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-09-16 18:01:58', '2025-09-16 18:01:58'),
(893, 7, 9803, 'system', 'Chelynn Joined the Meeting', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-09-16 18:01:58', '2025-09-16 18:01:58'),
(894, 7, 30, 'system', 'Kimberly Ann Madelo Left the Meeting.', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-09-16 18:27:09', '2025-09-16 18:27:09'),
(895, 7, 30, 'system', 'Kimberly Ann Madelo Joined the Meeting', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-09-16 18:27:16', '2025-09-16 18:27:16'),
(896, 7, 9801, 'system', 'Jing Beltran Left the Meeting.', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-09-16 19:02:36', '2025-09-16 19:02:36'),
(897, 7, 30, 'system', 'Kimberly Ann Madelo Left the Meeting.', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-09-16 19:02:38', '2025-09-16 19:02:38'),
(904, 7, 30, 'text', 'Good morning! I will start the call in a bit.', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-09-17 17:58:44', '2025-09-17 17:58:44'),
(905, 7, 30, 'system', 'Kimberly Ann Madelo Started the Meeting', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-09-17 18:01:05', '2025-09-17 18:01:05'),
(906, 7, 4911, 'system', 'Douglas Hill Joined the Meeting', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-09-17 18:01:19', '2025-09-17 18:01:19'),
(907, 7, 9801, 'system', 'Jing Beltran Joined the Meeting', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-09-17 18:01:35', '2025-09-17 18:01:35'),
(908, 7, 9803, 'system', 'Chelynn Joined the Meeting', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-09-17 18:01:46', '2025-09-17 18:01:46'),
(909, 7, 9798, 'system', 'Development Team Joined the Meeting', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-09-17 18:14:13', '2025-09-17 18:14:13'),
(910, 7, 4911, 'system', 'Douglas Hill Left the Meeting.', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-09-17 19:07:41', '2025-09-17 19:07:41'),
(911, 6, 4911, 'text', 'Hello! :)&nbsp; There will be a Marketing meeting in about 5 min.&nbsp; It will focus on updates', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-09-18 06:26:00', '2025-09-18 06:26:00'),
(912, 6, 9787, 'system', 'Sofia Dima-ano Started the Meeting', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-09-18 06:29:34', '2025-09-18 06:29:34'),
(913, 6, 4911, 'system', 'Douglas Hill Joined the Meeting', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-09-18 06:29:51', '2025-09-18 06:29:51'),
(914, 6, 9790, 'system', 'Saffi Patino Joined the Meeting', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-09-18 06:29:51', '2025-09-18 06:29:51'),
(915, 6, 9806, 'system', 'Saffi Patiño Joined the Meeting', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-09-18 06:30:45', '2025-09-18 06:30:45'),
(916, 6, 4911, 'system', 'Douglas Hill Left the Meeting.', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-09-18 07:19:28', '2025-09-18 07:19:28'),
(917, 6, 9806, 'system', 'Saffi Patiño Left the Meeting.', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-09-18 07:19:44', '2025-09-18 07:19:44'),
(918, 54, 30, 'text', 'Hello Dar. Try daw click ang + New&nbsp; Icon sa taas tapos ayha etype iyaha name', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-09-18 07:22:18', '2025-09-18 07:22:18'),
(919, 54, 9810, 'text', 'ok na maam kim&nbsp;', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-09-18 08:03:19', '2025-09-18 08:03:19'),
(920, 54, 9810, 'text', 'ni appear na', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-09-18 08:03:24', '2025-09-18 08:03:24'),
(921, 54, 9810, 'text', 'unsa akoa i message sa iya, haha makulbaan ko na maulaw haha', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-09-18 08:03:37', '2025-09-18 08:03:37'),
(922, 54, 30, 'text', 'Just greet him then Inroduce your name Dar then tell him na ikaaw ga follow ups sa iyaha mga projects', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-09-18 09:28:02', '2025-09-18 09:28:02'),
(923, 54, 30, 'text', 'Dayon igna \"I would just like to inform you of the updates so far, then ayha na latag sa status sa follow ups', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-09-18 09:29:49', '2025-09-18 09:29:49'),
(924, 54, 30, 'text', 'Like for exaample. Pila na imoha na reach out and na sendan og proposal tapos pila ang nag response ana lang.', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-09-18 09:30:39', '2025-09-18 09:30:39'),
(925, 54, 9810, 'text', 'kerek', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-09-18 09:35:42', '2025-09-18 09:35:42'),
(926, 55, 9810, 'text', 'Hi sir, Goodmorning. I am Darwin Garote sir. One of the virtual assistant. I was actually the one who do follow ups on your projects. For those projects posted that is already proposal sent. :) Have a nice day ahead', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-09-18 09:37:51', '2025-09-18 09:37:51'),
(927, 54, 9810, 'text', NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-09-18 09:38:06', '2025-09-18 09:38:06'),
(928, 39, 9810, 'text', 'goodmorning sir&nbsp;', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-09-18 13:55:43', '2025-09-18 13:55:43'),
(929, 56, 9810, 'text', 'Goodmorning maam&nbsp;', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-09-18 14:28:28', '2025-09-18 14:28:28'),
(930, 39, 9810, 'text', NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-09-18 15:17:44', '2025-09-18 15:17:44'),
(931, 39, 9810, 'text', 'Logging out sir&nbsp;', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-09-18 15:17:49', '2025-09-18 15:17:49'),
(932, 56, 9810, 'text', NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-09-18 15:17:54', '2025-09-18 15:17:54'),
(933, 56, 9810, 'text', 'Logging out maam on my 4th shift. already posted my report on Ms teams. Goodnyt&nbsp;', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-09-18 15:18:13', '2025-09-18 15:18:13'),
(934, 10, 30, 'text', 'Good morning! I will start the call in a bit. THANK YOU!', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-09-18 15:59:06', '2025-09-18 15:59:06'),
(935, 10, 30, 'system', 'Kimberly Ann Madelo Started the Meeting', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-09-18 16:00:21', '2025-09-18 16:00:21'),
(936, 10, 9801, 'system', 'Jing Beltran Joined the Meeting', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-09-18 16:00:32', '2025-09-18 16:00:32'),
(937, 10, 9790, 'system', 'Saffi Patino Started the Meeting', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-09-18 16:26:32', '2025-09-18 16:26:32'),
(938, 7, 30, 'text', 'Hello everyone!. I will call now.', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-09-18 18:00:44', '2025-09-18 18:00:44'),
(939, 7, 30, 'system', 'Kimberly Ann Madelo Joined the Meeting', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-09-18 18:00:47', '2025-09-18 18:00:47'),
(940, 7, 9801, 'system', 'Jing Beltran Joined the Meeting', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-09-18 18:01:28', '2025-09-18 18:01:28'),
(941, 7, 9803, 'system', 'Chelynn Joined the Meeting', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-09-18 18:02:21', '2025-09-18 18:02:21'),
(949, 56, 9810, 'text', 'Goodmorning maam saff', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-09-19 07:04:55', '2025-09-19 07:04:55'),
(950, 56, 9810, 'text', NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-09-19 07:05:24', '2025-09-19 07:05:24'),
(951, 56, 9810, 'text', 'Logging in on my last shift for the week maam&nbsp;', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-09-19 07:06:55', '2025-09-19 07:06:55'),
(952, 39, 9810, 'text', NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-09-19 07:33:05', '2025-09-19 07:33:05'),
(953, 39, 9810, 'text', 'just logged in sir 27 minutes ago for my last shift this week sir. Have a blessed day ahed', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-09-19 07:33:29', '2025-09-19 07:33:29'),
(955, 2, 4911, 'system', 'Douglas Hill Started the Meeting', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-09-20 06:24:32', '2025-09-20 06:24:32'),
(956, 2, 9790, 'system', 'Saffi Patino Joined the Meeting', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-09-20 06:25:22', '2025-09-20 06:25:22'),
(957, 2, 4911, 'system', 'Douglas Hill Left the Meeting.', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-09-20 06:27:40', '2025-09-20 06:27:40'),
(958, 2, 9790, 'system', 'Saffi Patino Started the Meeting', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-09-20 06:29:19', '2025-09-20 06:29:19'),
(959, 2, 4911, 'system', 'Douglas Hill Joined the Meeting', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-09-20 06:29:32', '2025-09-20 06:29:32'),
(960, 16, 9790, 'text', '<span>Hello Directors &amp; Manager, we will have a meeting tomorrow at 10 am PH time, which is 7 pm Cali time. Thank you.</span>', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-09-21 06:07:12', '2025-09-21 06:07:12'),
(961, 41, 9790, 'system', 'Saffi Patino Started the Meeting', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-09-21 18:37:47', '2025-09-21 18:37:47'),
(962, 41, 30, 'system', 'Kimberly Ann Madelo Started the Meeting', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-09-21 18:38:19', '2025-09-21 18:38:19'),
(963, 41, 4911, 'system', 'Douglas Hill Started the Meeting', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-09-21 18:38:29', '2025-09-21 18:38:29'),
(964, 41, 9790, 'system', 'Saffi Patino Left the Meeting.', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-09-21 18:54:46', '2025-09-21 18:54:46'),
(965, 41, 9790, 'system', 'Saffi Patino Started the Meeting', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-09-21 18:55:15', '2025-09-21 18:55:15'),
(966, 16, 9790, 'system', 'Saffi Patino Started the Meeting', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-09-21 19:00:31', '2025-09-21 19:00:31'),
(967, 16, 9790, 'system', 'Saffi Patino Started the Meeting', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-09-21 19:04:20', '2025-09-21 19:04:20'),
(968, 41, 30, 'system', 'Kimberly Ann Madelo Left the Meeting.', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-09-21 19:04:36', '2025-09-21 19:04:36'),
(969, 16, 9813, 'system', 'Sita Cabeling Joined the Meeting', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-09-21 19:04:38', '2025-09-21 19:04:38'),
(970, 41, 4911, 'system', 'Douglas Hill Left the Meeting.', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-09-21 19:05:23', '2025-09-21 19:05:23'),
(971, 16, 4911, 'system', 'Douglas Hill Joined the Meeting', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-09-21 19:05:47', '2025-09-21 19:05:47'),
(972, 16, 9787, 'system', 'Sofia Dima-ano Joined the Meeting', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-09-21 19:23:41', '2025-09-21 19:23:41'),
(973, 16, 9787, 'system', 'Sofia Dima-ano Joined the Meeting', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-09-21 19:24:00', '2025-09-21 19:24:00'),
(974, 16, 9787, 'system', 'Sofia Dima-ano Joined the Meeting', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-09-21 19:24:06', '2025-09-21 19:24:06'),
(975, 16, 9787, 'system', 'Sofia Dima-ano Left the Meeting.', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-09-21 19:24:09', '2025-09-21 19:24:09'),
(976, 16, 9787, 'system', 'Sofia Dima-ano Joined the Meeting', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-09-21 19:24:11', '2025-09-21 19:24:11'),
(977, 16, 9787, 'system', 'Sofia Dima-ano Left the Meeting.', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-09-21 19:24:40', '2025-09-21 19:24:40'),
(978, 16, 9787, 'system', 'Sofia Dima-ano Joined the Meeting', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-09-21 19:24:41', '2025-09-21 19:24:41'),
(979, 16, 4911, 'system', 'Douglas Hill Left the Meeting.', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-09-21 19:31:34', '2025-09-21 19:31:34'),
(980, 16, 30, 'system', 'Kimberly Ann Madelo Left the Meeting.', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-09-21 19:45:56', '2025-09-21 19:45:56'),
(981, 16, 9787, 'system', 'Sofia Dima-ano Left the Meeting.', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-09-21 19:57:13', '2025-09-21 19:57:13'),
(982, 16, 9813, 'system', 'Sita Cabeling Left the Meeting.', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-09-21 19:57:34', '2025-09-21 19:57:34'),
(983, 16, 9790, 'text', 'Hello everyone,&nbsp;<br>Please note that the meeting scheduled for tomorrow, Monday at 4 PM California time, has been rescheduled. This meeting is for the Directors, Managers, and Owners.&nbsp;<br>I hope everyone can attend. The agenda will include:<br>1) Presentation of the organizational chart, so the team can understand which Directors their members report to.2) An update on the submission of timesheets, which should be sent to the respective Director immediately after a project is completed.&nbsp;<br>Thank you!', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-09-21 20:05:00', '2025-09-21 20:05:00'),
(985, 17, 9790, 'system', 'Saffi Patino Joined the Meeting', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-09-22 05:37:23', '2025-09-22 05:37:23'),
(986, 17, 4911, 'system', 'Douglas Hill Joined the Meeting', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-09-22 05:37:36', '2025-09-22 05:37:36'),
(987, 17, 9812, 'system', 'Luisa Patino Joined the Meeting', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-09-22 05:39:31', '2025-09-22 05:39:31'),
(988, 17, 4911, 'system', 'Douglas Hill Left the Meeting.', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-09-22 05:54:55', '2025-09-22 05:54:55'),
(989, 58, 9790, 'system', 'Saffi Patino Started the Meeting', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-09-22 05:57:11', '2025-09-22 05:57:11'),
(990, 58, 4911, 'system', 'Douglas Hill Joined the Meeting', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-09-22 05:57:40', '2025-09-22 05:57:40'),
(991, 58, 4911, 'system', 'Douglas Hill Left the Meeting.', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-09-22 06:30:38', '2025-09-22 06:30:38'),
(992, 26, 30, 'system', 'Kimberly Ann Madelo Started the Meeting', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-09-22 07:03:15', '2025-09-22 07:03:15'),
(993, 26, 9799, 'system', 'Joy Cantancio Joined the Meeting', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-09-22 07:03:28', '2025-09-22 07:03:28'),
(994, 26, 9800, 'system', 'Jane Cantancio Joined the Meeting', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-09-22 07:03:43', '2025-09-22 07:03:43'),
(995, 26, 9803, 'system', 'Chelynn Joined the Meeting', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-09-22 07:03:57', '2025-09-22 07:03:57'),
(996, 26, 30, 'text', 'Hello everyone!. Sorry&nbsp; I called late.', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-09-22 07:04:03', '2025-09-22 07:04:03'),
(997, 26, 9806, 'system', 'Saffi Patiño Joined the Meeting', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-09-22 07:06:03', '2025-09-22 07:06:03'),
(998, 26, 9810, 'system', 'Darwin Garote Joined the Meeting', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-09-22 07:09:45', '2025-09-22 07:09:45'),
(999, 26, 44, 'system', 'Juan Joined the Meeting', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-09-22 07:09:55', '2025-09-22 07:09:55'),
(1000, 26, 9810, 'system', 'Darwin Garote Left the Meeting.', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-09-22 07:10:45', '2025-09-22 07:10:45'),
(1001, 26, 9810, 'system', 'Darwin Garote Joined the Meeting', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-09-22 07:10:46', '2025-09-22 07:10:46'),
(1002, 26, 9803, 'system', 'Chelynn Left the Meeting.', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-09-22 07:20:31', '2025-09-22 07:20:31'),
(1003, 26, 9803, 'system', 'Chelynn Joined the Meeting', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-09-22 07:20:34', '2025-09-22 07:20:34'),
(1004, 26, 30, 'system', 'Kimberly Ann Madelo Left the Meeting.', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-09-22 07:47:56', '2025-09-22 07:47:56'),
(1005, 26, 9803, 'system', 'Chelynn Left the Meeting.', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-09-22 07:51:01', '2025-09-22 07:51:01'),
(1006, 26, 9800, 'system', 'Jane Cantancio Left the Meeting.', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-09-22 07:51:08', '2025-09-22 07:51:08'),
(1007, 26, 44, 'system', 'Juan Left the Meeting.', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-09-22 07:51:08', '2025-09-22 07:51:08'),
(1008, 26, 30, 'system', 'Kimberly Ann Madelo Left the Meeting.', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-09-22 07:51:13', '2025-09-22 07:51:13'),
(1009, 26, 9806, 'system', 'Saffi Patiño Left the Meeting.', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-09-22 07:51:24', '2025-09-22 07:51:24'),
(1010, 16, 9790, 'system', 'Saffi Patino Started the Meeting', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-09-22 15:56:45', '2025-09-22 15:56:45'),
(1013, 16, 4911, 'system', 'Douglas Hill Started the Meeting', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-09-22 16:04:26', '2025-09-22 16:04:26'),
(1014, 16, 9813, 'system', 'Sita Cabeling Joined the Meeting', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-09-22 16:14:43', '2025-09-22 16:14:43'),
(1015, 16, 9787, 'system', 'Sofia Dima-ano Joined the Meeting', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-09-22 16:40:41', '2025-09-22 16:40:41'),
(1016, 16, 4911, 'system', 'Douglas Hill Left the Meeting.', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-09-22 16:44:05', '2025-09-22 16:44:05'),
(1017, 16, 9787, 'system', 'Sofia Dima-ano Left the Meeting.', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-09-22 16:44:33', '2025-09-22 16:44:33'),
(1026, 7, 30, 'text', 'I will call now TEAM', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-09-22 18:03:48', '2025-09-22 18:03:48'),
(1027, 7, 30, 'system', 'Kimberly Ann Madelo Joined the Meeting', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-09-22 18:03:52', '2025-09-22 18:03:52'),
(1028, 7, 4911, 'system', 'Douglas Hill Joined the Meeting', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-09-22 18:04:00', '2025-09-22 18:04:00'),
(1029, 7, 9801, 'system', 'Jing Beltran Joined the Meeting', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-09-22 18:05:31', '2025-09-22 18:05:31'),
(1030, 7, 9803, 'system', 'Chelynn Joined the Meeting', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-09-22 18:05:40', '2025-09-22 18:05:40'),
(1031, 7, 9800, 'system', 'Jane Cantancio Joined the Meeting', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-09-22 18:06:12', '2025-09-22 18:06:12'),
(1032, 7, 9799, 'system', 'Joy Cantancio Joined the Meeting', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-09-22 18:06:51', '2025-09-22 18:06:51'),
(1034, 7, 4911, 'system', 'Douglas Hill Left the Meeting.', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-09-22 18:24:26', '2025-09-22 18:24:26'),
(1035, 7, 9799, 'system', 'Joy Cantancio Left the Meeting.', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-09-22 19:20:07', '2025-09-22 19:20:07'),
(1036, 6, 9806, 'system', 'Saffi Patiño Started the Meeting', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-09-23 06:31:54', '2025-09-23 06:31:54'),
(1037, 6, 4911, 'text', 'Hello Team Marketing ...&nbsp; lets begin&nbsp;', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-09-23 06:32:09', '2025-09-23 06:32:09'),
(1038, 6, 9787, 'system', 'Sofia Dima-ano Joined the Meeting', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-09-23 06:32:14', '2025-09-23 06:32:14'),
(1039, 6, 4911, 'system', 'Douglas Hill Joined the Meeting', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-09-23 06:32:26', '2025-09-23 06:32:26'),
(1040, 6, 9790, 'system', 'Saffi Patino Joined the Meeting', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-09-23 06:33:47', '2025-09-23 06:33:47'),
(1041, 6, 4911, 'system', 'Douglas Hill Left the Meeting.', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-09-23 07:35:05', '2025-09-23 07:35:05'),
(1042, 6, 9806, 'system', 'Saffi Patiño Left the Meeting.', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-09-23 07:35:35', '2025-09-23 07:35:35'),
(1043, 20, 9790, 'system', 'Saffi Patino Started the Meeting', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-09-23 14:28:01', '2025-09-23 14:28:01'),
(1044, 20, 4911, 'system', 'Douglas Hill Started the Meeting', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-09-23 14:30:01', '2025-09-23 14:30:01'),
(1045, 20, 9813, 'system', 'Sita Cabeling Joined the Meeting', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-09-23 15:01:50', '2025-09-23 15:01:50'),
(1046, 20, 9813, 'system', 'Sita Cabeling Left the Meeting.', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-09-23 15:32:14', '2025-09-23 15:32:14');
INSERT INTO `chat_messages` (`id`, `conversation_id`, `user_id`, `type`, `body`, `deleted_by_moderator`, `original_body`, `reply_to_message_id`, `forwarded_from_message_id`, `forwarded_metadata`, `meta`, `edited_at`, `deleted_at`, `created_at`, `updated_at`) VALUES
(1047, 6, 4911, 'text', 'Are we meeting here ?', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-09-23 16:29:46', '2025-09-23 16:29:46'),
(1048, 6, 4911, 'system', 'Douglas Hill Started the Meeting', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-09-23 16:30:59', '2025-09-23 16:30:59'),
(1049, 6, 9790, 'system', 'Saffi Patino Started the Meeting', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-09-23 16:31:19', '2025-09-23 16:31:19'),
(1050, 6, 9787, 'system', 'Sofia Dima-ano Started the Meeting', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-09-23 16:33:10', '2025-09-23 16:33:10'),
(1051, 6, 9806, 'system', 'Saffi Patiño Joined the Meeting', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-09-23 16:37:29', '2025-09-23 16:37:29'),
(1052, 6, 9806, 'system', 'Saffi Patiño Left the Meeting.', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-09-23 16:44:30', '2025-09-23 16:44:30'),
(1053, 6, 9790, 'system', 'Saffi Patino Joined the Meeting', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-09-23 16:44:34', '2025-09-23 16:44:34'),
(1054, 6, 4911, 'system', 'Douglas Hill Left the Meeting.', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-09-23 16:44:41', '2025-09-23 16:44:41'),
(1055, 6, 9806, 'system', 'Saffi Patiño Joined the Meeting', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-09-23 16:44:51', '2025-09-23 16:44:51'),
(1056, 6, 9787, 'system', 'Sofia Dima-ano Joined the Meeting', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-09-23 16:46:11', '2025-09-23 16:46:11'),
(1057, 6, 9806, 'system', 'Saffi Patiño Left the Meeting.', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-09-23 17:09:32', '2025-09-23 17:09:32'),
(1058, 7, 30, 'text', 'Hello everyone! I will do the call now.', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-09-23 18:00:56', '2025-09-23 18:00:56'),
(1059, 7, 30, 'system', 'Kimberly Ann Madelo Joined the Meeting', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-09-23 18:00:59', '2025-09-23 18:00:59'),
(1060, 7, 30, 'text', 'Naa nako sa call TEAM.', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-09-23 18:02:22', '2025-09-23 18:02:22'),
(1061, 7, 9803, 'system', 'Chelynn Joined the Meeting', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-09-23 18:02:28', '2025-09-23 18:02:28'),
(1062, 7, 30, 'text', 'Where are you na?', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-09-23 18:02:30', '2025-09-23 18:02:30'),
(1063, 7, 9799, 'system', 'Joy Cantancio Joined the Meeting', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-09-23 18:03:11', '2025-09-23 18:03:11'),
(1064, 7, 9800, 'system', 'Jane Cantancio Joined the Meeting', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-09-23 18:03:27', '2025-09-23 18:03:27'),
(1065, 7, 9801, 'system', 'Jing Beltran Joined the Meeting', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-09-23 18:05:41', '2025-09-23 18:05:41'),
(1066, 7, 9801, 'system', 'Jing Beltran Left the Meeting.', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-09-23 19:02:47', '2025-09-23 19:02:47'),
(1067, 7, 9799, 'system', 'Joy Cantancio Left the Meeting.', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-09-23 19:02:53', '2025-09-23 19:02:53'),
(1068, 7, 30, 'system', 'Kimberly Ann Madelo Left the Meeting.', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-09-23 19:03:42', '2025-09-23 19:03:42'),
(1080, 17, 9790, 'system', 'Saffi Patino Started the Meeting', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-09-24 16:32:36', '2025-09-24 16:32:36'),
(1081, 17, 9790, 'system', 'Saffi Patino Joined the Meeting', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-09-24 16:32:38', '2025-09-24 16:32:38'),
(1082, 17, 9814, 'system', 'Marc Sabaulan Started the Meeting', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-09-24 16:34:13', '2025-09-24 16:34:13'),
(1083, 17, 4911, 'system', 'Douglas Hill Started the Meeting', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-09-24 16:41:10', '2025-09-24 16:41:10'),
(1084, 17, 4911, 'system', 'Douglas Hill Left the Meeting.', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-09-24 17:22:49', '2025-09-24 17:22:49'),
(1085, 6, 9806, 'system', 'Saffi Patiño Started the Meeting', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-09-24 17:24:35', '2025-09-24 17:24:35'),
(1086, 6, 9806, 'system', 'Saffi Patiño Joined the Meeting', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-09-24 17:24:37', '2025-09-24 17:24:37'),
(1087, 7, 30, 'text', 'Hello! everyone.&nbsp; I will call now.', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-09-24 18:01:16', '2025-09-24 18:01:16'),
(1088, 7, 9801, 'text', 'good morning all', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-09-24 18:02:03', '2025-09-24 18:02:03'),
(1089, 7, 9800, 'text', 'Hello all good morning', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-09-24 18:04:50', '2025-09-24 18:04:50'),
(1090, 7, 9801, 'text', 'hi Jane', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-09-24 18:05:04', '2025-09-24 18:05:04'),
(1091, 7, 9800, 'system', 'Jane Cantancio Started the Meeting', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-09-24 18:05:45', '2025-09-24 18:05:45'),
(1092, 7, 9801, 'system', 'Jing Beltran Joined the Meeting', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-09-24 18:06:00', '2025-09-24 18:06:00'),
(1093, 7, 9799, 'system', 'Joy Cantancio Joined the Meeting', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-09-24 18:07:54', '2025-09-24 18:07:54'),
(1094, 7, 9803, 'system', 'Chelynn Joined the Meeting', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-09-24 18:07:57', '2025-09-24 18:07:57'),
(1095, 7, 30, 'system', 'Kimberly Ann Madelo Joined the Meeting', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-09-24 18:11:05', '2025-09-24 18:11:05'),
(1096, 7, 9801, 'system', 'Jing Beltran Left the Meeting.', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-09-24 19:04:11', '2025-09-24 19:04:11'),
(1097, 7, 9799, 'system', 'Joy Cantancio Left the Meeting.', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-09-24 19:04:25', '2025-09-24 19:04:25'),
(1098, 7, 30, 'system', 'Kimberly Ann Madelo Left the Meeting.', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-09-24 19:04:40', '2025-09-24 19:04:40'),
(1099, 6, 9806, 'system', 'Saffi Patiño Left the Meeting.', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-09-24 19:20:34', '2025-09-24 19:20:34'),
(1100, 6, 4911, 'system', 'Douglas Hill Joined the Meeting', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-09-25 06:23:26', '2025-09-25 06:23:26'),
(1101, 6, 4911, 'system', 'Douglas Hill Left the Meeting.', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-09-25 06:24:02', '2025-09-25 06:24:02'),
(1102, 6, 9790, 'text', 'Hello, meeting at 6:30 am today.', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-09-25 06:24:26', '2025-09-25 06:24:26'),
(1103, 6, 4911, 'text', 'Hello all.&nbsp; Marketing catchup meeting will start soon&nbsp;', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-09-25 06:24:33', '2025-09-25 06:24:33'),
(1104, 6, 4911, 'system', 'Douglas Hill Joined the Meeting', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-09-25 06:29:45', '2025-09-25 06:29:45'),
(1105, 6, 9790, 'system', 'Saffi Patino Joined the Meeting', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-09-25 06:31:21', '2025-09-25 06:31:21'),
(1106, 6, 4911, 'text', 'I started the meeting', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-09-25 06:31:32', '2025-09-25 06:31:32'),
(1107, 6, 9787, 'system', 'Sofia Dima-ano Joined the Meeting', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-09-25 06:32:40', '2025-09-25 06:32:40'),
(1108, 6, 9802, 'system', 'Alfa Macasi Joined the Meeting', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-09-25 06:34:50', '2025-09-25 06:34:50'),
(1109, 6, 9787, 'system', 'Sofia Dima-ano Left the Meeting.', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-09-25 06:42:41', '2025-09-25 06:42:41'),
(1110, 6, 9787, 'system', 'Sofia Dima-ano Joined the Meeting', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-09-25 06:42:43', '2025-09-25 06:42:43'),
(1111, 6, 9802, 'system', 'Alfa Macasi Left the Meeting.', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-09-25 07:25:33', '2025-09-25 07:25:33'),
(1112, 2, 9790, 'text', 'Good Morning', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-09-25 15:02:07', '2025-09-25 15:02:07'),
(1113, 10, 9790, 'system', 'Saffi Patino Started the Meeting', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-09-25 16:12:22', '2025-09-25 16:12:22'),
(1114, 10, 30, 'system', 'Kimberly Ann Madelo Started the Meeting', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-09-25 16:16:09', '2025-09-25 16:16:09'),
(1115, 10, 9801, 'system', 'Jing Beltran Joined the Meeting', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-09-25 16:16:56', '2025-09-25 16:16:56'),
(1116, 10, 9790, 'system', 'Saffi Patino Joined the Meeting', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-09-25 16:21:33', '2025-09-25 16:21:33'),
(1117, 10, 30, 'system', 'Kimberly Ann Madelo Left the Meeting.', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-09-25 16:30:46', '2025-09-25 16:30:46'),
(1118, 10, 9801, 'system', 'Jing Beltran Left the Meeting.', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-09-25 16:36:21', '2025-09-25 16:36:21'),
(1119, 6, 4911, 'system', 'Douglas Hill Joined the Meeting', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-09-25 17:07:20', '2025-09-25 17:07:20'),
(1120, 6, 4911, 'system', 'Douglas Hill Left the Meeting.', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-09-25 17:07:42', '2025-09-25 17:07:42'),
(1121, 6, 9790, 'system', 'Saffi Patino Joined the Meeting', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-09-25 17:07:59', '2025-09-25 17:07:59'),
(1122, 6, 4911, 'text', 'I am ready for the meeting', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-09-25 17:08:02', '2025-09-25 17:08:02'),
(1123, 6, 4911, 'system', 'Douglas Hill Joined the Meeting', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-09-25 17:08:24', '2025-09-25 17:08:24'),
(1124, 6, 9802, 'system', 'Alfa Macasi Joined the Meeting', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-09-25 17:16:38', '2025-09-25 17:16:38'),
(1125, 7, 30, 'text', 'Hi. I will call now.', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-09-25 18:00:58', '2025-09-25 18:00:58'),
(1126, 7, 30, 'system', 'Kimberly Ann Madelo Started the Meeting', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-09-25 18:01:03', '2025-09-25 18:01:03'),
(1127, 7, 30, 'text', 'I am in the call already. Where are you guys?', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-09-25 18:03:36', '2025-09-25 18:03:36'),
(1128, 7, 9800, 'system', 'Jane Cantancio Joined the Meeting', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-09-25 18:03:48', '2025-09-25 18:03:48'),
(1129, 7, 9801, 'system', 'Jing Beltran Joined the Meeting', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-09-25 18:07:56', '2025-09-25 18:07:56'),
(1130, 7, 9799, 'system', 'Joy Cantancio Joined the Meeting', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-09-25 18:08:29', '2025-09-25 18:08:29'),
(1131, 7, 30, 'system', 'Kimberly Ann Madelo Left the Meeting.', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-09-25 18:09:17', '2025-09-25 18:09:17'),
(1132, 6, 4911, 'system', 'Douglas Hill Left the Meeting.', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-09-25 18:10:58', '2025-09-25 18:10:58'),
(1133, 7, 9801, 'system', 'Jing Beltran Joined the Meeting', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-09-25 18:19:04', '2025-09-25 18:19:04'),
(1134, 7, 9803, 'system', 'Chelynn Joined the Meeting', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-09-25 18:31:53', '2025-09-25 18:31:53'),
(1135, 7, 9802, 'system', 'Alfa Macasi Joined the Meeting', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-09-25 18:47:08', '2025-09-25 18:47:08'),
(1136, 7, 30, 'system', 'Kimberly Ann Madelo Left the Meeting.', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-09-25 19:18:55', '2025-09-25 19:18:55'),
(1137, 7, 9801, 'system', 'Jing Beltran Left the Meeting.', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-09-25 19:49:00', '2025-09-25 19:49:00'),
(1138, 6, 9790, 'system', 'Saffi Patino Joined the Meeting', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-09-26 06:02:09', '2025-09-26 06:02:09'),
(1139, 6, 4911, 'system', 'Douglas Hill Joined the Meeting', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-09-26 06:02:14', '2025-09-26 06:02:14'),
(1142, 6, 9787, 'system', 'Sofia Dima-ano Joined the Meeting', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-09-26 06:03:20', '2025-09-26 06:03:20'),
(1143, 6, 9787, 'system', 'Sofia Dima-ano Left the Meeting.', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-09-26 06:04:20', '2025-09-26 06:04:20'),
(1144, 6, 9787, 'system', 'Sofia Dima-ano Joined the Meeting', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-09-26 06:04:24', '2025-09-26 06:04:24'),
(1145, 6, 9787, 'system', 'Sofia Dima-ano Left the Meeting.', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-09-26 06:04:58', '2025-09-26 06:04:58'),
(1146, 6, 4911, 'system', 'Douglas Hill Left the Meeting.', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-09-26 06:05:43', '2025-09-26 06:05:43'),
(1147, 6, 4911, 'system', 'Douglas Hill Joined the Meeting', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-09-26 06:05:55', '2025-09-26 06:05:55'),
(1148, 6, 9787, 'system', 'Sofia Dima-ano Joined the Meeting', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-09-26 06:07:24', '2025-09-26 06:07:24'),
(1149, 6, 4911, 'text', 'Saffi and I are in the meeting now .... if you cant get into our room, then pls hang up your old session and try ti join us again', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-09-26 06:07:46', '2025-09-26 06:07:46'),
(1150, 6, 9787, 'system', 'Sofia Dima-ano Left the Meeting.', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-09-26 06:08:13', '2025-09-26 06:08:13'),
(1151, 6, 9787, 'system', 'Sofia Dima-ano Joined the Meeting', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-09-26 06:08:55', '2025-09-26 06:08:55'),
(1152, 6, 4911, 'text', 'still wrong meeting Sofia ....&nbsp;&nbsp;', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-09-26 06:09:28', '2025-09-26 06:09:28'),
(1153, 6, 9790, 'text', 'please join&nbsp;', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-09-26 06:09:34', '2025-09-26 06:09:34'),
(1154, 6, 4911, 'text', 'Is Alpha here ?', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-09-26 06:10:21', '2025-09-26 06:10:21'),
(1155, 6, 9787, 'system', 'Sofia Dima-ano Left the Meeting.', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-09-26 06:11:17', '2025-09-26 06:11:17'),
(1156, 6, 9787, 'system', 'Sofia Dima-ano Joined the Meeting', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-09-26 06:11:17', '2025-09-26 06:11:17'),
(1157, 6, 4911, 'text', 'Sofia, pls try leaving the meeting you are in now .... then make sure you end that meeting , refresh your session and try to join the Saffi meeting - maybe that will help ?', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-09-26 06:14:03', '2025-09-26 06:14:03'),
(1158, 6, 9787, 'system', 'Sofia Dima-ano Left the Meeting.', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-09-26 06:14:37', '2025-09-26 06:14:37'),
(1159, 6, 9787, 'system', 'Sofia Dima-ano Joined the Meeting', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-09-26 06:14:37', '2025-09-26 06:14:37'),
(1160, 6, 4911, 'system', 'Douglas Hill Joined the Meeting', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-09-26 06:14:40', '2025-09-26 06:14:40'),
(1161, 6, 4911, 'system', 'Douglas Hill Joined the Meeting', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-09-26 06:14:55', '2025-09-26 06:14:55'),
(1162, 6, 4911, 'system', 'Douglas Hill Joined the Meeting', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-09-26 06:14:57', '2025-09-26 06:14:57'),
(1163, 6, 9787, 'system', 'Sofia Dima-ano Joined the Meeting', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-09-26 06:15:44', '2025-09-26 06:15:44'),
(1164, 6, 9787, 'system', 'Sofia Dima-ano Left the Meeting.', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-09-26 06:17:31', '2025-09-26 06:17:31'),
(1165, 6, 9787, 'system', 'Sofia Dima-ano Joined the Meeting', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-09-26 06:20:33', '2025-09-26 06:20:33'),
(1166, 6, 9787, 'system', 'Sofia Dima-ano Left the Meeting.', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-09-26 06:21:31', '2025-09-26 06:21:31'),
(1167, 6, 9787, 'system', 'Sofia Dima-ano Joined the Meeting', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-09-26 06:21:49', '2025-09-26 06:21:49'),
(1168, 6, 9787, 'system', 'Sofia Dima-ano Left the Meeting.', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-09-26 06:23:02', '2025-09-26 06:23:02'),
(1169, 6, 9787, 'system', 'Sofia Dima-ano Joined the Meeting', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-09-26 06:23:15', '2025-09-26 06:23:15'),
(1172, 6, 9787, 'system', 'Sofia Dima-ano Joined the Meeting', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-09-26 06:34:51', '2025-09-26 06:34:51'),
(1173, 6, 9787, 'system', 'Sofia Dima-ano Left the Meeting.', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-09-26 06:35:54', '2025-09-26 06:35:54'),
(1174, 6, 4911, 'system', 'Douglas Hill Left the Meeting.', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-09-26 06:35:55', '2025-09-26 06:35:55'),
(1175, 6, 9787, 'system', 'Sofia Dima-ano Joined the Meeting', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-09-26 06:35:56', '2025-09-26 06:35:56'),
(1176, 6, 9787, 'system', 'Sofia Dima-ano Left the Meeting.', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-09-26 06:35:58', '2025-09-26 06:35:58'),
(1177, 6, 9787, 'system', 'Sofia Dima-ano Left the Meeting.', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-09-26 06:36:12', '2025-09-26 06:36:12'),
(1179, 6, 9787, 'system', 'Sofia Dima-ano Joined the Meeting', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-09-26 06:36:18', '2025-09-26 06:36:18'),
(1182, 6, 9787, 'system', 'Sofia Dima-ano Left the Meeting.', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-09-26 06:36:32', '2025-09-26 06:36:32'),
(1183, 6, 9787, 'system', 'Sofia Dima-ano Joined the Meeting', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-09-26 06:37:00', '2025-09-26 06:37:00'),
(1184, 6, 9787, 'system', 'Sofia Dima-ano Left the Meeting.', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-09-26 06:37:13', '2025-09-26 06:37:13'),
(1185, 6, 9787, 'system', 'Sofia Dima-ano Joined the Meeting', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-09-26 06:40:05', '2025-09-26 06:40:05'),
(1186, 6, 9787, 'system', 'Sofia Dima-ano Left the Meeting.', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-09-26 06:40:13', '2025-09-26 06:40:13'),
(1190, 62, 9798, 'text', 'Hello, @everyone. I\'m going to start the meeting later.&nbsp;', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-09-26 07:57:02', '2025-09-26 07:57:02'),
(1191, 62, 9798, 'system', 'Development Team Started the Meeting', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-09-26 08:00:55', '2025-09-26 08:00:55'),
(1192, 62, 9815, 'system', 'Faith Mikee Taccad Started the Meeting', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-09-26 08:01:07', '2025-09-26 08:01:07'),
(1193, 62, 9810, 'system', 'Darwin Garote Joined the Meeting', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-09-26 08:13:58', '2025-09-26 08:13:58'),
(1200, 17, 9790, 'system', 'Saffi Patino Started the Meeting', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-09-27 05:42:39', '2025-09-27 05:42:39'),
(1201, 17, 4911, 'system', 'Douglas Hill Started the Meeting', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-09-27 05:45:31', '2025-09-27 05:45:31'),
(1202, 17, 4911, 'system', 'Douglas Hill Left the Meeting.', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-09-27 05:55:28', '2025-09-27 05:55:28'),
(1205, 17, 9790, 'system', 'Saffi Patino Started the Meeting', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-09-29 06:01:59', '2025-09-29 06:01:59'),
(1206, 17, 9790, 'system', 'Saffi Patino Joined the Meeting', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-09-29 06:02:04', '2025-09-29 06:02:04'),
(1208, 17, 4911, 'system', 'Douglas Hill Joined the Meeting', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-09-29 06:03:33', '2025-09-29 06:03:33'),
(1209, 17, 9790, 'system', 'Saffi Patino Left the Meeting.', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-09-29 06:04:25', '2025-09-29 06:04:25'),
(1210, 17, 9790, 'system', 'Saffi Patino Left the Meeting.', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-09-29 06:15:06', '2025-09-29 06:15:06'),
(1214, 26, 30, 'text', 'Hi! Everyone.I will call now', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-09-29 07:01:59', '2025-09-29 07:01:59'),
(1215, 26, 30, 'system', 'Kimberly Ann Madelo Started the Meeting', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-09-29 07:02:03', '2025-09-29 07:02:03'),
(1216, 26, 9799, 'system', 'Joy Cantancio Started the Meeting', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-09-29 07:02:41', '2025-09-29 07:02:41'),
(1217, 26, 9803, 'system', 'Chelynn Joined the Meeting', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-09-29 07:02:42', '2025-09-29 07:02:42'),
(1218, 26, 9800, 'system', 'Jane Cantancio Joined the Meeting', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-09-29 07:03:05', '2025-09-29 07:03:05'),
(1219, 26, 9803, 'system', 'Chelynn Left the Meeting.', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-09-29 07:03:29', '2025-09-29 07:03:29'),
(1220, 26, 9803, 'system', 'Chelynn Joined the Meeting', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-09-29 07:05:24', '2025-09-29 07:05:24'),
(1221, 26, 9810, 'system', 'Darwin Garote Joined the Meeting', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-09-29 07:08:44', '2025-09-29 07:08:44'),
(1222, 26, 44, 'system', 'Juan Joined the Meeting', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-09-29 07:11:51', '2025-09-29 07:11:51'),
(1223, 26, 9799, 'system', 'Joy Cantancio Left the Meeting.', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-09-29 07:48:37', '2025-09-29 07:48:37'),
(1224, 16, 9790, 'system', 'Saffi Patino Started the Meeting', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-09-29 17:02:24', '2025-09-29 17:02:24'),
(1225, 16, 9787, 'system', 'Sofia Dima-ano Joined the Meeting', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-09-29 17:02:29', '2025-09-29 17:02:29'),
(1226, 16, 30, 'system', 'Kimberly Ann Madelo Joined the Meeting', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-09-29 17:04:09', '2025-09-29 17:04:09'),
(1227, 16, 9813, 'system', 'Sita Cabeling Joined the Meeting', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-09-29 17:04:18', '2025-09-29 17:04:18'),
(1228, 16, 9787, 'system', 'Sofia Dima-ano Joined the Meeting', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-09-29 17:04:22', '2025-09-29 17:04:22'),
(1229, 16, 9787, 'system', 'Sofia Dima-ano Left the Meeting.', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-09-29 17:04:27', '2025-09-29 17:04:27'),
(1230, 16, 9787, 'system', 'Sofia Dima-ano Joined the Meeting', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-09-29 17:04:30', '2025-09-29 17:04:30'),
(1231, 16, 9790, 'system', 'Saffi Patino Joined the Meeting', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-09-29 17:05:29', '2025-09-29 17:05:29'),
(1232, 16, 9790, 'system', 'Saffi Patino Joined the Meeting', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-09-29 17:06:42', '2025-09-29 17:06:42'),
(1233, 16, 9790, 'system', 'Saffi Patino Left the Meeting.', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-09-29 17:06:45', '2025-09-29 17:06:45'),
(1234, 16, 9790, 'system', 'Saffi Patino Joined the Meeting', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-09-29 17:06:48', '2025-09-29 17:06:48'),
(1235, 16, 4911, 'system', 'Douglas Hill Joined the Meeting', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-09-29 17:06:48', '2025-09-29 17:06:48'),
(1236, 16, 9787, 'system', 'Sofia Dima-ano Joined the Meeting', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-09-29 17:26:22', '2025-09-29 17:26:22'),
(1237, 16, 9787, 'system', 'Sofia Dima-ano Joined the Meeting', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-09-29 17:26:25', '2025-09-29 17:26:25'),
(1238, 16, 9787, 'system', 'Sofia Dima-ano Joined the Meeting', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-09-29 17:26:30', '2025-09-29 17:26:30'),
(1239, 16, 9787, 'system', 'Sofia Dima-ano Joined the Meeting', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-09-29 17:26:38', '2025-09-29 17:26:38'),
(1240, 16, 9787, 'system', 'Sofia Dima-ano Left the Meeting.', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-09-29 17:26:39', '2025-09-29 17:26:39'),
(1241, 16, 9787, 'system', 'Sofia Dima-ano Joined the Meeting', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-09-29 17:26:55', '2025-09-29 17:26:55'),
(1242, 16, 9787, 'system', 'Sofia Dima-ano Left the Meeting.', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-09-29 17:52:33', '2025-09-29 17:52:33'),
(1243, 16, 9813, 'system', 'Sita Cabeling Left the Meeting.', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-09-29 17:53:25', '2025-09-29 17:53:25'),
(1244, 7, 30, 'system', 'Kimberly Ann Madelo Joined the Meeting', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-09-29 18:03:18', '2025-09-29 18:03:18'),
(1245, 7, 9801, 'text', 'good morning', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-09-29 18:03:31', '2025-09-29 18:03:31'),
(1246, 7, 30, 'text', 'I am already in the call everyone!', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-09-29 18:03:56', '2025-09-29 18:03:56'),
(1247, 7, 9801, 'system', 'Jing Beltran Joined the Meeting', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-09-29 18:03:56', '2025-09-29 18:03:56'),
(1248, 7, 9803, 'system', 'Chelynn Joined the Meeting', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-09-29 18:04:25', '2025-09-29 18:04:25'),
(1249, 7, 9799, 'system', 'Joy Cantancio Joined the Meeting', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-09-29 18:06:08', '2025-09-29 18:06:08'),
(1250, 7, 9800, 'system', 'Jane Cantancio Joined the Meeting', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-09-29 18:07:50', '2025-09-29 18:07:50'),
(1251, 7, 30, 'system', 'Kimberly Ann Madelo Joined the Meeting', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-09-29 18:40:24', '2025-09-29 18:40:24'),
(1252, 7, 9801, 'system', 'Jing Beltran Left the Meeting.', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-09-29 19:05:05', '2025-09-29 19:05:05'),
(1253, 7, 30, 'system', 'Kimberly Ann Madelo Left the Meeting.', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-09-29 19:05:13', '2025-09-29 19:05:13'),
(1254, 7, 9799, 'system', 'Joy Cantancio Left the Meeting.', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-09-29 19:05:32', '2025-09-29 19:05:32'),
(1255, 44, 9635, 'text', 'hi not sure if you\'re the one i talk to but I\'m trying to download plans but my computer keeps blocking it cause its saying its spam', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-09-29 20:51:05', '2025-09-29 20:51:05'),
(1256, 6, 9790, 'system', 'Saffi Patino Joined the Meeting', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-09-30 06:10:25', '2025-09-30 06:10:25'),
(1257, 6, 4911, 'system', 'Douglas Hill Joined the Meeting', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-09-30 06:14:35', '2025-09-30 06:14:35'),
(1258, 6, 9787, 'system', 'Sofia Dima-ano Joined the Meeting', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-09-30 06:21:29', '2025-09-30 06:21:29'),
(1259, 6, 9802, 'system', 'Alfa Macasi Joined the Meeting', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-09-30 06:21:47', '2025-09-30 06:21:47'),
(1260, 6, 9815, 'system', 'Faith Mikee Taccad Joined the Meeting', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-09-30 06:31:42', '2025-09-30 06:31:42'),
(1261, 6, 4911, 'system', 'Douglas Hill Left the Meeting.', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-09-30 07:02:36', '2025-09-30 07:02:36'),
(1262, 6, 9802, 'system', 'Alfa Macasi Left the Meeting.', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-09-30 07:05:48', '2025-09-30 07:05:48'),
(1263, 20, 9790, 'system', 'Saffi Patino Started the Meeting', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-09-30 14:28:50', '2025-09-30 14:28:50'),
(1264, 20, 4911, 'system', 'Douglas Hill Started the Meeting', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-09-30 14:35:27', '2025-09-30 14:35:27'),
(1265, 44, 30, 'text', 'Hello! Antonio. Yes, you can reach me out anytime. I am supervising VA\'s and all portals under PlanPanther. Alright. I will note your concern and make sure that the development team will fix that bug right away. Thank you for reaching out.&nbsp;', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-09-30 17:36:42', '2025-09-30 17:36:42'),
(1266, 44, 30, 'text', 'Antonio. Just&nbsp; for clarification. When you say download plans. Is it from the email invitation you received from PlanPanther?', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-09-30 17:43:13', '2025-09-30 17:43:13'),
(1267, 7, 30, 'text', 'Goodmorning! I will call now.', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-09-30 18:00:21', '2025-09-30 18:00:21'),
(1268, 7, 30, 'system', 'Kimberly Ann Madelo Joined the Meeting', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-09-30 18:00:29', '2025-09-30 18:00:29'),
(1269, 7, 9803, 'system', 'Chelynn Joined the Meeting', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-09-30 18:01:37', '2025-09-30 18:01:37'),
(1270, 7, 9801, 'system', 'Jing Beltran Joined the Meeting', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-09-30 18:02:40', '2025-09-30 18:02:40'),
(1271, 7, 9800, 'system', 'Jane Cantancio Joined the Meeting', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-09-30 18:04:51', '2025-09-30 18:04:51'),
(1272, 44, 30, 'text', 'If it is inside your portal. \"Project Invitation\" then yes floor plans can be downloaded. Note we tried to check in and download a floorplan inside your portal and we are able to download the file.', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-09-30 19:14:09', '2025-09-30 19:14:09'),
(1273, 44, 30, 'text', 'If any case you see this upon downloading. T<span>here is nothing to worry about.&nbsp; Just click the \"Download anyway\"</span>', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-09-30 19:16:14', '2025-09-30 19:16:14'),
(1274, 40, 4911, 'text', 'Hi Kimberly , how r u ?', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-09-30 19:53:42', '2025-09-30 19:53:42'),
(1275, 40, 4911, 'system', 'Douglas Hill Started the Meeting', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-09-30 19:56:02', '2025-09-30 19:56:02'),
(1276, 40, 4911, 'system', 'Douglas Hill Left the Meeting.', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-09-30 19:59:57', '2025-09-30 19:59:57'),
(1277, 40, 4911, 'text', 'Hi Kimberly , r u there ?', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-09-30 20:02:18', '2025-09-30 20:02:18'),
(1278, 40, 30, 'text', 'Yes, Sir Douglas? I just had my breakfast.', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-09-30 20:04:51', '2025-09-30 20:04:51'),
(1279, 40, 4911, 'text', 'Hi, sorry to disturb your breakfast;&nbsp; I just wanted to know how the portal traning/testing meeting went ?&nbsp; I saw&nbsp;', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-09-30 20:05:51', '2025-09-30 20:05:51'),
(1280, 40, 30, 'text', 'There\'s no huge update so far. We have gathered all our feedback (mostly issues/bugs to get fixed hopefully) in the google sheet.&nbsp;', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-09-30 20:07:56', '2025-09-30 20:07:56'),
(1281, 40, 4911, 'text', '&nbsp;So, still same bugs not fixed yet ??', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-09-30 20:08:32', '2025-09-30 20:08:32'),
(1282, 40, 30, 'text', 'Yes. and there\'s more to add on the list.', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-09-30 20:09:28', '2025-09-30 20:09:28'),
(1283, 40, 4911, 'text', 'wow,&nbsp; &nbsp;any thoughts on why there is no progress from Kent and team?', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-09-30 20:10:10', '2025-09-30 20:10:10'),
(1284, 40, 30, 'text', 'Honestly. I am also waiting for an update. Sir Kent has not message or reach me right after our WebDev meeting last Monday. I don\'t know what is fixed or not but upon navigating the problem remains specially&nbsp; on the Backdoor access features.', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-09-30 20:12:16', '2025-09-30 20:12:16'),
(1285, 40, 4911, 'text', 'sigh', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-09-30 20:13:03', '2025-09-30 20:13:03'),
(1286, 40, 4911, 'text', 'May I cam call you and you share your screen to walk me thru the \" google sheets\" list of bug fix status ?', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-09-30 20:14:53', '2025-09-30 20:14:53'),
(1287, 40, 30, 'text', 'Alright', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-09-30 20:15:07', '2025-09-30 20:15:07'),
(1288, 40, 30, 'system', 'Kimberly Ann Madelo Started the Meeting', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-09-30 20:15:13', '2025-09-30 20:15:13'),
(1289, 40, 4911, 'system', 'Douglas Hill Joined the Meeting', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-09-30 20:15:23', '2025-09-30 20:15:23'),
(1290, 40, 4911, 'system', 'Douglas Hill Left the Meeting.', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-09-30 21:00:40', '2025-09-30 21:00:40'),
(1291, 63, 4911, 'text', 'Hi Jing', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-09-30 21:04:43', '2025-09-30 21:04:43'),
(1292, 63, 9801, 'text', 'Hi Sir', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-09-30 21:07:11', '2025-09-30 21:07:11'),
(1293, 63, 4911, 'text', 'Great !', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-09-30 21:07:22', '2025-09-30 21:07:22'),
(1294, 63, 9801, 'system', 'Jing Beltran Started the Meeting', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-09-30 21:07:51', '2025-09-30 21:07:51'),
(1295, 63, 4911, 'system', 'Douglas Hill Joined the Meeting', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-09-30 21:08:03', '2025-09-30 21:08:03'),
(1296, 41, 9790, 'system', 'Saffi Patino Started the Meeting', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-10-01 05:35:21', '2025-10-01 05:35:21'),
(1297, 41, 30, 'system', 'Kimberly Ann Madelo Joined the Meeting', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-10-01 05:35:35', '2025-10-01 05:35:35'),
(1298, 41, 4911, 'system', 'Douglas Hill Started the Meeting', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-10-01 05:36:12', '2025-10-01 05:36:12'),
(1311, 2, 9790, 'system', 'Saffi Patino Started the Meeting', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-10-01 15:53:13', '2025-10-01 15:53:13'),
(1312, 2, 4911, 'system', 'Douglas Hill Started the Meeting', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-10-01 15:54:42', '2025-10-01 15:54:42'),
(1313, 2, 4911, 'system', 'Douglas Hill Left the Meeting.', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-10-01 16:15:56', '2025-10-01 16:15:56'),
(1314, 44, 9635, 'text', 'thank you', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-10-01 16:52:28', '2025-10-01 16:52:28'),
(1315, 6, 9790, 'system', 'Saffi Patino Started the Meeting', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-10-01 17:06:35', '2025-10-01 17:06:35'),
(1316, 44, 30, 'text', 'You are welcome Antonio.', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-10-01 17:08:31', '2025-10-01 17:08:31'),
(1317, 6, 9790, 'system', 'Saffi Patino Left the Meeting.', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-10-01 17:09:38', '2025-10-01 17:09:38'),
(1318, 6, 9787, 'system', 'Sofia Dima-ano Joined the Meeting', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-10-01 17:27:37', '2025-10-01 17:27:37'),
(1319, 6, 9787, 'system', 'Sofia Dima-ano Joined the Meeting', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-10-01 17:28:38', '2025-10-01 17:28:38'),
(1320, 6, 9787, 'system', 'Sofia Dima-ano Left the Meeting.', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-10-01 17:28:45', '2025-10-01 17:28:45'),
(1321, 6, 9787, 'system', 'Sofia Dima-ano Joined the Meeting', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-10-01 17:28:47', '2025-10-01 17:28:47'),
(1322, 7, 30, 'text', 'Hello everyone! I will call now.', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-10-01 18:01:45', '2025-10-01 18:01:45'),
(1323, 7, 30, 'system', 'Kimberly Ann Madelo Started the Meeting', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-10-01 18:01:54', '2025-10-01 18:01:54'),
(1324, 7, 9803, 'system', 'Chelynn Joined the Meeting', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-10-01 18:02:23', '2025-10-01 18:02:23'),
(1325, 7, 9801, 'system', 'Jing Beltran Joined the Meeting', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-10-01 18:03:20', '2025-10-01 18:03:20'),
(1326, 7, 9800, 'system', 'Jane Cantancio Joined the Meeting', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-10-01 18:03:59', '2025-10-01 18:03:59'),
(1327, 7, 4911, 'system', 'Douglas Hill Joined the Meeting', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-10-01 18:04:37', '2025-10-01 18:04:37'),
(1328, 7, 9799, 'system', 'Joy Cantancio Joined the Meeting', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-10-01 18:04:50', '2025-10-01 18:04:50'),
(1329, 7, 9790, 'system', 'Saffi Patino Joined the Meeting', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-10-01 18:04:58', '2025-10-01 18:04:58'),
(1330, 7, 9798, 'system', 'Development Team Joined the Meeting', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-10-01 18:36:28', '2025-10-01 18:36:28'),
(1331, 7, 4911, 'system', 'Douglas Hill Left the Meeting.', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-10-01 19:07:54', '2025-10-01 19:07:54'),
(1332, 7, 30, 'system', 'Kimberly Ann Madelo Left the Meeting.', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-10-01 19:15:19', '2025-10-01 19:15:19'),
(1333, 6, 4911, 'system', 'Douglas Hill Joined the Meeting', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-10-02 05:55:47', '2025-10-02 05:55:47'),
(1334, 6, 9790, 'system', 'Saffi Patino Started the Meeting', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-10-02 05:56:03', '2025-10-02 05:56:03'),
(1335, 6, 9787, 'system', 'Sofia Dima-ano Started the Meeting', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-10-02 05:57:28', '2025-10-02 05:57:28'),
(1336, 6, 9802, 'system', 'Alfa Macasi Started the Meeting', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-10-02 06:21:08', '2025-10-02 06:21:08'),
(1337, 6, 4911, 'system', 'Douglas Hill Left the Meeting.', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-10-02 07:02:09', '2025-10-02 07:02:09'),
(1338, 10, 4911, 'text', 'Let\'s chat !', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-10-02 15:59:55', '2025-10-02 15:59:55'),
(1339, 10, 4911, 'system', 'Douglas Hill Started the Meeting', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-10-02 16:00:11', '2025-10-02 16:00:11'),
(1340, 10, 9801, 'system', 'Jing Beltran Joined the Meeting', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-10-02 16:00:26', '2025-10-02 16:00:26'),
(1341, 10, 9790, 'system', 'Saffi Patino Joined the Meeting', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-10-02 16:02:38', '2025-10-02 16:02:38'),
(1342, 10, 30, 'system', 'Kimberly Ann Madelo Joined the Meeting', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-10-02 16:02:50', '2025-10-02 16:02:50'),
(1343, 10, 9814, 'system', 'Marc Sabaulan Joined the Meeting', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-10-02 16:04:14', '2025-10-02 16:04:14'),
(1344, 10, 30, 'system', 'Kimberly Ann Madelo Left the Meeting.', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-10-02 17:08:19', '2025-10-02 17:08:19'),
(1345, 10, 9790, 'system', 'Saffi Patino Left the Meeting.', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-10-02 17:29:58', '2025-10-02 17:29:58'),
(1346, 7, 30, 'text', 'Hi. I will initiate the call now.', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-10-02 18:00:07', '2025-10-02 18:00:07'),
(1347, 7, 30, 'system', 'Kimberly Ann Madelo Joined the Meeting', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-10-02 18:00:09', '2025-10-02 18:00:09'),
(1348, 7, 9801, 'system', 'Jing Beltran Joined the Meeting', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-10-02 18:00:37', '2025-10-02 18:00:37'),
(1349, 7, 9803, 'system', 'Chelynn Joined the Meeting', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-10-02 18:00:45', '2025-10-02 18:00:45'),
(1350, 7, 9790, 'system', 'Saffi Patino Joined the Meeting', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-10-02 18:01:34', '2025-10-02 18:01:34'),
(1351, 7, 9798, 'system', 'Development Team Joined the Meeting', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-10-02 18:01:56', '2025-10-02 18:01:56'),
(1352, 7, 9799, 'system', 'Joy Cantancio Joined the Meeting', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-10-02 18:03:18', '2025-10-02 18:03:18'),
(1353, 7, 9800, 'system', 'Jane Cantancio Joined the Meeting', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-10-02 18:03:34', '2025-10-02 18:03:34'),
(1354, 7, 9814, 'system', 'Marc Sabaulan Joined the Meeting', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-10-02 18:10:57', '2025-10-02 18:10:57'),
(1355, 7, 9790, 'system', 'Saffi Patino Joined the Meeting', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-10-02 18:33:35', '2025-10-02 18:33:35'),
(1356, 7, 9790, 'system', 'Saffi Patino Left the Meeting.', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-10-02 18:33:40', '2025-10-02 18:33:40'),
(1357, 7, 9790, 'system', 'Saffi Patino Joined the Meeting', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-10-02 18:33:46', '2025-10-02 18:33:46'),
(1358, 7, 9814, 'system', 'Marc Sabaulan Left the Meeting.', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-10-02 18:57:06', '2025-10-02 18:57:06'),
(1359, 7, 9799, 'system', 'Joy Cantancio Left the Meeting.', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-10-02 19:15:10', '2025-10-02 19:15:10'),
(1360, 7, 30, 'system', 'Kimberly Ann Madelo Left the Meeting.', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-10-02 19:15:16', '2025-10-02 19:15:16'),
(1361, 7, 9803, 'text', 'URGENT!!! @<br>uploading team is facing a problem now in updating projects. In MANAGE BIDDING after we click a project then click EDIT, it goes to 500 SERVER ERROR—we cannot do our tasks today such as, updating schedule, updating bidders, etc.', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-10-02 20:15:09', '2025-10-02 20:15:09'),
(1362, 7, 9803, 'text', 'PLANPANTHER CLIENT PORTAL', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-10-02 20:15:46', '2025-10-02 20:15:46'),
(1363, 7, 9798, 'text', 'Please check and try again. Server Error fixed.', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-10-02 20:58:51', '2025-10-02 20:58:51'),
(1364, 7, 9803, 'text', 'it works now. thanks', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-10-02 21:00:08', '2025-10-02 21:00:08'),
(1388, 26, 30, 'text', 'Hello! I will do the call now.', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-10-06 07:01:40', '2025-10-06 07:01:40'),
(1389, 26, 30, 'system', 'Kimberly Ann Madelo Started the Meeting', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-10-06 07:01:49', '2025-10-06 07:01:49'),
(1390, 26, 30, 'system', 'Kimberly Ann Madelo Left the Meeting.', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-10-06 07:02:24', '2025-10-06 07:02:24'),
(1391, 26, 9803, 'system', 'Chelynn Joined the Meeting', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-10-06 07:02:35', '2025-10-06 07:02:35'),
(1392, 26, 9800, 'system', 'Jane Cantancio Joined the Meeting', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-10-06 07:02:35', '2025-10-06 07:02:35'),
(1393, 26, 9799, 'system', 'Joy Cantancio Joined the Meeting', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-10-06 07:02:48', '2025-10-06 07:02:48'),
(1394, 26, 9802, 'system', 'Alfa Macasi Joined the Meeting', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-10-06 07:08:02', '2025-10-06 07:08:02'),
(1400, 17, 4911, 'text', 'Here', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-10-06 07:11:45', '2025-10-06 07:11:45'),
(1402, 17, 9790, 'system', 'Saffi Patino Started the Meeting', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-10-06 07:12:02', '2025-10-06 07:12:02'),
(1403, 17, 4911, 'text', 'Let\'s invoice !&nbsp; :)&nbsp;', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-10-06 07:12:04', '2025-10-06 07:12:04'),
(1404, 17, 4911, 'system', 'Douglas Hill Joined the Meeting', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-10-06 07:12:15', '2025-10-06 07:12:15'),
(1405, 26, 9810, 'system', 'Darwin Garote Joined the Meeting', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-10-06 07:13:17', '2025-10-06 07:13:17'),
(1406, 26, 9810, 'system', 'Darwin Garote Joined the Meeting', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-10-06 07:16:51', '2025-10-06 07:16:51'),
(1408, 26, 44, 'system', 'Juan Joined the Meeting', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-10-06 07:28:17', '2025-10-06 07:28:17'),
(1410, 26, 9810, 'system', 'Darwin Garote Joined the Meeting', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-10-06 08:02:17', '2025-10-06 08:02:17'),
(1411, 26, 44, 'system', 'Juan Left the Meeting.', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-10-06 08:06:04', '2025-10-06 08:06:04'),
(1412, 26, 9810, 'system', 'Darwin Garote Left the Meeting.', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-10-06 08:06:35', '2025-10-06 08:06:35'),
(1413, 26, 9802, 'system', 'Alfa Macasi Left the Meeting.', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-10-06 08:06:46', '2025-10-06 08:06:46'),
(1414, 26, 9803, 'system', 'Chelynn Joined the Meeting', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-10-06 08:06:56', '2025-10-06 08:06:56'),
(1415, 26, 9803, 'system', 'Chelynn Left the Meeting.', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-10-06 08:07:08', '2025-10-06 08:07:08'),
(1416, 6, 9790, 'system', 'Saffi Patino Started the Meeting', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-10-06 16:39:02', '2025-10-06 16:39:02'),
(1417, 6, 4911, 'text', 'Hello', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-10-06 16:39:11', '2025-10-06 16:39:11'),
(1418, 6, 9802, 'system', 'Alfa Macasi Started the Meeting', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-10-06 16:39:11', '2025-10-06 16:39:11'),
(1419, 6, 4911, 'system', 'Douglas Hill Joined the Meeting', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-10-06 16:39:29', '2025-10-06 16:39:29'),
(1420, 6, 9814, 'system', 'Marc Sabaulan Joined the Meeting', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-10-06 17:06:22', '2025-10-06 17:06:22'),
(1421, 6, 9814, 'system', 'Marc Sabaulan Left the Meeting.', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-10-06 17:06:40', '2025-10-06 17:06:40'),
(1422, 6, 9814, 'system', 'Marc Sabaulan Joined the Meeting', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-10-06 17:07:12', '2025-10-06 17:07:12'),
(1423, 6, 9814, 'system', 'Marc Sabaulan Left the Meeting.', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-10-06 17:10:16', '2025-10-06 17:10:16'),
(1424, 6, 4911, 'system', 'Douglas Hill Left the Meeting.', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-10-06 17:10:17', '2025-10-06 17:10:17'),
(1425, 6, 9802, 'system', 'Alfa Macasi Left the Meeting.', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-10-06 17:10:43', '2025-10-06 17:10:43'),
(1426, 7, 30, 'text', 'I will call now TEAM.', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-10-06 18:00:57', '2025-10-06 18:00:57'),
(1427, 7, 30, 'system', 'Kimberly Ann Madelo Started the Meeting', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-10-06 18:02:52', '2025-10-06 18:02:52'),
(1428, 7, 9801, 'system', 'Jing Beltran Joined the Meeting', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-10-06 18:03:28', '2025-10-06 18:03:28'),
(1429, 7, 9800, 'system', 'Jane Cantancio Joined the Meeting', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-10-06 18:04:11', '2025-10-06 18:04:11'),
(1430, 7, 9803, 'system', 'Chelynn Joined the Meeting', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-10-06 18:04:17', '2025-10-06 18:04:17'),
(1431, 7, 9800, 'system', 'Jane Cantancio Left the Meeting.', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-10-06 18:04:45', '2025-10-06 18:04:45'),
(1432, 7, 9800, 'system', 'Jane Cantancio Joined the Meeting', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-10-06 18:04:46', '2025-10-06 18:04:46'),
(1433, 7, 9790, 'system', 'Saffi Patino Left the Meeting.', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-10-06 18:04:57', '2025-10-06 18:04:57'),
(1434, 7, 30, 'system', 'Kimberly Ann Madelo Left the Meeting.', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-10-06 18:05:01', '2025-10-06 18:05:01'),
(1435, 7, 9790, 'system', 'Saffi Patino Joined the Meeting', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-10-06 18:05:31', '2025-10-06 18:05:31'),
(1436, 7, 9800, 'system', 'Jane Cantancio Left the Meeting.', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-10-06 18:05:32', '2025-10-06 18:05:32'),
(1437, 7, 9800, 'system', 'Jane Cantancio Joined the Meeting', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-10-06 18:07:25', '2025-10-06 18:07:25'),
(1438, 7, 9800, 'system', 'Jane Cantancio Left the Meeting.', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-10-06 18:07:28', '2025-10-06 18:07:28'),
(1439, 7, 9814, 'system', 'Marc Sabaulan Joined the Meeting', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-10-06 18:15:06', '2025-10-06 18:15:06'),
(1440, 7, 9814, 'system', 'Marc Sabaulan Left the Meeting.', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-10-06 18:17:06', '2025-10-06 18:17:06'),
(1441, 7, 9814, 'system', 'Marc Sabaulan Joined the Meeting', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-10-06 18:17:09', '2025-10-06 18:17:09');
INSERT INTO `chat_messages` (`id`, `conversation_id`, `user_id`, `type`, `body`, `deleted_by_moderator`, `original_body`, `reply_to_message_id`, `forwarded_from_message_id`, `forwarded_metadata`, `meta`, `edited_at`, `deleted_at`, `created_at`, `updated_at`) VALUES
(1442, 7, 4911, 'system', 'Douglas Hill Joined the Meeting', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-10-06 18:19:20', '2025-10-06 18:19:20'),
(1443, 7, 4911, 'system', 'Douglas Hill Left the Meeting.', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-10-06 18:34:36', '2025-10-06 18:34:36'),
(1444, 6, 4911, 'system', 'Douglas Hill Started the Meeting', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-10-07 06:31:26', '2025-10-07 06:31:26'),
(1445, 6, 9787, 'system', 'Sofia Dima-ano Joined the Meeting', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-10-07 06:31:33', '2025-10-07 06:31:33'),
(1446, 6, 9815, 'system', 'Faith Mikee Taccad Joined the Meeting', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-10-07 06:32:29', '2025-10-07 06:32:29'),
(1447, 6, 9790, 'system', 'Saffi Patino Joined the Meeting', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-10-07 06:32:53', '2025-10-07 06:32:53'),
(1448, 6, 9802, 'system', 'Alfa Macasi Joined the Meeting', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-10-07 06:35:24', '2025-10-07 06:35:24'),
(1449, 7, 9810, 'system', 'Darwin Garote Joined the Meeting', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-10-07 07:03:41', '2025-10-07 07:03:41'),
(1450, 7, 9810, 'system', 'Darwin Garote Left the Meeting.', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-10-07 07:03:50', '2025-10-07 07:03:50'),
(1451, 7, 9810, 'system', 'Darwin Garote Joined the Meeting', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-10-07 07:06:43', '2025-10-07 07:06:43'),
(1452, 7, 9810, 'system', 'Darwin Garote Left the Meeting.', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-10-07 07:06:51', '2025-10-07 07:06:51'),
(1453, 6, 9815, 'system', 'Faith Mikee Taccad Left the Meeting.', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-10-07 07:15:17', '2025-10-07 07:15:17'),
(1454, 6, 9802, 'system', 'Alfa Macasi Left the Meeting.', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-10-07 07:15:39', '2025-10-07 07:15:39'),
(1455, 20, 9790, 'system', 'Saffi Patino Started the Meeting', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-10-07 14:29:44', '2025-10-07 14:29:44'),
(1456, 20, 4911, 'text', 'Hello :)', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-10-07 14:31:53', '2025-10-07 14:31:53'),
(1457, 20, 4911, 'system', 'Douglas Hill Joined the Meeting', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-10-07 14:32:02', '2025-10-07 14:32:02'),
(1458, 20, 9813, 'system', 'Sita Cabeling Joined the Meeting', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-10-07 14:37:22', '2025-10-07 14:37:22'),
(1459, 20, 4911, 'system', 'Douglas Hill Left the Meeting.', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-10-07 14:47:57', '2025-10-07 14:47:57'),
(1460, 20, 9813, 'system', 'Sita Cabeling Left the Meeting.', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-10-07 14:48:14', '2025-10-07 14:48:14'),
(1461, 20, 9790, 'system', 'Saffi Patino Left the Meeting.', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-10-07 14:57:17', '2025-10-07 14:57:17'),
(1462, 7, 9814, 'system', 'Marc Sabaulan Joined the Meeting', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-10-07 16:00:58', '2025-10-07 16:00:58'),
(1463, 7, 9814, 'system', 'Marc Sabaulan Left the Meeting.', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-10-07 16:01:39', '2025-10-07 16:01:39'),
(1464, 7, 30, 'text', 'Hi everyone! I will call now.', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-10-07 18:02:08', '2025-10-07 18:02:08'),
(1465, 7, 30, 'system', 'Kimberly Ann Madelo Joined the Meeting', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-10-07 18:02:10', '2025-10-07 18:02:10'),
(1466, 7, 9801, 'system', 'Jing Beltran Joined the Meeting', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-10-07 18:02:17', '2025-10-07 18:02:17'),
(1467, 7, 9814, 'system', 'Marc Sabaulan Joined the Meeting', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-10-07 18:02:47', '2025-10-07 18:02:47'),
(1468, 7, 9803, 'system', 'Chelynn Joined the Meeting', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-10-07 18:04:08', '2025-10-07 18:04:08'),
(1469, 7, 9799, 'system', 'Joy Cantancio Joined the Meeting', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-10-07 18:04:09', '2025-10-07 18:04:09'),
(1470, 7, 9800, 'system', 'Jane Cantancio Joined the Meeting', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-10-07 18:04:23', '2025-10-07 18:04:23'),
(1471, 7, 9790, 'system', 'Saffi Patino Joined the Meeting', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-10-07 18:05:26', '2025-10-07 18:05:26'),
(1472, 7, 9803, 'text', 'the pages: A5 width: <span>5.8, length:8.3, diagonal: 7.5 inches all</span>', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-10-07 18:14:46', '2025-10-07 18:14:46'),
(1473, 7, 9803, 'text', 'cover: diagonal: 8, width: 6', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-10-07 18:16:15', '2025-10-07 18:16:15'),
(1474, 7, 9803, 'system', 'Chelynn Joined the Meeting', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-10-07 18:47:37', '2025-10-07 18:47:37'),
(1475, 7, 9814, 'system', 'Marc Sabaulan Left the Meeting.', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-10-07 19:01:31', '2025-10-07 19:01:31'),
(1476, 7, 9799, 'system', 'Joy Cantancio Left the Meeting.', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-10-07 19:06:52', '2025-10-07 19:06:52'),
(1482, 6, 4911, 'text', 'Hello Sofia and Saffi :)&nbsp;', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-10-08 06:07:54', '2025-10-08 06:07:54'),
(1486, 6, 4911, 'text', 'Sofia , r u here yet ?', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-10-08 06:08:51', '2025-10-08 06:08:51'),
(1487, 6, 9787, 'text', 'yes sir', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-10-08 06:09:27', '2025-10-08 06:09:27'),
(1488, 6, 4911, 'text', 'cool !&nbsp;', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-10-08 06:09:39', '2025-10-08 06:09:39'),
(1489, 6, 4911, 'text', 'Saffi , r u here?', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-10-08 06:09:50', '2025-10-08 06:09:50'),
(1490, 6, 9787, 'text', 'the chat date above shows october 2 on my end', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-10-08 06:10:18', '2025-10-08 06:10:18'),
(1491, 6, 9790, 'system', 'Saffi Patino Started the Meeting', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-10-08 06:10:53', '2025-10-08 06:10:53'),
(1492, 6, 4911, 'text', 'Oh?&nbsp; it shows Oct 8 for me ??', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-10-08 06:11:05', '2025-10-08 06:11:05'),
(1493, 6, 4911, 'text', 'Sofia ??', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-10-08 06:12:01', '2025-10-08 06:12:01'),
(1494, 6, 9790, 'text', 'join with me now', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-10-08 06:12:09', '2025-10-08 06:12:09'),
(1495, 6, 9787, 'text', 'this is what I can see on my end. your previous message sir appeared earlier but now it\'s gone', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-10-08 06:12:15', '2025-10-08 06:12:15'),
(1496, 6, 9787, 'system', 'Sofia Dima-ano Joined the Meeting', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-10-08 06:12:25', '2025-10-08 06:12:25'),
(1497, 6, 4911, 'text', 'wow, interesting', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-10-08 06:12:42', '2025-10-08 06:12:42'),
(1498, 6, 4911, 'system', 'Douglas Hill Joined the Meeting', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-10-08 06:13:04', '2025-10-08 06:13:04'),
(1499, 6, 9790, 'system', 'Saffi Patino Joined the Meeting', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-10-08 06:14:04', '2025-10-08 06:14:04'),
(1500, 6, 9790, 'system', 'Saffi Patino Joined the Meeting', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-10-08 06:16:53', '2025-10-08 06:16:53'),
(1501, 6, 9790, 'system', 'Saffi Patino Joined the Meeting', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-10-08 06:19:16', '2025-10-08 06:19:16'),
(1502, 6, 4911, 'system', 'Douglas Hill Left the Meeting.', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-10-08 06:41:11', '2025-10-08 06:41:11'),
(1503, 6, 9790, 'system', 'Saffi Patino Left the Meeting.', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-10-08 06:41:28', '2025-10-08 06:41:28'),
(1513, 7, 30, 'system', 'Kimberly Ann Madelo Started the Meeting', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-10-08 18:06:27', '2025-10-08 18:06:27'),
(1514, 7, 9790, 'system', 'Saffi Patino Started the Meeting', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-10-08 18:06:29', '2025-10-08 18:06:29'),
(1515, 7, 9801, 'system', 'Jing Beltran Joined the Meeting', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-10-08 18:06:44', '2025-10-08 18:06:44'),
(1516, 7, 9799, 'system', 'Joy Cantancio Joined the Meeting', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-10-08 18:07:05', '2025-10-08 18:07:05'),
(1517, 7, 9800, 'system', 'Jane Cantancio Joined the Meeting', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-10-08 18:07:13', '2025-10-08 18:07:13'),
(1518, 7, 9798, 'system', 'Development Team Joined the Meeting', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-10-08 18:08:55', '2025-10-08 18:08:55'),
(1519, 7, 30, 'system', 'Kimberly Ann Madelo Joined the Meeting', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-10-08 18:09:45', '2025-10-08 18:09:45'),
(1520, 7, 9798, 'text', 'can you try to access this. <a href=\"https://staging.hillbcs.com/\" rel=\"noopener noreferrer\" target=\"_blank\">https://staging.hillbcs.com/</a> while merging the staging to production.', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-10-08 18:10:05', '2025-10-08 18:10:05'),
(1521, 7, 4911, 'system', 'Douglas Hill Left the Meeting.', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-10-08 18:15:29', '2025-10-08 18:15:29'),
(1522, 7, 9803, 'system', 'Chelynn Joined the Meeting', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-10-08 18:50:14', '2025-10-08 18:50:14'),
(1523, 7, 9799, 'system', 'Joy Cantancio Left the Meeting.', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-10-08 19:00:47', '2025-10-08 19:00:47'),
(1524, 7, 9790, 'system', 'Saffi Patino Left the Meeting.', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-10-09 00:00:21', '2025-10-09 00:00:21'),
(1525, 6, 4911, 'system', 'Douglas Hill Started the Meeting', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-10-09 06:00:01', '2025-10-09 06:00:01'),
(1526, 6, 9790, 'system', 'Saffi Patino Started the Meeting', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-10-09 06:00:04', '2025-10-09 06:00:04'),
(1527, 6, 9787, 'system', 'Sofia Dima-ano Joined the Meeting', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-10-09 06:00:33', '2025-10-09 06:00:33'),
(1528, 6, 9802, 'system', 'Alfa Macasi Joined the Meeting', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-10-09 06:01:05', '2025-10-09 06:01:05'),
(1529, 6, 30, 'system', 'Kimberly Ann Madelo Joined the Meeting', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-10-09 06:08:20', '2025-10-09 06:08:20'),
(1530, 6, 9802, 'system', 'Alfa Macasi Left the Meeting.', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-10-09 06:13:03', '2025-10-09 06:13:03'),
(1531, 6, 9802, 'system', 'Alfa Macasi Joined the Meeting', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-10-09 06:13:22', '2025-10-09 06:13:22'),
(1532, 6, 9787, 'system', 'Sofia Dima-ano Joined the Meeting', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-10-09 06:18:45', '2025-10-09 06:18:45'),
(1533, 6, 9787, 'system', 'Sofia Dima-ano Left the Meeting.', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-10-09 06:18:48', '2025-10-09 06:18:48'),
(1534, 6, 9787, 'system', 'Sofia Dima-ano Joined the Meeting', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-10-09 06:18:49', '2025-10-09 06:18:49'),
(1535, 6, 9802, 'system', 'Alfa Macasi Left the Meeting.', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-10-09 06:19:32', '2025-10-09 06:19:32'),
(1536, 6, 9802, 'system', 'Alfa Macasi Joined the Meeting', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-10-09 06:24:05', '2025-10-09 06:24:05'),
(1537, 6, 9815, 'system', 'Faith Mikee Taccad Joined the Meeting', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-10-09 06:32:07', '2025-10-09 06:32:07'),
(1538, 6, 9787, 'system', 'Sofia Dima-ano Left the Meeting.', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-10-09 06:48:27', '2025-10-09 06:48:27'),
(1539, 6, 9802, 'system', 'Alfa Macasi Left the Meeting.', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-10-09 06:48:33', '2025-10-09 06:48:33'),
(1540, 6, 4911, 'system', 'Douglas Hill Left the Meeting.', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-10-09 07:01:40', '2025-10-09 07:01:40'),
(1541, 6, 30, 'system', 'Kimberly Ann Madelo Left the Meeting.', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-10-09 07:01:42', '2025-10-09 07:01:42'),
(1544, 10, 30, 'text', 'Goodmorning everyone! I will call now.', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-10-09 16:02:15', '2025-10-09 16:02:15'),
(1545, 10, 30, 'system', 'Kimberly Ann Madelo Started the Meeting', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-10-09 16:02:20', '2025-10-09 16:02:20'),
(1546, 10, 30, 'system', 'Kimberly Ann Madelo Left the Meeting.', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-10-09 16:04:34', '2025-10-09 16:04:34'),
(1547, 10, 30, 'text', 'Let me know if you&nbsp; are all ready.', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-10-09 16:05:11', '2025-10-09 16:05:11'),
(1548, 10, 9801, 'system', 'Jing Beltran Started the Meeting', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-10-09 16:06:40', '2025-10-09 16:06:40'),
(1549, 10, 30, 'system', 'Kimberly Ann Madelo Joined the Meeting', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-10-09 16:06:56', '2025-10-09 16:06:56'),
(1550, 10, 30, 'system', 'Kimberly Ann Madelo Left the Meeting.', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-10-09 16:08:43', '2025-10-09 16:08:43'),
(1560, 7, 30, 'text', 'Hello everyone! I will call now.\\', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-10-09 18:00:53', '2025-10-09 18:00:53'),
(1561, 7, 30, 'system', 'Kimberly Ann Madelo Joined the Meeting', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-10-09 18:01:03', '2025-10-09 18:01:03'),
(1562, 7, 30, 'system', 'Kimberly Ann Madelo Left the Meeting.', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-10-09 18:04:59', '2025-10-09 18:04:59'),
(1563, 7, 30, 'system', 'Kimberly Ann Madelo Joined the Meeting', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-10-09 18:05:06', '2025-10-09 18:05:06'),
(1564, 7, 9803, 'system', 'Chelynn Joined the Meeting', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-10-09 18:07:18', '2025-10-09 18:07:18'),
(1565, 7, 9803, 'system', 'Chelynn Joined the Meeting', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-10-09 18:51:46', '2025-10-09 18:51:46'),
(1566, 7, 9803, 'system', 'Chelynn Left the Meeting.', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-10-09 18:52:03', '2025-10-09 18:52:03'),
(1574, 6, 4911, 'system', 'Douglas Hill Joined the Meeting', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-10-10 07:03:42', '2025-10-10 07:03:42'),
(1575, 6, 4911, 'system', 'Douglas Hill Left the Meeting.', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-10-10 07:03:52', '2025-10-10 07:03:52'),
(1579, 44, 9635, 'text', 'Hey quick question i sent a proposal on the file manager does it email all GCs ?', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-10-11 17:34:08', '2025-10-11 17:34:08'),
(1581, 58, 9790, 'system', 'Saffi Patino Started the Meeting', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-10-12 17:58:14', '2025-10-12 17:58:14'),
(1582, 58, 4911, 'system', 'Douglas Hill Started the Meeting', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-10-12 18:00:21', '2025-10-12 18:00:21'),
(1583, 58, 9790, 'system', 'Saffi Patino Left the Meeting.', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-10-12 18:02:00', '2025-10-12 18:02:00'),
(1594, 58, 4911, 'system', 'Douglas Hill Started the Meeting', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-10-13 06:56:10', '2025-10-13 06:56:10'),
(1595, 58, 9790, 'system', 'Saffi Patino Started the Meeting', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-10-13 06:56:14', '2025-10-13 06:56:14'),
(1596, 44, 30, 'text', 'Good morning, Antonio. Sorry if I was not able to answer your question. As we have our work off during weekends. To answer your question, yes, when you send a proposal to a particular project, it is sent automatically to all GCs listed.', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-10-13 07:02:08', '2025-10-13 07:02:08'),
(1597, 26, 30, 'text', 'Hello everyone! I will call now.', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-10-13 07:04:05', '2025-10-13 07:04:05'),
(1598, 26, 30, 'system', 'Kimberly Ann Madelo Started the Meeting', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-10-13 07:04:07', '2025-10-13 07:04:07'),
(1599, 7, 9799, 'system', 'Joy Cantancio Joined the Meeting', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-10-13 07:04:09', '2025-10-13 07:04:09'),
(1600, 7, 9800, 'system', 'Jane Cantancio Joined the Meeting', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-10-13 07:04:40', '2025-10-13 07:04:40'),
(1601, 7, 9799, 'system', 'Joy Cantancio Left the Meeting.', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-10-13 07:04:43', '2025-10-13 07:04:43'),
(1602, 26, 9803, 'system', 'Chelynn Joined the Meeting', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-10-13 07:04:45', '2025-10-13 07:04:45'),
(1603, 26, 9799, 'system', 'Joy Cantancio Joined the Meeting', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-10-13 07:05:01', '2025-10-13 07:05:01'),
(1604, 26, 44, 'system', 'Juan Joined the Meeting', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-10-13 07:05:04', '2025-10-13 07:05:04'),
(1605, 7, 9800, 'system', 'Jane Cantancio Left the Meeting.', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-10-13 07:05:06', '2025-10-13 07:05:06'),
(1606, 26, 9800, 'system', 'Jane Cantancio Joined the Meeting', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-10-13 07:05:11', '2025-10-13 07:05:11'),
(1607, 26, 9810, 'system', 'Darwin Garote Joined the Meeting', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-10-13 07:09:51', '2025-10-13 07:09:51'),
(1608, 26, 9802, 'system', 'Alfa Macasi Joined the Meeting', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-10-13 07:16:32', '2025-10-13 07:16:32'),
(1609, 58, 4911, 'system', 'Douglas Hill Left the Meeting.', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-10-13 07:27:36', '2025-10-13 07:27:36'),
(1610, 17, 9790, 'system', 'Saffi Patino Left the Meeting.', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-10-13 07:51:06', '2025-10-13 07:51:06'),
(1611, 26, 9799, 'system', 'Joy Cantancio Left the Meeting.', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-10-13 07:58:03', '2025-10-13 07:58:03'),
(1612, 26, 9802, 'system', 'Alfa Macasi Left the Meeting.', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-10-13 07:58:28', '2025-10-13 07:58:28'),
(1613, 26, 30, 'system', 'Kimberly Ann Madelo Left the Meeting.', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-10-13 08:07:43', '2025-10-13 08:07:43'),
(1614, 68, 9810, 'text', NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-10-13 12:42:35', '2025-10-13 12:42:35'),
(1615, 68, 9810, 'text', 'Hi sir goodmorning. I am Darwin, Your Virtual Assistant. Your 3 projects will be due tommorow. Is there anything else I can do to help you with? Just let me know. Thank you&nbsp;', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-10-13 12:43:58', '2025-10-13 12:43:58'),
(1616, 69, 9810, 'text', NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-10-13 14:02:36', '2025-10-13 14:02:36'),
(1617, 69, 9810, 'text', 'Hi sir Antonio, Goodmorning. I am Darwin One of your Virtual Assistant. One of your project is due tommorow. Is there Anything else I can do and&nbsp; help you with? Just let me know. :)&nbsp;', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-10-13 14:03:44', '2025-10-13 14:03:44'),
(1618, 7, 30, 'text', 'Hi. everyone! I will call now.', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-10-13 18:00:38', '2025-10-13 18:00:38'),
(1619, 7, 30, 'system', 'Kimberly Ann Madelo Joined the Meeting', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-10-13 18:00:40', '2025-10-13 18:00:40'),
(1620, 7, 9801, 'system', 'Jing Beltran Joined the Meeting', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-10-13 18:01:21', '2025-10-13 18:01:21'),
(1621, 7, 30, 'system', 'Kimberly Ann Madelo Left the Meeting.', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-10-13 18:01:25', '2025-10-13 18:01:25'),
(1622, 7, 30, 'system', 'Kimberly Ann Madelo Joined the Meeting', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-10-13 18:01:56', '2025-10-13 18:01:56'),
(1623, 7, 9803, 'system', 'Chelynn Joined the Meeting', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-10-13 18:04:19', '2025-10-13 18:04:19'),
(1624, 7, 9800, 'system', 'Jane Cantancio Joined the Meeting', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-10-13 18:04:20', '2025-10-13 18:04:20'),
(1625, 7, 9799, 'system', 'Joy Cantancio Joined the Meeting', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-10-13 18:04:34', '2025-10-13 18:04:34'),
(1626, 7, 9799, 'system', 'Joy Cantancio Left the Meeting.', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-10-13 19:02:30', '2025-10-13 19:02:30'),
(1630, 6, 9790, 'system', 'Saffi Patino Joined the Meeting', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-10-14 06:27:01', '2025-10-14 06:27:01'),
(1631, 6, 4911, 'system', 'Douglas Hill Joined the Meeting', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-10-14 06:28:55', '2025-10-14 06:28:55'),
(1632, 6, 9802, 'system', 'Alfa Macasi Joined the Meeting', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-10-14 06:30:33', '2025-10-14 06:30:33'),
(1633, 6, 9787, 'system', 'Sofia Dima-ano Joined the Meeting', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-10-14 06:32:17', '2025-10-14 06:32:17'),
(1634, 6, 9787, 'system', 'Sofia Dima-ano Left the Meeting.', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-10-14 06:35:33', '2025-10-14 06:35:33'),
(1635, 6, 9815, 'system', 'Faith Mikee Taccad Joined the Meeting', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-10-14 06:41:46', '2025-10-14 06:41:46'),
(1636, 69, 9810, 'text', 'goodmorning', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-10-14 07:15:03', '2025-10-14 07:15:03'),
(1637, 6, 4911, 'system', 'Douglas Hill Left the Meeting.', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-10-14 07:27:24', '2025-10-14 07:27:24'),
(1638, 6, 9815, 'system', 'Faith Mikee Taccad Left the Meeting.', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-10-14 07:40:06', '2025-10-14 07:40:06'),
(1639, 6, 9802, 'system', 'Alfa Macasi Left the Meeting.', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-10-14 07:43:25', '2025-10-14 07:43:25'),
(1640, 20, 9790, 'system', 'Saffi Patino Started the Meeting', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-10-14 14:26:51', '2025-10-14 14:26:51'),
(1641, 20, 9813, 'system', 'Sita Cabeling Started the Meeting', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-10-14 14:31:28', '2025-10-14 14:31:28'),
(1642, 20, 9790, 'system', 'Saffi Patino Joined the Meeting', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-10-14 15:20:25', '2025-10-14 15:20:25'),
(1643, 20, 9790, 'system', 'Saffi Patino Left the Meeting.', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-10-14 15:48:53', '2025-10-14 15:48:53'),
(1644, 7, 30, 'text', 'I will call now.', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-10-14 18:00:48', '2025-10-14 18:00:48'),
(1645, 7, 30, 'system', 'Kimberly Ann Madelo Joined the Meeting', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-10-14 18:00:51', '2025-10-14 18:00:51'),
(1646, 7, 9803, 'system', 'Chelynn Joined the Meeting', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-10-14 18:02:52', '2025-10-14 18:02:52'),
(1647, 7, 9801, 'system', 'Jing Beltran Joined the Meeting', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-10-14 18:11:13', '2025-10-14 18:11:13'),
(1674, 7, 30, 'text', 'Hello! everyone. I will call now.', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-10-15 18:00:21', '2025-10-15 18:00:21'),
(1675, 7, 30, 'system', 'Kimberly Ann Madelo Joined the Meeting', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-10-15 18:00:36', '2025-10-15 18:00:36'),
(1676, 7, 9800, 'system', 'Jane Cantancio Joined the Meeting', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-10-15 18:03:46', '2025-10-15 18:03:46'),
(1677, 7, 9801, 'system', 'Jing Beltran Joined the Meeting', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-10-15 18:04:42', '2025-10-15 18:04:42'),
(1678, 7, 9803, 'system', 'Chelynn Joined the Meeting', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-10-15 18:05:50', '2025-10-15 18:05:50'),
(1679, 7, 9799, 'system', 'Joy Cantancio Joined the Meeting', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-10-15 18:07:29', '2025-10-15 18:07:29'),
(1680, 7, 9799, 'system', 'Joy Cantancio Left the Meeting.', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-10-15 19:07:54', '2025-10-15 19:07:54'),
(1681, 7, 9801, 'system', 'Jing Beltran Left the Meeting.', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-10-15 19:13:23', '2025-10-15 19:13:23'),
(1682, 15, 30, 'text', 'Hello! Ms. Cesilia.', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-10-16 06:17:09', '2025-10-16 06:17:09'),
(1683, 31, 9820, 'text', 'Ok I\'m here', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-10-16 06:24:22', '2025-10-16 06:24:22'),
(1684, 31, 30, 'text', 'Alright. I will call you now.', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-10-16 06:25:24', '2025-10-16 06:25:24'),
(1685, 31, 30, 'system', 'Kimberly Ann Madelo Started the Meeting', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-10-16 06:25:35', '2025-10-16 06:25:35'),
(1686, 31, 9820, 'system', 'Cesilia Cortez Joined the Meeting', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-10-16 06:27:57', '2025-10-16 06:27:57'),
(1687, 6, 9790, 'system', 'Saffi Patino Joined the Meeting', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-10-16 06:29:31', '2025-10-16 06:29:31'),
(1688, 6, 9787, 'system', 'Sofia Dima-ano Joined the Meeting', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-10-16 06:29:36', '2025-10-16 06:29:36'),
(1689, 6, 4911, 'system', 'Douglas Hill Joined the Meeting', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-10-16 06:30:21', '2025-10-16 06:30:21'),
(1690, 31, 9820, 'system', 'Cesilia Cortez Left the Meeting.', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-10-16 06:32:11', '2025-10-16 06:32:11'),
(1691, 6, 9802, 'system', 'Alfa Macasi Joined the Meeting', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-10-16 06:32:11', '2025-10-16 06:32:11'),
(1692, 6, 9815, 'system', 'Faith Mikee Taccad Joined the Meeting', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-10-16 06:32:32', '2025-10-16 06:32:32'),
(1693, 6, 4911, 'system', 'Douglas Hill Joined the Meeting', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-10-16 07:11:31', '2025-10-16 07:11:31'),
(1694, 6, 4911, 'system', 'Douglas Hill Left the Meeting.', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-10-16 07:38:58', '2025-10-16 07:38:58'),
(1695, 6, 9815, 'system', 'Faith Mikee Taccad Left the Meeting.', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-10-16 07:39:38', '2025-10-16 07:39:38'),
(1696, 6, 9802, 'system', 'Alfa Macasi Left the Meeting.', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-10-16 07:39:46', '2025-10-16 07:39:46'),
(1697, 2, 4911, 'text', 'Hello', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-10-16 15:56:52', '2025-10-16 15:56:52'),
(1698, 6, 4911, 'system', 'Douglas Hill Joined the Meeting', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-10-16 15:58:46', '2025-10-16 15:58:46'),
(1699, 6, 4911, 'system', 'Douglas Hill Left the Meeting.', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-10-16 15:59:03', '2025-10-16 15:59:03'),
(1700, 2, 4911, 'system', 'Douglas Hill Started the Meeting', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-10-16 15:59:24', '2025-10-16 15:59:24'),
(1701, 2, 4911, 'system', 'Douglas Hill Left the Meeting.', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-10-16 16:00:12', '2025-10-16 16:00:12'),
(1702, 10, 9801, 'system', 'Jing Beltran Started the Meeting', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-10-16 16:00:14', '2025-10-16 16:00:14'),
(1703, 10, 30, 'system', 'Kimberly Ann Madelo Joined the Meeting', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-10-16 16:03:20', '2025-10-16 16:03:20'),
(1704, 10, 4911, 'system', 'Douglas Hill Joined the Meeting', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-10-16 16:04:04', '2025-10-16 16:04:04'),
(1705, 10, 9790, 'system', 'Saffi Patino Joined the Meeting', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-10-16 16:10:07', '2025-10-16 16:10:07'),
(1706, 10, 9801, 'system', 'Jing Beltran Left the Meeting.', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-10-16 16:27:58', '2025-10-16 16:27:58'),
(1707, 10, 4911, 'system', 'Douglas Hill Left the Meeting.', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-10-16 16:28:06', '2025-10-16 16:28:06'),
(1708, 10, 30, 'system', 'Kimberly Ann Madelo Left the Meeting.', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-10-16 16:28:10', '2025-10-16 16:28:10'),
(1709, 44, 9635, 'text', 'thank you kimberly', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-10-16 17:51:32', '2025-10-16 17:51:32'),
(1710, 69, 9635, 'text', 'good afternoon', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-10-16 17:51:57', '2025-10-16 17:51:57'),
(1711, 7, 30, 'text', 'Hi everyone! I will call now.', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-10-16 18:00:15', '2025-10-16 18:00:15'),
(1712, 7, 30, 'system', 'Kimberly Ann Madelo Joined the Meeting', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-10-16 18:00:18', '2025-10-16 18:00:18'),
(1713, 7, 9800, 'system', 'Jane Cantancio Joined the Meeting', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-10-16 18:01:16', '2025-10-16 18:01:16'),
(1714, 7, 9803, 'system', 'Chelynn Joined the Meeting', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-10-16 18:02:46', '2025-10-16 18:02:46'),
(1715, 7, 9803, 'system', 'Chelynn Joined the Meeting', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-10-16 18:02:46', '2025-10-16 18:02:46'),
(1716, 7, 9802, 'system', 'Alfa Macasi Joined the Meeting', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-10-16 18:06:47', '2025-10-16 18:06:47'),
(1717, 7, 9799, 'system', 'Joy Cantancio Joined the Meeting', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-10-16 18:07:58', '2025-10-16 18:07:58'),
(1718, 7, 9799, 'system', 'Joy Cantancio Left the Meeting.', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-10-16 19:00:42', '2025-10-16 19:00:42'),
(1719, 7, 9802, 'system', 'Alfa Macasi Left the Meeting.', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-10-16 19:00:45', '2025-10-16 19:00:45'),
(1720, 7, 30, 'system', 'Kimberly Ann Madelo Left the Meeting.', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-10-16 19:01:10', '2025-10-16 19:01:10'),
(1721, 6, 9787, 'system', 'Sofia Dima-ano Left the Meeting.', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-10-16 20:00:30', '2025-10-16 20:00:30'),
(1731, 39, 9810, 'text', 'Goodmorning sir', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-10-17 07:04:30', '2025-10-17 07:04:30'),
(1732, 68, 9810, 'text', 'Goodmorning Sir, How can I help you today?', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-10-17 07:04:48', '2025-10-17 07:04:48'),
(1733, 72, 9810, 'text', 'Goodmroning cecelia, How can I help you today?', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-10-17 07:11:57', '2025-10-17 07:11:57'),
(1734, 56, 9810, 'text', 'Goodmorning maam saffi,&nbsp; Ho can I assist you today?', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-10-17 07:13:23', '2025-10-17 07:13:23'),
(1737, 44, 30, 'text', 'You are welcome Antonio. Don\'t hesitate to use the portal and if there is any concern and problem you encounter in the portal just always reach me out here.', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-10-18 03:41:18', '2025-10-18 03:41:18'),
(1743, 39, 9810, 'text', 'Goodmorning', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-10-20 06:00:46', '2025-10-20 06:00:46'),
(1744, 56, 9810, 'text', 'goodmorning&nbsp;', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-10-20 06:00:55', '2025-10-20 06:00:55'),
(1748, 17, 4911, 'system', 'Douglas Hill Started the Meeting', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-10-20 06:03:24', '2025-10-20 06:03:24'),
(1749, 17, 9812, 'system', 'Luisa Patino Joined the Meeting', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-10-20 06:03:32', '2025-10-20 06:03:32'),
(1750, 17, 9790, 'system', 'Saffi Patino Started the Meeting', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-10-20 06:03:43', '2025-10-20 06:03:43'),
(1751, 73, 9787, 'text', 'Hello Miss Cesilia, good morning. My name is Sofia, the Social Media/Marketing Director. We\'re here to assist you. Let us know if you need any help. Have a great day!', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-10-20 06:20:00', '2025-10-20 06:20:00'),
(1752, 17, 4911, 'system', 'Douglas Hill Left the Meeting.', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-10-20 06:25:20', '2025-10-20 06:25:20'),
(1755, 26, 30, 'text', 'Hello! everyone!. I will be late just a few minutes.&nbsp;', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-10-20 07:03:14', '2025-10-20 07:03:14'),
(1756, 26, 30, 'system', 'Kimberly Ann Madelo Left the Meeting.', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-10-20 07:04:25', '2025-10-20 07:04:25'),
(1757, 26, 30, 'system', 'Kimberly Ann Madelo Joined the Meeting', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-10-20 07:04:31', '2025-10-20 07:04:31'),
(1758, 26, 30, 'text', 'Where are you guys?', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-10-20 07:06:13', '2025-10-20 07:06:13'),
(1759, 26, 9799, 'system', 'Joy Cantancio Joined the Meeting', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-10-20 07:06:22', '2025-10-20 07:06:22'),
(1760, 26, 9800, 'system', 'Jane Cantancio Joined the Meeting', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-10-20 07:06:29', '2025-10-20 07:06:29'),
(1761, 26, 9803, 'system', 'Chelynn Joined the Meeting', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-10-20 07:06:29', '2025-10-20 07:06:29'),
(1762, 26, 44, 'system', 'Juan Joined the Meeting', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-10-20 07:09:15', '2025-10-20 07:09:15'),
(1763, 26, 9802, 'system', 'Alfa Macasi Joined the Meeting', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-10-20 07:16:13', '2025-10-20 07:16:13'),
(1764, 26, 9810, 'system', 'Darwin Garote Joined the Meeting', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-10-20 07:17:11', '2025-10-20 07:17:11'),
(1765, 26, 9810, 'system', 'Darwin Garote Left the Meeting.', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-10-20 07:18:15', '2025-10-20 07:18:15'),
(1766, 26, 9810, 'system', 'Darwin Garote Joined the Meeting', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-10-20 07:19:27', '2025-10-20 07:19:27'),
(1768, 26, 4911, 'system', 'Douglas Hill Joined the Meeting', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-10-20 07:33:27', '2025-10-20 07:33:27'),
(1769, 26, 4911, 'system', 'Douglas Hill Left the Meeting.', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-10-20 07:33:30', '2025-10-20 07:33:30'),
(1770, 26, 4911, 'system', 'Douglas Hill Joined the Meeting', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-10-20 07:33:36', '2025-10-20 07:33:36'),
(1771, 26, 9790, 'system', 'Saffi Patino Joined the Meeting', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-10-20 07:33:49', '2025-10-20 07:33:49'),
(1772, 26, 9799, 'system', 'Joy Cantancio Left the Meeting.', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-10-20 07:49:08', '2025-10-20 07:49:08'),
(1773, 26, 4911, 'system', 'Douglas Hill Left the Meeting.', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-10-20 07:49:10', '2025-10-20 07:49:10'),
(1774, 26, 9790, 'system', 'Saffi Patino Left the Meeting.', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-10-20 07:49:14', '2025-10-20 07:49:14'),
(1775, 26, 9802, 'system', 'Alfa Macasi Left the Meeting.', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-10-20 07:49:28', '2025-10-20 07:49:28'),
(1776, 16, 9790, 'text', '<span><p>Hello everyone,</p><p>&nbsp;</p><p>We will have a meeting today at 5 PM California time, which is 8 AM Philippine time.</p><p>&nbsp;</p><p>I hope everyone can attend. Thank you.</p></span>', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-10-20 08:15:10', '2025-10-20 08:15:10'),
(1777, 69, 9810, 'text', 'Goodmorning sir, How can I help you today?', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-10-20 09:35:31', '2025-10-20 09:35:31'),
(1778, 68, 9810, 'text', '<div class=\"bubble me\" style=\"--tw-border-spacing-x: 0; --tw-border-spacing-y: 0; --tw-translate-x: 0; --tw-translate-y: 0; --tw-rotate: 0; --tw-skew-x: 0; --tw-skew-y: 0; --tw-scale-x: 1; --tw-scale-y: 1; --tw-pan-x: ; --tw-pan-y: ; --tw-pinch-zoom: ; --tw-scroll-snap-strictness: proximity; --tw-gradient-from-position: ; --tw-gradient-via-position: ; --tw-gradient-to-position: ; --tw-ordinal: ; --tw-slashed-zero: ; --tw-numeric-figure: ; --tw-numeric-spacing: ; --tw-numeric-fraction: ; --tw-ring-inset: ; --tw-ring-offset-width: 0px; --tw-ring-offset-color: #fff; --tw-ring-color: rgb(59 130 246 / 0.5); --tw-ring-offset-shadow: 0 0 #0000; --tw-ring-shadow: 0 0 #0000; --tw-shadow: 0 0 #0000; --tw-shadow-colored: 0 0 #0000; --tw-blur: ; --tw-brightness: ; --tw-contrast: ; --tw-grayscale: ; --tw-hue-rotate: ; --tw-invert: ; --tw-saturate: ; --tw-sepia: ; --tw-drop-shadow: ; --tw-backdrop-blur: ; --tw-backdrop-brightness: ; --tw-backdrop-contrast: ; --tw-backdrop-grayscale: ; --tw-backdrop-hue-rotate: ; --tw-backdrop-invert: ; --tw-backdrop-opacity: ; --tw-backdrop-saturate: ; --tw-backdrop-sepia: ; --tw-contain-size: ; --tw-contain-layout: ; --tw-contain-paint: ; --tw-contain-style: ;\">Goodmorning sir, How can I help you today?</div><div class=\"mt-1 flex items-center gap-2 justify-end\" style=\"--tw-border-spacing-x: 0; --tw-border-spacing-y: 0; --tw-translate-x: 0; --tw-translate-y: 0; --tw-rotate: 0; --tw-skew-x: 0; --tw-skew-y: 0; --tw-scale-x: 1; --tw-scale-y: 1; --tw-pan-x: ; --tw-pan-y: ; --tw-pinch-zoom: ; --tw-scroll-snap-strictness: proximity; --tw-gradient-from-position: ; --tw-gradient-via-position: ; --tw-gradient-to-position: ; --tw-ordinal: ; --tw-slashed-zero: ; --tw-numeric-figure: ; --tw-numeric-spacing: ; --tw-numeric-fraction: ; --tw-ring-inset: ; --tw-ring-offset-width: 0px; --tw-ring-offset-color: #fff; --tw-ring-color: rgb(59 130 246 / 0.5); --tw-ring-offset-shadow: 0 0 #0000; --tw-ring-shadow: 0 0 #0000; --tw-shadow: 0 0 #0000; --tw-shadow-colored: 0 0 #0000; --tw-blur: ; --tw-brightness: ; --tw-contrast: ; --tw-grayscale: ; --tw-hue-rotate: ; --tw-invert: ; --tw-saturate: ; --tw-sepia: ; --tw-drop-shadow: ; --tw-backdrop-blur: ; --tw-backdrop-brightness: ; --tw-backdrop-contrast: ; --tw-backdrop-grayscale: ; --tw-backdrop-hue-rotate: ; --tw-backdrop-invert: ; --tw-backdrop-opacity: ; --tw-backdrop-saturate: ; --tw-backdrop-sepia: ; --tw-contain-size: ; --tw-contain-layout: ; --tw-contain-paint: ; --tw-contain-style: ;\"><span class=\"stamp\" style=\"--tw-border-spacing-x: 0; --tw-border-spacing-y: 0; --tw-translate-x: 0; --tw-translate-y: 0; --tw-rotate: 0; --tw-skew-x: 0; --tw-skew-y: 0; --tw-scale-x: 1; --tw-scale-y: 1; --tw-pan-x: ; --tw-pan-y: ; --tw-pinch-zoom: ; --tw-scroll-snap-strictness: proximity; --tw-gradient-from-position: ; --tw-gradient-via-position: ; --tw-gradient-to-position: ; --tw-ordinal: ; --tw-slashed-zero: ; --tw-numeric-figure: ; --tw-numeric-spacing: ; --tw-numeric-fraction: ; --tw-ring-inset: ; --tw-ring-offset-width: 0px; --tw-ring-offset-color: #fff; --tw-ring-color: rgb(59 130 246 / 0.5); --tw-ring-offset-shadow: 0 0 #0000; --tw-ring-shadow: 0 0 #0000; --tw-shadow: 0 0 #0000; --tw-shadow-colored: 0 0 #0000; --tw-blur: ; --tw-brightness: ; --tw-contrast: ; --tw-grayscale: ; --tw-hue-rotate: ; --tw-invert: ; --tw-saturate: ; --tw-sepia: ; --tw-drop-shadow: ; --tw-backdrop-blur: ; --tw-backdrop-brightness: ; --tw-backdrop-contrast: ; --tw-backdrop-grayscale: ; --tw-backdrop-hue-rotate: ; --tw-backdrop-invert: ; --tw-backdrop-opacity: ; --tw-backdrop-saturate: ; --tw-backdrop-sepia: ; --tw-contain-size: ; --tw-contain-layout: ; --tw-contain-paint: ; --tw-contain-style: ;\">9:35 AM</span><div class=\"hs-dropdown relative inline-flex [--trigger:hover]\" style=\"--tw-border-spacing-x: 0; --tw-border-spacing-y: 0; --tw-translate-x: 0; --tw-translate-y: 0; --tw-rotate: 0; --tw-skew-x: 0; --tw-skew-y: 0; --tw-scale-x: 1; --tw-scale-y: 1; --tw-pan-x: ; --tw-pan-y: ; --tw-pinch-zoom: ; --tw-scroll-snap-strictness: proximity; --tw-gradient-from-position: ; --tw-gradient-via-position: ; --tw-gradient-to-position: ; --tw-ordinal: ; --tw-slashed-zero: ; --tw-numeric-figure: ; --tw-numeric-spacing: ; --tw-numeric-fraction: ; --tw-ring-inset: ; --tw-ring-offset-width: 0px; --tw-ring-offset-color: #fff; --tw-ring-color: rgb(59 130 246 / 0.5); --tw-ring-offset-shadow: 0 0 #0000; --tw-ring-shadow: 0 0 #0000; --tw-shadow: 0 0 #0000; --tw-shadow-colored: 0 0 #0000; --tw-blur: ; --tw-brightness: ; --tw-contrast: ; --tw-grayscale: ; --tw-hue-rotate: ; --tw-invert: ; --tw-saturate: ; --tw-sepia: ; --tw-drop-shadow: ; --tw-backdrop-blur: ; --tw-backdrop-brightness: ; --tw-backdrop-contrast: ; --tw-backdrop-grayscale: ; --tw-backdrop-hue-rotate: ; --tw-backdrop-invert: ; --tw-backdrop-opacity: ; --tw-backdrop-saturate: ; --tw-backdrop-sepia: ; --tw-contain-size: ; --tw-contain-layout: ; --tw-contain-paint: ; --tw-contain-style: ; --trigger: hover;\"><button type=\"button\" class=\"hs-dropdown-toggle ml-1 p-1.5 rounded-md hover:bg-blue-600/10 text-white/80\" style=\"--tw-border-spacing-x: 0; --tw-border-spacing-y: 0; --tw-translate-x: 0; --tw-translate-y: 0; --tw-rotate: 0; --tw-skew-x: 0; --tw-skew-y: 0; --tw-scale-x: 1; --tw-scale-y: 1; --tw-pan-x: ; --tw-pan-y: ; --tw-pinch-zoom: ; --tw-scroll-snap-strictness: proximity; --tw-gradient-from-position: ; --tw-gradient-via-position: ; --tw-gradient-to-position: ; --tw-ordinal: ; --tw-slashed-zero: ; --tw-numeric-figure: ; --tw-numeric-spacing: ; --tw-numeric-fraction: ; --tw-ring-inset: ; --tw-ring-offset-width: 0px; --tw-ring-offset-color: #fff; --tw-ring-color: rgb(59 130 246 / 0.5); --tw-ring-offset-shadow: 0 0 #0000; --tw-ring-shadow: 0 0 #0000; --tw-shadow: 0 0 #0000; --tw-shadow-colored: 0 0 #0000; --tw-blur: ; --tw-brightness: ; --tw-contrast: ; --tw-grayscale: ; --tw-hue-rotate: ; --tw-invert: ; --tw-saturate: ; --tw-sepia: ; --tw-drop-shadow: ; --tw-backdrop-blur: ; --tw-backdrop-brightness: ; --tw-backdrop-contrast: ; --tw-backdrop-grayscale: ; --tw-backdrop-hue-rotate: ; --tw-backdrop-invert: ; --tw-backdrop-opacity: ; --tw-backdrop-saturate: ; --tw-backdrop-sepia: ; --tw-contain-size: ; --tw-contain-layout: ; --tw-contain-paint: ; --tw-contain-style: ; font-size: 13.008px;\"><i class=\"bi bi-three-dots\" style=\"--tw-border-spacing-x: 0; --tw-border-spacing-y: 0; --tw-translate-x: 0; --tw-translate-y: 0; --tw-rotate: 0; --tw-skew-x: 0; --tw-skew-y: 0; --tw-scale-x: 1; --tw-scale-y: 1; --tw-pan-x: ; --tw-pan-y: ; --tw-pinch-zoom: ; --tw-scroll-snap-strictness: proximity; --tw-gradient-from-position: ; --tw-gradient-via-position: ; --tw-gradient-to-position: ; --tw-ordinal: ; --tw-slashed-zero: ; --tw-numeric-figure: ; --tw-numeric-spacing: ; --tw-numeric-fraction: ; --tw-ring-inset: ; --tw-ring-offset-width: 0px; --tw-ring-offset-color: #fff; --tw-ring-color: rgb(59 130 246 / 0.5); --tw-ring-offset-shadow: 0 0 #0000; --tw-ring-shadow: 0 0 #0000; --tw-shadow: 0 0 #0000; --tw-shadow-colored: 0 0 #0000; --tw-blur: ; --tw-brightness: ; --tw-contrast: ; --tw-grayscale: ; --tw-hue-rotate: ; --tw-invert: ; --tw-saturate: ; --tw-sepia: ; --tw-drop-shadow: ; --tw-backdrop-blur: ; --tw-backdrop-brightness: ; --tw-backdrop-contrast: ; --tw-backdrop-grayscale: ; --tw-backdrop-hue-rotate: ; --tw-backdrop-invert: ; --tw-backdrop-opacity: ; --tw-backdrop-saturate: ; --tw-backdrop-sepia: ; --tw-contain-size: ; --tw-contain-layout: ; --tw-contain-paint: ; --tw-contain-style: ;\"></i></button></div></div>', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-10-20 09:55:49', '2025-10-20 09:55:49');
INSERT INTO `chat_messages` (`id`, `conversation_id`, `user_id`, `type`, `body`, `deleted_by_moderator`, `original_body`, `reply_to_message_id`, `forwarded_from_message_id`, `forwarded_metadata`, `meta`, `edited_at`, `deleted_at`, `created_at`, `updated_at`) VALUES
(1779, 27, 9810, 'text', '<div class=\"bubble me\" style=\"--tw-border-spacing-x: 0; --tw-border-spacing-y: 0; --tw-translate-x: 0; --tw-translate-y: 0; --tw-rotate: 0; --tw-skew-x: 0; --tw-skew-y: 0; --tw-scale-x: 1; --tw-scale-y: 1; --tw-pan-x: ; --tw-pan-y: ; --tw-pinch-zoom: ; --tw-scroll-snap-strictness: proximity; --tw-gradient-from-position: ; --tw-gradient-via-position: ; --tw-gradient-to-position: ; --tw-ordinal: ; --tw-slashed-zero: ; --tw-numeric-figure: ; --tw-numeric-spacing: ; --tw-numeric-fraction: ; --tw-ring-inset: ; --tw-ring-offset-width: 0px; --tw-ring-offset-color: #fff; --tw-ring-color: rgb(59 130 246 / 0.5); --tw-ring-offset-shadow: 0 0 #0000; --tw-ring-shadow: 0 0 #0000; --tw-shadow: 0 0 #0000; --tw-shadow-colored: 0 0 #0000; --tw-blur: ; --tw-brightness: ; --tw-contrast: ; --tw-grayscale: ; --tw-hue-rotate: ; --tw-invert: ; --tw-saturate: ; --tw-sepia: ; --tw-drop-shadow: ; --tw-backdrop-blur: ; --tw-backdrop-brightness: ; --tw-backdrop-contrast: ; --tw-backdrop-grayscale: ; --tw-backdrop-hue-rotate: ; --tw-backdrop-invert: ; --tw-backdrop-opacity: ; --tw-backdrop-saturate: ; --tw-backdrop-sepia: ; --tw-contain-size: ; --tw-contain-layout: ; --tw-contain-paint: ; --tw-contain-style: ;\">Goodmorning sir, How can I help you today?</div><div class=\"mt-1 flex items-center gap-2 justify-end\" style=\"--tw-border-spacing-x: 0; --tw-border-spacing-y: 0; --tw-translate-x: 0; --tw-translate-y: 0; --tw-rotate: 0; --tw-skew-x: 0; --tw-skew-y: 0; --tw-scale-x: 1; --tw-scale-y: 1; --tw-pan-x: ; --tw-pan-y: ; --tw-pinch-zoom: ; --tw-scroll-snap-strictness: proximity; --tw-gradient-from-position: ; --tw-gradient-via-position: ; --tw-gradient-to-position: ; --tw-ordinal: ; --tw-slashed-zero: ; --tw-numeric-figure: ; --tw-numeric-spacing: ; --tw-numeric-fraction: ; --tw-ring-inset: ; --tw-ring-offset-width: 0px; --tw-ring-offset-color: #fff; --tw-ring-color: rgb(59 130 246 / 0.5); --tw-ring-offset-shadow: 0 0 #0000; --tw-ring-shadow: 0 0 #0000; --tw-shadow: 0 0 #0000; --tw-shadow-colored: 0 0 #0000; --tw-blur: ; --tw-brightness: ; --tw-contrast: ; --tw-grayscale: ; --tw-hue-rotate: ; --tw-invert: ; --tw-saturate: ; --tw-sepia: ; --tw-drop-shadow: ; --tw-backdrop-blur: ; --tw-backdrop-brightness: ; --tw-backdrop-contrast: ; --tw-backdrop-grayscale: ; --tw-backdrop-hue-rotate: ; --tw-backdrop-invert: ; --tw-backdrop-opacity: ; --tw-backdrop-saturate: ; --tw-backdrop-sepia: ; --tw-contain-size: ; --tw-contain-layout: ; --tw-contain-paint: ; --tw-contain-style: ;\"><span class=\"stamp\" style=\"--tw-border-spacing-x: 0; --tw-border-spacing-y: 0; --tw-translate-x: 0; --tw-translate-y: 0; --tw-rotate: 0; --tw-skew-x: 0; --tw-skew-y: 0; --tw-scale-x: 1; --tw-scale-y: 1; --tw-pan-x: ; --tw-pan-y: ; --tw-pinch-zoom: ; --tw-scroll-snap-strictness: proximity; --tw-gradient-from-position: ; --tw-gradient-via-position: ; --tw-gradient-to-position: ; --tw-ordinal: ; --tw-slashed-zero: ; --tw-numeric-figure: ; --tw-numeric-spacing: ; --tw-numeric-fraction: ; --tw-ring-inset: ; --tw-ring-offset-width: 0px; --tw-ring-offset-color: #fff; --tw-ring-color: rgb(59 130 246 / 0.5); --tw-ring-offset-shadow: 0 0 #0000; --tw-ring-shadow: 0 0 #0000; --tw-shadow: 0 0 #0000; --tw-shadow-colored: 0 0 #0000; --tw-blur: ; --tw-brightness: ; --tw-contrast: ; --tw-grayscale: ; --tw-hue-rotate: ; --tw-invert: ; --tw-saturate: ; --tw-sepia: ; --tw-drop-shadow: ; --tw-backdrop-blur: ; --tw-backdrop-brightness: ; --tw-backdrop-contrast: ; --tw-backdrop-grayscale: ; --tw-backdrop-hue-rotate: ; --tw-backdrop-invert: ; --tw-backdrop-opacity: ; --tw-backdrop-saturate: ; --tw-backdrop-sepia: ; --tw-contain-size: ; --tw-contain-layout: ; --tw-contain-paint: ; --tw-contain-style: ;\">9:35 AM</span><div class=\"hs-dropdown relative inline-flex [--trigger:hover]\" style=\"--tw-border-spacing-x: 0; --tw-border-spacing-y: 0; --tw-translate-x: 0; --tw-translate-y: 0; --tw-rotate: 0; --tw-skew-x: 0; --tw-skew-y: 0; --tw-scale-x: 1; --tw-scale-y: 1; --tw-pan-x: ; --tw-pan-y: ; --tw-pinch-zoom: ; --tw-scroll-snap-strictness: proximity; --tw-gradient-from-position: ; --tw-gradient-via-position: ; --tw-gradient-to-position: ; --tw-ordinal: ; --tw-slashed-zero: ; --tw-numeric-figure: ; --tw-numeric-spacing: ; --tw-numeric-fraction: ; --tw-ring-inset: ; --tw-ring-offset-width: 0px; --tw-ring-offset-color: #fff; --tw-ring-color: rgb(59 130 246 / 0.5); --tw-ring-offset-shadow: 0 0 #0000; --tw-ring-shadow: 0 0 #0000; --tw-shadow: 0 0 #0000; --tw-shadow-colored: 0 0 #0000; --tw-blur: ; --tw-brightness: ; --tw-contrast: ; --tw-grayscale: ; --tw-hue-rotate: ; --tw-invert: ; --tw-saturate: ; --tw-sepia: ; --tw-drop-shadow: ; --tw-backdrop-blur: ; --tw-backdrop-brightness: ; --tw-backdrop-contrast: ; --tw-backdrop-grayscale: ; --tw-backdrop-hue-rotate: ; --tw-backdrop-invert: ; --tw-backdrop-opacity: ; --tw-backdrop-saturate: ; --tw-backdrop-sepia: ; --tw-contain-size: ; --tw-contain-layout: ; --tw-contain-paint: ; --tw-contain-style: ; --trigger: hover;\"><button type=\"button\" class=\"hs-dropdown-toggle ml-1 p-1.5 rounded-md hover:bg-blue-600/10 text-white/80\" style=\"--tw-border-spacing-x: 0; --tw-border-spacing-y: 0; --tw-translate-x: 0; --tw-translate-y: 0; --tw-rotate: 0; --tw-skew-x: 0; --tw-skew-y: 0; --tw-scale-x: 1; --tw-scale-y: 1; --tw-pan-x: ; --tw-pan-y: ; --tw-pinch-zoom: ; --tw-scroll-snap-strictness: proximity; --tw-gradient-from-position: ; --tw-gradient-via-position: ; --tw-gradient-to-position: ; --tw-ordinal: ; --tw-slashed-zero: ; --tw-numeric-figure: ; --tw-numeric-spacing: ; --tw-numeric-fraction: ; --tw-ring-inset: ; --tw-ring-offset-width: 0px; --tw-ring-offset-color: #fff; --tw-ring-color: rgb(59 130 246 / 0.5); --tw-ring-offset-shadow: 0 0 #0000; --tw-ring-shadow: 0 0 #0000; --tw-shadow: 0 0 #0000; --tw-shadow-colored: 0 0 #0000; --tw-blur: ; --tw-brightness: ; --tw-contrast: ; --tw-grayscale: ; --tw-hue-rotate: ; --tw-invert: ; --tw-saturate: ; --tw-sepia: ; --tw-drop-shadow: ; --tw-backdrop-blur: ; --tw-backdrop-brightness: ; --tw-backdrop-contrast: ; --tw-backdrop-grayscale: ; --tw-backdrop-hue-rotate: ; --tw-backdrop-invert: ; --tw-backdrop-opacity: ; --tw-backdrop-saturate: ; --tw-backdrop-sepia: ; --tw-contain-size: ; --tw-contain-layout: ; --tw-contain-paint: ; --tw-contain-style: ; font-size: 13.008px;\"><i class=\"bi bi-three-dots\" style=\"--tw-border-spacing-x: 0; --tw-border-spacing-y: 0; --tw-translate-x: 0; --tw-translate-y: 0; --tw-rotate: 0; --tw-skew-x: 0; --tw-skew-y: 0; --tw-scale-x: 1; --tw-scale-y: 1; --tw-pan-x: ; --tw-pan-y: ; --tw-pinch-zoom: ; --tw-scroll-snap-strictness: proximity; --tw-gradient-from-position: ; --tw-gradient-via-position: ; --tw-gradient-to-position: ; --tw-ordinal: ; --tw-slashed-zero: ; --tw-numeric-figure: ; --tw-numeric-spacing: ; --tw-numeric-fraction: ; --tw-ring-inset: ; --tw-ring-offset-width: 0px; --tw-ring-offset-color: #fff; --tw-ring-color: rgb(59 130 246 / 0.5); --tw-ring-offset-shadow: 0 0 #0000; --tw-ring-shadow: 0 0 #0000; --tw-shadow: 0 0 #0000; --tw-shadow-colored: 0 0 #0000; --tw-blur: ; --tw-brightness: ; --tw-contrast: ; --tw-grayscale: ; --tw-hue-rotate: ; --tw-invert: ; --tw-saturate: ; --tw-sepia: ; --tw-drop-shadow: ; --tw-backdrop-blur: ; --tw-backdrop-brightness: ; --tw-backdrop-contrast: ; --tw-backdrop-grayscale: ; --tw-backdrop-hue-rotate: ; --tw-backdrop-invert: ; --tw-backdrop-opacity: ; --tw-backdrop-saturate: ; --tw-backdrop-sepia: ; --tw-contain-size: ; --tw-contain-layout: ; --tw-contain-paint: ; --tw-contain-style: ;\"></i></button></div></div>', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-10-20 09:55:58', '2025-10-20 09:55:58'),
(1780, 55, 9810, 'text', '<div class=\"bubble me\" style=\"--tw-border-spacing-x: 0; --tw-border-spacing-y: 0; --tw-translate-x: 0; --tw-translate-y: 0; --tw-rotate: 0; --tw-skew-x: 0; --tw-skew-y: 0; --tw-scale-x: 1; --tw-scale-y: 1; --tw-pan-x: ; --tw-pan-y: ; --tw-pinch-zoom: ; --tw-scroll-snap-strictness: proximity; --tw-gradient-from-position: ; --tw-gradient-via-position: ; --tw-gradient-to-position: ; --tw-ordinal: ; --tw-slashed-zero: ; --tw-numeric-figure: ; --tw-numeric-spacing: ; --tw-numeric-fraction: ; --tw-ring-inset: ; --tw-ring-offset-width: 0px; --tw-ring-offset-color: #fff; --tw-ring-color: rgb(59 130 246 / 0.5); --tw-ring-offset-shadow: 0 0 #0000; --tw-ring-shadow: 0 0 #0000; --tw-shadow: 0 0 #0000; --tw-shadow-colored: 0 0 #0000; --tw-blur: ; --tw-brightness: ; --tw-contrast: ; --tw-grayscale: ; --tw-hue-rotate: ; --tw-invert: ; --tw-saturate: ; --tw-sepia: ; --tw-drop-shadow: ; --tw-backdrop-blur: ; --tw-backdrop-brightness: ; --tw-backdrop-contrast: ; --tw-backdrop-grayscale: ; --tw-backdrop-hue-rotate: ; --tw-backdrop-invert: ; --tw-backdrop-opacity: ; --tw-backdrop-saturate: ; --tw-backdrop-sepia: ; --tw-contain-size: ; --tw-contain-layout: ; --tw-contain-paint: ; --tw-contain-style: ;\">Goodmorning sir, How can I help you today?</div><div class=\"mt-1 flex items-center gap-2 justify-end\" style=\"--tw-border-spacing-x: 0; --tw-border-spacing-y: 0; --tw-translate-x: 0; --tw-translate-y: 0; --tw-rotate: 0; --tw-skew-x: 0; --tw-skew-y: 0; --tw-scale-x: 1; --tw-scale-y: 1; --tw-pan-x: ; --tw-pan-y: ; --tw-pinch-zoom: ; --tw-scroll-snap-strictness: proximity; --tw-gradient-from-position: ; --tw-gradient-via-position: ; --tw-gradient-to-position: ; --tw-ordinal: ; --tw-slashed-zero: ; --tw-numeric-figure: ; --tw-numeric-spacing: ; --tw-numeric-fraction: ; --tw-ring-inset: ; --tw-ring-offset-width: 0px; --tw-ring-offset-color: #fff; --tw-ring-color: rgb(59 130 246 / 0.5); --tw-ring-offset-shadow: 0 0 #0000; --tw-ring-shadow: 0 0 #0000; --tw-shadow: 0 0 #0000; --tw-shadow-colored: 0 0 #0000; --tw-blur: ; --tw-brightness: ; --tw-contrast: ; --tw-grayscale: ; --tw-hue-rotate: ; --tw-invert: ; --tw-saturate: ; --tw-sepia: ; --tw-drop-shadow: ; --tw-backdrop-blur: ; --tw-backdrop-brightness: ; --tw-backdrop-contrast: ; --tw-backdrop-grayscale: ; --tw-backdrop-hue-rotate: ; --tw-backdrop-invert: ; --tw-backdrop-opacity: ; --tw-backdrop-saturate: ; --tw-backdrop-sepia: ; --tw-contain-size: ; --tw-contain-layout: ; --tw-contain-paint: ; --tw-contain-style: ;\"><span class=\"stamp\" style=\"--tw-border-spacing-x: 0; --tw-border-spacing-y: 0; --tw-translate-x: 0; --tw-translate-y: 0; --tw-rotate: 0; --tw-skew-x: 0; --tw-skew-y: 0; --tw-scale-x: 1; --tw-scale-y: 1; --tw-pan-x: ; --tw-pan-y: ; --tw-pinch-zoom: ; --tw-scroll-snap-strictness: proximity; --tw-gradient-from-position: ; --tw-gradient-via-position: ; --tw-gradient-to-position: ; --tw-ordinal: ; --tw-slashed-zero: ; --tw-numeric-figure: ; --tw-numeric-spacing: ; --tw-numeric-fraction: ; --tw-ring-inset: ; --tw-ring-offset-width: 0px; --tw-ring-offset-color: #fff; --tw-ring-color: rgb(59 130 246 / 0.5); --tw-ring-offset-shadow: 0 0 #0000; --tw-ring-shadow: 0 0 #0000; --tw-shadow: 0 0 #0000; --tw-shadow-colored: 0 0 #0000; --tw-blur: ; --tw-brightness: ; --tw-contrast: ; --tw-grayscale: ; --tw-hue-rotate: ; --tw-invert: ; --tw-saturate: ; --tw-sepia: ; --tw-drop-shadow: ; --tw-backdrop-blur: ; --tw-backdrop-brightness: ; --tw-backdrop-contrast: ; --tw-backdrop-grayscale: ; --tw-backdrop-hue-rotate: ; --tw-backdrop-invert: ; --tw-backdrop-opacity: ; --tw-backdrop-saturate: ; --tw-backdrop-sepia: ; --tw-contain-size: ; --tw-contain-layout: ; --tw-contain-paint: ; --tw-contain-style: ;\">9:35 AM</span><div class=\"hs-dropdown relative inline-flex [--trigger:hover]\" style=\"--tw-border-spacing-x: 0; --tw-border-spacing-y: 0; --tw-translate-x: 0; --tw-translate-y: 0; --tw-rotate: 0; --tw-skew-x: 0; --tw-skew-y: 0; --tw-scale-x: 1; --tw-scale-y: 1; --tw-pan-x: ; --tw-pan-y: ; --tw-pinch-zoom: ; --tw-scroll-snap-strictness: proximity; --tw-gradient-from-position: ; --tw-gradient-via-position: ; --tw-gradient-to-position: ; --tw-ordinal: ; --tw-slashed-zero: ; --tw-numeric-figure: ; --tw-numeric-spacing: ; --tw-numeric-fraction: ; --tw-ring-inset: ; --tw-ring-offset-width: 0px; --tw-ring-offset-color: #fff; --tw-ring-color: rgb(59 130 246 / 0.5); --tw-ring-offset-shadow: 0 0 #0000; --tw-ring-shadow: 0 0 #0000; --tw-shadow: 0 0 #0000; --tw-shadow-colored: 0 0 #0000; --tw-blur: ; --tw-brightness: ; --tw-contrast: ; --tw-grayscale: ; --tw-hue-rotate: ; --tw-invert: ; --tw-saturate: ; --tw-sepia: ; --tw-drop-shadow: ; --tw-backdrop-blur: ; --tw-backdrop-brightness: ; --tw-backdrop-contrast: ; --tw-backdrop-grayscale: ; --tw-backdrop-hue-rotate: ; --tw-backdrop-invert: ; --tw-backdrop-opacity: ; --tw-backdrop-saturate: ; --tw-backdrop-sepia: ; --tw-contain-size: ; --tw-contain-layout: ; --tw-contain-paint: ; --tw-contain-style: ; --trigger: hover;\"><button type=\"button\" class=\"hs-dropdown-toggle ml-1 p-1.5 rounded-md hover:bg-blue-600/10 text-white/80\" style=\"--tw-border-spacing-x: 0; --tw-border-spacing-y: 0; --tw-translate-x: 0; --tw-translate-y: 0; --tw-rotate: 0; --tw-skew-x: 0; --tw-skew-y: 0; --tw-scale-x: 1; --tw-scale-y: 1; --tw-pan-x: ; --tw-pan-y: ; --tw-pinch-zoom: ; --tw-scroll-snap-strictness: proximity; --tw-gradient-from-position: ; --tw-gradient-via-position: ; --tw-gradient-to-position: ; --tw-ordinal: ; --tw-slashed-zero: ; --tw-numeric-figure: ; --tw-numeric-spacing: ; --tw-numeric-fraction: ; --tw-ring-inset: ; --tw-ring-offset-width: 0px; --tw-ring-offset-color: #fff; --tw-ring-color: rgb(59 130 246 / 0.5); --tw-ring-offset-shadow: 0 0 #0000; --tw-ring-shadow: 0 0 #0000; --tw-shadow: 0 0 #0000; --tw-shadow-colored: 0 0 #0000; --tw-blur: ; --tw-brightness: ; --tw-contrast: ; --tw-grayscale: ; --tw-hue-rotate: ; --tw-invert: ; --tw-saturate: ; --tw-sepia: ; --tw-drop-shadow: ; --tw-backdrop-blur: ; --tw-backdrop-brightness: ; --tw-backdrop-contrast: ; --tw-backdrop-grayscale: ; --tw-backdrop-hue-rotate: ; --tw-backdrop-invert: ; --tw-backdrop-opacity: ; --tw-backdrop-saturate: ; --tw-backdrop-sepia: ; --tw-contain-size: ; --tw-contain-layout: ; --tw-contain-paint: ; --tw-contain-style: ; font-size: 13.008px;\"><i class=\"bi bi-three-dots\" style=\"--tw-border-spacing-x: 0; --tw-border-spacing-y: 0; --tw-translate-x: 0; --tw-translate-y: 0; --tw-rotate: 0; --tw-skew-x: 0; --tw-skew-y: 0; --tw-scale-x: 1; --tw-scale-y: 1; --tw-pan-x: ; --tw-pan-y: ; --tw-pinch-zoom: ; --tw-scroll-snap-strictness: proximity; --tw-gradient-from-position: ; --tw-gradient-via-position: ; --tw-gradient-to-position: ; --tw-ordinal: ; --tw-slashed-zero: ; --tw-numeric-figure: ; --tw-numeric-spacing: ; --tw-numeric-fraction: ; --tw-ring-inset: ; --tw-ring-offset-width: 0px; --tw-ring-offset-color: #fff; --tw-ring-color: rgb(59 130 246 / 0.5); --tw-ring-offset-shadow: 0 0 #0000; --tw-ring-shadow: 0 0 #0000; --tw-shadow: 0 0 #0000; --tw-shadow-colored: 0 0 #0000; --tw-blur: ; --tw-brightness: ; --tw-contrast: ; --tw-grayscale: ; --tw-hue-rotate: ; --tw-invert: ; --tw-saturate: ; --tw-sepia: ; --tw-drop-shadow: ; --tw-backdrop-blur: ; --tw-backdrop-brightness: ; --tw-backdrop-contrast: ; --tw-backdrop-grayscale: ; --tw-backdrop-hue-rotate: ; --tw-backdrop-invert: ; --tw-backdrop-opacity: ; --tw-backdrop-saturate: ; --tw-backdrop-sepia: ; --tw-contain-size: ; --tw-contain-layout: ; --tw-contain-paint: ; --tw-contain-style: ;\"></i></button></div></div>', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-10-20 09:56:04', '2025-10-20 09:56:04'),
(1781, 72, 9810, 'text', '<span>Goodmroning cecelia, How can I help you today?</span>', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-10-20 12:20:13', '2025-10-20 12:20:13'),
(1782, 16, 9790, 'system', 'Saffi Patino Started the Meeting', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-10-20 16:58:03', '2025-10-20 16:58:03'),
(1783, 16, 9813, 'system', 'Sita Cabeling Joined the Meeting', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-10-20 16:59:16', '2025-10-20 16:59:16'),
(1784, 16, 30, 'system', 'Kimberly Ann Madelo Joined the Meeting', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-10-20 17:01:14', '2025-10-20 17:01:14'),
(1785, 16, 9787, 'system', 'Sofia Dima-ano Joined the Meeting', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-10-20 17:10:33', '2025-10-20 17:10:33'),
(1786, 16, 9813, 'system', 'Sita Cabeling Joined the Meeting', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-10-20 17:11:53', '2025-10-20 17:11:53'),
(1787, 16, 9787, 'system', 'Sofia Dima-ano Left the Meeting.', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-10-20 17:31:27', '2025-10-20 17:31:27'),
(1788, 7, 30, 'text', 'Hello! team. I will Call now.', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-10-20 18:00:21', '2025-10-20 18:00:21'),
(1789, 7, 30, 'system', 'Kimberly Ann Madelo Joined the Meeting', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-10-20 18:00:26', '2025-10-20 18:00:26'),
(1790, 7, 9801, 'system', 'Jing Beltran Joined the Meeting', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-10-20 18:01:24', '2025-10-20 18:01:24'),
(1791, 7, 9803, 'system', 'Chelynn Joined the Meeting', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-10-20 18:02:31', '2025-10-20 18:02:31'),
(1792, 7, 9800, 'system', 'Jane Cantancio Joined the Meeting', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-10-20 18:03:29', '2025-10-20 18:03:29'),
(1793, 7, 9799, 'system', 'Joy Cantancio Joined the Meeting', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-10-20 18:04:42', '2025-10-20 18:04:42'),
(1794, 7, 30, 'system', 'Kimberly Ann Madelo Left the Meeting.', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-10-20 18:13:56', '2025-10-20 18:13:56'),
(1795, 7, 30, 'system', 'Kimberly Ann Madelo Joined the Meeting', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-10-20 18:16:00', '2025-10-20 18:16:00'),
(1796, 7, 9799, 'system', 'Joy Cantancio Left the Meeting.', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-10-20 19:06:37', '2025-10-20 19:06:37'),
(1797, 7, 30, 'system', 'Kimberly Ann Madelo Left the Meeting.', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-10-20 19:06:56', '2025-10-20 19:06:56'),
(1798, 71, 5965, 'text', 'test', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-11-21 03:38:11', '2025-11-21 03:38:11'),
(1799, 71, 30, 'text', 'yow', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-11-21 03:39:05', '2025-11-21 03:39:05'),
(1800, 71, 30, 'text', 'ee', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-11-21 03:39:21', '2025-11-21 03:39:21'),
(1818, 11, 9798, 'text', 'test', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-11-21 05:52:25', '2025-11-21 05:52:25'),
(1821, 12, 9790, 'text', 'tedt', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-11-21 06:07:43', '2025-11-21 06:07:43'),
(1828, 3, 9790, 'text', 'wwww', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-11-21 07:15:52', '2025-11-21 07:15:52'),
(1829, 3, 9790, 'text', 'bay', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-11-21 07:28:20', '2025-11-21 07:28:20'),
(1830, 3, 9790, 'text', 'uy', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-11-21 07:31:20', '2025-11-21 07:31:20'),
(1831, 3, 9798, 'text', 'unsa man ?', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-11-21 07:31:40', '2025-11-21 07:31:40'),
(1832, 11, 9790, 'text', 'hey', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-11-21 07:34:57', '2025-11-21 07:34:57'),
(1833, 11, 9790, 'text', 'kuan', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-11-21 07:35:15', '2025-11-21 07:35:15'),
(1834, 11, 9798, 'text', 'i', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-11-21 07:42:19', '2025-11-21 07:42:19'),
(1835, 11, 9790, 'text', 'test', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-11-21 07:42:32', '2025-11-21 07:42:32'),
(1836, 11, 9798, 'text', 'what ?', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-11-21 07:42:37', '2025-11-21 07:42:37'),
(1837, 11, 9790, 'text', 'yow', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-11-21 07:47:39', '2025-11-21 07:47:39'),
(1838, 11, 9798, 'text', 'checking..', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-11-21 07:47:50', '2025-11-21 07:47:50'),
(1839, 11, 9790, 'text', 'what ?', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-11-21 07:47:55', '2025-11-21 07:47:55'),
(1840, 27, 44, 'text', 'ss', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-11-21 11:20:59', '2025-11-21 11:20:59'),
(1841, 11, 9798, 'text', 'pak!', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-11-21 13:11:38', '2025-11-21 13:11:38'),
(1842, 11, 9798, 'text', 'yes ?', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-11-21 13:11:46', '2025-11-21 13:11:46'),
(1844, 11, 4911, 'text', 'hey', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-11-21 14:24:09', '2025-11-21 14:24:09'),
(1845, 11, 9798, 'text', 'nice', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-11-21 14:24:16', '2025-11-21 14:24:16'),
(1846, 11, 9798, 'text', 'douglas', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-11-21 14:37:31', '2025-11-21 14:37:31'),
(1847, 2, 9790, 'text', 'I have concerns.', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-11-21 14:48:26', '2025-11-21 14:48:26'),
(1848, 2, 4911, 'text', 'what is it ?', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-11-21 14:48:37', '2025-11-21 14:48:37'),
(1849, 2, 9790, 'text', 'can i call you ?', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-11-21 14:48:50', '2025-11-21 14:48:50'),
(1850, 2, 9790, 'text', 'hey', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-11-21 14:48:56', '2025-11-21 14:48:56'),
(1851, 2, 9790, 'text', 'yow', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-11-21 14:49:22', '2025-11-21 14:49:22'),
(1852, 2, 4911, 'text', '[System] This is a test activity message.', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-11-21 15:01:34', '2025-11-21 15:01:34'),
(1853, 2, 4911, 'text', '[System] This is a test activity message.', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-11-21 15:02:06', '2025-11-21 15:02:06'),
(1854, 2, 9790, 'text', '[System] This is a test activity message.', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-11-21 15:02:24', '2025-11-21 15:02:24'),
(1855, 2, 4911, 'system', '[System] This is a test activity message.', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-11-21 15:09:43', '2025-11-21 15:09:43'),
(1856, 2, 4911, 'text', '<b>aws asd </b>sadsadsad', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-11-21 15:21:50', '2025-11-21 15:21:50'),
(1857, 2, 4911, 'text', NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-11-21 15:22:33', '2025-11-21 15:22:33'),
(1858, 2, 4911, 'text', NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-11-21 15:25:30', '2025-11-21 15:25:30'),
(1859, 2, 4911, 'text', '[System] This is a test activity message.', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-11-21 15:28:28', '2025-11-21 15:28:28'),
(1860, 2, 4911, 'text', 'test', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-11-21 15:46:44', '2025-11-21 15:46:44'),
(1861, 2, 9790, 'text', 'ey', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-11-21 15:47:27', '2025-11-21 15:47:27'),
(1862, 2, 4911, 'text', 'pak', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-11-21 15:53:31', '2025-11-21 15:53:31'),
(1863, 80, 4911, 'text', 'hey', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-11-21 16:27:37', '2025-11-21 16:27:37'),
(1864, 81, 4911, 'text', 'hey', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-11-21 16:28:16', '2025-11-21 16:28:16'),
(1865, 79, 4911, 'text', '[System] This is a test activity message.', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-11-21 16:33:51', '2025-11-21 16:33:51'),
(1866, 81, 9798, 'text', 'what ?', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-11-21 16:35:07', '2025-11-21 16:35:07'),
(1867, 82, 4911, 'text', 'hello', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-11-21 16:47:31', '2025-11-21 16:47:31'),
(1868, 83, 4911, 'text', 'QWS', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-11-21 16:48:16', '2025-11-21 16:48:16'),
(1869, 80, 9798, 'text', '<b>test\r\n</b><i>asda</i>', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-11-21 17:10:59', '2025-11-21 17:10:59'),
(1870, 2, 4911, 'text', 'asd <b>asdasdds</b>', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-11-21 17:13:57', '2025-11-21 17:13:57'),
(1875, 80, 9798, 'text', 'ee', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-11-21 17:58:39', '2025-11-21 17:58:39'),
(1876, 80, 9798, 'text', 'wtf', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-11-21 17:58:51', '2025-11-21 17:58:51'),
(1877, 80, 4911, 'text', 'okay', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-11-21 17:59:01', '2025-11-21 17:59:01'),
(1881, 80, 4911, 'text', 'test', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-11-21 18:01:54', '2025-11-21 18:01:54'),
(1882, 80, 4911, 'text', 'hey', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-11-21 18:19:45', '2025-11-21 18:19:45'),
(1883, 80, 9798, 'text', 'what ?', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-11-21 18:19:53', '2025-11-21 18:19:53'),
(1884, 84, 4911, 'text', 'Joe!', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-11-21 18:20:11', '2025-11-21 18:20:11'),
(1885, 81, 4911, 'text', 'buddy', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-11-21 18:20:28', '2025-11-21 18:20:28'),
(1886, 81, 9798, 'text', 'what ?', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-11-21 18:20:34', '2025-11-21 18:20:34'),
(1887, 11, 9798, 'text', 'testing', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-11-21 18:20:50', '2025-11-21 18:20:50'),
(1888, 85, 9798, 'text', 'wwww', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-11-21 18:21:54', '2025-11-21 18:21:54'),
(1889, 97, 4911, 'text', 'te', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-11-21 19:36:30', '2025-11-21 19:36:30'),
(1890, 99, 9798, 'text', 'parikoy', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-11-21 19:48:57', '2025-11-21 19:48:57'),
(1891, 99, 4911, 'system', '<strong>Douglas Hill</strong> started a video meeting. <a href=\"https://meet.hillbcs.com/conv-99#config.prejoinPageEnabled=false&config.disableDeepLinking=true&userInfo.displayName=Douglas+Hill&userInfo.email=douglas.hill2012%40gmail.com\" target=\"_blank\" rel=\"noopener noreferrer\" class=\"underline\">Join now</a>', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-11-21 19:53:59', '2025-11-21 19:53:59'),
(1892, 99, 9798, 'system', '<strong>Development Team</strong> started a video meeting. <a href=\"https://meet.hillbcs.com/conv-99#config.prejoinPageEnabled=false&config.disableDeepLinking=true&userInfo.displayName=Development+Team&userInfo.email=kentrussellcasino.12%40gmail.com\" target=\"_blank\" rel=\"noopener noreferrer\" class=\"underline\">Join now</a>', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-11-21 19:54:11', '2025-11-21 19:54:11'),
(1897, 11, 9798, 'text', 'hey', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-11-21 20:09:09', '2025-11-21 20:09:09'),
(1898, 11, 4911, 'text', '<strong>Douglas Hill</strong> join a video meeting', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-11-21 20:09:22', '2025-11-21 20:09:22'),
(1899, 11, 4911, 'text', '<strong>Douglas Hill</strong> join a video meeting', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-11-21 20:10:06', '2025-11-21 20:10:06'),
(1900, 99, 9798, 'text', 'hey', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-11-21 20:12:17', '2025-11-21 20:12:17'),
(1901, 99, 9798, 'system', '<strong>Development Team</strong> join a video meeting', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-11-21 20:12:24', '2025-11-21 20:12:24'),
(1902, 99, 4911, 'system', '<strong>Douglas Hill</strong> join a video meeting', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-11-21 20:12:50', '2025-11-21 20:12:50'),
(1903, 11, 4911, 'system', '<strong>Douglas Hill</strong> join a video meeting', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-11-21 20:13:04', '2025-11-21 20:13:04'),
(1904, 100, 9831, 'text', 'Hey.', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-11-22 12:36:17', '2025-11-22 12:36:17'),
(1907, 18, 44, 'text', 'test', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-11-22 14:21:52', '2025-11-22 14:21:52'),
(1908, 108, 4, 'text', 'hi', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-12-06 07:02:00', '2025-12-06 07:02:00'),
(1909, 108, 6, 'text', 'dawda', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-12-06 07:02:07', '2025-12-06 07:02:07'),
(1910, 108, 4, 'text', 'hi', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-12-06 07:23:40', '2025-12-06 07:23:40'),
(1911, 108, 6, 'text', 'hello', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-12-06 07:23:52', '2025-12-06 07:23:52'),
(1912, 108, 6, 'text', 'hii', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-12-06 07:33:05', '2025-12-06 07:33:05'),
(1913, 108, 6, 'text', 'hi', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-12-06 07:42:16', '2025-12-06 07:42:16'),
(1914, 108, 4, 'text', 'hh', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-12-07 05:31:23', '2025-12-07 05:31:23'),
(1915, 109, 4, 'text', 'hi', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-12-07 08:51:03', '2025-12-07 08:51:03'),
(1916, 108, 6, 'text', 'hii', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-12-07 08:53:52', '2025-12-07 08:53:52'),
(1917, 108, 6, 'text', 'fafa', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-12-07 09:00:25', '2025-12-07 09:00:25'),
(1918, 108, 6, 'text', 'fafaffafa', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-12-07 09:15:33', '2025-12-07 09:15:33'),
(1919, 108, 6, 'text', 'tsetstes', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-12-07 09:21:13', '2025-12-07 09:21:13'),
(1920, 108, 6, 'text', 'dada', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-12-07 09:24:49', '2025-12-07 09:24:49'),
(1921, 108, 6, 'text', 'ffafafa', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-12-07 09:31:00', '2025-12-07 09:31:00'),
(1922, 108, 6, 'text', 'dadad', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-12-07 09:31:38', '2025-12-07 09:31:38'),
(1923, 108, 6, 'text', 'tsets', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-12-07 09:38:29', '2025-12-07 09:38:29'),
(1924, 108, 6, 'text', 'hii', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-12-07 09:39:23', '2025-12-07 09:39:23'),
(1925, 108, 4, 'gif', 'https://media.tenor.com/as7eiIp_r98AAAAC/uga-georgia.gif', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-12-07 09:50:05', '2025-12-07 09:50:05'),
(1926, 109, 8, 'text', 'hello', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-12-07 23:34:25', '2025-12-07 23:34:25'),
(1927, 16, 7, 'text', 'fsfs', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-12-23 09:08:09', '2025-12-23 09:08:09'),
(1928, 109, 7, 'text', 'ewwe', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-12-23 09:46:15', '2025-12-23 09:46:15'),
(1929, 7, 7, 'text', 'eqeweq', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-12-23 09:46:44', '2025-12-23 09:46:44'),
(1930, 109, 7, 'text', 'dada', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-12-23 09:49:46', '2025-12-23 09:49:46'),
(1931, 7, 7, 'text', 'dad', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-12-23 10:17:16', '2025-12-23 10:17:16'),
(1932, 7, 7, 'text', 'dada\'', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-12-23 10:19:33', '2025-12-23 10:19:33'),
(1933, 7, 7, 'text', 'dada', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-12-23 10:19:40', '2025-12-23 10:19:40'),
(1934, 16, 7, 'text', 'dzdz', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-12-23 10:20:55', '2025-12-23 10:20:55'),
(1935, 109, 7, 'text', 'dadad', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-12-23 10:23:34', '2025-12-23 10:23:34'),
(1936, 26, 7, 'text', 'dadw', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-12-23 11:37:38', '2025-12-23 11:37:38'),
(1937, 26, 7, 'text', 'dsd', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-12-23 11:37:50', '2025-12-23 11:37:50'),
(1938, 26, 7, 'text', 'dasd', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-12-23 11:48:39', '2025-12-23 11:48:39'),
(1939, 26, 7, 'text', 'dad', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-12-23 11:48:52', '2025-12-23 11:48:52'),
(1940, 26, 7, 'text', 'fsfa', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-12-23 11:50:42', '2025-12-23 11:50:42'),
(1941, 26, 7, 'text', 'dada', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-12-23 12:24:26', '2025-12-23 12:24:26'),
(1942, 26, 7, 'text', 'd', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-12-23 12:29:22', '2025-12-23 12:29:22'),
(1943, 26, 7, 'text', 'dd', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-12-23 12:29:47', '2025-12-23 12:29:47'),
(1944, 26, 7, 'text', 'dd', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-12-23 12:53:12', '2025-12-23 12:53:12'),
(1945, 26, 7, 'text', 'ddd', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-12-23 12:53:19', '2025-12-23 12:53:19'),
(1946, 26, 7, 'text', 'd', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-12-23 12:53:25', '2025-12-23 12:53:25'),
(1947, 26, 7, 'text', 'dd', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-12-23 12:53:27', '2025-12-23 12:53:27'),
(1948, 26, 7, 'text', 'ddd', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-12-23 12:53:34', '2025-12-23 12:53:34'),
(1949, 26, 7, 'text', 'dd', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-12-23 12:53:40', '2025-12-23 12:53:40'),
(1950, 26, 7, 'text', 'dd', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-12-23 12:53:47', '2025-12-23 12:53:47'),
(1951, 26, 7, 'text', 'dd', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-12-23 12:53:53', '2025-12-23 12:53:53'),
(1952, 26, 7, 'text', 'ddd', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-12-23 12:54:00', '2025-12-23 12:54:00'),
(1953, 26, 7, 'text', 'e', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-12-23 12:58:58', '2025-12-23 12:58:58'),
(1954, 109, 4, 'system', NULL, 1, 'hello', NULL, NULL, NULL, NULL, NULL, NULL, '2025-12-23 22:50:14', '2025-12-23 23:53:34'),
(1955, 109, 7, 'text', 'heyy', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-12-23 22:50:41', '2025-12-23 22:50:41'),
(1956, 109, 7, 'text', 'im gonna mute you', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-12-23 22:50:55', '2025-12-23 22:50:55'),
(1957, 109, 7, 'text', 'sasS', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-12-23 22:52:17', '2025-12-23 22:52:17'),
(1958, 109, 7, 'text', 'dd', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-12-23 23:01:48', '2025-12-23 23:01:48'),
(1959, 109, 7, 'text', 'ff', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-12-23 23:01:54', '2025-12-23 23:01:54'),
(1960, 109, 7, 'text', 'ff', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-12-23 23:02:01', '2025-12-23 23:02:01'),
(1961, 109, 7, 'text', 'dd', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-12-23 23:25:16', '2025-12-23 23:25:16'),
(1962, 109, 7, 'text', 'hjii', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-12-23 23:51:33', '2025-12-23 23:51:33'),
(1963, 109, 7, 'text', 'hrllo', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-12-23 23:51:51', '2025-12-23 23:51:51'),
(1964, 109, 4, 'text', 'dd', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-12-24 00:03:24', '2025-12-24 00:03:24'),
(1965, 109, 4, 'text', 'ehhehe', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-12-24 00:03:26', '2025-12-24 00:03:26'),
(1966, 109, 7, 'video', NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-12-24 00:57:56', '2025-12-24 00:57:56'),
(1967, 109, 4, 'text', 'hiddd', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-12-24 01:57:46', '2025-12-24 01:57:46'),
(1968, 109, 7, 'text', 'ff', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-12-24 01:59:02', '2025-12-24 01:59:02'),
(1969, 109, 4, 'text', 'ulol', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-12-24 01:59:10', '2025-12-24 01:59:10'),
(1970, 109, 7, 'text', 'fsf', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-12-24 02:05:45', '2025-12-24 02:05:45'),
(1971, 109, 4, 'text', 'fsfsfs', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-12-24 02:05:49', '2025-12-24 02:05:49'),
(1972, 109, 7, 'system', 'A moderator added Test User to the group', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-12-24 02:42:21', '2025-12-24 02:42:21'),
(1973, 109, 4, 'text', 'heeheh', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-12-24 02:42:44', '2025-12-24 02:42:44'),
(1974, 109, 7, 'system', 'Test User was removed by a moderator', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-12-24 02:43:06', '2025-12-24 02:43:06'),
(1975, 109, 7, 'system', 'A moderator added Test User to the group', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-12-24 02:49:09', '2025-12-24 02:49:09'),
(1976, 109, 7, 'system', 'Test User was removed by a moderator', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-12-24 02:50:39', '2025-12-24 02:50:39'),
(1977, 109, 7, 'system', 'A moderator added Test User to the group', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-12-24 03:51:43', '2025-12-24 03:51:43'),
(1978, 109, 7, 'text', 'announcement', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-12-24 04:10:18', '2025-12-24 04:10:18'),
(1979, 109, 7, 'text', '<p>dzdz</p>', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-12-24 05:21:56', '2025-12-24 05:21:56'),
(1980, 109, 7, 'text', '<p>dwda</p><p><br></p>', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-12-24 05:24:41', '2025-12-24 05:24:41');

-- --------------------------------------------------------

--
-- Table structure for table `chat_message_pins`
--

CREATE TABLE `chat_message_pins` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `conversation_id` bigint(20) UNSIGNED NOT NULL,
  `message_id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `chat_message_pins`
--

INSERT INTO `chat_message_pins` (`id`, `conversation_id`, `message_id`, `user_id`, `created_at`, `updated_at`) VALUES
(1, 108, 1910, 4, '2025-12-07 05:20:55', '2025-12-07 05:20:55'),
(3, 109, 1978, 7, '2025-12-24 04:10:20', '2025-12-24 04:10:20');

-- --------------------------------------------------------

--
-- Table structure for table `chat_message_reactions`
--

CREATE TABLE `chat_message_reactions` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `message_id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `reaction` varchar(32) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `chat_message_reactions`
--

INSERT INTO `chat_message_reactions` (`id`, `message_id`, `user_id`, `reaction`, `created_at`, `updated_at`) VALUES
(4, 1913, 4, '😆', '2025-12-07 00:12:41', '2025-12-07 00:12:41'),
(6, 1913, 6, '😀', '2025-12-07 05:11:21', '2025-12-07 05:11:21'),
(7, 1914, 6, '😂', '2025-12-07 05:31:37', '2025-12-07 05:31:37'),
(8, 1912, 6, '👍', '2025-12-07 05:39:17', '2025-12-07 05:39:17'),
(9, 1922, 7, '😂', '2025-12-23 06:00:13', '2025-12-23 06:00:13'),
(10, 1954, 4, '🤨', '2025-12-23 22:50:21', '2025-12-23 22:50:21'),
(11, 1954, 7, '🤣', '2025-12-23 22:50:28', '2025-12-23 22:50:28'),
(12, 1956, 4, '😱', '2025-12-23 22:51:05', '2025-12-23 22:51:05'),
(13, 1962, 7, '😃', '2025-12-23 23:51:42', '2025-12-23 23:51:42'),
(14, 1962, 4, '😀', '2025-12-23 23:51:47', '2025-12-23 23:51:47'),
(15, 1966, 4, '😀', '2025-12-24 01:00:40', '2025-12-24 01:00:40');

-- --------------------------------------------------------

--
-- Table structure for table `chat_message_reads`
--

CREATE TABLE `chat_message_reads` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `message_id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `read_at` timestamp NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `chat_message_reports`
--

CREATE TABLE `chat_message_reports` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `message_id` bigint(20) UNSIGNED NOT NULL,
  `reporter_id` bigint(20) UNSIGNED NOT NULL,
  `conversation_id` bigint(20) UNSIGNED DEFAULT NULL,
  `reason` text DEFAULT NULL,
  `context` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`context`)),
  `status` enum('pending','reviewing','action_taken','dismissed') NOT NULL DEFAULT 'pending',
  `resolved_by` bigint(20) UNSIGNED DEFAULT NULL,
  `resolved_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `chat_message_user_states`
--

CREATE TABLE `chat_message_user_states` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `message_id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `state` enum('manual_unread','saved_for_later') NOT NULL DEFAULT 'manual_unread',
  `set_at` timestamp NULL DEFAULT NULL,
  `metadata` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`metadata`)),
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `chat_moderation_events`
--

CREATE TABLE `chat_moderation_events` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `rule_id` bigint(20) UNSIGNED DEFAULT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `message_id` bigint(20) UNSIGNED DEFAULT NULL,
  `bot_id` bigint(20) UNSIGNED DEFAULT NULL,
  `action_taken` varchar(255) NOT NULL,
  `occurred_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `payload` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`payload`)),
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `chat_moderation_rules`
--

CREATE TABLE `chat_moderation_rules` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `conversation_id` bigint(20) UNSIGNED DEFAULT NULL,
  `created_by` bigint(20) UNSIGNED DEFAULT NULL,
  `bot_id` bigint(20) UNSIGNED DEFAULT NULL,
  `name` varchar(255) NOT NULL,
  `match_type` varchar(255) NOT NULL DEFAULT 'phrase',
  `pattern` text NOT NULL,
  `action` varchar(255) NOT NULL DEFAULT 'block',
  `action_duration_seconds` int(10) UNSIGNED DEFAULT NULL,
  `escalation_threshold` int(10) UNSIGNED DEFAULT NULL,
  `auto_resolve` tinyint(1) NOT NULL DEFAULT 1,
  `metadata` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`metadata`)),
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `chat_presence_statuses`
--

CREATE TABLE `chat_presence_statuses` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `status` varchar(255) NOT NULL DEFAULT 'offline',
  `platform` varchar(255) DEFAULT NULL,
  `custom_status` varchar(255) DEFAULT NULL,
  `last_active_at` timestamp NULL DEFAULT NULL,
  `expires_at` timestamp NULL DEFAULT NULL,
  `device_context` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`device_context`)),
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `chat_presence_statuses`
--

INSERT INTO `chat_presence_statuses` (`id`, `user_id`, `status`, `platform`, `custom_status`, `last_active_at`, `expires_at`, `device_context`, `created_at`, `updated_at`) VALUES
(1, 4, 'online', 'web', NULL, '2025-12-24 06:29:34', '2025-12-24 06:34:34', NULL, '2025-12-06 23:20:25', '2025-12-24 06:29:34'),
(2, 6, 'online', 'web', NULL, '2025-12-07 23:43:28', '2025-12-07 23:48:28', NULL, '2025-12-06 23:20:27', '2025-12-07 23:43:28'),
(3, 8, 'online', 'web', NULL, '2025-12-08 00:44:52', '2025-12-08 00:49:52', NULL, '2025-12-07 22:53:36', '2025-12-08 00:44:52'),
(4, 7, 'online', 'web', NULL, '2025-12-24 06:29:34', '2025-12-24 06:34:34', NULL, '2025-12-23 05:59:53', '2025-12-24 06:29:34');

-- --------------------------------------------------------

--
-- Table structure for table `chat_questions`
--

CREATE TABLE `chat_questions` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `identifier` varchar(255) NOT NULL,
  `question` varchar(255) NOT NULL,
  `response` text NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `chat_questions`
--

INSERT INTO `chat_questions` (`id`, `identifier`, `question`, `response`, `created_at`, `updated_at`) VALUES
(4, 'greeting_1', 'Hi', 'Hello! How can I help you today?', '2025-05-27 00:53:30', '2025-05-27 00:53:30'),
(5, 'greeting_2', 'Hello', 'Hi there! What can I do for you?', '2025-05-27 00:53:30', '2025-05-27 00:53:30'),
(6, 'greeting_3', 'Hey', 'Hey! Need any help?', '2025-05-27 00:53:30', '2025-05-27 00:53:30'),
(7, 'hours_1', 'What are your business hours?', 'We are open Monday to Friday, 9 AM to 6 PM.', '2025-05-27 00:53:30', '2025-05-27 00:53:30'),
(8, 'hours_2', 'Are you open on weekends?', 'We are closed on weekends, but available for urgent queries by email.', '2025-05-27 00:53:30', '2025-05-27 00:53:30'),
(9, 'location_1', 'Where are you located?', 'We are located at 123 Main Street, Springfield.', '2025-05-27 00:53:30', '2025-05-27 00:53:30'),
(10, 'contact_1', 'How can I contact support?', 'You can reach support at support@example.com or call us at 123-456-7890.', '2025-05-27 00:53:30', '2025-05-27 00:53:30'),
(11, 'services_1', 'What services do you offer?', 'We offer web development, mobile apps, and cloud solutions.', '2025-05-27 00:53:30', '2025-05-27 00:53:30'),
(12, 'pricing_1', 'What’s your pricing model?', 'Pricing depends on project size, please request a quote for accurate details.', '2025-05-27 00:53:30', '2025-05-27 00:53:30'),
(13, 'quote_1', 'Can I get a quote?', 'Sure! Please provide your requirements and contact details.', '2025-05-27 00:53:30', '2025-05-27 00:53:30'),
(14, 'appointment_1', 'Can I schedule a meeting?', 'Yes, please share your preferred date and time.', '2025-05-27 00:53:30', '2025-05-27 00:53:30'),
(15, 'signup_1', 'How do I sign up?', 'Visit our website and click the \"Sign Up\" button to create your account.', '2025-05-27 00:53:30', '2025-05-27 00:53:30'),
(16, 'login_1', 'I can’t log in.', 'Please reset your password or contact support if the issue continues.', '2025-05-27 00:53:30', '2025-05-27 00:53:30'),
(17, 'resetpass_1', 'How to reset my password?', 'Click on “Forgot password” on the login page and follow instructions.', '2025-05-27 00:53:30', '2025-05-27 00:53:30'),
(18, 'refund_1', 'What’s your refund policy?', 'Refunds are available within 14 days of purchase.', '2025-05-27 00:53:30', '2025-05-27 00:53:30'),
(19, 'cancel_1', 'How do I cancel my subscription?', 'Log in to your account, go to “Billing” and click “Cancel Subscription”.', '2025-05-27 00:53:30', '2025-05-27 00:53:30'),
(20, 'track_1', 'How do I track my order?', 'Use the tracking link in your email or contact us with your order ID.', '2025-05-27 00:53:30', '2025-05-27 00:53:30'),
(21, 'faq_1', 'Where can I find the FAQ?', 'Our FAQs are available at example.com/faq.', '2025-05-27 00:53:30', '2025-05-27 00:53:30'),
(22, 'support_1', 'Is 24/7 support available?', 'Yes, we offer 24/7 support via live chat and email.', '2025-05-27 00:53:30', '2025-05-27 00:53:30'),
(23, 'error_1', 'I see an error on the website.', 'Please try refreshing the page. If the issue persists, let us know the error message.', '2025-05-27 00:53:30', '2025-05-27 00:53:30'),
(24, 'slow_1', 'The site is slow.', 'We’re currently optimizing performance. Thank you for your patience.', '2025-05-27 00:53:30', '2025-05-27 00:53:30'),
(25, 'order_1', 'Can I change my order?', 'Yes, as long as it hasn’t been shipped yet.', '2025-05-27 00:53:30', '2025-05-27 00:53:30'),
(26, 'language_1', 'Do you support multiple languages?', 'Yes, we support English, Spanish, and French.', '2025-05-27 00:53:30', '2025-05-27 00:53:30'),
(27, 'payment_1', 'What payment methods do you accept?', 'We accept Visa, Mastercard, PayPal, and Stripe.', '2025-05-27 00:53:30', '2025-05-27 00:53:30'),
(28, 'invoice_1', 'Can I get an invoice?', 'Yes, invoices are sent via email after each purchase.', '2025-05-27 00:53:30', '2025-05-27 00:53:30'),
(29, 'student_1', 'Do you offer student discounts?', 'Yes, contact us with a valid student ID to get a discount.', '2025-05-27 00:53:30', '2025-05-27 00:53:30'),
(30, 'job_1', 'Are you hiring?', 'We’re always looking for great talent! Check out example.com/careers.', '2025-05-27 00:53:30', '2025-05-27 00:53:30'),
(31, 'blog_1', 'Do you have a blog?', 'Yes! Visit example.com/blog for news and updates.', '2025-05-27 00:53:30', '2025-05-27 00:53:30'),
(32, 'team_1', 'Who is on your team?', 'We are a group of developers, designers, and product managers.', '2025-05-27 00:53:30', '2025-05-27 00:53:30'),
(33, 'feedback_1', 'Where can I leave feedback?', 'You can leave feedback at example.com/feedback.', '2025-05-27 00:53:30', '2025-05-27 00:53:30'),
(34, 'project_1', 'Can you build my website?', 'Yes, please send us your requirements to get started.', '2025-05-27 00:53:30', '2025-05-27 00:53:30'),
(35, 'time_1', 'How long does a project take?', 'Depends on the scope, usually 2–6 weeks.', '2025-05-27 00:53:30', '2025-05-27 00:53:30'),
(36, 'tech_1', 'What technologies do you use?', 'We use Laravel, React, Tailwind CSS, and AWS.', '2025-05-27 00:53:30', '2025-05-27 00:53:30'),
(37, 'secure_1', 'Is your platform secure?', 'Yes, we use modern encryption and secure protocols.', '2025-05-27 00:53:30', '2025-05-27 00:53:30'),
(38, 'data_1', 'How is my data used?', 'We only use data to improve our service and never share it.', '2025-05-27 00:53:30', '2025-05-27 00:53:30'),
(39, 'terms_1', 'Where are your terms and conditions?', 'Visit example.com/terms to view our policies.', '2025-05-27 00:53:30', '2025-05-27 00:53:30'),
(40, 'privacy_1', 'What is your privacy policy?', 'Our privacy policy is available at example.com/privacy.', '2025-05-27 00:53:30', '2025-05-27 00:53:30'),
(41, 'joke_1', 'Tell me a joke', 'Why did the developer go broke? Because he used up all his cache.', '2025-05-27 00:53:30', '2025-05-27 00:53:30'),
(42, 'fun_1', 'Say something fun!', 'Why don’t robots ever panic? They’ve got nerves of steel!', '2025-05-27 00:53:30', '2025-05-27 00:53:30'),
(43, 'quote_2', 'Give me a motivational quote', '“Success is not final, failure is not fatal: It is the courage to continue that counts.”', '2025-05-27 00:53:30', '2025-05-27 00:53:30'),
(44, 'weather_1', 'What’s the weather today?', 'Please check your local weather app for real-time updates.', '2025-05-27 00:53:30', '2025-05-27 00:53:30'),
(45, 'news_1', 'Any news today?', 'You can find the latest news on our blog or your preferred news site.', '2025-05-27 00:53:30', '2025-05-27 00:53:30'),
(46, 'bye_1', 'Bye', 'Goodbye! Have a great day.', '2025-05-27 00:53:30', '2025-05-27 00:53:30'),
(47, 'thanks_1', 'Thank you', 'You’re welcome!', '2025-05-27 00:53:30', '2025-05-27 00:53:30'),
(48, 'help_1', 'Can you help me?', 'Of course! What do you need help with?', '2025-05-27 00:53:30', '2025-05-27 00:53:30'),
(49, 'repeat_1', 'Can you repeat that?', 'Sure! Let me repeat it for you.', '2025-05-27 00:53:30', '2025-05-27 00:53:30'),
(50, 'notunderstand_1', 'I don’t understand.', 'No worries, let me explain it in a simpler way.', '2025-05-27 00:53:30', '2025-05-27 00:53:30'),
(51, 'human_1', 'Can I talk to a human?', 'Yes, please wait a moment while I connect you.', '2025-05-27 00:53:30', '2025-05-27 00:53:30'),
(52, 'version_1', 'What version is this?', 'You are chatting with version 1.0 of our virtual assistant.', '2025-05-27 00:53:30', '2025-05-27 00:53:30');

-- --------------------------------------------------------

--
-- Table structure for table `chat_roles`
--

CREATE TABLE `chat_roles` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `conversation_id` bigint(20) UNSIGNED DEFAULT NULL,
  `created_by` bigint(20) UNSIGNED DEFAULT NULL,
  `name` varchar(255) NOT NULL,
  `color` varchar(255) DEFAULT NULL,
  `priority` int(10) UNSIGNED NOT NULL DEFAULT 0,
  `is_system` tinyint(1) NOT NULL DEFAULT 0,
  `applies_to_bots` tinyint(1) NOT NULL DEFAULT 0,
  `permissions` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`permissions`)),
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `chat_role_assignments`
--

CREATE TABLE `chat_role_assignments` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `role_id` bigint(20) UNSIGNED NOT NULL,
  `assignable_id` bigint(20) UNSIGNED NOT NULL,
  `assignable_type` varchar(255) NOT NULL,
  `assigned_by` bigint(20) UNSIGNED DEFAULT NULL,
  `assigned_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `chat_todos`
--

CREATE TABLE `chat_todos` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `conversation_id` bigint(20) UNSIGNED DEFAULT NULL,
  `assigned_to` bigint(20) UNSIGNED DEFAULT NULL,
  `title` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `priority` enum('low','medium','high','urgent') NOT NULL DEFAULT 'medium',
  `status` enum('pending','in_progress','completed','cancelled') NOT NULL DEFAULT 'pending',
  `due_date` timestamp NULL DEFAULT NULL,
  `completed_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `chat_topic_subscriptions`
--

CREATE TABLE `chat_topic_subscriptions` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `topic_id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `notification_level` varchar(255) NOT NULL DEFAULT 'all',
  `muted_until` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `chat_user_favorite_reactions`
--

CREATE TABLE `chat_user_favorite_reactions` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `slot` tinyint(3) UNSIGNED NOT NULL DEFAULT 1,
  `emoji` varchar(32) NOT NULL,
  `metadata` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`metadata`)),
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `chat_waiting_list`
--

CREATE TABLE `chat_waiting_list` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `assigned_by` bigint(20) UNSIGNED DEFAULT NULL,
  `conversation_id` bigint(20) UNSIGNED DEFAULT NULL,
  `status` enum('waiting','assigned','declined') NOT NULL DEFAULT 'waiting',
  `notes` text DEFAULT NULL,
  `assigned_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `companies`
--

CREATE TABLE `companies` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `slug` varchar(255) NOT NULL,
  `logo_url` varchar(255) DEFAULT NULL,
  `location` varchar(255) DEFAULT NULL,
  `industry` varchar(255) DEFAULT NULL,
  `established_year` smallint(5) UNSIGNED DEFAULT NULL,
  `employees_count` int(10) UNSIGNED DEFAULT NULL,
  `verified` tinyint(1) NOT NULL DEFAULT 0,
  `rating` decimal(3,2) DEFAULT NULL,
  `rating_count` int(10) UNSIGNED NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `companies`
--

INSERT INTO `companies` (`id`, `name`, `slug`, `logo_url`, `location`, `industry`, `established_year`, `employees_count`, `verified`, `rating`, `rating_count`, `created_at`, `updated_at`) VALUES
(1, 'SparkClean Janitorial Services', 'sparkclean-janitorial-services', 'https://api.dicebear.com/7.x/shapes/svg?seed=SparkClean%20Janitorial%20Services', 'Los Angeles, CA', 'business_process', 2015, 45, 1, 4.50, 245, '2025-12-04 00:56:35', '2025-12-04 00:56:35');

-- --------------------------------------------------------

--
-- Table structure for table `dropdown_options`
--

CREATE TABLE `dropdown_options` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `type` varchar(100) NOT NULL,
  `value` varchar(255) NOT NULL,
  `label` varchar(255) NOT NULL,
  `sort_order` int(11) NOT NULL DEFAULT 0,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `dropdown_options`
--

INSERT INTO `dropdown_options` (`id`, `type`, `value`, `label`, `sort_order`, `is_active`, `created_at`, `updated_at`) VALUES
(1, 'job_category', 'operations_va', 'Operations VA', 1, 1, '2025-12-08 03:56:58', '2025-12-08 03:56:58'),
(2, 'job_category', 'admin_support', 'Admin Support', 2, 1, '2025-12-08 03:56:58', '2025-12-08 03:56:58'),
(3, 'job_category', 'scheduling_dispatch', 'Scheduling / Dispatch', 3, 1, '2025-12-08 03:56:58', '2025-12-08 03:56:58'),
(4, 'job_category', 'client_success', 'Client Success', 4, 1, '2025-12-08 03:56:58', '2025-12-08 03:56:58'),
(5, 'job_category', 'back_office', 'Back Office', 5, 1, '2025-12-08 03:56:58', '2025-12-08 03:56:58'),
(6, 'employment_type', 'full_time', 'Full Time', 1, 1, '2025-12-08 03:56:58', '2025-12-08 03:56:58'),
(7, 'employment_type', 'part_time', 'Part Time', 2, 1, '2025-12-08 03:56:58', '2025-12-08 03:56:58'),
(8, 'employment_type', 'contract', 'Contract', 3, 1, '2025-12-08 03:56:58', '2025-12-08 03:56:58'),
(9, 'employment_type', 'freelance', 'Freelance', 4, 1, '2025-12-08 03:56:58', '2025-12-08 03:56:58'),
(10, 'employment_type', 'internship', 'Internship', 5, 1, '2025-12-08 03:56:58', '2025-12-08 03:56:58'),
(11, 'remote_type', 'remote', 'Remote', 1, 1, '2025-12-08 03:56:58', '2025-12-08 03:56:58'),
(12, 'remote_type', 'on_site', 'On-site', 2, 1, '2025-12-08 03:56:58', '2025-12-08 03:56:58'),
(13, 'remote_type', 'hybrid', 'Hybrid', 3, 1, '2025-12-08 03:56:58', '2025-12-08 03:56:58'),
(14, 'remote_type', 'flexible', 'Flexible', 4, 1, '2025-12-08 03:56:58', '2025-12-08 03:56:58'),
(15, 'remote_type', 'work_from_home', 'Work From Home', 5, 1, '2025-12-08 03:56:58', '2025-12-08 03:56:58'),
(16, 'currency', 'USD', 'USD - US Dollar', 1, 1, '2025-12-08 03:56:58', '2025-12-08 03:56:58'),
(17, 'currency', 'PHP', 'PHP - Philippine Peso', 2, 1, '2025-12-08 03:56:58', '2025-12-08 03:56:58'),
(18, 'currency', 'EUR', 'EUR - Euro', 3, 1, '2025-12-08 03:56:59', '2025-12-08 03:56:59'),
(19, 'currency', 'GBP', 'GBP - British Pound', 4, 1, '2025-12-08 03:56:59', '2025-12-08 03:56:59'),
(20, 'currency', 'AUD', 'AUD - Australian Dollar', 5, 1, '2025-12-08 03:56:59', '2025-12-08 03:56:59'),
(21, 'work_setup', 'work_from_home', 'Work From Home', 1, 1, '2025-12-08 03:56:59', '2025-12-08 03:56:59'),
(22, 'work_setup', 'office_based', 'Office Based', 2, 1, '2025-12-08 03:56:59', '2025-12-08 03:56:59'),
(23, 'work_setup', 'hybrid', 'Hybrid', 3, 1, '2025-12-08 03:56:59', '2025-12-08 03:56:59'),
(24, 'work_setup', 'field_work', 'Field Work', 4, 1, '2025-12-08 03:56:59', '2025-12-08 03:56:59'),
(25, 'work_setup', 'client_site', 'Client Site', 5, 1, '2025-12-08 03:56:59', '2025-12-08 03:56:59'),
(26, 'shift_schedule', 'day_shift', 'Day Shift', 1, 1, '2025-12-08 03:56:59', '2025-12-08 03:56:59'),
(27, 'shift_schedule', 'night_shift', 'Night Shift', 2, 1, '2025-12-08 03:56:59', '2025-12-08 03:56:59'),
(28, 'shift_schedule', 'mid_shift', 'Mid Shift', 3, 1, '2025-12-08 03:56:59', '2025-12-08 03:56:59'),
(29, 'shift_schedule', 'rotating_shift', 'Rotating Shift', 4, 1, '2025-12-08 03:56:59', '2025-12-08 03:56:59'),
(30, 'shift_schedule', 'flexible_hours', 'Flexible Hours', 5, 1, '2025-12-08 03:56:59', '2025-12-08 03:56:59'),
(31, 'candidate_title', 'virtual_assistant', 'Virtual Assistant', 1, 1, '2025-12-08 03:56:59', '2025-12-08 03:56:59'),
(32, 'candidate_title', 'operations_manager', 'Operations Manager', 2, 1, '2025-12-08 03:56:59', '2025-12-08 03:56:59'),
(33, 'candidate_title', 'admin_assistant', 'Administrative Assistant', 3, 1, '2025-12-08 03:56:59', '2025-12-08 03:56:59'),
(34, 'candidate_title', 'customer_service', 'Customer Service Representative', 4, 1, '2025-12-08 03:56:59', '2025-12-08 03:56:59'),
(35, 'candidate_title', 'project_coordinator', 'Project Coordinator', 5, 1, '2025-12-08 03:56:59', '2025-12-08 03:56:59'),
(36, 'work_mode', 'remote', 'Remote', 1, 1, '2025-12-08 03:56:59', '2025-12-08 03:56:59'),
(37, 'work_mode', 'on_site', 'On-site', 2, 1, '2025-12-08 03:56:59', '2025-12-08 03:56:59'),
(38, 'work_mode', 'hybrid', 'Hybrid', 3, 1, '2025-12-08 03:56:59', '2025-12-08 03:56:59'),
(39, 'work_mode', 'flexible', 'Flexible', 4, 1, '2025-12-08 03:56:59', '2025-12-08 03:56:59'),
(40, 'work_mode', 'any', 'Any', 5, 1, '2025-12-08 03:56:59', '2025-12-08 03:56:59'),
(41, 'availability', 'immediate', 'Immediate', 1, 1, '2025-12-08 03:56:59', '2025-12-08 03:56:59'),
(42, 'availability', '1_week', '1 Week Notice', 2, 1, '2025-12-08 03:56:59', '2025-12-08 03:56:59'),
(43, 'availability', '2_weeks', '2 Weeks Notice', 3, 1, '2025-12-08 03:56:59', '2025-12-08 03:56:59'),
(44, 'availability', '1_month', '1 Month Notice', 4, 1, '2025-12-08 03:56:59', '2025-12-08 03:56:59'),
(45, 'availability', 'negotiable', 'Negotiable', 5, 1, '2025-12-08 03:56:59', '2025-12-08 03:56:59'),
(46, 'job_type', 'full_time', 'Full Time', 1, 1, '2025-12-08 03:56:59', '2025-12-08 03:56:59'),
(47, 'job_type', 'part_time', 'Part Time', 2, 1, '2025-12-08 03:56:59', '2025-12-08 03:56:59'),
(48, 'job_type', 'contract', 'Contract', 3, 1, '2025-12-08 03:56:59', '2025-12-08 03:56:59'),
(49, 'job_type', 'freelance', 'Freelance', 4, 1, '2025-12-08 03:56:59', '2025-12-08 03:56:59'),
(50, 'job_type', 'any', 'Any', 5, 1, '2025-12-08 03:56:59', '2025-12-08 03:56:59'),
(51, 'expertise_category', 'administrative', 'Administrative', 1, 1, '2025-12-08 03:56:59', '2025-12-08 03:56:59'),
(52, 'expertise_category', 'customer_service', 'Customer Service', 2, 1, '2025-12-08 03:56:59', '2025-12-08 03:56:59'),
(53, 'expertise_category', 'operations', 'Operations', 3, 1, '2025-12-08 03:56:59', '2025-12-08 03:56:59'),
(54, 'expertise_category', 'scheduling', 'Scheduling & Dispatch', 4, 1, '2025-12-08 03:56:59', '2025-12-08 03:56:59'),
(55, 'expertise_category', 'data_entry', 'Data Entry', 5, 1, '2025-12-08 03:56:59', '2025-12-08 03:56:59'),
(56, 'language', 'english', 'English', 1, 1, '2025-12-08 03:56:59', '2025-12-08 03:56:59'),
(57, 'language', 'spanish', 'Spanish', 2, 1, '2025-12-08 03:56:59', '2025-12-08 03:56:59'),
(58, 'language', 'filipino', 'Filipino', 3, 1, '2025-12-08 03:56:59', '2025-12-08 03:56:59'),
(59, 'language', 'mandarin', 'Mandarin', 4, 1, '2025-12-08 03:56:59', '2025-12-08 03:56:59'),
(60, 'language', 'french', 'French', 5, 1, '2025-12-08 03:56:59', '2025-12-08 03:56:59'),
(61, 'industry_type', 'research_development', 'Research & Development', 1, 1, '2025-12-08 03:56:59', '2025-12-08 03:56:59'),
(62, 'industry_type', 'accounting', 'Accounting', 2, 1, '2025-12-08 03:56:59', '2025-12-08 03:56:59'),
(63, 'industry_type', 'business_process', 'Business Process', 3, 1, '2025-12-08 03:56:59', '2025-12-08 03:56:59'),
(64, 'industry_type', 'consulting', 'Consulting', 4, 1, '2025-12-08 03:56:59', '2025-12-08 03:56:59'),
(65, 'industry_type', 'administrative_support', 'Administrative Support', 5, 1, '2025-12-08 03:56:59', '2025-12-08 03:56:59'),
(66, 'industry_type', 'human_resources', 'Human Resources', 6, 1, '2025-12-08 03:56:59', '2025-12-08 03:56:59'),
(67, 'industry_type', 'marketing', 'Marketing', 7, 1, '2025-12-08 03:56:59', '2025-12-08 03:56:59'),
(68, 'recruiter_type', 'direct', 'Direct Company', 1, 1, '2025-12-08 03:56:59', '2025-12-08 03:56:59'),
(69, 'recruiter_type', 'agency', 'Agency', 2, 1, '2025-12-08 03:56:59', '2025-12-08 03:56:59'),
(70, 'recruiter_type', 'staffing', 'Staffing Firm', 3, 1, '2025-12-08 03:56:59', '2025-12-08 03:56:59'),
(71, 'recruiter_type', 'headhunter', 'Headhunter', 4, 1, '2025-12-08 03:56:59', '2025-12-08 03:56:59'),
(72, 'recruiter_type', 'freelance', 'Freelance Recruiter', 5, 1, '2025-12-08 03:56:59', '2025-12-08 03:56:59'),
(73, 'location', 'usa', 'USA (All)', 1, 1, '2025-12-08 03:56:59', '2025-12-08 03:56:59'),
(74, 'location', 'philippines', 'Philippines', 2, 1, '2025-12-08 03:56:59', '2025-12-08 03:56:59'),
(75, 'location', 'uk', 'United Kingdom', 3, 1, '2025-12-08 03:56:59', '2025-12-08 03:56:59'),
(76, 'location', 'australia', 'Australia', 4, 1, '2025-12-08 03:56:59', '2025-12-08 03:56:59'),
(77, 'location', 'canada', 'Canada', 5, 1, '2025-12-08 03:56:59', '2025-12-08 03:56:59');

-- --------------------------------------------------------

--
-- Table structure for table `failed_jobs`
--

CREATE TABLE `failed_jobs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `uuid` varchar(255) NOT NULL,
  `connection` text NOT NULL,
  `queue` text NOT NULL,
  `payload` longtext NOT NULL,
  `exception` longtext NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `group_join_requests`
--

CREATE TABLE `group_join_requests` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `conversation_id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `status` enum('pending','approved','rejected') NOT NULL DEFAULT 'pending',
  `message` text DEFAULT NULL,
  `reviewed_by` bigint(20) UNSIGNED DEFAULT NULL,
  `reviewed_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `jobs`
--

CREATE TABLE `jobs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `queue` varchar(255) NOT NULL,
  `payload` longtext NOT NULL,
  `attempts` tinyint(3) UNSIGNED NOT NULL,
  `reserved_at` int(10) UNSIGNED DEFAULT NULL,
  `available_at` int(10) UNSIGNED NOT NULL,
  `created_at` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `job_applications`
--

CREATE TABLE `job_applications` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `job_posting_id` bigint(20) UNSIGNED NOT NULL,
  `candidate_profile_id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `status` varchar(255) NOT NULL DEFAULT 'applied',
  `cover_letter` longtext DEFAULT NULL,
  `cv_path` varchar(255) DEFAULT NULL,
  `applied_at` timestamp NULL DEFAULT NULL,
  `reviewed_at` timestamp NULL DEFAULT NULL,
  `notes` longtext DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `job_applications`
--

INSERT INTO `job_applications` (`id`, `job_posting_id`, `candidate_profile_id`, `user_id`, `status`, `cover_letter`, `cv_path`, `applied_at`, `reviewed_at`, `notes`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 1, 1, 4, 'applied', NULL, NULL, '2025-12-04 03:12:53', NULL, NULL, '2025-12-04 03:12:53', '2025-12-04 03:12:53', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `job_batches`
--

CREATE TABLE `job_batches` (
  `id` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `total_jobs` int(11) NOT NULL,
  `pending_jobs` int(11) NOT NULL,
  `failed_jobs` int(11) NOT NULL,
  `failed_job_ids` longtext NOT NULL,
  `options` mediumtext DEFAULT NULL,
  `cancelled_at` int(11) DEFAULT NULL,
  `created_at` int(11) NOT NULL,
  `finished_at` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `job_postings`
--

CREATE TABLE `job_postings` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `company_id` bigint(20) UNSIGNED NOT NULL,
  `title` varchar(255) NOT NULL,
  `slug` varchar(255) NOT NULL,
  `location` varchar(255) DEFAULT NULL,
  `category` varchar(255) DEFAULT NULL,
  `industry_type` varchar(255) DEFAULT NULL,
  `recruiter_type` varchar(255) DEFAULT NULL,
  `employment_type` varchar(255) DEFAULT NULL,
  `remote_type` varchar(255) DEFAULT NULL,
  `vacancies` int(10) UNSIGNED NOT NULL DEFAULT 1,
  `status` varchar(255) NOT NULL DEFAULT 'open',
  `rating` decimal(3,2) DEFAULT NULL,
  `rating_count` int(10) UNSIGNED NOT NULL DEFAULT 0,
  `salary_min` decimal(10,2) DEFAULT NULL,
  `salary_max` decimal(10,2) DEFAULT NULL,
  `salary_currency` varchar(3) NOT NULL DEFAULT 'USD',
  `experience_min_years` tinyint(3) UNSIGNED DEFAULT NULL,
  `experience_max_years` tinyint(3) UNSIGNED DEFAULT NULL,
  `summary` text DEFAULT NULL,
  `description` longtext DEFAULT NULL,
  `requirements` longtext DEFAULT NULL,
  `responsibilities` longtext DEFAULT NULL,
  `highlight_work_setup` varchar(255) DEFAULT NULL,
  `highlight_shift_schedule` varchar(255) DEFAULT NULL,
  `highlight_monthly_rate` varchar(255) DEFAULT NULL,
  `highlight_benefits` varchar(255) DEFAULT NULL,
  `posted_at` timestamp NULL DEFAULT NULL,
  `closes_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `job_postings`
--

INSERT INTO `job_postings` (`id`, `company_id`, `title`, `slug`, `location`, `category`, `industry_type`, `recruiter_type`, `employment_type`, `remote_type`, `vacancies`, `status`, `rating`, `rating_count`, `salary_min`, `salary_max`, `salary_currency`, `experience_min_years`, `experience_max_years`, `summary`, `description`, `requirements`, `responsibilities`, `highlight_work_setup`, `highlight_shift_schedule`, `highlight_monthly_rate`, `highlight_benefits`, `posted_at`, `closes_at`, `created_at`, `updated_at`) VALUES
(1, 1, 'Operations Virtual Assistant (Janitorial)', 'operations-virtual-assistant-janitorial', 'Remote · Supporting US / Canada janitorial clients', 'Operations VA', NULL, NULL, 'full_time', 'remote', 3, 'open', NULL, 0, 800.00, 1200.00, 'USD', 2, 4, 'Janitorial Operations Virtual Assistant role supporting US/Canada clients.', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-11-29 00:56:35', NULL, '2025-12-04 00:56:35', '2025-12-04 00:56:35');

-- --------------------------------------------------------

--
-- Table structure for table `migrations`
--

CREATE TABLE `migrations` (
  `id` int(10) UNSIGNED NOT NULL,
  `migration` varchar(255) NOT NULL,
  `batch` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '0001_01_01_000000_create_users_table', 1),
(2, '0001_01_01_000001_create_cache_table', 1),
(3, '0001_01_01_000002_create_jobs_table', 1),
(4, '2025_10_18_141345_add_two_factor_columns_to_users_table', 1),
(5, '2025_10_18_141430_create_personal_access_tokens_table', 1),
(6, '2024_11_01_000001_create_wirechat_conversations_table', 2),
(7, '2024_11_01_000002_create_wirechat_attachments_table', 2),
(8, '2024_11_01_000003_create_wirechat_messages_table', 2),
(9, '2024_11_01_000004_create_wirechat_participants_table', 2),
(10, '2024_11_01_000006_create_wirechat_actions_table', 2),
(11, '2024_11_01_000007_create_wirechat_groups_table', 2),
(12, '2025_12_04_170000_create_companies_table', 3),
(13, '2025_12_04_170010_create_candidate_profiles_table', 3),
(14, '2025_12_04_170020_create_job_postings_table', 3),
(15, '2025_12_04_170030_create_job_applications_table', 3),
(16, '2025_12_04_180100_add_role_to_users_table', 4),
(17, '2025_12_04_180010_add_rich_fields_to_candidate_profiles_table', 5),
(18, '2025_12_04_180020_add_personal_fields_to_users_table', 5),
(19, '2025_12_04_190030_add_skills_and_languages_to_candidate_profiles_table', 6),
(20, '2025_12_04_190040_add_job_type_and_expertise_to_candidate_profiles_table', 7),
(21, '2025_12_05_200000_enhance_discord_like_chat_capabilities', 8),
(22, '2025_12_05_210000_create_chat_group_invites_table', 9),
(23, '2025_12_06_100000_create_personal_tags_table', 10),
(24, '2025_12_08_000001_add_gif_type_to_chat_messages', 11),
(25, '2025_12_06_000001_create_chat_todos_table', 12),
(26, '2025_12_08_000002_add_invite_columns_to_chat_conversation', 13),
(27, '2025_12_08_000004_create_group_join_requests_table', 14),
(28, '2025_12_08_071843_add_tools_used_to_candidate_profiles_table', 15),
(29, '2025_12_08_100000_create_dropdown_options_table', 16),
(30, '2025_12_08_120000_add_industry_and_recruiter_type_to_job_postings_table', 17),
(31, '2025_12_07_000001_add_deleted_at_to_job_applications_table', 18),
(32, '2025_12_05_000000_create_saved_jobs_table', 19),
(33, '2025_12_07_000002_add_highlights_to_job_postings_table', 20),
(34, '2025_12_08_150000_create_ratings_table', 20),
(35, '2025_12_23_000001_add_channel_permissions_and_member_controls', 21),
(36, '2025_12_23_000002_add_original_name_to_chat_attachments', 22),
(37, '2025_12_24_000001_add_moderator_delete_fields_to_messages', 23),
(38, '2025_12_24_000001_add_video_type_to_chat_messages', 24);

-- --------------------------------------------------------

--
-- Table structure for table `password_reset_tokens`
--

CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) NOT NULL,
  `token` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `personal_access_tokens`
--

CREATE TABLE `personal_access_tokens` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `tokenable_type` varchar(255) NOT NULL,
  `tokenable_id` bigint(20) UNSIGNED NOT NULL,
  `name` text NOT NULL,
  `token` varchar(64) NOT NULL,
  `abilities` text DEFAULT NULL,
  `last_used_at` timestamp NULL DEFAULT NULL,
  `expires_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `personal_tags`
--

CREATE TABLE `personal_tags` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `slug` varchar(255) NOT NULL,
  `color` varchar(255) NOT NULL DEFAULT '#6366f1',
  `icon` varchar(255) DEFAULT NULL,
  `is_private` tinyint(1) NOT NULL DEFAULT 1,
  `position` int(11) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `personal_tags`
--

INSERT INTO `personal_tags` (`id`, `user_id`, `name`, `slug`, `color`, `icon`, `is_private`, `position`, `created_at`, `updated_at`) VALUES
(1, 4, 'Saves', 'saves', '#22c55e', 'bookmark', 1, 1, '2025-12-07 01:33:11', '2025-12-07 01:33:11'),
(2, 4, 'test', 'test', '#6366f1', 'bookmark', 1, 2, '2025-12-07 01:34:53', '2025-12-07 01:34:53');

-- --------------------------------------------------------

--
-- Table structure for table `personal_tag_messages`
--

CREATE TABLE `personal_tag_messages` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `personal_tag_id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `body` text DEFAULT NULL,
  `forwarded_from_message_id` bigint(20) UNSIGNED DEFAULT NULL,
  `forwarded_metadata` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`forwarded_metadata`)),
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `personal_tag_messages`
--

INSERT INTO `personal_tag_messages` (`id`, `personal_tag_id`, `user_id`, `body`, `forwarded_from_message_id`, `forwarded_metadata`, `created_at`, `updated_at`) VALUES
(1, 1, 4, 'hi', 1910, '{\"original_body\": \"hi\", \"original_sender\": \"Test User\", \"original_created_at\": \"2025-12-06T15:23:40+00:00\"}', '2025-12-07 03:41:27', '2025-12-07 03:41:27');

-- --------------------------------------------------------

--
-- Table structure for table `ratings`
--

CREATE TABLE `ratings` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `rateable_type` varchar(255) NOT NULL,
  `rateable_id` bigint(20) UNSIGNED NOT NULL,
  `rating` tinyint(3) UNSIGNED NOT NULL,
  `review` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `saved_jobs`
--

CREATE TABLE `saved_jobs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `job_posting_id` bigint(20) UNSIGNED NOT NULL,
  `candidate_profile_id` bigint(20) UNSIGNED DEFAULT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `saved_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `sessions`
--

CREATE TABLE `sessions` (
  `id` varchar(255) NOT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `user_agent` text DEFAULT NULL,
  `payload` longtext NOT NULL,
  `last_activity` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `sessions`
--

INSERT INTO `sessions` (`id`, `user_id`, `ip_address`, `user_agent`, `payload`, `last_activity`) VALUES
('fK5bVhvkTATr9rfKx2a5QVjESd59ZMPQtJZH7bmV', 4, '127.0.0.1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', 'YTo1OntzOjY6Il90b2tlbiI7czo0MDoid3U1azN6T0thZHZTMU1Za0tUek5oQ2RqbGxsM2hKVnpqb3dSbUU1YiI7czozOiJ1cmwiO2E6MDp7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fXM6OToiX3ByZXZpb3VzIjthOjE6e3M6MzoidXJsIjtzOjQ3OiJodHRwOi8vbG9jYWxob3N0OjgwMDAvY2hhdHMvdjI/Y29udmVyc2F0aW9uPTEwOSI7fXM6NTA6ImxvZ2luX3dlYl81OWJhMzZhZGRjMmIyZjk0MDE1ODBmMDE0YzdmNThlYTRlMzA5ODlkIjtpOjQ7fQ==', 1766586574),
('r8g4Np9v7uVYR2mKObFNjFxeqtNPabiJ7lnIg3Mi', 7, '127.0.0.1', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36', 'YTo1OntzOjY6Il90b2tlbiI7czo0MDoiaFB0RFBmMDduNkNRWEE2VGdsTkw5YlNhSDdUN3JkdHRIeERzaHBVTCI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319czozOiJ1cmwiO2E6MDp7fXM6OToiX3ByZXZpb3VzIjthOjE6e3M6MzoidXJsIjtzOjQ3OiJodHRwOi8vbG9jYWxob3N0OjgwMDAvY2hhdHMvdjI/Y29udmVyc2F0aW9uPTEwOSI7fXM6NTA6ImxvZ2luX3dlYl81OWJhMzZhZGRjMmIyZjk0MDE1ODBmMDE0YzdmNThlYTRlMzA5ODlkIjtpOjc7fQ==', 1766586574);

-- --------------------------------------------------------

--
-- Table structure for table `t_file_manager`
--

CREATE TABLE `t_file_manager` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `link` varchar(7) NOT NULL,
  `name` varchar(255) NOT NULL,
  `path` varchar(255) DEFAULT NULL,
  `size` varchar(255) DEFAULT NULL,
  `format` varchar(255) DEFAULT NULL,
  `mime_type` varchar(255) DEFAULT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `is_folder` tinyint(1) NOT NULL DEFAULT 0,
  `isDeleted` int(11) NOT NULL DEFAULT 0,
  `parent_id` varchar(120) DEFAULT NULL,
  `uploader_id` int(11) DEFAULT NULL,
  `google_drive_id` varchar(120) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `t_file_manager`
--

INSERT INTO `t_file_manager` (`id`, `link`, `name`, `path`, `size`, `format`, `mime_type`, `user_id`, `is_folder`, `isDeleted`, `parent_id`, `uploader_id`, `google_drive_id`, `created_at`, `updated_at`) VALUES
(2969, 'eDJlTDb', 'Documents', NULL, NULL, NULL, NULL, 1, 1, 0, NULL, 1, NULL, '2025-10-28 19:50:15', '2025-10-28 19:50:15'),
(2970, 'LkaLN7b', 'Resources', NULL, NULL, NULL, NULL, 1, 1, 0, NULL, 1, NULL, '2025-10-28 20:01:11', '2025-10-28 20:01:11');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `role` varchar(32) NOT NULL DEFAULT 'candidate',
  `password` varchar(255) NOT NULL,
  `two_factor_secret` text DEFAULT NULL,
  `two_factor_recovery_codes` text DEFAULT NULL,
  `two_factor_confirmed_at` timestamp NULL DEFAULT NULL,
  `remember_token` varchar(100) DEFAULT NULL,
  `current_team_id` bigint(20) UNSIGNED DEFAULT NULL,
  `profile_photo_path` varchar(2048) DEFAULT NULL,
  `date_of_birth` date DEFAULT NULL,
  `gender` varchar(20) DEFAULT NULL,
  `phone` varchar(50) DEFAULT NULL,
  `marital_status` varchar(50) DEFAULT NULL,
  `address` text DEFAULT NULL,
  `social_facebook` varchar(255) DEFAULT NULL,
  `social_twitter` varchar(255) DEFAULT NULL,
  `social_instagram` varchar(255) DEFAULT NULL,
  `social_github` varchar(255) DEFAULT NULL,
  `social_youtube` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `email_verified_at`, `role`, `password`, `two_factor_secret`, `two_factor_recovery_codes`, `two_factor_confirmed_at`, `remember_token`, `current_team_id`, `profile_photo_path`, `date_of_birth`, `gender`, `phone`, `marital_status`, `address`, `social_facebook`, `social_twitter`, `social_instagram`, `social_github`, `social_youtube`, `created_at`, `updated_at`) VALUES
(1, 'Kent Russell Casiño', 'admin@ckent.dev', NULL, 'candidate', '$2y$12$.tetWTotB9EmXFllOJsb9eIlNK5v0BbTpgVMjuWxmBpUaE4rqc2Em', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-10-28 19:50:05', '2025-10-28 19:50:05'),
(2, 'Kent', 'kentrussellcasino.16@gmail.com', NULL, 'candidate', '$2y$12$ENw2dHORHw/W4ARvIYJdje/jhwTi/FKT/LCmDE6zpHSswEQpnbIb.', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-12-01 05:02:04', '2025-12-01 05:02:04'),
(3, 'Kent', 'kentrussellcasino.12@gmail.com', NULL, 'candidate', '$2y$12$C6v99ste2LEXnCmeJEnGPObJBUmvZXSczZwNW/v5eSSvftpD/ziQK', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-12-01 05:58:02', '2025-12-01 05:58:02'),
(4, 'Test User', 'test@example.com', '2025-12-04 00:19:39', 'candidate', '$2y$12$L.OvvXH9XPDevz2k3Xp88uUsQyeBk4W4RTIq.T60NxLGiwJA/aahS', NULL, NULL, NULL, '7Nrf17d2srn1kzGFC8BLllB3XEivmG9VUBU34U6o64HctY2jQINTL1wHB6XJ', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-12-04 00:19:41', '2025-12-04 00:19:41'),
(6, 'Test Nash', 'testnash@example.com', '2025-12-04 04:53:21', 'candidate', '$2y$12$OEji6LAg.0.0jfJh.yjrv.Hktgs8WsBM7UMmzNz00Co.L2oHwhqbW', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-12-04 04:53:21', '2025-12-04 04:53:21'),
(7, 'Moderator User', 'moderator@example.com', '2025-12-04 04:53:22', 'moderator', '$2y$12$iDNxt1AxD5kCYETgvHZmhO0gG80qgl6M6U3qeMCzvtixq8B0Vu3py', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-12-04 04:53:22', '2025-12-04 04:53:22'),
(8, 'Candidate User', 'candidate@example.com', '2025-12-04 04:53:22', 'candidate', '$2y$12$gWLllfoalkDhbZ.0nry9teZ/nv.VmNVtsoRqy9bSg2F8SVtXGAu3m', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-12-04 04:53:22', '2025-12-04 04:53:22'),
(9, 'Employer User', 'employer@example.com', '2025-12-04 04:53:22', 'employer', '$2y$12$foZH7TD0z3SR/KnolR/FDusJbJnog/BYhPaZXuIBClttOnyYmtXKK', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-12-04 04:53:22', '2025-12-04 04:53:22');

-- --------------------------------------------------------

--
-- Table structure for table `wirechat_actions`
--

CREATE TABLE `wirechat_actions` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `actionable_id` bigint(20) UNSIGNED NOT NULL,
  `actionable_type` varchar(255) NOT NULL,
  `actor_id` bigint(20) UNSIGNED NOT NULL,
  `actor_type` varchar(255) NOT NULL,
  `type` varchar(255) NOT NULL,
  `data` varchar(255) DEFAULT NULL COMMENT 'Some additional information about the action',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `wirechat_attachments`
--

CREATE TABLE `wirechat_attachments` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `attachable_id` bigint(20) UNSIGNED NOT NULL,
  `attachable_type` varchar(255) NOT NULL,
  `file_path` varchar(255) NOT NULL,
  `file_name` varchar(255) NOT NULL,
  `original_name` varchar(255) NOT NULL,
  `url` varchar(255) NOT NULL,
  `mime_type` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `wirechat_conversations`
--

CREATE TABLE `wirechat_conversations` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `type` varchar(255) NOT NULL COMMENT 'Private is 1-1 , group or channel',
  `disappearing_started_at` timestamp NULL DEFAULT NULL,
  `disappearing_duration` int(11) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `wirechat_groups`
--

CREATE TABLE `wirechat_groups` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `conversation_id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `avatar_url` varchar(255) DEFAULT NULL,
  `type` varchar(255) NOT NULL DEFAULT 'private',
  `allow_members_to_send_messages` tinyint(1) NOT NULL DEFAULT 1,
  `allow_members_to_add_others` tinyint(1) NOT NULL DEFAULT 1,
  `allow_members_to_edit_group_info` tinyint(1) NOT NULL DEFAULT 0,
  `admins_must_approve_new_members` tinyint(1) NOT NULL DEFAULT 0 COMMENT 'when turned on, admins must approve anyone who wants to join group',
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `wirechat_messages`
--

CREATE TABLE `wirechat_messages` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `conversation_id` bigint(20) UNSIGNED NOT NULL,
  `sendable_id` bigint(20) UNSIGNED NOT NULL,
  `sendable_type` varchar(255) NOT NULL,
  `reply_id` bigint(20) UNSIGNED DEFAULT NULL,
  `body` text DEFAULT NULL,
  `type` varchar(255) NOT NULL DEFAULT 'text',
  `kept_at` timestamp NULL DEFAULT NULL COMMENT 'filled when a message is kept from disappearing',
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `wirechat_participants`
--

CREATE TABLE `wirechat_participants` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `conversation_id` bigint(20) UNSIGNED NOT NULL,
  `role` varchar(255) NOT NULL,
  `participantable_id` bigint(20) UNSIGNED NOT NULL,
  `participantable_type` varchar(255) NOT NULL,
  `exited_at` timestamp NULL DEFAULT NULL,
  `last_active_at` timestamp NULL DEFAULT NULL,
  `conversation_cleared_at` timestamp NULL DEFAULT NULL,
  `conversation_deleted_at` timestamp NULL DEFAULT NULL,
  `conversation_read_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `cache`
--
ALTER TABLE `cache`
  ADD PRIMARY KEY (`key`);

--
-- Indexes for table `cache_locks`
--
ALTER TABLE `cache_locks`
  ADD PRIMARY KEY (`key`);

--
-- Indexes for table `candidate_profiles`
--
ALTER TABLE `candidate_profiles`
  ADD PRIMARY KEY (`id`),
  ADD KEY `candidate_profiles_user_id_foreign` (`user_id`);

--
-- Indexes for table `chat_attachments`
--
ALTER TABLE `chat_attachments`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `chat_bot_commands`
--
ALTER TABLE `chat_bot_commands`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `chat_bot_commands_bot_id_command_unique` (`bot_id`,`command`),
  ADD KEY `chat_bot_commands_required_role_id_foreign` (`required_role_id`);

--
-- Indexes for table `chat_bot_profiles`
--
ALTER TABLE `chat_bot_profiles`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `chat_bot_profiles_slug_unique` (`slug`),
  ADD KEY `chat_bot_profiles_conversation_id_foreign` (`conversation_id`),
  ADD KEY `chat_bot_profiles_owner_user_id_foreign` (`owner_user_id`);

--
-- Indexes for table `chat_conversation`
--
ALTER TABLE `chat_conversation`
  ADD PRIMARY KEY (`id`),
  ADD KEY `chat_conversation_created_by_foreign` (`created_by`),
  ADD KEY `chat_conversation_invite_code_index` (`invite_code`);

--
-- Indexes for table `chat_conversation_participants`
--
ALTER TABLE `chat_conversation_participants`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `chat_discussion_topics`
--
ALTER TABLE `chat_discussion_topics`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `chat_discussion_topics_conversation_id_slug_unique` (`conversation_id`,`slug`),
  ADD KEY `chat_discussion_topics_created_by_foreign` (`created_by`),
  ADD KEY `chat_discussion_topics_conversation_id_visibility_index` (`conversation_id`,`visibility`);

--
-- Indexes for table `chat_discussion_topic_messages`
--
ALTER TABLE `chat_discussion_topic_messages`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `chat_discussion_topic_messages_topic_id_message_id_unique` (`topic_id`,`message_id`),
  ADD KEY `chat_discussion_topic_messages_message_id_foreign` (`message_id`);

--
-- Indexes for table `chat_friendships`
--
ALTER TABLE `chat_friendships`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `chat_friendships_requester_id_addressee_id_unique` (`requester_id`,`addressee_id`),
  ADD KEY `chat_friendships_addressee_id_foreign` (`addressee_id`),
  ADD KEY `chat_friendships_blocked_by_user_id_foreign` (`blocked_by_user_id`),
  ADD KEY `chat_friendships_status_index` (`status`);

--
-- Indexes for table `chat_group_invites`
--
ALTER TABLE `chat_group_invites`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `chat_group_invites_code_unique` (`code`),
  ADD KEY `chat_group_invites_created_by_foreign` (`created_by`),
  ADD KEY `chat_group_invites_conversation_id_is_revoked_index` (`conversation_id`,`is_revoked`),
  ADD KEY `chat_group_invites_expires_at_index` (`expires_at`);

--
-- Indexes for table `chat_messages`
--
ALTER TABLE `chat_messages`
  ADD PRIMARY KEY (`id`),
  ADD KEY `chat_messages_forwarded_from_message_id_foreign` (`forwarded_from_message_id`);

--
-- Indexes for table `chat_message_pins`
--
ALTER TABLE `chat_message_pins`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `chat_message_pins_conversation_id_message_id_unique` (`conversation_id`,`message_id`),
  ADD KEY `chat_message_pins_message_id_foreign` (`message_id`),
  ADD KEY `chat_message_pins_user_id_foreign` (`user_id`);

--
-- Indexes for table `chat_message_reactions`
--
ALTER TABLE `chat_message_reactions`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `chat_message_reactions_message_id_user_id_reaction_unique` (`message_id`,`user_id`,`reaction`),
  ADD KEY `chat_message_reactions_user_id_foreign` (`user_id`);

--
-- Indexes for table `chat_message_reads`
--
ALTER TABLE `chat_message_reads`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `chat_message_reads_message_id_user_id_unique` (`message_id`,`user_id`),
  ADD KEY `chat_message_reads_user_id_foreign` (`user_id`);

--
-- Indexes for table `chat_message_reports`
--
ALTER TABLE `chat_message_reports`
  ADD PRIMARY KEY (`id`),
  ADD KEY `chat_message_reports_message_id_foreign` (`message_id`),
  ADD KEY `chat_message_reports_reporter_id_foreign` (`reporter_id`),
  ADD KEY `chat_message_reports_conversation_id_foreign` (`conversation_id`),
  ADD KEY `chat_message_reports_resolved_by_foreign` (`resolved_by`),
  ADD KEY `chat_message_reports_status_conversation_id_index` (`status`,`conversation_id`);

--
-- Indexes for table `chat_message_user_states`
--
ALTER TABLE `chat_message_user_states`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `chat_message_user_state_unique` (`message_id`,`user_id`,`state`),
  ADD KEY `chat_message_user_states_user_id_foreign` (`user_id`);

--
-- Indexes for table `chat_moderation_events`
--
ALTER TABLE `chat_moderation_events`
  ADD PRIMARY KEY (`id`),
  ADD KEY `chat_moderation_events_rule_id_foreign` (`rule_id`),
  ADD KEY `chat_moderation_events_user_id_foreign` (`user_id`),
  ADD KEY `chat_moderation_events_message_id_foreign` (`message_id`),
  ADD KEY `chat_moderation_events_bot_id_foreign` (`bot_id`),
  ADD KEY `chat_moderation_events_occurred_at_index` (`occurred_at`);

--
-- Indexes for table `chat_moderation_rules`
--
ALTER TABLE `chat_moderation_rules`
  ADD PRIMARY KEY (`id`),
  ADD KEY `chat_moderation_rules_conversation_id_foreign` (`conversation_id`),
  ADD KEY `chat_moderation_rules_created_by_foreign` (`created_by`),
  ADD KEY `chat_moderation_rules_bot_id_foreign` (`bot_id`);

--
-- Indexes for table `chat_presence_statuses`
--
ALTER TABLE `chat_presence_statuses`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `chat_presence_statuses_user_id_unique` (`user_id`);

--
-- Indexes for table `chat_questions`
--
ALTER TABLE `chat_questions`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `chat_questions_identifier_unique` (`identifier`);

--
-- Indexes for table `chat_roles`
--
ALTER TABLE `chat_roles`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `chat_roles_conversation_id_name_unique` (`conversation_id`,`name`),
  ADD KEY `chat_roles_created_by_foreign` (`created_by`);

--
-- Indexes for table `chat_role_assignments`
--
ALTER TABLE `chat_role_assignments`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `chat_role_assignments_unique` (`role_id`,`assignable_id`,`assignable_type`),
  ADD KEY `chat_role_assignments_assigned_by_foreign` (`assigned_by`),
  ADD KEY `chat_role_assignments_assignable_id_assignable_type_index` (`assignable_id`,`assignable_type`);

--
-- Indexes for table `chat_todos`
--
ALTER TABLE `chat_todos`
  ADD PRIMARY KEY (`id`),
  ADD KEY `chat_todos_assigned_to_foreign` (`assigned_to`),
  ADD KEY `chat_todos_user_id_status_index` (`user_id`,`status`),
  ADD KEY `chat_todos_conversation_id_status_index` (`conversation_id`,`status`),
  ADD KEY `chat_todos_due_date_index` (`due_date`);

--
-- Indexes for table `chat_topic_subscriptions`
--
ALTER TABLE `chat_topic_subscriptions`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `chat_topic_subscriptions_topic_id_user_id_unique` (`topic_id`,`user_id`),
  ADD KEY `chat_topic_subscriptions_user_id_foreign` (`user_id`);

--
-- Indexes for table `chat_user_favorite_reactions`
--
ALTER TABLE `chat_user_favorite_reactions`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `chat_user_favorite_reactions_user_id_slot_unique` (`user_id`,`slot`),
  ADD UNIQUE KEY `chat_user_favorite_reactions_user_id_emoji_unique` (`user_id`,`emoji`);

--
-- Indexes for table `chat_waiting_list`
--
ALTER TABLE `chat_waiting_list`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_waiting_user` (`user_id`,`status`),
  ADD KEY `chat_waiting_list_assigned_by_foreign` (`assigned_by`),
  ADD KEY `chat_waiting_list_conversation_id_foreign` (`conversation_id`),
  ADD KEY `chat_waiting_list_status_created_at_index` (`status`,`created_at`);

--
-- Indexes for table `companies`
--
ALTER TABLE `companies`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `companies_slug_unique` (`slug`);

--
-- Indexes for table `dropdown_options`
--
ALTER TABLE `dropdown_options`
  ADD PRIMARY KEY (`id`),
  ADD KEY `dropdown_options_type_is_active_index` (`type`,`is_active`),
  ADD KEY `dropdown_options_type_index` (`type`);

--
-- Indexes for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`);

--
-- Indexes for table `group_join_requests`
--
ALTER TABLE `group_join_requests`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `group_join_requests_conversation_id_user_id_status_unique` (`conversation_id`,`user_id`,`status`),
  ADD KEY `group_join_requests_user_id_foreign` (`user_id`),
  ADD KEY `group_join_requests_reviewed_by_foreign` (`reviewed_by`),
  ADD KEY `group_join_requests_conversation_id_status_index` (`conversation_id`,`status`);

--
-- Indexes for table `jobs`
--
ALTER TABLE `jobs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `jobs_queue_index` (`queue`);

--
-- Indexes for table `job_applications`
--
ALTER TABLE `job_applications`
  ADD PRIMARY KEY (`id`),
  ADD KEY `job_applications_job_posting_id_foreign` (`job_posting_id`),
  ADD KEY `job_applications_candidate_profile_id_foreign` (`candidate_profile_id`),
  ADD KEY `job_applications_user_id_foreign` (`user_id`);

--
-- Indexes for table `job_batches`
--
ALTER TABLE `job_batches`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `job_postings`
--
ALTER TABLE `job_postings`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `job_postings_slug_unique` (`slug`),
  ADD KEY `job_postings_company_id_foreign` (`company_id`);

--
-- Indexes for table `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `password_reset_tokens`
--
ALTER TABLE `password_reset_tokens`
  ADD PRIMARY KEY (`email`);

--
-- Indexes for table `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `personal_access_tokens_token_unique` (`token`),
  ADD KEY `personal_access_tokens_tokenable_type_tokenable_id_index` (`tokenable_type`,`tokenable_id`),
  ADD KEY `personal_access_tokens_expires_at_index` (`expires_at`);

--
-- Indexes for table `personal_tags`
--
ALTER TABLE `personal_tags`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `personal_tags_user_id_slug_unique` (`user_id`,`slug`),
  ADD KEY `personal_tags_user_id_position_index` (`user_id`,`position`);

--
-- Indexes for table `personal_tag_messages`
--
ALTER TABLE `personal_tag_messages`
  ADD PRIMARY KEY (`id`),
  ADD KEY `personal_tag_messages_user_id_foreign` (`user_id`),
  ADD KEY `personal_tag_messages_personal_tag_id_created_at_index` (`personal_tag_id`,`created_at`);

--
-- Indexes for table `ratings`
--
ALTER TABLE `ratings`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `ratings_user_id_rateable_type_rateable_id_unique` (`user_id`,`rateable_type`,`rateable_id`),
  ADD KEY `ratings_rateable_type_rateable_id_index` (`rateable_type`,`rateable_id`);

--
-- Indexes for table `saved_jobs`
--
ALTER TABLE `saved_jobs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `saved_jobs_job_posting_id_user_id_unique` (`job_posting_id`,`user_id`),
  ADD KEY `saved_jobs_candidate_profile_id_foreign` (`candidate_profile_id`),
  ADD KEY `saved_jobs_user_id_foreign` (`user_id`);

--
-- Indexes for table `sessions`
--
ALTER TABLE `sessions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sessions_user_id_index` (`user_id`),
  ADD KEY `sessions_last_activity_index` (`last_activity`);

--
-- Indexes for table `t_file_manager`
--
ALTER TABLE `t_file_manager`
  ADD PRIMARY KEY (`id`),
  ADD KEY `file_managers_user_id_foreign` (`user_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_email_unique` (`email`);

--
-- Indexes for table `wirechat_actions`
--
ALTER TABLE `wirechat_actions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `wirechat_actions_actionable_id_actionable_type_index` (`actionable_id`,`actionable_type`),
  ADD KEY `wirechat_actions_actor_id_actor_type_index` (`actor_id`,`actor_type`),
  ADD KEY `wirechat_actions_type_index` (`type`);

--
-- Indexes for table `wirechat_attachments`
--
ALTER TABLE `wirechat_attachments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `wirechat_attachments_attachable_id_attachable_type_index` (`attachable_id`,`attachable_type`);

--
-- Indexes for table `wirechat_conversations`
--
ALTER TABLE `wirechat_conversations`
  ADD PRIMARY KEY (`id`),
  ADD KEY `wirechat_conversations_type_index` (`type`);

--
-- Indexes for table `wirechat_groups`
--
ALTER TABLE `wirechat_groups`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `wirechat_messages`
--
ALTER TABLE `wirechat_messages`
  ADD PRIMARY KEY (`id`),
  ADD KEY `wirechat_messages_reply_id_foreign` (`reply_id`),
  ADD KEY `wirechat_messages_conversation_id_index` (`conversation_id`),
  ADD KEY `wirechat_messages_sendable_id_sendable_type_index` (`sendable_id`,`sendable_type`);

--
-- Indexes for table `wirechat_participants`
--
ALTER TABLE `wirechat_participants`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `conv_part_id_type_unique` (`conversation_id`,`participantable_id`,`participantable_type`),
  ADD KEY `wirechat_participants_role_index` (`role`),
  ADD KEY `wirechat_participants_exited_at_index` (`exited_at`),
  ADD KEY `wirechat_participants_conversation_cleared_at_index` (`conversation_cleared_at`),
  ADD KEY `wirechat_participants_conversation_deleted_at_index` (`conversation_deleted_at`),
  ADD KEY `wirechat_participants_conversation_read_at_index` (`conversation_read_at`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `candidate_profiles`
--
ALTER TABLE `candidate_profiles`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `chat_attachments`
--
ALTER TABLE `chat_attachments`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=60;

--
-- AUTO_INCREMENT for table `chat_bot_commands`
--
ALTER TABLE `chat_bot_commands`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `chat_bot_profiles`
--
ALTER TABLE `chat_bot_profiles`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `chat_conversation`
--
ALTER TABLE `chat_conversation`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=110;

--
-- AUTO_INCREMENT for table `chat_conversation_participants`
--
ALTER TABLE `chat_conversation_participants`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=347;

--
-- AUTO_INCREMENT for table `chat_discussion_topics`
--
ALTER TABLE `chat_discussion_topics`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `chat_discussion_topic_messages`
--
ALTER TABLE `chat_discussion_topic_messages`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `chat_friendships`
--
ALTER TABLE `chat_friendships`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `chat_group_invites`
--
ALTER TABLE `chat_group_invites`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `chat_messages`
--
ALTER TABLE `chat_messages`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1981;

--
-- AUTO_INCREMENT for table `chat_message_pins`
--
ALTER TABLE `chat_message_pins`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `chat_message_reactions`
--
ALTER TABLE `chat_message_reactions`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `chat_message_reads`
--
ALTER TABLE `chat_message_reads`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `chat_message_reports`
--
ALTER TABLE `chat_message_reports`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `chat_message_user_states`
--
ALTER TABLE `chat_message_user_states`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `chat_moderation_events`
--
ALTER TABLE `chat_moderation_events`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `chat_moderation_rules`
--
ALTER TABLE `chat_moderation_rules`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `chat_presence_statuses`
--
ALTER TABLE `chat_presence_statuses`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `chat_questions`
--
ALTER TABLE `chat_questions`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=53;

--
-- AUTO_INCREMENT for table `chat_roles`
--
ALTER TABLE `chat_roles`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `chat_role_assignments`
--
ALTER TABLE `chat_role_assignments`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `chat_todos`
--
ALTER TABLE `chat_todos`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `chat_topic_subscriptions`
--
ALTER TABLE `chat_topic_subscriptions`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `chat_user_favorite_reactions`
--
ALTER TABLE `chat_user_favorite_reactions`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `chat_waiting_list`
--
ALTER TABLE `chat_waiting_list`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `companies`
--
ALTER TABLE `companies`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `dropdown_options`
--
ALTER TABLE `dropdown_options`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=78;

--
-- AUTO_INCREMENT for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `group_join_requests`
--
ALTER TABLE `group_join_requests`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `jobs`
--
ALTER TABLE `jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `job_applications`
--
ALTER TABLE `job_applications`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `job_postings`
--
ALTER TABLE `job_postings`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=39;

--
-- AUTO_INCREMENT for table `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `personal_tags`
--
ALTER TABLE `personal_tags`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `personal_tag_messages`
--
ALTER TABLE `personal_tag_messages`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `ratings`
--
ALTER TABLE `ratings`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `saved_jobs`
--
ALTER TABLE `saved_jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `t_file_manager`
--
ALTER TABLE `t_file_manager`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2971;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `wirechat_actions`
--
ALTER TABLE `wirechat_actions`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `wirechat_attachments`
--
ALTER TABLE `wirechat_attachments`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `wirechat_conversations`
--
ALTER TABLE `wirechat_conversations`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `wirechat_groups`
--
ALTER TABLE `wirechat_groups`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `wirechat_messages`
--
ALTER TABLE `wirechat_messages`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `wirechat_participants`
--
ALTER TABLE `wirechat_participants`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `candidate_profiles`
--
ALTER TABLE `candidate_profiles`
  ADD CONSTRAINT `candidate_profiles_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `chat_bot_commands`
--
ALTER TABLE `chat_bot_commands`
  ADD CONSTRAINT `chat_bot_commands_bot_id_foreign` FOREIGN KEY (`bot_id`) REFERENCES `chat_bot_profiles` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `chat_bot_commands_required_role_id_foreign` FOREIGN KEY (`required_role_id`) REFERENCES `chat_roles` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `chat_bot_profiles`
--
ALTER TABLE `chat_bot_profiles`
  ADD CONSTRAINT `chat_bot_profiles_conversation_id_foreign` FOREIGN KEY (`conversation_id`) REFERENCES `chat_conversation` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `chat_bot_profiles_owner_user_id_foreign` FOREIGN KEY (`owner_user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `chat_discussion_topics`
--
ALTER TABLE `chat_discussion_topics`
  ADD CONSTRAINT `chat_discussion_topics_conversation_id_foreign` FOREIGN KEY (`conversation_id`) REFERENCES `chat_conversation` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `chat_discussion_topics_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `chat_discussion_topic_messages`
--
ALTER TABLE `chat_discussion_topic_messages`
  ADD CONSTRAINT `chat_discussion_topic_messages_message_id_foreign` FOREIGN KEY (`message_id`) REFERENCES `chat_messages` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `chat_discussion_topic_messages_topic_id_foreign` FOREIGN KEY (`topic_id`) REFERENCES `chat_discussion_topics` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `chat_friendships`
--
ALTER TABLE `chat_friendships`
  ADD CONSTRAINT `chat_friendships_addressee_id_foreign` FOREIGN KEY (`addressee_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `chat_friendships_blocked_by_user_id_foreign` FOREIGN KEY (`blocked_by_user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `chat_friendships_requester_id_foreign` FOREIGN KEY (`requester_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `chat_group_invites`
--
ALTER TABLE `chat_group_invites`
  ADD CONSTRAINT `chat_group_invites_conversation_id_foreign` FOREIGN KEY (`conversation_id`) REFERENCES `chat_conversation` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `chat_group_invites_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `chat_messages`
--
ALTER TABLE `chat_messages`
  ADD CONSTRAINT `chat_messages_forwarded_from_message_id_foreign` FOREIGN KEY (`forwarded_from_message_id`) REFERENCES `chat_messages` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `chat_message_reports`
--
ALTER TABLE `chat_message_reports`
  ADD CONSTRAINT `chat_message_reports_conversation_id_foreign` FOREIGN KEY (`conversation_id`) REFERENCES `chat_conversation` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `chat_message_reports_message_id_foreign` FOREIGN KEY (`message_id`) REFERENCES `chat_messages` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `chat_message_reports_reporter_id_foreign` FOREIGN KEY (`reporter_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `chat_message_reports_resolved_by_foreign` FOREIGN KEY (`resolved_by`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `chat_message_user_states`
--
ALTER TABLE `chat_message_user_states`
  ADD CONSTRAINT `chat_message_user_states_message_id_foreign` FOREIGN KEY (`message_id`) REFERENCES `chat_messages` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `chat_message_user_states_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `chat_moderation_events`
--
ALTER TABLE `chat_moderation_events`
  ADD CONSTRAINT `chat_moderation_events_bot_id_foreign` FOREIGN KEY (`bot_id`) REFERENCES `chat_bot_profiles` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `chat_moderation_events_message_id_foreign` FOREIGN KEY (`message_id`) REFERENCES `chat_messages` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `chat_moderation_events_rule_id_foreign` FOREIGN KEY (`rule_id`) REFERENCES `chat_moderation_rules` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `chat_moderation_events_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `chat_moderation_rules`
--
ALTER TABLE `chat_moderation_rules`
  ADD CONSTRAINT `chat_moderation_rules_bot_id_foreign` FOREIGN KEY (`bot_id`) REFERENCES `chat_bot_profiles` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `chat_moderation_rules_conversation_id_foreign` FOREIGN KEY (`conversation_id`) REFERENCES `chat_conversation` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `chat_moderation_rules_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `chat_presence_statuses`
--
ALTER TABLE `chat_presence_statuses`
  ADD CONSTRAINT `chat_presence_statuses_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `chat_roles`
--
ALTER TABLE `chat_roles`
  ADD CONSTRAINT `chat_roles_conversation_id_foreign` FOREIGN KEY (`conversation_id`) REFERENCES `chat_conversation` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `chat_roles_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `chat_role_assignments`
--
ALTER TABLE `chat_role_assignments`
  ADD CONSTRAINT `chat_role_assignments_assigned_by_foreign` FOREIGN KEY (`assigned_by`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `chat_role_assignments_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `chat_roles` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `chat_todos`
--
ALTER TABLE `chat_todos`
  ADD CONSTRAINT `chat_todos_assigned_to_foreign` FOREIGN KEY (`assigned_to`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `chat_todos_conversation_id_foreign` FOREIGN KEY (`conversation_id`) REFERENCES `chat_conversation` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `chat_todos_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `chat_topic_subscriptions`
--
ALTER TABLE `chat_topic_subscriptions`
  ADD CONSTRAINT `chat_topic_subscriptions_topic_id_foreign` FOREIGN KEY (`topic_id`) REFERENCES `chat_discussion_topics` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `chat_topic_subscriptions_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `chat_user_favorite_reactions`
--
ALTER TABLE `chat_user_favorite_reactions`
  ADD CONSTRAINT `chat_user_favorite_reactions_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `chat_waiting_list`
--
ALTER TABLE `chat_waiting_list`
  ADD CONSTRAINT `chat_waiting_list_assigned_by_foreign` FOREIGN KEY (`assigned_by`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `chat_waiting_list_conversation_id_foreign` FOREIGN KEY (`conversation_id`) REFERENCES `chat_conversation` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `chat_waiting_list_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `group_join_requests`
--
ALTER TABLE `group_join_requests`
  ADD CONSTRAINT `group_join_requests_conversation_id_foreign` FOREIGN KEY (`conversation_id`) REFERENCES `chat_conversation` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `group_join_requests_reviewed_by_foreign` FOREIGN KEY (`reviewed_by`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `group_join_requests_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `job_applications`
--
ALTER TABLE `job_applications`
  ADD CONSTRAINT `job_applications_candidate_profile_id_foreign` FOREIGN KEY (`candidate_profile_id`) REFERENCES `candidate_profiles` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `job_applications_job_posting_id_foreign` FOREIGN KEY (`job_posting_id`) REFERENCES `job_postings` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `job_applications_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `job_postings`
--
ALTER TABLE `job_postings`
  ADD CONSTRAINT `job_postings_company_id_foreign` FOREIGN KEY (`company_id`) REFERENCES `companies` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `personal_tags`
--
ALTER TABLE `personal_tags`
  ADD CONSTRAINT `personal_tags_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `personal_tag_messages`
--
ALTER TABLE `personal_tag_messages`
  ADD CONSTRAINT `personal_tag_messages_personal_tag_id_foreign` FOREIGN KEY (`personal_tag_id`) REFERENCES `personal_tags` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `personal_tag_messages_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `ratings`
--
ALTER TABLE `ratings`
  ADD CONSTRAINT `ratings_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `saved_jobs`
--
ALTER TABLE `saved_jobs`
  ADD CONSTRAINT `saved_jobs_candidate_profile_id_foreign` FOREIGN KEY (`candidate_profile_id`) REFERENCES `candidate_profiles` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `saved_jobs_job_posting_id_foreign` FOREIGN KEY (`job_posting_id`) REFERENCES `job_postings` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `saved_jobs_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `t_file_manager`
--
ALTER TABLE `t_file_manager`
  ADD CONSTRAINT `file_managers_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `wirechat_messages`
--
ALTER TABLE `wirechat_messages`
  ADD CONSTRAINT `wirechat_messages_conversation_id_foreign` FOREIGN KEY (`conversation_id`) REFERENCES `wirechat_conversations` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `wirechat_messages_reply_id_foreign` FOREIGN KEY (`reply_id`) REFERENCES `wirechat_messages` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `wirechat_participants`
--
ALTER TABLE `wirechat_participants`
  ADD CONSTRAINT `wirechat_participants_conversation_id_foreign` FOREIGN KEY (`conversation_id`) REFERENCES `wirechat_conversations` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
