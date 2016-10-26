
CREATE DATABASE `cs_target` DEFAULT CHARACTER SET utf8;
use `cs_target`;

CREATE TABLE `cs_user` (
  `id` INT PRIMARY KEY AUTO_INCREMENT,
  `username` VARCHAR(63) DEFAULT '',
  `password` VARCHAR(127) DEFAULT '',
  `name` VARCHAR(63) DEFAULT '',
  `sex` INT DEFAULT 0,
  `birthday` VARCHAR(63) DEFAULT '',
  `email` VARCHAR(127) DEFAULT '',
  `phone` VARCHAR(63) DEFAULT '',
  `address` VARCHAR(63) DEFAULT '',
  `team_id` INT DEFAULT 0,
  `power` INT DEFAULT 1,
  `status` INT DEFAULT 0,
  `remark` VARCHAR(1023) DEFAULT ''
);

CREATE TABLE `cs_team` (
  `id` INT PRIMARY KEY AUTO_INCREMENT,
  `number` VARCHAR(63) DEFAULT '',
  `name` VARCHAR(127) DEFAULT '',
  `year` VARCHAR(63) DEFAULT '',
  `user_sum` INT DEFAULT 0
);

CREATE TABLE `cs_target` (
  `id` INT PRIMARY KEY AUTO_INCREMENT,
  `title` VARCHAR(127) DEFAULT '',
  `start_time` VARCHAR(127) DEFAULT '',
  `achieve_time` VARCHAR(127) DEFAULT '',
  `type_id` INT DEFAULT 0,
  `detail` VARCHAR(1023) DEFAULT '',
  `status` INT DEFAULT 0,
  `user_id` INT DEFAULT 0
);

CREATE TABLE `cs_type` (
  `id` INT PRIMARY KEY AUTO_INCREMENT,
  `name` varchar(127) DEFAULT ''
);

INSERT INTO `cs_user` (`username`, `password`, `power`) VALUES ('admin', '30ea328eb728bcd6823825f99032d3de', 0);
INSERT INTO `cs_type` (`name`) VALUES ('其他');
















