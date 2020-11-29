<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Tag extends Model
{
    use SoftDeletes;

    protected $table = 'tags';
    protected $fillable = ['name'];

    /**
     * 添加标签，校验新标签，删除旧标签
     * @param $postData
     * @return bool|\Exception
     */
    public static function storePost($postData)
    {
        $oldIds = [];
        $newDatas = [];
        try {
            // 先更新旧数据
            foreach ($postData as $postDatum) {
                if (strpos($postDatum,'-')) {
                    $arr = explode('-', $postDatum);
                    $oldIds[] = $arr[0];
                    $find = self::find($arr[0]);
                    $find->name = $arr[1];
                    if (!$res = $find->save()) {
                        throw new \Exception($arr[0] . '更新失败');
                    }
                } else {
                    if (!empty($postDatum)) $newDatas[] = $postDatum;
                }
            }

            // 删除没更新的旧数据
            if (!empty($oldIds)) {
                $others = self::whereNotIn('id', $oldIds)->get();
                if ($others) {
                    // $others = $others->toArray();
                    foreach ($others as $other) {
                        $other->deleted_at = date('Y-m-d H:i:s');
                        if ($ress = $other->save()) {
                            throw new \Exception($other->id . '删除失败');
                        }
                    }
                }
            }

            // 插入新数据
            if (!empty($newDatas)) {
                foreach ($newDatas as $newData) {
                    $tag = new self();
                    $tag->name = $newData;
                    if (!$res = $tag->save()) {
                        throw new \Exception($newData . '标签添加失败');
                    }
                }
            }


        } catch (\Exception $e) {
            return $e;
        }

        return true;

        // foreach ($postData as $postDatum) {
        //     if (strpos($postDatum,'-')) {
        //         $arr = explode('-', $postDatum);
        //
        //         $oldDatas[] = [
        //             'id' => $arr[0],
        //             'name' => $arr[1],
        //         ];
        //     }
        // }
        // dd($oldDatas);
    }

    // 处理创建文章时传过来的标签
    public static function createArticle($tags)
    {
        $tags = explode(',', $tags);
        $tags = array_unique($tags);
        $tagIds = [];
        foreach ($tags as $tag) {
            if (!empty($tag) && strpos($tag, ' ') === false && strpos($tag, '，') === false) {
                // 根据标签去寻找库是否存在， 不存在则创建，并获取标签id
                $find = self::firstOrCreate(['name' => $tag]);
                $tagIds[] = $find->id;
            }
        }
        return $tagIds;
    }

    public function articles()
    {
        return $this->belongsToMany('App\Model\Article', 'articles_tags', 'tag_id', 'article_id' )
            ->withTimestamps()->using('App\Model\ArticleTag')->withPivot('id');
    }


}
