<?php
/**
* Receive register inputs and process register.
*
* @author 		H.Chihoon
* @copyright 	2018 DesignAndDevelop
*/

global $factory, $auth;

//	Receive register inputs by ajax
$register_inputs = json_decode(file_get_contents('php://input'), true);
$readable_id = $register_inputs['readable_id'];
$name = $register_inputs['name'];
$password = $register_inputs['password'];

//	Get querystring of referer
$querystring = parse_url($_SERVER['HTTP_REFERER'], PHP_URL_QUERY);
if (isset($querystring)) {
	parse_str($querystring, $query_params);
}

$login_controller = $factory->createInstance('\Povium\Security\Auth\Controller\LoginController', $auth);
$register_controller = $factory->createInstance('\Povium\Security\Auth\Controller\RegisterController', $auth);
$redirect_uri_validator = $factory->createInstance('\Povium\Base\Routing\Validator\RedirectURIValidator');

#	array(
#		'err' => bool,
#		'msg' => err msg for display,
#		'redirect' => redirect url (optional param)
#	);
$register_return = $register_controller->register($readable_id, $name, $password);

if ($register_return['err']) {			//	Register fail

} else {								//	Register success
	$login_controller->login($readable_id, $password);

	$register_return['redirect'] = '/';

	if (
		isset($query_params['redirect']) &&
		$redirect_uri_validator->validate($query_params['redirect'])
	) {
		$register_return['redirect'] = $query_params['redirect'];
	}
}

echo json_encode($register_return);