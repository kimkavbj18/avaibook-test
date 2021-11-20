<?php

/**
 *
 */

include "../vendor/autoload.php";
define('BASE_PATH', realpath( $_SERVER['DOCUMENT_ROOT'] . '/../src/' ));
new AvaiBook\App\Application();
