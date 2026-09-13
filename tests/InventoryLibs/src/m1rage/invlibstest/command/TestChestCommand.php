<?php

declare(strict_types=1);

namespace m1rage\invlibstest\command;

use m1rage\invlibs\factory\MenuFactory;
use m1rage\invlibs\InventoryLibs;
use m1rage\invlibs\transaction\DeterministicInventoryLibsTransaction;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\item\VanillaItems;
use pocketmine\player\Player;

final class TestChestCommand extends Command{

	public function __construct(private readonly MenuFactory $factory){
		parent::__construct('invtest', 'Открыть тестовый сундук InventoryLibs', '/invtest');
		$this->setPermission('inventorylibs.test');
	}

	public function execute(CommandSender $sender, string $commandLabel, array $args) : bool{
		if(!$this->testPermission($sender)){
			return true;
		}
		if(!$sender instanceof Player){
			$sender->sendMessage('Используйте /invtest в игре.');
			return true;
		}
		$menu = $this->factory->chest('InventoryLibs — тест', readonly: true);
		$menu->getInventory()->setItem(11, VanillaItems::EMERALD());
		$menu->getInventory()->setItem(13, VanillaItems::DIAMOND());
		$menu->getInventory()->setItem(15, VanillaItems::GOLD_INGOT());
		$menu->setListener(InventoryLibs::readonly(static function(DeterministicInventoryLibsTransaction $transaction) : void{
			$transaction->getPlayer()->sendMessage('§aНажатие по слоту: ' . $transaction->getAction()->getSlot());
		}));
		$menu->send($sender, callback: static function(bool $success) use($sender) : void{
			$sender->sendMessage($success ? '§aТестовый сундук открыт.' : '§cКлиент не открыл тестовый сундук.');
		});
		return true;
	}
}
