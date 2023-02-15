<?php


namespace app\admin\model;

use beacon\core\DB;
use beacon\core\DBException;
use beacon\core\Form;
use beacon\widget\Check;
use beacon\widget\Integer;
use beacon\widget\RadioGroup;
use beacon\widget\Select;
use beacon\widget\SelectDialog;
use beacon\widget\Text;
use beacon\widget\Textarea;

#[Form(title: '菜单管理', table: '@pf_sys_menu', template: 'form/sys_menu.tpl')]
class SysMenuModel
{

    #[Text(
        label: '菜单名称',
        validRule: ['r' => '请输入菜单名称'],
        prompt: '请输入系统菜单名称'
    )]
    public string $name = '';

    #[Check(
        label: '是否启用',
        after: '勾选启用菜单',
    )]
    public int $allow = 1;

    #[Select(
        label: '所属上级',
        header: [0, '顶级菜单'],
        prompt: '选择该菜单所在的上级菜单',
        optionFunc: [self::class, 'pidOptions']
    )]
    public int $pid = 0;


    #[RadioGroup(
        label: '链接类型',
        dynamic: [
            [
                'eq' => '1',
                'show' => 'app,icon',
                'hide' => 'url,ctl,act,params,blank,auth'
            ],
            [
                'eq' => '2',
                'show' => 'app,ctl,act,auth,params,blank',
                'hide' => 'url,icon'
            ],
            [
                'eq' => '3',
                'show' => 'app,url,params,blank',
                'hide' => 'icon,ctl,act,auth'
            ],
        ],
        options: [
            [
                'value' => '1',
                'text' => '菜单类型',
                'tips' => ''
            ],
            [
                'value' => '2',
                'text' => '控制器',
                'tips' => ''
            ],
            [
                'value' => '3',
                'text' => '链接方式',
                'tips' => ''
            ],
        ]
    )]
    public int $type = 1;


    #[Text(
        label: 'URL路径',
    )]
    public string $url = '';

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

    #[Check(
        label: '新窗口打开',
        after: '勾选为新窗口打开',
    )]
    public bool $blank = false;


    #[Check(
        label: '是否权限节点',
        after: '勾选为权限节点',
    )]
    public bool $auth = false;

    #[SelectDialog(
        label: 'ICON样式',
        prompt: '选择该菜单所在的上级菜单',
        url: '~/SysMenu/icon',
        attrs: ['data-width' => 860, 'style' => 'width:300px'],
    )]
    public string $icon = '';

    #[Integer(
        label: '排序',
        prompt: '越小越靠前'
    )]
    public int $sort = 0;

    #[Textarea(
        label: '备注信息',
    )]
    public string $remark = '';

    /**
     * @return array
     * @throws DBException
     */
    public static function pidOptions(): array
    {
        $items = [];
        $rows = DB::getList('select * from @pf_sys_menu where pid=0 order by sort asc');
        foreach ($rows as $item) {
            $items[] = [$item['id'], $item['name']];
            $temp = DB::getList('select * from @pf_sys_menu where pid=?  order by sort asc', $item['id']);
            foreach ($temp as $rs) {
                $items[] = [$rs['id'], '+--- ' . $rs['name']];
            }
        }
        return $items;
    }

}