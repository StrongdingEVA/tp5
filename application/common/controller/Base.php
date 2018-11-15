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

    public function __construct(){
        header("Content-type: text/html; charset=UTF-8");
        parent::__construct();
        $this->initial();
    }

    private function initial(){
        $nav = SL('Common/getNav');
        $this->assign($nav,$nav);
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
     * 返回正确数据
     * @param string $data 需要返回的数据
     * @return json
     * @author fengxing
     */
    protected function setBack($data, $url = '', $second = 3, $moreData = array()) {
        if( IS_AJAX || $data['return']==2) {
            $return = [$data, $url, $second, $moreData];
            $this->ajaxSetBack($return);
            exit();
        }
        $this->ajaxSetBack($data, $url, $second, $moreData);
        exit();
    }

    /**
     * 通用返回数据
     * @param array $buffer 返回数组
     *              错误array(0,'错误编号','跳转地址',替换数据，跳转默认时间)
     *              正确array(1,'正确提示|模板数据','跳转地址','跳转默认时间','更多数据包括cookie数据')
     *              模板数据格式 array('pageName'=>'页面标题','buffer'=>array('数据内容'))
     * @param int $ifTemplate 是否有可能模板输出 1是 0否
     * @param int $ifFetch 是否返回模板输出数据 1是 0否
     * @param array $moreData 更多的数据输出
     * @return null
     * @author fengxing
     */
    public function reback($buffer, $ifTemplate = 0, $ifFetch = 0) {
        if ($buffer[0] === 0) {  //输出错误
            if (empty($buffer[4]) || !is_numeric($buffer[4]))
            $this->setError($buffer[1], IS_AJAX, $buffer[2], $buffer[3], $buffer[4]);
        }else {
            if ($ifTemplate == 1) {  //输出模板
                /* 载入模板标签 */
                if ($ifFetch)
                    return $this->loadTemplate($buffer[1], $buffer[2], $ifFetch);
                return $this->loadTemplate($buffer[1], $buffer[2]);
            }else { //输出成功
                if (empty($buffer[3]) || !is_numeric($buffer[3]))
                    $buffer[3] = 3;
                $this->setBack($buffer[1], $buffer[2], $buffer[3],$buffer[4]);
            }
        }
    }
    /**
     * 载入模板数据
     * @param int $buffer 模板数据
     * @param int $tempFile 模板文件
     * @param int $ifFetch 是否返回模板输出数据 1是 0否
     * @author fengxing
     */
    public function loadTemplate($params, $tempFile = '', $ifFetch = 0) {
        foreach ($params as $i => $param) {
            $this->assign($i, $param); //模板标识
        }
        if ($ifFetch === 0)
            return $this->display($tempFile);
        return $this->fetch($tempFile);
    }

    /**
     * ajax 返回所有错误码
     * @param string $errorNum 错误码 多个则以逗号间隔
     * @param int $flag=0 类型 默认0返回错误页面 1返回ajax数据 2返回字符串
     * @param string $url 跳转路径
     * @param string $replace 错误码中%s替换 多个则以逗号间隔
     * @param string $diplayContent='Public/error' 默认加载模板
     * @return string|json
     * @author demo
     */
    public function ajaxSetError($errorNum,$flag=0,$url='',$replace='',$displayContent=''){

        if(!$errorNum) return ; //错误码为空

        //兼容多个错误码
        if(!is_array($errorNum)) $numArray=explode(',',$errorNum);
        else $numArray=$errorNum;

        $error = implode(',', $numArray);
        if (!$error)
            $error = '未知错误！'; //错误描述为空

        if(empty($error)){
            $error=$errorNum; //错误描述为空
            $errorNum=0;
        }
        if ($flag === false)
            $flag = 0;
        if ($flag === true)
            $flag = 1;


        //返回类型
        switch($flag){
            case 0:
                $this->showPageMsg($error, $url,3,$displayContent);
                break;
            case 1:
                if($url){
                    $data=[$error, $url, 3];
                }else{
                    $data=$error;
                }

                $newData['data']=$data;
                $newData['status']=0;
                $this->ajaxReturn($newData,'json');
                break;
            case 2:
                return $error;
                break;
        }
    }
    /**
     * 返回正确数据
     * @param string $data 需要返回的数据
     * @param string $url 跳转地址
     * @param int $second 跳转间隔时间
     * @return json
     * @author demo
     */
    public function ajaxSetBack($data,$url='',$second=3, $moreData = array()) {
        if( IS_AJAX || $data['return']==2) {
            $newData['data']=$data;
            $newData['status']=1;
            if(!empty($moreData)) $newData['code']=$moreData;
            $this->ajaxReturn($newData,'json');
        }
        $this->showPageMsg($data,$url,$second);
    }
    /**
     * 返回json数据
     * @param array $data 需要返回的数据
     * @return json
     * @author demo
     */
    public function ajaxBack($data) {
        $this->ajaxReturn($data,'json');
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
    public function cyback($data,string $tempName = '',bool $analysis = false){
        //len为1 直接fetch 、
        //len为2 是想要ajax返回
        //len为3 是想用系统的error success 返回
        $temp = $data;
        if(!is_array($temp)){
            $data = array();
            $data[] = $temp;
            unset($temp);
        }
        $len = count($data);
        $param = $data[0] ? $data[0] : array();
        $status = $data[1] ? $data[1] : -1;

        if($len <= 1){
            if ($analysis){
                return $this->display($param);
            }else{
                $this->mapAssign($param);
                return $this->fetch($tempName);
            }
        }else if($len == 2){
            return $this->cajax($param['msg'],$param['data'],$status);
        }else if($len == 3){
            !$param['url'] && $param['url'] = '';
            !$param['data'] && $param['data'] = array();
            !$param['wait'] && $param['wait'] = 3;
            if($status == SUCCESS_STATUS){
                !$param['msg'] && $param['msg'] = '成功';
                return $this->success($param['msg'],$param['url'],$param['data'],$param['wait']);
            }
            if($status == FAIL_STATUS){
                !$param['msg'] && $param['msg'] = '出错了';
                return $this->error($param['msg'],$param['url'],$param['data'],$param['wait']);
            }
        }else{
            exit('errrrrrrrrr');
        }
    }

    public function mapAssign(array $param){
        if(is_array($param)){
            foreach ($param as $bkey => $bval){
                $this->assign($bkey,$bval);
            }
        }
    }


    public function cajax(string $msg,$data = '',int $status = -1){
        !$data && $data = (object)$data;
        return ['status' => $status,'msg' => $msg,'data' => $data];
    }
}