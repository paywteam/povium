<?php
/**
* Table for user's info.
*
* @author 		H.Chihoon
* @copyright 	2018 DesignAndDevelop
*/

$sql = "CREATE TABLE users (
	id INT(11) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
	readable_id VARCHAR(20) NOT NULL UNIQUE,
	name VARCHAR(30) NOT NULL UNIQUE,
	editor_type_id TINYINT(1) UNSIGNED NOT NULL DEFAULT 1,
	password VARCHAR(255) NOT NULL,
	is_verified BOOLEAN NOT NULL DEFAULT FALSE,
	is_active BOOLEAN NOT NULL DEFAULT TRUE,
	last_login_dt DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
	reg_dt DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
	subs_expn_dt DATETIME,
	email VARCHAR(50) UNIQUE,
	CONSTRAINT FK__users__editor_types FOREIGN KEY (editor_type_id)
	REFERENCES editor_types (id) ON DELETE RESTRICT ON UPDATE CASCADE

) ENGINE=InnoDB DEFAULT CHARSET=utf8";
