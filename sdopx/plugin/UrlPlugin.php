<?php
/**
 * Created by PhpStorm.
 * User: wj008
 * Date: 2018/1/2
 * Time: 21:40
 */

namespace sdopx\plugin;


use beacon\Route;
use sdopx\lib\Outer;

/**
 * 站内URL 转换插件
 * Class UrlPlugin
 * @package sdopx\plugin
 */
class UrlPlugin
{
    public static function render(array $param, Outer $out)
    {
        if (isset($param['path'])) {
            $uri = $param['path'];
        } else {
            $app = isset($param['app']) ? $param['app'] : Route::get('app');
            $ctl = isset($param['ctl']) ? $param['ctl'] : Route::get('ctl');
            $act = isset($param['act']) ? $param['act'] : Route::get('act');
            $uri = '^/' . $app . '/' . $ctl . '/' . $act;
        }
        $args = (isset($param['args']) && is_array($param['args'])) ? $param['args'] : [];
        unset($param['path']);
        unset($param['app']);
        unset($param['ctl']);
        unset($param['act']);
        unset($param['args']);
        $args = array_merge($param, $args);
        $out->text(Route::url($uri, $args));
    }
}