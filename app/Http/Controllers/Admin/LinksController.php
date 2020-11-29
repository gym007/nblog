<?php

namespace App\Http\Controllers\Admin;

use App\Model\Links;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class LinksController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('admin.links.list');
    }

    public function list(Request $request)
    {
        $page = $request->input('page', 1);
        $limit = $request->input('limit', 10);
        $links = Links::select('id', 'name', 'link')->orderBy('created_at', 'desc')->skip(($page - 1) * $limit)->take($limit)->get()->toArray();
        $data = [
            'code' => 0,
            'msg' => 'OK',
            'count' => Links::count(),
            'data' => $links,
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
        $link = ['name' => '', 'link' => '', 'id' => 0];
        return view('admin.links.add', ['link' => $link, 'edit' => 0]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $name = $request->input('name', '');
        $url = $request->input('link', '');
        $urlPreg = '/^((https?|ftp|news):\/\/)?([a-z]([a-z0-9\-]*[\.。])+([a-z]{2}|aero|arpa|biz|com|coop|edu|gov|info|int|jobs|mil|museum|name|nato|net|org|pro|travel)|(([0-9]|[1-9][0-9]|1[0-9]{2}|2[0-4][0-9]|25[0-5])\.){3}([0-9]|[1-9][0-9]|1[0-9]{2}|2[0-4][0-9]|25[0-5]))(\/[a-z0-9_\-\.~]+)*(\/([a-z0-9_\-\.]*)(\?[a-z0-9+_\-\.%=&]*)?)?(#[a-z][a-z0-9_]*)?$/
';

        $returnData = [
            'code' => config('json.code.fail'),
            'text' => config('json.text.fail'),
        ];
        if (!preg_match($urlPreg, $url)) {
            $returnData['text'] = 'url格式不正确';
        } else if (empty($name)) {
            $returnData['text'] = '链接名不能为空';
        } else {
            $data = [
                    'name' => $name,
                    'link' => $url,
                ];
            if ($res = Links::create($data)) {
                $this->refreshLinksCache();
                $returnData = config('json.simple');
            }
        }
        return response()->json($returnData);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Model\Links  $links
     * @return \Illuminate\Http\Response
     */
    public function show(Links $links)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Model\Links  $link
     * @return \Illuminate\Http\Response
     */
    public function edit(Links $link)
    {
        $link = $link->toArray();
        return view('admin.links.add', ['link' => $link, 'edit' => 1]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Model\Links  $links
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Links $link)
    {
        $name = $request->input('name', '');
        $url = $request->input('link', '');
        $returnData = config('json.simple');
        if (!empty($link) && !empty($name) && !empty($url)) {
            $link->name = $name;
            $link->link = $url;
            if (!$res = $link->save()) {
                $returnData = [
                    'code' => config('json.code.fail'),
                    'text' => config('json.text.fail'),
                ];
            } else {
                $this->refreshLinksCache();
            }
        }
        return response()->json($returnData);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Model\Links  $link
     * @return \Illuminate\Http\Response
     */
    public function destroy(Links $link)
    {
        if ($res = $link->delete()) {
            $this->refreshLinksCache();
            $responseData = config('json.simple');
        } else {
            $responseData = [
                'code' => config('json.code.fail'),
                'text' => config('json.text.fail')
            ];
        }
        return response()->json($responseData);
    }

    public function refreshLinksCache()
    {
        $links = Links::select('id', 'name', 'link')
            ->orderBy('created_at', 'desc')
            ->get()
            ->toArray();
        Cache::put('links', $links, 3 * 24 * 60);
    }
}
