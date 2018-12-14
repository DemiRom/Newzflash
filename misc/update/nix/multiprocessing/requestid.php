<?php

require('.do_not_run/require.php');

use newzflash\libraries\Forking;

declare(ticks = 1);

(new Forking())->processWorkType('request_id');
