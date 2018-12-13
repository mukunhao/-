<?php

namespace App\Models;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;

class School extends Model
{
    protected $connection = 'mysql_center';
    protected $table = 'train_schools';
    public function getSearch($where){
        return DB::connection('mysql_center')->select($where);
    }
    public function getCount($schoolid){
        return $this->where('id',$schoolid)->pluck('count_push_nums')->toArray();
    }
    //每次导入数据记录总数
    public function countData($where){
        return DB::connection('mysql_center')->select($where);
    }
    public function updatecount($where){
        return DB::connection('mysql_center')->update($where);
    }
}
