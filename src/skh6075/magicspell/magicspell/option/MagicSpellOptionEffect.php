<?php

declare(strict_types=1);

namespace skh6075\magicspell\magicspell\option;

use InvalidArgumentException;
use pocketmine\color\Color;
use pocketmine\data\bedrock\EffectIdMap;
use pocketmine\entity\effect\EffectInstance;
use pocketmine\entity\Entity;
use pocketmine\math\Vector3;
use pocketmine\player\Player;

final class MagicSpellOptionEffect extends MagicSpellOption{

	private const ID = 'id';
	private const DURATION = 'duration';
	private const AMPLIFIER = 'amplifier';
	private const VISIBLE = 'visible';
	private const COLOR = 'color';

	private const SCAN = [
		self::ID,
		self::DURATION,
		self::AMPLIFIER
	];

	/** @var EffectInstance[] */
	private array $effects;

	private EffectIdMap $effectIdMap;

	public function __construct(array $data){
		parent::__construct($data);
		$this->effectIdMap = EffectIdMap::getInstance();
		$effects = [];
		foreach($data as $datum){
			foreach($datum as $effectData){
				foreach(self::SCAN as $value){
					if(!isset($effectData[$value])){
						throw new InvalidArgumentException("[EffectOption] $value ket not founded.");
					}
				}
				$effect = $this->effectIdMap->fromId($effectData[self::ID]);
				if($effect === null){
					throw new InvalidArgumentException("[EffectOption] The {$effectData[self::ID]} Effect could not be found.");
				}
				$duration = (int)$effectData[self::DURATION];
				$amplifier = (int)$effectData[self::AMPLIFIER];
				if(!is_numeric($duration) || !is_numeric($amplifier) || $duration <= 0 || $amplifier <= 0){
					throw new  InvalidArgumentException("[EffectOption] The duration or amplifier value must be greater than 0.");
				}
				$color = $effect->getColor();
				if(isset($effectData[self::COLOR])){
					$colorData = $effectData[self::COLOR];
					if(!is_array($colorData)){
						throw new InvalidArgumentException("[EffectOption] ColorData values must be Arrays.");
					}
					if(count($colorData) < 3){
						throw new InvalidArgumentException("[EffectOption] The ColorData array must be r, g, b. Example: [255, 255, 255]");
					}
					$color = new Color((int)$colorData[0], (int)$colorData[1], (int)$colorData[2]);
				}
				$visible = $effectData[self::VISIBLE] ?? false;
				if(!is_bool($visible)){
					throw new InvalidArgumentException("[EffectOption] The visible value can only be boolean.");
				}
				$effects[] = new EffectInstance(
					effectType: $effect,
					duration: $duration,
					amplifier: $amplifier,
					visible: $visible,
					ambient: false,
					overrideColor: $color
				);
			}
		}
		$this->effects = $effects;
	}

	public function execute(Entity|Vector3|Player|null $object = null) : void{
		if(!$object instanceof Entity){
			throw new InvalidArgumentException("[EffectOption] Effect interactions are only possible with Entity.");
		}
		foreach($this->effects as $effect){
			$object->getEffects()->add($effect);
		}
	}
}