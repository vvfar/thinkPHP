<?php
namespace app\index\controller;
use think\Controller;
use think\Request;

class Contract extends Controller{

    public function contract(){

        session_start();
        $username=$_SESSION["username"];

        $id=$this->request->get("id");

        $contract_info= array();

        if($id !=""){
            $contract_infos=\think\Db::name("contract")->where("id",$id)->select();
            $contract_info=$contract_infos[0];
        }else{
            $contract_info["id"]="";
            $contract_info["no"]="";
            $contract_info["contractType"]="";
            $contract_info["company"]="";
            $contract_info["store"]="";
            $contract_info["pingtai"]="";
            $contract_info["category"]="";
            $contract_info["money"]="";
            $contract_info["ismoney"]="";
            $contract_info["sales"]="";
            $contract_info["issales"]="";
            $contract_info["service"]="";
            $contract_info["isservice"]="";
            $contract_info["oldNo"]="";
            $contract_info["note"]="";
            $contract_info["input_time"]="";
            $contract_info["input_time2"]="";
        }

        $this->assign('username',$username);
        $this->assign('title','新增合同');
        $this->assign('contract_info',$contract_info);

        return $this->fetch();
    }

    public function w_contract(){

        session_start();
        $username=$_SESSION["username"];

        $contractID=$this->request->param("contractID");
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

        $sqlstr3="select count(*) as total from contract where not status like '%已归档%'";

        if($newLevel !="ADMIN" and $department !="财务部" and $department !="商业运营部"){
            $sqlstr3=$sqlstr3." and '$department' like concat('%',department,'%') ";
        }

        if($clientName !=""){
            $sqlstr3=$sqlstr3." and company like '%$clientName%'";
        }elseif($contractID !=""){
            $sqlstr3=$sqlstr3." and no like '%$contractID%'";
        }

        $sqlstr3=\think\Db::query($sqlstr3);

        $total=$sqlstr3[0]["total"];

        if($total%$pagesize==0){
            $pagecount=intval($total/$pagesize);
        }else{
            $pagecount=ceil($total/$pagesize);
        }

        //表格数据
        $sqlstr2="select id,no,company,pingtai,category,department,money,sales,service,re_date,'合同',status,shr,shTime from contract where not status like '%已归档%'";

        if($newLevel !="ADMIN" and $department !="财务部" and $department !="商业运营部"){
            
            $sqlstr2=$sqlstr2." and '$department' like concat('%',department,'%') ";
        }


        if($clientName !=""){
            $sqlstr2=$sqlstr2." and company like '%$clientName%'";
        }elseif($contractID !=""){
            $sqlstr2=$sqlstr2." and no like '%$contractID%'";
        }

        $sqlstr2=$sqlstr2." order by id desc limit ".($page-1)*$pagesize.",$pagesize";

        $contracts=\think\Db::query($sqlstr2);

        $this->assign('username',$username);
        $this->assign('title','待审核合同');
        $this->assign('contracts',$contracts);
        $this->assign('contractID',$contractID);
        $this->assign('clientName',$clientName);
        $this->assign('total',$total);
        $this->assign('pagecount',$pagecount);
        $this->assign('page',$page);
        $this->assign('pagesize',15);
        $this->assign('i',1);

        return $this->fetch();
    }

