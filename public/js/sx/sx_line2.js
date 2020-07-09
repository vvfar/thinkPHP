window.onload=function(){
    id=$("#id").html();

    $("#agree").click(function(){
        window.location.href="/index.php/Index/sx/sx_hkHandle?id=" + id + "&option=1"
    })
    
    $("#disagree").click(function(){
        window.location.href="/index.php/Index/sx/sx_hkHandle?id=" + id + "&option=0"
    })
}

