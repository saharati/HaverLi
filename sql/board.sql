CREATE TABLE `board` (
  `imageOrder` tinyint(2) unsigned NOT NULL,
  `image` tinytext CHARACTER SET utf8 NOT NULL,
  `imageLink` tinytext CHARACTER SET utf8,
  PRIMARY KEY (`imageOrder`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=latin1;