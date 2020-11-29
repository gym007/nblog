<?php

namespace App\Http\Controllers\Admin;

use App\Model\{Article, Category, Tag, ArticleTag};
use App\Jobs\sendMail;
use App\Jobs\UpdateElasticSearch;
use Elasticsearch\ClientBuilder;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
// use Illuminate\Support\Facades\Cache;
use Cache;
use DB;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;

class ArticleController extends Controller
{
    protected  $data = [
        'type' => 'add',
        'id' => 0,
        'title' => '',
        'content' => '',
        'created_at' => '',
    ];

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('admin.article.index');
    }

    /**
     * 按分页获取文章
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function allList(Request $request)
    {
        $page = $request->input('page', 1);
        $limit = $request->input('limit', 10);
        $articles = Article::select('id', 'cate_id', 'title', 'content', 'read_times', 'created_at', 'updated_at')->orderBy('created_at', 'desc')->skip(($page - 1) * $limit)->take($limit)->with('category:id,cate_title')->get()->toArray();
        $data = [
            'code' => 0,
            'msg' => 'OK',
            'count' => Article::count(),
            'data' => $articles,
        ];
        return response()->json($data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $tags = Tag::select('id', 'name')->get()->toArray();
        return view('admin.article.create', ['tags' => $tags]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $title = $request->input('title', '');
        $cate_id = $request->input('cate_id', '');
        $content = $request->input('test-editormd-markdown-doc', '');
        $tags = $request->input('tags', '');
        if (empty($cate_id)) {
            $cate_id = Category::first()->id;
        }
        $data = [
            'title' => $title,
            'cate_id' => $cate_id,
            'content' => $content,
        ];
        $responseData = [
            'code' => config('json.code.fail'),
            'text' => config('json.text.fail'),
        ];
        if (empty($title) || empty($cate_id) || empty($content) || empty($tags)) {
            return response()->json($responseData);
        } else {
            $article = new Article;
            $article->title = $data['title'];
            $article->cate_id = $data['cate_id'];
            $article->content = $data['content'];
            if ($res = $article->save()) {
                $this->data = [
                    'type' => 'add',
                    'id' => $article->id,
                    'title' => $article->title,
                    'content' => $article->content,
                    'created_at' => date('Y-m-d H:i:s', strtotime($article->created_at)) // 再次转义, 省去了时间相差8小时且格式不对的问题
                ];




                // 异步更新elasticsearch的数据
                // $index = config('scout.elasticsearch.index');
                // $params = [
                //     'index' => $index,
                //     'id' => $this->data['id'],
                //     'body' => [
                //         'article_id' => $this->data['id'],
                //         'article_title' => $this->data['title'],
                //         'article_content' => $this->data['content'],
                //         'article_created_at' => $this->data['created_at'],
                //     ],
                // ];
                //
                // try {
                //     $host = config('scout.elasticsearch.hosts');
                //
                //
                //     $clientBuilder = ClientBuilder::create();
                //     $clientBuilder->setHosts($host);
                //     $clientBuilder->setRetries(2);
                //
                //     $elasticClient = $clientBuilder->build();
                //     $responses = $elasticClient->index($params);
                //     dd($responses);
                // } catch (Elasticsearch\Common\Exceptions\TransportException $e) {
                //     $previous = $e->getPrevious();
                //     if ($previous instanceof Elasticsearch\Common\Exceptions\MaxRetriesException) {
                //         echo "Max retries!";
                //     }
                // }





                UpdateElasticSearch::dispatch($this->data);

                $this->refreshArticleCache();
                // 根据传回的标签获取标签id并校验是否新添加了标签
                $tagIds = Tag::createArticle($tags);
                if (!empty($tagIds)) {
                    $article->tags()->attach($tagIds); // 给文章添加标签
                }
                $responseData = config('json.simple');
            }
            return response()->json($responseData);
        }

    }

    /**
     * Display the specified resource.
     *
     * @param \App\Model\Article $article
     * @return \Illuminate\Http\Response
     */
    public function show(Article $article)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param \App\Model\Article $article
     * @return \Illuminate\Http\Response
     */
    public function edit(Article $article)
    {
        if ($article) {
            // 获取文章对应的多个标签
            $tagStr = '';
            if ($articleTags = $article->tags->toArray()) {
                foreach ($articleTags as $articleTag) {
                    $tagStr .= $articleTag['name'] . ',';
                }
                $tagStr = trim($tagStr, ',');
            }

            // 获取所有的标签
            $tags = Tag::select('id', 'name')->get()->toArray();

            $article = $article->toArray();
            return view('admin.article.edit', ['article' => $article, 'tagStr' => $tagStr, 'tags' => $tags]);
        } else {
            return back();
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\Model\Article $article
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Article $article)
    {
        $title = $request->input('title', '');
        $cate_id = $request->input('cate_id', '');
        $content = $request->input('test-editormd-markdown-doc', '');
        $tags = $request->input('tags', '');

        if (empty($cate_id)) {
            $cate_id = Category::first()->id;
        }

        $responseData = config('json.simple');
        if ($article && !empty($title) && !empty($cate_id) && !empty($content) && !empty($tags)) {
            // 检查标签是否有改动
            $oldTagIds = [];
            // 先删除旧标签
            if ($oldTags = $article->tags) {
                $articleId = $article->id;
                $oldTagsArr = $oldTags->toArray();
                // dd($oldTagsArr);
                foreach ($oldTagsArr as $value) {
                    $oldTagIds[] = $value['id'];
                }
                ArticleTag::where('article_id', $articleId)->whereIn('tag_id', $oldTagIds)->forceDelete();
            }

            $article->title = $title;
            $article->cate_id = $cate_id;
            $article->content = $content;
            if ($save = $article->save()) {
                $newTagIds = Tag::createArticle($tags);
                if (!empty($newTagIds)) {
                    $article->tags()->attach($newTagIds); // 给文章添加标签
                }

                $this->data = [
                    'type' => 'update',
                    'id' => $article->id,
                    'title' => $article->title,
                    'content' => $article->content,
                    'created_at' => date('Y-m-d H:i:s', strtotime($article->created_at)) // 再次转义, 省去了时间相差8小时且格式不对的问题
                ];

                // 异步更新elasticsearch的数据
                UpdateElasticSearch::dispatch($this->data);

                // 更新首页文章列表
                $this->refreshArticleCache();

                // 延时发送邮件
                $data = [
                    'send_id' => $article['id'],
                    'accept_id' => 15,
                    'content' => $article->title,
                ];
                sendMail::dispatch($data);
                // sendMail::dispatch($data)->delay(Carbon::now()->addMinutes(1));

                return response()->json($responseData);
            } else {
                $responseData['code'] = config('json.code.fail');
                $responseData['text'] = config('json.text.fail');
            }
        } else {
            $responseData['code'] = config('json.code.fail');
            $responseData['text'] = config('json.text.fail');
        }
        return response()->json($responseData);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \App\Model\Article $article
     * @return \Illuminate\Http\Response
     */
    public function destroy(Article $article)
    {
        if ($res = $article->delete()) {
            $this->data = [
                'type' => 'delete',
                'id' => $article->id,
                'title' => '',
                'content' => '',
                'created_at' => '',
            ];

            // 异步更新elasticsearch的数据
            UpdateElasticSearch::dispatch($this->data);

            // 删除中间表
            // $article->tags()->delete();
            $responseData = config('json.simple');
        } else {
            $responseData = [
                'code' => config('json.code.fail'),
                'text' => config('json.text.fail')
            ];
        }
        return response()->json($responseData);
    }

    /**
     * 处理编辑器上传的图片
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function photo(Request $request)
    {
        $data = [
            'message' => '上传失败',
            'success' => 0,
        ];
        $file = $request->file('editormd-image-file');
        if ($request->hasFile('editormd-image-file')) {
            if ($file->isValid()) {
                $time = date('Y/m');
                // 获取文件相关信息
                $originalName = $file->getClientOriginalName(); // 文件原名
                $ext = $file->getClientOriginalExtension();     // 扩展名
                $realPath = $file->getRealPath();   //临时文件的绝对路径
                $type = $file->getClientMimeType();     // image/jpeg
                // 上传文件
                $filename = uniqid() . '.' . $ext;
                // $data['pic_path'] = '/storage/'.$time.'/'.$filename;
                $path = '/' . $time . '/' . $filename;

                // 软连接的storage <==> storage/app/public
                if ($bool = Storage::disk('uploads')->put($path, file_get_contents($realPath))) {
                    $data = [
                        'url' => asset('storage/uploads' . $path),
                        'message' => 'OK',
                        'success' => 1,
                    ];
                }

            }
        }
        return response()->json($data);
    }

    // 刷新首页文章
    public function refreshArticleCache()
    {
        // 首页文章列表，列出最新的8篇文章
        // $articles = Article::select('id', 'cate_id', 'title', 'content', 'read_times', 'created_at', 'updated_at')
        //     ->orderBy('created_at', 'desc')
        //     ->with('category:id,cate_title')
        //     ->paginate(8);
        // $articles->withPath = 'http://www.nblog.com:99';
        //
        // // dd($articles);
        // Cache::put('index_articles', $articles, 3 * 24 * 60);

        // 热文排行
        $hots = Article::select('id', 'title')
            ->orderBy('read_times', 'desc')
            ->skip(0)
            ->take(8)
            ->get()
            ->toArray();
        Cache::put('hots', $hots, 3 * 24 * 60);
    }

    public function updateElastic()
    {

    }
}
