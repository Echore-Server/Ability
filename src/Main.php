<?php

declare(strict_types=1);

namespace Echore\Ability;

use pocketmine\plugin\PluginBase;
use RuntimeException;

class Main extends PluginBase {

	private static ?self $instance = null;

	public static function getInstance(): self {
		if (is_null(self::$instance)) {
			throw new RuntimeException("plugin not loaded");
		}

		return self::$instance;
	}

	protected function onLoad(): void {
		self::$instance = $this;
	}


}
