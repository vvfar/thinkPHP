<?php
namespace app\index\controller;
use think\Controller;

class Index extends Controller
{

    public function index(){

        session_start();

        if(isset($_SESSION["username"])){
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
        }else{
            return $this->redirect('/index.php/Index/Login/login');
        }
    }

    public function myWork(){

        session_start();

        if(isset($_SESSION["username"])){
            $username=$_SESSION["username"];
            $fileName="";

            $this->assign('username',$username);
            $this->assign('fileName',$fileName);

            return $this->fetch();
        }else{
            return $this->redirect('/index.php/Index/Login/login');
        }
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

        $ywy="";

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

        date_default_timezone_set("Asia/Shanghai");

        $date=date('Y-m-d', time());
        $dateMonth=date('Y-m', time());

        
        $object="全公司";

        $sqlstr1=\think\Db::name('store_data_sales')->field(['sum(salesMoney)','date'])->limit(30)->group('date')->order('date desc')->select();


        for($i=sizeof($sqlstr1)-1;$i>=0;$i--){

            $str='{"dateTime_xssj":"'.$sqlstr1[$i]["date"].'","number_xssj":"'.($sqlstr1[$i]["sum(salesMoney)"]/10000).'","object_xssj":"'.$object.'"}';

            array_push($data,$str);
            
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

    public function dataQuery(){

        session_start();

        if(isset($_SESSION["username"])){
            $username=$_SESSION["username"];

            $this->assign('username',$username);

            return $this->fetch();
        }else{
            return $this->redirect('/index.php/Index/Login/login');
        }
    }

    public function dataQueryControllerBar(){

        session_start();
        $username=$_SESSION["username"];

        $user=\think\Db::name('user_form')->where('username',$username)->select();

        $my_department=$user[0]["department"];
        $newLevel=$user[0]["newLevel"];

        $chooseOne=$this->request->post('chooseOne');
        $chooseTwo=$this->request->post('chooseTwo');
        $chooseThree=$this->request->post('chooseThree');
        $chooseFour=$this->request->post('chooseFour');
        $chooseFive=$this->request->post('chooseFive');
        $chooseSix=$this->request->post('chooseSix');
        $chooseSeven=$this->request->post('chooseSeven');
        $username=$this->request->post('username');

        //事业部控件
        $department_list="[";

        $sqlstr1="select distinct department from store where 1=1 ";

        if($my_department !="商业运营部" and ($newLevel !="" and $newLevel !="ADMIN") and strpos($my_department,'/') != true){
            $sqlstr1=$sqlstr1."and department='$my_department'";
        }

        $sqlstr1=\think\Db::query($sqlstr1);

        for($i=0;$i<sizeof($sqlstr1);$i++){
            if(strpos($sqlstr1[$i]["department"],'/') != true and strpos($sqlstr1[$i]["department"],'事业管理部') == true){
                $department_list=$department_list.'"'.$sqlstr1[$i]["department"].'",';  
            }
        }

        $department_list = substr($department_list,0,strlen($department_list)-1);

        $department_list=$department_list."]";

        //平台控件
        $pingtai_list="[";

        $sqlstr2="select distinct pingtai from store where 1=1 ";

        if($chooseTwo !="全部"){
            $sqlstr2=$sqlstr2."and department='$chooseTwo' ";
        }

        if($chooseTwo !="全部" and $my_department !="商业运营部" and ($newLevel !="" and $newLevel !="ADMIN") and strpos($my_department,'/') != true){
            if($newLevel =="M"){
                $sqlstr2=$sqlstr2."and department='$my_department' ";
            }else{
                $sqlstr2=$sqlstr2."and staff='$username' ";
            }
        }
        
        $sqlstr2=\think\Db::query($sqlstr2);

        for($i=0;$i<sizeof($sqlstr2);$i++){
            $pingtai_list=$pingtai_list.'"'.$sqlstr2[$i]["pingtai"].'",';  
        }

        $pingtai_list = substr($pingtai_list,0,strlen($pingtai_list)-1);

        $pingtai_list=$pingtai_list."]";


        //类目控件
        $category_list="[";

        $sqlstr3="select distinct category from store where 1=1 ";

        if($chooseTwo !="全部"){
            $sqlstr3=$sqlstr3."and department='$chooseTwo'";
        }

        if($my_department !="商业运营部" and ($newLevel !="" and $newLevel !="ADMIN") and strpos($my_department,'/') != true){
            if($newLevel =="M"){
                $sqlstr3=$sqlstr3."and department='$my_department' ";
            }else{
                $sqlstr3=$sqlstr3."and staff='$username' ";
            }
            
        }

        $sqlstr3=\think\Db::query($sqlstr3);

        for($i=0;$i<sizeof($sqlstr3);$i++){
            $category_list=$category_list.'"'.$sqlstr3[$i]["category"].'",';  
        }

        $category_list = substr($category_list,0,strlen($category_list)-1);

        $category_list=$category_list."]";

        //店铺控件
        $store_list="[";

        $sqlstr4="select distinct storeName from store where 1=1 ";

        if($chooseTwo !="全部"){
            $sqlstr4=$sqlstr4." and department='$chooseTwo' ";
        }

        if($my_department !="商业运营部" and ($newLevel !="" and $newLevel !="ADMIN") and strpos($my_department,'/') != true){
            if($newLevel =="M"){
                $sqlstr4=$sqlstr4."and department='$my_department' ";
            }else{
                $sqlstr4=$sqlstr4."and staff='$username' ";
            }
            
        }
        
        $sqlstr4=\think\Db::query($sqlstr4);

        for($i=0;$i<sizeof($sqlstr4);$i++){
            $store_list=$store_list.'"'.$sqlstr4[$i]["storeName"].'",';  
        }

        $store_list = substr($store_list,0,strlen($store_list)-1);

        $store_list=$store_list."]";
        
        //业务员控件
        $ywy_list="[";

        $sqlstr5="select distinct username from user_form where (newLevel = 'KA' or newLevel = 'M') ";

        if($chooseTwo !="全部"){
            $sqlstr5=$sqlstr5." and department='$chooseTwo' ";
        }

        if($chooseTwo !="全部" and $my_department !="商业运营部" and ($newLevel !="" and $newLevel !="ADMIN") and strpos($my_department,'/') != true){
            if($newLevel =="M"){
                $sqlstr5=$sqlstr5."and department='$my_department' ";
            }else{
                $sqlstr5=$sqlstr5."and username='$username' ";
            }
            
        }

        $sqlstr5_a=\think\Db::query($sqlstr5);

        for($i=0;$i<sizeof($sqlstr5_a);$i++){
            $ywy_list=$ywy_list.'"'.$sqlstr5_a[$i]["username"].'",';  
        }

        $ywy_list = substr($ywy_list,0,strlen($ywy_list)-1);

        $ywy_list=$ywy_list."]";

        //日期控件
        $date_list_1="[";

        if($chooseOne == "销售额"){
            if($chooseSeven == "日" or $chooseSeven == "全部"){
                $sqlstr6="select distinct date as dateTime from store_data_sales ";
            }elseif($chooseSeven == "月"){
                $sqlstr6="select distinct left(date,7) as dateTime from store_data_sales ";
            }elseif($chooseSeven == "年"){
                $sqlstr6="select distinct left(date,4) as dateTime from store_data_sales ";
            }
            
        }else{
            if($chooseSeven == "日" or $chooseSeven == "全部"){
                $sqlstr6="select distinct date as dateTime from store_data_hk ";
            }elseif($chooseSeven == "月"){
                $sqlstr6="select distinct left(date,7) as dateTime from store_data_hk ";
            }elseif($chooseSeven == "年"){
                $sqlstr6="select distinct left(date,4) as dateTime from store_data_sales ";
            }else{
                $sqlstr6="select distinct date as dateTime from store_data_hk ";
            }
        }


        if($chooseTwo !="全部"){
            $sqlstr6=$sqlstr6." where staff= any( select staff from store where department='$chooseTwo')";
        }

        $sqlstr6=$sqlstr6." order by dateTime desc";

        $sqlstr6=\think\Db::query($sqlstr6);

        $date_list="";
        
        for($i=0;$i<sizeof($sqlstr6);$i++){
            $date_list=$date_list.'"'.$sqlstr6[$i]["dateTime"].'",';  
        }

        $date_list = substr($date_list,0,strlen($date_list)-1);

        $date_list=$date_list_1.$date_list."]";



        $data='[
            {"name":"department","value":'.$department_list.'},
            {"name":"pingtai","value":'.$pingtai_list.'},
            {"name":"category","value":'.$category_list.'},
            {"name":"storeName","value":'.$store_list.'},
            {"name":"ywy","value":'.$ywy_list.'},
            {"name":"time","value":["日","月","年"]},
            {"name":"date","value":'.$date_list.'}
        ]';

        echo $data;
    }

    public function dataQueryController1(){
        session_start();
        $username=$_SESSION["username"];

        $user=\think\Db::name('user_form')->where('username',$username)->select();

        $department=$user[0]["department"];
        $newLevel=$user[0]["newLevel"];

        $chooseOne=$this->request->post('chooseOne');
        $chooseTwo=$this->request->post('chooseTwo');
        $chooseThree=$this->request->post('chooseThree');
        $chooseFour=$this->request->post('chooseFour');
        $chooseFive=$this->request->post('chooseFive');
        $chooseSix=$this->request->post('chooseSix');
        $chooseSeven=$this->request->post('chooseSeven');
        $chooseEight=$this->request->post('chooseEight');

        if($chooseEight=="默认"){
            date_default_timezone_set("Asia/Shanghai");
            $date=time();
        }else{
            $date=$chooseEight;
        }
    
        if($chooseEight=="默认"){
            $date1=date('Y-m-d',strtotime("-1 day"));
            $date2=date('Y-m-d', strtotime("-1 month"));
            $date3=date('Y-m-d', strtotime("-1 year"));
    
            $dateMonth=date('Y-m');
            $dateMonth2=date('Y-m', strtotime("-1 month"));
            $dateMonth3=date('Y-m', strtotime("-1 year"));
    
            $dateYear=date('Y');
            $dateYear2=date('Y', strtotime("-1 year"));
            $dateYear3=date('Y', strtotime("-1 year"));
        }else{
            $date1=date('Y-m-d', strtotime("$date"));
            $date2=date('Y-m-d', strtotime("$date -1 month"));
            $date3=date('Y-m-d', strtotime("$date -1 year"));
    
            $dateMonth=$date;
            $dateMonth2=date('Y-m', strtotime("$date -1 month"));
            $dateMonth3=date('Y-m', strtotime("$date -1 year"));
    
            $dateYear=date('Y', strtotime("$date"));
            $dateYear2=date('Y', strtotime("$date -1 year"));
            $dateYear3=date('Y', strtotime("$date -1 year"));
        }
    
    
        //销售额&回款
        if($chooseOne == "销售额"){
            $sqlstr1="select sum(salesMoney) as num from store_data_sales where storeID= any(select storeID from store where 1=1 ";
        }else if($chooseOne == "回款"){
            $sqlstr1="select sum(backMoney) as num from store_data_hk where  storeID= any(select storeID from store where 1=1 ";
        }
    
        //事业部
        if($chooseTwo != "全部"){
            $sqlstr1=$sqlstr1."and department='$chooseTwo' ";
        }
    
        //平台
        if($chooseThree != "全部"){
            $sqlstr1=$sqlstr1."and pingtai='$chooseThree' ";
        }
    
        //类目
        if($chooseFour != "全部"){
            $sqlstr1=$sqlstr1."and category='$chooseFour' ";
        }
    
        //店铺
        if($chooseFive != "全部"){
            $sqlstr1=$sqlstr1."and storeName='$chooseFive' ";
        }
    
        //业务员
        if($chooseSix != "全部"){
            $sqlstr1=$sqlstr1."and staff='$chooseSix' ";
        }
    
        if($chooseSeven != "月" and $chooseSeven != "年"){
            $chooseSeven = "日";
        }
        
        $sqlstr1=$sqlstr1.") ";
    
        //时间段
    
        if($chooseSeven == "日"){
            $sqlstr=$sqlstr1."and date='$date1' ";  //当期
            $sqlstr2=$sqlstr1."and date='$date2' ";  //环比
            $sqlstr3=$sqlstr1."and date='$date3' ";   //同比
        }elseif($chooseSeven == "月"){
            $sqlstr=$sqlstr1."and date like '%$dateMonth%' "; //当期
            $sqlstr2=$sqlstr1."and date like '%$dateMonth2%' ";  //环比
            $sqlstr3=$sqlstr1."and date like '%$dateMonth3%' ";   //同比
        }elseif($chooseSeven == "年"){
            $sqlstr=$sqlstr1."and date like '%$dateYear%' ";  //当期
            $sqlstr2=$sqlstr1."and date like '%$dateYear2%' ";  //环比
            $sqlstr3=$sqlstr1."and date like '%$dateYear3%' ";  //同比
        }
    
        
    
        $sqlstr=\think\Db::query($sqlstr);
        $num=$sqlstr[0]["num"];
    
        $sqlstr2=\think\Db::query($sqlstr2);
        $num2=$sqlstr2[0]["num"];
    
        $sqlstr3=\think\Db::query($sqlstr3);
        $num3=$sqlstr2[0]["num"];
        
        if($num3 !="" and $num !="" and $chooseSeven !="年"){
            $tb=($num-$num3)/$num3*100;
        }else{
            $tb=0;
        }
    
        if($num2 !="" and $num !=""){
            $hb=($num-$num2)/$num2*100;
        }else{
            $hb=0;
        }
    
        $data='[
            {"name":"title","value":"'.$chooseOne.'"},
            {"name":"time","value":"'.$chooseSeven.'"},
            {"name":"num","value":"'.number_format($num, 2).'"},
            {"name":"tb","value":"'.number_format($tb, 2).'"},
            {"name":"hb","value":"'.number_format($hb, 2).'"}
        ]';
    
        echo $data;
    
    }

