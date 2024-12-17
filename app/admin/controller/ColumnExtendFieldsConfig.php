<?php
// 栏目自定义字段配置控制器
declare(strict_types=1);
namespace app\admin\controller;

use think\App;
use app\admin\business\ColumnExtendFieldsConfig AS ColumnExtendFieldsConfigBusiness;
use app\admin\business\ColumnExtendFields AS ColumnExtendFieldsBusiness;
use think\facade\Db;

class ColumnExtendFieldsConfig extends AdminBase
{
  private $columnExtendFieldsConfigBusiness = null;
  private $columnExtendFieldsBusiness = null;

  public function __construct(App $app)
  {
    parent::__construct($app);
    $this->columnExtendFieldsConfigBusiness = new ColumnExtendFieldsConfigBusiness;
    $this->columnExtendFieldsBusiness = new ColumnExtendFieldsBusiness;
  }

  // 栏目自定义字段配置列表
  public function list()
  {
    $params = [
      'label' => input('get.label', ''),
      'page_size' => input('get.size', config('page.page_size'))
    ];

    try {
      $config_list = $this->columnExtendFieldsConfigBusiness->getConfigListWithPage($params);
    } catch (\Exception $e) {
      return $this->responseMessage->error('栏目自定义字段配置列表数据获取失败');
    }
    return $this->responseMessage->success('栏目自定义字段配置列表数据获取成功', $config_list);
  }

  // 栏目自定义字段配置列表
  public function allList()
  {
    try {
      $config_list = $this->columnExtendFieldsConfigBusiness->getConfigAllList();
    } catch (\Exception $e) {
      return $this->responseMessage->error('栏目自定义字段配置列表数据获取失败');
    }
    return $this->responseMessage->success('栏目自定义字段配置列表数据获取成功', $config_list);
  }

  // 添加栏目自定义字段配置
  public function add()
  {
    // 获取请求数据
    $category_data = [
      'field' => input('post.field'),
      'field_type' => input('post.field_type'),
      'field_length' => input('post.field_length'),
      'label' => input('post.label'),
      'tips' => input('post.tips'),
      'type' => input('post.type'),
      'props' => input('post.props'),
      'options' => input('post.options'),
      'value' => input('post.value'),
      'validate_rules' => input('post.validate_rules'),
      'sort' => input('post.sort', 0, 'intval'),
      'status' => input('post.status')
    ];

    try {
      Db::startTrans();
      $this->columnExtendFieldsConfigBusiness->insertConfig($category_data);

      $field_info = [
        'field' => $category_data['field'],
        'field_type' => $category_data['field_type'],
        'field_length' => $category_data['field_length'],
        'comment' => $category_data['label'],
        'default' => $category_data['value']
      ];
      $this->columnExtendFieldsBusiness->addTbField($field_info);
      Db::commit();
    } catch (\Exception $e) {
      Db::rollback();
      return $this->responseMessage->error($e->getMessage());
    }
    return $this->responseMessage->success('栏目自定义字段配置添加成功');
  }

  // 栏目自定义字段配置详情
  public function detail()
  {
    $id = input('get.id', 0, 'intval');
    try {
      $category_data = $this->columnExtendFieldsConfigBusiness->getConfigDetail($id);
    } catch (\Exception $e) {
      return $this->responseMessage->error($e->getMessage());
    }

    return $this->responseMessage->success('栏目自定义字段配置详情数据获取成功', $category_data);
  }

  // 编辑栏目自定义字段配置
  public function edit()
  {
    // 获取请求数据
    $category_data = [
      'id' => input('post.id', 0, 'intval'),
      'field' => input('post.field'),
      'field_type' => input('post.field_type'),
      'field_length' => input('post.field_length'),
      'label' => input('post.label'),
      'tips' => input('post.tips'),
      'type' => input('post.type'),
      'props' => input('post.props'),
      'options' => input('post.options'),
      'value' => input('post.value'),
      'validate_rules' => input('post.validate_rules'),
      'sort' => input('post.sort', 0, 'intval'),
      'status' => input('post.status')
    ];
    try {
      Db::startTrans();
      $old_fields_config = $this->columnExtendFieldsConfigBusiness
        ->columnExtendFieldsConfigModel
        ->field('field')
        ->where(['id' => $category_data['id']])
        ->find();

      $this->columnExtendFieldsConfigBusiness->updateConfig($category_data);
      $field_info = [
        'field' => $category_data['field'],
        'field_type' => $category_data['field_type'],
        'field_length' => $category_data['field_length'],
        'comment' => $category_data['label'],
        'default' => $category_data['value']
      ];
      $this->columnExtendFieldsBusiness->updateTbField($field_info, $old_fields_config);
      Db::commit();
    } catch (\Exception $e) {
      Db::rollback();
      return $this->responseMessage->error($e->getMessage());
    }
    return $this->responseMessage->success('栏目自定义字段配置编辑成功');
  }

  // 保存设置配置信息
  public function settingSave () {
    $setting_data = request()->post();
    try {
      $this->columnExtendFieldsConfigBusiness->settingSave($setting_data);
    } catch (\Exception $e) {
      return $this->responseMessage->error('保存失败');
    }
    return $this->responseMessage->success('保存成功');
  }
}