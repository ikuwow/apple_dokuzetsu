<?php

define('ROOT', dirname(dirname(__FILE__)));
define('DS', DIRECTORY_SEPARATOR);

require ROOT . DS . 'vendor/autoload.php';
$dotenv = new Dotenv\Dotenv(ROOT);
$dotenv->load();
