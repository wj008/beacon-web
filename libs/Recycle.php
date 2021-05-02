<?php


namespace libs;


use beacon\core\DB;
use beacon\core\DBException;
use beacon\core\Mysql;
use beacon\core\Request;
use Exception;

/*
 * 序号产生器表结构
CREATE TABLE `sl_recycle_bin` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `tableName` varchar(100) DEFAULT NULL COMMENT '表名',
  `deleteTime` datetime DEFAULT NULL COMMENT '删除时间',
  `userId` int(11) DEFAULT '0' COMMENT '操作人',
  `adminId` int(11) DEFAULT NULL COMMENT '管理员',
  `data` json DEFAULT NULL COMMENT '数据',
  `batchNum` int(11) DEFAULT NULL COMMENT '批次号',
  `condition` text COMMENT '删除条件',
  `url` varchar(500) DEFAULT NULL COMMENT '操作页面',
  PRIMARY KEY (`id`) USING BTREE,
  KEY `batchNum` (`batchNum`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COMMENT='回收站';
 */

/**
 * Class Recycle
 * @package libs
 */

class Recycle
{
    private static int $batchNum = 0;

    /**
     * 获取批次号
     * @return int
     * @throws DBException
     */
    private static function getBatchNum(): int
    {
        if (self::$batchNum == 0) {
            $batchNum = 1;
            $row = DB::getRow('select max(batchNum) as maxBatch from @pf_recycle_bin');
            if ($row) {
                $batchNum = intval($row['maxBatch']) + 1;
            }
            self::$batchNum = $batchNum;
        }
        return self::$batchNum;
    }

    /**
     * 备份删除数据
     * @param string $table
     * @param string|int|null $where
     * @param ?array $args
     * @throws DBException|Exception
     */
    public static function delete(string $table, string|int|null $where = null, ?array $args = null)
    {
        if (!DB::inTransaction()) {
            throw new Exception('必须在事务里面');
        }
        $where = trim($where);
        if (is_int($where) || is_numeric($where)) {
            $args = [intval($where)];
            $where = 'id=?';
        }
        if (empty($where) || $args === null || (is_array($args) && count($args) == 0)) {
            throw new Exception('删除条件必须存在');
        }
        $table = trim($table);
        $batchNum = self::getBatchNum();
        $time = date('Y-m-d H:i:s');
        $userId = Request::getSession('userId:i', 0);
        $adminId = Request::getSession('adminId:i', 0);
        $sql = 'select * from ' . $table . ' where ' . $where;
        $list = DB::getList($sql, $args);
        $condition = Mysql::format($where, $args);
        $url = Request::server('REQUEST_URI', '');
        foreach ($list as $row) {
            $input = [];
            $input['tableName'] = $table;
            $input['deleteTime'] = $time;
            $input['userId'] = $userId;
            $input['adminId'] = $adminId;
            $input['data'] = $row;
            $input['batchNum'] = $batchNum;
            $input['condition'] = $condition;
            $input['url'] = $url;
            DB::insert('@pf_recycle_bin', $input);
        }
        DB::delete($table, $where, $args);
    }

}