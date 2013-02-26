ALTER TABLE  `tusers` ADD  `code` VARCHAR( 100 ) NOT NULL;
ALTER TABLE  `tusers` ADD  `numero_rue` VARCHAR( 10 ) NOT NULL;

ALTER TABLE  `tusers` CHANGE  `code`  `code` VARCHAR( 100 ) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL ,
CHANGE  `numero_rue`  `numero_rue` VARCHAR( 10 ) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL