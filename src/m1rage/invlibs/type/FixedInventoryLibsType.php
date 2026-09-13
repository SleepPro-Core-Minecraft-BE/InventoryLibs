<?php

declare(strict_types=1);

namespace m1rage\invlibs\type;

/**
 * An InventoryLibsType with a fixed inventory size.
 */
interface FixedInventoryLibsType extends InventoryLibsType{

	/**
	 * Returns size (number of slots) of the inventory.
	 *
	 * @return int
	 */
	public function getSize() : int;
}