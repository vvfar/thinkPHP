<?php
namespace app\admin\controller;
use think\Controller;
use think\Db;

class Process extends Controller
{

    public function process_list(){
        session_start();

        if(isset($_SESSION["username"])){

            $username=$_SESSION["username"];
            
            $process_alls=DB::name("flprogress_all")->where("no",1)->select();

            $this->assign("title","流程管理");
            $this->assign("username",$username);
            $this->assign("process_alls",$process_alls);

            return $this->fetch();
        
        }else{
            return $this->redirect('/index.php/Index/Login/login');
        }
    }

    public function process_fl($id,$department){
        session_start();

        if(isset($_SESSION["username"])){

            $username=$_SESSION["username"];

            $sqlstr1=DB::query("select distinct department,flprogress_id from flprogress where flprogress_id=$id");

            $department=$sqlstr1[0]["department"];
            $flprogress_id=$id;

            $flprogress_all=DB::query("select * from flprogress where flprogress_id=$id order by number");


            //流程框
            $process_data="";

            $sqlstr1=DB::query("select name from flprogress where flprogress_id=$id order by number");
    
            for($i=0;$i<sizeof($sqlstr1);$i++){
                $process_data=$process_data."'".$sqlstr1[$i]["name"]."'".","; 
            }
              
            $process_data=substr($process_data,0,-1);

            $this->assign("title","辅料流程");
            $this->assign("username",$username);
            $this->assign("department",$department);
            $this->assign("flprogress_all",$flprogress_all);
            $this->assign("process_data",$process_data);

            return $this->fetch();

        }else{
            return $this->redirect('/index.php/Index/Login/login');
        }
    }

    public function process_fl_edit($id){
        session_start();

        if(isset($_SESSION["username"])){

            $username=$_SESSION["username"];

            $flprogress_line=DB::name("flprogress")->where("id",$id)->find();


            $this->assign("title","修改辅料流程");
            $this->assign("username",$username);
            $this->assign("flprogress_line",$flprogress_line);


            return $this->fetch();

        }else{
            return $this->redirect('/index.php/Index/Login/login');
        }
    }

    public function process_fl_editHandle($id){

        $id=$this->request->param("id");
        $number=$this->request->param("number");
        $name=$this->request->param("name");
        $sp=$this->request->param("sp");
        $cs=$this->request->param("cs");
        $department=$this->request->param("department");
        $flprocess_id=$this->request->param("flprocess_id");
    
        $sqlstr2=DB::query("update flprogress set number='$number',name='$name',sp='$sp',cs='$cs' where id=$id");

        return $this->redirect('/index.php/Admin/process/process_fl?id='.$flprocess_id.'&department='.$department);
        
    }

    public function process_fl_addHandle($id){

        $id=$this->request->param("id");
        $number=$this->request->param("number");
        $name=$this->request->param("name");
        $sp=$this->request->param("sp");
        $cs=$this->request->param("cs");
        $department=$this->request->param("department");
        $flprogress_id=$this->request->param("flprogress_id");
    
        $maxIDs=DB::name("flprogress")->field("max(id)")->find();
        $maxID=$maxIDs["max(id)"];
    
        if($maxID==""){
            $maxID=0;
        }

        $sqlstr2=DB::query("insert into flprogress values('$maxID'+1,'$number','$name','$sp','$cs','$department','$flprogress_id','1')");

        return $this->redirect('/index.php/Admin/process/process_fl?id='.$flprogress_id.'&department='.$department);
        
    }

    public function process_fl_del(){
        $id=$_GET["id"];
        $number=$_GET["number"];
        $flprogress_id=$_GET["flprogress_id"];
        $department=$_GET["department"];

        $sqlstr2=DB::table("flprogress")->where("id",$id)->delete();

        $sqlstr=DB::name("flprogress")->field("max(number)")->find();
        $maxNumber=$sqlstr["max(number)"];

        if($number < $maxNumber){
            $sqlstr3=DB::name("update flprogress set number=number-1 where number>=$number and flprogress_id='$flprogress_id'");
        }

        return $this->redirect('/index.php/Admin/process/process_fl?id='.$flprogress_id.'&department='.$department);
    }
}