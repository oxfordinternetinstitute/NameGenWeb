delimiter $$

CREATE TABLE `edge_attributes` (
  `edge_id` int(11) NOT NULL AUTO_INCREMENT,
  `session_id` varchar(32) DEFAULT NULL,
  `facebook_id` varchar(45) DEFAULT NULL,
  `name` varchar(255) DEFAULT NULL,
  `sex` varchar(12) DEFAULT NULL,
  `first_name` varchar(255) DEFAULT NULL,
  `middle_name` varchar(255) DEFAULT NULL,
  `last_name` varchar(255) DEFAULT NULL,
  `locale` varchar(45) DEFAULT NULL,
  `about_me` text,
  `hometown` varchar(255) DEFAULT NULL,
  `languages` varchar(255) DEFAULT NULL,
  `birthday_date` varchar(255) DEFAULT NULL,
  `political_beliefs` varchar(255) DEFAULT NULL,
  `relationship_status` varchar(255) DEFAULT NULL,
  `religious_beliefs` varchar(255) DEFAULT NULL,
  `likes_count` int(11) DEFAULT NULL,
  `friend_count` int(11) DEFAULT NULL,
  `mutual_friend_count` int(11) DEFAULT NULL,
  `picture_url` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`edge_id`),
  UNIQUE KEY `edge_id_UNIQUE` (`edge_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1$$

delimiter $$

CREATE TABLE `edges` (
  `session_id` varchar(32) NOT NULL,
  `edge_id` int(11) NOT NULL AUTO_INCREMENT,
  `uid1` varchar(45) NOT NULL,
  `uid2` varchar(45) NOT NULL,
  PRIMARY KEY (`edge_id`),
  UNIQUE KEY `edge_id_UNIQUE` (`edge_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1$$

delimiter $$

CREATE TABLE `egos` (
  `ego_id` int(11) NOT NULL AUTO_INCREMENT,
  `facebook_id` varchar(45) NOT NULL,
  `name` varchar(255) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `email_permission` bit(1) DEFAULT b'0',
  PRIMARY KEY (`ego_id`),
  UNIQUE KEY `ego_id_UNIQUE` (`ego_id`)
) ENGINE=InnoDB AUTO_INCREMENT=73 DEFAULT CHARSET=latin1$$

delimiter $$

CREATE TABLE `sessions` (
  `session_id` varchar(32) NOT NULL,
  `ego_fid` varchar(45) DEFAULT NULL,
  `session_date` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`session_id`),
  UNIQUE KEY `session_id_UNIQUE` (`session_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1$$

