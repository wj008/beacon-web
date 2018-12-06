{extends file='layout/layoutForm.tpl'}
{block name='title'}{$form->title}{/block}

{block name='form-header'}
<a class="yee-back" href="javascript:history.back();"><i class="icofont-reply"></i></a>
<div class="yee-title">{$form->title}</div>
{/block}

{block name='form-content'}
<form method="post" yee-module="validate">
<div class="yee-panel">

<div class="panel-caption">
<i class="icofont-pencil-alt-3"></i>
<h3>{if $form->isAdd()}新增{else}编辑{/if}商品订单</h3>
</div>
<div class="panel-content">
<!-- 允许 -->
<div class="yee-row" id="row_allow">
<label class="row-label">允许：</label>
<div class="row-cell">
{input field=$form->getField('allow')}
</div>
</div>
<!-- 锁定 -->
<div class="yee-row" id="row_lock">
<label class="row-label">锁定：</label>
<div class="row-cell">
{input field=$form->getField('lock')}
<div class="yee-row-inline" id="row_name">
<label class="inline-label">名称：</label>
<span style="margin-right: 10px">
{input field=$form->getField('name')}
</span>
</div>
</div>
</div>
<!-- 下拉框 -->
<div class="yee-row" id="row_dropDownBox">
<label class="row-label">下拉框：</label>
<div class="row-cell">
{input field=$form->getField('dropDownBox')}
</div>
</div>
<!-- 封面 -->
<div class="yee-row" id="row_cover">
<label class="row-label">封面：</label>
<div class="row-cell">
{input field=$form->getField('cover')}
</div>
</div>
<!-- 插件 -->
{input field=$form->getField('plugin')}
<!-- 内容 -->
<div class="yee-row" id="row_content">
<label class="row-label">内容：</label>
<div class="row-cell">
{input field=$form->getField('content')}
</div>
</div>
</div>
<div class="yee-submit">
<label class="submit-label"></label>
<div class="submit-cell">
{$form->fetchHideBox()|raw}
<input type="submit" class="form-btn red" value="提交">
<input type="hidden" name="__BACK__" value="{$this->getReferrer()}">
<a href="javascript:history.back();" class="form-btn back">返回</a>
</div>
</div>
</div>
{/block}