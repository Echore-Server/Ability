<?php

namespace Echore\Ability\cooltime;

use Echore\Ability\timer\TickTimer;
use Echore\Stargazer\ModifiableValue;

class Cooltime {

	protected ModifiableValue $base;

	protected TickTimer $timer;

	protected int $maxStock;

	protected int $stock;

	public function __construct(int $original) {
		$this->base = new ModifiableValue($original);
		$this->timer = new TickTimer($this->base);
		$this->timer->addCompleteHook(function(): void {
			$this->stock++;
			$this->start();
		});
	}

	public function start(): void {
		if ($this->stock >= $this->maxStock) {
			return;
		}

		$this->timer->start();
	}

	/**
	 * @return ModifiableValue
	 */
	public function get(): ModifiableValue {
		return $this->base;
	}

	/**
	 * @return TickTimer
	 */
	public function getTimer(): TickTimer {
		return $this->timer;
	}

	/**
	 * @return int
	 */
	public function getMaxStock(): int {
		return $this->maxStock;
	}

	/**
	 * @param int $maxStock
	 *
	 * @return Cooltime
	 */
	public function setMaxStock(int $maxStock): Cooltime {
		$this->maxStock = $maxStock;

		return $this;
	}

	/**
	 * @return int
	 */
	public function getStock(): int {
		return $this->stock;
	}

	/**
	 * @param int $stock
	 *
	 * @return Cooltime
	 */
	public function setStock(int $stock): Cooltime {
		$this->stock = $stock;

		return $this;
	}

	public function tick(int $ticks): void {
		$this->timer->tick($ticks);
	}
}
