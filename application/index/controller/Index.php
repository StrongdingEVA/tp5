<?php
namespace app\index\controller;

use app\common\Controller\Base;
use think\Request;

class Index extends Base
{
    public function index(Request $request)
    {
        return $this->cyback();
    }

    //异步登录
    public function login(Request $request){
        $res = SL('User/uLogin',$request);
        $this->cyasync($res,true);
    }

    //异步注册
    public function uReg(Request $request){
        $res = SL('User/reg',$request);
        return $this->cyasync($res,true);
    }

    public function show(Request $request){
        $res = SL('Index/readArticle',$request);
        return $this->cyback($res,'show');
    }
}
