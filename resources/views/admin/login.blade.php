<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>管理员登录-WeAdmin Frame型后台管理系统-WeAdmin 1.0</title>
    <meta name="renderer" content="webkit|ie-comp|ie-stand">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <meta name="viewport"
          content="width=device-width, initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0, user-scalable=0">
    <meta http-equiv="Cache-Control" content="no-siteapp"/>
    <link rel="shortcut icon" href="./favicon.ico" type="image/x-icon"/>
    <meta name="csrf-token" content="{{ csrf_token() }}"/>
    <link rel="stylesheet" href="{{asset(_ADMIN_ . '/static/css/font.css')}}">
    <link rel="stylesheet" href="{{asset(_ADMIN_ . '/static/css/weadmin.css')}}">
    <script type="text/javascript" src="{{asset(_ADMIN_ . '/lib/layui/layui.js')}}" charset="utf-8"></script>

</head>
<body class="login-bg">

<div class="login">
    <div class="message">云间阁-管理登录</div>
    <div id="darkbannerwrap"></div>

    <form method="post" class="layui-form">
        {{csrf_field()}}
        <input name="name" placeholder="用户名" type="text" lay-verify="required" class="layui-input">
        <hr class="hr15">
        <input name="password" lay-verify="required" placeholder="密码" type="password" class="layui-input">
        <hr class="hr15">
        <div>
            <input name="captcha" lay-verify="required" placeholder="验证码" type="text" class="layui-input" autocomplete="off">
            <img src="{{ $img }}">
        </div>
        <hr class="hr15">
        <input class="loginin" value="登录" lay-submit lay-filter="login" style="width:100%;" type="submit">
        <hr class="hr20">
        <div>

        </div>
    </form>
</div>

<script type="text/javascript">

    layui.extend({
        // admin: '{/}./static/js/admin'
        // admin: '{/}./static/admin/static/js/admin'
        admin: '/static/js/admin'
    });
    layui.use(['form', 'admin'], function () {
        var form = layui.form
            var $ = layui.jquery
            , admin = layui.admin;



        $(function(){
            $('img').click(function(){
                $(this).attr('src','{{captcha_src('mini')}}' + '&_=' + Math.random());
            })
        })


        // layer.msg('玩命卖萌中', function(){
        //   //关闭后的操作
        //   });
        //监听提交
        form.on('submit(login)', function (data) {
            // var a = $('meta[name="csrf-token"]').attr('content');
            // ll(a);
            // return false;

            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url: '/admin/login',
                // url: '/admin/getMenu',
{{--                url: '{{ route('login') }}',--}}
                type: 'POST',
                data: data.field,
                dataType: 'JSON',
                success: function (res) {
                    if (res.code === 200) {
                        location.href = '/admin';
                    } else {
                        layer.msg(res.text, function () {});
                    }
                    return false;

                },
                error: function (res) {
                    ll('login error');
                }
            });

            return false;
            // alert(888);return false;
            layer.msg(JSON.stringify(data.field), function () {
                location.href = '/admin';
            });
            return false;
        });
    });
</script>
<!-- 底部结束 -->
</body>
</html>