<?php

declare(strict_types=1);

namespace m1rage\invlibs\factory;

use m1rage\invlibs\InventoryLibs;

final class MenuFactory{

	public function chest(?string $name = null, bool $readonly = false) : InventoryLibs{
		return $this->create(InventoryLibs::TYPE_CHEST, $name, $readonly);
	}

	public function doubleChest(?string $name = null, bool $readonly = false) : InventoryLibs{
		return $this->create(InventoryLibs::TYPE_DOUBLE_CHEST, $name, $readonly);
	}

	public function hopper(?string $name = null, bool $readonly = false) : InventoryLibs{
		return $this->create(InventoryLibs::TYPE_HOPPER, $name, $readonly);
	}

	public function create(string $type, ?string $name = null, bool $readonly = false) : InventoryLibs{
		$menu = InventoryLibs::create($type)->setName($name);
		if($readonly){
			$menu->setListener(InventoryLibs::readonly());
		}
		return $menu;
	}
}
