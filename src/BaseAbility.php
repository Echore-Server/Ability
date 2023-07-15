<?php

namespace Echore\Ability;

use Echore\Ability\cooltime\Cooltime;
use Echore\Ability\restriction\IAbilityRestriction;
use Echore\Stargazer\ModifiableValueRegistry;

abstract class BaseAbility {

	protected Cooltime $cooltime;

	protected ModifiableValueRegistry $stats;

	protected AbilityTraitList $traits;

	public function __construct() {
		$this->cooltime = $this->getInitialCooltime();
		$this->stats = $this->getInitialStats();
		$this->traits = new AbilityTraitList();
	}

	abstract protected function getInitialCooltime(): Cooltime;

	abstract protected function getInitialStats(): ModifiableValueRegistry;

	/**
	 * @return ModifiableValueRegistry
	 */
	public function getStats(): ModifiableValueRegistry {
		return $this->stats;
	}

	/**
	 * @return AbilityTraitList
	 */
	public function getTraits(): AbilityTraitList {
		return $this->traits;
	}

	abstract public function getName(): string;

	abstract public function getDescription(): string;

	/**
	 * @return Cooltime
	 */
	public function getCooltime(): Cooltime {
		return $this->cooltime;
	}

}
