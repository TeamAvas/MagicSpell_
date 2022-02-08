<?php

declare(strict_types=1);

namespace skh6075\magicspell\magicspell;

use skh6075\magicspell\magicspell\manifest\MagicSpellManifest;

final class FolderMagicSpell extends MagicSpell{

	private MagicSpellManifest $manifest;

	public function __construct(
		private string $folderName,
		private string $folderPath,
		private string $manifestPath
	){
		$this->manifest = new MagicSpellManifest($this->manifestPath);
	}

	public function getManifest() : MagicSpellManifest{
		return $this->manifest;
	}
}