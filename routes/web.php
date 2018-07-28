<?php
/**
* Set routes for web page.
*
* @author H.Chihoon
* @copyright 2018 DesignAndDevelop
*/

/* Home Page */
$router->get(
	'/',
 	function () {
		require $_SERVER['DOCUMENT_ROOT'] . '/../resources/views/home.php';

		return true;
	}
);

/* Login Page */
$router->get(
	'/login',
 	function () use ($auth) {
		//	If already logged in, send to home page.
		if ($auth->isLoggedIn()) {
			header(
				'Location: ' . BASE_URI . '/',
 				true,
 				301
			);

			exit();
		}

		//	If referer is register page
		if (
			isset($_SERVER['HTTP_REFERER']) &&
 			'/register' == parse_url($_SERVER['HTTP_REFERER'], PHP_URL_PATH)
		) {
			$prev_query = parse_url($_SERVER['HTTP_REFERER'], PHP_URL_QUERY);
			$curr_query = parse_url($_SERVER['REQUEST_URI'], PHP_URL_QUERY);
			if (isset($prev_query) && !isset($curr_query)) {
				//	Concatenate referer's query to current url.
				//	Then redirect.
				header(
					'Location: ' . BASE_URI . parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH) . '?' . $prev_query,
					true,
	 				301
				);

				exit();
			}
		}

		require $_SERVER['DOCUMENT_ROOT'] . '/../resources/views/auth/login.php';

		return true;
	}
);

/* Regsiter Page */
$router->get(
	'/register',
 	function () use ($auth) {
		//	If already logged in, send to home page.
		if ($auth->isLoggedIn()) {
			header(
				'Location: ' . BASE_URI . '/',
 				true,
 				301
			);

			exit();
		}

		//	If referer is login page
		if (
			isset($_SERVER['HTTP_REFERER']) &&
 			'/login' == parse_url($_SERVER['HTTP_REFERER'], PHP_URL_PATH)
		) {
			$prev_query = parse_url($_SERVER['HTTP_REFERER'], PHP_URL_QUERY);
			$curr_query = parse_url($_SERVER['REQUEST_URI'], PHP_URL_QUERY);
			if (isset($prev_query) && !isset($curr_query)) {
				//	Concatenate referer's query to current url.
				//	Then redirect.
				header(
					'Location: ' . BASE_URI . parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH) . '?' . $prev_query,
					true,
	 				301
				);

				exit();
			}
		}

		require $_SERVER['DOCUMENT_ROOT'] . '/../resources/views/auth/register.php';

		return true;
	}
);

/* Editor test page */
$router->get(
	'/editor',
 	function () use ($auth) {
		// //	If is not logged in, send to login page.
		// if (!$auth->isLoggedIn()) {
		// 	$querystring = http_build_query(array(
		// 		'redirect' => BASE_URI . $_SERVER['REQUEST_URI']
		// 	));
		//
		// 	header(
		// 		'Location: ' . BASE_URI . '/register' . '?' . $querystring,
 		// 		true,
 		// 		301
		// 	);
		//
		// 	exit();
		// }

		require $_SERVER['DOCUMENT_ROOT'] . '/../resources/views/editor.php';

		return true;
	}
);

/* User Profile Page */
$router->get(
	'/@{readable_id:.+}',
 	function ($readable_id) use ($auth) {
		$readable_id = strtolower($readable_id);

		//	Nonexistent readable id.
		if (false === $user_id = $auth->getID($readable_id)) {
			return false;
		}

		require $_SERVER['DOCUMENT_ROOT'] . '/../resources/views/user_home.php';

		return true;
	},
	'user_profile'
);

/* Post Page */
$router->get(
	'/@{readable_id:.+}/{post_title:.+}-{post_id:\d+}',
 	function ($readable_id, $post_title, $post_id) {
		//	post_id에 해당하는 포스트가 있는지 체크
		//	없으면 return false
		//	있으면 포스트 작성자의 readable id와 포스트 타이틀을 가져옴
		//	대소문자 무시하고 가져온 readable id와 파라미터 readable_id 값을 비교
		//	대소문자 무시하고 가져온 title과 파라미터 title값을 비교 (가져온 title은 raw한 형태 -> 특문과 공백이 섞여있음)
		//	둘중 하나라도 다르면 올바른 uri로 redirect시킨 후 (header), exit() 시킴
		//	둘다 같으면 포스트페이지 require
		require $_SERVER['DOCUMENT_ROOT'] . '/../resources/views/post.php';

		return true;
	},
 	'post'
);

/* Email Setting Page */
$router->get(
	'/me/settings/email',
	function () use ($auth) {
		//	If is not logged in, send to register page.
		if (!$auth->isLoggedIn()) {
			$querystring = http_build_query(array(
				'redirect' => BASE_URI . $_SERVER['REQUEST_URI']
			));

			header(
				'Location: ' . BASE_URI . '/register' . '?' . $querystring,
 				true,
 				301
			);

			exit();
		}

		//	require page

		return true;
	}
);
