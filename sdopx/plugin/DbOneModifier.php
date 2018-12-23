<?php
/**
 * Created by PhpStorm.
 * User: wj008
 * Date: 18-12-16
 * Time: 上午2:14
 */

namespace sdopx\plugin;


use beacon\DB;

class DbOneModifier
{
    private static $cache = [];

    public static function render($string, string $tbName, string $field = '', string $where = '')
    {
        if (empty($field)) {
            $field = 'name';
        }
        if (empty($string)) {
            return '';
        }
        $sql = 'select `' . $field . '` from `' . $tbName . '`';
        if (!empty($where)) {
            $sql .= ' where ' . $where;
        } else {
            $sql .= ' where id=?';
        }
        $key = md5($tbName . '|' . $field . '|' . $where . '|' . $string);
        if (isset(self::$cache[$key])) {
            return self::$cache[$key];
        }
        $row = DB::getRow($sql, $string);
        if (!$row) {
            self::$cache[$key] = '';
            return self::$cache[$key];
        } else {
            self::$cache[$key] = isset($row[$field]) ? $row[$field] : '';
            return self::$cache[$key];
        }
    }
}
