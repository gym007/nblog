<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\MenuPost;
use App\Model\Menu;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class MenuController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('admin.admin.rule');
    }

    public function test($data, $pid, &$level = 0)
    {
        if ($pid == -1) return 0;
        if ($pid == 0) {
            $level++;
            return $level;
        }

        foreach ($data as $id => $datum) {
            if ($id == $pid) {
                $level++;
                return $this->test($data, $datum['pid'], $level);
            }
        }
    }

    public function list(Request $request)
    {
        $page = $request->input('page', 1);
        $limit = $request->input('limit', 10);
        $menus = Menu::select('id', 'name', 'url', 'route', 'updated_at')
            ->orderBy('created_at')
            ->skip(($page - 1) * $limit)
            ->take($limit)
            ->get()
            ->toArray();
        $data = [
            'code' => 0,
            'msg' => 'OK',
            'count' => Menu::count(),
            'data' => $menus,
        ];
        return response()->json($data);
    }

    /**
     * 获取全部的菜单整合成树形数据
     * @param Request $request
     * @return array
     */
    public function treeForSelect(Request $request)
    {
        $menus = Menu::treeForSelect();
        // 在原有基础上， 加一个根菜单
        $root = ['id' => 0, 'pid' => -1, 'name' => '根菜单', 'open' => true, 'checked' => true, 'spread' => true];
        array_unshift($menus, $root);
        return $menus;
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
        $responseData = config('json.simple');

        $validator = new MenuPost($request);
        $data = $request->all();
        if (!$validator->check($data, 'store')) {
            $responseData['code'] = config('json.code.fail');
            $responseData['text'] = $validator->getError();
        } else {

            $menu = new Menu();
            $menu->pid = $data['pid'];
            $menu->url = $data['url'];
            $menu->route = $data['route'];
            $menu->name = $data['name'];
            $menu->is_menu = $data['is_menu'];
            if (!$res = $menu->save()) {
                $responseData['code'] = config('json.code.fail');
                $responseData['text'] = '编辑失败，请稍后重试';
            }
        }
        return response()->json($responseData);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Model\Menu  $menu
     * @return \Illuminate\Http\Response
     */
    public function show(Menu $menu)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Model\Menu  $menu
     * @return \Illuminate\Http\Response
     */
    public function edit(Menu $menu)
    {
        if ($menu) {
            $menu = $menu->toArray();
            return view('admin.admin.rule-edit', ['menu' => $menu]);
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Model\Menu  $menu
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Menu $menu)
    {
        $responseData = config('json.simple');

        $validator = new MenuPost($request);
        $data = $request->all();
        if (!$validator->check($data, 'update')) {
            $responseData['code'] = config('json.code.fail');
            $responseData['text'] = $validator->getError();
        } else {
            $menu->pid = $data['pid'];
            $menu->name = $data['name'];
            $menu->url = $data['url'];
            $menu->route = $data['route'];
            $menu->is_menu = $data['is_menu'];
            if (!$res = $menu->save()) {
                $responseData['code'] = config('json.code.fail');
                $responseData['text'] = '编辑失败，请稍后重试';
            }
        }
        return response()->json($responseData);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Model\Menu  $menu
     * @return \Illuminate\Http\Response
     */
    public function destroy(Menu $menu)
    {
        $responseData = config('json.simple');
        if (!$res = $menu->delete()) {
            $responseData['code'] = config('json.code.fail');
            $responseData['text'] = config('json.text.fail');
        }
        return response()->json($responseData);
    }

    /**
     * 获取全部权限
     */
    public function treeForRole()
    {
        // 获取全部数据并计算出各自的菜单level
        $ori = Menu::select('id', 'pid', 'name')->orderBy('id')->orderBy('pid')->get()->toArray();
        $first = ['id' => 0, 'pid' => -1, 'name' => '顶级节点'];
        array_unshift($ori, $first);
        $arr = [];
        foreach ($ori as $value) {
            $value['level'] = 0;
            $arr[$value['id']] = $value;
        }
        // list
        foreach ($arr as $key => $item) {
            $arr[$key]['level'] = getLevel($arr, $item['pid']);
        }

        // 将树状数据以一维数组并按父子关系排列=>tree2list
        $tree = Menu::treeForSelect();
        $treeList = tree2list($tree);
        array_unshift($treeList, $first);
        foreach ($treeList as $key => $item) {
            $treeList[$key]['level'] = $arr[$item['id']]['level'];
            $treeList[$key]['organ_id'] = $item['id'];
            if ($treeList[$key]['level'] > 0) {
                $str = '';
                for ($i = 0; $i < $treeList[$key]['level']; $i++) {
                    $str .= '|--';
                }
                $treeList[$key]['organ_name'] = $str . $item['name'];
            } else {
                $treeList[$key]['organ_name'] = $item['name'];
            }

            $treeList[$key]['value'] = $item['id'];
            $treeList[$key]['title'] = $treeList[$key]['organ_name'];
            $treeList[$key]['parent_id'] = $item['pid'];
            $treeList[$key]['sequence'] = 0;
        }

        return response()->json($treeList);
        return $treeList;
    }
}
