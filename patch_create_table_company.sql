CREATE TABLE  `bsup`.`tcompany` (
`id` INT NOT NULL AUTO_INCREMENT PRIMARY KEY ,
`code` VARCHAR( 255 ) NOT NULL ,
`raison_social` VARCHAR( 255 ) NOT NULL ,
`diminutif` VARCHAR( 255 ) NULL ,
`rue` VARCHAR( 20 ) NULL ,
`code_postal` VARCHAR( 20 ) NULL ,
`ville` VARCHAR( 60 ) NULL ,
`telephone` VARCHAR( 60 ) NULL ,
`gsm` VARCHAR( 60 ) NULL ,
`tva` VARCHAR( 60 ) NULL ,
`compte_ban` VARCHAR( 100 ) NULL
) ENGINE = MYISAM ;