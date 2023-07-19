<?php

namespace Echore\Ability\timer;

use Closure;
use Echore\Stargazer\ModifiableValue;

class TickTimer {

	protected ModifiableValue $base;

	protected int $time;

	protected bool $ticking;

	protected bool $started;

	/**
	 * @var Closure(int $ticks): void[]
	 */
	protected array $tickHooks;

	/**
	 * @var Closure(): void[]
	 */
	protected array $completeHooks;

	public function __construct(ModifiableValue $base) {
		$this->base = $base;
		$this->time = -1;
		$this->started = false;
		$this->ticking = false;
		$this->tickHooks = [];
		$this->completeHooks = [];
	}

	/**
	 * @param Closure(int $ticks): void $callback
	 *
	 * @return void
	 */
	public function addTickHook(Closure $callback): void {
		$this->tickHooks[spl_object_hash($callback)] = $callback;
	}

	public function addCompleteHook(Closure $callback): void {
		$this->completeHooks[spl_object_hash($callback)] = $callback;
	}

	public function removeCompleteHook(Closure $callback): void {
		unset($this->completeHooks[spl_object_hash($callback)]);
	}

	public function removeTickHook(Closure $callback): void {
		unset($this->tickHooks[spl_object_hash($callback)]);
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

	public function restart(): void {
		if (!$this->started) {
			return;
		}

		$this->resume();

		$this->time = $this->base->getFinalFloored();
	}

	public function tick(int $ticks): void {
		if (!$this->ticking) {
			return;
		}

		$this->time -= $ticks;

		foreach ($this->tickHooks as $hook) {
			($hook)($ticks);
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

	public function stop(): void {
		if (!$this->started) {
			return;
		}

		$this->ticking = false;
	}

	public function resume(): void {
		if (!$this->started) {
			return;
		}

		$this->ticking = true;
	}

}
