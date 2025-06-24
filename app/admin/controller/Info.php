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
      'create_time' => input('get.create_time', []),
      'page_size' => input('get.size', config('page.page_size'))
    ];

 
    $list = $this->infoBusiness->getInfoListWithPage($params);
    
    return $this->responseMessage->success('信息列表数据获取成功', $list);
  }

  // 信息列表
  public function getListByCid()
  {
    $params = [
      'column_id' => input('get.column_id', '')
    ];

    $title_list = $this->infoBusiness->getListByCid($params);

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
      'publish_time' => strtotime(input('post.publish_time'))
    ];

    if (isset($_FILES['cover']) && !empty($_FILES['cover'])) {
      $cover_file                 = new UploadedFile($_FILES['cover']['tmp_name'], $_FILES['cover']['name'], $_FILES['cover']['type'], $_FILES['cover']['error']);
      $url                       = Filesystem::disk('public')->putFile('info_cover', $cover_file);
      $data['cover_url'] = str_replace('\\','/', $url);
    }

    // 图片集合处理
    if (isset($_FILES['images']) && !empty($_FILES['images'])) {
      $picFiles = $_FILES['images'];

      if (!empty($picFiles)) {
        $picsData = [];
        foreach ($picFiles['name'] as $key => $name) {
          $image_file                 = new UploadedFile($picFiles['tmp_name'][$key], $picFiles['name'][$key], $picFiles['type'][$key], $picFiles['error'][$key]);
          $url                       = Filesystem::disk('public')->putFile('info_images', $image_file);
          // 组装存储在数据库中的图片集合信息
          $picsData[] = [
            'url' => str_replace('\\','/', $url)
          ];
        }
        $data['images'] = json_encode($picsData);
      }
    }

    $this->infoBusiness->insertInfo($data);

    return $this->responseMessage->success('信息添加成功');
  }

  // 信息详情
  public function detail()
  {
    $id = input('get.id', 0, 'intval');

    $detail = $this->infoBusiness->getInfoDetail($id);

    $detail['images'] = $detail['images'] ? json_decode($detail['images']) : [];

    return $this->responseMessage->success('信息详情数据获取成功', $detail);
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

    if (isset($_FILES['cover'])) {
      $cover_file                 = new UploadedFile($_FILES['cover']['tmp_name'], $_FILES['cover']['name'], $_FILES['cover']['type'], $_FILES['cover']['error']);
      $url                       = Filesystem::disk('public')->putFile('info_cover', $cover_file);
      $data['cover_url'] = str_replace('\\','/', $url);
    }

    // 图片集合处理
    if (isset($_FILES['images']) && !empty($_FILES['images'])) {
      $picFiles = $_FILES['images'];

      if (!empty($picFiles)) {
        $picsData = [];
        foreach ($picFiles['name'] as $key => $name) {
          $image_file                 = new UploadedFile($picFiles['tmp_name'][$key], $picFiles['name'][$key], $picFiles['type'][$key], $picFiles['error'][$key]);
          $url                       = Filesystem::disk('public')->putFile('info_images', $image_file);
          // 组装存储在数据库中的图片集合信息
          $picsData[] = [
            'url' => str_replace('\\','/', $url)
          ];
        }
        $detail = $this->infoBusiness->infoModel
        ->where(['id' => $data['id']])
        ->field("images")
        ->find()
        ->toArray();

        $detail['images'] = $detail['images'] ? json_decode($detail['images']) : [];
        $data['images'] = json_encode(array_merge($picsData, $detail['images']));
      }
    }

    $res = $this->infoBusiness->updateInfo($data);

    return $this->responseMessage->success('信息编辑成功', $res);
  }

  // 删除信息
  public function delete() {
    $data = [
      'id' => input('post.id', 0, 'intval'),
    ];

    $this->infoBusiness->deleteInfo($data);
    
    return $this->responseMessage->success('删除成功');
  }

  public function deleteCache() {
    $data = [
      'id' => input('post.id'),
      'column_id' => input('post.column_id', 0, 'intval')
    ];

    $this->infoBusiness->deleteCache($data);

    return $this->responseMessage->success('清除缓存成功');
  }

  public function infoContentPicUpload () {
    $path = request()->domain() . "/storage/";
    $file_url = $this->uploadBusiness->uploadFile(request()->file('file'), $path, 'info_content');
    return $this->responseMessage->success('图片上传成功', $file_url);
  }

  public function deleteCover () {
    $info_id = input('get.info_id', 0);

    $this->infoBusiness->deleteCover($info_id);

    return $this->responseMessage->success('删除封面图成功');
  }

  // 删除资源（栏目数据模型为图片、下载时的图片、文件等资源删除）
  public function deleteResource () {
    $data = [
      'info_id' => input('post.info_id', 0, 'intval'),
      'column_id' => input('post.column_id', 0, 'intval'),
      'resource_type' => input('post.resource_type', 'pic'),
      'resource_url' => input('post.resource_url', ''),
    ];

    $this->infoBusiness->deleteResource($data);
    
    return $this->responseMessage->success('删除成功');
  }
}
