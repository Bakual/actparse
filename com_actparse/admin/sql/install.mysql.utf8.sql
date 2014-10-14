CREATE TABLE IF NOT EXISTS `#__actparse_raids` (
  `id` int(11) NOT NULL auto_increment,
  `raidname` varchar(64) NOT NULL,
  `date` date NOT NULL,
  `published` tinyint(1) NOT NULL DEFAULT 1,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=0 DEFAULT CHARSET=utf8;
