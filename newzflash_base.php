<?php

if ($argc > 1) {
	$constant = $argv[1];
	require_once 'newzflash/constants.php';

	if (defined($constant)) {
		exit(constant($constant));
	}
}

exit(dirname(__FILE__));

?>
