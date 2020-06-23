<?php
namespace app\index\controller;
use think\Controller;
use think\Db;

class Common extends Controller
{
    public function leftBar()
    {

        return $this->fetch();
    }

    public function header()
    {
        session_start();
        $username=$_SESSION["username"];

        $img=DB::name("user_form")->field("headImg")->where("username",$username)->find();


        $this->assign("img",$img["headImg"]);
        $this->assign("username",$username);

        return $this->fetch();
    }

}
