<?php

namespace Echore\Ability;

class AbilityTraitList {

	/**
	 * @var array<string, StatsList>
	 */
	protected array $traits;

	public function __construct() {
		$this->traits = [];
	}

	public function add(AbilityTrait $trait): void {
		$this->traits[$trait->getIdentifier()] = $trait;
	}

	public function remove(string $identifier): void {
		if ($this->has($identifier)) {
			unset($this->traits[$identifier]);
		}
	}

	public function has(string $identifier): bool {
		return isset($this->traits[$identifier]);
	}

	public function checkAndGet(string $identifier, AbilityTrait &$trait): bool {
		if ($this->has($identifier)) {
			$trait = $this->traits[$identifier];

			return true;
		}

		return false;
	}

	public function get(string $identifier): ?AbilityTrait {
		return $this->traits[$identifier] ?? null;
	}
}
