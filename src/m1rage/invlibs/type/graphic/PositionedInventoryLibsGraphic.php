<?php

declare(strict_types=1);

namespace m1rage\invlibs\type\graphic;

use pocketmine\math\Vector3;

interface PositionedInventoryLibsGraphic extends InventoryLibsGraphic{

	public function getPosition() : Vector3;
}