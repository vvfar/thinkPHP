<?php
namespace app\index\controller;
use think\Controller;
use think\Request;

class Contract extends Controller{

    public function contract(){

        session_start();
        $username=$_SESSION["username"];

        $this->assign('username',$username);
        $this->assign('title','新增合同');

        return $this->fetch();
    
    }

    public function w_contract(){

        session_start();
        $username=$_SESSION["username"];

        $contractID="";
        $clientName="";

        $sqlstr1=\think\Db::name("user_form")->field(["department","newLevel"])->where("username",$username)->select();

        $department=$sqlstr1[0]["department"];
        $newLevel=$sqlstr1[0]["newLevel"];

        $headers=["序号","合同编号","公司名称","授权平台","授权类目","事业部","流程状态","登记日期"];

        //分页代码
        if(!isset($_GET["page"]) || !is_numeric($_GET["page"])){
            $page=1;
        }else{
            $page=intval($_GET["page"]);
        }

        $pagesize=15;


        $sqlstr3="select count(*) as total from contract where not status like '%已归档%'";

        if($newLevel !="ADMIN" and $department !="财务部" and $department !="商业运营部"){
            //if($newLevel == "KA"){
            //    $sqlstr3=$sqlstr3." and shr like '%$username%'"; 
            //}else{
                $sqlstr3=$sqlstr3." and '$department' like concat('%',department,'%') ";
            //}
            
        }

        if($clientName !=""){
            $sqlstr3=$sqlstr3." and company like '%$clientName%'";
        }elseif($contractID !=""){
            $sqlstr3=$sqlstr3." and no like '%$contractID%'";
        }

        $sqlstr3=\think\Db::query($sqlstr3);

        $total=$sqlstr3[0]["total"];

        if($total%$pagesize==0){
            $pagecount=intval($total/$pagesize);
        }else{
            $pagecount=ceil($total/$pagesize);
        }


        $sqlstr2="select id,no,company,pingtai,category,department,money,sales,service,re_date,'合同',status,shr,shTime from contract where not status like '%已归档%'";

        if($newLevel !="ADMIN" and $department !="财务部" and $department !="商业运营部"){

            //if($newLevel == "KA"){
            //    $sqlstr2=$sqlstr2." and shr like '%$username%'"; 
            //}else{
                $sqlstr2=$sqlstr2." and '$department' like concat('%',department,'%') ";
            //}
            
        }


        if($clientName !=""){
            $sqlstr2=$sqlstr2." and company like '%$clientName%'";
        }elseif($contractID !=""){
            $sqlstr2=$sqlstr2." and no like '%$contractID%'";
        }

        $sqlstr2=$sqlstr2." order by re_date desc limit ".($page-1)*$pagesize.",$pagesize";

        $contracts=\think\Db::query($sqlstr2);

        $this->assign('username',$username);
        $this->assign('title','待审核合同');
        $this->assign('contracts',$contracts);
        $this->assign('contractID',$contractID);
        $this->assign('clientName',$clientName);
        $this->assign('total',$total);
        $this->assign('pagecount',$pagecount);
        $this->assign('page',$page);
        $this->assign('pagesize',15);
        $this->assign('headers',$headers);
        $this->assign('i',1);
        
        

        return $this->fetch();

    }

    public function d_contract(){

        session_start();
        $username=$_SESSION["username"];

        $headers=["序号","合同编号","公司名称","授权平台","授权类目","事业部","流程状态","登记日期"];

        $contractID="";
        $clientName="";

        $sqlstr1=\think\Db::name("user_form")->field(["department","newLevel"])->where("username",$username)->select();

        $department=$sqlstr1[0]["department"];
        $newLevel=$sqlstr1[0]["newLevel"];

        //分页代码
        if(!isset($_GET["page"]) || !is_numeric($_GET["page"])){
            $page=1;
        }else{
            $page=intval($_GET["page"]);
        }

        $pagesize=15;

        $sqlstr3="select count(*) as total from contract where status like '%已归档%'";

        if($newLevel !="ADMIN" and $department !="财务部" and $department !="商业运营部"){
            $sqlstr3=$sqlstr3." and '$department' like concat('%',department,'%') ";
        }

        if($clientName !=""){
            $sqlstr3=$sqlstr3." and company like '%$clientName%'";
        }elseif($contractID !=""){
            $sqlstr3=$sqlstr3." and no like '%$contractID%'";
        }

        $sqlstr3=\think\Db::query($sqlstr3);

        $total=$sqlstr3[0]["total"];

        if($total%$pagesize==0){
            $pagecount=intval($total/$pagesize);
        }else{
            $pagecount=ceil($total/$pagesize);
        }


        $sqlstr2="select id,no,company,pingtai,category,department,money,sales,service,re_date,'合同',status,shr,shTime from contract where status like '%已归档%'";

        if($newLevel !="ADMIN" and $department !="财务部" and $department !="商业运营部"){
            $sqlstr2=$sqlstr2." and '$department' like concat('%',department,'%') ";
        }

        if($clientName !=""){
            $sqlstr2=$sqlstr2." and company like '%$clientName%'";
        }elseif($contractID !=""){
            $sqlstr2=$sqlstr2." and no like '%$contractID%'";
        }

        $sqlstr2=$sqlstr2." limit ".($page-1)*$pagesize.",$pagesize";

        $contracts=\think\Db::query($sqlstr2);

        $i=1;

        $this->assign('username',$username);
        $this->assign('title','已审核合同');
        $this->assign('contracts',$contracts);
        $this->assign('contractID',$contractID);
        $this->assign('clientName',$clientName);
        $this->assign('total',$total);
        $this->assign('pagecount',$pagecount);
        $this->assign('page',$page);
        $this->assign('pagesize',15);
        $this->assign('headers',$headers);
        $this->assign('i',1);
        $this->assign('newLevel',$newLevel);

        return $this->fetch();

    }


}