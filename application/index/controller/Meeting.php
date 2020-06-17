<?php
namespace app\index\controller;
use think\Controller;
use think\Db;

class Meeting extends Controller
{
    public function view_meeting(){
        session_start();

        if(isset($_SESSION["username"])){

            $username=$_SESSION["username"];
        
            $sqlstr1=Db::name("user_form")->field(["department","newLevel"])->where("username",$username)->find();

            $department=$sqlstr1["department"];
            $newLevel=$sqlstr1["newLevel"];

            if(!isset($_GET["date"])){
                date_default_timezone_set("Asia/Shanghai");
                $date=date('Y-m-d', time());
            }else{
                $date=$_GET["date"];
            }

            $meetings=Db::query("select * from meeting where date='$date' and status='已审核'");


            $this->assign('title','会议室概况');
            $this->assign('username',$username);
            $this->assign('newLevel',$newLevel);
            $this->assign('department',$department);
            $this->assign('date',$date);
            $this->assign('meetings',$meetings);
        
            return $this->fetch();
        }else{
            return $this->redirect('/index.php/Index/Login/login');
        }
    }

    public function add_meeting(){
        session_start();

        $room=$this->request->param("room");

        if(isset($_SESSION["username"])){

            $username=$_SESSION["username"];

            $this->assign('room',$room);
            $this->assign('username',$username);
            $this->assign('title',"新增会议");

            return $this->fetch();

        }else{
            return $this->redirect('/index.php/Index/Login/login');
        }
    }

    public function meeting_line($id){
        session_start();

        if(isset($_SESSION["username"])){

            $username=$_SESSION["username"];

            $meeting_line=Db::name("meeting")->where("id",$id)->find();

            $meeting_line["dateTime"]=$meeting_line["date"]." ".$meeting_line["startTime"]."-".$meeting_line["endTime"];
            
            $this->assign('username',$username);
            $this->assign('meeting_line',$meeting_line);

            return $this->fetch();

        }else{
            return $this->redirect('/index.php/Index/Login/login');
        }
    }
}

