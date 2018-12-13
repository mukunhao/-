<?php

namespace App\Http\Controllers\Admin;

//use Illuminate\Http\Request;
use Illuminate\Support\Facades\Request;
use App\Http\Controllers\Controller;

class TsController extends Controller
{
    //搜索城市
    public function searchCity()
    {
        try {
            $input = Request::all();
            $city = $input['city'];
            $categoryModel = new \App\Models\Categories();
            if ($city == '') {
                $where = "SELECT COUNT(c.`name`) count,b.`city`,b.id FROM (SELECT a.id,a.`name` city FROM school_categories a WHERE a.category_type = 0) b 
LEFT JOIN train_schools  c ON b.id = c.school_area_id   GROUP BY b.`city`,b.id ORDER BY COUNT(c.`name`) DESC ";
                $datas = $categoryModel->getSearch($where);
                $nums = 0;
                foreach ($datas as $value){
                     $nums += $value->count;
                }
                $data['data'] = $datas;
                $data['nums'] = $nums;
                return $this->JsonReturnTrue($data);
            } else {
                $where = "SELECT COUNT(c.`name`) count,b.`city`,b.id FROM (SELECT a.id,a.`name` city FROM school_categories a WHERE a.category_type = 0) b 
LEFT JOIN train_schools  c ON b.id = c.school_area_id  WHERE b.`city` LIKE '" . $city . "%'   GROUP BY b.`city`,b.id ";
                $data = $categoryModel->getSearch($where);
                if ($data == []) {
                    throw new \Exception('没有此城市的学校信息', 1501);
                }
                return $this->JsonReturnTrue($data);
            }
        } catch (\Exception $e) {
            return $this->JsonReturnFalse($e->getCode(), $e->getMessage());
        }
    }

    //推送数据
    public function getTs()
    {
        try{
            $input = Request::all();
            $city = $input['cityid'];
            $cityid = $city == '' ? 0 : $city;
            if ($cityid == 0) {
                $where = "SELECT a.id, a.`name`,a.plan_push_nums,a.count_push_nums,a.prev_push_nums,date_format(a.push_time,'%Y-%m-%d') push_time,a.logo,a.school_area_id ,a.logo FROM train_schools a ";
                $status = "SELECT COUNT(b.`name`) count,b.`name` FROM applies a  LEFT JOIN train_schools b ON a.train_school_id = b.id WHERE a.source = 2 AND a.`status` = 3  GROUP BY b.`name`";
                $data = $this->getTsAll($where, $status);

                return $this->JsonReturnTrue($data);
            } else {
                $where = "SELECT a.id, a.`name`,a.plan_push_nums,a.count_push_nums,a.prev_push_nums,date_format(a.push_time,'%Y-%m-%d') push_time,a.logo,a.school_area_id ,a.logo FROM train_schools a where a.school_area_id = " . $cityid;
                $status = "SELECT COUNT(b.`name`) count,b.`name` FROM applies a  LEFT JOIN train_schools b ON a.train_school_id = b.id WHERE a.source = 2 AND a.`status` = 3 AND b.school_area_id = " . $cityid . " GROUP BY b.`name`";
                $data = $this->getTsAll($where, $status);
                return $this->JsonReturnTrue($data);
            }
        }catch (\Exception $e){
            return $this->JsonReturnFalse($e->getCode(),$e->getMessage());
        }

    }


    public function getTsAll($where, $status)
    {
        try{
            $applies = new \App\Models\Applies();
            $data = $applies->getInformation($where);
            $statu = $applies->getSchoolStudent($status);
            foreach ($data as $k => $value) {
                $data[$k]->count = 0;
                foreach ($statu as $values) {
                    if ($values->name == $value->name) {
                        $data[$k]->count = $values->count;
                    }
                }
            }
            return $this->JsonReturnTrue($data);
        }catch (\Exception $e){
            return $this->JsonReturnFalse($e->getCode(),$e->getMessage());
        }

    }
    //推送页文件上传
    public function upload(Request $request)
    {
        try {
            $input = Request::all();
            $schoolid =  request()->route( 'schoolid');
            $cityid =  request()->route( 'cityid');
                date_default_timezone_set("PRC");
                require_once '../excel/PHPExcel/Classes/PHPExcel.php';
                require_once '../excel/PHPExcel/Classes/PHPExcel/IOFactory.php';
                require_once '../excel/PHPExcel/Classes/PHPExcel/Reader/Excel5.php';
                $objReader = \PHPExcel_IOFactory::createReader('Excel5');//use excel2007 for 2007 format
                //接收存在缓存中的excel表格
                $objPHPExcel = $objReader->load($input['file']); //$filename可以是上传的表格，或者是指定的表格
                $sheet = $objPHPExcel->getSheet(0);
                $highestRow = $sheet->getHighestRow(); // 取得总行数
                $highestColumn = $sheet->getHighestColumn();
                $applies = new \App\Models\Applies();
                for ($j = 2; $j <= $highestRow; $j++)               //从第二行开始读取数据
                {
                    $str = '(';
                    $strValue = '';
                    for ($k = 'A'; $k <= $highestColumn; $k++)      //从A列读取数据
                    {
                        //读取单元格
                        $strValue .= "'" . $objPHPExcel->getActiveSheet()->getCell("$k$j")->getValue() . "'" . ',';
                    }
                    $strValue = substr($strValue, 0, -1);
                    $result = time() + $j;
                    $status = 6;
                    $source = 2;
                    $str .= $strValue . ','. $status . ','. $source . ',' . $result . ',' . $schoolid . ','.$cityid.'),';
                    $str = substr($str, 0, -1);
                    $data = $applies->TsAdd($str);
                }
                if ($data){
                    $schoolModel = new \App\Models\School();
                    $num = $schoolModel->getCount($schoolid);
                    $nums = $num[0] + $highestRow - 1;
                    $lastCount = $highestRow - 1;
                    $where = "update train_schools set `count_push_nums` =  ".$nums.",`prev_push_nums` = ".$lastCount." where `id` = ".$schoolid;
                    $schoolModel->countData($where);
                    return $this->JsonReturnTrue($data);
                } else {
                    throw new \Exception('导入失败', 1170);
                }
        } catch (\Exception $e) {
            return $this->JsonReturnFalse($e->getCode(), $e->getMessage());
        }
    }
    public function updatePlanCount(){
        try {
            $input = Request::all();
            $data = $input['data'];
            $schoolid = $input['schoolid'];
            $where = "update train_schools set plan_push_nums = ".$data." where id = ".$schoolid;
            $schoolModel = new \App\Models\School();
            $re = $schoolModel->updatecount($where);
            return $this->JsonReturnTrue($re);
        } catch (\Exception $e){
            return $this->JsonReturnFalse($e->getCode(),$e->getMessage());
        }

    }
}
