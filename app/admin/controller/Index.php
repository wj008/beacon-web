<?php


namespace app\admin\controller;


use beacon\core\CacheException;
use beacon\core\DBException;
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
     * @throws CacheException
     */
    #[Method(act: 'logout', method: Method::GET)]
    public function logout()
    {
        Request::clearSession();
        $this->redirect('~/index');
    }

    /**
     * 切换侧栏
     * @param string $app
     * @return void
     * @throws DBException
     */
    #[Method(act: 'left', method: Method::POST)]
    public function left(): void
    {
        $app = $this->param('app', '');
        $list = $this->leftMenu($app);
        $this->assign('list', $list);
        $code = $this->fetch('layout/left-menu.tpl');
        $this->success('ok', ['code' => $code]);
    }
}