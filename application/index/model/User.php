<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/11/14
 * Time: 16:58
 */
namespace app\index\model;

use app\common\model\Basic;
//use think\Model;

class User extends Basic {
    protected $cachePrex = 'user_'; //缓存前缀
    protected $openValidate = false; //是否开启模型自动验证
    protected $openCache = true; //是否开启主键缓存
    protected $cacheKey = 'id'; //主键名
    protected $autoWriteTimestamp = true; //填充时间
    protected $updateTime = false; //不填充update_time

    public function getInfoByLogin(string $username = ''):array {
        if(checkEmail($username)){
            $model = SM('Email');
            $model->where('email','=',$username);
        }else if(checkMobile($username)){
            $model = SM('Mobile');
            $model->where('mobile','=',$username);
        }
        if(!isset($model)){
            return ['status' => -1,'msg' => '用户名格式错误'];
        }
        $res = $model->hidden(['id','uid'])->find();
        return ['status' => 1,'data' => $res ? $res->toArray() : array()];
    }

    public function saveExt(array $data = array()){
        if(!isset($data['username']) || !isset($data['pwd'])){
            exception('用户|密码名不能为空');
            return false;
        }
        if(checkEmail($data['username'])){
            $model = SM('Email');
            $data['email'] = $data['username'];
        }else if(checkMobile($data['username'])){
            $model = SM('Mobile');
            $data['mobile'] = $data['mobile'];
        }
        unset($data['username']);
        if(!isset($model)){
            exception('用户格式错误');
            return false;
        }
        return $model->saveData($data);
    }
}