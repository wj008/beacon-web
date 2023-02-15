{extends file="layout/layout.tpl"}

{block name="wrapper"}
    <div class="yee-form-wrap">
        <div style="position: relative">
            <div class="yee-form-header">
                {block name='form-header'}{/block}
            </div>
            <div class="yee-wrap">
                {block name='form-content'}{/block}
            </div>
        </div>
    </div>
{/block}
