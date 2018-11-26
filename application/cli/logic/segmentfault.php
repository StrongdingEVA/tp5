<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/11/26
 * Time: 9:45
 */
namespace app\cli\logic;

class segmentfault{
    protected static $instance;

    protected static $baseUrl = 'https://segmentfault.com';

    private static $cookie = '_ga=GA1.2.1792865276.1541490056; PHPSESSID=web1~pmstmmpl24cdqoemtdnpetav8u; _gid=GA1.2.921234487.1542936738; Hm_lvt_e23800c454aa573c0ccb16b52665ac26=1542265629,1542612846,1542702053,1542936738; sf_remember=84db94ff593ba3a190f08f12576997b6; io=uvZ7qofcx_OB5TB0AFOy; _gat=1; Hm_lpvt_e23800c454aa573c0ccb16b52665ac26=1542937888';

    private $header = array(
        ':authority: segmentfault.com',
        ':method: GET',
        ':scheme: https',
        'accept: */*',
        'accept-encoding: gzip, deflate, br',
        'accept-language: zh-CN,zh;q=0.9',
        'user-agent: Mozilla/5.0 (Windows NT 6.1; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/70.0.3538.102 Safari/537.36',
        'x-requested-with: XMLHttpRequest'
    );

    private $typeInfo = array(
        'php' => array(
            'name' => 'php',
            'data_id' => '1040000000089387',
            'start' => '',
            'end' => '&_=485a6e4252b791017afc2503f95c2041',
            'refere' => '/t/php',
            'status' => 0,
            'enable' => 1
        ),
        'html5' => array(
            'name' => 'html5',
            'data_id' => '1040000000089409',
            'start' => '',
            'end' => '&_=02e75360c6f29bd1ea8dca4f08bebaf2',
            'refere' => 't/html5',
            'status' => 0,
            'enable' => 1
        ),
        'react.js' => array(
            'name' => 'react.js',
            'data_id' => '1040000002893277',
            'start' => '',
            'end' => '&_=2b7d311244e61cb2e6fafa3f667917b4',
            'refere' => '/t/react.js',
            'status' => 0,
            'enable' => 1
        ),
        'mysql' => array(
            'name' => 'mysql',
            'data_id' => '1040000000089439',
            'start' => '',
            'end' => '&_=1573ddd85b288ebf1c5233e1d4d0a2f6',
            'refere' => '/t/mysql',
            'status' => 0,
            'enable' => 1
        ),
        'vue.js' => array(
            'name' => 'vue.js',
            'data_id' => '1040000004003243',
            'start' => '',
            'end' => '&_=3c15b8e3e246bba7cc24262edd8a8545',
            'refere' => '/t/vue.js',
            'status' => 0,
            'enable' => 1
        ),
        'python' => array(
            'name' => 'python',
            'data_id' => '1040000000089534',
            'start' => '',
            'end' => '&_=cce1fef77c277f0a95e89973a33f35f6',
            'refere' => '/t/python',
            'status' => 0,
            'enable' => 1
        ),
        'node.js' => array(
            'name' => 'node.js',
            'data_id' => '1040000000089918',
            'start' => '',
            'end' => '&_=3617938d5c779061e0399384feed4d7c',
            'refere' => '/t/node.js',
            'status' => 0,
            'enable' => 1
        ),
        'html' => array(
            'name' => 'html',
            'data_id' => '1040000000089571',
            'start' => '',
            'end' => '&_=463c0d45b05e283fac72416b446d367a',
            'refere' => '/t/html',
            'status' => 0,
            'enable' => 1
        ),
        'css' => array(
            'name' => 'css',
            'data_id' => '1040000000089434',
            'start' => '',
            'end' => '&_=7a9a871abb486a01d25741028431010e',
            'refere' => '/t/css',
            'status' => 0,
            'enable' => 1
        ),
        'javascript' => array(
            'name' => 'css',
            'data_id' => '1040000000089436',
            'start' => '',
            'end' => '&_=6f04f045456bd62ea79e9bf7eddc469f',
            'refere' => '/t/javascript',
            'status' => 0,
            'enable' => 1
        ),
        '前端' => array(
            'name' => '前端',
            'data_id' => '1040000000089899',
            'start' => '',
            'end' => '&_=c760f362fa0254dc91ac3f8a195ac221',
            'refere' => '/t/%E5%89%8D%E7%AB%AF',
            'status' => 0,
            'enable' => 1
        ),
        'java' => array(
            'name' => 'java',
            'data_id' => '1040000000089449',
            'start' => '',
            'end' => '&_=6ef984a98f2e440cb135672fb9dbf528',
            'refere' => '/t/java',
            'status' => 0,
            'enable' => 1
        ),
        'jquery' => array(
            'name' => 'jquery',
            'data_id' => '1040000000089733',
            'start' => '',
            'end' => '&_=12cdea088a79c67dca4758e679883b1c',
            'refere' => '/t/jquery',
            'status' => 0,
            'enable' => 1
        ),
        'linux' => array(
            'name' => 'linux',
            'data_id' => '1040000000089392',
            'start' => '',
            'end' => '&_=ce721c760f21e8c60f6cb7837d2d815c',
            'refere' => '/t/linux',
            'status' => 0,
            'enable' => 1
        ),
        'css3' => array(
            'name' => 'css3',
            'data_id' => '1040000000090141',
            'start' => '',
            'end' => '&_=e410b841826e4b70b77c89a0413907cb',
            'refere' => '/t/css3',
            'status' => 0,
            'enable' => 1
        )
    );

