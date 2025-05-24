<?php
// 列表模板控制器
declare(strict_types=1);
namespace app\admin\controller;

use app\common\business\Upload as UploadBusiness;
use think\App;
use app\admin\business\ListTpl AS TplBusiness;

class ListTpl extends AdminBase
{
  private $tplBusiness = null;
  private $uploadBusiness = null;

  public function __construct(App $app)
  {
    parent::__construct($app);
    $this->tplBusiness = new TplBusiness;
    $this->uploadBusiness = new UploadBusiness;
  }

  // 列表模板列表
  public function list()
  {
    $params = [
      'category_id' => input('get.category_id', ''),
      'name' => input('get.name', ''),
      'create_time' => input('get.create_time', []),
      'page_size' => input('get.size', config('page.page_size'))
    ];

    $list = $this->tplBusiness->getTplListWithPage($params);
    
    return $this->responseMessage->success('列表模板列表数据获取成功', $list);
  }

  // 列表模板列表，基本信息
  public function baseList()
  {
    $tpl_list = $this->tplBusiness->getTplBaseInfoList();
    return $this->responseMessage->success('列表模板列表数据获取成功', $tpl_list);
  }

  // 添加列表模板
  public function add()
  {
    // 获取请求数据
    $data = [
      'category_id' => input('post.category_id'),
      'name' => input('post.name'),
      'content' => str_replace("\xC2\xA0","", input('post.content')),
      'item_var' => str_replace("\xC2\xA0","", input('post.item_var'))
    ];

    $this->tplBusiness->insertTpl($data);
    
    return $this->responseMessage->success('列表模板添加成功');
  }

  // 列表模板详情
  public function detail()
  {
    $id = input('get.id', 0, 'intval');
    $category_data = $this->tplBusiness->getTplDetail($id);
    

    return $this->responseMessage->success('列表模板详情数据获取成功', $category_data);
  }

  // 编辑列表模板
  public function edit()
  {
    // 获取请求数据
    $data = [
      'id' => input('post.id', 0, 'intval'),
      'category_id' => input('post.category_id'),
      'name' => input('post.name'),
      'content' => str_replace("\xC2\xA0","", input('post.content')),
      'item_var' => str_replace("\xC2\xA0","", input('post.item_var')),
      'model_tb_name'  => input('post.model_tb_name')
    ];
    $this->tplBusiness->updateTpl($data);
    
    return $this->responseMessage->success('列表模板编辑成功');
  }

  public function tplContentPicUpload () {
    $path = request()->domain() . "/storage/";
    $file_url = $this->uploadBusiness->uploadFile(request()->file('file'), $path, 'tpl_content');
    return $this->responseMessage->success('图片上传成功', $file_url);
  }
}