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
      'title' => input('get.title', ''),
      'create_time' => input('get.create_time', []),
      'page_size' => input('get.size', config('page.page_size'))
    ];

    $list = $this->systemConfigBusiness->getConfigListWithPage($params);
    
    return $this->responseMessage->success('系统配置列表数据获取成功', $list);
  }

  // 系统配置列表
  public function getListByCid()
  {
    $params = [
      'cid' => input('get.cid', '')
    ];

    $list = $this->systemConfigBusiness->getListByCid($params);
    
    return $this->responseMessage->success('系统配置分类列表数据获取成功', $list);
  }

  // 添加系统配置
  public function add()
  {
    $params = [
      'cid' => explode(',', input('post.cid')),
      'name' => input('post.name'),
      'field' => input('post.field'),
      'props' => input('post.props'),
      'sort' => input('post.sort', 0, 'intval'),
      'status' => input('post.status')
    ];

    $this->systemConfigBusiness->insertConfig($params);
    
    return $this->responseMessage->success('系统配置添加成功');
  }

  // 系统配置详情
  public function detail()
  {
    $id = input('get.id', 0, 'intval');
    
    $category_data = $this->systemConfigBusiness->getConfigDetail($id);
    
    return $this->responseMessage->success('系统配置详情数据获取成功', $category_data);
  }

  // 编辑系统配置
  public function edit()
  {
    // 获取请求数据
    $params = [
      'id' => input('post.id', 0, 'intval'),
      'cid' => explode(',', input('post.cid')),
      'name' => input('post.name'),
      'field' => input('post.field'),
      'props' => input('post.props'),
      'sort' => input('post.sort', 0, 'intval'),
      'status' => input('post.status')
    ];
    
    $this->systemConfigBusiness->updateConfig($params);
    
    return $this->responseMessage->success('系统配置编辑成功');
  }
  
  // 删除系统配置
  public function delete () { 
    $id = input('get.id', 0, 'intval');
    
    $this->systemConfigBusiness->deleteConfig($id);
    
    return $this->responseMessage->success('系统配置删除成功');
  }

  // 保存设置配置信息
  public function settingSave () {
    $setting_data = request()->post();
    
    $this->systemConfigBusiness->settingSave($setting_data);
    
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

    $conf_list = $this->systemConfigBusiness->getConfigValue($conf_keys);
    
    return $this->responseMessage->success('配置数据获取成功', $conf_list);
  }
}