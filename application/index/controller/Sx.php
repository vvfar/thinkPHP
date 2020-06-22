<?php
namespace app\index\controller;
use think\Controller;
use think\Db;

class Sx extends Controller
{
    public function write_sx(){
        session_start();

        if(isset($_SESSION["username"])){
            $username=$_SESSION["username"];

            $sqlstr1=Db::name("user_form")->field(["department","newLevel"])->where("username",$username)->find();

            $department=$sqlstr1["department"];
            $newLevel=$sqlstr1["newLevel"];

            $sqlstr2=Db::name("sx_id")->field(["id","name"])->find();        

            $id=$sqlstr2["id"];
            $no=$sqlstr2["name"];

            //拆分，获取最新sx_id
            $sx_id_arr=explode("-",$no);

            $sx_xl=array_pop($sx_id_arr);
            $sx_year=(int)substr($sx_xl,0,4);
            $sx_month=(int)substr($sx_xl,4,2);
            $sx_no=(int)substr($sx_xl,6,9);

            date_default_timezone_set("Asia/Shanghai");
            $date=date('Y-m-d H:i:s', time());
            $year=date("Y",strtotime($date));
            $month=date("m",strtotime($date));

            if((int)$year != (int)$sx_year || (int)$month != (int)$sx_month){
                $sx_year_new=$year;
                $sx_month_new=$month;
                $sx_no_new=1;
            }else{
                $sx_year_new=$year;
                $sx_month_new=$sx_month;
                $sx_no_new=$sx_no+1;
            
                if((int)$sx_month_new<10){
                    $sx_month_new="0".$sx_month_new;
                }
            }

            if($sx_no<10){
                $sx_no_new="00".$sx_no_new; 
            }elseif($sx_no<100){
                $sx_no_new="0".$sx_no_new;
            }

            $sx_xl_new=$sx_year_new.$sx_month_new.$sx_no_new;

            $sx_id=str_replace($sx_xl,$sx_xl_new,$no);

            //公司名称
            $clients=Db::query("select distinct client from store where staff='$username' and status='正常' and htsq='合同授权已提交'");

            //事业部
            $departments=Db::query("select distinct department from store");

            $this->assign('title','新增授信');
            $this->assign('username',$username);
            $this->assign('sx_id',$sx_id);
            $this->assign('clients',$clients);
            $this->assign('departments',$departments);

            return $this->fetch();
        }else{
            return $this->redirect('/index.php/Index/Login/login');
        }
    }

    public function back_sx(){
        session_start();
        
        if(isset($_SESSION["username"])){

            $username=$_SESSION["username"];

            $sqlstr1=\think\Db::name("user_form")->field(["department","newLevel"])->where("username",$username)->find();

            $department=$sqlstr1["department"];
            $newLevel=$sqlstr1["newLevel"];

            $sxs=\think\Db::name("sx_form")->field("sqid")->where("department",$department)->where("status","已生效")->select();
        
            $sxid=$this->request->param("no");


            $this->assign('title','填写回款');
            $this->assign('username',$username);
            $this->assign('sxs',$sxs);
            $this->assign('sxid',$sxid);

            return $this->fetch();
        
        }else{
            return $this->redirect('/index.php/Index/Login/login');
        }
    }

    public function sx_search(){
        $sxid=$_GET["sxid"];

        $sqlstr1=Db::query("select a.id,a.companyName,a.ywy,b.date2 from sx_form a,hk_form b where a.sqid='$sxid' and a.sqid=b.sqid");

        $msg_list="";
        
        $msg_list=$msg_list.$sqlstr1[0]["id"].",";
        $msg_list=$msg_list.$sqlstr1[0]["companyName"].",";
        $msg_list=$msg_list.$sqlstr1[0]["ywy"].",";

        $dateTime=explode(",",$sqlstr1[0]["date2"]);
        $dateTime_count=0;

        //纪录期数
        for($i=0;$i<12;$i++){
            if($dateTime[$i] !=""){
                $dateTime_count+=1;
            }
        }

        $dateTime_count+=1;

        $msg_list=$msg_list.$dateTime_count.",";

        echo $msg_list;
    }

    public function dsh_sx(){
        session_start();
        
        if(isset($_SESSION["username"])){
            $date1=$this->request->get("date1");
            $date2=$this->request->get("date2");
            $companyName=$this->request->get("companyName");

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

            $sqlstr3="select count(*) as total from sx_form a,hk_form b where a.sqid=b.sqid and (a.status='待归档' or a.status='已拒绝')";

            if($date1 !="" && $date2 !=""){
                $sqlstr3= $sqlstr3." and a.date3>='$date1' and a.date3<= '$date2'";
            }

            if($companyName !=""){
                $sqlstr3= $sqlstr3." and a.companyName like '%$companyName%'";
            }

            if($newLevel !="ADMIN" and $department != "商业运营部" and $department != "财务部"){
                if($newLevel == "KA"){
                    $sqlstr3=$sqlstr3." and a.ywy='$username'"; 
                }else{
                    $sqlstr3=$sqlstr3." and (a.department='$department' or a.gxDepartment like '%$department%' or '$department' like concat('%',a.department,'%'))";
                }
            }

            $sqlstr3=Db::query($sqlstr3);

            $total=$sqlstr3[0]["total"];

            if($total%$pagesize==0){
                $pagecount=intval($total/$pagesize);
            }else{
                $pagecount=ceil($total/$pagesize);
            }

            //表格数据
            $sqlstr2="select distinct a.id as id,a.date1 as date1,a.sqid as sqid,a.companyName as companyName,a.department as department,a.ywy as ywy,a.sqmoney as sqmoney,". 
            "b.dhkje as dhkje,a.status2 as status2,a.status,c.newMoney ".
            "from sx_form a,hk_form b,use_sx c where a.sqid=b.sqid and a.sqid=c.sqid and (a.status='待归档' or a.status='已拒绝')";

            if($date1 !="" and $date2 !=""){
                $sqlstr2=$sqlstr2." and a.date3 >= '$date1' and a.date3 <= '$date2'";
            }

            if($companyName !=""){
                $sqlstr2=$sqlstr2." and a.companyName like '%$companyName%'";
            }
            
            if($newLevel !="ADMIN" and $department != "商业运营部" and $department != "财务部"){
                if($newLevel == "KA"){
                    $sqlstr2=$sqlstr2." and a.ywy='$username'"; 
                }else{
                    $sqlstr2=$sqlstr2." and (a.department='$department' or a.gxDepartment like '%$department%' or '$department' like concat('%',a.department,'%'))";
                }
            }

            $sqlstr2=$sqlstr2." order by a.date1 desc limit ".($page-1)*$pagesize.",$pagesize";

            $sxs=Db::query($sqlstr2);

            for($i=0;$i<sizeof($sxs);$i++){
                $arr_shr=explode(",",$sxs[$i]["status"]);
                $shr=array_pop($arr_shr);
            }

            $this->assign('title','待审核授信');
            $this->assign('username',$username);
            $this->assign("total",$total);
            $this->assign('pagecount',$pagecount);
            $this->assign('page',$page);
            $this->assign('sxs',$sxs);
            $this->assign('date1',$date1);
            $this->assign('date2',$date2);
            $this->assign('companyName',$companyName);
            $this->assign('pagesize',15);

            return $this->fetch();
        }else{
            return $this->redirect('/index.php/Index/Login/login');
        }
    }

