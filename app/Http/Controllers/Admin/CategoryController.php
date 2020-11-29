<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Model\Category;
use App\Http\Requests\StoreCategoryPost;
use DB;
use Cache;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $categories = Category::allCategoriesTable();
        return view('admin.article.category', ['categories' => $categories]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        // 返回分类数据给页面展示
        $categories = Category::allCategories();
        return view('admin.article.category-add', ['categories' => $categories]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    // public function store(StoreCategoryPost $request)
    public function store(Request $request)
    {
        // 不再用系统的验证器，使用场景验证器
        $validate = new StoreCategoryPost();
        $data = $request->all();
        if (!$validate->check($data, 'store')) {
            var_dump($validate->getError());
        }


        $postData = [
            'pid' => $request->input('pid', 0),
            'top' => $request->input('top', 1),
            'cate_title' => $request->input('cate_title', '未命名'),
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
        ];

        $returnData = [
            'code' => config('json.code.success'),
            'text' => config('json.text.success'),
        ];

        if ($res = Category::insert($postData)) {
            $this->refreshCategoryCache();
        } else {
            $returnData = [
                'code' => config('json.code.fail'),
                'text' => config('json.text.fail'),
            ];
        }

        return response()->json($returnData);
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        if ($id < 0) return back()->withErrors(['非法请求，请重试']);
        $check = Category::find($id);
        if (!$check) return back()->withErrors(['非法请求，请重试']);
        $check = $check->toArray();

        $pid = $check['pid'] > 0 ? $check['pid'] : $check['id'];

        $tree = Category::treeForSelect();
        // $tree = json_encode($tree);
        return view('admin.article.category-edit', ['detail' => $check, 'tree' => $tree, 'id' => $pid]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
        // public function update(StoreCategoryPost $request)
    {
        $validate = new StoreCategoryPost();
        $data = $request->all();
        $responseData = config('json.simple');
        if (!$validate->check($data, 'update')) {
            $responseData['code'] = config('json.code.fail');
            $responseData['text'] = $validate->getError();
        } else {
            // 检查父类是否存在
            $checkParent = Category::where('id', $data['pid'])->first();
            if (!$checkParent) {
                $responseData['code'] = config('json.code.fail');
                $responseData['text'] = '抱歉， 该父类不存在';
            } else {
                $find = Category::find($data['id']);
                $find->cate_title = $data['cate_title'];
                $find->pid = $data['pid'];
                $find->top = $data['top'];
                $find->status = $data['status'];
                $find->save();
                $this->refreshCategoryCache();
            }
        }
        return response()->json($responseData);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        // 检查id是否存在以及子目录是否有正在启用的
        $sons = Category::sonsTree2List($id);
        $isDel = true;
        $ids = [];
        if (!empty($sons)) {
            foreach ($sons as $son) {
                if ($son['status']) {
                    $isDel = false;
                }
                $ids[] = $son['id'];
            }
        }

        $responseData = config('json.simple');
        if ($isDel) {
            $ids[] = $id;
            if ($res = DB::table('category')->whereIn('id', $ids)->delete()) {
                $this->refreshCategoryCache();
                return response()->json($responseData);
            }
        } else {
            $responseData['code'] = config('json.code.fail');
            $responseData['text'] = '尚有子目录正在启用状态， 请先停用子目录';
        }
        return response()->json($responseData);

    }

    /**
     * 修改分类的状态
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function status(Request $request)
    {
        $id = $request->input('id', 0);
        $status = (int)$request->input('status', -1);
        $data = config('json.simple');
        $a = [1, 2];
        if ($id < 1 || !in_array($status, [0, 1])) {
            $data['code'] = config('json.code.fail');
            $data['text'] = config('json.text.fail');
        } else {
            $cate = Category::find($id);
            $cate['status'] = $status;
            $cate->save();
            $this->refreshCategoryCache();
        }
        return response()->json($data);
    }

    // 返回下拉树数据
    public function treeForSelect()
    {
        return Category::treeForSelect();
    }

    // 刷新分类缓存
    public function refreshCategoryCache()
    {
        $res = Category::allCategoriesTable();
        Cache::put('categories', $res, 3 * 24 * 60);
    }
}
