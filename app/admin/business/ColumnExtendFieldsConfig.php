<?php
declare(strict_types=1);

namespace app\admin\business;

use app\common\model\mysql\ColumnExtendFieldsConfig AS ColumnExtendFieldsConfigModel;
use think\Exception;
use think\db\exception\DataNotFoundException;
use think\db\exception\DbException;
use think\db\exception\ModelNotFoundException;
use app\common\lib\Utils;
use app\admin\validate\ColumnExtendFieldsConfig AS ColumnExtendFieldsConfigValidate;

class ColumnExtendFieldsConfig
{
  public $columnExtendFieldsConfigModel = null;
  private $utils = null;

  public function __construct()
  {
    $this->columnExtendFieldsConfigModel = new ColumnExtendFieldsConfigModel;
    $this->utils = new Utils;
  }

  /**
   * 插入单条栏目自定义字段配置数据
   * @param $data 栏目自定义字段配置数据
   * @return int|string
   * @throws Exception
   */
  public function insertConfig($data)
  {
    $data['create_time'] = time();
    try {
      validate(ColumnExtendFieldsConfigValidate::class)
        ->scene('add')
        ->check($data);
    } catch (ValidateException $e) {
      throw new Exception($e->getMessage());
    }

    return $this->columnExtendFieldsConfigModel->insertOneData($data);
  }

  public function getConfigListWithPage($params)
  {
    $where = [];
    if ($params['label']) {
      $where[] = ['sc.label', 'LIKE', "%{$params['label']}%"];
    }
    $page_size = $params['page_size'];

    $column_extend_fields_config_res = $this->columnExtendFieldsConfigModel
      ->alias('sc')
      ->field(['sc.id', 'sc.field', 'sc.label', 'sc.type', 'sc.value', 'sc.sort', 'sc.create_time'])
      ->where($where)
      ->order('sc.sort ASC')
      ->paginate($page_size);

    $data = $column_extend_fields_config_res->toArray()['data'];
    $total_page = $column_extend_fields_config_res->total();
    return [
      'data' => $data,
      'total' => $total_page
    ];
  }

  public function getConfigAllList()
  {
    $config_list = $this->columnExtendFieldsConfigModel
      ->alias('sc')
      ->field(['*'])
      ->order('sc.sort ASC')
      ->select();
    foreach ($config_list as $idx => $item) {
      $config_list[$idx]['title'] = $config_list[$idx]['label'];
      $config_list[$idx]['info'] = ['info' => $config_list[$idx]['tips'], 'align' => 'left'];
      $config_list[$idx]['props'] = preg_replace("/\n/","", $config_list[$idx]['props']);
      $config_list[$idx]['options'] = preg_replace("/\n/","", $config_list[$idx]['options']);
      $config_list[$idx]['validate'] = preg_replace("/\n/","", $config_list[$idx]['validate_rules']);
      unset($config_list[$idx]['label']);
      unset($config_list[$idx]['tips']);
      unset($config_list[$idx]['validate_rules']);
    }
    return $config_list;
  }

  /**
   * 获取栏目自定义字段配置特定字段数据列表
   * @param $fields 需要获取的数据字段
   * @return array
   * @throws DataNotFoundException
   * @throws DbException
   * @throws ModelNotFoundException
   */
  public function getConfigInfoList($fields)
  {
    return $this->columnExtendFieldsConfigModel->getConfigInfoList($fields);
  }

  public function updateConfig($data)
  {
    $data['update_time'] = time();
    try {
      validate(ColumnExtendFieldsConfigValidate::class)
        ->scene('edit')
        ->check($data);
    } catch (ValidateException $e) {
      throw new Exception($e->getMessage());
    }
    return $this->columnExtendFieldsConfigModel->update($data);
  }

  public function getConfigDetail ($id) {
    try {
      validate(ColumnExtendFieldsConfigValidate::class)
        ->scene('detail')
        ->check(['id' => $id]);
    } catch (ValidateException $e) {
      throw new Exception($e->getMessage());
    }
    return $this
      ->columnExtendFieldsConfigModel
      ->where(['id' => $id])
      ->find()
      ->toArray();
  }

  public function settingSave ($params) {
    foreach ($params as $k => $v) {
      $this
        ->columnExtendFieldsConfigModel
        ->where(['field' => $k])
        ->save(['value' => $v]);
    }
  }

}
