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
    static $cacheKey = 'basic_key_';
    protected $openCache = false;
    protected $autoWriteTimestamp = true; //填充时间
    protected $updateTime = false; //不填充update_time

    /**
     * @param int $id 根据主键获取数据
     * @param string $field
     * @return array
     * @throws \think\Exception
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function getById(int $id,string $field = '*'){
        if(!$id){
            exception('缺少必要参数',-1);
        }
        if($this->openCache){
            $res = cache($this->getCacheKey($id));
            if(!$res){
                $res = $this->field($field)->find($id)->toArray();
                cache($this->getCacheKey($id),$res);
            }
        }else{
            $res = $this->field($field)->find($id)->toArray();
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
            $this->save($data);
            $res = $this->id;
        }else{
            $where = $key . '=' . $keyData;
            $this->allowField(true)->save($data,$where);
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
    public function getListRows(array $map = array(),bool $paginate = false,$apiModel = false){
        $where = $map['where'];
        $order = $map['order'];
        $page = $map['page'];
        $limit = $map['limit'] ?? 10;
        $field = $map['field'] ?? '*';

        foreach ($where as $item){
            $this->where($item['field'],$item['opt'] ? $item['opt'] : '=',$item['value']);
        }
        $order && $this->order($order);
        $page && $this->page($page);
        $page && $this->limit($limit);
        $field && $this->field($field);
        $this->openCache && $this->field('id');
        if($paginate){
            if($apiModel){
                $res = $this->paginate($limit)->toArray();
                return $this->listFormat($res);
            }else{
                $res = $this->paginate($limit);
                $page = $res->render(); //分页
                $list = $res->toArray(); //数据
                return [$page,$this->listFormat($list['data'])];
            }
        }else{
            return collection($this->select())->toArray();
        }
    }

    public function getCacheKey($id){
        return self::$cacheKey . $id;
    }

    public function listFormat(array $data):array {
        if($this->openCache){
            foreach ($data['data'] as &$item){
                $res = $this->getById($item['id']);
                $item = $res;
            }
        }
        return $data;
    }
}