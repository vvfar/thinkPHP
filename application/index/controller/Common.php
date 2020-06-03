<?php
namespace app\index\controller;
use think\Controller;

class Common extends Controller
{
    public function leftBar()
    {

        return $this->fetch();
    }

    public function header()
    {
       

        return $this->fetch();
    }

}
