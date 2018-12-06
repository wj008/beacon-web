<?php

namespace app\admin\controller;

/**
 * Created by PhpStorm.
 * User: wj008
 * Date: 2018/1/4
 * Time: 18:37
 */


use beacon\Controller;
use beacon\DB;
use beacon\Form;
use beacon\Logger;
use beacon\Route;


abstract class AdminController extends Controller
{
    protected $adminId = 0;
    protected $adminName = '';

    public function initialize()
    {
        $this->checkLogin();
    }

    protected function checkLogin()
    {
        $this->adminId = $this->getSession('adminId', 0);
        $this->adminName = $this->getSession('adminName', '');
        if (!empty($this->adminId) && !empty($this->adminName)) {
            return;
        }
        if ($this->isGet()) {
            $this->display('Login.tpl');
            exit;
        }
        $username = $this->post('username:s', '');
        $password = $this->post('password:s', '');
        $code = strtoupper($this->post('code:s', ''));
        if ($username == '') {
            $this->error(['username' => '账号名称不能为空！']);
        }
        if ($password == '') {
            $this->error(['password' => '账号密码不能为空！']);
        }
        $pCode = $this->getSession('validCode', '');
        if ($pCode == '' || $pCode != $code) {
            $this->setSession('code', '');
            $this->error(['code' => '验证码有误！']);
        }
        $row = DB::getRow('select * from @pf_manage where `name`=?', $username);
        if ($row == null) {
            $this->error(['username' => '账号不存在！']);
        }
        if ($row['pwd'] != md5($password)) {
            $this->error(['password' => '用户密码不正确！']);
        }
        $this->setSession('adminId', $row['id']);
        $this->setSession('adminName', $row['name']);
        $value = [];
        if (isset($row['thisTime']) && isset($row['lastTime'])) {
            $value['thisTime'] = date('Y-m-d H:i:s');
            $value['lastTime'] = $row['thisTime'];
        }
        if (isset($row['thisIp']) && isset($row['lastIp'])) {
            $value['thisIp'] = $this->getIP();
            $value['lastIp'] = $row['thisIp'];
        }
        if (count($value) > 0) {
            DB::update('@pf_manage', $value, 'id=?', $row['id']);
        }
        $this->success('登录成功', ['back' => Route::url('~/index')]);
    }

    protected function displayForm(Form $form, string $template = '')
    {
        $this->assign('form', $form);
        if (empty($template)) {
            if (!empty($form->template)) {
                $template = $form->template;
            }
        }
        return parent::display($template);
    }
}