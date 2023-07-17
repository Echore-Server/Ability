<?php

namespace Echore\Ability;

use Echore\Stargazer\ModifiableValueRegistry;

class StatsTrait {

	protected string $identifier;

	protected ModifiableValueRegistry $stats;

	public function __construct(string $identifier, ModifiableValueRegistry $stats) {
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
	 * @return ModifiableValueRegistry
	 */
	public function getStats(): ModifiableValueRegistry {
		return $this->stats;
	}
}
