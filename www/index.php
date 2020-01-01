<?php
###开发可打开调试模式，上线时一定要关闭
define('DEV_DEBUG', true);
#调试日志输出
define('DEBUG_LOG', true);
#调试sql语句输出
define('DEBUG_MYSQL_LOG', false);

###一定必须要定义根目录
define('ROOT_DIR', dirname(__DIR__));
#设置时区
date_default_timezone_set('PRC');
require(ROOT_DIR . '/vendor/autoload.php');

use beacon\Route;

#注册路由
Route::register('home');
Route::register('admin');
Route::register('service');

#接管路由目标映射
Route::adapter(Route::ADAPTER_TARGET, function ($data) {
    if (class_exists($data['classFullName'])) {
        return $data;
    }
    #接管路由指向,不存在的指向zero文件夹中对应的 Zero控制器
    $classFullName = $data['namespace'] . '\\zero\\controller\\Zero' . $data['className'];
    if (class_exists($classFullName)) {
        $data['classFullName'] = $classFullName;
        return $data;
    }
    return $data;
});

#脚手架工具注册
if (class_exists('\tool\Tool')) {
    \tool\Tool::register();
}

Route::run();
