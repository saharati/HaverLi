CREATE TABLE `album_photo` (
  `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `albumId` smallint(5) unsigned NOT NULL,
  `image` tinytext CHARACTER SET utf8 NOT NULL,
  `width` smallint(4) unsigned NOT NULL,
  `height` smallint(4) unsigned NOT NULL,
  `cover` tinyint(1) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=latin1;