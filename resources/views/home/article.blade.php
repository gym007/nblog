@extends('home.layout')

@section('pageCss')
    <link href="{{asset(_HOME_ . '/css/article.css')}}" rel="stylesheet"/>
@endsection

@section('body')
    <!-- 主体（一般只改变这里的内容） -->
    <div class="blog-body">
        <div class="blog-container">
            <blockquote class="layui-elem-quote sitemap layui-breadcrumb shadow">
                <a href="home.html" title="网站首页">网站首页</a>
                <a><cite class="target">文章专栏</cite></a>
            </blockquote>
            <div class="blog-main">
                <div class="blog-main-left">
                    @if (empty($res) && !empty($mark))
                        <div class="shadow"
                             style="text-align:center;font-size:16px;padding:40px 15px;background:#fff;margin-bottom:15px;">
                            没有相关{{ $mark }}的文章，随便看看吧！
                        </div>
                    @endif

                    @foreach($articles as $article)
                        <div class="article shadow">
                            <div class="article-left">
                                <img src="{{asset(_HOME_ . '/images/cover/201703181909057125.jpg')}}"
                                     alt="
                                     @if (!$showPage)
                                        {!! $article->title !!}
                                     @else
                                         {{ $article->title }}
                                     @endif
                                      "/>
                            </div>
                            <div class="article-right">
                                <div class="article-title">
                                    <a href="/detail/{{ $article->id }}"><strong>
                                            @if (!$showPage)
                                                {!! $article->title !!}
                                            @else
                                                {{ $article->title }}
                                            @endif
                                        </strong></a>
                                </div>
                                <div class="article-abstract"
                                     style="overflow: hidden; display: -webkit-box; -webkit-box-orient:vertical; -webkit-line-clamp:2">
                                    @if (!$showPage)
                                        {!! $article->content !!}
                                    @else
                                        {{ $article->content }}
                                    @endif
                                </div>
                            </div>
                            <div class="clear"></div>
                            <div class="article-footer">
                                <span><i class="fa fa-clock-o"></i>&nbsp;&nbsp;{{ $article->created_at }}</span>
                                <span class="article-author"><i class="fa fa-user"></i>&nbsp;&nbsp;洛克哈特</span>
                                <span><i class="fa fa-tag"></i>&nbsp;&nbsp;<a
                                            href="#">{{ $article->category->cate_title }}</a></span>
                                <span class="article-viewinfo"><i class="fa fa-eye"></i>&nbsp;{{ $article->read_times }}</span>
                                <span class="article-viewinfo"><i class="fa fa-commenting"></i>&nbsp;0</span>
                            </div>
                        </div>
                    @endforeach
                    @if ($showPage)
                    {{ $articles->links() }}
                    @endif

                </div>
                <div class="blog-main-right">
                    <div class="blog-search">
                        <form class="layui-form" action="">
                            <div class="layui-form-item">
                                <div class="search-keywords  shadow">
                                    <input type="text" name="keyword" lay-verify="required" placeholder="输入关键词, 高亮显示结果"
                                           autocomplete="off" class="layui-input">
                                </div>
                                <div class="search-submit  shadow">
                                    <a class="search-btn" lay-submit="formSearch" lay-filter="formSearch"><i
                                                class="fa fa-search"></i></a>
                                </div>
                            </div>
                        </form>
                    </div>
                    <div class="article-category shadow">
                        <div class="article-category-title">分类导航</div>
                        <div id="cateTree" class="demo-tree"></div>
                        <div class="clear"></div>
                    </div>
                    <div class="blog-module shadow" style="display: none">
                        <div class="blog-module-title">作者推荐</div>
                        <ul class="fa-ul blog-module-ul">
                            <li><i class="fa-li fa fa-hand-o-right"></i><a href="detail.html">Web安全之跨站请求伪造CSRF</a></li>
                            <li><i class="fa-li fa fa-hand-o-right"></i><a href="detail.html">ASP.NET MVC
                                    防范跨站请求伪造（CSRF）</a></li>
                            <li><i class="fa-li fa fa-hand-o-right"></i><a href="detail.html">C#基础知识回顾-扩展方法</a></li>
                            <li><i class="fa-li fa fa-hand-o-right"></i><a href="detail.html">一步步制作时光轴（一）（HTML篇）</a>
                            </li>
                            <li><i class="fa-li fa fa-hand-o-right"></i><a href="detail.html">一步步制作时光轴（二）（CSS篇）</a></li>
                            <li><i class="fa-li fa fa-hand-o-right"></i><a href="detail.html">一步步制作时光轴（三）（JS篇）</a></li>
                            <li><i class="fa-li fa fa-hand-o-right"></i><a href="detail.html">写了个Win10风格快捷菜单！</a></li>
                            <li><i class="fa-li fa fa-hand-o-right"></i><a href="detail.html">ASP.NET MVC自定义错误页</a></li>
                            <li><i class="fa-li fa fa-hand-o-right"></i><a href="detail.html">ASP.NET
                                    MVC制作404跳转（非302和200）</a></li>
                            <li><i class="fa-li fa fa-hand-o-right"></i><a href="detail.html">基于laypage的layui扩展模块（pagesize.js）！</a>
                            </li>
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
                    <!--右边悬浮 平板或手机设备显示-->
                    <div class="category-toggle"><i class="fa fa-chevron-left"></i></div>
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

    <script type="text/javascript">
        // ll(123);
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

            form.on('submit(formSearch)', function (data) {
                var index = layer.load(0);
                var keyword = data.field.keyword;
                location.href = '/article?keyword=' + keyword;
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
    </script>
@endsection
