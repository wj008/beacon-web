{extends file='layout/layoutList.tpl'}
{block name='title'}死得快{/block}

{block name='list-header'}
<div class="yee-list-header">
<div class="yee-caption">死得快</div>
<div class="yee-toolbar">
<span> 共 <span id="records-count">0</span> 条记录</span>
<a href="javascript:window.location.reload()" class="refresh-btn"><i class="icofont-refresh"></i>刷新</a>
<a href="{url act='add'}"  class="yee-btn red"><i class="icofont-patient-file"></i>新增</a>
</div></div>
{/block}

{block name='list-search'}
{if isset($search)}
{function fn=searchItem box=null}{if $box->prev}{call fn=searchItem box=$box->prev}{/if}
<div class="yee-cell"><label>{if isset($box->label[0]) && $box->label[0]!='!'}{$box->label}：{/if}{input field=$box}</label></div>
{if $box->next}{call fn=searchItem box=$box->next}{/if}{/function}
<div class="yee-list-search">
<div class="fl">
<form id="searchForm" yee-module="search-form" data-bind="#list">
{foreach from=$search->getViewFields('base') item=box}
{call fn=searchItem box=$box}
{/foreach}
{assign var=seniorItem value=$search->getViewFields('senior')}
{if count($seniorItem)}
<div class="senior-item">
{foreach from=$seniorItem item=box}
<div class="form-box" style="display: block;">
{if $box->prev}{call fn=searchItem box=$box->prev}{/if}
<label class="form-label">{if isset($box->label[0]) && $box->label[0]!='!'}{$box->label}：{/if}{input field=$box}</label>
{if $box->next}{call fn=searchItem box=$box->next}{/if}
</div>
{/foreach}
</div>
{/if}
<div class="yee-cell">
<input class="form-btn blue" value="查询" type="submit"/>
<input class="form-btn normal" value="重置" type="reset"/><input type="hidden" name="sort">
{$search->fetchHideBox()}
{if count($seniorItem)}
<a class="senior-btn" onclick="$('.yee-list-search').toggleClass('senior')">高级搜索<i></i></a>
{/if}
</div>
</form>
</div>
<div class="fr tr">
<a href="{url act='deleteChoice'}" yee-module="confirm ajax choice" data-confirm@msg="确定要删除所选数据了吗？" class="yee-btn red-bd" on-success="$('#list').emit('reload');"><i class="icofont-bin"></i>删除所选</a>
<a href="{url act='allowChoice'}" yee-module="ajax choice" class="yee-btn" on-success="$('#list').emit('reload');"><i class="icofont-verification-check"></i>审核所选</a>
<a href="{url act='revokeChoice'}" yee-module="ajax choice" class="yee-btn" on-success="$('#list').emit('reload');"><i class="icofont-not-allowed"></i>禁用所选</a>
</div><div class="clear"></div>
</div>
{/if}
{/block}

{block name='list-table'}
<table id="list" width=100% class="yee-datatable" yee-module="datatable" data-auto-load="true">
<thead>
<tr>
<th width="40"><input type="checkbox"></th>
<th data-order="id" align="center" width="80">id</th>
<th align="center" width="80">名称</th>
<th align="center" width="80">封面</th>
<th align="center" width="80">允许</th>
<th align="center" width="80">锁定</th>
<th align="right" width="180">操作</th>
</tr>
</thead>
<tbody yee-template>
<tr yee-each="list" yee-item="rs">
<td align="center" :html="rs._0"></td>
<td align="center" :html="rs._id"></td>
<td align="center" :html="rs._name"></td>
<td align="center" :html="rs._cover"></td>
<td align="center" :html="rs._allow"></td>
<td align="center" :html="rs._lock"></td>
<td align="right" :html="rs._6"></td>
</tr>
<tr yee-if="list.length==0"><td colspan="100"> 没有任何数据信息....</td></tr>
</tbody>
</table>
{/block}

{block name='list-pagebar'}
<div yee-module="pagebar" data-bind="#list" class="yee-pagebar">
    <div yee-template class="pagebar" :html="barCode"></div>
    <div yee-template class="pagebar-info">共有信息：<span :text="count"></span> 页次：<span :text="page"></span>/<span :text="pageCount"></span> 每页<span :text="pageSize"></span></div>
</div>
{/block}