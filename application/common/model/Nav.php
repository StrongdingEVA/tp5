<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/11/15
 * Time: 16:54
 */
namespace app\common\model;

class Nav extends Basic{
    protected $openCache = true;
    static $cachePrex = 'nav_key_';
}