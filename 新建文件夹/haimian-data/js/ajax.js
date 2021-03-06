let city = '';
var id = '';
//全部的数据
$(".fa-times-circle").click(function () {
    $(".nav_search_huashi").val('')
    $(".fa-times-circle").css("display", "none")
    circle()
})

function circle() {
    $.ajax({
        url: 'http://www.haimian.com/api/getsearch',
        dataType: 'JSON',
        type: 'POST',
        data: {
            "city": city
        },
        success: function (res) {
            let data = res.data.data;
            let $html = `<li class="nav-active" data-id=''>
                                    <a href="javascript:;">全部
                                                <span class="badge pull-right bg-danger">${res.data.nums}</span>
                                            </a>
                                </li>`;
            $.each(data, function (index, item) {
                $html += `<li data-id='${item.id}'>
                                    <a href="javascript:;">${item.city}
                                        <span class="badge pull-right bg-default">${item.count}</span>
                                    </a>
                                </li>`
                $('.nav-stacked').html($html)
            })
        }
    })
}

$(".update").click(function () {
    let data = $("#plancount").val()
    let schoolid = $("#plancount").attr("schoolid")
    $.ajax({
        url: 'http://www.haimian.com/api/updateplan',
        dataType: 'JSON',
        type: 'POST',
        data: {
            "data": data,
            "schoolid": schoolid
        },
        success: function (res) {

            if (res.code == 200) {
                tuisong()
            }
        }
    })
    $(this).attr("data-dismiss", "modal")
})
var storage = window.localStorage;
$(".news").click(function () {
    storage.time = (new Date()).getTime();
    console.log(storage["time"])
    $("#newStudent").html('')
    $(".news").hide()
})
$.ajax({
    url: 'http://www.haimian.com/api/getStudentNo',
    dataType: 'JSON',
    type: 'get',
    success: function (res) {
        console.log(storage["time"])
        if (res.data.time > storage["time"]) {
            if (res.data.count[0].count != 0) {
                $(".newStudentBm").html("你有" + res.data.count[0].count + "个新的报名数据")
                $(".newStudentSmall").html(res.data.date[0].created_at)
                $("#newStudent").html(1)
            }
        } else {
            $(".newStudentBm").html("暂无新报名数据")
        }
    }
})
$.ajax({
    url: 'http://www.haimian.com/api/getsearch',
    dataType: 'JSON',
    type: 'POST',
    data: {
        "city": city
    },
    success: function (res) {
        let data = res.data.data;
        let $html = `<li class="nav-active" data-id=''>
                                    <a href="javascript:;">全部
                                                <span class="badge pull-right bg-danger">${res.data.nums}</span>
                                            </a>
                                </li>`;
        $.each(data, function (index, item) {
            $html += `<li data-id='${item.id}'>
                                    <a href="javascript:;">${item.city}
                                        <span class="badge pull-right bg-default">${item.count}</span>
                                    </a>
                                </li>`
            $('.nav-stacked').html($html)
        })
    }
})
//数据
$(document).ready(function () {
    tuisong()
})

function tuisong() {
    $.ajax({
        url: 'http://www.haimian.com/api/getts',
        dataType: 'JSON',
        type: 'POST',
        data: {
            "cityid": city
        },
        success: function (res) {
            let data = res.data.original.data;
            let $html = '';


            $.each(data, function (index, item) {
                $html += `<div class="main-content-content" >
                    <div class="main-content-content-top">
                    <img src="http://7niu.meishufan.cn/uploads/train_school/logo/${item.id}/${item.logo}" >
                    <div>
                        <p><a href="mail.html? schoolid =${item.id}& cityid=${item.school_area_id}">${item.name}</a></p>
                        <span data-toggle="modal" data-target="#myModal" class="tuisong-sezhi " id="shezhi"  schoolid = '${item.id}'>设置</span>
                    </div>

                </div>
                <div class="main-content-content-bottom">
                    <div class="main-content-tongji-top main-content-tongji">
                        <span>${item.count_push_nums}/${item.plan_push_nums}</span>
                        <p><span>${item.count}</span>入学</p>
                    </div>
                    <div class="main-content-tongji">
                        <div class="main-content-tongji-bottom">
                            <p><span>${item.prev_push_nums}</span>条上次</p>
                            <p>${item.push_time}</p>
                        </div>
                        <!--<form  method="post" enctype="multipart/form-data" id="file" >-->
                         <div class="main-content-tuisong"><a href="javascript:;" data-toggle="toastr">导入<input type="file" name="fileName" class="fileExcel" cityid='${item.school_area_id}' schoolid = '${item.id}' data-index=${index}></a></div>
                        <!--</form>-->
                    </div>
                </div>
            </div>`
            })
            $('.main-content-content-box').html($html)
        }
    })
}

