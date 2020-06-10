<?php
namespace app\index\controller;
use think\Controller;
use think\Request;

class Fl extends Controller{
    
    public function flsq(){
        $id=$this->request->param("id");


        session_start();
        $username=$_SESSION["username"];

        $sqlstr1=\think\Db::name("user_form")->field(["department","newLevel"])->where("username",$username)->select();

        $department=$sqlstr1[0]["department"];
        $newLevel=$sqlstr1[0]["newLevel"];

        if($id != ""){
            $fl_infos=\think\Db::name("flsqd")->where("id",$id)->select();
            $fl_info=$fl_infos[0];

            $fl_sxs=\think\Db::name("use_sx")->field(["sqid","nowUseMoney"])->where("fl_no",$no)->select();
            $fl_sx=$fl_sxs[0];

        }else{
            date_default_timezone_set("Asia/Shanghai");

            $fl_info=[];

            $fl_info["Id"]="";
            $fl_info["no"]="";
            $fl_info["company"]="";
            $fl_info["people"]=$username;
            $fl_info["department"]=$department;
            $fl_info["date"]=date('Y-m-d H:i:s', time());
            $fl_info["address"]="";
            $fl_info["connection"]="";
            $fl_info["phone"]="";
            $fl_info["driving"]="";
            $fl_info["ishs"]="";

            $fl_info["category"]="";
            $fl_info["productNo"]="";
            $fl_info["productName"]="";
            $fl_info["amount"]="";
            $fl_info["price"]="";
            $fl_info["fls"]="";
            $fl_info["fwfxj"]="";
            $fl_info["flsName"]="";
            $fl_info["dj"]="";
            $fl_info["sl"]="";
            $fl_info["flfxj"]="";

            $fl_info["sd"]="";
            $fl_info["jkfs"]="";
            $fl_info["wlfs"]="";
            $fl_info["wlno"]="";
            $fl_info["wlprice"]="";
            $fl_info["note"]="";

            $fl_info["hd_sqslhj"]="";
            $fl_info["hd_fwfhj"]="";
            $fl_info["hd_flsl"]="";
            $fl_info["hd_flfhjsh"]="";
            $fl_info["hd_fwfflfzj"]="";
            $fl_info["hd_count"]=2;
            $fl_info["ywy"]="";
            $fl_info["status"]="";

            //订单自动编号

            $fl_infos=\think\Db::name("fl_no")->field("no")->where("department",$department)->select();
            
            if($fl_infos != []){
                $fl_no=$fl_infos[0]["no"];
            }else{
                $fl_no="";
            }
            

            $fl_no_date_arr=explode("-",$fl_no);
            $fl_no_date=array_pop($fl_no_date_arr);

            $fl_no_year=(int)substr($fl_no_date,0,4);
            $fl_no_month=(int)substr($fl_no_date,4,2);
            $fl_no_num=substr($fl_no_date,6,3);

            $sys_date=date('Y-m-d', time());
            $sys_date_arr=explode("-",$sys_date);

            $sys_date_year=(int)$sys_date_arr[0];
            $sys_date_month=(int)$sys_date_arr[1];
            
            if($sys_date_month>$fl_no_month and $sys_date_year==$fl_no_year){
                $fl_no_month=$sys_date_month;
                $fl_no_num=0;
            }
            
            if($sys_date_year>$fl_no_year){
                $fl_no_month=$sys_date_month;
                $fl_no_year=$sys_date_year;

                $fl_no_num=0;
            }

            $fl_no_num=$fl_no_num + 1;

            if($fl_no_num<10){
                $fl_no_num="00".$fl_no_num;
            }elseif($fl_no_num<100){
                $fl_no_num="0".$fl_no_num;
            }

            if($fl_no_month<10){
                $fl_no_month="0".$fl_no_month;
            }

            $fl_no_date_new=$fl_no_year.$fl_no_month.$fl_no_num;

            $fl_info["no"]=str_replace($fl_no_date,$fl_no_date_new,$fl_no);
        }

        $fl_wlfs=\think\Db::name("fl_wlfs")->field("name")->select();

        $fl_names=\think\Db::name("fl")->field("fl_name")->order("fl_name")->select();

        $fl_jkfss=\think\Db::name("fl_jkfs")->field("name")->select();

        $fl_sxInfos=\think\Db::query("select distinct a.sqid from sx_form a,use_sx b where a.sqid=b.sqid and (a.department='$department' or a.gxDepartment='$department') and a.status='已生效' and b.newMoney > 0");
        if($fl_sxInfos !=[]){
            $fl_sxInfo=$fl_sxInfos[0];
        }else{
            $fl_sxInfo="";
        }
        


        $category_arr=explode(',',$fl_info["category"]);
        $productNo_arr=explode(',',$fl_info["productNo"]);
        $productName_arr=explode(',',$fl_info["productName"]);
        $amount_arr=explode(',',$fl_info["amount"]);
        $price_arr=explode(',',$fl_info["price"]);
        $fls_arr=explode(',',$fl_info["fls"]);
        $fwfxj_arr=explode(',',$fl_info["fwfxj"]);
        $flsName_arr=explode(',',$fl_info["flsName"]);
        $dj_arr=explode(',',$fl_info["dj"]);
        $sl_arr=explode(',',$fl_info["sl"]);
        $flfxj_arr=explode(',',$fl_info["flfxj"]);
        $hd_count=$fl_info["hd_count"];

        $this->assign("username",$username);
        $this->assign("title","新增辅料单");
        $this->assign("fl_info",$fl_info);
        $this->assign("fl_wlfss",$fl_wlfs);
        $this->assign("categorys",$category_arr);
        $this->assign("productNos",$productNo_arr);
        $this->assign("productNames",$productName_arr);
        $this->assign("amounts",$amount_arr);
        $this->assign("prices",$price_arr);
        $this->assign("flss",$fls_arr);
        $this->assign("fwfxjs",$fwfxj_arr);
        $this->assign("flsNames",$flsName_arr);
        $this->assign("djs",$dj_arr);
        $this->assign("sls",$sl_arr);
        $this->assign("flfxjs",$flfxj_arr);
        $this->assign("hd_count",$hd_count);
        $this->assign("fl_names",$fl_names);
        $this->assign("fl_jkfss",$fl_jkfss);
        $this->assign("fl_sxInfos",$fl_sxInfo);


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
        $this->assign("newLevel",$newLevel);


        return $this->fetch();
    }

