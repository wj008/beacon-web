{extends file="layout/layout.tpl"}
{block name="wrapper"}
    <div class="wrapper yee-wrap scrollbar">
        {block name='list-header'}{/block}
        {block name='list-tab'}{/block}
        {block name='list-attention'}{/block}
        <div class="yee-list-main">
            {block name='list-search'}{/block}
            <div class="yee-list">
                {block name='list-table'}{/block}
                {block name='list-pagebar'}{/block}
            </div>
            {block name='list-information'}{/block}
        </div>
    </div>
{/block}
{block name='footer'}
    <script src="/static/admin/js/list.js"></script>
    {block name='footer'}{/block}
{/block}
