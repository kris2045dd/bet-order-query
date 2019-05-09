<?php

namespace App\Managers;

class ManagerBase
{

	private static $instances = [];

	/**
		取得實例
	*/
	public static function getInstance($shared = true)
	{
		if (! $shared) {
			return new static();
		}

		$class = get_called_class();
		if (! isset(self::$instances[$class])) {
			self::$instances[$class] = new static();
		}

		return self::$instances[$class];
	}

}
