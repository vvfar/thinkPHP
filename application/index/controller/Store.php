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


            $this->assign('title','店铺管理');
            $this->assign('username',$username);
            $this->assign("newLevel",$newLevel);
            $this->assign("total",$total);
            $this->assign('pagecount',$pagecount);
            $this->assign('page',$page);
            $this->assign('pagesize',15);
            $this->assign('stores',$stores);
            $this->assign('status',$status);

            return $this->fetch();

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

            
            $this->assign('title','店铺销售数据');
            $this->assign('username',$username);
            $this->assign("newLevel",$newLevel);
            $this->assign("total",$total);
            $this->assign('pagecount',$pagecount);
            $this->assign('page',$page);
            $this->assign('pagesize',15);
            $this->assign('store_datas',$store_datas);
            $this->assign('department',$department);
            $this->assign('date',$date);

            return $this->fetch();
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

            $this->assign('title','店铺回款数据');
            $this->assign('username',$username);
            $this->assign("newLevel",$newLevel);
            $this->assign("total",$total);
            $this->assign('pagecount',$pagecount);
            $this->assign('page',$page);
            $this->assign('pagesize',15);
            $this->assign('store_datas',$store_datas);
            $this->assign('department',$department);
            $this->assign('date',$date);

            return $this->fetch();
        
        }else{
            return $this->redirect('/index.php/Index/Login/login');
        }
    }

    public function store_data_details1(){
        session_start();

        if(isset($_SESSION["username"])){
            
            


            return $this->fetch();
        }else{
            return $this->redirect('/index.php/Index/Login/login');
        }
    }

    public function store_data_details2(){
        session_start();

        if(isset($_SESSION["username"])){

            $username=$_SESSION["username"];
        
            return $this->fetch();

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

            $this->assign('title','店铺问题');
            $this->assign('username',$username);
            $this->assign("newLevel",$newLevel);
            $this->assign("total",$total);
            $this->assign('pagecount',$pagecount);
            $this->assign('page',$page);
            $this->assign('pagesize',15);
            $this->assign('store_qss',$store_qss);
            $this->assign('department',$department);
            $this->assign('date',$date);
            $this->assign('status',$status);
        
            return $this->fetch();
        
        }else{
            return $this->redirect('/index.php/Index/Login/login');
        }

    }


}