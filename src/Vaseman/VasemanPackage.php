<?php
/**
 * Part of vaseman project. 
 *
 * @copyright  Copyright (C) 2014 {ORGANIZATION}. All rights reserved.
 * @license    GNU General Public License version 2 or later;
 */

namespace Vaseman;

use Vaseman\Twig\VasemanTwigExtension;
use Windwalker\Console\Console;
use Windwalker\Core\Package\AbstractPackage;
use Windwalker\Event\Dispatcher;
use Windwalker\Ioc;
use Windwalker\Loader\ClassLoader;
use Windwalker\Renderer\Twig\GlobalContainer;

/**
 * The VasemanPackage class.
 * 
 * @since  {DEPLOY_VERSION}
 */
class VasemanPackage extends AbstractPackage
{
	/**
	 * Property name.
	 *
	 * @var  string
	 */
	protected $name = 'vaseman';

	/**
	 * initialise
	 *
	 * @return  void
	 */
	public function initialise()
	{
		// A workaround before Windwalker Loader fix bug
		class_alias('Windwalker\Loader\Loader\VasemanPsr4Loader', 'Windwalker\Loader\Loader\Psr4Loader');

		$loader = new ClassLoader;

		$loader->register();

		$config = Ioc::getConfig();

		if ($config->get('outer_project') || $config->get('mode') == 'test')
		{
			$loader->addPsr4('Vaseman\\', $config->get('project.path.data') . '/src');
		}

		parent::initialise();

		GlobalContainer::addExtension('vaseman', new VasemanTwigExtension);
	}

	/**
	 * registerCommands
	 *
	 * @param Console $console
	 *
	 * @return  void
	 */
	public static function registerCommands(Console $console)
	{
	}

	/**
	 * registerListeners
	 *
	 * @param Dispatcher $dispatcher
	 *
	 * @return  void
	 */
	public function registerListeners(Dispatcher $dispatcher)
	{
		$config = Ioc::getConfig();

		$plugins = $config->get('plugins', array());

		foreach ($plugins as $plugin)
		{
			if (class_exists($plugin) && is_subclass_of($plugin, 'Vaseman\\Plugin\\AbstractPlugin') && $plugin::$isEnabled)
			{
				$dispatcher->addListener(new $plugin);
			}
		}
	}
}
