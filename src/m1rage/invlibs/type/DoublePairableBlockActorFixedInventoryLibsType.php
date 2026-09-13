<?php

declare(strict_types=1);

namespace m1rage\invlibs\type;

use m1rage\invlibs\inventory\InventoryLibsInventory;
use m1rage\invlibs\InventoryLibs;
use m1rage\invlibs\type\graphic\BlockActorInventoryLibsGraphic;
use m1rage\invlibs\type\graphic\BlockInventoryLibsGraphic;
use m1rage\invlibs\type\graphic\InventoryLibsGraphic;
use m1rage\invlibs\type\graphic\MultiBlockInventoryLibsGraphic;
use m1rage\invlibs\type\graphic\network\InventoryLibsGraphicNetworkTranslator;
use m1rage\invlibs\type\util\InventoryLibsTypeHelper;
use pocketmine\block\Block;
use pocketmine\block\tile\Chest;
use pocketmine\block\VanillaBlocks;
use pocketmine\inventory\Inventory;
use pocketmine\math\Facing;
use pocketmine\player\Player;

final class DoublePairableBlockActorFixedInventoryLibsType implements FixedInventoryLibsType{

	public function __construct(
		readonly private Block $block,
		readonly private int $size,
		readonly private string $tile_id,
		readonly private ?InventoryLibsGraphicNetworkTranslator $network_translator = null,
		readonly private int $animation_duration = 0
	){}

	public function getSize() : int{
		return $this->size;
	}

	public function createGraphic(InventoryLibs $menu, Player $player) : ?InventoryLibsGraphic{
		$position = $player->getPosition();
		$origin = $position->addVector(InventoryLibsTypeHelper::getBehindPositionOffset($player))->floor();
		if(!InventoryLibsTypeHelper::isValidYCoordinate($origin->y)){
			return null;
		}

		$graphics = [];
		$menu_name = $menu->getName();
		$world = $position->getWorld();
		foreach([
			[$origin, $origin->east(), [Facing::NORTH, Facing::SOUTH, Facing::WEST]],
			[$origin->east(), $origin, [Facing::NORTH, Facing::SOUTH, Facing::EAST]]
		] as [$origin_pos, $pair_pos, $connected_sides]){
			$graphics[] = new BlockActorInventoryLibsGraphic(
				$this->block,
				$origin_pos,
				BlockActorInventoryLibsGraphic::createTile($this->tile_id, $menu_name)
					->setInt(Chest::TAG_PAIRX, $pair_pos->x)
					->setInt(Chest::TAG_PAIRZ, $pair_pos->z),
				$this->network_translator,
				$this->animation_duration
			);
			foreach(InventoryLibsTypeHelper::findConnectedBlocks("Chest", $world, $origin_pos, $connected_sides) as $side){
				$graphics[] = new BlockInventoryLibsGraphic(VanillaBlocks::BARRIER(), $side);
			}
		}

		return count($graphics) > 1 ? new MultiBlockInventoryLibsGraphic($graphics) : $graphics[0];
	}

	public function createInventory() : Inventory{
		return new InventoryLibsInventory($this->size);
	}
}