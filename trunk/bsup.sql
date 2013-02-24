-- phpMyAdmin SQL Dump
-- version 3.2.4
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Feb 24, 2013 at 08:13 PM
-- Server version: 5.1.44
-- PHP Version: 5.3.1

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `bsup`
--

-- --------------------------------------------------------

--
-- Table structure for table `tcategory`
--

CREATE TABLE IF NOT EXISTS `tcategory` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=3 ;

--
-- Dumping data for table `tcategory`
--

INSERT INTO `tcategory` (`id`, `name`) VALUES
(1, 'Application'),
(2, 'Materiel');

-- --------------------------------------------------------

--
-- Table structure for table `tcriticality`
--

CREATE TABLE IF NOT EXISTS `tcriticality` (
  `id` int(2) NOT NULL AUTO_INCREMENT,
  `number` int(2) NOT NULL,
  `name` varchar(50) NOT NULL,
  `color` varchar(10) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=5 ;

--
-- Dumping data for table `tcriticality`
--

INSERT INTO `tcriticality` (`id`, `number`, `name`, `color`) VALUES
(1, 0, 'Critique', 'red'),
(2, 1, 'Grave', 'orange'),
(3, 2, 'Moyenne', 'yellow'),
(4, 3, 'Basse', 'green');

-- --------------------------------------------------------

--
-- Table structure for table `tevents`
--

CREATE TABLE IF NOT EXISTS `tevents` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `technician` int(10) NOT NULL,
  `incident` int(10) NOT NULL,
  `date` datetime NOT NULL,
  `type` int(1) NOT NULL,
  `disable` tinyint(1) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

--
-- Dumping data for table `tevents`
--


-- --------------------------------------------------------

--
-- Table structure for table `tincidents`
--

CREATE TABLE IF NOT EXISTS `tincidents` (
  `id` int(5) NOT NULL AUTO_INCREMENT,
  `technician` int(5) NOT NULL,
  `title` varchar(100) NOT NULL,
  `description` varchar(10000) NOT NULL,
  `resolution` varchar(10000) NOT NULL,
  `user` varchar(20) NOT NULL,
  `date_create` date NOT NULL,
  `date_hope` date NOT NULL,
  `date_res` date NOT NULL,
  `date_modif` datetime NOT NULL,
  `state` int(1) NOT NULL,
  `priority` int(2) NOT NULL,
  `criticality` int(2) NOT NULL,
  `img1` varchar(100) NOT NULL,
  `img2` varchar(100) NOT NULL,
  `img3` varchar(100) NOT NULL,
  `time` int(10) NOT NULL,
  `time_hope` int(10) NOT NULL,
  `creator` int(3) NOT NULL,
  `category` int(3) NOT NULL,
  `subcat` int(3) NOT NULL,
  `techread` int(1) NOT NULL DEFAULT '1',
  `template` int(1) NOT NULL,
  `disable` int(1) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=2 ;

--
-- Dumping data for table `tincidents`
--

INSERT INTO `tincidents` (`id`, `technician`, `title`, `description`, `resolution`, `user`, `date_create`, `date_hope`, `date_res`, `date_modif`, `state`, `priority`, `criticality`, `img1`, `img2`, `img3`, `time`, `time_hope`, `creator`, `category`, `subcat`, `techread`, `template`, `disable`) VALUES
(1, 5, 'test', 'desc test', '24/02/2013 15:05: Attribution de l''incident à tech.\r\n', '3', '2013-02-24', '0000-00-00', '0000-00-00', '0000-00-00 00:00:00', 1, 0, 4, '', '', '', 1, 1, 3, 1, 1, 0, 0, 0);

-- --------------------------------------------------------

--
-- Table structure for table `tmails`
--

CREATE TABLE IF NOT EXISTS `tmails` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `incident` int(10) NOT NULL,
  `open` int(1) NOT NULL,
  `close` int(1) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

--
-- Dumping data for table `tmails`
--


-- --------------------------------------------------------

--
-- Table structure for table `tparameters`
--

CREATE TABLE IF NOT EXISTS `tparameters` (
  `company` varchar(50) DEFAULT NULL,
  `version` varchar(4) NOT NULL,
  `maxline` int(4) NOT NULL,
  `mail_smtp` varchar(100) DEFAULT NULL,
  `mail_auth` varchar(10) DEFAULT NULL,
  `mail_secure` varchar(10) DEFAULT NULL,
  `mail_username` varchar(150) DEFAULT NULL,
  `mail_password` varchar(150) DEFAULT NULL,
  `mail_txt` varchar(300) NOT NULL,
  `mail_cc` varchar(50) NOT NULL,
  `mail_from` varchar(60) NOT NULL,
  `mail_auto` int(1) NOT NULL,
  `mail_newticket` int(1) NOT NULL,
  `mail_newticket_address` varchar(200) NOT NULL,
  `mail_color_title` varchar(6) NOT NULL,
  `mail_color_bg` varchar(6) NOT NULL,
  `mail_color_text` varchar(6) NOT NULL,
  `logo` varchar(50) NOT NULL,
  `user_advanced` int(1) NOT NULL,
  `lign_yellow` varchar(50) NOT NULL,
  `lign_orange` varchar(50) NOT NULL,
  `time_display_msg` int(5) NOT NULL,
  `ldap` int(1) NOT NULL,
  `ldap_auth` int(1) NOT NULL,
  `ldap_server` varchar(100) NOT NULL,
  `ldap_domain` varchar(200) NOT NULL,
  `ldap_url` varchar(200) NOT NULL,
  `ldap_user` varchar(100) NOT NULL,
  `ldap_password` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tparameters`
--

INSERT INTO `tparameters` (`company`, `version`, `maxline`, `mail_smtp`, `mail_auth`, `mail_secure`, `mail_username`, `mail_password`, `mail_txt`, `mail_cc`, `mail_from`, `mail_auto`, `mail_newticket`, `mail_newticket_address`, `mail_color_title`, `mail_color_bg`, `mail_color_text`, `logo`, `user_advanced`, `lign_yellow`, `lign_orange`, `time_display_msg`, `ldap`, `ldap_auth`, `ldap_server`, `ldap_domain`, `ldap_url`, `ldap_user`, `ldap_password`) VALUES
('Societe', '2.4', 30, 'localhost', '', '', '', '', 'Bonjour, <br />Vous avez fait la demande suivante auprès du support:', 'support@exemple.fr', 'support@exemple.fr', 1, 0, '', '0075A4', 'D8D8D8', '0075A4', 'logo.png', 0, '30', '45', 500, 0, 0, '', '', '', '', ''),
('Societe', '2.4', 30, 'localhost', '', '', '', '', 'Bonjour, <br />Vous avez fait la demande suivante auprès du support:', 'support@exemple.fr', 'support@exemple.fr', 1, 0, '', '0075A4', 'D8D8D8', '0075A4', 'logo.png', 0, '30', '45', 500, 0, 0, '', '', '', '', '');

-- --------------------------------------------------------

--
-- Table structure for table `tpriority`
--

CREATE TABLE IF NOT EXISTS `tpriority` (
  `id` int(2) NOT NULL AUTO_INCREMENT,
  `number` int(2) NOT NULL,
  `name` varchar(50) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=7 ;

--
-- Dumping data for table `tpriority`
--

INSERT INTO `tpriority` (`id`, `number`, `name`) VALUES
(1, 0, 'Urgent'),
(2, 1, 'Très haute'),
(3, 2, 'Haute'),
(4, 3, 'Moyenne'),
(5, 4, 'Basse'),
(6, 5, 'Très basse');

-- --------------------------------------------------------

--
-- Table structure for table `tprofiles`
--

CREATE TABLE IF NOT EXISTS `tprofiles` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL,
  `level` int(10) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=6 ;

--
-- Dumping data for table `tprofiles`
--

INSERT INTO `tprofiles` (`id`, `name`, `level`) VALUES
(1, 'technicien', 0),
(2, 'utilisateur avec pouvoir', 1),
(3, 'utilisateur', 2),
(4, 'superviseur', 3),
(5, 'administrateur', 4);

-- --------------------------------------------------------

--
-- Table structure for table `trights`
--

CREATE TABLE IF NOT EXISTS `trights` (
  `id` int(5) NOT NULL AUTO_INCREMENT,
  `profile` int(5) NOT NULL,
  `search` int(1) NOT NULL,
  `task` int(1) NOT NULL,
  `stat` int(1) NOT NULL,
  `admin` int(1) NOT NULL,
  `admin_user_profile` int(1) NOT NULL,
  `admin_user_view` int(1) NOT NULL,
  `userbar` int(1) NOT NULL,
  `side_open_ticket` int(1) NOT NULL,
  `side_your` int(1) NOT NULL,
  `side_your_not_read` int(1) NOT NULL,
  `side_your_not_attribute` int(1) NOT NULL,
  `side_all` int(1) NOT NULL,
  `side_all_wait` int(1) NOT NULL,
  `side_view` int(1) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=6 ;

--
-- Dumping data for table `trights`
--

INSERT INTO `trights` (`id`, `profile`, `search`, `task`, `stat`, `admin`, `admin_user_profile`, `admin_user_view`, `userbar`, `side_open_ticket`, `side_your`, `side_your_not_read`, `side_your_not_attribute`, `side_all`, `side_all_wait`, `side_view`) VALUES
(1, 0, 2, 2, 2, 2, 2, 2, 2, 2, 2, 2, 2, 2, 2, 2),
(2, 1, 0, 0, 0, 0, 0, 0, 0, 2, 0, 0, 0, 0, 2, 0),
(3, 2, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0),
(4, 3, 2, 2, 2, 0, 0, 2, 0, 2, 0, 0, 0, 2, 0, 2),
(5, 4, 2, 2, 2, 2, 2, 2, 2, 2, 2, 2, 2, 2, 2, 2);

-- --------------------------------------------------------

--
-- Table structure for table `tstates`
--

CREATE TABLE IF NOT EXISTS `tstates` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `number` int(2) NOT NULL,
  `name` varchar(50) NOT NULL,
  `mail_object` varchar(200) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=7 ;

--
-- Dumping data for table `tstates`
--

INSERT INTO `tstates` (`id`, `number`, `name`, `mail_object`) VALUES
(1, 2, 'Attente PEC', 'Notification d''ouverture'),
(2, 3, 'En cours ', 'Notification'),
(3, 5, 'Resolu', 'Notification de clôture'),
(4, 6, 'Rejeté', 'Notification de rejet'),
(5, 1, 'Non attribué', 'Notification de déclaration'),
(6, 4, 'Attente Retour', 'Notification d''attente de retour ');

-- --------------------------------------------------------

--
-- Table structure for table `tsubcat`
--

CREATE TABLE IF NOT EXISTS `tsubcat` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `cat` int(10) NOT NULL,
  `name` varchar(100) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=3 ;

--
-- Dumping data for table `tsubcat`
--

INSERT INTO `tsubcat` (`id`, `cat`, `name`) VALUES
(1, 1, 'Office'),
(2, 2, 'PC');

-- --------------------------------------------------------

--
-- Table structure for table `ttemplates`
--

CREATE TABLE IF NOT EXISTS `ttemplates` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL,
  `incident` int(10) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

--
-- Dumping data for table `ttemplates`
--


-- --------------------------------------------------------

--
-- Table structure for table `ttime`
--

CREATE TABLE IF NOT EXISTS `ttime` (
  `id` int(2) NOT NULL AUTO_INCREMENT,
  `min` int(100) NOT NULL,
  `name` varchar(50) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=11 ;

--
-- Dumping data for table `ttime`
--

INSERT INTO `ttime` (`id`, `min`, `name`) VALUES
(1, 1, '1m'),
(2, 5, '5m'),
(3, 10, '10m'),
(4, 30, '30m'),
(5, 60, '1h'),
(6, 180, '3h'),
(7, 300, '5h'),
(8, 480, '1j'),
(9, 960, '2j'),
(10, 2400, '1s');

-- --------------------------------------------------------

--
-- Table structure for table `tusers`
--

CREATE TABLE IF NOT EXISTS `tusers` (
  `id` int(4) NOT NULL AUTO_INCREMENT,
  `login` varchar(20) NOT NULL,
  `password` varchar(50) NOT NULL,
  `salt` varchar(50) NOT NULL,
  `firstname` varchar(40) NOT NULL,
  `lastname` varchar(40) NOT NULL,
  `profile` int(10) NOT NULL,
  `mail` varchar(50) NOT NULL,
  `phone` varchar(30) NOT NULL,
  `fax` varchar(20) NOT NULL,
  `company` varchar(50) NOT NULL,
  `address1` varchar(100) NOT NULL,
  `address2` varchar(100) NOT NULL,
  `zip` varchar(20) NOT NULL,
  `city` varchar(100) NOT NULL,
  `custom1` varchar(100) NOT NULL,
  `custom2` varchar(100) NOT NULL,
  `disable` int(1) NOT NULL,
  `chgpwd` int(1) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=6 ;

--
-- Dumping data for table `tusers`
--

INSERT INTO `tusers` (`id`, `login`, `password`, `salt`, `firstname`, `lastname`, `profile`, `mail`, `phone`, `fax`, `company`, `address1`, `address2`, `zip`, `city`, `custom1`, `custom2`, `disable`, `chgpwd`) VALUES
(1, 'admin', '682b2bb50e9eecd79953416cd3118dcb', '42f85', 'admin', 'sahli', 4, 'sahli28@gmail.com', '06 09 56 89 45', '0', '', '', '', '', '', '', '', 0, 0),
(2, 'user', '430ba14ec8d1fbd76765f9bc4328be8f', '39bdf', 'user', '', 2, 'sahli28@hotmail.com', '', '0', '', '', '', '', '', '', '', 0, 0),
(3, 'poweruser', 'dab26aca5f636ac4e32aa6004e3e2bef', 'eba17', 'poweruser', '', 1, 'poweruser@exemple.fr', '', '0', '', '', '', '0', '', '', '', 0, 0),
(4, 'super', '32f46e447e81b2c33e1405ea9614282c', 'bd3b2', 'supervisor', '', 3, 'supervisor@exemple.fr', '', '0', '', '', '', '0', '', '', '', 0, 0),
(5, 'tech', '7d205ef0f3371de891af6d33f6a6071d', '57d80', 'tech', '', 0, 'tech@exemple.fr', '', '0', '', '', '', '0', '', '', '', 0, 0);

-- --------------------------------------------------------

--
-- Table structure for table `tviews`
--

CREATE TABLE IF NOT EXISTS `tviews` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uid` int(10) NOT NULL,
  `name` varchar(100) NOT NULL,
  `category` int(5) NOT NULL,
  `subcat` int(5) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=2 ;

--
-- Dumping data for table `tviews`
--

INSERT INTO `tviews` (`id`, `uid`, `name`, `category`, `subcat`) VALUES
(1, 1, 'Ali sahli', 0, 0);
