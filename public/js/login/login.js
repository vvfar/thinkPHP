$(document).ready(function(){
    
    window.onload=function(){
        if(screen.width<600){
            window.location.href="../../home/mobile/login.php";
        }
    }

    $(".reset").click(function(){
        window.location.href="/index.php/Index/login/forget_pwd.html";
    })
})