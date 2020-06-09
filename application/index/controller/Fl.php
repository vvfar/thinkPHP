<?php
namespace app\index\controller;
use think\Controller;
use think\Request;

class Fl extends Controller{
    
    public function flsq(){

        session_start();
        $username=$_SESSION["username"];

        $this->assign("username",$username);

        return $this->fetch();
    }

    public function w_fl(){

        session_start();
        $username=$_SESSION["username"];

        $status2=$this->request->param("status2");
        $time=$this->request->param("time");
        $input_time=$this->request->param("input_time");
        $input_time2=$this->request->param("input_time2");
        $clientName=$this->request->param("clientName");

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

        $sqlstr3="select count(*) as total from flsqd where (not status like '%已归档单据%' and not status like '%品牌部归档%') and not status like '%作废%' and (not status='KA级提交单据' and not shr='$username')";

        if($newLevel !="ADMIN"){
            $sqlstr3=$sqlstr3." and (shr like '%$username%' or ywy like '%$username%')";
        }

        if($input_time !=""){
            $input_time_full=$input_time." 00:00:00";

            $sqlstr3=$sqlstr3." and date >='$input_time_full' ";
        }

        if($input_time2 != ""){
            $input_time2_full=$input_time2." 23:59:59";

            $sqlstr3=$sqlstr3." and date <='$input_time2_full' ";
        }

        if($clientName != ""){
            $sqlstr3=$sqlstr3." and company like '%$clientName%' ";
        }


        $fl_counts=\think\Db::query($sqlstr3);
        $total=$fl_counts[0]["total"];

        if($total%$pagesize==0){
            $pagecount=intval($total/$pagesize);
        }else{
            $pagecount=ceil($total/$pagesize);
        }

        $count=0;

        //表格数据
        $sqlstr2="select id,no,company,people,date,date2,status,shr from flsqd where (not status like '%已归档单据%' and not status like '%品牌部归档%') and not status like '%作废%'  and (not status='KA级提交单据' and not shr='$username')";

        if($newLevel !="ADMIN"){
            $sqlstr2=$sqlstr2." and (shr like '%$username%' or ywy like '%$username%')";
        }

        if($input_time !=""){
            $input_time_full=$input_time." 00:00:00";

            $sqlstr2=$sqlstr2." and date >='$input_time_full' ";
        }

        if($input_time2 != ""){
            $input_time2_full=$input_time2." 23:59:59";

            $sqlstr2=$sqlstr2." and date <='$input_time2_full' ";
        }

        if($clientName != ""){
            $sqlstr2=$sqlstr2." and company like '%$clientName%' ";
        }

        $sqlstr2=$sqlstr2." order by id desc limit ".($page-1)*$pagesize.",$pagesize";

        $fls=\think\Db::query($sqlstr2);

        for($i=0;$i<sizeof($fls);$i++){
            $arr_status=explode(",",$fls[$i]["status"]);
            $status=array_pop($arr_status);
            $fls[$i]["status"]=$status;

            $arr_shr=explode(",",$fls[$i]["shr"]);
            $shr=array_pop($arr_shr);
            $fls[$i]["shr"]=$shr;

            //加入key
            $key_count=\think\Db::name("fl_key")->field("count(*)")->where("fl_no",$fls[$i]["id"])->select();
            $fls[$i]["key"]=$key_count[0]["count(*)"];

        }

        $this->assign("username",$username);
        $this->assign('title','待审核辅料单');
        $this->assign('total',$total);
        $this->assign('pagecount',$pagecount);
        $this->assign('page',$page);
        $this->assign('pagesize',15);
        $this->assign('i',1);
        $this->assign("input_time",$input_time);
        $this->assign("input_time2",$input_time2);
        $this->assign("clientName",$clientName);
        $this->assign("fls",$fls);
        $this->assign("status2",$status2);
        $this->assign("time",$time);


        return $this->fetch();
    }

    public function d_fl(){
        
        session_start();
        $username=$_SESSION["username"];

        $this->assign("username",$username);

        return $this->fetch();
    }

    public function old_fl(){
        
        session_start();
        $username=$_SESSION["username"];

        $this->assign("username",$username);

        return $this->fetch();
    }

    public function flLine(){
        
        session_start();
        $username=$_SESSION["username"];

        $this->assign("username",$username);

        return $this->fetch();
    }


}