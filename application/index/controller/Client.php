<?php
namespace app\index\controller;
use think\Controller;
use think\Request;
use think\Db;

class Client extends Controller{

    public function clientList(){
    
        session_start();
        $username=$_SESSION["username"];

        if(!isset($_GET["page"]) || !is_numeric($_GET["page"])){
            $page=1;
        }else{
            $page=intval($_GET["page"]);
        }

        $optionID=$this->request->param('optionID');
        $companyName=$this->request->param('companyName');
        $storeName=$this->request->param('storeName');
        $department=$this->request->param('department');
        $staff=$this->request->param('staff');

        $pagesize=15;

        if($optionID=="1"){
            $companyInfo_all=Db::name('client')->field('id,clientName,storeName,department,staff,telephone,address')->where('clientName','like','%'.$companyName.'%')->select();
            $companyInfo=Db::name('client')->field('id,clientName,storeName,department,staff,telephone,address')->order(['id'=>'desc'])->where('clientName','like','%'.$companyName.'%')->limit(($page-1)*$pagesize,$page*$pagesize)->select();
        }else if($optionID=="2"){
            $companyInfo_all=Db::name('client')->field('id,clientName,storeName,department,staff,telephone,address')->where('storeName','like','%'.$storeName.'%')->select();
            $companyInfo=Db::name('client')->field('id,clientName,storeName,department,staff,telephone,address')->order(['id'=>'desc'])->where('storeName','like','%'.$storeName.'%')->limit(($page-1)*$pagesize,$page*$pagesize)->select();
        }else if($optionID=="3"){
            $companyInfo_all=Db::name('client')->field('id,clientName,storeName,department,staff,telephone,address')->where('department','like','%'.$department.'%')->select();
            $companyInfo=Db::name('client')->field('id,clientName,storeName,department,staff,telephone,address')->order(['id'=>'desc'])->where('department','like','%'.$department.'%')->limit(($page-1)*$pagesize,$page*$pagesize)->select();
        }else if($optionID=="4"){
            $companyInfo_all=Db::name('client')->field('id,clientName,storeName,department,staff,telephone,address')->where('staff','like','%'.$staff.'%')->select();
            $companyInfo=Db::name('client')->field('id,clientName,storeName,department,staff,telephone,address')->order(['id'=>'desc'])->where('staff','like','%'.$staff.'%')->limit(($page-1)*$pagesize,$page*$pagesize)->select();
        }else{
            $companyInfo_all=Db::name('client')->field('id,clientName,storeName,department,staff,telephone,address')->select();
            $companyInfo=Db::name('client')->field('id,clientName,storeName,department,staff,telephone,address')->order(['id'=>'desc'])->limit(($page-1)*$pagesize,$page*$pagesize)->select();
        }
        

        $total=sizeof($companyInfo_all);
        $pagecount=(int)($total/$pagesize+1);

        if($total % $pagesize ==0){
            $pagecount=$pagecount-1;
        }
        
        $data=[
            'username' => $username,
            'companyInfos' => $companyInfo,
            'page' => $page,
            'pagecount' => $pagecount,
            'pagesize' => $pagesize,
            'total' => $total,
            'optionID' => $optionID,
            'companyName' => $companyName,
            'storeName' => $storeName,
            'department' => $department,
            'staff' => $staff
        ];

        return $this->fetch('',$data);
    }

    public function addClient(){
        
        session_start();
        $method=$this->request->method();

        if(isset($_SESSION["username"])){
            if($method=="POST"){
                $username=$_SESSION["username"];
                
                $clientName=$this->request->param('client');
                $storeName=$this->request->param('storeName');
                $department=$this->request->param('department');
                $staff=$this->request->param('staff');
                $telephone=$this->request->param('telephone');
                $address=$this->request->param('address');

                $result=Db::table('client')->insert(
                    [
                        'clientName'=>$clientName,
                        'storeName'=>$storeName,
                        'department'=>$department,
                        'staff'=>$staff,
                        'telephone'=>$telephone,
                        'address'=>$address,
                    ]
                );

                if($result==1){
                    return $this->success('添加成功！','/index.php/Index/client/clientList.html','',1);
                }else{
                    return $this->error('添加失败！','/index.php/Index/client/addClient.html','',1);
                }
                
            }else{

                $username=$_SESSION["username"];

                $departments=Db::name('user_form')->field('department')->where('username',$username)->limit(1)->select();
        
                $this->assign('username',$username);
                $this->assign('departments',$departments);

                return $this->fetch();
            }
        }else{
            return $this->redirect('/index.php/Index/Login/login');
        }
    }
}
?>