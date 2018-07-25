<?php
/**
* Set routes of middleware
*
* @author H.Chihoon
* @copyright 2018 DesignAndDevelop
*/

/* Login Ajax */
$router->post(
	'/login',
	function () {
		require $_SERVER['DOCUMENT_ROOT'] . '/../app/Controllers/Auth/loginController.php';

		return true;
	}
);

/* Register confirm Ajax */
$router->post(
	'/register',
	function () {
		require $_SERVER['DOCUMENT_ROOT'] . '/../app/Controllers/Auth/registerConfirmController.php';

		return true;
	}
);

/* Register verify Ajax */
$router->put(
	'/register',
	function () {
		require $_SERVER['DOCUMENT_ROOT'] . '/../app/Controllers/Auth/registerVerifyController.php';

		return true;
	}
);

/* Logout Ajax */
$router->post(
	'/logout',
	function () {
		require $_SERVER['DOCUMENT_ROOT'] . '/../app/Controllers/Auth/logoutController.php';

		return true;
	}
);

/* Register new email address Ajax */
$router->get(		#	Get is Test mode. Original is post.
	'/me/settings/email/new-registration',
	function () {
		require $_SERVER['DOCUMENT_ROOT'] . '/../app/Controllers/Auth/sendEmailForEmailAuthController.php';

		return true;
	}
);

/* Email authentication page */
$router->get(
	'/c/account/verify',
	function () {
		require $_SERVER['DOCUMENT_ROOT'] . '/../app/Controllers/Auth/emailAuthController.php';

		return true;
	},
	'email_authentication'
);