<?php

namespace App\Http\Middleware;

use Closure;
use App\Models\Log;

class AdminLog
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Closure $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $name = $request->name;
        $input = json_encode($request->all()); //操作的内容
        $content = $input == null ? 0 : $input;

        $path = $request->url();  //操作的路由
        $method = $request->method();  //操作的方法
        $ip = $request->ip();  //操作的IP
        $log = new Log();
        $log->user_id = 11;
        $log->name = 'aa';
        $log->content = $content;
        $log->url = $path;
        $log->method = $method;
        $log->ip = $ip;
        $log->addtime = date("Y-m-d");


        $log->save();

        return $next($request);
    }
}
