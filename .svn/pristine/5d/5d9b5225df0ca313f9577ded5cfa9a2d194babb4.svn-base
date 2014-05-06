-- phpMyAdmin SQL Dump
-- version 3.5.8.1deb1
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Sep 04, 2013 at 04:13 AM
-- Server version: 5.5.32-0ubuntu0.13.04.1
-- PHP Version: 5.4.9-4ubuntu2.2

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `btms_mtt`
--

-- --------------------------------------------------------

--
-- Table structure for table `active_places`
--

CREATE TABLE IF NOT EXISTS `active_places` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `line_id` int(11) NOT NULL,
  `inactive_places` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `from_date` date NOT NULL,
  `to_date` date NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `line_id` (`line_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=3 ;

--
-- Dumping data for table `active_places`
--

INSERT INTO `active_places` (`id`, `line_id`, `inactive_places`, `from_date`, `to_date`) VALUES
(1, 1, '1,2,3,4,5,6,7,8,9,10,11,12,13,14,15,16,17,18,19,20,55,33,34,36,32,31,30,29,25,26,21,22,23,24,27,28', '2013-04-24', '2013-04-24'),
(2, 2, '', '2013-08-29', '2013-08-22');

-- --------------------------------------------------------

--
-- Table structure for table `bus_info`
--

CREATE TABLE IF NOT EXISTS `bus_info` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `departure_date` date NOT NULL DEFAULT '0000-00-00',
  `direction` int(11) NOT NULL,
  `line_id` int(11) NOT NULL,
  `registration_number` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `first_driver` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `second_driver` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `stewart` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `cities`
--

CREATE TABLE IF NOT EXISTS `cities` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `city` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=cp1251 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `currencies`
--

CREATE TABLE IF NOT EXISTS `currencies` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `currency` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `index` double NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `currency` (`currency`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=4 ;

--
-- Dumping data for table `currencies`
--

INSERT INTO `currencies` (`id`, `currency`, `index`) VALUES
(1, 'EUR', 1.95),
(3, 'USD', 1.3);

-- --------------------------------------------------------

--
-- Table structure for table `discounts`
--

CREATE TABLE IF NOT EXISTS `discounts` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name_bg` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `name_en` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `description_bg` text COLLATE utf8_unicode_ci NOT NULL,
  `description_en` text COLLATE utf8_unicode_ci NOT NULL,
  `discount` double NOT NULL,
  `discount_type` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=5 ;

--
-- Dumping data for table `discounts`
--

INSERT INTO `discounts` (`id`, `name_bg`, `name_en`, `description_bg`, `description_en`, `discount`, `discount_type`) VALUES
(2, 'Деца до 4г.  ', 'CHD to 4 years', 'Деца до 4г.  ', 'CHD to 4years', 30, 0),
(3, 'Деца от 4г. до 12г.', 'CHD  3 - 12  years', 'Деца от 4г. до 12г.', 'CHD  3 - 12  years', 20, 0),
(4, 'Ученици студенти и пенсионери', 'Students and retirees	', 'Ученици студенти и пенсионери', 'Students and retirees', 10, 0);

-- --------------------------------------------------------

--
-- Table structure for table `group_city`
--

CREATE TABLE IF NOT EXISTS `group_city` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `lang` varchar(2) NOT NULL DEFAULT '',
  `ID_city` int(11) NOT NULL DEFAULT '0',
  `city` varchar(200) NOT NULL DEFAULT '',
  PRIMARY KEY (`ID`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=33 ;

--
-- Dumping data for table `group_city`
--

INSERT INTO `group_city` (`ID`, `lang`, `ID_city`, `city`) VALUES
(1, 'bg', 1, 'Пловдив'),
(2, 'en', 1, 'Plovdiv'),
(3, 'bg', 2, 'София'),
(4, 'en', 2, 'Sofia'),
(7, 'bg', 3, 'Благоевград'),
(8, 'en', 3, 'Blagoevgrad'),
(9, 'bg', 4, 'Сандански'),
(10, 'en', 4, 'Sandanski'),
(11, 'bg', 5, 'Серес'),
(12, 'en', 5, 'Seres'),
(13, 'bg', 6, 'Солун'),
(14, 'en', 6, 'Thessaloniki'),
(15, 'bg', 7, 'Катерини'),
(16, 'en', 7, 'Katerini'),
(17, 'bg', 8, 'Лариса'),
(18, 'en', 8, 'Larisa'),
(19, 'bg', 9, 'Кардица'),
(20, 'en', 9, 'Karditsa'),
(21, 'bg', 10, 'Трикала'),
(22, 'en', 10, 'Trikala'),
(23, 'bg', 11, 'Каламбака'),
(24, 'en', 11, 'Kalampaka'),
(25, 'bg', 12, 'Янина'),
(26, 'en', 12, 'Ioánina'),
(27, 'bg', 13, 'Арта'),
(28, 'en', 13, 'Arta'),
(29, 'bg', 14, 'Агринио'),
(30, 'en', 14, 'Agrinio'),
(31, 'bg', 15, 'Патра'),
(32, 'en', 15, 'Patra');

-- --------------------------------------------------------

--
-- Table structure for table `invoices`
--

CREATE TABLE IF NOT EXISTS `invoices` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `created_on` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=14 ;

--
-- Dumping data for table `invoices`
--

INSERT INTO `invoices` (`id`, `created_on`) VALUES
(1, '2013-05-16 09:00:59'),
(2, '2013-08-15 16:56:04'),
(3, '2013-08-18 04:42:51'),
(4, '2013-08-18 06:12:56'),
(5, '2013-08-18 07:52:44'),
(6, '2013-08-18 09:25:22'),
(7, '2013-08-19 03:02:15'),
(8, '2013-08-19 03:53:50'),
(9, '2013-08-20 01:17:06'),
(10, '2013-08-20 02:42:57'),
(11, '2013-08-21 02:20:02'),
(12, '2013-08-22 22:10:28'),
(13, '2013-09-04 00:21:25');

-- --------------------------------------------------------

--
-- Table structure for table `invoice_elements`
--

CREATE TABLE IF NOT EXISTS `invoice_elements` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `invoice_id` int(11) NOT NULL,
  `reservation_id` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=33 ;

--
-- Dumping data for table `invoice_elements`
--

INSERT INTO `invoice_elements` (`id`, `invoice_id`, `reservation_id`) VALUES
(2, 2, 2),
(8, 3, 8),
(7, 3, 7),
(6, 3, 6),
(9, 4, 9),
(10, 4, 10),
(11, 4, 11),
(12, 5, 12),
(13, 5, 13),
(14, 5, 14),
(15, 6, 15),
(16, 6, 16),
(17, 6, 17),
(18, 6, 18),
(19, 7, 19),
(20, 7, 20),
(21, 7, 21),
(22, 8, 22),
(23, 9, 23),
(24, 9, 24),
(25, 9, 25),
(26, 10, 26),
(27, 10, 27),
(28, 11, 28),
(29, 12, 29),
(30, 13, 30),
(31, 13, 31),
(32, 13, 32);

-- --------------------------------------------------------

--
-- Table structure for table `lines`
--

CREATE TABLE IF NOT EXISTS `lines` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `line_name` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `from_city_id` int(11) NOT NULL,
  `to_city_id` int(11) NOT NULL,
  `from_date` date DEFAULT NULL,
  `to_date` date DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=cp1251 AUTO_INCREMENT=7 ;

--
-- Dumping data for table `lines`
--

INSERT INTO `lines` (`id`, `line_name`, `from_city_id`, `to_city_id`, `from_date`, `to_date`) VALUES
(1, 'Пловдив - Солун', 1, 6, NULL, NULL),
(2, 'Пловдив - Патра', 1, 15, NULL, NULL),
(3, 'Пловдив - Пловдив', 1, 1, NULL, NULL),
(4, 'Пловдив - Пловдив', 1, 1, NULL, NULL),
(5, 'Пловдив - Пловдив', 1, 1, NULL, NULL),
(6, 'Пловдив - Пловдив', 1, 1, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `line_points`
--

CREATE TABLE IF NOT EXISTS `line_points` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `line_id` int(11) NOT NULL,
  `city_id` int(11) NOT NULL,
  `stopover` int(11) NOT NULL COMMENT 'in minutes',
  `stopover_back` int(11) NOT NULL,
  `arrival_time` time NOT NULL,
  `bus_station_bg` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `bus_station_en` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `arrival_time_back` time NOT NULL,
  `bus_station_back_bg` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `bus_station_back_en` varchar(255) NOT NULL,
  `order` int(11) NOT NULL COMMENT 'the order in which the points will appear in',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=cp1251 AUTO_INCREMENT=24 ;

--
-- Dumping data for table `line_points`
--

INSERT INTO `line_points` (`id`, `line_id`, `city_id`, `stopover`, `stopover_back`, `arrival_time`, `bus_station_bg`, `bus_station_en`, `arrival_time_back`, `bus_station_back_bg`, `bus_station_back_en`, `order`) VALUES
(1, 1, 1, 0, 0, '08:00:00', 'Автогара Юг', 'South Bus station', '16:00:00', 'Автогара Юг', 'South Bus station', 0),
(2, 1, 2, 30, 30, '10:30:00', 'Централна автогара', 'Central Bus station', '14:00:00', 'Централна автогара', 'Central Bus station', 1),
(3, 1, 3, 15, 20, '12:15:00', 'Автогара', 'Bus station', '12:00:00', 'Автогара', 'Bus station', 2),
(4, 1, 4, 45, 45, '13:00:00', 'бензиностанция Шел', 'Gas Stations SHELL', '10:15:00', 'бензиностанция Шел', 'Gas Stations SHELL', 3),
(5, 1, 5, 15, 15, '15:30:00', 'Автогара', 'Bus station', '08:45:00', 'Автогара', 'Bus station', 4),
(6, 1, 6, 0, 0, '16:30:00', 'Автогара ', 'Bus station', '07:30:00', 'Автогара', 'Bus station', 5),
(7, 2, 1, 0, 0, '18:30:00', 'Автогара Юг', 'South Bus station', '08:15:00', 'Автогара Юг', 'South Bus station', 0),
(8, 2, 2, 0, 0, '20:30:00', 'Централна автогара', 'Central Bus station', '05:55:00', 'Централна автогара', 'Central Bus station', 1),
(9, 2, 6, 0, 0, '02:25:00', 'Анагдостаки 8', 'Anagdostaki 8', '23:45:00', 'Анагдостаки 8', 'Anagdostaki 8', 2),
(10, 2, 7, 0, 0, '03:40:00', 'бензиностанция Шел', 'Gas Stations SHELL', '22:35:00', 'бензиностанция Шел', 'Gas Stations SHELL', 3),
(11, 2, 8, 0, 0, '04:50:00', 'ЦЖПР ОСЕ', 'Railway station OSE', '21:10:00', 'ЦЖПР ОСЕ', 'Railway station OSE', 4),
(12, 2, 9, 0, 0, '06:05:00', 'ЦЖПР ОСЕ', 'Railway station OSE', '19:55:00', 'ЦЖПР ОСЕ', 'Railway station OSE', 5),
(13, 2, 10, 0, 0, '06:35:00', 'ЦЖПР ОСЕ', 'Railway station OSE', '19:25:00', 'ЦЖПР ОСЕ', 'Railway station OSE', 6),
(14, 2, 11, 0, 0, '07:10:00', 'ЦЖПР ОСЕ', 'Railway station OSE', '18:50:00', 'ЦЖПР ОСЕ', 'Railway station OSE', 7),
(15, 2, 12, 0, 0, '10:05:00', 'Маг. JUMBO', 'Railway station OSE', '15:55:00', 'Маг. JUMBO', 'Railway station OSE', 8),
(16, 2, 13, 0, 0, '11:15:00', 'Старата болница', 'Old hospital', '14:45:00', 'Старата болница', 'Old hospital', 9),
(17, 2, 14, 0, 0, '12:40:00', 'Срещу JUMBO', 'Opposite JUMBO', '13:20:00', 'Срещу JUMBO', 'Opposite JUMBO', 10),
(18, 2, 15, 0, 0, '14:10:00', 'ЦЖПР ОСЕ', '', '12:00:00', 'ЦЖПР ОСЕ', '', 11),
(19, 3, 1, 0, 0, '00:00:00', '', '', '00:00:00', '', '', 0),
(20, 4, 1, 0, 0, '00:00:00', '', '', '00:00:00', '', '', 0),
(21, 5, 1, 0, 0, '00:00:00', '', '', '00:00:00', '', '', 0),
(22, 6, 1, 0, 0, '00:00:00', '', '', '00:00:00', '', '', 0),
(23, 6, 1, 0, 0, '00:00:00', '', '', '00:00:00', '', '', 1);

-- --------------------------------------------------------

--
-- Table structure for table `promotions`
--

CREATE TABLE IF NOT EXISTS `promotions` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `subline_id` int(11) NOT NULL,
  `promo_percent` double NOT NULL,
  `expires` date NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=3 ;

--
-- Dumping data for table `promotions`
--

INSERT INTO `promotions` (`id`, `subline_id`, `promo_percent`, `expires`) VALUES
(1, 5, 30, '2013-03-19'),
(2, 1, 1, '2013-08-13');

-- --------------------------------------------------------

--
-- Table structure for table `reservations`
--

CREATE TABLE IF NOT EXISTS `reservations` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `subline_id` int(11) NOT NULL,
  `back_subline_id` int(11) DEFAULT NULL,
  `place` int(11) NOT NULL,
  `place_back` int(11) DEFAULT NULL,
  `ticket_number` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `passenger_name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `passenger_passpor` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `contact_phone` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `contact_email` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `price` double NOT NULL,
  `discount_id` int(11) NOT NULL,
  `birthday` date NOT NULL,
  `date` date NOT NULL,
  `date_back` date DEFAULT NULL,
  `ticket_type` tinyint(1) NOT NULL,
  `payed` tinyint(1) NOT NULL,
  `user_id` int(11) NOT NULL,
  `last_update` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `created` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=33 ;

--
-- Dumping data for table `reservations`
--

INSERT INTO `reservations` (`id`, `subline_id`, `back_subline_id`, `place`, `place_back`, `ticket_number`, `passenger_name`, `passenger_passpor`, `contact_phone`, `contact_email`, `price`, `discount_id`, `birthday`, `date`, `date_back`, `ticket_type`, `payed`, `user_id`, `last_update`, `created`) VALUES
(2, 5, 0, 2, 0, '', ' ', '', '', '', 58, 0, '0000-00-00', '2013-08-31', '0000-00-00', 0, 0, 1, '2013-08-15 16:56:04', '2013-08-15 16:56:04'),
(8, 4, 0, 25, 0, '', ' ', '', '', '', 56, 0, '0000-00-00', '2013-08-31', '0000-00-00', 0, 0, 1, '2013-08-18 04:43:30', '2013-08-18 04:43:30'),
(7, 4, 0, 21, 0, '', ' ', '', '', '', 56, 0, '0000-00-00', '2013-08-31', '0000-00-00', 0, 0, 1, '2013-08-18 04:43:29', '2013-08-18 04:43:29'),
(6, 4, 0, 16, 0, '', ' ', '', '', '', 56, 0, '0000-00-00', '2013-08-31', '0000-00-00', 0, 0, 1, '2013-08-18 04:43:28', '2013-08-18 04:43:28'),
(9, 14, 0, 12, 0, '1', ' ', '', '', '', 46, 0, '0000-00-00', '2013-08-20', '0000-00-00', 0, 2, 1, '2013-08-20 03:23:13', '2013-08-18 06:12:56'),
(10, 14, 0, 3, 0, '', ' ', '', '', '', 46, 0, '0000-00-00', '2013-08-20', '0000-00-00', 0, 0, 1, '2013-08-18 06:12:58', '2013-08-18 06:12:58'),
(11, 14, 0, 5, 0, '123', ' ', '', '', '', 46, 0, '0000-00-00', '2013-08-20', '0000-00-00', 0, 1, 1, '2013-08-20 03:22:58', '2013-08-18 06:12:59'),
(12, 4, 0, 7, 0, '', ' ', '', '', '', 56, 0, '0000-00-00', '2013-08-30', '0000-00-00', 0, 0, 1, '2013-08-18 07:52:44', '2013-08-18 07:52:44'),
(13, 4, 0, 13, 0, '', ' ', '', '', '', 56, 0, '0000-00-00', '2013-08-30', '0000-00-00', 0, 0, 1, '2013-08-18 07:52:46', '2013-08-18 07:52:46'),
(14, 4, 0, 19, 0, '', ' ', '', '', '', 56, 0, '0000-00-00', '2013-08-30', '0000-00-00', 0, 0, 1, '2013-08-18 07:52:47', '2013-08-18 07:52:47'),
(15, 5, 0, 8, 0, '', ' ', '', '', '', 58, 0, '0000-00-00', '2013-08-24', '0000-00-00', 0, 0, 1, '2013-08-18 09:25:22', '2013-08-18 09:25:22'),
(16, 5, 0, 21, 0, '', ' ', '', '', '', 58, 0, '0000-00-00', '2013-08-24', '0000-00-00', 0, 0, 1, '2013-08-18 09:25:23', '2013-08-18 09:25:23'),
(17, 5, 0, 17, 0, '', ' ', '', '', '', 58, 0, '0000-00-00', '2013-08-24', '0000-00-00', 0, 0, 1, '2013-08-18 09:25:25', '2013-08-18 09:25:25'),
(18, 5, 0, 25, 0, '', ' ', '', '', '', 58, 0, '0000-00-00', '2013-08-24', '0000-00-00', 0, 0, 1, '2013-08-18 09:25:27', '2013-08-18 09:25:27'),
(19, 5, 0, 17, 0, '', ' ', '', '', '', 58, 0, '0000-00-00', '2013-08-31', '0000-00-00', 0, 0, 1, '2013-08-19 03:02:15', '2013-08-19 03:02:15'),
(20, 5, 0, 13, 0, '', ' ', '', '', '', 58, 0, '0000-00-00', '2013-08-31', '0000-00-00', 0, 0, 1, '2013-08-19 03:02:16', '2013-08-19 03:02:16'),
(21, 5, 0, 9, 0, '', ' ', '', '', '', 58, 0, '0000-00-00', '2013-08-31', '0000-00-00', 0, 0, 1, '2013-08-19 03:02:18', '2013-08-19 03:02:18'),
(22, 5, 0, 7, 0, '', ' ', '', '', '', 58, 0, '0000-00-00', '2013-08-31', '0000-00-00', 0, 0, 1, '2013-08-19 03:53:50', '2013-08-19 03:53:50'),
(23, 5, 0, 11, 0, '', ' ', '', '', '', 58, 0, '0000-00-00', '2013-08-22', '0000-00-00', 0, 0, 1, '2013-08-20 01:17:06', '2013-08-20 01:17:06'),
(24, 5, 0, 13, 0, '', ' ', '', '', '', 58, 0, '0000-00-00', '2013-08-22', '0000-00-00', 0, 0, 1, '2013-08-20 01:17:08', '2013-08-20 01:17:08'),
(25, 5, 0, 21, 0, '', ' ', '', '', '', 58, 0, '0000-00-00', '2013-08-22', '0000-00-00', 0, 0, 1, '2013-08-20 01:17:10', '2013-08-20 01:17:10'),
(26, 4, 0, 4, 0, '', ' ', '', '', '', 56, 0, '0000-00-00', '2013-08-22', '0000-00-00', 0, 0, 1, '2013-08-20 02:42:57', '2013-08-20 02:42:57'),
(27, 4, 0, 7, 0, '', ' ', '', '', '', 56, 0, '0000-00-00', '2013-08-22', '0000-00-00', 0, 0, 1, '2013-08-20 02:43:00', '2013-08-20 02:43:00'),
(28, 4, 0, 8, 0, '', ' ', '', '', '', 56, 0, '0000-00-00', '2013-08-22', '0000-00-00', 0, 0, 1, '2013-08-21 02:20:02', '2013-08-21 02:20:02'),
(29, 4, 0, 3, 0, '', ' ', '', '', '', 56, 0, '0000-00-00', '2013-08-23', '0000-00-00', 0, 0, 1, '2013-08-22 22:10:28', '2013-08-22 22:10:28'),
(30, 5, 0, 8, 0, '', ' ', '', '', '', 58, 0, '0000-00-00', '2013-09-28', '0000-00-00', 0, 0, 1, '2013-09-04 00:21:25', '2013-09-04 00:21:25'),
(31, 5, 0, 3, 0, '', ' ', '', '', '', 58, 0, '0000-00-00', '2013-09-28', '0000-00-00', 0, 0, 1, '2013-09-04 00:21:26', '2013-09-04 00:21:26'),
(32, 5, 0, 7, 0, '', ' ', '', '', '', 58, 0, '0000-00-00', '2013-09-28', '0000-00-00', 0, 0, 1, '2013-09-04 00:21:29', '2013-09-04 00:21:29');

-- --------------------------------------------------------

--
-- Table structure for table `reservation_buffer`
--

CREATE TABLE IF NOT EXISTS `reservation_buffer` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `subline_id` int(11) NOT NULL,
  `back_subline_id` int(11) DEFAULT NULL,
  `place` int(11) NOT NULL,
  `place_back` int(11) DEFAULT NULL,
  `ticket_number` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `passenger_name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `passenger_passpor` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `contact_phone` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `contact_email` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `price` double NOT NULL,
  `discount_id` int(11) NOT NULL,
  `birthday` date NOT NULL,
  `date` date NOT NULL,
  `date_back` date DEFAULT NULL,
  `ticket_type` tinyint(1) NOT NULL,
  `user_id` int(11) NOT NULL DEFAULT '0',
  `buffer_id` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=672 ;

--
-- Dumping data for table `reservation_buffer`
--

INSERT INTO `reservation_buffer` (`id`, `subline_id`, `back_subline_id`, `place`, `place_back`, `ticket_number`, `passenger_name`, `passenger_passpor`, `contact_phone`, `contact_email`, `price`, `discount_id`, `birthday`, `date`, `date_back`, `ticket_type`, `user_id`, `buffer_id`, `created_at`) VALUES
(670, 5, 0, 22, 0, '', 'fasdfsa fasf', 'asdfsafasfd', '131232141131', 'fasdffasdfasf@mail.bg', 58, 0, '2013-08-05', '2013-08-16', '0000-00-00', 0, 0, '787ec58f319b8e9200c5a74a945538db', '2013-08-15 13:58:21'),
(671, 5, 0, 33, 0, '', 'mghnfnhgn nhh', 'gfdndndfg', '12313123', 'fasdf@mail.bg', 58, 0, '2013-08-21', '2013-08-16', '0000-00-00', 0, 0, '1ceb1355a7795c172e189f886a50f0ba', '2013-08-15 14:40:55');

-- --------------------------------------------------------

--
-- Table structure for table `returned_tickets`
--

CREATE TABLE IF NOT EXISTS `returned_tickets` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `reservation_id` int(11) NOT NULL,
  `return_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `reservation_id` (`reservation_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=2 ;

--
-- Dumping data for table `returned_tickets`
--

INSERT INTO `returned_tickets` (`id`, `reservation_id`, `return_date`) VALUES
(1, 9, '2013-08-20 03:23:13');

-- --------------------------------------------------------

--
-- Table structure for table `saved_places`
--

CREATE TABLE IF NOT EXISTS `saved_places` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `subline_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `places` text COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=3 ;

--
-- Dumping data for table `saved_places`
--

INSERT INTO `saved_places` (`id`, `subline_id`, `user_id`, `places`) VALUES
(2, 1, 1, '{1},{2},{3},{4}');

-- --------------------------------------------------------

--
-- Table structure for table `sublines`
--

CREATE TABLE IF NOT EXISTS `sublines` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `line_id` int(11) NOT NULL,
  `from_point_id` int(11) NOT NULL,
  `to_point_id` int(11) NOT NULL,
  `travel_time` time NOT NULL,
  `price_oneway` double NOT NULL,
  `price_twoway` double NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=cp1251 AUTO_INCREMENT=163 ;

--
-- Dumping data for table `sublines`
--

INSERT INTO `sublines` (`id`, `line_id`, `from_point_id`, `to_point_id`, `travel_time`, `price_oneway`, `price_twoway`) VALUES
(1, 1, 1, 2, '02:00:00', 0, 0),
(2, 1, 1, 3, '04:15:00', 0, 0),
(3, 1, 1, 4, '05:00:00', 0, 0),
(4, 1, 1, 5, '07:15:00', 56, 80),
(5, 1, 1, 6, '08:30:00', 58, 106),
(6, 1, 2, 3, '02:15:00', 0, 0),
(7, 1, 2, 4, '03:00:00', 0, 0),
(8, 1, 2, 5, '05:15:00', 42, 56),
(9, 1, 2, 6, '06:30:00', 50, 80),
(10, 1, 3, 4, '00:45:00', 0, 0),
(11, 1, 3, 5, '03:00:00', 28, 36),
(12, 1, 3, 6, '04:15:00', 46, 64),
(13, 1, 4, 5, '02:15:00', 28, 36),
(14, 1, 4, 6, '03:30:00', 46, 64),
(15, 1, 5, 6, '00:00:00', 0, 0),
(16, 1, 6, 5, '01:15:00', 0, 0),
(17, 1, 6, 4, '03:30:00', 46, 64),
(18, 1, 6, 3, '04:15:00', 46, 64),
(19, 1, 6, 2, '06:30:00', 50, 80),
(20, 1, 6, 1, '08:30:00', 58, 106),
(21, 1, 5, 4, '02:15:00', 28, 36),
(22, 1, 5, 3, '03:00:00', 28, 36),
(23, 1, 5, 2, '05:15:00', 42, 56),
(24, 1, 5, 1, '07:15:00', 56, 80),
(25, 1, 4, 3, '00:00:00', 0, 0),
(26, 1, 4, 2, '00:00:00', 0, 0),
(27, 1, 4, 1, '00:00:00', 0, 0),
(28, 1, 3, 2, '00:00:00', 0, 0),
(29, 1, 3, 1, '00:00:00', 0, 0),
(30, 1, 2, 1, '00:00:00', 0, 0),
(31, 2, 7, 8, '02:00:00', 0, 0),
(32, 2, 7, 9, '07:55:00', 58, 106),
(33, 2, 7, 10, '09:10:00', 73, 130),
(34, 2, 7, 11, '10:20:00', 86, 154),
(35, 2, 7, 12, '11:35:00', 98, 167),
(36, 2, 7, 13, '12:05:00', 98, 167),
(37, 2, 7, 14, '12:40:00', 98, 167),
(38, 2, 7, 15, '15:35:00', 119, 205),
(39, 2, 7, 16, '16:45:00', 119, 205),
(40, 2, 7, 17, '18:10:00', 140, 250),
(41, 2, 7, 18, '19:40:00', 140, 250),
(42, 2, 8, 9, '05:55:00', 50, 80),
(43, 2, 8, 10, '07:10:00', 60, 109),
(44, 2, 8, 11, '08:20:00', 73, 130),
(45, 2, 8, 12, '09:35:00', 85, 145),
(46, 2, 8, 13, '10:05:00', 85, 145),
(47, 2, 8, 14, '10:40:00', 85, 145),
(48, 2, 8, 15, '13:35:00', 102, 176),
(49, 2, 8, 16, '14:45:00', 102, 176),
(50, 2, 8, 17, '16:10:00', 125, 223),
(51, 2, 8, 18, '17:40:00', 125, 223),
(52, 2, 9, 10, '00:00:00', 0, 0),
(53, 2, 9, 11, '00:00:00', 0, 0),
(54, 2, 9, 12, '00:00:00', 0, 0),
(55, 2, 9, 13, '00:00:00', 0, 0),
(56, 2, 9, 14, '00:00:00', 0, 0),
(57, 2, 9, 15, '00:00:00', 0, 0),
(58, 2, 9, 16, '00:00:00', 0, 0),
(59, 2, 9, 17, '00:00:00', 0, 0),
(60, 2, 9, 18, '00:00:00', 0, 0),
(61, 2, 10, 11, '00:00:00', 0, 0),
(62, 2, 10, 12, '00:00:00', 0, 0),
(63, 2, 10, 13, '00:00:00', 0, 0),
(64, 2, 10, 14, '00:00:00', 0, 0),
(65, 2, 10, 15, '00:00:00', 0, 0),
(66, 2, 10, 16, '00:00:00', 0, 0),
(67, 2, 10, 17, '00:00:00', 0, 0),
(68, 2, 10, 18, '00:00:00', 0, 0),
(69, 2, 11, 12, '00:00:00', 0, 0),
(70, 2, 11, 13, '00:00:00', 0, 0),
(71, 2, 11, 14, '00:00:00', 0, 0),
(72, 2, 11, 15, '00:00:00', 0, 0),
(73, 2, 11, 16, '00:00:00', 0, 0),
(74, 2, 11, 17, '00:00:00', 0, 0),
(75, 2, 11, 18, '00:00:00', 0, 0),
(76, 2, 12, 13, '00:00:00', 0, 0),
(77, 2, 12, 14, '00:00:00', 0, 0),
(78, 2, 12, 15, '00:00:00', 0, 0),
(79, 2, 12, 16, '00:00:00', 0, 0),
(80, 2, 12, 17, '00:00:00', 0, 0),
(81, 2, 12, 18, '00:00:00', 0, 0),
(82, 2, 13, 14, '00:00:00', 0, 0),
(83, 2, 13, 15, '00:00:00', 0, 0),
(84, 2, 13, 16, '00:00:00', 0, 0),
(85, 2, 13, 17, '00:00:00', 0, 0),
(86, 2, 13, 18, '00:00:00', 0, 0),
(87, 2, 14, 15, '00:00:00', 0, 0),
(88, 2, 14, 16, '00:00:00', 0, 0),
(89, 2, 14, 17, '00:00:00', 0, 0),
(90, 2, 14, 18, '00:00:00', 0, 0),
(91, 2, 15, 16, '00:00:00', 0, 0),
(92, 2, 15, 17, '00:00:00', 0, 0),
(93, 2, 15, 18, '00:00:00', 0, 0),
(94, 2, 16, 17, '00:00:00', 0, 0),
(95, 2, 16, 18, '00:00:00', 0, 0),
(96, 2, 17, 18, '00:00:00', 0, 0),
(97, 2, 18, 17, '01:20:00', 0, 0),
(98, 2, 18, 16, '02:45:00', 0, 0),
(99, 2, 18, 15, '03:55:00', 0, 0),
(100, 2, 18, 14, '06:50:00', 0, 0),
(101, 2, 18, 13, '07:25:00', 0, 0),
(102, 2, 18, 12, '07:55:00', 0, 0),
(103, 2, 18, 11, '09:10:00', 0, 0),
(104, 2, 18, 10, '10:35:00', 0, 0),
(105, 2, 18, 9, '11:45:00', 0, 0),
(106, 2, 18, 8, '17:55:00', 125, 223),
(107, 2, 18, 7, '20:15:00', 140, 250),
(108, 2, 17, 16, '00:00:00', 0, 0),
(109, 2, 17, 15, '00:00:00', 0, 0),
(110, 2, 17, 14, '00:00:00', 0, 0),
(111, 2, 17, 13, '00:00:00', 0, 0),
(112, 2, 17, 12, '00:00:00', 0, 0),
(113, 2, 17, 11, '00:00:00', 0, 0),
(114, 2, 17, 10, '00:00:00', 0, 0),
(115, 2, 17, 9, '00:00:00', 0, 0),
(116, 2, 17, 8, '00:00:00', 0, 0),
(117, 2, 17, 7, '00:00:00', 0, 0),
(118, 2, 16, 15, '00:00:00', 0, 0),
(119, 2, 16, 14, '00:00:00', 0, 0),
(120, 2, 16, 13, '00:00:00', 0, 0),
(121, 2, 16, 12, '00:00:00', 0, 0),
(122, 2, 16, 11, '00:00:00', 0, 0),
(123, 2, 16, 10, '00:00:00', 0, 0),
(124, 2, 16, 9, '00:00:00', 0, 0),
(125, 2, 16, 8, '00:00:00', 0, 0),
(126, 2, 16, 7, '00:00:00', 0, 0),
(127, 2, 15, 14, '00:00:00', 0, 0),
(128, 2, 15, 13, '00:00:00', 0, 0),
(129, 2, 15, 12, '00:00:00', 0, 0),
(130, 2, 15, 11, '00:00:00', 0, 0),
(131, 2, 15, 10, '00:00:00', 0, 0),
(132, 2, 15, 9, '00:00:00', 0, 0),
(133, 2, 15, 8, '00:00:00', 0, 0),
(134, 2, 15, 7, '00:00:00', 0, 0),
(135, 2, 14, 13, '00:00:00', 0, 0),
(136, 2, 14, 12, '00:00:00', 0, 0),
(137, 2, 14, 11, '00:00:00', 0, 0),
(138, 2, 14, 10, '00:00:00', 0, 0),
(139, 2, 14, 9, '00:00:00', 0, 0),
(140, 2, 14, 8, '00:00:00', 0, 0),
(141, 2, 14, 7, '00:00:00', 0, 0),
(142, 2, 13, 12, '00:00:00', 0, 0),
(143, 2, 13, 11, '00:00:00', 0, 0),
(144, 2, 13, 10, '00:00:00', 0, 0),
(145, 2, 13, 9, '00:00:00', 0, 0),
(146, 2, 13, 8, '00:00:00', 0, 0),
(147, 2, 13, 7, '00:00:00', 0, 0),
(148, 2, 12, 11, '00:00:00', 0, 0),
(149, 2, 12, 10, '00:00:00', 0, 0),
(150, 2, 12, 9, '00:00:00', 0, 0),
(151, 2, 12, 8, '00:00:00', 0, 0),
(152, 2, 12, 7, '00:00:00', 0, 0),
(153, 2, 11, 10, '00:00:00', 0, 0),
(154, 2, 11, 9, '00:00:00', 0, 0),
(155, 2, 11, 8, '00:00:00', 0, 0),
(156, 2, 11, 7, '00:00:00', 0, 0),
(157, 2, 10, 9, '00:00:00', 0, 0),
(158, 2, 10, 8, '00:00:00', 0, 0),
(159, 2, 10, 7, '00:00:00', 0, 0),
(160, 2, 9, 8, '00:00:00', 0, 0),
(161, 2, 9, 7, '00:00:00', 0, 0),
(162, 2, 8, 7, '00:00:00', 0, 0);

-- --------------------------------------------------------

--
-- Table structure for table `travel_days`
--

CREATE TABLE IF NOT EXISTS `travel_days` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `line_id` int(11) NOT NULL,
  `direction` tinyint(1) NOT NULL,
  `mon` tinyint(1) NOT NULL,
  `tue` tinyint(1) NOT NULL,
  `wed` tinyint(1) NOT NULL,
  `thu` tinyint(1) NOT NULL,
  `fri` tinyint(1) NOT NULL,
  `sat` tinyint(1) NOT NULL,
  `sun` tinyint(1) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=8 ;

--
-- Dumping data for table `travel_days`
--

INSERT INTO `travel_days` (`id`, `line_id`, `direction`, `mon`, `tue`, `wed`, `thu`, `fri`, `sat`, `sun`) VALUES
(1, 1, 0, 1, 1, 1, 1, 1, 1, 1),
(2, 1, 1, 1, 1, 1, 1, 1, 1, 1),
(3, 2, 0, 0, 0, 0, 0, 1, 0, 0),
(4, 2, 1, 0, 0, 0, 0, 0, 0, 1),
(5, 3, 0, 0, 0, 0, 0, 0, 0, 0),
(6, 5, 0, 0, 0, 0, 0, 0, 0, 0),
(7, 6, 0, 0, 0, 0, 0, 0, 0, 0);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE IF NOT EXISTS `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `passwd` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `access` int(11) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`),
  UNIQUE KEY `user` (`user`)
) ENGINE=MyISAM  DEFAULT CHARSET=cp1251 AUTO_INCREMENT=7 ;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `user`, `passwd`, `access`) VALUES
(1, 'admin', 'admin', 2),
(2, 'kasa1', '1234', 1),
(4, 'kasa2', '4321', 1),
(5, 'kasa3', '12345', 1),
(6, 'office', 'grigorov_mtt', 1);

-- --------------------------------------------------------

--
-- Table structure for table `user_access`
--

CREATE TABLE IF NOT EXISTS `user_access` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `administration_access` tinyint(1) NOT NULL,
  `sell_access` tinyint(1) NOT NULL,
  `agent_report_access` tinyint(1) NOT NULL,
  `travel_list_report_access` tinyint(1) NOT NULL,
  `date2date_report_access` tinyint(1) NOT NULL,
  `destination_report_access` tinyint(1) NOT NULL,
  `all_sales_access` tinyint(1) NOT NULL,
  `sale_edit_access` tinyint(1) NOT NULL,
  `sale_delete_access` tinyint(1) NOT NULL,
  `sale_return_access` tinyint(1) NOT NULL,
  `show_epay_sales_access` tinyint(1) NOT NULL,
  `save_places_access` tinyint(1) NOT NULL,
  `free_places_access` tinyint(1) NOT NULL,
  `has_payment_restrictions` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `user_id` (`user_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=6 ;

--
-- Dumping data for table `user_access`
--

INSERT INTO `user_access` (`id`, `user_id`, `administration_access`, `sell_access`, `agent_report_access`, `travel_list_report_access`, `date2date_report_access`, `destination_report_access`, `all_sales_access`, `sale_edit_access`, `sale_delete_access`, `sale_return_access`, `show_epay_sales_access`, `save_places_access`, `free_places_access`, `has_payment_restrictions`) VALUES
(1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1),
(2, 2, 0, 1, 1, 0, 0, 0, 0, 1, 0, 1, 0, 0, 0, 0),
(3, 4, 0, 1, 1, 0, 0, 0, 0, 1, 0, 1, 0, 0, 0, 0),
(4, 5, 0, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 0),
(5, 6, 0, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 0);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
