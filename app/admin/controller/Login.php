<?php


namespace app\admin\controller;


use beacon\core\App;
use beacon\core\Controller;
use beacon\core\DB;
use beacon\core\DBException;
use beacon\core\Method;
use beacon\core\Request;

class Login extends Controller
{

    #[Method(act: 'index', method: Method::GET)]
    public function index()
    {
        $this->display('login.tpl');
    }

    /**
     * @throws DBException
     */
    #[Method(act: 'index', method: Method::POST)]
    public function postLogin()
    {
        $username = $this->post('username:s', '');
        $password = $this->post('password:s', '');
        $code = strtoupper($this->post('code:s', ''));
        if ($username == '') {
            $this->error(['username' => '账号名称不能为空！']);
        }
        if ($password == '') {
            $this->error(['password' => '账号密码不能为空！']);
        }
        $pCode = Request::getSession('validCode', '');
        if ($pCode == '' || $pCode != $code) {
            Request::setSession('code', '');
            $this->error(['code' => '验证码有误！']);
        }
        $row = DB::getRow('select * from @pf_manage where `name`=?', $username);
        if ($row == null) {
            $this->error(['username' => '账号不存在！']);
        }
        if ($row['pwd'] != md5($password)) {
            $this->error(['password' => '用户密码不正确！']);
        }
        Request::setSession('adminId', $row['id']);
        Request::setSession('adminName', $row['name']);
        $value = [];
        if (isset($row['thisTime']) && isset($row['lastTime'])) {
            $value['thisTime'] = date('Y-m-d H:i:s');
            $value['lastTime'] = $row['thisTime'];
        }
        if (isset($row['thisIp']) && isset($row['lastIp'])) {
            $value['thisIp'] = Request::ip();
            $value['lastIp'] = $row['thisIp'];
        }
        if (count($value) > 0) {
            DB::update('@pf_manage', $value, 'id=?', $row['id']);
        }
        $this->success('登录成功', ['back' => App::url('~/index')]);
    }
}