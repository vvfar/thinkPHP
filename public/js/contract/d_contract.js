window.onload=function(){
    $("#query_contract").click(function(){
        keywords=$("#keywords").val();
        window.location.href="/index.php/Index/contract/d_contract.html?keywords=" +keywords + "&status=已归档";
    })

    $("#download_contract").click(function(){
        keywords=$("#keywords").val();
        window.location.href="/index.php/Index/contract/contract_download?keywords=" +keywords + "&status=已归档";
    })
}