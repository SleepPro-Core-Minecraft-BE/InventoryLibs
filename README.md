# InventoryLibs

Библиотека виртуальных инвентарей для **SleepPro Public**. Создавайте сундуки,
меню выбора и интерфейсы с обработкой нажатий без размещения блоков в мире.

## Установка

1. Скачайте `InventoryLibs-0.1.1.phar` из [релизов](https://github.com/SleepPro-Core-Minecraft-BE/InventoryLibs/releases).
2. Поместите файл в `plugins/` и перезапустите сервер.
3. В плагине, использующем библиотеку, укажите:

```yaml
depend: [InventoryLibs]
```

Требуется API 5.44.2 или новее. InventoryLibs автоматически регистрирует
обработчики; повторная регистрация в вашем плагине не нужна.

## Создание меню

```php
use m1rage\invlibs\Loader;
use pocketmine\item\VanillaItems;

$library = $this->getServer()->getPluginManager()->getPlugin('InventoryLibs');
if(!$library instanceof Loader || !$library->isEnabled()){
    throw new \LogicException('InventoryLibs не загружен');
}

$factory = $library->getManager()->getFactory();
$menu = $factory->chest('Выбор предмета', readonly: true);
$menu->getInventory()->setItem(13, VanillaItems::DIAMOND());
$menu->send($player);
```

| Метод фабрики | Размер |
| --- | --- |
| `chest()` | 27 слотов |
| `doubleChest()` | 54 слота |
| `hopper()` | 5 слотов |

Все методы принимают название и `readonly`. По умолчанию предметы можно
перемещать; для интерфейсов выбора используйте `readonly: true`.
`create($type, $name, $readonly)` позволяет использовать зарегистрированный тип.

## Обработка нажатий

Меню только для чтения отменяет перемещение предметов, но позволяет обрабатывать
нажатия. Если заменяете обработчик, используйте `InventoryLibs::readonly()`:

```php
use m1rage\invlibs\InventoryLibs;
use m1rage\invlibs\transaction\DeterministicInventoryLibsTransaction;

$menu->setListener(InventoryLibs::readonly(
    static function(DeterministicInventoryLibsTransaction $transaction) : void{
        $player = $transaction->getPlayer();
        $slot = $transaction->getAction()->getSlot();
        $player->sendMessage("Выбран слот $slot");
    }
));
```

Для меню с перемещением предметов обработчик возвращает решение:

```php
use m1rage\invlibs\transaction\InventoryLibsTransaction;
use m1rage\invlibs\transaction\InventoryLibsTransactionResult;

$menu->setListener(static function(InventoryLibsTransaction $transaction) : InventoryLibsTransactionResult{
    return $transaction->getAction()->getSlot() === 0
        ? $transaction->discard()
        : $transaction->continue();
});
```

## Открытие и закрытие

`send()` асинхронный: библиотека синхронизирует открытие с клиентом.
Результат можно проверить callback-функцией:

```php
$menu->send($player, callback: static function(bool $success) use($player) : void{
    if(!$success){
        $player->sendMessage('Не удалось открыть меню');
    }
});
```

```php
use pocketmine\inventory\Inventory;
use pocketmine\player\Player;

$menu->setInventoryCloseListener(static function(Player $player, Inventory $inventory) : void{
    $player->sendMessage('Меню закрыто');
});
```

Для открытия следующего интерфейса из обработчика клика используйте
`$transaction->then(...)`: действие выполнится после завершения транзакции.
Не открывайте новое окно в середине обработки перемещения предметов.

## Тестовый сундук

На сервере с `devtools-mode: true` скопируйте `tests/InventoryLibs` в
`plugins/InventoryLibsTest`. Команда `/invtest` открывает сундук с изумрудом,
алмазом и золотом на защищённой странице: предметы нельзя забрать или положить.
Стрелка переключает на вторую страницу, где перенос предметов разрешён.
При закрытии второй страницы оставленные предметы возвращаются игроку.
Разрешение `inventorylibs.test` по умолчанию выдано операторам.

Тестовый плагин отдельно от библиотеки и не включён в её PHAR.
Он проверяет регистрацию, размеры меню, readonly и запись предметов.
По умолчанию сервер не останавливается; `shutdown-after-test: true` предназначен
только для изолированных автоматических проверок.

## Структура

- `src/m1rage/invlibs/Loader.php` — загрузка библиотеки.
- `manager/MenuManager.php` — регистрация и доступ к фабрике.
- `factory/MenuFactory.php` — создание меню.
- `inventory/`, `transaction/`, `type/`, `session/` — инвентари, транзакции,
  графика и сетевые сессии внутри `src/m1rage/invlibs/`.

Все классы форка используют namespace `m1rage\invlibs`. Основной API:
`InventoryLibs`, `InventoryLibsHandler`, `InventoryLibsTransaction` и
`DeterministicInventoryLibsTransaction`. Старые имена классов не поддерживаются.
Виртуальное меню не сохраняет предметы между перезапусками: постоянное
хранение и обработку переполнения должен реализовать использующий плагин.

## Сборка

```sh
php -d phar.readonly=0 tools/build.php
```

Результат: `build/InventoryLibs-0.1.1.phar`. Существующий файл не перезаписывается;
для повторной сборки передайте новый путь первым аргументом.

## Авторство

Основа — InvMenu 4.7.4 Muqsit. Интеграция для SleepPro — m1rage.
Лицензия GPL-3.0. История исходного проекта сохранена;
подробности в [NOTICE.md](NOTICE.md), полный текст в [LICENSE](LICENSE).
