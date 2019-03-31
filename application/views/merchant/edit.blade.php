<script src="/static/common/js/custom.js"></script>
<div class="bjui-pageContent">
    <div class="bs-example">
        <form action="{{$action}}" class="datagrid-edit-form" data-toggle="validate" data-data-type="json">
            <input type="hidden" name="id" value="{{$id}}">
            <div class="bjui-row col-1">
                <label class="row-label">商户编号</label>
                <div class="row-input required">
                    <input type="text" name="merchant_no" value="{{$merchant_no}}" data-rule="required">
                </div>
                <label class="row-label">商户名称</label>
                <div class="row-input">
                    <input type="text" name="merchant_name" value="{{$merchant_name}}" data-rule='required'>
                </div>
                <label class="row-label">联系人</label>
                <div class="row-input required">
                    <input type="text"  name="contact_name" value="{{$contact_name}}"  data-rule='required'>
                </div>
                <label class="row-label">联系电话</label>
                <div class="row-input required">
                    <input type="text" name="contact_tel" value="{{$contact_tel}}"  data-rule='required'>
                </div>
                <label class="row-label">联系邮箱</label>
                <div class="row-input">
                    <input type="text" name="contact_email" value="{{$contact_email}}">
                </div>
                <label class="row-label">联系地址</label>
                <div class="row-input">
                    <input type="text" name="contact_address" value="{{$contact_address}}">
                </div>

                <label class="row-label">状态</label>
                <div class="row-input required">
                    <input type="radio" name="status"  data-toggle="icheck" value="1" data-rule="checked" data-label="启用&nbsp;&nbsp;" @if( !$status || $status ==1) checked @endif>
                    <input type="radio" name="status"  data-toggle="icheck" value="2" data-label="停用" @if($status ==2) checked @endif>
                </div>
            </div>
        </form>
    </div>
</div>
<div class="bjui-pageFooter">
    <ul>
        <li><button type="button" class="btn-close" data-icon="close">取消</button></li>
        <li><button type="submit" class="btn-green" data-icon="save">保存</button></li>
    </ul>
</div>