    public function d_contract(){

        session_start();
        $username=$_SESSION["username"];

        $contractID=$this->request->param("contractID");
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

        $sqlstr3="select count(*) as total from contract where status like '%已归档%'";

        if($newLevel !="ADMIN" and $department !="财务部" and $department !="商业运营部"){
            $sqlstr3=$sqlstr3." and '$department' like concat('%',department,'%') ";
        }

        if($clientName !=""){
            $sqlstr3=$sqlstr3." and company like '%$clientName%'";
        }elseif($contractID !=""){
            $sqlstr3=$sqlstr3." and no like '%$contractID%'";
        }

        $sqlstr3=\think\Db::query($sqlstr3);

        $total=$sqlstr3[0]["total"];

        if($total%$pagesize==0){
            $pagecount=intval($total/$pagesize);
        }else{
            $pagecount=ceil($total/$pagesize);
        }


        $sqlstr2="select id,no,company,pingtai,category,department,money,sales,service,re_date,'合同',status,shr,shTime from contract where status like '%已归档%'";

        if($newLevel !="ADMIN" and $department !="财务部" and $department !="商业运营部"){
            $sqlstr2=$sqlstr2." and '$department' like concat('%',department,'%') ";
        }

        if($clientName !=""){
            $sqlstr2=$sqlstr2." and company like '%$clientName%'";
        }elseif($contractID !=""){
            $sqlstr2=$sqlstr2." and no like '%$contractID%'";
        }

        $sqlstr2=$sqlstr2."order by id desc limit ".($page-1)*$pagesize.",$pagesize";

        $contracts=\think\Db::query($sqlstr2);

        $i=1;

        $this->assign('username',$username);
        $this->assign('title','已审核合同');
        $this->assign('contracts',$contracts);
        $this->assign('contractID',$contractID);
        $this->assign('clientName',$clientName);
        $this->assign('total',$total);
        $this->assign('pagecount',$pagecount);
        $this->assign('page',$page);
        $this->assign('pagesize',15);
        $this->assign('i',1);
        $this->assign('newLevel',$newLevel);
        $this->assign('title','已审核合同');

        return $this->fetch();

    }

    public function add_contract_handle($progress){
        
        date_default_timezone_set("Asia/Shanghai");

        session_start();
        $username=$_SESSION["username"];

        $sqlstr1=\think\Db::name("user_form")->field(["department","newLevel"])->where("username",$username)->select();

        $department=$sqlstr1[0]["department"];
        
        if($progress==1){

            $id=$this->request->param('id');
            $no=$this->request->param('no');
            $pingtai=$this->request->param('pingtai');
            $category=$this->request->param('category');
            $company=$this->request->param('company');
            $store=$this->request->param('store');
            $input_time=$this->request->param('input_time');
            $input_time2=$this->request->param('input_time2');
            $money=$this->request->param('money');
            $ismoney=$this->request->param('ismoney');
            $sales=$this->request->param('sales');
            $issales=$this->request->param('issales');
            $service=$this->request->param('service');
            $isservice=$this->request->param('isservice');
            $note=$this->request->param('note');
            $oldNo=$this->request->param('oldNo');
            $contractType=$this->request->param('contractType');

            $re_date=date('Y-m-d', time());
            $time=date('Y-m-d  H:i:s', time());
    
            if($contractType=="供应商合同"){
                $store=$company;
                $pingtai="工厂";
            }
    
            $sqlstr=\think\Db::name("contract")->field("max(id)")->select();

            $maxID=$sqlstr[0]["max(id)"];
    
            if($maxID==""){
                $maxID=0;
            }

            if($id==""){

                \think\Db::table('contract')->insert(
                    [
                        'id'=>$maxID+1,
                        're_date'=>$re_date,
                        'no'=>$no,
                        'department'=>$department,
                        'pingtai'=>$pingtai,
                        'category'=>$category,
                        'company'=>$company,
                        'store'=>$store,
                        'input_time'=>$input_time,
                        'input_time2'=>$input_time2,
                        'money'=>$money,
                        'ismoney'=>$ismoney,
                        'sales'=>$sales,
                        'issales'=>$issales,
                        'service'=>$service,
                        'isservice'=>$isservice,
                        'note'=>$note,
                        'status'=>'待归档',
                        'oldNo'=>$oldNo,
                        'shr'=>$username,
                        'shTime'=>$time,
                        'contractType'=>$contractType,
                    ]
                );
            }else{
                $sqlstr1=\think\Db::query("update contract set no='$no',company='$company',store='$store',pingtai='$pingtai',category='$category',money='$money',ismoney='$ismoney',sales='$sales',issales='$issales',service='$service',isservice='$isservice',note='$note',oldNo='$oldNo',status='待归档',shr='$username',shTime='$time',contractType='$contractType',input_time='$input_time',input_time2='$input_time2' where id='$id'");
            }
            
            return redirect('/index.php/Index/contract/w_contract.html');
            
        }elseif($progress == 4){
            //审核通过
            $id=$_GET["id"];
            
            $update=\think\Db::table("contract")->where("id",$id)->update(["status"=>"已归档"]);
  
            return redirect("/index.php/Index/contract/contract_line.html?id=".$id);
    
        }elseif($progress == 5){
            //审核拒绝
            $id=$_GET["id"];
            
            $update=\think\Db::table("contract")->where("id",$id)->update(["status"=>"审核拒绝"]);
  
            return redirect("/index.php/Index/contract/contract_line.html?id=".$id);

        }elseif($progress == 6){
            $id=$_GET["id"];
    
            $del=\think\Db::table("contract")->where("id",$id)->delete();

            return redirect("/index.php/Index/contract/d_contract.html");
        }        
    }

