<?php
/**
* Validate user's password.
*
* @author		H.Chihoon
* @copyright	2018 DesignAndDevelop
*/

namespace Povium\Security\Validator\UserInfo;

use Povium\Validator\ValidatorInterface;
use ZxcvbnPhp\Zxcvbn;

class PasswordValidator implements ValidatorInterface
{
	/**
	 * @var array
	 */
	protected $config;

	/**
	 * @var Zxcvbn
	 */
	protected $zxcvbn;

	/**
	 * @param array 	$config
	 * @param Zxcvbn	$zxcvbn
	 */
	public function __construct(array $config, Zxcvbn $zxcvbn)
	{
		$this->config = $config;
		$this->zxcvbn = $zxcvbn;
	}

	/**
	 * {@inheritdoc}
	 *
	 * @return array 	Error flag and message
	 */
	public function validate($password)
	{
		$return = array(
			'err' => true,
			'msg' => ''
		);

		if (empty($password)) {
			$return['msg'] = $this->config['msg']['password_empty'];

			return $return;
		}

		if (!preg_match($this->config['regex']['password_regex_base'], $password)) {
			$return['msg'] = $this->config['msg']['password_invalid'];

			return $return;
		}

		if (strlen($password) < (int)$this->config['len']['password_min_length']) {
			$return['msg'] = $this->config['msg']['password_short'];

			return $return;
		}

		if (strlen($password) > (int)$this->config['len']['password_max_length']) {
			$return['msg'] = $this->config['msg']['password_long'];

			return $return;
		}

		if (!preg_match($this->config['regex']['password_regex_required'], $password)) {
			$return['msg'] = $this->config['msg']['password_required_condition'];

			return $return;
		}

		$return['err'] = false;

		return $return;
	}

	/**
	 * Measure password strength.
	 *
	 * @param  string $password
	 *
	 * @return int	Password strength (0 ~ 2)
	 */
	public function getPasswordStrength($password)
	{
		$score = $this->zxcvbn->passwordStrength($password)['score'];

		if ($score <= 1) {			//	weak password
			$strength = 0;
		} else if ($score <= 3) {	//	normal password
			$strength = 1;
		} else {					//	safe password
			$strength = 2;
		}

		return $strength;
	}
}