$(document).on("click", "#shezhi", function () {
    let schoolids = $(this).attr("schoolid")
    $("#plancount").attr("schoolid", schoolids)
})
var formData = '';
var name = ''
$(document).on("change", ".fileExcel", function () {
    formData = new FormData();
    name = $(this).val();
    formData.append("file", $(this)[0].files[0]);
    formData.append("name", name);
    let city_id = $(this).attr('cityid');
    let school_id = $(this).attr('schoolid');
    $.ajax({
        url: 'http://www.haimian.com/api/upload/' + city_id + '/' + school_id,
        dataType: 'JSON',
        type: 'POST',
        processData: false,
        contentType: false,
        data: formData,
        success: res => {
            if (res.code == 200) {
                name = ''
                formData = ''
                setTimeout(function () {
                    toastr.options.closeButton = true;
                    toastr.info('导入成功');
                }, 500);
                if (id == '') {
                    tuisong()
                } else {

                    $.ajax({
                        url: 'http://www.haimian.com/api/getts',
                        dataType: 'JSON',
                        type: 'POST',
                        data: {
                            "cityid": id
                        },
                        success: function (res) {
                            let data = res.data.original.data;
                            let $html = '';
                            let $htmls = ''
                            if (data == '') {
                                $htmls = `<div style='background:#eee;width:100%;text-align:center;line-height:50px;font-size:16px'>暂无数据</div>`
                                $('.main-content-content-box').html($htmls)
                            } else {
                                $.each(data, function (index, item) {
                                    $html += `<div class="main-content-content">
                        <div class="main-content-content-top">
                        <img src="http://7niu.meishufan.cn/uploads/train_school/logo/${item.id}/${item.logo}" alt="">
                        <div>
                            <p><a href="mail.html? schoolid =${item.id}& cityid=${item.school_area_id}">${item.name}</a></p>
                            <span data-toggle="modal" data-target="#myModal" class="tuisong-sezhi"  schoolid = '${item.id}' id="shezhi">设置</span>
                        </div>

                    </div>
                    <div class="main-content-content-bottom">
                        <div class="main-content-tongji-top main-content-tongji">
                            <span>${item.count_push_nums}/${item.plan_push_nums}</span>
                            <p><span>${item.count}</span>入学</p>
                        </div>
                        <div class="main-content-tongji">
                            <div class="main-content-tongji-bottom">
                                <p><span>${item.prev_push_nums}</span>条上次</p>
                                <p>${item.push_time}</p>
                            </div>
                            <form  method="post" enctype="multipart/form-data" id="file">
                         <div class="main-content-tuisong"><a href="javascript:;">导入<input type="file" name="fileName" class="fileExcel" cityid='${item.school_area_id}' schoolid = '${item.id}' ></a></div>
                        </form>
                           
                        </div>
                    </div>
                </div>`
                                })
                                $('.main-content-content-box').html($html)
                            }

                            //console.log(data)
                        }
                    })
                }
            } else {
                alert('导入失败')
            }
        }
    })
})
//点击
$(document).on("click", ".nav-pills li", function () {
    id = $(this).attr('data-id');
    $(this).addClass('nav-active').siblings('.nav-active').removeClass('nav-active')
    //console.log(id)
    $.ajax({
        url: 'http://www.haimian.com/api/getts',
        dataType: 'JSON',
        type: 'POST',
        data: {
            "cityid": id
        },
        success: function (res) {
            let data = res.data.original.data;
            let $html = '';
            let $htmls = ''
            if (data == '') {
                $htmls = `<div style='background:#eee;width:100%;text-align:center;line-height:50px;font-size:16px'>暂无数据</div>`
                $('.main-content-content-box').html($htmls)
            } else {
                $.each(data, function (index, item) {
                    $html += `<div class="main-content-content">
                        <div class="main-content-content-top">
                        <img src="http://7niu.meishufan.cn/uploads/train_school/logo/${item.id}/${item.logo}" alt="">
                        <div>
                            <p><a href="mail.html? schoolid =${item.id}& cityid=${item.school_area_id}">${item.name}</a></p>
                            <span data-toggle="modal" data-target="#myModal" class="tuisong-sezhi"  schoolid = '${item.id}' id="shezhi">设置</span>
                        </div>

                    </div>
                    <div class="main-content-content-bottom">
                        <div class="main-content-tongji-top main-content-tongji">
                            <span>${item.count_push_nums}/${item.plan_push_nums}</span>
                            <p><span>${item.count}</span>入学</p>
                        </div>
                        <div class="main-content-tongji">
                            <div class="main-content-tongji-bottom">
                                <p><span>${item.prev_push_nums}</span>条上次</p>
                                <p>${item.push_time}</p>
                            </div>
                            <form  method="post" enctype="multipart/form-data" id="file">
                         <div class="main-content-tuisong"><a href="javascript:;"  data-toggle="toastr">导入<input type="file" name="fileName" class="fileExcel" cityid='${item.school_area_id}' schoolid = '${item.id}' ></a></div>
                        </form>
                           
                        </div>
                    </div>
                </div>`
                })
                $('.main-content-content-box').html($html)
            }

            //console.log(data)
        }
    })
})

