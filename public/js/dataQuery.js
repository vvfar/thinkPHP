window.onload=function(){
    initData()
}


function initData(changeData,no){

    username=$("#username").html()

    chooseOne=$("#chooseOne").val()
    chooseTwo=$("#chooseTwo").val()
    chooseThree=$("#chooseThree").val()
    chooseFour=$("#chooseFour").val()
    chooseFive=$("#chooseFive").val()
    chooseSix=$("#chooseSix").val()
    chooseSeven=$("#chooseSeven").val()
    chooseEight=$("#chooseEight").val()

    $("#title4").html(chooseOne)

    if(chooseSeven==null){
        $("#btn04").html("日")
    }else{
        $("#btn04").html(chooseSeven)
    }
    

    if(chooseTwo==null){
        chooseTwo="全部"
    }

    if(chooseThree==null){
        chooseThree="全部"
    }

    if(chooseFour==null){
        chooseFour="全部"
    }

    if(chooseFive==null){
        chooseFive="全部"
    }

    if(chooseSix==null){
        chooseSix="全部"
    }

    if(chooseSeven==null){
        chooseSeven="全部"
    }

    if(chooseEight==null){
        chooseEight="默认"
    }
    

    data={
        username:username,
        chooseOne:chooseOne,
        chooseTwo:chooseTwo,
        chooseThree:chooseThree,
        chooseFour:chooseFour,
        chooseFive:chooseFive,
        chooseSix:chooseSix,
        chooseSeven:chooseSeven,
        chooseEight:chooseEight,
    }

    console.log(JSON.stringify(data))

    $.ajax({
        type:"post",
        async:true,
        url:"../../controller/index/dataQueryControllerBar.php",
        data:data,
        dataType:"json",
        success:function(result){

            if(result){
                for(var i=0;i<result.length;i++){
                    if(no==undefined){

                        $("#chooseTwo").html(function(){
                            str="<option>全部</option>"
    
                            if(result[i].name=="department"){
                                
                                for(var j=0;j<result[i].value.length;j++){
                                    if(changeData==result[i].value[j]){
                                        str=str+"<option selected>" + result[i].value[j] +"</option>"
                                    }else{
                                        str=str+"<option>" + result[i].value[j] +"</option>"
                                    }
                                    
                                    
                                }
                                return str
                            }
                            
                        })
                    }
                    
                    if(no==undefined){
                        $("#chooseThree").html(function(){
                            str="<option>全部</option>"

                            if(result[i].name=="pingtai"){
                                for(var j=0;j<result[i].value.length;j++){
                                    if(changeData==result[i].value[j]){
                                        str=str+"<option selected>" + result[i].value[j] +"</option>"
                                    }else{
                                        str=str+"<option>" + result[i].value[j] +"</option>"
                                    }
                                    
                                }
                                return str
                            }
                            
                        })
                    }

                    if(no==undefined){
                        $("#chooseFour").html(function(){
                            str="<option>全部</option>"

                            if(result[i].name=="category"){
                                for(var j=0;j<result[i].value.length;j++){
                                    if(changeData==result[i].value[j]){
                                        str=str+"<option selected>" + result[i].value[j] +"</option>"
                                    }else{
                                        str=str+"<option>" + result[i].value[j] +"</option>"
                                    }
                                    
                                }
                                return str
                            }
                            
                        })
                    }

                    if(no==undefined){
                        $("#chooseFive").html(function(){
                            str="<option>全部</option>"

                            if(result[i].name=="storeName"){
                                for(var j=0;j<result[i].value.length;j++){
                                    if(changeData==result[i].value[j]){
                                        str=str+"<option selected>" + result[i].value[j] +"</option>"
                                    }else{
                                        str=str+"<option>" + result[i].value[j] +"</option>"
                                    }
                                    
                                }
                                return str
                            }
                            
                        })
                    }

                    if(no==undefined){
                        $("#chooseSix").html(function(){
                            str="<option>全部</option>"

                            if(result[i].name=="ywy"){
                                for(var j=0;j<result[i].value.length;j++){
                                    if(changeData==result[i].value[j]){
                                        str=str+"<option selected>" + result[i].value[j] +"</option>"
                                    }else{
                                        str=str+"<option>" + result[i].value[j] +"</option>"
                                    }
                                    
                                }
                                return str
                            }
                            
                        })
                    }

                    if(no==6 || no==undefined){
                        $("#chooseSeven").html(function(){
                            str=""

                            if(result[i].name=="time"){
                                for(var j=0;j<result[i].value.length;j++){
                                    if(changeData==result[i].value[j]){
                                        str=str+"<option selected>" + result[i].value[j] +"</option>"
                                    }else{
                                        str=str+"<option>" + result[i].value[j] +"</option>"
                                    }
                                    
                                }
                                return str
                            }
                            
                        })
                    }

                    if(no==6 || no==undefined){
                        $("#chooseEight").html(function(){
                            str="<option>默认</option>"

                            if(result[i].name=="date"){
                                for(var j=0;j<result[i].value.length;j++){
                                    if(changeData==result[i].value[j]){
                                        str=str+"<option selected>" + result[i].value[j] +"</option>"
                                    }else{
                                        str=str+"<option>" + result[i].value[j] +"</option>"
                                    }
                                    
                                }
                                return str
                            }
                            
                        })
                    }
                    
                }
            }
        },

        error:function(XMLHttpRequest, textStatus, errorThrown){
            console.log("数据错误")
        }
    })

    if(no !=6){
        $.ajax({
            type:"post",
            async:true,
            url:"../../controller/index/dataQueryController1.php",
            data:data,
            dataType:"json",
            success:function(result){
                if(result){
                    for(var i=0;i<result.length;i++){
                        $("#title").html(result[0].value)
                        $("#mytime").html(result[1].value)
                        $("#num").html(result[2].value)
                        $("#tb").html(result[3].value)
                        $("#hb").html(result[4].value)
                        
                    }
                }
            },
    
            error:function(XMLHttpRequest, textStatus, errorThrown){
                console.log("数据错误")
            }
        })
    
    
        //第二个框
        $.ajax({
            type:"post",
            async:true,
            url:"../../controller/index/dataQueryController2.php",
            data:data,
            dataType:"json",
            success:function(result){
                if(result){
                    for(var i=0;i<result.length;i++){
                        $("#title2").html(result[0].value)
                        $("#mytime2").html(result[1].value)
                        $("#num2").html(result[2].value)
                        $("#tb2").html(result[3].value)
                        $("#hb2").html(result[4].value)
                        
                    }
                }
            },
    
            error:function(XMLHttpRequest, textStatus, errorThrown){
                console.log("数据错误")
            }
        })
        
        
        //第三个框
        $.ajax({
            type:"post",
            async:true,
            data:data,
            url:"../../controller/index/dataQueryController3.php",
            dataType:"json",
            success:function(result){
                if(result){
                    for(var i=0;i<result.length;i++){
                        $("#title3").html(result[0].value)
                        $("#mytime3").html(result[1].value)
                        
                        str=""
    
                        $("#rank").html(function(){
                            for(var j=0;j<result[2].value.length;j++){
                                if(j==0){
                                    str=str + "<li><span style='background-color:red;color:#fff;padding-left:5px;padding-right:5px;border-radius:3px;'>" + (j+1) +"</span><span style='margin-left:10px'>" +result[2].value[j] + "</span></li>"
                                }else if(j==1){
                                    str=str + "<li><span style='background-color:brown;color:#fff;padding-left:5px;padding-right:5px;border-radius:3px;'>" + (j+1) +"</span><span style='margin-left:10px'>" +result[2].value[j] + "</span></li>"
                                }else if(j==2){
                                    str=str + "<li><span style='background-color:orange;color:#fff;padding-left:5px;padding-right:5px;border-radius:3px;'>" + (j+1) +"</span><span style='margin-left:10px'>" +result[2].value[j] + "</span></li>"
                                }else{
                                    str=str + "<li><span style='background-color:grey;color:#fff;padding-left:5px;padding-right:5px;border-radius:3px;'>" + (j+1) +"</span><span style='margin-left:10px'>" +result[2].value[j] + "</span></li>"
                                }  
                            }
    
                            return str;
                        })
    
                        str=""
    
                        $("#number").html(function(){
                            for(var j=0;j<result[3].value.length;j++){
                                str=str + "<li><span style='margin-left:10px;'>" +result[3].value[j] + "</span></li>"
                            }
    
                            return str;
                        })
                            
                        
                        
                    }
                }
            },
    
            error:function(XMLHttpRequest, textStatus, errorThrown){
                console.log("数据错误")
            }
        })
    
        
    
        //第五个框
        $.ajax({
            type:"post",
            async:true,
            data:data,
            url:"../../controller/index/dataQueryController5.php",
            dataType:"json",
            success:function(result){
                if(result){
                    for(var i=0;i<result.length;i++){
                        $("#title5").html(result[0].value)
                        $("#mytime5").html(result[1].value)
                        $("#num5").html(result[2].value)
                        $("#tb5").html(result[3].value)
                        $("#hb5").html(result[4].value)
                        
                    }
                }
            },
    
            error:function(XMLHttpRequest, textStatus, errorThrown){
                console.log("数据错误")
            }
        })
    
        //第六个框
        $.ajax({
            type:"post",
            async:true,
            data:data,
            url:"../../controller/index/dataQueryController6.php",
            dataType:"json",
            success:function(result){
                if(result){
                    for(var i=0;i<result.length;i++){
                        $("#title6").html(result[0].value)
                        $("#mytime6").html(result[1].value)
                        $("#num6").html(result[2].value)
                        $("#tb6").html(result[3].value)
                        $("#hb6").html(result[4].value)
                        
                    }
                }
            },
    
            error:function(XMLHttpRequest, textStatus, errorThrown){
                console.log("数据错误")
            }
        })
    
        //第四个框
        var myChart=echarts.init(document.getElementById('data_body'),"light");
    
        myChart.showLoading();
    
        var names=[];
        var numbers=[];
    
        $.ajax({
            type:"post",
            async:true,
            data:data,
            url:"../../controller/index/dataQueryController4.php",
            dataType:"json",
            success:function(result){
                if(result[0].dateTime_xssj != null){
                    for(var i=0;i<result.length;i++){
                        names.push(result[i].dateTime_xssj);
                        numbers.push(result[i].number_xssj);
                    }
                    object=result[0].object_xssj;
                    line=result[0].line;
                }
                
                myChart.hideLoading();
            
                var option={
                    title:{
                        text:'',
                        subtext:object,
                        x:'left' 
                    },
                    tooltip:{
                        trigger:'item',
                    },
                    legend:{
                        orient:'vertical', 
                        left:'right',  
                        data:[line]
                    },
                    
                    xAxis:{
                        data:names
                    },
                    yAxis:{
                        type: 'value',
                        //name:'万',
                        axisLabel: {
                            formatter:'{value} (万)'
                        }
                    },
                    series:[{
                        name:line,
                        type:'line',
                        data:numbers
                    }]
                }
    
                myChart.setOption(option);
    
            }
        })
    }
    


}