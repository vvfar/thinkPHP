$(document).ready(function(){
    var managerHeader_path=window.location.pathname;

    managerHeader_path=managerHeader_path.split("/");
    managerHeader_path=managerHeader_path.pop();
    
    if(managerHeader_path == "manager_index.php"){
        $(".header_manager1").siblings().removeClass("active");
        $(".header_manager1").addClass("active");
    }else if(managerHeader_path == "managerFL.php" || managerHeader_path == "managerFL_edit.php"){
        $(".header_manager2").siblings().removeClass("active");
        $(".header_manager2").addClass("active");
    }else if(managerHeader_path == "managerNews.php" || managerHeader_path == "managerAddContent.php"){
        $(".header_manager3").siblings().removeClass("active");
        $(".header_manager3").addClass("active");
    }else if(managerHeader_path == "managerFile.php" || managerHeader_path == "managerAddFile.php"){
        $(".header_manager4").siblings().removeClass("active");
        $(".header_manager4").addClass("active");
    }else if(managerHeader_path == "managerStaff.php" || managerHeader_path == "manager_staffLine.php" ){
        $(".header_manager5").siblings().removeClass("active");
        $(".header_manager5").addClass("active");
    }else if(managerHeader_path == "managerStore.php" || managerHeader_path == "manager_storeLine.php"){
        $(".header_manager6").siblings().removeClass("active");
        $(".header_manager6").addClass("active");
    }else if(managerHeader_path == "managerData.php"){
        $(".header_manager7").siblings().removeClass("active");
        $(".header_manager7").addClass("active");
    }else if(managerHeader_path == "manager_process.php" || managerHeader_path == "flProcess.php"){
        $(".header_manager8").siblings().removeClass("active");
        $(".header_manager8").addClass("active");
    }else if(managerHeader_path == "manager_log.php" || managerHeader_path == "manager_backup.php"){
        $(".header_manager9").siblings().removeClass("active");
        $(".header_manager9").addClass("active");
    }

    $("#center").click(function(){
        window.location.href="center.php";
    })
})