<?php

declare(strict_types=1);

namespace m1rage\invlibs\type\util\builder;

use m1rage\invlibs\type\BlockFixedInventoryLibsType;
use m1rage\invlibs\type\graphic\network\BlockInventoryLibsGraphicNetworkTranslator;

final class BlockFixedInventoryLibsTypeBuilder implements InventoryLibsTypeBuilder{
	use BlockInventoryLibsTypeBuilderTrait;
	use FixedInventoryLibsTypeBuilderTrait;
	use GraphicNetworkTranslatableInventoryLibsTypeBuilderTrait;

	public function __construct(){
		$this->addGraphicNetworkTranslator(BlockInventoryLibsGraphicNetworkTranslator::instance());
	}

	public function build() : BlockFixedInventoryLibsType{
		return new BlockFixedInventoryLibsType($this->getBlock(), $this->getSize(), $this->getGraphicNetworkTranslator());
	}
}