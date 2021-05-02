<h1>{$info.name}</h1>
{foreach from=$rows item=rs}
    <dl>
        <dt><i class="{$rs.icon}"></i><span>{$rs.name}</span></dt>
        {foreach from=$rs.childs item=xrs}
            <dd><a href="{$xrs.url}" target="main">{$xrs.name}</a></dd>
        {/foreach}
    </dl>
{/foreach}