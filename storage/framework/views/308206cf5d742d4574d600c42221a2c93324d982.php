<meta name="csrf-token" content="<?php echo e(csrf_token()); ?>"/>
<link rel="stylesheet" href="<?php echo e(asset(_ADMIN_ . '/static/editor/css/editormd.css')); ?>">
<?php $__env->startSection('content'); ?>
<div class="weadmin-body">

    <form id="form1" class="layui-form" method="post">
        <?php echo e(method_field('PATCH')); ?>

        <div class="layui-tab">
            <ul class="layui-tab-title">
                <li class="layui-this">编辑文章</li>
            </ul>
            <div class="layui-tab-content">
                <div class="layui-tab-item layui-show">

                    <div class="layui-form-item">
                        <label class="layui-form-label">标题</label>
                        <div class="layui-input-block">
                            <input type="text" name="title" lay-verify="required" jq-error="请输入文章标题" placeholder="请输入文章标题" autocomplete="off" class="layui-input" value="<?php echo e($article['title']); ?>">
                            <input type="hidden" class="pid" value="<?php echo e($article['cate_id']); ?>" name="cate_id">
                        </div>
                    </div>

                    <div class="layui-form-item">
                        <label for="" class="layui-form-label">选择分类</label>
                        <div class="layui-input-block">
                            <input type="text" id="tree" lay-filter="tree" class="layui-input">
                        </div>
                    </div>

                    
                    <div id="layout" class="editor">
                        <style>
                            .layui-form select{display: inline;}
                            ul li {
                                list-style: circle;
                            }
                            ol li {
                                list-style: decimal;
                            }
                        </style>
                        <div id="test-editormd">
                            <textarea class="editormd-markdown-textarea" name="test-editormd-markdown-doc" style="display: none;"><?php echo e($article['content']); ?></textarea>
                        </div>
                    </div>

                    
                    <div class="layui-form-item">
                        <label for="" class="layui-form-label">选择标签</label>
                        <?php $__currentLoopData = $tags; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $tag): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <div class="layui-input-inline" id="insertInput11">
                                <input readonly type="text" value="<?php echo e($tag['name']); ?>" autocomplete="off" placeholder="请输入" lay-verify="required" class="layui-input tag">
                            </div>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </div>

                    
                    <div class="layui-form-item">
                        <label for="" class="layui-form-label">选择结果</label>
                        <div class="layui-input-block">
                            <input type="text" value="<?php echo e($tagStr); ?>" name="tags" autocomplete="off" placeholder="请选择上方标签，如要添加新标签，以英文逗号间隔输入，如‘标签1,标签2’" lay-verify="required" class="layui-input choosed-tags">
                            <i class="layui-icon layui-icon-close deleteEduBg"></i>
                        </div>
                    </div>

                </div>
            </div>
        </div>

        <div class="layui-form-item">
            <div class="layui-input-block">
                <button class="layui-btn" id="editTopic" lay-submit="" lay-filter="add">立即提交</button>
                <button type="reset" class="layui-btn layui-btn-primary">重置</button>
            </div>
        </div>
        <input type="hidden" name="level" value="0" />
    </form>
</div>
<?php $__env->stopSection(); ?>
<?php $__env->startSection('script'); ?>
<script type="text/javascript" src="<?php echo e(asset(_ADMIN_ . '/lib/layui/layui.js')); ?>" charset="utf-8"></script>
<script type="text/javascript" src="<?php echo e(asset(_ADMIN_ . '/static/editor/jquery.min.js')); ?>"></script>
<script type="text/javascript" src="<?php echo e(asset(_ADMIN_ . '/static/editor/editormd.min.js')); ?>"></script>
<?php $__env->stopSection(); ?>
<?php $__env->startSection('js'); ?>
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
            ll(data.field);
            //发异步，把数据提交给php

            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url: '/admin/article/' + <?php echo e($article['id']); ?>,
                data: data.field,
                type: 'post',
                dataType: 'JSON',
                success: function (res) {
                    if (res.code !== 200) {
                        layer.alert(res.text);
                        return false;
                    } else {
                        // 获得frame索引
                        var index = parent.layer.getFrameIndex(window.name);
                        //关闭当前frame
                        parent.layer.close(index);
                    }
                },
                error: function () {
                    ll('error');
                }

            });



            layer.alert("增加成功", {
                icon: 6
            }, function () {
                // 获得frame索引
                var index = parent.layer.getFrameIndex(window.name);
                //关闭当前frame
                parent.layer.close(index);
            });
            return false;
        });

        // 请求下拉列表树
        var dataUrl = '/admin/category/treeForSelect';
        

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
            click: function(d){
                ll('根据点击结果动态改变pid的值');
                // console.log(d.current.id);
                $(".pid").val(d.current.id);
            },
            // 加载完成后的回调函数
            success: function (d) {
                ll('加载完成后的回调函数');
                console.log(d);
//                选中节点，根据id筛选
// 						treeSelect.checkNode('tree', catId);
                treeSelect.checkNode('tree', <?php echo e($article['cate_id']); ?>);
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

    // md 文本编辑器
    $(function () {
        var testEditor;
        testEditor = editormd("test-editormd", {
            placeholder:'本编辑器支持Markdown编辑，左边编写，右边预览',  //默认显示的文字，这里就不解释了
            width: "90%",
            height: 640,
            syncScrolling: "single",
            path: "<?php echo e(_ADMIN_ . '/static/editor/lib/'); ?>",   //你的path路径（原资源文件中lib包在我们项目中所放的位置）

            imageUpload : true,
            imageFormats : ["jpg", "jpeg", "gif", "png", "bmp", "webp"],
            imageUploadURL : "/admin/article/photo",
            onload: function() {
                ll('omload');
            },

            theme: "dark",//工具栏主题
            previewTheme: "dark",//预览主题
            editorTheme: "pastel-on-dark",//编辑主题

            codeFold : true,
            searchReplace    : true,

            saveHTMLToTextarea: true,
            emoji: true,
            taskList: true,
            tocm: true,         // Using [TOCM]
            tex: true,                   // 开启科学公式TeX语言支持，默认关闭
            flowChart: true,             // 开启流程图支持，默认关闭
            sequenceDiagram: true,       // 开启时序/序列图支持，默认关闭,
            toolbarIcons : function() {  //自定义工具栏，后面有详细介绍
                // return editormd.toolbarModes['simple']; // full, simple, mini
                return editormd.toolbarModes['full']; // full, simple, mini
            },
        });

        // 点击添加标签
        $('.tag').click(function () {
            var choosedTags = $('.choosed-tags').val();
            var current = this.value;
            if (choosedTags !== '') {
                choosedTags += ',' + current;
            } else {
                choosedTags = current;
            }
            $('.choosed-tags').val(choosedTags);
        });

        // 点击清除已选标签
        $("body").on("click", ".deleteEduBg", function (e) { //user click on remove text
            $('.choosed-tags').val('');
        })
    });

</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('admin.layouts.admin', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>