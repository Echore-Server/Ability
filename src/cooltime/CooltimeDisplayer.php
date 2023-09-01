<?php

namespace Echore\Ability\cooltime;

use pocketmine\player\Player;

class CooltimeDisplayer implements ICooltimeDisplayer {

	/**
	 * @var (array{0: Cooltime, 1: string})[]
	 */
	protected array $cooltimes;

	protected Player $player;

	protected string $format;

	/**
	 * @param Player $player
	 */
	public function __construct(Player $player) {
		$this->cooltimes = [];
		$this->format = "§r§7%s: %s %s\n";
		$this->player = $player;
	}

	/**
	 * @return string
	 */
	public function getFormat(): string {
		return $this->format;
	}

	/**
	 * @param string $format
	 */
	public function setFormat(string $format): void {
		$this->format = $format;
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
			$stockStatus = $cooltime->getMaxStock() > 1 ? "§e§l{$cooltime->getStock()}§r§8/{$cooltime->getMaxStock()}" : "";
			$status = (!$cooltime->getTimer()->isRunning() ? "§a使用可能" : ($cooltime->getStock() > 0 ? "§e{$remainSeconds}秒" : "§c残り {$remainSeconds}秒"));

			$text .= sprintf($this->format, $label, $stockStatus, $status);
		}

		$this->player->sendPopup(rtrim($text, "\n"));
	}

	/**
	 * @return array{0: Cooltime, 1: string}[]
	 */
	public function getCooltimes(): array {
		return $this->cooltimes;
	}
}
