<?php


namespace app\admin\controller;


use beacon\core\DB;
use beacon\core\DBException;
use beacon\core\DBSelector;
use beacon\core\Form;


abstract class ZeroController extends Admin
{

    /**
     * ZeroController所需的配置信息
     * @return array
     * 'table' 表名称,
     * 'pageSize' 分页数量，如没有分页可为0,
     * 'template' 列表模板页面,
     * 'hookTemplate' 数据修饰模板页面
     */
    abstract protected function zeroConfig(): array;

    /**
     * 获取列表数据查询器
     * @return DBSelector
     */
    abstract protected function getSelector(): DBSelector;

    /**
     * 获取搜索表单
     * @return ?Form
     */
    abstract protected function getSearchForm(): ?Form;

    /**
     * 获取表单
     * @param string $type
     * @return Form
     */
    abstract protected function getForm(string $type = ''): Form;


    /**
     * @throws DBException
     */
    protected function index()
    {
        $config = $this->zeroConfig();
        if (!$this->isAjax()) {
            $this->assign('search', $this->getSearchForm());
            $this->display($config['template']);
            return;
        }
        //处理Ajax数据
        $selector = $this->getSelector();
        if (isset($config['pageSize'])) {
            $selector->setPage($config['pageSize']);
            $data = $selector->pageData();
        } else {
            $data = [];
            $data['pageInfo'] = ['recordsCount' => $selector->getCount()];
            $data['list'] = $selector->getList();
        }
        $data['list'] = $this->hookData($data['list'], $config['hookTemplate']);
        $this->success('获取数据成功', $data);
    }

    protected function add()
    {
        $form = $this->getForm('add');
        if ($this->isGet()) {
            $this->displayForm($form);
            return;
        }
        $input = $this->completeForm($form);
        DB::insert($form->table, $input);
        $this->success('添加' . $form->title . '成功');
    }

    protected function edit()
    {
        $id = $this->param('id:i');
        $form = $this->getForm('edit');
        if ($this->isGet()) {
            $row = DB::getItem($form->table, $id);
            $form->setData($row);
            $this->displayForm($form);
            return;
        }
        $input = $this->completeForm($form);
        DB::update($form->table, $input, $id);
        $this->success('编辑' . $form->title . '成功');
    }

    protected function delete()
    {
        $id = $this->param('id:i');
        $form = $this->getForm('delete');
        DB::delete($form->table, $id);
        $this->success('删除' . $form->title . '成功');
    }

    protected function batchDelete()
    {
        $ids = $this->param('choice:a', []);
        $form = $this->getForm('delete');
        foreach ($ids as $id) {
            DB::delete($form->table, $id);
        }
        $this->success('删除' . $form->title . '成功');
    }

    protected function batchEnable()
    {
        $ids = $this->param('choice:a', []);
        $zero = $this->zeroConfig();
        $table = $zero['table'];
        foreach ($ids as $id) {
            DB::update($table, ['allow' => 1], $id);
        }
        $this->success('设置审核成功');
    }

    protected function batchDisable()
    {
        $ids = $this->param('choice:a', []);
        $zero = $this->zeroConfig();
        $table = $zero['table'];
        foreach ($ids as $id) {
            DB::update($table, ['allow' => 0], $id);
        }
        $this->success('设置禁用成功');
    }

    /**
     * @return void
     * @throws DBException
     */
    protected function sort()
    {
        $id = $this->param('id:i');
        $sort = $this->param('sort:i');
        $kind = $this->param('kind:s');
        $bind = $this->param('bind:s');
        $zero = $this->zeroConfig();
        $table = $zero['table'];
        //上下调整方式更改排序
        if (!empty($kind)) {
            $sql = 'select id,sort';
            //将排序相同字段绑定
            if (!empty($bind) && preg_match('@^\w+(,\w+)*$@', $bind)) {
                $sql .= ',' . $bind;
            }
            $sql .= ' from ' . $table . ' where id=?';
            $row = DB::getRow($sql, $id);
            if ($row == null) {
                $this->error('不存在的数据');
            }
            $args = [];
            $temp = explode(',', $bind);
            $find = '';
            if (count($temp) > 0) {
                foreach ($temp as &$item) {
                    $item = trim($item);
                    if (empty($item)) {
                        continue;
                    }
                    $args[] = $row[$item];
                    $find .= $item . '=? and';
                }
            }
            $args[] = $row['sort'];
            if ($kind == 'up') {
                $change = DB::getRow('select id,sort from ' . $table . ' where ' . $find . ' sort < ? order by sort desc limit 0,1', $args);
            } else {
                $change = DB::getRow('select id,sort from ' . $table . ' where ' . $find . ' sort > ? order by sort asc limit 0,1', $args);
            }
            if ($change) {
                DB::update($table, ['sort' => $change['sort']], $row['id']);
                DB::update($table, ['sort' => $row['sort']], $change['id']);
            }
            $this->success('更新排序成功');
        }
        //直接输入形式调整排序
        $row = DB::getRow('select id from ' . $table . ' where id=?', $id);
        if ($row == null) {
            $this->error('不存在的数据');
        }
        DB::update($table, ['sort' => $sort], $id);
        $this->success('更新排序成功');
    }

    /**
     * 切换状态
     * @throws DBException
     */
    protected function toggle()
    {
        $id = $this->param('id:i');
        $zero = $this->zeroConfig();
        $table = $zero['table'];
        $row = DB::getRow('select id,allow from ' . $table . ' where id=?', $id);
        if ($row == null) {
            $this->error('不存在的数据');
        }
        $allow = (intval($row['allow']) == 1 ? 0 : 1);
        DB::update($table, ['allow' => $allow], $id);
        if ($allow == 1) {
            $this->success('设置审核成功');
        }
        $this->success('设置禁用成功');
    }

    /**
     * 启用
     * @throws DBException
     */
    protected function enable()
    {
        $id = $this->param('id:i');
        $zero = $this->zeroConfig();
        $table = $zero['table'];
        DB::update($table, ['allow' => 1], $id);
        $this->success('设置信息启用成功');
    }

    /**
     * 禁用
     * @throws DBException
     */
    protected function disable(int $id = 0)
    {
        $id = $this->param('id:i');
        $zero = $this->zeroConfig();
        $table = $zero['table'];
        DB::update($table, ['allow' => 0], $id);
        $this->success('设置信息禁用成功');
    }
}