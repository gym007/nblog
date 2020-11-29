@extends('admin.layouts.admin')
<meta name="csrf-token" content="{{ csrf_token() }}"/>
@section('content')
<div class="weadmin-nav">
			<span class="layui-breadcrumb">
        <a href="">首页</a>
        <a href="">管理员管理</a>
        <a>
          <cite>权限管理</cite></a>
      </span>
    <a class="layui-btn layui-btn-sm" style="line-height:1.6em;margin-top:3px;float:right"
       href="javascript:location.replace(location.href);" title="刷新">
        <i class="layui-icon" style="line-height:30px">ဂ</i></a>
</div>
<div class="weadmin-body">
    <div class="layui-row">
        <form class="layui-form layui-col-md12 we-search layui-form-pane">
            {{ csrf_field() }}
            {{-- 菜单树 --}}
            <div class="layui-input-inline">
                <input type="text" id="tree" lay-filter="tree" class="layui-input">
                <input type="hidden" class="pid" value="-1" name="pid">
            </div>

            {{-- 是否菜单 --}}
            <div class="layui-input-inline">
                <select name="is_menu">
                    <option value="-1">请选择是否是菜单</option>
                    <option value="1">是</option>
                    <option value="0">否</option>
                </select>
            </div>

            {{-- 菜单名 --}}
            <div class="layui-inline">
                <input type="text" name="name" class="layui-input" placeholder="权限名"  autocomplete="off">
            </div>

            {{-- 菜单url --}}
            <div class="layui-inline">
                <input type="text" name="url" class="layui-input" placeholder="菜单对应的url" autocomplete="off">
            </div>

            {{-- 菜单路由 --}}
            <div class="layui-inline">
                <input type="text" name="route" class="layui-input" placeholder="/模块/控制器/方法(小写,可空)" autocomplete="off">
            </div>

            <button class="layui-btn" lay-submit="" lay-filter="create"><i class="layui-icon"></i>增加</button>
        </form>
    </div>
    <div class="weadmin-block">
        <button class="layui-btn layui-btn-danger" onclick="delAll()"><i class="layui-icon"></i>批量删除</button>
        <span class="fr" style="line-height:40px">共有数据：88 条</span>
    </div>
    <table class="layui-hide" id="test" lay-filter="test"></table>
    <script type="text/html" id="barDemo">
        {{--        <a class="layui-btn layui-btn-primary layui-btn-xs" lay-event="detail">查看</a>--}}
        <a class="layui-btn layui-btn-xs" lay-event="edit">编辑</a>
        <a class="layui-btn layui-btn-danger layui-btn-xs" lay-event="del">删除</a>
    </script>
</div>
@section('script')
<script type="text/javascript" src="{{asset(_ADMIN_ . '/lib/layui/layui.js')}}" charset="utf-8"></script>
<script src="{{asset(_ADMIN_ . '/static/js/eleDel.js')}}" type="text/javascript" charset="utf-8"></script>
@endsection
@section('js')
<script>
    layui.extend({
        treeSelect: '{/}/static/admin/lib/layui/lay/modules/treeSelect'
    });

    layui.use(['treeSelect','form', 'table'], function () {
        var treeSelect= layui.treeSelect,
            $ = layui.$,
            table = layui.table;
            form = layui.form;
        form.on('submit(create)', function (data) {
            ll(data);
            var uri = '/admin/menu',
            type = 'POST';
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

                        layer.msg('OK');

                        // layer.alert("OK", {icon: 6}, function () {
                        //     parent.location.reload();
                        //
                        //     // 获得frame索引
                        //     var index = parent.layer.getFrameIndex(window.name);
                        //     //关闭当前frame
                        //     parent.layer.close(index);
                        // });
                    } else {
                        layer.msg(res.text, function () {});
                    }
                },
                error: function (res) {
                    ll('error');
                }
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
                console.log(d);
                $(".pid").val(d.current.id);
            },
            // 加载完成后的回调函数
            success: function (d) {
                console.log(d);
//                选中节点，根据id筛选
//                treeSelect.checkNode('tree', 0);

//                获取zTree对象，可以调用zTree方法
//                var treeObj = treeSelect.zTree('tree');
//                console.log(treeObj);

//                刷新树结构
//                treeSelect.refresh();
            }
        });

        table.render({
            elem: '#test',
            url: 'menu/list',
            cellMinWidth: 80,
            cols: [[
                {type: 'numbers'},
                {type: 'checkbox'},
                {field:'id', title:'ID', unresize: true, sort: true},
                {field:'name', title:'权限名', templet: '#usernameTpl'},
                {field:'url', title:'url'},
                {field:'route', title:'模块/控制器/方法'},
                {field:'updated_at', title:'更新时间', width:200, unresize: true, sort: true},
                {fixed: 'right', title:'操作', toolbar: '#barDemo'}
            ]],
            page: true
        });

        //监听工具条
        table.on('tool(test)', function(obj){
            ll(123);
            var data = obj.data;
            if(obj.event === 'detail'){
                layer.msg('ID：'+ data.id + ' 的查看操作');
            } else if(obj.event === 'del'){
                layer.confirm('真的删除行么', function(index){
                    $.ajax({
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        url: '/admin/menu/' + data.id,
                        data: data,
                        dataType: 'JSON',
                        type: 'DELETE',
                        success: function (res) {
                            if (res.code === 200) {
                                obj.del();
                                layer.close(index);
                            }
                        },
                        error: function (res) {
                            layer.msg(res.text, function () {})
                        }
                    });

                    return false;
                    obj.del();
                    layer.close(index);
                });
            } else if(obj.event === 'edit'){
                WeAdminShow('编辑权限菜单', '/admin/menu/' + data.id + '/edit');

            }
        });




    });
</script>
@endsection
