<?php
/**
 * Part of joomla330 project. 
 *
 * @copyright  Copyright (C) 2011 - 2014 SMS Taiwan, Inc. All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE
 */

namespace Windwalker\CCK;

use Joomla\DI\Container;
use Joomla\DI\ServiceProviderInterface;

// No direct access
defined('_JEXEC') or die;

/**
 * Class CCKProvider
 *
 * @since 1.0
 */
class CCKProvider implements ServiceProviderInterface
{
	/**
	 * Registers the service provider with a DI container.
	 *
	 * @param   Container $container The DI container.
	 *
	 * @return  Container  Returns itself to support chaining.
	 *
	 * @since   1.0
	 */
	public function register(Container $container)
	{
		$container->share(
			'cck',
			function($container)
			{
				return new CCKEngine(
					$container->get('app'),
					$container->get('event.dispatcher'),
					$container
				);
			}
		);

		\JForm::addFieldPath(__DIR__ . '/Fields');
		\JForm::addFormPath(__DIR__ . '/Resource/Form');
	}
}
 