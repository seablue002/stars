<?php
declare(strict_types=1);

namespace app\admin\business;

use app\common\model\mysql\SystemConfig AS SystemConfigModel;
use think\Exception;
use think\db\exception\DataNotFoundException;
use think\db\exception\DbException;
use think\db\exception\ModelNotFoundException;
use app\common\lib\Utils;
use think\facade\Db;
use app\admin\validate\SystemConfig AS SystemConfigValidate;

class SystemConfig
{
  private $systemConfigModel = null;
  private $utils = null;

  public function __construct()
  {
    $this->systemConfigModel = new SystemConfigModel;
    $this->utils = new Utils;
  }

  public function getListByCid($params)
  {
    $where = [];
    if ($params['cid']) {
      $where[] = ['cid', '=', $params['cid']];
    }

    $category_list = $this->systemConfigModel->where($where)->order('sort ASC')->select();
    
    foreach ($category_list as $idx => $item) {
      $category_list[$idx]['props'] = preg_replace("/\n/","", $category_list[$idx]['props']);
      $props = $this->utils->parseInvalidJson($category_list[$idx]['props']);

      $category_list[$idx]['type'] = $props['type'] ?? 'input';
      $category_list[$idx]['title'] = $props['title'] ?? '';
      $category_list[$idx]['info'] = $props['info'] ?? [];
      $category_list[$idx]['options'] = $props['options'] ?? [];
      $category_list[$idx]['validate'] = $props['rules'] ?? [];
      $category_list[$idx]['style'] = $props['style'] ?? [];

      $props['value'] = empty($category_list[$idx]['value']) ? ($props['value'] ?? null) : $category_list[$idx]['value'];

      $category_list[$idx]['props'] = $props;
    }

    return $category_list;
  }

  /**
   * 插入单条系统配置数据
   * @param $data 系统配置数据
   * @return int|string
   * @throws Exception
   */
  public function insertConfig($data)
  {
    $data = array_merge($data, [
      'create_time' => time()
    ]);

    $cid_len = count($data['cid']);
    $data['cid'] = (int)$data['cid'][$cid_len - 1];

    validate(SystemConfigValidate::class)
        ->scene('add')
        ->check($data);

    return $this->systemConfigModel->insertOneData($data);
  }

  public function getConfigListWithPage($params)
  {
    $where = [];
    if ($params['title']) {
      $where[] = ['sc.props', 'LIKE', '%"title":"%'.$params['title'].'"%'];
    }
    if ($params['cid']) {
      $where[] = ['sc.cid', '=', $params['cid']];
    }
    if (count($params['create_time']) > 0) {
      $where[] = ['sc.create_time', 'BETWEEN', [strtotime($params['create_time'][0]), strtotime($params['create_time'][1])]];
    }
    $page_size = $params['page_size'];

    $system_config_res = $this->systemConfigModel
      ->alias('sc')
      ->leftJoin('system_config_category scc', 'scc.id = sc.cid')
      ->field(['sc.id', 'sc.name', 'sc.field', 'sc.props', 'sc.value', 'sc.sort', 'sc.status', 'sc.create_time', 'scc.name AS category_name'])
      ->where($where)
      ->order('sc.sort ASC')
      ->paginate($page_size);
    $data = $system_config_res->toArray()['data'];

    foreach ($data as $idx => $item) {
      $props = preg_replace("/\n/","", $data[$idx]['props']);
      $props = $this->utils->parseInvalidJson($props);
      
      $data[$idx]['default_value'] = $props['value'] ?? '';
      unset($data[$idx]['props']);
    }

    
    $total_page = $system_config_res->total();
    return [
      'data' => $data,
      'total' => $total_page
    ];
  }

  /**
   * 获取系统配置特定字段数据列表
   * @param $fields 需要获取的数据字段
   * @return array
   * @throws DataNotFoundException
   * @throws DbException
   * @throws ModelNotFoundException
   */
  public function getConfigInfoList($fields)
  {
    return $this->systemConfigModel->getConfigInfoList($fields);
  }

  public function getListByCategoryGroup()
  {
    $system_config_list = [];
    $system_config_category_lst = Db::name('system_config_category')->field(['id', 'name'])->order('sort ASC')->select();
    foreach ($system_config_category_lst as $system_config_category) {
      $system_config_category['children'] = $this->systemConfigModel->where(['cid' => $system_config_category['id']])->field(['id', 'name'])->order('sort ASC')->select();
      $system_config_list[] = $system_config_category;
    }
    return $system_config_list;
  }

  public function updateConfig($data)
  {
    $data = array_merge($data, ['update_time' => time()]);

    $cid_len = count($data['cid']);
    $data['cid'] = (int)$data['cid'][$cid_len - 1];
    

    validate(SystemConfigValidate::class)
      ->scene('edit')
      ->check($data);

    return $this->systemConfigModel->update($data);
  }

  public function getConfigDetail ($id) {
    validate(SystemConfigValidate::class)
      ->scene('detail')
      ->check(['id' => $id]);

    $detail = $this
      ->systemConfigModel
      ->where(['id' => $id])
      ->field('id, cid, name, field, props, sort, status')
      ->find()
      ->toArray();

    $props = $this->utils->parseInvalidJson($detail['props']);

    $detail['props'] = $props;
    return $detail;
  }

  public function deleteConfig($id)
  {
    validate(SystemConfigValidate::class)
      ->scene('delete')
      ->check(['id' => $id]);

    return $this->systemConfigModel->where(['id' => $id])->delete();
  }

  public function settingSave ($params) {
    foreach ($params as $k => $v) {
      $this
        ->systemConfigModel
        ->where(['field' => $k])
        ->save(['value' => $v]);
    }
  }

  public function getConfigValue ($configKeys) {
    $configList = [];
    foreach ($configKeys as $configKey) {
      $sql = "SELECT
            field
            , value
            FROM web_system_config
            WHERE field = '{$configKey}'
            LIMIT 1";
      $configList[$configKey] = Db::query($sql)[0];
    }


    return $configList;
  }

}
