<?php

namespace app\admin\form;

/**
 * Created by PhpStorm.
 * User: wj008
 * Date: 2018/1/5
 * Time: 1:15
 */


use beacon\DB;
use beacon\Form;
use beacon\Request;
use beacon\Route;

class ManageForm extends Form
{
    public $title = '账号管理';
    public $template = 'Manage.form.tpl';

    protected function load()
    {
        $load = [
            'name' => [
                'label' => '账号名称',
                'data-val-rule' => ['r' => true, 'minlen' => 5, 'maxlen' => 20],
                'data-val-message' => ['r' => '请输入账号名称', 'minlen' => '账号名称至少是5个字符以上', 'maxlen' => '账号名称过长，不可超过20个字符'],
                'tips' => '请输入管理员账号名称，5-20位字母数字组合',
                'box-class' => 'form-inp text',
                'type' => 'remote',
                'data-url' => Route::url('~/Manage/checkName'),
                'remote-func' => function ($value) {
                    $id = Request::param('id:i', 0);
                    $row = DB::getRow('select id from @pf_manage where `name`=? and id<>?', [$value, $id]);
                    if ($row) {
                        return false;
                    }
                    return true;
                },
            ],
            'realName' => [
                'label' => '真实姓名',
                'tips' => '请输入真实姓名',
                'box-class' => 'form-inp text',
            ],
            'email' => [
                'label' => '电子邮箱',
                'tips' => '请输入电子邮箱',
                'box-class' => 'form-inp text',
            ],
            'type' => [
                'label' => '选择角色',
                'tips' => '请选择账号角色',
                'box-class' => 'form-inp select',
                'type' => 'select',
                'data-val-rule' => ['r' => true],
                'data-val-message' => ['r' => '请选择所属角色'],
                'header' => '请选择角色',
                'options' => function () {
                    $options = [];
                    if (DB::existsTable('@pf_role')) {
                        $rows = DB::getList('select id,name from @pf_role order by sort asc');
                        foreach ($rows as $rs) {
                            $item = [];
                            $item['value'] = isset($rs['id']) ? $rs['id'] : '';
                            $item['text'] = isset($rs['name']) ? $rs['name'] : '';
                            $options[] = $item;
                        }
                    } else {
                        $options[] = ['value' => 1, 'text' => '后台管理员'];
                        $options[] = ['value' => 2, 'text' => '普通管理员'];
                    }
                    return $options;
                },
            ],

            'pwd' => [
                'label' => '账号密码',
                'type' => 'password',
                'data-val-rule' => function () {
                    if ($this->isEdit()) {
                        return ['minlen' => 6, 'maxlen' => 20];
                    }
                    return ['r' => true, 'minlen' => 6, 'maxlen' => 20];
                },
                'data-val-message' => function () {
                    if ($this->isEdit()) {
                        return ['minlen' => '密码至少是6个字符以上', 'maxlen' => '密码过长，不可超过20个字符'];
                    }
                    return ['r' => '请输入新密码', 'minlen' => '密码至少是6个字符以上', 'maxlen' => '密码过长，不可超过20个字符'];
                },
                'tips' => function () {
                    if ($this->isEdit()) {
                        return '设置账号密码，请输入6-20位字符，如果留空则不修改密码';
                    }
                    return '设置账号密码，请输入6-20位字符';
                },
                'encode-func' => 'md5',
                'box-class' => 'form-inp text',
                'box-name' => 'password'
            ],
            'cfmPass' => [
                'label' => '确认密码',
                'type' => 'password',
                'close' => true,
                'data-val-rule' => ['eqto' => '#password'],
                'data-val-message' => ['eqto' => '两次输入的密码不一致'],
                'tips' => '再次输入密码',
                'encode-func' => 'md5',
                'box-class' => 'form-inp text',
                'box-name' => 'cfmPass'
            ],
        ];
        return $load;
    }
}