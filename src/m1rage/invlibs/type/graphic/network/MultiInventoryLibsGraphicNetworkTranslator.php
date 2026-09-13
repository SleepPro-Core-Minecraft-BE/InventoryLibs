<?php

declare(strict_types=1);

namespace m1rage\invlibs\type\graphic\network;

use m1rage\invlibs\session\InventoryLibsInfo;
use m1rage\invlibs\session\PlayerSession;
use pocketmine\network\mcpe\protocol\ContainerOpenPacket;

final class MultiInventoryLibsGraphicNetworkTranslator implements InventoryLibsGraphicNetworkTranslator{

	/**
	 * @param InventoryLibsGraphicNetworkTranslator[] $translators
	 */
	public function __construct(
		readonly private array $translators
	){}

	public function translate(PlayerSession $session, InventoryLibsInfo $current, ContainerOpenPacket $packet) : void{
		foreach($this->translators as $translator){
			$translator->translate($session, $current, $packet);
		}
	}
}