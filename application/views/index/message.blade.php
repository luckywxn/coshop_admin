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
        .main_box{position:absolute; top:45%; left:50%; margin:-200px 0 0 -180px; padding:15px 20px; width:360px; height:300px; min-width:320px; background:#FAFAFA; background:rgba(255,255,255,0.5); box-shadow: 1px 2px 8px rgba(255,255,255,0.5); border-radius:6px;}
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
            <br>
            <br>

            <div class="form-group" style="text-align: center;color: red;font-size: 24px">
                <span>{{$message}}</span>
            </div>

            <br>
            <div class="text-center">
                <button type="button" class="btn btn-success btn-lg"><a href="/login">现在登录</a></button>&nbsp;&nbsp;&nbsp;&nbsp;
                <button type="button" class="btn btn-default btn-lg"><a href="/index/register">继续注册</a></button>&nbsp;&nbsp;&nbsp;&nbsp;
            </div>

            <div class="text-center">
                <hr>
                2017－2020 <a href="">强势文化传播集团有限公司</a><br>
            </div>
        </form>
    </div>
</div>
</body>
</html>