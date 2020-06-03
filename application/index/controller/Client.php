<?php
namespace app\index\controller;
use think\Controller;

class Client extends Controller
{
    public function clientList()
    {
        session_start();
        $username=$_SESSION["username"];

        if(!isset($_GET["page"]) || !is_numeric($_GET["page"])){
            $page=1;
        }else{
            $page=intval($_GET["page"]);
        }

        $pagesize=15;

        $companyInfo_all=\think\Db::name('client')->field('id,clientName,storeName,department,staff,telephone,address')->select();
        $companyInfo=\think\Db::name('client')->field('id,clientName,storeName,department,staff,telephone,address')->order(['id'=>'desc'])->limit(($page-1)*$pagesize,$page*$pagesize)->select();
        $headers=array("序号","客户名称","店铺名称","事业部","绑定KA","联系电话","地址");

        $total=sizeof($companyInfo_all);
        $pagecount=(int)($total/$pagesize+1);

        if($total % $pagesize ==0){
            $pagecount=$pagecount-1;
        }
        
        $this->assign('username',$username);
        $this->assign('companyInfos',$companyInfo);
        $this->assign('page',$page);
        $this->assign('pagecount',$pagecount);
        $this->assign('pagesize',$pagesize);
        $this->assign('headers',$headers);
        $this->assign('total',$total);

        return $this->fetch();
    }
}
?>