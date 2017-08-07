CREATE TABLE `album_video` (
  `id` smallint(4) unsigned NOT NULL AUTO_INCREMENT,
  `albumId` smallint(5) unsigned NOT NULL,
  `video` tinytext CHARACTER SET utf8 NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=latin1;