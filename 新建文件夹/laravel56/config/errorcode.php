<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/11/13
 * Time: 16:41
 */
return [

    /*
    |--------------------------------------------------------------------------
    | customized http code
    |--------------------------------------------------------------------------
    |
    | The first number is error type, the second and third number is
    | product type, and it is a specific error code from fourth to
    | sixth.But the success is different.
    |
    */

    'code' => [
        200 => '成功',
        201 => '你需要更新',
        200001 => '缺少必要的参数',

        //文章
        503001 => '上传文件的格式不正确',
        503002 => '同步成功-记录保存失败',
        503003 => '权限错误',
        503004 => '文章保存失败',
        403017 => '临近定时时间不能取消发送任务',
        403018 => '临近定时时间不能修改发送任务',
        403019 => '超过发送时间不能发送',
        403020 => '缺少发表记录ID参数',
        //SMS
        416001 => '添加成功,审核中,请耐心等待',
        416002 => '签名添加失败',
    ]
];