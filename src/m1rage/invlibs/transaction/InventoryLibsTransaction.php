<?php

declare(strict_types=1);

namespace m1rage\invlibs\transaction;

use pocketmine\inventory\transaction\action\SlotChangeAction;
use pocketmine\inventory\transaction\InventoryTransaction;
use pocketmine\item\Item;
use pocketmine\player\Player;

interface InventoryLibsTransaction{

	public function getPlayer() : Player;

	public function getOut() : Item;

	public function getIn() : Item;

	/**
	 * Returns the item that was clicked / taken out of the inventory.
	 *
	 * @link InventoryLibsTransaction::getOut()
	 * @return Item
	 */
	public function getItemClicked() : Item;

	/**
	 * Returns the item that an item was clicked with / placed in the inventory.
	 *
	 * @link InventoryLibsTransaction::getIn()
	 * @return Item
	 */
	public function getItemClickedWith() : Item;

	public function getAction() : SlotChangeAction;

	public function getTransaction() : InventoryTransaction;

	public function continue() : InventoryLibsTransactionResult;

	public function discard() : InventoryLibsTransactionResult;
}