    public function dhk_sx(){
        session_start();

        if(isset($_SESSION["username"])){
            $username=$_SESSION["username"];

            if(!isset($_GET["date1"]) && !isset($_GET["date1"])){
                $date1="";
                $date2="";
            }else{
                $date1=$_GET["date1"];
                $date2=$_GET["date2"];
            }

            if(!isset($_GET["chooseInfo"])){
                $chooseInfo="";
            }else{
                $chooseInfo=$_GET["chooseInfo"];
            }
            
            
            if($chooseInfo=="授信编号"){
                $sqid=$_GET["sqid"];
                $companyName="";
                $s_department="";
            }elseif($chooseInfo=="公司名称"){
                $companyName=$_GET["companyName"];
                $sqid="";
                $s_department="";
            }elseif($chooseInfo=="事业部"){
                $sqid="";
                $companyName="";
                $s_department=$_GET["department"];
            }else{
                $sqid="";
                $companyName="";
                $s_department="";
            }

            $sqlstr1=\think\Db::name("user_form")->field(["department","newLevel"])->where("username",$username)->find();

            $department=$sqlstr1["department"];
            $newLevel=$sqlstr1["newLevel"];

            $departments=Db::name("sx_form")->distinct(true)->field("department")->select();

            //分页代码
            if(!isset($_GET["page"]) || !is_numeric($_GET["page"])){
                $page=1;
            }else{
                $page=intval($_GET["page"]);
            }

            $pagesize=15;

            $sqlstr3="select count(*) as total from sx_form a,hk_form b where a.sqid=b.sqid and a.status='已生效'";

            if($date1 !="" && $date2 !=""){
                $sqlstr3= $sqlstr3." and a.date3>='$date1' and a.date3<= '$date2'";
            }

            if($chooseInfo=="授信编号"){
                $sqlstr3=$sqlstr3." and a.sqid='$sqid'";
            }elseif($chooseInfo=="公司名称"){
                $sqlstr3=$sqlstr3." and a.companyName like '%$companyName%'";
            }elseif($chooseInfo=="事业部"){
                $sqlstr3=$sqlstr3." and a.department='$s_department'";
            }

            if($newLevel !="ADMIN" and $department != "商业运营部" and $department != "财务部"){
                if($newLevel == "KA"){
                    $sqlstr3=$sqlstr3." and a.ywy='$username'"; 
                }else{
                    $sqlstr3=$sqlstr3." and (a.department='$department' or a.gxDepartment like '%$department%' or '$department' like concat('%',a.department,'%'))";
                }
            }

            $sqlstr3=Db::query($sqlstr3);

            $total=$sqlstr3[0]['total'];

            if($total%$pagesize==0){
                $pagecount=intval($total/$pagesize);
            }else{
                $pagecount=ceil($total/$pagesize);
            }

            //授信概况合计
            $sxMoney=0;
            $syyh=0;
            $syed=0;

            $sqlstr5="select distinct a.sqmoney as sxMoney,b.dhkje as syyh,c.newMoney as syed from sx_form a,hk_form b,use_sx c where a.status='已生效' and a.sqid=b.sqid and a.sqid=c.sqid ";

            if($s_department !=""){
                $sqlstr5=$sqlstr5." and a.department='$s_department'";
            }elseif($sqid !=""){
                $sqlstr5=$sqlstr5." and a.sqid='$sqid'";
            }elseif($companyName !=""){
                $sqlstr5=$sqlstr5." and a.companyName like '%$companyName%'";
            }

            $sxHjs=Db::query($sqlstr5);

            for($i=0;$i<sizeof($sxHjs);$i++){
                $sxMoney=$sxMoney+$sxHjs[0]["sxMoney"];
                $syyh=$syyh+$sxHjs[0]["syyh"];
                $syed=$syed+$sxHjs[0]["syed"];

                $sxMoney = sprintf("%.2f",$sxMoney);
                $syyh = sprintf("%.2f",$syyh);
                $syed = sprintf("%.2f",$syed);
            }

            //表格数据
            $sqlstr2="select distinct a.id as id,a.date1 as date1,a.sqid as sqid,a.companyName as companyName,a.department as department,a.ywy as ywy,a.sqmoney as sqmoney,". 
                        "b.dhkje as dhkje,a.status2,a.status,c.newMoney as newMoney ".
                        "from sx_form a,hk_form b,use_sx c where a.sqid=b.sqid and a.sqid=c.sqid and a.status='已生效'";

            if($date1 !="" and $date2 !=""){
                $sqlstr2=$sqlstr2." and a.date3 >= '$date1' and a.date3 <= '$date2'";
            }

            if($chooseInfo=="授信编号"){
                $sqlstr2=$sqlstr2." and a.sqid='$sqid'";
            }elseif($chooseInfo=="公司名称"){
                $sqlstr2=$sqlstr2." and a.companyName like '%$companyName%'";
            }elseif($chooseInfo=="事业部"){
                $sqlstr2=$sqlstr2." and a.department='$s_department'";
            }

            if($newLevel !="ADMIN" and $department != "商业运营部" and $department != "财务部"){
                if($newLevel == "KA"){
                    $sqlstr2=$sqlstr2." and a.ywy='$username'"; 
                }else{
                    $sqlstr2=$sqlstr2." and (a.department='$department' or a.gxDepartment like '%$department%' or '$department' like concat('%',a.department,'%'))";
                }
            }

            $sqlstr2=$sqlstr2." order by a.date1 desc limit ".($page-1)*$pagesize.",$pagesize";

            $sxs=Db::query($sqlstr2);

            for($i=0;$i<sizeof($sxs);$i++){
                $arr_shr=explode(",",$sxs[$i]["status"]);
                $shr=array_pop($arr_shr);
            }

            $this->assign('title','待回款授信');
            $this->assign('username',$username);
            $this->assign('department',$department);
            $this->assign('newLevel',$newLevel);
            $this->assign('date1',$date1);
            $this->assign('date2',$date2);
            $this->assign('companyName',$companyName);
            $this->assign('sqid',$sqid);
            $this->assign('chooseInfo',$chooseInfo);
            $this->assign('departments',$departments);
            $this->assign("total",$total);
            $this->assign('pagecount',$pagecount);
            $this->assign('page',$page);
            $this->assign('pagesize',15);
            $this->assign('sxs',$sxs);
            $this->assign('s_department',$s_department);
            $this->assign('sxMoney',$sxMoney);
            $this->assign('syyh',$syyh);
            $this->assign('syed',$syed);

            return $this->fetch();
        }else{
            return $this->redirect('/index.php/Index/Login/login');
        }
    }

    public function ywc_sx(){
        session_start();

        if(isset($_SESSION["username"])){
            $username=$_SESSION["username"];

            if(!isset($_GET["chooseInfo"])){
                $chooseInfo="";
            }else{
                $chooseInfo=$_GET["chooseInfo"];
            }

            if(!isset($_GET["date1"]) && !isset($_GET["date1"]) && !isset($_GET["companyName"]) && !isset($_GET["sqid"]) ){
                $date1="";
                $date2="";
                $companyName="";
                $sqid="";
            }else{
                $date1=$_GET["date1"];
                $date2=$_GET["date2"];
                $companyName=$_GET["companyName"];
                $sqid=$_GET["sqid"];
            }

            $sqlstr1=\think\Db::name("user_form")->field(["department","newLevel"])->where("username",$username)->find();

            $department=$sqlstr1["department"];
            $newLevel=$sqlstr1["newLevel"];

            $departments=Db::name("sx_form")->distinct(true)->field("department")->select();

            //分页代码
            if(!isset($_GET["page"]) || !is_numeric($_GET["page"])){
                $page=1;
            }else{
                $page=intval($_GET["page"]);
            }

            $pagesize=15;

            $sqlstr3="select count(*) as total from sx_form a,hk_form b where a.sqid=b.sqid and a.status='已完成' ";

            if($date1 !="" && $date2 !=""){
                $sqlstr3= $sqlstr3." and a.date3>='$date1' and a.date3<= '$date2'";
            }

            if($companyName !=""){
                $sqlstr3= $sqlstr3." and a.companyName like '%$companyName%'";
            }

            if($newLevel !="ADMIN" and $department != "商业运营部" and $department != "财务部"){
                if($newLevel == "KA"){
                    $sqlstr3=$sqlstr3." and a.ywy='$username'"; 
                }else{
                    $sqlstr3=$sqlstr3." and (a.department='$department' or a.gxDepartment like '%$department%' or '$department' like concat('%',a.department,'%'))";
                }
            }

            $sqlstr3=Db::query($sqlstr3);

            $total=$sqlstr3[0]['total'];

            if($total%$pagesize==0){
                $pagecount=intval($total/$pagesize);
            }else{
                $pagecount=ceil($total/$pagesize);
            }

            //表格数据
            $sqlstr2="select distinct a.id,a.date1,a.sqid,a.companyName,a.department,a.ywy,a.sqmoney,". 
                            "b.dhkje,a.status2,a.status,c.newMoney ".
                            "from sx_form a,hk_form b,use_sx c where a.sqid=b.sqid and a.sqid=c.sqid and a.status='已完成' ";
    
            if($date1 !="" and $date2 !=""){
                $sqlstr2=$sqlstr2." and a.date3 >= '$date1' and a.date3 <= '$date2'";
            }

            if($companyName !=""){
                $sqlstr2=$sqlstr2." and a.companyName like '%$companyName%'";
            }
            
            if($newLevel !="ADMIN" and $department != "商业运营部" and $department != "财务部"){
                if($newLevel == "KA"){
                    $sqlstr2=$sqlstr2." and a.ywy='$username'"; 
                }else{
                    $sqlstr2=$sqlstr2." and (a.department='$department' or a.gxDepartment like '%$department%' or '$department' like concat('%',a.department,'%'))";
                }
            }

            $sqlstr2=$sqlstr2." order by a.date1 desc limit ".($page-1)*$pagesize.",$pagesize";

            $sxs=Db::query($sqlstr2);

            for($i=0;$i<sizeof($sxs);$i++){
                $arr_shr=explode(",",$sxs[$i]["status"]);
                $shr=array_pop($arr_shr);
            }


            $this->assign('title','已完成授信');
            $this->assign('username',$username);
            $this->assign('date1',$date1);
            $this->assign('date2',$date2);
            $this->assign('companyName',$companyName);
            $this->assign('chooseInfo',$chooseInfo);
            $this->assign('department',$department);
            $this->assign('departments',$departments);
            $this->assign('newLevel',$newLevel);
            $this->assign('sqid',$sqid);
            $this->assign("total",$total);
            $this->assign('pagecount',$pagecount);
            $this->assign('page',$page);
            $this->assign('pagesize',15);
            $this->assign('sxs',$sxs);

            return $this->fetch();
        }else{
            return $this->redirect('/index.php/Index/Login/login');
        }
    }

