<?php

namespace Echore\Ability\timer;

use Closure;
use Echore\Stargazer\ModifiableValue;
use pocketmine\utils\ObjectSet;

class TickTimer {

	protected ModifiableValue $base;

	protected int $time;

	protected bool $ticking;

	protected bool $started;

	/**
	 * @var ObjectSet<Closure(int &$ticks): bool>
	 */
	protected ObjectSet $tickHooks;

	/**
	 * @var ObjectSet<Closure(): void>
	 */
	protected ObjectSet $completeHooks;

	/**
	 * @var ObjectSet<Closure(int): void>
	 */
	protected ObjectSet $tickListeners;

	public function __construct(ModifiableValue $base) {
		$this->base = $base;
		$this->time = -1;
		$this->started = false;
		$this->ticking = false;
		$this->tickHooks = new ObjectSet();
		$this->completeHooks = new ObjectSet();
		$this->tickListeners = new ObjectSet();
	}

	/**
	 * @return ObjectSet<Closure(int $ticks): void>
	 */
	public function getTickHooks(): ObjectSet {
		return $this->tickHooks;
	}

	/**
	 * @return ObjectSet<Closure(): void>
	 */
	public function getCompleteHooks(): ObjectSet {
		return $this->completeHooks;
	}

	/**
	 * @return ObjectSet<Closure(int): void>
	 */
	public function getTickListeners(): ObjectSet {
		return $this->tickListeners;
	}

	/**
	 * @return ModifiableValue
	 */
	public function getBase(): ModifiableValue {
		return $this->base;
	}

	/**
	 * @return float|int
	 */
	public function getTime(): float|int {
		return $this->time;
	}

	public function start(): void {
		if ($this->started) {
			return;
		}

		$this->started = true;
		$this->ticking = true;
		$this->time = $this->base->getFinalFloored();
	}

	/**
	 * @return bool
	 */
	public function isRunning(): bool {
		return $this->started;
	}

	/**
	 * @return bool
	 */
	public function isTicking(): bool {
		return $this->ticking;
	}

	public function cancel(): void {
		$this->started = false;
		$this->ticking = false;
		$this->time = -1;
	}

	public function tickTo(int $remainTime): int {
		$ticks = $this->time - $remainTime;

		$this->tick($ticks);

		return $ticks;
	}

	public function tick(int $ticks): void {
		if (!$this->ticking) {
			return;
		}

		foreach ($this->tickHooks as $hook) {
			if (!($hook)($ticks)) {
				return;
			}
		}

		$this->time -= $ticks;

		foreach ($this->tickListeners as $listener) {
			($listener)($ticks);
		}

		if ($this->time <= 0) {
			$this->complete();
		}
	}

	public function complete(): void {
		if (!$this->started) {
			return;
		}

		$this->started = false;
		$this->ticking = false;
		$this->time = -1;

		foreach ($this->completeHooks as $hook) {
			($hook)();
		}
	}

	public function restart(): void {
		if (!$this->started) {
			return;
		}

		$this->resume();

		$this->time = $this->base->getFinalFloored();
	}

	public function resume(): void {
		if (!$this->started) {
			return;
		}

		$this->ticking = true;
	}

	public function stop(): void {
		if (!$this->started) {
			return;
		}

		$this->ticking = false;
	}

}
