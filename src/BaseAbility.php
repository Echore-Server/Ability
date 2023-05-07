<?php

namespace Echore\Ability;

use Echore\Ability\cooltime\Cooltime;
use Echore\Ability\restriction\IAbilityRestriction;

abstract class BaseAbility {

	protected Cooltime $cooltime;

	protected ?IAbilityRestriction $restriction;

	protected StatsList $stats;

	protected AbilityTraitList $traits;

	public function __construct() {
		$this->cooltime = $this->getInitialCooltime();
		$this->restriction = null;
		$this->stats = $this->getInitialStats();
		$this->traits = new AbilityTraitList();
	}

	abstract protected function getInitialCooltime(): Cooltime;

	abstract protected function getInitialStats(): StatsList;

	/**
	 * @return StatsList
	 */
	public function getStats(): StatsList {
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
	 * @return IAbilityRestriction|null
	 */
	public function getRestriction(): ?IAbilityRestriction {
		return $this->restriction;
	}

	/**
	 * @param IAbilityRestriction|null $restriction
	 *
	 * @return BaseAbility
	 */
	public function setRestriction(?IAbilityRestriction $restriction): BaseAbility {
		$this->restriction = $restriction;

		return $this;
	}


	/**
	 * @return Cooltime
	 */
	public function getCooltime(): Cooltime {
		return $this->cooltime;
	}

}
