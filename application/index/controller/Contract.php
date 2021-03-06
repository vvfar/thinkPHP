<?php
namespace app\index\controller;
use think\Controller;
use think\Request;
use think\Db;

class Contract extends Controller{

    public function contract(){

        session_start();
        $username=$_SESSION["username"];

        $id=$this->request->get("id");

        $contract_info= array();

        if($id !=""){
            $contract_infos=Db::name("contract")->where("id",$id)->select();
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

        $data=[
            'username' => $username,
            'title' => '新增合同',
            'contract_info' => $contract_info
        ];

        return $this->fetch('',$data);
    }

    public function w_contract(){

        session_start();
        $username=$_SESSION["username"];

        $keywords=$this->request->param("keywords");
        $option=0;

        $contract_no_count=Db::name("contract")->field("count(*)")->where("no","like",'%'.$keywords.'%')->find();
        $contract_company_count=Db::name("contract")->field("count(*)")->where("company","like",'%'.$keywords.'%')->find();
        $contract_pingtai_count=Db::name("contract")->field("count(*)")->where("pingtai","like",'%'.$keywords.'%')->find();
        $contract_category_count=Db::name("contract")->field("count(*)")->where("category","like",'%'.$keywords.'%')->find();
        $contract_department_count=Db::name("contract")->field("count(*)")->where("department","like",'%'.$keywords.'%')->find();
        $contract_shr_count=Db::name("contract")->field("count(*)")->where("shr","like",'%'.$keywords.'%')->find();

        if($contract_no_count["count(*)"] != 0){
            $option=1;
        }elseif($contract_company_count["count(*)"] != 0){
            $option=2;
        }elseif($contract_pingtai_count["count(*)"] != 0){
            $option=3;
        }elseif($contract_category_count["count(*)"] != 0){
            $option=4;
        }elseif($contract_department_count["count(*)"] != 0){
            $option=5;
        }elseif($contract_shr_count["count(*)"] != 0){
            $option=6;
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

        $sqlstr3="select count(*) as total from contract where not status like '%已归档%'";

        if($newLevel !="ADMIN" and $department !="财务部" and $department !="商业运营部"){
            $sqlstr3=$sqlstr3." and '$department' like concat('%',department,'%') ";
        }

        $sqlstr3=Db::query($sqlstr3);

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


        if($option==1){
            $sqlstr2=$sqlstr2." and no like '%$keywords%' ";
        }elseif($option==2){
            $sqlstr2=$sqlstr2." and company like '%$keywords%' ";
        }elseif($option==3){
            $sqlstr2=$sqlstr2." and pingtai like '%$keywords%' ";
        }elseif($option==4){
            $sqlstr2=$sqlstr2." and category like '%$keywords%' ";
        }elseif($option==5){
            $sqlstr2=$sqlstr2." and department like '%$keywords%' ";
        }elseif($option==6){
            $sqlstr2=$sqlstr2." and shr like '%$keywords%' ";
        }

        $sqlstr2=$sqlstr2." order by id desc limit ".($page-1)*$pagesize.",$pagesize";

        $contracts=Db::query($sqlstr2);

        $data=[
            'username' => $username,
            'title' => '待审核合同',
            'contracts' => $contracts,
            'keywords' => $keywords,
            'total' => $total,
            'pagecount' => $pagecount,
            'page' => $page,
            'pagesize' => 15,
        ];

        return $this->fetch('',$data);
    }

    public function d_contract(){

        session_start();
        $username=$_SESSION["username"];

        $keywords=$this->request->param("keywords");
        $option=0;

        $contract_no_count=Db::name("contract")->field("count(*)")->where("no","like",'%'.$keywords.'%')->find();
        $contract_company_count=Db::name("contract")->field("count(*)")->where("company","like",'%'.$keywords.'%')->find();
        $contract_pingtai_count=Db::name("contract")->field("count(*)")->where("pingtai","like",'%'.$keywords.'%')->find();
        $contract_category_count=Db::name("contract")->field("count(*)")->where("category","like",'%'.$keywords.'%')->find();
        $contract_department_count=Db::name("contract")->field("count(*)")->where("department","like",'%'.$keywords.'%')->find();
        $contract_shr_count=Db::name("contract")->field("count(*)")->where("shr","like",'%'.$keywords.'%')->find();

        if($contract_no_count["count(*)"] != 0){
            $option=1;
        }elseif($contract_company_count["count(*)"] != 0){
            $option=2;
        }elseif($contract_pingtai_count["count(*)"] != 0){
            $option=3;
        }elseif($contract_category_count["count(*)"] != 0){
            $option=4;
        }elseif($contract_department_count["count(*)"] != 0){
            $option=5;
        }elseif($contract_shr_count["count(*)"] != 0){
            $option=6;
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

        $sqlstr3="select count(*) as total from contract where status like '%已归档%'";

        if($newLevel !="ADMIN" and $department !="财务部" and $department !="商业运营部"){
            $sqlstr3=$sqlstr3." and '$department' like concat('%',department,'%') ";
        }

        if($option==1){
            $sqlstr3=$sqlstr3." and no like '%$keywords%' ";
        }elseif($option==2){
            $sqlstr3=$sqlstr3." and company like '%$keywords%' ";
        }elseif($option==3){
            $sqlstr3=$sqlstr3." and pingtai like '%$keywords%' ";
        }elseif($option==4){
            $sqlstr3=$sqlstr3." and category like '%$keywords%' ";
        }elseif($option==5){
            $sqlstr3=$sqlstr3." and department like '%$keywords%' ";
        }elseif($option==6){
            $sqlstr3=$sqlstr3." and shr like '%$keywords%' ";
        }

        $sqlstr3=Db::query($sqlstr3);

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

        if($option==1){
            $sqlstr2=$sqlstr2." and no like '%$keywords%' ";
        }elseif($option==2){
            $sqlstr2=$sqlstr2." and company like '%$keywords%' ";
        }elseif($option==3){
            $sqlstr2=$sqlstr2." and pingtai like '%$keywords%' ";
        }elseif($option==4){
            $sqlstr2=$sqlstr2." and category like '%$keywords%' ";
        }elseif($option==5){
            $sqlstr2=$sqlstr2." and department like '%$keywords%' ";
        }elseif($option==6){
            $sqlstr2=$sqlstr2." and shr like '%$keywords%' ";
        }

        $sqlstr2=$sqlstr2."order by id desc limit ".($page-1)*$pagesize.",$pagesize";

        $contracts=Db::query($sqlstr2);

        $i=1;

        $data=[
            'username' => $username,
            'newLevel' => $newLevel,
            'title' => '已审核合同',
            'contracts' => $contracts,
            'keywords' => $keywords,
            'total' => $total,
            'pagecount' => $pagecount,
            'page' => $page,
            'pagesize' => 15,
        ];

        return $this->fetch('',$data);
    }

    public function add_contract_handle($progress){
        
        date_default_timezone_set("Asia/Shanghai");

        session_start();
        $username=$_SESSION["username"];

        $sqlstr1=Db::name("user_form")->field(["department","newLevel"])->where("username",$username)->select();

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
    
            if($id==""){

                $result=Db::table('contract')->insert(
                    [
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
                $result=Db::query("update contract set no='$no',company='$company',store='$store',pingtai='$pingtai',category='$category',money='$money',ismoney='$ismoney',sales='$sales',issales='$issales',service='$service',isservice='$isservice',note='$note',oldNo='$oldNo',status='待归档',shr='$username',shTime='$time',contractType='$contractType',input_time='$input_time',input_time2='$input_time2' where id='$id'");
            }

            if($result==1){
                return $this->success('提交成功！','/index.php/Index/contract/w_contract.html','',1);
            }else{
                return $this->error('提交失败！','/index.php/Index/contract/addClient.html','',1);
            }
            
        }elseif($progress == 4){
            //审核通过
            $id=$_GET["id"];
            
            $result=Db::table("contract")->where("id",$id)->update(["status"=>"已归档"]);

            if($result==1){
                return $this->success('提交成功！','/index.php/Index/contract/contract_line.html?id='.$id,'',1);
            }else{
                return $this->error('提交失败！','/index.php/Index/contract/contract_line.html?id='.$id,'',1);
            }

        }elseif($progress == 5){
            //审核拒绝
            $id=$_GET["id"];
            
            $result=Db::table("contract")->where("id",$id)->update(["status"=>"审核拒绝"]);
  
            if($result==1){
                return $this->success('提交成功！','/index.php/Index/contract/contract_line.html?id='.$id,'',1);
            }else{
                return $this->error('提交失败！','/index.php/Index/contract/contract_line.html?id='.$id,'',1);
            }

        }elseif($progress == 6){
            $id=$_GET["id"];
    
            $result=Db::table("contract")->where("id",$id)->delete();

            if($result==1){
                return $this->success('删除成功！','/index.php/Index/contract/d_contract.html','',1);
            }else{
                return $this->error('删除失败！','/index.php/Index/contract/d_contract.html','',1);
            }
        }        
    }

    public function contract_line($id){
        session_start();
        $username=$_SESSION["username"];

        $sqlstr1=Db::name("user_form")->field(["department","newLevel"])->where("username",$username)->select();

        $department=$sqlstr1[0]["department"];
        $newLevel=$sqlstr1[0]["newLevel"];

        $contract_items=Db::name("contract")->where("id",$id)->select();
        $contract_item=$contract_items[0];
        
        $counts=Db::name("contract")->field("count(*)")->where("no",$contract_item["oldNo"])->select();
        $count=$counts[0];

        $contracts_all=Db::name("contract")->field(["company","store","pingtai","category","money","ismoney","sales","issales","service","isservice"])->where("no",$contract_item["no"])->select();

        $data=[
            'username' => $username,
            'department' => $department,
            'newLevel' => $newLevel,
            'contract_item' => $contract_item,
            'count' => $count,
            'contracts_all' => $contracts_all,
            'title' => '合同详情'
        ];

        return $this->fetch('',$data);
    }

    public function contract_download($status){

        $conn=mysqli_connect("localhost","root","root","yzl_database") or die("连接数据库服务器失败！".mysqli_error());
        mysqli_query($conn,"set names utf8");

        session_start();
        $username=$_SESSION['username'];

        $sqlstr1=Db::name("user_form")->field(["department","newLevel"])->where("username",$username)->select();

        $department=$sqlstr1[0]["department"];
        $newLevel=$sqlstr1[0]["newLevel"];
    
        $keywords=$this->request->param("keywords");
        $option=0;

        $contract_no_count=Db::name("contract")->field("count(*)")->where("no","like",'%'.$keywords.'%')->find();
        $contract_company_count=Db::name("contract")->field("count(*)")->where("company","like",'%'.$keywords.'%')->find();
        $contract_pingtai_count=Db::name("contract")->field("count(*)")->where("pingtai","like",'%'.$keywords.'%')->find();
        $contract_category_count=Db::name("contract")->field("count(*)")->where("category","like",'%'.$keywords.'%')->find();
        $contract_department_count=Db::name("contract")->field("count(*)")->where("department","like",'%'.$keywords.'%')->find();
        $contract_shr_count=Db::name("contract")->field("count(*)")->where("shr","like",'%'.$keywords.'%')->find();

        if($contract_no_count["count(*)"] != 0){
            $option=1;
        }elseif($contract_company_count["count(*)"] != 0){
            $option=2;
        }elseif($contract_pingtai_count["count(*)"] != 0){
            $option=3;
        }elseif($contract_category_count["count(*)"] != 0){
            $option=4;
        }elseif($contract_department_count["count(*)"] != 0){
            $option=5;
        }elseif($contract_shr_count["count(*)"] != 0){
            $option=6;
        }

    
        $sqlstr2="select * from contract where 1=1 ";
                    
        if($newLevel !="ADMIN" and $department !="财务部" and $department !="商业运营部"){
            if($newLevel == "KA"){
                $sqlstr2=$sqlstr2." and shr like '%$username%'"; 
            }else{
                $sqlstr2=$sqlstr2." and '$department' like concat('%',department,'%') ";
            }
        }

        if($option==1){
            $sqlstr2=$sqlstr2." and no like '%$keywords%' ";
        }elseif($option==2){
            $sqlstr2=$sqlstr2." and company like '%$keywords%' ";
        }elseif($option==3){
            $sqlstr2=$sqlstr2." and pingtai like '%$keywords%' ";
        }elseif($option==4){
            $sqlstr2=$sqlstr2." and category like '%$keywords%' ";
        }elseif($option==5){
            $sqlstr2=$sqlstr2." and department like '%$keywords%' ";
        }elseif($option==6){
            $sqlstr2=$sqlstr2." and shr like '%$keywords%' ";
        }

        if($status=="已归档"){
            $sqlstr2=$sqlstr2." and status = '已归档' ";
        }else{
            $sqlstr2=$sqlstr2." and status <> '已归档' ";
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