{extends file='layout/dialog_list.tpl'}
{block name="title"}权限节点{/block}
{block name="caption"} 系统-权限节点{/block}

{block name='list-header'}
    <div class="yee-list-header">
        <div class="yee-caption"><i class="icofont-listine-dots"></i> 系统-系统菜单</div>
        <div class="yee-toolbar">
            <span> 共 <span id="records-count">0</span> 条记录</span>
            <a href="javascript:window.location.reload()" class="refresh-btn"><i class="icofont-refresh"></i>刷新</a>
            <a id="add-btn" href="{url act='add_node' pid=$pid}" yee-module="dialog" data-height="660" data-width="900"
               on-success="$('#list').emit('reload');" class="yee-btn red"><i class="icofont-plus"></i>添加节点</a>
        </div>
    </div>
{/block}

{block name='list-search'}
    <div class="yee-list-search">
        <form id="searchForm" yee-module="search-form" data-bind="#list">
            <div class="yee-cell">
                <label class="yee-label"><em></em>节点名称：</label>
                <span><input name="name" class="form-inp text" type="text"/></span>
            </div>
            <div class="yee-cell">
                <input type="hidden" name="pid" value="{$pid}">
                <input class="form-btn blue" value="查询" type="submit"/>
                <input class="form-btn normal" value="重置" type="reset"/>
                <input type="hidden" name="sort">
            </div>
        </form>
    </div>
{/block}

{block name='list-table'}
    <table id="list" class="yee-datatable" yee-module="datatable" data-auto-load="true" width="100%">
        <thead>
        <tr>
            <th width="40">ID</th>
            <th align="left">节点名称</th>
            <th width="100">应用</th>
            <th width="100">控制器</th>
            <th width="100">方法</th>
            <th width="100">参数</th>
            <th width="80">排序</th>
            <th width="80">状态</th>
            <th width="180" width="180" data-fixed="right">操作</th>
        </tr>
        </thead>
        <tbody yee-template>
        <tr v-for="rs in list">
            <td align="center" v-html="rs.id"></td>
            <td v-html="rs.title"></td>
            <td align="center" v-html="rs.app"></td>
            <td align="center" v-html="rs.ctl"></td>
            <td align="center" v-html="rs.act"></td>
            <td align="center" v-html="rs.params"></td>
            <td align="center" v-html="rs._sort"></td>
            <td align="center" v-html="rs._allow"></td>
            <td align="center" v-html="rs._operate"></td>
        </tr>
        <tr v-if="list.length==0">
            <td colspan="100">没有任何数据！</td>
        </tr>
        </tbody>
    </table>
{/block}

{block name='list-pagebar'}
    <div yee-module="pagebar" data-bind="#list" class="yee-pagebar">
        <div yee-template="vue">
            <div class="pagebar" v-html="barCode"></div>
            <div class="pagebar-info">共有信息：<span v-text="recordsCount"></span> 页次：<span v-text="page"></span>/<span v-text="pageCount"></span> 每页<span v-text="pageSize"></span></div>
        </div>
    </div>
{/block}