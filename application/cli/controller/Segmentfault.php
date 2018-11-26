<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/11/26
 * Time: 9:36
 */
namespace app\cli\controller;

class Segmentfault{
    public function getSegFault(){
        $obj = SL('segmentfault/beforeRun',['html5','abcedf'],array(
            'abcedf' => array(
                'name' => 'abcedf',
                'data_id' => '1040000000089387',
                'start' => '',
                'end' => '&_=485a6e4252b791017afc2503f95c2041',
                'refere' => '/t/abcedf',
                'status' => 0,
                'enable' => 1
            ),
        ));
        $obj = SL('segmentfault/beforeRun',['php']);
//        $obj = SL('segmentfault/beforeRun',['python','vue.js']);
//        $obj::exec('php');
//        $obj::exec('html5');
        print_r($obj);
    }
}