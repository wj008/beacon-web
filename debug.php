<?php

use beacon\core\Logger;

require(__DIR__ . '/vendor/autoload.php');
Logger::listen();
#启用远程调试，如链接密码是：123456 需要设置链接密码
//Logger::listen(true,'123456');