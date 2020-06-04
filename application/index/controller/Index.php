<?php
namespace app\index\controller;
use think\Controller;

class Index extends Controller
{

    public function index(){

        session_start();
        $username=$_SESSION["username"];

        $department=\think\Db::query("select department from user_form where username='$username'");
        $newLevel=\think\Db::query("select newLevel from user_form where username='$username'");

        $news_dt=\think\Db::name('news')->field('id,title,time')->limit(4)->where('newsType','公司动态')->select();
        $news_ggl=\think\Db::name('news')->field('id,title,time')->limit(10)->where('newsType','公司公告')->select();
        $files=\think\Db::name('files')->field('id,title,fileName,createUser,time')->limit(7)->select();

        $this->assign('username',$username);
        $this->assign('news_dt',$news_dt);
        $this->assign('files',$files);
        $this->assign('news_ggl',$news_ggl);

        return $this->fetch();
    }

    public function myWork(){

        session_start();
        $username=$_SESSION["username"];
        $fileName="";

        $this->assign('username',$username);
        $this->assign('fileName',$fileName);

        return $this->fetch();
    }

    public function myWorkController1(){
        session_start();
        $username=$_SESSION["username"];

        $user=\think\Db::name('user_form')->where('username',$username)->select();

        $department=$user[0]["department"];
        $newLevel=$user[0]["newLevel"];

        $this->assign('department',$department);
        $this->assign('newLevel',$newLevel);
       
        //计算待审核辅料
        $count_fl=0;
        $link_fl="../../home/fl/flList.php";

        $sqlstr=\think\Db::name('flsqd')->where('shr','like','%'.$username.'%')->where('status','notlike','%已归档%')->select();

        for($i=0;$i<sizeof($sqlstr);$i++){
            
            $shr=$sqlstr[$i]["shr"];
            $shr_arr=explode(",",$shr);
            $my_shr=array_pop($shr_arr);

            if($username == $my_shr and $newLevel == "KA"){
                $count_fl=$count_fl+1;
                $link_fl="../../home/fl/saveFL.php";
            }elseif($username == $my_shr){
                $count_fl=$count_fl+1;
            }
        }

        //计算待审核授信
        $count_sx=0;
        $link_sx="../../home/sx/zhangmu.php";

        $sqlstr2=\think\Db::name('sx_form')->field(['ywy','status'])->select();

        for($i=0;$i<sizeof($sqlstr2);$i++){
            
            $ywy=$sqlstr2[$i]["ywy"];
            $status=$sqlstr2[$i]["status"];

            if($username == $ywy and $status == "待生效"){
                $count_sx=$count_sx+1;
                $link_sx='../../home/sx/djLoad.php';
            }elseif($username == $ywy and $status == "已拒绝"){
                $count_sx=$count_sx+1;
                $link_sx='../../home/sx/zhangmu.php';
            }elseif($department == "商业运营部" and  $status == "待归档"){
                $count_sx=$count_sx+1;
            }
        }

        //计算待审核合同
        $count_contract=0;
        $link_contract="../../home/contract/w_contract.php";

        $sqlstr3=\think\Db::name('contract')->field(['shr','status'])->select();

        for($i=0;$i<sizeof($sqlstr3);$i++){
            
            $shr=$sqlstr3[$i]["shr"];
            $status=$sqlstr3[$i]["status"];

            if($shr == $ywy and $status == "审核拒绝" and $newLevel == "KA"){
                $count_contract=$count_contract+1;
            }elseif($department == "商业运营部" and  $status == "待归档"){
                $count_contract=$count_contract+1;
            }
        }

        //计算待审核授权
        $count_sq=0;
        $link_sq="../../home/contract/w_sq.php";

        $sqlstr4=\think\Db::name('sq')->field(['shr','status'])->select();

        for($i=0;$i<sizeof($sqlstr4);$i++){
            
            $shr=$sqlstr4[$i]["shr"];
            $status=$sqlstr4[$i]["status"];

            if($shr == $ywy and $status == "审核拒绝" and $newLevel == "KA"){
                $count_sq=$count_sq+1;
            }elseif($department == "商业运营部" and  $status == "待归档"){
                $count_sq=$count_sq+1;

            }
        }

        //计算待审核回款
        $count_hk=0;
        $link_hk="../../home/sx/sx_cw.php";

        $sqlstr5=\think\Db::name('hk_form2')->field('status')->where('status','待财务审批')->select();

        for($i=0;$i<sizeof($sqlstr5);$i++){
            
            $shr=$myrow[0];
            $status=$myrow[1];

            if($department == "财务部" ){
                $count_hk=$count_hk+1;
            }
        }

        //计算待处理问题
        $count_qs=0;
        $link_qs="../../home/store/storeQS.php";

        $sqlstr6=\think\Db::query("select username from user_form where department  like concat('%', (select department from store where storeID = (select storeID from store_qs where status='待处理')),'%') and newLevel='M'");


        for($i=0;$i<sizeof($sqlstr6);$i++){

            $shr2=$sqlstr6[$i]["username"];

            if($username == $shr2 ){

                $count_qs=$count_qs+1;
            }
        }

        $data='[
            {"name_dbsx":"待审辅料","number_dbsx":"'.$count_fl.'","link_dbsx":"'.$link_fl.'"},
            {"name_dbsx":"待审授信","number_dbsx":"'.$count_sx.'","link_dbsx":"'.$link_sx.'"},
            {"name_dbsx":"待审合同","number_dbsx":"'.$count_contract.'","link_dbsx":"'.$link_contract.'"},
            {"name_dbsx":"待审授权","number_dbsx":"'.$count_sq.'","link_dbsx":"'.$link_sq.'"},
            {"name_dbsx":"待审回款","number_dbsx":"'.$count_hk.'","link_dbsx":"'.$link_hk.'"},
            {"name_dbsx":"店铺问题","number_dbsx":"'.$count_qs.'","link_dbsx":"'.$link_qs.'"}
        ]';
    
