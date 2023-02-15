{extends file='layout/form.tpl'}
{block name='title'}角色权限设置{/block}

{block name='form-header'}
    <div class="yee-title">角色权限设置</div>
    <div class="yee-toolbar"><a href="javascript:history.back();" class="refresh-btn"><i class="icofont-reply"></i>返回</a></div>
{/block}

{block name='form-content'}
    <form method="post" yee-module="validate ajax" data-mode="2">

        <div class="yee-tab">
            <ul yee-module="form-tab">
                {foreach from=$items item="rs" key='key'}
                    <li data-bind-name="{$key}"{if $key=='admin'} class="curr"{/if}><a href="javascript:void(0);">{$rs.name}</a></li>
                {/foreach}
            </ul>
        </div>

        <div class="yee-panel">
            <div class="yee-row">
                <label class="row-label">角色名称：</label>
                <div class="row-cell">
                    {$row.name}
                </div>
            </div>

            {foreach from=$items item="rs" key='key'}
                <div class="panel-content" name="{$key}" {if $key!='admin'} style="display: none" {/if}>
                    <div class="yee-row">
                        <label class="row-label">权限设置：</label>
                        <div class="row-cell">

                            {foreach from=$rs.options item=dir}
                                <div class="yee-line" style="background:#f8f8f8; border-left: none;">
                                    <label class="line-label">{$dir.name}</label>
                                    <label style="display:inline-block; margin-left: 20px">
                                        <input type="checkbox" class="form-inp" onclick="$(this).parents('div.yee-line:first').next('div.nodes').find(':input').prop('checked',$(this).prop('checked'));">
                                        <span>全部</span>
                                    </label>
                                </div>
                                <div class="nodes">
                                    {foreach from=$dir.items item='item'}
                                        <div class="items">
                                            <label style="width:160px;display:inline-block; line-height: 40px;height: 40px">
                                                <input type="checkbox" name="n[]" class="form-inp" value="{$item.id}" {if $item.checked} checked="checked"{/if}>
                                                <span>{$item.name}</span>
                                            </label>

                                            {foreach from=$item.items item=opt attr=attr}
                                                <label style="width:160px;display:inline-block; line-height: 40px;height: 40px">
                                                    <input type="checkbox" name="n[]" class="form-inp" value="{$opt.id}" {if $opt.checked} checked="checked"{/if}>
                                                    <span class="blue">{$opt.name}</span>
                                                </label>
                                            {/foreach}
                                        </div>
                                    {/foreach}
                                </div>
                            {/foreach}
                        </div>
                    </div>
                </div>
            {/foreach}

            <div class="yee-submit">
                <label class="submit-label"></label>
                <div class="submit-cell">
                    <input type="hidden" id="roleId" value="{$row.id}">
                    <input type="submit" class="form-btn red" value="提交">
                    <input type="hidden" name="__BACK__" value="{$this->referrer()}">
                    <a href="javascript:history.back();" class="form-btn back">返回</a>
                </div>
            </div>
        </div>
    </form>
{/block}