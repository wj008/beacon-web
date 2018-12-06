{hook fn='id' rs=null}{$rs.id}{/hook}
{hook fn='name' rs=null}{$rs.name}{/hook}
{hook fn='realName' rs=null}{$rs.realName}{/hook}
{hook fn='email' rs=null}{$rs.email}{/hook}
{hook fn='type' rs=null}{$rs.type|match:[1=>'后台管理员',2=>'普通管理员']:'其他管理员'}{/hook}
{hook fn='isLock' rs=null}{$rs.isLock|match:1:'锁定':'正常'}{/hook}
{hook fn='lastTime' rs=null}{$rs.lastTime|date_format:'Y-m-d H:i:s'}{/hook}
{hook fn='lastIp' rs=null}{$rs.lastIp}{/hook}
{hook fn='_operate' rs=null}
    <a href="{url act='edit' id=$rs.id}" class="yee-btn blue-bd"><i class="icofont-pencil-alt-5"></i>编辑</a>
{if $rs.id != 1}
    <a href="{url act='del' id=$rs.id}" yee-module="confirm ajax" data-confirm@msg="确定要删除该账号了吗？" on-success="$('#list').emit('reload');" class="yee-btn red-bd"><i class="icofont-bin"></i>删除</a>
{/if}
{/hook}
