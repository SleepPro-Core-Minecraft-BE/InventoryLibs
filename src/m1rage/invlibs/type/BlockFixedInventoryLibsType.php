<?php

declare(strict_types=1);

namespace m1rage\invlibs\type;

use m1rage\invlibs\inventory\InventoryLibsInventory;
use m1rage\invlibs\InventoryLibs;
use m1rage\invlibs\type\graphic\BlockInventoryLibsGraphic;
use m1rage\invlibs\type\graphic\InventoryLibsGraphic;
use m1rage\invlibs\type\graphic\network\InventoryLibsGraphicNetworkTranslator;
use m1rage\invlibs\type\util\InventoryLibsTypeHelper;
use pocketmine\block\Block;
use pocketmine\inventory\Inventory;
use pocketmine\player\Player;

final class BlockFixedInventoryLibsType implements FixedInventoryLibsType{

	public function __construct(
		readonly private Block $block,
		readonly private int $size,
		readonly private ?InventoryLibsGraphicNetworkTranslator $network_translator = null
	){}

	public function getSize() : int{
		return $this->size;
	}

	public function createGraphic(InventoryLibs $menu, Player $player) : ?InventoryLibsGraphic{
		$origin = $player->getPosition()->addVector(InventoryLibsTypeHelper::getBehindPositionOffset($player))->floor();
		if(!InventoryLibsTypeHelper::isValidYCoordinate($origin->y)){
			return null;
		}

		return new BlockInventoryLibsGraphic($this->block, $origin, $this->network_translator);
	}

	public function createInventory() : Inventory{
		return new InventoryLibsInventory($this->size);
	}
}