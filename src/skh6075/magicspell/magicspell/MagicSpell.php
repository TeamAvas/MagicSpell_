<?php

declare(strict_types=1);

namespace skh6075\magicspell\magicspell;

use skh6075\magicspell\magicspell\manifest\MagicSpellManifest;

abstract class MagicSpell{
	public const MANIFEST_FILE = 'manifest.json';

	abstract public function getManifest(): MagicSpellManifest;
}