    public function dataQueryController2(){
        session_start();
        $username=$_SESSION["username"];

        $user=\think\Db::name('user_form')->where('username',$username)->select();

        $department=$user[0]["department"];
        $newLevel=$user[0]["newLevel"];

        $chooseOne=$this->request->post('chooseOne');
        $chooseTwo=$this->request->post('chooseTwo');
        $chooseThree=$this->request->post('chooseThree');
        $chooseFour=$this->request->post('chooseFour');
        $chooseFive=$this->request->post('chooseFive');
        $chooseSix=$this->request->post('chooseSix');
        $chooseSeven=$this->request->post('chooseSeven');
        $chooseEight=$this->request->post('chooseEight');

        if($chooseEight=="默认"){
            date_default_timezone_set("Asia/Shanghai");
            $date=time();
        }else{
            $date=$chooseEight;
        }
    
        if($chooseEight=="默认"){
            $date1=date('Y-m-d',strtotime("-1 day"));
            $date2=date('Y-m-d', strtotime("-1 month"));
            $date3=date('Y-m-d', strtotime("-1 year"));
    
            $dateMonth=date('Y-m');
            $dateMonth2=date('Y-m', strtotime("-1 month"));
            $dateMonth3=date('Y-m', strtotime("-1 year"));
    
            $dateYear=date('Y');
            $dateYear2=date('Y', strtotime("-1 year"));
            $dateYear3=date('Y', strtotime("-1 year"));
        }else{
            $date1=date('Y-m-d', strtotime("$date"));
            $date2=date('Y-m-d', strtotime("$date -1 month"));
            $date3=date('Y-m-d', strtotime("$date -1 year"));
    
            $dateMonth=date('Y-m', strtotime("$date"));
            $dateMonth2=date('Y-m', strtotime("$date -1 month"));
            $dateMonth3=date('Y-m', strtotime("$date -1 year"));
    
            $dateYear=date('Y', strtotime("$date -1 year"));
            $dateYear2=date('Y', strtotime("$date -1 year"));
            $dateYear3=date('Y', strtotime("$date -1 year"));
        }
    
        //销售额&回款
        if($chooseOne == "销售额"){
            $sqlstr1="select sum(salesMoney) as num from store_data_sales  where storeID= any(select storeID from store where 1=1 ";
        }else if($chooseOne == "回款"){
            $sqlstr1="select sum(backMoney) as num from store_data_hk where storeID= any(select storeID from store  where 1=1 ";
        }else{
            $sqlstr1="select sum(salesMoney) as num from store_data_sales  where storeID= any(select storeID from store where 1=1 ";     
        }
    
        //事业部
        if($chooseTwo != "全部"){
            $sqlstr1=$sqlstr1." and department ='$department' ";
        }
    
        //平台
        if($chooseThree != "全部"){
            $sqlstr1=$sqlstr1."and pingtai='$chooseThree' ";
        }
    
        //类目
        if($chooseFour != "全部"){
            $sqlstr1=$sqlstr1."and category='$chooseFour' ";
        }
    
        //店铺
        if($chooseFive != "全部"){
            $sqlstr1=$sqlstr1."and storeName='$chooseFive' ";
        }
    
        //业务员
        if($chooseSix != "全部"){
            $sqlstr1=$sqlstr1."and staff='$chooseSix' ";
        }
    
        $sqlstr1=$sqlstr1.") ";
    
        if($chooseSeven != "月" and $chooseSeven != "年"){
            $chooseSeven = "日";
        }
        
        //时间段
        if($chooseSeven == "日"){
            $sqlstr_sj=$sqlstr1."and date='$date1' ";  //当期
            $sqlstr_sjhb=$sqlstr1."and date='$date2' ";  //环比
            $sqlstr__sjtb=$sqlstr1."and date='$date3' ";   //同比
        }elseif($chooseSeven == "月"){
            $sqlstr_sj=$sqlstr1."and date like '%$dateMonth%' "; //当期
            $sqlstr_sjhb=$sqlstr1."and date like '%$dateMonth2%' ";  //环比
            $sqlstr__sjtb=$sqlstr1."and date like '%$dateMonth3%' ";   //同比
        }elseif($chooseSeven == "年"){
            $sqlstr_sj=$sqlstr1."and date like '%$dateYear%' ";  //当期
            $sqlstr_sjhb=$sqlstr1."and date like '%$dateYear2%' ";  //环比
            $sqlstr__sjtb=$sqlstr1."and date like '%$dateYear3%' ";  //同比
        }
    
        $sqlstr_sj=\think\Db::query($sqlstr_sj);

        $num=$sqlstr_sj[0]["num"];
    
        //销售额目标&回款目标
        if($chooseOne == "销售额"){
            $sqlstr2="select sum(storeTarget) as num_t from store_target where storeID= any(select storeID from store where 1=1 ";
        }else if($chooseOne == "回款"){
            $sqlstr2="select sum(hkTarget) as num_t from store_target where storeID= any(select storeID from store where 1=1 ";
        }else{
            $sqlstr2="select sum(storeTarget) as num_t from store_target where storeID= any(select storeID from store where 1=1 ";
        }
    
        //事业部
        if($chooseTwo != "全部"){
            $sqlstr2=$sqlstr2."and department='$chooseTwo' ";
        }
    
        //平台
        if($chooseThree != "全部"){
            $sqlstr2=$sqlstr2."and pingtai='$chooseThree' ";
        }
    
        //类目
        if($chooseFour != "全部"){
            $sqlstr2=$sqlstr2."and category='$chooseThree' ";
        }
    
        //店铺
        if($chooseFive != "全部"){
            $sqlstr2=$sqlstr2."and storeName='$chooseFive' ";
        }
    
        //业务员
        if($chooseSix != "全部"){
            $sqlstr2=$sqlstr2."and staff='$chooseFour' ";
        }
    
        if($chooseSeven != "月" and $chooseSeven != "年"){
            $chooseSeven = "日";
        }
        
        $sqlstr2=$sqlstr2.") ";
    
       //时间段
        if($chooseSeven == "日"){
            $sqlstr=$sqlstr2."and dateMonth like '%$dateMonth%' ";  //当期
            $sqlstr2_=$sqlstr2."and dateMonth like '%$dateMonth2%' ";  //环比
            $sqlstr3_=$sqlstr2."and dateMonth like '%$dateMonth3%' ";   //同比
        }elseif($chooseSeven == "月"){
            $sqlstr=$sqlstr2."and dateMonth like '%$dateMonth%' "; //当期
            $sqlstr2_=$sqlstr2."and dateMonth like '%$dateMonth2%' ";  //环比
            $sqlstr3_=$sqlstr2."and dateMonth like '%$dateMonth3%' ";   //同比
        }elseif($chooseSeven == "年"){
            $sqlstr=$sqlstr2."and dateMonth like '%$dateYear%' ";  //当期
            $sqlstr2_=$sqlstr2."and dateMonth like '%$dateYear2%' ";  //环比
            $sqlstr3_=$sqlstr2."and dateMonth like '%$dateYear3%' ";  //同比
        }
       
    
        //当期
        $sqlstr=\think\Db::query($sqlstr);
    
        $num_t=0;
    
        if($chooseSeven == "日"){
            $num_t=$sqlstr[0]["num_t"]/30;
        }elseif($chooseSeven == "月"){
            $num_t=$sqlstr[0]["num_t"];
        }elseif($chooseSeven == "年"){
            $num_t=$sqlstr[0]["num_t"];
        }
    
        if($num_t !=0){
            $percent=number_format($num/$num_t, 2);
        }else{
            $percent="100";
        }

       
        //环比
        $sqlstr_sjhb=\think\Db::query($sqlstr_sjhb);
        
        $num=0;
    
        $num=$sqlstr_sjhb[0]["num"];
    
        $sqlstr2_1=\think\Db::query($sqlstr2_);
        
        $num_t2=0;
    
        $num_t2=$sqlstr2_1[0]["num_t"];
    
        if($num_t2 !=0){
            $percent2=number_format($num/$num_t2, 2);
        }else{
            $percent2="100";
        }
    
        //同比
        $sqlstr_sjtb=\think\Db::query($sqlstr__sjtb);
        
        $num=0;
    
        $num=$sqlstr_sjtb[0]["num"];

        $sqlstr3_=\think\Db::query($sqlstr3_);
    
    
        $num_t3=0;
    
        $sqlstr2_=\think\Db::query($sqlstr2_);
        
        
        $num_t3=$sqlstr2_[0]["num_t"];
        
        if($num_t3 !=0){
            $percent3=number_format($num/$num_t3, 2);
        }else{
            $percent3="100";
        }
        
        if($percent2!=0){
            $tb=number_format(($percent-$percent3)/$percent3*100,2);
        }else{
            $tb="0.00";
        }
        
        if($percent2!=0){
            $hb=number_format(($percent-$percent2)/$percent2*100,2);
        }else{
            $hb="0.00";
        }
        
    
        $data='[
            {"name":"title","value":"完成比"},
            {"name":"time","value":"'.$chooseSeven.'"},
            {"name":"num","value":"'.$percent.'%"},
            {"name":"tb","value":"'.$tb.'"},
            {"name":"hb","value":"'.$hb.'"}
        ]';
    
