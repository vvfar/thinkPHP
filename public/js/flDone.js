$(document).ready(function(){

    $("#download_fl").click(function(){
        time1=$("#time1").val()
        input_time=$("#input_time").val()
        input_time2=$("#input_time2").val()
        keywords=$("#keywords").val()

        window.location.href="download_fl?option=1&status=1&time=" + time1 + "&input_time=" + input_time + "&input_time2=" + input_time2 + "&keywords=" +keywords
        
    })

    $("#query_fl").click(function(){
        status=$("#status").val()
        time1=$("#time1").val()
        input_time=$("#input_time").val()
        input_time2=$("#input_time2").val()
        keywords=$("#keywords").val()

        window.location.href="d_fl.html?status=" + status + "&time=" + time1 + "&input_time=" + input_time + "&input_time2=" + input_time2 + "&keywords=" +keywords
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