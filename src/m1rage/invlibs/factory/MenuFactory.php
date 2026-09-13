<?php

declare(strict_types=1);

namespace m1rage\invlibs\factory;

use muqsit\invmenu\InvMenu;

final class MenuFactory{

	public function chest(?string $name = null, bool $readonly = false) : InvMenu{
		return $this->create(InvMenu::TYPE_CHEST, $name, $readonly);
	}

	public function doubleChest(?string $name = null, bool $readonly = false) : InvMenu{
		return $this->create(InvMenu::TYPE_DOUBLE_CHEST, $name, $readonly);
	}

	public function hopper(?string $name = null, bool $readonly = false) : InvMenu{
		return $this->create(InvMenu::TYPE_HOPPER, $name, $readonly);
	}

	public function create(string $type, ?string $name = null, bool $readonly = false) : InvMenu{
		$menu = InvMenu::create($type)->setName($name);
		if($readonly){
			$menu->setListener(InvMenu::readonly());
		}
		return $menu;
	}
}
