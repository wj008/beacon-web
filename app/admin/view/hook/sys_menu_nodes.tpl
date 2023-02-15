{hook fn='id' rs=null}{$rs.id}{/hook}
{hook fn='title' rs=null}{$rs.name}{/hook}
{hook fn='app' rs=null}{$rs.app}{/hook}
{hook fn='ctl' rs=null}{$rs.ctl}{/hook}
{hook fn='act' rs=null}{$rs.act}{/hook}
{hook fn='params' rs=null}{$rs.params}{/hook}
{hook fn='_sort' rs=null}<input class="form-inp tc snumber" name="sort" value="{$rs.sort}" yee-module="ajax" data-url="{url act='sort' id=$rs.id}"/>{/hook}
{hook fn='_allow' rs=null}{if $rs.allow}<span class="green">启用</span>{else}<span>禁用</span>{/if}{/hook}
{hook fn='_operate' rs=null}{$this->nodeBtn($rs)}{/hook}