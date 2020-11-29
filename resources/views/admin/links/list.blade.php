@extends('admin.layouts.admin')
<meta name="csrf-token" content="{{ csrf_token() }}"/>
@section('content')
<div class="weadmin-nav">
			<span class="layui-breadcrumb">
        <a href="">首页</a>
        <a href="">订单管理</a>
        <a><cite>订单列表</cite></a>
      </span>
    <a class="layui-btn layui-btn-sm" style="line-height:1.6em;margin-top:3px;float:right"
       href="javascript:location.replace(location.href);" title="刷新">
        <i class="layui-icon" style="line-height:30px">ဂ</i></a>
</div>
<div class="weadmin-body">
    <div class="weadmin-block">
        <button class="layui-btn layui-btn-danger delAll"><i class="layui-icon"></i>批量删除</button>
        <button class="layui-btn" onclick="WeAdminShow('添加友链','links/create')"><i class="layui-icon"></i>添加</button>
        <span class="fr" style="line-height:40px">共有数据：88 条</span>
    </div>

    <table class="layui-hide" id="test" lay-filter="test"></table>
    <script type="text/html" id="barDemo">
        <a class="layui-btn layui-btn-xs" lay-event="edit">编辑</a>
        <a class="layui-btn layui-btn-danger layui-btn-xs" lay-event="del">删除</a>
    </script>

</div>
@endsection
@section('script')
<script src="{{asset(_ADMIN_ . '/lib/layui/layui.js')}}" charset="utf-8"></script>
@endsection
@section('js')
<script>
    layui.extend({
        admin: '{/}../../static/js/admin'
    });
    layui.use(['laydate', 'jquery', 'admin', 'table'], function () {
        var laydate = layui.laydate,
            $ = layui.jquery,
            admin = layui.admin;
            table = layui.table;
        //执行一个laydate实例
        laydate.render({
            elem: '#start' //指定元素
        });
        //执行一个laydate实例
        laydate.render({
            elem: '#end' //指定元素
        });

        table.render({
            elem: '#test'
            , url: '/admin/links/list'
            , toolbar: '#toolbarDemo' //开启头部工具栏，并为其绑定左侧模板
            , defaultToolbar: ['filter', 'exports', 'print', { //自定义头部工具栏右侧图标。如无需自定义，去除该参数即可
                title: '提示'
                , layEvent: 'LAYTABLE_TIPS'
                , icon: 'layui-icon-tips'
            }]
            , title: '友链列表'
            , cols: [[
                {type: 'checkbox', fixed: 'left'}
                , {field: 'id', title: 'ID', fixed: 'left', unresize: true, sort: true}
                , {field: 'name', title: '链接名', edit: 'text'}
                , {field: 'link', title: '链接'}
                , {fixed: 'right', title: '操作', toolbar: '#barDemo'}
            ]]
            , page: true
        });

        //监听操作按钮
        table.on('tool(test)', function(obj){
            var data = obj.data;
            ll(obj);
            ll(data);
            ll('----------');
            if(obj.event === 'detail'){
                layer.msg('ID：'+ data.id + ' 的查看操作');
            } else if(obj.event === 'del'){
                layer.confirm('真的删除此链接么', function(index){
                    $.ajax({
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        url: '/admin/links/' + data.id,
                        type: 'POST',
                        data: {'id': data.id, '_method': 'DELETE'},
                        dataType: 'JSON',
                        success: function (res) {
                            if (res.code != 200) {
                                layer.alert(res.text);
                                return false;
                            }
                        }
                    });


                    obj.del();
                    layer.close(index);
                });
            } else if(obj.event === 'edit'){
                var url = '/admin/links/' + data.id + '/edit';
                WeAdminShow('编辑', url);
                // layer.alert('编辑行：<br>'+ JSON.stringify(data))
            }
        });

        $('.delAll').click(function (res) {
            ll('delAll');
        });

    });

</script>
@endsection
