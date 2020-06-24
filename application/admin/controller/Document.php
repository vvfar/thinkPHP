<?php
namespace app\admin\controller;
use think\Controller;
use think\Db;

class Document extends Controller
{

    public function document_list(){
        session_start();

        if(isset($_SESSION["username"])){

            $username=$_SESSION["username"];

            //分页代码
            if(!isset($_GET["page"]) || !is_numeric($_GET["page"])){
                $page=1;
            }else{
                $page=intval($_GET["page"]);
            }

            $pagesize=10;

            $sqlstr1=DB::query("select count(*) as total from files");
            
            $total=$sqlstr1[0]["total"];

            if($total%$pagesize==0){
                $pagecount=intval($total/$pagesize);
            }else{
                $pagecount=ceil($total/$pagesize);
            }

            $files=DB::query("select * from files  limit ".($page-1)*$pagesize.",$pagesize");

            $this->assign("title","公告管理");
            $this->assign("username",$username);
            $this->assign("files",$files);
            $this->assign('total',$total);
            $this->assign('pagecount',$pagecount);
            $this->assign('page',$page);
            $this->assign('pagesize',15);

            return $this->fetch();
        }else{
            return $this->redirect('/index.php/Index/Login/login');
        }
    }

    public function document_add(){
        session_start();

        if(isset($_SESSION["username"])){

            $username=$_SESSION["username"];

            $this->assign("title","上传文件");
            $this->assign("username",$username);

            return $this->fetch();
        }else{
            return $this->redirect('/index.php/Index/Login/login');
        }
    }

    public function document_addHandle(){
        $title=$_POST['title'];
        $note=$_POST['note'];
    
        if(!empty($_FILES['upfile']['name'])){
            $fileinfo=$_FILES['upfile'];
            if($fileinfo['size']<10240000 && $fileinfo['size']>0){
    
                //iconv防止出现上传中文名乱码
                $path=iconv('utf-8','gb2312',getcwd()."/file/myfile/".$_FILES["upfile"]["name"]);
    
                if(file_exists($path)){
                    $this->error('文件已存在！','/index.php/Admin/Document/document_list.html');
                }else{
                    move_uploaded_file($fileinfo['tmp_name'],$path);
    
                    session_start();
                    $username=$_SESSION["username"];
                    $fileName=$_FILES['upfile']['name'];
                    $time=date('Y-m-d');
    
                    $maxIDs=DB::name("files")->field("max(id)")->find();
                    $maxID=$maxIDs["max(id)"];
            
                    if($maxID==""){
                        $maxID=0;
                    }
    
                    if($note !=""){
                        $sqlstr1=DB::query("insert into files values('$maxID'+1,'$title','$fileName','$username','$time','$note')");
                    }else{
                        $sqlstr1=DB::query("insert into files values('$maxID'+1,'$title','$fileName','$username','$time','')");
                    }
                    
                    return $this->redirect('/index.php/Admin/Document/document_list.html');
                    
                }
    
            }else{
                $this->error('文件太大上传失败！','/index.php/Admin/Document/document_list.html');
            }
        }
    }

    public function document_del($id,$fileName){

        $sqlstr=DB::table("files")->where("id",$id)->delete();

        $path=iconv('utf-8','gb2312',getcwd()."/file/myfile/".$fileName);
        unlink($path);

        return $this->redirect('/index.php/Admin/Document/document_list.html');
    }
}