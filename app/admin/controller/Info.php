<?php
// 信息控制器
declare(strict_types=1);
namespace app\admin\controller;

use think\App;
use app\admin\business\Info AS InfoBusiness;
use app\common\business\Upload AS UploadBusiness;
use think\facade\Filesystem;
use think\file\UploadedFile;

class Info extends AdminBase
{
  private $infoBusiness = null;
  private $uploadBusiness = null;

  public function __construct(App $app)
  {
    parent::__construct($app);
    $this->infoBusiness = new InfoBusiness;
    $this->uploadBusiness = new UploadBusiness;
  }

  // 信息列表
  public function list()
  {
    $params = [
      'column_id' => input('get.column_id', ''),
      'title' => input('get.title', ''),
      'page_size' => input('get.size', config('page.page_size'))
    ];

    try {
      $title_list = $this->infoBusiness->getInfoListWithPage($params);
    } catch (\Exception $e) {
      return $this->responseMessage->error('信息列表数据获取失败'.$e->getMessage());
    }
    return $this->responseMessage->success('信息列表数据获取成功', $title_list);
  }

  // 信息列表
  public function getListByCid()
  {
    $params = [
      'column_id' => input('get.column_id', '')
    ];

    try {
      $title_list = $this->infoBusiness->getListByCid($params);
    } catch (\Exception $e) {
      return $this->responseMessage->error('信息列表数据获取失败'.$e->getMessage());
    }
    return $this->responseMessage->success('信息列表数据获取成功', $title_list);
  }

  // 添加信息
  public function add()
  {
    // 获取请求数据
    $data = [
      'column_id' => explode(',', input('post.column_id')),
      'title' => input('post.title'),
      'sub_title' => input('post.sub_title'),
      'content' => input('post.content'),
      'title_url' => input('post.title_url'),
      'label_ids' => input('post.label'),
      'publish_time' => input('post.publish_time')
    ];

    try {
      if (isset($_FILES['cover']) && !empty($_FILES['cover'])) {
        $cover_file                 = new UploadedFile($_FILES['cover']['tmp_name'], $_FILES['cover']['name'], $_FILES['cover']['type'], $_FILES['cover']['error']);
        $url                       = Filesystem::disk('public')->putFile('info_cover', $cover_file);
        $data['cover_url'] = str_replace('\\','/', $url);;
      }
      $this->infoBusiness->insertInfo($data);
    } catch (\Exception $e) {
      return $this->responseMessage->error($e->getMessage());
    }
    return $this->responseMessage->success('信息添加成功');
  }

  // 信息详情
  public function detail()
  {
    $id = input('get.id', 0, 'intval');
    try {
      $category_data = $this->infoBusiness->getInfoDetail($id);
    } catch (\Exception $e) {
      return $this->responseMessage->error($e->getMessage());
    }

    return $this->responseMessage->success('信息详情数据获取成功', $category_data);
  }

  // 编辑信息
  public function edit()
  {
    // 获取请求数据
    $data = [
      'id' => input('post.id', 0, 'intval'),
      'column_id' => explode(',', input('post.column_id')),
      'title' => input('post.title'),
      'sub_title' => input('post.sub_title'),
      'content' => input('post.content'),
      'title_url' => input('post.title_url'),
      'label_ids' => input('post.label'),
      'publish_time' => strtotime(input('post.publish_time'))
    ];
    try {
      if (isset($_FILES['cover'])) {
        $cover_file                 = new UploadedFile($_FILES['cover']['tmp_name'], $_FILES['cover']['name'], $_FILES['cover']['type'], $_FILES['cover']['error']);
        $url                       = Filesystem::disk('public')->putFile('info_cover', $cover_file);
        $data['cover_url'] = str_replace('\\','/', $url);
      }
      $this->infoBusiness->updateInfo($data);
    } catch (\Exception $e) {
      return $this->responseMessage->error($e->getMessage());
    }
    return $this->responseMessage->success('信息编辑成功');
  }

  public function infoContentPicUpload () {
    $path = request()->domain() . "/storage/";
    $file_url = $this->uploadBusiness->uploadFile(request()->file('file'), $path, 'info_content');
    return $this->responseMessage->success('图片上传成功', $file_url);
  }

  public function deleteCover () {
    $info_id = input('get.info_id', 0);
    try {
      $this->infoBusiness->deleteCover($info_id);
    } catch (\Exception $e) {
      return $this->responseMessage->error('删除封面图失败');
    }
    return $this->responseMessage->success('删除封面图成功');
  }
}
