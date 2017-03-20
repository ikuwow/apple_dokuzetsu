<?php
date_default_timezone_set('Asia/Tokyo');
require '_constants.php';
require ROOT . DS . 'vendor/autoload.php';
$dotenv = new Dotenv\Dotenv(ROOT);
$dotenv->load();