    public function d_fl(){
        
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

        $sqlstr3="select count(*) as total from flsqd where (status like '%已归档单据%' or status like '%品牌部归档%' or status like '%作废%') ";

        if($newLevel !="ADMIN" and $department !="商业运营部" and $department !="义乌部"){
            $sqlstr3=$sqlstr3." and (shr like '%$username%' or ywy like '%$username%')";
        }

        if($input_time !=""){
            $input_time_full=$input_time." 00:00:00";

            if($time == "流程开始时间"){
                $sqlstr3=$sqlstr3." and date >='$input_time_full' ";
            }else{
                $sqlstr3=$sqlstr3." and date2 >='$input_time_full' ";
            }
            
        }

        if($input_time2 != ""){
            $input_time2_full=$input_time2." 23:59:59";

            if($time == "流程开始时间"){
                $sqlstr3=$sqlstr3." and date <='$input_time2_full' ";
            }else{
                $sqlstr3=$sqlstr3." and date2 <='$input_time2_full' ";
            }
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
        $sqlstr2="select id,no,company,people,date,date2,status,shr,allTime from flsqd where (status like '%已归档单据%' or status like '%品牌部归档%' or status like '%作废%') ";

        if($newLevel !="ADMIN" and $department !="商业运营部" and $department !="义乌部"){
            $sqlstr2=$sqlstr2." and (shr like '%$username%' or ywy like '%$username%')";
        }

        if($input_time !=""){
            $input_time_full=$input_time." 00:00:00";

            if($time == "流程开始时间"){
                $sqlstr2=$sqlstr2." and date >='$input_time_full' ";
            }else{
                $sqlstr2=$sqlstr2." and date2 >='$input_time_full' ";
            }
            
        }

        if($input_time2 != ""){
            $input_time2_full=$input_time2." 23:59:59";

            if($time == "流程开始时间"){
                $sqlstr2=$sqlstr2." and date <='$input_time2_full' ";
            }else{
                $sqlstr2=$sqlstr2." and date2 <='$input_time2_full' ";
            }
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
        $this->assign("time",$time);
        $this->assign("input_time",$input_time);
        $this->assign("input_time2",$input_time2);
        $this->assign("clientName",$clientName);
        $this->assign('total',$total);
        $this->assign('pagecount',$pagecount);
        $this->assign('page',$page);
        $this->assign('pagesize',15);
        $this->assign('i',1);
        $this->assign("fls",$fls);
        $this->assign("newLevel",$newLevel);
        $this->assign("status2",$status2);

        return $this->fetch();
    }

    public function old_fl(){
        
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

        $sqlstr3="select count(*) as total from oldflsqd where 1=1";

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
        $sqlstr2="select id,no,company,people,date,date2,status,allTime from oldflsqd where 1=1 ";

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

        $this->assign("username",$username);
        $this->assign("time",$time);
        $this->assign("input_time",$input_time);
        $this->assign("input_time2",$input_time2);
        $this->assign("clientName",$clientName);
        $this->assign("status2",$status2);
        $this->assign("newLevel",$newLevel);
        $this->assign("total",$total);
        $this->assign('pagecount',$pagecount);
        $this->assign('page',$page);
        $this->assign('pagesize',15);
        $this->assign('i',1);
        $this->assign('fls',$fls);

        return $this->fetch();
    }

    public function fl_line($id){
        
        session_start();
        $username=$_SESSION["username"];

        $sqlstr1=\think\Db::name("user_form")->field(["department","newLevel"])->where("username",$username)->select();

        $department=$sqlstr1[0]["department"];
        $newLevel=$sqlstr1[0]["newLevel"];

        $fl_lines=\think\Db::name("flsqd")->where("id",$id)->select();
        $fl_line=$fl_lines[0];

        $status_arr2=explode(",",$fl_line["status"]);
        $shr_arr2=explode(",",$fl_line["shr"]);
        $fl_line["shr_pop"]=array_pop($shr_arr2);
        $allTime_arr=explode(",",$fl_line["allTime"]);

        $status_arr=explode(",",$fl_line["status"]);
        $fl_line["status_pop"]=array_pop($status_arr);
        $fl_line["status_shift"]=array_shift($status_arr);

        $category_arr=explode(',',$fl_line["category"]);
        $productNo_arr=explode(',',$fl_line["productNo"]);
        $productName_arr=explode(',',$fl_line["productName"]);
        $amount_arr=explode(',',$fl_line["amount"]);
        $price_arr=explode(',',$fl_line["price"]);
        $fls_arr=explode(',',$fl_line["fls"]);
        $fwfxj_arr=explode(',',$fl_line["fwfxj"]);
        $flsName_arr=explode(',',$fl_line["flsName"]);
        $dj_arr=explode(',',$fl_line["dj"]);
        $sl_arr=explode(',',$fl_line["sl"]);
        $flfxj_arr=explode(',',$fl_line["flfxj"]);
        $hd_count=$fl_line["hd_count"];

        $sx_infos=\think\Db::name("use_sx")->field(["sqid","nowUseMoney","newMoney"])->where("fl_no",$fl_line["no"])->select();
        
        if($sx_infos !=[]){
            $sx_info=$sx_infos[0];

            $sx_filesNames=\think\Db::name("sx_form")->field("file_name")->where("sqid",$sx_info["sqid"])->select();

            if($sx_filesNames !=[]){
                $sx_filesName=$sx_filesNames[0];
            }else{
                $sx_filesName=[];
            }
        }else{
            $sx_info=[];
            $sx_filesName=[];
            $sx_info["sqid"]="";
            $sx_info["nowUseMoney"]=0;
            $sx_info["newMoney"]=0;
        }

        

        $phones=\think\Db::name("user_form")->field("phone")->where("username",$username)->select();
        $phone=$phones[0];


        $this->assign("username",$username);
        $this->assign("fl_line",$fl_line);
        $this->assign("title","辅料申请单");
        $this->assign("newLevel",$newLevel);
        $this->assign("department",$department);

        $this->assign("categorys",$category_arr);
        $this->assign("productNos",$productNo_arr);
        $this->assign("productNames",$productName_arr);
        $this->assign("amounts",$amount_arr);
        $this->assign("prices",$price_arr);
        $this->assign("flss",$fls_arr);
        $this->assign("fwfxjs",$fwfxj_arr);
        $this->assign("flsName",$flsName_arr);
        $this->assign("djs",$dj_arr);
        $this->assign("sls",$sl_arr);
        $this->assign("flfxjs",$flfxj_arr);
        
        $this->assign("sx_info",$sx_info);
        $this->assign("sx_filesName",$sx_filesName);
        $this->assign("shr_arrs",$shr_arr2);
        $this->assign("allTimes",$allTime_arr);
        $this->assign("statuss",$status_arr2);
        $this->assign("phone",$phone);
        
        
        $this->assign("hd_count",$hd_count);

        return $this->fetch();
    }

    public function fldj($fl){
  
        $prices=\think\Db::name("fl")->field("fl_price")->where("fl_name",$fl)->select();
        $price=$prices[0]["fl_price"];

        return $price;
    }

}