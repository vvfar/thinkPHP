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

    public function my_centerHandle(){
        session_start();

        $username=$_SESSION['username'];

        $option=$_GET["option"];

        if($option == 1){
            //修改基本信息

            if(isset($_POST['nickname'])){
                $nickname=$_POST['nickname'];
                $phone="";
                $email="";
            }elseif(isset($_POST['phone'])){
                $phone=$_POST['phone'];
                $email="";
                $nickname="";
            }elseif(isset($_POST['email'])){
                $email=$_POST['email'];
                $phone="";
                $nickname="";
            }
        
            if($nickname !=""){
                $sqlstr1=DB::query("update user_form set nickname='$nickname' where username='$username'");
            }elseif($phone !=""){
                $sqlstr1=DB::query("update user_form set phone='$phone' where username='$username'");
            }elseif($email !=""){
                $sqlstr1=DB::query("update user_form set email='$email' where username='$username'");
            }

            return $this->redirect('/index.php/Index/center/my_center.html');

        }elseif($option ==2){
            //修改密码

            $new_pwd1=$_POST["newPwd1"];
            
            $sqlstr1=DB::query("update user_form set password='$new_pwd1' where username='$username'");
            
            return $this->redirect('/index.php/Index/Login/logoutHandle');
        
        
        }elseif($option ==3){
            //上传头像

            if(!empty($_FILES['upfile']['name'])){
                $fileinfo=$_FILES['upfile'];
                if($fileinfo['size']<2097152 && $fileinfo['size']>0){
                    $path=getcwd()."/file/user_icon/".$_FILES["upfile"]["name"];
                    move_uploaded_file($fileinfo['tmp_name'],$path);
                    
                    $fileName=$_FILES['upfile']['name'];

                    $sqlstr1=DB::query("update user_form set headImg='$fileName' where username='$username'");

                    return $this->redirect('/index.php/Index/center/my_center.html');
                }else{
                    $this->error('头像上传失败！','/index.php/Index/center/my_center.html');
                }
            }else{
                $this->error('头像上传失败！','/index.php/Index/center/my_center.html');
            }
        }
    }
}