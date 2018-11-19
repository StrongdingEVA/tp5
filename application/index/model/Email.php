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

class Email extends Basic {
    protected $openValidate = true; //是否开启模型自动验证
    protected $openCache = false; //是否开启主键缓存
    protected $autoWriteTimestamp = false; //填充时间
}