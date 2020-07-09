window.onload=function(){
    window.onload=function(){
        $("#sxbh").click();
    }

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

    $("#sxbh").click(function(){

        var xmlhttp;
        if(window.ActiveXObject){
            xmlhttp=new ActiveXObject('Microsoft.XMLHTTP');
        }else{
            xmlhttp=new XMLHttpRequest();
        }

        sxbh=$("#sxbh").val();

        xmlhttp.open("GET","/index.php/Index/sx/sx_search?sxid=" + sxbh,true);
    
        xmlhttp.onreadystatechange=function(){
            
            if(xmlhttp.readyState==4 && xmlhttp.status ==200){

                var msg=xmlhttp.responseText;
                msg_list=msg.split(",");

                $("#id").attr("value",msg_list[0]);
                $("#cn").attr("value",msg_list[1]);
                $("#ywy").attr("value",msg_list[2]);
                if(msg_list[3] != undefined){
                    $("#hkqs").attr("value","第" + msg_list[3] + "期");
                }
                

            }
        }
        
        xmlhttp.send(null);
    })


    $("#submit").click(function(){
        if($("input[name='sxbh']").val() =="" || $("input[name='cn']").val() =="" ||
            $("input[name='storeName']").val() =="" || $("input[name='ywy']").val() =="" || 
            $("input[name='date1']").val() =="" ||  $("input[name='date2']").val() =="" || 
            $("input[name='sjhkje']").val() =="" ||  $("input[name='hkfs']").val() ==""){
            alert("请将表单填写完整！")
        }else{
            $("#hd_submit").click()
        } 
    })
}