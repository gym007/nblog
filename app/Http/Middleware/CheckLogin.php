<?php

namespace App\Http\Middleware;

use Closure;

class CheckLogin
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
        // 检查session有没有登录，没有的话滚去登录
        if (session()->exists('_admin')) {
            $admin = session()->get('_admin');
            if (!empty($admin)) {
                return $next($request);
            }
        }

        return redirect('/admin/login');
    }
}
