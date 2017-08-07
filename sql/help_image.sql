CREATE TABLE `help_image` (
  `imageOrder` tinyint(2) unsigned NOT NULL,
  `image` tinytext CHARACTER SET utf8 NOT NULL,
  `image2` tinytext CHARACTER SET utf8 NOT NULL,
  PRIMARY KEY (`imageOrder`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=latin1;