<?php


namespace app\common\model\mysql;


class AuthRule extends BaseModel
{
  public function getAuthRulePageList($where, $page_size) {
    $dataModel = $this
      ->alias('a')
      ->leftJoin('auth_rule a2', 'a.pid = a2.id')
      ->field('a.*, a2.title ptitle')
      ->whereOr($where)
      ->paginate($page_size);

    return [
      'data' => $dataModel->toArray()['data'],
      'total' => $dataModel->toArray()['total']
    ];
  }
  
  public function getAuthRuleList() {
    $dataModel = $this
      ->getList();
    
    return [
      'data' => $dataModel['data'],
      'total' => $dataModel['total']
    ];
  }
}