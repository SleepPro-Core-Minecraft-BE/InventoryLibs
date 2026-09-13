<?php

declare(strict_types=1);

namespace m1rage\invlibs\type\util\builder;

use m1rage\invlibs\type\InventoryLibsType;

interface InventoryLibsTypeBuilder{

	public function build() : InventoryLibsType;
}