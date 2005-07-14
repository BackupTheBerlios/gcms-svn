# phpMyAdmin SQL Dump
# version 2.5.7-pl1
# http://www.phpmyadmin.net
#
# Host: localhost
# Generation Time: Jul 14, 2005 at 02:39 PM
# Server version: 4.0.21
# PHP Version: 5.0.4
# 
# Database : `gcms`
# 

# --------------------------------------------------------

#
# Table structure for table `dev_categories`
#

DROP TABLE IF EXISTS `dev_categories`;
CREATE TABLE `dev_categories` (
  `id` smallint(5) unsigned NOT NULL auto_increment,
  `root_id` int(11) NOT NULL default '0',
  `name` varchar(50) default NULL,
  `title` varchar(50) default NULL,
  `link` varchar(255) default NULL,
  `image` varchar(255) default NULL,
  `show_on` smallint(5) unsigned default NULL,
  `sort_order` smallint(5) unsigned default NULL,
  PRIMARY KEY  (`id`)
) TYPE=MyISAM PACK_KEYS=0 AUTO_INCREMENT=14 ;

#
# Dumping data for table `dev_categories`
#

INSERT INTO `dev_categories` VALUES (1, 0, 'login', 'login', 'index.php?module=login', NULL, 1, 2);
INSERT INTO `dev_categories` VALUES (2, 1, 'logout', 'logout', 'index.php?module=login&amp;section=logout', NULL, 1, 3);
INSERT INTO `dev_categories` VALUES (3, 0, 'imprint', 'imprint', 'index.php?module=imprint', NULL, 98, 1);
INSERT INTO `dev_categories` VALUES (4, 0, 'news', 'news', 'index.php?module=news', NULL, 1, 1);
INSERT INTO `dev_categories` VALUES (5, 4, 'index', 'index', 'index.php?module=news&amp;section=index', NULL, 2, 1);
INSERT INTO `dev_categories` VALUES (6, 4, 'archive', 'archive', 'index.php?module=news&amp;section=archive', NULL, 2, 2);
INSERT INTO `dev_categories` VALUES (7, 4, 'rss', 'rss', 'index.php?module=news&amp;section=rss', NULL, 2, 3);
INSERT INTO `dev_categories` VALUES (8, 4, 'add', 'add', 'index.php?module=news&amp;section=add', NULL, 2, 4);
INSERT INTO `dev_categories` VALUES (9, 4, 'edit', 'edit', 'index.php?module=news&amp;section=edit', NULL, 99, 0);
INSERT INTO `dev_categories` VALUES (10, 4, 'delete', 'delete', 'index.php?module=news&amp;section=delete', NULL, 99, 0);
INSERT INTO `dev_categories` VALUES (11, 4, 'lock', 'lock', 'index.php?module=news&amp;section=lock', NULL, 99, 0);
INSERT INTO `dev_categories` VALUES (12, 4, 'unlock', 'unlock', 'index.php?module=news&amp;section=unlock', NULL, 99, 0);
INSERT INTO `dev_categories` VALUES (13, 4, 'comments', 'comments', 'index.php?module=news&amp;section=comments', NULL, 99, 0);

# --------------------------------------------------------

#
# Table structure for table `dev_categories_auth`
#

DROP TABLE IF EXISTS `dev_categories_auth`;
CREATE TABLE `dev_categories_auth` (
  `groups_id` smallint(5) unsigned NOT NULL default '0',
  `categories_id` smallint(5) unsigned NOT NULL default '0',
  KEY `categories_id` (`categories_id`),
  KEY `groups_id` (`groups_id`)
) TYPE=MyISAM;

#
# Dumping data for table `dev_categories_auth`
#

