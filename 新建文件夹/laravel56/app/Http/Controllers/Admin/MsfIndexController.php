<?php

namespace App\Http\Controllers\Admin;

//use Illuminate\Http\Request;
use Illuminate\Support\Facades\Request;
use App\Http\Controllers\Controller;
use Excel;
use think\db\exception\DataNotFoundException;

class MsfIndexController extends Controller
{
    //获取报名的数据
    public function getStudentNo(){
        $appModel = new \App\Models\Applies();
        $re = $appModel->getStudentN();
        $re['time'] = strtotime($re['date'][0]->created_at);
        return $this->JsonReturnTrue($re);
    }
    //获取客户信息
    public function getSchool()
    {
        try {
            $input = Request::all();
            $school = $input['school'];
            $schoolModel = new \App\Models\School();
            if ($school == '') {
                $where = "SELECT c.id,c.abbreviation,COUNT(d.abbreviation) count FROM train_schools c LEFT JOIN (
                              SELECT b.train_school_id train_school_id,a.abbreviation FROM train_schools a LEFT JOIN applies  b on
                              a.id = b.train_school_id WHERE b.source = 1) d ON c.id = d.train_school_id  GROUP BY c.abbreviation,c.id ORDER BY COUNT(d.abbreviation) DESC";
                $datas = $schoolModel->getSearch($where);
                $nums = 0;
                foreach ($datas as $value){
                    $nums += $value->count;
                }
                $data['data'] = $datas;
                $data['nums'] = $nums;
                return $this->JsonReturnTrue($data);
            } else {
                $where = "SELECT c.id,c.abbreviation,COUNT(d.abbreviation) count FROM train_schools c LEFT JOIN (
                              SELECT b.train_school_id train_school_id,a.abbreviation FROM train_schools a LEFT JOIN applies  b on
                              a.id = b.train_school_id WHERE b.source = 1) d ON c.id = d.train_school_id where  c.abbreviation LIKE '" . $school . "%' GROUP BY c.abbreviation,c.id ORDER BY COUNT(d.abbreviation) DESC";
                $datas = $schoolModel->getSearch($where);
                $data['data'] = $datas;
                return $this->JsonReturnTrue($data);
            }
        } catch (\Exception $e) {
            return $this->JsonReturnFalse($e->getCode(), $e->getMessage());
        }
    }

    //列表页
    public function index()
    {
        try {
             $input = Request::all();
             $schoolid = $input['schoolid'];
            if ($schoolid == 0) {
                $where = " WHERE a.source = 1 AND a.status != 5";
                $data = $this->getMsfStudent($where);
                foreach ($data['data'] as $k=>$value){
                    $data['data'][$k]->statu = '';
                    if ($value->status == 1){
                        $data['data'][$k]->statu = '未处理';
                    }elseif ($value->status == 2){
                        $data['data'][$k]->statu = '已查看电话号码';
                    }elseif ($value->status == 3){
                        $data['data'][$k]->statu = '已入学';
                    }
                }
                return $this->JsonReturnTrue($data);
            } else {
                $where = " WHERE a.source = 1 AND a.status != 5 AND a.train_school_id = " . $schoolid;
                $data = $this->getMsfStudent($where);
                foreach ($data['data'] as $k=>$value){
                    $data['data'][$k]->statu = '';
                    if ($value->status == 1){
                        $data['data'][$k]->statu = '未处理';
                    }elseif ($value->status == 2){
                        $data['data'][$k]->statu = '已查看电话号码';
                    }elseif ($value->status == 3){
                        $data['data'][$k]->statu = '已入学';
                    }
                }
                return $this->JsonReturnTrue($data);
            }
        } catch (\Exception $e) {
            return $this->JsonReturnFalse($e->getCode(), $e->getMessage());
        }

    }
    public function huishou()
    {
        try {
            $input = Request::all();
            $schoolid = $input['schoolid'];
            if ($schoolid == 0) {
                $where = " WHERE a.source = 1 AND a.status = 5";
                $data = $this->getMsfStudent($where);
                foreach ($data['data'] as $k=>$value){
                    $data['data'][$k]->statu = '';
                    if ($value->status == 5){
                        $data['data'][$k]->statu = '回收';
                    }
                }
                return $this->JsonReturnTrue($data);
            } else {
                $where = " WHERE a.source = 1 AND a.status = 5 AND a.train_school_id = " . $schoolid;
                $data = $this->getMsfStudent($where);
                foreach ($data['data'] as $k=>$value){
                    $data['data'][$k]->statu = '';
                    if ($value->status == 5){
                        $data['data'][$k]->statu = '回收';
                    }
                }
                return $this->JsonReturnTrue($data);
            }
        } catch (\Exception $e) {
            return $this->JsonReturnFalse($e->getCode(), $e->getMessage());
        }

    }

    public function getMsfStudent($where)
    {
        try {
            $studentModel = new \App\Models\Applies();
            $data= $studentModel->getAll($where);
            return $data;
        } catch (\Exception $e) {
            return $this->JsonReturnFalse($e->getCode(), $e->getMessage());
        }
    }
    //批量导出
    public function export()
    {
        $input = Request::all();
        $data = $input['date_'];
        $data = json_encode($data, true);
        $data = json_decode($data, true);
        $wheres = " WHERE a.id in (".$data.')';
        $re = $this->getMsfStudent($wheres);
        foreach ($re['data'] as $k=>$value){
            if ($value->status == 1){
                $re['data'][$k]->status = '已推送';
            }elseif ($value->status == 2){
                $re['data'][$k]->status = '已查看电话号码';
            }elseif ($value->status == 3){
                $re['data'][$k]->status = '已入学';
            }elseif ($value->status == 4){
                $re['data'][$k]->status = '未入学';
            }elseif ($value->status == 5){
                $re['data'][$k]->status = '已回收';
            }elseif ($value->status == 6){
                $re['data'][$k]->status = '暂未推送';
            }
        }

        $re = json_encode($re['data'], true);
        $re = json_decode($re, true);
        Array_unshift($re, ['编号', '省份','联系号码','状态','推送时间','报名客户','id']);
        Excel::create('学生成绩',function($excel) use ($re){
            $excel->sheet('score', function($sheet) use ($re){
                $sheet->rows($re);
                $sheet->setWidth(array( 'A' => 15 ,'B' => 10,'C' => 15,'D' => 10,'E' => 25,'F' => 20,'G' => 5));
            });
        })->export('xls');
    }
    //全部导出
    public function exportData()
    {
        $input = Request::all();
        $schoolid = $input['schoolid_'];
        $status = $input['status_'];
        if ($status = 0){
            $wheres = $schoolid == 0?" WHERE  a.source = 1 and a.status != 5":" WHERE  a.source = 1 and c.id  = ".$schoolid . " AND a.status != 5";
        }else{
            $wheres = $schoolid == 0?" WHERE   a.source = 1 and a.status != ".$status:" WHERE  a.source = 1 and c.id  = ".$schoolid . " AND a.status != ".$status;
        }

//        $wheres = " WHERE c.id  = ".$schoolid . " AND a.status = ".$status ;
        $re = $this->getMsfStudent($wheres);
        foreach ($re['data'] as $k=>$value){
            if ($value->status == 1){
                $re['data'][$k]->status = '已推送';
            }elseif ($value->status == 2){
                $re['data'][$k]->status = '已查看电话号码';
            }elseif ($value->status == 3){
                $re['data'][$k]->status = '已入学';
            }elseif ($value->status == 4){
                $re['data'][$k]->status = '未入学';
            }elseif ($value->status == 5){
                $re['data'][$k]->status = '已回收';
            }elseif ($value->status == 6){
                $re['data'][$k]->status = '暂未推送';
            }
        }

        $re = json_encode($re['data'], true);
        $re = json_decode($re, true);
        Array_unshift($re, ['编号', '省份','联系号码','状态','推送时间','报名客户','id']);
        Excel::create('学生成绩',function($excel) use ($re){
            $excel->sheet('score', function($sheet) use ($re){
                $sheet->rows($re);
                $sheet->setWidth(array( 'A' => 15 ,'B' => 10,'C' => 15,'D' => 10,'E' => 25,'F' => 20,'G' => 5));
            });
        })->export('xls');
    }

}
