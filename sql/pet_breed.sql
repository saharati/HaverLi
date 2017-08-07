CREATE TABLE `pet_breed` (
  `id` tinyint(2) unsigned NOT NULL AUTO_INCREMENT,
  `isDog` tinyint(1) unsigned NOT NULL,
  `name` varchar(40) CHARACTER SET utf8 NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=latin1;