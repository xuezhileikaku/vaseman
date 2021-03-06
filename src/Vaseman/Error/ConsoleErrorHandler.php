<?php
/**
 * Part of vaseman project. 
 *
 * @copyright  Copyright (C) 2014 {ORGANIZATION}. All rights reserved.
 * @license    GNU General Public License version 2 or later;
 */

namespace Vaseman\Error;

use Windwalker\Core\Error\ErrorHandler;

/**
 * The ConsoleErrorHandler class.
 * 
 * @since  {DEPLOY_VERSION}
 */
class ConsoleErrorHandler extends ErrorHandler
{
	/**
	 * error
	 *
	 * @param int    $code
	 * @param string $message
	 * @param string $file
	 * @param int    $line
	 * @param mixed  $context
	 *
	 * @return  void
	 *
	 * @throws \ErrorException
	 */
	public static function error($code, $message, $file, $line, $context)
	{
		$content = sprintf('%s. File: %s (line: %s)', $message, $file, $line);

		throw new \ErrorException($content, $code, 1, $file, $line);
	}

	/**
	 * registerErrorHandler
	 *
	 * @param bool $restore
	 *
	 * @return void
	 */
	public static function register($restore = true)
	{
		if ($restore)
		{
			static::restore();
		}

		set_error_handler(array(get_called_class(), 'error'));
	}

	/**
	 * restore
	 *
	 * @return  void
	 */
	public static function restore()
	{
		restore_error_handler();
	}
}
