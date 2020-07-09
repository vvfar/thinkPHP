window.onload=function(){
    id=$("#id").html();

    $("#agree").click(function(){
        window.location.href="/index.php/Index/sx/sx_Handle?id=" + id + "&progress=2&option=1"
    })

    $("#disagree").click(function(){
        window.location.href="/index.php/Index/sx/sx_Handle?id=" + id + "&progress=2&option=0"
    })

    $("#edit").click(function(){
        window.location.href="/index.php/Index/sx/write_sx.html?id=" + id 
    })

    $("#tomb").click(function(){
        window.location.href="/index.php/Index/sx/template_sx.html?id=" + id
    })
}