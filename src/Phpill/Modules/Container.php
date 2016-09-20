<?php

/**
 * @author Beck Xu <beck917@gmail.com>
 * @date 2016-09-07
 * @copyright (c) 2009-2016 Phpill Team
 */
namespace Phpill\Modules;
final class Container {
	public function exec()
	{
		$phpill_modules = __DIR__."/../";
		define('MODPATH', str_replace('\\', '/', realpath($phpill_modules)).'/');
	}
}