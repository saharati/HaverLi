CREATE TABLE `help` (
  `position` tinyint(2) unsigned NOT NULL,
  `title` varchar(45) CHARACTER SET utf8 NOT NULL,
  `caption` text CHARACTER SET utf8 NOT NULL,
  `image` tinytext CHARACTER SET utf8 NOT NULL,
  PRIMARY KEY (`position`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=latin1;