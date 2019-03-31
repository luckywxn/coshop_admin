<div class="bjui-pageContent">
<form id="treeform" action="{{$action}}" class="datagrid-edit-form" data-toggle="validate" data-data-type="json">
    <input type="hidden" id="treedata" name="treedata">
    <input type="hidden" name="id" value="{{$id}}">
    <div class="bjui-row col-2">
        <label class="row-label">角色编码</label>
        <div class="row-input required">
            <input type="text" name="roleno" value="{{$roleno}}" data-rule="required">
        </div>

        <label class="row-label">角色名称</label>
        <div class="row-input required">
            <input type="text" name="rolename" value="{{$rolename}}" data-rule="required">
        </div>

        <label class="row-label">角色说明</label>
        <div class="row-input">
            <input type="text" name="roledesc" value="{{$roledesc}}" >
        </div>

        <label class="row-label">状态</label>
        <div class="row-input required">
            <input type="radio" name="status"  data-toggle="icheck" value="1" data-rule="checked" data-label="启用&nbsp;&nbsp;" @if( !$status || $status ==1) checked @endif>
            <input type="radio" name="status"  data-toggle="icheck" value="2" data-label="停用" @if($status ==2) checked @endif>
        </div>
    </div>
    <div class="bjui-row col-3">
        <label class="row-label" style="color: #333;font-size: 16px;"><strong>角色权限分配：</strong></label>
        <div class="row-input">
            <button type="button" class=" btn btn-danger" onclick="checkAllNodes(this)">全选</button>
            <button type="button" id="backcheck" class=" btn btn-danger">反选</button>
        </div>
    </div>

    <div class="bjui-row col-3">
        @foreach ($module as $mgroup)
        <label class="row-label" style="color: #f76447;font-size: 16px;"><strong>{{$mgroup['mval']}}:</strong></label>
        <div id="qx-checkNode" class="row-input">
            <ul id="{{$mgroup['msysno']}}" class="ztree" data-toggle="ztree" data-check-enable="true"  data-options="{expandAll:false}">
                <li data-id="{{$mgroup['msysno']}}" data-pid="{{$mgroup['msysno']}}" data-checked="{{$mgroup['check']}}" data-faicon="folder-open">{{$mgroup['mval']}}</li>
                @foreach($privileges2 as $group)
                    @if($group['parent_sysno']==$mgroup['msysno'])
                    <li data-id="{{$group['sysno']}}" data-pid="{{$group['parent_sysno']}}" data-checked="{{$group['check']}}" data-tabid="form-button" data-faicon="folder-open-o">{{$group['privilegename']}}</li>
                        @foreach($privileges3 as $grouptwo)
                            @if($grouptwo['parent_sysno']==$group['sysno'])
                                <li data-id="{{$grouptwo['sysno']}}" data-pid="{{$grouptwo['parent_sysno']}}" data-checked="{{$grouptwo['check']}}" data-tabid="form-button" data-faicon="user-circle">{{$grouptwo['privilegename']}}</li>
                            @endif
                        @endforeach
                    @endif
                @endforeach
            </ul>
        </div> 
        @endforeach
    </div>
</form>
    </div>
<div class="bjui-pageFooter">
    <ul>
        <li><button type="button" class="btn-close" data-icon="close">取消</button></li>
        <li><button type="button" id="treesubmit" class="btn-default" data-icon="save">保存</button></li>
    </ul>
</div>
<script src="/static/common/js/custom.js"></script>