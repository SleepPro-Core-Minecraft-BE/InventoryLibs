<?php

declare(strict_types=1);

namespace m1rage\invlibs\type\graphic\network;

use InvalidArgumentException;
use m1rage\invlibs\session\InventoryLibsInfo;
use m1rage\invlibs\session\PlayerSession;
use m1rage\invlibs\type\graphic\PositionedInventoryLibsGraphic;
use pocketmine\network\mcpe\protocol\ContainerOpenPacket;
use pocketmine\network\mcpe\protocol\types\BlockPosition;

final class BlockInventoryLibsGraphicNetworkTranslator implements InventoryLibsGraphicNetworkTranslator{

	public static function instance() : self{
		static $instance = null;
		return $instance ??= new self();
	}

	private function __construct(){
	}

	public function translate(PlayerSession $session, InventoryLibsInfo $current, ContainerOpenPacket $packet) : void{
		$graphic = $current->graphic;
		$graphic instanceof PositionedInventoryLibsGraphic || throw new InvalidArgumentException("Expected " . PositionedInventoryLibsGraphic::class . ", got " . $graphic::class);
		$pos = $graphic->getPosition();
		$packet->blockPosition = new BlockPosition((int) $pos->x, (int) $pos->y, (int) $pos->z);
	}
}