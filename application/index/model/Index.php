<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/11/14
 * Time: 9:51
 */
namespace app\index\model;

use think\Model;

class Index extends Model {
    public function test(){
        return 'this is test module';
    }
}