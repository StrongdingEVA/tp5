<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006-2016 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: liu21st <liu21st@gmail.com>
// +----------------------------------------------------------------------

// [ 应用入口文件 ]

// 定义应用目录
define('BIND_MODULE','index');
define('APP_PATH', __DIR__ . '/../application/');
define('SUCCESS_STATUS',1);
define('FAIL_STATUS',-1);
define('COMMON_MODULE',APP_PATH . 'common/model/'); //基础模型类
define('COMMON_LOGIC',APP_PATH . 'common/logic/'); //公共逻辑
// 加载框架引导文件
require __DIR__ . '/../thinkphp/start.php';
