<?php

namespace Echore\Ability;

use pocketmine\player\Player;
use pocketmine\scheduler\ClosureTask;
use pocketmine\scheduler\TaskHandler;
use pocketmine\scheduler\TaskScheduler;
use pocketmine\Server;
use RuntimeException;

abstract class Ability {

	protected TaskScheduler $scheduler;

	protected bool $active;

	protected bool $disposed;

	protected Player $player;

	private TaskHandler $schedulerHeartBeater;

	private BaseAbility $base;

	public function __construct(Player $player, BaseAbility $base) {
		$this->base = $base;
		$this->player = $player;
		$this->scheduler = new TaskScheduler();
		$this->schedulerHeartBeater = Main::getInstance()->getScheduler()->scheduleRepeatingTask(
			new ClosureTask(function(): void {
				$this->scheduler->mainThreadHeartbeat(Server::getInstance()->getTick());
			}), 1);
	}

	/**
	 * @return TaskScheduler
	 */
	public function getScheduler(): TaskScheduler {
		return $this->scheduler;
	}

	/**
	 * @return Player
	 */
	public function getPlayer(): Player {
		return $this->player;
	}

	public function activate(): ActivateAbilityResult {
		if ($this->disposed) {
			throw new RuntimeException("cant activate disposed ability");
		}

		if ($this->base->getCooltime()->getStock() <= 0) {
			return ActivateAbilityResult::FAILED_COOLTIME();
		}

		if ($this->active) {
			return ActivateAbilityResult::FAILED_ALREADY_ACTIVE();
		}

		$this->base->getCooltime()->start();
		$result = $this->onActivate();

		if ($result->isFailed()) {
			$this->base->getCooltime()->getTimer()->cancel();
		}

		if ($result->isSucceeded()) {
			$this->base->getCooltime()->setStock($this->base->getCooltime()->getStock() - 1);
		}

		return $result;
	}

	abstract protected function onActivate(): ActivateAbilityResult;

	/**
	 * @return bool
	 */
	public function isActive(): bool {
		return $this->active;
	}

	/**
	 * @return bool
	 */
	public function isDisposed(): bool {
		return $this->disposed;
	}

	/**
	 * @return BaseAbility
	 */
	public function getBase(): BaseAbility {
		return $this->base;
	}


	public function dispose(): void {
		$this->disposed = true;
		$this->scheduler->cancelAllTasks();
		$this->schedulerHeartBeater->cancel();
	}
}
