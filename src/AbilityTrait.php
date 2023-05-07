<?php

namespace Echore\Ability;

class AbilityTrait {

	protected string $identifier;

	protected StatsList $stats;

	public function __construct(string $identifier, StatsList $stats) {
		$this->identifier = $identifier;
		$this->stats = $stats;
	}

	/**
	 * @return string
	 */
	public function getIdentifier(): string {
		return $this->identifier;
	}

	/**
	 * @return StatsList
	 */
	public function getStats(): StatsList {
		return $this->stats;
	}
}
