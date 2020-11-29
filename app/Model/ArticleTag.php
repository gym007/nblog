<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\Pivot;

// class ArticleTag extends Model
class ArticleTag extends Pivot
{
    use SoftDeletes;

    protected $table = 'articles_tags';

}
