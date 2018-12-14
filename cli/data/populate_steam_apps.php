<?php

require_once dirname(__DIR__, 2) . DIRECTORY_SEPARATOR . 'bootstrap.php';

use newzflash\Steam;

$steam = new Steam();

$steam->populateSteamAppsTable();
