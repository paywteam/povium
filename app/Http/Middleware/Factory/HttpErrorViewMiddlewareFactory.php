<?php
/**
 * This factory is responsible for creating "HttpErrorViewMiddleware" instance.
 *
 * @author		H.Chihoon
 * @copyright	2019 Payw
 */

namespace Povium\Http\Middleware\Factory;

use Povium\Base\Factory\AbstractChildFactory;
use Povium\Base\Factory\MasterFactory;
use Povium\Http\Controller\Error\HttpErrorViewController;

class HttpErrorViewMiddlewareFactory extends AbstractChildFactory
{
	/**
	 * {@inheritdoc}
	 */
	protected function prepareArgs()
	{
		$factory = new MasterFactory();

		$http_error_view_controller = $factory->createInstance(HttpErrorViewController::class);

		$this->args[] = $http_error_view_controller;
	}
}
