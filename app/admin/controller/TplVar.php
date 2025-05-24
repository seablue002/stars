<?php

// 模板变量控制器
declare(strict_types=1);

namespace app\admin\controller;

use think\App;
use app\admin\business\TplVar AS TplBusiness;

class TplVar extends AdminBase
{
  private $tplBusiness = null;

  public function __construct(App $app)
  {
    parent::__construct($app);
    $this->tplBusiness = new TplBusiness;
  }

  // 模板变量列表
  public function list()
  {
    $params = [
      'category_id' => input('get.category_id', ''),
      'var_key' => input('get.var_key'),
      'var_name' => input('get.var_name', ''),
      'create_time' => input('get.create_time', []),
      'page_size' => input('get.size', config('page.page_size'))
    ];

    $name_list = $this->tplBusiness->getTplListWithPage($params);
    
    return $this->responseMessage->success('模板变量列表数据获取成功', $name_list);
  }

  // 添加模板变量
  public function add()
  {
    // 获取请求数据
    $data = [
      'category_id' => input('post.category_id'),
      'var_key' => input('post.var_key'),
      'var_name' => input('post.var_name'),
      'var_value' => str_replace("\xC2\xA0","", input('post.var_value'))
    ];

    $this->tplBusiness->insertTpl($data);
    
    return $this->responseMessage->success('模板变量添加成功');
  }

  // 模板变量详情
  public function detail()
  {
    $id = input('get.id', 0, 'intval');
    
    $category_data = $this->tplBusiness->getTplDetail($id);
    
    return $this->responseMessage->success('模板变量详情数据获取成功', $category_data);
  }

  // 编辑模板变量
  public function edit()
  {
    // 获取请求数据
    $data = [
      'id' => input('post.id', 0, 'intval'),
      'category_id' => input('post.category_id'),
      'var_key' => input('post.var_key'),
      'var_name' => input('post.var_name'),
      'var_value' => str_replace("\xC2\xA0","", input('post.var_value'))
    ];
    
    $this->tplBusiness->updateTpl($data);
    
    return $this->responseMessage->success('模板变量编辑成功');
  }
}