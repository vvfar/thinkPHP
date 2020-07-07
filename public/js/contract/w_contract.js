$("#query_contract").click(function(){
    keywords=$("#keywords").val();
    window.location.href="/index.php/Index/contract/w_contract.html?keywords=" +keywords + "&status=待归档";
})

$("#download_contract").click(function(){
    keywords=$("#keywords").val();
    window.location.href="/index.php/Index/contract/contract_download?keywords=" +keywords + "&status=待归档";
})