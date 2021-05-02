<?php


namespace app\admin\controller;


use beacon\core\App;
use beacon\core\DB;
use beacon\core\DBException;
use beacon\core\Method;
use beacon\core\Request;

class Index extends Admin
{
    /**
     * @throws DBException
     */
    #[Method(act: 'index', method: Method::GET)]
    public function index()
    {
        $rows = DB::getList('select * from @pf_sys_menu where pid=0 and allow=1 order by sort asc');
        $this->assign('rows', $rows);
        $adm = DB::getRow('select * from @pf_manage where id=?', $this->adminId);
        $this->assign('adm', $adm);
        $this->display('index.tpl');
    }

    /**
     * 左侧页面
     * @param int $pid
     * @throws DBException
     */
    #[Method(act: 'left', method: Method::GET)]
    public function left(int $pid = 0)
    {
        $info = DB::getRow('select * from @pf_sys_menu where id=?', $pid);
        $this->assign('info', $info);
        $rows = DB::getList('select * from @pf_sys_menu where pid=? and allow=1 order by sort asc', $pid);
        foreach ($rows as &$row) {
            $row['childs'] = DB::getList('select * from @pf_sys_menu where pid=? and allow=1 order by sort asc', $row['id']);
            foreach ($row['childs'] as &$child) {
                if (!empty($child['url']) && ($child['url'][0] == '~' || $child['url'][0] == '^')) {
                    $child['url'] = App::url($child['url']);
                }
            }
        }
        $this->assign('rows', $rows);
        $code = $this->fetch('index.left.tpl');
        $this->success('ok', ['code' => $code]);
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

    /**
     * 欢迎页面
     */
    #[Method(act: 'welcome', method: Method::GET)]
    public function welcome()
    {
        $this->display('index.welcome.tpl');
    }

}