<?php
/**
 * Manage all post records.
 *
 * @author		H.Chihoon
 * @copyright	2019 Payw
 */

namespace Povium\Publication\Post;

use Povium\Record\AbstractRecordManager;
use Povium\Record\Exception\InvalidParameterNumberException;

class PostManager extends AbstractRecordManager
{
	/**
	 * Returns a post instance.
	 *
	 * @param  int	$post_id
	 *
	 * @return Post|false
	 */
	public function getPost($post_id)
	{
		$record = $this->getRecord($post_id);

		$post = new Post(...array_values($record));

		return $post;
	}

	/**
	 * {@inheritdoc}
	 *
	 * @param int	  		$user_id
	 * @param string  		$contents
	 * @param string		$body
	 * @param string  		$title
	 * @param bool			$is_premium
	 * @param string|null  	$subtitle
	 * @param string|null	$thumbnail
	 * @param int|null  	$series_id
	 */
	public function addRecord()
	{
		if (func_num_args() != 8) {
			throw new InvalidParameterNumberException('Invalid parameter number for creating "post" record.');
		}

		$args = func_get_args();

		$user_id = $args[0];
		$contents = $args[1];
		$body = $args[2];
		$title = $args[3];
		$is_premium = $args[4];
		$subtitle = $args[5];
		$thumbnail = $args[6];
		$series_id = $args[7];

		$stmt = $this->conn->prepare(
			"INSERT INTO {$this->config['table']}
			(user_id, contents, body, title, is_premium, subtitle, thumbnail, series_id)
			VALUES (:user_id, :contents, :body, :title, :is_premium, :subtitle, :thumbnail, :series_id)"
		);
		$query_params = [
			':user_id' => $user_id,
			':contents' => $contents,
			':body' => $body,
			':title' => $title,
			':is_premium' => $is_premium,
			':subtitle' => $subtitle,
			':thumbnail' => $thumbnail,
			':series_id' => $series_id
		];
		if (!$stmt->execute($query_params)) {
			return false;
		}

		return true;
	}
}
