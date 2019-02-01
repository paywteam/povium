<?php
/**
 * Bootstrap database services.
 *
 * @author		H.Chihoon
 * @copyright	2019 Payw
 */

namespace Readigm\Provider;

use Readigm\Base\Database\DBBuilder;
use Readigm\Base\Database\DBConnection;

class DBServiceProvider extends AbstractServiceProvider
{
	/**
	 * {@inheritdoc}
	 */
	public function boot()
	{
		//	Connect to database
		DBConnection::getInstance();

		//	Build a database
		$db_builder = $this->factory->createInstance(DBBuilder::class);
		$db_builder->build(DBBuilder::NOT_BUILD);

		return $db_builder;
	}
}
