@extends('home.layout')

@section('pageCss')
    <link href="{{asset(_HOME_ . '/css/about.css')}}" rel="stylesheet"/>
@endsection

@section('body')
    <!-- 主体（一般只改变这里的内容） -->
    <div class="blog-body">
        <div class="blog-container">
            <blockquote class="layui-elem-quote sitemap layui-breadcrumb shadow">
                <a href="" title="网站首页">网站首页</a>
                <a><cite>关于本站</cite></a>
            </blockquote>
            <div class="blog-main">
                <div class="layui-tab layui-tab-brief shadow" lay-filter="tabAbout">
                    <ul class="layui-tab-title">
                        <li lay-id="1">关于博客</li>
                        <li lay-id="2">关于作者</li>
                        <li lay-id="3" id="frinedlink">友情链接</li>
                        <li lay-id="4">留言墙</li>
                    </ul>
                    <div class="layui-tab-content">
                        <div class="layui-tab-item">
                            <div class="aboutinfo">
                                <div class="aboutinfo-figure">
                                    <img src="/storage/uploads/2020/06/logo.jpg" width="20%" height="15%" alt="云间阁"/>
                                </div>
                                <p class="aboutinfo-nickname">云间阁</p>
                                <p class="aboutinfo-introduce">一个PHP程序员的个人博客，记录博主学习和成长之路，分享PHP方面技术和源码</p>
                                <p class="aboutinfo-location"><i class="fa fa-link"></i>&nbsp;&nbsp;<a target="_blank"
                                                                                                       href="http://www.nblog.com:99">www.nblog.com:99</a>
                                </p>
                                <hr/>
                                <div class="aboutinfo-contact">
                                    <a target="_blank" title="网站首页" href="/"><i class="fa fa-home fa-2x"
                                                                                        style="font-size:2.5em;position:relative;top:3px"></i></a>
                                    <a target="_blank" title="文章专栏" href="/article"><i
                                                class="fa fa-file-text fa-2x"></i></a>
                                    <a target="_blank" title="资源分享" href="resource.html" style="display: none"><i
                                                class="fa fa-tags fa-2x"></i></a>
                                    <a target="_blank" title="点点滴滴" href="timeline.html" style="display: none"><i
                                                class="fa fa-hourglass-half fa-2x"></i></a>
                                </div>

                                <fieldset class="layui-elem-field layui-field-title">
                                    <legend>简介</legend>
                                    <div class="layui-field-box aboutinfo-abstract">
                                        <p style="text-align:center;">云间阁是一个由thinkphp3.2
                                            MVC开发的个人博客网站，诞生于2017年11月7日，起劲为止经历了一次大改，暂且称为云间阁2.0。</p>
                                        <h1>第一个版本</h1>
                                        <p>诞生的版本，采用thinkphp5作为后台框架，用了Bootstrap的栅格系统来布局！起初并没有注意美工，只打算完成基本的功能，故视觉体验是比较差的。</p>
                                        <h1>当前版本</h1>
                                        <p>
                                            从公司的一个后台管理系统的前端发现了Layer弹窗插件，于是追根溯源，发现了Layui前端框架！Layui简洁的风格让我很是喜欢，于是决定再次将网站改版！此次改版从里到外几乎全部更新。后台增加了面向接口开发，使用了laravel框架,前端则移除Bootstarp，引入Layui。视觉体验显著提高。</p>
                                        <h1 style="text-align:center;">The End</h1>
                                    </div>
                                </fieldset>
                            </div>
                        </div><!--关于网站End-->
                        <div class="layui-tab-item">
                            <div class="aboutinfo">
                                <div class="aboutinfo-figure">
                                    <img src="../images/Absolutely.jpg" alt="Absolutely"/>
                                </div>
                                <p class="aboutinfo-nickname">洛克哈特</p>
                                <p class="aboutinfo-introduce">一枚PHP开发工程师，略懂Web前端</p>
                                <p class="aboutinfo-location"><i class="fa fa-location-arrow"></i>&nbsp;广东-广州</p>
                                <hr/>
                                <div class="aboutinfo-contact">
                                    <a target="_blank" title="QQ交流" href="javascript:layer.msg('启动QQ会话窗口')"><i
                                                class="fa fa-qq fa-2x"></i></a>
                                    <a target="_blank" title="给我写信" href="javascript:layer.msg('启动邮我窗口')"><i
                                                class="fa fa-envelope fa-2x"></i></a>
                                    <a target="_blank" title="新浪微博" href="javascript:layer.msg('转到你的微博主页')"><i
                                                class="fa fa-weibo fa-2x"></i></a>
                                    <a target="_blank" title="码云" href="javascript:layer.msg('转到你的github主页')"><i
                                                class="fa fa-git fa-2x"></i></a>
                                </div>
                                <fieldset class="layui-elem-field layui-field-title">
                                    <legend>简介</legend>
                                    <div class="layui-field-box aboutinfo-abstract abstract-bloger">
                                        <p style="text-align:center;">
                                            洛克哈特，云间阁创始人，诞生于1989年01月27日，目前是一个码农，从事PHP开发。</p>
                                        <h1>个人信息</h1>
                                        <p>暂无</p>
                                        <h1>个人介绍</h1>
                                        <p>一个没有故事的男人，没什么介绍......</p>
                                        <h1 style="text-align:center;">The End</h1>
                                    </div>
                                </fieldset>
                            </div>
                        </div><!--关于作者End-->
                        <div class="layui-tab-item">
                            <div class="aboutinfo">
                                <div class="aboutinfo-figure">
                                    <img src="{{asset(_HOME_ . '/images/handshake.png')}}" alt="友情链接"/>
                                </div>
                                <p class="aboutinfo-nickname">友情链接</p>
                                <p class="aboutinfo-introduce">Name：云间阁&nbsp;&nbsp;&nbsp;&nbsp;Site：www.nblog.com</p>
                                <p class="aboutinfo-location">
                                    <i class="fa fa-close"></i>经常宕机&nbsp;
                                    <i class="fa fa-close"></i>不合法规&nbsp;
                                    <i class="fa fa-close"></i>插边球站&nbsp;
                                    <i class="fa fa-close"></i>红标报毒&nbsp;
                                    <i class="fa fa-check"></i>原创优先&nbsp;
                                    <i class="fa fa-check"></i>技术优先
                                </p>
                                <hr/>
                                <div class="aboutinfo-contact">
                                    <p style="font-size:2em;">互换友链，携手并进！</p>
                                </div>
                                <fieldset class="layui-elem-field layui-field-title">
                                    <legend>Friend Link</legend>
                                    <div class="layui-field-box">
                                        <ul class="friendlink">
                                            <li>
                                                <a target="_blank" href="http://www.layui.com/" title="Layui"
                                                   class="friendlink-item">
                                                    <p class="friendlink-item-pic"><img
                                                                src="http://www.layui.com/favicon.ico" alt="Layui"/></p>
                                                    <p class="friendlink-item-title">Layui</p>
                                                    <p class="friendlink-item-domain">layui.com</p>
                                                </a>
                                            </li>
                                            <li>
                                                <a target="_blank" href="http://www.pagemark.cn/" title="页签"
                                                   class="friendlink-item">
                                                    <p class="friendlink-item-pic"><img
                                                                src="http://pm.lyblogs.cn/Images/logo-png.png"
                                                                alt="页签"/></p>
                                                    <p class="friendlink-item-title">页签</p>
                                                    <p class="friendlink-item-domain">pagemark.cn</p>
                                                </a>
                                            </li>
                                        </ul>
                                    </div>
                                </fieldset>
                            </div>
                        </div><!--友情链接End-->
                        <div class="layui-tab-item">
                            <div class="aboutinfo">
                                <div class="aboutinfo-figure">
                                    <img src="{{asset(_HOME_ . '/images/messagewall.png')}}" alt="留言墙"/>
                                </div>
                                <p class="aboutinfo-nickname">留言墙</p>
                                <p class="aboutinfo-introduce">本页面可留言、吐槽、提问。欢迎灌水，杜绝广告！</p>
                                <p class="aboutinfo-location">
                                    <i class="fa fa-clock-o"></i>&nbsp;<span id="time"></span>
                                </p>
                                <hr/>
                                <div class="aboutinfo-contact">
                                    <p style="font-size:2em;">沟通交流，拉近你我！</p>
                                </div>
                                <fieldset class="layui-elem-field layui-field-title">
                                    <legend>Leave a message</legend>
                                    <div class="layui-field-box">
                                        <div class="leavemessage" style="text-align:initial">
                                            <form class="layui-form blog-editor" action="">
                                                <div class="layui-form-item">
                                                    <textarea name="editorContent" lay-verify="content"
                                                              id="remarkEditor" placeholder="请输入内容"
                                                              class="layui-textarea layui-hide"></textarea>
                                                </div>
                                                <div class="layui-form-item">
                                                    <button class="layui-btn" lay-submit="formLeaveMessage"
                                                            lay-filter="formLeaveMessage">提交留言
                                                    </button>
                                                </div>
                                            </form>
                                            <ul class="blog-comment">

                                            </ul>
                                        </div>
                                    </div>
                                </fieldset>
                            </div>
                        </div><!--留言墙End-->
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('pageJs')
    <script src="{{asset(_ADMIN_ . '/static/editor/jquery.min.js')}}"></script>
    <script src="{{asset(_HOME_ . '/js/about.js')}}"></script>
    <script>
        $(".layui-nav li[class='layui-nav-item']").each(function () {
            ll($(this).find('a').text());
            // ll($(this).find('a').text() === target);
            if ($(this).find('a').text() == '关于本站') {
                $(this).addClass('layui-this');
            } else {
                $(this).removeClass('layui-this');
            }
        });



        // prettyPrint();
        layui.use(['form', 'layedit', 'tree'], function () {
            var form = layui.form();
            var $ = layui.jquery;
            var layedit = layui.layedit;
            var tree = layui.tree;

            // 显示评论
            {{--var imgSrc = "{{asset(_HOME_ . '/images/Absolutely.jpg')}}";--}}
            var imgSrc = "/storage/uploads/2020/06/logo.jpg";
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url: '/getComments/0',
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
            // form.on('submit(formRemark)', function (data) {
            form.on('submit(formLeaveMessage)', function (data) {
                var index = layer.load(1);

                ll('点击评论');
                var content = data.field.editorContent;
                var articleId = {{ $article['id'] }};
                var body = {'articleId': articleId, 'content': content};

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
            });
        });





    </script>
@endsection