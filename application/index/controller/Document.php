<?php
namespace app\index\controller;
use think\Controller;
use think\Db;

class Document extends Controller
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

            $pagesize=15;

            $sqlstr1=Db::name("news")->field("count(*)")->select();

            $total=$sqlstr1[0]["count(*)"];

            if($total%$pagesize==0){
                $pagecount=intval($total/$pagesize);
            }else{
                $pagecount=ceil($total/$pagesize);
            }

            $news=Db::name("news")->select();

            $data=[
                'title' => '公告列表',
                'username' => $username,
                'news' => $news,
                "total" => $total,
                'pagecount' => $pagecount,
                'pagesize' => 15,
                'page' => $page
            ];

            return $this->fetch('',$data);
        
        }else{
            return $this->redirect('/index.php/Index/Login/login');
        }
    }

    public function news_line($id){
        session_start();
        
        if(isset($_SESSION["username"])){

            $username=$_SESSION["username"];

            $news_line=Db::name("news")->where("id",$id)->find();

            $data=[
                'title' => '公告详情',
                'username' => $username,
                'news_line' => $news_line
            ];

            return $this->fetch('',$data);
        
        }else{
            return $this->redirect('/index.php/Index/Login/login');
        }
    }

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

            $pagesize=15;

            $sqlstr1=Db::name("files")->field("count(*)")->select();

            $total=$sqlstr1[0]["count(*)"];

            if($total%$pagesize==0){
                $pagecount=intval($total/$pagesize);
            }else{
                $pagecount=ceil($total/$pagesize);
            }

            $files=Db::name("files")->select();

            $data=[
                'title','文件列表',
                'username' => $username,
                'files' => $files,
                'total',$total,
                'pagecount' => $pagecount,
                'pagesize' => $pagesize,
                'page' => $page
            ];

            return $this->fetch('',$data);
        
        }else{
            return $this->redirect('/index.php/Index/Login/login');
        }
    }
}