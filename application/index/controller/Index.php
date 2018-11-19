<?php
namespace app\index\controller;

use app\common\Controller\Base;
use app\index\model\Test;
use think\Request;

class Index extends Base
{
    public function index(Request $request)
    {
        $res = SL('Common/uLogin',$_REQUEST);
//        print_r(111);exit;
        return $this->cyback($res);
    }

    public function ucenter(){
        $l = SL('Index');
        print_r($l);exit;
        $buffer = [1,'err'];
        return $this->reback($buffer,1);
    }
}
