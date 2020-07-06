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
            
            //分页代码
            if(!isset($_GET["page"]) || !is_numeric($_GET["page"])){
                $page=1;
            }else{
                $page=intval($_GET["page"]);
            }

            $pagesize=15;

            $sqlstr1=DB::name("flprogress_all")->field("count(*)")->where("no",1)->select();
            $total=$sqlstr1[0]["count(*)"];

            if($total%$pagesize==0){
                $pagecount=intval($total/$pagesize);
            }else{
                $pagecount=ceil($total/$pagesize);
            }

            $process_alls=DB::name("flprogress_all")->limit(($page-1)*$pagesize,$page*$pagesize)->select();

            $jkfss=DB::name("fl_jkfs")->field("name")->select();

            $data=[
                'title' => '流程管理',
                'username' => $username,
                'process_alls' => $process_alls,
                'jkfss' =>  $jkfss,
                'total' => $total,
                'pagecount' => $pagecount,
                'pagesize' => 15,
                'page' => $page
            ];

            return $this->fetch('',$data);
        
        }else{
            return $this->redirect('/index.php/Index/Login/login');
        }
    }

    public function process_list_addHandle(){
        
        $name=$this->request->param("name");
        $department=$this->request->param("department");
        $jkfs=$this->request->param("jkfs");
        $change_date=$this->request->param("change_date");

        $result=DB::table("flprogress_all")->insert([
            "name" => $name,
            "department" =>  $department,
            "no" => 1,
            "jkfs" => $jkfs,
            "change_date" => $change_date,
            "status" => "未生效"
        ]);

        $flprogressId = Db::name('flprogress_all')->getLastInsID();

        $result=DB::table("flprogress")->insert([
            "flprogress_id" => $flprogressId,
        ]);
        
        if($result==1){
            return $this->success('添加成功！','/index.php/Admin/process/process_fl.html?id='.$flprogressId.'&department='.$department.'','',1);
        }else{
            return $this->error('添加失败！','/index.php/Admin/process/process_list.html','',1);
        }
    }

    public function process_list_edit($id){

        session_start();

        if(isset($_SESSION["username"])){

            $username=$_SESSION["username"];
            $method=$this->request->method();

            if($method=="POST"){
                $name=$this->request->param("name");
                $department=$this->request->param("department");
                $jkfs=$this->request->param("jkfs");
                $change_date=$this->request->param("change_date");
                $status=$this->request->param("status");

                $result=DB::table("flprogress_all")->where("id",$id)->update([
                    'name' => $name,
                    'department' => $department,
                    'jkfs' => $jkfs,
                    'change_date' => $change_date,
                    'status' => $status
                ]);

                if($result==1){
                    return $this->success('修改成功！','/index.php/Admin/process/process_list.html','',1);
                }else{
                    return $this->error('修改失败！','/index.php/Admin/process/process_list_edit?id='.$id,'',1);
                }

            }else{
                $process_list_line=DB::name("flprogress_all")->where("id",$id)->find();
                $jkfss=DB::name("fl_jkfs")->field("name")->select();

                $data=[
                    'title' => '流程编辑',
                    'username' => $username,
                    'process_list_line' => $process_list_line,
                    'jkfss' => $jkfss
                ];

                return $this->fetch('',$data);
            }

        }else{
            return $this->redirect('/index.php/Index/Login/login');
        }
    }

    public function process_list_del($id){
        $result=DB::table("flprogress_all")->where("id",$id)->delete();

        if($result==1){
            return $this->success('删除成功！','/index.php/Admin/process/process_list.html','',1);
        }else{
            return $this->error('删除失败！','/index.php/Admin/process/process_list.html','',1);
        }
    }

    public function process_fl($id,$department){
        session_start();

        if(isset($_SESSION["username"])){

            $username=$_SESSION["username"];

            $fl_progress_des=DB::name("flprogress_all")->where("id",$id)->find();

            $flprogress_all=DB::name("flprogress")->where("flprogress_id",$id)->order(["number" => 'asc'])->select();

            //流程框
            $process_data="";

            $sqlstr1=DB::name("flprogress")->field("name")->where("flprogress_id",$id)->order(["number" => 'asc'])->select();
    
            for($i=0;$i<sizeof($sqlstr1);$i++){
                $process_data=$process_data."'".$sqlstr1[$i]["name"]."'".","; 
            }
            
            $process_data=substr($process_data,0,-1);

            $data=[
                "title" => "辅料流程",
                "username" => $username,
                "department" => $department,
                "flprogress_all" => $flprogress_all,
                "fl_progress_des" => $fl_progress_des,
                "process_data" => $process_data
            ];

            return $this->fetch('',$data);

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