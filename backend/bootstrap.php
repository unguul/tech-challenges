<?php

define('ROOT_PATH', __DIR__);

if (file_exists(ROOT_PATH . '/vendor/autoload.php') === false) {
    echo "run this command first: composer install";
    exit();
}
require_once ROOT_PATH . '/vendor/autoload.php';

define('PATH_TO_FIXTURES', ROOT_PATH . "/tests/fixtures");
define('PATH_TO_DATA', ROOT_PATH . "/data");