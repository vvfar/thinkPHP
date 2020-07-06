<?php
namespace app\index\controller;
use think\Controller;
use think\Request;
use think\Db;

class Store extends Controller{

    public function manage_store($status=1){
        session_start();

        if(isset($_SESSION["username"])){
            
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

            if($status==1){
                $sqlstr3="select count(*) as total from store where status='正常'";
            }else{
                $sqlstr3="select count(*) as total from store where status='关闭'";
            }
            

            if($newLevel !="ADMIN" and $department != "商业运营部"){
                if($newLevel == "KA"){
                    $sqlstr3=$sqlstr3." and staff like '%$username%'"; 
                }else{
                    $sqlstr3=$sqlstr3." and '$department' like concat('%',department,'%') ";
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
            $sqlstr2="select id,storeID,client,storeName,department,staff,createDate,htsq,link from store where 1=1";
                        
            if($newLevel !="ADMIN" and $department != "商业运营部"){
                if($newLevel == "KA"){
                    $sqlstr2=$sqlstr2." and staff like '%$username%'"; 
                }else{
                    $sqlstr2=$sqlstr2." and '$department' like concat('%',department,'%') ";
                }
            }

            if($status==1){
                $sqlstr2=$sqlstr2." and status='正常'";
            }else{
                $sqlstr2=$sqlstr2." and status='关闭'";
            }


            $sqlstr2=$sqlstr2."  order by id desc limit ".($page-1)*$pagesize.",$pagesize";

            $stores=Db::query($sqlstr2);

            $data=[
                'title' => '店铺管理',
                'username' => $username,
                "newLevel" => $newLevel,
                "total" => $total,
                'pagecount' => $pagecount,
                'page' => $page,
                'pagesize' => 15,
                'stores'  => $stores,
                'status'  => $status
            ];

            return $this->fetch('',$data);

        }else{
            return $this->redirect('/index.php/Index/Login/login');
        }
    }

    public function store_data1(){
        session_start();

        if(isset($_SESSION["username"])){
            $username=$_SESSION["username"];
            
            $sqlstr1=Db::name("user_form")->field(["department","newLevel"])->where("username",$username)->find();

            $department=$sqlstr1["department"];
            $newLevel=$sqlstr1["newLevel"];
    
            date_default_timezone_set("Asia/Shanghai");
            $dateMonth=date('Y-m', time());
            
            if(!isset($_GET["date"])){
                $date=date('Y-m-d', time());
            }else{
                $date=$_GET["date"];
            }
    
    
            //分页代码
            if(!isset($_GET["page"]) || !is_numeric($_GET["page"])){
                $page=1;
            }else{
                $page=intval($_GET["page"]);
            }
    
            $pagesize=15;
    
    
            $sqlstr3="select count(*) as total from store a,store_data_sales b where a.storeID=b.storeID and b.date='$date' and a.status='正常'";
    
            if($newLevel !="ADMIN" and $department != "商业运营部"){
                if($newLevel == "KA"){
                    $sqlstr3=$sqlstr3." and a.staff like '%$username%'"; 
                }else{
                    $sqlstr3=$sqlstr3." and '$department' like concat('%',a.department,'%') ";
                }
            }
    
            $sqlstr3=Db::query($sqlstr3);
    
            $total=$sqlstr3[0]["total"];
    
            if($total%$pagesize==0){
                $pagecount=intval($total/$pagesize);
            }else{
                $pagecount=ceil($total/$pagesize);
            }
    
            $year=substr($date,0,4);
            $dateMonth2=substr($date,0,10);

            $sqlstr2="select a.storeID,a.client,a.storeName,a.staff,b.salesMoney,d.storeTarget,a.status,c.sumMoney from store_data_sales b,store a join (select storeID,sum(salesMoney) as sumMoney from store_data_sales where date like '%$dateMonth2%' group by storeID) c on a.storeID=c.storeID left join store_target d on a.storeID=d.storeID and d.dateMonth='$dateMonth2' where a.storeID=b.storeID and b.date='$date' and a.status='正常' ";

            if($newLevel !="ADMIN" and $department != "商业运营部"){
                if($newLevel == "KA"){
                    $sqlstr2=$sqlstr2." and a.staff like '%$username%'"; 
                }else{
                    $sqlstr2=$sqlstr2." and '$department' like concat('%',a.department,'%') ";
                }
            }

            $sqlstr2=$sqlstr2." order by b.id desc limit ".($page-1)*$pagesize.",$pagesize";

            $store_datas=Db::query($sqlstr2);

            $data=[
                'title' => '店铺销售数据',
                'username' => $username,
                "newLevel" => $newLevel,
                "total" => $total,
                'pagecount' => $pagecount,
                'page' => $page,
                'pagesize' => 15,
                'store_datas' => $store_datas,
                'department' => $department,
                'date' => $date
            ];

            return $this->fetch('',$data);
        }else{
            return $this->redirect('/index.php/Index/Login/login');
        }
    }

    public function store_data2(){
        session_start();

        if(isset($_SESSION["username"])){

            $username=$_SESSION["username"];
        
            $sqlstr1=Db::name("user_form")->field(["department","newLevel"])->where("username",$username)->find();

            $department=$sqlstr1["department"];
            $newLevel=$sqlstr1["newLevel"];

            date_default_timezone_set("Asia/Shanghai");
            $dateMonth=date('Y-m', time());
            
            if(!isset($_GET["date"])){
                $date=date('Y-m-d', time());
            }else{
                $date=$_GET["date"];
            }


            //分页代码
            if(!isset($_GET["page"]) || !is_numeric($_GET["page"])){
                $page=1;
            }else{
                $page=intval($_GET["page"]);
            }

            $pagesize=15;


            $sqlstr3="select count(*) as total from store a,store_data_hk b where a.storeID=b.storeID and b.date='$date' and a.status='正常' ";

            if($newLevel !="ADMIN" and $department != "商业运营部"){
                if($newLevel == "KA"){
                    $sqlstr3=$sqlstr3." and a.staff like '%$username%'"; 
                }else{
                    $sqlstr3=$sqlstr3." and '$department' like concat('%',a.department,'%') ";
                }
            }

            $sqlstr3=Db::query($sqlstr3);

            $total=$sqlstr3[0]["total"];

            if($total%$pagesize==0){
                $pagecount=intval($total/$pagesize);
            }else{
                $pagecount=ceil($total/$pagesize);
            }

            $year=substr($date,0,4);

            $sqlstr2="select a.storeID,a.client,a.storeName,a.staff,b.backMoney,d.hkTarget,a.status,c.backMoney from store_data_hk b,store a join (select storeID,sum(backMoney) as backMoney from store_data_hk where date like '%$dateMonth%' group by storeID) c on a.storeID=c.storeID left join store_target d on a.storeID=d.storeID and d.dateMonth='$dateMonth'  where a.storeID=b.storeID and b.date='$date' and a.status='正常' ";

            if($newLevel !="ADMIN" and $department != "商业运营部"){
                if($newLevel == "KA"){
                    $sqlstr2=$sqlstr2." and a.staff like '%$username%'"; 
                }else{
                    $sqlstr2=$sqlstr2." and '$department' like concat('%',a.department,'%') ";
                }
            }

            $sqlstr2=$sqlstr2." order by b.id desc limit ".($page-1)*$pagesize.",$pagesize";

            $store_datas=Db::query($sqlstr2);

            $data=[
                'title' => '店铺回款数据',
                'username' => $username,
                'newLevel' => $newLevel,
                "total" => $total,
                'pagecount' => $pagecount,
                'page' => $page,
                'pagesize' => 15,
                'store_datas' => $store_datas,
                'department' => $department,
                'date' => $date
            ];

            return $this->fetch('',$data);
        
        }else{
            return $this->redirect('/index.php/Index/Login/login');
        }
    }

    public function store_data_details1($storeID){
        session_start();

        if(isset($_SESSION["username"])){
            
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


            $sqlstr3="select count(*) as total from store a,store_data_sales b where a.storeID=b.storeID and a.storeID='$storeID'";

            if($newLevel !="ADMIN" and $department != "商业运营部"){
                if($newLevel == "KA"){
                    $sqlstr3=$sqlstr3." and a.staff like '%$username%'"; 
                }else{
                    $sqlstr3=$sqlstr3." and '$department' like concat('%',a.department,'%') ";
                }
            }

            $sqlstr1=Db::query($sqlstr3);
            $total=$sqlstr1[0]["total"];

            if($total%$pagesize==0){
                $pagecount=intval($total/$pagesize);
            }else{
                $pagecount=ceil($total/$pagesize);
            }

            $store_line=Db::name("store")->field(["storeID","client","storeName","pingtai","category","department","staff","status"])->where("storeID",$storeID)->find();

            $date=$this->request->param("date");

            if($date==""){
                date_default_timezone_set("Asia/Shanghai");
                $date=date('Y-m-d', time());
            }

            $sqlstr2="select a.storeID,a.client,a.storeName,a.pingTai,a.category,b.salesMoney,b.salesNum,b.date,a.id from store a,store_data_sales b where a.storeID=b.storeID and a.storeID='$storeID'";
                        
            if($newLevel !="ADMIN" and $department != "商业运营部"){
                if($newLevel == "KA"){
                    $sqlstr2=$sqlstr2." and a.staff like '%$username%'"; 
                }else{
                    $sqlstr2=$sqlstr2." and '$department' like concat('%',a.department,'%') ";
                }
            }

            $sqlstr2=$sqlstr2." order by b.date desc limit ".($page-1)*$pagesize.",$pagesize";

            $store_datas=Db::query($sqlstr2);
            
            $data=[
                'title' => '店铺销售数据',
                'username' => $username,
                'department' => $department,
                'newLevel' => $newLevel,
                "total" => $total,
                'pagecount' => $pagecount,
                'pagesize' => 15,
                'page' => $page,
                'storeID' => $storeID,
                'store_line' => $store_line,
                'store_datas' => $store_datas
            ];


            return $this->fetch('',$data);
        }else{
            return $this->redirect('/index.php/Index/Login/login');
        }
    }

    public function store_data_details2($storeID){
        session_start();

        if(isset($_SESSION["username"])){

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

            $sqlstr3="select count(*) as total from store a,store_data_hk b where a.storeID=b.storeID and a.storeID='$storeID'";

            if($newLevel !="ADMIN" and $department != "商业运营部"){
                if($newLevel == "KA"){
                    $sqlstr3=$sqlstr3." and a.staff like '%$username%'"; 
                }else{
                    $sqlstr3=$sqlstr3." and '$department' like concat('%',a.department,'%') ";
                }
            }

            $sqlstr1=Db::query($sqlstr3);
            $total=$sqlstr1[0]["total"];

            if($total%$pagesize==0){
                $pagecount=intval($total/$pagesize);
            }else{
                $pagecount=ceil($total/$pagesize);
            }

            $store_line=Db::name("store")->field(["storeID","client","storeName","pingtai","category","department","staff","status","id"])->where("storeID",$storeID)->find();

            $date=$this->request->param("date");

            if($date==""){
                date_default_timezone_set("Asia/Shanghai");
                $date=date('Y-m-d', time());
            }

            $sqlstr2="select a.storeID,a.client,a.storeName,a.pingTai,a.category,b.backMoney,b.date from store a,store_data_hk b where a.storeID=b.storeID and a.storeID='$storeID'";
                        
            if($newLevel !="ADMIN" and $department != "商业运营部"){
                if($newLevel == "KA"){
                    $sqlstr2=$sqlstr2." and a.staff like '%$username%'"; 
                }else{
                    $sqlstr2=$sqlstr2." and '$department' like concat('%',a.department,'%') ";
                }
            }

            $sqlstr2=$sqlstr2." order by b.date desc limit ".($page-1)*$pagesize.",$pagesize";

            $store_datas=Db::query($sqlstr2);

            $data=[
                'title' => '店铺回款数据',
                'username' => $username,
                'department' => $department,
                'newLevel' => $newLevel,
                "total" => $total,
                'pagecount' => $pagecount,
                'pagesize' => 15,
                'page' => $page,
                'storeID' => $storeID,
                'store_line' => $store_line,
                'store_datas' => $store_datas
            ];
        
            return $this->fetch('',$data);

        }else{
            return $this->redirect('/index.php/Index/Login/login');
        }
    }

    public function store_qs($status=0){
        session_start();

        if(isset($_SESSION["username"])){

            $username=$_SESSION["username"];
        
            $sqlstr1=Db::name("user_form")->field(["department","newLevel"])->where("username",$username)->find();

            $department=$sqlstr1["department"];
            $newLevel=$sqlstr1["newLevel"];

            if(!isset($_GET["date"])){
                date_default_timezone_set("Asia/Shanghai");
                $date=date('Y-m-d', time());
            }else{
                $date=$_GET["date"];
            }


            //分页代码
            if(!isset($_GET["page"]) || !is_numeric($_GET["page"])){
                $page=1;
            }else{
                $page=intval($_GET["page"]);
            }

            $pagesize=15;


            $sqlstr3="select count(*) as total from store a,store_qs b where a.storeID=b.storeID ";

            if($status==0){
                $sqlstr3=$sqlstr3."and b.status='待处理' "; 
            }else{
                $sqlstr3=$sqlstr3."and b.status='已处理' "; 
            }

            if($newLevel !="ADMIN" and $department != "商业运营部"){
                if($newLevel == "KA"){
                    $sqlstr3=$sqlstr3." and a.staff like '%$username%'"; 
                }else{
                    $sqlstr3=$sqlstr3." and '$department' like concat('%',a.department,'%') ";
                }
            }

            $sqlstr3=Db::query($sqlstr3);

            $total=$sqlstr3[0]["total"];

            if($total%$pagesize==0){
                $pagecount=intval($total/$pagesize);
            }else{
                $pagecount=ceil($total/$pagesize);
            }

            $sqlstr2="select a.storeID,a.client,a.storeName,a.staff,b.question,b.answer,b.status,b.date,b.id from  store a,store_qs b where a.storeID=b.storeID ";


            if($status==0){
                $sqlstr2=$sqlstr2."and b.status='待处理' "; 
            }else{
                $sqlstr2=$sqlstr2."and b.status='已处理' "; 
            }

            if($newLevel !="ADMIN" and $department != "商业运营部"){
                if($newLevel == "KA"){
                    $sqlstr2=$sqlstr2." and a.staff like '%$username%'"; 
                }else{
                    $sqlstr2=$sqlstr2." and '$department' like concat('%',a.department,'%') ";
                }
            }

            $sqlstr2=$sqlstr2." order by b.date desc limit ".($page-1)*$pagesize.",$pagesize";

            $store_qss=Db::query($sqlstr2);

            $data=[
                'title' => '店铺问题',
                'username' => $username,
                'newLevel' => $newLevel,
                "total" => $total,
                'pagecount' => $pagecount,
                'page' => $page,
                'pagesize' => 15,
                'store_qss' => $store_qss,
                'department' => $department,
                'date' => $date,
                'status' => $status,
            ];
        
            return $this->fetch('',$data);
        
        }else{
            return $this->redirect('/index.php/Index/Login/login');
        }
    }

    public function store_daily($id){
        session_start();

        if(isset($_SESSION["username"])){
            
            $username=$_SESSION["username"];

            $date=$this->request->param("date");
            $hk=$this->request->param("hk");

            $sqlstr1=Db::name("user_form")->field(["department","newLevel"])->where("username",$username)->find();

            $department=$sqlstr1["department"];
            $newLevel=$sqlstr1["newLevel"];

            $store_line=Db::name("store")->where("storeID",$id)->find();

            $data=[
                'title' => '每日数据',
                'username' => $username,
                'newLevel' => $newLevel,
                'store_line' => $store_line,
                'department' => $department,
                'date' => $date,
                'hk' => $hk,
            ];

            return $this->fetch('',$data);

        }else{
            return $this->redirect('/index.php/Index/Login/login');
        }
    }

    public function write_store_qs($id){
        session_start();

        if(isset($_SESSION["username"])){

            $username=$_SESSION["username"];

            $sqlstr1=Db::name("user_form")->field(["department","newLevel"])->where("username",$username)->find();

            $department=$sqlstr1["department"];
            $newLevel=$sqlstr1["newLevel"];
            
            $store_line=Db::name("store")->where("storeID",$id)->find();

            $data=[
                'title' => '店铺问题',
                'username' => $username,
                'newLevel' => $newLevel,
                'store_line' => $store_line,
            ];

            return $this->fetch('',$data);
        }else{
            return $this->redirect('/index.php/Index/Login/login');
        }
    }

    public function store_dailyHandle(){
    
        session_start();

        $username=$_SESSION["username"];

        $sqlstr1=Db::name("user_form")->field(["department","newLevel"])->where("username",$username)->find();

        $department=$sqlstr1["department"];
        $newLevel=$sqlstr1["newLevel"];

        $id=$_POST["id"];
        $storeID=$_POST["storeID"];
        $client=$_POST["client"];
        $storeName=$_POST["storeName"];
    
        if($department =="商业运营部"){
            $salesMoney=$_POST["salesMoney"];
            $salesNum=$_POST["salesNum"];
            $backMoney="";
        }else{
            $backMoney=$_POST["backMoney"];
            $link=$_POST["link"];
            $salesMoney="";
            $salesNum="";
        }
        
        $date=$_POST["dateTime"];
    
        
        if($department =="商业运营部"){

            $maxIDs=Db::name("store_data_sales")->field("max(id)")->find();

            $maxID=$maxIDs["max(id)"];
        
            if($maxID==""){
                $maxID=0;
            }
        
            $store_staffs=Db::name("store")->field("staff")->where("storeID",$storeID)->find();
            $store_staff=$store_staffs["staff"];
                
            $sqlstr2=Db::query("select count(*) from store_data_sales where storeID='$storeID' and date='$date'");

            $dup_data=$sqlstr2[0]["count(*)"];

            if($dup_data >0){
                $sqlstr3=Db::query("update store_data_sales set salesMoney='$salesMoney',salesNum='$salesNum',date='$date',corp='$username' where storeID='$storeID' and date='$date'");
            }else{
                $sqlstr3=Db::query("insert into store_data_sales values('$maxID'+1,'$storeID','$salesMoney','$salesNum','$date','$store_staff','$username')");
            }

            return $this->redirect('/index.php/Index/store/store_data1.html');
    
        }else{
            $maxIDs=Db::name("store_data_hk")->field("max(id)")->find();

            $maxID=$maxIDs["max(id)"];
    
            if($maxID==""){
                $maxID=0;
            }
    
            $store_staffs=Db::name("store")->field("staff")->where("storeID",$storeID)->find();
            $store_staff=$store_staffs["staff"];

            $sqlstr2=Db::query("select count(*) from store_data_hk where storeID='$storeID' and date='$date'");
            $dup_data=$sqlstr2[0]["count(*)"];

            if($dup_data >0){
                $sqlstr3=Db::query("update store_data_hk set backMoney='$backMoney',date='$date',corp='$username' where storeID='$storeID' and date='$date'");
            }else{
                $sqlstr3=Db::query("insert into store_data_hk values('$maxID'+1,'$storeID','$backMoney','$date','$store_staff','$username')");
            }
        
            if($link != ""){
                $sqlstr4=Db::query("update store set link='$link' where storeID='$storeID'");
            }

            return $this->redirect('/index.php/Index/store/store_data2.html');
        }
    }

    public function store_opt(){
        session_start();

        if(isset($_SESSION["username"])){
            $username=$_SESSION["username"];
            
            $sqlstr1=Db::name("user_form")->field(["department","newLevel"])->where("username",$username)->find();

            $department=$sqlstr1["department"];
            $newLevel=$sqlstr1["newLevel"];

            if(isset($_GET['id'])){
                $id=$_GET['id'];
                $title="店铺修改";
            }else{
                $id="";
                $title="新增店铺";
            }

            if($id != ""){
                date_default_timezone_set("Asia/Shanghai");
                $dateMonth=date('Y-m', time());

                $store_lines=Db::query("select a.*,b.storeTarget,b.hkTarget  from store a left join store_target b on a.storeID=b.storeID and b.dateMonth='$dateMonth' where a.storeID='$id'");
                $store_line=$store_lines[0];

            }else{
                $store_line["id"]="";
                $store_line["storeID"]="";
                $store_line["client"]="";
                $store_line["storeName"]="";
                $store_line["pingtai"]="";
                $store_line["category"]=""; 
                $store_line["department"]="";
                $store_line["staff"]="";
                $store_line["storeTarget"]="";
                $store_line["hkTarget"]="";
                $store_line["createDate"]="";
                $store_line["link"]="";
                $store_line["staff_time"]="";
            }

            $users=Db::name("user_form")->field("username")->where("department",$department)->select();

            $data=[
                'title' => '店铺编辑',
                'username' => $username,
                'newLevel' => $newLevel,
                'store_line' => $store_line,
                'users' => $users
            ];

            return $this->fetch('',$data);

        }else{
            return $this->redirect('/index.php/Index/Login/login');
        }
    }

    public function store_optHandle(){
    
        session_start();

        if(isset($_SESSION["username"])){

            $username=$_SESSION["username"];
        
            $id=$this->request->param("id");
            $storeID=$this->request->param("storeID");
            $client=$this->request->param("client");
            $storeName=$this->request->param("storeName");
            $pingtai=$this->request->param("pingtai");
            $category=$this->request->param("category");
            $link=$this->request->param("link");
            $staff=$this->request->param("staff");
            $staff_time=$this->request->param("staff_time");
            $createDate=$this->request->param("createDate");
            $oldStaff=$this->request->param("oldStaff");
            $storeTarget=$this->request->param("storeTarget");
            $hkTarget=$this->request->param("hkTarget");

            $departments=DB::name("user_form")->field("department")->where("username",$username)->find();
            $department=$departments["department"];

            date_default_timezone_set("Asia/Shanghai");
            //$createDate=date('Y-m-d', time());  //签署日期

            $date=date('Y-m-d', time());
            $dateMonth=date('Y-m', time());

            //计算从店铺创立到今天的天数
            $days_store=floor((strtotime($date)-strtotime($createDate))/86400);
            $days_staff=floor((strtotime($date)-strtotime($staff_time))/86400);

            $sqlstr1=DB::query("update store set storeID='$storeID',client='$client',storeName='$storeName',pingtai='$pingtai',category='$category',link='$link' where id='$id'");

            $count_store=DB::query("select count(*) from store_target where storeID='$storeID' and dateMonth='$dateMonth'");
            
            $storeTarget_count=$count_store[0]["count(*)"];

            if($storeTarget_count > 0){
                $sqlstr2=DB::query("update store_target set storeTarget='$storeTarget',hkTarget='$hkTarget' where storeID='$storeID' and dateMonth='$dateMonth'");
            }else{
                $maxIDs=DB::name("store_target")->field("max(id)")->find();
                $maxID=$maxIDs["max(id)"];
            
                if($maxID==""){
                    $maxID=0;
                }

                $sqlstr2=DB::query("insert into store_target values('$maxID'+1,'$storeID','$storeTarget','$hkTarget','$dateMonth') ");
            }

            if($oldStaff != $staff){

                if($days_store<=180){
                    $this->error('新店<=180天无法进行转让！"'.$staff_time.'"','/index.php/Index/store/store_opt.html?id=".$storeID."');
                }else{
                    if($days_staff<=90){
                        $this->error('新店<=180天无法进行转让！"'.$staff_time.'"','/index.php/Index/store/store_opt.html?id=".$storeID."');
                    }else{  
                        $sqlstr3=DB::query("update store set staff='$staff',staff_time='$date' where id='$id'");
                    }
                }
            }

            return $this->redirect('/index.php/Index/store/manage_store.html');

        }else{
            return $this->redirect('/index.php/Index/Login/login');
        }
    }

    public function store_close($id){
    
        session_start();

        if(isset($_SESSION["username"])){

            $username=$_SESSION["username"];

            $store_line=DB::name("store")->where("storeID",$id)->find();

            $data=[
                'title' => '关闭店铺',
                'username' => $username,
                'store_line' => $store_line,
            ];

            return $this->fetch('',$data);

        }else{
            return $this->redirect('/index.php/Index/Login/login');
        }
    }

    public function store_closeHandle($id){
        date_default_timezone_set("Asia/Shanghai");
        $date1=date('Y-m-d', time());
        
        $id=$this->request->param("id");
        $reason=$this->request->param("reason");
    
        $sqlstr1=DB::query("update store set reason='$reason',status='关闭',createDate='$date1' where id='$id'");
    
        return $this->redirect('/index.php/Index/store/manage_store.html?status=0');
    }

    public function store_open($id){
        session_start();

        if(isset($_SESSION["username"])){

            $username=$_SESSION["username"];

            $id=$this->request->param("id");
            $sqlstr1=DB::query("update store set status='正常' where storeID='$id'");

            $this->assign('username',$username);
            $this->assign('title','开放店铺');
            
            return $this->redirect('/index.php/Index/store/manage_store.html?status=1');

        }else{
            return $this->redirect('/index.php/Index/Login/login');
        }
    }

    public function downloadStoreMB(){
        
        $conn=mysqli_connect("localhost","root","root","yzl_database") or die("连接数据库服务器失败！".mysqli_error());
        mysqli_query($conn,"set names utf8");

        session_start();
        $username=$_SESSION['username'];

        $sqlstr1="select storeID,client,storeName,pingtai,category,department,staff from store where status='正常'";
        $result=mysqli_query($conn,$sqlstr1);

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

        $header=array('店铺编号','公司名称','店铺名称','平台','类目','部门','负责人','销售额','销售单量','日期');
                
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

        $list2=range(0,6);

        createtable($data,'每日销售数据',$header,$list2);
    }

    public function downloadStoreData($option){
        $conn=mysqli_connect("localhost","root","root","yzl_database") or die("连接数据库服务器失败！".mysqli_error());
        mysqli_query($conn,"set names utf8");

        session_start();
        $username=$_SESSION['username'];

        date_default_timezone_set("Asia/Shanghai");
        $date=date('Y-m-d', time());  //签署日期

        $startTime=$this->request->param("dateTime2");
        $endTime=$this->request->param("dateTime3");

        $sqlstr1="select department,newLevel from user_form where username='$username'";

        $result=mysqli_query($conn,$sqlstr1);

        while($myrow=mysqli_fetch_row($result)){
            $department=$myrow[0];
            $newLevel=$myrow[1];
        }

        if($option == 1){
            $sqlstr2="select a.storeID,a.client,a.storeName,a.staff,b.salesMoney,b.salesNum,a.status,b.date from store_data_sales b,store a  where date >= '$startTime' and  date <= '$endTime' and a.storeID=b.storeID ";
        }else{
            $sqlstr2="select a.storeID,a.client,a.storeName,a.staff,b.backMoney,a.status,c.backMoney,b.date from store_data_hk b,store a join (select storeID,sum(backMoney) as backMoney from store_data_hk where date >= '2020-01-01' group by storeID) c on a.storeID=c.storeID where a.storeID=b.storeID ";
        }

        if($newLevel !="ADMIN" and $department != "商业运营部"){
            if($newLevel == "KA"){
                $sqlstr2=$sqlstr2." and a.staff like '%$username%'"; 
            }else{
                $sqlstr2=$sqlstr2." and '$department' like concat('%',a.department,'%') ";
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

            }
        }

        if($option == 1){
            $header=array('店铺编号','公司名称','店铺名称','负责人','销售额','销售单量','店铺状态','日期');
        }else{
            $header=array('店铺编号','公司名称','店铺名称','负责人','回款金额','店铺状态','累计回款额','日期');
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

        if($option == 1){
            $list2=range(0,7);
            createtable($data,'销售数据汇总',$header,$list2);
        }else{
            $list2=range(0,7);
            createtable($data,'现金回款数据汇总',$header,$list2);
        }
    }

    public function upload_store_data(){
        
        error_reporting(0);

        $conn=mysqli_connect("localhost","root","root","yzl_database") or die("连接数据库服务器失败！".mysqli_error());
        mysqli_query($conn,"set names utf8");

        require_once getcwd().'/PHPExcel/PHPExcel.php';
        require_once getcwd().'/PHPExcel/PHPExcel/IOFactory.php';

        session_start();
        $username=$_SESSION["username"];

        if(!empty($_FILES['excel']['name'])){
            $fileName=$_FILES['excel']['name'];
            $dotArray=explode('.',$fileName);
            $type=end($dotArray);

            if($type!="xls" && $type!="xlsx"){

                return $this->error('不是Excel文件，请重新上传！','/index.php/Index/store/store_data1.html','','1');
            }else{
                $fileinfo=$_FILES['excel'];

                $a=$fileinfo['size'];

                if($fileinfo['size']<120971520 && $fileinfo['size']>0){

                    //iconv防止出现上传中文名乱码
                    $path=iconv('utf-8','gb2312',getcwd()."/file/daysData/".$_FILES["excel"]["name"]);

                    if(file_exists($path)){
                        return $this->error('文件已存在！','/index.php/Index/store/store_data1.html','','1');
                    }else{
                        move_uploaded_file($fileinfo['tmp_name'],$path);
                    }

                    if(!file_exists($path)){
                        return $this->error('上传文件丢失！','/index.php/Index/store/store_data1.html','','1');
                    }else{
                        //文件的扩展名
                        $ext = strtolower(pathinfo($path, PATHINFO_EXTENSION));
                        if ($ext == 'xlsx') {
                            $objReader = \PHPExcel_IOFactory::createReader('Excel2007');
                            $objPHPExcel = $objReader->load($path, 'utf-8');
                        } elseif ($ext == 'xls') {
                            $objReader = \PHPExcel_IOFactory::createReader('Excel5');
                            $objPHPExcel = $objReader->load($path, 'utf-8');
                        }

                        $sheet = $objPHPExcel->getSheet(0);
                        $highestRow = $sheet->getHighestRow(); // 取得总行数
                        $highestColumn = $sheet->getHighestColumn(); // 取得总列数

                        $ar=array();
                        $i=0;
                        $importRows=0;

                        $sqlstr1="select max(id) from store_data_sales";

                        $result=mysqli_query($conn,$sqlstr1);

                        while($myrow=mysqli_fetch_row($result)){
                            $maxID=$myrow[0];
                        }
                
                        if($maxID==""){
                            $maxID=0;
                        }

                        //读表
                        for($j=2;$j<=$highestRow;$j++){
                            $maxID++;

                            $importRows++;

                            $storeID=(string)$objPHPExcel->getActiveSheet()->getCell("A$j")->getValue();
                            $salesMoney=(string)$objPHPExcel->getActiveSheet()->getCell("H$j")->getValue();
                            $salesNum=(string)$objPHPExcel->getActiveSheet()->getCell("I$j")->getValue();
                            $date=(string)$objPHPExcel->getActiveSheet()->getCell("J$j")->getValue();
                            $staff=(string)$objPHPExcel->getActiveSheet()->getCell("G$j")->getValue();
                            
                            $t1 = $date;
                            $n1 = intval(($t1 - 25569) * 3600 * 24);
                            $date=gmdate('Y-m-d',$n1);
                            
                            $sqlstr2="select count(*) from store_data_sales where storeID='$storeID' and date='$date'";

                            $result=mysqli_query($conn,$sqlstr2);

                            while($myrow=mysqli_fetch_row($result)){
                                $dup_data=$myrow[0];
                            }

                            if($dup_data >0){
                                $sqlstr3="update store_data_sales set salesMoney='$salesMoney',salesNum='$salesNum',date='$date',corp='$username' where storeID='$storeID' and date='$date'";
                            }else{
                                $sqlstr3="insert into store_data_sales values('$maxID','$storeID','$salesMoney','$salesNum','$date','$staff','$username')";
                            }

                            $result=mysqli_query($conn,$sqlstr3);

                        }

                        //删除文件
                        unlink($path);

                        return $this->success('数据提交成功！','/index.php/Index/store/store_data1.html','','1');
                    }
                }else{
                    return $this->error('文件过大！','/index.php/Index/store/store_data1.html','','1');
                }
            }

            //mysqli_free_result($result);
            mysqli_close($conn);
        }else{
            return $this->error('文件上传失败！','/index.php/Index/store/store_data1.html','','1');
        }
    }

    public function download_sx_data(){
        error_reporting(E_ALL || ~E_NOTICE);
        session_start();

        $conn=mysqli_connect("localhost","root","root","yzl_database") or die("连接数据库服务器失败！".mysqli_error());
        mysqli_query($conn,"set names utf8");
    
        date_default_timezone_set("Asia/Shanghai");
        $date=date('Y-m-d', time());  //签署日期
    
        $option=$_GET["option"];
    
        $username=$_SESSION['username'];
    
        $sqlstr1="select department,newLevel from user_form where username='$username'";
    
        $result=mysqli_query($conn,$sqlstr1);
    
        while($myrow=mysqli_fetch_row($result)){
            $department=$myrow[0];
            $newLevel=$myrow[1];
        }
    
    
        $sqlstr2="select * from sxhk_form where status='已审核' ";
        
    
        if($newLevel !="ADMIN" and $department != "商业运营部" and $department != "财务部"){
            if($newLevel == "KA"){
                $sqlstr2=$sqlstr2." and ywy='$username'"; 
            }else{
                $sqlstr2=$sqlstr2." and '$department' like concat('%',a.department,'%') ";
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
    
            }
        }
    
        $header=array('授权编号','公司名称','事业部','负责人','授信回款金额','日期');
        
        
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
    
            $list2=range(1,6);
            createtable($data,'授信回款数据汇总',$header,$list2);
        
        mysqli_free_result($result);
        mysqli_close($conn);
    }

}