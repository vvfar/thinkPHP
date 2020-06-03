$(document).ready(function(){

    $("#download_fl").click(function(){
        status=$("#status").val()
        time1=$("#time1").val()
        input_time=$("#input_time").val()
        input_time2=$("#input_time2").val()
        clientName=$("#clientName").val()


        window.location.href="../../controller/fl/download_fl.php?option=1&status=" + status + "&time=" + time1 + "&input_time=" + input_time + "&input_time2=" + input_time2 + "&clientName=" +clientName
        
    })

    $("#query_fl").click(function(){
        status=$("#status").val()
        time1=$("#time1").val()
        input_time=$("#input_time").val()
        input_time2=$("#input_time2").val()
        clientName=$("#clientName").val()

        window.location.href="flList.php?status=" + status + "&time=" + time1 + "&input_time=" + input_time + "&input_time2=" + input_time2 + "&clientName=" +clientName

    })

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
})