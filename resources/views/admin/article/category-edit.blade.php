@extends('admin.layouts.admin')
<meta name="csrf-token" content="{{ csrf_token() }}"/>
@section('content')
<div class="weadmin-body">

        <form id="form1" class="layui-form">
            {{ method_field('PATCH') }}
            <div class="layui-tab">
                <ul class="layui-tab-title">
                    <li class="layui-this">基本设置</li>
                    {{--						<li>栏目模板</li>--}}
                    {{--						<li>栏目简介</li>--}}
                </ul>
                <div class="layui-tab-content">
                    <div class="layui-tab-item layui-show">

                        <div class="layui-form-item">
                            <label for="" class="layui-form-label">父类</label>
                            <div class="layui-input-block">
                                <input type="text" id="tree" lay-filter="tree" class="layui-input">
                            </div>
                        </div>

                        <div class="layui-form-item">
                            <label class="layui-form-label">分类名称</label>
                            <div class="layui-input-block">
                                <input type="text" name="cate_title" value="{{ $detail['cate_title'] }}"
                                       lay-verify="required" jq-error="请输入分类名称" placeholder="请输入分类名称" autocomplete="off"
                                       class="layui-input">
                                <input type="hidden" class="pid" value="{{ $detail['pid'] }}" name="pid">
                                <input type="hidden" value="{{ $detail['id'] }}" name="id">
                                <input type="hidden" value="update" name="_scene">
                            </div>
                        </div>
                        <div class="layui-form-item" style="display: none">
                            <label class="layui-form-label">栏目别名</label>
                            <div class="layui-input-block">
                                <input type="text" name="cname" value="1" lay-verify="required" jq-error="请输入栏目别名"
                                       placeholder="请输入栏目别名" autocomplete="off" class="layui-input">
                            </div>
                        </div>
                        <div class="layui-form-item" style="display: none">
                            <label class="layui-form-label">栏目缩略图</label>
                            <div class="layui-input-block">
                                <button type="button" class="layui-btn layui-btn-primary" id="topicImg"><i
                                            class="layui-icon">&#xe67c;</i>上传图片
                                </button>
                            </div>
                        </div>
                        <div class="layui-form-item">
                            <label class="layui-form-label">排序</label>
                            <div class="layui-input-inline">
                                <input type="text" name="top" lay-verify="number" value="100" jq-error="排序必须为数字"
                                       placeholder="分类排序" autocomplete="off" class="layui-input">
                            </div>
                        </div>
                        <div class="layui-form-item">
                            <label class="layui-form-label">状态</label>
                            <div class="layui-input-inline">
                                <input type="radio" name="status" title="启用" value="1" checked/>
                                <input type="radio" name="status" title="禁用" value="0"/>
                            </div>
                        </div>
                        <!--//tab1 content-->
                    </div>
                    {{--						<div class="layui-tab-item">--}}
                    {{--							<!--tab2 content-->--}}
                    {{--							<div class="layui-form-item">--}}
                    {{--								<label class="layui-form-label">栏目类型</label>--}}
                    {{--								<div class="layui-input-block">--}}
                    {{--									<input type="radio" name="type" title="频道" value="0" checked />--}}
                    {{--									<input type="radio" name="type" title="单页" value="1" />--}}
                    {{--									<input type="radio" name="type" title="外链" value="2" />--}}
                    {{--								</div>--}}
                    {{--							</div>--}}
                    {{--							<div class="layui-form-item">--}}
                    {{--								<label class="layui-form-label">外链地址</label>--}}
                    {{--								<div class="layui-input-block">--}}
                    {{--									<input type="text" name="topicLink" value="1" lay-verify="required" jq-error="请输入栏目跳转网址" placeholder="请输入栏目跳转网址" autocomplete="off" class="layui-input ">--}}
                    {{--								</div>--}}
                    {{--							</div>--}}
                    {{--							<div class="layui-form-item">--}}
                    {{--								<label class="layui-form-label">栏目模板</label>--}}
                    {{--								<div class="layui-input-block">--}}
                    {{--									<div class="layui-input-inline">--}}
                    {{--										<input type="text" name="topicModel" value="1" lay-verify="required" jq-error="请选择栏目模板" placeholder="请选择栏目模板" autocomplete="off" class="layui-input ">--}}
                    {{--									</div>--}}
                    {{--									<button type="button" class="layui-btn layui-btn-primary" id="topicModelBtn">选择文件</button>--}}
                    {{--								</div>--}}
                    {{--							</div>--}}

                    {{--							<!--//tab2 content-->--}}
                    {{--						</div>--}}
                    {{--						<div class="layui-tab-item">--}}
                    {{--							<!--tab3 content-->--}}
                    {{--							<textarea id="topicBody" style="display: none;"></textarea>--}}
                    {{--							<!--//tab3 content-->--}}
                    {{--						</div>--}}

                </div>
            </div>

            <div class="layui-form-item">
                <div class="layui-input-block">
                    <button class="layui-btn" id="editTopic" lay-submit="" lay-filter="add">立即提交</button>
                    <button type="reset" class="layui-btn layui-btn-primary">重置</button>
                </div>
            </div>
            <input type="hidden" name="level" value="0"/>
        </form>
    </div>
@endsection
@section('script')
<script type="text/javascript" src="{{asset(_ADMIN_ . '/lib/layui/layui.js')}}" charset="utf-8"></script>
{{--		<script type="text/javascript" src="{{asset(_ADMIN_ . '/lib/layui/lay/modules/common.js')}}" charset="utf-8"></script>--}}
{{--		<script src="{{asset(_ADMIN_ . '/category-edit.js')}}" type="text/javascript" charset="utf-8"></script>--}}
@endsection
@section('js')
<script>
        layui.extend({
            admin: '{/}/static/admin/static/js/admin',
            treeSelect: '{/}/static/admin/lib/layui/lay/modules/treeSelect'
        });
        layui.use(['admin', 'jquery', 'element', 'upload', 'form', 'layer', 'layedit', 'treeSelect'], function () {

            var admin = layui.admin,
                $ = layui.jquery,
                element = layui.element,
                upload = layui.upload,
                form = layui.form,
                layer = layui.layer,
                treeSelect = layui.treeSelect,
                layedit = layui.layedit;

            // console.log(treeSelect);
            // console.log(form);
            //图片上传
            //上传缩略图，设定文件大小限制
            upload.render({
                elem: '#topicImg',
                url: '/upload/',
                size: 500 //限制文件大小，单位 KB
                ,
                done: function (res) {
                    console.log(res)
                }
            });
            //选择文件，栏目模板
            upload.render({
                elem: '#topicModelBtn',
                url: '/upload/',
                auto: false,
                accept: 'file'
                //,multiple: true
                ,
                bindAction: '#editTopic',
                choose: function (res) {
                    //var files = res.pushFile();
                    //预读本地文件，如果是多文件，则会遍历。(不支持ie8/9)
                    res.preview(function (index, file, result) {
                        //console.log(index); //得到文件索引
                        //console.log(file); //得到文件对象
                        //console.log(result); //得到文件base64编码，比如图片
                        $('input[name=topicModel]').val(file.name);
                        //console.log($('input[name=topicModel]').val())

                    });
                }
            });

            layedit.build('topicBody'); //建立编辑器

            //监听提交
            form.on('submit(add)', function (data) {
                console.log(data.field);
                //发异步，把数据提交给php

                $.ajax({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    url: '/admin/category',
                    data: data.field,
                    type: 'post',
                    dataType: 'JSON',
                    success: function (res) {
                        if (res.code !== 200) {
                            layer.alert(res.text);
                            return false;
                        }
                        var index = parent.layer.getFrameIndex(window.name);
                        //关闭当前frame
                        parent.layer.close(index);

                    },
                    error: function () {
                        ll('error');
                    }

                });


                // layer.alert("增加成功", {
                // 	icon: 6
                // }, function () {
                // 	// 获得frame索引
                // 	var index = parent.layer.getFrameIndex(window.name);
                // 	//关闭当前frame
                // 	parent.layer.close(index);
                // });
                return false;
            });

            // 请求下拉列表树
            var dataUrl = '/admin/category/treeForSelect';
            var catId = {{ $id }};

            // treeSelect组件
            treeSelect.render({
                // 选择器
                elem: '#tree',
                // 数据
                // data: 'data/data3.json',
                data: dataUrl,
                // 请求头
                headers: {},
                // 异步加载方式：get/post，默认get
                type: 'get',
                // 占位符
                placeholder: '修改默认提示信息',
                // 是否开启搜索功能：true/false，默认false
                search: true,
                // 一些可定制的样式
                style: {
                    folder: {
                        enable: true
                    },
                    line: {
                        enable: true
                    }
                },
                // 点击回调
                click: function (d) {
                    ll('根据点击结果动态改变pid的值');
                    // console.log(d.current.id);
                    $(".pid").val(d.current.id);
                },
                // 加载完成后的回调函数
                success: function (d) {
                    ll('加载完成后的回调函数');
                    ll(catId);
                    console.log(d);
//                选中节点，根据id筛选
                    treeSelect.checkNode('tree', catId);

                    console.log($('#tree').val());

//                获取zTree对象，可以调用zTree方法
                    var treeObj = treeSelect.zTree('tree');
                    console.log(treeObj);

//                刷新树结构
                    treeSelect.refresh('tree');
                }
            });

            form.render();

        });
</script>
@endsection
