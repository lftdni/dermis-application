-- phpMyAdmin SQL Dump
-- version 4.3.11
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: Jul 22, 2015 at 09:04 PM
-- Server version: 5.6.24
-- PHP Version: 5.6.8

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `dermis_app`
--

-- --------------------------------------------------------

--
-- Table structure for table `customers`
--

CREATE TABLE IF NOT EXISTS `customers` (
  `id` int(10) unsigned NOT NULL,
  `first_name` varchar(70) NOT NULL,
  `last_name` varchar(45) NOT NULL,
  `birthdate` date NOT NULL,
  `address` varchar(70) NOT NULL,
  `city` varchar(50) NOT NULL,
  `postal_code` varchar(10) NOT NULL,
  `telephone` varchar(10) NOT NULL,
  `mobile_phone` varchar(10) NOT NULL,
  `email` varchar(255) NOT NULL,
  `allergies` varchar(255) NOT NULL,
  `chronic_diseases` varchar(255) NOT NULL,
  `therapy` varchar(255) NOT NULL,
  `family_preferences` varchar(255) NOT NULL,
  `menstruation_type` varchar(255) NOT NULL,
  `nutrition_type` varchar(255) NOT NULL,
  `smoker` tinyint(1) NOT NULL,
  `remark` text NOT NULL,
  `user_id` int(10) unsigned NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8;

--
-- Dumping data for table `customers`
--

INSERT INTO `customers` (`id`, `first_name`, `last_name`, `birthdate`, `address`, `city`, `postal_code`, `telephone`, `mobile_phone`, `email`, `allergies`, `chronic_diseases`, `therapy`, `family_preferences`, `menstruation_type`, `nutrition_type`, `smoker`, `remark`, `user_id`) VALUES
(1, 'Joki', 'Cevko', '1991-07-22', 'Neki Kurac 23', 'Zagreb', '10000', '051234234', '0965555020', 'nekik@gmail.com', 'Pelud,Prašina', 'Distorzija', 'Stalna', 'Incest', 'strong', 'carbo', 1, 'Nema detalja.', 1),
(2, 'Neko', 'Tamo', '1998-04-03', 'Trg Bana Jelacica 2', 'Zagreb', '10000', '051252363', '0952536666', 'neko.tamo@gmail.com', 'Kara', 'Distorzija', 'Hardcore', 'Nema', 'poorly', 'prote', 1, 'Dosta toga.', 1),
(3, 'Velikoime', 'Prezime1', '1994-01-01', 'Neka Adresa', 'Rijeka', '51000', '051661114', '0911234569', 'mail@mail.com', 'alergije', 'kronicne b', 'terapija', 'neke sklonosti', 'poorly', 'mixed', 0, 'opaska o klijentu', 1),
(4, 'Anita', 'Banita', '1999-08-23', 'Zagrebacka Cesta 40', 'Zagreb', '10000', '051525525', '0928533256', 'bica@net.hr', 'Bolestina', 'Sickness', 'Puno terapija', 'Nastranost', 'strong', 'carbo', 1, 'Puno opaske', 1),
(5, 'Fata', 'Fatovic', '1997-05-02', 'Bosanska Cesta 12', 'Sarajevo', '21000', '051252325', '0957778525', 'fata.iz.bosne@gmail.ba', 'Nema Cega nema', 'Vjera', 'Odvikavanje od vjere', 'Obrezivanje', 'strong', 'veget', 1, 'Stalno puši cigarete!', 1);

-- --------------------------------------------------------

--
-- Table structure for table `services`
--

CREATE TABLE IF NOT EXISTS `services` (
  `id` int(10) unsigned NOT NULL,
  `title` varchar(45) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `duration` float(5,2) unsigned NOT NULL,
  `description` tinytext CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `creation_datetime` datetime NOT NULL,
  `user_id` int(10) unsigned NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8;

--
-- Dumping data for table `services`
--

INSERT INTO `services` (`id`, `title`, `duration`, `description`, `creation_datetime`, `user_id`) VALUES
(1, 'Sranjee', 30.00, 'Ma ciscenje sranjaae', '0000-00-00 00:00:00', 1),
(2, 'Blabla', 33.00, 'asdasdas', '0000-00-00 00:00:00', 1),
(3, 'asdasd', 33.00, 'asdasd', '2015-07-20 08:44:48', 1),
(4, 'Geliranje', 10.00, 'geliranje noktiju', '2015-07-21 19:43:44', 1),
(5, 'Poliranje', 255.00, 'Poliranje noktiju', '2015-07-21 19:43:58', 1),
(6, 'Masaža', 7.21, 'masiranje', '2015-07-21 19:44:29', 1),
(8, 'Usluga 2', 2.22, 'Usluga 1Usluga 1Usluga 1Usluga 1Usluga 1Usluga 1Usluga 1Usluga 1Usluga 1Usluga 1Usluga 1Usluga 1Usluga 1Usluga 1Usluga 1\r\nUsluga 1Usluga 1Usluga 1Usluga 1Usluga 1Usluga 1\r\nUsluga 1Usluga 1Usluga 1Usluga 1Usluga 1Usluga 1Usluga 1Usluga 1\r\nUsluga 1Usluga 1U', '2015-07-21 19:45:35', 1),
(9, 'usluga 3', 3.33, 'Usluga 1Usluga 1Usluga 1Usluga 1Usluga 1Usluga 1Usluga 1Usluga 1Usluga 1Usluga 1Usluga 1Usluga 1Usluga 1Usluga 1Usluga 1\r\nUsluga 1Usluga 1Usluga 1Usluga 1Usluga 1Usluga 1\r\nUsluga 1Usluga 1Usluga 1Usluga 1Usluga 1Usluga 1Usluga 1Usluga 1\r\nUsluga 1Usluga 1U', '2015-07-21 19:46:07', 1),
(10, 'usluga 4', 4.44, 'Usluga 1Usluga 1Usluga 1Usluga 1Usluga 1Usluga 1Usluga 1Usluga 1Usluga 1Usluga 1Usluga 1Usluga 1Usluga 1Usluga 1Usluga 1\r\nUsluga 1Usluga 1Usluga 1Usluga 1Usluga 1Usluga 1\r\nUsluga 1Usluga 1Usluga 1Usluga 1Usluga 1Usluga 1Usluga 1Usluga 1\r\nUsluga 1Usluga 1U', '2015-07-21 19:46:24', 1),
(11, 'nova usluga', 5.00, 'OPIS USLUGE', '2015-07-21 21:05:28', 1),
(12, 'Sranje', 30.00, 'Ma ciscenje sranjaasasd', '2015-07-22 03:33:45', 1);

-- --------------------------------------------------------

--
-- Table structure for table `services_list`
--

CREATE TABLE IF NOT EXISTS `services_list` (
  `id` int(10) unsigned NOT NULL,
  `date` datetime NOT NULL,
  `remark` text CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `customers_id` int(10) unsigned NOT NULL,
  `services_id` int(10) unsigned NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE IF NOT EXISTS `users` (
  `id` int(10) unsigned NOT NULL,
  `first_name` varchar(70) NOT NULL,
  `last_name` varchar(35) NOT NULL,
  `username` varchar(16) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` char(40) NOT NULL,
  `permissions` tinyint(1) unsigned NOT NULL,
  `creation_datetime` datetime NOT NULL,
  `lastupdate_datetime` datetime NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `first_name`, `last_name`, `username`, `email`, `password`, `permissions`, `creation_datetime`, `lastupdate_datetime`) VALUES
(1, 'Ivan', 'Coban', 'jcoban', 'webdexcore@gmail.com', 'a94a8fe5ccb19ba61c4c0873d391e987982fbbd3', 0, '2015-07-04 09:06:05', '2015-07-04 09:06:05');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `customers`
--
ALTER TABLE `customers`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `services`
--
ALTER TABLE `services`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `services_list`
--
ALTER TABLE `services_list`
  ADD PRIMARY KEY (`id`,`customers_id`,`services_id`), ADD KEY `fk_services_list_customers_idx` (`customers_id`), ADD KEY `fk_services_list_services1_idx` (`services_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `customers`
--
ALTER TABLE `customers`
  MODIFY `id` int(10) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=6;
--
-- AUTO_INCREMENT for table `services`
--
ALTER TABLE `services`
  MODIFY `id` int(10) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=13;
--
-- AUTO_INCREMENT for table `services_list`
--
ALTER TABLE `services_list`
  MODIFY `id` int(10) unsigned NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(10) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=2;
--
-- Constraints for dumped tables
--

--
-- Constraints for table `services_list`
--
ALTER TABLE `services_list`
ADD CONSTRAINT `fk_services_list_customers` FOREIGN KEY (`customers_id`) REFERENCES `customers` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
ADD CONSTRAINT `fk_services_list_services1` FOREIGN KEY (`services_id`) REFERENCES `services` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
