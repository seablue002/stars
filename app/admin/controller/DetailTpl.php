<?php

// 详情模板控制器
declare(strict_types=1);

namespace app\admin\controller;

use app\common\business\Upload as UploadBusiness;
use think\App;
use app\admin\business\DetailTpl AS TplBusiness;

class DetailTpl extends AdminBase
{
  private $tplBusiness = null;
  private $uploadBusiness = null;

  public function __construct(App $app)
  {
    parent::__construct($app);
    $this->tplBusiness = new TplBusiness;
    $this->uploadBusiness = new UploadBusiness;
  }

  // 详情模板列表
  public function list()
  {
    $params = [
      'category_id' => input('get.category_id', ''),
      'name' => input('get.name', ''),
      'page_size' => input('get.size', config('page.page_size'))
    ];

    try {
      $name_list = $this->tplBusiness->getTplListWithPage($params);
    } catch (\Exception $e) {
      return $this->responseMessage->error('详情模板列表数据获取失败' . $e->getMessage());
    }
    return $this->responseMessage->success('详情模板列表数据获取成功', $name_list);
  }

  // 详情模板列表，基本信息
  public function baseList()
  {
    $tpl_list = $this->tplBusiness->getTplBaseInfoList();
    return $this->responseMessage->success('详情模板列表数据获取成功', $tpl_list);
  }

  // 详情模板列表
  public function getListByCid()
  {
    $params = [
      'category_id' => input('get.category_id', '')
    ];

    try {
      $name_list = $this->tplBusiness->getListByCid($params);
    } catch (\Exception $e) {
      return $this->responseMessage->error('详情模板列表数据获取失败' . $e->getMessage());
    }
    return $this->responseMessage->success('详情模板列表数据获取成功', $name_list);
  }

  // 添加详情模板
  public function add()
  {
    // 获取请求数据
    $data = [
      'category_id' => input('post.category_id'),
      'name' => input('post.name'),
      'content' => str_replace("\xC2\xA0","", input('post.content'))
    ];

    try {
      $this->tplBusiness->insertTpl($data);
    } catch (\Exception $e) {
      return $this->responseMessage->error($e->getMessage());
    }
    return $this->responseMessage->success('详情模板添加成功');
  }

  // 详情模板详情
  public function detail()
  {
    $id = input('get.id', 0, 'intval');
    try {
      $category_data = $this->tplBusiness->getTplDetail($id);
    } catch (\Exception $e) {
      return $this->responseMessage->error($e->getMessage());
    }

    return $this->responseMessage->success('详情模板详情数据获取成功', $category_data);
  }

  // 编辑详情模板
  public function edit()
  {
    // 获取请求数据
    $data = [
      'id' => input('post.id', 0, 'intval'),
      'category_id' => input('post.category_id'),
      'name' => input('post.name'),
      'content' => str_replace("\xC2\xA0","", input('post.content')),
      'model_tb_name' => input('post.model_tb_name')
    ];
    try {
      $this->tplBusiness->updateTpl($data);
    } catch (\Exception $e) {
      return $this->responseMessage->error($e->getMessage());
    }
    return $this->responseMessage->success('详情模板编辑成功');
  }

  public function tplContentPicUpload()
  {
    $path = request()->domain() . "/storage/";
    $file_url = $this->uploadBusiness->uploadFile(request()->file('file'), $path, 'tpl_content');
    return $this->responseMessage->success('图片上传成功', $file_url);
  }
}