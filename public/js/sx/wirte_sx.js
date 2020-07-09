window.onload=function(){
    $(".form_datetime").datetimepicker({
        format: 'yyyy-mm-dd',
        weekStart: 1,
        autoclose: true,
        todayBtn: true,
        startView: 2,  
        minView: 2, 
        forceParse: false,
        language:'cn',
        pickerPosition: "bottom-left"
    });
    
    $("#isgx").click(function(){
        if($(this).val()=="是"){
            $(".gxCount").css("display","")
            $(".gxCount").css("clear","both")
        }else{
            $(".gxCount").css("display","none")
            $(".gxDepartment").css("display","none")       
        }
    })
    
    $("#gxCount_val").click(function(){
        gx=parseFloat($(this).val());
    
        for(i=1;i<=gx;i++){
            $(".gxDepartment" + i).css("display","")
            $(".gxDepartment" + i).css("clear","both")
        }
    
        for(i=12;i>gx;i--){
            $(".gxDepartment" + i).css("display","none")
            $(".gxDepartment" + i).css("clear","both")
        }
    })
    
    $("#qs").click(function(){
        qs=parseFloat($("#qs").val());
        
        for(i=1;i<=qs;i++){
            $(".zh" + i).css("display","")
            $(".zh" + i).css("clear","both")
        }
    
        for(i=12;i>qs;i--){
            $(".zh" + i).css("display","none")
            $(".zh" + i).css("clear","both")
        }
    })
    
    $("#submit").click(function(){
        if($("input[name='cn']").val() =="" || $("input[name='storeName']").val() =="" ||
            $("input[name='ywy']").val() =="" || $("input[name='date1']").val() =="" || 
            $("input[name='sqid']").val() =="" ||  $("input[name='sqmoney']").val() =="" || 
            $("input[name='qs']").val() =="" ||  $("input[name='zzqrr']").val() ==""){
            alert("请将表单填写完整！")
        }else{
            $("#hd_submit").click()
        } 
    })
}

