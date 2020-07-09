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

    var excel=function(){
        date1=$("#date1").val()
        date2=$("#date2").val()
        companyName=$("#companyName").val()

        window.location.href='formHandle/sc_form.php?date1=' + date1 + '&date2=' + date2 +"&companyName=" + companyName + "&option=2"
    }

    var search=function(){
        date1=$("#date1").val()
        date2=$("#date2").val()
        companyName=$("#companyName").val()

        window.location.href="<?php echo $_SERVER['PHP_SELF']?>?date1=" +date1 + "&date2=" + date2 +"&companyName=" + companyName

    }
}