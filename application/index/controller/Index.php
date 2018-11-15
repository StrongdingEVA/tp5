<?php
namespace app\index\controller;

use app\common\Controller\Base;
use think\Request;

class Index extends Base
{
    public function index(Request $request)
    {
        return $this->cyback();//asdfads
    }

    public function ucenter(){
        $l = SL('Index');
        print_r($l);exit;
        $buffer = [1,'err'];
        return $this->reback($buffer,1);
    }
}
