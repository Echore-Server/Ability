<?php

namespace Echore\Ability;

class ModifiableValue {

	protected int|float $original;

	protected int|float $value;


	/**
	 * @var Modifier[]
	 */
	protected array $modifiers;

	protected int|float $finalValue;

	protected bool $dirty;

	public function __construct(int|float $original) {
		$this->value = $original;
		$this->original = $original;
	}

	public function apply(Modifier $modifier): void {
		$this->modifiers[spl_object_hash($modifier)] = $modifier;
		$this->dirty = true;
	}

	public function remove(Modifier $modifier): void {
		unset($this->modifiers[spl_object_hash($modifier)]);
		$this->dirty = true;
	}

	public function getFinalFloored(): int {
		return (int) floor($this->getFinal());
	}

	public function getFinal(): float {
		if ($this->dirty) {
			$v = $this->value;
			$finalAbsolute = 0;
			foreach ($this->modifiers as $modifier) {
				$v *= $modifier->multiplier;
				$finalAbsolute += $modifier->absolute;
			}

			$this->finalValue = $v + $finalAbsolute;
			$this->dirty = false;
		}

		return $this->finalValue;
	}

	/**
	 * @return float|int
	 */
	public function getOriginal(): float|int {
		return $this->original;
	}

	/**
	 * @return float|int
	 */
	public function getValue(): float|int {
		return $this->value;
	}

	/**
	 * @param float|int $value
	 */
	public function setValue(float|int $value): void {
		$this->value = $value;
		$this->dirty = true;
	}

}
