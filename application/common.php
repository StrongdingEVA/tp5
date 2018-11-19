<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006-2016 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: 流年 <liu21st@gmail.com>
// +----------------------------------------------------------------------
use think\Loader;
// 应用公共文件
global $publicData; //存储通用数据
/**
 * 通用函数库，该项目下所有通用函数
 */
$sqltrace = array();

//记录sql执行语句
function setSqlTrace($sql) {
    global $sqltrace;
    $sqltrace[] = $sql;
}

//返回sql执行语句
function getSqlTrace() {
    global $sqltrace;
    return implode('<br>', $sqltrace);
}

//查看是否移动端登陆
function isMobile() {
    // 如果有HTTP_X_WAP_PROFILE则一定是移动设备
    if (isset($_SERVER['HTTP_X_WAP_PROFILE']))
        return true;

    //此条摘自TPM智能切换模板引擎，适合TPM开发
    if (isset($_SERVER['HTTP_CLIENT']) && 'PhoneClient' == $_SERVER['HTTP_CLIENT'])
        return true;
    //如果via信息含有wap则一定是移动设备,部分服务商会屏蔽该信息
    if (isset($_SERVER['HTTP_VIA']))
        //找不到为flase,否则为true
        return stristr($_SERVER['HTTP_VIA'], 'wap') ? true : false;
    //判断手机发送的客户端标志,兼容性有待提高
    if (isset($_SERVER['HTTP_USER_AGENT'])) {
        $clientkeywords = array(
            'nokia',
            'sony',
            'ericsson',
            'mot',
            'samsung',
            'htc',
            'sgh',
            'lg',
            'sharp',
            'sie-',
            'philips',
            'panasonic',
            'alcatel',
            'lenovo',
            'iphone',
            'ipod',
            'blackberry',
            'meizu',
            'android',
            'netfront',
            'symbian',
            'ucweb',
            'windowsce',
            'palm',
            'operamini',
            'operamobi',
            'openwave',
            'nexusone',
            'cldc',
            'midp',
            'wap',
            'mobile'
        );
        //从HTTP_USER_AGENT中查找手机浏览器的关键字
        if (preg_match("/(" . implode('|', $clientkeywords) . ")/i", strtolower($_SERVER['HTTP_USER_AGENT']))) {
            return true;
        }
    }
    //协议法，因为有可能不准确，放到最后判断
    if (isset($_SERVER['HTTP_ACCEPT'])) {
        // 如果只支持wml并且不支持html那一定是移动设备
        // 如果支持wml和html但是wml在html之前则是移动设备
        if ((strpos($_SERVER['HTTP_ACCEPT'], 'vnd.wap.wml') !== false) && (strpos($_SERVER['HTTP_ACCEPT'], 'text/html') === false || (strpos($_SERVER['HTTP_ACCEPT'], 'vnd.wap.wml') < strpos($_SERVER['HTTP_ACCEPT'], 'text/html')))) {
            return true;
        }
    }
    return false;
}

//对传入的变量进行转义
if (phpversion() < '5.3.0') {
    set_magic_quotes_runtime(0);
    @ ini_set('magic_quotes_sybase', 0);
}
if (get_magic_quotes_gpc()) {
    $_GET = stripslashes_deep($_GET);
    $_POST = stripslashes_deep($_POST);
    $_COOKIE = stripslashes_deep($_COOKIE);
} else {
    $_GET = add_magic_quotes($_GET);
    $_POST = add_magic_quotes($_POST);
    $_COOKIE = add_magic_quotes($_COOKIE);
    $_SERVER = add_magic_quotes($_SERVER);
    $_REQUEST = array_merge($_GET, $_POST);
}

/**
 * 去除转义字符；
 * @param array $value 待转义数组
 * @return array 转义后的数组
 * @author fengxing
 */
function stripslashes_deep($value) {
    if (is_array($value)) {
        $value = array_map('stripslashes_deep', $value);
    } elseif (is_object($value)) {
        $vars = get_object_vars($value);
        foreach ($vars as $key => $data) {
            $value->{$key} = stripslashes_deep($data);
        }
    } else {
        $value = stripslashes($value);
    }
    return $value;
}

/**
 * 添加转义字符；
 * @param array $array 待转义数组
 * @return array 转义后的数组
 * @author fengxing
 */
function add_magic_quotes($array) {
    foreach ((array) $array as $k => $v) {
        if (is_array($v)) {
            $array[$k] = add_magic_quotes($v);
        } else {
            $array[$k] = addslashes($v);
        }
    }
    return $array;
}

