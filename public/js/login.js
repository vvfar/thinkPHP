$(document).ready(function(){
    
    window.onload=function(){
        if(screen.width<600){
            window.location.href="../../home/mobile/login.php";
        }
    }

    $(".reset").click(function(){
        alert("请联系信息技术部！")
    })
})