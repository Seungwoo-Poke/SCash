<?php


namespace SCash\command;

use pocketmine\command\Command;
use pocketmine\command\CommandSender;

use SCash\SCash;

class SetCashCommand extends Command
{
	
	/** @var null|SCash */
	protected $plugin = null;
	
	/** @var string */
	public const PERMISSION = "op";
	
	
	public function __construct (SCash $plugin)
	{
		$this->plugin = $plugin;
		parent::__construct ("캐쉬 설정", "캐쉬 설정 명령어 입니다.");
		$this->setPermission (self::PERMISSION);
	}
	
	public function execute (CommandSender $player, string $label, array $args): bool
	{
		if ($player->hasPermission (self::PERMISSION)) {
			if (isset ($args [0]) and isset ($args [1]) and is_numeric ($args [1])) {
				$this->plugin->setCash ($args [0], $args [1]);
				SCash::message ($player, "§a{$args [0]}님§7의 캐쉬를 §a" . number_format ($args [1]) . "§7 로 설정했습니다.");
				if (($target = $this->plugin->getServer ()->getPlayer ($args [1])) !== null) {
					SCash::message ($target, "관리자 {$player->getName ()}님 께서 당신의 캐쉬를 §a" . number_format ($args [1]) . "§7 로 설정했습니다.");
				}
			} else {
				SCash::message ($player, "/캐쉬 설정 (닉네임) (캐쉬)");
			}
		} else {
			SCash::message ($player, "당신은 이 명령어를 사용할 권한이 없습니다.");
		}
		return true;
	}
	
}