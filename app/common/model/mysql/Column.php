<?php
/*栏目（菜单）模型*/
namespace app\common\model\mysql;


class Column extends BaseModel
{
  public function getColumnNormalInfoList() {
    return $this
      ->alias('c1')
      ->leftJoin('column c2', 'c1.pid = c2.id')
      ->field(['c1.id', 'c1.name', 'c1.is_last', 'c1.is_show_in_nav', 'c1.cover_url', 'c1.parent_dir_path', 'c1.column_dir_path', 'c1.pid', 'c2.name as p_name'])
      ->order('c1.sort ASC')
      ->select()
      ->toArray();
  }
    public function getColumnInfoList($fields = '*', $where = [])
  {
    return $this->getList($fields, ['sort ASC'], $where);
  }

  public function extendFields()
  {
    return $this->hasOne(ColumnExtendFields::class,'column_id');
  }

}