    public function djLoad(){
        session_start();

        if(isset($_SESSION["username"])){
            $username=$_SESSION["username"];

            $sqlstr1=\think\Db::name("user_form")->field(["department","newLevel"])->where("username",$username)->find();

            $department=$sqlstr1["department"];
            $newLevel=$sqlstr1["newLevel"];

            if(!isset($_GET["chooseInfo"])){
                $chooseInfo="";
            }else{
                $chooseInfo=$_GET["chooseInfo"];
            }

            if(!isset($_GET["date1"]) && !isset($_GET["date1"]) && !isset($_GET["companyName"])  ){
                $date1="";
                $date2="";
                $companyName="";
            }else{
                $date1=$_GET["date1"];
                $date2=$_GET["date2"];
                $companyName=$_GET["companyName"];
            }

            //分页代码
            if(!isset($_GET["page"]) || !is_numeric($_GET["page"])){
                $page=1;
            }else{
                $page=intval($_GET["page"]);
            }

            $pagesize=15;

            $sqlstr3="select count(*) as total from sx_form a,hk_form b where a.sqid=b.sqid and a.status='待生效'";

            if($date1 !="" && $date2 !=""){
                $sqlstr3= $sqlstr3." and a.date3>='$date1' and a.date3<= '$date2'";
            }

            if($companyName !=""){
                $sqlstr3= $sqlstr3." and a.companyName like '%$companyName%'";
            }

            if($newLevel !="ADMIN" and $department != "商业运营部"){
                if($newLevel == "KA"){
                    $sqlstr3=$sqlstr3." and a.ywy='$username'"; 
                }else{
                    $sqlstr3=$sqlstr3." and (a.department='$department' or a.gxDepartment like '%$department%' or '$department' like concat('%',a.department,'%'))";
                }
            }
            
            $sqlstr3=Db::query($sqlstr3);

            $total=$sqlstr3[0]['total'];

            if($total%$pagesize==0){
                $pagecount=intval($total/$pagesize);
            }else{
                $pagecount=ceil($total/$pagesize);
            }

            //表格数据
            $sqlstr2="select distinct a.id,a.date1,a.sqid,a.companyName,a.department,a.ywy,a.sqmoney,". 
                        "b.dhkje,a.status2,a.status,c.newMoney ".
                        "from sx_form a,hk_form b,use_sx c where a.sqid=b.sqid and a.sqid=c.sqid and a.status='待生效'";

            if($date1 !="" and $date2 !=""){
                $sqlstr2=$sqlstr2." and a.date3 >= '$date1' and a.date3 <= '$date2'";
            }

            if($companyName !=""){
                $sqlstr2=$sqlstr2." and a.companyName like '%$companyName%'";
            }
            

            if($newLevel !="ADMIN" and $department != "商业运营部"){
                if($newLevel == "KA"){
                    $sqlstr2=$sqlstr2." and a.ywy='$username'"; 
                }else{
                    $sqlstr2=$sqlstr2." and (a.department='$department' or a.gxDepartment like '%$department%' or '$department' like concat('%',a.department,'%'))";
                }
            }

            $sqlstr2=$sqlstr2." order by a.id desc limit ".($page-1)*$pagesize.",$pagesize";

            $sxs=Db::query($sqlstr2);

            for($i=0;$i<sizeof($sxs);$i++){
                $arr_shr=explode(",",$sxs[$i]["status"]);
                $shr=array_pop($arr_shr);
            }

            $this->assign('title','待上传单据授信');
            $this->assign('username',$username);
            $this->assign('department',$department);
            $this->assign("total",$total);
            $this->assign('pagecount',$pagecount);
            $this->assign('page',$page);
            $this->assign('pagesize',15);
            $this->assign('sxs',$sxs);
            $this->assign('date1',$date1);
            $this->assign('date2',$date2);
            $this->assign('companyName',$companyName);

            return $this->fetch();
        }else{
            return $this->redirect('/index.php/Index/Login/login');
        }
    }

    public function sx_line($id){
        session_start();

        if(isset($_SESSION["username"])){

            date_default_timezone_set("Asia/Shanghai");
            $now=date('Y-m-d', time());  //签署日期

            $username=$_SESSION["username"];

            $sqlstr1=\think\Db::name("user_form")->field(["department","newLevel"])->where("username",$username)->find();

            $department=$sqlstr1["department"];
            $newLevel=$sqlstr1["newLevel"];

            $sx_lines=Db::query("select a.sqid,a.companyName,a.department,a.ywy,a.date1,a.sqmoney,a.sxf,". 
            "b.dhkje,a.shr,a.date2 as startDate,a.date3 as endDate,a.dateTime,a.hkje,a.wyfl,a.hkfs,a.hkfsbz,b.date2 as hk_date,".
            "b.sjhkje,b.hkfs as sjhkfs,b.hkfs2,a.file_name,a.status,a.status2,a.allTime,a.note,a.gxDepartment,b.syjehkfs,a.bpeople ".
            "from sx_form a,hk_form b where a.sqid=b.sqid and a.id=$id");

            $status=$sx_lines[0]["status"];
            $sqid=$sx_lines[0]["sqid"];
            $sx_line=$sx_lines[0];

            $shr2_arr=explode(",",$sx_lines[0]["shr"]);

            $syhkje=$sx_lines[0]["dhkje"];
            $dateTime=explode(",",$sx_lines[0]["dateTime"]);
            $hkje=explode(",",$sx_lines[0]["hkje"]);
            $wyfl=explode(",",$sx_lines[0]["wyfl"]);
            $hkfs=explode(",",$sx_lines[0]["hkfs"]);
            $hkfsbz=explode(",",$sx_lines[0]["hkfsbz"]);
            $date2=explode(",",$sx_lines[0]["hk_date"]);
            $sjhkje=explode(",",$sx_lines[0]["sjhkje"]);
            $sjhkfs=explode(",",$sx_lines[0]["sjhkfs"]);
            $hkfs2=explode(",",$sx_lines[0]["hkfs2"]);
            $note=$sx_lines[0]["note"];
            $syjehkfs=$sx_lines[0]["syjehkfs"];

            $fls=Db::query("select a.*,b.id as fl_id from use_sx a left join flsqd b on a.fl_no=b.no  where a.sqid='$sqid' and a.fl_no <> '' order by a.id asc");

            $this->assign('username',$username);
            $this->assign('department',$department);
            $this->assign('newLevel',$newLevel);
            $this->assign('status',$status);
            $this->assign('sx_line',$sx_line);
            $this->assign('newLevel',$newLevel);
            $this->assign('fls',$fls);
            $this->assign('dateTime',$dateTime);
            $this->assign('date2',$date2);
            $this->assign('hkje',$hkje);
            $this->assign('wyfl',$wyfl);
            $this->assign('hkfsbz',$hkfsbz);
            $this->assign('hkfs',$hkfs);
            $this->assign('sjhkje',$sjhkje);
            $this->assign('hkfs2',$hkfs2);
            $this->assign('sjhkfs',$sjhkfs);
            $this->assign('now',$now);     
            $this->assign('id',$id);      
            
            return $this->fetch();
        }else{
            return $this->redirect('/index.php/Index/Login/login');
        }
    }

    public function sx_cw(){
        session_start();

        if(isset($_SESSION["username"])){
            $username=$_SESSION["username"];

            if(!isset($_GET["date1"]) && !isset($_GET["date1"]) && !isset($_GET["companyName"])  ){
                $date1="";
                $date2="";
                $companyName="";
            }else{
                $date1=$_GET["date1"];
                $date2=$_GET["date2"];
                $companyName=$_GET["companyName"];
            }
            
            $sqlstr1=\think\Db::name("user_form")->field(["department","newLevel"])->where("username",$username)->find();

            $department=$sqlstr1["department"];
            $newLevel=$sqlstr1["newLevel"];

            //分页代码
            if(!isset($_GET["page"]) || !is_numeric($_GET["page"])){
                $page=1;
            }else{
                $page=intval($_GET["page"]);
            }

            $pagesize=15;

            $sqlstr3="select count(*) as total from sx_form a,hk_form2 b where a.sqid=b.sqid  and b.status <> '已审核' ";

            if($date1 !="" && $date2 !=""){
                $sqlstr3= $sqlstr3." and a.date3>='$date1' and a.date3<= '$date2'";
            }

            if($companyName !=""){
                $sqlstr3= $sqlstr3." and a.companyName like '%$companyName%'";
            }

            if($newLevel !="ADMIN" and $department != "商业运营部" and $department !="财务部"){
                if($newLevel == "KA"){
                    $sqlstr3=$sqlstr3." and a.ywy='$username' ";
                }else{
                    $sqlstr3=$sqlstr3." and '$department' like concat('%',a.department,'%') ";
                }
            }
                       
            $sqlstr3=Db::query($sqlstr3);

            $total=$sqlstr3[0]['total'];

            if($total%$pagesize==0){
                $pagecount=intval($total/$pagesize);
            }else{
                $pagecount=ceil($total/$pagesize);
            }

            $sqlstr2="select distinct a.id,a.date1,a.sqid,a.companyName,a.department,a.ywy,a.sqmoney,". 
                        "b.dhkje,a.status2,b.status,c.newMoney ".
                        "from sx_form a,hk_form2 b,use_sx c where a.sqid=b.sqid and a.sqid=c.sqid and b.status <> '已审核' ";

            if($date1 !="" and $date2 !=""){
                $sqlstr2=$sqlstr2." and a.date3 >= '$date1' and a.date3 <= '$date2'";
            }

            if($companyName !=""){
                $sqlstr2=$sqlstr2." and a.companyName like '%$companyName%'";
            }
            
            if($newLevel !="ADMIN" and $department != "商业运营部" and $department !="财务部"){
                if($newLevel == "KA"){
                    $sqlstr2=$sqlstr2." and a.ywy='$username' ";
                }else{
                    $sqlstr2=$sqlstr2." and '$department' like concat('%',a.department,'%') ";
                }
            }

            $sqlstr2=$sqlstr2." order by a.date1 desc limit ".($page-1)*$pagesize.",$pagesize";

            $sxs=Db::query($sqlstr2);



            $this->assign('username',$username);
            $this->assign("total",$total);
            $this->assign('pagecount',$pagecount);
            $this->assign('pagesize',$pagesize);
            $this->assign('page',$page);
            $this->assign('sxs',$sxs);
            $this->assign('date1',$date1);
            $this->assign('date2',$date2);
            $this->assign('companyName',$companyName);

            return $this->fetch();
        }else{
            return $this->redirect('/index.php/Index/Login/login');
        }
    }

