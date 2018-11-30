<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/11/21
 * Time: 11:03
 */
namespace app\index\controller;

use app\common\controller\Base;
use think\Request;

class Error extends Base {
    public function index(Request $request){
        $isAjax = $request->isAjax();
        $this->cyasync(['msg' => '操作错误','status' => -1,'url' => '/'],$isAjax);
    }
}