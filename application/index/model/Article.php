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

class Article extends Basic {
    protected $openCache = false;
    protected $bo = ['belongsTo'];


    public function belongsTo_(){
        return $this->belongsTo('User','id');
    }
}