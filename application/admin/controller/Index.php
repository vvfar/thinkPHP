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

            //分页代码
            if(!isset($_GET["page"]) || !is_numeric($_GET["page"])){
                $page=1;
            }else{
                $page=intval($_GET["page"]);
            }

            $pagesize=15;

            $sqlstr1=Db::query("select count(*) as total from user_form");

            $total=$sqlstr1[0]["total"];

            if($total%$pagesize==0){
                $pagecount=intval($total/$pagesize);
            }else{
                $pagecount=ceil($total/$pagesize);
            }

            $users=Db::query("select id,username,department,level,newLevel from user_form limit ".($page-1)*$pagesize.",$pagesize");

            $this->assign("title","后台管理");
            $this->assign("username",$username);
            $this->assign("users",$users);
            $this->assign('total',$total);
            $this->assign('pagecount',$pagecount);
            $this->assign('page',$page);
            $this->assign('pagesize',15);

            return $this->fetch();


        }else{
            return $this->redirect('/index.php/Index/Login/login');
        }

    }
}