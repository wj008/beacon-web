<?php


namespace app\admin\controller;


use beacon\core\App;
use beacon\core\Controller;
use beacon\core\DB;
use beacon\core\DBException;
use beacon\core\DBSelector;
use beacon\core\Request;


abstract class Admin extends Controller
{
    /**
     * 菜单选项
     * @var int[]
     */
    protected array $webMenus = [1];
    /**
     * 管理员信息
     */
    public int $adminId = 0;
    public string $adminName = '';
    public string $adminAvatar='';

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
        $this->adminAvatar=Request::getSession('adminAvatar:s', '');
        if (!empty($this->adminId) && !empty($this->adminName)) {
            return;
        }
        if ($this->isAjax()) {
            $this->error('登录超时,请重新登录', ['back' => App::url('~/login')]);
        }
        $this->redirect('~/login');
    }

    /**
     * 获取菜单栏目
     * @throws DBException
     */
    public function leftBar(): array
    {
        $app = App::get('app');
        $ctl = App::get('ctl');
        $act = App::get('act');
        $selector = new DBSelector('@pf_sys_menu');
        $selector->where('allow=1');
        $selector->search('pid in([?])', $this->webMenus);
        $selector->order('sort asc,id asc');
        $rows = $selector->getList();
        foreach ($rows as &$row) {
            $row['items'] = DB::getList('select * from @pf_sys_menu where pid=? and allow=1 order by sort asc', $row['id']);
            foreach ($row['items']  as &$child) {
                $child['active'] = false;
                if (empty($child['url']) || !($child['url'][0] == '~' || $child['url'][0] == '^')) {
                    continue;
                }
                $child['temp']=$temp=App::parseVirtualUrl($child['url']);
                $child['url'] = App::url($child['url']);
                $key = $temp['app'] . '/' . $temp['ctl'];
                if ($temp['act'] != 'index') {
                    $map[$key][] = $temp['act'];
                }
            }
        }
        foreach ($rows as &$row) {
            foreach ($row['items'] as &$child) {
                $temp=$child['temp'];
                unset($child['temp']);
                if (!isset($temp['app']) || $temp['app'] != $app || $temp['ctl'] != $ctl) {
                    continue;
                }
                $key = $temp['app'] . '/' . $temp['ctl'];
                if (($temp['act'] == 'index' && !in_array($act, $map[$key])) || ($temp['act'] == $act)) {
                    $child['active'] = true;
                }
            }
        }
        return $rows;
    }

}