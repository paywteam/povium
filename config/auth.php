<?php
/**
* config array for authentication
*
* @author H.Chihoon
* @copyright 2018 DesignAndDevelop
*/


return [
	'table_users' => 'users',
	'table_tokens' => 'tokens',


	'cookie_params' => [
		'expire' => 60*60*24*30,
		'path' => '/',
		'domain' => '.povium.com',
		'secure' => false,
		'httponly' => false
	],


	'pw_hash_options' => [
		'cost' => 13,
		'salt' => md5(openssl_random_pseudo_bytes(16)),
	],


	'regexp' => [
		'name_regexp_base_1' => '/^[\xEA-\xED\x80-\xBF\w\s.]{3,40}$/',
		'name_regexp_base_2' => '/^[\xEA-\xED\x80-\xBFa-zA-Z0-9].*[\xEA-\xED\x80-\xBFa-zA-Z0-9]$/',
		'name_regexp_banned_1' => '/^[\xEA-\xED\x80-\xBF]{3}$/',
		'name_regexp_banned_2' => '/\s{2,}|\.{2,}|_{2,}/',

		'password_regexp' => '/^\S*(?=\S{8,50})(?=\S*[a-z])(?=\S*[A-Z])(?=\S*[0-9])\S*$/'
	],


	'len' => [
		'email_min_length' => 6,
		'email_max_length' => 50,

		'name_min_length' => 3,
		'name_max_length' => 40,

		'password_min_length' => 8,
		'password_max_length' => 50
	],


	//	message
	'msg' => [
		'system_warning' => '알 수 없는 오류입니다.',
		'token_insert_to_db_err' => '자동 로그인 설정 오류',
		'user_insert_to_db_err' => '신규유저 등록 오류',

		'account_incorrect' => '이메일 또는 비밀번호를 다시 확인하세요.',
		'account_inactive' => '비활성화 계정입니다.',
		// 'account_invalid' => '없는 계정입니다.',

		'email_istaken' => '이미 사용중인 이메일입니다.',
		'name_istaken' => '이미 사용중인 이름입니다.',


		'email_short' => '이메일을 6자 이상 입력해주세요.',
		'email_long' => '이메일을 50자 이하로 입력해주세요.',
		'email_invalid' => '유효하지 않은 이메일 형식 입니다.',

		'name_short' => '이름을 3자 이상 입력해주세요. (단, 한글은 최소 2자)',
		'name_long' => '이름을 40자 이하로 입력해주세요. (단, 한글은 최대 13자)',
		'name_single_korean' => '이름이 너무 짧습니다.',
		'name_both_ends_illegal' => '이름의 양끝은 한글, 영어 대소문자 또는 숫자로 입력해주세요.',
		'name_continuous_special_chars' => '띄어쓰기와 특수문자는 연속으로 사용할 수 없습니다.',
		'name_invalid' => '한글, 영어 대소문자, 숫자, . , _ , 띄어쓰기를 조합해 입력해주세요.',

		'password_short' => '비밀번호를 8자 이상 입력해주세요.',
		'password_long' => '비밀번호를 50자 이하로 입력해주세요.',
		'password_invalid' => '8~50자 영어 대소문자, 숫자를 최소 하나씩 사용해주세요.'
	]

];


?>
