<?php
namespace app\index\controller;
use think\Controller;
use think\Request;
use think\Db;

class Client extends Controller{

    public function clientList(){
    
        session_start();
        $username=$_SESSION["username"];

        $sqlstr1=Db::name("user_form")->field(["department","newLevel"])->where("username",$username)->select();

        $department=$sqlstr1[0]["department"];
        $newLevel=$sqlstr1[0]["newLevel"];
        
        if(!isset($_GET["page"]) || !is_numeric($_GET["page"])){
            $page=1;
        }else{
            $page=intval($_GET["page"]);
        }

        $optionID=$this->request->param('optionID');
        $companyName=$this->request->param('companyName');
        $storeName=$this->request->param('storeName');
        $department=$this->request->param('department');
        $staff=$this->request->param('staff');

        $pagesize=15;

        if($optionID=="1"){
            $companyInfo_all=Db::name('client')->where('clientName','like','%'.$companyName.'%')->select();
            $companyInfo=Db::name('client')->order(['id'=>'desc'])->where('clientName','like','%'.$companyName.'%')->limit(($page-1)*$pagesize,$page*$pagesize)->select();
        }else if($optionID=="2"){
            $companyInfo_all=Db::name('client')->where('storeName','like','%'.$storeName.'%')->select();
            $companyInfo=Db::name('client')->order(['id'=>'desc'])->where('storeName','like','%'.$storeName.'%')->limit(($page-1)*$pagesize,$page*$pagesize)->select();
        }else if($optionID=="3"){
            $companyInfo_all=Db::name('client')->where('department','like','%'.$department.'%')->select();
            $companyInfo=Db::name('client')->order(['id'=>'desc'])->where('department','like','%'.$department.'%')->limit(($page-1)*$pagesize,$page*$pagesize)->select();
        }else if($optionID=="4"){
            $companyInfo_all=Db::name('client')->where('staff','like','%'.$staff.'%')->select();
            $companyInfo=Db::name('client')->order(['id'=>'desc'])->where('staff','like','%'.$staff.'%')->limit(($page-1)*$pagesize,$page*$pagesize)->select();
        }else{
            $companyInfo_all=Db::name('client')->select();
            $companyInfo=Db::name('client')->order(['id'=>'desc'])->limit(($page-1)*$pagesize,$page*$pagesize)->select();
        }
        

        $total=sizeof($companyInfo_all);
        $pagecount=(int)($total/$pagesize+1);

        if($total % $pagesize ==0){
            $pagecount=$pagecount-1;
        }
        
        $data=[
            'title' => '客户列表',
            'username' => $username,
            'companyInfos' => $companyInfo,
            'page' => $page,
            'pagecount' => $pagecount,
            'pagesize' => $pagesize,
            'total' => $total,
            'optionID' => $optionID,
            'companyName' => $companyName,
            'storeName' => $storeName,
            'department' => $department,
            'staff' => $staff
        ];

        return $this->fetch('',$data);
    }

    public function addClient(){
        
        session_start();
        $method=$this->request->method();

        if(isset($_SESSION["username"])){
            if($method=="POST"){
                date_default_timezone_set("Asia/Shanghai");
                $re_date=date('Y-m-d', time());

                $username=$_SESSION["username"];
                $departments=Db::name('user_form')->field('department')->where('username',$username)->limit(1)->find();
                $department=$departments["department"];

                $clientName=$this->request->param('client');
                $storeName=$this->request->param('storeName');
                $staff=$this->request->param('staff');
                $telephone=$this->request->param('telephone');
                $address=$this->request->param('address');
                $contractID=$this->request->param('contractID');

                $result=Db::table('client')->insert(
                    [
                        'clientName'=>$clientName,
                        'storeName'=>$storeName,
                        'department'=>$department,
                        'staff'=>$staff,
                        'telephone'=>$telephone,
                        'address'=>$address,
                        'contractID'=>$contractID,
                        're_date' => $re_date,
                        'change_by' => $username
                    ]
                );

                if($result==1){
                    return $this->success('添加成功！','/index.php/Index/client/clientList.html','',1);
                }else{
                    return $this->error('添加失败！','/index.php/Index/client/addClient.html','',1);
                }
                
            }else{

                $username=$_SESSION["username"];

                $departments=Db::name('user_form')->field('department')->where('username',$username)->limit(1)->select();

                $data=[
                    'title' => '新增客户',
                    'username' => $username,
                    'departments' => $departments,
                ];

                return $this->fetch('',$data);
            }
        }else{
            return $this->redirect('/index.php/Index/Login/login');
        }
    }

