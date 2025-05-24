<?php
declare(strict_types=1);

namespace app\common\model\mysql;


use think\Model;

class BaseModel extends Model
{
  // 插入一条数据，并返回新增id
  public function insertOneData($data)
  {
    return $this->insertGetId($data);
  }
  
  // 插入多条数据获并取成功插入条数
  public function insertAllData($list)
  {
    return $this->insertAll($list);
  }

  // 插入多条数据获并取成功插入数据的主键id
  public function insertAllDataAndGetIds($list)
  {
    return array_map(function($item) {
      return $item['id'];
    }, $this->saveAll($list)->toArray());
  }
  
  // 获取数据列表
  public function getList($fields='*', $order=[], $where = [])
  {
    return $this
      ->field($fields)
      ->where($where)
      ->order($order)
      ->select()
      ->toArray();
  }
  
  // 获取数据列表，有分页
  public function getPageList($where=[], $fields='*', $page_size, $order = [])
  {
    $dataModel = $this
      ->field($fields)
      ->where($where)
      ->order($order)
      ->paginate($page_size);

    return [
      'data' => $dataModel->toArray()['data'],
      'total' => $dataModel->toArray()['total']
    ];
  }

  // 获取数据列表，有分页，并返回页码
  public function getPageListWithPageHtml($where=[], $whereStr='', $fields='*', $page_size)
  {
    $dataModel = $this
      ->field($fields)
      ->where($where)
      ->where($whereStr)
      ->paginate($page_size);

    return [
      'data' => $dataModel->toArray()['data'],
      'total' => $dataModel->toArray()['total'],
      'page' => $dataModel->render()
    ];
  }
  
  // 获取一条数据
  public function getOne($where=[], $fields='*', $order = []) {
    return $this
      ->field($fields)
      ->where($where)
      ->order($order)
      ->findOrEmpty();
  }

  public function updateOne($data, $where = []) {
    return $this
      ->where($where)
      ->update($data);
  }
  
  public function updateAll($data) {
    return array_map(function($item) {
      return $item['id'];
    }, $this->saveAll($data)->toArray());
  }

  public function getFields($where, $field, $order=[], $key = '')
  {
    return $this->where($where)->order($order)->column($field, $key);
  }
}