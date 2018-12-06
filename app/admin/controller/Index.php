<?php

namespace app\admin\controller;

/**
 * Created by PhpStorm.
 * User: wj008
 * Date: 2018/1/4
 * Time: 21:01
 */

use beacon\Console;
use beacon\DB;
use beacon\Route;

class Index extends AdminController
{


    public function indexAction()
    {
        $rows = DB::getList('select * from @pf_sys_menu where pid=0 and allow=1 order by sort asc');
        $this->assign('rows', $rows);
        $adm = DB::getRow('select * from @pf_manage where id=?', $this->adminId);
        $this->assign('adm', $adm);
        $this->display('Index.tpl');
    }

    public function leftAction()
    {
        $pid = $this->get('pid:i', 0);
        $info = DB::getRow('select * from @pf_sys_menu where id=?', $pid);
        $this->assign('info', $info);
        $rows = DB::getList('select * from @pf_sys_menu where pid=? and allow=1 order by sort asc', $pid);
        foreach ($rows as &$row) {
            $row['childs'] = DB::getList('select * from @pf_sys_menu where pid=? and allow=1 order by sort asc', $row['id']);
            foreach ($row['childs'] as &$child) {
                if (!empty($child['url']) && ($child['url'][0] == '~' || $child['url'][0] == '^')) {
                    $child['url'] = Route::url($child['url']);
                }
            }
        }
        $this->assign('rows', $rows);
        $this->display('IndexLeft.tpl');
    }

    public function logoutAction()
    {
        $this->delSession();
        $this->redirect('~/index');
    }

    public function welcomeAction()
    {
        $this->display('IndexWelcome.tpl');
    }
}