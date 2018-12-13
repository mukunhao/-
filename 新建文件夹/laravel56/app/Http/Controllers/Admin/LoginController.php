<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Request;
use Excel;

class LoginController extends Controller
{
    //登录
    public function login()
    {
        try {
            $input = Request::all();
            if (!isset($input['name'])) {
                throw new \Exception('Missing the required parameter $name when calling usersLoginPost', 182);
            }
            $name = $input['name'];
            $name = trim($name);//那边登录 可能会多加空格导致 登录失败
            if (!isset($input['password'])) {
                throw new \Exception('Missing the required parameter $password when calling usersLoginPost', 183);
            }
            $password = $input['password'];
            $userModel = new \App\Models\Users();
            $u = $userModel->getAll($name);
            if (!$u) {
                throw new \Exception('登录账号不存在，请检查.', 1101);
            }
            $storePwd = ic_getSaltPwd($password);
            if ($storePwd != $u[0]['password']) {
                throw new \Exception('登录密码不正确，请检查.', 1103);
            }
//            unset($u[0]['password']);
//            //将用户信息保存到session
//            \Session::put('login_user_info', $u[0]);
//            \Session::save();
            return $this->jsonReturnTrue($u[0]);
        } catch
        (\Exception $e) {
            return $this->jsonReturnFalse($e->getCode(), $e->getMessage());
        }
    }

    //退出
    public function export(){
        $cellData = [
            ['学号','姓名','成绩'],
            ['10001','AAAAA','99'],
            ['10002','BBBBB','92'],
            ['10003','CCCCC','95'],
            ['10004','DDDDD','89'],
            ['10005','EEEEE','96'],
        ];
       return  Excel::create('学生成绩',function($excel) use ($cellData){
            $excel->sheet('score', function($sheet) use ($cellData){
                $sheet->rows($cellData);
            });
        })->export('xls');
    }

    public function index()
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

    public function shangchuan()
    {
        return view('index');
    }

    public function sc()
    {
        $filename = $_FILES['fileName'];
        dd($filename);
        try {
            if (empty($_POST['fileName'])) {
                throw new \Exception('请选择你要上传的文件', 1171);
            }
            //限制上传表格类型
            $file_type = $_FILES['fileName']['type'];
//application/vnd.ms-excel  为xls文件类型
            if ($file_type != 'application/vnd.ms-excel') {
                throw new \Exception('请选择正确的文件类型', 1164);
            }
            if (is_uploaded_file($_FILES['fileName']['tmp_name'])) {
                $input = Request::all();
                $schoolid = $input['schoolid'];
                date_default_timezone_set("PRC");
                require_once '../excel/PHPExcel/Classes/PHPExcel.php';
                require_once '../excel/PHPExcel/Classes/PHPExcel/IOFactory.php';
                require_once '../excel/PHPExcel/Classes/PHPExcel/Reader/Excel5.php';
                $objReader = \PHPExcel_IOFactory::createReader('Excel5');//use excel2007 for 2007 format
                //接收存在缓存中的excel表格
                $filename = $_FILES['fileName']['tmp_name'];
                $objPHPExcel = $objReader->load($filename); //$filename可以是上传的表格，或者是指定的表格
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
                    $str .= $strValue . ',' . $result . ',' . $schoolid . '),';
                    $str = substr($str, 0, -1);
                    $data = $applies->TsAdd($str);
                    if ($data){
                        $schoolModel = new \App\Models\School();
                        $num = $schoolModel->getCount($schoolid);
                        $nums = $num[0] + $highestRow - 1;
                        $lastCount = $highestRow - 1;
                        $where = "update train_schools set `count_push_nums` =  ".$nums.",`prev_push_nums` = ".$lastCount." where `id` = ".$schoolid;
                        $schoolModel->countData($where);
                    } else {
                        throw new \Exception('导入失败', 1170);
                    }
                }
            } else {
                throw new \Exception('导入失败', 1170);
            }

        } catch (\Exception $e) {
            return $this->JsonReturnFalse($e->getCode(), $e->getMessage());
        }
    }
    public function dc(){
        require_once '../excel/PHPExcel/Classes/PHPExcel.php';
        require_once '../excel/PHPExcel/Classes/PHPExcel/IOFactory.php';
        require_once '../excel/PHPExcel/Classes/PHPExcel/Reader/Excel5.php';
        $phpexcel = new \PHPExcel();
        $phpexcel->getActiveSheet()->setTitle('毅创科技 提示技术支持');
        $phpexcel->getActiveSheet() ->setCellValue('A1','餐证字')
            ->setCellValue('B1','单位名称')
            ->setCellValue('C1','法定代表人')
            ->setCellValue('D1','城市')
            ->setCellValue('E1','地区')
            ->setCellValue('F1','地址')
            ->setCellValue('G1','类别')
            ->setCellValue('H1','备注(经营范围)')
            ->setCellValue('I1','发证机关')
            ->setCellValue('J1','起始日期')
            ->setCellValue('K1','终止日期')
            ->setCellValue('L1','食品安全管理人')
            ->setCellValue('M1','是否执证')
            ->setCellValue('N1','发证日期')
            ->setCellValue('O1','联系电话')
            ->setCellValue('P1','使用面积')
            ->setCellValue('Q1','从业人员数')
            ->setCellValue('R1','变更情况')
            ->setCellValue('S1','持证情况')
            ->setCellValue('T1','所属监管科室');
//从数据库取得需要导出的数据
        $i = 2;
        $phpexcel->getActiveSheet() ->setCellValue('A'.$i,'吉'.'1')
            ->setCellValue('B'.$i,1 )
            ->setCellValue('C'.$i,1 )
            ->setCellValue('D'.$i,1 )
            ->setCellValue('E'.$i,1 );
        $obj_Writer = \PHPExcel_IOFactory::createWriter($phpexcel,'Excel5');
        $filename ='Export'. date('Y-m-d').".xls";//文件名
        header("Content-Type: application/force-download");
        header("Content-Type: application/octet-stream");
        header("Content-Type: application/download");
        header('Content-Disposition:inline;filename="'.$filename.'"');
        header("Content-Transfer-Encoding: binary");
        header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
        header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
        header("Pragma: no-cache");
        $obj_Writer->save('php://output');

}
}
