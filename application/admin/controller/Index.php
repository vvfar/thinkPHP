<?php
namespace app\admin\controller;
use think\Controller;
use think\Db;

class Index extends Controller
{

    public function index(){

        session_start();

        if(isset($_SESSION["username"])){
            
            $username=$_SESSION["username"];

            $information=trim($this->request->param("information"));

            //分页代码
            if(!isset($_GET["page"]) || !is_numeric($_GET["page"])){
                $page=1;
            }else{
                $page=intval($_GET["page"]);
            }

            $pagesize=15;

            $sqlstr="select count(*) as total from user_form where 1=1 ";

            if($information != ""){
                $sqlstr= $sqlstr."and username like '%$information%'";
            }


            $sqlstr1=Db::query($sqlstr);

            $total=$sqlstr1[0]["total"];

            if($total%$pagesize==0){
                $pagecount=intval($total/$pagesize);
            }else{
                $pagecount=ceil($total/$pagesize);
            }

            $sqlstr="select id,username,department,level,newLevel from user_form where 1=1 ";

        
            if($information != ""){
                $sqlstr= $sqlstr."and username like '%$information%'";
            }

            $sqlstr= $sqlstr."limit ".($page-1)*$pagesize.",$pagesize";
            
            $users=Db::query($sqlstr);

            $data=[
                "title" => "用户管理",
                "username" => $username,
                "users" => $users,
                'total' => $total,
                'pagecount' => $pagecount,
                'page' => $page,
                'pagesize' => 15,
                'information' => $information
            ];

            return $this->fetch('',$data);
            
        }else{
            return $this->redirect('/index.php/Index/Login/login');
        }
    }

    public function add_user(){
        session_start();

        if(isset($_SESSION["username"])){

            $username=$_SESSION["username"];

            $sqlstr1=Db::name("user_form")->field(["department","newLevel"])->where("username",$username)->select();

            $department=$sqlstr1[0]["department"];
            $newLevel=$sqlstr1[0]["newLevel"];

            $id=$this->request->param("id");

            if($id != ""){
                $user_line=DB::name("user_form")->where("id",$id)->find();

        
            }else{
                $user_line["Id"]="";
                $user_line["username"]="";
                $user_line["department"]="";
                $user_line["level"]="";
                $user_line["phone"]="";
                $user_line["email"]="";
                $user_line["nickname"]="";
                $user_line["newLevel"]="";
            }

            $this->assign("title","新增用户");
            $this->assign("username",$username);
            $this->assign("user_line",$user_line);
            $this->assign("newLevel",$newLevel);

            return $this->fetch();

        }else{
            return $this->redirect('/index.php/Index/Login/login');
        }
    }

    public function add_userHandle(){
        
        $id=$this->request->param("id");
        $username=$this->request->param("username");
        $department=$this->request->param("department");
        $newLevel=$this->request->param("newLevel");
        $level=$this->request->param("level");
        $phone=$this->request->param("phone");
        $email=$this->request->param("email");
        $nickname=$this->request->param("nickname");
    
        $maxIDs=DB::name("user_form")->field("max(id)")->find();
        $maxID=$maxIDs["max(id)"];
    
        if($maxID==""){
            $maxID=0;
        }
    
        if($id==""){
            $sqlstr2=DB::query("insert into user_form values('$maxID'+1,'$username','123456','$department','$level','$phone','$email','$nickname','default_icon.jpg','$newLevel')");
        }else{
            $sqlstr2=DB::query("update user_form set username='$username',department='$department',level='$level',newLevel='$newLevel',phone='$phone',email='$email',nickname='$nickname' where id='$id'");
        }

        return $this->redirect('/index.php/Admin/index.html');
    }

    public function del_user(){
        
        $id=$this->request->param("id");

        $sqlstr=DB::table("user_form")->where("id",$id)->delete();
        
        return $this->redirect('/index.php/Admin/index.html');

    }

    public function reset_pwd(){

        $id=$this->request->param("id");

        $sqlstr=DB::query("update user_form set password='123456' where id='$id'");
        
        return $this->redirect('/index.php/Admin/index.html');

    }
}