        echo $data;
    }

    public function dataQueryController3(){
        session_start();
        $username=$_SESSION["username"];

        date_default_timezone_set("Asia/Shanghai");
        $date1=date('Y-m-d', time());
        $dateMonth=date('Y-m', time());
        $dateYear=date('Y', time());

        $user=\think\Db::name('user_form')->where('username',$username)->select();

        $department=$user[0]["department"];
        $newLevel=$user[0]["newLevel"];

        $chooseOne=$this->request->post('chooseOne');
        $chooseTwo=$this->request->post('chooseTwo');
        $chooseThree=$this->request->post('chooseThree');
        $chooseFour=$this->request->post('chooseFour');
        $chooseFive=$this->request->post('chooseFive');
        $chooseSix=$this->request->post('chooseSix');
        $chooseSeven=$this->request->post('chooseSeven');
        $chooseEight=$this->request->post('chooseEight');

        //销售额&回款
        if($chooseOne == "销售额"){
            $sqlstr1="select sum(b.salesMoney) as num,a.staff from store a,store_data_sales b where a.storeID=b.storeID ";
        }else if($chooseOne == "回款"){
            $sqlstr1="select sum(b.backMoney) as num,a.staff from store a,store_data_hk b where a.storeID=b.storeID ";
        }

        //事业部
        if($chooseTwo != "全部"){
            $sqlstr1=$sqlstr1." and a.department='$chooseTwo' ";
        }

        //平台
        if($chooseThree != "全部"){
            $sqlstr1=$sqlstr1."and a.pingtai='$chooseThree' ";
        }

        //类目
        if($chooseFour != "全部"){
            $sqlstr1=$sqlstr1."and a.category='$chooseFour' ";
        }

        //店铺
        if($chooseFive != "全部"){
            $sqlstr1=$sqlstr1."and a.storeName='$chooseFive' ";
        }

        //业务员
        if($chooseSix != "全部"){
            $sqlstr1=$sqlstr1."and a.staff='$chooseSix' ";
        }

        if($chooseSeven != "月" and $chooseSeven != "年"){
            $chooseSeven = "日";
        }

        //时间段
        if($chooseEight=="默认"){
            if($chooseSeven == "日"){

                $date1=date('Y-m-d',strtotime("-1 day"));

                $sqlstr1=$sqlstr1." and b.date='$date1' ";
            }elseif($chooseSeven == "月"){
                $sqlstr1=$sqlstr1." and b.date like '%$dateMonth%' ";
            }elseif($chooseSeven == "年"){
                $sqlstr1=$sqlstr1." and b.date like '%$dateYear%' ";
            }
        }else{
            $sqlstr1=$sqlstr1." and b.date like '%$chooseEight%' "; //当期
        }

        $sqlstr1=$sqlstr1." group by a.staff order by ";
        
        if($chooseOne == "销售额"){
            $sqlstr1=$sqlstr1." sum(b.salesMoney) ";
        }else if($chooseOne == "回款"){
            $sqlstr1=$sqlstr1." sum(b.backMoney) ";
        }
        
        $sqlstr1=$sqlstr1." desc limit 0,5";

        $sqlstr1=\think\Db::query($sqlstr1);
        
        $staff_list1='[';
        $staff_list_str="";

        $number_list1='[';
        $number_list_str="";

        for($i=0;$i<sizeof($sqlstr1);$i++){
            $number_list_str=$number_list_str.'"'.$chooseOne.'：￥'.number_format($sqlstr1[$i]["num"],2).'",';
            $staff_list_str=$staff_list_str.'"'.$sqlstr1[$i]["staff"].'",';
        }

        $staff_list_str = substr($staff_list_str,0,strlen($staff_list_str)-1);
        $number_list_str = substr($number_list_str,0,strlen($number_list_str)-1);

        $staff_list=$staff_list1.$staff_list_str.']';
        $number_list=$number_list1.$number_list_str.']';

        $tb=0;
        $hb=0;

        $data='[
            {"name":"title","value":"业绩排名"},
            {"name":"time","value":"'.$chooseSeven.'"},
            {"name":"rank","value":'.$staff_list.'},
            {"name":"number","value":'.$number_list.'}
        ]';

        echo $data;
    
    }

    
    public function dataQueryController4(){
        session_start();
        $username=$_SESSION["username"];

        date_default_timezone_set("Asia/Shanghai");

        $date1=date('Y-m-d', time());
        $dateMonth=date('Y-m', time());
        $dateYear=date('Y', time());


        $user=\think\Db::name('user_form')->where('username',$username)->select();

        $department=$user[0]["department"];
        $newLevel=$user[0]["newLevel"];

        $chooseOne=$this->request->post('chooseOne');
        $chooseTwo=$this->request->post('chooseTwo');
        $chooseThree=$this->request->post('chooseThree');
        $chooseFour=$this->request->post('chooseFour');
        $chooseFive=$this->request->post('chooseFive');
        $chooseSix=$this->request->post('chooseSix');
        $chooseSeven=$this->request->post('chooseSeven');
        $chooseEight=$this->request->post('chooseEight');
    
        $data=[];

        if($chooseEight=="默认"){
            if($chooseSeven=="日"){
                $chooseEight=$date1;
            }elseif($chooseSeven=="月"){
                $chooseEight=$dateMonth;
            }else{
                $chooseEight=$dateYear;
            }
        }
    
        $object=$chooseOne;
    
        if($chooseEight=="默认"){
            if($chooseSeven=="日" or $chooseSeven=="全部"){
                if($chooseOne=="销售额"){
                    $sqlstr1="select sum(salesMoney) as num,date from store_data_sales where  storeID= any(select storeID from store where 1=1 ";
                }else if($chooseOne == "回款"){
                    $sqlstr1="select sum(backMoney) as num,date from store_data_hk where storeID= any(select storeID from store where 1=1  ";
                }
        
            }elseif($chooseSeven=="月"){
                if($chooseOne=="销售额"){
                    $sqlstr1="select sum(salesMoney) as num,left(date,7) as month from store_data_sales where storeID= any(select storeID from store where 1=1  ";
                }else if($chooseOne == "回款"){
                    $sqlstr1="select sum(backMoney) as num,left(date,7) as month from store_data_hk where storeID= any(select storeID from store where 1=1  ";
                }
            }elseif($chooseSeven=="年"){
                if($chooseOne=="销售额"){
                    $sqlstr1="select sum(salesMoney) as num,left(date,7) as month from store_data_sales where storeID= any(select storeID from store where 1=1  ";
                }else if($chooseOne == "回款"){
                    $sqlstr1="select sum(backMoney) as num,left(date,7) as month from store_data_hk where storeID= any(select storeID from store where 1=1  ";
                }
            }
        }else{
            if($chooseSeven=="日" or $chooseSeven=="月"){
                $sqlstr1="select sum(salesMoney) as num,date from store_data_sales where  storeID= any(select storeID from store where 1=1 ";
            }else{
                $sqlstr1="select sum(salesMoney) as num,left(date,7) as month from store_data_sales where storeID= any(select storeID from store where 1=1  ";
            }
        }
        
    
        //事业部
        if($chooseTwo != "全部"){
            $sqlstr1=$sqlstr1."and department='$chooseTwo' ";
        }
    
        //平台
        if($chooseThree != "全部"){
            $sqlstr1=$sqlstr1."and pingtai='$chooseThree' ";
        }
    
        //类目
        if($chooseFour != "全部"){
            $sqlstr1=$sqlstr1."and category='$chooseFour' ";
        }
    
        //店铺
        if($chooseFive != "全部"){
            $sqlstr1=$sqlstr1."and storeName='$chooseFive' ";
        }
    
        //业务员
        if($chooseSix != "全部"){
            $sqlstr1=$sqlstr1."and staff='$chooseSix' ";
        }
    
        $sqlstr1=$sqlstr1.") ";
    
    
        if($chooseSeven=="日" or $chooseSeven=="全部"){
            //if($chooseEight=="默认"){
            //    $chooseEight=date('Y-m-d',strtotime("-1 day"));
            //}

            $sqlstr1=$sqlstr1." and date_sub('".$chooseEight."', INTERVAL 30 DAY) < date  group by date limit 0,30";
        }elseif($chooseSeven=="月"){
            $sqlstr1=$sqlstr1." and date like '%".$chooseEight."%' group by date";
        }elseif($chooseSeven=="年"){
            $sqlstr1=$sqlstr1." and left(date,7) like '%".$chooseEight."%' group by left(date,7)";
        }
        
    
        $sqlstr1_a=\think\Db::query($sqlstr1);
    
        for($i=0;$i<sizeof($sqlstr1_a);$i++){
    
            $str='{
                    "line":"'.$chooseOne.'",
                    "dateTime_xssj":"'.$sqlstr1_a[$i]["date"].'",
                    "number_xssj":"'.round($sqlstr1_a[$i]["num"]/10000,2).'",
                    "object_xssj":"'.$object.'"
                }';
    
            array_push($data,$str);
            
        }
    
        
        if(sizeof($data)==0){
            $str='{
                    "line":"'.$chooseOne.'",
                    "dateTime_xssj":"暂无数据",
                    "number_xssj":"0",
                    "object_xssj":"'.$object.'"
                }';
    
            array_push($data,$str);
        }
    
    
        echo "[".implode(",",$data)."]";

    }

    public function dataQueryController5(){
        session_start();
        $username=$_SESSION["username"];

        $user=\think\Db::name('user_form')->where('username',$username)->select();

        $department=$user[0]["department"];
        $newLevel=$user[0]["newLevel"];

        $chooseOne=$this->request->post('chooseOne');
        $chooseTwo=$this->request->post('chooseTwo');
        $chooseThree=$this->request->post('chooseThree');
        $chooseFour=$this->request->post('chooseFour');
        $chooseFive=$this->request->post('chooseFive');
        $chooseSix=$this->request->post('chooseSix');
        $chooseSeven=$this->request->post('chooseSeven');
        $chooseEight=$this->request->post('chooseEight');

        $date=$chooseEight;
        
        if($chooseEight=="默认"){
            date_default_timezone_set("Asia/Shanghai");
            $date1=date('Y-m-d', strtotime("-1 day"));
            $date2=date("Y-m-d", strtotime("-1 month"));
            $date3=date("Y-m-d", strtotime("-1 year"));
    
            $dateMonth=date('Y-m', time());
            $dateMonth2=date('Y-m', strtotime("-1 month"));
            $dateMonth3=date('Y-m', strtotime("-1 year"));
    
            $dateYear=date('Y', time());
            $dateYear2=date('Y', strtotime("-1 year"));
            $dateYear3=date('Y', strtotime("-1 year"));
        }else{
            $date1=date('Y-m-d', strtotime("$date"));
            $date2=date('Y-m-d', strtotime("$date -1 month"));
            $date3=date('Y-m-d', strtotime("$date -1 year"));
    
            $dateMonth=$date;
            $dateMonth2=date('Y-m', strtotime("$date -1 month"));
            $dateMonth3=date('Y-m', strtotime("$date -1 year"));
    
            $dateYear=date('Y', strtotime("$date"));
            $dateYear2=date('Y', strtotime("$date -1 year"));
            $dateYear3=date('Y', strtotime("$date -1 year"));
            
        }
       

        $sqlstr1="select count(*) as count from store where status='正常' ";


        //事业部
        if($chooseTwo != "全部"){
            $sqlstr1=$sqlstr1."and department='$chooseTwo' ";
        }

        //平台
        if($chooseThree != "全部"){
            $sqlstr1=$sqlstr1."and pingtai='$chooseThree' ";
        }

        //类目
        if($chooseFour != "全部"){
            $sqlstr1=$sqlstr1."and category='$chooseFour' ";
        }

        //店铺
        if($chooseFive != "全部"){
            $sqlstr1=$sqlstr1."and storeName='$chooseFive' ";
        }

        //业务员
        if($chooseSix != "全部"){
            $sqlstr1=$sqlstr1."and staff='$chooseSix' ";
        }

        if($chooseSeven != "月" and $chooseSeven != "年"){
            $chooseSeven = "日";
        }

        
        
        //时间段
        if($chooseEight=="默认"){
            if($chooseSeven == "日"){
                $sqlstr=$sqlstr1."and createDate='$date1' ";  //当期
                $sqlstr2=$sqlstr1."and createDate='$date2' ";  //环比
                $sqlstr3=$sqlstr1."and createDate='$date3' ";   //同比
            }elseif($chooseSeven == "月"){
                $sqlstr=$sqlstr1."and createDate like '%$dateMonth%' "; //当期
                $sqlstr2=$sqlstr1."and createDate like '%$dateMonth2%' ";  //环比
                $sqlstr3=$sqlstr1."and createDate like '%$dateMonth3%' ";   //同比
            }elseif($chooseSeven == "年"){
                $sqlstr=$sqlstr1."and createDate like '%$dateYear%' ";  //当期
                $sqlstr2=$sqlstr1."and createDate like '%$dateYear2%' ";  //环比
                $sqlstr3=$sqlstr1."and createDate like '%$dateYear3%' ";  //同比
            }
        }else{
            $sqlstr=$sqlstr1."and createDate like '%$chooseEight%' "; //当期
            $sqlstr2=$sqlstr1."and createDate like '%$date2%' ";  //环比
            $sqlstr3=$sqlstr1."and createDate like '%$date3%' ";  //同比
        }

        //当期

        $sqlstr=\think\Db::query($sqlstr);
        
        $num=0;

        for($i=0;$i<sizeof($sqlstr);$i++){
            $num=$sqlstr[$i]["count"];
        }

        //环比
        $sqlstr2=\think\Db::query($sqlstr2);
        
        $num2=0;

        for($i=0;$i<sizeof($sqlstr2);$i++){
            $num2=$sqlstr2[$i]["count"];
        }

        //同比
        $sqlstr3=\think\Db::query($sqlstr3);
        
        $num3=0;

        for($i=0;$i<sizeof($sqlstr3);$i++){
            $num3=$sqlstr3[$i]["count"];
        }


        if($num3 !="" and $num !=""){
            $tb=($num-$num3)/$num3*100;
        }else{
            $tb=0;
        }

        if($num2 !="" and $num !=""){
            $hb=($num-$num2)/$num2*100;
        }else{
            $hb=0;
        }
        

        $data='[
            {"name":"title","value":"新开拓店铺"},
            {"name":"time","value":"'.$chooseSeven.'"},
            {"name":"num","value":"'.$num.'"},
            {"name":"tb","value":"'.number_format($tb, 2).'"},
            {"name":"hb","value":"'.number_format($hb, 2).'"}  
        ]';

        echo $data;
    
    }

    public function dataQueryController6(){
        session_start();
        $username=$_SESSION["username"];

        $user=\think\Db::name('user_form')->where('username',$username)->select();

        $department=$user[0]["department"];
        $newLevel=$user[0]["newLevel"];

        $chooseOne=$this->request->post('chooseOne');
        $chooseTwo=$this->request->post('chooseTwo');
        $chooseThree=$this->request->post('chooseThree');
        $chooseFour=$this->request->post('chooseFour');
        $chooseFive=$this->request->post('chooseFive');
        $chooseSix=$this->request->post('chooseSix');
        $chooseSeven=$this->request->post('chooseSeven');
        $chooseEight=$this->request->post('chooseEight');

        $date=$chooseEight;

        if($chooseEight=="默认"){
            date_default_timezone_set("Asia/Shanghai");
            $date1=date('Y-m-d', strtotime("-1 day"));
            $date2=date("Y-m-d", strtotime("-1 month"));
            $date3=date("Y-m-d", strtotime("-1 year"));
    
            $dateMonth=date('Y-m', time());
            $dateMonth2=date('Y-m', strtotime("-1 month"));
            $dateMonth3=date('Y-m', strtotime("-1 year"));
    
            $dateYear=date('Y', time());
            $dateYear2=date('Y', strtotime("-1 year"));
            $dateYear3=date('Y', strtotime("-1 year"));
        }else{
            $date1=date('Y-m-d', strtotime("$date"));
            $date2=date('Y-m-d', strtotime("$date -1 month"));
            $date3=date('Y-m-d', strtotime("$date -1 year"));
    
            $dateMonth=$date;
            $dateMonth2=date('Y-m', strtotime("$date -1 month"));
            $dateMonth3=date('Y-m', strtotime("$date -1 year"));
    
            $dateYear=date('Y', strtotime("$date"));
            $dateYear2=date('Y', strtotime("$date -1 year"));
            $dateYear3=date('Y', strtotime("$date -1 year"));
            
        }

        $sqlstr1="select count(*) as count from store where status='关闭' ";


        //事业部
        if($chooseTwo != "全部"){
            $sqlstr1=$sqlstr1."and department='$chooseTwo' ";
        }

        //平台
        if($chooseThree != "全部"){
            $sqlstr1=$sqlstr1."and pingtai='$chooseThree' ";
        }

        //类目
        if($chooseFour != "全部"){
            $sqlstr1=$sqlstr1."and category='$chooseFour' ";
        }

        //店铺
        if($chooseFive != "全部"){
            $sqlstr1=$sqlstr1."and storeName='$chooseFive' ";
        }

        //业务员
        if($chooseSix != "全部"){
            $sqlstr1=$sqlstr1."and staff='$chooseSix' ";
        }

        if($chooseSeven != "月" and $chooseSeven != "年"){
            $chooseSeven = "日";
        }
        
        //时间段
        if($chooseSeven == "日"){
            $sqlstr=$sqlstr1."and createDate='$date1' ";  //当期
            $sqlstr2=$sqlstr1."and createDate='$date2' ";  //环比
            $sqlstr3=$sqlstr1."and createDate='$date3' ";   //同比
        }elseif($chooseSeven == "月"){
            $sqlstr=$sqlstr1."and createDate like '%$dateMonth%' "; //当期
            $sqlstr2=$sqlstr1."and createDate like '%$dateMonth2%' ";  //环比
            $sqlstr3=$sqlstr1."and createDate like '%$dateMonth3%' ";   //同比
        }elseif($chooseSeven == "年"){
            $sqlstr=$sqlstr1."and createDate like '%$dateYear%' ";  //当期
            $sqlstr2=$sqlstr1."and createDate like '%$dateYear2%' ";  //环比
            $sqlstr3=$sqlstr1."and createDate like '%$dateYear3%' ";  //同比
        }
        

        //当期

        $sqlstr=\think\Db::query($sqlstr);
        
        $num=0;

        for($i=0;$i<sizeof($sqlstr);$i++){
            $num=$sqlstr[$i]["count"];
        }

        //环比
        $sqlstr2=\think\Db::query($sqlstr2);
        
        $num2=0;

        for($i=0;$i<sizeof($sqlstr2);$i++){
            $num2=$sqlstr2[$i]["count"];
        }

        //同比
        $sqlstr3=\think\Db::query($sqlstr3);
        
        $num3=0;

        for($i=0;$i<sizeof($sqlstr3);$i++){
            $num3=$sqlstr3[$i]["count"];
        }


        if($num3 !="" and $num !=""){
            $tb=($num-$num3)/$num3*100;
        }else{
            $tb=0;
        }

        if($num2 !="" and $num !=""){
            $hb=($num-$num2)/$num2*100;
        }else{
            $hb=0;
        }
        

        $data='[
            {"name":"title","value":"不合作店铺"},
            {"name":"time","value":"'.$chooseSeven.'"},
            {"name":"num","value":"'.$num.'"},
            {"name":"tb","value":"'.number_format($tb, 2).'"},
            {"name":"hb","value":"'.number_format($hb, 2).'"}  
        ]';

        echo $data;
    
    }

    public function dataQueryController7(){

        session_start();
        $username=$_SESSION["username"];

        $user=\think\Db::name('user_form')->where('username',$username)->select();

        $department=$user[0]["department"];
        $newLevel=$user[0]["newLevel"];

        $chooseOne=$this->request->post('chooseOne');
        $chooseTwo=$this->request->post('chooseTwo');
        $chooseThree=$this->request->post('chooseThree');
        $chooseFour=$this->request->post('chooseFour');
        $chooseFive=$this->request->post('chooseFive');
        $chooseSix=$this->request->post('chooseSix');
        $chooseSeven=$this->request->post('chooseSeven');
        $chooseEight=$this->request->post('chooseEight');

        $date=$chooseEight;

        if($chooseEight=="默认"){
            date_default_timezone_set("Asia/Shanghai");
            $date1=date('Y-m-d', strtotime("-1 day"));
            $date2=date("Y-m-d", strtotime("-1 month"));
            $date3=date("Y-m-d", strtotime("-1 year"));
    
            $dateMonth=date('Y-m', time());
            $dateMonth2=date('Y-m', strtotime("-1 month"));
            $dateMonth3=date('Y-m', strtotime("-1 year"));
    
            $dateYear=date('Y', time());
            $dateYear2=date('Y', strtotime("-1 year"));
            $dateYear3=date('Y', strtotime("-1 year"));
        }else{
            $date1=date('Y-m-d', strtotime("$date"));
            $date2=date('Y-m-d', strtotime("$date -1 month"));
            $date3=date('Y-m-d', strtotime("$date -1 year"));
    
            $dateMonth=$date;
            $dateMonth2=date('Y-m', strtotime("$date -1 month"));
            $dateMonth3=date('Y-m', strtotime("$date -1 year"));
    
            $dateYear=date('Y', strtotime("$date"));
            $dateYear2=date('Y', strtotime("$date -1 year"));
            $dateYear3=date('Y', strtotime("$date -1 year"));
            
        }

        $sqlstr1="select count(*) as count from sx_form where status !='已完成' ";
        $sqlstr1_b="select count(*) as count from sx_form where status !='已完成' ";

        //事业部
        if($chooseTwo != "全部"){
            $sqlstr1=$sqlstr1."and department='$chooseTwo' ";
        }

        //业务员
        if($chooseSix != "全部"){
            $sqlstr1=$sqlstr1."and ywy='$chooseSix' ";
        }

        if($chooseSeven != "月" and $chooseSeven != "年"){
            $chooseSeven = "日";
        }
        
        //时间段
        if($chooseSeven == "日"){
            $sqlstr=$sqlstr1."and date1='$date1' ";  //当期
            $sqlstr_b=$sqlstr1_b."and date1='$date1' ";  //当期全部

            $sqlstr2=$sqlstr1."and date1='$date2' ";  //环比
            $sqlstr2_b=$sqlstr1_b."and date1='$date2' ";  //环比全部
            
            $sqlstr3=$sqlstr1."and date1='$date3' ";   //同比
            $sqlstr3_b=$sqlstr1_b."and date1='$date3' ";   //同比全部
        
        }elseif($chooseSeven == "月"){
            $sqlstr=$sqlstr1."and date1 like '%$dateMonth%' "; //当期
            $sqlstr_b=$sqlstr1_b."and date1 like '%$dateMonth%' "; //当期全部
            
            $sqlstr2=$sqlstr1."and date1 like '%$dateMonth2%' ";  //环比
            $sqlstr2_b=$sqlstr1_b."and date1 like '%$dateMonth2%' ";  //环比全部
            
            $sqlstr3=$sqlstr1."and date1 like '%$dateMonth3%' ";   //同比
            $sqlstr3_b=$sqlstr1_b."and date1 like '%$dateMonth3%' ";   //同比全部
        
        }elseif($chooseSeven == "年"){
            $sqlstr=$sqlstr1."and date1 like '%$dateYear%' ";  //当期
            $sqlstr_b=$sqlstr1_b."and date1 like '%$dateYear%' ";  //当期
            
            $sqlstr2=$sqlstr1."and date1 like '%$dateYear2%' ";  //环比
            $sqlstr2_b=$sqlstr1_b."and date1 like '%$dateYear2%' ";  //环比
            
            $sqlstr3=$sqlstr1."and date1 like '%$dateYear3%' ";  //同比
            $sqlstr3_b=$sqlstr1_b."and date1 like '%$dateYear3%' ";  //同比
        
        }
        

        //当期

        $sqlstr=\think\Db::query($sqlstr);
        
        $num=0;

        for($i=0;$i<sizeof($sqlstr);$i++){
            $num=$sqlstr[$i]["count"];
        }

        //当期全部

        $sqlstr_b=\think\Db::query($sqlstr_b);

        $num_b=0;

        for($i=0;$i<sizeof($sqlstr_b);$i++){
            $num_b=$sqlstr_b[$i]["count"];
        }

        if($num_b==0){
            $per1=0;
        }else{
            $per1=number_format(($num/$num_b)*100,0);
        }
        

        //环比
        $sqlstr2=\think\Db::query($sqlstr2);
        
        $num2=0;

        for($i=0;$i<sizeof($sqlstr2);$i++){
            $num2=$sqlstr2[$i]["count"];
        }

        //环比全部
        $sqlstr2_b=\think\Db::query($sqlstr2_b);
        
        $num2=0;

        for($i=0;$i<sizeof($sqlstr2_b);$i++){
            $num2_b=$sqlstr2_b[$i]["count"];
        }

        if($num_b==0){
            $per2=0;
        }else{
            $per2=number_format(($num/$num_b)*100,2);
        }

        //同比
        $sqlstr3=\think\Db::query($sqlstr3);
        
        $num3=0;

        for($i=0;$i<sizeof($sqlstr3);$i++){
            $num3=$sqlstr3[$i]["count"];
        }

        //同比全部
        $sqlstr3_b=\think\Db::query($sqlstr3_b);
        
        $num3_b=0;

        for($i=0;$i<sizeof($sqlstr3_b);$i++){
            $num3_b=$sqlstr3_b[$i]["count"];
        }

        if($num_b==0){
            $per3=0;
        }else{
            $per3=number_format(($num/$num_b)*100,2);
        }
        
        $data='[
            {"name":"title","value":"授信欠据占比"},
            {"name":"time","value":"'.$chooseSeven.'"},
            {"name":"num","value":"'.$per1.'%"},
            {"name":"tb","value":"'.$per2.'"},
            {"name":"hb","value":"'.$per3.'"}  
        ]';

        echo $data;
    }

    //柱状图
    public function dataQueryController8(){

        session_start();
        $username=$_SESSION["username"];

        $user=\think\Db::name('user_form')->where('username',$username)->select();

        $department=$user[0]["department"];
        $newLevel=$user[0]["newLevel"];

        $chooseOne=$this->request->post('chooseOne');
        $chooseTwo=$this->request->post('chooseTwo');
        $chooseThree=$this->request->post('chooseThree');
        $chooseFour=$this->request->post('chooseFour');
        $chooseFive=$this->request->post('chooseFive');
        $chooseSix=$this->request->post('chooseSix');
        $chooseSeven=$this->request->post('chooseSeven');
        $chooseEight=$this->request->post('chooseEight');

        $date=$chooseEight;

        if($chooseEight=="默认"){
            date_default_timezone_set("Asia/Shanghai");
            $date1=date('Y-m-d', strtotime("-1 day"));
            $date2=date("Y-m-d", strtotime("-1 month"));
            $date3=date("Y-m-d", strtotime("-1 year"));
    
            $dateMonth=date('Y-m', time());
            $dateMonth2=date('Y-m', strtotime("-1 month"));
            $dateMonth3=date('Y-m', strtotime("-1 year"));
    
            $dateYear=date('Y', time());
            $dateYear2=date('Y', strtotime("-1 year"));
            $dateYear3=date('Y', strtotime("-1 year"));
        }else{
            $date1=date('Y-m-d', strtotime("$date"));
            $date2=date('Y-m-d', strtotime("$date -1 month"));
            $date3=date('Y-m-d', strtotime("$date -1 year"));
    
            $dateMonth=$date;
            $dateMonth2=date('Y-m', strtotime("$date -1 month"));
            $dateMonth3=date('Y-m', strtotime("$date -1 year"));
    
            $dateYear=date('Y', strtotime("$date"));
            $dateYear2=date('Y', strtotime("$date -1 year"));
            $dateYear3=date('Y', strtotime("$date -1 year"));
            
        }

        //事业部
        if($chooseTwo != "全部"){
            $sqlstr1=$sqlstr1."and department='$chooseTwo' ";
        }

        //业务员
        if($chooseSix != "全部"){
            $sqlstr1=$sqlstr1."and ywy='$chooseSix' ";
        }

        if($chooseSeven != "月" and $chooseSeven != "年"){
            $chooseSeven = "日";
        }
        
        $sqlstr="select ROUND(sum(a.salesMoney)/10000,2) as num,b.department as department from store_data_sales a left join store b on a.storeID=b.storeID where 1=1 ";

        //时间段
        if($chooseSeven == "日"){
            $sqlstr=$sqlstr."and date='$date1' ";  //当期
        }elseif($chooseSeven == "月"){
            $sqlstr=$sqlstr."and date like '%$dateMonth%' "; //当期
        }elseif($chooseSeven == "年"){
            $sqlstr=$sqlstr."and date like '%$dateYear%' ";  //当期
        }

        $sqlstr=$sqlstr."group by b.department";

        $sqlstr=\think\Db::query($sqlstr);
        $label=["TOMMY","自营","服饰","家纺","居家","母婴","京东","拼多多","天猫","女装","线下"];

        for($i=0;$i<sizeof($sqlstr);$i++){
            for($j=0;$j<sizeof($label);$j++){
                if(strpos($sqlstr[$i]["department"],$label[$j]) !==false){
                    $sqlstr[$i]["department"]=$label[$j];
                }
            }
        }

        echo json_encode($sqlstr,JSON_UNESCAPED_UNICODE);
    }

    //饼图
    public function dataQueryController9(){

        session_start();
        $username=$_SESSION["username"];

        $user=\think\Db::name('user_form')->where('username',$username)->select();

        $department=$user[0]["department"];
        $newLevel=$user[0]["newLevel"];

        $chooseOne=$this->request->post('chooseOne');
        $chooseTwo=$this->request->post('chooseTwo');
        $chooseThree=$this->request->post('chooseThree');
        $chooseFour=$this->request->post('chooseFour');
        $chooseFive=$this->request->post('chooseFive');
        $chooseSix=$this->request->post('chooseSix');
        $chooseSeven=$this->request->post('chooseSeven');
        $chooseEight=$this->request->post('chooseEight');

        $date=$chooseEight;

        if($chooseEight=="默认"){
            date_default_timezone_set("Asia/Shanghai");
            $date1=date('Y-m-d', strtotime("-1 day"));
            $date2=date("Y-m-d", strtotime("-1 month"));
            $date3=date("Y-m-d", strtotime("-1 year"));
    
            $dateMonth=date('Y-m', time());
            $dateMonth2=date('Y-m', strtotime("-1 month"));
            $dateMonth3=date('Y-m', strtotime("-1 year"));
    
            $dateYear=date('Y', time());
            $dateYear2=date('Y', strtotime("-1 year"));
            $dateYear3=date('Y', strtotime("-1 year"));
        }else{
            $date1=date('Y-m-d', strtotime("$date"));
            $date2=date('Y-m-d', strtotime("$date -1 month"));
            $date3=date('Y-m-d', strtotime("$date -1 year"));
    
            $dateMonth=$date;
            $dateMonth2=date('Y-m', strtotime("$date -1 month"));
            $dateMonth3=date('Y-m', strtotime("$date -1 year"));
    
            $dateYear=date('Y', strtotime("$date"));
            $dateYear2=date('Y', strtotime("$date -1 year"));
            $dateYear3=date('Y', strtotime("$date -1 year"));
            
        }

        $sqlstr="select ROUND(sum(a.salesMoney)/10000,2) as num,b.pingtai as pt from store_data_sales a left join store b on a.storeID=b.storeID where 1=1 ";

        //事业部
        if($chooseTwo != "全部"){
            $sqlstr1=$sqlstr1."and department='$chooseTwo' ";
        }

        //业务员
        if($chooseSix != "全部"){
            $sqlstr1=$sqlstr1."and ywy='$chooseSix' ";
        }

        if($chooseSeven != "月" and $chooseSeven != "年"){
            $chooseSeven = "日";
        }
        
        

        //时间段
        if($chooseSeven == "日"){
            $sqlstr=$sqlstr."and date='$date1' ";  //当期
        }elseif($chooseSeven == "月"){
            $sqlstr=$sqlstr."and date like '%$dateMonth%' "; //当期
        }elseif($chooseSeven == "年"){
            $sqlstr=$sqlstr."and date like '%$dateYear%' ";  //当期
        }

        $sqlstr=$sqlstr."group by b.pingtai order by num desc  limit 10";

        $sqlstr=\think\Db::query($sqlstr);

        // $data=array();

        // $label=["京东","天猫","唯品会","苏宁","其他"];
        // $value=["235", "274", "310", "335", "400"];

        // for($i=0;$i<sizeof($label);$i++){
        //     $data[$label[$i]]=$value[$i];
        // }

        echo json_encode($sqlstr,JSON_UNESCAPED_UNICODE);

    }

}
