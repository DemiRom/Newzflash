<?php

spl_autoload_register(
	function ($className) {
		$paths = [
			NEWZFLASH_WWW . 'pages' . DS,
			NEWZFLASH_WWW . 'pages' . DS . 'admin' . DS,
			NEWZFLASH_WWW . 'pages' . DS . 'install' . DS,
		];

		foreach ($paths as $path) {
			$spec = str_replace('\\', DS, $path . $className . '.php');

			if (file_exists($spec)) {
				require_once $spec;
				break;
			}
		}
	},
	true
);

?>
