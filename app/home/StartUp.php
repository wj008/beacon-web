<?php

namespace app\home;

use \beacon\core\Config;


class StartUp
{
    public static function init()
    {
        Config::append([
            'sdopx.template_dir' => ['/app/home/view'],
        ]);
    }
}
