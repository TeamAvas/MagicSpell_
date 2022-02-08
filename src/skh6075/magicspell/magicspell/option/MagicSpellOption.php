<?php

declare(strict_types=1);

namespace skh6075\magicspell\magicspell\option;

use InvalidArgumentException;
use pocketmine\entity\Entity;
use pocketmine\math\Vector3;
use pocketmine\player\Player;

class MagicSpellOption{

	public function __construct(protected array|string $data){}

	public function execute(Player|Vector3|Entity|null $object = null): void{}

	final public static function convertOption(string $option, array $data): MagicSpellOption{
		return match ($option){
			'effects' => new MagicSpellOptionEffect($data),
			default => new InvalidArgumentException()
		};
	}
}