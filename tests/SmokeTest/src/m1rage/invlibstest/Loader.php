<?php

declare(strict_types=1);

namespace m1rage\invlibstest;

use m1rage\invlibs\Loader as LibraryLoader;
use muqsit\invmenu\InvMenuHandler;
use pocketmine\item\VanillaItems;
use pocketmine\plugin\PluginBase;
use pocketmine\scheduler\ClosureTask;
use RuntimeException;

final class Loader extends PluginBase{

	protected function onEnable() : void{
		$plugin = $this->getServer()->getPluginManager()->getPlugin('InventoryLibs');
		if(!$plugin instanceof LibraryLoader || !InvMenuHandler::isRegistered()){
			throw new RuntimeException('InventoryLibs registration failed');
		}
		$factory = $plugin->getManager()->getFactory();
		foreach([
			[$factory->chest('Test chest', true), 27],
			[$factory->doubleChest('Test double chest', true), 54],
			[$factory->hopper('Test hopper', true), 5],
		] as [$menu, $size]){
			if($menu->getInventory()->getSize() !== $size || $menu->getListener() === null){
				throw new RuntimeException('Menu factory test failed');
			}
			$item = VanillaItems::DIAMOND();
			$menu->getInventory()->setItem(0, $item);
			if(!$menu->getInventory()->getItem(0)->equals($item)){
				throw new RuntimeException('Menu inventory roundtrip failed');
			}
		}
		if($factory->chest()->getListener() !== null){
			throw new RuntimeException('Writable menu unexpectedly readonly');
		}
		$this->getLogger()->info('INVENTORYLIBS_SMOKE_OK: registration, 27/54/5 slots, readonly and item roundtrip');
		$this->getScheduler()->scheduleDelayedTask(new ClosureTask(fn() => $this->getServer()->shutdown()), 10);
	}
}
