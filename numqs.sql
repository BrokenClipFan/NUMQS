-- phpMyAdmin SQL Dump
-- version 5.2.3
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Jul 15, 2026 at 04:24 AM
-- Server version: 8.4.3
-- PHP Version: 8.3.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `numqs`
--

-- --------------------------------------------------------

--
-- Table structure for table `cache`
--

CREATE TABLE `cache` (
  `key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `value` mediumtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` bigint NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `cache_locks`
--

CREATE TABLE `cache_locks` (
  `key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `owner` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` bigint NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `driver_profiles`
--

CREATE TABLE `driver_profiles` (
  `id` bigint UNSIGNED NOT NULL,
  `user_id` bigint UNSIGNED NOT NULL,
  `first_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `last_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `middle_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `phone` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `address` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `birthdate` date DEFAULT NULL,
  `emergency_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `emergency_phone` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `license_number` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `plate_number` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `image_path` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `jeep_icon` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '/icons/defaultJeepney.png',
  `profile` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '/profiles/defaultAvatar.png'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `driver_profiles`
--

INSERT INTO `driver_profiles` (`id`, `user_id`, `first_name`, `last_name`, `middle_name`, `phone`, `address`, `birthdate`, `emergency_name`, `emergency_phone`, `license_number`, `plate_number`, `created_at`, `updated_at`, `image_path`, `jeep_icon`, `profile`) VALUES
(1, 1, 'Alexzander Koch', 'Juston Conn', 'Garrett Kovacek II', '(215) 818-4719', '46307 Cronin Vista Suite 732\nOlgastad, MT 18017-8220', '1993-09-05', 'Sonya Gutkowski', '+1.706.917.3110', 'cyd-918240', 'NVN-0941', '2026-07-10 00:52:21', '2026-07-10 00:52:21', NULL, 'icons/jeep-orange.png', '/profiles/defaultAvatar.png'),
(2, 2, 'Susanna Paucek', 'Dr. Jadyn White PhD', 'Anabel Kshlerin', '608.383.0713', '66494 Christa Mountain Apt. 272\nLake Amie, CT 21018-2229', '1976-10-14', 'Karen Sanford', '+1.541.889.8132', 'brs-360383', 'LVX-7173', '2026-07-10 00:52:21', '2026-07-10 00:52:21', NULL, 'icons/jeep-black.png', '/profiles/defaultAvatar.png'),
(6, 6, 'August Borer', 'Tiffany Rutherford', 'Ms. Brielle Simonis', '(463) 665-8193', '488 Davis Pass Suite 376\nEast Erynbury, RI 19551', '2006-03-05', 'Dr. Allan Mosciski', '(414) 210-7714', 'fsw-012737', 'FRP-2846', '2026-07-10 00:52:21', '2026-07-10 00:52:21', NULL, 'icons/jeep-darkblue.png', '/profiles/defaultAvatar.png'),
(7, 7, 'Asha Blanda MD', 'Aiyana Tremblay MD', 'Newell Balistreri', '+1-820-462-7716', '717 Weissnat Springs Suite 400\nLake Bradlyburgh, MI 67853-9515', '1991-11-07', 'Ms. Sydnee Thompson MD', '+1.916.225.8390', 'qsq-449860', 'UES-9558', '2026-07-10 00:52:21', '2026-07-10 00:52:21', NULL, 'icons/jeep-lime.png', '/profiles/defaultAvatar.png'),
(8, 8, 'Maia Eichmann PhD', 'Sid Schaefer', 'Flossie Ernser', '(757) 881-5874', '6995 Lavern Freeway Suite 956\nSouth Maynardstad, DC 87185', '1978-08-30', 'Israel Renner', '385.875.7043', 'lsh-890247', 'DJS-5327', '2026-07-10 00:52:21', '2026-07-12 23:00:08', NULL, 'icons/jeep-red.png', '//profiles/dctw7VoT6y0BQBrMsXgxpGoQGCaoaemOFm61omrH.png'),
(9, 9, 'Ms. Nettie Collier Sr.', 'Ms. Gwen Schaefer DVM', 'Levi Funk', '+1-380-426-7988', '999 Gretchen Lodge Suite 300\nRoweburgh, KS 06050', '1976-11-19', 'Mr. Junior Wiegand IV', '386.962.0249', 'gio-853882', 'IRT-6758', '2026-07-10 00:52:21', '2026-07-10 00:52:21', NULL, 'icons/jeep-green.png', '/profiles/defaultAvatar.png'),
(10, 10, 'Karolann Gerhold', 'Mr. Hyman Willms', 'Prof. Ivory McLaughlin', '214.225.0753', '293 Chaz Grove Suite 019\nPort Kaylee, MD 35232', '2002-04-16', 'Hillard Baumbach', '(678) 496-9428', 'muh-485237', 'TCK-3745', '2026-07-10 00:52:21', '2026-07-10 00:52:21', NULL, 'icons/jeep-darkgreen.png', '/profiles/defaultAvatar.png'),
(11, 11, 'Aron Dietrich', 'Fermin Will', 'Miss Damaris Roob', '276-644-6772', '6313 Adonis Gateway\nFloridafurt, NM 17773-5349', '1992-10-27', 'Jodie Stanton', '757-420-1127', 'ksj-778501', 'FES-5912', '2026-07-10 00:52:40', '2026-07-10 00:52:40', NULL, 'icons/jeep-green.png', '/profiles/defaultAvatar.png'),
(12, 12, 'Mr. Hadley Reynolds MD', 'Marilou Crona', 'Hayley Koelpin V', '1-832-500-7024', '1583 DuBuque Mountains Suite 421\nHanehaven, MN 38416', '1976-10-03', 'Miss Mayra Swift Jr.', '+1-518-820-6661', 'jjm-004197', 'QFC-9421', '2026-07-10 00:52:40', '2026-07-10 00:52:40', NULL, 'icons/jeep-black.png', '/profiles/defaultAvatar.png'),
(13, 13, 'Prof. Emmitt Reinger', 'Prof. Vivien Lueilwitz Jr.', 'Dorian Fahey Jr.', '(812) 863-8137', '3395 Shaniya Field Apt. 948\nPort Michelle, LA 54099', '2006-05-13', 'Jacquelyn Hahn PhD', '1-972-243-9319', 'wny-800393', 'PJL-4944', '2026-07-10 00:52:40', '2026-07-10 00:52:40', NULL, 'icons/jeep-maroon.png', '/profiles/defaultAvatar.png'),
(14, 14, 'Miss Luisa Grimes', 'Jailyn Kuphal', 'Miss Tamia Keebler PhD', '862-433-9721', '2966 Schowalter Station Apt. 017\nZitaview, SC 51669', '1982-09-29', 'Jeffery Gibson', '(223) 505-3659', 'swf-078736', 'AAI-4300', '2026-07-10 00:52:40', '2026-07-10 00:52:40', NULL, 'icons/jeep-red.png', '/profiles/defaultAvatar.png'),
(15, 15, 'Mrs. Rhianna Nolan DVM', 'Demarco Bosco', 'Emilie Cremin', '1-669-747-9809', '802 Wilderman Summit\nKohlerburgh, CA 93483', '1978-06-05', 'Dr. Rollin Mills', '434-790-9975', 'qkm-109647', 'ROF-6144', '2026-07-10 00:52:40', '2026-07-10 00:52:40', NULL, 'icons/jeep-pink.png', '/profiles/defaultAvatar.png'),
(16, 16, 'Caroline Green PhD', 'Isaias Breitenberg', 'Camryn Ondricka DVM', '(563) 308-0358', '80729 Mercedes Shores\nNew Rebeccabury, VA 40577-8941', '1995-09-06', 'Prof. Jacky Fritsch', '1-346-465-7236', 'jgx-446433', 'QWQ-1505', '2026-07-10 00:52:40', '2026-07-10 00:52:40', NULL, 'icons/jeep-orange.png', '/profiles/defaultAvatar.png'),
(17, 17, 'Karl Jacobs', 'Cecile Langosh', 'Margarett Hilpert', '1-510-504-0719', '8073 Mabel Manors\nMayramouth, NE 25069', '1979-02-17', 'Dr. Lloyd Haley', '248-661-2018', 'ofl-985066', 'HCK-3910', '2026-07-10 00:52:40', '2026-07-10 00:52:40', NULL, 'icons/jeep-red.png', '/profiles/defaultAvatar.png'),
(18, 18, 'Taylor Kulas', 'Nelle Lang', 'Prof. Jaida Gerhold', '208-294-8758', '8692 Klein Trafficway\nEast Sven, ME 77193', '1976-12-16', 'Dr. Francisco Bogan', '+1 (309) 258-5981', 'fzh-851406', 'YDA-2689', '2026-07-10 00:52:40', '2026-07-10 00:52:40', NULL, 'icons/jeep-yellow.png', '/profiles/defaultAvatar.png'),
(19, 19, 'Chris Flatley Jr.', 'Prof. Warren Sanford', 'Gilbert Schuster', '+1-678-356-3331', '142 Arlene Course\nBlaisebury, TN 72924-7097', '1988-12-25', 'Alfred Lang DVM', '+1.516.436.4660', 'rlo-010493', 'UQG-0190', '2026-07-10 00:52:40', '2026-07-10 00:52:40', NULL, 'icons/jeep-white.png', '/profiles/defaultAvatar.png'),
(20, 20, 'Destiney Jacobs', 'Mrs. Ida Wolff DDS', 'Mr. Brycen Blanda', '(810) 344-7824', '467 Labadie Dale\nKatrineborough, KS 35680', '1982-07-13', 'Isabell Cassin', '+1-551-998-4823', 'dkz-954823', 'HTZ-5948', '2026-07-10 00:52:40', '2026-07-10 00:52:40', NULL, 'icons/jeep-maroon.png', '/profiles/defaultAvatar.png'),
(21, 21, 'Elizabeth Reilly', 'Allen Muller', 'Prof. Creola Greenholt MD', '865-769-8668', '2552 Ottis Landing Suite 368\nSchmitttown, UT 46829-1057', '1987-11-27', 'Johnson Boyer', '1-341-394-1989', 'mmp-878595', 'HJE-3982', '2026-07-10 00:52:40', '2026-07-10 00:52:40', NULL, 'icons/jeep-pink.png', '/profiles/defaultAvatar.png'),
(22, 22, 'Betsy Metz DDS', 'Casper Kuphal', 'Mrs. Nicole Wiza', '(228) 832-3750', '65391 Batz Plain Suite 046\nWest Susiechester, MO 85207', '1984-08-14', 'Vergie Rempel Sr.', '+1 (820) 246-3888', 'itm-101936', 'URM-3053', '2026-07-10 00:52:40', '2026-07-10 00:52:40', NULL, 'icons/jeep-white.png', '/profiles/defaultAvatar.png'),
(23, 23, 'Selena Goyette', 'Ona Dietrich', 'Abbigail Marks', '+1.678.963.5093', '566 Osinski Square Apt. 076\nStarkfort, IL 22166-7667', '1987-01-22', 'Jany Ledner', '+1 (986) 579-6723', 'jiw-148631', 'KUH-8825', '2026-07-10 00:52:40', '2026-07-10 00:52:40', NULL, 'icons/jeep-purple.png', '/profiles/defaultAvatar.png'),
(24, 24, 'Noelia Gutkowski', 'Claude Simonis', 'Zoie Schmidt', '+1.838.290.6891', '6213 Mills Underpass\nWest Todside, NC 14493', '1979-12-04', 'Louisa Abbott', '+1-585-993-0915', 'ltt-411054', 'RKW-2717', '2026-07-10 00:52:40', '2026-07-10 00:52:40', NULL, 'icons/jeep-red.png', '/profiles/defaultAvatar.png'),
(25, 25, 'Gabrielle Marquardt', 'Drew Howe', 'Augustus Kiehn', '+1 (469) 783-7261', '991 Durgan Center Suite 171\nLubowitzbury, MA 02977', '1976-10-12', 'Dolly Legros', '508.614.1294', 'suw-052022', 'RRO-0453', '2026-07-10 00:52:40', '2026-07-10 00:52:40', NULL, 'icons/jeep-maroon.png', '/profiles/defaultAvatar.png'),
(26, 26, 'Kelsie Prosacco', 'Miles O\'Hara', 'Hallie Sawayn I', '816.391.6410', '808 Senger Vista Suite 735\nSouth Jenniferstad, MI 74336', '1985-01-03', 'Kennedy Gutkowski', '+17346624854', 'jnw-903829', 'GFW-7664', '2026-07-10 00:52:40', '2026-07-10 00:52:40', NULL, 'icons/jeep-cyan.png', '/profiles/defaultAvatar.png'),
(27, 27, 'Ally Adams', 'Christy Botsford DVM', 'Miss Kelli Shields', '+14402069942', '2930 Murphy Rapid\nNew Jazlynfort, WY 02603-1851', '1997-01-12', 'Myron Schmitt III', '272.935.0994', 'lbe-039694', 'JWH-2933', '2026-07-10 00:52:40', '2026-07-10 00:52:40', NULL, 'icons/jeep-blue.png', '/profiles/defaultAvatar.png'),
(28, 28, 'Mrs. Margret Jenkins Sr.', 'Libby Hickle PhD', 'Jenifer Jerde I', '458.353.7080', '69568 Will Union Suite 549\nJedidiahborough, MI 61929-9839', '1995-03-15', 'Amya Stroman II', '+1-480-369-2142', 'zkd-790136', 'AFG-6868', '2026-07-10 00:52:40', '2026-07-10 00:52:40', NULL, 'icons/jeep-pink.png', '/profiles/defaultAvatar.png'),
(29, 29, 'Devante Schamberger', 'Neoma Cremin', 'Barton Tremblay', '+16208004221', '4220 Dante Island Suite 377\nNew Briannebury, WY 26236-1298', '2000-09-27', 'Chauncey Schamberger', '+1 (530) 436-4395', 'zpb-082212', 'JKI-5257', '2026-07-10 00:52:40', '2026-07-10 00:52:40', NULL, 'icons/jeep-darkblue.png', '/profiles/defaultAvatar.png'),
(30, 30, 'Prof. Jordi Little', 'Alessia Wuckert', 'Scotty Purdy', '806.786.8475', '34216 Carter Throughway Apt. 802\nNorth Arlo, TN 74832', '1993-07-20', 'Josephine Conroy', '(908) 430-8786', 'etb-720302', 'ANC-1044', '2026-07-10 00:52:40', '2026-07-10 00:52:40', NULL, 'icons/jeep-brown.png', '/profiles/defaultAvatar.png'),
(31, 31, 'Axle', 'Lapiz', 'Geraldez', '09432897574', 'asdfasf', '2026-07-17', 'Grace Lapiz Geraldez', '0998-765-4321', 'N01-23-456789', 'ABC-1234', '2026-07-10 00:53:47', '2026-07-10 00:53:47', 'jeepneys/driver_31.png', '/icons/defaultJeepney.png', '/profiles/defaultAvatar.png'),
(32, 32, 'Joy', 'Boy', 'aa', '234234234', 'sdfasf', '2026-07-08', 'adfawsadf', '23412412412421', 'N01-23-4567892', 'ABC-12342', '2026-07-11 04:55:05', '2026-07-11 04:55:05', 'jeepneys/driver_32.jpg', '/icons/defaultJeepney.png', '/profiles/defaultAvatar.png'),
(37, 33, 'asdf', 'fdas', 'xzcv', '23424', 'fsdsdasdsfasdf', '2026-06-30', 'wasadf', '23424', 'N01-23-456789234', 'ABC-12343123', '2026-07-11 05:12:58', '2026-07-11 05:12:58', 'jeepneys/driver_33.png', '/icons/defaultJeepney.png', '/profiles/defaultAvatar.png'),
(38, 34, 'asdfasdf', 'asdfasdf', 'asdfasdf', '234242314124', 'asdfasdfasdfasdf', '2026-07-08', 'asdfasdfa', '34346346345', 'N01-23-4567892342', 'ABC-1234546734', '2026-07-11 05:23:16', '2026-07-11 05:23:16', 'jeepneys/driver_34.jpg', '/icons/defaultJeepney.png', '/profiles/defaultAvatar.png'),
(39, 35, 'John1231', 'Pendoko34234', 'xzcv424', '094328975743', 'asdfsadwfas', '2026-07-16', 'Maria Sanchezasdf', '0998-765-4321', 'N01-23-456789234234', 'ABC-123423424', '2026-07-11 05:29:19', '2026-07-11 05:29:19', 'jeepneys/driver_35.png', '/icons/defaultJeepney.png', '/profiles/defaultAvatar.png');

-- --------------------------------------------------------

--
-- Table structure for table `driver_queues`
--

CREATE TABLE `driver_queues` (
  `id` bigint UNSIGNED NOT NULL,
  `driver_profile_id` bigint UNSIGNED NOT NULL,
  `terminal_id` bigint UNSIGNED NOT NULL,
  `queued_at` timestamp NOT NULL,
  `filling_at` timestamp NULL DEFAULT NULL,
  `position` int NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `driver_statuses`
--

CREATE TABLE `driver_statuses` (
  `id` bigint UNSIGNED NOT NULL,
  `user_id` bigint UNSIGNED NOT NULL,
  `latitude` decimal(10,7) NOT NULL DEFAULT '0.0000000',
  `longitude` decimal(10,7) NOT NULL DEFAULT '0.0000000',
  `dispatched_to` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `state` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'idle',
  `last_updated` datetime DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `is_online` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '0',
  `waypoint_index` int NOT NULL DEFAULT '0',
  `going_to` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'Uling',
  `wifi_bssid` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `queued_in` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `driver_statuses`
--

INSERT INTO `driver_statuses` (`id`, `user_id`, `latitude`, `longitude`, `dispatched_to`, `state`, `last_updated`, `created_at`, `updated_at`, `is_online`, `waypoint_index`, `going_to`, `wifi_bssid`, `queued_in`) VALUES
(1, 1, 10.2413498, 123.7231766, 'Naga', 'in_route', '2026-07-15 02:59:55', '2026-07-10 00:52:21', '2026-07-14 18:59:55', '1', 206, 'Naga', NULL, NULL),
(2, 2, 10.2880361, 123.7161827, 'Uling', 'in_route', '2026-07-15 02:59:55', '2026-07-10 00:52:21', '2026-07-14 18:59:55', '1', 356, 'Uling', NULL, NULL),
(6, 6, 10.2445040, 123.7241851, 'Uling', 'in_route', '2026-07-15 02:59:55', '2026-07-10 00:52:21', '2026-07-14 18:59:55', '1', 218, 'Uling', NULL, NULL),
(7, 7, 10.2122655, 123.7414263, 'Naga', 'in_route', '2026-07-15 02:59:55', '2026-07-10 00:52:21', '2026-07-14 18:59:55', '1', 95, 'Naga', NULL, NULL),
(8, 8, 10.2375529, 123.7212534, 'Uling', 'in_route', '2026-07-15 02:59:55', '2026-07-10 00:52:21', '2026-07-14 18:59:55', '1', 194, 'Uling', NULL, NULL),
(9, 9, 10.2049441, 123.7551177, 'Naga', 'in_route', '2026-07-15 02:59:55', '2026-07-10 00:52:21', '2026-07-14 18:59:55', '1', 12, 'Naga', NULL, NULL),
(10, 10, 10.2883448, 123.7163731, 'Uling', 'queued', '2026-07-15 02:59:55', '2026-07-10 00:52:21', '2026-07-14 18:59:55', '1', 357, 'Uling', '00:1A:2B:3C:4D:51', NULL),
(11, 11, 10.2128449, 123.7410857, 'Uling', 'in_route', '2026-07-15 02:59:55', '2026-07-10 00:52:40', '2026-07-14 18:59:55', '1', 100, 'Uling', NULL, NULL),
(12, 12, 10.2076499, 123.7456226, 'Uling', 'in_route', '2026-07-15 02:59:55', '2026-07-10 00:52:40', '2026-07-14 18:59:55', '1', 58, 'Uling', NULL, NULL),
(13, 13, 10.2676630, 123.7137486, 'Naga', 'in_route', '2026-07-15 02:59:53', '2026-07-10 00:52:40', '2026-07-14 18:59:53', '1', 304, 'Naga', NULL, NULL),
(14, 14, 10.2451929, 123.7248798, 'Naga', 'in_route', '2026-07-15 02:59:55', '2026-07-10 00:52:40', '2026-07-14 18:59:55', '1', 223, 'Naga', NULL, NULL),
(15, 15, 10.2055684, 123.7530738, 'Uling', 'in_route', '2026-07-15 02:59:55', '2026-07-10 00:52:40', '2026-07-14 18:59:55', '1', 22, 'Uling', NULL, NULL),
(16, 16, 10.2665373, 123.7134133, 'Uling', 'in_route', '2026-07-15 02:59:53', '2026-07-10 00:52:40', '2026-07-14 18:59:53', '1', 301, 'Uling', NULL, NULL),
(17, 17, 10.2815834, 123.7130097, 'Naga', 'in_route', '2026-07-15 02:59:55', '2026-07-10 00:52:40', '2026-07-14 18:59:55', '1', 339, 'Naga', NULL, NULL),
(18, 18, 10.2525186, 123.7194094, 'Naga', 'in_route', '2026-07-15 02:59:55', '2026-07-10 00:52:40', '2026-07-14 18:59:55', '1', 249, 'Naga', NULL, NULL),
(19, 19, 10.2124819, 123.7414250, 'Naga', 'in_route', '2026-07-15 02:59:55', '2026-07-10 00:52:40', '2026-07-14 18:59:55', '1', 98, 'Naga', NULL, NULL),
(20, 20, 10.2146544, 123.7376015, 'Uling', 'in_route', '2026-07-15 02:59:55', '2026-07-10 00:52:40', '2026-07-14 18:59:55', '1', 125, 'Uling', NULL, NULL),
(21, 21, 10.2175594, 123.7361974, 'Naga', 'in_route', '2026-07-15 02:59:53', '2026-07-10 00:52:40', '2026-07-14 18:59:53', '1', 138, 'Naga', NULL, NULL),
(22, 22, 10.2079627, 123.7584087, 'Naga', 'queued', '2026-07-15 02:59:55', '2026-07-10 00:52:40', '2026-07-14 18:59:55', '1', 0, 'Naga', '00:1A:2B:3C:4D:52', NULL),
(23, 23, 10.2281496, 123.7338585, 'Naga', 'in_route', '2026-07-15 02:59:55', '2026-07-10 00:52:40', '2026-07-14 18:59:55', '1', 163, 'Naga', NULL, NULL),
(24, 24, 10.2202096, 123.7337472, 'Naga', 'in_route', '2026-07-15 02:59:55', '2026-07-10 00:52:40', '2026-07-14 18:59:55', '1', 147, 'Naga', NULL, NULL),
(25, 25, 10.2737583, 123.7134817, 'Naga', 'in_route', '2026-07-15 02:59:55', '2026-07-10 00:52:40', '2026-07-14 18:59:55', '1', 320, 'Naga', NULL, NULL),
(26, 26, 10.2883448, 123.7163731, 'Uling', 'queued', '2026-07-15 02:59:55', '2026-07-10 00:52:40', '2026-07-14 18:59:55', '1', 357, 'Uling', '00:1A:2B:3C:4D:51', NULL),
(27, 27, 10.2202096, 123.7337472, 'Uling', 'in_route', '2026-07-15 02:59:55', '2026-07-10 00:52:40', '2026-07-14 18:59:55', '1', 147, 'Uling', NULL, NULL),
(28, 28, 10.2174868, 123.7366198, 'Uling', 'in_route', '2026-07-15 02:59:55', '2026-07-10 00:52:40', '2026-07-14 18:59:55', '1', 137, 'Uling', NULL, NULL),
(29, 29, 10.2527113, 123.7132323, 'Uling', 'in_route', '2026-07-15 02:59:55', '2026-07-10 00:52:40', '2026-07-14 18:59:55', '1', 262, 'Uling', NULL, NULL),
(30, 30, 10.2079614, 123.7487139, 'Naga', 'in_route', '2026-07-15 02:59:55', '2026-07-10 00:52:40', '2026-07-14 18:59:55', '1', 38, 'Naga', NULL, NULL),
(31, 31, 0.0000000, 0.0000000, NULL, 'idle', NULL, '2026-07-10 00:53:47', '2026-07-10 00:53:47', '0', 0, 'Uling', NULL, NULL),
(32, 32, 0.0000000, 0.0000000, NULL, 'idle', NULL, '2026-07-11 04:55:05', '2026-07-11 04:55:05', '0', 0, 'Uling', NULL, NULL),
(33, 33, 0.0000000, 0.0000000, NULL, 'idle', NULL, '2026-07-11 05:12:58', '2026-07-11 05:12:58', '0', 0, 'Uling', NULL, NULL),
(34, 34, 0.0000000, 0.0000000, NULL, 'idle', NULL, '2026-07-11 05:23:16', '2026-07-11 05:23:16', '0', 0, 'Uling', NULL, NULL),
(35, 35, 0.0000000, 0.0000000, NULL, 'idle', NULL, '2026-07-11 05:29:19', '2026-07-11 05:29:19', '0', 0, 'Uling', NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `failed_jobs`
--

CREATE TABLE `failed_jobs` (
  `id` bigint UNSIGNED NOT NULL,
  `uuid` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `connection` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `queue` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `exception` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `jobs`
--

CREATE TABLE `jobs` (
  `id` bigint UNSIGNED NOT NULL,
  `queue` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `attempts` smallint UNSIGNED NOT NULL,
  `reserved_at` int UNSIGNED DEFAULT NULL,
  `available_at` int UNSIGNED NOT NULL,
  `created_at` int UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `job_batches`
--

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
  `finished_at` int DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `migrations`
--

CREATE TABLE `migrations` (
  `id` int UNSIGNED NOT NULL,
  `migration` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '0001_01_01_000000_create_users_table', 1),
(2, '0001_01_01_000001_create_cache_table', 1),
(3, '0001_01_01_000002_create_jobs_table', 1),
(4, '2026_06_22_052848_add_socialite_fields_to_users', 1),
(5, '2026_06_22_055018_add_admin_verification_to_users_table', 1),
(6, '2026_06_22_060203_alter_facebook_token_column_type', 1),
(7, '2026_06_23_030843_alter_facebook_token_to_nullable', 1),
(8, '2026_06_23_033223_insert_avatar_to_users_table', 1),
(9, '2026_06_23_044006_create_driver_profiles_table', 1),
(10, '2026_06_23_051815_alter_driver_profiles_license_number', 1),
(11, '2026_06_23_073131_alter_license_plate_number_to_unique_for_driver_profile_table', 1),
(12, '2026_06_23_101918_add_role_to_users_table', 1),
(13, '2026_06_24_023639_create_driver_statuses_table', 1),
(14, '2026_06_25_080539_add_is_online_column_to_driver_statuses', 1),
(15, '2026_06_25_111925_create__routes_table', 1),
(16, '2026_06_25_120844_add_waypoint_index_column_to_driver_statuses_table', 1),
(17, '2026_06_27_012447_create_terminal_table', 1),
(18, '2026_06_28_052636_create_driver_queues_table', 1),
(19, '2026_07_03_112421_add_image_path_to_driver_profiles', 1),
(20, '2026_07_04_121012_add_jeep_icon_to_driver_profiles', 1),
(21, '2026_07_04_121317_add_driver_avatar_to_driver_profiles', 1),
(22, '2026_07_10_013333_add_going_to_to_users_table', 1),
(23, '2026_07_10_013755_add_going_to_to_driver_statuses', 1),
(24, '2026_07_10_020428_add_wifi_bssid_to_driver_statuses', 1),
(25, '2026_07_10_082547_create_violations_table', 1),
(26, '2026_07_11_022138_add_queued_in_to_driver_statuses', 2);

-- --------------------------------------------------------

--
-- Table structure for table `password_reset_tokens`
--

CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `routes`
--

CREATE TABLE `routes` (
  `id` bigint UNSIGNED NOT NULL,
  `route` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `path` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `routes`
--

INSERT INTO `routes` (`id`, `route`, `path`, `created_at`, `updated_at`) VALUES
(1, 'Naga-To-Uling', '[{\"lat\":10.207962686686447,\"lng\":123.75840872526172},{\"lat\":10.207616878947688,\"lng\":123.75821158289912},{\"lat\":10.207391179801007,\"lng\":123.75802785158159},{\"lat\":10.207209036513285,\"lng\":123.75783070921901},{\"lat\":10.206980697316876,\"lng\":123.75753164291383},{\"lat\":10.206765556768039,\"lng\":123.75717625021937},{\"lat\":10.206564934775507,\"lng\":123.75686243176462},{\"lat\":10.206270600965436,\"lng\":123.756390362978},{\"lat\":10.206003984454087,\"lng\":123.75596255064013},{\"lat\":10.205800722211206,\"lng\":123.75568360090259},{\"lat\":10.205576341662475,\"lng\":123.75548109412193},{\"lat\":10.205388917788756,\"lng\":123.75536575913432},{\"lat\":10.204944115618463,\"lng\":123.75511765480043},{\"lat\":10.204845123952984,\"lng\":123.75508949160579},{\"lat\":10.204313208209905,\"lng\":123.75475957989694},{\"lat\":10.204243253964147,\"lng\":123.75470325350761},{\"lat\":10.204289450165902,\"lng\":123.75464290380481},{\"lat\":10.20446499567139,\"lng\":123.75442296266559},{\"lat\":10.204593025039154,\"lng\":123.75426068902017},{\"lat\":10.204702575900587,\"lng\":123.7541614472866},{\"lat\":10.204971833279286,\"lng\":123.75383153557779},{\"lat\":10.205237130767866,\"lng\":123.75350028276445},{\"lat\":10.205568422346094,\"lng\":123.75307381153108},{\"lat\":10.205977586767528,\"lng\":123.7525574862957},{\"lat\":10.206257402133492,\"lng\":123.75220477581026},{\"lat\":10.20649762078914,\"lng\":123.75188961625102},{\"lat\":10.206723320569603,\"lng\":123.7515972554684},{\"lat\":10.2070057750401,\"lng\":123.75125527381898},{\"lat\":10.207137763024473,\"lng\":123.75104069709779},{\"lat\":10.207183958806077,\"lng\":123.75096157193185},{\"lat\":10.207245993130805,\"lng\":123.75086098909381},{\"lat\":10.207446614693975,\"lng\":123.75033393502237},{\"lat\":10.207593121150317,\"lng\":123.7498886883259},{\"lat\":10.207676273433364,\"lng\":123.74960705637935},{\"lat\":10.2077435871704,\"lng\":123.74941527843477},{\"lat\":10.207768664833488,\"lng\":123.7493482232094},{\"lat\":10.207880854354723,\"lng\":123.74897405505183},{\"lat\":10.207945528296053,\"lng\":123.74879166483882},{\"lat\":10.207961366810284,\"lng\":123.74871388077737},{\"lat\":10.207982484828042,\"lng\":123.74859586358072},{\"lat\":10.207981164951983,\"lng\":123.74849930405618},{\"lat\":10.207966646314855,\"lng\":123.7483048439026},{\"lat\":10.207956087305638,\"lng\":123.74823108315469},{\"lat\":10.207923090399525,\"lng\":123.74809965491298},{\"lat\":10.207896692872186,\"lng\":123.74804332852365},{\"lat\":10.207822779783973,\"lng\":123.74779924750331},{\"lat\":10.207733028153768,\"lng\":123.74751895666124},{\"lat\":10.207603680171582,\"lng\":123.7471327185631},{\"lat\":10.207524487503495,\"lng\":123.74690473079683},{\"lat\":10.20749017067453,\"lng\":123.74680280685426},{\"lat\":10.207475652015011,\"lng\":123.74674379825593},{\"lat\":10.207451894207082,\"lng\":123.74666467309001},{\"lat\":10.207438695424145,\"lng\":123.7465935945511},{\"lat\":10.207428136397397,\"lng\":123.74648228287698},{\"lat\":10.207432096032472,\"lng\":123.74638974666597},{\"lat\":10.207466412867696,\"lng\":123.74623015522958},{\"lat\":10.207527127259418,\"lng\":123.74600082635882},{\"lat\":10.207630077723234,\"lng\":123.74567762017251},{\"lat\":10.207649875885542,\"lng\":123.74562263488771},{\"lat\":10.207703990856217,\"lng\":123.74543890357018},{\"lat\":10.207776584095113,\"lng\":123.74523237347604},{\"lat\":10.207855776700479,\"lng\":123.74506741762163},{\"lat\":10.208032640114611,\"lng\":123.74483942985536},{\"lat\":10.208264938180816,\"lng\":123.74463960528377},{\"lat\":10.20842068338025,\"lng\":123.74449744820596},{\"lat\":10.208478757841883,\"lng\":123.74442368745805},{\"lat\":10.208524953428787,\"lng\":123.74436467885972},{\"lat\":10.208721614566553,\"lng\":123.74404415488243},{\"lat\":10.208774409482167,\"lng\":123.74395295977595},{\"lat\":10.208919595454951,\"lng\":123.74369546771052},{\"lat\":10.209033104442057,\"lng\":123.74348223209383},{\"lat\":10.209174330683446,\"lng\":123.74322474002838},{\"lat\":10.209381550382084,\"lng\":123.74284386634828},{\"lat\":10.209571611643053,\"lng\":123.74250188469888},{\"lat\":10.209657403147284,\"lng\":123.74231815338136},{\"lat\":10.209813147665036,\"lng\":123.7420150637627},{\"lat\":10.20988970002718,\"lng\":123.74182999134064},{\"lat\":10.209970211974387,\"lng\":123.7416046857834},{\"lat\":10.210017727212344,\"lng\":123.74147593975067},{\"lat\":10.210069202045467,\"lng\":123.74135792255403},{\"lat\":10.210116717268644,\"lng\":123.74129086732866},{\"lat\":10.210202508625867,\"lng\":123.74121576547624},{\"lat\":10.210265862228635,\"lng\":123.74117687344553},{\"lat\":10.21034241448185,\"lng\":123.74114736914638},{\"lat\":10.210422926314456,\"lng\":123.7411352992058},{\"lat\":10.210556232746617,\"lng\":123.74112725257875},{\"lat\":10.210676340474297,\"lng\":123.74114066362384},{\"lat\":10.210903357154367,\"lng\":123.7412077188492},{\"lat\":10.211329672923924,\"lng\":123.74138340353969},{\"lat\":10.211565928166241,\"lng\":123.74147996306422},{\"lat\":10.211757307982488,\"lng\":123.7415698170662},{\"lat\":10.211843098897221,\"lng\":123.74157652258873},{\"lat\":10.211973765015182,\"lng\":123.74161005020143},{\"lat\":10.212109710515287,\"lng\":123.74159663915636},{\"lat\":10.212145346704744,\"lng\":123.7416931986809},{\"lat\":10.212265453832343,\"lng\":123.74142631888392},{\"lat\":10.212227177939456,\"lng\":123.74156311154367},{\"lat\":10.21236180347335,\"lng\":123.74150142073634},{\"lat\":10.212481910519209,\"lng\":123.7414249777794},{\"lat\":10.212576940237645,\"lng\":123.74133512377742},{\"lat\":10.212844871096435,\"lng\":123.74108567833902},{\"lat\":10.213003253763958,\"lng\":123.74094352126124},{\"lat\":10.21323290849175,\"lng\":123.74072626233102},{\"lat\":10.213297581344937,\"lng\":123.74065384268764},{\"lat\":10.213368853453629,\"lng\":123.74057069420816},{\"lat\":10.213413728476825,\"lng\":123.74046608805659},{\"lat\":10.213446724813377,\"lng\":123.74037891626361},{\"lat\":10.213458603493697,\"lng\":123.74023273587228},{\"lat\":10.213408449062658,\"lng\":123.74004900455476},{\"lat\":10.213325298277997,\"lng\":123.73976871371272},{\"lat\":10.213222349657338,\"lng\":123.739462941885},{\"lat\":10.213141838533335,\"lng\":123.73922288417818},{\"lat\":10.213094323762066,\"lng\":123.73908206820491},{\"lat\":10.213052088403868,\"lng\":123.7388835847378},{\"lat\":10.213045489128643,\"lng\":123.73874545097352},{\"lat\":10.213090364197473,\"lng\":123.73855233192445},{\"lat\":10.213153717225042,\"lng\":123.73837664723398},{\"lat\":10.213243467325833,\"lng\":123.73826801776887},{\"lat\":10.21349291967879,\"lng\":123.73804405331613},{\"lat\":10.21363150423479,\"lng\":123.73793944716456},{\"lat\":10.213822882808039,\"lng\":123.7378039956093},{\"lat\":10.213957507666436,\"lng\":123.73771950602534},{\"lat\":10.214086853064929,\"lng\":123.73764038085939},{\"lat\":10.214246554963953,\"lng\":123.73759344220163},{\"lat\":10.21439041858945,\"lng\":123.73757734894755},{\"lat\":10.21465438837521,\"lng\":123.73760148882866},{\"lat\":10.215100496815008,\"lng\":123.7376779317856},{\"lat\":10.215294514195598,\"lng\":123.73772084712984},{\"lat\":10.215476652853312,\"lng\":123.7377355992794},{\"lat\":10.215657471562462,\"lng\":123.73770475387575},{\"lat\":10.216033626941806,\"lng\":123.73762965202333},{\"lat\":10.216485012809551,\"lng\":123.73752102255824},{\"lat\":10.216647353184248,\"lng\":123.73745128512385},{\"lat\":10.216937717875314,\"lng\":123.73731717467311},{\"lat\":10.21716341024745,\"lng\":123.73718038201334},{\"lat\":10.217291434504277,\"lng\":123.73705297708513},{\"lat\":10.217410219851098,\"lng\":123.73687192797661},{\"lat\":10.217486770384422,\"lng\":123.73661980032924},{\"lat\":10.217559361390379,\"lng\":123.73619735240938},{\"lat\":10.217605555658254,\"lng\":123.73586878180507},{\"lat\":10.217659668934953,\"lng\":123.73568370938303},{\"lat\":10.217832567391442,\"lng\":123.7354463338852},{\"lat\":10.218357861361705,\"lng\":123.73508960008623},{\"lat\":10.218739292871444,\"lng\":123.73481869697572},{\"lat\":10.219095647155537,\"lng\":123.73452097177508},{\"lat\":10.219754241614178,\"lng\":123.7339510023594},{\"lat\":10.21992185962525,\"lng\":123.73384907841684},{\"lat\":10.220209581674355,\"lng\":123.73374715447429},{\"lat\":10.2203851183929,\"lng\":123.73371496796611},{\"lat\":10.220732232069663,\"lng\":123.73370826244356},{\"lat\":10.221434377207192,\"lng\":123.73386383056642},{\"lat\":10.221690422228434,\"lng\":123.73383834958078},{\"lat\":10.222187993664184,\"lng\":123.73372033238412},{\"lat\":10.222527186477466,\"lng\":123.73368278145792},{\"lat\":10.222814906167516,\"lng\":123.73372569680217},{\"lat\":10.22361471274437,\"lng\":123.73387053608896},{\"lat\":10.224209947025171,\"lng\":123.73396843671802},{\"lat\":10.225079699766972,\"lng\":123.73410388827324},{\"lat\":10.225715845180948,\"lng\":123.7342058122158},{\"lat\":10.22615533908999,\"lng\":123.73423665761949},{\"lat\":10.226675340106796,\"lng\":123.73415887355804},{\"lat\":10.227085797160932,\"lng\":123.7340945005417},{\"lat\":10.22760051747584,\"lng\":123.73397648334505},{\"lat\":10.228149551558825,\"lng\":123.73385846614839},{\"lat\":10.228640513925225,\"lng\":123.73376995325091},{\"lat\":10.228937466600966,\"lng\":123.73365998268129},{\"lat\":10.229143353626469,\"lng\":123.73350307345393},{\"lat\":10.229291169870125,\"lng\":123.73332604765895},{\"lat\":10.229454823488204,\"lng\":123.73304173350334},{\"lat\":10.229541929411807,\"lng\":123.73289421200754},{\"lat\":10.229635634242342,\"lng\":123.73272791504861},{\"lat\":10.230105477764358,\"lng\":123.73188167810441},{\"lat\":10.230354916879719,\"lng\":123.73138412833217},{\"lat\":10.23047501712461,\"lng\":123.73090267181398},{\"lat\":10.230585878848819,\"lng\":123.73042523860933},{\"lat\":10.23090130663804,\"lng\":123.72992768883707},{\"lat\":10.231497847039448,\"lng\":123.7291230261326},{\"lat\":10.231846268348216,\"lng\":123.72858792543414},{\"lat\":10.232238241863456,\"lng\":123.72777655720712},{\"lat\":10.232518034110235,\"lng\":123.72719988226893},{\"lat\":10.232983914049383,\"lng\":123.72634425759317},{\"lat\":10.23322147296189,\"lng\":123.72555300593379},{\"lat\":10.233593648233985,\"lng\":123.724779188633},{\"lat\":10.23412419520776,\"lng\":123.72427225112916},{\"lat\":10.234613828568191,\"lng\":123.72358158230783},{\"lat\":10.234945089523732,\"lng\":123.72305855154991},{\"lat\":10.235286908234892,\"lng\":123.72249260544778},{\"lat\":10.235512587568243,\"lng\":123.72185289859773},{\"lat\":10.235585174454197,\"lng\":123.72136741876604},{\"lat\":10.235618168487786,\"lng\":123.72121721506122},{\"lat\":10.235711871524474,\"lng\":123.72111260890962},{\"lat\":10.23594810863497,\"lng\":123.7210375070572},{\"lat\":10.236531441975664,\"lng\":123.7210750579834},{\"lat\":10.23730085965127,\"lng\":123.72112065553667},{\"lat\":10.237552932617753,\"lng\":123.7212534248829},{\"lat\":10.238070275460858,\"lng\":123.72176572680476},{\"lat\":10.238464880786752,\"lng\":123.72236251831056},{\"lat\":10.23870111584728,\"lng\":123.72280776500703},{\"lat\":10.238963745680124,\"lng\":123.72294858098032},{\"lat\":10.23927256641143,\"lng\":123.72282117605212},{\"lat\":10.239667170241638,\"lng\":123.72256234288217},{\"lat\":10.239994466691568,\"lng\":123.72247114777566},{\"lat\":10.240294048226678,\"lng\":123.72252076864245},{\"lat\":10.240589670257066,\"lng\":123.72266292572023},{\"lat\":10.240928843407842,\"lng\":123.72284799814226},{\"lat\":10.241211267466728,\"lng\":123.7230169773102},{\"lat\":10.241349839927002,\"lng\":123.7231765687466},{\"lat\":10.241529324166498,\"lng\":123.7234649062157},{\"lat\":10.241772155622959,\"lng\":123.72373983263972},{\"lat\":10.242077014416429,\"lng\":123.72382432222368},{\"lat\":10.242397709714021,\"lng\":123.72386053204538},{\"lat\":10.242672214073256,\"lng\":123.72379347682002},{\"lat\":10.243506283552883,\"lng\":123.72336566448213},{\"lat\":10.243708201941946,\"lng\":123.7233066558838},{\"lat\":10.243902201841697,\"lng\":123.72332006692889},{\"lat\":10.244056609840325,\"lng\":123.72339919209482},{\"lat\":10.24419782050828,\"lng\":123.72351452708247},{\"lat\":10.24442085405198,\"lng\":123.7238082289696},{\"lat\":10.244503996693798,\"lng\":123.7241850793362},{\"lat\":10.244585819589858,\"lng\":123.72483283281328},{\"lat\":10.244794335906915,\"lng\":123.72511714696886},{\"lat\":10.244935546246255,\"lng\":123.72513324022296},{\"lat\":10.245046402917199,\"lng\":123.72506484389308},{\"lat\":10.24519289203004,\"lng\":123.72487977147104},{\"lat\":10.245306388052912,\"lng\":123.7246383726597},{\"lat\":10.245345979679238,\"lng\":123.72442647814754},{\"lat\":10.245332782471007,\"lng\":123.72408583760263},{\"lat\":10.245294510564053,\"lng\":123.72386455535889},{\"lat\":10.245389530462488,\"lng\":123.72357219457629},{\"lat\":10.24556769269527,\"lng\":123.72339919209482},{\"lat\":10.24586462952741,\"lng\":123.72323155403139},{\"lat\":10.246206436469793,\"lng\":123.72313499450685},{\"lat\":10.246499413555602,\"lng\":123.72315376996997},{\"lat\":10.246793710085386,\"lng\":123.72325435280803},{\"lat\":10.24742585282861,\"lng\":123.7235225737095},{\"lat\":10.247717509076292,\"lng\":123.72376129031184},{\"lat\":10.248039518377393,\"lng\":123.72419044375422},{\"lat\":10.248486899609183,\"lng\":123.72505143284799},{\"lat\":10.248802309596536,\"lng\":123.72563883662227},{\"lat\":10.249101882808478,\"lng\":123.72568979859355},{\"lat\":10.249318314380094,\"lng\":123.72556507587434},{\"lat\":10.24978284993711,\"lng\":123.72519358992577},{\"lat\":10.250306771324802,\"lng\":123.72448682785036},{\"lat\":10.250612941608749,\"lng\":123.72370898723604},{\"lat\":10.250806937282091,\"lng\":123.72301161289217},{\"lat\":10.251233199466796,\"lng\":123.72237995266917},{\"lat\":10.251381005415507,\"lng\":123.72168123722078},{\"lat\":10.25193263772226,\"lng\":123.72061774134639},{\"lat\":10.25243544049523,\"lng\":123.71972456574441},{\"lat\":10.252518581034332,\"lng\":123.71940940618516},{\"lat\":10.252504064433841,\"lng\":123.71836066246034},{\"lat\":10.252816831042706,\"lng\":123.71679291129112},{\"lat\":10.252981792209905,\"lng\":123.7160646915436},{\"lat\":10.253053055407563,\"lng\":123.71573343873025},{\"lat\":10.253041178209061,\"lng\":123.71542364358903},{\"lat\":10.252985751276857,\"lng\":123.7149837613106},{\"lat\":10.253018743499577,\"lng\":123.71479600667956},{\"lat\":10.253113761081817,\"lng\":123.71456801891328},{\"lat\":10.25318634393792,\"lng\":123.71437624096873},{\"lat\":10.25319162269044,\"lng\":123.71410802006723},{\"lat\":10.253059653850984,\"lng\":123.71383041143419},{\"lat\":10.252853782351627,\"lng\":123.71348708868028},{\"lat\":10.252711255850604,\"lng\":123.71323227882387},{\"lat\":10.252678263595863,\"lng\":123.71299222111703},{\"lat\":10.252705977090082,\"lng\":123.71277227997781},{\"lat\":10.252797035696869,\"lng\":123.71247723698617},{\"lat\":10.253090006688925,\"lng\":123.7116886675358},{\"lat\":10.25322065582775,\"lng\":123.71150225400926},{\"lat\":10.253439723959787,\"lng\":123.71132120490077},{\"lat\":10.253730054985647,\"lng\":123.71115624904634},{\"lat\":10.253933286545424,\"lng\":123.71107846498492},{\"lat\":10.254283002885195,\"lng\":123.71103420853618},{\"lat\":10.254677588027546,\"lng\":123.71099665760995},{\"lat\":10.25502070514302,\"lng\":123.71083036065103},{\"lat\":10.255388895788053,\"lng\":123.71066674590114},{\"lat\":10.25562907726845,\"lng\":123.71063724160196},{\"lat\":10.255890373397367,\"lng\":123.71061578392984},{\"lat\":10.256502702875023,\"lng\":123.7106774747372},{\"lat\":10.256665022775078,\"lng\":123.71071100234987},{\"lat\":10.257504334101482,\"lng\":123.7109658122063},{\"lat\":10.258140414473713,\"lng\":123.71115088462831},{\"lat\":10.258573265116642,\"lng\":123.71119782328608},{\"lat\":10.258971803904789,\"lng\":123.7110556662083},{\"lat\":10.259306998391153,\"lng\":123.71088400483133},{\"lat\":10.259466677529517,\"lng\":123.71084913611412},{\"lat\":10.259757003029291,\"lng\":123.71084108948709},{\"lat\":10.260076360771468,\"lng\":123.710914850235},{\"lat\":10.260803492279987,\"lng\":123.71117904782297},{\"lat\":10.261151881376351,\"lng\":123.71121659874917},{\"lat\":10.261733848670618,\"lng\":123.71126353740694},{\"lat\":10.261975344988745,\"lng\":123.7113091349602},{\"lat\":10.26216009614597,\"lng\":123.71138960123064},{\"lat\":10.26238723090964,\"lng\":123.71155729134979},{\"lat\":10.262775207710236,\"lng\":123.71163507541121},{\"lat\":10.263822851707122,\"lng\":123.71169403195383},{\"lat\":10.264271530282219,\"lng\":123.71176108717921},{\"lat\":10.264779592018222,\"lng\":123.71185496449472},{\"lat\":10.265250703080765,\"lng\":123.71196225285532},{\"lat\":10.265675626182512,\"lng\":123.71217817068101},{\"lat\":10.266051722240515,\"lng\":123.71260464191438},{\"lat\":10.266344680965377,\"lng\":123.71316120028496},{\"lat\":10.266537347366258,\"lng\":123.71341332793239},{\"lat\":10.26678807743827,\"lng\":123.71364265680316},{\"lat\":10.267162852543786,\"lng\":123.71371775865556},{\"lat\":10.267662991869656,\"lng\":123.71374860405923},{\"lat\":10.267901844408522,\"lng\":123.71377542614938},{\"lat\":10.267990259390977,\"lng\":123.7138330936432},{\"lat\":10.268413859187937,\"lng\":123.71422067284585},{\"lat\":10.268574853354508,\"lng\":123.71428236365321},{\"lat\":10.268816344450686,\"lng\":123.71428504586221},{\"lat\":10.269688614032617,\"lng\":123.71423810720445},{\"lat\":10.269955177025485,\"lng\":123.7141737341881},{\"lat\":10.270368217258717,\"lng\":123.71402889490129},{\"lat\":10.270967322580663,\"lng\":123.71375665068629},{\"lat\":10.27114415080673,\"lng\":123.71360912919046},{\"lat\":10.271547952205957,\"lng\":123.71311426162721},{\"lat\":10.27180263713698,\"lng\":123.71296137571336},{\"lat\":10.2721061474086,\"lng\":123.71288761496547},{\"lat\":10.27234895541614,\"lng\":123.71291711926462},{\"lat\":10.272975768703876,\"lng\":123.71318668127061},{\"lat\":10.273758293864065,\"lng\":123.71348172426225},{\"lat\":10.274243907154633,\"lng\":123.71359705924989},{\"lat\":10.27449727031442,\"lng\":123.71353268623353},{\"lat\":10.274737437288803,\"lng\":123.71333152055742},{\"lat\":10.275048862324487,\"lng\":123.71279507875444},{\"lat\":10.275236245037096,\"lng\":123.71256172657014},{\"lat\":10.275626845263936,\"lng\":123.71250808238985},{\"lat\":10.276123012423588,\"lng\":123.71264755725862},{\"lat\":10.276716828478888,\"lng\":123.71260464191438},{\"lat\":10.277933488111334,\"lng\":123.71244102716447},{\"lat\":10.278302971671046,\"lng\":123.71216475963593},{\"lat\":10.278397981659419,\"lng\":123.71163368225099},{\"lat\":10.27849826994949,\"lng\":123.71121525764467},{\"lat\":10.278772743001632,\"lng\":123.7109497189522},{\"lat\":10.279355997445704,\"lng\":123.7110221385956},{\"lat\":10.27993925081298,\"lng\":123.71135473251344},{\"lat\":10.280269144774707,\"lng\":123.71186167001727},{\"lat\":10.280646543044426,\"lng\":123.71247589588167},{\"lat\":10.281068806308227,\"lng\":123.71279239654542},{\"lat\":10.281583438897764,\"lng\":123.71300965547563},{\"lat\":10.282338231845648,\"lng\":123.71301770210268},{\"lat\":10.283053436086394,\"lng\":123.71290236711505},{\"lat\":10.283692104187022,\"lng\":123.71303111314775},{\"lat\":10.284338688344995,\"lng\":123.71329933404924},{\"lat\":10.284998466733246,\"lng\":123.71366679668428},{\"lat\":10.28557115325657,\"lng\":123.71402889490129},{\"lat\":10.286391914158513,\"lng\":123.7142837047577},{\"lat\":10.286692771318053,\"lng\":123.71438562870027},{\"lat\":10.286843199790315,\"lng\":123.71469944715501},{\"lat\":10.286998906379075,\"lng\":123.71499449014667},{\"lat\":10.287239063848446,\"lng\":123.7151125073433},{\"lat\":10.287595341076338,\"lng\":123.71514201164246},{\"lat\":10.287883001655832,\"lng\":123.7152010202408},{\"lat\":10.287988565105477,\"lng\":123.71539145708086},{\"lat\":10.287962174246386,\"lng\":123.71561676263812},{\"lat\":10.28793578338507,\"lng\":123.71601909399034},{\"lat\":10.28803606864631,\"lng\":123.71618270874023},{\"lat\":10.288344841487431,\"lng\":123.7163731455803}]', '2026-07-10 00:52:23', '2026-07-10 00:52:23');

-- --------------------------------------------------------

--
-- Table structure for table `sessions`
--

CREATE TABLE `sessions` (
  `id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` bigint UNSIGNED DEFAULT NULL,
  `ip_address` varchar(45) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `user_agent` text COLLATE utf8mb4_unicode_ci,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `last_activity` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `sessions`
--

INSERT INTO `sessions` (`id`, `user_id`, `ip_address`, `user_agent`, `payload`, `last_activity`) VALUES
('6L95vXzNGfqsM0GXRaB7U7mnwQ9GEz2zYSRvYzwy', 31, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', 'eyJfdG9rZW4iOiJJZ0NQM3p6M1ZGVVM5Q09ycXFROG9aU3l2elBmb21EOHNxcDIxVjNHIiwiX2ZsYXNoIjp7Im9sZCI6W10sIm5ldyI6W119LCJ1cmwiOnsiaW50ZW5kZWQiOiJodHRwOlwvXC9sb2NhbGhvc3Q6ODAwMCJ9LCJfcHJldmlvdXMiOnsidXJsIjoiaHR0cDpcL1wvbG9jYWxob3N0OjgwMDBcL3F1ZXVlIiwicm91dGUiOiJnZXQucXVldWVzIn0sInN0YXRlIjoiUjZzNm5GdXMwNDZHRFB2RHRlSE1EcDBXcEMzVWx6eGdZUjJSc1dqaCIsImxvZ2luX3dlYl81OWJhMzZhZGRjMmIyZjk0MDE1ODBmMDE0YzdmNThlYTRlMzA5ODlkIjozMX0=', 1784084396),
('BiTf4JBi2F1k1NJn8VCtCGhvUnsXnCDcuo61A1hc', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Code/1.128.0 Chrome/148.0.7778.271 Electron/42.5.0 Safari/537.36', 'eyJfdG9rZW4iOiJFdnRzbkcyc0JjU3M2NEcwRXVjQVJkY0ROVDVqeUtvU1dlTmc2VkhPIiwiX3ByZXZpb3VzIjp7InVybCI6Imh0dHA6XC9cLzEyNy4wLjAuMTo4MDAwIiwicm91dGUiOm51bGx9LCJfZmxhc2giOnsib2xkIjpbXSwibmV3IjpbXX19', 1784080122),
('j9Bg7SVUaGXsrYDroCEJdkuzP8GG7JyRK99bbLOU', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Code/1.128.0 Chrome/148.0.7778.271 Electron/42.5.0 Safari/537.36', 'eyJfdG9rZW4iOiI4NnphdDE3ZERIem1GWURzeGdqMHZQS1NvRG04S05GQ24wMENhTVd1IiwidXJsIjp7ImludGVuZGVkIjoiaHR0cDpcL1wvbG9jYWxob3N0OjgwMDAifSwiX3ByZXZpb3VzIjp7InVybCI6Imh0dHA6XC9cL2xvY2FsaG9zdDo4MDAwXC9hdXRoXC9yZWRpcmVjdCIsInJvdXRlIjoiZmFjZWJvb2sucmVkaXJlY3QifSwiX2ZsYXNoIjp7Im9sZCI6W10sIm5ldyI6W119LCJzdGF0ZSI6ImJDa0hnWXdNSzZYS0c2SDJJamNkY2huUldKZVNvcXUyVFRsZEExdjYifQ==', 1784080301);

-- --------------------------------------------------------

--
-- Table structure for table `terminals`
--

CREATE TABLE `terminals` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `bssid` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `location_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `terminals`
--

INSERT INTO `terminals` (`id`, `name`, `bssid`, `location_type`, `created_at`, `updated_at`) VALUES
(1, 'Naga', '00:1A:2B:3C:4D:52', 'Terminal', NULL, NULL),
(2, 'Uling', '00:1A:2B:3C:4D:51', 'Terminal', NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `facebook_id` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `facebook_token` text COLLATE utf8mb4_unicode_ci,
  `is_verified` tinyint(1) NOT NULL DEFAULT '0',
  `avatar` text COLLATE utf8mb4_unicode_ci,
  `role` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'driver',
  `going_to` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'Uling'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `email_verified_at`, `password`, `remember_token`, `created_at`, `updated_at`, `facebook_id`, `facebook_token`, `is_verified`, `avatar`, `role`, `going_to`) VALUES
(1, 'Dr. Elaina O\'Conner MD', 'eborer@example.com', '2026-07-10 00:52:20', '$2y$12$px0XxjVBJeQ8/KzRjAbmcuxcSztyttmbwGjkkf5STIsP5oznLe3hq', 'x1Jjr2lGeV', '2026-07-10 00:52:21', '2026-07-10 00:52:21', NULL, NULL, 1, NULL, 'driver', 'Uling'),
(2, 'Lorenz Gislason II', 'kiana80@example.com', '2026-07-10 00:52:21', '$2y$12$px0XxjVBJeQ8/KzRjAbmcuxcSztyttmbwGjkkf5STIsP5oznLe3hq', 'k3sPO4khEd', '2026-07-10 00:52:21', '2026-07-10 00:52:21', NULL, NULL, 1, NULL, 'driver', 'Uling'),
(6, 'Zelma Kozey', 'avery34@example.net', '2026-07-10 00:52:21', '$2y$12$px0XxjVBJeQ8/KzRjAbmcuxcSztyttmbwGjkkf5STIsP5oznLe3hq', 'nkMbqoGSRM', '2026-07-10 00:52:21', '2026-07-10 00:52:21', NULL, NULL, 1, NULL, 'driver', 'Uling'),
(7, 'Dion Dickens', 'parker98@example.com', '2026-07-10 00:52:21', '$2y$12$px0XxjVBJeQ8/KzRjAbmcuxcSztyttmbwGjkkf5STIsP5oznLe3hq', '8Kh21BCnPF', '2026-07-10 00:52:21', '2026-07-10 00:52:21', NULL, NULL, 1, NULL, 'driver', 'Uling'),
(8, 'Madisyn Fadel', 'lelah46@example.org', '2026-07-10 00:52:21', '$2y$12$px0XxjVBJeQ8/KzRjAbmcuxcSztyttmbwGjkkf5STIsP5oznLe3hq', '9uyq0BtCvt', '2026-07-10 00:52:21', '2026-07-10 00:52:21', NULL, NULL, 1, NULL, 'driver', 'Uling'),
(9, 'Donnell Koch', 'cronin.ebba@example.net', '2026-07-10 00:52:21', '$2y$12$px0XxjVBJeQ8/KzRjAbmcuxcSztyttmbwGjkkf5STIsP5oznLe3hq', 'yv7cIntDAO', '2026-07-10 00:52:21', '2026-07-10 00:52:21', NULL, NULL, 1, NULL, 'driver', 'Uling'),
(10, 'Lauretta Wehner', 'ikozey@example.net', '2026-07-10 00:52:21', '$2y$12$px0XxjVBJeQ8/KzRjAbmcuxcSztyttmbwGjkkf5STIsP5oznLe3hq', '3KUxHsBD3U', '2026-07-10 00:52:21', '2026-07-10 00:52:21', NULL, NULL, 1, NULL, 'driver', 'Uling'),
(11, 'Miss Madge O\'Reilly I', 'reinger.courtney@example.net', '2026-07-10 00:52:40', '$2y$12$81Lf4C/biWPxWgVPLpAzXOZJtiXJTYOFeTCmzWz1qJu6kFN12BhMi', 'xCrFVsdrLu', '2026-07-10 00:52:40', '2026-07-10 00:52:40', NULL, NULL, 1, NULL, 'driver', 'Uling'),
(12, 'Adriana Ryan MD', 'nkuhlman@example.com', '2026-07-10 00:52:40', '$2y$12$81Lf4C/biWPxWgVPLpAzXOZJtiXJTYOFeTCmzWz1qJu6kFN12BhMi', 'dZsrwih6Sp', '2026-07-10 00:52:40', '2026-07-10 00:52:40', NULL, NULL, 1, NULL, 'driver', 'Uling'),
(13, 'Haleigh Rogahn DDS', 'dion91@example.net', '2026-07-10 00:52:40', '$2y$12$81Lf4C/biWPxWgVPLpAzXOZJtiXJTYOFeTCmzWz1qJu6kFN12BhMi', 'f8RWM7QKDU', '2026-07-10 00:52:40', '2026-07-10 00:52:40', NULL, NULL, 1, NULL, 'driver', 'Uling'),
(14, 'Elwin Frami Jr.', 'tyrell.gleason@example.org', '2026-07-10 00:52:40', '$2y$12$81Lf4C/biWPxWgVPLpAzXOZJtiXJTYOFeTCmzWz1qJu6kFN12BhMi', 'krB4Ct8EeA', '2026-07-10 00:52:40', '2026-07-10 00:52:40', NULL, NULL, 1, NULL, 'driver', 'Uling'),
(15, 'Prof. Morton Keebler', 'stiedemann.emmanuelle@example.com', '2026-07-10 00:52:40', '$2y$12$81Lf4C/biWPxWgVPLpAzXOZJtiXJTYOFeTCmzWz1qJu6kFN12BhMi', '9v44TL68zl', '2026-07-10 00:52:40', '2026-07-10 00:52:40', NULL, NULL, 1, NULL, 'driver', 'Uling'),
(16, 'Prof. Kade Stracke PhD', 'bonnie23@example.net', '2026-07-10 00:52:40', '$2y$12$81Lf4C/biWPxWgVPLpAzXOZJtiXJTYOFeTCmzWz1qJu6kFN12BhMi', 'KOmcXa4IyB', '2026-07-10 00:52:40', '2026-07-10 00:52:40', NULL, NULL, 1, NULL, 'driver', 'Uling'),
(17, 'Lavonne Bayer', 'qernser@example.com', '2026-07-10 00:52:40', '$2y$12$81Lf4C/biWPxWgVPLpAzXOZJtiXJTYOFeTCmzWz1qJu6kFN12BhMi', '6aCXqeH5B9', '2026-07-10 00:52:40', '2026-07-10 00:52:40', NULL, NULL, 1, NULL, 'driver', 'Uling'),
(18, 'Jackson Abshire', 'cletus49@example.org', '2026-07-10 00:52:40', '$2y$12$81Lf4C/biWPxWgVPLpAzXOZJtiXJTYOFeTCmzWz1qJu6kFN12BhMi', 'VCkkS670se', '2026-07-10 00:52:40', '2026-07-10 00:52:40', NULL, NULL, 1, NULL, 'driver', 'Uling'),
(19, 'Gerry Monahan Jr.', 'mathew.ritchie@example.org', '2026-07-10 00:52:40', '$2y$12$81Lf4C/biWPxWgVPLpAzXOZJtiXJTYOFeTCmzWz1qJu6kFN12BhMi', 'ei4JTXgvUZ', '2026-07-10 00:52:40', '2026-07-10 00:52:40', NULL, NULL, 1, NULL, 'driver', 'Uling'),
(20, 'Prof. Kenna Schmeler I', 'ehartmann@example.net', '2026-07-10 00:52:40', '$2y$12$81Lf4C/biWPxWgVPLpAzXOZJtiXJTYOFeTCmzWz1qJu6kFN12BhMi', 'm7TyRGpJ1f', '2026-07-10 00:52:40', '2026-07-10 00:52:40', NULL, NULL, 1, NULL, 'driver', 'Uling'),
(21, 'Dr. Maxime Kreiger', 'qhills@example.com', '2026-07-10 00:52:40', '$2y$12$81Lf4C/biWPxWgVPLpAzXOZJtiXJTYOFeTCmzWz1qJu6kFN12BhMi', 'pVwQGjWHNZ', '2026-07-10 00:52:40', '2026-07-10 00:52:40', NULL, NULL, 1, NULL, 'driver', 'Uling'),
(22, 'Conor Wintheiser Jr.', 'rkshlerin@example.org', '2026-07-10 00:52:40', '$2y$12$81Lf4C/biWPxWgVPLpAzXOZJtiXJTYOFeTCmzWz1qJu6kFN12BhMi', 'Xibl67Wbyu', '2026-07-10 00:52:40', '2026-07-10 00:52:40', NULL, NULL, 1, NULL, 'driver', 'Uling'),
(23, 'Philip Nikolaus', 'fatima.mclaughlin@example.com', '2026-07-10 00:52:40', '$2y$12$81Lf4C/biWPxWgVPLpAzXOZJtiXJTYOFeTCmzWz1qJu6kFN12BhMi', 'G8kdubMN5f', '2026-07-10 00:52:40', '2026-07-10 00:52:40', NULL, NULL, 1, NULL, 'driver', 'Uling'),
(24, 'Carli Cassin', 'pablo89@example.com', '2026-07-10 00:52:40', '$2y$12$81Lf4C/biWPxWgVPLpAzXOZJtiXJTYOFeTCmzWz1qJu6kFN12BhMi', 'OBckPhyc3x', '2026-07-10 00:52:40', '2026-07-10 00:52:40', NULL, NULL, 1, NULL, 'driver', 'Uling'),
(25, 'Dessie Turner', 'jamel64@example.com', '2026-07-10 00:52:40', '$2y$12$81Lf4C/biWPxWgVPLpAzXOZJtiXJTYOFeTCmzWz1qJu6kFN12BhMi', 'wLF6Gcvefy', '2026-07-10 00:52:40', '2026-07-10 00:52:40', NULL, NULL, 1, NULL, 'driver', 'Uling'),
(26, 'Dr. Jason Robel III', 'kfranecki@example.org', '2026-07-10 00:52:40', '$2y$12$81Lf4C/biWPxWgVPLpAzXOZJtiXJTYOFeTCmzWz1qJu6kFN12BhMi', 'QspeKtFa7j', '2026-07-10 00:52:40', '2026-07-10 00:52:40', NULL, NULL, 1, NULL, 'driver', 'Uling'),
(27, 'Dorothy Sporer', 'benjamin97@example.com', '2026-07-10 00:52:40', '$2y$12$81Lf4C/biWPxWgVPLpAzXOZJtiXJTYOFeTCmzWz1qJu6kFN12BhMi', 'a8I5lflLwC', '2026-07-10 00:52:40', '2026-07-10 00:52:40', NULL, NULL, 1, NULL, 'driver', 'Uling'),
(28, 'Dr. Cyrus Lemke', 'flavie65@example.org', '2026-07-10 00:52:40', '$2y$12$81Lf4C/biWPxWgVPLpAzXOZJtiXJTYOFeTCmzWz1qJu6kFN12BhMi', 'nxBTEZ14Wj', '2026-07-10 00:52:40', '2026-07-10 00:52:40', NULL, NULL, 1, NULL, 'driver', 'Uling'),
(29, 'Kacey Orn', 'myrl24@example.com', '2026-07-10 00:52:40', '$2y$12$81Lf4C/biWPxWgVPLpAzXOZJtiXJTYOFeTCmzWz1qJu6kFN12BhMi', '37cZjRlRtQ', '2026-07-10 00:52:40', '2026-07-10 00:52:40', NULL, NULL, 1, NULL, 'driver', 'Uling'),
(30, 'Georgiana Mann Jr.', 'sconsidine@example.net', '2026-07-10 00:52:40', '$2y$12$81Lf4C/biWPxWgVPLpAzXOZJtiXJTYOFeTCmzWz1qJu6kFN12BhMi', 'cH10qyX7uo', '2026-07-10 00:52:40', '2026-07-10 00:52:40', NULL, NULL, 1, NULL, 'driver', 'Uling'),
(31, 'Axle Lapiz', NULL, NULL, '$2y$12$8vg6iIRyeKrBCM2NGuumAeVrKXHmbFfw0MUox21x4vtAQxLAMdUTC', NULL, '2026-07-10 00:52:54', '2026-07-14 18:27:00', '1673898707161724', 'EAAXPeyHwDJIBR8k70cgt1FVeWZAeDeUgkw0plfec9SOKUTjSZAAP08ZARZAZAP2yFF89Qt5rFUIUoOAuXfaERZC0eYzCfaU99PQZCzfVSZCGn6KEV6qqXqafi4QuJKMNRNm0jZA9h3T08rNcFirjnzddmlVzMJDs5hUTL4bqQIjXescq0t4tVop91oQs0XRCVSnZAU0aOa2s2zKzHzzBNcNggQ90oRdWJxp9oOoRIZA5dLxZAOp53ZCNgy6aLfH6lwbIOoBLdRTxBuTBvpqyK86VRaE5OKK2hGbEmsnEjVAEM7HlYToAzE5RHAqBl69fAS4HUewK3DwSqsuNJQlAb4cpZBZANxwgevD4o1z54NHMmorE8FbkvkeNYEZD', 1, 'https://platform-lookaside.fbsbx.com/platform/profilepic/?asid=1673898707161724&width=1920&ext=1786265573&hash=Afvkx86PpzoYjvLlKhvZspHx', 'admin', 'Uling'),
(32, 'Joy Boy', 'asldsadf@gmail.com', NULL, '$2y$12$xQLeao96EQw5IXsOLEkUMu8zUpLtLI6QM3yMutY5UHM.MG9r4lT5.', NULL, '2026-07-11 04:26:27', '2026-07-11 04:55:05', NULL, NULL, 1, NULL, 'driver', 'Uling'),
(33, 'Tonyo Stark', 'johndoe@gmail.com', NULL, '$2y$12$PoofXbFXVN5ackW3AjaYSOstENBz9Gg/rh7Vl2mXmyO8Cw7J2l/Sm', NULL, '2026-07-11 04:26:46', '2026-07-11 05:12:58', NULL, NULL, 1, NULL, 'driver', 'Uling'),
(34, 'asdfasdfasdf', 'asdfsadfwasdf@gmail.com', NULL, '$2y$12$xzCt6dnabYNJ5QIFHtJuSuaDdYVMOekTN4gq2/r6QGr3xOtjSWgV.', NULL, '2026-07-11 05:21:43', '2026-07-11 05:23:16', NULL, NULL, 1, NULL, 'driver', 'Uling'),
(35, 'Tonyo Stark234r', 'asdfwasdf@gmail.com', NULL, '$2y$12$TuO.lgI58MWgoYRvgCHDCODZiHyiQEWxNyYWPHtHt91H8QUNrBEDe', NULL, '2026-07-11 05:22:00', '2026-07-11 05:29:19', NULL, NULL, 1, NULL, 'driver', 'Uling'),
(37, 'joyboy222', 'joyboy222@gmailcom', NULL, '$2y$12$V/PY5ZG7lTNzaNDArTDCw.w5nZgZcmuVVEZHOEBIG7MIOaXexuLca', NULL, '2026-07-12 21:45:44', '2026-07-12 21:45:44', NULL, NULL, 0, NULL, 'driver', 'Uling');

-- --------------------------------------------------------

--
-- Table structure for table `violations`
--

CREATE TABLE `violations` (
  `id` bigint UNSIGNED NOT NULL,
  `driver_profile_id` bigint UNSIGNED NOT NULL,
  `type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `location` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `severity` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'medium',
  `properties` json DEFAULT NULL,
  `resolved_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `violations`
--

INSERT INTO `violations` (`id`, `driver_profile_id`, `type`, `name`, `location`, `severity`, `properties`, `resolved_at`, `created_at`, `updated_at`) VALUES
(1, 8, 'unauthorized_terminal_entry', 'Unauthorized Terminal Entry', 'Uling', 'high', '{\"waypoint_index\": 357, \"actual_destination\": \"Uling\", \"expected_destination\": \"Naga\"}', NULL, '2026-07-10 01:28:47', '2026-07-10 01:28:47'),
(2, 1, 'unauthorized_terminal_entry', 'Unauthorized Terminal Entry', 'Uling', 'high', '{\"waypoint_index\": 357, \"actual_destination\": \"Uling\", \"expected_destination\": \"Naga\"}', NULL, '2026-07-10 01:42:28', '2026-07-10 01:42:28'),
(3, 1, 'unauthorized_terminal_entry', 'Unauthorized Terminal Entry', 'Uling', 'high', '{\"waypoint_index\": 357, \"actual_destination\": \"Uling\", \"expected_destination\": \"Naga\"}', NULL, '2026-07-10 01:50:46', '2026-07-10 01:50:46'),
(4, 19, 'unauthorized_terminal_entry', 'Unauthorized Terminal Entry', 'Uling', 'high', '{\"waypoint_index\": 357, \"actual_destination\": \"Uling\", \"expected_destination\": \"Naga\"}', NULL, '2026-07-10 18:56:21', '2026-07-10 18:56:21');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `cache`
--
ALTER TABLE `cache`
  ADD PRIMARY KEY (`key`),
  ADD KEY `cache_expiration_index` (`expiration`);

--
-- Indexes for table `cache_locks`
--
ALTER TABLE `cache_locks`
  ADD PRIMARY KEY (`key`),
  ADD KEY `cache_locks_expiration_index` (`expiration`);

--
-- Indexes for table `driver_profiles`
--
ALTER TABLE `driver_profiles`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `driver_profiles_user_id_unique` (`user_id`),
  ADD UNIQUE KEY `driver_profiles_license_number_unique` (`license_number`);

--
-- Indexes for table `driver_queues`
--
ALTER TABLE `driver_queues`
  ADD PRIMARY KEY (`id`),
  ADD KEY `driver_queues_driver_profile_id_foreign` (`driver_profile_id`),
  ADD KEY `driver_queues_terminal_id_foreign` (`terminal_id`);

--
-- Indexes for table `driver_statuses`
--
ALTER TABLE `driver_statuses`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `driver_statuses_user_id_unique` (`user_id`);

--
-- Indexes for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`),
  ADD KEY `failed_jobs_connection_queue_failed_at_index` (`connection`,`queue`,`failed_at`);

--
-- Indexes for table `jobs`
--
ALTER TABLE `jobs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `jobs_queue_index` (`queue`);

--
-- Indexes for table `job_batches`
--
ALTER TABLE `job_batches`
  ADD PRIMARY KEY (`id`);

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
-- Indexes for table `routes`
--
ALTER TABLE `routes`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `sessions`
--
ALTER TABLE `sessions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sessions_user_id_index` (`user_id`),
  ADD KEY `sessions_last_activity_index` (`last_activity`);

--
-- Indexes for table `terminals`
--
ALTER TABLE `terminals`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `terminals_bssid_unique` (`bssid`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_email_unique` (`email`),
  ADD UNIQUE KEY `users_facebook_id_unique` (`facebook_id`);

--
-- Indexes for table `violations`
--
ALTER TABLE `violations`
  ADD PRIMARY KEY (`id`),
  ADD KEY `violations_driver_profile_id_foreign` (`driver_profile_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `driver_profiles`
--
ALTER TABLE `driver_profiles`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=40;

--
-- AUTO_INCREMENT for table `driver_queues`
--
ALTER TABLE `driver_queues`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=781;

--
-- AUTO_INCREMENT for table `driver_statuses`
--
ALTER TABLE `driver_statuses`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=36;

--
-- AUTO_INCREMENT for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `jobs`
--
ALTER TABLE `jobs`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=27;

--
-- AUTO_INCREMENT for table `routes`
--
ALTER TABLE `routes`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `terminals`
--
ALTER TABLE `terminals`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=38;

--
-- AUTO_INCREMENT for table `violations`
--
ALTER TABLE `violations`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `driver_profiles`
--
ALTER TABLE `driver_profiles`
  ADD CONSTRAINT `driver_profiles_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `driver_queues`
--
ALTER TABLE `driver_queues`
  ADD CONSTRAINT `driver_queues_driver_profile_id_foreign` FOREIGN KEY (`driver_profile_id`) REFERENCES `driver_profiles` (`id`),
  ADD CONSTRAINT `driver_queues_terminal_id_foreign` FOREIGN KEY (`terminal_id`) REFERENCES `terminals` (`id`);

--
-- Constraints for table `driver_statuses`
--
ALTER TABLE `driver_statuses`
  ADD CONSTRAINT `driver_statuses_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `violations`
--
ALTER TABLE `violations`
  ADD CONSTRAINT `violations_driver_profile_id_foreign` FOREIGN KEY (`driver_profile_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
