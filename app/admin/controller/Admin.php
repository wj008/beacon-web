<?php


namespace app\admin\controller;


use beacon\core\App;
use beacon\core\CacheException;
use beacon\core\Controller;
use beacon\core\DB;
use beacon\core\DBException;
use beacon\core\DBSelector;
use beacon\core\Request;
use libs\Auth;
use libs\MenuUtil;


abstract class Admin extends Controller
{
    protected string $menuCtl = '';
    public int $adminId = 0;
    public string $adminName = '';
    public string $adminFace = '';

    public function __construct()
    {
        if (empty($this->menuCtl)) {
            $this->menuCtl = App::get('ctl');
        }
        $this->checkLogin();
        if (!Auth::checkAuth()) {
            if ($this->isAjax()) {
                $this->error('您没有执行该操作的权限');
                exit;
            }
            $this->display('auth_error.tpl');
            exit;
        }
    }

    /**
     * 检查登录
     * @throws CacheException
     */
    protected function checkLogin()
    {
        $this->adminId = Request::getSession('adminId:i', 0);
        $this->adminName = Request::getSession('adminName:s', '');
        $this->adminFace = Request::getSession('adminFace:s', '');
        if (empty($this->adminId) || empty($this->adminName)) {
            goto logout;
        }
        /*
         $sessionId = Request::getSessionId();
        $row = DB::getRow('select id,shopId,`identity` from @pf_manager where id=? and `identity`=1', [$this->adminId,$sessionId]);
        if ($row == null) {
            goto logout;
        }
        */
        return;
        logout:
        if ($this->isAjax()) {
            $this->error('登录超时,请重新登录', ['back' => App::url(['app' => 'admin', 'ctl' => 'login']), 'logout' => 1]);
        }
        $this->redirect(App::url(['app' => 'admin', 'ctl' => 'login']));
    }

    /**
     * @throws DBException
     */
    public function topMenu(): array
    {
        $app = App::get('app');
        return MenuUtil::getTop($app, ['admin', 'emall']);
    }

    /**
     * 获取菜单栏目
     * @throws DBException
     */
    public function leftMenu(string $app = ''): array
    {
        if (empty($app)) {
            $app = App::get('app');
        }
        return MenuUtil::getLeft($app, $this->menuCtl);
    }

    public function leftTop(): array
    {
        return [];
    }

}