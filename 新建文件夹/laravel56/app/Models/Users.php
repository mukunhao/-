<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;


class Users extends Model
{

    protected $table = 'dashuju_users';

    public function getAll($name)
    {
        return $this->where('name', $name)->get()->toArray();
    }

    public function userAdd()
    {

    }

    public function getAuth($userid)
    {
        $sql = "SELECT c.`name` as role,e.`name` ,e.path FROM dashuju_users as a 
                LEFT JOIN dashuju_user_role as b ON a.id = b.user_id 
                LEFT JOIN dashuju_roles as c ON b.role_id = c.id 
                LEFT JOIN dashuju_role_auth as d ON c.id = d.role_id
                LEFT JOIN dashuju_auth as e ON d.auth_id = e.id where a.id = " . $userid;
        return DB::select($sql);
    }

}