    public function time_sx(){
        session_start();

        if(isset($_SESSION["username"])){
            $username=$_SESSION["username"];

            if(!isset($_GET["date1"]) && !isset($_GET["date1"]) && !isset($_GET["companyName"])  ){
                $date1="";
                $date2="";
                $companyName="";
            }else{
                $date1=$_GET["date1"];
                $date2=$_GET["date2"];
                $companyName=$_GET["companyName"];
            }

            $sqlstr1=\think\Db::name("user_form")->field(["department","newLevel"])->where("username",$username)->find();

            $department=$sqlstr1["department"];
            $newLevel=$sqlstr1["newLevel"];

            //分页代码
            if(!isset($_GET["page"]) || !is_numeric($_GET["page"])){
                $page=1;
            }else{
                $page=intval($_GET["page"]);
            }

            $pagesize=15;

            $sqlstr3="select count(*) as total from sx_form a,hk_form b where a.sqid=b.sqid and a.status='已生效'";

            if($date1 !="" && $date2 !=""){
                $sqlstr3= $sqlstr3." and a.date3>='$date1' and a.date3<= '$date2'";
            }

            if($companyName !=""){
                $sqlstr3= $sqlstr3." and a.companyName like '%$companyName%'";
            }

            if($newLevel !="ADMIN"){
                $sqlstr3=$sqlstr3." and (a.department='$department' or a.gxDepartment like '%$department%')";
            }
            
            $sqlstr3=Db::query($sqlstr3);

            $total=$sqlstr3[0]['total'];

            if($total%$pagesize==0){
                $pagecount=intval($total/$pagesize);
            }else{
                $pagecount=ceil($total/$pagesize);
            }

            $sqlstr2="select distinct a.id,a.date1,a.sqid,a.companyName,a.department,a.ywy,a.sqmoney,". 
            "b.dhkje,a.status2,a.status,c.newMoney,a.dateTime,a.hkje,b.date2,b.sjhkje,a.date3,a.wyfl ".
            "from sx_form a,hk_form b,use_sx c where a.sqid=b.sqid and a.sqid=c.sqid and a.status='已生效'";

            if($date1 !="" and $date2 !=""){
                $sqlstr2=$sqlstr2." and a.date3 >= '$date1' and a.date3 <= '$date2'";
            }

            if($companyName !=""){
                $sqlstr2=$sqlstr2." and a.companyName like '%$companyName%'";
            }
            
            if($newLevel !="ADMIN" and $department !="财务部"){
                $sqlstr2=$sqlstr2." and (a.department='$department' or a.gxDepartment like '%$department%')";
            }

            $sqlstr2=$sqlstr2." order by a.date1 desc limit ".($page-1)*$pagesize.",$pagesize";

            $sxs=Db::query($sqlstr2);

            $myTotal=1;

            $yqsj="";

            for($i=0;$i<sizeof($sxs);$i++){
                $arr_shr=explode(",",$sxs[0]["status"]);
                $shr=array_pop($arr_shr);
                
                //计划回款期数，金额
                $arr_qs=explode(",",$sxs[0]["dateTime"]);
                $arr_hkje=explode(",",$sxs[0]["hkje"]);
                $arr_wyfl=explode(",",$sxs[0]["wyfl"]);

                //实际回款期数，金额
                $arr_qs2=explode(",",$sxs[0]["date2"]);
                $arr_hkje2=explode(",",$sxs[0]["sjhkje"]);
                
                //合同期限
                $lastDate=$sxs[0]["date3"];

                $qs=0;
                $all_jhhk=0;

                //到期时间
                $expireDate="";
                $yqsj="";

                date_default_timezone_set("Asia/Shanghai");
                //$date1=date('Y-m-d', time());

                $date1=date('Y-m-d', time());
                $date2=date("Y-m-d",strtotime("+1week",strtotime(date('Y-m-d', time()))));


                for($count=0;$count<sizeof($arr_qs);$count++){
                    if($arr_qs[$count] != ""){
                        $qs=$qs+1;

                        if($arr_qs[$count] <= $date2){
                            $all_jhhk=$all_jhhk+$arr_hkje[$count]*(1+$arr_wyfl[$count]/100);
                            $expireDate=$arr_qs[$count];
                        }
                    }
                }
                
                    
                if($qs==0){
                    $expireDate=$lastDate;
                    $all_jhhk=$sxs[0]["sqmoney"];
                }

                $qs2=0;
                $all_sjhk=0;


                for($count=0;$count<sizeof($arr_qs2);$count++){
                    if($arr_qs2[$count] != ""){
                        $qs2=$qs2+1;

                        if($arr_qs[$count] <= $date2){
                            $all_sjhk=$all_sjhk+$arr_hkje2[$count];
                        }
                    } 
                }
                
                if($qs2==0){
                    $all_sjhk=$sxs[0]["sqmoney"]-$sxs[0]["dhkje"];
                }

                

                if($all_jhhk > $all_sjhk){
                    //逾期天数
                    $yqsj=floor((strtotime($expireDate)-strtotime($date1))/86400);

                    if($yqsj<=7 and $yqsj >= 0){
                        $yqsj=$yqsj."天到期";
                    }else{
                        $yqsj="";
                    }
                }else{
                    $expireDate="";
                }

                if($expireDate > $date2 or $expireDate < $date1){
                    $expireDate="";
                }

            }


            $this->assign('username',$username);
            $this->assign('sxs',$sxs);
            $this->assign('yqsj',$yqsj);
            $this->assign("total",$total);
            $this->assign('pagecount',$pagecount);
            $this->assign('page',$page);
            $this->assign('date1',$date1);
            $this->assign('date2',$date2);
            $this->assign('companyName',$companyName);
            
            

            return $this->fetch();
        }else{
            return $this->redirect('/index.php/Index/Login/login');
        }
    }

