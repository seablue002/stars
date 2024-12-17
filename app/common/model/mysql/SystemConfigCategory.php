<?php


namespace app\common\model\mysql;


class SystemConfigCategory extends BaseModel
{
  protected $table = 'web_system_config_category';

  public function getCategoryNormalInfoList() {
    return $this
      ->alias('c1')
      ->leftJoin('system_config_category c2', 'c1.pid = c2.id')
      ->field(['c1.id', 'c1.name', 'c1.pid', 'c2.name as p_name'])
      ->select()
      ->toArray();
  }
  public function getCategoryInfoList($fields = '*', $where = [])
  {
    return $this->getList($fields, [], $where);
  }

}
