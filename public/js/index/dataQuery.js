click="1"

window.onload=function(){
    initData()

    $("#no").click(function(){
        $(".store1").css("display","none")
        $(".store2").css("display","")
    })

    $("#yes").click(function(){
        $(".store2").css("display","none")
        $(".store1").css("display","")
    })

    $(".click_bar button").click(function(){
        option=$(this).html();

        $(this).siblings().removeClass("btn-success");
        $(this).removeClass("btn-default");
        $(this).addClass("btn-success");
        
        if(option=="折线图"){
            plant(1)
            click="1"
        }else if(option=="柱状图"){
            plant(2)
            click="2"
        }else if(option=="饼图"){
            plant(3)
            click="3"
        }


    })

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

    if(chooseThree==null || no==1){
        chooseThree="全部"
    }

    if(chooseFour==null || no==1){
        chooseFour="全部"
    }

    if(chooseFive==null || no==1){
        chooseFive="全部"
    }

    if(chooseSix==null || no==1){
        chooseSix="全部"
    }

    if(chooseSeven==null){
        chooseSeven="日"
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
        username:username,
    }

    console.log(JSON.stringify(data))

    $.ajax({
        type:"post",
        async:true,
        url:"/index.php/Index/index/dataQueryControllerBar",
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
                    
                    if(no==1 || no==undefined){
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

                    if(no==1 || no==undefined){
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

                    if(no==1 || no==undefined){
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

                    if(no==1 || no==undefined){
                        
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
            url:"/index.php/Index/index/dataQueryController1",
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
            url:"/index.php/Index/index/dataQueryController2",
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
            url:"/index.php/Index/index/dataQueryController3",
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
            url:"/index.php/Index/index/dataQueryController5",
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


        //第七个框
       $.ajax({
        type:"post",
        async:true,
        data:data,
        url:"/index.php/Index/index/dataQueryController7",
        dataType:"json",
        success:function(result){
            if(result){
                for(var i=0;i<result.length;i++){
                    $("#title7").html(result[0].value)
                    $("#mytime7").html(result[1].value)
                    $("#num7").html(result[2].value)
                    $("#tb7").html(result[3].value)
                    $("#hb7").html(result[4].value)
                    
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
            url:"/index.php/Index/index/dataQueryController6",
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
        
        option=$(".click_bar button").html();

        if(click==1){
            plant(1)
        }else if(click==2){
            plant(2)
        }else if(click==3){
            plant(3)
        }
    }
}

//第四个框 
function plant(no){

    if(no==1){
        var myChart=echarts.init(document.getElementById('data_body'),"light");
    
        //myChart.showLoading();
    
        var names=[];
        var numbers=[];
    
        $.ajax({
            type:"post",
            async:true,
            data:data,
            url:"/index.php/Index/index/dataQueryController4",
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
                
                //myChart.hideLoading();
            
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
                        data:names,
                        "axisLabel":{
                            interval: 3
                        },
                        show:true
                    },
                    yAxis:{
                        type: 'value',
                        //name:'万',
                        axisLabel: {
                            formatter:'{value} (万)'
                        },
                        show:true
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
    }else if(no==2){
        var myChart=echarts.init(document.getElementById('data_body'),"light");
    
        var names=[];
        var numbers=[];

        $.ajax({
            type:"post",
            async:true,
            data:data,
            url:"/index.php/Index/index/dataQueryController8",
            dataType:"json",
            success:function(result){
                console.log(result)

                for(i=0;i<result.length;i++){

                    names.push(result[i].department)
                    numbers.push(result[i].num)
                }

                //console.log(names)
                //console.log(numbers)

                // 指定图表的配置项和数据
                var option = {
                    title: {
                        text: ''
                    },
                    tooltip: {},
                    legend: {
                        data:['销量']
                    },
                    xAxis: {
                        splitLine: { show: false },
                        data: names,
                        "axisLabel":{
                            interval: 0
                        },
                        show:true
                    },
                    // axisLabel: {
                    //     interval: 0,
                    //     formatter: function(data) {
                    //         return data.split("").join("\n");
                    //     }
                    // },

                    yAxis: {show:true},
                    series: [{
                        name: '销量',
                        type: 'bar',
                        data: numbers,
                    }],
                };

                
                // 使用刚指定的配置项和数据显示图表。
                myChart.setOption(option);
            }
        })
    }else if(no=3){
        var myChart3=echarts.init(document.getElementById('data_body'),"light");
    
        var names=[];
        var numbers=[];

        $.ajax({
            type:"post",
            async:true,
            data:data,
            url:"/index.php/Index/index/dataQueryController9",
            dataType:"json",
            success:function(result){

                var pie_str="";

                for(i=0;i<result.length;i++){
                    pie_str+="{value:'"+result[i]["num"]+"', name:'"+ result[i]["pt"] +"'},"
                }

                pie_str="["+pie_str+"]";
                console.log(pie_str)

                myChart3.setOption({
                    series : [
                        {
                            name: '',
                            type: 'pie',
                            radius: '55%',
                            data:eval(pie_str)
                        }
                    ],
                    xAxis : [{show:false}],
                    yAxis : [{show:false}]
                })

                // 使用刚指定的配置项和数据显示图表。
                myChart3.setOption(option);
            }
        })
    }
}