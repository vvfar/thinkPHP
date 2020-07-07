window.onload=function(){
    $("#edit").click(function(){
        window.location.href="/index.php/Index/Contract/contract.html?id={$contract_item.id}"
    })

    $("#yes").click(function(){
        window.location.href="/index.php/Index/Contract/add_contract_handle?id={$contract_item.id}&progress=4"
    })

    $("#no").click(function(){
        window.location.href="/index.php/Index/Contract/add_contract_handle?id={$contract_item.id}&progress=5"
    })

    var changeContract=function(){
        window.location.href="/index.php/Index/Contract/contract.html?id={$contract_item.id}&option=0"
    }

    var delContract=function(){
        window.location.href="/index.php/Index/Contract/add_contract_handle?id={$contract_item.id}&progress=6"
    }
}