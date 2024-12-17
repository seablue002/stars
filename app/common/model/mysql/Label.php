<?php
/*标签模型*/
namespace app\common\model\mysql;


class Label extends BaseModel
{
  public function getLabelNormalInfoList() {
    return $this
      ->alias('c1')
      ->leftJoin('label c2', 'c1.pid = c2.id')
      ->field(['c1.id', 'c1.name', 'c1.pid', 'c2.name as p_name'])
      ->order('c1.sort ASC')
      ->select()
      ->toArray();
  }
  public function getLabelInfoList($fields = '*', $where = [])
  {
    return $this->getList($fields, ['sort ASC'], $where);
  }

}