//搜索

$('.nav_search_huashi').on("input", function () {
    //console.log($('.nav_search_huashi').val())
    //let ctx = e.originalEvent.data
    let search = $('.nav_search_huashi').val()
    console.log(search)
    if (search != '') {
        $(".fa-times-circle").css("display", "block")
    } else {
        $(".fa-times-circle").css("display", "none")
    }
    $.ajax({
        url: 'http://www.haimian.com/api/getsearch',
        dataType: 'JSON',
        type: 'POST',
        data: {
            "city": search
        },
        success: res => {
            let data = res.data.data;
            let $html = ''
            // $html += `<li class="nav-active" data-id=''>
            //                         <a href="javascript:;">全部
            //                                     <span class="badge pull-right bg-danger">${res.data.nums}</span>
            //                                 </a>
            //                     </li>`;
            $.each(data, function (index, item) {
                $html += `<li data-id='${item.id}'>
                                    <a href="javascript:;">${item.city}
                                        <span class="badge pull-right bg-default">${item.count}</span>
                                    </a>
                                </li>`
                $('.nav-stacked').html($html)
            })
            // console.log(res.data)
        }
        // if (search == '') {
        //     console.log(res)
        //     return false
        // } else if (res.message == '没有此城市的学校信息' && search != '') {
        //     alert('暂无该城市信息')
        // } else if (res.message == "成功" && search != '') {
        //     let id = res.data[0].id
        //
        //     $.ajax({
        //         url: 'http://www.haimian.com/api/getts',
        //         dataType: 'JSON',
        //         type: 'POST',
        //         data: {
        //             "cityid": id
        //         },
        //         success: function(res) {
        //             let data = res.data.original.data;
        //             let $html = '';
        //             let $htmls = ''
        //             if (data == '') {
        //                 $htmls = `<div style='background:#eee;width:100%;text-align:center;line-height:50px;font-size:16px'>暂无数据</div>`
        //                 $('.main-content-content-box').html($htmls)
        //             } else {
        //                 $.each(data, function(index, item) {
        //                     $html += `<div class="main-content-content">
        //                     <div class="main-content-content-top">
        //                     <img src="./img/avatar.jpg" alt="">
        //                     <div>
        //                         <p><a href="mail.html">${item.name}</a></p>
        //                         <span data-toggle="modal" data-target="#myModal" class="tuisong-sezhi">设置</span>
        //                     </div>
        //
        //                 </div>
        //                 <div class="main-content-content-bottom">
        //                     <div class="main-content-tongji-top main-content-tongji">
        //                         <span>${item.plan_push_nums}/${item.count_push_nums}</span>
        //                         <p><span>${item.count}</span>入学</p>
        //                     </div>
        //                     <div class="main-content-tongji">
        //                         <div class="main-content-tongji-bottom">
        //                             <p><span>${item.prev_push_nums}</span>条上次</p>
        //                             <p>2018.12.01</p>
        //                         </div>
        //                          <form  method="post" enctype="multipart/form-data" id="file">
        //              <div class="main-content-tuisong"><a href="javascript:;">导入<input type="file" name="fileName" class="fileExcel" ></a></div>
        //             </form>
        //                     </div>
        //                 </div>
        //             </div>`
        //                 })
        //                 $('.main-content-content-box').html($html)
        //             }
        //
        //             //console.log(data)
        //         }
        //     })
        //
        //     //console.log(res.data[0].id)
        // }


    })


})