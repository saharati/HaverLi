CREATE TABLE `family` (
  `imageOrder` tinyint(2) unsigned NOT NULL,
  `image` tinytext CHARACTER SET utf8 NOT NULL,
  `imageCaption` text CHARACTER SET utf8 NOT NULL,
  PRIMARY KEY (`imageOrder`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=latin1;