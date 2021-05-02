<?php


namespace libs;


use beacon\core\DB;
use beacon\core\DBException;

/*
 * 序号产生器表结构
CREATE TABLE `@pf_generation` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `month` varchar(20) DEFAULT '' COMMENT '年月',
  `groupName` varchar(100) DEFAULT '' COMMENT '组名',
  `counter` int(11) DEFAULT NULL COMMENT '计数器',
  PRIMARY KEY (`id`),
  KEY `month` (`month`,`groupName`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COMMENT='序号产生';
 */

/**
 * Class GenerationUtil
 * @package libs
 */
class GenerationUtil
{
    /**
     * @param string $groupName
     * @return array
     * @throws DBException
     */
    public static function getData(string $groupName): array
    {
        $counter = 1;
        $month = date('Ym');
        $row = DB::getRow('select id from @pf_generation where id=?', 1);
        if (!$row) {
            DB::replace('@pf_generation', ['id' => 1, 'month' => '0000', 'groupName' => '--', 'counter' => 1]);
        }
        try {
            DB::beginTransaction();
            //锁定
            DB::update('@pf_generation', ['counter' => 1], 1);
            $row = DB::getRow('select id,counter from @pf_generation where `month`=? and groupName=?', [$month, $groupName]);
            if ($row) {
                $counter = intval($row['counter']) + 1;
                $gid = intval($row['id']);
                DB::update('@pf_generation', ['counter' => $counter], $gid);
            } else {
                DB::insert('@pf_generation', ['month' => $month, 'groupName' => $groupName, 'counter' => $counter]);
            }
            DB::commit();
        } catch (\Exception $exception) {
            DB::rollBack();
            throw $exception;
        }
        return ['month' => $month, 'counter' => $counter];
    }


    /**
     * 获取编号
     * @param string $type
     * @param string prefix
     * @param int $len
     * @return string
     * @throws DBException
     */
    public static function getNumber(string $type, string $prefix = '', int $len = 5): string
    {
        if (empty($type)) {
            return '';
        }
        $data = self::getData($type);
        return $prefix . $data['month'] . str_pad($data['counter'], $len, '0', STR_PAD_LEFT);
    }

}