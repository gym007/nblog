<?php

namespace App\Http\Middleware;

use Closure;

class CheckPermission
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $actionName = $request->route()->getActionName();
        $a = explode('@', $actionName);
        if ($a != ['Closure']) {
            list($class, $action) = $a;
        } else {
            return $next($request);
        }

        // 获取访问的模块、控制器、方法
        $module = str_replace(
            '\\',
            '.',
            str_replace(
                'App\\Http\\Controllers\\',
                '',
                trim(
                    implode('\\', array_slice(explode('\\', $class), 0, -1)),
                    '\\'
                )
            )
        );
        $class = str_replace(
            'Controller',
            '',
            substr(strrchr($class, '\\'), 1)
        );

        $module = strtolower($module);
        $class = strtolower($class);
        $action = strtolower($action);

        $route = '/' . $module . '/' . $class . '/' . $action;

        $admin = session()->get('_admin');

        // dump($route);
        // dd($admin['permissions']);

        // 非超管需要验证权限
        if ($admin['id'] != 1 && !in_array($route, $admin['permissions'])) {
        // if (!in_array($route, $admin['permissions'])) {
            // dump($route);
            // dd($admin['permissions']);

            if ($request->ajax()) {
                return response()->json([
                    'code' => config('json.code.fail'),
                    'text' => '权限不足， 无法操作~',
                ]);
            } else {
                return back()->withErrors('权限不足， 无法操作~');
            }
        }

        return $next($request);
    }
}
