{*用于创建单一插件容器集合的hook函数模板*}
{hook fn='single' field=null form=null}
<!-- 名字 -->
<div class="yee-row" id="row_{$form->getField('name')->boxId}">
<label class="row-label">名字：</label>
<div class="row-cell">
{input field=$form->getField('name')}
</div>
</div>
<!-- 性别 -->
<div class="yee-row" id="row_{$form->getField('gender')->boxId}">
<label class="row-label">性别：</label>
<div class="row-cell">
{input field=$form->getField('gender')}
</div>
</div>
<!-- 年龄 -->
<div class="yee-row" id="row_{$form->getField('age')->boxId}">
<label class="row-label">年龄：</label>
<div class="row-cell">
{input field=$form->getField('age')}
</div>
</div>
<!-- 日期 -->
<div class="yee-row" id="row_{$form->getField('date')->boxId}">
<label class="row-label">日期：</label>
<div class="row-cell">
{input field=$form->getField('date')}
</div>
</div>
{/hook}
{*用于创建多行插件容器集合的hook函数模板 lastIndex 最后行的索引，body 已有item的模板渲染数据，source 用于js动态创建的模板数据base64  *}
{hook fn='multiple-wrap' field=null form=null lastIndex=0 body=null source=null}
<div class="yee-row" id="row_{$field->boxId}">
<label class="row-label">{$field->label}：</label>
<div class="row-cell">
<div yee-module="container" id="{$field->boxId}" data-index="{$lastIndex}"{if $field->dataMinSize} data-min-size="{$field->dataMinSize}"{/if}{if $field->dataMaxSize} data-max-size="{$field->dataMaxSize}"{/if} data-source="{$source}">
<div class="container-wrap" style="display: block">
{$body|raw}
</div>
<div style="display: block;">
<a href="javascript:;" name="add" class="yee-btn"><i class="icofont-plus-circle"></i>新增行</a>
{if $field->tips}<span class="field-tips">{$field->tips}</span>{/if} <span id="{$field->boxId}-validation"></span>
</div>
</div>
</div>
</div>
{/hook}
{*用于创建多行插件容器中每行的数据hook函数模板 form 插件的表单 index 每项的索引*}
{hook fn='multiple-item' field=null form=null index=null}
<div class="container-item">
<div class="yee-container-title">
<label class="inline-label" style="text-align: left;">&nbsp;&nbsp;  第 <span name="index" class="red2" style="font-size: 18px;"></span>项&nbsp;&nbsp;&nbsp;</label>
{if $field->viewRemoveBtn}<a href="javascript:;" class="yee-btn" name="remove"><i class="icofont-minus-circle"></i>移除</a>{/if}
{if $field->viewInsertBtn}<a href="javascript:;" name="insert" class="yee-btn"><i class="icofont-puzzle"></i>插入</a>{/if}
{if $field->viewSortBtn}<a href="javascript:;" name="upsort" class="yee-btn"><i class="icofont-long-arrow-up"></i>上移</a><a href="javascript:;" name="dnsort" class="yee-btn"><i class="icofont-long-arrow-down"></i>下移</a>{/if}
</div>
<div class="yee-container-body">
<!-- 名字 -->
<div class="yee-row" id="row_{$form->getField('name')->boxId}">
<label class="row-label">名字：</label>
<div class="row-cell">
{input field=$form->getField('name')}
</div>
</div>
<!-- 性别 -->
<div class="yee-row" id="row_{$form->getField('gender')->boxId}">
<label class="row-label">性别：</label>
<div class="row-cell">
{input field=$form->getField('gender')}
</div>
</div>
<!-- 年龄 -->
<div class="yee-row" id="row_{$form->getField('age')->boxId}">
<label class="row-label">年龄：</label>
<div class="row-cell">
{input field=$form->getField('age')}
</div>
</div>
<!-- 日期 -->
<div class="yee-row" id="row_{$form->getField('date')->boxId}">
<label class="row-label">日期：</label>
<div class="row-cell">
{input field=$form->getField('date')}
</div>
</div>
</div>
</div>
{/hook}