    public function delClient($id){
        session_start();
        $method=$this->request->method();

        if(isset($_SESSION["username"])){

            $result=Db::table('client')->where('Id',$id)->delete();

            if($result==1){
                return $this->success('删除成功！','/index.php/Index/client/clientList.html','',1);
            }else{
                return $this->error('删除失败！','/index.php/Index/client/clientList.html','',1);
            }

        }else{
            return $this->redirect('/index.php/Index/Login/login');
        }
    }

    public function edit_client($id){
        session_start();
        $method=$this->request->method();

        if(isset($_SESSION["username"])){

            $username=$_SESSION["username"];

            $departments=Db::name('user_form')->field('department')->where('username',$username)->limit(1)->find();
            $department=$departments["department"];

            if($method == "POST"){
                date_default_timezone_set("Asia/Shanghai");
                $re_date=date('Y-m-d', time());

                $id=$this->request->param("id");
                $contractID=$this->request->param("contractID");
                $client=$this->request->param("client");
                $storeName=$this->request->param("storeName");
                $staff=$this->request->param("staff");
                $telephone=$this->request->param("telephone");
                $address=$this->request->param("address");

                $result=Db::table("client")->where("id",$id)->update([
                    'clientName'=>$client,
                    'storeName'=>$storeName,
                    'department'=>$department,
                    'staff'=>$staff,
                    'telephone'=>$telephone,
                    'address'=>$address,
                    'contractID'=>$contractID,
                    'change_date' => $re_date,
                    'change_by' => $username
                ]);

                if($result==1){
                    return $this->success('更新成功！','/index.php/Index/client/clientList.html','',1);
                }else{
                    return $this->error('更新失败！','/index.php/Index/client/edit_client?id='.$id,'',1);
                }
                
            }else{
                $client_line=Db::name("client")->where("Id",$id)->find();
            
                $data=[
                    'title' => '编辑客户',
                    'username' => $username,
                    'client_line' => $client_line,
                ];
            
                return $this->fetch('',$data);
            }
        }else{
            return $this->redirect('/index.php/Index/Login/login');
        }
    }

    public function client_line($id){
        session_start();

        if(isset($_SESSION["username"])){

            $username=$_SESSION["username"];

            $client_line=Db::name("client")->where("Id",$id)->find();

            $contractID=$client_line["contractID"];
            $is_agrees=Db::name("contract")->field("count(*)")->where("no",$contractID)->find();
            $is_agree=$is_agrees["count(*)"];

            if($is_agree>0){
                $status=Db::name("contract")->field("status")->where("no",$contractID)->find();

                if($status["status"]=="已归档"){
                    $client_line["is_agree"]="是";
                }else{
                    $client_line["is_agree"]="否";
                }
            }else{
                $client_line["is_agree"]="否";
            }

            $data=[
                'title' => '查看客户',
                'username' => $username,
                'client_line' => $client_line,
            ];
        
            return $this->fetch('',$data);


        }else{
            return $this->redirect('/index.php/Index/Login/login');
        }
    }

    public function client_download(){
        $conn=mysqli_connect("localhost","root","root","yzl_database") or die("连接数据库服务器失败！".mysqli_error());
        mysqli_query($conn,"set names utf8");

        session_start();
        $username=$_SESSION['username'];

        $sqlstr1=Db::name("user_form")->field(["department","newLevel"])->where("username",$username)->select();

        $department=$sqlstr1[0]["department"];
        $newLevel=$sqlstr1[0]["newLevel"];

        $optionID=$this->request->param("optionID");
        $companyName=$this->request->param("companyName");
        $storeName=$this->request->param("storeName");
        $department=$this->request->param("department");
        $staff=$this->request->param("staff");
    
        $sqlstr2="select * from client where 1=1 ";

        if($optionID == 1){
            $sqlstr2=$sqlstr2."and clientName like '%$companyName%'";
        }elseif($optionID == 2){
            $sqlstr2=$sqlstr2."and storeName like '%$storeName%'";
        }elseif($optionID == 3){
            $sqlstr2=$sqlstr2."and department like '%$department%'";
        }elseif($optionID == 4){
            $sqlstr2=$sqlstr2."and staff like '%$staff%'";
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
    
        $header=array('客户名称','店铺名称','事业部','业务员','联系电话','地址','登记日期','修改日期','修改人','主合同编号');
            
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
    
        $list2=range(1,10);
    
        createtable($data,'客户信息',$header,$list2);
    }
}
?>