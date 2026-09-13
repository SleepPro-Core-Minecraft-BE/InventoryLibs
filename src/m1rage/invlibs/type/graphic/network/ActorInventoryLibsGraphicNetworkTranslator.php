<?php

declare(strict_types=1);

namespace m1rage\invlibs\type\graphic\network;

use m1rage\invlibs\session\InventoryLibsInfo;
use m1rage\invlibs\session\PlayerSession;
use pocketmine\network\mcpe\protocol\ContainerOpenPacket;
use pocketmine\network\mcpe\protocol\types\BlockPosition;

final class ActorInventoryLibsGraphicNetworkTranslator implements InventoryLibsGraphicNetworkTranslator{

	public function __construct(
		readonly private int $actor_runtime_id
	){}

	public function translate(PlayerSession $session, InventoryLibsInfo $current, ContainerOpenPacket $packet) : void{
		$packet->actorUniqueId = $this->actor_runtime_id;
		$packet->blockPosition = new BlockPosition(0, 0, 0);
	}
}