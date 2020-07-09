window.onload=function(){
    id=$("#id").html();

    $("#yes1").click(function(){
        window.location.href="/index.php/Index/fl/fl_liucheng?id=" + id +"&option=1"
    })

    $("#no1").click(function(){
        window.location.href="/index.php/Index/fl/fl_liucheng?id=" + id +"&option=0"
    })
    
    $("#yes2").click(function(){

        wlfs=$("#wlfs").val()
        wlno=$("#wlno").val()
        wlprice=$("#wlprice").val()
        note=$("#note").val()

        window.location.href="/index.php/Index/fl/fl_liucheng?id=" + id +"&option=3&wlfs=" + wlfs + "&wlno=" + wlno + "&wlprice=" + wlprice + "&note=" + note
    })

    $("#no2").click(function(){
        window.location.href="/index.php/Index/fl/fl_liucheng?id=" + id +"&option=0"
    })

    $("#yes4").click(function(){
        window.location.href="/index.php/Index/fl/fl_liucheng?id=" + id +"&option=6"
    })

    $("#edit_YW").click(function(){
        wlfs=$("#wlfs").val()
        wlno=$("#wlno").val()
        wlprice=$("#wlprice").val()
        note=$("#note").val()

        window.location.href="/index.php/Index/fl/fl_liucheng.html?id=" + id +"&option=8&wlfs=" + wlfs + "&wlno=" + wlno + "&wlprice=" + wlprice + "&note=" + note
    })

    $(".agree_yzm").click(function(){

        phone=$("#phone").html()

        $.ajax({
            type:"get",
            async:true,
            url:"/index.php/Index/fl/fl_yzm?phone=" + phone,
            dataType:"json",
            success:function(result){
                $("#yzm").html(result)
                alert("发送成功！")
            }
        })
    })

    $("#submit_yzm").click(function(){
        user_yzm=$("#user_yzm").val();
        sys_yzm=$("#yzm").html();

        if(user_yzm !=sys_yzm){
            alert("验证码错误！")
        }else{
            $("#hd_submit_yzm").click()
        }
        })

        $("#backEdit").click(function(){
        window.location.href="/index.php/Index/fl/fl_liucheng?id=" + id +"&option=0"
    })


    //打印页面
    var printPage= function(){

        //页面打印缩放比例设置
        //document.getElementsByTagName('form').style.zoom=2;
        var xmlhttp;
        if(window.ActiveXObject){
            xmlhttp=new ActiveXObject('Microsoft.XMLHTTP');
        }else{
            xmlhttp=new XMLHttpRequest();
        }

        xmlhttp.open("GET","is_print?id=" + id +"&department={$department}",true);

        xmlhttp.onreadystatechange=function(){
            
            if(xmlhttp.readyState==4 && xmlhttp.status ==200){

                var msg=xmlhttp.responseText;
                if(msg==0){
                    //alert("打印订单！")
                }else{
                    //alert("打印失败！")                    
                }
            }
        }

        xmlhttp.send(null);

        window.print();

    }
}
