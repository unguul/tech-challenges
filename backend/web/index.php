<?php

declare(strict_types=1);

require __DIR__ . "/../bootstrap.php";

$filename = __DIR__ . preg_replace('#(\?.*)$#', '', $_SERVER['REQUEST_URI']);
if (php_sapi_name() === 'cli-server' && is_file($filename)) {
    return false;
}

require_once 'src/Client/Webapp/app.php';
