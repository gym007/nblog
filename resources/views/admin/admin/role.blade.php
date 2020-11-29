@extends('admin.layouts.admin')
<meta name="csrf-token" content="{{ csrf_token() }}"/>
@section('content')
<div class="weadmin-nav">
			<span class="layui-breadcrumb">
        <a href="">首页</a>
        <a href="">管理员管理</a>
        <a>
          <cite>角色管理</cite></a>
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
        <button class="layui-btn" onclick="WeAdminShow('添加用户','/admin/role/create')"><i class="layui-icon"></i>添加</button>
        <span class="fr" style="line-height:40px">共有数据：88 条</span>
    </div>

    <table id="demo" lay-filter="test"></table>
</div>
@endsection

@section('script')
<script src="{{asset(_ADMIN_ . '/lib/layui/layui.js')}}" charset="utf-8"></script>
<script src="{{asset(_ADMIN_ . '/static/js/eleDel.js')}}" type="text/javascript" charset="utf-8"></script>
@endsection

@section('js')
<script>
    layui.use(['table', 'jquery', 'layer'], function () {
        var table = layui.table,
            layer = layui.layer,
            $ = layui.$;

        table.render({
            elem: '#demo',
            cellMinWidth: 80,
            url: '/admin/role/list',
            cols: [[
                {type: 'numbers'},
                {type: 'checkbox'},
                {field: 'id', title: 'ID', unresize: true, sort: true},
                {field: 'name', title: '角色名'},
                {field: 'created_at', title: '创建时间'},
                {
                    title: '操作', unresize: true, templet: function (obj) {
                        var id = obj.id;
                        var html = '<a title="编辑" onclick="WeAdminShow(\'编辑\', ' + '\'/admin/role/' + id + '/edit' + '\')" href="javascript:;">';
                        html += '<i class="layui-icon">&#xe642;</i></a>';
                        html += '<a title="删除" onclick="member_del(this,' + id + ')" href="javascript:;">' +
                            '<i class="layui-icon">&#xe640;</i>' + '</a>';
                        return html;
                    }
                }
            ]],
            page: true
        });

    });
</script>
@endsection
