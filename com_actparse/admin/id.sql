ALTER TABLE `encounter_table`
	CHANGE `starttime` `starttime` TIMESTAMP NOT NULL DEFAULT '0000-00-00 00:00:00',
	ADD `id` int(11) NOT NULL AUTO_INCREMENT,
	ADD `catid` int(11) NOT NULL DEFAULT '0',
	ADD `rid` int(11),
	ADD `checked_out` int(11) NOT NULL,
	ADD `checked_out_time` datetime NOT NULL,
	ADD `published` tinyint(1) NOT NULL DEFAULT 1,
	ADD PRIMARY KEY  (`id`);