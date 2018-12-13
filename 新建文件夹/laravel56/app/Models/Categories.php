<?php

namespace App\Models;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;

class Categories extends Model
{
    //
    protected $connection = 'mysql_center';
    protected $table = 'school_categories';
    public function getStudent($where){
        $data = DB::connection('mysql_center')->select($where);
        return $data;
    }
    public function getSearch($where){
        $data = DB::connection('mysql_center')->select($where);
        return $data;
    }
}
