<?php

use beacon\core\App;

date_default_timezone_set('PRC');
###开发可打开调试模式，上线时一定要关闭
define('DEV_DEBUG', false);
#调试日志输出
define('DEBUG_LOG', true);
#调试sql语句输出
define('DEBUG_MYSQL_LOG', true);
#使用 REDIS_SESSION
define('USE_REDIS_SESSION', false);
#定义根目录
define('ROOT_DIR', dirname(__DIR__));

require(ROOT_DIR . '/vendor/autoload.php');
#定义路由
App::route('home', '/');
App::route('admin', '/admin');
App::route('service', '/service');
App::route('tool', '/tool', 'tool');
App::run();


