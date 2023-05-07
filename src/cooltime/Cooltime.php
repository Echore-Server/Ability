<?php

namespace Echore\Ability\cooltime;

use Echore\Ability\ModifiableValue;
use Echore\Ability\restriction\ICooltimeRestriction;
use Echore\Ability\timer\TickTimer;

class Cooltime {

	protected ModifiableValue $base;

	protected TickTimer $timer;

	protected int $maxStock;
	protected int $stock;

	protected ?ICooltimeRestriction $restriction;

	public function __construct(int $original) {
		$this->base = new ModifiableValue($original);
		$this->timer = new TickTimer($this->base);
		$this->timer->addCompleteHook(function(): void {
			if (!($this->restriction?->stock() ?? true)) {
				return;
			}

			$this->stock++;
			$this->start();
		});

		$this->restriction = null;
	}

	public function start(): void {
		if ($this->stock >= $this->maxStock) {
			return;
		}

		$this->timer->start();
	}

	/**
	 * @return ICooltimeRestriction|null
	 */
	public function getRestriction(): ?ICooltimeRestriction {
		return $this->restriction;
	}

	/**
	 * @param ICooltimeRestriction|null $restriction
	 *
	 * @return Cooltime
	 */
	public function setRestriction(?ICooltimeRestriction $restriction): Cooltime {
		$this->restriction = $restriction;
		return $this;
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
		if (!($this->restriction?->tick() ?? true)) {
			return;
		}
		$this->timer->tick($ticks);
	}
}
