<?php


namespace libs;


use beacon\core\App;
use beacon\core\DB;
use beacon\core\DBException;
use beacon\core\Mysql;
use beacon\core\Request;

class MenuUtil
{

    public static string $current = '';

    const CACHE_EXPIRE = 1;

    /**
     * @param string $menuApp
     * @param array $apps
     * @return array
     * @throws DBException
     */
    public static function getTop(string $menuApp = 'admin', array $apps = []): array
    {
        if (count($apps) == 0) {
            $apps[] = $menuApp;
        }
        foreach ($apps as &$val) {
            $val = Mysql::escape($val);
        }
        $temp = join(',', $apps);
        $list = DB::getList("select * from @pf_sys_menu where menu=1 and pid=0 and app in ({$temp}) and allow=1 order by sort asc");
        $items = [];
        foreach ($list as $item) {
            $item['active'] = $item['app'] == $menuApp;
            $item['url'] = '#';
            $app = $item['app'];
            $nodes = DB::getList(sql: 'select app,ctl,act,`type` from @pf_sys_menu where menu=1 and app=? and pid<>0 and `type` in (2,3) and allow=1 order by sort asc,id asc', args: $app);
            foreach ($nodes as $child) {
                if (intval($child['type']) != 2) {
                    $items[] = $item;
                    break;
                }
                $child['act'] = empty($child['act']) ? 'index' : $child['act'];
                if ($child['app'] == 'tool') {
                    $items[] = $item;
                    break;
                }
                if (Auth::checkAuth($child['app'], $child['ctl'], $child['act'])) {
                    $items[] = $item;
                    break;
                }
            }
        }
        return $items;
    }

    /**
     * 获取菜单
     * @param string $app
     * @param string $ctl
     * @param array|null $param
     * @return array
     * @throws DBException
     */
    public static function getLeft(string $app = '', string $ctl = '', ?array $param = null): array
    {
        $app = empty($app) ? App::get('app') : $app;
        $ctl = empty($ctl) ? App::get('ctl') : $ctl;
        $act = App::get('act');
        //获取第一层目录
        $rows = DB::getList(sql: 'select * from @pf_sys_menu where menu=1 and app=? and pid<>0 and `type` =1 and allow=1 order by sort asc,id asc', args: $app);
        $map = [];
        $list = [];
        foreach ($rows as $row) {
            $menuId = intval($row['id']);
            $nodes = DB::getList(sql: 'select * from @pf_sys_menu where pid=? and allow=1 and menu=1 and `type` in (2,3) order by sort asc', args: $menuId);
            $items = [];
            foreach ($nodes as $child) {
                $child['active'] = false;
                if (intval($child['type']) != 2) {
                    $items[] = $child;
                    continue;
                }
                $child['app'] = empty($child['app']) ? $app : $child['app'];
                $child['ctl'] = empty($child['ctl']) ? 'index' : $child['ctl'];
                $child['act'] = empty($child['act']) ? 'index' : $child['act'];
                if (!Auth::checkAuth($child['app'], $child['ctl'], $child['act'])) {
                    continue;
                }
                $acts = explode(',', $child['act']);
                $child['act'] = $acts[0];
                $key = $child['app'] . '/' . $child['ctl'];
                if (!isset($map[$key])) {
                    $map[$key] = [];
                }
                if ($child['act'] != 'index') {
                    $map[$key][] = $child['act'];
                }
                $info = ['app' => $child['app'], 'ctl' => $child['ctl'], 'act' => $child['act']];
                $query = [];
                if (!empty($child['params'])) {
                    parse_str($child['params'], $temp);
                    $query = array_merge($temp, $query);
                    foreach ($query as $key => $value) {
                        if (preg_match('@^\{(.+)\}$@', $value, $m)) {
                            if ($param == null) {
                                $query[$key] = Request::param($m[1]);
                            } else {
                                $query[$key] = Request::lookup($param, $m[1]);
                            }
                        }
                    }
                }
                $child['url'] = App::url($info, $query);
                $items[] = $child;
            }

            if (count($items) > 0) {
                $row['items'] = $items;
                $list[] = $row;
            }
        }
        //设置选中状态
        foreach ($list as &$row) {
            foreach ($row['items'] as &$child) {
                if (!isset($child['app']) || $child['app'] != $app) {
                    continue;
                }
                $key = $child['app'] . '/' . $child['ctl'];
                if (!empty(self::$current) && $key == self::$current) {
                    $child['active'] = true;
                    continue;
                }
                if ($child['ctl'] != $ctl) {
                    continue;
                }
                if (($child['act'] == 'index' && !in_array($act, $map[$key])) || ($child['act'] == $act)) {
                    $child['active'] = true;
                }
            }
        }
        return $list;
    }


}