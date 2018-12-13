<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Support\Facades\Request;
//use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class UserController extends Controller
{
    //处理用户添加
    public function UserAdd()
    {
        $input = Request::all();

    }

    public function auth()
    {
        $input = Request::all();
        $userid = $input['userid'];
        $userModel = new \App\Models\Users();
       $data =  $userModel->getAuth($userid);
       return $this->JsonReturnTrue($data);
    }
}
