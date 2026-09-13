<?php

declare(strict_types=1);

namespace m1rage\invlibs\session\network\handler;

use Closure;
use m1rage\invlibs\session\network\NetworkStackLatencyEntry;

interface PlayerNetworkHandler{

	public function createNetworkStackLatencyEntry(Closure $then) : NetworkStackLatencyEntry;
}