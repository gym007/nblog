<?php

namespace App\Http\Requests;

use http\Env\Request;
use Illuminate\Validation\Rule;

class RolePost extends BaseValidate {
    //验证规则
    protected $rule =[
        'id' => 'required|integer',
        // 'pid' => ['required', 'integer', ],
        // 'name' => 'required|string',
        'menu_ids' => 'required|array',
    ];

    public function __construct($request)
    {
        $method = $request->method();
        if ($method === 'POST') {
            $this->rule['name'] = 'required|string|between:2,10|unique:roles,name';
        } else {
            $id = $request->input('id', 0);
            // $this->rule['name'] = 'required|unique:admins,name,' . $id;
            $this->rule['name'] = [
                'required',
                'string',
                'between:2,10',
                Rule::unique('roles')->ignore($id),
                ];
        }
    }

    //自定义验证信息
    protected $message = [
        'id.required' => '请编辑正确的角色',
        'id.integer' => '选择无效，请刷新页面重试',
        // 'url.regex' => 'url格式不对',
        // 'route.regex' => '路由格式不对',
        'name.required' => '角色名不能为空',
        'name.string' => '角色名格式不对',
        'name.between' => '角色名长度必须在2-10位之间',
        'name.unique' => '角色名已存在',
        'menu_ids.required' => '至少选择一个权限',
        'menu_ids.array' => '权限格式不正确',
    ];

    //自定义场景
    protected $scene = [
        'store' => "name,menu_ids",
        'update' => "id,name,menu_ids",
    ];
}

