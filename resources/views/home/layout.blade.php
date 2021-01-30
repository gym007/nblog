<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; Charset=gb2312">
    <meta http-equiv="Content-Language" content="zh-CN">
    <meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=no" />
    <meta name="csrf-token" content="{{ csrf_token() }}"/>
    <title>云间阁 - 一个PHP程序员的个人博客网站</title>
    <link rel="shortcut icon" href="{{asset(_HOME_ . '/images/Logo_40.png')}}" type="image/x-icon">
    <!--Layui-->
    <link href="{{asset(_HOME_ . '/plug/layui/css/layui.css')}}" rel="stylesheet" />
    <!--font-awesome-->
    <link href="{{asset(_HOME_ . '/plug/font-awesome/css/font-awesome.min.css')}}" rel="stylesheet" />
    <!--全局样式表-->
    <link href="{{asset(_HOME_ . '/css/global.css')}}" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdn.bootcss.com/bootstrap/3.3.4/css/bootstrap.min.css">


    <!-- 本页样式文件 -->
    @section('pageCss')
    @show

</head>
<body>
<!-- 导航 -->
<nav class="blog-nav layui-header">
    <div class="blog-container">
        <!-- QQ互联登陆 -->
        <a href="javascript:;" class="blog-user">
            <i class="fa fa-qq"></i>
        </a>
        <a href="javascript:;" class="blog-user layui-hide">
            <img src="{{asset(_HOME_ . '/images/Absolutely.jpg')}}" alt="Absolutely" title="Absolutely" />
        </a>
        <!-- 云间 -->
        <a class="blog-logo" href="/">云间阁</a>
        <!-- 导航菜单 -->
        <ul class="layui-nav" lay-filter="nav">
            <li class="layui-nav-item">
                <a href="/"><i class="fa fa-home fa-fw"></i>网站首页</a>
            </li>
            <li class="layui-nav-item">
                <a href="/article"><i class="fa fa-file-text fa-fw"></i>文章专栏</a>
            </li>
            <li class="layui-nav-item" style="display: none">
                <a href="resource.html"><i class="fa fa-tags fa-fw"></i>资源分享</a>
            </li>
            <li class="layui-nav-item" style="display: none">
                <a href="timeline.html"><i class="fa fa-hourglass-half fa-fw"></i>点点滴滴</a>
            </li>
            <li class="layui-nav-item">
                <a href="/about"><i class="fa fa-info fa-fw"></i>关于本站</a>
            </li>
        </ul>
        <!-- 手机和平板的导航开关 -->
        <a class="blog-navicon" href="javascript:;">
            <i class="fa fa-navicon"></i>
        </a>
    </div>
</nav>

<!-- 主体（一般只改变这里的内容） -->
@section('body')
@show

<!-- 底部 -->
<footer class="blog-footer">
    <p><span>Copyright</span><span>&copy;</span><span>2017</span><a href="http://www.lyblogs.cn">云间阁</a><span>Design By LY</span></p>
    <p><a href="http://www.miibeian.gov.cn/" target="_blank">蜀ICP备16029915号-1</a></p>
</footer>
<!--侧边导航-->
<ul class="layui-nav layui-nav-tree layui-nav-side blog-nav-left layui-hide" lay-filter="nav">
    <li class="layui-nav-item layui-this">
        <a href="/"><i class="fa fa-home fa-fw"></i>&nbsp;网站首页</a>
    </li>
    <li class="layui-nav-item">
        <a href="/article"><i class="fa fa-file-text fa-fw"></i>&nbsp;文章专栏</a>
    </li>
    <li class="layui-nav-item" style="display: none">
        <a href="resource.html"><i class="fa fa-tags fa-fw"></i>&nbsp;资源分享</a>
    </li>
    <li class="layui-nav-item" style="display: none">
        <a href="timeline.html"><i class="fa fa-road fa-fw"></i>&nbsp;点点滴滴</a>
    </li>
    <li class="layui-nav-item">
        <a href="/about"><i class="fa fa-info fa-fw"></i>&nbsp;关于本站</a>
    </li>
</ul>
<!--分享窗体-->
<div class="blog-share layui-hide">
    <div class="blog-share-body">
        <div style="width: 200px;height:100%;">
            <div class="bdsharebuttonbox">
                <a class="bds_qzone" data-cmd="qzone" title="分享到QQ空间"></a>
                <a class="bds_tsina" data-cmd="tsina" title="分享到新浪微博"></a>
                <a class="bds_weixin" data-cmd="weixin" title="分享到微信"></a>
                <a class="bds_sqq" data-cmd="sqq" title="分享到QQ好友"></a>
            </div>
        </div>
    </div>
</div>
<!--遮罩-->
<div class="blog-mask animated layui-hide"></div>
<!-- layui.js -->
<script src="{{asset(_HOME_ . '/plug/layui/layui.js')}}"></script>
<!-- 全局脚本 -->
<script src="{{asset(_HOME_ . '/js/global.js')}}"></script>
<script src="{{asset(_HOME_ . '/robot/robot.js?022107')}}"></script>

<!-- 本页脚本 -->
@section('pageJs')
@show
</body>
</html>