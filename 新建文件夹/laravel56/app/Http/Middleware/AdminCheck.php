<?php

namespace App\Http\Middleware;

use Closure;

class AdminCheck
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
        $curLoginUser = getLoginUserInfo($request);
        $u=\Session::get('login_user_info');
        dd(11);
        if ($curLoginUser) {
            \Session::put('login_user_info', $curLoginUser);
        }
        if (!$curLoginUser) {
            return response()->json(['error_no' => -999, 'error_msg' => '您未登录或登录信息已丢失，请重新登录.', "results" => []]);
        } else {
            \Session::put('login_user_info', $curLoginUser);
        }
        unset($request['remember_token']);
        unset($request['remember_user_id']);
        unset($request['remember_member_id']);
        return $next($request);

        //TODO 判断权限
        $ispermis = checkPermission('', $curLoginUser);//检查权限
        if ('no' == $ispermis) {//没有权限，跳转到控制首页
            header('Access-Control-Allow-Origin: *');
            return response()->json(['error_no' => -999, 'error_msg' => '您没有权限访问' . $reject_url . '，请联系管理员', "results" => []]);
            exit;
        }
        return $next($request);//判断通过
    }
}

