<?php


namespace SCash;

use pocketmine\plugin\PluginBase;
use pocketmine\utils\Config;

use pocketmine\Player;

use SCash\command\{
	AddCashCommand,
	ReduceCashCommand,
	SeeCashCommand,
	SetCashCommand
};

class SCash extends PluginBase
{
	
	private static $instance = null;
	
	public static $prefix = "§l§6[캐쉬]§r§7 ";
	
	public $config, $db;
	
	
	public static function runFunction (): SCash
	{
		return self::$instance;
	}
	
	public function onLoad (): void
	{
		if (self::$instance === null) {
			self::$instance = $this;
		}
		if (!file_exists ($this->getDataFolder ())) {
			@mkdir ($this->getDataFolder ());
		}
		$this->config = new Config ($this->getDataFolder () . "config.yml", Config::YAML);
		$this->db = $this->config->getAll ();
	}
	
	public function onEnable (): void
	{
		$this->getServer ()->getCommandMap ()->registerAll ("avas", [
			new AddCashCommand ($this),
			new ReduceCashCommand ($this),
			new SetCashCommand ($this),
			new SeeCashCommand ($this)
		]);
	}
	
	public function onDisable (): void
	{
		$this->config->setAll ($this->db);
		$this->config->save ();
	}
	
	public function addCash ($player, int $cash = 0): void
	{
		$name = ($player instanceof Player) ? $player->getName () : $player;
		if (!isset ($this->db [$name])) {
			$this->db [$name] = 0;
		}
		$this->db [$name] += $cash;
	}
	
	public function reduceCash ($player, int $cash = 0): void
	{
		$name = ($player instanceof Player) ? $player->getName () : $player;
		if (!isset ($this->db [$name])) {
			$this->db [$name] = 0;
			return;
		}
		$this->db [$name] -= $cash;
		if ($this->db [$name] <= 0) {
			$this->db [$name] = 0;
		}
	}
	
	public function setCash ($player, int $cash = 0): void
	{
		$name = ($player instanceof Player) ? $player->getName () : $player;
		if (!isset ($this->db [$name])) {
			$this->db [$name] = 0;
			return;
		}
		$this->db [$name] = $cash;
		if ($this->db [$name] <= 0) {
			$this->db [$name] = 0;
		}
	}
	
	public function getCash ($player): int
	{
		$name = ($player instanceof Player) ? $player->getName () : $player;
		if (isset ($this->db [$name])) {
			return $this->db [$name];
		}
		return 0;
	}
	
	public static function message ($player, string $msg): void
	{
		$player->sendMessage (self::$prefix . $msg);
	}
}