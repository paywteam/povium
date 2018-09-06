<?php
/**
* Table for connected user.
*
* @author 		H.Chihoon
* @copyright 	2018 DesignAndDevelop
*/

$sql = "CREATE TABLE connected_user (
	id INT(11) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
	session_id VARCHAR(512) NOT NULL UNIQUE,
	hash CHAR(96) NOT NULL UNIQUE,
	user_id INT(11) UNSIGNED NOT NULL,
	ip VARCHAR(50) NOT NULL,
	expn_dt DATETIME NOT NULL,
	agent TEXT,
	CONSTRAINT FK__session__connected_user FOREIGN KEY (session_id)
	REFERENCES session (id) ON DELETE CASCADE ON UPDATE CASCADE,
	CONSTRAINT FK__user__connected_user FOREIGN KEY (user_id)
	REFERENCES user (id) ON DELETE CASCADE ON UPDATE CASCADE

) ENGINE=InnoDB DEFAULT CHARSET=utf8";
