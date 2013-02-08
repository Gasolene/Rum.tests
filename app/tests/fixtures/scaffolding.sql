-- phpMyAdmin SQL Dump
-- version 2.9.0-rc1
-- http://www.phpmyadmin.net
-- 
-- Host: localhost
-- Generation Time: Oct 23, 2008 at 05:12 PM
-- Server version: 5.0.27
-- PHP Version: 5.1.6
-- 
-- Database: `darnell_test-plugin`
-- 

-- --------------------------------------------------------

-- 
-- Table structure for table `category`
-- 

DROP TABLE IF EXISTS `category`;
CREATE TABLE `category` (
  `category_id` int(10) unsigned NOT NULL auto_increment,
  `category` varchar(255) NOT NULL,
  PRIMARY KEY  (`category_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=3 ;

-- 
-- Dumping data for table `category`
-- 

INSERT INTO `category` (`category_id`, `category`) VALUES 
(1, 'Active'),
(2, 'Disabled');

-- --------------------------------------------------------

-- 
-- Table structure for table `customer`
-- 

DROP TABLE IF EXISTS `customer`;
CREATE TABLE `customer` (
  `customer_id` int(10) unsigned NOT NULL auto_increment,
  `category_id` int(10) unsigned NOT NULL,
  `customer_name` varchar(50) NOT NULL,
  `customer_phone` varchar(20) NOT NULL,
  `customer_birthday` date NOT NULL,
  `customer_details` mediumtext NOT NULL,
  `customer_active` tinyint(1) NOT NULL,
  PRIMARY KEY  (`customer_id`),
  KEY `category_id` (`category_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=12 ;

-- 
-- Dumping data for table `customer`
-- 

INSERT INTO `customer` (`customer_id`, `category_id`, `customer_name`, `customer_phone`, `customer_birthday`, `customer_details`, `customer_active`) VALUES 
(8, 1, 'Active', 'Active', '2008-10-09', 'Test', 1),
(9, 2, 'Disabled', 'Disabled ', '2008-10-31', 'Blah Blah', 0);

-- --------------------------------------------------------

-- 
-- Table structure for table `group`
-- 

DROP TABLE IF EXISTS `group`;
CREATE TABLE `group` (
  `group_id` int(10) unsigned NOT NULL auto_increment,
  `group_name` varchar(255) NOT NULL,
  PRIMARY KEY  (`group_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=3 ;

-- 
-- Dumping data for table `group`
-- 

INSERT INTO `group` (`group_id`, `group_name`) VALUES 
(1, 'Test Group'),
(2, 'Another Group');

-- --------------------------------------------------------

-- 
-- Table structure for table `group_customer`
-- 

DROP TABLE IF EXISTS `group_customer`;
CREATE TABLE `group_customer` (
  `group_id` int(10) unsigned NOT NULL,
  `customer_id` int(10) unsigned NOT NULL,
  PRIMARY KEY  (`group_id`,`customer_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- 
-- Dumping data for table `group_customer`
-- 

INSERT INTO `group_customer` (`group_id`, `customer_id`) VALUES 
(1, 8),
(1, 9),
(2, 8);

-- --------------------------------------------------------

-- 
-- Table structure for table `users`
-- 

DROP TABLE IF EXISTS `users`;
CREATE TABLE `users` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `username` varchar(255) NOT NULL,
  `password` varchar(32) NOT NULL,
  `active` tinyint(1) NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=2 ;

-- 
-- Dumping data for table `users`
-- 

INSERT INTO `users` (`id`, `username`, `password`, `active`) VALUES 
(1, 'admin', 'd3e9d6ee443cc1b1aa44ba7d3df1fd50', 1);


--
-- Table structure for table `customerlog`
--

DROP TABLE IF EXISTS `customerlog`;
CREATE TABLE `customerlog` (
  `customerlog_id` int(10) unsigned NOT NULL,
  `customer_id` int(10) unsigned NOT NULL,
  PRIMARY KEY  (`customerlog_id`),
  KEY `customer_id` (`customer_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `customerlog`
--

INSERT INTO `customerlog` (`customerlog_id`, `customer_id`) VALUES
(1, 9),
(2, 9);
