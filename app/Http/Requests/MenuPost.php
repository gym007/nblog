<?php

namespace App\Http\Requests;

use http\Env\Request;
use Illuminate\Validation\Rule;

class MenuPost extends BaseValidate {
    //验证规则
    protected $rule =[
        // 'id' => ['required', 'integer', ],
        // 'pid' => ['required', 'integer', ],
        'url' => ['regex:/^\/[a-z]+([A-Z]?[a-z]*)*(\/[a-z]+[A-Z]?[a-z]*[A-Z]*[a-z]*)*[a-z]$/'],
        'route' => ['regex:/^(\/[a-z]+)+[a-z]?$/'],
        'name' => ['required', 'string', ],
        'is_menu' => ['required', 'integer', 'between:0,1'],
    ];

    public function __construct($request)
    {
        // $method = $request->method();
        // if ($method === 'POST') {
        //     $this->rule['name'] = 'required|alpha_num|between:5,11|unique:admins,name';
        // } else {
        //     $id = $request->input('id', 0);
        //     // $this->rule['name'] = 'required|unique:admins,name,' . $id;
        //     $this->rule['name'] = [
        //         'required',
        //         'alpha_num',
        //         'between:5,11',
        //         Rule::unique('admins')->ignore($id),
        //         ];
        // }
    }

    //自定义验证信息
    protected $message = [
        // 'id.required' => '请编辑正确的用户',
        // 'id.integer' => '选择无效，请刷新页面重试',
        'url.regex' => 'url格式不对',
        'route.regex' => '路由格式不对',
        'name.required' => '权限名不能为空',
        'name.string' => '权限名111',
        'is_menu.required' => '请选择是否是菜单',
        'is_menu.integer' => '选择菜单失败，请刷新页面重试',
        'is_menu.between' => '请选择正确的菜单显示范围',
    ];

    //自定义场景
    protected $scene = [
        'store'=>"url,route,name,is_menu",
        'update'=> "url,route,name,is_menu",
    ];
}

