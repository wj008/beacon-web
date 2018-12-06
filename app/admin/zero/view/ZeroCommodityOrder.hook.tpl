{hook fn='_0' rs=null}<input type="checkbox" name="choice" value="{$rs.id}">{/hook}
{hook fn='_id' rs=null}{$rs.id}{/hook}
{hook fn='_name' rs=null}{$rs.name}{/hook}
{hook fn='_cover' rs=null}<img src="{$rs.cover}" height="40"/>{/hook}
{hook fn='_allow' rs=null}{if $rs.allow}<span class="green">正常</span>{else}<span class="gray">禁用</span>{/if}{/hook}
{hook fn='_lock' rs=null}{if $rs.lock}<span class="gray">锁定</span>{else}<span class="green">正常</span>{/if}{/hook}
{hook fn='_6' rs=null}<a href="{url act='toggleAllow' id=$rs.id}" yee-module="ajax"  class="yee-btn reload">{if $rs.allow}<i class="icofont-not-allowed"></i>禁用{else}<i class="icofont-verification-check"></i>审核{/if}</a>
<a href="{url act='edit' id=$rs.id}" class="yee-btn blue-bd"><i class="icofont-pencil-alt-5"></i>编辑</a>
<a href="{url act='delete' id=$rs.id}" yee-module="confirm ajax" data-confirm@msg="确定要删除该数据了吗？" class="yee-btn red-bd reload"><i class="icofont-bin"></i>删除</a>{/hook}