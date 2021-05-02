{hook fn='id' rs=null}{$rs.id}{/hook}
{hook fn='icon' rs=null}{if $rs.icon}<i class="{$rs.icon}"></i>{/if}{/hook}
{hook fn='title' rs=null}{$rs.title|raw}{/hook}
{hook fn='url' rs=null}{$rs.url}{/hook}
{hook fn='_sort' rs=null}<input class="form-inp tc snumber" name="sort" value="{$rs.sort}" yee-module="ajax" data-url="{url act='sort' id=$rs.id}"/>{/hook}
{hook fn='_allow' rs=null}{if $rs.allow}<span class="green">启用</span>{else}<span>禁用</span>{/if}{/hook}
{hook fn='_operate' rs=null}
{if $rs.create}
    <a href="{url act='add' pid=$rs.id}" class="yee-btn red"><i class="icofont-ui-add"></i>添加子项</a>
{/if}
    <a href="{url act='edit' id=$rs.id}" class="yee-btn blue-bd"><i class="icofont-edit"></i>编辑</a>
    <a href="{url act='delete' id=$rs.id}" yee-module="confirm ajax" on-success="$('#list').emit('reload');" data-confirm="确定要删除该菜单了吗？" class="yee-btn red-bd"><i class="icofont-bin"></i>删除</a>
{/hook}