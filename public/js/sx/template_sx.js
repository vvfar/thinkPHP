window.onload=function(){
    $(".form_datetime").datetimepicker({
        format: 'yyyy-mm-dd',
        weekStart: 1,
        autoclose: true,
        todayBtn: true,
        startView: 2,  
        minView: 2, 
        forceParse: false,
        language:'cn',
        pickerPosition: "bottom-left"
    });

    /** 数字金额大写转换(可以处理整数,小数,负数) */    
    function smalltoBIG(n)     
    {    
        var fraction = ['角', '分'];    
        var digit = ['零', '壹', '贰', '叁', '肆', '伍', '陆', '柒', '捌', '玖'];    
        var unit = [ ['元', '万', '亿'], ['', '拾', '佰', '仟']  ];    
        var head = n < 0? '欠': '';    
        n = Math.abs(n);    
      
        var s = '';    
      
        for (var i = 0; i < fraction.length; i++)     
        {    
            s += (digit[Math.floor(n * 10 * Math.pow(10, i)) % 10] + fraction[i]).replace(/零./, '');    
        }    
        s = s || '整';    
        n = Math.floor(n);    
      
        for (var i = 0; i < unit[0].length && n > 0; i++)     
        {    
            var p = '';    
            for (var j = 0; j < unit[1].length && n > 0; j++)     
            {    
                p = digit[n % 10] + unit[1][j] + p;    
                n = Math.floor(n / 10);    
            }    
            s = p.replace(/(零.)*零$/, '').replace(/^$/, '零')  + unit[0][i] + s;    
        }    
        return head + s.replace(/(零.)*零元/, '元').replace(/(零.)+/g, '零').replace(/^整$/, '零元整');    
    }

    $("#ds").html(function(){
        return smalltoBIG($("#xs").html())
    })

    $(".longLine").html(function(){
        return '_______________________________'
    })

    $(".middleLine").html(function(){
        return '_________________'
    })

    $(".shortLine").html(function(){
        return '_________'
    })

    $(".vshortLine").html(function(){
        return '_____'
    })

    $(".btn-blue").click(function(){
        window.location.href='/index.php/Index/sx/sx_line.html?id={$sx_line.id}'
    })

    $(".btn-download").click(function(){
        ds=$("#ds").html();

        window.location.href='dtemplate_sx.html?id={$sx_line.id}&ds=' + ds
    })
    
    window.onload=function(){
        ds=$("#ds").html();

        //window.location.href='dtemplate_sx.html?id={$sx_line.id}&ds=' + ds

        //window.location.href="javascript:history.go(-1)";
    }
}