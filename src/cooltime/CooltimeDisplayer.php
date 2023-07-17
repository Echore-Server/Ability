<?php

namespace Echore\Ability\cooltime;

use pocketmine\player\Player;

class CooltimeDisplayer {

	/**
	 * @var (array{0: Cooltime, 1: string})[]
	 */
	protected array $cooltimes;

	protected Player $player;

	/**
	 * @param Player $player
	 */
	public function __construct(Player $player) {
		$this->cooltimes = [];

		$this->player = $player;
	}

	public function add(Cooltime $cooltime, string $label): void {
		$this->cooltimes[spl_object_hash($cooltime)] = [$cooltime, $label];
	}

	public function remove(Cooltime $cooltime): void {
		unset($this->cooltimes[spl_object_hash($cooltime)]);
	}

	public function clear(): void {
		$this->cooltimes = [];
	}

	/**
	 * @return Player
	 */
	public function getPlayer(): Player {
		return $this->player;
	}

	public function display(): void {
		$text = "";

		foreach ($this->getCooltimes() as $data) {
			/**
			 * @var array{0: Cooltime, 1: string} $data
			 */
			[$cooltime, $label] = $data;

			$remainSeconds = round($cooltime->getTimer()->getTime() / 20, 1);
			$stockStatus = $cooltime->getMaxStock() > 1 ? "§e§l{$cooltime->getStock()}§r§f/{$cooltime->getMaxStock()} " : "";
			$status = $stockStatus . $cooltime->getTimer()->isRunning() ? "§a使用可能" : "§c残り {$remainSeconds}秒";

			$text .= "§7$label: $status\n";
		}

		$this->player->sendPopup($text);
	}

	/**
	 * @return array{0: Cooltime, 1: string}[]
	 */
	public function getCooltimes(): array {
		return $this->cooltimes;
	}
}
