<?php

declare(strict_types=1);

namespace m1rage\invlibs\type;

use m1rage\invlibs\InventoryLibs;
use m1rage\invlibs\type\graphic\InventoryLibsGraphic;
use pocketmine\inventory\Inventory;
use pocketmine\player\Player;

interface InventoryLibsType{

	public function createGraphic(InventoryLibs $menu, Player $player) : ?InventoryLibsGraphic;

	public function createInventory() : Inventory;
}