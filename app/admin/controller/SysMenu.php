<?php


namespace app\admin\controller;


use app\admin\model\SysMenuModel;
use beacon\core\DB;
use beacon\core\DBException;
use beacon\core\Form;
use beacon\core\Method;

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
                $rows = DB::getList("SELECT * FROM @pf_sys_menu A WHERE pid = 0 AND ( `name` LIKE CONCAT('%', ?, '%') OR EXISTS (SELECT id FROM @pf_sys_menu B WHERE pid = A.id AND (`name` LIKE CONCAT('%', ?, '%') OR EXISTS (SELECT 1 FROM @pf_sys_menu WHERE pid = B.id AND `name` LIKE CONCAT('%', ?, '%'))))) ORDER BY sort ASC", [$name, $name, $name]);
            } else {
                $rows = DB::getList('select * from @pf_sys_menu where pid=0 order by sort asc');
            }
            foreach ($rows as $item) {
                $item['title'] = '<b>' . $item['name'] . '</b>';
                $item['create'] = true;
                $items[] = $item;
                if (!empty($name)) {
                    $temp = DB::getList("SELECT * FROM @pf_sys_menu A WHERE pid = ? AND ( `name` LIKE CONCAT('%', ?, '%') OR EXISTS (SELECT id FROM @pf_sys_menu B WHERE pid = A.id AND `name` LIKE CONCAT('%', ?, '%'))) ORDER BY sort ASC", [$item['id'], $name, $name]);
                } else {
                    $temp = DB::getList('select * from @pf_sys_menu where pid=?  order by sort asc', $item['id']);
                }
                foreach ($temp as $rs) {
                    $rs['title'] = '+--- <span>' . $rs['name'] . '</span>';
                    $rs['create'] = true;
                    $items[] = $rs;
                    if (!empty($name)) {
                        $xtemp = DB::getList("SELECT * FROM @pf_sys_menu A WHERE pid = ? AND  `name` LIKE CONCAT('%', ?, '%') ORDER BY sort ASC", [$rs['id'], $name]);
                    } else {
                        $xtemp = DB::getList('select * from @pf_sys_menu where pid=? order by sort asc', $rs['id']);
                    }
                    foreach ($xtemp as $xrs) {
                        $xrs['title'] = '+---+--- <span class="blue">' . $xrs['name'] . '</span>';
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

    #[Method(act: 'icon', method: Method::GET)]
    public function iconAction()
    {
        $this->display('list/sys_menu.icon.tpl');
    }
}