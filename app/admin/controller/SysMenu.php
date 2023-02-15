<?php


namespace app\admin\controller;


use app\admin\model\SysMenuModel;
use app\admin\model\SysMenuNodesModel;
use beacon\core\DB;
use beacon\core\DBException;
use beacon\core\DBSelector;
use beacon\core\Form;
use beacon\core\Method;
use beacon\core\Util;
use libs\BtnUtil;

class SysMenu extends Admin
{
    /**
     * @throws DBException
     */
    #[Method(act: 'index', method: Method::GET | Method::POST)]
    public function index()
    {
        if ($this->isAjax()) {
            $items = [];
            $name = $this->get('name', '');
            if (!empty($name)) {
                $rows = DB::getList("SELECT * FROM @pf_sys_menu A WHERE pid = 0 and menu=1 AND ( `name` LIKE CONCAT('%', ?, '%') OR EXISTS (SELECT id FROM @pf_sys_menu B WHERE pid = A.id  and menu=1 AND (`name` LIKE CONCAT('%', ?, '%') OR EXISTS (SELECT 1 FROM @pf_sys_menu WHERE pid = B.id  and menu=1 AND `name` LIKE CONCAT('%', ?, '%'))))) ORDER BY sort ASC", [$name, $name, $name]);
            } else {
                $rows = DB::getList('select * from @pf_sys_menu where pid=0 and menu=1 order by sort asc');
            }
            foreach ($rows as $item) {
                $item['title'] = '<b>' . $item['name'] . '</b>';
                $item['create'] = true;
                $items[] = $item;
                if (!empty($name)) {
                    $temp = DB::getList("SELECT * FROM @pf_sys_menu A WHERE pid = ?  and menu=1 AND ( `name` LIKE CONCAT('%', ?, '%') OR EXISTS (SELECT id FROM @pf_sys_menu B WHERE pid = A.id and menu=1 AND `name` LIKE CONCAT('%', ?, '%'))) ORDER BY sort ASC", [$item['id'], $name, $name]);
                } else {
                    $temp = DB::getList('select * from @pf_sys_menu where pid=?  and menu=1 order by sort asc', $item['id']);
                }
                foreach ($temp as $rs) {
                    $rs['title'] = '+--- <span>' . $rs['name'] . '</span>';
                    $rs['create'] = true;
                    $items[] = $rs;
                    if (!empty($name)) {
                        $xtemp = DB::getList("SELECT * FROM @pf_sys_menu A WHERE pid = ?  and menu=1 AND  `name` LIKE CONCAT('%', ?, '%') ORDER BY sort ASC", [$rs['id'], $name]);
                    } else {
                        $xtemp = DB::getList('select * from @pf_sys_menu where pid=?  and menu=1 order by sort asc', $rs['id']);
                    }
                    foreach ($xtemp as $xrs) {
                        if ($xrs['auth'] == 1 && $xrs['menu'] == 0) {
                            $xrs['title'] = '+---+--- <span class="gray">' . $xrs['name'] . '</span>';
                        } else {
                            $xrs['title'] = '+---+--- <span class="blue">' . $xrs['name'] . '</span>';
                        }
                        $xrs['create'] = false;
                        $items[] = $xrs;
                    }
                }
            }
            $data = [];
            $data['list'] = $this->hookData($items, 'hook/sys_menu.tpl');
            $data['pageInfo'] = ['recordsCount' => count($items)];
            $this->success('获取数据成功', $data);
        }
        $this->display('list/sys_menu.tpl');
    }

    /**
     * @param int $id
     * @param int $sort
     * @throws DBException
     */
    #[Method(act: 'sort', method: Method::GET | Method::POST)]
    public function sort(int $id = 0, int $sort = 0)
    {
        DB::update('@pf_sys_menu', ['sort' => $sort], $id);
        $this->success('更新排序成功');
    }

    /**
     * @throws DBException
     */
    #[Method(act: 'add', method: Method::GET | Method::POST)]
    public function add()
    {
        $model = new SysMenuModel();
        $model->sort = intval(DB::getMax('@pf_sys_menu', 'sort')) + 10;
        $model->pid = $this->get('pid:i', 0);
        $form = Form::create($model, 'add');
        if ($this->isGet()) {
            $this->displayForm($form);
            return;
        }
        $input = $this->completeForm($form);
        $input['menu'] = 1;
        $input['ctl'] = Util::toUnder($input['ctl']);
        $input['act'] = Util::toUnder($input['act']);
        DB::insert('@pf_sys_menu', $input);
        $this->success('添加菜单信息成功');
    }

    /**
     * @param int $id
     * @throws DBException
     */
    #[Method(act: 'edit', method: Method::GET | Method::POST)]
    public function edit(int $id = 0)
    {
        if ($id == 0) {
            $this->error('参数有误');
        }
        $form = Form::create(SysMenuModel::class, 'edit');
        $row = DB::getItem('@pf_sys_menu', $id);
        if (!$row) {
            $this->error("数据不存在");
        }
        $form->setData($row);
        if ($this->isGet()) {
            $this->displayForm($form);
            return;
        }
        $input = $this->completeForm($form);
        $input['ctl'] = Util::toUnder($input['ctl']);
        $input['act'] = Util::toUnder($input['act']);
        DB::update('@pf_sys_menu', $input, $id);
        $this->success('编辑菜单信息成功');
    }

