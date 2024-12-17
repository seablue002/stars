<?php
// 系统配置分类控制器
declare(strict_types=1);
namespace app\admin\controller;

use think\App;
use app\admin\business\SystemConfigCategory AS SystemConfigCategoryBusiness;

class SystemConfigCategory extends AdminBase
{
  private $systemConfigCategoryBusiness = null;

  public function __construct(App $app)
  {
    parent::__construct($app);
    $this->systemConfigCategoryBusiness = new SystemConfigCategoryBusiness;
  }

  // 系统配置分类列表
  public function list()
  {
    try {
      $category_list = $this->systemConfigCategoryBusiness->getCategoryNormalInfoList();
    } catch (\Exception $e) {
      return $this->responseMessage->error($e->getMessage());
    }
    return $this->responseMessage->success('系统配置分类列表数据获取成功', $category_list);
  }

  // 系统配置分类列表
  public function getListByRid()
  {
    $params = [
      'rid' => input('get.rid', '')
    ];

    try {
      $label_list = $this->systemConfigCategoryBusiness->getListByRid($params);
    } catch (\Exception $e) {
      return $this->responseMessage->error('系统配置分类列表数据获取失败'.$e->getMessage());
    }
    return $this->responseMessage->success('系统配置分类列表数据获取成功', $label_list);
  }

  // 系统配置分类列表，基本信息
  public function baseList()
  {
    try {
      $category_list = $this->systemConfigCategoryBusiness->getCategoryBaseInfoList();
    } catch (\Exception $e) {
      return $this->responseMessage->error($e->getMessage());
    }
    return $this->responseMessage->success('分类基本信息列表数据获取成功', $category_list);
  }

  // 添加系统配置分类
  public function add()
  {
    // 获取请求数据
    $name          = input('post.name');
    $pid           = input('post.pid', [0]);
    $sort          = input('post.sort', 0, 'intval');
    $category_data = [
      'name' => $name,
      'pid'  => $pid,
      'sort' => $sort,
    ];
    try {
      $this->systemConfigCategoryBusiness->insertCategory($category_data);
    } catch (\Exception $e) {
      return $this->responseMessage->error($e->getMessage());
    }
    return $this->responseMessage->success('分类添加成功');
  }

  // 系统配置分类详情
  public function detail()
  {
    $id = input('get.id', 0, 'intval');
    try {
      $category_data = $this->systemConfigCategoryBusiness->getCategoryDetail($id);
    } catch (\Exception $e) {
      return $this->responseMessage->error($e->getMessage());
    }

    return $this->responseMessage->success('分类详情数据获取成功', $category_data);
  }

  // 编辑系统配置分类
  public function edit()
  {
    // 获取请求数据
    $id = input('post.id', 0, 'intval');
    $name          = input('post.name');
    $pid           = input('post.pid', [0]);
    $sort          = input('post.sort', 0, 'intval');
    $category_data = [
      'id'   => $id,
      'name' => $name,
      'pid'  => $pid,
      'sort' => $sort,
    ];
    try {
      $this->systemConfigCategoryBusiness->updateCategory($category_data);
    } catch (\Exception $e) {
      return $this->responseMessage->error($e->getMessage());
    }
    return $this->responseMessage->success('分类编辑成功');
  }
}
