<?php

namespace App\Http\Controllers\Admin;

use App\Model\Admin;
use App\Model\Role;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\AdminPost;

class AdminController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('admin.admin.admin');
    }

    public function list(Request $request)
    {
        $page = $request->input('page', 1);
        $limit = $request->input('limit', 10);

        $data = Admin::select('id', 'name', 'status', 'created_at')
            ->skip(($page - 1) * $limit)
            ->take($limit)
            ->get()
            ->toArray();
        $data = [
            'code' => 0,
            'msg' => 'OK',
            'count' => Admin::count(),
            'data' => $data,
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
        // 获取所有的角色...
        $roles = Role::all()->toArray();
        $admin = [
            'id' => 0,
            'name' => '',
            'pass' => '',
            'roles' => [],
            'edit' => 0,
            'repass' => '',
        ];
        $myRoles = [];
        return view('admin.admin.add', ['admin' => $admin, 'roles' => $roles, 'myRoles' => $myRoles]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $roleIds = $request->input('role', []);

        $responseData = config('json.simple');

        $validator = new AdminPost($request);
        $data = $request->all();
        if (empty($roleIds)) {
            $responseData['code'] = config('json.code.fail');
            $responseData['text'] = '至少选择一个角色';
        } else if (!$validator->check($data, 'store')) {
            $responseData['code'] = config('json.code.fail');
            $responseData['text'] = $validator->getError();
        } else {
            $admin = new Admin();

            // 还要处理角色等问题

            $admin->name = $data['name'];
            $admin->pass = bcrypt($data['name']);
            $admin->status = 1;
            if (!$res = $admin->save()) {
                $responseData['code'] = config('json.code.fail');
                $responseData['text'] = '添加失败，请稍后重试';
            } else {
                $admin->roles()->attach($roleIds);
            }
        }
        return response()->json($responseData);
    }

    /**
     * Display the specified resource.
     *
     * @param \App\Admin $admin
     * @return \Illuminate\Http\Response
     */
    public function show(Admin $admin)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param \App\Admin $admin
     * @return \Illuminate\Http\Response
     */
    public function edit(Admin $admin)
    {
        // 获取管理员的全部角色
        $myRoles = $admin->roles->toArray();
        $arr = [];
        if (!empty($myRoles)) {
            foreach ($myRoles as $myRole) {
                $arr[] = $myRole['id'];
            }
        }
        $myRoles = $arr;

        $admin = $admin->toArray();
        // 获取所有的角色...
        $roles = Role::all()->toArray();

        $admin['edit'] = 1;
        $admin['pass'] = 123456;
        $admin['repass'] = 123456;
        return view('admin.admin.add', ['admin' => $admin, 'roles' => $roles, 'myRoles' => $myRoles]);
        // return view('admin.admin.add', ['admin' => $admin]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\Admin $admin
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Admin $admin)
    {
        $roleIds = $request->input('role', []);

        $responseData = config('json.simple');

        $validator = new AdminPost($request);
        $data = $request->all();
        if (empty($roleIds)) {
            $responseData['code'] = config('json.code.fail');
            $responseData['text'] = '至少选择一个角色';
        } else if (!$validator->check($data, 'update')) {
            $responseData['code'] = config('json.code.fail');
            $responseData['text'] = $validator->getError();
        } else {
            $admin->name = $data['name'];
            if ((int)$data['pass'] !== 123456) {
                $admin->password = bcrypt($data['pass']);
            }
            $admin->status = 1;
            if (!$res = $admin->save()) {
                $responseData['code'] = config('json.code.fail');
                $responseData['text'] = '编辑失败，请稍后重试';
            } else {
                $admin->roles()->sync($roleIds); // 给角色更新权限
            }
        }
        return response()->json($responseData);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \App\Admin $admin
     * @return \Illuminate\Http\Response
     */
    public function destroy(Admin $admin)
    {
        $responseData = config('json.simple');
        if (!$res = $admin->delete()) {
            $responseData['code'] = config('json.code.fail');
            $responseData['text'] = config('json.text.fail');
        }
        return response()->json($responseData);
    }

    public function status(Request $request)
    {
        $id = $request->input('id', 0);
        $responseData = [
            'code' => config('json.code.fail'),
            'text' => config('json.text.fail'),
        ];
        if ($admin = Admin::find($id)) {
            $admin->status = abs($admin->status - 1);
            if ($res = $admin->save()) {
                $responseData = config('json.simple');
            }
        } else {
            $responseData['text'] = '未知错误， 请刷新页面重试';
        }
        return response()->json($responseData);
    }
}