/**
 * 提取分页信息
 * @param int $count 数据总数
 * @param int $page 当前页数
 * @param int $limit 限定数量
 * @return int
 * @author fengxing
 */
function page($count, $page, $limit = 10) {
    if ($page == '' || !is_numeric($page) || $page <= 1) {
        return 1;
    }
    $n = ceil($count / $limit);
    if ($page > $n) {
        return $n;
    }
    return $page;
}

/**
 * 根据参数判断并获取缓存
 * @param string $cacheName 缓存名称
 * @param string|array $value 缓存内容
 * @return array
 * @author fengxing
 */
function SS($cacheName,$value='') {
    $path=APP_PATH.'Runtime/Temp/';
    if(!file_exists($path)) mkdir($path,0755,true);
    if($value===''){
        return unserialize(file_get_contents($path.md5($cacheName)));
    }
    file_put_contents($path.md5($cacheName),serialize($value));
}

/**
 * 描述：使用tool文件夹下的方法集合
 * @param string $className 类名或者第三方工具类的路径
 * @param string $functionName 方法名
 * @param array $param 参数集合
 * @author fengxing
 */
function useToolFunction($className, $functionName = '', $param = array()) {
    $thisClassName = explode('/', $className);
    $thisClassName = $thisClassName[count($thisClassName) - 1];
    if (!class_exists($thisClassName)) {
        $importClassName = str_replace('/', '.', $className);
        import('Common.Tool.' . $importClassName);
    }
    $model = new $thisClassName(); // 实例化
    if ($functionName) {
        return call_user_func_array(array(
            $model,
            $functionName), $param);
    }
    return $model;
}

/**
 * 描述：格式化字符串通用方法，调用Tool/String.class.php中方法
 * @param string $functionName 函数名
 * @author fengxing
 */
function stringChange($functionName) {
    $param = func_get_args();
    $param = array_splice($param, 1);
    return useToolFunction('StringChange', $functionName, $param); //支持3个参数超出用数组
}

/**
 * 描述：检测字符串通用方法，调用Tool/StringCheck.class.php中方法
 * @param string $functionName 函数名
 * @author fengxing
 */
function checkString($functionName) {
    $param = func_get_args();
    $param = array_splice($param, 1);
    return useToolFunction('StringCheck', $functionName, $param); //支持3个参数超出用数组
}

/**
 * 日期相关处理 调用Tool/DateHandle.class.php中方法
 * @param string $functionName 函数名
 * @notice 以后对应日期处理相关函数 请封装至Tool/DateHandle.class.php类中
 * @author fengxing
 */
function handleDate($functionName) {
    $param = func_get_args();
    $param = array_splice($param, 1);
    return useToolFunction('DateHandle', $functionName, $param); //支持3个参数超出用数组
}

/**
 * 分页相关处理
 * @param $functionName
 * 请统一此方法处理分页相关处理
 * @author fengxing
 */
function thisPage($functionName) {
    //@notice 调用Page类相关方法,请先调用init方法
    $param = func_get_args();
    $param = array_splice($param, 1);
    return useToolFunction('ThisPage', $functionName, $param); //支持3个参数超出用数组
}

/**
 * 描述：不存在的链接进行错误提示
 * @author fengxing
 */
function emptyUrl() {
    $map = C('LOSE_URL_JUMP');
    if (isset($map[CONTROLLER_NAME])) {
        header('Location:' . $map[CONTROLLER_NAME]);
        exit;
    }
    if (IS_AJAX) {
        R('Common/SystemLayer/ajaxSetError', array(
            '00139',
            1));
    }
    R('Common/SystemLayer/jumpError');
    exit();
}

/**
 * 上传数据到远程
 * @param string $url 提交地址
 * @param array  $data 提交数据
 * @return String
 * @author fengxing
 */
function CURL($url, $data = '') {
    $curl = curl_init();

    if (!empty($data)) {
        curl_setopt($curl, CURLOPT_POST, 1);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
    }
    curl_setopt($curl, CURLOPT_HEADER, false);
    curl_setopt($curl, CURLOPT_URL, $url);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($curl, CURLOPT_USERAGENT, "Mozilla/4.0");
    if (strstr($url, 'https://')) {
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, FALSE); // https请求 不验证证书和hosts
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, FALSE);
    }

    $result = curl_exec($curl);
    $error = curl_error($curl);
    $output = $error ? $error : $result;
    return $output;
}

