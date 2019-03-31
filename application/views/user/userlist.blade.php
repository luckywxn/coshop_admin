<div class="bjui-pageHeader " style="background-color:#fefefe; border-bottom:none;padding: 0;">
    <form data-toggle="ajaxsearch" data-options="{searchDatagrid:$.CurrentNavtab.find('#userlist-table')}">
        <fieldset>
            <legend style="font-weight:normal;">高级搜索</legend>
            <div class="bjui-row col-4">
                <label class="row-label">用户账号</label>
                <div class="row-input">
                    <input type="text" name="username" value="{{$username or ''}}" placeholder="用户账号">
                </div>
                <label class="row-label">真实姓名</label>
                <div class="row-input">
                    <input type="text" name="realname" value="{{$realname or ''}}" placeholder="真实姓名">
                </div>
                <label class="row-label">账号状态</label>
                <div class="row-input">
                    <select data-toggle="selectpicker" data-width="100%" name="bar_status">
                        <option value="" selected="">不限</option>
                        <option value="1" >启用</option>
                        <option value="2">停用</option>
                    </select>
                </div>
                <div class="row-input">
                    <div class="btn-group">
                        <button type="submit" class="btn-green" data-icon="search">开始搜索</button>
                    </div>
                </div>

            </div>

        </fieldset>
    </form>
</div>
<div class="bjui-pageContent clearfix">
    <table class="table table-bordered" id="userlist-table" data-toggle="datagrid" data-options="{
        tableWidth:'99%',
        height: '100%',
        gridTitle : '',
        showToolbar: true,
        toolbarItem: 'add,|,edit,|,del,|,refresh',
        dataUrl: 'user/userlistJson',
        dataType: 'json',
        jsonPrefix: 'obj',
        editMode: {dialog:{width:'600',height:'500',title:'账号管理',mask:true}},
        editUrl: '/user/useredit/id/{sysno}',
        delUrl:'/user/userdeljson',
        delPK:'sysno',

        paging: {pageSize:10},
        showCheckboxcol: false,
        linenumberAll: true,
        filterThead:false,
        showLinenumber:true
    }">
        <thead>
            <tr data-options="{name:'sysno'}">
                <th data-options="{name:'username',align:'center'}">用户账号</th>
                <th  data-options="{name:'realname',align:'center'}">真实姓名</th>
                <th  data-options="{name:'rolename',align:'center'}">会员级别</th>
                <th data-options="{name:'lastlogintime',align:'center',type:'date',pattern:'yyyy-MM-dd HH:mm'}">最后登录时间</th>
                <th data-options="{name:'lastloginip',align:'center'}">最后登陆IP</th>
                <th data-options="{name:'created_at',align:'center',type:'date',pattern:'yyyy-MM-dd HH:mm'}">创建时间</th>
                <th data-options="{name:'updated_at',align:'center',type:'date',pattern:'yyyy-MM-dd HH:mm'}">修改时间</th>
                <th data-options="{name:'status',align:'center',render:function(value){return value =='1' ? '启用' : '停用'}}">状态</th>
            </tr>
        </thead>
    </table>
</div>