ALTER TABLE  `tusers` ADD  `code` VARCHAR( 100 )  NULL;
ALTER TABLE  `tusers` ADD  `numero_rue` VARCHAR( 10 )  NULL;
ALTER TABLE  `tusers` ADD  `service` VARCHAR( 200 ) NULL;
ALTER TABLE  `tusers` ADD  `mobil` VARCHAR( 20 ) NULL;
ALTER TABLE  `tusers` ADD  `civility` VARCHAR( 4 ) NULL;
ALTER TABLE  `tusers` ADD  `code_tva` VARCHAR( 20 ) NULL ,
ADD  `note` TEXT NULL;
ALTER TABLE  `tusers` ADD  `group_id` INT NOT NULL COMMENT  'il pointe sur company'
ALTER TABLE  `tcompany` ADD  `nom` VARCHAR( 255 ) NULL