/**
 * Token检查
 * @return bool
 * @author fengxing
 */
function checkToken($data) {
    if (C('TOKEN_ON')) {
        $name = C('TOKEN_NAME');
        if (!isset($data[$name]) || !isset($_SESSION[$name])) { // 令牌数据无效
            return false;
        }
        // 令牌验证
        list($key, $value) = explode('_', $data[$name]);

        if (isset($_SESSION[$name][$key]) && $value && $_SESSION[$name][$key] === $value) { // 防止重复提交
            if (!IS_AJAX) {//Ajax重复提交兼容 否则前台所有Ajax都要追加设置变更Token
                unset($_SESSION[$name][$key]); // 验证完成销毁session
            }
            return true;
        }
        // 开启TOKEN重置
        if (C('TOKEN_RESET'))
            unset($_SESSION[$name][$key]);
        return false;
    }
    //Toekn未开启直接返回true
    return true;
}

/**
 * 描述：获取静态方法
 * @return string
 * @author fengxing
 */
function getStaticFunction($modelName, $functionName) {
    $modell = D($modelName);
    return $modell::$functionName;
}

/**
 * 通用获取API方法 用于__call
 * @param string $functionName 当前调用的方法名称
 * @param string $args 参数数组
 * @return mixed
 * @author fengxing
 */
function getApi($functionName, $args) {

    $module = str_replace('getApi', '', $functionName);

    //处理$args[0]目前结构为Grade/grade
    $urlArray = explode('/', $args[0]);

    $model = A($module . '/' . $urlArray[0], 'Api');
    if ($model === false)
        return '';
    $param = $args;
    $param = array_slice($param, 1);
    return call_user_func_array(array(
        $model,
        $urlArray[1]), $param);
}

/**
 * 兼容低版本的函数<5.5
 * @author fengxing
 */
if (!function_exists('array_column')) {

    function array_column($input, $columnKey, $indexKey = null) {
        $columnKeyIsNumber = (is_numeric($columnKey)) ? true : false;
        $indexKeyIsNull = (is_null($indexKey)) ? true : false;
        $indexKeyIsNumber = (is_numeric($indexKey)) ? true : false;
        $result = array();
        foreach ((array) $input as $key => $row) {
            if ($columnKeyIsNumber) {
                $tmp = array_slice($row, $columnKey, 1);
                $tmp = (is_array($tmp) && !empty($tmp)) ? current($tmp) : null;
            } else {
                $tmp = isset($row[$columnKey]) ? $row[$columnKey] : null;
            }
            if (!$indexKeyIsNull) {
                if ($indexKeyIsNumber) {
                    $key = array_slice($row, $indexKey, 1);
                    $key = (is_array($key) && !empty($key)) ? current($key) : null;
                    $key = is_null($key) ? 0 : $key;
                } else {
                    $key = isset($row[$indexKey]) ? $row[$indexKey] : 0;
                }
            }
            $result[$key] = $tmp;
        }
        return $result;
    }

}

/**
 * 获取当前模型对应db类
 * 如果当前模块下有对应模型使用当前模块下的模型  否则使用公共模块下的模型
 * Article模型 SM('article')  SM('common/article')
 */
function SM() {
    //处理$args[0]目前结构为Grade/grade
    $param = func_get_args();
    $urlArray = explode('/', $param[0]);
    $len = count($urlArray);
    if(!$param){
        exception('不存在要实例化的模型',-1);
    }
    if($len > 2){
        exception('参数错误',-1);
    }

    if (file_exists(APP_PATH . NOW_MODULE . '/model/'. $urlArray[0] .'.php')) {
        $model = Loader::model($urlArray[0], 'model', false,'index');
    }else if(file_exists(APP_PATH . 'common' . '/model/'. $urlArray[0] .'.php')){
        $model = Loader::model($urlArray[0], 'model', false);
    } else {
        exception('不存在模型：' . $urlArray[0],-1);
    }

    if (count($urlArray) == 1){
        return $model;
    }

    $param = array_slice($param, 1);
    return call_user_func_array(array($model,$urlArray[1]), $param);
}

/**
 * 获取当前模型对应逻辑类
 * 如果当前模块下有对应逻辑使用当前模块下的逻辑  否则使用公共模块下的逻辑
 * Article逻辑 SL('article')  SL('common/article')
 */
