@extends('home.layout')

@section('pageCss')
    <link href="{{asset(_HOME_ . '/css/detail.css')}}" rel="stylesheet"/>
    <link rel="stylesheet" href="{{asset(_ADMIN_ . '/static/editor/css/editormd.preview.css')}}"/>
    <style>
        .editormd-html-preview {
            width: 96%;
        }
    </style>
@endsection

@section('body')
    <!-- 主体（一般只改变这里的内容） -->
    <div class="blog-body">
        <div class="blog-container">
            <blockquote class="layui-elem-quote sitemap layui-breadcrumb shadow">
                <a href="home.html" title="网站首页">网站首页</a>
                <a class="target" href="article.html" title="文章专栏">文章专栏</a>
                <a><cite>文章标题</cite></a>
            </blockquote>
            <div class="blog-main">
                <div class="blog-main-left">
                    <!-- 文章内容（使用Kingeditor富文本编辑器发表的） -->
                    <div class="article-detail shadow">
                        <div class="article-detail-title">
                            {{ $article['title'] }}
                        </div>
                        <div class="article-detail-info">
                            <span>创建时间：{{ $article['created_at'] }}</span>
                            <span>上次编辑：{{ $article['updated_at'] }}</span>
                            <span>浏览量：{{ $article['read_times'] }}</span>
                        </div>
                        <div class="article-detail-content">
                            <p style="text-align:center;">
                                <span style="font-size:18px;">文章分类：{{ $article['category']['cate_title'] }}</span>
                            </p>


                            <div style="text-align:center;">
                                &nbsp; &nbsp; <span
                                        style="color:#EE33EE;">前言</span>：{{ isset($article['note']) ? $article['note'] : '暂无' }}
                            </div>
                            <hr/>
                            <p>
                                <br/>
                            </p>

                            <div id="test-editormd-view">
                                <textarea id="append-test" style="display:none;">{{ $article['content'] }}</textarea>
                            </div>

                            <hr/>
                            <p>
                                <br/>
                            </p>
                            <p>
                                &nbsp; &nbsp; 本文标签：
                                @foreach($tags as $tag)
                                    &nbsp; &nbsp; <a href="/article?tag={{ $tag['id'] }}" target="_blank"><span
                                                style="color:#337FE5;">{{ $tag['name'] }}</span></a>
                                @endforeach
                            </p>
                            <hr/>
                            &nbsp; &nbsp; 出自：云间阁
                            <p>
                                &nbsp; &nbsp; 地址：<a href="http://www.nblog.com" target="_blank">www.nblog.com</a>
                            </p>
                            <p>
                                &nbsp; &nbsp; 转载请注明出处！<img
                                        src="http://www.lyblogs.cn/kindeditor/plugins/emoticons/images/0.gif" border="0"
                                        alt=""/>
                            </p>
                            <p>
                                <br/>
                            </p>
                        </div>
                    </div>


                    <!-- 评论区域 -->
                    <div class="blog-module shadow" style="box-shadow: 0 1px 8px #a6a6a6;">
                        <fieldset class="layui-elem-field layui-field-title" style="margin-bottom:0">
                            <legend>来说两句吧</legend>
                            <div class="layui-field-box">
                                <form class="layui-form blog-editor" action="">
                                    <div class="layui-form-item">
                                        <textarea name="editorContent" lay-verify="content" id="remarkEditor"
                                                  placeholder="请输入内容" class="layui-textarea layui-hide"></textarea>
                                        <div class="layui-input-inline">
                                            <input class="captcha" name="captcha" lay-verify="required" placeholder="验证码" type="text" autocomplete="off" style="width: 84.5%; height: 30px;">
                                            <img src="{{ $img }}">
                                        </div>
                                    </div>
                                    <div class="layui-form-item">
                                        <button class="layui-btn" lay-submit="formRemark" lay-filter="formRemark">提交评论
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </fieldset>
                        <div class="blog-module-title">最新评论</div>
                        <ul class="blog-comment">
                            @foreach($comments as $comment)
                                <li>
                                    <div class="comment-parent">
                                        <img src="{{asset(_HOME_ . '/images/Absolutely.jpg')}}" alt="洛克哈特"/>
                                        <div class="info">
                                            <span class="username">{{ $comment['userName'] }}</span>
                                            <span class="time">{{ $comment['created_at'] }}</span>
                                        </div>
                                        <div class="content">
                                            {{ $comment['content'] }}
                                        </div>
                                    </div>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                </div>
                <div class="blog-main-right">
                    <!--右边悬浮 平板或手机设备显示-->
                    <div class="category-toggle"><i class="fa fa-chevron-left"></i></div>
                    <!--这个div位置不能改，否则需要添加一个div来代替它或者修改样式表-->
                    <div class="article-category shadow" style="display: none">
                        <div class="article-category-title">分类导航</div>
                        <!-- 点击分类后的页面和artile.html页面一样，只是数据是某一类别的 -->
                        <a href="javascript:layer.msg(&#39;切换到相应分类&#39;)">ASP.NET MVC</a>
                        <a href="javascript:layer.msg(&#39;切换到相应分类&#39;)">SQL Server</a>
                        <a href="javascript:layer.msg(&#39;切换到相应分类&#39;)">Entity Framework</a>
                        <a href="javascript:layer.msg(&#39;切换到相应分类&#39;)">Web前端</a>
                        <a href="javascript:layer.msg(&#39;切换到相应分类&#39;)">C#基础</a>
                        <a href="javascript:layer.msg(&#39;切换到相应分类&#39;)">杂文随笔</a>
                        <div class="clear"></div>
                    </div>

                    <div class="article-category shadow">
                        <div class="article-category-title">分类导航</div>
                        <!-- 点击分类后的页面和artile.html页面一样，只是数据是某一类别的 -->

                        <div id="cateTree" class="demo-tree"></div>

                        <div class="clear"></div>
                    </div>

                    <div class="blog-module shadow">
                        <div class="blog-module-title">相似文章</div>
                        <ul class="fa-ul blog-module-ul">
                            @foreach ($likes as $like)
                            <li><i class="fa-li fa fa-hand-o-right"></i><a href="/detail/{{ $like['id'] }}">{{ $like['title'] }}</a>
                            </li>
                            @endforeach
                        </ul>
                    </div>
                    <div class="blog-module shadow">
                        <div class="blog-module-title">随便看看</div>
                        <ul class="fa-ul blog-module-ul">
                            @foreach ($rands as $rand)
                                <li><i class="fa-li fa fa-hand-o-right"></i><a href="/detail/{{ $rand['id'] }}">{{ $rand['title'] }}</a></li>
                            @endforeach
                        </ul>
                    </div>
                </div>
                <div class="clear"></div>
            </div>
        </div>
    </div>
