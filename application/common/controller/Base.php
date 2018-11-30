<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006~2018 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: liu21st <liu21st@gmail.com>
// +----------------------------------------------------------------------
namespace app\common\controller;
use think\captcha\Captcha;
use think\Controller;
use think\Request;

class Base extends Controller {
    public $u = '';
    public function __construct(){
        header("Content-type: text/html; charset=UTF-8");
        parent::__construct();
        $this->initial();
        $this->iniUser();
    }

    private function initial(){
        $nav = SL('Common/getNav');
        $this->mapAssign($nav);
    }

    /**
     * 初始化登录用户
     */
    private function iniUser(){
        $prefix = config('LOGIN_COOKIE_PREFIX') ?? '';
        $cipher = $cipher ?? config('AES_CIPHER');
        $key = $key ?? config('AES_KEY');
        $iv = cookie($prefix . 'iv');
        $u = cookie($prefix . 'u');
        $tag = cookie($prefix . 'tag');
        $uInfo = aesDecrypt($u,$iv,$tag,$cipher,$key);
        $uInfo = json_decode($uInfo,true);
        if($uInfo){
            $uInfo = SM('User')->getById($uInfo['id']);
            if(!$uInfo['status']){
                $this->cyasync(['msg' => '您已被禁止登陆','wait' => 3,'status' => -1],false);
            }
            $this->u = $uInfo;
            $this->assign('uinfo',$uInfo);
        }if(NOW_CONTROLLER == 'ucenter' && !$uInfo){
            $this->redirect('/');
        }
    }

    /**
     * 编辑器图片上传
     */
    public function upload(){
        $dir=$_GET['dir'];
        if(empty($dir)) $dir='images';
        //logo上传
        if ($_FILES) {
            $path = SL('Upload')->uploadImage($dir);
            if (!strstr($path, 'Uploads')) {
                $buffer=[0,
                    $path];
            }else{
                $buffer=[1,$path];
            }
        }else{
            $buffer=[0,'请上传文件'];
        }

        $this->reback($buffer,!IS_AJAX);
    }
    
    /**
     * 描述：对不存在的方法进行处理
     * @author fengxing
     */
    function __call($functionName, $args){
        //TODO
    }

    /**
     * 返回错误码
     * @param string $errorNum 错误码 多个则以逗号间隔
     * @param int $flag 类型 默认0返回错误页面 1返回ajax数据 2返回字符串
     * @param string $url 跳转路径
     * @param string $replace 错误码中%s替换 多个则以逗号间隔
     * @return string|json
     * @author fengxing
     */
    protected function setError($errorNum,$flag=0,$url='',$replace='') {
        $this->ajaxSetError($errorNum,$flag,$url,$replace);
    }

    /**
     * 提示信息
     * @param string $msgDetail 错误提示标题
     * @param string $link 跳转地址
     * @param bool $autoRedirect = true 跳转地址
     * @param int $seconds=3 等待时间
     * @param sting $displayContent 调取模板名称
     * @return bool
     * @author demo
     */
    public function showPageMsg($msgDetail, $link='',  $seconds = 3,$displayContent=''){
        if(empty($displayContent)) $displayContent=MODULE_NAME.'@Public/msg';
        if ($link) {
            $links[0]['text'] = '进入>>';
            $links[0]['href'] = $link;
            $links[0]['target'] = '_self';
        }else{
            $links[0]['text'] = '返回上一页';
            $links[0]['href'] = 'javascript:history.go(-1)';
            $links[0]['target'] = '_self';
        }
        $this->assign('msg', $msgDetail);
        $this->assign('links', $links);
        $this->assign('jumpUrl', $links[0]['href']);
        $this->assign('waitSecond', $seconds);
        $this->display($displayContent);
        exit;
    }

    /**
     * 验证验证码
     * @param Request $request
     * @return bool
     */
    public function checkCapt(Request $request){
        $code = $request->param('code',0,'int');
        $id = $request->param('key','');
        $captcha = new Captcha();
        return $captcha->check($code, $id);
    }

    /**
     * 通用返回
     * @param $tempName  是否指定模板路径  否则使用默认路径
     * @param $analysis  是否直接解析 不经过模板 默认false
     */
    public function cyback(array $data = array(),string $tempName = '',bool $analysis = false){
        if ($analysis){
            return $this->display($data);
        }else{
            $this->mapAssign($data);
            return $this->fetch($tempName);
        }
    }

    public function cyasync(array $data = array(),bool $isAsync = true){
        if($isAsync){
            return $this->cajax($data['msg'] ?? '',$data['data'],$data['status']);
        }else{
            !$data['url'] && $data['url'] = '';
            !$data['data'] && $data['data'] = array();
            !$data['wait'] && $data['wait'] = 3;
            if($data['status'] == SUCCESS_STATUS){
                !$data['msg'] && $data['msg'] = '成功';
                return $this->success($data['msg'],$data['url'],$data['data'],$data['wait']);
            }
            if($data['status'] == FAIL_STATUS){
                !$data['msg'] && $data['msg'] = '出错了';
                return $this->error($data['msg'],$data['url'],$data['data'],$data['wait']);
            }
        }
    }

    public function mapAssign(array $param){
        if(is_array($param)){
            foreach ($param as $bkey => $bval){
                $this->assign($bkey,$bval);
            }
        }
    }


    public function cajax(string $msg = '',$data = '',int $status = -1){
        !$data && $data = (object)$data;
        return ['status' => $status,'msg' => $msg,'data' => $data];
    }

    public function _empty(Request $request){
        $isAjax = false;
        if($request->isAjax()){
            $isAjax = true;
        }
        $this->cyasync(['msg' => '操作错误','status' => -1,'url' => '/'],$isAjax);
    }
}