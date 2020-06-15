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

            $this->assign('title','新增授信');
            $this->assign('username',$username);

            return $this->fetch();
        }else{
            return $this->redirect('/index.php/Index/Login/login');
        }
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

            $sqlstr2=$sqlstr2." order by a.date1 desc limit ".($page-1)*$pagesize.",$pagesize";

            $sxs=Db::query($sqlstr2);

            for($i=0;$i<sizeof($sxs);$i++){
                $arr_shr=explode(",",$sxs[$i]["status"]);
                $shr=array_pop($arr_shr);
            }

            $this->assign('title','待上传单据授信');
            $this->assign('username',$username);
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
            "b.dhkje,a.shr,a.date2 as startDate,a.date3 as endDate,a.dateTime,a.hkje,a.wyfl,a.hkfs,a.hkfsbz,b.date2,".
            "b.sjhkje,b.hkfs as sjhkfs,b.hkfs2,a.file_name,a.status,a.status2,a.allTime,a.note,a.gxDepartment,b.syjehkfs ".
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
            $date1=explode(",",$sx_lines[0]["date1"]);
            $sjhkje=explode(",",$sx_lines[0]["sjhkje"]);
            $sjhkfs=explode(",",$sx_lines[0]["sjhkfs"]);
            $hkfs2=explode(",",$sx_lines[0]["hkfs2"]);
            $note=$sx_lines[0]["note"];
            $syjehkfs=$sx_lines[0]["syjehkfs"];

            $fls=Db::query("select a.*,b.id as fl_id from use_sx a left join flsqd b on a.fl_no=b.no  where a.sqid='$sqid' and a.fl_no <> '' order by a.id asc");

            $this->assign('username',$username);
            $this->assign('status',$status);
            $this->assign('sx_line',$sx_line);
            $this->assign('newLevel',$newLevel);

            $this->assign('dateTime',$dateTime);
            $this->assign('date1',$date1);
            $this->assign('hkje',$hkje);
            $this->assign('wyfl',$wyfl);
            $this->assign('hkfsbz',$hkfsbz);

            $this->assign('hkfs',$hkfs);
            $this->assign('sjhkje',$sjhkje);
            $this->assign('hkfs2',$hkfs2);
            $this->assign('sjhkfs',$sjhkfs);
            $this->assign('now',$now);


            $this->assign('fls',$fls);
            

            //var_dump($hkje);
            return $this->fetch();
        }else{
            return $this->redirect('/index.php/Index/Login/login');
        }
    }

    public function sx_cw(){
        session_start();

        if(isset($_SESSION["username"])){
            $username=$_SESSION["username"];

            $this->assign('username',$username);

            return $this->fetch();
        }else{
            return $this->redirect('/index.php/Index/Login/login');
        }
    }

    public function time_sx(){
        session_start();

        if(isset($_SESSION["username"])){
            $username=$_SESSION["username"];

            $this->assign('username',$username);

            return $this->fetch();
        }else{
            return $this->redirect('/index.php/Index/Login/login');
        }
    }

    public function expire_sx(){
        session_start();

        if(isset($_SESSION["username"])){
            $username=$_SESSION["username"];

            $this->assign('username',$username);

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


            $this->assign('username',$username);
            $this->assign('date1',$date1);
            $this->assign('date2',$date2);
            $this->assign('companyName',$companyName);
            $this->assign('newLevel',$newLevel);

            return $this->fetch();
        }else{
            return $this->redirect('/index.php/Index/Login/login');
        }
    }

    
}