    public function expire_sx(){
        session_start();

        if(isset($_SESSION["username"])){
            $username=$_SESSION["username"];

            if(!isset($_GET["date1"]) && !isset($_GET["date1"]) && !isset($_GET["companyName"])  ){
                $date1="";
                $date2="";
                $companyName="";
            }else{
                $date1=$_GET["date1"];
                $date2=$_GET["date2"];
                $companyName=$_GET["companyName"];
            }
                        
            $username=$_SESSION["username"];

            $sqlstr1=\think\Db::name("user_form")->field(["department","newLevel"])->where("username",$username)->find();

            $department=$sqlstr1["department"];
            $newLevel=$sqlstr1["newLevel"];

            //分页代码
            if(!isset($_GET["page"]) || !is_numeric($_GET["page"])){
                $page=1;
            }else{
                $page=intval($_GET["page"]);
            }

            $pagesize=15;

            $sqlstr3="select count(*) as total from sx_form a,hk_form b where a.sqid=b.sqid and a.status='已生效'";

            if($date1 !="" && $date2 !=""){
                $sqlstr3= $sqlstr3." and a.date3>='$date1' and a.date3<= '$date2'";
            }

            if($companyName !=""){
                $sqlstr3= $sqlstr3." and a.companyName like '%$companyName%'";
            }

            if($newLevel !="ADMIN" and $department !="财务部"){
                $sqlstr3=$sqlstr3." and (a.department='$department' or a.gxDepartment like '%$department%')";
            }

            $sqlstr3=Db::query($sqlstr3);

            $total=$sqlstr3[0]['total'];

            if($total%$pagesize==0){
                $pagecount=intval($total/$pagesize);
            }else{
                $pagecount=ceil($total/$pagesize);
            }

            $sqlstr2="select distinct a.id,a.date1,a.sqid,a.companyName,a.department,a.ywy,a.sqmoney,". 
            "b.dhkje,a.status2,a.status,c.newMoney,a.dateTime,a.hkje,b.date2,b.sjhkje,a.date3,a.wyfl ".
            "from sx_form a,hk_form b,use_sx c where a.sqid=b.sqid and a.sqid=c.sqid and a.status='已生效'";

            if($date1 !="" and $date2 !=""){
                $sqlstr2=$sqlstr2." and a.date3 >= '$date1' and a.date3 <= '$date2'";
            }

            if($companyName !=""){
                $sqlstr2=$sqlstr2." and a.companyName like '%$companyName%'";
            }
            
            if($newLevel !="ADMIN" and $department !="财务部"){
                $sqlstr2=$sqlstr2." and (a.department='$department' or a.gxDepartment like '%$department%')";
            }

            //$sqlstr2=$sqlstr2." order by a.date1 desc limit ".($page-1)*$pagesize.",$pagesize";

            $sqlstr2=$sqlstr2." order by a.date1 desc";

            $sxs=Db::query($sqlstr2);


            $yqsj="";

            for($i=0;$i<sizeof($sxs);$i++){
                            
                //计划回款期数，金额
                $arr_qs=explode(",",$sxs[0]["dateTime"]);
                $arr_hkje=explode(",",$sxs[0]["hkje"]);
                $arr_wyfl=explode(",",$sxs[0]["wyfl"]);

                //实际回款期数，金额
                $arr_qs2=explode(",",$sxs[0]["date2"]);
                $arr_hkje2=explode(",",$sxs[0]["sjhkje"]);

                $qs=0;
                $all_jhhk=0;

                //到期时间
                $expireDate="";
                $yqsj="";

                //合同期限
                $lastDate=$sxs[0]["date3"];

                date_default_timezone_set("Asia/Shanghai");
                $date1=date('Y-m-d', time());

                for($count=0;$count<sizeof($arr_qs);$count++){
                    if($arr_qs[$count] != ""){
                        $qs=$qs+1;

                        if($arr_qs[$count] <= $date1){
                            $all_jhhk=$all_jhhk+$arr_hkje[$count]*(1+$arr_wyfl[$count]/100);
                            $expireDate=$arr_qs[$count];
                        }
                    }
                }

                if($qs==0){
                    $expireDate=$lastDate;
                    $all_jhhk=$sxs[0]["sqmoney"];
                }

                $qs2=0;
                $all_sjhk=0;

                for($count=0;$count<sizeof($arr_qs2);$count++){
                    if($arr_qs2[$count] != ""){
                        $qs2=$qs2+1;

                        if($arr_qs2[$count] <= $date1){
                            $all_sjhk=$all_sjhk+$arr_hkje2[$count];
                        }
                    } 
                }

                if($qs2==0){
                    $all_sjhk=$sxs[0]["sqmoney"]-$sxs[0]["dhkje"];
                }
                
                if($all_jhhk > $all_sjhk){
                    //逾期天数
                    $yqsj=floor((strtotime($date1)-strtotime($expireDate))/86400);

                    if($yqsj<=30 and $yqsj > 0){
                        $yqsj="逾期".$yqsj."天";
                    }elseif($yqsj > 30){
                        $yqsj="逾期30天以上";
                    }else{
                        $yqsj = "";
                    }
                }else{
                    $expireDate="";
                }
                
                if($expireDate>$date1){
                    $expireDate="";
                    $all_jhhk=0;
                }
            }



            $this->assign('username',$username);
            $this->assign("total",$total);
            $this->assign('pagecount',$pagecount);
            $this->assign('page',$page);
            $this->assign('sxs',$sxs);
            $this->assign('yqsj',$yqsj);
            $this->assign("total",$total);
            $this->assign('date1',$date1);
            $this->assign('date2',$date2);
            $this->assign('companyName',$companyName);

            return $this->fetch();
        }else{
            return $this->redirect('/index.php/Index/Login/login');
        }
    }

    public function zf_sx(){
        session_start();

        if(isset($_SESSION["username"])){
            $username=$_SESSION["username"];

            $sqlstr1=\think\Db::name("user_form")->field(["department","newLevel"])->where("username",$username)->find();

            $department=$sqlstr1["department"];
            $newLevel=$sqlstr1["newLevel"];

            if(!isset($_GET["date1"]) && !isset($_GET["date1"]) && !isset($_GET["companyName"])  ){
                $date1="";
                $date2="";
                $companyName="";
            }else{
                $date1=$_GET["date1"];
                $date2=$_GET["date2"];
                $companyName=$_GET["companyName"];
            }

            //分页代码
            if(!isset($_GET["page"]) || !is_numeric($_GET["page"])){
                $page=1;
            }else{
                $page=intval($_GET["page"]);
            }

            $pagesize=15;

            $sqlstr3="select count(*) as total from sx_form a,hk_form b where a.sqid=b.sqid and a.status='已作废' ";
            
            if($date1 !="" && $date2 !=""){
                $sqlstr3= $sqlstr3." and a.date3>='$date1' and a.date3<= '$date2'";
            }

            if($companyName !=""){
                $sqlstr3= $sqlstr3." and a.companyName like '%$companyName%'";
            }

            if($newLevel !="ADMIN" and $department !="财务部"){
                $sqlstr3=$sqlstr3." and (a.department='$department' or a.gxDepartment like '%$department%')";
            }
            
            $sqlstr3=Db::query($sqlstr3);

            $total=$sqlstr3[0]['total'];

            if($total%$pagesize==0){
                $pagecount=intval($total/$pagesize);
            }else{
                $pagecount=ceil($total/$pagesize);
            }

            $sqlstr2="select distinct a.id,a.date1,a.sqid,a.companyName,a.department,a.ywy,a.sqmoney,". 
                        "b.dhkje,a.status2,a.status,c.newMoney ".
                        "from sx_form a,hk_form b,use_sx c where a.sqid=b.sqid and a.sqid=c.sqid and a.status='已作废' ";

            if($date1 !="" and $date2 !=""){
                $sqlstr2=$sqlstr2." and a.date3 >= '$date1' and a.date3 <= '$date2'";
            }

            if($companyName !=""){
                $sqlstr2=$sqlstr2." and a.companyName like '%$companyName%'";
            }
            
            if($newLevel !="ADMIN" and $department !="财务部"){
                $sqlstr2=$sqlstr2." and (a.department='$department' or a.gxDepartment like '%$department%')";
            }

            $sqlstr2=$sqlstr2." order by a.date1 desc limit ".($page-1)*$pagesize.",$pagesize";

            $sxs=Db::query($sqlstr2);


            $this->assign('username',$username);
            $this->assign('date1',$date1);
            $this->assign('date2',$date2);
            $this->assign('companyName',$companyName);
            $this->assign('newLevel',$newLevel);
            $this->assign("total",$total);
            $this->assign('pagecount',$pagecount);
            $this->assign('page',$page);
            $this->assign('sxs',$sxs);

            return $this->fetch();
        }else{
            return $this->redirect('/index.php/Index/Login/login');
        }
    }

