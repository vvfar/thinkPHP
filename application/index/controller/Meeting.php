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

            $meetings=Db::name("meeting")->where("date",$date)->where("status","已审核")->select();
            
            for($i=0;$i<sizeof($meetings);$i++){
                $meetings[$i]["room"]=$meetings[$i]["room"]-1;
            }

            $data=[
                'title' => '会议室概况',
                'username' => $username,
                'newLevel' => $newLevel,
                'department' => $department,
                'date' => $date,
                'meetings' => $meetings
            ];
        
            return $this->fetch('',$data);
        }else{
            return $this->redirect('/index.php/Index/Login/login');
        }
    }

    public function add_meeting(){
        
        session_start();

        $method=$this->request->method();
        $room=$this->request->param("room");

        if(isset($_SESSION["username"])){

            $username=$_SESSION["username"];

            if($method == "POST"){
                $title=$this->request->param("title");
                $dateTime=$this->request->param("dateTime");
                $startTime=$this->request->param("startTime");
                $endTime=$this->request->param("endTime");
                $chooseRoom=$this->request->param("chooseRoom");
                $roomResource=$this->request->param("roomResource");
                $apply=$this->request->param("apply");
                $department=$this->request->param("department");
                $people=$this->request->param("people");
        
                //$roomResource=implode(',',$roomResource);

                $result=DB::table("meeting")->insert(
                    [
                        'title' => $title,
                        'department' => $department,
                        'dateTime' => $dateTime,
                        'startTime' => $startTime,
                        'endTime' => $endTime,
                        'chooseRoom' => $chooseRoom,
                        'roomResource' => $roomResource,
                        'apply' => $apply,
                        'status' => '已审核',
                        'people' => '$people'
                    ]
                );

                if($result==1){
                    return $this->success('提交成功！','/index.php/Index/meeting/view_meeting.html','',1);
                }else{
                    return $this->error('提交失败！','/index.php/Index/contract/add_meeting.html','',1);
                }

                return $this->redirect();

            }else{
                $data=[
                    'room' => $room,
                    'username' => $username,
                    'title' => '新增会议'
                ];
    
                return $this->fetch('',$data);
            }

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
            
            $data=[
                'username' => $username,
                'meeting_line' => $meeting_line
            ];

            return $this->fetch('',$data);

        }else{
            return $this->redirect('/index.php/Index/Login/login');
        }
    }
}

