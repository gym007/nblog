<?php

namespace App\Http\Requests;

class StoreCategoryPost extends BaseValidate {
    //验证规则
    protected $rule =[
            'pid' => 'required|integer',
            'top' => 'required|integer|max:200|min:1',
            'cate_title' => 'required|unique:category,cate_title',
        ];
    //自定义验证信息
    protected $message = [
            'pid.required' => '必须选择一个分类',
            'pid.integer' => '选择无效，请刷新页面重试',
            'top.required' => '请输入排序值',
            'top.integer' => '排序值必须为整数',
            'top.max' => '排序值最大200',
            'top.min' => '排序值最小1',
            'cate_title.required' => '分类标题不可为空',
            'cate_title.unique' => '该分类名字已存在，请换一个',
        ];

    //自定义场景
    protected $scene = [
        'store'=>"pid,top,cate_title",
        'update'=> 'pid,top',
    ];
}



// use Illuminate\Foundation\Http\FormRequest;
// class StoreCategoryPost extends FormRequest
// class StoreCategoryPost extends BaseValidate
// {
//     public $scenes = [
//         'store' => 'pid,top,cate_title',
//         'update' => 'pid,top',
//     ];
//
//     /**
//      * Determine if the user is authorized to make this request.
//      *
//      * @return bool
//      */
//     // public function authorize()
//     // {
//     //     return true;
//     // }
//
//     /**
//      * Get the validation rules that apply to the request.
//      *
//      * @return array
//      */
//     public function rules()
//     {
//         return [
//             'pid' => 'required|integer',
//             'top' => 'required|integer|max:200|min:1',
//             'cate_title' => 'required|unique:category,cate_title',
//         ];
//     }
//
//     /**
//      * @return array
//      */
//     public function messages()
//     {
//         return [
//             'pid.required' => '必须选择一个分类',
//             'pid.integer' => '选择无效，请刷新页面重试',
//             'top.required' => '请输入排序值',
//             'top.integer' => '排序值必须为整数',
//             'top.max' => '排序值最大200',
//             'top.min' => '排序值最小1',
//             'cate_title.required' => '分类标题不可为空',
//             'cate_title.unique' => '该分类名字已存在，请换一个',
//         ];
//     }
//
// }
