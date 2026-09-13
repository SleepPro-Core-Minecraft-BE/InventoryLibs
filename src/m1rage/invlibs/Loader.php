<?php

declare(strict_types=1);

namespace m1rage\invlibs;

use m1rage\invlibs\manager\MenuManager;
use pocketmine\plugin\PluginBase;

final class Loader extends PluginBase{

	private MenuManager $manager;

	protected function onEnable() : void{
		$this->manager = new MenuManager($this);
		$this->manager->register();
	}

	public function getManager() : MenuManager{
		return $this->manager;
	}
}
