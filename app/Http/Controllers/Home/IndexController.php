<?php

namespace App\Http\Controllers\Home;

// use Illuminate\Filesystem\Cache;
use Cache;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Model\{Article, Category, Comments, Links, Tag, ArticleTag};
use Illuminate\Support\Facades\Redis;
use Captcha;
use Validator;

class IndexController extends Controller
{
    public function __construct()
    {
        // 全部视图共享数据

        // 全部分类
        $categories = Category::treeForSelect(true);

        // 随便看看
        $rands = Article::inRandomOrder()->skip(0)->take(5)->get()->toArray();

        view()->share('categories', $categories);
        view()->share('rands', $rands);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        // try {
        //     $a = 'asdasd';
        //     $b = 50;
        //     echo $a * $b;
        // } catch (\Exception $e) {
        //     dump($e);
        //     dump($e->getMessage());
        // }

        $page = $request->input('page', 1);
        if ($page > 1) {
            $articles = Article::select('id', 'cate_id', 'title', 'content', 'read_times', 'created_at', 'updated_at')
                ->orderBy('created_at', 'desc')
                ->with('category:id,cate_title')
                ->paginate(8);
        } else {
            // 首页文章列表，列出最新的8篇文章
            $articles = Cache::remember('index_articles', 3 * 24 * 60, function () {
                return Article::select('id', 'cate_id', 'title', 'content', 'read_times', 'created_at', 'updated_at')
                    ->orderBy('created_at', 'desc')
                    ->with('category:id,cate_title')
                    ->paginate(8);
            });
        }

        // dd($articles);




        // 热文排行
        $hots = Cache::remember('hots', 3 * 24 * 60, function () {
            return Article::select('id', 'title')
                ->orderBy('read_times', 'desc')
                ->skip(0)
                ->take(8)
                ->get()
                ->toArray();
        });

        // 站点全部标签
        $tags = Cache::remember('tags', 3 * 24 * 60, function () {
            return Tag::select('id', 'name')
                ->orderBy('created_at', 'desc')
                ->get()
                ->toArray();
        });

        // 友链
        $links = Cache::remember('links', 3 * 24 * 60, function () {
            return Links::select('id', 'name', 'link')
                ->orderBy('created_at', 'desc')
                ->get()
                ->toArray();
        });

        return view('home.home', ['articles' => $articles, 'hots' => $hots, 'tags' => $tags, 'links' => $links]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $id = abs((int)($id));
        $article = Article::select('id', 'cate_id', 'title', 'content', 'read_times', 'created_at', 'updated_at')
            // ->with('comments:id,user_id,content')
            ->with('category:id,cate_title')
            ->find($id);

        if ($article) {
            // 文章存在，将文章阅读数+1
            Article::find($id)->increment('read_times', 1, ['updated_at' => $article->updated_at]);
            $article->read_times++;

            // 文章对应的标签
            $tags = $article->tags->toArray();

            // 根据标题搜索相似文章
            $title = $article->title;
            $likes = Article::likes($title, $id);

            // 文章本体
            $article = $article->toArray();

            // 验证码
            $img = Captcha::src('mini');

            return view('home.detail', ['tags' => $tags, 'article' => $article, 'likes' => $likes, 'comments' => [], 'img' => $img]);
        } else {
            return back()->withErrors('文章不存在，请刷新页面重试~');
        }
    }

    // 文章列表页， 有分类查分类， 没分类查标签，没标签查搜索， 什么都没有就默认展示
    public function article(Request $request)
    {

        $returnData = Article::home($request);
        return view('home.article', [
            'articles' => $returnData['articles'],
            'keyword' => $returnData['keyword'],
            'res' => $returnData['res'],
            'mark' => $returnData['mark'],
            'showPage' => $returnData['showPage'],
        ]);
    }

    public function about()
    {
        return view('home.about', ['article' => ['id' => 0]]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    public function comments(Request $request)
    {
        $articleId = (int)$request->input('articleId', 0);
        $content = (string)$request->input('content', '');
        $userId = 0;
        $time = time();
        $returnData = [
            'code' => 300,
            'text' => '留言失败，请稍后重试',
            'data' => [
                'userId' => 0,
                'userName' => '',
                'time' => 0,
                'content' => '',
            ],
        ];

        // 验证码判断
        $rules = ['captcha'  => 'required|captcha'];
        $message = [
            'captcha.required' => '验证码不能为空',
            'captcha.captcha' => '验证码错误',
        ];

        $validator = Validator::make($request->all(), $rules, $message);
        $errors = $validator->errors();

        if ($errors = $errors->get('captcha')) {
            $msg = '';
            foreach ($errors as $error) {
                $msg = $error;
                break;
            }
            $returnData['text'] = $msg;
            return response()->json($returnData);

        }

        if ($articleId) {
            if (!$article = Article::find($articleId)) {
                $returnData['text'] = '文章不存在, 请刷新页面重试';
                return response()->json($returnData);
            }
        }

        if (empty($content)) {
            $returnData['text'] = '评论内容不能为空';
            return response()->json($returnData);
        }

        if ($user = session('user', '')) {
            // 已登录
            $user = json_decode($user, true);
            $returnData['data']['userId'] = $user['id'];
            $returnData['data']['userName'] = $user['name'];

            $key = 'comments_history_' . $user['id'];
            if (Redis::exists($key)) {
                if ($last5 = Redis::lindex($key, -5)) {
                    if ($time - $last5 < 17200) {
                        $returnData['text'] = '评论过于频繁，请稍后在评论';
                    } else {
                        Redis::lpush($key, $time);
                    }
                }

            } else {
                Redis::lpush($key, $time);
            }

            while(Redis::llen($key) > 5) {
                // 删除多余的评论历史
                Redis::rpop($key);
            }


        } else {
            // 游客
            // 先检测该IP的游客最近留言次数
            $clientIp = $request->getClientIp();
            $key = 'comments_history_' . $clientIp;
            if (Redis::exists($key)) {
                if ($last5 = Redis::lindex($key, 4)) {
                    if ($time - $last5 < 7200) {
                        $returnData['text'] = '评论过于频繁，请勿灌水';
                        return response()->json($returnData);
                    } else {
                        Redis::lpush($key, $time);
                    }
                } else {
                    Redis::lpush($key, $time);
                }
            } else {
                Redis::lpush($key, $time);
            }

            while(Redis::llen($key) > 5) {
                // 删除多余的评论历史
                Redis::rpop($key);
            }

            $comment = new Comments;
            $comment->user_id = 0;
            $comment->article_id = $articleId;
            $comment->content = $content;
            if (!$res = $comment->save()) {
                $returnData['text'] = '评论失败， 请稍后重试~~';
                return response()->json($returnData);
            } else {
                $clientIp = str_replace('.', '', $clientIp);
                $returnData = [
                    'code' => 200,
                    'text' => 'OK',
                    'data' => [
                        'userId' => 0,
                        'userName' => '游客' . $clientIp,
                        'time' => date('Y-m-d H:i:s', $time),
                        'content' => $content,
                    ],
                ];
            }
        }

        return response()->json($returnData);
    }

    public function getComments(Request $request, $id)
    {
        // 文章对应的评论
        $comments = Comments::select('id', 'user_id', 'content', 'created_at')
            ->where('article_id', $id)
            ->orderBy('created_at', 'desc')
            ->get()
            ->toArray();

        if ($comments) {
            foreach ($comments as &$comment) {
                if (!$comment['user_id']) {
                    $comment['userName'] = '游客';
                } else {
                    $comment['userName'] = '用户';
                }
            }
        }
        $returnData = config('json.simple');
        $returnData['data'] = $comments;
        return response()->json($returnData);
    }

    public function download(Request $request, $file_name)
    {
        return response()->download(realpath(base_path('public')) . '/static/download/' . $file_name);
    }
}
