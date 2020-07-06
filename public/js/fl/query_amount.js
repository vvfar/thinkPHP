window.onload=function(){
    $("#query").click(function(){
        information=$("#search").val();
        window.location.href="/index.php/Index/fl/query_amount.html?information=" + information;
    })

    $("#download").click(function(){
        window.location.href="/index.php/Index/fl/download_fl_info.html";
    })

    
    
}