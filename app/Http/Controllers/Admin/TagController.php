<?php

namespace App\Http\Controllers\Admin;

use App\Model\Article;
use App\Model\Tag;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Cache;

class TagController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $tags = Tag::select('id', 'name')->get()->toArray();
        return view('admin.article.tagIndex', ['tags' => $tags]);
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
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $postData = $request->input('edu_bg', []);
        $responseData = [
            'code' => config('json.code.fail'),
            'text' => config('json.text.fail'),
        ];
        if (!empty($postData)) {
            if ($res = Tag::storePost($postData)) {
                $this->refreshTagCache();
                $responseData = config('json.simple');
            } else {
                $responseData['text'] = $res;
            }
        }
        return response()->json($responseData);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Model\Tag  $tag
     * @return \Illuminate\Http\Response
     */
    public function show(Tag $tag)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Model\Tag  $tag
     * @return \Illuminate\Http\Response
     */
    public function edit(Tag $tag)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Model\Tag  $tag
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Tag $tag)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Model\Tag  $tag
     * @return \Illuminate\Http\Response
     */
    public function destroy(Tag $tag)
    {
        //
    }

    // 刷新首页文章
    public function refreshTagCache()
    {
        $tags = Tag::select('id', 'name')
            ->orderBy('created_at', 'desc')
            ->get()
            ->toArray();
        Cache::put('tags', $tags, 3 * 24 * 60);
    }
}
