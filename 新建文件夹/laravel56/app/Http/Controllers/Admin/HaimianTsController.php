<?php

namespace App\Http\Controllers\Admin;

//use Illuminate\Http\Request;
use Illuminate\Support\Facades\Request;
use App\Http\Controllers\Controller;
use Excel;
use think\Exception;

class HaimianTsController extends Controller
{
    public function getHmSchool()
    {
        try {
            $input = Request::all();
            $school = $input['school'];
            $schoolModel = new \App\Models\School();
            if ($school == '') {
                $where = "SELECT c.id,c.abbreviation,COUNT(d.abbreviation) count,c.school_area_id school_area_id FROM train_schools c LEFT JOIN (
                              SELECT b.train_school_id train_school_id,a.abbreviation FROM train_schools a LEFT JOIN applies  b on
                              a.id = b.train_school_id WHERE b.source = 2) d ON c.id = d.train_school_id  GROUP BY c.abbreviation,c.id ORDER BY COUNT(d.abbreviation) DESC ";
                $datas = $schoolModel->getSearch($where);
                $nums = 0;
                foreach ($datas as $value) {
                    $nums += $value->count;
                }
                $data['data'] = $datas;
                $data['nums'] = $nums;
                return $this->JsonReturnTrue($data);
            } else {
                $where = "SELECT c.id,c.abbreviation,COUNT(d.abbreviation) count ,c.school_area_id school_area_id FROM train_schools c LEFT JOIN (
                              SELECT b.train_school_id train_school_id,a.abbreviation FROM train_schools a LEFT JOIN applies  b on
                              a.id = b.train_school_id WHERE b.source = 2) d ON c.id = d.train_school_id where  c.abbreviation LIKE '%" . $school . "%' GROUP BY c.abbreviation,c.id ORDER BY COUNT(d.abbreviation) DESC";
                $datas = $schoolModel->getSearch($where);
                $data['data'] = $datas;
                return $this->JsonReturnTrue($data);
            }
        } catch (\Exception $e) {
            return $this->JsonReturnFalse($e->getCode(), $e->getMessage());
        }
    }


    //首页
    public function push()
    {
        $input = Request::all();
        $schoolid = $input['schoolid'];
        if ($schoolid == 0) {
            $wheres = " WHERE a.source = 2 AND a.status != 5 AND a.status != 6";
            $data = $this->getMsfStudent($wheres);
            foreach ($data['data'] as $k => $value) {
                $data['data'][$k]->statu = '';
                if ($value->status == 1) {
                    $data['data'][$k]->statu = '未处理';
                } elseif ($value->status == 2) {
                    $data['data'][$k]->statu = '已查看电话号码';
                } elseif ($value->status == 3) {
                    $data['data'][$k]->statu = '已入学';
                } elseif ($value->status == 4) {
                    $data['data'][$k]->statu = '未入学';
                }
            }
            return $this->JsonReturnTrue($data);
        } else {
            $wheres = " WHERE a.source = 2 AND a.status != 5 AND a.status != 6 AND a.train_school_id = " . $schoolid;
            $data = $this->getMsfStudent($wheres);
            foreach ($data['data'] as $k => $value) {
                $data['data'][$k]->statu = '';
                if ($value->status == 1) {
                    $data['data'][$k]->statu = '未处理';
                } elseif ($value->status == 2) {
                    $data['data'][$k]->statu = '已查看电话号码';
                } elseif ($value->status == 3) {
                    $data['data'][$k]->statu = '已入学';
                } elseif ($value->status == 4) {
                    $data['data'][$k]->statu = '未入学';
                }
            }
            return $this->JsonReturnTrue($data);
        }
    }

    public function noPush()
    {
        $input = Request::all();
        $schoolid = $input['schoolid'];
        if ($schoolid == 0) {
            $wheres = " WHERE a.source = 2  AND a.status = 6";
            $data = $this->getMsfStudent($wheres);
            foreach ($data['data'] as $k => $value) {
                $data['data'][$k]->statu = '';
                if ($value->status == 6) {
                    $data['data'][$k]->statu = '暂未推送';
                }
            }
            return $this->JsonReturnTrue($data);
        } else {
            $wheres = " WHERE a.source = 2  AND a.status = 6 AND a.train_school_id = " . $schoolid;
            $data = $this->getMsfStudent($wheres);
            foreach ($data['data'] as $k => $value) {
                $data['data'][$k]->statu = '';
                if ($value->status == 6) {
                    $data['data'][$k]->statu = '暂未推送';
                }
            }
            return $this->JsonReturnTrue($data);
        }
    }

    public function huishou()
    {
        $input = Request::all();
        $schoolid = $input['schoolid'];
        if ($schoolid == 0) {
            $wheres = " WHERE a.source = 2  AND a.status = 5";
            $data = $this->getMsfStudent($wheres);
            foreach ($data['data'] as $k => $value) {
                $data['data'][$k]->statu = '';
                if ($value->status == 5) {
                    $data['data'][$k]->statu = '已回收';
                }
            }
            return $this->JsonReturnTrue($data);
        } else {
            $wheres = " WHERE a.source = 2  AND a.status = 5 AND a.train_school_id = " . $schoolid;
            $data = $this->getMsfStudent($wheres);
            foreach ($data['data'] as $k => $value) {
                $data['data'][$k]->statu = '';
                if ($value->status == 5) {
                    $data['data'][$k]->statu = '已回收';
                }
            }
            return $this->JsonReturnTrue($data);
        }
    }


    public function getMsfStudent($wheres)
    {
        $studentModel = new \App\Models\Applies();
        $data = $studentModel->getTs($wheres);
        return $data;
    }

    //批量导出
    public function export()
    {
        $input = Request::all();
        $data = $input['date_'];
        $data = json_encode($data, true);
        $data = json_decode($data, true);
        $wheres = " WHERE a.id in (" . $data . ')';
        $re = $this->getMsfStudent($wheres);
        foreach ($re['data'] as $k => $value) {
            if ($value->status == 1) {
                $re['data'][$k]->status = '已推送';
            } elseif ($value->status == 2) {
                $re['data'][$k]->status = '已查看电话号码';
            } elseif ($value->status == 3) {
                $re['data'][$k]->status = '已入学';
            } elseif ($value->status == 4) {
                $re['data'][$k]->status = '未入学';
            } elseif ($value->status == 5) {
                $re['data'][$k]->status = '已回收';
            } elseif ($value->status == 6) {
                $re['data'][$k]->status = '暂未推送';
            }
        }

        $re = json_encode($re['data'], true);
        $re = json_decode($re, true);
        Array_unshift($re, ['编号', '省份', '联系号码', '状态', '推送时间', '跟进记录', 'id']);
        Excel::create('学生成绩', function ($excel) use ($re) {
            $excel->sheet('score', function ($sheet) use ($re) {
                $sheet->rows($re);
                $sheet->setWidth(array('A' => 15, 'B' => 10, 'C' => 15, 'D' => 10, 'E' => 25, 'F' => 20, 'G' => 5));
            });
        })->export('xls');
    }

    //全部导出
    public function exportData()
    {
        $input = Request::all();
        $schoolid = $input['schoolid_'];
        $status = $input['status_'];
        $wheres = $schoolid == 0 ? " WHERE  a.source = 2 and " . $status : " WHERE a.source = 2 and c.id  = " . $schoolid . " AND  " . $status;
//        $wheres = " WHERE c.id  = ".$schoolid . " AND a.status = ".$status ;
        $re = $this->getMsfStudent($wheres);
        foreach ($re['data'] as $k => $value) {
            if ($value->status == 1) {
                $re['data'][$k]->status = '已推送';
            } elseif ($value->status == 2) {
                $re['data'][$k]->status = '已查看电话号码';
            } elseif ($value->status == 3) {
                $re['data'][$k]->status = '已入学';
            } elseif ($value->status == 4) {
                $re['data'][$k]->status = '未入学';
            } elseif ($value->status == 5) {
                $re['data'][$k]->status = '已回收';
            } elseif ($value->status == 6) {
                $re['data'][$k]->status = '暂未推送';
            }
        }

        $re = json_encode($re['data'], true);
        $re = json_decode($re, true);
        Array_unshift($re, ['编号', '省份', '联系号码', '状态', '推送时间', '跟进记录', 'id']);
        Excel::create('学生成绩', function ($excel) use ($re) {
            $excel->sheet('score', function ($sheet) use ($re) {
                $sheet->rows($re);
                $sheet->setWidth(array('A' => 15, 'B' => 10, 'C' => 15, 'D' => 10, 'E' => 25, 'F' => 20, 'G' => 5));
            });
        })->export('xls');
    }

    //推送学生
    public function HaimianPush()
    {
        try {
            $input = Request::all();
            $studentid = $input['studentid_'];
            $schoolid = $input['schoolid'];
            if ($schoolid == 0) {
                throw new \Exception('请选择画室', 211);
            }
                $appliesModel = new \App\Models\Applies();
                $re = $appliesModel->setStudent($schoolid, $studentid);
                return $this->JsonReturnTrue($re);
        } catch (\Exception $e) {
            return $this->JsonReturnFalse($e->getCode(), $e->getMessage());
        }
    }
    public function HmDelete(){
        try{
            $input = Request::all();
            $studentid = $input['studentid_'];
            $appliesModel = new \App\Models\Applies();
            $re = $appliesModel->Hmdel($studentid);
            return $this->JsonReturnTrue($re);
        }catch (\Exception $e){
            return $this->JsonReturnFalse($e->getCode(),$e->getMessage());
        }
    }
}
