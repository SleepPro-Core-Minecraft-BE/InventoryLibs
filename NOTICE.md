# Upstream and fork

InventoryLibs is a plugin distribution of Muqsit's InvMenu for SleepPro Public.

- Upstream: https://github.com/Muqsit/InvMenu
- Based on commit: `1035fd9eca0b1ebdd38fc166deb7955406d299e5` (InvMenu 4.7.4).
- Upstream author: Muqsit.
- Fork integration: m1rage / SleepPro.
- License: GNU GPL v3, retained in LICENSE.

The upstream Git history is preserved. The fork classes were moved from
`muqsit\invmenu` to `m1rage\invlibs`; the InvMenu class prefix and type IDs
were renamed to InventoryLibs / inventorylibs. Added plugin bootstrap and a small menu
factory, separate tests and InventoryLibs-specific documentation. No private
SleepPro inventory implementation is included.
