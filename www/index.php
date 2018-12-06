<?php
###开发可打开调试模式，上线时一定要关闭
define('DEV_DEBUG', true);
define('DEBUG_LOG', true);
###一定必须要定义根目录
define('ROOT_DIR', dirname(__DIR__));
date_default_timezone_set('PRC');
require(ROOT_DIR . '/vendor/autoload.php');

use beacon\Route;

Route::register('home');
Route::register('admin');
Route::register('service');
//添加控制器路由前缀，如果命名空间下面 controller\\类名 不存在 则路由到 zero\\controller\\Zero类名 上
Route::addCtlPrefix('admin', 'zero\\controller\\Zero');
//工具注册
\tool\Tool::register();


Route::run();
