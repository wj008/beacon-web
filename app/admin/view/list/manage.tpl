{extends file='layout/list.tpl'}
{block name="title"}管理员{/block}
{block name="caption"} 管理员-账号管理{/block}

{block name='list-header'}
    <div class="yee-list-header">
        <div class="yee-caption"><i class="icofont-users-alt-2"></i> 管理员-账号管理</div>
        <div class="yee-toolbar">
            <span> 共 <span id="records-count">0</span> 条记录</span>
            <a href="javascript:window.location.reload()" class="refresh-btn"><i class="icofont-refresh"></i>刷新</a>
            <a id="add-btn" href="{url act='add'}" class="yee-btn red"><i class="icofont-patient-file"></i>添加账号</a>
        </div>
    </div>
{/block}

{block name='list-search'}
    <div class="yee-list-search">
        <form id="searchForm" yee-module="search-form" data-bind="#list">
            <div class="yee-cell">
                <label class="yee-label"><em></em>用户名/真实姓名：</label>
                <span><input name="name" class="form-inp text" type="text"/></span>
            </div>
            <div class="yee-cell">
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
            <th width="40" data-order="id">ID</th>
            <th width="150" align="left" data-order="name">账号名称</th>
            <th width="150" align="left" data-order="realName">真实姓名</th>
            <th class="sort down">电子邮箱</th>
            <th width="80" data-order="type">类型</th>
            <th width="80">状态</th>
            <th width="180">上次登录时间</th>
            <th width="180">上次登录IP</th>
            <th width="180" data-fixed="right">操作</th>
        </tr>
        </thead>

        <tbody yee-template>
        <tr v-for="rs in list">
            <td v-html="rs.id"></td>
            <td v-html="rs.name"></td>
            <td v-html="rs.realName"></td>
            <td v-html="rs.email"></td>
            <td align="center" v-html="rs.type"></td>
            <td align="center" v-html="rs.isLock"></td>
            <td align="center" v-html="rs.lastTime"></td>
            <td align="center" v-html="rs.lastIp"></td>
            <td class="opt-btns" v-html="rs._operate"></td>
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