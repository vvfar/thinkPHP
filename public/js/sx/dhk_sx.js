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

var excel=function(){
    date1=$("#date1").val()
    date2=$("#date2").val()
    companyName=$("#companyName").val()

    window.location.href='../../controller/sx/sc_form.php?date1=' + date1 + '&date2=' + date2 +"&companyName=" + companyName + "&option=1"
}

var search=function(){
    date1=$("#date1").val()
    date2=$("#date2").val()
    chooseInfo=$("#chooseInfo").val()

    sqid=$("#sqid").val()
    companyName=$("#companyName").val()
    department=$("#department").val()

    if(chooseInfo=="授信编号"){
        window.location.href="<?php echo $_SERVER['PHP_SELF']?>?date1=" +date1 + "&date2=" + date2 +"&chooseInfo=授信编号&sqid=" + sqid
    }else if(chooseInfo=="公司名称"){
        window.location.href="<?php echo $_SERVER['PHP_SELF']?>?date1=" +date1 + "&date2=" + date2 +"&chooseInfo=公司名称&companyName=" + companyName
    }else if(chooseInfo=="事业部"){
        window.location.href="<?php echo $_SERVER['PHP_SELF']?>?date1=" +date1 + "&date2=" + date2 +"&chooseInfo=事业部&department=" + department
    }

}

$("#chooseInfo").change(function(){
    chooseInfo=$(this).val()

    if(chooseInfo=="授信编号"){
        $("#sqid").css("display","inline")
        $("#companyName").css("display","none")
        $("#department").css("display","none")
    }else if(chooseInfo=="公司名称"){
        $("#sqid").css("display","none")
        $("#companyName").css("display","inline")
        $("#department").css("display","none")
    }else if(chooseInfo=="事业部"){
        $("#sqid").css("display","none")
        $("#companyName").css("display","none")
        $("#department").css("display","inline")
    }

})