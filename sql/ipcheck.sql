CREATE TABLE `ipcheck` (
  `ip` varchar(40) CHARACTER SET utf8 NOT NULL,
  `fails` tinyint(1) unsigned NOT NULL,
  `timeSince` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`ip`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;