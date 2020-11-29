<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
// use Laravel\Scout\Searchable;

class SearchArticle extends Model
{
    // use Searchable;

    protected $table = 'articles';
    protected $fillable = ['title', 'content', 'created_at'];

    public function searchableAs()
    {
        return '_doc';
    }

    public function toSearchableArray()
    {
        return [
            'article_id' => $this->id,
            'article_title' => $this->title,
            'article_content' => $this->content,
            'article_created_at' => $this->created_at,
        ];
    }


}
