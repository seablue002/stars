<?php
declare(strict_types=1);

namespace app\admin\business;

use app\common\model\mysql\ColumnExtendFieldsConfig AS ColumnExtendFieldsConfigModel;
use think\Exception;
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
    
    validate(ColumnExtendFieldsConfigValidate::class)
        ->scene('add')
        ->check($data);

    return $this->columnExtendFieldsConfigModel->insertOneData($data);
  }

  public function getConfigListWithPage($params)
  {
    $where = [];
    if ($params['name']) {
      $where[] = ['sc.name', 'LIKE', "%{$params['name']}%"];
    }

    if (count($params['create_time']) > 0) {
      $where[] = ['sc.create_time', 'BETWEEN', [strtotime($params['create_time'][0]), strtotime($params['create_time'][1])]];
    }

    $page_size = $params['page_size'];

    $res = $this->columnExtendFieldsConfigModel
      ->alias('sc')
      ->field(['sc.id', 'sc.field', 'sc.name', 'sc.props', 'sc.sort', 'sc.status', 'sc.create_time'])
      ->where($where)
      ->order('sc.sort ASC')
      ->paginate($page_size);

    $data = $res->toArray()['data'];
    $data = array_map(function ($item) {
      $item['props'] = $this->utils->parseInvalidJson($item['props']);
      return $item;
    }, $data);
    $total_page = $res->total();

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
      $config_list[$idx]['props'] = preg_replace("/\n/","", $config_list[$idx]['props']);
      $config_list[$idx]['props'] = $this->utils->parseInvalidJson($config_list[$idx]['props']);

      $config_list[$idx]['type'] = $config_list[$idx]['props']['type'] ?? 'input';
      $config_list[$idx]['title'] = $config_list[$idx]['props']['title'] ?? '';
      $config_list[$idx]['info'] = $config_list[$idx]['props']['info'] ?? [];
      $config_list[$idx]['value'] = $config_list[$idx]['props']['value'] ?? null;
      $config_list[$idx]['options'] = $config_list[$idx]['props']['options'] ?? [];
      $config_list[$idx]['validate'] = $config_list[$idx]['props']['rules'] ?? [];
      $config_list[$idx]['style'] = $config_list[$idx]['props']['style'] ?? [];
      unset($config_list[$idx]['name']);
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
    
    validate(ColumnExtendFieldsConfigValidate::class)
        ->scene('edit')
        ->check($data);

    return $this->columnExtendFieldsConfigModel->update($data);
  }

  public function getConfigDetail ($id) {
    validate(ColumnExtendFieldsConfigValidate::class)
        ->scene('detail')
        ->check(['id' => $id]);

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
