<?php

declare(strict_types=1);

namespace m1rage\invlibs;

use Closure;
use LogicException;
use m1rage\invlibs\inventory\SharedInventoryLibsSynchronizer;
use m1rage\invlibs\session\InventoryLibsInfo;
use m1rage\invlibs\session\PlayerWindowDispatcher;
use m1rage\invlibs\transaction\DeterministicInventoryLibsTransaction;
use m1rage\invlibs\transaction\InventoryLibsTransaction;
use m1rage\invlibs\transaction\InventoryLibsTransactionResult;
use m1rage\invlibs\transaction\SimpleInventoryLibsTransaction;
use m1rage\invlibs\type\InventoryLibsType;
use m1rage\invlibs\type\InventoryLibsTypeIds;
use pocketmine\inventory\Inventory;
use pocketmine\inventory\transaction\action\SlotChangeAction;
use pocketmine\inventory\transaction\InventoryTransaction;
use pocketmine\item\Item;
use pocketmine\player\Player;

class InventoryLibs implements InventoryLibsTypeIds{

	/**
	 * @param string $identifier
	 * @param mixed ...$args
	 * @return InventoryLibs
	 */
	public static function create(string $identifier, ...$args) : InventoryLibs{
		return new InventoryLibs(InventoryLibsHandler::getTypeRegistry()->get($identifier), ...$args);
	}

	/**
	 * @param (Closure(DeterministicInventoryLibsTransaction) : void)|null $listener
	 * @return Closure(InventoryLibsTransaction) : InventoryLibsTransactionResult
	 */
	public static function readonly(?Closure $listener = null) : Closure{
		return static function(InventoryLibsTransaction $transaction) use($listener) : InventoryLibsTransactionResult{
			$result = $transaction->discard();
			if($listener !== null){
				$listener(new DeterministicInventoryLibsTransaction($transaction, $result));
			}
			return $result;
		};
	}

	readonly public InventoryLibsType $type;
	protected ?string $name = null;
	protected ?Closure $listener = null;
	protected ?Closure $inventory_close_listener = null;
	protected Inventory $inventory;
	protected ?SharedInventoryLibsSynchronizer $synchronizer = null;

	public function __construct(InventoryLibsType $type, ?Inventory $custom_inventory = null){
		InventoryLibsHandler::isRegistered() || throw new LogicException("Tried creating menu before calling " . InventoryLibsHandler::class . "::register()");
		$this->type = $type;
		$this->inventory = $this->type->createInventory();
		$this->setInventory($custom_inventory);
	}

	public function __destruct(){
		$this->setInventory(null);
	}

	public function getName() : ?string{
		return $this->name;
	}

	public function setName(?string $name) : self{
		$this->name = $name;
		return $this;
	}

	/**
	 * @return (Closure(InventoryLibsTransaction) : InventoryLibsTransactionResult)|null
	 */
	public function getListener() : ?Closure{
		return $this->listener;
	}

	/**
	 * @param (Closure(InventoryLibsTransaction) : InventoryLibsTransactionResult)|null $listener
	 * @return self
	 */
	public function setListener(?Closure $listener) : self{
		$this->listener = $listener;
		return $this;
	}

	/**
	 * @return (Closure(Player, Inventory) : void)|null
	 */
	public function getInventoryCloseListener() : ?Closure{
		return $this->inventory_close_listener;
	}

	/**
	 * @param (Closure(Player, Inventory) : void)|null $listener
	 * @return self
	 */
	public function setInventoryCloseListener(?Closure $listener) : self{
		$this->inventory_close_listener = $listener;
		return $this;
	}

	public function getInventory() : Inventory{
		return $this->inventory;
	}

	public function setInventory(?Inventory $custom_inventory) : void{
		if($this->synchronizer !== null){
			$this->synchronizer->destroy();
			$this->synchronizer = null;
		}

		if($custom_inventory !== null){
			$this->synchronizer = new SharedInventoryLibsSynchronizer($this, $custom_inventory);
		}
	}

	/**
	 * @param Player $player
	 * @param string|null $name
	 * @param (Closure(bool) : void)|null $callback
	 */
	final public function send(Player $player, ?string $name = null, ?Closure $callback = null) : void{
		$player->removeCurrentWindow();

		$session = InventoryLibsHandler::getPlayerManager()->get($player);
		if($session->dispatcher !== null){
			$session->dispatcher->then($this, $name, $callback);
			return;
		}

		$graphic = $this->type->createGraphic($this, $player);
		if($graphic === null){
			if($callback !== null){
				$callback(false);
			}
			return;
		}

		$session->dispatcher = new PlayerWindowDispatcher($session, new InventoryLibsInfo($this, $graphic, $name));
		if($callback !== null){
			$session->dispatcher->addCallback($callback);
		}
	}

	public function handleInventoryTransaction(Player $player, Item $out, Item $in, SlotChangeAction $action, InventoryTransaction $transaction) : InventoryLibsTransactionResult{
		$inv_menu_txn = new SimpleInventoryLibsTransaction($player, $out, $in, $action, $transaction);
		return $this->listener !== null ? ($this->listener)($inv_menu_txn) : $inv_menu_txn->continue();
	}

	public function onClose(Player $player) : void{
		if($this->inventory_close_listener !== null){
			($this->inventory_close_listener)($player, $this->getInventory());
		}
	}
}
