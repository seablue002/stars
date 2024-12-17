<?php

namespace app\home\business;
use app\common\lib\Utils;
use app\common\model\mysql\Column AS ColumnModel;

class Column
{
  private $columnModel = null;
  private $utils = null;

  public function __construct()
  {
    $this->columnModel = new ColumnModel;
    $this->utils = new Utils;
  }

  public function getColumnNormalInfoList()
  {
    $column_list = $this->columnModel->getColumnNormalInfoList();
    $temp_column_list = [];
    $this->utils->getChildrenCategory($temp_column_list, $column_list, 0);

    return $temp_column_list;
  }

  public function getColumnDetail ($where, $data) {
    return $this->columnModel->whereRaw($where, $data)->find();
  }
}
