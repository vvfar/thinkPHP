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
}