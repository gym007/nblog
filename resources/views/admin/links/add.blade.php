@extends('admin.layouts.admin')
<meta name="csrf-token" content="{{ csrf_token() }}"/>
@section('content')
<div class="weadmin-body">
    <form class="layui-form">
        <div class="layui-form-item">
            <label for="username" class="layui-form-label">
                <span class="we-red">*</span>链接名
            </label>
            <div class="layui-input-inline">
                <input type="text" id="username" name="name" required="" lay-verify="required" autocomplete="off"
                       class="layui-input" value="{{ $link['name'] }}">
            </div>
        </div>
        <div class="layui-form-item">
            <label for="username" class="layui-form-label">
                <span class="we-red">*</span>链接
            </label>
            <div class="layui-input-inline">
                <input type="text" id="username" name="link" required="" lay-verify="required" autocomplete="off"
                       class="layui-input" value="{{ $link['link'] }}">
            </div>
        </div>
        <div class="layui-form-item">
            <label for="L_repass" class="layui-form-label">
            </label>
            <button class="layui-btn" lay-filter="add" lay-submit="">提交</button>
        </div>
    </form>
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
    layui.use(['form', 'jquery', 'table', 'layer'], function () {
        var form = layui.form,
            admin = layui.admin,
            $ = layui.jquery,
            table = layui.table,
            layer = layui.layer;

        //自定义验证规则
        // form.verify({
        //     nikename: function (value) {
        //         if (value.length < 5) {
        //             return '昵称至少得5个字符啊';
        //         }
        //     },
        //     pass: [/(.+){6,12}$/, '密码必须6到12位'],
        //     repass: function (value) {
        //         if ($('#L_pass').val() != $('#L_repass').val()) {
        //             return '两次密码不一致';
        //         }
        //     }
        // });

        //监听提交
        form.on('submit(add)', function (data) {
            //发异步，把数据提交给php
            var edit = {{ $edit }};
            var url;
            if (edit) {
                data.field._method = 'PUT';
                url = '/admin/links/' + {{ $link['id'] }};
            } else {
                url = '/admin/links';
            }

            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                // url: '/admin/links',
                url: url,
                type: 'POST',
                data: data.field,
                dataType: 'JSON',
                success: function (res) {
                    if (res.code === 200) {
                        layer.alert("OK", {
                            icon: 6
                        }, function () {
                            // 获得frame索引
                            var index = parent.layer.getFrameIndex(window.name);
                            // 点击关闭当前frame
                            parent.layer.close(index);
                        });
                    } else {
                        layer.alert(res.text);
                    }
                },
                error: function (res) {
                    ll('error');
                    ll(res);
                }
            });



            // layer.alert("增加成功", {
            //     icon: 6
            // }, function () {
            //     // 获得frame索引
            //     var index = parent.layer.getFrameIndex(window.name);
            //     //关闭当前frame
            //     parent.layer.close(index);
            // });
            return false;
        });

        var num = 3;

        window.addTable = function () {
            var tableHtml = "";
            tableHtml += '<tr id="tr' + num + '">' +
                '<td>' + num + '</td>' +
                '<td><div class="layui-input-inline"><input type="text" name="canshu1" class="layui-input"></div></td>' +
                '<td><div class="layui-input-inline"><input type="text" name="canshu2" class="layui-input"></div></td>' +
                '<td><div class="layui-input-inline"><input type="text" name="canshu3" class="layui-input"></div></td>' +
                '<td><div class="layui-input-inline"><input type="text" name="canshu4" class="layui-input"></div></td>' +
                '<td><a style="cursor: pointer; color: blue;" onclick="removeTr(' + num + ')">删除</a>' +
                '</td>' +
                '</tr>';
//				tableHtml +='<tr>'+
//								'<td>2</td>'+
//								'<td>haier海尔 BC-93TMPF 93升单门冰箱</td>'+
//								'<td>0.01</td>'+
//								'<td>984</td>'+
//								'<td>9.84</td>'+
//								'<td><a style="cursor: pointer; color: blue;" onclick="removeTr(2)">删除</a></td>'+
//							'</tr>';

            var elements = $("#myTable").children().length; //表示id为“mtTable”的标签下的子标签的个数

            $("#myTable").children().eq(elements - 1).after(tableHtml); //在表头之后添加空白行
            num++;
        }

        //删除行
        function removeTr(trNum) {
            $("#tr" + trNum).remove();
        }

    });
</script>
@endsection
