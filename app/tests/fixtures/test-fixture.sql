SET FOREIGN_KEY_CHECKS=0;

DROP TABLE IF EXISTS `fruit`;
CREATE TABLE `fruit` (
`fruit_id` INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY ,
`name` VARCHAR( 20 ) NOT NULL
) ENGINE = innodb;

DROP TABLE IF EXISTS `region`;
CREATE TABLE `region` (
`region_id` INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY ,
`fruit_id` INT UNSIGNED NOT NULL ,
`name` VARCHAR( 20 ) NOT NULL ,
INDEX ( `fruit_id` ),
FOREIGN KEY (fruit_id) REFERENCES fruit(fruit_id) ON DELETE RESTRICT
) ENGINE = innodb;

INSERT INTO `region` (`region_id`, `fruit_id`, `name`) VALUES 
(2, 2, 'Canada');

INSERT INTO `region` (`region_id`, `fruit_id`, `name`) VALUES 
(3, 3, 'Mexico');

INSERT INTO `fruit` (`fruit_id`, `name`) VALUES
(2, 'Apple');

INSERT INTO `fruit` (`fruit_id`, `name`) VALUES
(3, 'Banana');

SET FOREIGN_KEY_CHECKS=1;