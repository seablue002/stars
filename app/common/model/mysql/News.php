<?php
/*信息模型*/
namespace app\common\model\mysql;


class News extends BaseModel
{

  // 获取数据列表，有分页
  public function getPageList($where=[], $fields='*', $page_size, $order = [])
  {
    $dataModel = $this
      ->alias('news')
      ->field($fields)
      ->where($where)
      ->with(['columnInfo'])
      ->order($order)
      ->paginate($page_size);

    return [
      'data' => $dataModel->toArray()['data'],
      'total' => $dataModel->toArray()['total']
    ];
  }

  // 获取数据列表，有分页，并返回页码
  public function getPageListWithPageHtml($where=[], $whereStr='', $fields='*', $page_size, $order = [])
  {
    $dataModel = $this
      ->field($fields)
      ->where($where)
      ->where($whereStr)
      ->with(['columnInfo'])
      ->paginate($page_size);

    return [
      'data' => $dataModel->toArray()['data'],
      'total' => $dataModel->toArray()['total'],
      'page' => $dataModel->render()
    ];
  }

  // 关联
  public function columnInfo()
  {
    return $this->hasOne(Column::class,'id', 'column_id')->alias('column')->bind(['column_name' => 'name', 'parent_dir_path', 'column_dir_path']);
  }
}