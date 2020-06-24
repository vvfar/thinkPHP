$(document).ready(function(){
    var managerHeader_path=window.location.pathname;

    managerHeader_path=managerHeader_path.split("/");
    managerHeader_path=managerHeader_path.pop();

    if(managerHeader_path == "index.html"){
        $(".header_manager1").siblings().removeClass("active");
        $(".header_manager1").addClass("active");
    }else if(managerHeader_path == "fl_list.html" || managerHeader_path == "fl_edit"){
        $(".header_manager2").siblings().removeClass("active");
        $(".header_manager2").addClass("active");
    }else if(managerHeader_path == "news_list.html" || managerHeader_path == "news_add.html"){
        $(".header_manager3").siblings().removeClass("active");
        $(".header_manager3").addClass("active");
    }else if(managerHeader_path == "document_list.html" || managerHeader_path == "managerAddFile.php"){
        $(".header_manager4").siblings().removeClass("active");
        $(".header_manager4").addClass("active");
    }else if(managerHeader_path == "process_list.html" || managerHeader_path == "process_fl.html"){
        $(".header_manager8").siblings().removeClass("active");
        $(".header_manager8").addClass("active");
    }else if(managerHeader_path == "system_log.html" || managerHeader_path == "system_backup.html"){
        $(".header_manager9").siblings().removeClass("active");
        $(".header_manager9").addClass("active");
    }

    $("#center").click(function(){
        window.location.href="center.php";
    })
})