-- XLR(8) sign-in/portal database schema

CREATE TABLE users(
	user_id int(12) unsigned NOT NULL auto_increment,
	given_name varchar(64) NOT NULL,
	surname varchar(64) NOT NULL,
	password varchar(256) DEFAULT NULL,
	email varchar(256) DEFAULT NULL,
	role ENUM('student', 'parent', 'leader', 'administrator') NOT NULL DEFAULT 'student',
	grade tinyint(1) unsigned DEFAULT NULL,
	PRIMARY KEY ( user_id )
);

CREATE TABLE attendance(
	record_id int(12) unsigned NOT NULL auto_increment,
	`date` date NOT NULL,
	user_id int(12) unsigned NOT NULL,
	behavior_score tinyint(1) unsigned NOT NULL,
	PRIMARY KEY ( record_id ),
	UNIQUE KEY ( `date`, user_id ),
	CONSTRAINT FOREIGN KEY ( user_id ) REFERENCES users ( user_id ) ON DELETE CASCADE
);

CREATE TABLE guardians(
	relationship_id int(12) unsigned NOT NULL auto_increment,
	parent_id int(12) unsigned NOT NULL,
	child_id int(12) unsigned NOT NULL,
	PRIMARY KEY ( relationship_id ),
	UNIQUE KEY ( parent_id, child_id ),
	CONSTRAINT FOREIGN KEY ( parent_id ) REFERENCES users ( user_id ) ON DELETE CASCADE,
	CONSTRAINT FOREIGN KEY ( child_id ) REFERENCES users ( user_id ) ON DELETE CASCADE
);

CREATE TABLE homework(
	homework_id bigint(20) unsigned NOT NULL auto_increment,
	attendance_id int(12) unsigned NOT NULL,
	subject ENUM('reading', 'math', 'science', 'other') NOT NULL,
	amount tinyint(1) unsigned NOT NULL,
	PRIMARY KEY ( homework_id ),
	CONSTRAINT FOREIGN KEY ( attendance_id ) REFERENCES attendance ( record_id ) ON DELETE CASCADE
);

CREATE TABLE config(
	entry_name varchar(64) NOT NULL,
	entry_value mediumtext,
	PRIMARY KEY ( entry_name )
);
