{extends file='layout/form.tpl'}
{block name='title'}{$form->title}{/block}
{block name='form-header'}
    <a class="yee-back" href="{url act='index' ctl='SysMenu'}"><i class="icofont-reply"></i></a>
    <div class="yee-title">{$form->title}</div>
{/block}

{block name='form-content'}
    <form method="post" yee-module="validate ajax">
        <div class="yee-panel">
            <div class="panel-caption">
                <i class="icofont-pencil-alt-3"></i>
                <h3>{if $form->isAdd()}新增菜单栏目{else}编辑菜单栏目{/if}</h3>
            </div>
            <div class="panel-content">
                {foreach from=$form->getViewFields() item=field}
                    <div class="yee-row" id="row_{$field->boxId}">
                        <label class="row-label">{if $field->star}<em></em>{/if}{$field->label}：</label>
                        <div class="row-cell">
                            {input field=$field}
                            <span id="{$field->boxId}-validation"></span>
                            {if $field->prompt}<p class="yee-field-tips">{$field->prompt|raw}</p>{/if}
                        </div>
                    </div>
                {/foreach}
            </div>
            <div class="yee-submit">
                <label class="submit-label"></label>
                <div class="submit-cell">
                    {$form->fetchHideBox()}
                    <input type="submit" class="form-btn red" value="提交">
                    <input type="hidden" name="__BACK__" value="{url act='index' ctl='SysMenu'}">
                    <a href="{url act='index' ctl='SysMenu'}" class="form-btn back">返回</a>
                </div>
            </div>

        </div>
    </form>
{/block}