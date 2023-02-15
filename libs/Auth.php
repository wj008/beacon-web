<?php


namespace libs;


use beacon\core\App;
use beacon\core\CacheException;
use beacon\core\DB;
use beacon\core\DBException;
use beacon\core\Redis;
use beacon\core\Request;
use beacon\core\Util;

class Auth
{

    /**
     * @throws DBException
     */
    private static function getNode(string $app = '', string $ctl = '', string $act = ''): int
    {
        $row = DB::getRow(sql: 'select id from @pf_sys_menu where auth=1 and app=? and ctl=? and FIND_IN_SET(?,`act`)', args: [$app, $ctl, $act]);
        if ($row == null) {
            return 0;
        }
        return intval($row['id']);
    }

    /**
     * @param int $adminId
     * @return array
     * @throws DBException
     */
    private static function getRoleMap(int $adminId): array
    {
        static $cache = [];
        if (isset($cache[$adminId])) {
            return $cache[$adminId];
        }
        $role = DB::getRow('select B.nodes from @pf_manager A inner join @pf_role B on B.id=A.roleId where A.id=?', $adminId);
        if ($role == null) {
            return $cache[$adminId] = [];
        }
        $temp = Helper::convertArray($role['nodes']);
        $tempMap = [];
        foreach ($temp as $item) {
            $tempMap[$item] = $item;
        }
        return $cache[$adminId] = $tempMap;
    }

    /**
     * 权限判断
     * @param string $app
     * @param string $ctl
     * @param string $act
     * @return bool
     * @throws DBException|CacheException
     */
    public static function checkAuth(string $app = '', string $ctl = '', string $act = ''): bool
    {

        $adminId = Request::getSession('adminId:i', 0);
        if ($adminId == 1) {
            return true;
        }
        if (empty($app)) {
            $app = App::get('app');
        }
        if (empty($ctl)) {
            $ctl = App::get('ctl');
        }
        if (empty($act)) {
            $act = App::get('act');
        }

        $ctl = Util::toUnder($ctl);
        $act = Util::toUnder($act);
        //如果没有加入控制
        $nodeId = self::getNode($app, $ctl, $act);
        if ($nodeId == 0) {
            return true;
        }
        $roleMap = self::getRoleMap($adminId);
        return isset($roleMap[$nodeId]);
    }

}