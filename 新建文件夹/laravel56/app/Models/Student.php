<?php

namespace App\Models;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;
use phpDocumentor\Reflection\DocBlock\Tags\Var_;


class Student extends Model
{
    protected $connection = 'mysql_center';
    protected $table = 'train_schools_applies';

    public function getAll($where, $size, $start)
    {
        $sql = "SELECT * FROM train_schools_applies AS a LEFT JOIN applies AS b ON a.apply_id = b.id LEFT JOIN train_schools as c ON a.train_school_id = c.id " . $where . " LIMIT " . $start . ',' . $size;
        $sqls = "SELECT count(*) AS statistics FROM train_schools_applies AS a LEFT JOIN applies AS b ON a.apply_id = b.id LEFT JOIN train_schools as c ON a.train_school_id = c.id  " . $where;
        $datas = DB::connection('mysql_center')->select($sql);
        $count = DB::connection('mysql_center')->select($sqls);
        $data = [];
        $data['data'] = $datas;
        $data['count'] = $count;
        return $data;
    }

    public function getTs($wheres, $size, $start)
    {
        $sql = "SELECT * FROM train_schools_applies AS a LEFT JOIN applies AS b ON a.apply_id = b.id LEFT JOIN train_schools as c ON a.train_school_id = c.id" . $wheres . " LIMIT " . $start . ',' . $size;
        $sqls = "SELECT count(*) AS statistics FROM train_schools_applies AS a LEFT JOIN applies AS b ON a.apply_id = b.id  LEFT JOIN train_schools as c ON a.train_school_id = c.id" . $wheres;
        $datas = DB::connection('mysql_center')->select($sql);
        $count = DB::connection('mysql_center')->select($sqls);
        //上次推送的时间
        $lastSql = "SELECT COUNT(date_format(a.push_time,'%Y-%m-%d')) AS number ,date_format(a.push_time,'%Y-%m-%d') AS time  FROM train_schools_applies AS a 
                    LEFT JOIN applies AS b ON a.apply_id = b.id 
                    LEFT JOIN train_schools as c ON a.train_school_id = c.id
                    GROUP BY date_format(a.push_time,'%Y-%m-%d') ORDER BY date_format(a.push_time,'%Y-%m-%d') DESC LIMIT 1";
        $lastCount = DB::connection('mysql_center')->select($lastSql);
        $data = [];
        $data['data'] = $datas;
        $data['count'] = $count;
        $data['lastCount'] = $lastCount;
        return $data;
    }

    //获取学生入学信息
    public function getInformation($whereall)
    {
        $sql = "SELECT COUNT(a.`status`) as number ,a.`status` FROM train_schools_applies AS a 
                LEFT JOIN applies AS b ON a.apply_id = b.id 
                LEFT JOIN train_schools as c ON a.train_school_id = c.id " . $whereall . " GROUP BY a.`status`";
        $studentdata = DB::connection('mysql_center')->select($sql);

        return $studentdata;

    }


}