    public function write_sxHandle(){
        session_start();

        if(isset($_SESSION["username"])){
            $username=$_SESSION["username"];

            $sx_id_id=$this->request->param('sx_id_id'); //授信编号对应表单的id
            $sqid=$this->request->param('sqid');   //授信编号
            $companyName=$this->request->param('companyName');//公司名称
            $sqmoney=$this->request->param('sqmoney');  //授信额度
            $sxf=$this->request->param('sxf');  //手续费
            $note=$this->request->param('note');  //备注
            $isgx=$this->request->param('isgx');  //是否共享
            $qs=$this->request->param('qs');  //回款期数
            $gxCount_val=$this->request->param('gxCount_val');  //共享事业部数
            $bpeople=$this->request->param('bpeople');  //共享事业部数

            $gxDepartment="";//共享事业部

            $sqlstr1=\think\Db::name("user_form")->field(["department","newLevel"])->where("username",$username)->find();

            $department=$sqlstr1["department"];
            $newLevel=$sqlstr1["newLevel"];

            if((int)$gxCount_val>0){
                for($i=1;$i<=(int)$gxCount_val;$i++){
                    if($i<(int)$gxCount_val){
                        $gxDepartment=$gxDepartment.$this->request->param('gxDepartment'.$i).",";
                    }else{
                        $gxDepartment=$gxDepartment.$this->request->param('gxDepartment'.$i);
                    }
                }
            }
    
            date_default_timezone_set("Asia/Shanghai");
            $date1=date('Y-m-d', time());  //签署日期
            $date2=$this->request->param('date2');  //授信期限开始
            $date3=$this->request->param('date3');  //授信期限结束

            for($i=1;$i<=12;$i++){
                if($i<=(int)$qs){
                    $dateTime[]=$this->request->param('dateTime'.$i);
                    $hkje[]=$this->request->param('hkje'.$i);
                    $hkfs[]=$this->request->param('hkfs'.$i);
                    $hkjhbz[]=$this->request->param('hkjhbz'.$i);
                    $wyfl[]=$this->request->param('wyfl'.$i);
                }else{
                    $dateTime[]="";
                    $hkje[]="";
                    $hkfs[]="";
                    $hkjhbz[]="";
                    $wyfl[]="";
                }
            }

            $dateTime=implode(",",$dateTime);
            $hkje=implode(",",$hkje);
            $hkfs=implode(",",$hkfs);
            $hkjhbz=implode(",",$hkjhbz);
            $wyfl=implode(",",$wyfl);

            $maxID=Db::name("sx_form")->field("max(id)")->find();
            $maxID=$maxID["max(id)"];

            if($maxID==""){
                $maxID=0;
            }

            //防止授信单号重复
            $dup="false";

            $sx_no=Db::name("sx_id")->field("name")->find();

            $sx_no=$sx_no["name"];

            if($sqid < $sx_no){
                $sqid_no=(int)substr($sqid,6,9)+1;

                if($sqid_no<10){
                    $sqid=substr($sqid,0,6)."00".$sqid_no;
                }elseif($sqid_no<100){
                    $sqid=substr($sqid,0,6)."0".$sqid_no;
                }else{
                    $sqid=substr($sqid,0,6).$sqid_no;
                }

                $dup="true";
            }

            if($sx_id_id!=""){
                $sqlstr3=Db::query("update sx_form set companyName='$companyName',ywy='$username',department='$department',date1='$date1',sqid='$sqid',sqmoney='$sqmoney',sxf='$sxf',dateTime='$dateTime',
                                hkje='$hkje',wyfl='$wyfl',hkfs='$hkfs',hkfsbz='$hkjhbz',note='$note',date2='$date2',date3='$date3',isgx='$isgx',gxCount_val='$gxCount_val',gxDepartment='$gxDepartment',status ='待生效',bpeople='$bpeople' where sqid='$sqid'");
            
            }else{
                $sqlstr3=Db::query("insert into sx_form values('$maxID'+1,'$companyName','$username','$department','$date1','$sqid','$sqmoney','$sxf','$dateTime','$hkje','$wyfl','$hkfs','$hkjhbz','$note','','','$date2','$date3','待生效','待上传纸质附件','','','$isgx','$gxCount_val','$gxDepartment','$bpeople') ");
            }

  
            $maxID=Db::name("hk_form")->field("max(id)")->find();
    
            $maxID=$maxID["max(id)"];

            if($maxID==""){
                $maxID=0;
            }

            if($sx_id_id!=""){
                $sqlstr4=Db::query("update hk_form set companyName='$companyName',department='$department',ywy='$username',sqid='$sqid',dhkje='$sqmoney' where sqid='$sqid'");
                $sqlstr10=Db::query("update hk_form2 set companyName='$companyName',department='$department',ywy='$username',sqid='$sqid',dhkje='$sqmoney' where sqid='$sqid'");
            }else{
                $sqlstr4=Db::query("insert into  hk_form values('$maxID'+1,'$companyName','$department','$username','$sqid','$date1',',,,,,,,,,,,',',,,,,,,,,,,',',,,,,,,,,,,',',,,,,,,,,,,',null,'$sqmoney')");
                $sqlstr10=Db::query("insert into  hk_form2 values('$maxID'+1,'$companyName','$department','$username','$sqid','$date1',',,,,,,,,,,,',',,,,,,,,,,,',',,,,,,,,,,,',',,,,,,,,,,,',null,'$sqmoney',null)");
            }

            $sqlstr5=Db::query("update sx_id set name='$sqid' where id=1");

            $maxID=Db::name("use_sx")->field("max(id)")->find();;
        
            $maxID=$maxID["max(id)"];

            if($maxID==""){
                $maxID=0;
            }

            if($sx_id_id!=""){
                $sqlstr9=Db::query("update use_sx set sqid='$sqid',sqmoney='$sqmoney',remainMoney='$sqmoney',useDepartment='$department',date='$date1',newMoney='$sqmoney' where sqid='$sqid'");
            }else{
                $sqlstr9=Db::query("insert into use_sx values('$maxID'+1,'$sqid','$sqmoney',0,0,'$sqmoney','','$department','$date1','新增授信','$sqmoney')");
            }

            return $this->redirect('/index.php/Index/sx/djLoad.html');

        }else{
            return $this->redirect('/index.php/Index/Login/login');
        }
    
    }

    public function sx_Handle(){
        session_start();

        if(isset($_SESSION["username"])){
            $progress=$_GET["progress"];

            if($progress == "1"){
                $sqid=$this->request->param("id");
        
                if(!empty($_FILES['upfile']['name'])){
                    $fileinfo=$_FILES['upfile'];
                    if($fileinfo['size']<209715200 && $fileinfo['size']>0){
                        
                        $path=getcwd()."/file/sx_file/".$_FILES["upfile"]["name"];
                        move_uploaded_file($fileinfo['tmp_name'],$path);

                        $fileName=$_FILES['upfile']['name'];

                        echo "update sx_form set file_name='$fileName',status='待归档',status2='纸质附件已上传' where id='$sqid'";

                        $sqlstr3=Db::query("update sx_form set file_name='$fileName',status='待归档',status2='纸质附件已上传' where id='$sqid'");
                    

                        return $this->redirect('/index.php/Index/sx/dsh_sx.html');
                    }    
                }
                
            }else if($progress == "2"){
        
                $id=$this->request->param("id");
                $option=$this->request->param("option");
        
                if($option == "1"){
                    $sqlstr3=Db::query("update sx_form set status='已生效' where id='$id'");
        
                }else{
                    $sqlstr3=Db::query("update sx_form set status='已拒绝' where id='$id'");
                }

                return $this->redirect('/index.php/Index/sx/dhk_sx.html');
            }
        }else{
            return $this->redirect('/index.php/Index/Login/login');
        }
    
    }

    public function hk_sxHandle(){
        session_start();

        if(isset($_SESSION["username"])){

            $username=$_SESSION["username"];
        
            $sqlstr1=\think\Db::name("user_form")->field(["department","newLevel"])->where("username",$username)->find();

            $department=$sqlstr1["department"];
            $newLevel=$sqlstr1["newLevel"];

            $sxbh=$this->request->param('sxbh');

            date_default_timezone_set("Asia/Shanghai");
            $date1=date('Y-m-d', time());  //签署日期
        
            $cn=$this->request->param('cn');
            $ywy=$this->request->param('ywy');
            $hkqs=$this->request->param('hkqs');
            $date2=$this->request->param('date2');
            $sjhkje=$this->request->param('sjhkje');
            $hkfs=$this->request->param('hkfs');
            $hkfs2=$this->request->param('hkfs2');
            $syjehkfs=$this->request->param('syjehkfs');
        
            $sqlstr1=Db::name("hk_form")->field(["date2","sjhkje","hkfs","hkfs2","dhkje"])->where("sqid",$sxbh)->find();
            
            $sql_date2=$sqlstr1["date2"];
            $sql_sjhkje=$sqlstr1["sjhkje"];
            $sql_hkfs=$sqlstr1["hkfs"];
            $sql_hkfs2=$sqlstr1["hkfs2"];
            $sql_dhkje=$sqlstr1["dhkje"];
        
            $sql_dhkje=(float)$sql_dhkje - (float)$sjhkje;
        
            $sql_date2_arr=explode(",",$sql_date2);
            $sql_sjhkje_arr=explode(",",$sql_sjhkje);
            $sql_hkfs_arr=explode(",",$sql_hkfs);
            $sql_hkfs2_arr=explode(",",$sql_hkfs2);
            
            for($i=1;$i<13;$i++){
                $str="第".$i."期";
        
                if($hkqs==$str){
                    $sql_date2_arr[$i-1]=$date2;
                    $sql_sjhkje_arr[$i-1]=$sjhkje;
                    $sql_hkfs_arr[$i-1]=$hkfs;
                    $sql_hkfs2_arr[$i-1]=$hkfs2;
                }
            }
            
            $sql_date2=implode(",",$sql_date2_arr);
            $sql_sjhkje=implode(",",$sql_sjhkje_arr);
            $sql_hkfs=implode(",",$sql_hkfs_arr);
            $sql_hkfs2=implode(",",$sql_hkfs2_arr);
                
            $sqlstr3=Db::query("update hk_form2 set date1='$date1',date2='$sql_date2',sjhkje='$sql_sjhkje',".
            "hkfs='$sql_hkfs',hkfs2='$hkfs2',syjehkfs='$syjehkfs',dhkje='$sql_dhkje',status='待财务审批' ".
            "where sqid='$sxbh'");
                
            $sqlstr4=Db::name("sxhk_form")->field("max(id)")->find();

            $maxID=$sqlstr4["max(id)"];
        
            $sqlstr5=Db::query("insert into sxhk_form values('$maxID'+1,'$sxbh','$cn','$department','$ywy','$sjhkje','$date1','待财务审批')");
        
            $id=Db::name("sx_form")->field("id")->where("sqid",$sxbh)->find();
            $id=$id["id"];

            return $this->redirect('/index.php/Index/sx/sx_line2.html?id='.$id);
        }else{
            return $this->redirect('/index.php/Index/Login/login');
        }
    }

    public function sx_line2(){
        session_start();

        if(isset($_SESSION["username"])){

            $username=$_SESSION["username"];

            $sqlstr1=\think\Db::name("user_form")->field(["department","newLevel"])->where("username",$username)->find();

            $department=$sqlstr1["department"];
            $newLevel=$sqlstr1["newLevel"];

            $id=$this->request->param("id");

            $sx_lines=Db::query("select a.sqid,a.companyName,a.department,a.ywy,a.date1,a.sqmoney,a.sxf,". 
            "b.dhkje,a.shr,a.date2 as startDate,a.date3 as endDate,a.dateTime,a.hkje,a.wyfl,a.hkfs,a.hkfsbz,b.date2,".
            "b.sjhkje,b.hkfs as sjhkfs,b.hkfs2,b.status as c_status,a.file_name,a.status,a.status2,a.allTime,a.note,a.gxDepartment,b.syjehkfs,b.id,b.status,a.bpeople ".
            "from sx_form a,hk_form2 b where a.sqid=b.sqid and a.id=$id");

            $status=$sx_lines[0]["status"];
            $sx_line=$sx_lines[0];

            $shr2_arr=explode(",",$sx_lines[0]["shr"]);

            $syhkje=$sx_lines[0]["dhkje"];
            $dateTime=explode(",",$sx_lines[0]["dateTime"]);
            $hkje=explode(",",$sx_lines[0]["hkje"]);
            $wyfl=explode(",",$sx_lines[0]["wyfl"]);
            $hkfs=explode(",",$sx_lines[0]["hkfs"]);
            $hkfsbz=explode(",",$sx_lines[0]["hkfsbz"]);
            $date2=explode(",",$sx_lines[0]["date2"]);
            $sjhkje=explode(",",$sx_lines[0]["sjhkje"]);
            $sjhkfs=explode(",",$sx_lines[0]["sjhkfs"]);
            $hkfs2=explode(",",$sx_lines[0]["hkfs2"]);
            $note=$sx_lines[0]["note"];
            $syjehkfs=$sx_lines[0]["syjehkfs"];



            
            $this->assign('username',$username);
            $this->assign('title',"待审核回款");
            $this->assign('status',$status);
            $this->assign('newLevel',$newLevel);
            $this->assign('department',$department);
            $this->assign('sx_line',$sx_line);
            $this->assign('dateTime',$dateTime);
            $this->assign('date2',$date2);
            $this->assign('hkje',$hkje);
            $this->assign('wyfl',$wyfl);
            $this->assign('hkfsbz',$hkfsbz);
            $this->assign('hkfs',$hkfs);
            $this->assign('sjhkje',$sjhkje);
            $this->assign('hkfs2',$hkfs2);
            $this->assign('sjhkfs',$sjhkfs);
            

            return $this->fetch();

        }else{
            return $this->redirect('/index.php/Index/Login/login');
        }
    }

    public function sx_hkHandle(){
        session_start();

        if(isset($_SESSION["username"])){

            $username=$_SESSION["username"];

            $id=$this->request->param("id");
            $option=$this->request->param("option");
        
            //同意授信，form=form2，拒绝，form2=form
        
            if($option==1){
                $hk_form=Db::name("hk_form2")->where("id",$id)->find();
            
                $companyName=$hk_form["companyName"];
                $department=$hk_form["department"];
                $ywy=$hk_form["ywy"];
                $sqid=$hk_form["sqid"];
                $date1=$hk_form["date1"];
                $date2=$hk_form["date2"];
                $sjhkje=$hk_form["sjhkje"];
                $hkfs=$hk_form["hkfs"];
                $hkfs2=$hk_form["hkfs2"];
                $syjehkfs=$hk_form["syjehkfs"];
                $dhkje=$hk_form["dhkje"];
                $status=$hk_form["status"];
            
                $sqlstr1=Db::query("update hk_form set companyName='$companyName',department='$department',ywy='$ywy',
                            sqid='$sqid',date1='$date1',date2='$date2',sjhkje='$sjhkje',
                            hkfs='$hkfs',hkfs2='$hkfs2',syjehkfs='$syjehkfs',dhkje='$dhkje' where id=$id");
            
                            
                if($dhkje==0){
                    $sqlstr4=Db::query("update sx_form set status='已完成' where sqid='$sqid'");
                }
            
                $sqlstr5=Db::query("update hk_form2 set status='已审核' where sqid='$sqid'");
                
                $sqlstr6=Db::query("update sxhk_form set status='已审核' where sqid='$sqid' and status='待财务审批' ");

                return $this->redirect('/index.php/Index/sx/sx_cw.html');
            }else{
                $sqlstr2="select * from hk_form where id=$id";
        
                $result=mysqli_query($conn,$sqlstr2);
            
                while($myrow=mysqli_fetch_row($result)){
                    $companyName=$myrow[1];
                    $department=$myrow[2];
                    $ywy=$myrow[3];
                    $sqid=$myrow[4];
                    $date1=$myrow[5];
                    $date2=$myrow[6];
                    $sjhkje=$myrow[7];
                    $hkfs=$myrow[8];
                    $hkfs2=$myrow[9];
                    $syjehkfs=$myrow[10];
                    $dhkje=$myrow[11];
                    $status=$myrow[12];
                }
            
                $sqlstr3="update hk_form2 set companyName='$companyName',department='$department',ywy='$ywy',
                            sqid='$sqid',date1='$date1',date2='$date2',sjhkje='$sjhkje',
                            hkfs='$hkfs',hkfs2='$hkfs2',syjehkfs='$syjehkfs',dhkje='$dhkje' where id=$id";
            
                $result2=mysqli_query($conn,$sqlstr3);
            
                $sqlstr3="select id from sx_form where sqid='$sqid'";
            
                $result=mysqli_query($conn,$sqlstr3);
            
                while($myrow=mysqli_fetch_row($result)){
                    $my_id=$myrow[0];
                }
            
                if($dhkje==0){
                    $sqlstr4="update sx_form set status='已完成' where id=$my_id";
                }
            
                $result=mysqli_query($conn,$sqlstr4);
            
                $sqlstr5="update hk_form2 set status='已拒绝' where id=$id";
                $result=mysqli_query($conn,$sqlstr5);
        
                $sqlstr6="update sxhk_form set status='已拒绝' where sqid='$sqid' and status='待财务审批' ";
                $result6=mysqli_query($conn,$sqlstr6);
            }

            
        }else{
            return $this->redirect('/index.php/Index/Login/login');
        }
    }

    public function template_sx(){
        session_start();

        if(isset($_SESSION["username"])){

            $username=$_SESSION["username"];

            $id=$this->request->param("id");

            $sx_line=Db::name("sx_form")->where("id",$id)->find();

            $dateTime=explode(",",$sx_line["dateTime"]);
            $hkje=explode(",",$sx_line["hkje"]);
            $wyfl=explode(",",$sx_line["wyfl"]);
            $hkfs=explode(",",$sx_line["hkfs"]);
            $hkfsbz=explode(",",$sx_line["hkfsbz"]);
            

            $sx_line["startDate_arr"]=explode("-",$sx_line["date2"]);
            $sx_line["endDate_arr"]=explode("-",$sx_line["date3"]);
            $sx_line["reDate_arr"]=explode("-",$sx_line["date1"]);


            $this->assign("username",$username);
            $this->assign("title","授信额度模板");
            $this->assign("sx_line",$sx_line);
            $this->assign("dateTime",$dateTime);
            $this->assign("hkje",$hkje);
            $this->assign("wyfl",$wyfl);
            $this->assign("hkfs",$hkfs);
            $this->assign("hkfsbz",$hkfsbz);
            
            return $this->fetch();
        
        
        }else{
            return $this->redirect('/index.php/Index/Login/login');
        }
    }

    public function dtemplate_sx(){
        session_start();

        if(isset($_SESSION["username"])){

            $username=$_SESSION["username"];

            $id=$this->request->param("id");
            $ds=$this->request->param("ds");
        
            $sx_line=Db::name("sx_form")->where("id",$id)->find();
        
            $no=$sx_line["sqid"];
            $company=$sx_line["companyName"];
            $sqmoney=$sx_line["sqmoney"];
            $sxf=$sx_line["sxf"];
            $startDate=$sx_line["date2"];
            $endDate=$sx_line["date3"];
            $reDate=$sx_line["date1"];
            $bpeople=$sx_line["bpeople"];
            $dateTime=explode(",",$sx_line["dateTime"]);
            $hkje=explode(",",$sx_line["hkje"]);
            $wyfl=explode(",",$sx_line["wyfl"]);
            $hkfs=explode(",",$sx_line["hkfs"]);
            $hkfsbz=explode(",",$sx_line["hkfsbz"]);

        
            $startDate_arr=explode("-",$startDate);
            $endDate_arr=explode("-",$endDate);
            $reDate_arr=explode("-",$reDate);
        
            $html='
                <div class="zw" style="font-family:楷体;font-size:16px;line-height:28px;">
                    <div class="zw_content">
                        <p class="hd middle" style="text-align:center;font-size:28px;">授信额度协议</p>
                        <p class="middle" >编号：（<span style="text-decoration: underline;">'.$no.'</span>）</p>
                        <p style="margin-top:28px;">尊敬的上海俞兆林品牌管理有限公司领导：</p>
                        <p style="text-indent: 2em;"><span style="text-decoration: underline;" class="longLine">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>（下称申请人）因业务需求向上海俞兆林品牌管理有限公司申领商标、防伪标、合格证等（下称商标辅料），每次申领需向上海俞兆林品牌管理有限公司支付相关品牌服务费，为方便双方合作，现向<span style="text-decoration: underline;">上海俞兆林品牌管理有限公司</span>申请给与<span style="text-decoration: underline;">'.$company.'</span>&nbsp;<span id="xs" style="text-decoration: underline;">'.$sqmoney.'</span>元（大写人民币<span id="ds" style="text-decoration: underline;">'.$ds.'</span>）授信额度，授信手续费<span style="text-decoration: underline;">'.$sxf.'</span>元，授信额度有效期限为<span style="text-decoration: underline;">'.$startDate_arr[0].'</span>年<span style="text-decoration: underline;">'.$startDate_arr[1].'</span>月<span style="text-decoration: underline;">'.$startDate_arr[2].'</span>日至<span style="text-decoration: underline;">'.$endDate_arr[0].'</span>年<span style="text-decoration: underline;">'.$endDate_arr[1].'</span>月<span style="text-decoration: underline;">'.$endDate_arr[2].'</span>日。在授信额度用完时，再次申领辅料需缴纳额度内未结算的相应的标费及辅料费。
                        <p style="text-indent: 2em;">申请人应当支付的品牌服务费具体金额由产品单价、申领数量及品牌服务费费率共同确定，申领数量及品牌服务费费率固定不变，如果因申请人申领的商标辅料使用在不同单价的产品上，导致的实际品牌服务费与本授信欠据已确定品牌服务费产生差异的，可以在支付时间截止日之前书面提出异议，进行金额调整。</p>
                        <p style="text-indent: 2em;">申请人未按支付计划（见附件）支付款项时，每延期一日需按照应付支付的千分之三向你公司支付逾期利息，申请人如超期仍未能支付款项时，上海俞兆林品牌管理有限公司即可凭此据向上海俞兆林品牌管理有限公司所在地的人民法院起诉，包括律师费以及诉讼费等一切费用由申请人承担，诉讼管辖地为债权人所在地的人民法院。</p>
                        <p style="text-indent: 2em;">上述申请人的债务由保证人（<span style="text-decoration:underline">'.$bpeople.'</span>）提供连带担保。</p>
                        <p style="text-indent: 2em;">此据盖章生效，具有法律效力。</p>
                    </div>
                    <div style="margin-top:100px;">
                        <p>申请人：<span style="text-decoration: underline;" class="longLine">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>（盖章）（签字）</p>
                        <p>签署时间：<span style="text-decoration: underline;" class="shortLine">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>年<span style="text-decoration: underline;" class="vshortLine">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>月<span style="text-decoration: underline;" class="vshortLine">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>日</p>
                        <p>签署地点：上海市虹口区东大名路948号19楼</p>
                    </div>
                    <div style="margin-top:20px;">
                        <p>俞兆林品牌管理有限公司签署：</p>    
                        <p>申请人：<span style="text-decoration: underline;margin-top:10px;" class="middleLine">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>部门领导：<span style="text-decoration: underline;" class="middleLine">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>总经理：<span style="text-decoration: underline;" class="middleLine">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>商业运营(COC)：<span style="text-decoration: underline;" class="middleLine">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span></p>
                    </div>
                </div>
        
                <div class="fj" style="font-family:楷体;font-size:16px;margin-top:1000px;">
                    <p style="font-weight:bold;font-size:18px;margin-bottom:20px;">附件：支付计划</p>
                    <table class="table table-responsive table-bordered" style="font-family:楷体;font-size:16px;border-collapse:collapse;" border=1 >
                        <tr>
                            <td colspan="5">授信金额：<span style="text-decoration: underline;">'.$sqmoney.'</span>元   授信起止时间：<span style="text-decoration: underline;">'.$startDate_arr[0].'</span>年<span style="text-decoration: underline;">'.$startDate_arr[1].'</span>月<span style="text-decoration: underline;">'.$startDate_arr[2].'</span>日至<span style="text-decoration: underline;">'.$endDate_arr[0].'</span>年<span style="text-decoration: underline;">'.$endDate_arr[1].'</span>月<span style="text-decoration: underline;">'.$endDate_arr[2].'</span>日</td>
                        </tr>
                        <tr>
                            <td>计划回款时间</td>
                            <td>金额</td>
                            <td>违约费率</td>
                            <td>小计</td>
                            <td>备注</td>
                        </tr>
                        ';
                            $hkje_hj=0;
                            $wyfl_hj=0;
                            $hkjez_hj=0;
        
                            for($i=0;$i<12;$i++){
                                if($dateTime[$i] != ""){
                                    $html=$html.'
                            
                                    <tr>
                                        <td style="width:50px;">'.explode("-",$dateTime[$i])[0].'年'.explode("-",$dateTime[$i])[1].'月'.explode("-",$dateTime[$i])[2].'日'.'</td>
                                        <td>'.$hkje[$i].'</td>
                                        <td style="width:30px;">'.$wyfl[$i].'%</td>
                                        <td>'.$hkje[$i]*($wyfl[$i]/100+1).'</td>
                                        <td>'.$hkfsbz[$i].'</td>
                                    </tr>
        
                                    '
                                    
                                    ;
                                
                                    $hkje_hj=$hkje_hj+$hkje[$i];
                                    $wyfl_hj=$wyfl[$i];
                                    $hkjez_hj=$hkjez_hj+$hkje[$i]*($wyfl[$i]/100+1);
        
                                }
                            }
        
        
        
                        $html=$html.'
        
                        <tr>
                            <td>合计</td>
                            <td>'.$hkje_hj.'</td>
                            <td>'.$wyfl_hj.'%</td>
                            <td>'.$hkjez_hj.'</td>
                            <td></td>
                        </tr>
                    </table>
        
                    <p class="qkr" style="margin-top:40px;">申请人：<span style="text-decoration: underline;" class="longLine">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>（盖章）（签字）</p>
                    <p class="qkr" style="margin-top:20px;">保证人：<span style="text-decoration: underline;" class="longLine">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span></p>
                    <p class="qkr" style="margin-top:20px;"><span style="text-decoration: underline;" class="shortLine">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>年<span style="text-decoration: underline;" class="vshortLine">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>月<span style="text-decoration: underline;" class="vshortLine">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>日</p>
                </div>
            
                <script>
            
                    /** 数字金额大写转换(可以处理整数,小数,负数) */    
                    function smalltoBIG(n)     
                    {    
                        var fraction = ["角", "分"];    
                        var digit = ["零", "壹", "贰", "叁", "肆", "伍", "陆", "柒", "捌", "玖"];    
                        var unit = [ ["元", "万", "亿"], ["", "拾", "佰", "仟"]  ];    
                        var head = n < 0? "欠": "";    
                        n = Math.abs(n);    
                    
                        var s = "";    
                    
                        for (var i = 0; i < fraction.length; i++)     
                        {    
                            s += (digit[Math.floor(n * 10 * Math.pow(10, i)) % 10] + fraction[i]).replace(/零./, "");    
                        }    
                        s = s || "整";    
                        n = Math.floor(n);    
                    
                        for (var i = 0; i < unit[0].length && n > 0; i++)     
                        {    
                            var p = "";    
                            for (var j = 0; j < unit[1].length && n > 0; j++)     
                            {    
                                p = digit[n % 10] + unit[1][j] + p;    
                                n = Math.floor(n / 10);    
                            }    
                            s = p.replace(/(零.)*零$/, "").replace(/^$/, "零")  + unit[0][i] + s;    
                        }    
                        return head + s.replace(/(零.)*零元/, "元").replace(/(零.)+/g, "零").replace(/^整$/, "零元整");    
                    }
                
                    $("#ds").html(function(){
                        //return smalltoBIG($("#xs").html())
                        return "111111111111111111";
                    })
                
                    $(".longLine").html(function(){
                        return "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;"
                    })
                    
                    
                    $(".middleLine").html(function(){
                        return "<?php for($i=0;$i<8;$i++){ echo "&nbsp;"; }?>"
                    })
                
                    $(".shortLine").html(function(){
                        return "<?php for($i=0;$i<5;$i++){ echo "&nbsp;"; }?>"
                    })
                
                    $(".vshortLine").html(function(){
                        return "<?php for($i=0;$i<3;$i++){ echo "&nbsp;"; }?>"
                    })
                
                    $(".btn-blue").click(function(){
                        window.location.href="/sx_line.php?id=<?=$id?>"
                    })
                
                    $(".btn-download").click(function(){
                        window.location.href="/createSX_mb.php?id=<?=$id?>"
                    })
                </script>
            
                <style>
                    p{margin:0}
        
                    .hd{
                        font-size:28px;
                        font-weight:bold;
                    }
                
                    .middle{
                        text-align:center;
                    }
                
                    .qkr{
                        margin-left:220px;
                    }
        
                </style>
                
                ';
        
            /**
             * @desc 方法一、生成word文档
             * @param $content
             * @param string $fileName
             */
            function createWord($content = 'aabbcc', $fileName = '111')
            {
                if (empty($content)) {
                    return;
                }
                $content='<html 
                        xmlns:o="urn:schemas-microsoft-com:office:office" 
                        xmlns:w="urn:schemas-microsoft-com:office:word" 
                        xmlns="http://www.w3.org/TR/REC-html40">
                        <meta charset="UTF-8" />'.$content.'</html>';
                if (empty($fileName)) {
                    $fileName = date('YmdHis').'.doc';
                }
                file_put_contents($fileName, $content);
            }
        
            /**
             * @desc 方法二、生成word文档并下载
             * @param $content
             * @param string $fileName
             */
            function downloadWord($content, $fileName=''){
        
                if(empty($content)){
                    return;
                }
                if (empty($fileName)) {
                    $fileName = date('YmdHis').'.doc';
                }
                // header("location:xxx.doc");
                header("Cache-Control: no-cache, must-revalidate");
                header("Pragma: no-cache");
                header("Content-Type: application/octet-stream");
                header("Content-Disposition: attachment; filename={$fileName}");
        
                $html = '<html xmlns:v="urn:schemas-microsoft-com:vml"
                    xmlns:o="urn:schemas-microsoft-com:office:office"
                    xmlns:w="urn:schemas-microsoft-com:office:word" 
                    xmlns:m="http://schemas.microsoft.com/office/2004/12/omml" 
                    xmlns="http://www.w3.org/TR/REC-html40">';
                $html .= '<head><meta http-equiv="Content-Type" content="text/html;charset="UTF-8" /></head>';
        
                echo $html . '<body>'.$content .'</body></html>';
            }
            
            //createWord($html,"ccc.doc");
            downloadWord($html,"授信额度协议.doc");

        }else{
            return $this->redirect('/index.php/Index/Login/login');
        }
    }
}