<?php
declare(strict_types=1);

namespace app\admin\business;

use app\common\model\mysql\ColumnExtendFields AS ColumnExtendFieldsModel;
use app\admin\business\ColumnExtendFieldsConfig as ColumnExtendFieldsConfigBusiness;
use think\Exception;
use think\facade\Db;


class ColumnExtendFields
{
  private $columnExtendFieldsModel = null;
  private $columnExtendFieldsConfigBusiness = null;

  public function __construct()
  {
    $this->columnExtendFieldsModel = new ColumnExtendFieldsModel;
    $this->columnExtendFieldsConfigBusiness = new ColumnExtendFieldsConfigBusiness;

  }

  public function saveConfig($data)
  {
    $data['update_time'] = time();
    $mdl = $this->columnExtendFieldsModel
                ->where(['column_id' => $data['column_id']])
                ->field('column_id')
                ->find();
    if (empty($mdl)) {
      $this->columnExtendFieldsModel->save($data);
    } else {
      $mdl->save($data);
    }
  }

  public function addTbField ($params) {
    $sql = "ALTER TABLE web_column_extend_fields
            ADD COLUMN " . $params['field'] ." {$params['field_type']}({$params['field_length']}) NOT NULL DEFAULT '" . $params['default'] . "' comment '" . $params['comment'] ."'";

    Db::execute($sql);
  }
  public function updateTbField ($params, $old_fields_config) {
    if (empty($old_fields_config)) {
      throw new Exception('栏目自定义字段不存在');
    }

    $sql = "ALTER TABLE web_column_extend_fields
            CHANGE COLUMN " . $old_fields_config['field'] . ' ' . $params['field'] .
            " {$params['field_type']}({$params['field_length']}) NOT NULL DEFAULT '" . $params['default'] ."' comment '" .$params['comment'] ."'";

    Db::execute($sql);
  }

}
