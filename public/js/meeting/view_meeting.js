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
    
    $(".date").change(function(){
        date=$("#dateTime").val();
    
        window.location.href="view_meeting.html?date="+date;
    })
}
