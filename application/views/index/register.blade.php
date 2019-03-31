<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
    <title>强势文化学习系统</title>
    <meta name="renderer" content="webkit">
    <script src="/static/BJUI/B-JUI/js/jquery-1.11.3.min.js"></script>
    <script src="/static/BJUI/B-JUI/js/jquery.cookie.js"></script>
    <script src="/static/BJUI/B-JUI/js/bjui-all.js"></script>
    <link href="/static/BJUI/B-JUI/themes/css/bootstrap.min.css" rel="stylesheet">

    <style type="text/css">
        html, body { height: 100%; overflow: hidden; }
        body {
            font-family: "Verdana", "Tahoma", "Lucida Grande", "Microsoft YaHei", "Hiragino Sans GB", sans-serif;
            background: url('/static/images/loginbg_03.jpg') no-repeat center center fixed;
            background-size: cover;
        }
        .form-control{height:42px;}
        .main_box{position:absolute; top:45%; left:50%; margin:-200px 0 0 -180px; padding:15px 20px; width:360px; height:470px; min-width:320px; background:#FAFAFA; background:rgba(255,255,255,0.5); box-shadow: 1px 2px 8px rgba(255,255,255,0.5); border-radius:6px;}
        .login_msg{height:30px;}
        .input-group >.input-group-addon.code{padding:0;}
        #captcha_img{cursor:pointer;}
        .main_box .logo img{height:35px;}
        @media (min-width: 768px) {
            .main_box {margin-left:-240px; padding:15px 55px; width:480px;}
            .main_box .logo img{height:50px;}
        }
    </style>
    <script type="text/javascript">
        $(function() {
            choose_bg();
         //   changeCode();

            $("#captcha_img").click(function(){
                changeCode();
            });

        });
        function changeCode(){
            $("#captcha_img").attr("src", "/index/vcode?t="+ (new Date().getTime()));
        }
        function choose_bg() {
            var bg = Math.floor(Math.random() * 9 + 1);
            $('body').css('background-image', 'url(/static/images/loginbg_0'+ 3 +'.jpg)');
        }
    </script>
</head>
<body>
<!--[if lte IE 7]>
<style type="text/css">
    #errorie {position: fixed; top: 0; z-index: 100000; height: 30px; background: #FCF8E3;}
    #errorie div {width: 900px; margin: 0 auto; line-height: 30px; color: orange; font-size: 14px; text-align: center;}
    #errorie div a {color: #459f79;font-size: 14px;}
    #errorie div a:hover {text-decoration: underline;}
</style>
<div id="errorie"><div>您还在使用老掉牙的IE，请升级您的浏览器到 IE8以上版本 <a target="_blank" href="http://windows.microsoft.com/zh-cn/internet-explorer/ie-8-worldwide-languages">点击升级</a>&nbsp;&nbsp;强烈建议您更改换浏览器：<a href="http://down.tech.sina.com.cn/content/40975.html" target="_blank">谷歌 Chrome</a></div></div>
<![endif]-->
<div class="container">
    <div class="main_box">
        <form id="registerform" action="/index/registerjson" method="post">
            <br>
            <p class="text-center logo"><img src="/static/images/logo5.png"></p>
            <div class="login_msg text-center"><font color="red">{{$msg}}</font></div>
            <div class="form-group">
                <div class="input-group required">
                    <span class="input-group-addon" id="sizing-addon-user"><span class="glyphicon glyphicon-user"></span></span>
                    <input type="text" id="phone" class="form-control" name="username" value="" placeholder="手机号码" aria-describedby="sizing-addon-user" data-rule="required mobile">
                </div>
            </div>
            <div class="form-group">
                <div class="input-group">
                    <span class="input-group-addon" id="sizing-addon-password"><span class="glyphicon glyphicon-lock"></span></span>
                    <input type="password" class="form-control"  name="passwordhash" placeholder="登录密码" aria-describedby="sizing-addon-password">
                </div>
            </div>
            <div class="form-group">
                <div class="input-group">
                    <span class="input-group-addon" id="sizing-addon-password"><span class="glyphicon glyphicon-exclamation-sign"></span></span>
                    <input type="text" class="form-control"  name="captcha" placeholder="验证码" aria-describedby="sizing-addon-password">
                    <span class="input-group-addon"><input id="sendcode" type="button" value="发送验证码" style="padding: 0px;margin: 0px;height: 28px;width: 150px;"></span>
                </div>
            </div>

            <div class="form-group">
                <div class="input-group">
                    <span class="input-group-addon" id="sizing-addon-user"><span class="glyphicon glyphicon-user"></span></span>
                    <input type="text" class="form-control"  name="reference" value="" placeholder="推荐人手机" aria-describedby="sizing-addon-user">
                </div>
            </div>

            <div class="text-center">
                <button type="submit" id="register" class="btn btn-success btn-lg">&nbsp;注&nbsp;册&nbsp;</button>&nbsp;&nbsp;&nbsp;&nbsp;
                <button type="button" class="btn btn-default btn-lg"><a href="/login">&nbsp;返&nbsp;回&nbsp;</a></button>&nbsp;&nbsp;&nbsp;&nbsp;
            </div>

            <div class="text-center">
                <hr>
                2017－2020 <a href="">强势文化传播集团有限公司</a><br>
            </div>
        </form>
    </div>
</div>
</body>
<script>
    var InterValObj;
    var curCount;
    $("#sendcode").click(function (){
        curCount = 90;
        var phone = $("#phone").val();
        if(phone){
            $.ajax({
                url:'index/sendcode',
                data:{ phone:phone},
                type:'POST',
                success:function(options){
                    var parsedJson = jQuery.parseJSON(options);
                    if(parsedJson.resp.respCode=='000000'){
                        //BJUI.alertmsg('correct','验证码发送成功');
                        $("#sendcode").attr("disabled","disabled");
                        InterValObj = window.setInterval(SetRemainTime, 1000); //启动计时器，1秒执行一次
                    }else{
                        console.log(parsedJson.resp.respCode);
                    }
                }
            });
        }
    });

    //timer处理函数
    function SetRemainTime() {
        if (curCount == 0) {
            window.clearInterval(InterValObj);//停止计时器
            $("#sendcode").removeAttr("disabled");//启用按钮
            $("#sendcode").val("重新发送验证码");
        }else {
            curCount--;
            $("#sendcode").val("请在" + curCount + "秒内输入验证码");
        }
    }

//    $("#register").click(function (){
//        BJUI.ajax('ajaxform', {
//            url: '/index/registerjson',
//            form: $('#registerform'),
//            validate: true,
//            loadingmask: true,
//            okCallback: function(resdata) {
//                console.log(resdata);
//            },
//            error:function(){
//                console.log(123);
//            }
//        });
//    })
</script>
</html>