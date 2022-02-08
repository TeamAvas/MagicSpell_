<?php

declare(strict_types=1);

namespace skh6075\magicspell;

use InvalidArgumentException;
use pocketmine\Server;
use pocketmine\utils\SingletonTrait;
use PrefixedLogger;
use skh6075\magicspell\magicspell\FolderMagicSpell;
use skh6075\magicspell\magicspell\MagicSpell;
use Webmozart\PathUtil\Path;
use function is_dir;
use function mkdir;
use function array_diff;
use function scandir;
use function unlink;
use function file_exists;

final class MagicSpellManager{
	use SingletonTrait;

	private Server $server;

	private PrefixedLogger $logger;

	private array $magicSpell = [];

	public static function getInstance() : MagicSpellManager{
		return self::$instance ??= new self;
	}

	private function __construct(){
		$this->server = Server::getInstance();
		$this->logger = new PrefixedLogger($this->server->getLogger(), 'MagicSpell');

		$folderPath = Path::join($this->server->getDataPath() . "spells");
		if(!is_dir($folderPath)){
			mkdir($folderPath, 0777, true);
		}
		foreach(array_diff(scandir($folderPath), ['.', '..']) as $innerPath){
			$realPath = Path::join($folderPath, $innerPath);
			if(!is_dir($realPath)){
				unlink($realPath);
				$this->logger->debug("Deleted unnecessary file \"{$innerPath}\"");
				continue;
			}
			$manifestPath = Path::join($realPath, MagicSpell::MANIFEST_FILE);
			if(!file_exists($manifestPath)){
				throw new InvalidArgumentException("[Folder: $innerPath] " . MagicSpell::MANIFEST_FILE . " file is required when loading magic spells!");
			}
			$class = new FolderMagicSpell($innerPath, $realPath, $manifestPath);
			$this->magicSpell[$class->getManifest()->getName()] = $class;
		}
	}
}