INSERT INTO `dev_categories_auth` VALUES (1, 1);
INSERT INTO `dev_categories_auth` VALUES (2, 2);
INSERT INTO `dev_categories_auth` VALUES (1, 3);
INSERT INTO `dev_categories_auth` VALUES (2, 3);
INSERT INTO `dev_categories_auth` VALUES (1, 4);
INSERT INTO `dev_categories_auth` VALUES (2, 4);
INSERT INTO `dev_categories_auth` VALUES (1, 5);
INSERT INTO `dev_categories_auth` VALUES (2, 5);
INSERT INTO `dev_categories_auth` VALUES (1, 6);
INSERT INTO `dev_categories_auth` VALUES (2, 6);
INSERT INTO `dev_categories_auth` VALUES (1, 7);
INSERT INTO `dev_categories_auth` VALUES (2, 7);
INSERT INTO `dev_categories_auth` VALUES (2, 8);
INSERT INTO `dev_categories_auth` VALUES (4, 8);
INSERT INTO `dev_categories_auth` VALUES (2, 9);
INSERT INTO `dev_categories_auth` VALUES (4, 9);
INSERT INTO `dev_categories_auth` VALUES (2, 10);
INSERT INTO `dev_categories_auth` VALUES (4, 10);
INSERT INTO `dev_categories_auth` VALUES (2, 11);
INSERT INTO `dev_categories_auth` VALUES (4, 11);
INSERT INTO `dev_categories_auth` VALUES (2, 12);
INSERT INTO `dev_categories_auth` VALUES (4, 12);
INSERT INTO `dev_categories_auth` VALUES (1, 13);
INSERT INTO `dev_categories_auth` VALUES (2, 13);

# --------------------------------------------------------

#
# Table structure for table `dev_groups`
#

DROP TABLE IF EXISTS `dev_groups`;
CREATE TABLE `dev_groups` (
  `id` smallint(5) unsigned NOT NULL auto_increment,
  `name` varchar(50) default NULL,
  `description` text,
  PRIMARY KEY  (`id`)
) TYPE=MyISAM PACK_KEYS=0 AUTO_INCREMENT=5 ;

#
# Dumping data for table `dev_groups`
#

INSERT INTO `dev_groups` VALUES (1, 'guest', 'guest');
INSERT INTO `dev_groups` VALUES (2, 'registered', 'registered');
INSERT INTO `dev_groups` VALUES (3, 'admin', 'admin');
INSERT INTO `dev_groups` VALUES (4, 'news', 'news');

# --------------------------------------------------------

#
# Table structure for table `dev_guests`
#

DROP TABLE IF EXISTS `dev_guests`;
CREATE TABLE `dev_guests` (
  `id` smallint(5) unsigned NOT NULL auto_increment,
  `postips_id` smallint(5) unsigned NOT NULL default '0',
  `name` varchar(50) default NULL,
  `email` varchar(100) default NULL,
  PRIMARY KEY  (`id`),
  KEY `postips_id` (`postips_id`)
) TYPE=MyISAM AUTO_INCREMENT=1 ;

#
# Dumping data for table `dev_guests`
#


# --------------------------------------------------------

#
# Table structure for table `dev_links`
#

DROP TABLE IF EXISTS `dev_links`;
CREATE TABLE `dev_links` (
  `posts_id` smallint(5) unsigned NOT NULL default '0',
  `link` varchar(255) default NULL,
  `description` varchar(255) default NULL,
  `sortorder` smallint(5) unsigned default NULL,
  `species` smallint(5) unsigned default NULL,
  KEY `posts_id` (`posts_id`)
) TYPE=MyISAM;

#
# Dumping data for table `dev_links`
#


# --------------------------------------------------------

#
# Table structure for table `dev_postips`
#

DROP TABLE IF EXISTS `dev_postips`;
CREATE TABLE `dev_postips` (
  `id` smallint(5) unsigned NOT NULL auto_increment,
  `ip` varchar(20) default NULL,
  `time` varchar(20) default NULL,
  PRIMARY KEY  (`id`)
) TYPE=MyISAM AUTO_INCREMENT=1 ;

#
# Dumping data for table `dev_postips`
#


# --------------------------------------------------------

#
# Table structure for table `dev_posts`
#