    public function contract_line($id){
        session_start();
        $username=$_SESSION["username"];

        $sqlstr1=\think\Db::name("user_form")->field(["department","newLevel"])->where("username",$username)->select();

        $department=$sqlstr1[0]["department"];
        $newLevel=$sqlstr1[0]["newLevel"];

        $contract_items=\think\Db::name("contract")->where("id",$id)->select();
        $contract_item=$contract_items[0];
        
        $counts=\think\Db::name("contract")->field("count(*)")->where("no",$contract_item["oldNo"])->select();
        $count=$counts[0];

        $contracts_all=\think\Db::name("contract")->field(["company","store","pingtai","category","money","ismoney","sales","issales","service","isservice"])->where("no",$contract_item["no"])->select();

        $this->assign('username',$username);
        $this->assign('department',$department);
        $this->assign('newLevel',$newLevel);
        $this->assign('contract_item',$contract_item);
        $this->assign('count',$count);
        $this->assign('contracts_all',$contracts_all);
        $this->assign('title','合同详情');

        return $this->fetch();
    }

    public function contract_download($contractID,$clientName,$status){

        $conn=mysqli_connect("localhost","root","root","yzl_database") or die("连接数据库服务器失败！".mysqli_error());
        mysqli_query($conn,"set names utf8");

        session_start();
        $username=$_SESSION['username'];

        $sqlstr1=\think\Db::name("user_form")->field(["department","newLevel"])->where("username",$username)->select();

        $department=$sqlstr1[0]["department"];
        $newLevel=$sqlstr1[0]["newLevel"];
    
        if($contractID !=""){
            $sqlstrIn=" and no like '%".$contractID."%'";
        }elseif($clientName !=""){
            $sqlstrIn=" and company like '%".$clientName."%'";
        }else{
            $sqlstrIn="";
        }
    
        if($status !=""){
            if($status=="待审核"){
                $sqlstrIn=$sqlstrIn." and status='待归档'";
            }else{
                $sqlstrIn=$sqlstrIn." and status='已归档'";
            }
        }

    
        $sqlstr2="select * from contract where 1=1 ".$sqlstrIn;
                    
        if($newLevel !="ADMIN" and $department !="财务部" and $department !="商业运营部"){
            if($newLevel == "KA"){
                $sqlstr2=$sqlstr2." and shr like '%$username%'"; 
            }else{
                $sqlstr2=$sqlstr2." and '$department' like concat('%',department,'%') ";
            }
        }
    
        $result=mysqli_query($conn,$sqlstr2);

        $data=array();

        while($myrow=mysqli_fetch_row($result)){
            $data[]=str_replace("\t",'',$myrow);
            //$data[]=$myrow;
            //echo json_encode($data);
        }
        
        /*for($i=0;$i<sizeof($downloads);$i++){
            $data[]=str_replace("\t",'',$downloads[0][]);
            //$data[]=$myrow;
            echo json_encode($data);
        }
        */  
        
    
        
        foreach($data as $key=>$value){
            foreach($value as $keys=>$values){
    
            }
        }
    
        $header=array('登记日期','合同编号','事业部','授权平台','授权类目','公司名称','店铺名','合同日期(开始)','合同日期(结束)','保证金','是否共享保证金','销售额(万)','是否共享销售额','服务费(万)','是否共享服务费','备注','状态','原合同编号','审核人','审核时间');
    
            
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
    
        $list2=range(1,20);
    
        createtable($data,'合同',$header,$list2);

        return redirect('/index.php/Index/contract/d_contract.html');
    }

}