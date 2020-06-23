<?php
namespace app\index\controller;
use think\Controller;
use think\Db;

class Device extends Controller
{
    public function device_list(){
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

            $sqlstr3="select count(*) as total from it";

            $sqlstr3=Db::query($sqlstr3);

            $total=$sqlstr3[0]["total"];

            if($total%$pagesize==0){
                $pagecount=intval($total/$pagesize);
            }else{
                $pagecount=ceil($total/$pagesize);
            }

            $sqlstr2="select id,barcode,user,department,brand,system2,ram,hardpan,leixing,leibie from it order by department desc,leibie asc limit ".($page-1)*$pagesize.",$pagesize";
                                                
            $devices=Db::query($sqlstr2);

            $this->assign('title','设备列表');
            $this->assign('username',$username);
            $this->assign('devices',$devices);
            $this->assign("total",$total);
            $this->assign('pagecount',$pagecount);
            $this->assign('pagesize',15);
            $this->assign('page',$page);

            return $this->fetch();
        }else{
            return $this->redirect('/index.php/Index/Login/login');
        }
    }

    public function add_device(){
        session_start();

        if(isset($_SESSION["username"])){
            $username=$_SESSION["username"];

            $id=$this->request->param("id");

            if($id !=""){
                $device_line=Db::name("it")->where("id",$id)->find();
            }else{
                $device_line["id"]="";
                $device_line["leibie"]="";
                $device_line["user"]="";
                $device_line["department"]="";
                $device_line["orginalDepartment"]="";
                $device_line["ytMac"]="";
                $device_line["wxMac"]="";
                $device_line["leixing"]="";
                $device_line["brand"]="";
                $device_line["xinghao"]="";
                $device_line["year"]="";
                $device_line["system2"]="";
                $device_line["cpu"]="";
                $device_line["ram"]="";
                $device_line["hardpan"]="";
                $device_line["barcode"]="";
                $device_line["mouse"]="";
                $device_line["power"]="";
                $device_line["bag"]="";
                $device_line["note"]="";
                $device_line["position"]="";
            }
            
            $this->assign('title','新增设备');
            $this->assign('username',$username);
            $this->assign('device_line',$device_line);
        
            return $this->fetch();
        }else{
            return $this->redirect('/index.php/Index/Login/login');
        }
    }

    public function add_deviceHandle(){
        session_start();

        $username=$_SESSION["username"];

        $id=$this->request->param('id');
        $leibie=$this->request->param('leibie');
        $user=$this->request->param('user');
        $department=$this->request->param('department');
        $orginalDepartment=$this->request->param('orginalDepartment');
        $ytMac=$this->request->param('ytMac');
        $wxMac=$this->request->param('wxMac');
        $leixing=$this->request->param('leixing');
        $brand=$this->request->param('brand');
        $xinghao=$this->request->param('xinghao');
        $year=$this->request->param('year');
        $system=$this->request->param('system');
        $cpu=$this->request->param('cpu');
        $ram=$this->request->param('ram');
        $hardpan=$this->request->param('hardpan');
        $barcode=$this->request->param('barcode');
        $mouse=$this->request->param('mouse');
        $power=$this->request->param('power');
        $bag=$this->request->param('bag');
        $note=$this->request->param('note');
        $position=$this->request->param('position');
            
        if($id==""){

            $sqlstr=DB::name("it")->field("max(id)")->find();
        
            $maxID=$sqlstr["max(id)"];
            
            if($maxID==""){
                $maxID=0;
            }
    
            $sqlstr1=DB::query("insert into it values('$maxID'+1,'$leibie','$user','$department','$orginalDepartment','$ytMac','$wxMac','$leixing','$brand','$xinghao','$year','$system','$cpu','$ram','$hardpan','$barcode','$position','$mouse','$power','$bag','$note')");
    
        }else{
    
            $sqlstr1=DB::query("update it set leibie='$leibie',department='$department',user='$user',orginalDepartment='$orginalDepartment',ytMac='$ytMac',wxMac='$wxMac',leixing='$leixing',brand='$brand',xinghao='$xinghao',year='$year',system2='$system',".
                        "cpu='$cpu',ram='$ram',hardpan='$hardpan',barcode='$barcode',position='$position',mouse='$mouse',power='$power',bag='$bag',note='$note' where id='$id'");
        }

        return $this->redirect('/index.php/Index/device/device_list.html');
    }

    public function download_deviceHandle(){
        $conn=mysqli_connect("localhost","root","root","yzl_database") or die("连接数据库服务器失败！".mysqli_error());
        mysqli_query($conn,"set names utf8");

        error_reporting(E_ALL || ~E_NOTICE);
        session_start();
    
        $username=$_SESSION['username'];
    
        
        $sqlstr2="select * from it";
        $result=mysqli_query($conn,$sqlstr2);
    
        $data=array();
    
        while($myrow=mysqli_fetch_row($result)){
            $data[]=str_replace("\t",'',$myrow);
        }
    
        
        foreach($data as $key=>$value){
            foreach($value as $keys=>$values){
    
            }
        }
    
        $header=array('类别','用户','部门','归属部门','以太网MAC','无线网MAC','类型','品牌','型号','购买年份','操作系统','CPU','内存','硬盘','序列号','位置','鼠标','电源','包','备注');
    
            
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
    
        $list2=range(1,22);
    
        createtable($data,'员工电脑设备',$header,$list2);
    
    
    
    }
}