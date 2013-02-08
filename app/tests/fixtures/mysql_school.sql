
	DROP TABLE IF EXISTS `classrooms`;
	DROP TABLE IF EXISTS `School`;
	DROP TABLE IF EXISTS `student`;
	DROP TABLE IF EXISTS `student_classrooms`;
	DROP TABLE IF EXISTS `page`;
	DROP TABLE IF EXISTS `team`;
	DROP TABLE IF EXISTS `player`;

	CREATE TABLE `classrooms` (
	  `classroom_id` int(10) unsigned NOT NULL auto_increment,
	  `School_id` int(11),
	  `name` varchar(80) NOT NULL,
	  PRIMARY KEY  (`classroom_id`),
	  KEY `School_id` (`School_id`)
	) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

	CREATE TABLE `School` (
	  `School_id` int(10) unsigned NOT NULL auto_increment,
	  `School_name` varchar(80) NOT NULL,
	  PRIMARY KEY  (`School_id`)
	) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

	CREATE TABLE `student` (
	  `student_id` int(10) unsigned NOT NULL auto_increment,
	  `student_name` varchar(80) NOT NULL,
	  `student_age` int(10) unsigned NOT NULL,
	  PRIMARY KEY  (`student_id`)
	) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

	CREATE TABLE `student_classrooms` (
	  `student_id` int(10) unsigned NOT NULL,
	  `classroom_id` int(10) unsigned NOT NULL,
	  PRIMARY KEY  (`student_id`,`classroom_id`)
	) ENGINE=MyISAM DEFAULT CHARSET=utf8;

	CREATE TABLE `page` (
	  `page_id` int(10) unsigned NOT NULL auto_increment,
	  `parent_id` int(10) unsigned,
	  PRIMARY KEY  (`page_id`)
	) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

	CREATE TABLE `team` (
	  `team_id` int(10) unsigned NOT NULL auto_increment,
	  `player_id` int(10) unsigned,
	  PRIMARY KEY  (`team_id`)
	) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

	CREATE TABLE `player` (
	  `player_id` int(10) unsigned NOT NULL auto_increment,
	  `team_id` int(10) unsigned,
	  PRIMARY KEY  (`player_id`)
	) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;
