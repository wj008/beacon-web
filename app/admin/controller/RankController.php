<?php
/**
 * Created by PhpStorm.
 * User: wj008
 * Date: 2019/1/24
 * Time: 2:20
 */

namespace app\admin\controller;

use beacon\DB;

/**
 * 用于层级关系的继承
 * Class RankController
 * @package app\admin\controller
 */
abstract class RankController extends ZeroController
{

    /**
     * 查找子类数据
     * @param int $pid
     * @param array $temp
     * @param int $level
     * @param array $incList
     */
    protected function children(int $pid = 0, array $temp = [], int $level = 0, array &$incList)
    {
        $level += 1;
        $selector = $this->zeroSelector();
        $selector->emptyWhere();//清空原有查询，换成使用ID来查
        if (isset($temp[$pid]) && count($temp[$pid]) > 0) {
            $selector->where('id in (' . join(',', $temp[$pid]) . ')');
        } else {
            $selector->where('1=0');
        }
        $list = $selector->getList();
        foreach ($list as $item) {
            $item['level'] = $level;
            $incList[] = $item;
            $this->children($item['id'], $temp, $level, $incList);
        }
    }

    protected function indexAction()
    {
        if ($this->isAjax()) {
            $data = [];
            $selector = $this->zeroSelector();
            //先找到所有的记录
            $list = $selector->getList();
            //开始堆叠,为了搜索命中子类的时候 可以反查父类
            $map = [];
            foreach ($list as $item) {
                $pid = $item['pid'];
                $id = $item['id'];
                $map[$id] = $pid;
            }
            foreach ($map as $id => $pid) {
                redo: # 如果父ID不为0 就一直反查所有父节点
                if ($pid > 0 && !isset($map[$pid])) {
                    $row = DB::getRow('select id,pid from ' . $this->zero['tbName'] . ' where id=?', $pid);
                    $pid = $row['pid'];
                    $id = $row['id'];
                    $map[$id] = $pid;
                    goto redo;
                }
            }
            //转换每个父节点的子类id
            $temp = [];
            foreach ($map as $id => $pid) {
                if (!isset($temp[$pid])) {
                    $temp[$pid] = [];
                }
                $temp[$pid][] = $id;
            }
            //堆叠结束，查出每个父类的下属子类--

            $selector->emptyWhere(); #清空原有查询
            if (isset($temp[0]) && count($temp[0]) > 0) {
                $selector->where('id in (' . join(',', $temp[0]) . ')');
            } else {
                $selector->where('1=0');
            }
            //如果有分页
            if (isset($this->zero['pageSize']) && $this->zero['pageSize'] > 0) {
                $plist = $selector->getPageList($this->zero['pageSize']);
                if (method_exists($this, 'zeroCount')) {
                    $plist->setCount($this->zeroCount());
                }
                $data['list'] = $plist->getList();
                $data['pageInfo'] = $plist->getInfo();
            } else {
                $data['list'] = $selector->getList();
                $data['pageInfo'] = ['recordsCount' => $selector->getCount()];
            }
            $incList = [];
            foreach ($data['list'] as $item) {
                $item['level'] = 0;
                $incList[] = $item;
                $this->children($item['id'], $temp, 0, $incList);
            }
            //修饰数据
            $origFields = isset($this->zero['origFields']) ? $this->zero['origFields'] : [];
            $data['list'] = $this->hook($this->zero['templateHook'], $incList, $origFields);
            $this->success('获取数据成功', $data);
        }
        $this->zeroForSearch();
        $this->display($this->zero['template']);
    }
}