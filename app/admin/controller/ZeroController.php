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