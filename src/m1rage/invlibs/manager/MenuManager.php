<?php

declare(strict_types=1);

namespace m1rage\invlibs\manager;

use m1rage\invlibs\factory\MenuFactory;
use m1rage\invlibs\InventoryLibsHandler;
use pocketmine\plugin\Plugin;

final class MenuManager{

	private readonly MenuFactory $factory;

	public function __construct(private readonly Plugin $plugin){
		$this->factory = new MenuFactory();
	}

	public function register() : void{
		if(!InventoryLibsHandler::isRegistered()){
			InventoryLibsHandler::register($this->plugin);
		}
	}

	public function getFactory() : MenuFactory{
		return $this->factory;
	}
}
