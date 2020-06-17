<?php
namespace app\index\controller;
use think\Controller;
use think\Db;

class Center extends Controller
{
    public function my_center(){
        session_start();
        
        if(isset($_SESSION["username"])){

            $username=$_SESSION["username"];

        
            $user=Db::name("user_form")->field(["username","department","level","phone","email","nickname"])->where("username",$username)->find();

            $this->assign('username',$username);
            $this->assign('user',$user);
            $this->assign('title',"个人中心");

            return $this->fetch();
        }else{
            return $this->redirect('/index.php/Index/Login/login');
        }
    }
}