<?php

declare(strict_types=1);

namespace m1rage\invlibs;

use InvalidArgumentException;
use LogicException;
use m1rage\invlibs\session\PlayerManager;
use m1rage\invlibs\type\InventoryLibsTypeRegistry;
use pocketmine\plugin\Plugin;
use pocketmine\Server;

final class InventoryLibsHandler{

	private static ?Plugin $registrant = null;
	private static InventoryLibsTypeRegistry $type_registry;
	private static PlayerManager $player_manager;

	public static function register(Plugin $plugin) : void{
		!self::isRegistered() || throw new InvalidArgumentException("{$plugin->getName()} attempted to register " . self::class . " twice.");
		self::$registrant = $plugin;
		self::$type_registry = new InventoryLibsTypeRegistry();
		self::$player_manager = new PlayerManager(self::getRegistrant());
		Server::getInstance()->getPluginManager()->registerEvents(new InventoryLibsEventHandler(self::getPlayerManager()), $plugin);
	}

	public static function isRegistered() : bool{
		return self::$registrant instanceof Plugin;
	}

	public static function getRegistrant() : Plugin{
		return self::$registrant ?? throw new LogicException("Cannot obtain registrant before registration");
	}

	public static function getTypeRegistry() : InventoryLibsTypeRegistry{
		return self::$type_registry;
	}

	public static function getPlayerManager() : PlayerManager{
		return self::$player_manager;
	}
}