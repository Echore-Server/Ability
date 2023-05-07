<?php

namespace Echore\Ability;

use pocketmine\utils\EnumTrait;

/**
 * @method static self SUCCEEDED()
 * @method static self FAILED()
 * @method static self FAILED_COOLTIME()
 * @method static self FAILED_RESTRICTION()
 * @method static self FAILED_ALREADY_ACTIVE()
 * @method static self IGNORED()
 */
class ActivateAbilityResult {
	use EnumTrait;

	protected static function setup(): void {
		self::register(new self("succeeded"));
		self::register(new self("failed"));
		self::register(new self("failed_cooltime"));
		self::register(new self("failed_restriction"));
		self::register(new self("failed_already_active"));
		self::register(new self("ignored"));
	}

	public function isFailed(): bool {
		return $this->equals(self::FAILED()) || $this->equals(self::FAILED_RESTRICTION()) || $this->equals(self::FAILED_COOLTIME()) || $this->equals(self::FAILED_ALREADY_ACTIVE());
	}

	public function isSucceeded(): bool {
		return $this->equals(self::SUCCEEDED());
	}
}
