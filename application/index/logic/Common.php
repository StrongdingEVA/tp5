<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/11/15
 * Time: 16:53
 */
namespace app\index\logic;

class Common {
    public function getNav(){
        return SM('Nav/getListRows');
    }
}