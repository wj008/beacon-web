<?php


namespace app\admin\controller;


use beacon\core\Method;
use beacon\core\Request;

class Index extends Admin
{
    /**
     * 欢迎首页
     */
    #[Method(act: 'index', method: Method::GET)]
    public function index()
    {
        $this->display('index.tpl');
    }

    /**
     * 退出
     */
    #[Method(act: 'logout', method: Method::GET)]
    public function logout()
    {
        Request::clearSession();
        $this->redirect('~/index');
    }

}