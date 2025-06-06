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
    $category_list = $this->systemConfigCategoryBusiness->getCategoryNormalInfoList();
    
    return $this->responseMessage->success('系统配置分类列表数据获取成功', $category_list);
  }

  // 系统配置分类列表
  public function getListByRid()
  {
    $params = [
      'rid' => input('get.rid', '')
    ];

    $label_list = $this->systemConfigCategoryBusiness->getListByRid($params);
    
    return $this->responseMessage->success('系统配置分类列表数据获取成功', $label_list);
  }

  // 系统配置分类列表，基本信息
  public function baseList()
  {
    $category_list = $this->systemConfigCategoryBusiness->getCategoryBaseInfoList();
    
    return $this->responseMessage->success('分类基本信息列表数据获取成功', $category_list);
  }

  // 添加系统配置分类
  public function add()
  {
    // 获取请求数据
    $params = [
      'name' => input('post.name'),
      'pid'  => explode(',', input('post.pid')),
      'sort' => input('post.sort', 0, 'intval'),
    ];
    
    $this->systemConfigCategoryBusiness->insertCategory($params);
    
    return $this->responseMessage->success('分类添加成功');
  }

  // 系统配置分类详情
  public function detail()
  {
    $id = input('get.id', 0, 'intval');
    
    $category_data = $this->systemConfigCategoryBusiness->getCategoryDetail($id);
    
    return $this->responseMessage->success('分类详情数据获取成功', $category_data);
  }

  // 编辑系统配置分类
  public function edit()
  {
    // 获取请求数据
    $id = input('post.id', 0, 'intval');
    $name          = input('post.name');
    $pid           = explode(',', input('post.pid'));
    $sort          = input('post.sort', 0, 'intval');
    $category_data = [
      'id'   => $id,
      'name' => $name,
      'pid'  => $pid,
      'sort' => $sort,
    ];
    
    $this->systemConfigCategoryBusiness->updateCategory($category_data);
    
    return $this->responseMessage->success('分类编辑成功');
  }

  // 删除系统配置分类
  public function delete()
  {
    $id = input('get.id', 0, 'intval');
    
    $this->systemConfigCategoryBusiness->deleteCategory($id);
    
    return $this->responseMessage->success('分类删除成功');
  }
}
