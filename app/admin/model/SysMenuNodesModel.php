<?php


namespace app\admin\model;

use beacon\core\Form;
use beacon\widget\Check;
use beacon\widget\Integer;
use beacon\widget\Text;
use beacon\widget\Textarea;

#[Form(title: '菜单管理', table: '@pf_sys_menu', template: 'form/sys_menu.tpl')]
class SysMenuNodesModel
{

    #[Text(
        label: '菜单名称',
        validRule: ['r' => '请输入菜单名称'],
        prompt: '请输入系统菜单名称'
    )]
    public string $name = '';

    #[Check(
        label: '是否启用',
        after: '勾选启用节点',
    )]
    public int $allow = 1;


    #[Text(
        label: '所属应用',
    )]
    public string $app = 'admin';

    #[Text(
        label: '控制器',
        prompt: '请输入控制器名称'
    )]
    public string $ctl = '';

    #[Text(
        label: '方法名称',
        prompt: '多个可用,隔开',
        attrs: ['style' => 'width:300px'],
    )]
    public string $act = '';

    #[Text(
        label: '请求参数',
        attrs: ['style' => 'width:300px'],
    )]
    public string $params = '';

    #[Integer(
        label: '排序',
        prompt: '越小越靠前'
    )]
    public int $sort = 0;

    #[Textarea(
        label: '备注信息',
    )]
    public string $remark = '';


}