@endsection

@section('pageJs')
    <!-- 比较好用的代码着色插件 -->
    <script src="{{asset(_HOME_ . '/js/prettify.js')}}"></script>
    <!-- 本页脚本 -->
    <script src="{{asset(_ADMIN_ . '/static/editor/jquery.min.js')}}"></script>
    <script src="{{asset(_ADMIN_ . '/static/editor/lib/marked.min.js')}}"></script>
    <script src="{{asset(_ADMIN_ . '/static/editor/lib/prettify.min.js')}}"></script>
    <script src="{{asset(_ADMIN_ . '/static/editor/lib/raphael.min.js')}}"></script>
    <script src="{{asset(_ADMIN_ . '/static/editor/lib/underscore.min.js')}}"></script>
    <script src="{{asset(_ADMIN_ . '/static/editor/lib/sequence-diagram.min.js')}}"></script>
    <script src="{{asset(_ADMIN_ . '/static/editor/lib/flowchart.min.js')}}"></script>
    <script src="{{asset(_ADMIN_ . '/static/editor/lib/jquery.flowchart.min.js')}}"></script>
    <script src="{{asset(_ADMIN_ . '/static/editor/editormd.min.js')}}"></script>
    {{--    <script src="{{asset(_HOME_ . '/js/detail.js')}}"></script>--}}
    <script type="text/javascript">

        var target = $('.target').html();
        // ll(target);
        $(".layui-nav li[class='layui-nav-item']").each(function () {
            // ll($(this).find('a').text());
            // ll($(this).find('a').text() === target);
            if ($(this).find('a').text() == target) {
                $(this).addClass('layui-this');
            } else {
                $(this).removeClass('layui-this');
            }
        });

        prettyPrint();
        layui.use(['form', 'layedit', 'tree'], function () {
            var form = layui.form();
            var $ = layui.jquery;
            var layedit = layui.layedit;
            var tree = layui.tree;

            $(function(){
                $('img').click(function(){
                    $(this).attr('src','{{captcha_src('mini')}}' + '&_=' + Math.random());
                })
            })

            // 显示评论
            var id = {{ $article['id'] }};
            var imgSrc = "{{asset(_HOME_ . '/images/Absolutely.jpg')}}";
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url: '/getComments/' + id,
                type: 'GET',
                dataType: 'JSON',
                success: function (res) {
                    ll('comment OK');
                    var data = res.data;
                    var comment = '';
                    $.each(data, function (i, item) {
                        comment += '<li><div class="comment-parent">' +
                            '<img src=' + imgSrc + ' alt="absolutely"/>' +
                            '<div class="info">' +
                            '<span class="username">' + item.userName + '</span>' +
                            '<span class="time">' + item.created_at + '</span></div>' +
                            '<div class="content">' + item.content + '</div>' +
                            '</div></li>';
                    })
                    $('.blog-comment').append(comment);
                },
                error: function (res) {
                    ll('comment error');
                    ll(res);
                }
            });




            //评论和留言的编辑器
            var editIndex = layedit.build('remarkEditor', {
                height: 150,
                tool: ['face', '|', 'left', 'center', 'right', '|', 'link'],
            });
            //评论和留言的编辑器的验证
            layui.form().verify({
                content: function (value) {
                    value = $.trim(layedit.getText(editIndex));
                    if (value == "") return "至少得有一个字吧";
                    layedit.sync(editIndex);
                }
            });

            //监听评论提交
            form.on('submit(formRemark)', function (data) {
                var index = layer.load(1);

                ll('点击评论');
                var content = data.field.editorContent;
                var articleId = {{ $article['id'] }};
                var captcha = $('.captcha').val();
                var body = {'articleId': articleId, 'content': content, 'captcha': captcha};

                $.ajax({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    url: '/comments',
                    type: 'POST',
                    data: body,
                    dataType: 'JSON',
                    success: function (res) {
                        ll('OK');
                        ll(res);
                        if (res.code === 200) {
                            layer.close(index);
                            var content = res.data.content;
                            var imgSrc = "{{asset(_HOME_ . '/images/Absolutely.jpg')}}";
                            var html = '<li><div class="comment-parent">' +
                                // '<img src="../images/Absolutely.jpg"alt="absolutely"/>' +
                                '<img src=' + imgSrc + ' alt="云间阁"/>' +
                                '<div class="info">' +
                                '<span class="username">'+ res.data.userName + '</span>' +
                                '<span class="time">' + res.data.time +' </span></div>' +
                                '<div class="content">' + content + '</div>' +
                                '</div></li>';
                            $('.blog-comment').append(html);
                            $('#remarkEditor').val('');
                            editIndex = layui.layedit.build('remarkEditor', {
                                height: 150,
                                tool: ['face', '|', 'left', 'center', 'right', '|', 'link'],
                            });
                            layer.msg("评论成功", {icon: 1});
                        } else {
                            layer.close(index);
                            layer.msg(res.text, {icon: 2});
                        }
                        return false;
                    },
                    error: function (res) {
                        ll('error');
                        ll(res);
                        return false;
                    }
                });

                return false;





                //模拟评论提交
                setTimeout(function () {
                    layer.close(index);
                    var content = data.field.editorContent;
                    var imgSrc = "{{asset(_HOME_ . '/images/Absolutely.jpg')}}";
                    var html = '<li><div class="comment-parent">' +
                        // '<img src="../images/Absolutely.jpg"alt="absolutely"/>' +
                        '<img src=' + imgSrc + ' alt="absolutely"/>' +
                        '<div class="info">' +
                        '<span class="username">Absolutely</span>' +
                        '<span class="time">2017-03-18 18:46:06</span></div>' +
                        '<div class="content">' + content + '</div>' +
                        '</div></li>';
                    $('.blog-comment').append(html);
                    $('#remarkEditor').val('');
                    editIndex = layui.layedit.build('remarkEditor', {
                        height: 150,
                        tool: ['face', '|', 'left', 'center', 'right', '|', 'link'],
                    });
                    layer.msg("评论成功", {icon: 1});
                }, 500);
                return false;
            });

            tree({
                elem: "#cateTree"
                ,
                nodes: @json($categories)
                ,
                click: function (node) {
                    var $select = $($(this)[0].elem).parents(".layui-form-select");
                    $select.removeClass("layui-form-selected").find(".layui-select-title span").html(node.name).end().find("input:hidden[name='selectID']").val(node.id);
                }
            });

        });


        $(function () {
            var testEditor;
            testEditor = editormd.markdownToHTML("test-editormd-view", {//注意：这里是上面DIV的id
                htmlDecode: "style,script,iframe",
                emoji: true,
                taskList: true,
                tex: true, // 默认不解析
                flowChart: true, // 默认不解析
                sequenceDiagram: true, // 默认不解析
                codeFold: true,
            });
        });
    </script>
@endsection
