@extends('admin.layouts.admin')
<meta name="csrf-token" content="{{ csrf_token() }}"/>
@section('content')
<div class="weadmin-body">
    <form id="form1" class="layui-form">
        {{ csrf_field() }}
        <div class="layui-form-item p-tree">
            <label class="layui-form-label">父级分类树</label>
            <div class="layui-input-inline">
                <div id="test12" class="demo-tree-more" name="cate"></div>

            </div>
        </div>

        <div class="layui-form-item p-id">
            <label class="layui-form-label">父级分类值</label>
            <div class="layui-input-block">
                <input type="text" disabled="true" lay-verify="required" jq-error="请选择上方的一个父级分类"
                       placeholder="请选择上方的一个父级分类"
                       autocomplete="off" class="layui-input p-input-1" value="">
                <input type="hidden" disabled="true" lay-verify="required" jq-error="请选择上方的一个父级分类" name="pid" value="-1"
                       class="layui-input p-input-2">
            </div>
        </div>

        <div class="layui-form-item">
            <label class="layui-form-label">分类名称</label>
            <div class="layui-input-block">
                <input type="text" name="cate_title" lay-verify="required" jq-error="请输入分类名称" placeholder="请输入分类名称"
                       autocomplete="off" class="layui-input ">
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">排序</label>
            <div class="layui-input-inline">
                <input type="text" name="top" lay-verify="number" value="100" jq-error="排序必须为数字" placeholder="分类排序"
                       autocomplete="off" class="layui-input ">
            </div>
        </div>
        {{--        <div class="layui-form-item">--}}
        {{--            <label class="layui-form-label">状态</label>--}}
        {{--            <div class="layui-input-inline">--}}
        {{--                <input type="radio" name="switch" title="启用" value="1" checked/>--}}
        {{--                <input type="radio" name="switch" title="禁用" value="0"/>--}}
        {{--            </div>--}}
        {{--        </div>--}}
        <div class="layui-form-item">
            <div class="layui-input-block">
                <button class="layui-btn" lay-submit="" lay-filter="add">立即提交</button>
                <button type="reset" class="layui-btn layui-btn-primary">重置</button>
            </div>
        </div>
        <input type="hidden" name="level" value="0"/>
    </form>
</div>
@endsection
@section('script')
<script type="text/javascript" src="{{asset(_ADMIN_ . '/lib/layui/layui.js')}}" charset="utf-8"></script>
@endsection
@section('js')
<script type="text/javascript">
    layui.extend({
        admin: '{/}/static/admin/static/js/admin'
    });

    // layui.use(['admin', 'jquery', 'form', 'layer', 'tree'], function () {
    layui.use(['jquery', 'form', 'layer', 'tree'], function () {
        var admin = layui.admin,
            $ = layui.jquery,
            form = layui.form,
            layer = layui.layer,
            tree = layui.tree;

        //监听提交
        form.on('submit(add)', function (data) {
            //发异步，把数据提交给php
            $.ajax({
                url: '/admin/category',
                method: 'post',
                data: data.field,
                dataType: 'JSON',
                success: function (res) {
                    if (res.code === 200) {
                        layer.msg('OK', {
                            icon: 6,
                            time: 2000
                        });
                    } else {
                        layer.alert(res.text, {
                            closeBtn: 1,
                            anim: 4,
                            btn: ['确定'],
                            icon: 2
                        });
                        return false;
                    }
                },
                error: function (msg) {
                    var json = JSON.parse(msg.responseText);
                    $.each(json.errors, function (index, obj) {
                        layer.alert(obj[0], {
                            closeBtn: 1,
                            anim: 4,
                            btn: ['确定'],
                            icon: 2
                        });
                        return false;
                    });
                }
            });



            // 获得frame索引
            var index = parent.layer.getFrameIndex(window.name);
            //关闭当前frame
            parent.layer.close(index);


            // layer.alert("增加成功", {
            //     icon: 6
            // }, function () {
            //     // 获得frame索引
            //     var index = parent.layer.getFrameIndex(window.name);
            //     //关闭当前frame
            //     parent.layer.close(index);
            // });
            // return false;
        });

        var data = @json($categories);

        var ren = tree.render({
            elem: '#test12' //默认是点击节点可进行收缩
            , data: data
            , showCheckbox: true
            // ,checkAllName: 'abc'
            , click: function (obj) {
                // console.log(obj.data); //得到当前点击的节点数据
                // console.log(obj.state); //得到当前节点的展开状态：open、close、normal
                // console.log(obj.elem); //得到当前节点元素
                // console.log('---------------------------');
            }
            , oncheck: function (obj) {
                // console.log(obj.data); //得到当前点击的节点数据
                // console.log(obj.checked); //得到当前节点的展开状态：open、close、normal
                // console.log(obj.elem); //得到当前节点元素
                // var after = '<div class="layui-form-item p-id">';
                // after += '<label class="layui-form-label">父级分类</label>';
                // after += '<div class="layui-input-block">';
                // after += '<input type="text" disabled="true" name="pid" lay-verify="required" jq-error="请点击选择一个父类" class="layui-input" value="' + obj.data.title + '">';
                // after += '</div></div>';

                var checked = obj.checked;
                if (checked) {
                    $('.p-input-1').val(obj.data.title);
                    $('.p-input-2').val(obj.data.id);
                    // $('.layui-form-checkbox').not($(this)).removeClass('layui-form-checked');
                    // $('.layui-form-checkbox').not($(this)).prop('checked', false);
                    // $(this).prop('checked', true);
                    // $(this).addClass('layui-form-checked');
                    // clearTimeout(checkedClass($(this)));
                    // timer = setTimeout(checkedClass($(this)), 100);
                } else {
                    // $('.layui-form-checkbox').not($(this)).removeClass('layui-form-checked');
                    $('.p-input-1').val('');
                    $('.p-input-2').val('');
                }
            }
        });

        $(document).ready(function () {
            $('.p-input-1').val('');
            $('.p-input-2').val('');
        });

        //遍历select option
        // $(document).ready(function(){
        // 	$("#pid-select option").each(function (text){
        // 	 	var level = $(this).attr('data-level');
        // 	 	var text = $(this).text();
        // 	 	console.log(text);
        // 	 	if(level>0){
        // 	 		text = "├　"+ text;
        // 	 		for(var i=0;i<level;i++){
        // 		 		text ="　　"+ text;　//js中连续显示多个空格，需要使用全角的空格
        // 		 		//console.log(i+"text:"+text);
        // 		 	}
        // 	 	}
        // 	 	$(this).text(text);
        //
        // 	});
        //
        // 	form.render('select'); //刷新select选择框渲染
        // });
        // 	form.render('select'); //刷新select选择框渲染

        form.render(); //刷新select选择框渲染

        function checkedClass(obj) {
            console.log(obj);
            obj.addClass('layui-form-checked');
        }

    });
</script>
@endsection
