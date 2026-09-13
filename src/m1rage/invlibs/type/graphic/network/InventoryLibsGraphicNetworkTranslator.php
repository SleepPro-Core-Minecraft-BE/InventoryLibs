<?php

declare(strict_types=1);

namespace m1rage\invlibs\type\graphic\network;

use m1rage\invlibs\session\InventoryLibsInfo;
use m1rage\invlibs\session\PlayerSession;
use pocketmine\network\mcpe\protocol\ContainerOpenPacket;

interface InventoryLibsGraphicNetworkTranslator{

	public function translate(PlayerSession $session, InventoryLibsInfo $current, ContainerOpenPacket $packet) : void;
}