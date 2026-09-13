<?php

declare(strict_types=1);

namespace m1rage\invlibs\type\graphic;

use m1rage\invlibs\type\graphic\network\InventoryLibsGraphicNetworkTranslator;
use pocketmine\inventory\Inventory;
use pocketmine\player\Player;

interface InventoryLibsGraphic{

	public function send(Player $player, ?string $name) : void;

	public function sendInventory(Player $player, Inventory $inventory) : bool;

	public function remove(Player $player) : void;

	public function getNetworkTranslator() : ?InventoryLibsGraphicNetworkTranslator;

	/**
	 * Returns a rough duration (in milliseconds) the client
	 * takes to animate the inventory opening and closing.
	 *
	 * @return int
	 */
	public function getAnimationDuration() : int;
}