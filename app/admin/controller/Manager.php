<?php


namespace app\admin\controller;


use app\admin\model\ManagerModel;
use beacon\core\CacheException;
use libs\BtnUtil;
use beacon\core\App;
use beacon\core\DB;
use beacon\core\DBException;
use beacon\core\DBSelector;
use beacon\core\Form;
use beacon\core\Method;
use beacon\core\Request;


class Manager extends Admin
{

    /**
     * @throws DBException
     */
    #[Method(act: 'index', method: Method::GET | Method::POST)]
    public function index()
    {
        if ($this->isAjax()) {
            $selector = new DBSelector('@pf_manager');
            $name = $this->get('account', '');
            if ($name) {
                $selector->where("(`account` LIKE CONCAT('%',?,'%') OR realName LIKE CONCAT('%',?,'%'))", [$name, $name]);
            }
            $data = $selector->pageData();
            $data['list'] = $this->listFilter($data['list']);
            $this->success('获取数据成功', $data);
            return;
        }
        $this->display('list/manager.tpl');
    }

    /**
     * @param string $name
     * @param int $id
     * @throws DBException
     */
    #[Method(act: 'check_name', method: Method::GET | Method::POST)]
    public function checkName(string $account = '', int $id = 0)
    {
        $row = DB::getRow('select id from @pf_manager where `account`=? and id<>?', [$account, $id]);
        if ($row) {
            $this->error('用户名已经存在');
        }
        $this->success('用户名可以使用');
    }

    /**
     * @throws DBException
     */
    #[Method(act: 'add', method: Method::GET | Method::POST)]
    public function add()
    {
        $form = Form::create(ManagerModel::class, 'add');
        if ($this->isGet()) {
            $this->displayForm($form);
            return;
        }
        $input = $this->completeForm($form);
        $row = DB::getRow('select id from @pf_manager where `name`=? and id<>?', [$input['name'], 0]);
        if ($row) {
            $this->error(['name' => '用户名已经存在']);
        }
        DB::insert($form->table, $input);
        $this->success('添加管理员信息成功');
    }

    /**
     * @param int $id
     * @throws DBException
     */
    #[Method(act: 'edit', method: Method::GET | Method::POST)]
    public function edit(int $id = 0)
    {
        $form = Form::create(ManagerModel::class, 'edit');
        $row = DB::getItem('@pf_manager', $id);
        if ($row == null) {
            $this->error('用户信息不存在');
        }
        $passwordField = $form->getField('password');
        $cfmPassField = $form->getField('cfmPass');
        $passwordField->prompt = '设置账号密码，请输入6-20位字符，如果留空则不修改密码';
        $passwordField->star = false;
        $cfmPassField->star = false;

        unset($passwordField->valid['rule']['r']);
        unset($cfmPassField->valid['rule']['r']);

        if ($this->isGet()) {
            $form->setData($row);
            $form->setHideBox('id', $id);
            $this->displayForm($form);
            return;
        }
        $input = $this->completeForm($form);
        $row = DB::getRow('select id from @pf_manager where `account`=? and id<>?', [$input['account'], $id]);
        if ($row) {
            $this->error(['account' => '用户名已经存在']);
        }
        if (empty($this->post('password'))) {
            unset($input['password']);
        }
        DB::update($form->table, $input, $id);
        $this->success('编辑管理员信息成功');
    }

    /**
     * @param int $id
     * @throws DBException
     */
    #[Method(act: 'delete', method: Method::GET | Method::POST)]
    public function delete(int $id = 0)
    {
        if ($id == 1) {
            $this->error('最高管理员不可删除');
        }
        $row = DB::getItem('@pf_manager', $id);
        if ($row == null) {
            $this->error('用户信息不存在');
        }
        DB::delete('@pf_manager', $id);
        $this->success('删除用户成功');
    }


    /**
     * 修改个人密码
     * @throws DBException|CacheException
     */
    #[Method(act: 'password', method: Method::GET | Method::POST)]
    public function password()
    {
        if ($this->isGet()) {
            $this->assign('row', Request::getSession());
            $this->display('form/manager.password.tpl');
            return;
        }
        $oldPass = $this->post('oldPass:s', '');
        $newPass = $this->post('newPass:s', '');
        if ($oldPass == '') {
            $this->error(['oldPass' => '旧密码不可为空']);
        }
        if ($newPass == '') {
            $this->error(['newPass' => '新密码不可为空']);
        }
        $row = DB::getRow('select id,password from @pf_manager where id=?', $this->adminId);
        if ($row == null) {
            $this->error('账号不存在');
        }
        if (md5($oldPass) != $row['password']) {
            $this->error(['oldPass' => '旧密码不正确，请重新输入']);
        }
        $newPass = md5($newPass);
        DB::update('@pf_manager', ['password' => $newPass], $this->adminId);
        $this->success('修改密码成功');
    }


    /**
     * @throws DBException
     * @throws CacheException
     */
    #[Method(act: 'info', method: Method::GET | Method::POST)]
    public function info()
    {
        $form = Form::create(ManagerModel::class, 'add');
        $row = DB::getItem('@pf_manager', $this->adminId);
        if ($row == null) {
            $this->error('用户信息不存在');
        }
        $form->getField('account')->offEdit = true;
        $form->getField('realName')->offEdit = true;
        $form->getField('password')->close = true;
        $form->getField('cfmPass')->close = true;
        if ($this->isGet()) {
            $form->setData($row);
            $form->setHideBox('id', $this->adminId);
            $this->displayForm($form, 'form/manager_info.tpl');
            return;
        }
        $input = $this->completeForm($form);
        $row = DB::getRow('select id from @pf_manager where `account`=? and id<>?', [$input['account'], $this->adminId]);
        if ($row) {
            $this->error(['name' => '账码已经存在']);
        }
        DB::update($form->table, $input, $this->adminId);
        Request::setSession('adminFace', $input['avatar']);
        $this->success('编辑个人信息成功');
    }


    /**
     * 处理输出字段
     * @param array $list
     * @return array
     */
    protected function listFilter(array $list): array
    {
        $temp = [];
        foreach ($list as $rs) {
            $item = [];
            $item['id'] = $rs['id'];
            $item['account'] = $rs['account'];
            $item['realName'] = $rs['realName'];
            $item['email'] = $rs['email'];
            $item['type'] = match (intval($rs['type'])) {
                1 => '后台管理员',
                2 => '普通管理员',
                default => '其他管理员',
            };
            $item['isLock'] = match (intval($rs['isLock'])) {
                1 => '锁定',
                default => '正常',
            };
            $item['lastTime'] = $rs['lastTime'];
            $item['lastIp'] = $rs['lastIp'];
            $item['_operate'] = $this->listBtn($rs);
            $temp[] = $item;
        }
        return $temp;
    }

    /**
     * 列表按钮
     * @param $rs
     * @return string
     */
    protected function listBtn($rs): string
    {
        $btn = [];
        $btn['编辑'] = [
            'url' => App::url(['act' => 'edit', 'id' => $rs['id']]),
            'icon' => 'icofont-pencil-alt-5',
            'css' => 'blue-bd'
        ];
        if ($rs['id'] != 1) {
            $btn['删除'] = [
                'url' => App::url(['act' => 'delete', 'id' => $rs['id']]),
                'icon' => 'icofont-bin',
                'css' => 'red-bd',
                'ajax' => true,
                'confirm' => '确定要删除该账号了吗？',
                'reload' => 1
            ];
        }
        return BtnUtil::makeButton($btn);
    }

}