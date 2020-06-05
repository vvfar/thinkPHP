<?php
namespace app\index\controller;
use think\Controller;

class Sx extends Controller
{
    public function write_sx(){
        session_start();

        if(isset($_SESSION["username"])){
            $username=$_SESSION["username"];

            $this->assign('title','新增授信');
            $this->assign('username',$username);

            return $this->fetch();
        }else{
            return $this->redirect('/index.php/Index/Login/login');
        }
    }

    public function dsh_sx(){
        session_start();
        
        if(isset($_SESSION["username"])){
            $username=$_SESSION["username"];

            return $this->fetch();
        }else{
            return $this->redirect('/index.php/Index/Login/login');
        }
    }

    public function dhk_sx(){
        session_start();

        if(isset($_SESSION["username"])){
            $username=$_SESSION["username"];

            return $this->fetch();
        }else{
            return $this->redirect('/index.php/Index/Login/login');
        }
    }

    public function ywc_sx(){
        session_start();

        if(isset($_SESSION["username"])){
            $username=$_SESSION["username"];

            return $this->fetch();
        }else{
            return $this->redirect('/index.php/Index/Login/login');
        }
    }

    public function djLoad(){
        session_start();

        if(isset($_SESSION["username"])){
            $username=$_SESSION["username"];

            return $this->fetch();
        }else{
            return $this->redirect('/index.php/Index/Login/login');
        }
    }

    
}