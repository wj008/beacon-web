{hook fn='id' rs=null}{$rs.id}{/hook}
{hook fn='icon' rs=null}{if $rs.icon}<i class="{$rs.icon}"></i>{/if}{/hook}
{hook fn='title' rs=null}{$rs.title|raw}{/hook}

{hook fn='_type' rs=null}{if $rs.type==1}菜单{elseif $rs.type==2}<span class="org">控制器</span>{else}<span class="blue">外链</span>{/if}{/hook}
{hook fn='_auth' rs=null}{if !$rs.create}{if $rs.auth}<span class="green">是</span>{else}<span>否</span>{/if}{else}-{/if}{/hook}
{hook fn='app' rs=null}{$rs.app}{/hook}
{hook fn='ctl' rs=null}{if !$rs.create}{$rs.ctl}{else}-{/if}{/hook}
{hook fn='act' rs=null}{if !$rs.create}{$rs.act}{else}-{/if}{/hook}
{hook fn='params' rs=null}{if !$rs.create}{$rs.params}{else}-{/if}{/hook}

{hook fn='url' rs=null}{if !$rs.create}{$rs.url}{else}-{/if}{/hook}
{hook fn='_sort' rs=null}<input class="form-inp tc snumber" name="sort" value="{$rs.sort}" yee-module="ajax" data-url="{url act='sort' id=$rs.id}"/>{/hook}
{hook fn='_allow' rs=null}{if $rs.allow}<span class="green">启用</span>{else}<span>禁用</span>{/if}{/hook}
{hook fn='_operate' rs=null}{$this->listBtn($rs)}{/hook}