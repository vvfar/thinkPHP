<?php
namespace app\index\controller;
use think\Controller;

class Login extends Controller
{
    public function login(){

        session_start();
        session_unset();
        session_destroy();

        $this->assign('title','用户登录');

        return $this->fetch();
    }

    public function loginHandle(){
        $username=$this->request->post("username");
        $password=$this->request->post("password");

        $pwd=\think\Db::name("user_form")->field("password")->where("username",$username)->select();

        $pwd=$pwd[0]["password"];

        if(trim($password)==trim($pwd)){
            session_start();
            $_SESSION["username"]=$username;
            $_SESSION["password"]=$pwd;

            $this->redirect('/');
        }else{
            $this->error('登录失败！','/index.php/Index/Login/login.html');
        }
    }

    public function logoutHandle(){
        session_start();
        session_unset();
        session_destroy();

        $this->redirect('/index.php/Index/Login/login.html');
    }
}