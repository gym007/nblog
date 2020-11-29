@extends('admin.layouts.admin')
<meta name="csrf-token" content="{{ csrf_token() }}"/>
@section('content')
<div class="weadmin-body">
    <form class="layui-form">
        <div class="layui-form-item">
            <label for="categoryWeight" class="layui-form-label">栏目权重</label>
            <div class="layui-input-inline">
                <input type="text" id="weight" name="weight" autocomplete="off" class="layui-input">
            </div>
        </div>
        <div class="layui-form-item">
            <label for="categoryName" class="layui-form-label">栏目名称</label>
            <div class="layui-input-inline">
                <input type="text" id="categoryName" name="categoryName" autocomplete="off" class="layui-input">
            </div>
        </div>


        <div class="layui-form-item">
            <label for="username" class="layui-form-label">
                <span class="we-red">*</span>父级栏目
            </label>
            <div class="layui-input-inline">
                <select name="contrller">
                    <option>支付方式</option>
                    <option>支付宝</option>
                    <option>微信</option>
                    <option>货到付款</option>
                </select>
            </div>
        </div>
    </form>
</div>
@endsection
@section('script')
<script type="text/javascript" src="{{asset(_ADMIN_ . '/lib/layui/layui.js')}}" charset="utf-8"></script>
@endsection
@section('js')
<script>
    layui.use(['form', 'layer'], function () {
        var form = layui.form,
            layer = layui.layer;

        //监听提交
        form.on('submit(add)', function (data) {
            console.log(data);
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
@endsection
