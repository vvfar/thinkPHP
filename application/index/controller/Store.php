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

            return $this->fetch();

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
}