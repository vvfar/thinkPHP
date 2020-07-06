window.onload=function(){
    $("#status").click(function(){
        if($("#status").val()=="公司名称"){
            $("#optionID").attr("value","1");
            $("#companyName").css("display","inline");
            $("#storeName").css("display","none");
            $("#department").css("display","none");
            $("#staff").css("display","none");
        }else if($("#status").val()=="店铺名称"){
            $("#optionID").attr("value","2");
            $("#companyName").css("display","none");
            $("#storeName").css("display","inline");
            $("#department").css("display","none");
            $("#staff").css("display","none");
        }else if($("#status").val()=="事业部"){
            $("#optionID").attr("value","3");
            $("#companyName").css("display","none");
            $("#storeName").css("display","none");
            $("#department").css("display","inline");
            $("#staff").css("display","none");
        }else if($("#status").val()=="绑定KA"){
            $("#optionID").attr("value","4");
            $("#companyName").css("display","none");
            $("#storeName").css("display","none");
            $("#department").css("display","none");
            $("#staff").css("display","inline");
        }
    })
    
    $("#download").click(function(){
        window.location.href="/index.php/Index/client/client_download?optionID={$optionID}&companyName={$companyName}&storeName={$storeName}&department={$department}&staff={$staff}"
    })
}

