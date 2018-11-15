<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/11/8
 * Time: 18:11
 */
namespace app\index\controller;
use think\controller\Rest;

class Info extends Rest{
    public function read_json($id){
        echo 'this is json';exit;
    }

    public function read_xml($id){
        echo 'this is xml';exit;
    }
}