<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\RolePost;
use App\Model\Menu;
use App\Model\Role;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class RoleController extends Controller
{

    public function list(Request $request)
    {
        $page = $request->input('page', 1);
        $limit = $request->input('limit', 10);

        $roles = Role::select('id', 'name', 'created_at')
            ->skip(($page - 1) * $limit)
            ->take($limit)
            ->get();
        if ($roles) {
            $roles = $roles->toArray();
        } else {
            $roles = [];
        }
        $data = [
            'code' => 0,
            'msg' => '',
            'count' => Role::count(),
            'data' => $roles,
        ];
        return response()->json($data);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('admin.admin.role');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $role = ['name' => ''];
        $role['id'] = 0;
        $role['edit'] = 0;
        $menuTree = $this->treeForRole();
        return view('admin.admin.role-add', ['role' => $role, 'menuTree' => $menuTree, 'has_ids' => []]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $response = [
            'code' => config('json.code.fail'),
            'text' => config('json.text.fail'),
        ];

        $validator = new RolePost($request);
        $data = $request->all();
        if (!$validator->scene('store')->check($data)) {
            $response['text'] = $validator->getError();
        } else {
            $menu_ids = $data['menu_ids'];
            if (empty($menu_ids) || count($menu_ids) < 2) {
                $response['text'] = '多给点权限吧~';
            } else {
                $role = new Role();
                $role->name = $data['name'];
                if ($res = $role->save()) {
                    $new_ids = [];
                    foreach ($menu_ids as $menu_id) {
                        if ($menu_id > 0) $new_ids[] = $menu_id;
                    }
                    $role->menus()->attach($new_ids); // 给角色添加权限
                    $response = config('json.simple');
                }
            }
        }
        return response()->json($response);

    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Model\Role  $role
     * @return \Illuminate\Http\Response
     */
    public function show(Role $role)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Model\Role  $role
     * @return \Illuminate\Http\Response
     */
    public function edit(Role $role)
    {
        $has_menus = $role->menus->toArray();
        $has_ids = [];
        if (!empty($has_menus)) {
            foreach ($has_menus as $has_menu) {
                $has_ids[] = $has_menu['id'];
            }
        }
        $role = $role->toArray();
        $role['edit'] = 1;

        $menuTree = $this->treeForRole();
        return view('admin.admin.role-add', ['role' => $role, 'menuTree' => $menuTree, 'has_ids' => $has_ids]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Model\Role  $role
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Role $role)
    {
        $response = [
            'code' => config('json.code.fail'),
            'text' => config('json.text.fail'),
        ];

        $validator = new RolePost($request);
        $data = $request->all();
        if (!$validator->scene('update')->check($data)) {
            $response['text'] = $validator->getError();
        } else {
            $menu_ids = $data['menu_ids'];
            if (empty($menu_ids) || count($menu_ids) < 2) {
                $response['text'] = '多给点权限吧~';
            } else {
                $role->name = $data['name'];
                if ($res = $role->save()) {
                    $new_ids = [];
                    foreach ($menu_ids as $menu_id) {
                        if ($menu_id > 0) $new_ids[] = $menu_id;
                    }
                    $role->menus()->sync($new_ids); // 给角色更新权限
                    $response = config('json.simple');
                }
            }
        }
        return response()->json($response);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Model\Role  $role
     * @return \Illuminate\Http\Response
     */
    public function destroy(Role $role)
    {
        //
    }

    /**
     * 获取全部权限
     */
    public function treeForRole()
    {
        // 获取全部数据并计算出各自的菜单level
        $ori = Menu::select('id', 'pid', 'name')->orderBy('id')->orderBy('pid')->get()->toArray();
        $first = ['id' => 0, 'pid' => -1, 'name' => '顶级节点', 'is_menu' => 1];
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
            // $treeList[$key]['organ_id'] = $item['id'];
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
            if ($item['is_menu']) $treeList[$key]['title'] .= '【菜单】';
            $treeList[$key]['checked'] = $item['id'] > 0 ? false : true;
            $treeList[$key]['disabled'] = $item['id'] > 0 ? false : true;
            // $treeList[$key]['value'] = $item['id'];
            // $treeList[$key]['parent_id'] = $item['pid'];
            // $treeList[$key]['sequence'] = 0;
        }

        return $treeList;
    }
}
