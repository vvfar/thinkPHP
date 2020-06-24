<?php
namespace app\admin\controller;
use think\Controller;
use think\Db;

class News extends Controller
{

    public function news_list(){
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

            $sqlstr1=DB::query("select count(*) as total from news");
            
            $total=$sqlstr1[0]["total"];

            if($total%$pagesize==0){
                $pagecount=intval($total/$pagesize);
            }else{
                $pagecount=ceil($total/$pagesize);
            }

            $news=DB::query("select * from news  limit ".($page-1)*$pagesize.",$pagesize");

            $this->assign("title","公告管理");
            $this->assign("username",$username);
            $this->assign("news",$news);
            $this->assign('total',$total);
            $this->assign('pagecount',$pagecount);
            $this->assign('page',$page);
            $this->assign('pagesize',15);

            return $this->fetch();

        }else{
            return $this->redirect('/index.php/Index/Login/login');
        }

    }

    public function news_add(){
        session_start();

        if(isset($_SESSION["username"])){

            $username=$_SESSION["username"];

            $id=$this->request->param("id");

            if($id != ""){
                $news_line=DB::name("news")->where("id",$id)->find();
            
            }else{
                $news_line["id"]="";
                $news_line["title"]="";
                $news_line["content"]="";
            }


            $this->assign("title","编辑公告");
            $this->assign("username",$username);
            $this->assign("news_line",$news_line);

            return $this->fetch();

        }else{
            return $this->redirect('/index.php/Index/Login/login');
        }
    }

    public function news_addHandle(){

        session_start();
        $person=$_SESSION["username"];

        $id=$this->request->param("id");
        $content=$this->request->param("content");
        $content=$this->request->param("content");
        $newsType=$this->request->param("newsType");
        $title=$this->request->param("title");

        $time=date('Y-m-d');

        $maxIDs=DB::name("news")->field("max(id)")->find();
        $maxID=$maxIDs["max(id)"];
    
        if($maxID==""){
            $maxID=0;
        }
    
        if($id==""){
            $sqlstr2=DB::query("insert into news values('$maxID'+1,'$title','$time','$person','$content','$newsType')");
        }else{
            $sqlstr2=DB::query("update news set title='$title',content='$content',newsType='$newsType' where id='$id'");
        }

        return $this->redirect('/index.php/Admin/News/news_list.html');

    }

    public function news_del($id){
        session_start();

        $username=$_SESSION["username"];

        $sqlstr=DB::name("news")->where("id",$id)->delete();

        return $this->redirect('/index.php/Admin/News/news_list.html');

    }
}