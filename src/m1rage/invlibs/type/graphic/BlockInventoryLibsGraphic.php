<?php

declare(strict_types=1);

namespace m1rage\invlibs\type\graphic;

use m1rage\invlibs\type\graphic\network\InventoryLibsGraphicNetworkTranslator;
use pocketmine\block\Block;
use pocketmine\inventory\Inventory;
use pocketmine\math\Vector3;
use pocketmine\network\mcpe\convert\TypeConverter;
use pocketmine\network\mcpe\protocol\types\BlockPosition;
use pocketmine\network\mcpe\protocol\UpdateBlockPacket;
use pocketmine\player\Player;

final class BlockInventoryLibsGraphic implements PositionedInventoryLibsGraphic{

	public function __construct(
		readonly private Block $block,
		readonly private Vector3 $position,
		readonly private ?InventoryLibsGraphicNetworkTranslator $network_translator = null,
		readonly private int $animation_duration = 0
	){}

	public function getPosition() : Vector3{
		return $this->position;
	}

	public function send(Player $player, ?string $name) : void{
		$player->getNetworkSession()->sendDataPacket(UpdateBlockPacket::create(BlockPosition::fromVector3($this->position), TypeConverter::getInstance()->getBlockTranslator()->internalIdToNetworkId($this->block->getStateId()), UpdateBlockPacket::FLAG_NETWORK, UpdateBlockPacket::DATA_LAYER_NORMAL));
	}

	public function sendInventory(Player $player, Inventory $inventory) : bool{
		return $player->setCurrentWindow($inventory);
	}

	public function remove(Player $player) : void{
		$network = $player->getNetworkSession();
		foreach($player->getWorld()->createBlockUpdatePackets([$this->position]) as $packet){
			$network->sendDataPacket($packet);
		}
	}

	public function getNetworkTranslator() : ?InventoryLibsGraphicNetworkTranslator{
		return $this->network_translator;
	}

	public function getAnimationDuration() : int{
		return $this->animation_duration;
	}
}
