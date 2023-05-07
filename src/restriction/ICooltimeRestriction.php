<?php

namespace Echore\Ability\restriction;

interface ICooltimeRestriction extends IRestriction {

	public function stock(): bool;

	public function tick(): bool;

}
