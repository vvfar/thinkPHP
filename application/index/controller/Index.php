<?php
namespace app\index\controller;
use think\Controller;

class Index extends Controller
{

    public function index()
    {
        session_start();
        $username=$_SESSION["username"];

        $department=\think\Db::query("select department from user_form where username='$username'");
        $newLevel=\think\Db::query("select newLevel from user_form where username='$username'");

        $news_dt=\think\Db::name('news')->field('id,title,time')->limit(4)->where('newsType','公司动态')->select();
        $news_ggl=\think\Db::name('news')->field('id,title,time')->limit(10)->where('newsType','公司公告')->select();
        $files=\think\Db::name('files')->field('id,title,fileName,createUser,time')->limit(7)->select();

        $this->assign('username',$username);
        $this->assign('news_dt',$news_dt);
        $this->assign('files',$files);
        $this->assign('news_ggl',$news_ggl);

        return $this->fetch();
    }

    public function student()
    {
        //$student=\think\Db::query('select name from student');
        $student=\think\Db::name('student')->field('name')->select();


        //$arr=[];
        //foreach ($student as $v){
        //    $arr[]=$v['name'];
        //}

        //return implode('，',$arr);

        $this->assign('data',$student);
        return $this->fetch();

    }

    public function detail($id,$name,$age=0){
        return 'id:'.$id.',name'.$name.'age:'.$age;
    }
}
