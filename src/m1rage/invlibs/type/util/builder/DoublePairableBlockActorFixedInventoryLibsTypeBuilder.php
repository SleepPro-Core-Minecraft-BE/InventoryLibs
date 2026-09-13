<?php

declare(strict_types=1);

namespace m1rage\invlibs\type\util\builder;

use LogicException;
use m1rage\invlibs\type\DoublePairableBlockActorFixedInventoryLibsType;
use m1rage\invlibs\type\graphic\network\BlockInventoryLibsGraphicNetworkTranslator;

final class DoublePairableBlockActorFixedInventoryLibsTypeBuilder implements InventoryLibsTypeBuilder{
	use BlockInventoryLibsTypeBuilderTrait;
	use FixedInventoryLibsTypeBuilderTrait;
	use GraphicNetworkTranslatableInventoryLibsTypeBuilderTrait;
	use AnimationDurationInventoryLibsTypeBuilderTrait;

	private ?string $block_actor_id = null;

	public function __construct(){
		$this->addGraphicNetworkTranslator(BlockInventoryLibsGraphicNetworkTranslator::instance());
	}

	public function setBlockActorId(string $block_actor_id) : self{
		$this->block_actor_id = $block_actor_id;
		return $this;
	}

	private function getBlockActorId() : string{
		return $this->block_actor_id ?? throw new LogicException("No block actor ID was specified");
	}

	public function build() : DoublePairableBlockActorFixedInventoryLibsType{
		return new DoublePairableBlockActorFixedInventoryLibsType($this->getBlock(), $this->getSize(), $this->getBlockActorId(), $this->getGraphicNetworkTranslator(), $this->getAnimationDuration());
	}
}