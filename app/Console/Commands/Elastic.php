<?php

namespace App\Console\Commands;

use Elasticsearch\Common\Exceptions\MaxRetriesException;
use Elasticsearch\Common\Exceptions\TransportException;
use Illuminate\Console\Command;

use App\Model\Article;

class Elastic extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'elastic';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'ElasticSearch Init...';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $this->info('init success');
        // //创建template
        // $client = new Client(); //这里的Clinet()是你vendor下的GuzzleHttp下的Client文件
        // $url = config('scout.elasticsearch.hosts')[0].'/inssa';   //这里写logstash配置中index参数
        // $client->delete($url);//确定没有这个url
        //
        // /*
        //  * 这个模板作用于我要做用的索引
        //  * */
        // $param = [
        //     'json'=>[
        //         /*
        //          * 这句是取在scout.php（scout是驱动）里我们配置好elasticsearch引擎的
        //          * index项。
        //          * PS：其实都是取数组项，scout本身就是return一个数组，
        //          * scout.elasticsearch.index就是取
        //          * scout[elasticsearch][index]
        //          * */
        //         'template'=>config('scout.elasticsearch.index'),
        //         'mappings'=>[
        //             '_default_'=>[
        //                 'dynamic_templates'=>[
        //                     [
        //                         'string'=>[
        //                             'match_mapping_type'=>'string',//传进来的是string
        //                             'mapping'=>[
        //                                 'type'=>'text',//把传进来的string按text（文本）处理
        //                                 'analyzer'=>'ik_smart',//用ik_smart进行解析（ik是专门解析中的插件）
        //                                 'fields'=>[
        //                                     'keyword'=>[
        //                                         'type'=>'keyword'
        //                                     ]
        //                                 ]
        //                             ]
        //                         ]
        //                     ]
        //                 ]
        //             ]
        //         ],
        //     ],
        // ];
        // $client->put($url,$param);
        //
        // $this->info('============create template success============');
        //
        // //创建index
        // $url = config('scout.elasticsearch.hosts')[0].'/'.config('scout.elasticsearch.index');
        // //$client->delete($url);
        //
        // $param = [
        //     'json'=>[
        //         'settings'=>[
        //             'refresh_interval'=>'5s',
        //             'number_of_shards'=>1,
        //             'number_of_replicas'=>0,
        //         ],
        //
        //         'mappings'=>[
        //             '_default_'=>[
        //                 '_all'=>[
        //                     'enabled'=>false
        //                 ]
        //             ]
        //         ]
        //     ]
        // ];
        //
        // $client->put($url,$param);
        // $this->info('============create index success============');

        // $article = Article::find(1);

        // require_once public_path() . '/../vendor/autoload.php';

        $host = config('scout.elasticsearch.hosts');
        $index = config('scout.elasticsearch.index');

        $clientBuilder = \Elasticsearch\ClientBuilder::create();
        $clientBuilder->setHosts($host);
        $clientBuilder->setRetries(2);

        $client = $clientBuilder->build();

        try {

            // $articles = Article::select('id', 'cate_id', 'title', 'content', 'read_times', 'created_at', 'updated_at')
            //     ->orderBy('created_at', 'desc')
            //     ->with('category:id,cate_title');

            // 先清空所有的数据, 再填充
            $params = [
                'index' => $index,
                'body' => [
                    'query' => [
                        'match_all' => new \stdClass(),
                    ],
                ],
            ];
            $response = $client->deleteByQuery($params);
            $this->info("清除旧数据成功~~");

            $articles = Article::all()->toArray();

            $params = ['body' => []];

            foreach ($articles as $article) {
                $this->info('正在准备ID='. $article['id'] .' 标题=' . $article['title'] . ' 的内容');

                $params['body'][] = [
                    'index' => [
                        '_index' => $index,
                        '_id'    => $article['id'],
                    ]
                ];

                $params['body'][] = [
                    'article_id'     => $article['id'],
                    'article_title' => $article['title'],
                    'article_content' => $article['content'],
                    'article_created_at' => $article['created_at'],
                ];
            }

            $this->info('准备工作完成, 即将初始化搜索数据...');
            $responses = $client->bulk($params);

            // foreach ($articles as $article) {
            //
            // }

            $total = count($responses['items']);

            if ($responses['errors'] === false) {
                $this->info('全部处理完成~共初始化了' . $total . '条数据');
            } else {
                $successes = $total - $responses['errors'];
                $this->info('!!!!!!!!!全部' . $total . '条数据, 成功处理了' . $successes . '条数据, ' . $responses['errors'] . '条处理失败');
            }


            // dump($responses);
            return;






            $res = $client->search($params);
            var_dump($res);
        } catch (Elasticsearch\Common\Exceptions\TransportException $e) {
            $previous = $e->getPrevious();
            if ($previous instanceof Elasticsearch\Common\Exceptions\MaxRetriesException) {
                echo "Max retries!";
            }
        }

    }
}
