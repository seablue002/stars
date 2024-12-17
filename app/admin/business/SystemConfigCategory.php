<?php
declare(strict_types=1);

namespace app\admin\business;

use app\common\model\mysql\SystemConfigCategory AS SystemConfigCategoryModel;
use think\Exception;
use think\db\exception\DataNotFoundException;
use think\db\exception\DbException;
use think\db\exception\ModelNotFoundException;
use app\common\lib\Utils;
use app\admin\validate\SystemConfigCategory AS SystemConfigCategoryValidate;

class SystemConfigCategory
{
  private $systemConfigCategoryModel = null;
  private $utils = null;

  public function __construct()
  {
    $this->systemConfigCategoryModel = new SystemConfigCategoryModel;
    $this->utils = new Utils;
  }

  /**
   * 插入单条系统配置分类数据
   * @param $data 系统配置分类数据
   * @return int|string
   * @throws Exception
   */
  public function insertCategory($data)
  {
    $pid_len = count($data['pid']);
    if ($pid_len > 1) {
      $data['rid'] = $data['pid'][1];
    } else if ($pid_len === 1){
      $data['rid'] = $data['pid'][0];
    }
    $data['pid'] = $data['pid'][$pid_len - 1];
    $system_config_category_data = array_merge($data, [
      'create_time' => time()
    ]);
    try {
      validate(SystemConfigCategoryValidate::class)
        ->scene('add')
        ->check($system_config_category_data);
    } catch (ValidateException $e) {
      throw new Exception($e->getMessage());
    }

    return $this->systemConfigCategoryModel->insertOneData($system_config_category_data);
  }

  /**
   * 获取系统配置分类基本信息(id, name, pid)数据列表
   * @return array
   * @throws DataNotFoundException
   * @throws DbException
   * @throws ModelNotFoundException
   */
  public function getCategoryBaseInfoList()
  {
    $category_list = $this->getCategoryInfoList(['id', 'name', 'pid']);
    $temp_category_list = [];
    $this->utils->getChildrenCategory($temp_category_list, $category_list, 0);
    return $temp_category_list;
  }

  public function getCategoryNormalInfoList()
  {
    $category_list = $this->systemConfigCategoryModel->getCategoryNormalInfoList();
    $temp_category_list = [];
    $this->utils->getChildrenCategory($temp_category_list, $category_list, 0);

    return $temp_category_list;
  }

  public function getListByRid($params)
  {
    $where = [];
    if ($params['rid']) {
      $where[] = ['rid', '=', $params['rid']];
    }
    $category_list = $this->systemConfigCategoryModel->where($where)->order('sort ASC')->select();

    return $category_list;
  }

  /**
   * 获取系统配置分类特定字段数据列表
   * @param $fields 需要获取的数据字段
   * @return array
   * @throws DataNotFoundException
   * @throws DbException
   * @throws ModelNotFoundException
   */
  public function getCategoryInfoList($fields)
  {
    return $this->systemConfigCategoryModel->getCategoryInfoList($fields);
  }

  public function updateCategory($data)
  {
    if (is_array($data['pid'])) {
      $pid_len = count($data['pid']);
      if ($pid_len > 1) {
        $data['rid'] = $data['pid'][1];
      } else if ($pid_len === 1) {
        $data['rid'] = $data['pid'][0];
      }
      $data['pid'] = $data['pid'][$pid_len - 1];
    }

    $data = array_merge($data, ['update_time' => time()]);
    try {
      validate(SystemConfigCategoryValidate::class)
        ->scene('edit')
        ->check($data);
    } catch (ValidateException $e) {
      throw new Exception($e->getMessage());
    }
    return $this->systemConfigCategoryModel->update($data);
  }

  public function getCategoryDetail ($id) {
    try {
      validate(SystemConfigCategoryValidate::class)
        ->scene('detail')
        ->check(['id' => $id]);
    } catch (ValidateException $e) {
      throw new Exception($e->getMessage());
    }
    return $this
      ->systemConfigCategoryModel
      ->where(['id' => $id])
      ->find()
      ->toArray();
  }
}
