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

    public function forget_pwd(){
        
        $this->assign('title','重置密码');

        return $this->fetch();
    }

    public function getPhone(){

        $username=$this->request->get("username");

        $phones=\think\Db::name("user_form")->field("phone")->where("username",$username)->select();
        
        if($phones != []){
            echo $phones[0]["phone"];
        }else{
            echo "";
        }
    }

    public function getYzm(){

        $phone=$this->request->get("phone");

        function send_sms($phone,$message) {
            $appkey = "zlsy66";
            $appcode = "1000";
            $appsecret="440I53";
            $timestamp=time()*1000;
    
            $sign=md5($appkey.$appsecret.$timestamp);
    
            $data=array(
                    "sign" => $sign,
                    'appkey'=>$appkey,
                    'appcode'=>$appcode,
                    'timestamp'=>$timestamp,
                    'sms' =>[ array(
                        'msg'=>$message,
                        'phone'=> $phone,
                        'extend'=>''
                    )]
                );
    
            $url = "http://39.97.4.102:9090/sms/distinct/v1";//此处为短信接口的链接，具体的用法参考短信接口的说明
            $curl = curl_init(); //初始化一个新的会话
            
            $timeout = 15;   
    
            $headers = array('Accept:text/plain;charset=utf-8', 'Content-type:application/json','charset=utf-8'); 
    
            /* 设置验证方式 */
            curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
            
            curl_setopt ($curl, CURLOPT_RETURNTRANSFER, 1);
    
            /* 设置返回结果为流 */
            curl_setopt($curl, CURLOPT_HEADER, 0);
            curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
    
            /* 设置超时时间*/
            curl_setopt ($curl, CURLOPT_CONNECTTIMEOUT, $timeout);
    
            /* 设置通信方式 */
            curl_setopt($curl, CURLOPT_POST, 1);
            curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
    
            curl_setopt ($curl, CURLOPT_URL, $url);        
    
            curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($data));
    
            $res = curl_exec($curl);  //执行会话
    
            curl_close($curl);   // 关闭会话，释放资源。
            
        }
    
        $chars = array("1", "2",  "3", "4", "5", "6", "7", "8", "9" ); 
        $charsLen = count($chars) - 1; 
        shuffle($chars);   
        $yzm = ""; 
        for ($i=0; $i<4; $i++) 
        { 
            $yzm .= $chars[mt_rand(0, $charsLen)]; 
        } 
    
        send_sms($phone,'【上海兆林实业】您的CRM系统的验证码为：'.$yzm);
        
        echo $yzm;
        
        
        session_start();
        
        $_SESSION["yzm"]=$yzm;

    }

    public function resetPwd(){

        session_start();

        $username=$this->request->param("username");
        $newPwd1=$this->request->param("newPwd1");
        $yzm=$this->request->param("yzm");

        if($yzm == $_SESSION["yzm"]){
            $update_Pwd=\think\Db::table('user_form')->where('username',$username)->update(['password'=>$newPwd1]);

            $this->success('密码修改成功，请重新登录！','/index.php/Index/login/login.html');
        }else{
            $this->error('验证码校验失败！','/index.php/Index/login/forget_pwd.html');
        }


    }
}