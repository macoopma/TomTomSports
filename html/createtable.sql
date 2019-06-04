

CREATE TABLE IF NOT EXISTS `tracking` (
  `id`               int(11) NOT NULL,
  `index`            int(11) NOT NULL,
  `date`             datetime NOT NULL,
  `steps`            int,
  `metabolic_energy` int,
  `active_time`      int,
  `distance`         int,
  `sleep`            int,
  `muscle`           smallint(3),
  `fat`              smallint(3),
  `weight`           smallint(3),
  `hr_min`           smallint(3),
  `hr_max`           smallint(3),
  `hr_avg`           smallint(3),
  `hr_rest`          smallint(3)
 
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

 
 ALTER TABLE `tracking` ADD INDEX(`id`);
 ALTER TABLE `tracking` CHANGE `id` `id`  int(11) NOT NULL AUTO_INCREMENT;
 ALTER TABLE `tracking` ADD UNIQUE(`id`);
 
