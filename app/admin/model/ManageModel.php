<?php


namespace app\admin\model;

use beacon\core\DB;
use beacon\core\DBException;
use beacon\core\Form;
use beacon\widget\Password;
use beacon\widget\Select;
use beacon\widget\Text;

#[Form(title: '账号管理', table: '@pf_manage', template: 'form/manage.tpl')]
class ManageModel
{
    #[Text(
        label: '账号名称',
        validRule: ['r' => '请输入账号名称', 'minLen' => [5, '账号名称至少是5个字符以上'], 'maxLen' => [20, '账号名称过长，不可超过20个字符']],
        prompt: '请输入管理员账号名称，5-20位字母数字组合',
        star: true,
        attrs: ['yee-module' => 'remote', 'data-url' => '/admin/manage/check_name']
    )]
    public string $name = '';

    #[Text(
        label: '真实姓名',
        prompt: '请输入真实姓名',
    )]
    public string $realName = '';


    #[Text(
        label: '电子邮箱',
        prompt: '请输入电子邮箱',
    )]
    public string $email = '';

    #[Select(
        label: '选择角色',
        prompt: '请选择账号角色',
        validRule: ['r' => '请选择所属角色'],
        header: '请选择角色',
        star: true,
        optionFunc: [self::class, 'typeOptions']
    )]
    public int $type = 0;


    #[Password(
        label: '账号密码',
        validRule: ['r' => '请输入新密码', 'minLen' => [6, '密码至少是6个字符以上'], 'maxLen' => [20, '密码过长，不可超过20个字符']],
        prompt: '设置账号密码，请输入6-20位字符',
        star: true,
        attrs: ['name' => 'password', 'id' => 'password']
    )]
    public string $pwd = '';

    #[Password(
        label: '确认密码',
        star: true,
        validRule: ['r' => '请再次输入密码', 'minLen' => [6, '密码至少是6个字符以上'], 'maxLen' => [20, '密码过长，不可超过20个字符'], 'eqTo' => ['#password', '两次输入的密码不一致']],
        prompt: '设置账号密码，请输入6-20位字符',
        offJoin: true
    )]
    public string $cfmPass = '';

    /**
     * @return array
     * @throws DBException
     */
    public static function typeOptions(): array
    {
        if (DB::existsTable('@pf_role')) {
            return DB::getList('select id as value,name as text from @pf_role order by sort asc');
        } else {
            $options = [];
            $options[] = ['value' => 1, 'text' => '后台管理员'];
            $options[] = ['value' => 2, 'text' => '普通管理员'];
        }
        return $options;
    }


}