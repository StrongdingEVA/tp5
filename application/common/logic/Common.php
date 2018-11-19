<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/11/15
 * Time: 16:53
 */

namespace app\common\logic;

class Common {
    public function getNav():array {
        $map = array(
            'condition' => array(
                array('where' => 'where','field' => 'status','opt' => '=','value' => 1)
            ),
            'order' => 'sort asc'
        );
        return ['nav' => SM('Nav/getListRows',$map)];
    }

    public function uLogin($request){
        $userName = $request['username'];
        $pwd = $request['pwd'];
        if(!$userName){
            return [['msg' => '用户名不能为空'],0];
        }
        $uInfo = SM('User/getInfoByLogin',$userName);
        print_r($uInfo);exit;
        exit;
    }
}