    /**
     * @param int $id
     * @throws DBException
     */
    #[Method(act: 'delete', method: Method::GET | Method::POST)]
    public function delete(int $id = 0)
    {
        if ($id == 0) {
            $this->error('参数有误');
        }
        $row = DB::getItem('@pf_sys_menu', $id);
        if (!$row) {
            $this->error("数据不存在");
        }
        DB::delete('@pf_sys_menu', $id);
        $this->success('删除菜单信息成功');
    }


    /**
     * @throws DBException
     */
    #[Method(act: 'add_node', method: Method::GET | Method::POST)]
    public function addNode(int $pid = 0)
    {
        $model = new SysMenuNodesModel();
        $row = DB::getRow('select ctl from @pf_sys_menu where id=?', $pid);
        if ($row == null) {
            $this->error('菜单节点信息不存在');
        }
        $row['sort'] = 10;
        $sortRow = DB::getRow('select ifnull(max(sort),0) as mySort from @pf_sys_menu where pid=?', $pid);
        if ($sortRow) {
            $row['sort'] = intval($sortRow['mySort']) + 10;
        }
        $form = Form::create($model, 'add');
        if ($this->isGet()) {
            $form->setData($row);
            $this->displayForm($form);
            return;
        }
        $input = $this->completeForm($form);
        $input['menu'] = 0;
        $input['pid'] = $pid;
        $input['auth'] = 1;
        $input['isUrl'] = 0;
        $input['ctl'] = Util::toUnder($input['ctl']);
        $input['act'] = Util::toUnder($input['act']);
        DB::insert('@pf_sys_menu', $input);
        $this->success('添加菜单信息成功');
    }

    /**
     * @param int $id
     * @throws DBException
     */
    #[Method(act: 'edit_node', method: Method::GET | Method::POST)]
    public function editNode(int $id = 0)
    {
        if ($id == 0) {
            $this->error('参数有误');
        }
        $form = Form::create(SysMenuNodesModel::class, 'edit');
        $row = DB::getItem('@pf_sys_menu', $id);
        if (!$row) {
            $this->error("数据不存在");
        }
        $form->setData($row);
        if ($this->isGet()) {
            $this->displayForm($form);
            return;
        }
        $input = $this->completeForm($form);
        $input['ctl'] = Util::toUnder($input['ctl']);
        $input['act'] = Util::toUnder($input['act']);
        DB::update('@pf_sys_menu', $input, $id);
        $this->success('编辑菜单信息成功');
    }


    #[Method(act: 'nodes', method: Method::GET | Method::POST)]
    public function nodes(int $pid = 0)
    {
        $row = DB::getRow('select id from @pf_sys_menu where id=?', $pid);
        if ($row == null) {
            $this->error('菜单节点信息不存在');
        }
        $this->assign('pid', $pid);
        if ($this->isAjax()) {
            $selector = new DBSelector('@pf_sys_menu');
            $selector->where('pid=?', $pid);
            $selector->order('sort asc,id desc');
            $data = $selector->pageData();
            $data['list'] = $this->hookData($data['list'], 'hook/sys_menu_nodes.tpl');
            $this->success('获取数据成功', $data);
        }
        $this->display('list/sys_menu_nodes.tpl');
    }

    #[Method(act: 'icon', method: Method::GET)]
    public function iconAction()
    {
        $this->display('list/sys_menu.icon.tpl');
    }

    public function listBtn(array $rs)
    {

        $id = $rs['id'];
        $btn = [];
        if ($rs['create'] == 1) {

            $btn['添加子项'] = [
                'url' => ['act' => 'add', 'pid' => $id],
                'dialog' => [1200, 900],
                'css' => $rs['pid'] == 0 ? 'red' : 'red-bd',
                'icon' => 'icofont-plus'
            ];
        } else {
            $btn['权限节点'] = [
                'url' => ['act' => 'nodes', 'pid' => $id],
                'dialog' => [1200, 900],
                'icon' => 'icofont-list'
            ];
        }
        $btn['编辑'] = [
            'url' => ['act' => 'edit', 'id' => $id],
            'dialog' => [900, 660],
            'css' => 'blue-bd',
            'icon' => 'icofont-edit'
        ];
        $btn['删除'] = [
            'url' => ['act' => 'delete', 'id' => $id],
            'ajax' => true,
            'confirm' => '确定要删除该菜单了吗?',
            'css' => 'red-bd',
            'icon' => 'icofont-bin'
        ];
        return BtnUtil::makeButton($btn);

    }

    public function nodeBtn(array $rs)
    {

        $id = $rs['id'];
        $btn = [];
        $btn['编辑'] = [
            'url' => ['act' => 'edit_node', 'id' => $id],
            'dialog' => [1200, 900],
            'css' => 'blue-bd',
            'icon' => 'icofont-edit'
        ];
        $btn['删除'] = [
            'url' => ['act' => 'delete', 'id' => $id],
            'ajax' => true,
            'confirm' => '确定要删除该菜单了吗?',
            'css' => 'red-bd',
            'icon' => 'icofont-bin'
        ];
        return BtnUtil::makeButton($btn);

    }
}