DROP TABLE IF EXISTS `dev_posts`;
CREATE TABLE `dev_posts` (
  `id` smallint(5) unsigned NOT NULL auto_increment,
  `guests_id` smallint(5) unsigned NOT NULL default '0',
  `postips_id` smallint(5) unsigned NOT NULL default '0',
  `categories_id` smallint(5) unsigned NOT NULL default '0',
  `crypt_id` varchar(32) default NULL,
  `title` varchar(255) default NULL,
  `text` longtext,
  `extension` text,
  `counter` int(10) unsigned default NULL,
  `time` varchar(20) default NULL,
  `locked` tinyint(1) default NULL,
  `edittime` varchar(20) default NULL,
  `edituser` smallint(5) unsigned default NULL,
  `language` varchar(20) default NULL,
  PRIMARY KEY  (`id`),
  KEY `categories_id` (`categories_id`),
  KEY `postips_id` (`postips_id`),
  KEY `guests_id` (`guests_id`)
) TYPE=MyISAM AUTO_INCREMENT=1 ;

#
# Dumping data for table `dev_posts`
#


# --------------------------------------------------------

#
# Table structure for table `dev_rel_users_groups`
#

DROP TABLE IF EXISTS `dev_rel_users_groups`;
CREATE TABLE `dev_rel_users_groups` (
  `groups_id` smallint(5) unsigned NOT NULL default '0',
  `users_id` smallint(5) unsigned NOT NULL default '0',
  `accepted` tinyint(1) default NULL,
  KEY `groups_id` (`groups_id`),
  KEY `users_id` (`users_id`)
) TYPE=MyISAM;

#
# Dumping data for table `dev_rel_users_groups`
#

INSERT INTO `dev_rel_users_groups` VALUES (1, 1, 1);
INSERT INTO `dev_rel_users_groups` VALUES (2, 2, 1);
INSERT INTO `dev_rel_users_groups` VALUES (3, 2, 1);
INSERT INTO `dev_rel_users_groups` VALUES (2, 3, 1);
INSERT INTO `dev_rel_users_groups` VALUES (3, 3, 1);

# --------------------------------------------------------

#
# Table structure for table `dev_users`
#

DROP TABLE IF EXISTS `dev_users`;
CREATE TABLE `dev_users` (
  `id` smallint(5) unsigned NOT NULL auto_increment,
  `username` varchar(50) default NULL,
  `passwd` varchar(50) default NULL,
  `active` tinyint(1) default NULL,
  `lastvisit` varchar(20) default NULL,
  `regdate` varchar(20) default NULL,
  `posts` int(11) default NULL,
  `timezone` smallint(5) unsigned default NULL,
  `dateformat` varchar(20) default NULL,
  `timeformat` varchar(20) default NULL,
  `template` varchar(20) default NULL,
  `lang` varchar(20) default NULL,
  `allow_viewemail` tinyint(1) default NULL,
  `allow_viewonline` tinyint(1) default NULL,
  `actkey` varchar(50) default NULL,
  `newpasswd` varchar(50) default NULL,
  `email` varchar(100) default NULL,
  `icq` varchar(20) default NULL,
  `website` varchar(100) default NULL,
  PRIMARY KEY  (`id`)
) TYPE=MyISAM PACK_KEYS=0 AUTO_INCREMENT=4 ;

#
# Dumping data for table `dev_users`
#

INSERT INTO `dev_users` VALUES (1, 'guest', NULL, 1, '0', '0', 0, 0, 'd.m.Y', 'H:i', 'ghcif', 'deutsch', 0, 0, '0', NULL, 'nomail@ghcif.de', NULL, NULL);
INSERT INTO `dev_users` VALUES (2, 'mosez', '09d3bb0e0377882008f885b4bc57417e', 1, '1117553591', '1117553591', 0, 0, 'd.m.Y', 'H:i', 'ghcif', 'deutsch', 1, 1, '0', NULL, 'mosez@ghcif.de', 0, 'http://www.ghcif.de');
INSERT INTO `dev_users` VALUES (3, 'atomic', '09d3bb0e0377882008f885b4bc57417e', 1, '1117562807', '1117562807', 0, 0, 'd.m.Y', 'H:i', 'ghcif', 'deutsch', 1, 1, '0', NULL, 'atomic@ghcif.de', 0, 'http://www.ghcif.de');
