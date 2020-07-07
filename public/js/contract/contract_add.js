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

    $("#contractType").change(function(){
        contract_type=$(this).val();

        if(contract_type=="经销商合同"){
            $(".store_pingtai").css("display","")
        }else{
            $(".store_pingtai").css("display","none")
        }
    })

    $("#submit").click(function(){
        input_time=$("#input_time").val();
        input_time2=$("#input_time2").val();

        if(input_time == "" || input_time2 == ""){
            alert("合同期限必须填写");
        }else{
            $("#hd_submit").click();
        }
    })
}