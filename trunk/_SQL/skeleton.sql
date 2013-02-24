CREATE TABLE `tincidents` (
  `id` int(5) NOT NULL auto_increment,
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
  `techread` int(1) NOT NULL default '1',
  `template` int(1) NOT NULL,
  `disable` int(1) NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;


CREATE TABLE `tparameters` (
  `company` varchar(50) default NULL,
  `version` varchar(4) NOT NULL,
  `maxline` int(4) NOT NULL,
  `mail_smtp` VARCHAR( 100 ),
  `mail_auth` VARCHAR( 10 ),
  `mail_secure` VARCHAR( 10 ),
  `mail_username` VARCHAR( 150 ),
  `mail_password` VARCHAR( 150 ),
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
  `ldap` INT( 1 ) NOT NULL,
  `ldap_auth` INT( 1 ) NOT NULL,
  `ldap_server` VARCHAR( 100 ) NOT NULL,
  `ldap_domain` VARCHAR( 200 ) NOT NULL,
  `ldap_url` VARCHAR( 200 ) NOT NULL,
  `ldap_user` VARCHAR( 100 ) NOT NULL,
  `ldap_password` VARCHAR( 100 ) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;


INSERT INTO `tparameters` (
`company`,
`version`,
`maxline`,
`mail_smtp`,
`mail_auth`,
`mail_secure`,
`mail_username`,
`mail_password`,
`mail_txt`,
`mail_cc`,
`mail_from`,
`mail_auto`,
`mail_newticket`,
`mail_newticket_address`,
`mail_color_title`,
`mail_color_bg`,
`mail_color_text`,
`logo`,
`lign_yellow`,
`lign_orange`,
`time_display_msg`,
`ldap`,
`ldap_auth`,
`ldap_server`,
`ldap_domain`,
`ldap_url`,
`ldap_user`,
`ldap_password`

 ) VALUES (
'Societe',
 '2.4',
 '30',
 'localhost',
 '0',
 '0',
 '',
 '',
 'Bonjour, <br />Vous avez fait la demande suivante auprès du support:',
 'support@exemple.fr',
 'support@exemple.fr',
 '0',
 '0',
 'admin@exemple.fr',
 '0075A4',
 'D8D8D8',
 '0075A4',
 'logo.png',
 '30',
 '45',
 '500',
 '0',
 '0',
 'localhost',
 'exemple.fr',
 'cn=users',
 '',
 ''
 );


CREATE TABLE IF NOT EXISTS `tstates` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `number` int(2) NOT NULL,
  `name` varchar(50) NOT NULL,
  `mail_object` varchar(200) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=7 ;

INSERT INTO `tstates` (`id`, `number`, `name`, `mail_object`) VALUES
(1, 2, 'Attente PEC', 'Notification d''ouverture'),
(2, 3, 'En cours ', 'Notification'),
(3, 5, 'Resolu', 'Notification de clôture'),
(4, 6, 'Rejeté', 'Notification de rejet'),
(5, 1, 'Non attribué', 'Notification de déclaration'),
(6, 4, 'Attente Retour', 'Notification d''attente de retour ');


CREATE TABLE `tcategory` (
  `id` int(11) NOT NULL auto_increment,
  `name` varchar(50) NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1;


INSERT INTO `tcategory` (`id`, `name`) VALUES
(1, 'Application'),
(2, 'Materiel');

CREATE TABLE `tsubcat` (
  `id` int(10) NOT NULL auto_increment,
  `cat` int(10) NOT NULL,
  `name` varchar(100) NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

INSERT INTO `tsubcat` (`id`, `cat`, `name`) VALUES
(1, 1, 'Office'),
(2, 2, 'PC');


CREATE TABLE `tusers` (
  `id` int(4) NOT NULL auto_increment,
  `login` varchar(20) NOT NULL,
  `password` varchar(50) NOT NULL,
  `salt` varchar(50) NOT NULL,
  `firstname` varchar(40) NOT NULL,
  `lastname` varchar(40) NOT NULL,
  `profile` int(10) NOT NULL,
  `mail` varchar(50) NOT NULL,
  `phone` varchar(30) NOT NULL,
  `fax` VARCHAR(20) NOT NULL,
  `company` varchar(50) NOT NULL,
  `address1` varchar(100) NOT NULL,
  `address2` varchar(100) NOT NULL,
  `zip` VARCHAR(20) NOT NULL,
  `city` varchar(100) NOT NULL,
  `custom1` varchar(100) NOT NULL,
  `custom2` varchar(100) NOT NULL,
  `disable` int(1) NOT NULL,
  `chgpwd` int(1) NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=5 ;

--
-- Dumping data for table `tusers`
--

INSERT INTO `tusers` (`id`, `login`, `password`, `salt`,  `firstname`, `lastname`, `profile`, `mail`, `phone`, `fax`, `company`, `address1`, `address2`, `zip`, `city`, `disable`, `chgpwd`) VALUES
(1, 'admin', 'admin', 'salt', 'admin', '', 4, 'admin@exemple.fr', '06 09 56 89 45', 0, '', '', '', 0, '', 0, 1),
(2, 'user', 'user', 'salt', 'user', '', 2, 'user@exemple.fr', '', 0, '', '', '', 0, '', 0, 1),
(3, 'poweruser', 'poweruser', 'salt', 'poweruser', '', 1, 'poweruser@exemple.fr', '', 0, '', '', '', 0, '', 0, 1),
(4, 'super', 'super', 'salt', 'supervisor', '', 3, 'supervisor@exemple.fr', '', 0, '', '', '', 0, '', 0, 1),
(5, 'tech', 'tech', 'salt', 'tech', '', 0, 'tech@exemple.fr', '', 0, '', '', '', 0, '', 0, 1);

CREATE TABLE IF NOT EXISTS `tpriority` (
  `id` int(2) NOT NULL AUTO_INCREMENT,
  `number` int(2) NOT NULL,
  `name` varchar(50) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=7 ;


INSERT INTO `tpriority` (`id`, `number`, `name`) VALUES
(1, 0, 'Urgent'),
(2, 1, 'Très haute'),
(3, 2, 'Haute'),
(4, 3, 'Moyenne'),
(5, 4, 'Basse'),
(6, 5, 'Très basse');


CREATE TABLE IF NOT EXISTS `ttime` (
  `id` int(2) NOT NULL AUTO_INCREMENT,
  `min` int(100) NOT NULL,
  `name` varchar(50) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=11 ;


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

CREATE TABLE IF NOT EXISTS `tevents` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `technician` int(10) NOT NULL,
  `incident` int(10) NOT NULL,
  `date` datetime NOT NULL,
  `type` int(1) NOT NULL,
  `disable` tinyint(1) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=0 ;

CREATE TABLE IF NOT EXISTS `tprofiles` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL,
  `level` int(10) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=4 ;


INSERT INTO `tprofiles` (`id`, `name`, `level`) VALUES
(1, 'technicien', 0),
(2, 'utilisateur avec pouvoir', 1),
(3, 'utilisateur', 2),
(4, 'superviseur', 3),
(5, 'administrateur', 4);

CREATE TABLE IF NOT EXISTS `ttemplates` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL,
  `incident` int(10) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

--
-- Structure de la table `tcriticality`
--

CREATE TABLE IF NOT EXISTS `tcriticality` (
  `id` int(2) NOT NULL AUTO_INCREMENT,
  `number` int(2) NOT NULL,
  `name` varchar(50) NOT NULL,
  `color` varchar(10) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=5 ;

--
-- Contenu de la table `tcriticality`
--

INSERT INTO `tcriticality` (`id`, `number`, `name`, `color`) VALUES
(1, 0, 'Critique', 'red'),
(2, 1, 'Grave', 'orange'),
(3, 2, 'Moyenne', 'yellow'),
(4, 3, 'Basse', 'green');

--
-- Structure de la table `tview`
--

CREATE TABLE IF NOT EXISTS `tviews` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uid` int(10) NOT NULL,
  `name` VARCHAR(100) NOT NULL,
  `category` int(5) NOT NULL,
  `subcat` int(5) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

--
-- Structure de la table `trights`
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
-- Contenu de la table `trights`
--

INSERT INTO `trights` (`id`, `profile`, `search`, `task`, `stat`, `admin`, `admin_user_profile`, `admin_user_view`, `userbar`, `side_open_ticket`, `side_your`, `side_your_not_read`, `side_your_not_attribute`, `side_all`, `side_all_wait`, `side_view`) VALUES
(1, 0, 2, 2, 2, 2, 2, 2, 2, 2, 2, 2, 2, 2, 2, 2),
(2, 1, 0, 0, 0, 0, 0, 0, 0, 2, 0, 0, 0, 0, 2, 0),
(3, 2, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0),
(4, 3, 2, 2, 2, 0, 0, 2, 0, 2, 0, 0, 0, 2, 0, 2),
(5, 4, 2, 2, 2, 2, 2, 2, 2, 2, 2, 2, 2, 2, 2, 2);


CREATE TABLE IF NOT EXISTS `tmails` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `incident` int(10) NOT NULL,
  `open` int(1) NOT NULL,
  `close` int(1) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=0 ;