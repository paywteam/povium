<?php
/**
 * AutoSavedPost class store auto saved post info.
 *
 * @author		H.Chihoon
 * @copyright	2018 DesignAndDevelop
 */

namespace Povium\Publication\Post;

class AutoSavedPost extends PostFrame
{
	/**
	 * @var string	Datetime
	 */
	protected $creationDt;

	/**
	 * @var string	Datetime
	 */
	protected $lastEditedDt;

	/**
	 * @var int|null
	 */
	protected $postID;

	/**
	 * @param int	     	$id
	 * @param int     		$user_id
	 * @param string  		$title
	 * @param string		$body
	 * @param string  		$contents
	 * @param bool    		$is_premium
	 * @param string  		$creation_dt
	 * @param string  		$last_edited_dt
	 * @param int|null		$post_id
	 * @param int|null    	$series_id
	 * @param string|null 	$subtitle
	 * @param string|null 	$thumbnail
	 */
	public function __construct(
		int $id,
		int $user_id,
		string $title,
		string $body,
		string $contents,
		bool $is_premium,
		string $creation_dt,
		string $last_edited_dt,
		?int $post_id,
		?int $series_id,
		?string $subtitle,
		?string $thumbnail
	) {
		$this->id = $id;
		$this->userID = $user_id;
		$this->title = $title;
		$this->body = $body;
		$this->contents = $contents;
		$this->isPremium = $is_premium;
		$this->creationDt = $creation_dt;
		$this->lastEditedDt = $last_edited_dt;
		$this->postID = $post_id;
		$this->seriesID = $series_id;
		$this->subtitle = $subtitle;
		$this->thumbnail = $thumbnail;
	}

	/**
	 * @return string	Datetime
	 */
	public function getCreationDt()
	{
		return $this->creationDt;
	}

	/**
	 * @return string	Datetime
	 */
	public function getLastEditedDt()
	{
		return $this->lastEditedDt;
	}

	/**
	 * @return int|null
	 */
	public function getPostID()
	{
		return $this->postID;
	}
}