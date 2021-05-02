<?php


namespace app\admin\controller;


use beacon\core\App;
use beacon\core\Controller;
use beacon\core\Request;

abstract class Admin extends Controller
{
    protected int $adminId = 0;
    protected string $adminName = '';

    public function __construct()
    {
        $this->checkLogin();
    }

    /**
     * 检查登录
     */
    protected function checkLogin()
    {
        $this->adminId = Request::getSession('adminId:i', 0);
        $this->adminName = Request::getSession('adminName:s', '');
        if (!empty($this->adminId) && !empty($this->adminName)) {
            return;
        }
        if ($this->isAjax()) {
            $this->error('登录超时,请重新登录', ['back' => App::url('~/login')]);
        }
        $this->redirect('~/login');
    }

}