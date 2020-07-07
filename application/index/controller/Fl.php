<?php
namespace app\index\controller;
use think\Controller;
use think\Request;
use think\Db;

class Fl extends Controller{
    
    
    public function flsq(){
        $id=$this->request->param("id");

        date_default_timezone_set("Asia/Shanghai");
        session_start();
        $username=$_SESSION["username"];

        $sqlstr1=Db::name("user_form")->field(["department","newLevel"])->where("username",$username)->select();

        $department=$sqlstr1[0]["department"];
        $newLevel=$sqlstr1[0]["newLevel"];

        if($id != ""){
            $fl_infos=Db::name("flsqd")->where("id",$id)->select();
            $fl_info=$fl_infos[0];

            $fl_sxs=Db::name("use_sx")->field(["sqid","nowUseMoney"])->where("fl_no",$fl_info["no"])->select();
            
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

            $fl_infos=Db::name("fl_no")->field("no")->where("department",$department)->select();
            
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

        $fl_wlfs=Db::name("fl_wlfs")->field("name")->select();

        $fl_names=Db::name("fl")->field("fl_name")->order("fl_name")->select();

        $fl_jkfss=Db::name("fl_jkfs")->field("name")->select();

        $fl_sxInfos=Db::query("select distinct a.sqid from sx_form a,use_sx b where a.sqid=b.sqid and (a.department='$department' or a.gxDepartment='$department') and a.status='已生效' and b.newMoney > 0");
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

        $sqlstr1=Db::name("user_form")->field(["department","newLevel"])->where("username",$username)->select();

        $department=$sqlstr1[0]["department"];
        $newLevel=$sqlstr1[0]["newLevel"];

        //分页代码
        if(!isset($_GET["page"]) || !is_numeric($_GET["page"])){
            $page=1;
        }else{
            $page=intval($_GET["page"]);
        }

        $pagesize=15;

        $sqlstr3="select count(*) as total from flsqd where 1=1 ";        

        $sqlstr3=$sqlstr3." and status='KA级提交单据' ";

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

        $fl_counts=Db::query($sqlstr3);
        $total=$fl_counts[0]["total"];

        if($total%$pagesize==0){
            $pagecount=intval($total/$pagesize);
        }else{
            $pagecount=ceil($total/$pagesize);
        }

        $count=0;

        $sqlstr2="select id,no,company,people,date,date2,status,shr from flsqd where 1=1";

        $sqlstr2=$sqlstr2." and status='KA级提交单据' ";

        if($newLevel !="ADMIN"){
            $sqlstr2=$sqlstr2." and shr like '%$username%'";
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

        $fls=Db::query($sqlstr2);

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
        
        //逻辑判断，关键字属于哪一类
        $keywords=$this->request->param("keywords");
        $option=0;

        $fl_no_count=Db::name("flsqd")->field("count(*)")->where("no","like",'%'.$keywords.'%')->find();
        $fl_company_count=Db::name("flsqd")->field("count(*)")->where("company","like",'%'.$keywords.'%')->find();
        $fl_department_count=Db::name("flsqd")->field("count(*)")->where("department","like",'%'.$keywords.'%')->find();
        $fl_people_count=Db::name("flsqd")->field("count(*)")->where("people","like",'%'.$keywords.'%')->find();
        
        if($fl_no_count["count(*)"] != 0){
            $option=1;
        }elseif($fl_company_count["count(*)"] != 0){
            $option=2;
        }elseif($fl_department_count["count(*)"] != 0){
            $option=3;
        }elseif($fl_people_count["count(*)"] != 0){
            $option=4;
        }

        $sqlstr1=Db::name("user_form")->field(["department","newLevel"])->where("username",$username)->select();

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

        if($option==1){
            $sqlstr3=$sqlstr3." and no like '%$keywords%' ";
        }elseif($option==2){
            $sqlstr3=$sqlstr3." and company like '%$keywords%' ";
        }elseif($option==3){
            $sqlstr3=$sqlstr3." and department like '%$keywords%' ";
        }elseif($option==4){
            $sqlstr3=$sqlstr3." and people like '%$keywords%' ";
        }


        $fl_counts=Db::query($sqlstr3);
        $total=$fl_counts[0]["total"];

        if($total%$pagesize==0){
            $pagecount=intval($total/$pagesize);
        }else{
            $pagecount=ceil($total/$pagesize);
        }

        $count=0;

        //表格数据
        $sqlstr2="select id,no,company,people,date,date2,status,shr,jkfs from flsqd where (not status like '%已归档单据%' and not status like '%品牌部归档%') and not status like '%作废%'  and (not status='KA级提交单据' and not shr='$username')";

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

        if($option==1){
            $sqlstr2=$sqlstr2." and no like '%$keywords%' ";
        }elseif($option==2){
            $sqlstr2=$sqlstr2." and company like '%$keywords%' ";
        }elseif($option==3){
            $sqlstr2=$sqlstr2." and department like '%$keywords%' ";
        }elseif($option==4){
            $sqlstr2=$sqlstr2." and people like '%$keywords%' ";
        }

        $sqlstr2=$sqlstr2." order by id desc limit ".($page-1)*$pagesize.",$pagesize";

        $fls=Db::query($sqlstr2);

        for($i=0;$i<sizeof($fls);$i++){
            $arr_status=explode(",",$fls[$i]["status"]);
            $status=array_pop($arr_status);
            $fls[$i]["status"]=$status;

            $arr_shr=explode(",",$fls[$i]["shr"]);
            $shr=array_pop($arr_shr);
            $fls[$i]["shr"]=$shr;

            //加入key
            $key_count=Db::name("fl_key")->field("count(*)")->where("fl_no",$fls[$i]["id"])->select();
            $fls[$i]["key"]=$key_count[0]["count(*)"];

        }

        $data=[
            'title' => '待审核辅料单',
            'username' => $username,
            'keywords' => $keywords,
            'fls' => $fls,
            'status2' => $status2,
            'newLevel' => $newLevel,
            'time' => $time,
            'input_time' => $input_time,
            'input_time2' => $input_time2,
            'total' => $total,
            'pagecount' => $pagecount,
            'page' => $page,
            'pagesize' => 15
        ];

        return $this->fetch('',$data);
    }

    public function d_fl(){
        
        session_start();
        $username=$_SESSION["username"];

        $status2=$this->request->param("status2");
        $time=$this->request->param("time");
        $input_time=$this->request->param("input_time");
        $input_time2=$this->request->param("input_time2");

        //逻辑判断，关键字属于哪一类
        $keywords=$this->request->param("keywords");
        $option=0;

        $fl_no_count=Db::name("flsqd")->field("count(*)")->where("no","like",'%'.$keywords.'%')->find();
        $fl_company_count=Db::name("flsqd")->field("count(*)")->where("company","like",'%'.$keywords.'%')->find();
        $fl_department_count=Db::name("flsqd")->field("count(*)")->where("department","like",'%'.$keywords.'%')->find();
        $fl_people_count=Db::name("flsqd")->field("count(*)")->where("people","like",'%'.$keywords.'%')->find();
        
        if($fl_no_count["count(*)"] != 0){
            $option=1;
        }elseif($fl_company_count["count(*)"] != 0){
            $option=2;
        }elseif($fl_department_count["count(*)"] != 0){
            $option=3;
        }elseif($fl_people_count["count(*)"] != 0){
            $option=4;
        }


        $sqlstr1=Db::name("user_form")->field(["department","newLevel"])->where("username",$username)->select();

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

        if($option==1){
            $sqlstr3=$sqlstr3." and no like '%$keywords%' ";
        }elseif($option==2){
            $sqlstr3=$sqlstr3." and company like '%$keywords%' ";
        }elseif($option==3){
            $sqlstr3=$sqlstr3." and department like '%$keywords%' ";
        }elseif($option==4){
            $sqlstr3=$sqlstr3." and people like '%$keywords%' ";
        }

        $fl_counts=Db::query($sqlstr3);
        $total=$fl_counts[0]["total"];

        if($total%$pagesize==0){
            $pagecount=intval($total/$pagesize);
        }else{
            $pagecount=ceil($total/$pagesize);
        }

        $count=0;

        //表格数据
        $sqlstr2="select id,no,company,people,date,date2,status,shr,allTime,jkfs from flsqd where (status like '%已归档单据%' or status like '%品牌部归档%' or status like '%作废%') ";

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

        if($option==1){
            $sqlstr2=$sqlstr2." and no like '%$keywords%' ";
        }elseif($option==2){
            $sqlstr2=$sqlstr2." and company like '%$keywords%' ";
        }elseif($option==3){
            $sqlstr2=$sqlstr2." and department like '%$keywords%' ";
        }elseif($option==4){
            $sqlstr2=$sqlstr2." and people like '%$keywords%' ";
        }

        $sqlstr2=$sqlstr2." order by date2 desc limit ".($page-1)*$pagesize.",$pagesize";

        $fls=Db::query($sqlstr2);

        for($i=0;$i<sizeof($fls);$i++){
            $arr_status=explode(",",$fls[$i]["status"]);
            $status=array_pop($arr_status);
            $fls[$i]["status"]=$status;

            $arr_shr=explode(",",$fls[$i]["shr"]);
            $shr=array_pop($arr_shr);
            $fls[$i]["shr"]=$shr;

            //加入key
            $key_count=Db::name("fl_key")->field("count(*)")->where("fl_no",$fls[$i]["id"])->select();
            $fls[$i]["key"]=$key_count[0]["count(*)"];

        }

        $data=[
            'title' => '已完成辅料单',
            'username' => $username,
            'keywords' => $keywords,
            'fls' => $fls,
            'status2' => $status2,
            'newLevel' => $newLevel,
            'time' => $time,
            'input_time' => $input_time,
            'input_time2' => $input_time2,
            'total' => $total,
            'pagecount' => $pagecount,
            'page' => $page,
            'pagesize' => 15
        ];

        return $this->fetch('',$data);
    }

    public function old_fl(){
        
        session_start();
        $username=$_SESSION["username"];

        $status2=$this->request->param("status2");
        $time=$this->request->param("time");
        $input_time=$this->request->param("input_time");
        $input_time2=$this->request->param("input_time2");
        $clientName=$this->request->param("clientName");

        $sqlstr1=Db::name("user_form")->field(["department","newLevel"])->where("username",$username)->select();

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

        $fl_counts=Db::query($sqlstr3);
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


        $fls=Db::query($sqlstr2);

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

        $sqlstr1=Db::name("user_form")->field(["department","newLevel"])->where("username",$username)->find();

        $department=$sqlstr1["department"];
        $newLevel=$sqlstr1["newLevel"];

        $fl_line=Db::name("flsqd")->where("id",$id)->find();

        if(strpos($fl_line["shr"],",") == false){
            $fl_line["shr"]=$fl_line["shr"].",";
        }
        $status_arr=explode(",",$fl_line["status"]);
        $status_arr2=explode(",",$fl_line["status"]);
        $shr_arr2=explode(",",$fl_line["shr"]);
        $allTime_arr=explode(",",$fl_line["allTime"]);
        
        $fl_line["shr_pop"]=array_pop($shr_arr2);
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

        $sx_infos=Db::name("use_sx")->field(["sqid","nowUseMoney","newMoney"])->where("fl_no",$fl_line["no"])->select();
        
        if($sx_infos !=[]){
            $sx_info=$sx_infos[0];

            $sx_filesNames=Db::name("sx_form")->field("file_name")->where("sqid",$sx_info["sqid"])->find();

            if($sx_filesNames !=[]){
                $sx_filesName=$sx_filesNames["file_name"];
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

        $phones=Db::name("user_form")->field("phone")->where("username",$username)->select();
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

        $sqlstr1=Db::name("user_form")->field(["department","newLevel"])->where("username",$username)->select();

        $department=$sqlstr1[0]["department"];
        $newLevel=$sqlstr1[0]["newLevel"];

        $old_fls=Db::name("oldflsqd")->where("id",$id)->select();
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
  
        $prices=Db::name("fl")->field("fl_price")->where("fl_name",$fl)->select();
        $price=$prices[0]["fl_price"];

        return $price;
    }

    public function add_fl_Handle(){
        
        session_start();   
        $username=$_SESSION["username"];
        
        $sqlstr1=Db::name("user_form")->field(["department","newLevel"])->where("username",$username)->select();

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
        $upfile=$this->request->param('upfile');
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
    
        //表中的最大行数为30
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
    
        //获取当前流程审批节点
        $p_no=$this->request->get("no");

        if($jkfs == ""){
            $jkfs="全现金";
        }

        //查询走的流程
        $flprocess_id=DB::name("flprogress_all")->field("id")->where("jkfs",$jkfs)->where("status","生效中")->where("change_date",">",$date)->find();
        
        if($flprocess_id == []){
            $this->error('未查询到流程，请联系管理员！','/index.php/Index/fl/flsq.html','',1);
        }else{
            $flprocess_id=$flprocess_id["id"];

            //获取当前节点名称
            if($newLevel=="M"){
                $p_no=(int)$p_no+1;
            }else{
                $p_no=(int)$p_no;
            }

            $names=Db::name("flprogress")->field("name")->where("flprogress_id",$flprocess_id)->where("number",$p_no)->find();
            $name_now=$names["name"];

            //获取下个节点名称
            $p_no=(int)$p_no+1;

            $names=Db::name("flprogress")->field("name")->where("flprogress_id",$flprocess_id)->where("number",$p_no)->find();
            $name_next=$names["name"];

            
            //获取下个节点审核人
            if($newLevel=="M"){
                $shr=Db::name("flprogress")->field("sp")->where("flprogress_id",$flprocess_id)->where("number",$p_no)->find();
                $shr=$shr["sp"];
            }else{
                $shr=Db::name("user_form")->field("username")->where("department","like","%$department%")->where("newLevel","M")->find();
                $shr=$shr["username"];
            }

            if($option==1){
                //option=1提交单据
                $name=$name_now.",".$name_next;
                $shr=$username.",".$shr;
            }else{
                //option=0保存单据
                $name=$name_now;
                $shr=$username;
            }

            //附件上传
            $fileName="";

            if(!empty($_FILES['upfile']['name'])){
                $fileinfo=$_FILES['upfile'];
                if($fileinfo['size']<2097152 && $fileinfo['size']>0){
                    $path=getcwd()."/file/fl_file/".$_FILES["upfile"]["name"];
                    move_uploaded_file($fileinfo['tmp_name'],$path);
                    
                    $fileName=$_FILES['upfile']['name'];
                }
            }

            //防止辅料单重号
            if($id==""){
                $count_nos=Db::name("flsqd")->field("count(*)")->where("no",$no)->select();
                $count_no=$count_nos[0]["count(*)"];
    
                if($count_no != '0'){
                    $no_sqls=Db::name("fl_no")->field("no")->where("department",$department)->select();
                    $no_sql=$no_sqls[0]["no"];
    
                    $no_arr=explode("-",$no_sql);  
                    $no_old=array_pop($no_arr);
                    $no_new=$no_old+1;
                    $no= str_replace($no_old,$no_new,$no_sql);
                }
            }

            //未被保存过的单据
            if($id =="" and $no != ""){
                $result=Db::table("flsqd")->insert([
                    'no' => $no,
                    'company' => $company,
                    'people' => $people,
                    'department' => $department,
                    'date' => $date,
                    'address' => $address,
                    'connection' => $connection,
                    'phone' => $phone,
                    'driving' => $driving,
                    'ishs' => $ishs,
                    'category' => $category,
                    'productNo' => $productNo,
                    'productName' => $productName,
                    'amount' => $amount,
                    'price' => $price,
                    'fls' => $fls,
                    'fwfxj' => $fwfxj,
                    'flsName' => $flsName,
                    'dj' => $dj,
                    'sl' => $sl,
                    'flfxj' => $flfxj,
                    'sd' => $sd,
                    'jkfs' => $jkfs,
                    'wlfs' => $wlfs,
                    'wlno' => $wlno,
                    'wlprice' => $wlprice,
                    'note' => $note,
                    'hd_sqslhj' => $hd_sqslhj,
                    'hd_fwfhj' => $hd_fwfhj,
                    'hd_flsl' => $hd_flsl,
                    'hd_flfhjsh' => $hd_flfhjsh,
                    'hd_fwfflfzj' => $hd_fwfflfzj,
                    'hd_count' => $hd_count+1,
                    'ywy' => $ywy,
                    'status' => $name,
                    'shr' => $shr,
                    'date' => $date,
                    'file' => $fileName,
                    'allTime' => $date
                ]);

                $id=Db::name('flsqd')->getLastInsID();

                if($result == 1){
                    $result2=DB::table("fl_no")->where("department",$department)->update(['no' => $no]);
                }else{
                    $this->error('新增单据失败！','/index.php/Index/fl/flsq.html','',1);
                }        
                    
            //已被保存或提交后拒绝的单据
            }else{
                $result=Db::table("flsqd")->where("id",$id)->update([
                    'no' => $no,
                    'company' => $company,
                    'people' => $people,
                    'department' => $department,
                    'date' => $date,
                    'address' => $address,
                    'connection' => $connection,
                    'phone' => $phone,
                    'driving' => $driving,
                    'ishs' => $ishs,
                    'category' => $category,
                    'productNo' => $productNo,
                    'productName' => $productName,
                    'amount' => $amount,
                    'price' => $price,
                    'fls' => $fls,
                    'fwfxj' => $fwfxj,
                    'flsName' => $flsName,
                    'dj' => $dj,
                    'sl' => $sl,
                    'flfxj' => $flfxj,
                    'sd' => $sd,
                    'jkfs' => $jkfs,
                    'wlfs' => $wlfs,
                    'wlno' => $wlno,
                    'wlprice' => $wlprice,
                    'note' => $note,
                    'hd_sqslhj' => $hd_sqslhj,
                    'hd_fwfhj' => $hd_fwfhj,
                    'hd_flsl' => $hd_flsl,
                    'hd_flfhjsh' => $hd_flfhjsh,
                    'hd_fwfflfzj' => $hd_fwfflfzj,
                    'hd_count' => $hd_count+1,
                    'ywy' => $ywy,
                    'status' => $name,
                    'shr' => $shr,
                    'date' => $date,
                    'file' => $fileName,
                    'allTime' => $date
                ]);
            }
            
            //提交后扣减授信金额
            if($sqid !="" and $usesqmoney !=""){
    
                $result3=Db::name("use_sx")->field(['sqmoney','newMoney'])->where("sqid",$sqid)->find();
                    
                $sqmoney=$result3["sqmoney"];
                $newMoney=$result3["newMoney"];
    
                $useMoney=(int)$sqmoney-(int)$newMoney;
                $remainMoney=(int)$sqmoney-(int)$useMoney-(int)$usesqmoney;
                
                $result4=Db::table("use_sx")->insert([
                    'sqid' => $sqid,
                    'sqmoney' => $sqmoney,
                    'useMoney' => $useMoney,
                    'nowUseMoney' => $usesqmoney,
                    'remainMoney' => $remainMoney,
                    'fl_no' => $no,
                    'useDepartment' => $department,
                    'date' => $date,
                    'note' => '使用授信',
                    'newMoney' => $remainMoney
                ]);

                $result5=Db::table("use_sx")->where('sqid',$sqid)->update([
                    'newMoney' => $remainMoney
                ]);
            }

            return $this->success('提交成功！','/index.php/Index/fl/fl_line.html?id='.$id,'','1');
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
                $update_fl_print=Db::query("update flsqd set isprint='1' where id=$id");
            }

            echo 0;
        }
    }

    public function search_sx_money(){

        $sxid=$this->request->param('sxid');

        session_start();
        $username=$_SESSION["username"];

        $sqlstr=Db::query("select newMoney from use_sx where sqid='$sxid'");
    
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

        $notes=Db::name("flsqd")->field("note")->where("id",$id)->select();
        $note=$notes[0]["flsqd"];

        $note=$note."/辅料单作废，备注：".$zf_note;

        //重新编辑需要返还授信金额
        $sqlstr4=Db::query("select count(*) from use_sx where fl_no = (select no from flsqd where id=$id)");
        $count=$sqlstr4[0]["count(*)"];

        if($count > 0){
            $sqlstr5=Db::query("select nowUseMoney,sqid from use_sx where  fl_no = (select no from flsqd where id=$id)");

            $nowUseMoney=$sqlstr5[0]["nowUseMoney"];
            $sqid=$sqlstr5[0]["sqid"];

            $update_sx=Db::query("update use_sx set newMoney= $nowUseMoney + newMoney where sqid='$sqid'");

            $del_sx=Db::query("delete from use_sx where fl_no = (select no from flsqd where id=$id)");
        }


        $update_zf=Db::query("update flsqd set note='$note',status='作废' where id=$id");
        
        return redirect("/index.php/Index/fl/fl_line.php?id=".$id);
          
    }

    public function fl_liucheng(){
        session_start();
        $username=$_SESSION["username"]; 

        $sqlstr1=Db::name("user_form")->field(["department","newLevel"])->where("username",$username)->select();

        $my_department=$sqlstr1[0]["department"];
        $newLevel=$sqlstr1[0]["newLevel"];

        date_default_timezone_set("Asia/Shanghai");
        $time=date('Y-m-d H:i:s', time());
        
        $id=$this->request->param("id"); 
        $option=$this->request->param("option"); 

        $result=Db::name("flsqd")->field(['department','jkfs','status','shr','allTime','date'])->where("id",$id)->find();

        $department=$result["department"];
        $jkfs=$result["jkfs"];
        $status=$result["status"];
        $shr=$result["shr"];
        $date=$result["date"];
        $shTime="";
        
        //1：同意 3：义乌同意 8：义乌修改
        if($option == 1 || $option == 3 || $option == 8){
            //同意流程
            
            $status_arr=explode(",",$status);
            $shr_arr=explode(",",$shr);

            $status_now=array_pop($status_arr);
            $shr_now=array_pop($shr_arr);

            //防止表单重复提交后影响流程
            if($shr_now==$username or $username == "俞俏丽"){

                $flprocess_id=Db::name("flprogress_all")->field("id")->where("jkfs",$jkfs)->where("status","生效中")->where("change_date",">",$date)->find();

                if($flprocess_id == []){
                    $this->error('未查询到流程，请联系管理员！','/index.php/Index/fl/flsq.html','',1);
                }else{
                    $flprocess_id=$flprocess_id["id"];

                    $numbers=Db::name("flprogress")->field('number')->where("flprogress_id",$flprocess_id)->where("name",$status_now)->find();
                    $flprocess_lengths=Db::name("flprogress")->field('count(*)')->where("flprogress_id",$flprocess_id)->find();
                    
                    $number=$numbers["number"];
                    $flprocess_length=$flprocess_lengths["count(*)"];

                    //下个流程
                    $number_forward=$number+1;

                    $status_forwards=Db::name("flprogress")->field(['name','sp'])->where("flprogress_id",$flprocess_id)->where("number",$number_forward)->find();
                    
                    $status_forward=$status_forwards["name"];
                    $sp_forward=$status_forwards["sp"];

                    $status_new=$status.",".$status_forward;
                    $sp_new=$shr.",".$sp_forward;
                    $shTime_new=$shTime.",".$time;

                    //更新流程
                    if($option != 8){
                        $result6=Db::table("flsqd")->where("id",$id)->update([
                            'status' => $status_new,
                            'shr' => $sp_new,
                            'allTime' => $shTime_new,
                        ]);
                    }
                    
                    //流程是否到底
                    if((int)$flprocess_length==(int)$number_forward){
                        $result7=Db::table("flsqd")->where("id",$id)->update([
                            'date2' => $time
                        ]);
                    }

                    //财务，商业运营加入key
                    if($my_department=="财务部"){
                        $sqlstr_k=Db::table("fl_key")->insert([
                            'fl_no'=> $id,
                            'hasKey' => 1,
                            'time' => $time
                        ]);
                    }

                    //义乌审批单据加入物流信息
                    if($option == 3 || $option == 8){
                        
                        $wlfs=$this->request->param("wlfs");
                        $wlno=$this->request->param("wlno");
                        $wlprice=$this->request->param("wlprice");
                        $note=$this->request->param("note");

                        $result8=Db::table("flsqd")->where("id",$id)->update([
                            'wlfs'=>$wlfs,
                            'wlno'=>$wlno,
                            'wlprice'=>$wlprice,
                            'note'=>$note
                        ]);
                    }

                    return $this->success('提交成功！','/index.php/Index/fl/w_fl.html','','1');
                }

            }else{
                //不能重复提交表单
                return redirect('/index.php/Index/fl/fl_line.html?id='.$id);
            }

        }elseif($option==6){
            //业务员重新编辑

            //重新编辑需要返还授信金额
            $sqlstr=Db::query("select count(*) from use_sx where fl_no = (select no from flsqd where id=$id)");
            $count=$sqlstr[0]["count(*)"];

            if($count > 0){
                $sqlstr2=Db::query("select nowUseMoney,sqid from use_sx where  fl_no = (select no from flsqd where id=$id)");   

                $nowUseMoney=$sqlstr2[0]["nowUseMoney"];
                $sqid=$sqlstr2[0]["sqid"];

                $sqlstr3=Db::query("update use_sx set newMoney= $nowUseMoney + newMoney where sqid='$sqid'");

                $sqlstr4=Db::query("delete from use_sx where fl_no = (select no from flsqd where id=$id)");   
            }

            return redirect('/index.php/Index/fl/flsq.html?id='.$id);

        }elseif($option==7){
            //KA删除单据
            $sqlstr=Db::name("flsqd")->where("id",$id)->delete();

            return $this->success('删除成功！','/index.php/Index/fl/w_fl.html','','1');
        
        }else{
            //拒绝，待业务员审核
            $flprocess_id=Db::name("flprogress_all")->field("id")->where("jkfs",$jkfs)->where("status","生效中")->where("change_date",">",$date)->find();

            if($flprocess_id == []){
                $this->error('未查询到流程，请联系管理员！','/index.php/Index/fl/flsq.html','',1);
            }else{
                $flprocess_id=$flprocess_id["id"];
            
                $sqlstr=Db::name("flsqd")->field(["status","shr","allTime"])->where("id",$id)->find();

                $qq_status=$sqlstr["status"];
                $qq_shr=$sqlstr["shr"];
                $qq_allTime=$sqlstr["allTime"];
                
                $arr_shr=explode(",",$qq_shr);
                $shr=$arr_shr[0];

                $arr_status=explode(",",$qq_status);
                $status=$arr_status[0];
    
                $time=date('Y-m-d H:i:s', time());

                $sp=$qq_shr.",".$shr;
                $status=$qq_status.",".$status;
                $allTime=$qq_allTime.",".$time;
    
                $result9=Db::table("flsqd")->where("id",$id)->update([
                    'status'=>$status,
                    'shr'=>$sp,
                    'allTime'=>$allTime
                ]);
    
                //财务，商业运营拒绝删除key
                $result10=Db::name("fl_key")->field("count(*)")->where("fl_no",$id)->select();
                $count_key=$result10[0]["count(*)"];
                
                if($count_key>0){
                    $sqlstr4=Db::table("fl_key")->where("fl_no",$id)->delete();
                }
    
                return $this->error('拒绝辅料成功！','/index.php/Index/fl/fl_line.html?id='.$id,'','1');
            }
        }
    }

    public function download_fl($option,$status,$time,$input_time,$input_time2,$keywords){
        
        error_reporting(E_ALL || ~E_NOTICE);

        session_start();
        $username=$_SESSION["username"]; 
    
        $conn=mysqli_connect("localhost","root","root","yzl_database") or die("连接数据库服务器失败！".mysqli_error());
        mysqli_query($conn,"set names utf8");

        $sqlstr1=Db::name("user_form")->field(["department","newLevel"])->where("username",$username)->select();

        $my_department=$sqlstr1[0]["department"];
        $newLevel=$sqlstr1[0]["newLevel"];
    
        if($option == 1){
            //新系统辅料单下载
            
            //逻辑判断，关键字属于哪一类
            $keywords=$this->request->param("keywords");
            $option=0;

            $fl_no_count=Db::name("flsqd")->field("count(*)")->where("no","like",'%'.$keywords.'%')->find();
            $fl_company_count=Db::name("flsqd")->field("count(*)")->where("company","like",'%'.$keywords.'%')->find();
            $fl_department_count=Db::name("flsqd")->field("count(*)")->where("department","like",'%'.$keywords.'%')->find();
            $fl_people_count=Db::name("flsqd")->field("count(*)")->where("people","like",'%'.$keywords.'%')->find();
            
            if($fl_no_count["count(*)"] != 0){
                $option=1;
            }elseif($fl_company_count["count(*)"] != 0){
                $option=2;
            }elseif($fl_department_count["count(*)"] != 0){
                $option=3;
            }elseif($fl_people_count["count(*)"] != 0){
                $option=4;
            }


            $sqlstr2="select * from flsqd where 1=1 ";
    
            if($newLevel != "ADMIN" and $department !="财务部" and $department !="商业运营部"){
                $sqlstr2=$sqlstr2." and shr like '%$username%'";
            }
        
            if($clientName !=""){
                $sqlstr2=$sqlstr2." and company like '%$clientName%'";
            }
        
            if($status == 1){
                $sqlstr2=$sqlstr2." and status like '%已归档%' ";
            }elseif($status == 0){
                $sqlstr2=$sqlstr2." and not status like '%已归档%' ";
            }
        
            if($input_time != ""){
                $input_time_full=$input_time." 00:00:00";
        
                if($time=="流程开始时间"){
                    $sqlstr2=$sqlstr2." and date >='$input_time_full' ";
                }elseif($time=="流程结束时间"){
                    $sqlstr2=$sqlstr2." and date2 >='$input_time_full' ";
                }
            }
    
        
            if($input_time2 != ""){
                $input_time2_full=$input_time2." 23:59:59";
        
                if($time=="流程开始时间"){
                    $sqlstr2=$sqlstr2." and date <='$input_time2_full' ";
                }elseif($time=="流程结束时间"){
                    $sqlstr2=$sqlstr2." and date2 <='$input_time2_full' ";
                }
            }

            if($option==1){
                $sqlstr2=$sqlstr2." and no like '%$keywords%' ";
            }elseif($option==2){
                $sqlstr2=$sqlstr2." and company like '%$keywords%' ";
            }elseif($option==3){
                $sqlstr2=$sqlstr2." and department like '%$keywords%' ";
            }elseif($option==4){
                $sqlstr2=$sqlstr2." and people like '%$keywords%' ";
            }

        }else{
            //旧系统辅料单下载
    
            $sqlstr2="select * from oldflsqd where 1=1 ";
        
            if($clientName !=""){
                $sqlstr2=$sqlstr2." and company like '%$clientName%'";
            }
        
            if($status=="已完成"){
                $sqlstr2=$sqlstr2." and status like '%归档%' ";
            }
        
            if($input_time != ""){
                $input_time_full=$input_time." 00:00:00";
        
                if($time=="流程开始时间"){
                    $sqlstr2=$sqlstr2." and date >='$input_time_full' ";
                }
            }
        
            if($input_time2 != ""){
                $input_time2_full=$input_time2." 23:59:59";
        
                if($time=="流程开始时间"){
                    $sqlstr2=$sqlstr2." and date <='$input_time2_full' ";
                }
            }
        }
    
        $result=mysqli_query($conn,$sqlstr2);
    
        $data=array();
    
        while($myrow=mysqli_fetch_row($result)){
            $data[]=str_replace("\t",'',$myrow);
            //$data[]=$myrow;
            //echo var_dump($data);
        }
    
        
        foreach($data as $key=>$value){
            foreach($value as $keys=>$values){
    
                if($keys>=11 and $keys<=21){
    
                    //$str_hksj=explode(",",$data[$key][$keys]);
                    $str_hksj=explode(",",str_replace("\t",'',trim($data[$key][$keys])));
    
                    //echo sizeof($str_hksj);
    
                    for($i=0;$i<sizeof($str_hksj);$i++){
                        $j=26+$keys+$i*11;
                        $data[$key][$j]=$str_hksj[$i];                    
                    }            
                }
            }
        }
    
        $header=array('申请单编号','申请单位','申请人','申请部门','申请日期','收货地址','联系人','联系电话','运输方式','是否含税包装价格');
    
        array_push($header,'品类all','货号all','品名all','申请数量all','包装价格all','费率/单价all','服务费小计all','辅料名称all','单价all','辅料数量all','辅料小计all');
        
        array_push($header,'税点','结款方式','物流方式','物流单号','物流费用','备注','申请数量合计','服务费合计','辅料数量','辅料费小计含税','服务费辅料费总计',
        '条数','业务员','流程状态','结束日期');
    
        for($i=1;$i<=20;$i++){
            array_push($header,'品类'.$i,'货号'.$i,'品名'.$i,'申请数量'.$i,'包装价格'.$i,'费率/单价'.$i,'服务费小计'.$i,'辅料名称'.$i,'单价'.$i,'辅料数量'.$i,'辅料小计'.$i);
        }
            
        
        
        function createtable($list,$filename,$header=array(),$index = array()){ 
            header("Content-type:application/vnd.ms-excel"); 
            header("Content-Disposition:filename=".$filename.".xls"); 
            $teble_header = implode("\t",$header); 
            $strexport = $teble_header."\r"; 
            foreach ($list as $row){ 
                foreach($index as $val){ 
                    $strexport.=$row[$val]."\t";  
                    } 
                    $strexport.="\r"; 
                    
                    } 
                $strexport=iconv('UTF-8',"GB2312//IGNORE",$strexport); 
                exit($strexport);  
        }
    
        $list2=range(1,267);
    
        createtable($data,'flsqd',$header,$list2);
        mysqli_free_result($result);
        mysqli_close($conn);
    }

    public function query_amount(){
        
        $information=trim($this->request->param("information"));

        session_start();
        $username=$_SESSION["username"]; 
        
        $sqlstr1=Db::name("user_form")->field(["department","newLevel"])->where("username",$username)->find();

        $department=$sqlstr1["department"];
        $newLevel=$sqlstr1["newLevel"];

        //分页代码
        if(!isset($_GET["page"]) || !is_numeric($_GET["page"])){
            $page=1;
        }else{
            $page=intval($_GET["page"]);
        }

        $pagesize=15;

        $sqlstr="select count(*) as total from fl where not fl_name like '%(赠)%' ";

        if($information != ""){
            $sqlstr= $sqlstr."and fl_name like '%$information%'";
        }

        $sqlstr1=DB::query($sqlstr);

        $total=$sqlstr1[0]["total"];

        if($total%$pagesize==0){
            $pagecount=intval($total/$pagesize);
        }else{
            $pagecount=ceil($total/$pagesize);
        }

        $sqlstr="select * from fl where not fl_name like '%(赠)%' ";

        if($information != ""){
            $sqlstr= $sqlstr."and fl_name like '%$information%'";
        }

        $sqlstr= $sqlstr." order by fl_name asc limit ".($page-1)*$pagesize.",$pagesize";

        $fls=DB::query($sqlstr);

        $data=[
            "title" => "查询辅料数",
            "username" => $username,
            "fls" => $fls,
            'total' => $total,
            'pagecount' => $pagecount,
            'page' => $page,
            'pagesize' => 15,
            'information' => $information,
            'newLevel'=> $newLevel
        ];

        return $this->fetch('',$data);
    
    }

    public function fl_add(){

        session_start();
        $username=$_SESSION["username"]; 
    
        $fl_name=trim($this->request->param('fl_name'));
        $fl_price=$this->request->param('fl_price');
        $fl_amount=$this->request->param('fl_amount');

        $fl_dups=DB::name("fl")->field("count(*)")->where("fl_name",$fl_name)->find();
        $fl_dup=$fl_dups["count(*)"];

        if($fl_dup==0){
            $result1=Db::table("fl")->insert([
                'fl_name' => $fl_name,
                'fl_price' => $fl_price,
                'fl_amount' => $fl_amount
            ]);
                
            $fl_name=$fl_name."(赠)";
            
            $result2=Db::table("fl")->insert([
                'fl_name' => $fl_name,
                'fl_price' => $fl_price,
                'fl_amount' => $fl_amount
            ]);
        }

        return $this->redirect('/index.php/Index/fl/query_amount.html');
    }

    public function fl_edit($id){

        $method=$this->request->method();

        session_start();
        $username=$_SESSION["username"];

        if($method=="POST"){
            $id=$this->request->param("id");
            $fl_name=$this->request->param('fl_name');
            $fl_price=$this->request->param('fl_price');
            $fl_amount=$this->request->param('fl_amount');
    
            $result=Db::table("fl")->where("id",$id)->update([
                'fl_name' => $fl_name,
                'fl_price' =>$fl_price,
                'fl_amount' => $fl_amount
            ]);
        
            return $this->redirect('/index.php/Index/Fl/query_amount.html');
        }else{
            $fl_line=DB::name("fl")->where("id",$id)->find();

            $data=[
                "title" => "编辑辅料",
                "username" => $username,
                "fl_line" => $fl_line
            ];

            return $this->fetch('',$data);
        }
        
    }

    public function fl_del($id){
        $sqlstr1=DB::name("fl")->field("fl_name")->where("id",$id)->find();

        $fl_name=$sqlstr1["fl_name"]."(赠)";

        $sqlstr2=DB::name("fl")->where("fl_name",$fl_name)->delete();
        $sqlstr3=DB::name("fl")->where("id",$id)->delete();

        return $this->redirect('/index.php/Index/fl/query_amount.html');
    }

    public function download_fl_info(){
        error_reporting(E_ALL || ~E_NOTICE);

        session_start();
        $username=$_SESSION["username"]; 
    
        $conn=mysqli_connect("localhost","root","root","yzl_database") or die("连接数据库服务器失败！".mysqli_error());
        mysqli_query($conn,"set names utf8");
    
        $sqlstr2="select * from fl";
    
        $result=mysqli_query($conn,$sqlstr2);
    
        $data=array();
    
        while($myrow=mysqli_fetch_row($result)){
            $data[]=str_replace("\t",'',$myrow);
            //$data[]=$myrow;
            //echo var_dump($data);
        }
        
        foreach($data as $key=>$value){
            foreach($value as $keys=>$values){
    
            }
        }
    
        $header=array('辅料名称','辅料价格','辅料数量');
        
        function createtable($list,$filename,$header=array(),$index = array()){ 
            header("Content-type:application/vnd.ms-excel"); 
            header("Content-Disposition:filename=".$filename.".xls"); 
            $teble_header = implode("\t",$header); 
            $strexport = $teble_header."\r"; 
            foreach ($list as $row){ 
                foreach($index as $val){ 
                    $strexport.=$row[$val]."\t";  
                    } 
                    $strexport.="\r"; 
                    
                    } 
                $strexport=iconv('UTF-8',"GB2312//IGNORE",$strexport); 
                exit($strexport);  
        }
    
        $list2=range(1,3);
    
        createtable($data,'辅料信息',$header,$list2);
        mysqli_free_result($result);
        mysqli_close($conn);
    }
}