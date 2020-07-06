window.onload=function(){
    $("#info").click(function(){
        $(this).siblings().css("border-bottom","1px  solid #cccccc")
        $(this).css("border-bottom","2px solid red")
        $("#page1").css("display","inline")
        $("#page2").css("display","none")
        $("#page3").css("display","none")
    })

    $("#pwd").click(function(){
        $(this).siblings().css("border-bottom","1px  solid #cccccc")
        $(this).css("border-bottom","2px solid red")
        $("#page2").css("display","inline")
        $("#page1").css("display","none")
        $("#page3").css("display","none")
    })

    $("#head").click(function(){
        $(this).siblings().css("border-bottom","1px  solid #cccccc")
        $(this).css("border-bottom","2px solid red")
        $("#page3").css("display","inline")
        $("#page1").css("display","none")
        $("#page2").css("display","none")
    })

    $("#nickname_link").click(function(){
        $("#nickname").css("display","none")
        $("#nickname_link").css("display","none")
        $(".form_nickname").css("display","inline")
    })

    $("#back1").click(function(){
        $("#nickname").css("display","inline")
        $("#nickname_link").css("display","inline")
        $(".form_nickname").css("display","none")
    })

    $("#phone_link").click(function(){
        $("#phone").css("display","none")
        $("#phone_link").css("display","none")
        $(".form_phone").css("display","inline")
    })

    $("#back2").click(function(){
        $("#phone").css("display","inline")
        $("#phone_link").css("display","inline")
        $(".form_phone").css("display","none")
    })

    $("#email_link").click(function(){
        $("#email").css("display","none")
        $("#email_link").css("display","none")
        $(".form_email").css("display","inline")
    })

    $("#back3").click(function(){
        $("#email").css("display","inline")
        $("#email_link").css("display","inline")
        $(".form_email").css("display","none")
    })

    $("#submit").click(function(){
        postForm=false;
        msg="";

        newPwd1=$("#newPwd1").val();
        newPwd2=$("#newPwd2").val();

        if($.trim(newPwd1) == "" || $.trim(newPwd2) == ""){
            msg="新密码或确认密码不能为空！";
        }else{
            if($.trim(newPwd1) != $.trim(newPwd2)){
                msg="新密码与确认密码不一致！";
            }else{
                if(newPwd1.length<8){
                    msg="新密码小于8位！";
                }else{
                    reg=/^(?=.*[a-zA-Z])(?=.*[1-9])(?=.*[\W]).{8,}$/;

                    if(reg.test(newPwd1)){
                        msg="";
                        postForm=true;
                    }else{
                        msg="密码必须包含字母、数字以及特殊字符！";
                    }

                }
            }
        }

        if(postForm==true){
            $("#hidden_submit").click();
        }else{
            alert(msg)
        }
    })
}