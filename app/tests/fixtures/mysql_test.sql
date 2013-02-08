
	DROP TABLE IF EXISTS `test`;

	CREATE TABLE `test` (
	  `test_id` int(10) unsigned NOT NULL auto_increment,
	  `test_double` double(5,3) NOT NULL,
	  `test_float` float(4,4) NOT NULL,
	  `test_decimal` decimal(6,2),
	  `test_bool` tinyint(1),

	  `test_char` char(2),
	  `test_varchar` varchar(80),
	  `test_blob` blob,
	  `test_varbinary` varbinary(255),

	  `test_date` date,
	  `test_time` time,
	  `test_datetime` datetime,
	  `test_timestamp` timestamp NOT NULL,

	  PRIMARY KEY  (`test_id`),
	  UNIQUE (`test_float`)
	) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;
