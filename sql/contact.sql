CREATE TABLE `contact` (
  `viewOrder` tinyint(2) unsigned NOT NULL,
  `name` varchar(45) CHARACTER SET utf8 NOT NULL,
  `value` varchar(45) CHARACTER SET utf8 NOT NULL,
  PRIMARY KEY (`viewOrder`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=latin1;