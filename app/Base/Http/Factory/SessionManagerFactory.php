<?php
/**
* This factory is responsible for creating "SessionManager" instance.
*
* @author		H.Chihoon
* @copyright	2019 Payw
*/

namespace Povium\Base\Http\Factory;

use Povium\Base\Factory\AbstractChildFactory;
use Povium\Base\Factory\MasterFactory;
use Povium\Base\Database\DBConnection;
use Povium\Base\Http\Session\PDOSessionHandler;

class SessionManagerFactory extends AbstractChildFactory
{
	/**
	 * {@inheritdoc}
	 */
	protected function prepareArgs()
	{
		$factory = new MasterFactory();

		$conn = DBConnection::getInstance()->getConn();
		$session_handler = $factory->createInstance(PDOSessionHandler::class);
		$config = $this->configLoader->load('session');

		$this->args[] = $conn;
		$this->args[] = $session_handler;
		$this->args[] = $config;
	}
}
