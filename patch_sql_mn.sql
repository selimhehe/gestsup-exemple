-- 18/02/2013
ALTER TABLE `tcompany` ADD `responsible` INT NOT NULL AFTER `id` 
-- 02/03/2013
ALTER TABLE  `tcompany` ADD  `service` VARCHAR( 255 ) NOT NULL;
ALTER TABLE  `tcompany` 
ADD  `civilite` VARCHAR( 64 ) NOT NULL AFTER  `nom` ,
ADD  `prenom` VARCHAR( 255 ) NOT NULL AFTER  `civilite` ,
ADD  `email` VARCHAR( 255 ) NOT NULL AFTER  `prenom` ,
ADD  `type_groupe` VARCHAR( 10 ) NOT NULL AFTER  `code`;
-- 03/03/2013
ALTER TABLE  `tcompany` ADD  `nom_groupe` VARCHAR( 255 ) NOT NULL AFTER  `responsible`

-- -- --> Last Query By Mahmoud : 05/03/2013
ALTER TABLE  `trights` ADD  `company` INT( 1 ) NOT NULL AFTER  `task`;
