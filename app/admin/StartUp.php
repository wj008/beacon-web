<?php

namespace app\admin;

use beacon\core\App;
use \beacon\core\Config;
use beacon\core\RouteError;

class StartUp
{
    public static function init()
    {
        Config::append([
            'webname' => 'BeaconPHP',
            'sdopx.template_dir' => ['/app/admin/view', '/app/admin/zero/view'],
        ]);
    }

    /**
     * 接管应用执行
     * @param array $data
     * @throws \ReflectionException
     * @throws \beacon\core\RouteError
     */
    public static function execute(array $data)
    {
        $classFullName = $data['classFullName'];
        if (!class_exists($classFullName)) {
            $data['className'] = 'Zero' . $data['className'];
            $classFullName = $data['namespace'] . '\\zero\\controller\\' . $data['className'];
            if (!class_exists($classFullName)) {
                throw new RouteError('没有找到控制器信息' . $data['className']);
            }
        }
        App::executeMethod($classFullName, $data['method']);
    }
}

