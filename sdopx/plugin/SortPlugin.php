<?php
/**
 * Created by PhpStorm.
 * User: wj008
 * Date: 2019/1/24
 * Time: 0:18
 */

namespace sdopx\plugin;

use beacon\Route;
use sdopx\lib\Outer;

class SortPlugin
{
    public static function render(array $param, Outer $out)
    {
        if (!isset($param['id'])) {
            $out->throw('[id] attr is required.');
        }
        if (empty($param['kind'])) {
            if (!isset($param['value'])) {
                $out->throw('[value] attr is required.');
            }
            $out->html('<input name="sort" type="text" class="form-inp snumber tc" yee-module="ajax" value="' . htmlspecialchars($param['value']) . '" data-url="' . Route::url(['act' => 'sort', 'id' => $param['id']]) . '"/>');
            return;
        }
        $map1 = ['act' => 'sort', 'id' => $param['id']];
        $map2 = ['act' => 'sort', 'id' => $param['id']];
        if ($param['kind'] == 'up') {
            $map1['kind'] = 'up';
            $map2['kind'] = 'dn';
        } else {
            $map2['kind'] = 'up';
            $map1['kind'] = 'dn';
        }
        if (!empty($param['bind'])) {
            $map2['bind'] = $param['bind'];
            $map1['bind'] = $param['bind'];
        }
        $out->html(' ');
        $out->html('<a href="' . Route::url($map1) . '" class="blue" yee-module="ajax" on-success="$(\'#list\').emit(\'reload\');">上移</a>');
        $out->html(' | ');
        $out->html('<a href="' . Route::url($map2) . '" class="blue" yee-module="ajax"  on-success="$(\'#list\').emit(\'reload\');">下移</a>');
    }
}