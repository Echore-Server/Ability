<?php

namespace Echore\Ability;

class StatsTraitList {

	/**
	 * @var array<string, StatsTrait>
	 */
	protected array $traits;

	public function __construct() {
		$this->traits = [];
	}

	public function add(StatsTrait $trait): void {
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

	public function checkAndGet(string $identifier, StatsTrait &$trait): bool {
		if ($this->has($identifier)) {
			$trait = $this->traits[$identifier];

			return true;
		}

		return false;
	}

	public function get(string $identifier): ?StatsTrait {
		return $this->traits[$identifier] ?? null;
	}
}
