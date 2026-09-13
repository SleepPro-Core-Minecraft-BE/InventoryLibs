<?php

declare(strict_types=1);

namespace m1rage\invlibs\session;

use m1rage\invlibs\InventoryLibs;
use m1rage\invlibs\type\graphic\InventoryLibsGraphic;

final class InventoryLibsInfo{

	public function __construct(
		readonly public InventoryLibs $menu,
		readonly public InventoryLibsGraphic $graphic,
		readonly public ?string $graphic_name
	){}
}