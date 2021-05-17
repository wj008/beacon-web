<?php

use beacon\core\Logger;

require(__DIR__ . '/vendor/autoload.php');

Logger::client('127.0.0.1', 1024, '123456');