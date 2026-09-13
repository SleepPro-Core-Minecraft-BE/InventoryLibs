<?php

declare(strict_types=1);

namespace m1rage\invlibs\type\util;

use m1rage\invlibs\type\util\builder\ActorFixedInventoryLibsTypeBuilder;
use m1rage\invlibs\type\util\builder\BlockActorFixedInventoryLibsTypeBuilder;
use m1rage\invlibs\type\util\builder\BlockFixedInventoryLibsTypeBuilder;
use m1rage\invlibs\type\util\builder\DoublePairableBlockActorFixedInventoryLibsTypeBuilder;

final class InventoryLibsTypeBuilders{

	public static function ACTOR_FIXED() : ActorFixedInventoryLibsTypeBuilder{
		return new ActorFixedInventoryLibsTypeBuilder();
	}

	public static function BLOCK_ACTOR_FIXED() : BlockActorFixedInventoryLibsTypeBuilder{
		return new BlockActorFixedInventoryLibsTypeBuilder();
	}

	public static function BLOCK_FIXED() : BlockFixedInventoryLibsTypeBuilder{
		return new BlockFixedInventoryLibsTypeBuilder();
	}

	public static function DOUBLE_PAIRABLE_BLOCK_ACTOR_FIXED() : DoublePairableBlockActorFixedInventoryLibsTypeBuilder{
		return new DoublePairableBlockActorFixedInventoryLibsTypeBuilder();
	}
}