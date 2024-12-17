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
      $category_list[$idx]['title'] = $category_list[$idx]['label'];
      $category_list[$idx]['info'] = ['info' => $category_list[$idx]['tips'], 'align' => 'left'];
      $category_list[$idx]['props'] = preg_replace("/\n/","", $category_list[$idx]['props']);
      $category_list[$idx]['options'] = preg_replace("/\n/","", $category_list[$idx]['options']);
      $category_list[$idx]['validate'] = preg_replace("/\n/","", $category_list[$idx]['validate_rules']);
      unset($category_list[$idx]['label']);
      unset($category_list[$idx]['tips']);
      unset($category_list[$idx]['validate_rules']);
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
    $system_config_data = array_merge($data, [
      'create_time' => time()
    ]);

    try {
      validate(SystemConfigValidate::class)
        ->scene('add')
        ->check($system_config_data);
    } catch (ValidateException $e) {
      throw new Exception($e->getMessage());
    }

    return $this->systemConfigModel->insertOneData($system_config_data);
  }

  public function getConfigListWithPage($params)
  {
    $where = [];
    if ($params['label']) {
      $where[] = ['sc.label', 'LIKE', "%{$params['label']}%"];
    }
    if ($params['cid']) {
      $where[] = ['sc.cid', '=', $params['cid']];
    }
    $page_size = $params['page_size'];

    $system_config_res = $this->systemConfigModel
      ->alias('sc')
      ->leftJoin('system_config_category scc', 'scc.id = sc.cid')
      ->field(['sc.id', 'sc.field', 'sc.label', 'sc.type', 'sc.value', 'sc.sort', 'sc.create_time', 'scc.name AS category_name'])
      ->where($where)
      ->order('sc.sort ASC')
      ->paginate($page_size);

    $data = $system_config_res->toArray()['data'];
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
    try {
      validate(SystemConfigValidate::class)
        ->scene('edit')
        ->check($data);
    } catch (ValidateException $e) {
      throw new Exception($e->getMessage());
    }
    return $this->systemConfigModel->update($data);
  }

  public function getConfigDetail ($id) {
    try {
      validate(SystemConfigValidate::class)
        ->scene('detail')
        ->check(['id' => $id]);
    } catch (ValidateException $e) {
      throw new Exception($e->getMessage());
    }
    return $this
      ->systemConfigModel
      ->where(['id' => $id])
      ->find()
      ->toArray();
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
