$(document).ready(function(){

    var del=false
    var add=false
    var add_del=0;

    $(".flno").children().change(function(){
        fl=$(this).val()

        fl_price=0;

        $(this).parent().siblings(".dj").children().attr("value",function(){

            $.ajax({
                type:"get",
                async:false,
                url:"../../controller/fl/fldj.php?fl=" +encodeURIComponent(fl),
                //dataType:"json",
                success:function(result){
                    
                    if(result){
                        fl_price=result;   
                    }
                }
            })

            return fl_price
        })
        
    })
})