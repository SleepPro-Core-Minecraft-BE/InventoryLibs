<?php

declare(strict_types=1);

namespace m1rage\invlibs\type\util\builder;

use m1rage\invlibs\type\graphic\network\InventoryLibsGraphicNetworkTranslator;
use m1rage\invlibs\type\graphic\network\MultiInventoryLibsGraphicNetworkTranslator;
use m1rage\invlibs\type\graphic\network\WindowTypeInventoryLibsGraphicNetworkTranslator;

trait GraphicNetworkTranslatableInventoryLibsTypeBuilderTrait{

	/** @var InventoryLibsGraphicNetworkTranslator[] */
	private array $graphic_network_translators = [];

	public function addGraphicNetworkTranslator(InventoryLibsGraphicNetworkTranslator $translator) : self{
		$this->graphic_network_translators[] = $translator;
		return $this;
	}

	public function setNetworkWindowType(int $window_type) : self{
		$this->addGraphicNetworkTranslator(new WindowTypeInventoryLibsGraphicNetworkTranslator($window_type));
		return $this;
	}

	protected function getGraphicNetworkTranslator() : ?InventoryLibsGraphicNetworkTranslator{
		if(count($this->graphic_network_translators) === 0){
			return null;
		}

		if(count($this->graphic_network_translators) === 1){
			return $this->graphic_network_translators[array_key_first($this->graphic_network_translators)];
		}

		return new MultiInventoryLibsGraphicNetworkTranslator($this->graphic_network_translators);
	}
}