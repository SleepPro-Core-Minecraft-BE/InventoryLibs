<?php

declare(strict_types=1);

namespace m1rage\invlibstest\manager;

use m1rage\invlibs\factory\MenuFactory;
use m1rage\invlibs\transaction\InventoryLibsTransaction;
use m1rage\invlibs\transaction\InventoryLibsTransactionResult;
use pocketmine\inventory\Inventory;
use pocketmine\item\VanillaItems;
use pocketmine\player\Player;

final class PageManager{

	private const NAVIGATION_SLOT = 26;

	public function __construct(private readonly MenuFactory $factory){}

	public function open(Player $player, int $page = 1) : void{
		$menu = $this->factory->chest($page === 1 ? 'Страница 1/2 — защищённая' : 'Страница 2/2 — перенос предметов');
		if($page === 1){
			$menu->getInventory()->setItem(11, VanillaItems::EMERALD());
			$menu->getInventory()->setItem(13, VanillaItems::DIAMOND());
			$menu->getInventory()->setItem(15, VanillaItems::GOLD_INGOT());
		}
		$menu->getInventory()->setItem(self::NAVIGATION_SLOT, VanillaItems::ARROW()->setCustomName($page === 1 ? '§aСледующая страница' : '§eПредыдущая страница'));
		$menu->setListener(function(InventoryLibsTransaction $transaction) use($page) : InventoryLibsTransactionResult{
			if($transaction->getAction()->getSlot() === self::NAVIGATION_SLOT){
				return $transaction->discard()->then(function(Player $player) use($page) : void{
					$this->open($player, $page === 1 ? 2 : 1);
				});
			}
			return $page === 1 ? $transaction->discard() : $transaction->continue();
		});
		if($page === 2){
			$menu->setInventoryCloseListener(static function(Player $player, Inventory $inventory) : void{
				$contents = $inventory->getContents();
				unset($contents[self::NAVIGATION_SLOT]);
				$inventory->clearAll();
				foreach($contents as $item){
					foreach($player->getInventory()->addItem($item) as $overflow){
						$player->getWorld()->dropItem($player->getPosition(), $overflow);
					}
				}
			});
		}
		$menu->send($player, callback: static function(bool $success) use($player) : void{
			if(!$success) $player->sendMessage('§cКлиент не открыл сундук.');
		});
	}
}