function SL() {
    //处理$args[0]目前结构为ade/ade
    $param = func_get_args();

    $urlArray = explode('/', $param[0]);
    $len = count($urlArray);
    if(!$param){
        exception('不存在要实例化的模型',-1);
    }
    if($len > 2){
        exception('参数错误',-1);
    }

    if (file_exists(APP_PATH . NOW_MODULE . '/logic/'. $urlArray[0] .'.php')) {
        $lName = \think\APP::$namespace . '\\' . NOW_MODULE . 'logic\\' . $urlArray[0];
    } else if(file_exists(APP_PATH . 'common/logic/'. $urlArray[0] .'.php')){
        $lName = \think\APP::$namespace . '\\' . 'common\\logic\\' . $urlArray[0];
    }else{
        exception('不存在要实例化的逻辑类：' . $urlArray[0],-1);
    }

    $logic = new $lName;
    if (count($urlArray) == 1)
        return $logic;

    $param = array_slice($param, 1);
    return call_user_func_array(array($logic,$urlArray[1]), $param);
}

/**
 * 获取路径参数数据
 * @return null
 * @author fengxing
 */
function SU($num) {
    $param = $_SERVER['REQUEST_URI'];
    $param = str_replace('.' . C('URL_HTML_SUFFIX'), '', $param); //去掉后缀
    $param = preg_replace('/\?\=/', C('URL_PATHINFO_DEPR'), $param); //替换问号和等号
    $paramArray = explode(C('URL_PATHINFO_DEPR'), $param);
    return $paramArray[$num];
}

//兼容mysql获取方法
if (!function_exists('mysql_get_server_info')) {

    function mysql_get_server_info() {
        return '';
    }

}


//aes ecb加密
function aes_ecb_encrypt($con, $key) {
    $AESed = base64_encode(mcrypt_encrypt(MCRYPT_RIJNDAEL_128, $key, $con, MCRYPT_MODE_ECB)); #加密
    return $AESed;
}

//aes ecb解密
function aes_ecb_decrypt($con, $key) {
    $decrypted = mcrypt_decrypt(MCRYPT_RIJNDAEL_128, $key, base64_decode($con), MCRYPT_MODE_CBC);
    return $decrypted;
}

function is_iphone() {
    $agent = strtolower($_SERVER['HTTP_USER_AGENT']);
    if (strpos($agent, 'iphone') !== false) {
        return true;
    }
    if (strpos($agent, 'ipad') !== false) {
        return true;
    }
    if (strpos($agent, 'ipod') !== false) {
        return true;
    }
    return false;
}

function is_wechat() {
    $agent = strtolower($_SERVER['HTTP_USER_AGENT']);
    if (strpos($agent, 'micromessenger') !== false) {
        return true;
    }
    return false;
}
function is_qq() {
    $agent = strtolower($_SERVER['HTTP_USER_AGENT']);
    if (strpos($agent, 'QQ') !== false) {
        return true;
    }
    return false;
}
function is_android() {
    $agent = strtolower($_SERVER['HTTP_USER_AGENT']);
    if (strpos($agent, 'Android') !== false) {
        return true;
    }
    if (strpos($agent, 'SymbianOS') !== false) {
        return true;
    }
    return false;
}

/**
 * 字符串命名风格转换
 * type 0 将Java风格转换为C的风格 1 将C风格转换为Java的风格
 * @param string $name 字符串
 * @param integer $type 转换类型
 * @return string
 */
function parse_name($name, $type=0) {
    if ($type) {
        return ucfirst(preg_replace_callback('/_([a-zA-Z])/', function($match){return strtoupper($match[1]);}, $name));
    } else {
        return strtolower(trim(preg_replace("/[A-Z]/", "_\\0", $name), "_"));
    }
}
/**
 * 获取随机字符串
 * @param int $length
 * @param string $char
 * @return string
 */
function getNonstr($length = 32, $char = '123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ') {
    if(!is_int($length) || $length < 0) {
        $length = 32;
    }

    $string = '';
    for($i = $length; $i > 0; $i--) {
        $string .= $char[mt_rand(0, strlen($char) - 1)];
    }
    return $string;
}

/**
 * 验证邮箱
 * @param $email
 * @return bool
 */
function checkEmail($email){
    if(preg_match('/^[A-Za-z0-9]+@[a-zA-Z0-9_-]+(\.[a-zA-Z0-9_-]+)+$/',$email)){
        return true;
    }
    return false;
}

function checkMobile($mobile){
    return false;
}