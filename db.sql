-- CREATE DATABASE universitysync CHARACTER SET utf8 COLLATE utf8_slovak_ci;
USE universitysync;

DROP TABLE IF EXISTS `member`;
DROP TABLE IF EXISTS `group`;
DROP TABLE IF EXISTS `user`;
DROP TABLE IF EXISTS `faculty`;
DROP TABLE IF EXISTS `university`;

CREATE TABLE `university` (
	id 			INT(11)			NOT NULL	PRIMARY KEY AUTO_INCREMENT,
	name		TEXT			NOT NULL,
	short_name	VARCHAR(100)	NOT NULL
);

CREATE TABLE `faculty` (
	id 				INT(11)			NOT NULL	PRIMARY KEY AUTO_INCREMENT,
	id_university	INT(11)			NOT NULL	REFERENCES `univerity`(id) ON DELETE CASCADE,
	name			TEXT			NOT NULL,
	short_name		VARCHAR(100)	NOT NULL
);

CREATE TABLE `user` (
	id 			INT(11)			NOT NULL	PRIMARY KEY AUTO_INCREMENT,
	name		VARCHAR(31)		NOT NULL,
	surname		VARCHAR(31)		NOT NULL,
	email		VARCHAR(40)		NOT NULL,
	password	VARCHAR(18)		NOT NULL,
	university 	VARCHAR(100),
	info		VARCHAR(5000),
	rank		INT(11)			DEFAULT 0,
	sex			VARCHAR(1)		DEFAULT 'm',
	id_faculty	INT(11)			REFERENCES `faculty`(id)
);

CREATE TABLE `group` (
	id 			INT(11)			NOT NULL	PRIMARY KEY AUTO_INCREMENT,
	name		VARCHAR(500)	NOT NULL,
	university 	VARCHAR(100),
	info		VARCHAR(5000),
	public		INT(1)			DEFAULT 0,
	member_info	VARCHAR(4000),
	faculty		VARCHAR(127)
);

CREATE TABLE `member` (
	id 			INT(11)	NOT NULL	PRIMARY KEY AUTO_INCREMENT,
	id_user		INT(11)	NOT NULL	REFERENCES `user`(id) ON DELETE CASCADE,
	id_group	INT(11)	NOT NULL	REFERENCES `group`(id) ON DELETE CASCADE,
	admin		INT(1)	DEFAULT 0		
);
