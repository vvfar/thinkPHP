<?php
namespace app\index\controller;
use think\Controller;
use think\Request;

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
            $companyInfo_all=\think\Db::name('client')->field('id,clientName,storeName,department,staff,telephone,address')->where('clientName','like','%'.$companyName.'%')->select();
            $companyInfo=\think\Db::name('client')->field('id,clientName,storeName,department,staff,telephone,address')->order(['id'=>'desc'])->where('clientName','like','%'.$companyName.'%')->limit(($page-1)*$pagesize,$page*$pagesize)->select();
        }else if($optionID=="2"){
            $companyInfo_all=\think\Db::name('client')->field('id,clientName,storeName,department,staff,telephone,address')->where('storeName','like','%'.$storeName.'%')->select();
            $companyInfo=\think\Db::name('client')->field('id,clientName,storeName,department,staff,telephone,address')->order(['id'=>'desc'])->where('storeName','like','%'.$storeName.'%')->limit(($page-1)*$pagesize,$page*$pagesize)->select();
        }else if($optionID=="3"){
            $companyInfo_all=\think\Db::name('client')->field('id,clientName,storeName,department,staff,telephone,address')->where('department','like','%'.$department.'%')->select();
            $companyInfo=\think\Db::name('client')->field('id,clientName,storeName,department,staff,telephone,address')->order(['id'=>'desc'])->where('department','like','%'.$department.'%')->limit(($page-1)*$pagesize,$page*$pagesize)->select();
        }else if($optionID=="4"){
            $companyInfo_all=\think\Db::name('client')->field('id,clientName,storeName,department,staff,telephone,address')->where('staff','like','%'.$staff.'%')->select();
            $companyInfo=\think\Db::name('client')->field('id,clientName,storeName,department,staff,telephone,address')->order(['id'=>'desc'])->where('staff','like','%'.$staff.'%')->limit(($page-1)*$pagesize,$page*$pagesize)->select();
        }else{
            $companyInfo_all=\think\Db::name('client')->field('id,clientName,storeName,department,staff,telephone,address')->select();
            $companyInfo=\think\Db::name('client')->field('id,clientName,storeName,department,staff,telephone,address')->order(['id'=>'desc'])->limit(($page-1)*$pagesize,$page*$pagesize)->select();
        }
        
        $headers=array("序号","客户名称","店铺名称","事业部","绑定KA","联系电话","地址");

        $total=sizeof($companyInfo_all);
        $pagecount=(int)($total/$pagesize+1);

        if($total % $pagesize ==0){
            $pagecount=$pagecount-1;
        }
        
        $this->assign('username',$username);
        $this->assign('companyInfos',$companyInfo);
        $this->assign('page',$page);
        $this->assign('pagecount',$pagecount);
        $this->assign('pagesize',$pagesize);
        $this->assign('headers',$headers);
        $this->assign('total',$total);
        $this->assign('optionID',$optionID);
        $this->assign('companyName',$companyName);
        $this->assign('storeName',$storeName);
        $this->assign('department',$department);
        $this->assign('staff',$staff);

        return $this->fetch();
    }

    public function addClientPage(){
        session_start();
        $username=$_SESSION["username"];
        $departments=\think\Db::name('user_form')->field('department')->where('username',$username)->limit(1)->select();

        $this->assign('username',$username);
        $this->assign('departments',$departments);

        return $this->fetch();
    }

    public function addClientHandle(){
        session_start();
        $username=$_SESSION["username"];

        
        $clientName=$this->request->param('client');
        $storeName=$this->request->param('storeName');
        $department=$this->request->param('department');
        $staff=$this->request->param('staff');
        $telephone=$this->request->param('telephone');
        $address=$this->request->param('address');

        \think\Db::table('client')->insert(
            [
                'clientName'=>$clientName,
                'storeName'=>$storeName,
                'department'=>$department,
                'staff'=>$staff,
                'telephone'=>$telephone,
                'address'=>$address,
            ]
        );

        $this->redirect('/index.php/Index/client/clientList.html');

    }
}
?>