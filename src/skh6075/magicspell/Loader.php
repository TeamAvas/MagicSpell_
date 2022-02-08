<?php

declare(strict_types=1);

namespace skh6075\magicspell;

use InvalidArgumentException;
use pocketmine\plugin\PluginBase;
use pocketmine\utils\SingletonTrait;

final class Loader extends PluginBase{
	use SingletonTrait;

	public static function getInstance() : Loader{
		if(self::$instance === null){
			throw new InvalidArgumentException('Unable to check MagicSpell loading status.');
		}
		return self::$instance;
	}

	protected function onLoad() : void{
		self::$instance = $this;
	}

	protected function onEnable() : void{
	}
}