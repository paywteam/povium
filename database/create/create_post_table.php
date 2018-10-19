<?php
/**
* Create post table.
*
* @author 		H.Chihoon
* @copyright 	2018 DesignAndDevelop
*/

class CreatePostTable
{
	/**
	 * Returns sql for creating post table.
	 *
	 * @return string
	 */
	public function getCreateSQL()
	{
		$sql = "CREATE TABLE IF NOT EXISTS post (
			id VARCHAR(32) PRIMARY KEY,
			user_id INT(11) UNSIGNED NOT NULL,
			title VARCHAR(200) NOT NULL,
			contents JSON NOT NULL,
			is_premium BOOLEAN NOT NULL,
			is_deleted BOOLEAN NOT NULL DEFAULT FALSE,
			view_cnt INT(11) UNSIGNED NOT NULL DEFAULT 0,
			share_cnt INT(11) UNSIGNED NOT NULL DEFAULT 0,
			publishing_dt DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
			last_edited_dt DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
			series_id INT(11) UNSIGNED,
			subtitle VARCHAR(500),
			thumbnail VARCHAR(512),
			CONSTRAINT FK__user__post FOREIGN KEY (user_id)
			REFERENCES user (id) ON DELETE CASCADE ON UPDATE CASCADE,
			CONSTRAINT FK__series__post FOREIGN KEY (series_id)
			REFERENCES series (id) ON DELETE SET NULL ON UPDATE CASCADE

		) ENGINE=InnoDB DEFAULT CHARSET=utf8";

		return $sql;
	}

	/**
	 * Returns sql for dropping post table.
	 *
	 * @return string
	 */
	public function getDropSQL()
	{
		$sql = "DROP TABLE IF EXISTS post";

		return $sql;
	}
}