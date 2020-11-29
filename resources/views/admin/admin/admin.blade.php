@extends('admin.layouts.admin')
<meta name="csrf-token" content="{{ csrf_token() }}"/>
@section('content')
<div class="weadmin-nav">
			<span class="layui-breadcrumb">
        <a href="">首页</a>
        <a href="">管理员管理</a>
        <a>
          <cite>管理员列表1</cite></a>
      </span>
    <a class="layui-btn layui-btn-sm" style="line-height:1.6em;margin-top:3px;float:right"
       href="javascript:location.replace(location.href);" title="刷新">
        <i class="layui-icon" style="line-height:30px">ဂ</i></a>
</div>
<div class="weadmin-body">
    <div class="layui-row">
        <form class="layui-form layui-col-md12 we-search">
            <div class="layui-inline">
                <input class="layui-input" placeholder="开始日" name="start" id="start">
            </div>
            <div class="layui-inline">
                <input class="layui-input" placeholder="截止日" name="end" id="end">
            </div>
            <div class="layui-inline">
                <input type="text" name="username" placeholder="请输入用户名" autocomplete="off" class="layui-input">
            </div>
            <button class="layui-btn" lay-submit="" lay-filter="sreach"><i class="layui-icon">&#xe615;</i></button>
        </form>
    </div>
    <div class="weadmin-block">
        <button class="layui-btn layui-btn-danger" onclick="delAll()"><i class="layui-icon"></i>批量删除</button>
        <button class="layui-btn" onclick="WeAdminShow('添加用户','/admin/admin/create')"><i class="layui-icon"></i>添加</button>
        <span class="fr" style="line-height:40px">共有数据：88 条</span>
    </div>

    <table class="layui-hide" id="test" lay-filter="test"></table>
    <script type="text/html" id="barDemo">
{{--        <a class="layui-btn layui-btn-primary layui-btn-xs" lay-event="detail">查看</a>--}}
        <a class="layui-btn layui-btn-xs" lay-event="edit">编辑</a>
        <a class="layui-btn layui-btn-danger layui-btn-xs" lay-event="del">删除</a>
    </script>

</div>
@endsection

@section('script')
<script type="text/javascript" src="{{asset(_ADMIN_ . '/lib/layui/layui.js')}}" charset="utf-8"></script>
<script src="{{asset(_ADMIN_ . '/static/js/eleDel.js')}}" type="text/javascript" charset="utf-8"></script>
@endsection

@section('js')
<script>
    layui.use('table', function () {
        var table = layui.table,
            $ = layui.$,
            form = layui.form;

        table.render({
            elem: '#test',
            url: 'admin/list',
            cellMinWidth: 80,
            cols: [[
                {type: 'numbers'},
                {type: 'checkbox'},
                {field:'id', title:'ID', unresize: true, sort: true},
                {field:'name', title:'账号', templet: '#usernameTpl'},
                {field:'status', title:'状态', unresize: true, templet: function (obj) {
                        var html = '';
                        if (obj.status > 0) {
                            html = '<input type="checkbox" name="status" value="' + obj.id + '" lay-skin="switch" lay-text="已启用|已禁用" lay-filter="sexDemo" checked>';
                        } else {
                            html = '<input type="checkbox" name="status" value="' + obj.id + '" lay-skin="switch" lay-text="已启用|已禁用" lay-filter="sexDemo">';
                        }
                        return html;
                    }},
                {field:'created_at', title:'创建时间', width:200, unresize: true, sort: true},
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
                        url: '/admin/admin/' + data.id,
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
                WeAdminShow('新增/编辑管理员', '/admin/admin/' + data.id + '/edit');

            }
        });

        //监听状态操作
        form.on('switch(sexDemo)', function(obj){
            var id = obj.value;
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url: '/admin/admin/status',
                data: {'id': id},
                dataType: 'JSON',
                type: 'POST',
                success: function (res) {
                    if (res.code === 200) {
                        layer.tips('设置成功', obj.othis);
                    } else {
                        layer.tips(res.text, obj.othis);

                    }
                },
                error: function (res) {
                    ll('error');
                    ll('res');
                }

            });
            // layer.tips(this.value + ' ' + this.name + '：'+ obj.elem.checked, obj.othis);
        });



    })
</script>
@endsection
