//window.onload=function(){
$(document).ready(function(){

    var leftBar_path=window.location.pathname;

    leftBar_path=leftBar_path.split("/");
    leftBar_path=leftBar_path.pop()

    if(leftBar_path == "" || leftBar_path == "/" || leftBar_path == "dataQuery.html" || leftBar_path == "myWork.html" || leftBar_path == "news_list.html" || leftBar_path == "news_line.html" || leftBar_path == "document_list.html"){

        $(".leftbarAll li").css("background-color","#160509");
        $(".leftbar0").css("background-color","darkslateblue");
        
        $(".leftbar0 i").css("color","#ffffff");

        $(".leftbar0 a").css("color","#ffffff");
        $(".leftbar1Z").toggle();
        $(".leftbar2Z").toggle();
        $(".leftbar5Z").toggle();
        $(".leftbar6Z").toggle();
        $(".leftbar7Z").toggle();
        $(".leftbar8Z").toggle();
        $(".leftbar9Z").toggle();
        $(".leftbar10Z").toggle();
        $(".leftbar11Z").toggle();
        $(".leftbar13Z").toggle();

        if(leftBar_path ==  "" || leftBar_path == "news_list.html" || leftBar_path == "news_line.html" || leftBar_path == "document_list.html"){
            $(".leftbar0Z1 a").css("color","#fff")
        }else if(leftBar_path == "myWork.html"){
            $(".leftbar0Z2 a").css("color","#fff")
        }else if(leftBar_path == "dataQuery.html"){
            $(".leftbar0Z3 a").css("color","#fff")
        }

    }else if(leftBar_path ==  "write_sx.html" || leftBar_path ==  "back_sx.html" || leftBar_path ==  "dsh_sx.html"  || leftBar_path ==  "dhk_sx.html" || leftBar_path ==  "ywc_sx.html" || leftBar_path ==  "sx_line.html" || leftBar_path ==  "template_sx.html"  || leftBar_path ==  "companyManger1_edit.php" || leftBar_path == "expire_sx.html"    || leftBar_path == "time_sx.html"|| leftBar_path == "sx_cw.html" || leftBar_path == "zf_sx.html" || leftBar_path ==  "sx_line2.html" || leftBar_path ==  "djLoad.html"){

        $(".leftbarAll li").css("background-color","#160509");
        $(".leftbar1").css("background-color","darkslateblue");
        $(".leftbar1 a").css("color","#ffffff");

        $(".leftbar1 i").css("color","#ffffff");

        $(".leftbar0Z").toggle();
        $(".leftbar2Z").toggle();
        $(".leftbar5Z").toggle();
        $(".leftbar6Z").toggle();
        $(".leftbar7Z").toggle();
        $(".leftbar8Z").toggle();
        $(".leftbar9Z").toggle();
        $(".leftbar10Z").toggle();
        $(".leftbar11Z").toggle();
        $(".leftbar13Z").toggle();

        if(leftBar_path ==  "dsh_sx.html" || leftBar_path ==  "sx_cw.html"){
            $(".leftbar1Z1 a").css("color","#fff")
        }else if(leftBar_path == "write_sx.html" || leftBar_path ==  "djLoad.html"){
            $(".leftbar1Z2 a").css("color","#fff")
        }else if(leftBar_path == "companyManger2.php"){
            $(".leftbar1Z3 a").css("color","#fff")
        }else if(leftBar_path ==  "dhk_sx.html" || leftBar_path ==  "expire_sx.html" || leftBar_path ==  "time_sx.html"){
            $(".leftbar1Z4 a").css("color","#fff")
        }else if(leftBar_path ==  "ywc_sx.html" || leftBar_path ==  "zf_sx.html"){
            $(".leftbar1Z5 a").css("color","#fff")
        }
        
    }else if(leftBar_path == "data.php" || leftBar_path == "form.php" || leftBar_path == "uploadInfo.php" || leftBar_path == "addDayData.php" || leftBar_path == "sumDayData.php" || leftBar_path == "powerPage.php"){
        
        $(".leftbarAll li").css("background-color","#160509");
        $(".leftbar2").css("background-color","darkslateblue");
        $(".leftbar2 a").css("color","#ffffff");

        $(".leftbar2 i").css("color","#ffffff");

        $(".leftbar0Z").toggle();
        $(".leftbar1Z").toggle();
        $(".leftbar5Z").toggle();
        $(".leftbar6Z").toggle();
        $(".leftbar7Z").toggle();
        $(".leftbar8Z").toggle();
        $(".leftbar9Z").toggle();
        $(".leftbar10Z").toggle();
        $(".leftbar11Z").toggle();
        $(".leftbar13Z").toggle();

        if(leftBar_path ==  "data.php"){
            $(".leftbar2Z1 a").css("color","#fff")
        }else if(leftBar_path == "form.php"){
            $(".leftbar2Z2 a").css("color","#fff")
        }else if(leftBar_path == "sumDayData.php"){
            $(".leftbar2Z3 a").css("color","#fff")
        }else if(leftBar_path == "powerPage.php"){
            $(".leftbar2Z4 a").css("color","#fff")
        }

    }else if(leftBar_path == "form.php"){
        $(".leftbarAll li").css("background-color","#160509");
        $(".leftbar3").css("background-color","darkslateblue");
        $(".leftbar3 a").css("color","#ffffff");
        
        $(".leftbar3 i").css("color","#ffffff");

    }else if(leftBar_path == "uploadInfo.php"){
        $(".leftbarAll li").css("background-color","#160509");
        $(".leftbar4").css("background-color","darkslateblue");
        $(".leftbar4 a").css("color","#ffffff");

        $(".leftbar4 i").css("color","#ffffff");

    }else if(leftBar_path == "my_center.html"){
        $(".leftbarAll li").css("background-color","#160509");
        $(".leftbar5").css("background-color","darkslateblue");
        $(".leftbar5 a").css("color","#ffffff");

        
        $(".leftbar5 i").css("color","#ffffff");

        $(".leftbar0Z").toggle();
        $(".leftbar1Z").toggle();
        $(".leftbar2Z").toggle();
        $(".leftbar6Z").toggle();
        $(".leftbar7Z").toggle();
        $(".leftbar8Z").toggle();
        $(".leftbar9Z").toggle();
        $(".leftbar10Z").toggle();
        $(".leftbar11Z").toggle();
        $(".leftbar13Z").toggle();

        if(leftBar_path ==  "my_center.html"){
            $(".leftbar5Z1 a").css("color","#fff")
        }
    }else if(leftBar_path == "flsq.html" || leftBar_path == "fl_line.html" ||  leftBar_path == "save_fl.html" || leftBar_path == "w_fl.html" ||  leftBar_path == "d_fl.html" ||  leftBar_path == "old_fl.html"  || leftBar_path == "old_flline.html" || leftBar_path == "query_amount.html" || leftBar_path == "fl_edit"){
        $(".leftbarAll li").css("background-color","#160509");
        $(".leftbar6").css("background-color","darkslateblue");
        $(".leftbar6 a").css("color","#ffffff");
        
        $(".leftbar6 i").css("color","#ffffff");
        $(".leftbar0Z").toggle();
        $(".leftbar1Z").toggle();
        $(".leftbar2Z").toggle();
        $(".leftbar5Z").toggle();
        $(".leftbar7Z").toggle();
        $(".leftbar8Z").toggle();
        $(".leftbar9Z").toggle();
        $(".leftbar10Z").toggle();
        $(".leftbar11Z").toggle();
        $(".leftbar13Z").toggle();

        if(leftBar_path ==  "flsq.html" ||  leftBar_path == "save_fl.html"){
            $(".leftbar6Z1 a").css("color","#fff")
        }else if(leftBar_path == "w_fl.html"){
            $(".leftbar6Z2 a").css("color","#fff")
        }else if(leftBar_path == "d_fl.html" || leftBar_path == "old_fl.html"  || leftBar_path == "old_flline.html"){
            $(".leftbar6Z3 a").css("color","#fff")
        }else if(leftBar_path == "query_amount.html"  || leftBar_path == "fl_edit"){
            $(".leftbar6Z4 a").css("color","#fff")
        }

    }else if(leftBar_path == "contract.html" || leftBar_path == "w_contract.html" || leftBar_path == "d_contract.html" || leftBar_path == "contract_line.html" || leftBar_path == "contract_query.php" || leftBar_path == "newSQ.php" || leftBar_path == "sq_line.php"  || leftBar_path == "w_sq.php"  || leftBar_path ==  "sqList.php"  || leftBar_path ==  "contractAddition.php" ||  leftBar_path == "contract_AddList.php" ||  leftBar_path == "w_contractAdd.php"){
        $(".leftbarAll li").css("background-color","#160509");
        $(".leftbar7").css("background-color","darkslateblue");
        $(".leftbar7 a").css("color","#ffffff");

        $(".leftbar7 i").css("color","#ffffff");

        $(".leftbar0Z").toggle();
        $(".leftbar1Z").toggle();
        $(".leftbar2Z").toggle();
        $(".leftbar5Z").toggle();
        $(".leftbar6Z").toggle();
        $(".leftbar8Z").toggle();
        $(".leftbar9Z").toggle();
        $(".leftbar10Z").toggle();
        $(".leftbar11Z").toggle();
        $(".leftbar13Z").toggle();

        if(leftBar_path ==  "d_contract.html" || leftBar_path ==  "sqList.php" ||  leftBar_path == "contract_AddList.php"){
            $(".leftbar7Z1 a").css("color","#fff")
        }else if(leftBar_path == "contract.html" || leftBar_path == "newSQ.php" || leftBar_path ==  "contractAddition.php"){
            $(".leftbar7Z2 a").css("color","#fff")
        }else if(leftBar_path == "w_contract.html" || leftBar_path == "w_sq.php" ||  leftBar_path == "w_contractAdd.php"){
            $(".leftbar7Z3 a").css("color","#fff")
        }

    }else if(leftBar_path == "add_device.html" || leftBar_path == "device_list.html"){
        $(".leftbarAll li").css("background-color","#160509");
        $(".leftbar8").css("background-color","darkslateblue");
        $(".leftbar8 a").css("color","#ffffff");

        $(".leftbar8 i").css("color","#ffffff");

        $(".leftbar0Z").toggle();
        $(".leftbar1Z").toggle();
        $(".leftbar2Z").toggle();
        $(".leftbar5Z").toggle();
        $(".leftbar6Z").toggle();
        $(".leftbar7Z").toggle();
        $(".leftbar9Z").toggle();
        $(".leftbar10Z").toggle();
        $(".leftbar11Z").toggle();
        $(".leftbar13Z").toggle();

        if(leftBar_path ==  "device_list.html"){
            $(".leftbar8Z1 a").css("color","#fff")
        }else if(leftBar_path == "add_device.html"){
            $(".leftbar8Z2 a").css("color","#fff")
        }
    }else if(leftBar_path ==  "add_meeting.html" || leftBar_path ==  "view_meeting.html"  || leftBar_path ==  "meeting_line.html"){
        $(".leftbar10 i").css("color","#ffffff");
        $(".leftbar10").css("background-color","darkslateblue");
        $(".leftbar10 a").css("color","#ffffff");
        
        $(".leftbar0Z").toggle();
        $(".leftbar1Z").toggle();
        $(".leftbar2Z").toggle();
        $(".leftbar5Z").toggle();
        $(".leftbar6Z").toggle();
        $(".leftbar7Z").toggle();
        $(".leftbar8Z").toggle();
        $(".leftbar9Z").toggle();
        $(".leftbar11Z").toggle();
        $(".leftbar13Z").toggle();

        if(leftBar_path ==  "view_meeting.html"){
            $(".leftbar10Z1 a").css("color","#fff")
        }else if(leftBar_path == "add_meeting.html"){
            $(".leftbar10Z2 a").css("color","#fff")
        }

    }else if(leftBar_path ==  "manage_store.html"  || leftBar_path ==  "store_daily.html"   || leftBar_path ==  "store_data2.html" || leftBar_path ==  "store_data_details1.html"   || leftBar_path ==  "closeStore.php" || leftBar_path == "store_data1.html" || leftBar_path ==  "store_qs.html" || leftBar_path ==  "storeQSLine.php"  || leftBar_path ==  "write_store_qs.html"   || leftBar_path ==  "store_data_details2.html" || leftBar_path ==  "store_opt.html" ){
        $(".leftbarAll li").css("background-color","#160509");
        $(".leftbar11").css("background-color","darkslateblue");
        $(".leftbar11 a").css("color","#ffffff");

        $(".leftbar11 i").css("color","#ffffff");


        $(".leftbar0Z").toggle();
        $(".leftbar1Z").toggle();
        $(".leftbar2Z").toggle();
        $(".leftbar5Z").toggle();
        $(".leftbar6Z").toggle();
        $(".leftbar7Z").toggle();
        $(".leftbar8Z").toggle();
        $(".leftbar9Z").toggle();
        $(".leftbar10Z").toggle();
        $(".leftbar13Z").toggle();

        if(leftBar_path == "manage_store.html" || leftBar_path == "store_daily.html"  || leftBar_path ==  "closeStore.php" || leftBar_path ==  "write_store_qs.html"  || leftBar_path ==  "store_opt.html"){
            $(".leftbar11Z2 a").css("color","#fff")
        }else if(leftBar_path == "store_data1.html" || leftBar_path == "store_data_details1.html" || leftBar_path == "store_data2.html"  || leftBar_path ==  "store_data_details2.html"){
            $(".leftbar11Z4 a").css("color","#fff")
        }else if(leftBar_path ==  "store_qs.html" || leftBar_path ==  "storeQSLine.php"){
            $(".leftbar11Z5 a").css("color","#fff")
        }
    }else if(leftBar_path == "manager_index.php"){
        $(".leftbarAll li").css("background-color","#160509");
        $(".leftbar12").css("background-color","darkslateblue");
        $(".leftbar12 a").css("color","#ffffff");

        $(".leftbar12 i").css("color","#ffffff");


        $(".leftbar0Z").toggle();
        $(".leftbar1Z").toggle();
        $(".leftbar2Z").toggle();
        $(".leftbar5Z").toggle();
        $(".leftbar6Z").toggle();
        $(".leftbar7Z").toggle();
        $(".leftbar8Z").toggle();
        $(".leftbar9Z").toggle();
        $(".leftbar10Z").toggle();
        $(".leftbar11Z").toggle();
        $(".leftbar13Z").toggle();
        
    }else if(leftBar_path == "clientList.html" || leftBar_path == "addClient.html" || leftBar_path == "client_line.html"  || leftBar_path == "edit_client.html"){
        $(".leftbarAll li").css("background-color","#160509");
        $(".leftbar13").css("background-color","darkslateblue");
        $(".leftbar13 a").css("color","#ffffff");
        
        $(".leftbar13 i").css("color","#ffffff");
        
        $(".leftbar0Z").toggle();
        $(".leftbar1Z").toggle();
        $(".leftbar2Z").toggle();
        $(".leftbar5Z").toggle();
        $(".leftbar6Z").toggle();
        $(".leftbar7Z").toggle();
        $(".leftbar8Z").toggle();
        $(".leftbar9Z").toggle();
        $(".leftbar10Z").toggle();
        $(".leftbar11Z").toggle();

        if(leftBar_path == "clientList.html"){
            $(".leftbar13Z2 a").css("color","#fff")
        }else if(leftBar_path == "addClient.html"){
            $(".leftbar13Z1 a").css("color","#fff")
        }
    }else{

        $(".leftbarAll li").css("background-color","#160509");
        $(".leftbar12").css("background-color","darkslateblue");
        $(".leftbar12 a").css("color","#ffffff");

        $(".leftbar12 i").css("color","#ffffff");
        
        $(".leftbar0Z").toggle();
        $(".leftbar1Z").toggle();
        $(".leftbar2Z").toggle();
        $(".leftbar5Z").toggle();
        $(".leftbar6Z").toggle();
        $(".leftbar7Z").toggle();
        $(".leftbar8Z").toggle();
        $(".leftbar9Z").toggle();
        $(".leftbar10Z").toggle();
        $(".leftbar11Z").toggle();
        $(".leftbar13Z").toggle();
    }

    $(".leftbar0").click(function(){
        $(".leftbar0Z").toggle();
    })

    $(".leftbar1").click(function(){
        $(".leftbar1Z").toggle();
    })

    $(".leftbar2").click(function(){
        $(".leftbar2Z").toggle();
    })

    $(".leftbar3").click(function(){
        $(".leftbar2Z").toggle();
    })

    $(".leftbar4").click(function(){
        $(".leftbar2Z").toggle();
    })



    $(".leftbar5").click(function(){
        $(".leftbar5Z").toggle();
    })

    $(".leftbar6").click(function(){
        $(".leftbar6Z").toggle();
    })

    $(".leftbar7").click(function(){
        $(".leftbar7Z").toggle();
    })

    $(".leftbar8").click(function(){
        $(".leftbar8Z").toggle();
    })

    $(".leftbar9").click(function(){
        $(".leftbar9Z").toggle();
    })


    $(".leftbar10").click(function(){
        $(".leftbar10Z").toggle();
    })

    $(".leftbar11").click(function(){
        $(".leftbar11Z").toggle();
    })

    $(".leftbar13").click(function(){
        $(".leftbar13Z").toggle();
    })


    $("#logout").click(function(){
        window.location.href="formHandle/account/logoutHandle.php"; 
    })

    $("#center").click(function(){
        window.location.href="center.php";
    })
    
    var menu=[{"我的门户":[{"href":"index.php","name":"我的主页"},{"href":"#","name":"数据查询"},{"href":"#","name":"数据录入"}]},{"公司授信":[{"href":"companyManger1.php","name":"填写授信单"},{"href":"zhangmu.php","name":"待审核授信"},{"href":"zhangmu2.php","name":"待回款授信"},{"href":"zhangmu3.php","name":"已完成授信"},{"href":"timeSX.php","name":"到期授信单"},{"href":"ZFSX.php","name":"作废授信单"}]},{"辅料申请单":[{"href":"flsq.php","name":"新增辅料单"},{"href":"flList.php","name":"未完成辅料"},{"href":"flDone.php","name":"已完成辅料"},{"href":"oldflDone.php","name":"旧系统辅料"}]},{"店铺信息":[{"href":"manStore.php","name":"店铺分配"},{"href":"uploadStore.php","name":"数据提交"},{"href":"dataStore.php","name":"店铺数据"}]},{"店铺合同":[{"href":"contract.php","name":"新增合同"},{"href":"w_contract.php","name":"待归档合同"},{"href":"contractList.php","name":"已归档合同"}]},{"电脑设备":[{"href":"itList.php","name":"设备列表"},{"href":"it.php","name":"新增设备"}]},{"数据统计":[{"href":"data.php?month=","name":"当月数据报表"},{"href":"form.php"},{"name":"日数据报表"},{"href":"sumDayData.php","name":"合计数据报表"},{"href":"powerPage.php","name":"BI可视化报表"}]},{"订会议室":[{"href":"viewMeeting.php","name":"查看会议"}]},{"个人中心":[{"href":"center.php","name":"我的资料"}]},{"文件下载":[{"href":"document.php","name":"文件下载"}]}]
    
    for(i=0;i<menu.length;i++){
        for (key in menu[i]){
            //console.log(key)

            for(j=0;j<menu[i][key].length;j++){
                for (key2 in menu[i][key][j]){
                    //console.log(menu[i][key][j][key2])
                }
            }
        }
    }

})


    