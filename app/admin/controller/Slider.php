<?php


namespace app\admin\controller;
use think\App;
use app\admin\business\Slider AS SliderBusiness;
use think\facade\Config;
use think\facade\Filesystem;
use think\file\UploadedFile;

class Slider extends AdminBase
{
  private $sliderBusiness = null;

  public function __construct(App $app)
  {
    parent::__construct($app);
    $this->sliderBusiness = new SliderBusiness;
  }

  public function list () {
    $params = [
      'name' => input('get.name'),
      'page' => input('get.page', 1),
      'page_size' => input('get.page_size', Config::get('page.page_size'))
    ];

    $slider = $this->sliderBusiness->getSliderList($params);
    

    return $this->responseMessage->success('轮播图列表数据获取成功', $slider);
  }

  public function add() {
    // 获取请求数据
    $params = [
      'name' => input('post.name'),
      'page_url' => input('post.page_url'),
      'sort' => input('post.sort'),
      'status' => input('post.status'),
      'remarks' => input('post.remarks')
    ];

    if (isset($_FILES['slider']) && !empty($_FILES['slider'])) {
      $slider_file                 = new UploadedFile($_FILES['slider']['tmp_name'], $_FILES['slider']['name'], $_FILES['slider']['type'], $_FILES['slider']['error']);
      $url                       = Filesystem::disk('public')->putFile('sliders', $slider_file);
      $params['url'] = str_replace('\\','/', $url);;
    }
    $this->sliderBusiness->insertSlider($params);
    

    return $this->responseMessage->success('轮播图添加成功');
  }

  public function delete() {
    // 获取请求数据
    $params = [
      'id' => input('get.id', 0, 'intval')
    ];

    $this->sliderBusiness->delete($params);

    return $this->responseMessage->success('轮播图删除成功');
  }

  public function detail() {
    $params = [
      'id' => input('get.id', 0, 'intval')
    ];

    $slider_data = $this->sliderBusiness->detail($params);

    return $this->responseMessage->success('轮播图详情获取成功', $slider_data);
  }

  public function edit() {
    // 获取请求数据
    $params = [
      'id' => input('post.id', 0, 'intval'),
      'name' => input('post.name'),
      'page_url' => input('post.page_url'),
      'sort' => input('post.sort'),
      'status' => input('post.status'),
      'remarks' => input('post.remarks')
    ];


    if (isset($_FILES['slider']) && !empty($_FILES['slider'])) {
      $slider_file                 = new UploadedFile($_FILES['slider']['tmp_name'], $_FILES['slider']['name'], $_FILES['slider']['type'], $_FILES['slider']['error']);
      $url                       = Filesystem::disk('public')->putFile('sliders', $slider_file);
      $params['url'] = str_replace('\\','/', $url);;
    }
    $this->sliderBusiness->updateSlider($params);


    return $this->responseMessage->success('轮播图编辑成功');
  }

}