<?php
// 系统配置控制器
declare(strict_types=1);
namespace app\admin\controller;

use think\App;
use app\admin\business\SystemConfig AS SystemConfigBusiness;

class SystemConfig extends AdminBase
{
  private $systemConfigBusiness = null;

  public function __construct(App $app)
  {
    parent::__construct($app);
    $this->systemConfigBusiness = new SystemConfigBusiness;
  }

  // 系统配置列表
  public function list()
  {
    $params = [
      'cid' => input('get.cid', ''),
      'label' => input('get.label', ''),
      'page_size' => input('get.size', config('page.page_size'))
    ];

    try {
      $label_list = $this->systemConfigBusiness->getConfigListWithPage($params);
    } catch (\Exception $e) {
      return $this->responseMessage->error('系统配置列表数据获取失败');
    }
    return $this->responseMessage->success('系统配置列表数据获取成功', $label_list);
  }

  // 系统配置列表
  public function getListByCid()
  {
    $params = [
      'cid' => input('get.cid', '')
    ];

    try {
      $label_list = $this->systemConfigBusiness->getListByCid($params);
    } catch (\Exception $e) {
      return $this->responseMessage->error('系统配置分类列表数据获取失败'.$e->getMessage());
    }
    return $this->responseMessage->success('系统配置分类列表数据获取成功', $label_list);
  }

  // 添加系统配置
  public function add()
  {
    // 获取请求数据
    $cid = input('post.cid', 0, 'intval');
    $field = input('post.field');
    $label = input('post.label');
    $tips = input('post.tips');
    $type = input('post.type');
    $props = input('post.props');
    $options = input('post.options');
    $value = input('post.value');
    $validate_rules = input('post.validate_rules');
    $sort = input('post.sort', 0, 'intval');
    $status = input('post.status');
    $category_data = [
      'cid' => $cid,
      'field' => $field,
      'label' => $label,
      'tips' => $tips,
      'type' => $type,
      'props' => $props,
      'options' => $options,
      'value' => $value,
      'validate_rules' => $validate_rules,
      'sort' => $sort,
      'status' => $status
    ];

    try {
      $this->systemConfigBusiness->insertConfig($category_data);
    } catch (\Exception $e) {
      return $this->responseMessage->error($e->getMessage());
    }
    return $this->responseMessage->success('系统配置添加成功');
  }

  // 系统配置详情
  public function detail()
  {
    $id = input('get.id', 0, 'intval');
    try {
      $category_data = $this->systemConfigBusiness->getConfigDetail($id);
    } catch (\Exception $e) {
      return $this->responseMessage->error($e->getMessage());
    }

    return $this->responseMessage->success('系统配置详情数据获取成功', $category_data);
  }

  // 编辑系统配置
  public function edit()
  {
    // 获取请求数据
    $id = input('post.id', 0, 'intval');
    // 获取请求数据
    $cid = input('post.cid', 0, 'intval');
    $field = input('post.field');
    $label = input('post.label');
    $tips = input('post.tips');
    $type = input('post.type');
    $props = input('post.props');
    $options = input('post.options');
    $value = input('post.value');
    $validate_rules = input('post.validate_rules');
    $sort = input('post.sort', 0, 'intval');
    $status = input('post.status');
    $category_data = [
      'id' => $id,
      'cid' => $cid,
      'field' => $field,
      'label' => $label,
      'tips' => $tips,
      'type' => $type,
      'props' => $props,
      'options' => $options,
      'value' => $value,
      'validate_rules' => $validate_rules,
      'sort' => $sort,
      'status' => $status
    ];
    try {
      $this->systemConfigBusiness->updateConfig($category_data);
    } catch (\Exception $e) {
      return $this->responseMessage->error($e->getMessage());
    }
    return $this->responseMessage->success('系统配置编辑成功');
  }

  // 保存设置配置信息
  public function settingSave () {
    $setting_data = request()->post();
    try {
      $this->systemConfigBusiness->settingSave($setting_data);
    } catch (\Exception $e) {
      return $this->responseMessage->error('保存失败');
    }
    return $this->responseMessage->success('保存成功');
  }

  public function getSystemConfig () {
    // 允许前端访问的系统配置项列表
    $VALID_CONF_KEYS = [
      'app_name',
      'copyright'
    ];

    $conf_keys = input('get.config_keys', []);

    foreach ($conf_keys as $conf_key) {
      if (!in_array($conf_key, $VALID_CONF_KEYS)) {
        return $this->responseMessage->error('配置数据获取失败');
      }
    }

    try {
      $conf_list = $this->systemConfigBusiness->getConfigValue($conf_keys);
    } catch (\Exception $e) {
      return $this->responseMessage->error('配置数据获取失败');
    }
    return $this->responseMessage->success('配置数据获取成功', $conf_list);
  }
}