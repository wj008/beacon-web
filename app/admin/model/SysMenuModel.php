<?php


namespace app\admin\model;

use beacon\core\DB;
use beacon\core\DBException;
use beacon\core\Form;
use beacon\widget\Check;
use beacon\widget\Integer;
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
        optionFunc:[self::class, 'pidOptions']
    )]
    public int $pid = 0;

    #[SelectDialog(
        label: 'ICON样式',
        prompt: '选择该菜单所在的上级菜单',
        url: '~/SysMenu/icon',
        attrs: ['data-width' => 860, 'style' => 'width:300px'],
    )]
    public string $icon = '';

    #[Text(
        label: '连接',
        prompt: '连接地址,仅最后一层需要输入'
    )]
    public string $url = '';

    #[Check(
        label: '是否新窗口',
        after: '是否新窗口打开',
    )]
    public int $blank = 0;

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