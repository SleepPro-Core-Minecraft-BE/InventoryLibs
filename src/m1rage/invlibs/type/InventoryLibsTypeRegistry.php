<?php

declare(strict_types=1);

namespace m1rage\invlibs\type;

use m1rage\invlibs\type\util\InventoryLibsTypeBuilders;
use pocketmine\block\VanillaBlocks;
use pocketmine\network\mcpe\protocol\types\inventory\WindowTypes;

final class InventoryLibsTypeRegistry{

	/** @var array<string, InventoryLibsType> */
	private array $types = [];

	/** @var array<int, string> */
	private array $identifiers = [];

	public function __construct(){
		$this->register(InventoryLibsTypeIds::TYPE_CHEST, InventoryLibsTypeBuilders::BLOCK_ACTOR_FIXED()
			->setBlock(VanillaBlocks::CHEST())
			->setSize(27)
			->setBlockActorId("Chest")
		->build());

		$this->register(InventoryLibsTypeIds::TYPE_DOUBLE_CHEST, InventoryLibsTypeBuilders::DOUBLE_PAIRABLE_BLOCK_ACTOR_FIXED()
			->setBlock(VanillaBlocks::CHEST())
			->setSize(54)
			->setBlockActorId("Chest")
			->setAnimationDuration(1)
		->build());

		$this->register(InventoryLibsTypeIds::TYPE_HOPPER, InventoryLibsTypeBuilders::BLOCK_ACTOR_FIXED()
			->setBlock(VanillaBlocks::HOPPER())
			->setSize(5)
			->setBlockActorId("Hopper")
			->setNetworkWindowType(WindowTypes::HOPPER)
		->build());
	}

	public function register(string $identifier, InventoryLibsType $type) : void{
		if(isset($this->types[$identifier])){
			unset($this->identifiers[spl_object_id($this->types[$identifier])], $this->types[$identifier]);
		}

		$this->types[$identifier] = $type;
		$this->identifiers[spl_object_id($type)] = $identifier;
	}

	public function exists(string $identifier) : bool{
		return isset($this->types[$identifier]);
	}

	public function get(string $identifier) : InventoryLibsType{
		return $this->types[$identifier];
	}

	public function getIdentifier(InventoryLibsType $type) : string{
		return $this->identifiers[spl_object_id($type)];
	}

	public function getOrNull(string $identifier) : ?InventoryLibsType{
		return $this->types[$identifier] ?? null;
	}

	/**
	 * @return array<string, InventoryLibsType>
	 */
	public function getAll() : array{
		return $this->types;
	}
}