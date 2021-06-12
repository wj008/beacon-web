<?php
/**
 * Created by PhpStorm.
 * User: wj008
 * Date: 2018/1/2
 * Time: 21:40
 */

namespace sdopx\plugin;


use beacon\core\App;
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
        $args = (isset($param['args']) && is_array($param['args'])) ? $param['args'] : [];
        unset($param['args']);
        if (isset($param['path'])) {
            $uri = $param['path'];
            unset($param['path']);
            $args = array_merge($args, $param);
            $out->html(App::url($uri, $args));
        } else {
            $app = $param['app'] ?? App::get('app');
            $ctl = $param['ctl'] ?? App::get('ctl');
            $act = $param['act'] ?? 'index';
            unset($param['app']);
            unset($param['ctl']);
            unset($param['act']);
            $args = array_merge($args, $param);
            $out->html(App::url(['app' => $app, 'ctl' => $ctl, 'act' => $act], $args));
        }
    }
}