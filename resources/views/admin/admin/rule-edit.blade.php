@extends('admin.layouts.admin')
<meta name="csrf-token" content="{{ csrf_token() }}"/>
@section('content')
<div class="weadmin-body">
    <form class="layui-form">
        <div class="layui-form-item">
            <label for="tree" class="layui-form-label">
                <span class="we-red">*</span>请选择父权限
            </label>
            <div class="layui-input-inline">
                <input type="text" id="tree" lay-filter="tree" class="layui-input">
            </div>
        </div>

        <div class="layui-form-item">
            <label class="layui-form-label">
                <span class="we-red">*</span>是否是菜单
            </label>
            <div class="layui-input-inline">
                <select name="is_menu">
                    <option value="-1">请选择</option>
                    <option value="1">是</option>
                    <option value="0">否</option>
                </select>
            </div>
        </div>

        <div class="layui-form-item">
            <label for="username" class="layui-form-label">
                <span class="we-red">*</span>权限名
            </label>
            <div class="layui-input-inline">
                <input type="text" id="username" name="name" required="" lay-verify="required"
                       autocomplete="off" class="layui-input" value="{{ $menu['name'] }}">
                <input type="hidden" class="pid" value="{{ $menu['pid'] }}" name="pid">
            </div>
        </div>

        <div class="layui-form-item">
            <label class="layui-form-label"><span class="we-red">*</span>对应url</label>
            <div class="layui-input-inline">
                <input type="text" id="username" name="url" required="" lay-verify="required"
                       autocomplete="off" class="layui-input" value="{{ $menu['url'] }}">
            </div>
        </div>

        <div class="layui-form-item">
            <label class="layui-form-label"><span class="we-red">*</span>对应模块/控制器/方法</label>
            <div class="layui-input-inline">
                <input type="text" id="username" name="route" required="" lay-verify="required"
                       autocomplete="off" class="layui-input" value="{{ $menu['route'] }}">
            </div>
        </div>


        <div class="layui-form-item">
            <label for="L_repass" class="layui-form-label"></label>
            <button class="layui-btn" lay-filter="add" lay-submit="">修改</button>
        </div>
    </form>
</div>
@endsection
@section('script')
<script type="text/javascript" src="{{asset(_ADMIN_ . '/lib/layui/layui.js')}}" charset="utf-8"></script>
@endsection
@section('js')
<script type="text/javascript">

    layui.extend({
        // admin: '{/}../../static/js/admin'
        admin: '/static/admin/static/js/admin',
        treeSelect: '{/}/static/admin/lib/layui/lay/modules/treeSelect'

    });
    layui.use(['form', 'layer', 'admin', 'jquery', 'treeSelect'], function () {
        var form = layui.form,
            admin = layui.admin,
            $ = layui.jquery,
            treeSelect = layui.treeSelect,
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
            var edit = 1;
            var uri,type,id;
            if (edit) {
                data.field._method = 'PUT';
                id = {{ $menu['id'] }};
                uri = '/admin/menu/' + id;
                type = 'PUT';
            } else {
                uri = '/admin/menu';
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

        treeSelect.render({
            // 选择器
            elem: '#tree',
            // 数据
            data: '/admin/menu/treeForSelect',
            // 异步加载方式：get/post，默认get
            type: 'post',
            // 占位符
            placeholder: '请选择父级菜单',
            // 是否开启搜索功能：true/false，默认false
            search: true,
            // 点击回调
            click: function(d){
                // console.log(d);
                $(".pid").val(d.current.id);
            },
            // 加载完成后的回调函数
            success: function (d) {
                ll('aaa');
                console.log(d);
                // 选中节点，根据id筛选
                treeSelect.checkNode('tree', {{ $menu['pid'] }});

                // 获取zTree对象，可以调用zTree方法
                // var treeObj = treeSelect.zTree('tree');
                // console.log(treeObj);
                //
                // 刷新树结构
                // treeSelect.refresh();
            }
        });
    });
</script>
@endsection