        echo $data; 
    }

    public function myWorkController2(){
        
        session_start();
        $username=$_SESSION["username"];

        $user=\think\Db::name('user_form')->where('username',$username)->select();

        $department=$user[0]["department"];
        $newLevel=$user[0]["newLevel"];

        $data=[];

        if($newLevel == "KA"){
            $object=$username;

            $sqlstr1=\think\Db::name('store_data_sales')->field(['sum(salesMoney)','date'])->where('staff',$username)->limit(30)->group('date')->select();
    
            //$sqlstr1="select sum(salesMoney),date from store_data_sales  where staff='".$username."' group by date limit 0,30";
    
            for($i=0;$i<sizeof($sqlstr1);$i++){
                
                $str='{"dateTime_xssj":"'.$sqlstr1[$i]["date"].'","number_xssj":"'.$sqlstr1[$i]["sum(salesMoney)"].'","object_xssj":"'.$object.'"}';
                
                array_push($data,$str);
                
            }
    
        }elseif($newLevel == "M" and $username !="崔立德" and $username !="宋歌"){
            $object=$department;
            
            $sqlstr1=\think\Db::query("select sum(salesMoney),date from store_data_sales where staff=any(select staff from store where '$department' like concat('%',department,'%')) group by date limit 0,30");
    
            for($i=0;$i<sizeof($sqlstr1);$i++){
    
                $str='{"dateTime_xssj":"'.$sqlstr1[$i]["date"].'","number_xssj":"'.$sqlstr1[$i]["sum(salesMoney)"].'","object_xssj":"'.$object.'"}';
    
                array_push($data,$str);
                
            }
    
        }else{
            $object="全公司";
    
            $sqlstr1=\think\Db::name('store_data_sales')->field(['sum(salesMoney)','date'])->limit(30)->group('date')->select();

    
            for($i=0;$i<sizeof($sqlstr1);$i++){
    
                $str='{"dateTime_xssj":"'.$sqlstr1[$i]["date"].'","number_xssj":"'.$sqlstr1[$i]["sum(salesMoney)"].'","object_xssj":"'.$object.'"}';
    
                array_push($data,$str);
                
            }
        }
    
        echo "[".implode(",",$data)."]";

    }

    public function myWorkController3(){
        session_start();
        date_default_timezone_set("Asia/Shanghai");

        $username=$_SESSION["username"];

        $user=\think\Db::name('user_form')->where('username',$username)->select();

        $department=$user[0]["department"];
        $newLevel=$user[0]["newLevel"];

        $date=date('Y-m-d', time());
        $dateMonth=date('Y-m', time());

        $date_arr=explode("-",$date);

        $year=$date_arr[0];
        $month=$date_arr[1];
        $day=$date_arr[2];

        if($month != 12){
            $dateStart=$year."-".$month."-01";
            $dateEnd=$year."-".($month+1)."-01";
        }else{
            $dateStart=$year."-12-01";
            $dateEnd=($year+1)."-".$month."-01";
        }
    
        $sqlstr1=\think\Db::name('store_data_sales')->field("sum(salesMoney)")->where("staff",$username)->where("date",">=",$dateStart)->where("date","<",$dateEnd)->select();

        //$sqlstr1="select sum(salesMoney) from store_data_sales where staff='$username' and date >= '$dateStart' and date < '$dateEnd'";
    
        $num1=$sqlstr1[0]["sum(salesMoney)"];
    
        $sqlstr2=\think\Db::query("select sum(storeTarget)  from store_target where storeID =any (select storeID from store where staff='$username') and dateMonth='$dateMonth'");
    
        $num2=$sqlstr2[0]["sum(storeTarget)"];
    
        $sqlstr3=\think\Db::query("select sum(a.salesMoney) from store_data_sales a,store b where '$department' like concat('%',b.department,'%') and a.date >= '$dateStart' and a.date < '$dateEnd' and  a.storeID=b.storeID");
    
        $num3=$sqlstr3[0]["sum(a.salesMoney)"];

        $sqlstr4=\think\Db::query("select sum(storeTarget)  from store_target where storeID =any (select storeID from store where '$department' like concat('%',department,'%')) and dateMonth='$dateMonth'");
    
        $num4=$sqlstr4[0]["sum(storeTarget)"];
    
        $sqlstr5=\think\Db::query("select sum(backMoney) from store_data_hk where staff='$username' and date >= '$dateStart' and date < '$dateEnd'");
    
        $num5=$sqlstr5[0]["sum(backMoney)"];
    
        $sqlstr6=\think\Db::query("select sum(hkTarget)  from store_target where storeID =any (select storeID from store where staff='$username') and dateMonth='$dateMonth'");
    
        $num6=$sqlstr6[0]["sum(hkTarget)"];
    
        $sqlstr7=\think\Db::query("select sum(a.backMoney) from store_data_hk a,store b where '$department' like concat('%',b.department,'%') and a.date >= '$dateStart' and a.date < '$dateEnd' and  a.storeID=b.storeID");
    
        $num7=$sqlstr7[0]["sum(a.backMoney)"];
    
        $sqlstr8=\think\Db::query("select sum(hkTarget)  from store_target where storeID =any (select storeID from store where '$department' like concat('%',department,'%')) and dateMonth='$dateMonth'");
    
        $num8=$sqlstr8[0]["sum(hkTarget)"];
    
        $num1=($num1=="")?0:$num1;
        $num2=($num2=="")?0:$num2;
        $num3=($num3=="")?0:$num3;
        $num4=($num4=="")?0:$num4;
        $num5=($num5=="")?0:$num5;
        $num6=($num6=="")?0:$num6;
        $num7=($num7=="")?0:$num7;
        $num8=($num8=="")?0:$num8;
    
        $data='[
                    {"name":"个人销售数据","number":"'.$num1.'"},
                    {"name":"个人销售数据目标","number":"'.$num2.'"},
                    {"name":"部门销售数据","number":"'.$num3.'"},
                    {"name":"部门销售数据目标","number":"'.$num4.'"},
                    {"name":"个人回款数据","number":"'.$num5.'"},
                    {"name":"个人回款数据目标","number":"'.$num6.'"},
                    {"name":"部门回款数据","number":"'.$num7.'"},
                    {"name":"部门回款数据目标","number":"'.$num8.'"}
                ]';
    
        echo $data;

    }
}
