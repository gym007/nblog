<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use sngrl\SphinxSearch\SphinxSearch;
use Elasticsearch\Common\Exceptions\MaxRetriesException;
use Elasticsearch\Common\Exceptions\TransportException;
use Elasticsearch\ClientBuilder;
// use Tag;

class Article extends Model
{
    use SoftDeletes;

    protected $table = 'articles';

    public function category()
    {
        // return $this->hasOne('App\Model\Category', 'id', 'cate_id');
        return $this->hasOne('App\Model\Category', 'id', 'cate_id');
    }

    public function tags()
    {
        return $this->belongsToMany('App\Model\Tag', 'articles_tags', 'article_id', 'tag_id' )
            ->withTimestamps()->using('App\Model\ArticleTag')->withPivot('id');
            // ->using('App\Model\ArticleTag');
    }

    // public function comments()
    // {
    //     return $this->hasMany('App\Model\Comments', 'article_id', 'bid');
    // }

    /**
     * 根据前端传的参数查询文章并返回列表
     * @param $query
     */
    public static function home($request)
    {
        $query = $request->all();
        $cate_id = isset($query['cate_id']) ? (int)$query['cate_id'] : 0;
        $keyword = isset($query['keyword']) ? (string)$query['keyword'] : '';
        $tagId = isset($query['tag']) ? (string)$query['tag'] : '';
        $page = isset($query['page']) ? (int)$query['page'] : 1;

        $returnData = [
            'articles' => [],
            'cate_id' => $cate_id,
            'keyword' => $keyword,
            'res' => [],
            'mark' => '',
            'showPage' => true, // 是否显示分页
        ];
        $finalCheck = true;

        if (!empty($cate_id)) {
            // dd(123);
            // 根据分类查询文章，将该分类下的所有文章全部检索出来
            $articles = self::select('id', 'cate_id', 'title', 'content', 'read_times', 'created_at', 'updated_at')
                ->where('cate_id', $cate_id)
                ->orderBy('created_at', 'desc')
                ->with('category:id,cate_title')
                ->paginate(8);
            $returnData['res'] = $articles->total() > 0 ? $articles : [];
            $returnData['mark'] = '分类';
            $finalCheck = $returnData['res'] ? false : true;
        }else if (!empty($tagId)) {
            $tag = Tag::find($tagId);
            $articleIds = [];
            foreach ($tag->articles as $article) {
                $articleIds[] = $article->id;
            }
            $articles = self::select('id', 'cate_id', 'title', 'content', 'read_times', 'created_at', 'updated_at')
                ->whereIn('id', $articleIds)
                ->orderBy('created_at', 'desc')
                ->with('category:id,cate_title')
                ->paginate(8);
            $returnData['res'] = $articles->total() > 0 ? $articles : [];
            $returnData['mark'] = '标签';
            $finalCheck = $returnData['res'] ? false : true;

        }  else if (!empty($keyword)) {
            try {
                $host = config('scout.elasticsearch.hosts');
                $index = config('scout.elasticsearch.index');

                $clientBuilder = ClientBuilder::create();
                $clientBuilder->setHosts($host);
                $clientBuilder->setRetries(2);

                $client = $clientBuilder->build();

                $params = [
                    'index' => $index,
                    'body'  => [
                        'query' => [
                            'bool' => [
                                'should' => [
                                    [ 'match' => [ 'article_title' => $keyword ]],
                                    [ 'match' => [ 'article_content' => $keyword]],
                                ]
                            ]
                        ],
                        'highlight' => [
                            'pre_tags' => "<span style='color: red'>",
                            'post_tags' => "</span>",
                            'fields' => [
                                'article_title' => new \stdClass(),
                                'article_content' => new \stdClass(),
                            ]
                        ],
                        'aggs' => [
                            'nums' => [
                                'terms' => [
                                    'size' => 1000,
                                    'field' => 'article_title',
                                ],
                            ],
                        ],
                        'size' => 1000,
                    ]
                ];

                $results = $client->search($params);


            } catch (Elasticsearch\Common\Exceptions\TransportException $e) {
                $previous = $e->getPrevious();
                if ($previous instanceof Elasticsearch\Common\Exceptions\MaxRetriesException) {
                    dump($e->getMessage());
                    dd($e->getMessage());
                }
            }

            // dump($keyword);
            //
            // dump($results);

            // 有数据就获取全部数据的ID
            if ($results['hits']['total']['value'] > 0) {
                $articles = [];
                foreach ($results['hits']['hits'] as $hit) {
                    $article = self::select('id', 'cate_id', 'title', 'content', 'read_times', 'created_at', 'updated_at')
                        ->where('id', $hit['_id'])
                        ->with('category:id,cate_title')
                        ->get();
                    $article = $article[0];
                    // dd($article[0]->id);
                    if (isset($hit['highlight']['article_title'])) {
                        $article->title = $hit['highlight']['article_title'][0];
                    }
                    if (isset($hit['highlight']['article_content'])) {
                        $article->content = $hit['highlight']['article_content'][0];
                    }
                    $articles[] = $article;
                }
                $returnData['res'] = $articles;
                $returnData['showPage'] = false;
            } else {
                $returnData['res'] = 0;
            }

            // $articles = self::select('id', 'cate_id', 'title', 'content', 'read_times', 'created_at', 'updated_at')
            //     ->where('title', 'like', '%' . $keyword . '%')
            //     ->orderBy('created_at', 'desc')
            //     ->with('category:id,cate_title')
            //     ->paginate(8);
            // $returnData['res'] = $articles->total() > 0 ? $articles : [];
            $returnData['mark'] = '关键词';
            $finalCheck = $returnData['res'] ? false : true;
        }

        if ($finalCheck) {
            // 单纯的而进入文章专栏(或者检索不到想要的数据)就直接把最近的文章拿出去
            $articles = self::select('id', 'cate_id', 'title', 'content', 'read_times', 'created_at', 'updated_at')
                ->orderBy('created_at', 'desc')
                ->with('category:id,cate_title')
                ->paginate(8);
            // $returnData['mark'] = '';
        }

        $returnData['articles'] = $articles;

        return $returnData;
    }

    /**
     * 根据传过来的id和标题查找相似文章
     * @param $title
     * @param $id
     * @return array
     */
    public static function likes($title, $id)
    {
        $others = self::select('id', 'title')->where('id', '<>', $id)->get();
        $likes = [];
        if ($others) {
            $others = $others->toArray();
            foreach ($others as $other)  {
                $res = similar_text($title, $other['title'], $percent);
                $percent = (int)$percent;
                if ($percent > 39) {
                    $likes[] = [
                        'id' => $other['id'],
                        'title' => $other['title'],
                    ];
                }
            }
        }
        return $likes;
    }
}
