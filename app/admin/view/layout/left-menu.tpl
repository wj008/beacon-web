{foreach from=$list item=rs}
    <div class="item">
        <div class="name"><a href="javascript:;"><i class="{$rs.icon}"></i>{$rs.name}</a></div>
        <div class="list">
            <ul>
                {foreach from=$rs.items item=xrs}
                    <li><a {if $xrs.active} class="on"{/if} {if $xrs.blank}target="_blank"{/if} href="{$xrs.url}">{$xrs.name}</a></li>
                {/foreach}
            </ul>
        </div>
    </div>
{/foreach}