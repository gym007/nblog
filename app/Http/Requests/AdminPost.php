<?php

namespace App\Http\Requests;

use http\Env\Request;
use Illuminate\Validation\Rule;

class AdminPost extends BaseValidate {
    //验证规则
    protected $rule =[
        'id' => 'required|integer',
        'pass' => 'required|alpha_dash|between:6,20',
        'pass_confirmation' => 'required|same:pass',
    ];

    public function __construct($request)
    {
        $method = $request->method();
        if ($method === 'POST') {
            $this->rule['name'] = 'required|alpha_num|between:5,11|unique:admins,name';
        } else {
            $id = $request->input('id', 0);
            // $this->rule['name'] = 'required|unique:admins,name,' . $id;
            $this->rule['name'] = [
                'required',
                'alpha_num',
                'between:5,11',
                Rule::unique('admins')->ignore($id),
                ];
        }
    }

    //自定义验证信息
    protected $message = [
        'id.required' => '请编辑正确的用户',
        'id.integer' => '选择无效，请刷新页面重试',
        'pass.required' => '请输入密码',
        'pass.alpha_dash' => '密码只能包含字母、数字、破折号（ - ）以及下划线（ _ ）',
        'pass.between' => '密码长度6-20位',
        'repass.required' => '确认密码不能为空',
        'repass.pass_confirmed' => '两次密码不一致',
        'name.required' => '用户名不能为空',
        'name.alpha_num' => '用户名只能包含字母和数字',
        'name.between' => '用户名长度必须介于5-11位之间',
        'name.unique' => '用户名已存在',
    ];

    //自定义场景
    protected $scene = [
        'store'=>"name,pass,repass",
        'update'=> 'name,pass,repass,id',
    ];
}

