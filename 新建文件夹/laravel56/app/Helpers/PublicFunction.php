<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/11/19
 * Time: 16:25
 */
function ic_getSaltPwd($pwd)
{
    return md5("ahk35typkuyt" . $pwd);
}

/**
 * 获取当前控制器名
 *
 * @return string
 */

function getCurrentControllerName()
{
    return getCurrentAction()['controller'];
}

/**
 * 获取当前方法名
 *
 * @return string
 */

function getCurrentMethodName()
{
    return getCurrentAction()['method'];
}

/**
 * 获取当前控制器与方法
 *
 * @return array
 */

function getCurrentAction()
{
    $action = \Route::current()->getActionName();
    list($class, $method) = explode('@', $action);

    return ['controller' => $class, 'method' => $method];
}
function getLoginUserInfo($request=false) {
    $u=\Session::get('login_user_info');
    return $u;
}