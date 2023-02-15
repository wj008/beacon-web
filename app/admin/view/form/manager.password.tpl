{extends file='layout/form.tpl'}
{block name='title'}修改账号密码{/block}
{block name='form-header'}
    <a class="yee-back" href="javascript:;"><i class="icofont-key"></i></a>
    <div class="yee-title">修改账号密码</div>
{/block}

{block name='form-content'}
    <form method="post" yee-module="validate ajax">
        <div class="yee-panel">
            <div class="panel-caption">
                <i class="icofont-pencil-alt-3"></i>
                <h3>修改账号密码</h3>
            </div>
            <div class="panel-content">
                <div class="yee-row">
                    <label class="row-label">账号名称：</label>
                    <div class="row-cell">
                        <span class="field-text">{$row.adminName}</span>
                    </div>
                </div>

                <div class="yee-row">
                    <label class="row-label"><em></em>旧密码：</label>
                    <div class="row-cell">
                        <span><input name="oldPass" id="oldPass" class="form-inp text" type="password"/></span>
                        <p class="field-tips">请输入旧密码以确认您的身份</p>
                    </div>
                </div>

                <div class="yee-row">
                    <label class="row-label"><em></em>新密码：</label>
                    <div class="row-cell">
                        <span><input name="newPass" id="newPass" class="form-inp text" type="password"/></span>
                        <p class="field-tips">设置新的账号密码，请输入6-20位字符</p>
                    </div>
                </div>

                <div class="yee-row">
                    <label class="row-label"><em></em>确认密码：</label>
                    <div class="row-cell">
                        <span><input id="cfmPass" class="form-inp text" type="password"/></span>
                        <p class="field-tips">再次输入新密码</p>
                    </div>
                </div>
            </div>
            <div class="yee-submit">
                <label class="submit-label"></label>
                <div class="submit-cell">
                    <input type="submit" class="form-btn red" value="提交">
                </div>
            </div>

        </div>
    </form>
{/block}

{block name="footer" literal=true}
    <script>
        $('#oldPass').data('valid-rule', {r: '请输入旧密码'});
        $('#newPass').data('valid-rule', {r: '请输入新密码', minLen: [6,'密码至少是6个字符以上'], maxLen: [20,'密码过长，不可超过20个字符']});
        $('#cfmPass').data('valid-rule', {r: '请再次输入新密码', eqTo: ['#newPass','两次输入的密码不一致']});
    </script>
{/block}