    public function __construct(array $options = array()){
        if(is_array($options) && !empty($options)){
            $this->typeInfo = array_merge($this->typeInfo,$options);
        }
    }

    public function beforeRun($keys = ''){
        $temp = array();
        if(!is_array($keys)){
            $temp[] = $keys;
        }else{
            $temp = $keys;
        }
        self::getInstance($this->typeInfo);
        foreach ($temp as $key){
            if(!$key || !array_key_exists($key,$this->typeInfo)){
                return false;
            }
            $enable = self::getOptAttr($key,'enable');
            $status = self::getOptAttr($key,'status');
            if(!$enable || $status) {
                continue;
//                exception('当前通道'. $key .'：不可用');
//                unset(self::$instance->typeInfo[$key]);
            }

            //设置path
            self::setPath($key);
            //得到要请求的url
            $url = self::$baseUrl . self::getOptAttr($key,'path');
            self::setOptAttr($url,$key,'url');
            //得到请求头
            $header = self::getHeader($key);
            self::setOptAttr($header,$key,'header');
            $refere = self::$baseUrl . '/' . ltrim(self::getOptAttr($key,'refere'),'/');
            self::setOptAttr($refere,$key,'refere');
        }
        return self::$instance;
    }

    public static function getOptAttr(string $key = '',string $attr = ''){
        if(!$key || !$attr){
            return '';
        }
        $opt = self::$instance->typeInfo[$key];
        return $opt ? $opt[$attr] : '';
    }

    public static function setPath(string $key = ''){
        if(!$key){
            return '';
        }
        $tag = self::$instance->getOptAttr($key,'data_id');
        $start = self::$instance->getOptAttr($key,'start');
        $end = self::$instance->getOptAttr($key,'end');
        $path = '/api/tag/'. $tag .'/contents?start='. $start . $end;
        self::$instance->setOptAttr($path,$key,'path');
    }

    public function getStart(){
        return time() . rand(100,999);
    }

    public static function setOptAttr($value,string $key = '',string $attr = ''){
        if(!$key || !$attr){
            return '';
        }
        $opt = self::$instance->typeInfo[$key];
        $opt[$attr] = $value;
        self::$instance->typeInfo[$key] = $opt;
    }

    public function getHeader(string $key = ''){
        $header = $this->header;
        $header[] = ':path: ' . $this->getOptAttr($key,'path');
        return $header;
    }

    private static function getInstance($options = array()){
        if (is_null(self::$instance)) {
            self::$instance = new static($options);
        }
        return self::$instance;
    }

    public static function exec($key){
        self::setOptAttr(1,$key,'status');
        $refere = self::getOptAttr($key,'refere');
        $cookie = self::$cookie;
        $a = 0;
        if(self::getOptAttr($key,'enable')){
            do{
                $header = self::getOptAttr($key,'header');
                $url = self::getOptAttr($key,'url');
                $res = curl($url,'',$cookie,$header,$refere);
                $result = json_decode($res,1);
                if(!$result){
                    return false;
                }
                if($result['status'] == 0){
                    $data = $result['data'];
                    self::setOptAttr($data['start'],$key,'start');
                    self::setPath($key);
                    $url = self::$baseUrl . self::getOptAttr($key,'path');
                    self::setOptAttr($url,$key,'url');
//                    print_r($data['rows']);
                }else{
                    return false;
                }$a++;
//            }while(self::getOptAttr($key,'enable'));
            }while($a < 2);
        }
        return false;
    }
}