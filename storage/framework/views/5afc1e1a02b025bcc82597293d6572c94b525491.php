

<?php $__env->startSection('pageCss'); ?>
    <link href="<?php echo e(asset(_HOME_ . '/css/home.css')); ?>" rel="stylesheet"/>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('body'); ?>
    <!-- 主体（一般只改变这里的内容） -->
    <div class="blog-body">
        <!-- canvas -->
        <canvas id="canvas-banner" style="background: #393D49;"></canvas>
        <!--为了及时效果需要立即设置canvas宽高，否则就在home.js中设置-->
        <script type="text/javascript">
            var canvas = document.getElementById('canvas-banner');
            canvas.width = window.document.body.clientWidth - 10;//减去滚动条的宽度
            if (screen.width >= 992) {
                canvas.height = window.innerHeight * 1 / 3;
            } else {
                canvas.height = window.innerHeight * 2 / 7;
            }
        </script>
        <!-- 这个一般才是真正的主体内容 -->
        <div class="blog-container">
            <div class="blog-main">
                <!-- 网站公告提示 -->
                <div class="home-tips shadow">
                    <i style="float:left;line-height:17px;" class="fa fa-volume-up"></i>
                    <div class="home-tips-container">
                        <span style="color: #009688">由于服务器配置低,  安装了elasticsearch后占用内存较大, 搜索功能偶尔会挂掉, 功能完善中..</span>
                        <span style="color: #009688">客服功能使用的swoole, 完善中</span>
                        <span style="color: #009688">偷偷告诉大家，本博客的后台管理也正在制作，后续为大家准备游客专用账号！</span>




                        <span style="color: #009688">云间阁 &nbsp;—— &nbsp;一个PHP程序员的个人博客，新版网站采用Layui为前端框架，以laravel为后端，目前正在建设中！</span>
                    </div>
                </div>
                <!--左边文章列表-->
                <div class="blog-main-left">
                    <!-- 循环展示文章 -->
                    <?php echo $__env->make('home.error', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>
                    <?php $__currentLoopData = $articles; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $article): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <div class="article shadow">
                            <div class="article-left">
                                <img src="<?php echo e(asset(_HOME_ . '/images/cover/201703181909057125.jpg')); ?>"
                                     alt="<?php echo e($article->title); ?>"/>
                            </div>
                            <div class="article-right">
                                <div class="article-title">
                                    <a href="/detail/<?php echo e($article->id); ?>"><strong><?php echo e($article->title); ?></strong></a>
                                </div>
                                <div class="article-abstract"
                                     style="overflow: hidden; display: -webkit-box; -webkit-box-orient:vertical; -webkit-line-clamp:2">
                                    <?php echo e($article->content); ?>

                                </div>
                            </div>
                            <div class="clear"></div>
                            <div class="article-footer">
                                <span><i class="fa fa-clock-o"></i>&nbsp;&nbsp;<?php echo e($article->created_at); ?></span>
                                <span class="article-author"><i class="fa fa-user"></i>&nbsp;&nbsp;洛克哈特</span>
                                <span><i class="fa fa-tag"></i>&nbsp;&nbsp;<a
                                            href="#"><?php echo e($article->category->cate_title); ?></a></span>
                                <span class="article-viewinfo"><i class="fa fa-eye"></i>&nbsp;<?php echo e($article->read_times); ?></span>
                                <span class="article-viewinfo"><i class="fa fa-commenting"></i>&nbsp;0</span>
                            </div>
                        </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    <?php echo e($articles->links()); ?>

                </div>
                <!--右边小栏目-->
                <div class="blog-main-right">
                    <div class="blogerinfo shadow">
                        <div class="blogerinfo-figure">
                            <img src="/storage/uploads/2020/06/logo.jpg" width="20%" height="15%" alt="云间阁" alt="Absolutely"/>
                        </div>
                        <p class="blogerinfo-nickname">洛克哈特</p>
                        <p class="blogerinfo-introduce">一枚PHP开发工程师</p>
                        <p class="blogerinfo-location"><i class="fa fa-location-arrow"></i>&nbsp;广东 - 广州</p>
                        <hr/>
                        <div class="blogerinfo-contact" style="display: none">
                            <a target="_blank" title="QQ交流" href="javascript:layer.msg('启动QQ会话窗口')"><i
                                        class="fa fa-qq fa-2x"></i></a>
                            <a target="_blank" title="给我写信" href="javascript:layer.msg('启动邮我窗口')"><i
                                        class="fa fa-envelope fa-2x"></i></a>
                            <a target="_blank" title="新浪微博" href="javascript:layer.msg('转到你的微博主页')"><i
                                        class="fa fa-weibo fa-2x"></i></a>
                            <a target="_blank" title="码云" href="javascript:layer.msg('转到你的github主页')"><i
                                        class="fa fa-git fa-2x"></i></a>
                        </div>
                    </div>
                    <div></div><!--占位-->
                    <div class="blog-module shadow">
                        <div class="blog-module-title">热文排行</div>
                        <ul class="fa-ul blog-module-ul">
                            <?php $__currentLoopData = $hots; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $hot): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <li><i class="fa-li fa fa-hand-o-right"></i><a
                                            href="/detail/<?php echo e($hot['id']); ?>"><?php echo e($hot['title']); ?></a></li>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </ul>
                    </div>
                    <div class="blog-module shadow" style="display: none">
                        <div class="blog-module-title">最近分享</div>
                        <ul class="fa-ul blog-module-ul">
                            <li><i class="fa-li fa fa-hand-o-right"></i><a href="http://pan.baidu.com/s/1c1BJ6Qc"
                                                                           target="_blank">Canvas</a></li>
                            <li><i class="fa-li fa fa-hand-o-right"></i><a href="http://pan.baidu.com/s/1kVK8UhT"
                                                                           target="_blank">pagesize.js</a></li>
                            <li><i class="fa-li fa fa-hand-o-right"></i><a href="https://pan.baidu.com/s/1mit2aiW"
                                                                           target="_blank">时光轴</a></li>
                            <li><i class="fa-li fa fa-hand-o-right"></i><a href="https://pan.baidu.com/s/1nuAKF81"
                                                                           target="_blank">图片轮播</a></li>
                        </ul>
                    </div>
                    <div class="blog-module shadow">
                        <div class="blog-module-title">全站标签</div>
                        <?php $__currentLoopData = $tags; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $tag): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <a href="javascript:;"
                               onclick="showTag('<?php echo e($tag['id']); ?>', '<?php echo e($tag['name']); ?>')"><?php echo e($tag['name']); ?></a>&nbsp;
                            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        <div class="clear"></div>


                    </div>
                    <div class="blog-module shadow" style="display: none">
                        <div class="blog-module-title">后台记录</div>
                        <dl class="footprint">
                            <dt>2017年03月16日</dt>
                            <dd>分页新增页容量控制</dd>
                            <dt>2017年03月12日</dt>
                            <dd>新增管家提醒功能</dd>
                            <dt>2017年03月10日</dt>
                            <dd>新增Win10快捷菜单</dd>
                        </dl>
                    </div>
                    <div class="blog-module shadow">
                        <div class="blog-module-title">友情链接</div>
                        <ul class="blogroll">
                            <?php $__currentLoopData = $links; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $link): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <li><a target="_blank" href="<?php echo e($link['link']); ?>" title="页签"><?php echo e($link['name']); ?></a></li>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </ul>
                    </div>
                </div>
                <div class="clear"></div>
            </div>
        </div>
    </div>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('pageJs'); ?>
    <!-- 本页脚本 -->
    <script src="<?php echo e(asset(_HOME_ . '/js/home.js')); ?>"></script>
    <!-- 本页脚本 -->
    <script src="<?php echo e(asset(_ADMIN_ . '/static/editor/jquery.min.js')); ?>"></script>
    <script>
        // var target = $('.target').html();
        // ll(target);
        $(".layui-nav li[class='layui-nav-item']").each(function () {
            // ll($(this).find('a').text());
            // ll($(this).find('a').text() === target);
            if ($(this).find('a').text() == '网站首页') {
                $(this).addClass('layui-this');
            } else {
                $(this).removeClass('layui-this');
            }
        });

        function showTag(id, name) {
            layer.msg(
                "&#39;切换到标签" + name + "&#39;",
                {time: 1000},
                function (index) {
                    location.href = '/article?tag=' + id;
                }
            )
        }
    </script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('home.layout', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>