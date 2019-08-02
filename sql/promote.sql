CREATE TABLE `promote` (
  `page` varchar(20) CHARACTER SET utf8 NOT NULL,
  `pageHebrew` varchar(20) CHARACTER SET utf8 NOT NULL,
  `title` varchar(45) CHARACTER SET utf8 NOT NULL,
  `description` text CHARACTER SET utf8 NOT NULL,
  `image` tinytext CHARACTER SET utf8 NOT NULL,
  `url` tinytext CHARACTER SET utf8 NOT NULL,
  PRIMARY KEY (`page`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

INSERT INTO `promote` VALUES ('default', 'דף הבית / כללי', '', '', '', '');
INSERT INTO `promote` VALUES ('about', 'אודות', '', '', '', '');
INSERT INTO `promote` VALUES ('adopted', 'משפחות מאושרות', '', '', '', '');
INSERT INTO `promote` VALUES ('articles', 'מאמרים', '', '', '', '');
INSERT INTO `promote` VALUES ('board', 'לוח מודעות', '', '', '', '');
INSERT INTO `promote` VALUES ('cats', 'פינת אימוץ חתולים', '', '', '', '');
INSERT INTO `promote` VALUES ('dogs', 'פינת אימוץ כלבים', '', '', '', '');
INSERT INTO `promote` VALUES ('contact', 'יצירת קשר', '', '', '', '');
INSERT INTO `promote` VALUES ('donate', 'תרומות', '', '', '', '');
INSERT INTO `promote` VALUES ('volunteer', 'התנדבות', '', '', '', '');
INSERT INTO `promote` VALUES ('foster', 'בתי אומנה', '', '', '', '');
INSERT INTO `promote` VALUES ('info', 'מידע למאמץ', '', '', '', '');
INSERT INTO `promote` VALUES ('lost', 'אבד לי הכלב', '', '', '', '');
INSERT INTO `promote` VALUES ('process', 'תהליך אימוץ', '', '', '', '');
INSERT INTO `promote` VALUES ('recommend', 'המומלצים שלנו', '', '', '', '');