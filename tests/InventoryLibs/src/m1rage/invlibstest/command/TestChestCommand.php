<?php

declare(strict_types=1);

namespace m1rage\invlibstest\command;

use m1rage\invlibs\factory\MenuFactory;
use m1rage\invlibstest\manager\PageManager;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
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
		(new PageManager($this->factory))->open($sender);
		return true;
	}
}
