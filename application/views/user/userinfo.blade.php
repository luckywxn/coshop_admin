<script src="/static/common/js/custom.js"></script>
<div class="bjui-pageContent">
    <form id="userinfoform" action="/user/userEditJson/" class="datagrid-edit-form" data-toggle="validate" data-data-type="json">
        <input type="hidden" name="id" value="{{$sysno}}">
        <input type="hidden" name="role" value="{{$role}}">
        <div class="bjui-row col-3">
            <label class="row-label">会员头像</label>
            <div class="row-input">
                <input type="file" data-name="custom.pic" data-toggle="webuploader" data-options="
                {
                    pick: {label: '点击选择图片'},
                    server: '/user/ajaxUpload',
                    fileNumLimit: 1,
                    formData: {'backid':'userphoto'},
                    required: false,
                    uploaded: '{{$userphoto}}',
                    accept: {
                        title: '图片',
                        extensions: 'jpg,png,pdf,txt',
                        mimeTypes: '.jpg,.png,.pdf,.txt'
                    }
                }">
            </div>

            <label class="row-label">昵称</label>
            <div class="row-input">
                <input type="text" name="nickname" value="{{$nickname}}">
            </div>

            <label class="row-label">会员id</label>
            <div class="row-input">
                <input type="text" name="sysno" value="{{$sysno}}" data-rule="number" readonly>
            </div>

            <label class="row-label">账号</label>
            <div class="row-input">
                <input type="text" name="username" value="{{$username}}" readonly>
            </div>

            <label class="row-label">姓名</label>
            <div class="row-input">
                <input type="text" name="realname" value="{{$realname}}" data-rule="chinese">
            </div>

            <label class="row-label">会员等级</label>
            <div class="row-input">
                <input type="text" name="rolename" value="{{$rolename}}" data-rule="chinese" readonly>
            </div>

            <label class="row-label">性别</label>
            <div class="row-input">
                <input type="radio" name="sex"  data-toggle="icheck" value="0" data-label="男&nbsp;&nbsp;" @if($sex ==0) checked @endif>
                <input type="radio" name="sex"  data-toggle="icheck" value="1" data-label="女" @if($sex ==1) checked @endif>
            </div>

            <label class="row-label">联系电话</label>
            <div class="row-input">
                <input type="text" name="telephone" value="{{$telephone}}">
            </div>

            <label class="row-label">电子邮箱</label>
            <div class="row-input">
                <input type="text" name="email" value="{{$email}}" data-rule="email">
            </div>

            <label class="row-label">出生日期</label>
            <div class="row-input">
                <input type="text" name="birthday" value="{{$birthday}}" data-toggle="datepicker" >
            </div>

            <label class="row-label">民族</label>
            <div class="row-input">
                <input type="text" name="nation" value="{{$nation}}" data-rule="chinese">
            </div>

            <label class="row-label">籍贯</label>
            <div class="row-input">
                <input type="text" name="origin" value="{{$origin}}" data-rule="chinese">
            </div>

            <label class="row-label">婚姻状况</label>
            <div class="row-input">
                <input type="radio" name="marriage"  data-toggle="icheck" value="0" data-label="未婚&nbsp;&nbsp;" @if($marriage ==0) checked @endif>
                <input type="radio" name="marriage"  data-toggle="icheck" value="1" data-label="已婚" @if($marriage ==1) checked @endif>
            </div>

            <label class="row-label">政治面貌</label>
            <div class="row-input">
                <input type="text" name="politics" value="{{$politics}}" data-rule="chinese">
            </div>

            <label class="row-label">学历</label>
            <div class="row-input">
                <input type="text" name="education" value="{{$education}}" data-rule="chinese">
            </div>

            <label class="row-label">专业</label>
            <div class="row-input">
                <input type="text" name="major" value="{{$major}}" data-rule="chinese">
            </div>

            <label class="row-label">毕业院校</label>
            <div class="row-input">
                <input type="text" name="university" value="{{$university}}" data-rule="chinese">
            </div>

            <label class="row-label">联系地址</label>
            <div class="row-input">
                <input type="text" name="address" value="{{$address}}">
            </div>

            <label class="row-label">身份证号</label>
            <div class="row-input">
                <input type="text" name="idcard" value="{{$idcard}}" data-rule="IDcard">
            </div>

            <label class="row-label">银行帐号</label>
            <div class="row-input">
                <input type="text" name="bankaccount" value="{{$bankaccount}}" data-rule="number">
            </div>

            <label class="row-label">注册时间</label>
            <div class="row-input">
                <input type="text" name="created_at" value="{{$created_at}}" readonly>
            </div>

            <label class="row-label">备注</label>
            <div class="row-input">
                <textarea type="text" name="memo" >{{$memo}}</textarea>
            </div>
        </div>

        <br>
        <br>
        <div class="text-center btns-user">
            <button id="userinfosave" type="button" class="btn btn-green btn-lg">保存</button>
        </div>
    </form>
</div>
<script>
    $("#userinfosave").click(function (){
        BJUI.ajax('ajaxform', {
            url: '/user/userEditJson/',
            form: $.CurrentNavtab.find('#userinfoform'),
            validate: true,
            loadingmask: true,
            okCallback: function (json, options) {
                BJUI.navtab('closeCurrentTab', '');
            }
        });
    })
</script>