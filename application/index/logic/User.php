<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/11/14
 * Time: 11:17
 */
namespace app\index\logic;

class User{
    public function uLogin($request){
        $userName = $request->param('username');
        $pwd = $request->param('pwd');
        if(!$userName){
            return ['msg' => '用户名不能为空','status' => -1];
        }
        $uModel = SM('User');
        $res = $uModel->getInfoByLogin($userName);
        if (!$res['status']){
            return ['msg' => $res['msg'],'status' => $res['status']];
        }
        $uInfo = $res['data'];
        if(!$uInfo || empty($uInfo)){
            return ['msg' => '不存在此用户','status' => -1];
        }
        if($uInfo['pwd'] != md5($pwd)){
            return ['msg' => '密码错误','status' => -1];
        }
        $uInfo = array_merge($uInfo,$uModel->getById($uInfo['uid']));
        if(!$uInfo['status']){
            return ['msg' => '该用户被禁止登陆，请联系客服~','status' => -1];
        }
        unset($uInfo['pwd']);
        //登录成功
        $this->setUinfoCookie($uInfo);
//        $expretime = config('LOGIN_TOKEN_EXPRE') ?? 3600 * 2;
//        $prefix = config('LOGIN_COOKIE_PREFIX') ?? '';
//
//        cookie($prefix . 't',$this->getToken($uInfo),$expretime);
        return ['data' => $uInfo,'status' => 1];
    }

    public function uReg($request){
        $userName = $request->param('username');
        $nickname = $request->param('nickname');
        $pwd = $request->param('pwd');
        $pwdRet = $request->param('pwdRet');
        if(!$userName){
            return ['msg' => '用户名不能为空','status' => -1];
        }
        if(!$nickname){
            return ['msg' => '昵称不能为空','status' => -1];
        }
        if(!$pwd){
            return ['msg' => '密码不能为空','status' => -1];
        }
        if($pwd != $pwdRet){
            return ['msg' => '密码和确认密码不一致','status' => -1];
        }
        //判断验证码
        //判断用户名是否被注册过
        $uModel = SM('User');
        $res = $uModel->getInfoByLogin($userName);
        if (!$res['status']){
            return ['msg' => $res['msg'],'status' => $res['status']];
        }
        if($res['data']){
            return ['msg' => '该用户已存在','status' => -1];
        }

        $data = array('nickname' => $nickname,'status' => 1);
        $uModel->startTrans();
        $res1 = $uModel->saveData($data);
        $dataExt = array('username' => $userName,'pwd' => md5($pwd),'uid' => $res1);
        $res2 = $uModel->saveExt($dataExt);
        if($res1 && $res2){
            $uModel->commit();
            //自动登录
            return $this->uLogin($request);
        }
        $uModel->rollback();
        return ['msg' => '注册失败','status' => -1];
    }

    public function getToken($data){
        $str = '';
        foreach ($data as $key => $val){
            $str .= $key . '=' . $val . '&';
        }
        $str .= 'key=' . config('LOGIN_TOKEN_KEY') ?? '';
        return md5($str);
    }

    public function logOut(){
        $prefix = config('LOGIN_COOKIE_PREFIX') ?? '';
        cookie($prefix . 'u',null);
        cookie($prefix . 'cp',null);
        cookie($prefix . 'iv',null);
        cookie($prefix . 'tag',null);
    }

    public function setUinfoCookie($data){
        $temp = json_encode($data);
        $res = aesEncrypt($temp);
        if($res){
            $expretime = config('LOGIN_TOKEN_EXPRE') ?? 3600 * 2;
            $prefix = config('LOGIN_COOKIE_PREFIX') ?? '';
            cookie($prefix . 'u',$res['data'],$expretime);
//            cookie($prefix . 'cp',$res['cipher'],$expretime);
            cookie($prefix . 'iv',$res['iv'],$expretime);
            cookie($prefix . 'tag',$res['tag'],$expretime);
        }
    }
}