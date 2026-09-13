<?php

declare(strict_types=1);

namespace m1rage\invlibs\type\graphic\network;

use m1rage\invlibs\session\InventoryLibsInfo;
use m1rage\invlibs\session\PlayerSession;
use pocketmine\network\mcpe\protocol\ContainerOpenPacket;

final class WindowTypeInventoryLibsGraphicNetworkTranslator implements InventoryLibsGraphicNetworkTranslator{

	public function __construct(
		readonly private int $window_type
	){}

	public function translate(PlayerSession $session, InventoryLibsInfo $current, ContainerOpenPacket $packet) : void{
		$packet->windowType = $this->window_type;
	}
}