<?php
namespace app\index\controller;
use think\Controller;
use think\Request;

class Fl extends Controller{
    
    
    public function flsq(){
        $id=$this->request->param("id");

        date_default_timezone_set("Asia/Shanghai");
        session_start();
        $username=$_SESSION["username"];

        $sqlstr1=\think\Db::name("user_form")->field(["department","newLevel"])->where("username",$username)->select();

        $department=$sqlstr1[0]["department"];
        $newLevel=$sqlstr1[0]["newLevel"];

        if($id != ""){
            $fl_infos=\think\Db::name("flsqd")->where("id",$id)->select();
            $fl_info=$fl_infos[0];

            $fl_sxs=\think\Db::name("use_sx")->field(["sqid","nowUseMoney"])->where("fl_no",$fl_info["no"])->select();
            
            if($fl_sxs != []){
                $fl_sx=$fl_sxs[0];
            }else{
                $fl_sx="";
            }

        }else{
            
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

    public function save_fl(){
        session_start();
        $username=$_SESSION["username"];

        $status2=$this->request->param("status");
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

        $sqlstr3="select count(*) as total from flsqd where shr='$username'";        

        if($newLevel !="ADMIN"){
            $sqlstr3=$sqlstr3." and shr like '%$username%'";
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

        $sqlstr2="select id,no,company,people,date,date2,status,shr from flsqd where shr='$username'";

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

        }

        $this->assign("username",$username);
        $this->assign("title","已保存辅料单");
        $this->assign('total',$total);
        $this->assign('pagecount',$pagecount);
        $this->assign('page',$page);
        $this->assign('pagesize',15);
        $this->assign('i',1);
        $this->assign('fls',$fls);
        $this->assign("input_time",$input_time);
        $this->assign("input_time2",$input_time2);
        $this->assign("clientName",$clientName);
        $this->assign("status2",$status2);
        $this->assign("time",$time);

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

        $sqlstr2=$sqlstr2." order by date2 desc limit ".($page-1)*$pagesize.",$pagesize";

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

        $sqlstr2=$sqlstr2." order by date2 desc limit ".($page-1)*$pagesize.",$pagesize";


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

    public function old_flline($id){

        session_start();
        $username=$_SESSION["username"];

        $sqlstr1=\think\Db::name("user_form")->field(["department","newLevel"])->where("username",$username)->select();

        $department=$sqlstr1[0]["department"];
        $newLevel=$sqlstr1[0]["newLevel"];

        $old_fls=\think\Db::name("oldflsqd")->where("id",$id)->select();
        $old_fl=$old_fls[0];
    
        $status_arr=explode(",",$old_fl["status"]);
        $shr_arr2=explode(",",$old_fl["shr"]);
        $allTime_arr=explode(",",$old_fl["allTime"]);

        $old_fl["status_pop"]=array_pop($status_arr);

        $category_arr=explode(',',$old_fl["category"]);
        $productNo_arr=explode(',',$old_fl["productNo"]);
        $productName_arr=explode(',',$old_fl["productName"]);
        $amount_arr=explode(',',$old_fl["amount"]);
        $price_arr=explode(',',$old_fl["price"]);
        $fls_arr=explode(',',$old_fl["fls"]);
        $fwfxj_arr=explode(',',$old_fl["fwfxj"]);
        $flsName_arr=explode(',',$old_fl["flsName"]);
        $dj_arr=explode(',',$old_fl["dj"]);
        $sl_arr=explode(',',$old_fl["sl"]);
        $flfxj_arr=explode(',',$old_fl["flfxj"]);
        $hd_count=$old_fl["hd_count"];

        $this->assign("username",$username);
        $this->assign("title","旧系统辅料");
        $this->assign("old_fl",$old_fl);

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

        $this->assign("hd_count",$hd_count);

        return $this->fetch();
    }

    public function fldj($fl){
  
        $prices=\think\Db::name("fl")->field("fl_price")->where("fl_name",$fl)->select();
        $price=$prices[0]["fl_price"];

        return $price;
    }

    public function add_fl_Handle(){
        
        session_start();   
        $username=$_SESSION["username"];
        
        $sqlstr1=\think\Db::name("user_form")->field(["department","newLevel"])->where("username",$username)->select();

        $department=$sqlstr1[0]["department"];
        $newLevel=$sqlstr1[0]["newLevel"];
    
        //获取表中的字段
        $id=$this->request->param('id');
        $no=$this->request->param('no');
        $company=$this->request->param('company');
        $people=$this->request->param('people');
        $department=$this->request->param('department');
        $date=$this->request->param('date');
        $address=$this->request->param('address');
        $connection=$this->request->param('connection');
        $phone=$this->request->param('phone');
        $driving=$this->request->param('driving');
        $ishs=$this->request->param('ishs');
        $sd=$this->request->param('sd');
        $jkfs=$this->request->param('jkfs');
        $wlfs=$this->request->param('wlfs');
        $wlno=$this->request->param('wlno');
        $wlprice=$this->request->param('wlprice');
        $note=$this->request->param('note');
        $hd_sqslhj=$this->request->param('hd_sqslhj');
        $hd_fwfhj=$this->request->param('hd_fwfhj');
        $hd_flsl=$this->request->param('hd_flsl');
        $hd_flfhjsh=$this->request->param('hd_flfhjsh');
        $hd_fwfflfzj=$this->request->param('hd_fwfflfzj');
        $ywy=$people;
        $sqid=$this->request->param('sxid');
        $usesqmoney=$this->request->param('sxmoney');
        $option=$this->request->param('option');   //判断状态，数据保存0，数据提交1
    
        //多行数据字段传递
        $category="";
        $productNo="";
        $productName="";
        $amount="";
        $price="";
        $fls="";
        $fwfxj="";
        $flsName="";
        $dj="";
        $sl="";
        $flfxj="";
    
        //表中的最大行数为20
        $hd_count=$_POST['hd_count'];
    
        if($hd_count>30){
            $hd_count=29;
        }
    
        for($i=0;$i<=(int)$hd_count;$i++){
            $category=$category.$this->request->param('category'.$i).",";
            $productNo=$productNo.$this->request->param('productNo'.$i).",";
            $productName=$productName.$this->request->param('productName'.$i).",";
            $amount=$amount.$this->request->param('amount'.$i).",";
            $price=$price.$this->request->param('price'.$i).",";
            $fls=$fls.$this->request->param('fls'.$i).",";
            $fwfxj=$fwfxj.(float)$this->request->param('amount'.$i)*(float)$this->request->param('price'.$i)*(float)$this->request->param('fls'.$i).",";
            $flsName=$flsName.$this->request->param('flsName'.$i).",";
            $dj=$dj.$this->request->param('dj'.$i).",";
            $sl=$sl.$this->request->param('sl'.$i).",";
            $flfxj=$flfxj.(float)$this->request->param('dj'.$i)*(float)$this->request->param('sl'.$i).",";
        }
    
        $maxIDs=\think\Db::name("flsqd")->field("max(id)")->select();
        $maxID=$maxIDs[0]["max(id)"];
    
        //获取当前流程审批节点
        $p_no=$this->request->get("no");
    
        //获取下个节点名称
        $names=\think\Db::name("flprogress")->field("name")->where("number",(int)$p_no+1)->select();
        $name=$names[0]["name"];
    

        //M级审批单据
        if($name == "M级审批单据"){
            
            $sps=\think\Db::name("user_form")->field("username")->where("department","like","%$department%")->where("newLevel","M")->select();
            $sp=$sps[0]["username"];
        
            $sp=$username.",".$sp;
            
            $name="KA级提交单据,".$name;
        
            $fileName="";
        
            //防止辅料单重号
            if($id==""){

                $count_nos=\think\Db::name("flsqd")->field("count(*)")->where("no",$no)->select();
                $count_no=$count_nos[0]["count(*)"];
    
                if($count_no != '0'){
                    $no_sqls=\think\Db::name("fl_no")->field("no")->where("department",$department)->select();
                    $no_sql=$no_sqls[0]["no"];
    
                    $no_arr=explode("-",$no_sql);  
                    $no_old=array_pop($no_arr);
                    $no_new=$no_old+1;
                    $no= str_replace($no_old,$no_new,$no_sql);
                }
            }
    
            //option=1提交单据
            if($option==1){
                //未被保存过的单据
                if($id =="" and $no != ""){

                    $no_update=\think\Db::query("update fl_no set no='$no' where department='$department'");
            
                    $sqlstr1=\think\Db::query("insert into flsqd values('$maxID'+1,'$no','$company','$people','$department','$date','$address',".
                        "'$connection','$phone','$driving','$ishs','$category','$productNo','$productName',".
                        "'$amount','$price','$fls','$fwfxj','$flsName','$dj','$sl','$flfxj','$sd','$jkfs',".
                        "'$wlfs','$wlno','$wlprice','$note','$hd_sqslhj','$hd_fwfhj','$hd_flsl','$hd_flfhjsh',".
                        "'$hd_fwfflfzj','$hd_count'+1,'$ywy','$name','','','$sp','','$date','$fileName')");
                //已被保存或提交后拒绝的单据
                }else{
                    $sqlstr1=\think\Db::query("update flsqd set no='$no',company='$company',people='$people',department='$department',date='$date',address='$address',".
                    "connection='$connection',phone='$phone',driving='$driving',ishs='$ishs',category='$category',productName='$productName',productNo='$productNo',".
                    "amount='$amount',price='$price',fls='$fls',fwfxj='$fwfxj',flsName='$flsName',dj='$dj',sl='$sl',flfxj='$flfxj',sd='$sd',jkfs='$jkfs',".
                    "wlfs='$wlfs',wlno='$wlno',wlprice='$wlprice',note='$note',hd_sqslhj='$hd_sqslhj',hd_fwfhj='$hd_fwfhj',hd_flsl='$hd_flsl',hd_flfhjsh='$hd_flfhjsh',".
                    "hd_fwfflfzj='$hd_fwfflfzj',hd_count='$hd_count'+1,ywy='$ywy',status='$name',shr='$sp',csr='',allTime='$date',file='$fileName' where id='$id'");
                }    
            }else{
                //点击一键保存的执行流程
                if($id =="" and $no != ""){
                    $no_update=\think\Db::query("update fl_no set no='$no' where department='$department'");
            
                    $sqlstr1=\think\Db::query("insert into flsqd values('$maxID'+1,'$no','$company','$people','$department','$date','$address',".
                        "'$connection','$phone','$driving','$ishs','$category','$productNo','$productName',".
                        "'$amount','$price','$fls','$fwfxj','$flsName','$dj','$sl','$flfxj','$sd','$jkfs',".
                        "'$wlfs','$wlno','$wlprice','$note','$hd_sqslhj','$hd_fwfhj','$hd_flsl','$hd_flfhjsh',".
                        "'$hd_fwfflfzj','$hd_count'+1,'$ywy','KA级提交单据','','','$username','','','$fileName')");
                }else{
    
                    $sqlstr1=\think\Db::query("update flsqd set no='$no',company='$company',people='$people',department='$department',date='$date',address='$address',".
                    "connection='$connection',phone='$phone',driving='$driving',ishs='$ishs',category='$category',productName='$productName',productNo='$productNo',".
                    "amount='$amount',price='$price',fls='$fls',fwfxj='$fwfxj',flsName='$flsName',dj='$dj',sl='$sl',flfxj='$flfxj',sd='$sd',jkfs='$jkfs',".
                    "wlfs='$wlfs',wlno='$wlno',wlprice='$wlprice',note='$note',hd_sqslhj='$hd_sqslhj',hd_fwfhj='$hd_fwfhj',hd_flsl='$hd_flsl',hd_flfhjsh='$hd_flfhjsh',".
                    "hd_fwfflfzj='$hd_fwfflfzj',hd_count='$hd_count'+1,ywy='$ywy',status='KA级提交单据',shr='$username',csr='',allTime='',file='$fileName' where id='$id'");
                }
            }
        
            //提交后扣减授信金额
            if($sqid !="" and $usesqmoney !=""){

                $maxID2s=\think\Db::name("use_sx")->field("max(id)")->select();
                $maxID2=$maxID2s[0]["max(id)"];
    
                if($maxID==""){
                    $maxID2=0;
                }
    
                $sqlstr3=\think\Db::query("select distinct sqmoney,newMoney from use_sx where sqid='$sqid'");
                    
                $sqmoney=$sqlstr3[0]["sqmoney"];
                $newMoney=$sqlstr3[0]["newMoney"];
    
                $useMoney=(int)$sqmoney-(int)$newMoney;
                            
                $remainMoney=(int)$sqmoney-(int)$useMoney-(int)$usesqmoney;
                
                $sqlstr4=\think\Db::query("insert into use_sx values('$maxID2'+1,'$sqid','$sqmoney','$useMoney','$usesqmoney','$remainMoney','$no','$department','$date','使用授信','$remainMoney')");
                $sqlstr5=\think\Db::query("update use_sx set newMoney='$remainMoney' where sqid='$sqid'");
    
            }
        }
    
        //提交后跳转页面

        if($option ==1){
            if($id ==""){
                //提交后跳转maxID+1
                return redirect('/index.php/Index/fl/fl_line.html?id='.($maxID+1));
            }else{
                //提交后跳转当前ID
                return redirect('/index.php/Index/fl/fl_line.html?id='.$id);
            }
        }else{
            if($id ==""){
                //提交后跳转maxID+1
                return redirect('/index.php/Index/fl/save_fl.html?id='.($maxID+1));
            }else{
                //提交后跳转当前ID
                return redirect('/index.php/Index/fl/save_fl.html?id='.$id);
            }
        }

    }

    public function fl_yzm(){
        $phone=$this->request->param('phone');

        function send_sms($phone,$message) {
            $appkey = "zlsy66";
            $appcode = "1000";
            $appsecret="440I53";
            $timestamp=time()*1000;
    
            $sign=md5($appkey.$appsecret.$timestamp);
    
            $data=array(
                    "sign" => $sign,
                    'appkey'=>$appkey,
                    'appcode'=>$appcode,
                    'timestamp'=>$timestamp,
                    'sms' =>[ array(
                        'msg'=>$message,
                        'phone'=> $phone,
                        'extend'=>''
                    )]
                );
    
            $url = "http://39.97.4.102:9090/sms/distinct/v1";//此处为短信接口的链接，具体的用法参考短信接口的说明
            $curl = curl_init(); //初始化一个新的会话
            
            $timeout = 15;   
    
            $headers = array('Accept:text/plain;charset=utf-8', 'Content-type:application/json','charset=utf-8'); 
    
            /* 设置验证方式 */
            curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
            
            curl_setopt ($curl, CURLOPT_RETURNTRANSFER, 1);
    
            /* 设置返回结果为流 */
            curl_setopt($curl, CURLOPT_HEADER, 0);
            curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
    
            /* 设置超时时间*/
            curl_setopt ($curl, CURLOPT_CONNECTTIMEOUT, $timeout);
    
            /* 设置通信方式 */
            curl_setopt($curl, CURLOPT_POST, 1);
            curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
    
            curl_setopt ($curl, CURLOPT_URL, $url);        
    
            curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($data));
    
            $res = curl_exec($curl);  //执行会话
    
            curl_close($curl);   // 关闭会话，释放资源。
            
        }
    
        $chars = array("1", "2",  "3", "4", "5", "6", "7", "8", "9" ); 
        $charsLen = count($chars) - 1; 
        shuffle($chars);   
        $yzm = ""; 
        for ($i=0; $i<4; $i++) 
        { 
            $yzm .= $chars[mt_rand(0, $charsLen)]; 
        } 
    
        send_sms($phone,'【上海兆林实业】您的CRM系统的验证码为：'.$yzm);
    
        echo $yzm;
    }

    public function is_print(){
        $id=$this->request->param('id');
        $department=$this->request->param('department');

        if($id != ""){

            if($department=="义乌部"){
                $update_fl_print=\think\Db::query("update flsqd set isprint='1' where id=$id");
            }

            echo 0;
        }
    }

    public function search_sx_money(){

        $sxid=$this->request->param('sxid');

        session_start();
        $username=$_SESSION["username"];

        $sqlstr=\think\Db::query("select newMoney from use_sx where sqid='$sxid'");
    
        $newMoney="";
    
        if($sqlstr !=[]){
            $newMoney=$sqlstr[0]["newMoney"];

            if($newMoney==""){
                echo 0;
            }else{
                echo $newMoney;
            }
        }else{
            echo 0;
        }
    }

    public function zf_fl(){
        $id=$this->request->param("id");
        $zf_note=$this->request->param("zf_note");

        $notes=\think\Db::name("flsqd")->field("note")->where("id",$id)->select();
        $note=$notes[0]["flsqd"];

        $note=$note."/辅料单作废，备注：".$zf_note;

        //重新编辑需要返还授信金额
        $sqlstr4=\think\Db::query("select count(*) from use_sx where fl_no = (select no from flsqd where id=$id)");
        $count=$sqlstr4[0]["count(*)"];

        if($count > 0){
            $sqlstr5=\think\Db::query("select nowUseMoney,sqid from use_sx where  fl_no = (select no from flsqd where id=$id)");

            $nowUseMoney=$sqlstr5[0]["nowUseMoney"];
            $sqid=$sqlstr5[0]["sqid"];

            $update_sx=\think\Db::query("update use_sx set newMoney= $nowUseMoney + newMoney where sqid='$sqid'");

            $del_sx=\think\Db::query("delete from use_sx where fl_no = (select no from flsqd where id=$id)");
        }


        $update_zf=\think\Db::query("update flsqd set note='$note',status='作废' where id=$id");
        
        return redirect("/index.php/Index/fl/fl_line.php?id=".$id);
          
    }

    public function fl_liucheng(){
        session_start();
        $username=$_SESSION["username"]; 

        $sqlstr1=\think\Db::name("user_form")->field(["department","newLevel"])->where("username",$username)->select();

        $my_department=$sqlstr1[0]["department"];
        $newLevel=$sqlstr1[0]["newLevel"];

        date_default_timezone_set("Asia/Shanghai");
        $time=date('Y-m-d H:i:s', time());
        
        $id=$this->request->param("id"); 
        $option=$this->request->param("option"); 

        $sqlstr0=\think\Db::query("select department,jkfs,status,shr,allTime from flsqd where id='$id'");

        $department=$sqlstr0[0]["department"];
        $jkfs=$sqlstr0[0]["jkfs"];
        $status=$sqlstr0[0]["status"];
        $shr=$sqlstr0[0]["shr"];
        $shTime="";
        

        if($option==1){
            //同意流程
            
            $status_arr=explode(",",$status);
            $shr_arr=explode(",",$shr);

            $status_now=array_pop($status_arr);
            $shr_now=array_pop($shr_arr);

            if($shr_now==$username){
                $sqlstr3=\think\Db::query("select number from flprogress where name='$status_now' ");
                $number=$sqlstr3[0]["number"];
        
                $number_forward=$number+1;
        
                $sqlstr4=\think\Db::query("select name,sp from flprogress where number='$number_forward' ");
                $status_forward=$sqlstr4[0]["name"];
                $sp_forward=$sqlstr4[0]["sp"];

        
                if(($jkfs=="全现金" and $status_forward == "商业运营审批单据") or (($jkfs=="全授信" or $jkfs=="标费补贴") and $status_forward == "财务审批单据")){
        
                    $sqlstr5=\think\Db::query("select number from flprogress where name='$status_forward' ");
                    $number=$sqlstr5[0]["number"];

                    $number=$number+1;
        
                    $sqlstr6=\think\Db::query("select name,sp from flprogress where number='$number' ");

                    $status_forward=$sqlstr6[0]["name"];
                    $sp_forward=$sqlstr6[0]["sp"];
      
                }
        
                $status_new=$status.",".$status_forward;
                $sp_new=$shr.",".$sp_forward;
                $shTime_new=$shTime.",".$time;
        
                if($status_forward=="已归档单据"){
                    $sqlstr_fl=\think\Db::query("update flsqd set status='$status_new',shr='$sp_new',allTime='$shTime_new',date2='$time',file='$username' where id='$id'");
                }else{
                    $sqlstr_fl=\think\Db::query("update flsqd set status='$status_new',shr='$sp_new',allTime='$shTime_new',file='$username' where id='$id'");
                }

                //财务，商业运营加入key
                if($my_department=="财务部" or ($my_department=="商业运营部" and $status_forward !="已归档单据")){
        
                    //获取辅料申请单最大ID
                    $sqlstr=\think\Db::query("select max(id) from fl_key");
                    $maxID=$sqlstr[0]["max(id)"];
                    
                    if($maxID==""){
                        $maxID=0;
                    }
        
                    $sqlstr_k=\think\Db::query("insert into fl_key values('$maxID'+1,'$id',1,'$time')");
                }

                return redirect('/index.php/Index/fl/w_fl.html');
            }else{
                //不能重复提交表单
                return redirect('/index.php/Index/fl/fl_line.html?id='.$id);
            }
        
        }elseif($option==3){
            //义务审批单据走此逻辑

            $wlfs=$_GET["wlfs"];
            $wlno=$_GET["wlno"];
            $wlprice=$_GET["wlprice"];
            $note=$_GET["note"];

            //找出当前流程序号（同意状态）
            $sqlstr1=\think\Db::name("flprogress")->field("number")->where("sp",$username)->where("no",1)->select();
            $number=$sqlstr1[0]["number"];

            $sqlstr4=\think\Db::name("flsqd")->field(["status","shr","allTime"])->where("id",$id)->select();
            
            $qqstatus=$sqlstr4[0]["status"];
            $qqshr=$sqlstr4[0]["shr"];
            $allTime=$sqlstr4[0]["allTime"];

            //找出下个流程信息
            $sqlstr2=\think\Db::name("flprogress")->field(["name","sp"])->where("number",$number+1)->where("no",1)->select();

            $name=$sqlstr2[0]["name"];
            $sp=$sqlstr2[0]["sp"];
            
            $sp=$qqshr.",".$sp;
            $name=$qqstatus.",".$name;

            $allTime=$allTime.",".$time;

            //将下个流程信息放入
            $sqlstr3=\think\Db::table("flsqd")->where("id",$id)->update(['status'=>$name,'shr'=>$sp,'allTime'=>$allTime,'wlfs'=>$wlfs,'wlno'=>$wlno,'wlprice'=>$wlprice,'note'=>$note]);

            return redirect('/index.php/Index/fl/w_fl.html');

        }elseif($option==6){
            //业务员重新编辑

            //重新编辑需要返还授信金额
            $sqlstr=\think\Db::query("select count(*) from use_sx where fl_no = (select no from flsqd where id=$id)");
            $count=$sqlstr[0]["count(*)"];

            if($count > 0){
                $sqlstr2=\think\Db::query("select nowUseMoney,sqid from use_sx where  fl_no = (select no from flsqd where id=$id)");   

                $nowUseMoney=$sqlstr2[0]["nowUseMoney"];
                $sqid=$sqlstr2[0]["sqid"];

                $sqlstr3=\think\Db::query("update use_sx set newMoney= $nowUseMoney + newMoney where sqid='$sqid'");

                $sqlstr4=\think\Db::query("delete from use_sx where fl_no = (select no from flsqd where id=$id)");   
            }

            return redirect('/index.php/Index/fl/flsq.html?id='.$id);

        }elseif($option==7){
            //KA删除单据
            $sqlstr=\think\Db::name("flsqd")->where("id",$id)->delete();

            return redirect('/index.php/Index/fl/w_fl.html');
        
        }elseif($option==8){

            //义乌修改单据
            $wlfs=$_GET["wlfs"];
            $wlno=$_GET["wlno"];
            $wlprice=$_GET["wlprice"];
            $note=$_GET["note"];

            $sqlstr3=\think\Db::table("flsqd")->where("id",$id)->update(['wlfs'=>$wlfs,'wlno'=>$wlno,'wlprice'=>$wlprice,'note'=>$note]);

            return redirect('/index.php/Index/fl/w_fl.html');

        }else{
            //拒绝，待业务员审核
            $sqlstr=\think\Db::name("flsqd")->field(["status","shr","allTime"])->where("id",$id)->select();

            $qqstatus=$sqlstr[0]["status"];
            $qqshr=$sqlstr[0]["shr"];
            $allTime=$sqlstr[0]["allTime"];
            
            $arr_shr=explode(",",$qqshr);
            $shr=array_shift($arr_shr);

            $sp=$qqshr.",".$shr;
            $name=$qqstatus.",待KA审核单据";
            $time=date('Y-m-d H:i:s', time());
            $allTime=$allTime.",".$time;

            $sqlstr2=\think\Db::table("flsqd")->where("id",$id)->update(['status'=>$name,'shr'=>$sp,'allTime'=>$allTime]);

            //财务，商业运营拒绝删除key
            $sqlstr3=\think\Db::name("fl_key")->field("count(*)")->where("fl_no",$id)->select();
            $count_key=$sqlstr3[0]["count(*)"];
            
            if($count_key>0){
                $sqlstr4=\think\Db::table("fl_key")->where("fl_no",$id)->delete();
            }

            return redirect('/index.php/Index/fl/fl_line.html?id='.$id);
        }
    }
}