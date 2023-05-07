<?php

namespace Echore\Ability\restriction;

use Echore\Ability\BaseAbility;

interface IAbilityRestriction extends IRestriction {

	public function getAbility(): BaseAbility;

	public function activate(): bool;

}
