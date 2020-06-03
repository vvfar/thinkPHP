$(document).ready(function(){
    $("#time").html(function(){
        date=new Date();
        hour=date.getHours();

        if(hour>=9 && hour<=11){
            return "早上好，"
        }else if(hour==12){
            return "中午好，"
        }else if(hour>=13 && hour<=18){
            return "下午好，"
        }else if(hour>=19){
            return "晚上好，"
        }else{
            return "早上好，"
        }
    })
})