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
    static $cachePrex = 'user_'; //缓存前缀
    protected $openValidate = true; //是否开启模型自动验证
    protected $openCache = true; //是否开启主键缓存
    protected $cacheKey = 'id'; //主键名
    protected $autoWriteTimestamp = true; //填充时间
    protected $updateTime = false; //不填充update_time

    public function getInfoByLogin(string $username = ''):array {
        if(checkEmail($username)){
            $model = SM('Email');
        }else if(checkMobile($username)){
            $model = SM('Mobile');
        }
        if(!isset($model)){
            return ['status' => 0,'msg' => '不存在的登录方式'];
        }
        $res = $model->find($username)->toArray();
        return $res;
    }
}