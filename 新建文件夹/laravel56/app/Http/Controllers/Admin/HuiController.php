<?php

namespace App\Http\Controllers\Admin;

//use Illuminate\Http\Request;
use Illuminate\Support\Facades\Request;
use App\Http\Controllers\Controller;
use Excel;

class HuiController extends Controller
{
    //获取学生城市
    public function getCity(){
        try{
            $input = Request::all();
            $city = $input['city'];
//                $city = "";
            $categoryModel = new \App\Models\Categories();
            if ($city == ''){
                $where = "  SELECT b.`name` city,b.id ,COUNT(a.name) count  FROM (SELECT * FROM applies a WHERE a.status = 5) a 
  RIGHT JOIN school_categories b ON a.province = b.id WHERE b.category_type = 0 GROUP BY b.`name` ,b.id ORDER BY COUNT(a.name) DESC";
                $datas = $categoryModel->getSearch($where);
                $nums = 0;
                foreach ($datas as $value){
                    $nums += $value->count;
                }
                $data['data'] = $datas;
                $data['nums'] = $nums;
                return $this->JsonReturnTrue($data);
            }else{
                $where = "  SELECT b.`name` city,b.id ,COUNT(a.name) count  FROM (SELECT * FROM applies a WHERE a.status = 5) a 
                        RIGHT JOIN school_categories b ON a.province = b.id WHERE  b.`name` LIKE '".$city."%' and b.category_type = 0 GROUP BY b.`name` ,b.id";
                $datas = $categoryModel->getSearch($where);
                $data['data'] = $datas;
                return $this->JsonReturnTrue($data);
            }
        }catch (\Exception $e){
            return $this->JsonReturnFalse($e->getCode(),$e->getMessage());
        }

    }
    //获取回收的学生信息
    public function getStudentHs(){
        $input = Request::all();
        $cityid = $input['cityid'];
        if ($cityid == 0) {
          $where = "SELECT a.id,a.user_number,b.`name`,a.phone,a.updated_at FROM applies as a LEFT JOIN school_categories as b ON a.province = b.id  where a.status = 5 ORDER BY a.updated_at desc ";
            $categoryModel = new \App\Models\Categories();
            $data = $categoryModel->getStudent($where);
            return $this->JsonReturnTrue($data);
        } else {
            $where = "SELECT a.id,a.user_number,b.`name`,a.phone,a.updated_at FROM applies as a 
LEFT JOIN school_categories as b ON a.province = b.id  where a.status = 5 and a.province = " . $cityid ." ORDER BY a.updated_at desc ";
            $categoryModel = new \App\Models\Categories();
            $data = $categoryModel->getStudent($where);
            return $this->JsonReturnTrue($data);
        }
    }
    //批量导出
    public function export()
    {
        $input = Request::all();
        $data = $input['date_'];
        $data = json_encode($data, true);
        $data = json_decode($data, true);
        $where = "SELECT a.id,a.user_number,b.`name`,a.phone,a.updated_at FROM applies as a LEFT JOIN school_categories as b ON a.province = b.id  where a.id in (" . $data.')';
        $categoryModel = new \App\Models\Categories();
        $re = $categoryModel->getStudent($where);
        $re = json_encode($re, true);
        $re = json_decode($re, true);
        Array_unshift($re, ['编号', '省份','联系号码','回收时间']);
        Excel::create('学生成绩',function($excel) use ($re){
            $excel->sheet('score', function($sheet) use ($re){
                $sheet->rows($re);
            });
        })->export('xls');
    }
    //全部导出
    public function exportData()
    {
        $input = Request::all();

        $cityid = $input['cityid_'];
        if ($cityid == 0){
            $where = "SELECT a.id,a.user_number,b.`name`,a.phone,a.updated_at FROM applies as a LEFT JOIN school_categories as b ON a.province = b.id ";
        }else{
            $where = "SELECT a.id,a.user_number,b.`name`,a.phone,a.updated_at FROM applies as a LEFT JOIN school_categories as b ON a.province = b.id where a.province = ".$cityid;
        }
        $categoryModel = new \App\Models\Categories();
        $re = $categoryModel->getStudent($where);
        $re = json_encode($re, true);
        $re = json_decode($re, true);
        Array_unshift($re, ['编号', '省份','联系号码','回收时间']);
        Excel::create('学生成绩',function($excel) use ($re){
            $excel->sheet('score', function($sheet) use ($re){
                $sheet->rows($re);
            });
        })->export('xls');
    }


}
