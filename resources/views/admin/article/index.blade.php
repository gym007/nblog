@extends('admin.layouts.admin')
<meta name="csrf-token" content="{{ csrf_token() }}"/>
<style type="text/css">
    .layui-form-switch {
        width: 55px;
    }

    .layui-form-switch em {
        width: 40px;
    }

</style>
@section('content')
<div class="weadmin-nav">
			<span class="layui-breadcrumb">
        <a href="">首页</a>
        <a href="">文章管理</a>
        <a>
          <cite>文章列表</cite></a>
      </span>
    <a class="layui-btn layui-btn-sm" style="line-height:1.6em;margin-top:3px;float:right"
       href="javascript:location.replace(location.href);" title="刷新">
        <i class="layui-icon" style="line-height:30px">ဂ</i></a>
</div>
<div class="weadmin-body">
    <div class="layui-row">
        <form class="layui-form layui-col-md12 we-search">
            文章搜索：
            <div class="layui-input-inline">
                <select name="cateid">
                    <option>1请选择分类</option>
                    <option>2文章</option>
                    <option>会员</option>
                    <option>权限</option>
                </select>
            </div>
            <div class="layui-inline">
                <input class="layui-input" placeholder="开始日" name="start" id="start">
            </div>
            <div class="layui-inline">
                <input class="layui-input" placeholder="截止日" name="end" id="end">
            </div>
            <div class="layui-inline">
                <input type="text" name="keyword" placeholder="请输入关键字" autocomplete="off" class="layui-input">
            </div>
            <button class="layui-btn" lay-submit="" lay-filter="sreach"><i class="layui-icon">&#xe615;</i></button>
        </form>
    </div>

    <table class="layui-hide" id="test" lay-filter="test"></table>

    <script type="text/html" id="toolbarDemo">
        <div class="layui-btn-container">
            <button class="layui-btn layui-btn-sm" lay-event="getCheckData">获取选中行数据</button>
            <button class="layui-btn layui-btn-sm" lay-event="getCheckLength">获取选中数目</button>
            <button class="layui-btn layui-btn-sm" lay-event="isAll">验证是否全选</button>
            <button class="layui-btn layui-btn-sm" lay-event="create">写文章</button>
        </div>
    </script>
    <script type="text/html" id="barDemo">
{{--        <a class="layui-btn layui-btn-primary layui-btn-xs" lay-event="detail">查看</a>--}}
        <a class="layui-btn layui-btn-xs" lay-event="edit">编辑</a>
        <a class="layui-btn layui-btn-danger layui-btn-xs" lay-event="del">删除</a>
    </script>
</div>
@endsection
@section('script')
<script type="text/javascript" src="https://cdn.bootcss.com/jquery/3.2.1/jquery.min.js"></script>
{{--    <script type="text/javascript" src="{{asset(_ADMIN_ . '/static/editor/jquery.min.js')}}"></script>--}}
<script type="text/javascript" src="{{asset(_ADMIN_ . '/lib/layui/layui.js')}}" charset="utf-8"></script>
@endsection
@section('js')
<script type="text/javascript">
    layui.extend({
        admin: '{/}../../static/js/admin',
    });

    layui.use(['table', 'admin'], function () {
        var table = layui.table;

        table.render({
            elem: '#test'
            , url: '/admin/article/allList'
            , toolbar: '#toolbarDemo' //开启头部工具栏，并为其绑定左侧模板
            , defaultToolbar: ['filter', 'exports', 'print', { //自定义头部工具栏右侧图标。如无需自定义，去除该参数即可
                title: '提示'
                , layEvent: 'LAYTABLE_TIPS'
                , icon: 'layui-icon-tips'
            }]
            , title: '文章列表'
            , cols: [[
                {type: 'checkbox', fixed: 'left'}
                , {field: 'id', title: 'ID', width: 80, fixed: 'left', unresize: true, sort: true}
                , {field: 'title', title: '标题', width: 200, edit: 'text'}
                , {
                    field: 'category', title: '分类', width: 120, templet: function (res) {
                        return res.category.cate_title;
                    }
                }
                , {field: 'content', title: '内容', width: 400}
                , {field: 'read_times', title: '阅读量', width: 100}
                , {field: 'created_at', title: '创作时间',width: 150}
                , {field: 'updated_at', title: '上次修改时间',width: 150}
                , {title: '操作', toolbar: '#barDemo', width:400}
            ]]
            , page: true
        });

        //头工具栏事件
        table.on('toolbar(test)', function (obj) {
            var checkStatus = table.checkStatus(obj.config.id);
            switch (obj.event) {
                case 'getCheckData':
                    var data = checkStatus.data;
                    layer.alert(JSON.stringify(data));
                    break;
                case 'getCheckLength':
                    var data = checkStatus.data;
                    // ll(data);
                    layer.msg('选中了：' + data.length + ' 个');
                    break;
                case 'isAll':
                    layer.msg(checkStatus.isAll ? '全选' : '未全选');
                    break;

                //自定义头工具栏右侧图标 - 提示
                case 'LAYTABLE_TIPS':
                    layer.alert('这是工具栏右侧自定义的一个图标按钮');
                    break;
                case 'create':
                    WeAdminShow('写文章', '/admin/article/create');
                    break;
            }
            ;
        });

        //监听操作按钮
        table.on('tool(test)', function(obj){
            var data = obj.data;
            ll(obj);
            ll(data);
            if(obj.event === 'detail'){
                layer.msg('ID：'+ data.id + ' 的查看操作');
            } else if(obj.event === 'del'){
                layer.confirm('真的删除行么', function(index){
                    $.ajax({
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        url: '/admin/article/' + data.id,
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
                var url = '/admin/article/' + data.id + '/edit';
                WeAdminShow('编辑', url);
                // layer.alert('编辑行：<br>'+ JSON.stringify(data))
            }
        });


        table.on('checkbox(test)', function (obj) {
            ll('查看点击的复选框的行数据');
            ll(obj);
        })
    });

</script>
@endsection
