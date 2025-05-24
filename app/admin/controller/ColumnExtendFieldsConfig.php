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
      'name' => input('get.name', ''),
      'create_time' => input('get.create_time', []),
      'page_size' => input('get.size', config('page.page_size'))
    ];

    $config_list = $this->columnExtendFieldsConfigBusiness->getConfigListWithPage($params);

    return $this->responseMessage->success('栏目自定义字段配置列表数据获取成功', $config_list);
  }

  // 栏目自定义字段配置列表
  public function allList()
  {
    $config_list = $this->columnExtendFieldsConfigBusiness->getConfigAllList();

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
      'name' => input('post.name'),
      'props' => input('post.props'),
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
        'comment' => $category_data['name'],
        'default' => $category_data['value']
      ];
      $this->columnExtendFieldsBusiness->addTbField($field_info);
      Db::commit();
    } catch (\Throwable $e) {
      Db::rollback();
      throw $e;
    }
    return $this->responseMessage->success('栏目自定义字段配置添加成功');
  }

  // 栏目自定义字段配置详情
  public function detail()
  {
    $id = input('get.id', 0, 'intval');

    $category_data = $this->columnExtendFieldsConfigBusiness->getConfigDetail($id);
    $category_data['props'] = json_decode($category_data['props'], true);

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
      'name' => input('post.name'),
      'props' => input('post.props'),
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

      $props = json_decode($category_data['props'], true);
      $field_info = [
        'field' => $category_data['field'],
        'field_type' => $category_data['field_type'],
        'field_length' => $category_data['field_length'],
        'comment' => $category_data['name'],
        'default' => $props['value']
      ];
      $this->columnExtendFieldsBusiness->updateTbField($field_info, $old_fields_config);
      Db::commit();
    } catch (\Throwable $e) {
      Db::rollback();
      throw $e;
    }
    return $this->responseMessage->success('栏目自定义字段配置编辑成功');
  }

  // 保存设置配置信息
  public function settingSave () {
    $setting_data = request()->post();

    $this->columnExtendFieldsConfigBusiness->settingSave($setting_data);

    return $this->responseMessage->success('保存成功');
  }
}