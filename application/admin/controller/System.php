<?php
namespace app\admin\controller;
use think\Controller;
use think\Db;

class System extends Controller
{

    public function system_log(){
        session_start();

        if(isset($_SESSION["username"])){

            $username=$_SESSION["username"];

            $fileName="log.txt";
                    
            if(file_exists('log.txt')){
                
                $f_open=fopen($fileName,"r");

                while($str=fgets($f_open,255)){

                    $chr=explode(",",$str);
                }

                fclose($f_open);
            }else{
                $chr="";
            }


            $this->assign("title","日志管理");
            $this->assign("username",$username);
            $this->assign("chr",$chr);

            return $this->fetch();

        }else{
            return $this->redirect('/index.php/Index/Login/login');
        }
    }

    public function system_backup(){
        
        session_start();

        if(isset($_SESSION["username"])){

            $username=$_SESSION["username"];

            date_default_timezone_set("Asia/Shanghai");
            $date1=date('Ymd', time());  

            $name=$date1.".sql";

            function show_file(){
                $folder_name=getcwd()."/file/backup_data/";
                $d_open=opendir($folder_name);
                $num=0;
                while($file=readdir($d_open)){
                    $filename[$num]=$file;
                    $num++;
                }
                closedir($d_open);
                return $filename;
            }

            $filename=show_file();


            $this->assign("title","系统管理");
            $this->assign("username",$username);
            $this->assign("name",$name);
            $this->assign("filename",$filename);
            

            return $this->fetch();
            
        }else{
            return $this->redirect('/index.php/Index/Login/login');
        }
    }

    public function system_backupHandle($name){

        if($_GET['option']==0){
            $mysqlstr="E:\phpstudy_pro\Extensions\MySQL5.7.26\bin\mysqldump -u root -proot --databases yzl_database > ".getcwd()."/file/backup_data/".$name;
            exec($mysqlstr);
            
            $this->success('数据库备份成功！','/index.php/Admin/System/system_backup.html');

        }elseif($_GET['option']==1){
            $mysqlstr="mysql -uroot -proot yzl_database < ".getcwd()."/file/backup_data/".$name;
            exec($mysqlstr);

            $this->success('数据库恢复成功！','/index.php/Admin/System/system_backup.html');
        }elseif($_GET['option']==2){
            function show_file(){
                $folder_name=getcwd()."/file/backup_data/";
                $d_open=opendir($folder_name);
                $num=0;
                while($file=readdir($d_open)){
                    $filename[$num]=$file;
                    $num++;
                }
                closedir($d_open);
                return $filename;
            }

            $filename=show_file();

            for($i=2;$i<sizeof($filename);$i++){
                unlink(getcwd()."/file/backup_data/".$filename[$i]);
            }

            $this->success('数据库备份删除成功！','/index.php/Admin/System/system_backup.html');
        }
    }
}