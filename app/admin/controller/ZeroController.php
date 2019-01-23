<?php
/**
 * Created by PhpStorm.
 * User: wj008
 * Date: 2018/2/23
 * Time: 18:23
 */

namespace app\admin\controller;

use beacon\DB;
use beacon\Form;

abstract class ZeroController extends AdminController
{
    protected $zero = [];

    public function initialize()
    {
        $this->zero = $this->zeroLoad();
        if (isset($this->zero['actionForm'])) {
            $actionForm = preg_replace_callback('@\\\\zero\\\\form\\\\Zero(.*)$@', function ($m) {
                return '\\form\\' . $m[1];
            }, $this->zero['actionForm']);
            if (class_exists($actionForm)) {
                $this->zero['actionForm'] = $actionForm;
            }
        }
        parent::initialize();
    }

    protected function indexAction()
    {
        if ($this->isAjax()) {
            $data = [];
            $selector = $this->zeroSelector();
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
            //修饰数据
            $origFields = isset($this->zero['origFields']) ? $this->zero['origFields'] : [];
            $data['list'] = $this->hook($this->zero['templateHook'], $data['list'], $origFields);
            $this->success('获取数据成功', $data);
        }
        $this->zeroForSearch();
        $this->display($this->zero['template']);
    }

    protected function addAction()
    {
        $form = Form::instance($this->zero['actionForm'], 'add');
        if ($this->isGet()) {
            $this->displayForm($form);
            return;
        }
        if ($this->isPost()) {
            $form->autoComplete();
            if (!$form->validation($error)) {
                $this->error($error);
            }
            $form->insert();
            $this->success('添加' . $form->title . '成功');
        }
    }

    protected function editAction()
    {
        $id = $this->param('id:i');
        $form = Form::instance($this->zero['actionForm'], 'edit');
        if ($id == 0) {
            $this->error('参数有误');
        }
        $row = $form->getRow($id);
        $form->setValues($row);
        if ($this->isGet()) {
            $this->displayForm($form);
            return;
        }
        if ($this->isPost()) {
            $form->autoComplete();
            if (!$form->validation($error)) {
                $this->error($error);
            }
            $form->update($id);
            $this->success('编辑' . $form->title . '成功');
        }
    }

    protected function deleteAction()
    {
        $id = $this->param('id:i');
        if ($id == 0) {
            $this->error('参数有误');
        }
        $form = Form::instance($this->zero['actionForm']);
        $form->delete($id);
        $this->success('删除' . $form->title . '成功');
    }

    protected function deleteChoiceAction()
    {
        $ids = $this->param('choice:a', []);
        $form = Form::instance($this->zero['actionForm']);
        foreach ($ids as $id) {
            $form->delete($id);
        }
        $this->success('删除' . $form->title . '成功');
    }

    protected function allowChoiceAction()
    {
        $ids = $this->param('choice:a', []);
        foreach ($ids as $id) {
            DB::update($this->zero['tbName'], ['allow' => 1], $id);
        }
        $this->success('设置审核成功');
    }

    protected function revokeChoiceAction()
    {
        $ids = $this->param('choice:a', []);
        foreach ($ids as $id) {
            DB::update($this->zero['tbName'], ['allow' => 0], $id);
        }
        $this->success('设置禁用成功');
    }

    protected function sortAction()
    {
        $id = $this->param('id:i');
        $sort = $this->param('sort:i');
        $kind = $this->param('kind:s');
        $bind = $this->param('bind:s');
        //上下调整方式更改排序
        if (!empty($kind)) {
            $sql = 'select id,sort';
            //将排序相同字段绑定
            if (!empty($bind) && preg_match('@^\w+(,\w+)*$@', $bind)) {
                $sql .= ',' . $bind;
            }
            $sql .= ' from ' . $this->zero['tbName'] . ' where id=?';
            $row = DB::getRow($sql, $id);
            if ($row == null) {
                $this->error('不存在的数据');
            }
            $args = [];
            $temp = explode(',', $bind);
            $find = '';
            if (count($temp) > 0) {
                foreach ($temp as &$item) {
                    $item = trim($item);
                    if (empty($item)) {
                        continue;
                    }
                    $args[] = $row[$item];
                    $find .= $item . '=? and';
                }
            }
            $args[] = $row['sort'];
            $change = null;
            if ($kind == 'up') {
                $change = DB::getRow('select id,sort from ' . $this->zero['tbName'] . ' where ' . $find . ' sort < ? order by sort desc limit 0,1', $args);
            } else {
                $change = DB::getRow('select id,sort from ' . $this->zero['tbName'] . ' where ' . $find . ' sort > ? order by sort asc limit 0,1', $args);
            }
            if ($change) {
                DB::update($this->zero['tbName'], ['sort' => $change['sort']], $row['id']);
                DB::update($this->zero['tbName'], ['sort' => $row['sort']], $change['id']);
            }
            $this->success('更新排序成功');
        }
        //直接输入形式调整排序
        $row = DB::getRow('select id from ' . $this->zero['tbName'] . ' where id=?', $id);
        if ($row == null) {
            $this->error('不存在的数据');
        }
        DB::update($this->zero['tbName'], ['sort' => $sort], $id);
        $this->success('更新排序成功');
    }

    protected function toggleAllowAction()
    {
        $id = $this->param('id:i');
        $row = DB::getRow('select id,allow from ' . $this->zero['tbName'] . ' where id=?', $id);
        if ($row == null) {
            $this->error('不存在的数据');
        }
        $allow = (intval($row['allow']) == 1 ? 0 : 1);
        DB::update($this->zero['tbName'], ['allow' => $allow], $id);
        if ($allow == 1) {
            $this->success('设置审核成功');
        }
        $this->success('设置禁用成功');
    }

}