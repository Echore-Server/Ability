<?php

namespace Echore\Ability;

class Modifier {

	public int|float $absolute;
	public int|float $multiplier;

	/**
	 * @param float|int $absolute
	 * @param float|int $multiplier
	 */
	public function __construct(float|int $absolute, float|int $multiplier) {
		$this->absolute = $absolute;
		$this->multiplier = $multiplier;
	}


}
