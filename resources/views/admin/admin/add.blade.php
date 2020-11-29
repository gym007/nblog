
<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <title>添加管理员-WeAdmin Frame型后台管理系统-WeAdmin 1.0</title>
    <meta name="renderer" content="webkit">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <meta name="viewport"
          content="width=device-width, initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0, user-scalable=0">
    <meta name="csrf-token" content="{{ csrf_token() }}"/>
    <link rel="stylesheet" href="{{asset(_ADMIN_ . '/static/css/font.css')}}">
    <link rel="stylesheet" href="{{asset(_ADMIN_ . '/static/css/weadmin.css')}}">
    <!-- 让IE8/9支持媒体查询，从而兼容栅格 -->
    <!--[if lt IE 9]>
    <script src="https://cdn.staticfile.org/html5shiv/r29/html5.min.js"></script>
    <script src="https://cdn.staticfile.org/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->
</head>

<body>
<div class="weadmin-body">
    <form class="layui-form">
        <div class="layui-form-item">
            <label for="username" class="layui-form-label">
                <span class="we-red">*</span>登录名
            </label>
            <div class="layui-input-inline">
                <input type="text" id="username" name="name" required="" lay-verify="required"
                       autocomplete="off" class="layui-input" value="{{ $admin['name'] }}">
            </div>
            <div class="layui-form-mid layui-word-aux">
                <span class="we-red">*</span>将会成为您唯一的登入名
            </div>
        </div>

        <div class="layui-form-item">
            <label class="layui-form-label"><span class="we-red">*</span>角色</label>
            <div class="layui-input-block">
                @foreach ($roles as $role)
                    <input type="checkbox" name="role[]" lay-skin="primary" title="{{ $role['name'] }}"value="{{ $role['id'] }}"
                    @if (in_array($role['id'], $myRoles))
                        checked="checked"
                    @endif
                    >
                @endforeach



{{--                <input type="checkbox" name="role[]" lay-skin="primary" title="超级管理员" checked="" value="1">--}}
{{--                <input type="checkbox" name="role[]" lay-skin="primary" title="编辑人员" value="2">--}}
{{--                <input type="checkbox" name="role[]" lay-skin="primary" title="宣传人员" checked="" value="3">--}}
            </div>
        </div>

        <div class="layui-form-item">
            <label for="L_pass" class="layui-form-label">
                <span class="we-red">*</span>密码
            </label>
            <div class="layui-input-inline">
                <input type="password" id="L_pass" name="pass" required="" lay-verify="pass"
                       autocomplete="off" class="layui-input" value="{{ $admin['pass'] }}">
            </div>
            <div class="layui-form-mid layui-word-aux">
                6到16个字符
            </div>
        </div>
        <div class="layui-form-item">
            <label for="L_repass" class="layui-form-label">
                <span class="we-red">*</span>确认密码
                @if ($admin['edit'])
                    <input type="hidden" name="id" value="{{ $admin['id'] }}">
                @endif
            </label>
            <div class="layui-input-inline">
                <input type="password" id="L_repass" name="pass_confirmation" required="" lay-verify="repass"
                       autocomplete="off" class="layui-input" value="{{ $admin['repass'] }}">
            </div>
        </div>
        <div class="layui-form-item">
            <label for="L_repass" class="layui-form-label"></label>
            <button class="layui-btn" lay-filter="add" lay-submit="">
                @if ($admin['edit'])
                    修改
                @else
                    增加
                @endif
            </button>
        </div>
    </form>
</div>
<script type="text/javascript" src="{{asset(_ADMIN_ . '/lib/layui/layui.js')}}" charset="utf-8"></script>
<script type="text/javascript">
    layui.extend({
        // admin: '{/}../../static/js/admin'
        admin: '/static/admin/static/js/admin'
    });
    layui.use(['form', 'layer', 'admin'], function () {
        var form = layui.form,
            admin = layui.admin,
            $ = layui.$,
            layer = layui.layer;
        form.render();
        //自定义验证规则
        form.verify({
            nikename: function (value) {
                if (value.length < 6) {
                    return '昵称至少得6个字符啊';
                }
            }
            , pass: [/(.+){6,11}$/, '密码必须6到11位']
            , repass: function (value) {
                if ($('#L_pass').val() != $('#L_repass').val()) {
                    return '两次密码不一致';
                }
            }
        });

        //监听提交
        form.on('submit(add)', function (data) {
            ll(data);
            var edit = {{ $admin['edit'] }};
            var uri,type,id;
            if (edit) {
                data.field._method = 'PUT';
                id = {{ $admin['id'] }};
                uri = '/admin/admin/' + id;
                type = 'PUT';
            } else {
                uri = '/admin/admin';
                type = 'POST';
            }
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url: uri,
                type: type,
                data: data.field,
                dataType: 'JSON',
                success: function (res) {
                    ll('OK');
                    if (res.code === 200) {
                        layer.alert("OK", {icon: 6}, function () {
                            parent.location.reload();

                            // 获得frame索引
                            var index = parent.layer.getFrameIndex(window.name);
                            //关闭当前frame
                            parent.layer.close(index);

                        });
                    } else {
                        layer.msg(res.text, function () {});
                    }
                },
                error: function (res) {
                    ll('error');
                }
            });
            return false;


            //发异步，把数据提交给php
            layer.alert("增加成功", {icon: 6}, function () {
                // 获得frame索引
                var index = parent.layer.getFrameIndex(window.name);
                //关闭当前frame
                parent.layer.close(index);
            });
            return false;
        });
    });
</script>
</body>

</html>