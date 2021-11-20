<?php

/**
 * Se carga el fichero autoload del composer para cargar las librerías en vendor y se define el path del proyecto
 */

include "../vendor/autoload.php";
define('BASE_PATH', realpath( $_SERVER['DOCUMENT_ROOT'] . '/../src/' ));
new AvaiBook\App\Application();
