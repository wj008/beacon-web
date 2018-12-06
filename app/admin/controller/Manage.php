<?php

namespace app\admin\controller;


/**
 * Created by PhpStorm.
 * User: wj008
 * Date: 2018/1/5
 * Time: 1:26
 */


use app\admin\form\ManageForm;
use beacon\Console;
use beacon\DB;
use beacon\Request;
use beacon\SqlSelector;

class Manage extends AdminController
{
    public function indexAction()
    {
        if ($this->isAjax()) {
            $selector = new SqlSelector('@pf_manage');
            $name = $this->get('name', '');
            if ($name) {
                $selector->where("(`name` LIKE CONCAT('%',?,'%') OR realName LIKE CONCAT('%',?,'%'))", [$name, $name]);
            }
            $sort = $this->get('sort', 'id_asc');
            switch ($sort) {
                case 'id_asc':
                    $selector->order('id asc');
                    break;
                case 'id_desc':
                    $selector->order('id desc');
                    break;
                case 'name_asc':
                    $selector->order('name asc');
                    break;
                case 'name_desc':
                    $selector->order('name desc');
                    break;
                case 'realname_asc':
                    $selector->order('realName asc');
                    break;
                case 'realname_desc':
                    $selector->order('realName desc');
                    break;
                case 'type_asc':
                    $selector->order('type asc');
                    break;
                case 'type_desc':
                    $selector->order('type desc');
                    break;
                default:
                    $selector->order('id asc');
                    break;
            }
            $plist = $selector->getPageList(10);
            $pageInfo = $plist->getInfo();
            $list = $this->hook('Manage.hook.tpl', $plist->getList());
            $this->assign('list', $list);
            $this->assign('pageInfo', $pageInfo);
            $data = $this->getAssign();
            $this->success('获取数据成功', $data);
        }
        $this->display('Manage.tpl');
    }

    public function checkNameAction()
    {
        $username = $this->param('name', '');
        $id = $this->param('id', 0);
        $row = DB::getRow('select id from @pf_manage where `name`=? and id<>?', [$username, $id]);
        if ($row) {
            $this->error('用户名已经存在');
        }
        $this->success('用户名可以使用');
    }

    public function addAction()
    {
        $form = new ManageForm('add');
        if ($this->isGet()) {
            $this->displayForm($form);
            return;
        }
        if ($this->isPost()) {
            $values = $form->autoComplete();
            if (!$form->validation($error)) {
                $this->error($error);
            }
            DB::insert('@pf_manage', $values);
            $this->success('添加' . $form->title . '成功');
        }
    }

    public function editAction(int $id = 0)
    {
        $form = new ManageForm('edit');
        if ($id == 0) {
            $this->error('参数有误');
        }
        $row = DB::getRow('select * from @pf_manage where id=?', $id);
        $form->setValues($row);
        if ($this->isGet()) {
            $this->displayForm($form);
            return;
        }
        if ($this->isPost()) {
            $values = $form->autoComplete();
            if (!$form->validation($error)) {
                $this->error($error);
            }
            if (empty($this->post('password'))) {
                unset($values['pwd']);
            }
            DB::update('@pf_manage', $values, $id);
            $this->success('编辑' . $form->title . '成功');
        }
    }

    public function delAction(int $id = 0)
    {
        if ($id == 0) {
            $this->error('参数有误');
        }
        if ($id == 1) {
            $this->error('最高管理员不可删除');
        }
        DB::delete('@pf_manage', $id);
        $this->success('删除账号成功');
    }

    //修改账号密码
    public function passwordAction()
    {
        if ($this->isGet()) {
            $this->assign('row', $this->getSession());
            $this->display('Manage.password.tpl');
            return;
        }
        if ($this->isPost()) {
            $oldPass = $this->post('oldPass:s', '');
            $newPass = $this->post('newPass:s', '');
            if ($oldPass == '') {
                $this->error(['oldPass' => '旧密码不可为空']);
            }
            if ($newPass == '') {
                $this->error(['newPass' => '新密码不可为空']);
            }
            $row = DB::getRow('select id,pwd from @pf_manage where id=?', $this->adminId);
            if ($row == null) {
                $this->error('账号不存在');
            }
            if (md5($oldPass) != $row['pwd']) {
                $this->error(['oldPass' => '旧密码不正确，请重新输入']);
            }
            $newPass = md5($newPass);
            DB::update('@pf_manage', ['pwd' => $newPass], $this->adminId);
            $this->success('修改密码成功');
        }
    }
}