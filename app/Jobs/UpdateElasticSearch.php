<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Elasticsearch\Common\Exceptions\MaxRetriesException;
use Elasticsearch\Common\Exceptions\TransportException;
use Elasticsearch\ClientBuilder;
use DB;


class UpdateElasticSearch implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $tries = 3;

    public $timeout = 120;

    protected $data;

    protected $index;
    protected $elasticClient;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($data)
    {
        $this->data = $data;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $host = config('scout.elasticsearch.hosts');
        $this->index = config('scout.elasticsearch.index');

        $clientBuilder = ClientBuilder::create();
        $clientBuilder->setHosts($host);
        $clientBuilder->setRetries(2);

        $this->elasticClient = $clientBuilder->build();

        switch($this->data['type']) {
            case 'add':
                $this->add();
                break;

            case 'delete':
                $this->delete();
                break;

            case 'update':
                $this->update();
                break;

            default:
                return;
        }
    }

    public function add()
    {
        $params = [
            'index' => $this->index,
            'id' => $this->data['id'],
            'body' => [
                'article_id' => $this->data['id'],
                'article_title' => $this->data['title'],
                'article_content' => $this->data['content'],
                'article_created_at' => $this->data['created_at'],
            ],
        ];

        try {
            $responses = $this->elasticClient->index($params);
        } catch (Elasticsearch\Common\Exceptions\TransportException $e) {
            $previous = $e->getPrevious();
            if ($previous instanceof Elasticsearch\Common\Exceptions\MaxRetriesException) {
                echo "Max retries!";
            }
        }
    }

    public function delete()
    {
        $params = [
            'index' => $this->index,
            'id'    => $this->data['id'],
        ];

        try {
            $response = $this->elasticClient->delete($params);
        } catch (Elasticsearch\Common\Exceptions\TransportException $e) {
            $previous = $e->getPrevious();
            if ($previous instanceof Elasticsearch\Common\Exceptions\MaxRetriesException) {
                echo "Max retries!";
            }
        }
    }

    public function update()
    {
        $params = [
            'index'=> $this->index,
            'id'        => $this->data['id'],
            'body'  => [
                'doc' => [
                    'article_title' => $this->data['title'],
                    'article_content' => $this->data['content'],
                ]
            ]
        ];

        try {
            $responses = $this->elasticClient->update($params);
        } catch (Elasticsearch\Common\Exceptions\TransportException $e) {
            $previous = $e->getPrevious();
            if ($previous instanceof Elasticsearch\Common\Exceptions\MaxRetriesException) {
                echo "Max retries!";
            }
        }
    }
}
