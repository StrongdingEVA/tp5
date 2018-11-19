<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/11/14
 * Time: 11:17
 */
namespace app\index\logic;

class Index{
    public function test() {
        $map = array(
            'condition' => array(
                array('where' => 'alias','alias' => 'a'),
                array('where' => 'join','join' => 'user u','on' => 'a.uid = u.id'),
                array('where' => 'where','field' => 'a.id','opt' => '=','value' => 1),
            ),
            'field' => '*',
            'hidden' => array('views','comts')
        );
        return ['list' => SM('Article/getListRows',$map)];
    }
}