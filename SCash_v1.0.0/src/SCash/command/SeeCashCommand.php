<?php


namespace SCash\command;

use pocketmine\command\Command;
use pocketmine\command\CommandSender;

use SCash\SCash;

class SeeCashCommand extends Command
{
	
	/** @var null|SCash */
	protected $plugin = null;
	
	
	public function __construct (SCash $plugin)
	{
		$this->plugin = $plugin;
		parent::__construct ("캐쉬 보기", "캐쉬 보기 명령어 입니다.");
	}
	
	public function execute (CommandSender $player, string $label, array $args): bool
	{
		$name = $args [0] ?? $player->getName ();
		SCash::message ($player, "{$name}님의 캐쉬: §a" . number_format ($this->plugin->getCash ($name)) . "개");
		return true;
	}
}