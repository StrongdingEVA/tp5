<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/11/14
 * Time: 17:35
 */
namespace app\common\model;

use think\Model;

class Basic extends Model {
//    protected $insert = ['create_time']; //自动完成
//    protected function setCreateTimeAttr(){
//        return time();
//    }
    protected $cachePrex = 'basic_key_'; //缓存前缀
    protected $openValidate = false; //是否开启模型自动验证
    protected $openCache = false; //是否开启主键缓存
    protected $cacheKey = 'id'; //主键名
    protected $autoWriteTimestamp = true; //填充时间
    protected $updateTime = false; //不填充update_time
    protected $bo = []; //模型的关联

    /**
     * @param int $id 根据主键获取数据
     * @param string $field
     * @return array
     * @throws \think\Exception
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function getById(int $id,string $field = '*'):array {
        if(!$id){
            exception('缺少必要参数',-1);
        }
        if($this->openCache){
            $res = cache($this->getCacheKey($id));
            if(!$res){
                $obj = $this->field($field)->find($id);
                $res = $this->searchBo($obj)->toArray();
                cache($this->getCacheKey($id),$res);
            }
        }else{
            $obj = $this->field($field)->find($id);
            $res = $this->searchBo($obj)->toArray();
        }
        return $res;
    }

    /**
     * @param array $data 要修改的数据
     * @param string $key 当做where条件 data中包含这个条件执行更新操作 否则添加操作
     * @return int
     * @throws \Exception
     */
    public function saveData(array $data = array(),string $key = 'id'): int {
        if(!$data){
            exception('缺少必要参数',-1);
        }
        $keyData = $data[$key];
        unset($data[$key]);
        if(empty($keyData)){
            if($this->openValidate){
                $this->validate(true)->save($data);
            }else{
                $this->save($data);
            }
            $res = $this->id;
        }else{
            $where = $key . '=' . $keyData;
            if($this->openValidate){
                $this->validate(true)->allowField(true)->save($data,$where);
            }else{
                $this->allowField(true)->save($data,$where);
            }
            $res = 1;
            if($this->openCache){
                cache($this->getCacheKey($keyData),null);
            }
        }
        return $res;
    }

    /**
     * @param array $map 查询条件
     * @param bool $paginate 是否分页 开启分页后如果是aip模式 直接返回['total','per_page','current_page','last_page','data' => '']
     *                                为开启api模式 返回['分页','数据']
     * @param bool $apiModel 是否是api模式
     * @return array|\think\Paginator
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function getListRows(array $map = array(),bool $paginate = false,$apiModel = false):array {
        $condition = $map['condition'];
        $order = $map['order'];
        $page = $map['page'];
        $limit = $map['limit'] ?? 10;
        $field = $map['field'] ?? '*';
        $hiddenField = $map['hidden'] ?? array(); //设置不显示的字段
        $isJoin = false;
        foreach ($condition as $item){
            $c = $item['where'];
            switch ($c){
                case 'where':
                    $this->where($item['field'],$item['opt'] ? $item['opt'] : '=',$item['value']);
                    break;
                case 'whereor':
                    $this->whereOr($item['field'],$item['opt'] ? $item['opt'] : '=',$item['value']);
                    break;
                case 'alias':
                    $this->alias($item['alias']);
                    break;
                case 'join':
                    $this->join($item['join'],$item['on']);
                    $isJoin = true;
                    break;
                default:
                    break;
            }
        }
        $order && $this->order($order);
        $page && $this->page($page);
        $page && $this->limit($limit);
        //如果开启了主键缓存（根据表主键建立缓存） 查询时设置查询字段为id，之后根据id从缓存中取数据
        //如果使用链接查询则不使用主键缓存
        if(!$isJoin){
            $this->openCache && $this->field($this->cacheKey);
        }else{
            $field && $this->field($field);
        }
        if($paginate){
            if($apiModel){
                $obj = $this->hidden($hiddenField)->paginate($limit);
                $res = $this->searchBo($obj)->toArray();
                !$isJoin && $res = $this->listFormat($res);
                return $res;
            }else{
                $obj = $this->hidden($hiddenField)->paginate($limit);
                $obj = $this->searchBo($obj);
                $page = $obj->render(); //分页
                $list = $obj->toArray(); //数据
                !$isJoin && $res = $this->listFormat($list['data']);
                return [$page,$res];
            }
        }else{
//            return $this->fetchSql(true)->select();
            $res = $this->searchBo(collection($this->hidden($hiddenField)->select()))->toArray();
            !$isJoin && $res = $this->listFormat($res);
            return $res;
        }
    }

    public function getInfoFind($condition = '',string $field = '*',array $hidden = array()):array {
        if(!is_array($condition)){
            $this->where($condition);
            $condition = array();
        }
        $field = $field ?? '*';
        $hiddenField = $hidden ?? array(); //设置不显示的字段
        foreach ($condition as $item){
            $c = $item['where'];
            switch ($c){
                case 'where':
                    $this->where($item['field'],$item['opt'] ? $item['opt'] : '=',$item['value']);
                    break;
                case 'whereor':
                    $this->whereOr($item['field'],$item['opt'] ? $item['opt'] : '=',$item['value']);
                    break;
                case 'alias':
                    $this->alias($item['alias']);
                    break;
                case 'join':
                    $this->join($item['join'],$item['on']);
                    break;
                default:
                    break;
            }
        }
        return $this->field($field)->hidden($hiddenField)->find()->toArray();
    }

    public function getCacheKey($id){
        return $this->cachePrex . $id;
    }

    public function listFormat(array $data):array {
        if($this->openCache){
            foreach ($data as &$item){
                $res = $this->getById($item[$this->cacheKey]);
                $item = $res;
            }
        }
        return $data;
    }

    /**
     * 获取关联
     */
    public function searchBo($obj) {
        $len = count($this->bo);
        if($len){
            foreach ($this->bo as $item){
                switch ($item){
                    case 'belongsTo':
                        $obj->belongsTo_;
                        break;
                    case 'hasOne':
                        $obj->hasOne_;
                        break;
                    case 'hasMany':
                        $obj->hasMany_;
                        break;
                    case 'belongsToMany':
                        $obj->belongsToMany_;
                        break;
                    default:
                        break;
                }
            }
        }
        return $obj;
    }
}