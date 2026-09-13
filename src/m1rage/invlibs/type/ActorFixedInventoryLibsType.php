<?php

declare(strict_types=1);

namespace m1rage\invlibs\type;

use m1rage\invlibs\inventory\InventoryLibsInventory;
use m1rage\invlibs\InventoryLibs;
use m1rage\invlibs\type\graphic\ActorInventoryLibsGraphic;
use m1rage\invlibs\type\graphic\InventoryLibsGraphic;
use m1rage\invlibs\type\graphic\network\InventoryLibsGraphicNetworkTranslator;
use pocketmine\inventory\Inventory;
use pocketmine\network\mcpe\protocol\types\entity\MetadataProperty;
use pocketmine\player\Player;

final class ActorFixedInventoryLibsType implements FixedInventoryLibsType{

	/**
	 * @param string $actor_identifier
	 * @param int $actor_runtime_identifier
	 * @param array<int, MetadataProperty> $actor_metadata
	 * @param int $size
	 * @param InventoryLibsGraphicNetworkTranslator|null $network_translator
	 */
	public function __construct(
		readonly private string $actor_identifier,
		readonly private int $actor_runtime_identifier,
		readonly private array $actor_metadata,
		readonly private int $size,
		readonly private ?InventoryLibsGraphicNetworkTranslator $network_translator = null
	){}

	public function getSize() : int{
		return $this->size;
	}

	public function createGraphic(InventoryLibs $menu, Player $player) : ?InventoryLibsGraphic{
		return new ActorInventoryLibsGraphic($this->actor_identifier, $this->actor_runtime_identifier, $this->actor_metadata, $this->network_translator);
	}

	public function createInventory() : Inventory{
		return new InventoryLibsInventory($this->size);
	}
}