<?php
/**
 * Controller for editing post.
 *
 * @author 		H.Chihoon
 * @copyright 	2018 DesignAndDevelop
 */

namespace Povium\Http\Controller\Post;

use Povium\Http\Controller\Exception\InvalidAccessException;
use Povium\Http\Controller\Exception\PostNotFoundException;
use Povium\Publication\Post\AutoSavedPostManager;
use Povium\Publication\Post\PostManager;
use Povium\Security\User\User;

class PostEditController
{
	/**
	 * @var array
	 */
	private $config;

	/**
	 * Database connection (PDO)
	 *
	 * @var \PDO
	 */
	protected $conn;

	/**
	 * @var PostManager
	 */
	protected $postManager;

	/**
	 * @var PostFormValidationController
	 */
	protected $postFormValidationController;

	/**
	 * @var AutoSavedPostManager
	 */
	protected $autoSavedPostManager;

	/**
	 * @param array 						$config
	 * @param \PDO 							$conn
	 * @param PostManager 					$post_manager
	 * @param PostFormValidationController 	$post_form_validation_controller
	 * @param AutoSavedPostManager 			$auto_saved_post_manager
	 */
	public function __construct(
		array $config,
		\PDO $conn,
		PostManager $post_manager,
		PostFormValidationController $post_form_validation_controller,
		AutoSavedPostManager $auto_saved_post_manager
	) {
		$this->config = $config;
		$this->conn = $conn;
		$this->postManager = $post_manager;
		$this->postFormValidationController = $post_form_validation_controller;
		$this->autoSavedPostManager = $auto_saved_post_manager;
	}

	/**
	 * Validate post fields and update the post record.
	 * And delete the auto saved record for post.
	 *
	 * @param int			$post_id
	 * @param User 			$user		User who requested
	 * @param string 		$title
	 * @param string 		$body
	 * @param string 		$contents
	 * @param bool 			$is_premium
	 * @param int|null 		$series_id
	 * @param string|null 	$subtitle
	 * @param string|null 	$thumbnail
	 *
	 * @return array	Error flag and message
	 *
	 * @throws PostNotFoundException	If the post is not found
	 * @throws InvalidAccessException	If the requested user isn't the editor of the post
	 */
	public function edit(
		$post_id,
		$user,
		$title,
		$body,
		$contents,
		$is_premium,
		$series_id = null,
		$subtitle = null,
		$thumbnail = null
	) {
		$return = array(
			'err' => true,
			'msg' => ''
		);

		$post = $this->postManager->getPost($post_id);

		if ($post === false) {
			throw new PostNotFoundException($this->config['msg']['post_not_found']);
		}

		if ($user->getID() != $post->getUserID()) {
			throw new InvalidAccessException($this->config['msg']['invalid_access']);
		}

		/* Validation check for fields */

		if (!$this->postFormValidationController->isValid(
			$user,
			$title,
			$body,
			$contents,
			$is_premium,
			$series_id,
			$subtitle,
			$thumbnail
		)) {
			$return['msg'] = $this->config['msg']['incorrect_form'];

			return $return;
		}

		/* Edit */

		try {
			$this->conn->beginTransaction();

			$params = array(
				'title' => $title,
				'body' => $body,
				'contents' => $contents,
				'is_premium' => $is_premium,
				'last_edited_dt' => date('Y-m-d H:i:s'),
				'series_id' => $series_id,
				'subtitle' => $subtitle,
				'thumbnail' => $thumbnail
			);
			$this->postManager->updateRecord($post_id, $params);

			$auto_saved_post_id = $this->autoSavedPostManager->getAutoSavedPostFromPostID($post_id)->getID();
			$this->autoSavedPostManager->deleteRecord($auto_saved_post_id);

			$this->conn->commit();
		} catch (\PDOException $e) {
			$this->conn->rollBack();

			$return['msg'] = $this->config['msg']['edit_err'];

			return $return;
		}

		//	Successfully edited
		$return['err'] = false;

		return $return;
	}
}
