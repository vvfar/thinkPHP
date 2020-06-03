window.onload=function(){

    //代办事项
    $.ajax({
        type:"post",
        async:true,
        url:"../../controller/index/myworkController.php",
        dataType:"json",
        success:function(result){
            if(result){
                dataForm=""


                for(var i=0;i<result.length;i++){

                    dataForm=dataForm + '<div class="willDo_child">' +
                                        '<p class="caption">' + result[i].name_dbsx + '</p>' + 
                                        '<h3><a href="' + result[i].link_dbsx  + '"target="_blank"' +'>' + result[i].number_dbsx +'</a></h3></div>';
                }

                $("#willDo_ajax").html(dataForm)
            }
        }
    })


    //数据图表（销售）
    var myChart=echarts.init(document.getElementById('data_body'),"light");

    myChart.showLoading();

    var names=[];
    var numbers=[];

    $.ajax({
        type:"post",
        async:true,
        url:"../../controller/index/myworkController2.php",
        dataType:"json",
        success:function(result){
            if(result){
                for(var i=0;i<result.length;i++){
                    names.push(result[i].dateTime_xssj);
                    numbers.push(result[i].number_xssj);
                    object=result[i].object_xssj;
                }
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
                    data:['销量']
                },
                
                xAxis:{
                    data:names
                },
                yAxis:{},
                series:[{
                    name:'销量',
                    type:'line',
                    data:numbers
                }]
            }

            myChart.setOption(option);

        }
    })


    //注意进度条依赖 element 模块，否则无法进行正常渲染和功能性操作
    layui.use('element', function(){
        var element = layui.element;
    });

    /*
    //销售数据

    $.ajax({
        
        type:"post",
        async:true,
        url:"../../controller/index/myworkController3.php",
        dataType:"json",
        success:function(result){

            if(result){

                for(var i=0;i<result.length;i++){

                    if(result[i].name == "个人销售数据"){
                        sales_one=result[i].number;
                        
                    }else if(result[i].name == "个人销售数据目标"){
                        if(result[i].number == 0){
                            sales_one_bar=1;
                        }else{
                            sales_one_bar=sales_one/result[i].number;
                        }

                    
                        if(sales_one_bar < 0.3){
                            $("#sales_one_bar").addClass("layui-bg-red")
                        }else if(sales_one_bar < 0.6){
                            $("#sales_one_bar").addClass("layui-bg-orange")
                        }else if(sales_one_bar < 0.8){
                            $("#sales_one_bar").addClass("layui-bg-blue")
                        }else{
                            $("#sales_one_bar").addClass("layui-bg-green")
                        }

                        $("#sales_one").html((sales_one_bar*100).toFixed(2)+"%");
                        $("#sales_one_bar").attr("lay-percent",sales_one_bar*100+"%")
                        $("#sales_one_bar").css("width",sales_one_bar*100+"%")
                        
                    }else if(result[i].name == "部门销售数据"){
                        sales_two=result[i].number;   
                    }else if(result[i].name == "部门销售数据目标"){
                        if(result[i].number == 0){
                            sales_two_bar=1;
                        }else{
                            sales_two_bar=sales_two/result[i].number;
                        }

                        if(sales_two_bar < 0.3){
                            $("#sales_two_bar").addClass("layui-bg-red")
                        }else if(sales_two_bar < 0.6){
                            $("#sales_two_bar").addClass("layui-bg-orange")
                        }else if(sales_two_bar < 0.8){
                            $("#sales_two_bar").addClass("layui-bg-blue")
                        }else{
                            $("#sales_two_bar").addClass("layui-bg-green")
                        }

                        $("#sales_two").html((sales_two_bar*100).toFixed(2)+"%");
                        $("#sales_two_bar").attr("lay-percent",sales_two_bar*100+"%")
                        $("#sales_two_bar").css("width",sales_two_bar*100+"%")
                        
                    }else if(result[i].name == "个人回款数据"){
                        sales_three=result[i].number;   
                    }else if(result[i].name == "个人回款数据目标"){
                        if(result[i].number == 0){
                            sales_three_bar=1;
                        }else{
                            sales_three_bar=sales_three/result[i].number;
                        }

                        if(sales_three_bar < 0.3){
                            $("#sales_three_bar").addClass("layui-bg-red")
                        }else if(sales_three_bar < 0.6){
                            $("#sales_three_bar").addClass("layui-bg-orange")
                        }else if(sales_three_bar < 0.8){
                            $("#sales_three_bar").addClass("layui-bg-blue")
                        }else{
                            $("#sales_three_bar").addClass("layui-bg-green")
                        }

                        $("#sales_three").html((sales_three_bar*100).toFixed(2)+"%");
                        $("#sales_three_bar").attr("lay-percent",sales_three_bar*100+"%")
                        $("#sales_three_bar").css("width",sales_three_bar*100+"%")
                        
                    }else if(result[i].name == "部门回款数据"){
                        sales_four=result[i].number;   
                    }else if(result[i].name == "部门回款数据目标"){
                        if(result[i].number == 0){
                            sales_four_bar=1;
                        }else{
                            sales_four_bar=sales_four/result[i].number;
                        }      

                        if(sales_four_bar < 0.3){
                            $("#sales_four_bar").addClass("layui-bg-red")
                        }else if(sales_four_bar < 0.6){
                            $("#sales_four_bar").addClass("layui-bg-orange")
                        }else if(sales_four_bar < 0.8){
                            $("#sales_four_bar").addClass("layui-bg-blue")
                        }else{
                            $("#sales_four_bar").addClass("layui-bg-green")
                        }

                        $("#sales_four").html((sales_four_bar*100).toFixed(2)+"%");
                        $("#sales_four_bar").attr("lay-percent",sales_four_bar*100+"%")
                        $("#sales_four_bar").css("width",sales_four_bar*100+"%")
                    }
                }

            }
        }
    })
*/

    /*
     //数据图表（销售）
     var myChart2=echarts.init(document.getElementById('data_body_two'),"light");

     myChart2.showLoading();
 
     var names=[];
     var numbers=[];
 
     $.ajax({
         type:"post",
         async:true,
         url:"../../controller/index/myworkController4.php",
         dataType:"json",
         success:function(result){
             if(result){
                 for(var i=0;i<result.length;i++){
                     names.push(result[i].dateTime_xssj);
                     numbers.push(result[i].number_xssj);
                     object=result[i].object_xssj;
                 }
             }
 
             myChart2.hideLoading();
         
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
                     data:['销量']
                 },
                 
                 xAxis:{
                     data:names
                 },
                 yAxis:{},
                 series:[{
                     name:'回款',
                     type:'bar',
                     data:numbers
                 }]
             }
 
             myChart2.setOption(option);
 
         }
     })
     */
 
    
}