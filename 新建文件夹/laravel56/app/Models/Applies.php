<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Applies extends Model
{

    protected $connection = 'mysql_center';
    protected $table = 'applies';
    protected $primaryKey = 'id';

    /**
     * The "type" of the auto-incrementing ID.
     *
     * @var string
     */
    protected $keyType = 'int';

    /**
     * Indicates if the IDs are auto-incrementing.
     *
     * @var bool
     */
    public $incrementing = true;

    public function TsAdd($str)
    {
        $sql = "INSERT INTO applies (`phone_time`,`address`,`user_status`,`phone`,`grade`,`name`,`sex`,`is_interest`,`school_phone`,`hope_address`,`base`,`circumstances`,`status`,`source`,`user_number`,`train_school_id`,`province`) values $str";
        $data = DB::connection('mysql_center')->insert($sql);
        return $data;
    }

    public function getAll($where)
    {
        $sql = "SELECT a.`user_number`,d.name,a.`phone`,a.`status`,a.`push_time`,c.abbreviation,a.`id` FROM applies AS a  LEFT JOIN train_schools as c ON a.train_school_id = c.id left join school_categories d on a.province = d.id" . $where;
        $sqls = "SELECT count(*) AS statistics FROM applies AS a LEFT JOIN train_schools as c ON a.train_school_id = c.id  " . $where;
        $datas = DB::connection('mysql_center')->select($sql);
        $count = DB::connection('mysql_center')->select($sqls);
        $data = [];
        $data['data'] = $datas;
        $data['count'] = $count;
        return $data;
    }

    public function getTs($wheres)
    {
        $sql = "SELECT a.`user_number`,d.`name`,a.`phone`,a.`status`,a.`push_time`,a.`contact`,a.id FROM  applies AS a  LEFT JOIN train_schools as c ON a.train_school_id = c.id left join school_categories d on a.province = d.id" . $wheres;
        $sqls = "SELECT count(*) AS statistics FROM  applies AS a   LEFT JOIN train_schools as c ON a.train_school_id = c.id" . $wheres;
        $datas = DB::connection('mysql_center')->select($sql);
        $count = DB::connection('mysql_center')->select($sqls);
        $data = [];
        $data['data'] = $datas;
        $data['count'] = $count;
        return $data;
    }

    //获取报名学生
    public function getStudentN()
    {
        $sql = "SELECT COUNT(*) count from applies a WHERE a.source = 1 AND a.`status` =1  ";
        $sqls = "SELECT  a.created_at from applies a WHERE a.source = 1 AND a.`status` =1 GROUP BY a.created_at ORDER BY a.created_at DESC LIMIT 1";
        $studentdata = DB::connection('mysql_center')->select($sql);
        $studentdatas = DB::connection('mysql_center')->select($sqls);
        $data['count'] = $studentdata;
        $data['date'] = $studentdatas;

        return $data;
    }

    //获取学生入学信息
    public function getInformation($where)
    {
        $studentdata = DB::connection('mysql_center')->select($where);
        return $studentdata;
    }

    //获取没给学校入学学生
    public function getSchoolStudent($status)
    {
        $data = DB::connection('mysql_center')->select($status);
        return $data;
    }
    //三天回收学生
//    public function update(){
//        $sql = "UPDATE  applies SET train_school_id = 0 ,status = 5 where datediff(curdate(), push_time)>=3 AND `status` = 1";
//        DB::connection('mysql_center')->select($sql);
//    }
//海绵推送
    public function setStudent($schoolid, $studentid)
    {
        $sql = "UPDATE  applies a SET train_school_id = " . $schoolid . "  , status = 1 where a.id in (" . $studentid . ')';
        $data = DB::connection('mysql_center')->update($sql);
        return $data;
    }
//海绵删除
    public function Hmdel($where){
        $sql = "DELETE FROM applies  where id= ".$where;
        return DB::connection('mysql_center')->delete($sql);
    }
//点击查看电话未入学俩月回收

}
