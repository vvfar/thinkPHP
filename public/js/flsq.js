$(document).ready(function(){

    var del=false
    var add=false
    var add_del=0;

    $(".flno").children().change(function(){
        fl=$(this).val()

        fl_price=0;

        $(this).parent().siblings(".dj").children().attr("value",function(){

            $.ajax({
                type:"get",
                async:false,
                url:"../../controller/fl/fldj.php?fl=" +encodeURIComponent(fl),
                //dataType:"json",
                success:function(result){
                    
                    if(result){
                        fl_price=result;   
                    }
                }
            })

            return fl_price
        })

        $(this).parent().siblings(".flfxj").html(
            parseFloat($(this).parent().siblings(".dj").children().val()*$(this).parent().siblings(".sl").children().val()).toFixed(2)
        )
        
    })

    $(".sqsl").children().blur(function(){
        $(this).parent().siblings(".fwfxj").html(
            parseFloat($(this).val()*$(this).parent().siblings(".bzjg").children().val()*$(this).parent().siblings(".fldj").children().val()).toFixed(2)
        )
    })

    $(".bzjg").children().blur(function(){
        $(this).parent().siblings(".fwfxj").html(
            parseFloat($(this).val()*$(this).parent().siblings(".sqsl").children().val()*$(this).parent().siblings(".fldj").children().val()).toFixed(2)
        )
    })

    $(".fldj").children().blur(function(){
        $(this).parent().siblings(".fwfxj").html(
            parseFloat($(this).val()*$(this).parent().siblings(".bzjg").children().val()*$(this).parent().siblings(".sqsl").children().val()).toFixed(2)
        )
    })

    $(".dj").children().blur(function(){
        $(this).parent().siblings(".flfxj").html(
            parseFloat($(this).val()*$(this).parent().siblings(".sl").children().val()).toFixed(2)
        )
    })

    $(".sl").children().blur(function(){
        $(this).parent().siblings(".flfxj").html(
            parseFloat($(this).val()*$(this).parent().siblings(".dj").children().val()).toFixed(2)
        )
    })

    $(".sqsl").children().blur(function(){
        sum=0
        $(".sqsl").children().each(function(){
            if($(this).val() != ""){
                sum=sum+parseFloat($(this).val())
            }  
        })
        $("#hj").html(sum)

        sum2=0
        $(".fwfxj").each(function(){
            if($(this).html() != NaN && $(this).html() !=""){
                sum2=sum2+parseFloat($(this).html())
            }  
        })
        $("#fwfhj").html("￥" + sum2.toFixed(2))
    })

    $(".bzjg").children().blur(function(){

        sum2=0
        $(".fwfxj").each(function(){
            if($(this).html() != NaN && $(this).html() !=""){
                sum2=sum2+parseFloat($(this).html())
            }  
        })
        $("#fwfhj").html("￥" + sum2.toFixed(2))
    })

    $(".fldj").children().blur(function(){

        sum2=0
        $(".fwfxj").each(function(){
            if($(this).html() != NaN && $(this).html() !=""){
                sum2=sum2+parseFloat($(this).html())
            }  
        })
        $("#fwfhj").html("￥" + sum2.toFixed(2))
    })

    $(".dj").children().blur(function(){
        sum=0
        $(".sl").each(function(){
            if($(this).children().val() != NaN && $(this).children().val() !=""){
                sum=sum+parseFloat($(this).children().val())
            }  

        })
        $("#flslhj").html(sum)
    })

    $(".sl").children().blur(function(){
        sum=0
        $(".sl").each(function(){
            if($(this).children().val() != NaN && $(this).children().val() !=""){
                sum=sum+parseFloat($(this).children().val())
            }  

        })
        $("#flslhj").html(sum)
    })

    $(".flno").children().change(function(){
        $(".sl").children().blur()
    })

    $(".dj").children().blur(function(){
        sum=0
        $(".flfxj").each(function(){
            if($(this).html() != NaN && $(this).html() !=""){
                sum=sum+parseFloat($(this).html())
            }  
        })
        $("#flfhj").html("￥" + sum.toFixed(2))
    })

    $(".sl").children().blur(function(){
        sum=0
        $(".flfxj").each(function(){
            if($(this).html() != NaN && $(this).html() !=""){
                sum=sum+parseFloat($(this).html())
            }  
        })
        $("#flfhj").html("￥" + sum.toFixed(2))
    })

    $(".sd").children().blur(function(){
        sum=0
        $(".flfxj").each(function(){
            if($(this).html() != NaN && $(this).html() !=""){
                sum=sum+parseFloat($(this).html())
            }  
        })
        $("#fwfhjhs").html("￥" + (sum*$(".sd").children().val()).toFixed(2))
    })

    $(".dj").children().blur(function(){
        sum=0
        $(".flfxj").each(function(){
            if($(this).html() != NaN && $(this).html() !=""){
                sum=sum+parseFloat($(this).html())
            }  
        })
        $("#fwfhjhs").html("￥" + (sum*$(".sd").children().val()).toFixed(2))
    })

    $(".sl").children().blur(function(){
        sum=0
        $(".flfxj").each(function(){
            if($(this).html() != NaN && $(this).html() !=""){
                sum=sum+parseFloat($(this).html())
            }  
        })
        $("#fwfhjhs").html("￥" + (sum*$(".sd").children().val()).toFixed(2))
    })

    $(".sqsl").children().blur(function(){
        sum=parseFloat($("#fwfhj").html().replace("￥",""))+parseFloat($("#fwfhjhs").html().replace("￥",""))
        $("#fwfflfhj").html("￥" + sum.toFixed(2))
    })

    $(".bzjg").children().blur(function(){
        sum=parseFloat($("#fwfhj").html().replace("￥",""))+parseFloat($("#fwfhjhs").html().replace("￥",""))
        $("#fwfflfhj").html("￥" + sum.toFixed(2))
    })

    $(".fldj").children().blur(function(){
        sum=parseFloat($("#fwfhj").html().replace("￥",""))+parseFloat($("#fwfhjhs").html().replace("￥",""))
        $("#fwfflfhj").html("￥" + sum.toFixed(2))
    })

    $(".dj").children().blur(function(){
        sum=parseFloat($("#fwfhj").html().replace("￥",""))+parseFloat($("#fwfhjhs").html().replace("￥",""))
        $("#fwfflfhj").html("￥" +sum.toFixed(2))
    })

    $(".sl").children().blur(function(){
        sum=parseFloat($("#fwfhj").html().replace("￥",""))+parseFloat($("#fwfhjhs").html().replace("￥",""))
        $("#fwfflfhj").html("￥" +sum.toFixed(2))
    })

    $(".sd").children().blur(function(){
        sum=parseFloat($("#fwfhj").html().replace("￥",""))+parseFloat($("#fwfhjhs").html().replace("￥",""))
        $("#fwfflfhj").html("￥" +sum.toFixed(2))
    })

    $("#addLine").click(function(){
        count=parseInt($("#count").html())

        $(".tr"+(count+1)).removeClass("hidden");

        count=count+1

        $("#count").html(count)

        add_del=1
    })
    
    $("#delLine").click(function(){
        
        count=parseInt($("#count").html())+1

        $(".tr"+(count)).addClass("hidden");

        count=count-1

        $("#count").html(count-1)

        add_del=-1
    })

    $("#addLine2").click(function(){
        count=parseInt($("#count").html())

        $(".tr"+(count+1)).removeClass("hidden");

        count=count+1

        $("#count").html(count)

        add=true
        
    })
    
    $("#delLine2").click(function(){
        
        count=parseInt($("#count").html())+1

        $(".tr"+(count)).addClass("hidden");

        count=count-1

        $("#count").html(count-1)

        del=true
        
    })


    $("#sxid").change(function(){
        var xmlhttp;
        if(window.ActiveXObject){
            xmlhttp=new ActiveXObject('Microsoft.XMLHTTP');
        }else{
            xmlhttp=new XMLHttpRequest();
        }

        sxid=$(this).val();

        xmlhttp.open("GET","../../controller/fl/searchSXMoney.php?sxid=" + sxid,true);

        xmlhttp.onreadystatechange=function(){

            if(xmlhttp.readyState==4 && xmlhttp.status ==200){

                var msg=xmlhttp.responseText;
                
                $("#newMoney").html(msg)

            }
        }

        xmlhttp.send(null);
    })

    

    $("#submit").click(function(){

        //隐藏表单赋值
        $("#hd_sqslhj").attr("value",function(){
            return $("#hj").html().replace("￥","");
        })

        $("#hd_fwfhj").attr("value",function(){
            return $("#fwfhj").html().replace("￥","");
        })

        $("#hd_flsl").attr("value",function(){
            return $("#flslhj").html();
        })

        $("#hd_flfhjsh").attr("value",function(){
            return $("#fwfhjhs").html().replace("￥","");
        })

        $("#hd_fwfflfzj").attr("value",function(){
            return $("#fwfflfhj").html().replace("￥","");
        })

        var path=window.location.search;

        if(path.indexOf("id")<0){

            if(add_del==-1){
                $("#hd_count").attr("value",function(){
                    return parseInt($("#count").html())+1;
                })
            }else if(add_del==1){
                $("#hd_count").attr("value",function(){
                    return $("#count").html();
                })
            }else{
                $("#hd_count").attr("value",function(){
                    return $("#count").html();
                })
            }
            
        }else{

            if($("#count").html()=="1"){
                if(del || (!del && !add)){
                    $("#hd_count").attr("value",function(){
                        return parseInt($("#count").html())+1;
                    })
                }else{
                    $("#hd_count").attr("value",function(){
                        return parseInt($("#count").html());
                    })
                }
                
            }else{
                if(add){
                    $("#hd_count").attr("value",function(){
                        return parseInt($("#count").html());
                    })
                }else{
                    
                    $("#hd_count").attr("value",function(){
                        return parseInt($("#count").html())+1;
                    })
                }   
            }

        }

        
        //表单内容需全部提交
        var isOk="True"

        if($("input[name='no']").val() =="" || $("input[name='company']").val() =="" ||
            $("input[name='people']").val() =="" || $("input[name='department']").val() =="" || 
            $("input[name='date']").val() =="" ||  $("input[name='address']").val() =="" || 
            $("input[name='connection']").val() =="" ||  $("input[name='phone']").val() =="" ||
            $("input[name='sd']").val() =="" || $("#driving").val() ==""){
            alert("请将表单填写完整！")
        }else if($(".sqdbh span").html()=="辅料订单编号重复，请修改！"){
            alert("辅料订单编号重复，请修改！")
        }else{
            if($("select[name='jkfs']").val() !="全现金" && $("select[name='jkfs']").val() !="标费补贴" && $("select[name='jkfs']").val() !="预付费领标" && $("select[name='jkfs']").val() !="其他" ){
                if($("select[name='sxid']").val() =="" || $("input[name='sxmoney']").val() ==""){
                    alert("请填写授信信息！")
                }else{
                    newMoney=parseInt($("#newMoney").html());
                    sxmoney=parseInt($("input[name='sxmoney']").val());

                    if(sxmoney<=newMoney){
                        $("#hd_submit").click()
                    }else{
                        alert("使用金额不能大于剩余授信可用额度！")
                    }
                }
            }else{
                if($("#newMoney").html() != ""){
                    newMoney=parseInt($("#newMoney").html());
                }else{
                    newMoney=""
                }
                
                if($("input[name='sxmoney']").val() != ""){
                    sxmoney=parseInt($("input[name='sxmoney']").val());
                }else{
                    sxmoney=""
                }

                if((newMoney =="" || newMoney ==0) && sxmoney ==""){
                    $("#hd_submit").click()
                }else{
                    if(sxmoney<=newMoney){
                        $("#hd_submit").click()
                    }else{
                        alert("使用金额不能大于剩余授信可用额度！")
                    }
                }
                
            }        
        }

    })

    $("#save").click(function(){
        //隐藏表单赋值
        $("#hd_sqslhj").attr("value",function(){
            return $("#hj").html().replace("￥","");
        })

        $("#hd_fwfhj").attr("value",function(){
            return $("#fwfhj").html().replace("￥","");
        })

        $("#hd_flsl").attr("value",function(){
            return $("#flslhj").html();
        })

        $("#hd_flfhjsh").attr("value",function(){
            return $("#fwfhjhs").html().replace("￥","");
        })

        $("#hd_fwfflfzj").attr("value",function(){
            return $("#fwfflfhj").html().replace("￥","");
        })

        var path=window.location.search;

        if(path.indexOf("id")<0){

            if(add_del==-1){
                $("#hd_count").attr("value",function(){
                    return parseInt($("#count").html())+1;
                })
            }else if(add_del==1){
                $("#hd_count").attr("value",function(){
                    return $("#count").html();
                })
            }else{
                $("#hd_count").attr("value",function(){
                    return $("#count").html();
                })
            }
            
        }else{

            if($("#count").html()=="1"){
                if(del || (!del && !add)){
                    $("#hd_count").attr("value",function(){
                        return parseInt($("#count").html())+1;
                    })
                }else{
                    $("#hd_count").attr("value",function(){
                        return parseInt($("#count").html());
                    })
                }
                
            }else{
                if(add){
                    $("#hd_count").attr("value",function(){
                        return parseInt($("#count").html());
                    })
                }else{
                    
                    $("#hd_count").attr("value",function(){
                        return parseInt($("#count").html())+1;
                    })
                }   
            }

        }

        if($(".sqdbh span").html()=="辅料订单编号重复，请修改！"){
            alert("辅料订单编号重复，请修改！")
        }else{
            $("#option").attr("value",0)
            $("#hd_submit").click()
        }       
    }) 
    
    var submitcount=0;  
    function submitOnce (form){  
        if (submitcount == 0){  
            submitcount++;  
            return true;  
        } else{  
            alert("正在操作，请不要重复提交，谢谢！");  
            return false;  
        }  
    }  

    $("#delete").click(function(){

        id=$("#my_id").val()

        window.location.href="../../controller/fl/flLiucheng.php?option=7&id=" + id;
    })
})

