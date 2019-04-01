<div class="bjui-pageHeader " style="background-color:#fefefe; border-bottom:none;padding: 0;">
    <form data-toggle="ajaxsearch" data-options="{searchDatagrid:$.CurrentNavtab.find('#merchantlist-table')}">
        <fieldset>
            <legend style="font-weight:normal;">高级搜索</legend>
            <div class="bjui-row col-4">
                <label class="row-label">商户编号</label>
                <div class="row-input">
                    <input type="text" name="merchant_no" value="{{$merchant_no}}" placeholder="商户编号">
                </div>
                <label class="row-label">商户名称</label>
                <div class="row-input">
                    <input type="text" name="merchant_name" value="{{$merchant_name}}" placeholder="商户名称">
                </div>
                <label class="row-label">状态</label>
                <div class="row-input">
                    <select data-toggle="selectpicker" data-width="100%" name="status">
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
    <table class="table table-bordered" id="merchantlist-table" data-toggle="datagrid" data-options="{
        tableWidth:'99%',
        height: '100%',
        gridTitle : '',
        showToolbar: true,
        toolbarItem: 'add,|,edit,|,del,|,refresh',
        dataUrl: 'merchant/merchantListJson',
        dataType: 'json',
        jsonPrefix: 'obj',
        editMode: {dialog:{width:'600',height:'500',title:'商户管理',mask:true}},
        editUrl: '/merchant/merchantedit/id/{sysno}',
        delUrl:'/merchant/merchantdeljson',
        delPK:'sysno',
        paging: {pageSize:10},
        showCheckboxcol: false,
        linenumberAll: true,
        filterThead:false,
        showLinenumber:true
    }">
        <thead>
            <tr data-options="{name:'sysno'}">
                <th data-options="{name:'merchant_no',align:'center'}">商户编号</th>
                <th  data-options="{name:'merchant_name',align:'center'}">商户名称</th>
                <th  data-options="{name:'contact_name',align:'center'}">联系人</th>
                <th  data-options="{name:'contact_tel',align:'center'}">联系电话</th>
                <th  data-options="{name:'contact_email',align:'center'}">联系邮箱</th>
                <th  data-options="{name:'contact_address',align:'center'}">联系地址</th>
                <th data-options="{name:'created_at',align:'center',type:'date',pattern:'yyyy-MM-dd HH:mm'}">创建时间</th>
                <th data-options="{name:'updated_at',align:'center',type:'date',pattern:'yyyy-MM-dd HH:mm'}">修改时间</th>
                <th data-options="{name:'merchant_status',align:'center',render:function(value){return value =='1' ? '启用' : '停用'}}">状态</th>
            </tr>
        </thead>
    </table>
</div>