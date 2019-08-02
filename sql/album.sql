CREATE TABLE `album` (
  `id` smallint(5) unsigned NOT NULL AUTO_INCREMENT,
  `postDate` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `name` varchar(40) CHARACTER SET utf8 NOT NULL,
  `description` text CHARACTER SET utf8 NOT NULL,
  `isDog` tinyint(1) unsigned NOT NULL,
  `isMale` tinyint(1) unsigned NOT NULL,
  `breedId` tinyint(2) unsigned NOT NULL,
  `size` tinyint(1) unsigned NOT NULL,
  `bornDate` date NOT NULL,
  `important` tinyint(1) unsigned NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=latin1;