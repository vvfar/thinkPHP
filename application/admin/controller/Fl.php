<?php
namespace app\admin\controller;
use think\Controller;
use think\Db;

class Fl extends Controller
{

    public function fl_list(){
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

            $sqlstr="select count(*) as total from fl where not fl_name like '%(赠)%' ";

            if($information != ""){
                $sqlstr= $sqlstr."and fl_name like '%$information%'";
            }

            $sqlstr1=DB::query($sqlstr);
            
            $total=$sqlstr1[0]["total"];

            if($total%$pagesize==0){
                $pagecount=intval($total/$pagesize);
            }else{
                $pagecount=ceil($total/$pagesize);
            }

            $sqlstr="select * from fl where not fl_name like '%(赠)%' ";

            if($information != ""){
                $sqlstr= $sqlstr."and fl_name like '%$information%'";
            }

            $sqlstr= $sqlstr." order by fl_name asc limit ".($page-1)*$pagesize.",$pagesize";

            $fls=DB::query($sqlstr);
            
            $data=[
                "title" => "用户管理",
                "username" => $username,
                "fls" => $fls,
                'total' => $total,
                'pagecount' => $pagecount,
                'page' => $page,
                'pagesize' => 15,
                'information' => $information,
            ];

            return $this->fetch('',$data);
            
        }else{
            return $this->redirect('/index.php/Index/Login/login');
        }

    }

    public function fl_add(){
        $fl_name=trim($_POST['fl_name']);
        $fl_price=$_POST['fl_price'];

        $fl_dups=DB::name("fl")->field("count(*)")->where("fl_name",$fl_name)->find();
        $fl_dup=$fl_dups["count(*)"];

        if($fl_dup==0){

            $maxIDs=DB::name("fl")->field("max(id)")->find();
            $maxID=$maxIDs["max(id)"];
        
            if($maxID==""){
                $maxID=0;
            }
                
            $sqlstr3=DB::query("insert into fl values('$maxID'+1,'$fl_name','$fl_price')");

            $fl_name=$fl_name."(赠)";
            $sqlstr4=DB::query("insert into fl values('$maxID'+2,'$fl_name','$fl_price')");
        }

        return $this->redirect('/index.php/Admin/Fl/fl_list.html');
    }

    public function fl_edit($id){

        session_start();

        if(isset($_SESSION["username"])){

            $username=$_SESSION["username"];

            $fl_line=DB::name("fl")->where("id",$id)->find();

            $this->assign("title","用户管理");
            $this->assign("username",$username);
            $this->assign("fl_line",$fl_line);

            return $this->fetch();

        }else{
            return $this->redirect('/index.php/Index/Login/login');
        }
        
    
    }

    public function fl_editHandle(){

        $id=$_POST["id"];
        $fl_name=$_POST['fl_name'];
        $fl_price=$_POST['fl_price'];

        $sqlstr1=DB::query("update fl set fl_name='$fl_name',fl_price='$fl_price' where id='$id'");
    
        return $this->redirect('/index.php/Admin/Fl/fl_list.html');

    }

    public function fl_del($id){

        $sqlstr1=DB::name("fl")->field("fl_name")->where("id",$id)->find();

        $fl_name=$sqlstr1["fl_name"]."(赠)";

        $sqlstr2=DB::name("fl")->where("fl_name",$fl_name)->delete();
        $sqlstr3=DB::name("fl")->where("id",$id)->delete();

        return $this->redirect('/index.php/Admin/Fl/fl_list.html');

    }
}