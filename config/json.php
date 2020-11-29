<?php
/**
 * json返回数据的格式化处理
 */

return [
    'code' => [
        'success' => 200,
        'fail' => 300,
        'unknown_error' => 400,
        'illegal' => 500,
    ],

    'text' => [
        'success' => 'OK',
        'fail' => '请求失败',
        'unknown_error' => '未知错误，请稍后重试',
        'illegal' => '非法请求！'
    ],

    'simple' => [
        'code' => 200,
        'text' => 'OK',
    ],
];