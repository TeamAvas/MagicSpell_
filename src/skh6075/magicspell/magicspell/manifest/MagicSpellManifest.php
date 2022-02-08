<?php

declare(strict_types=1);

namespace skh6075\magicspell\magicspell\manifest;

use skh6075\magicspell\magicspell\option\MagicSpellOption;

final class MagicSpellManifest{

	private array $data;

	/** @var MagicSpellOption[] */
	private array $options;

	public function __construct(array|string $manifest){
		if(is_string($manifest)){
			$manifest = json_decode(file_get_contents($manifest), true, 512, JSON_THROW_ON_ERROR);
		}
		$this->data = $manifest;
		if(isset($this->data['options'])){
			foreach($this->data['options'] as $type => $datum){
				$option = MagicSpellOption::convertOption($type, $datum);
				$this->options[$type] = $option;
			}
		}
	}

	public function getName(): string{ return $this->data['name']; }

	public function getOptions(): array{ return $this->options; }
}